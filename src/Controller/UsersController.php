<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Mailer\Email;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Cake\Filesystem\File;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    /**
     * Before filter method
     * 
     * @param Event $event
     * @return null
     */
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(['add', 'active']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Managers']
        ];
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Managers', 'Managers.Departments'],
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Send email method
     * 
     * @param array $user, String $url
     * @return null
     */
    public function sendEmail($user, $url)
    {
        $email = new Email();
        $email
            ->to($user->email)
            ->subject('Verify your account')
            ->message();
        $content = "Your account has been created, Click link to verify: ";
        $content .= $url;
        $content .= "\n Username: " . $this->request->data['username'];
        $content .= "\n Password: " . $this->request->data['password'];
        $email->send($content);
    }

    /**
     * Activation method
     * 
     * @param string $token
     * @return null
     */
    public function active($token = null)
    {
        if (!empty($token)) {
            $user = $this->Users->find()->where(['token' => $token, 'timeout >' => time()])->first();
            if ($user) {
//set timeout is null and reset token
                $user->timeout = null;
                $new_token = sha1($user->username . rand(1, 100));
                $user->token = $new_token;
                $this->Users->save($user);
            } else {
                $this->redirect('/');
            }
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
//add data into Users and Managers Table
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            //add avatar
            $filename = $this->request->data('username');
            $uploadpath = 'img/';
            $uploadFile = $uploadpath . $filename;
            if (move_uploaded_file($this->request->data['avatar']['tmp_name'], $uploadFile)) {
                $user = $this->Users->newEntity();
                $user = $this->Users->patchEntity($user, $this->request->data);
                $user->avatar = $filename;
//create token and send email to active
                $key = Security::hash(uniqid());
//create timeout check 1 day
                $timeout = time() + DAY;
                $url = 'http://192.168.56.56:8080' . Router::url(['controller' => 'Users', 'action' => 'active']) . '/' . $key;
                $user->token = $key;
                $user->timeout = $timeout;
                if ($this->Users->save($user)) {
                    $department_ids = $this->request->data('department_id');
                    $managers = $this->request->data('manager');
                    //Add relationship users with departments, with each department_id, check this in array data manager
                    //If true, set field isManager is not null
                    foreach ($department_ids as $department_id) {
                        $manager = $this->Users->Managers->newEntity();
                        $manager->department_id = $department_id;
                        $manager->user_id = $user->user_id;
                        //Check is manager for add
                        if (!empty($managers) && in_array($department_id, $managers)) {
                            $manager->isManager = 1;
                        }
                        $this->Users->Managers->save($manager);
                    }
                    $this->sendEmail($user, $url);
                    $this->Flash->success(__('The user has been created.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('The user could not be saved. Please, try again.'));
                }
            }
        }
        $departments = $this->Users->Managers->Departments->find('list', ['limit' => 200]);
        $this->set(compact('user', 'departments'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Managers', 'Managers.Departments']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
//Change in users table
            $user = $this->Users->patchEntity($user, $this->request->data);
            $user->modify = time();
//Change in Manager table
//Delete all record and add again
            $this->Users->Managers->query()->delete()
                ->where(['user_id' => $user->user_id])
                ->execute();
            if ($this->Users->save($user)) {
                $department_ids = $this->request->data('department_id');
                $managers = $this->request->data('manager');
//Add relationship users with departments
                foreach ($department_ids as $department_id) {
                    $manager = $this->Users->Managers->newEntity();
                    $manager->department_id = $department_id;
                    $manager->user_id = $user->user_id;
                    if (in_array($department_id, $managers)) {
                        $manager->isManager = 1;
                    }
                    $this->Users->Managers->save($manager);
                }
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $departments = $this->Users->Managers->Departments->find('list', ['limit' => 200]);
        $this->set(compact('user', 'departments'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->Managers->query()->delete()
                ->where(['user_id' => $user->user_id])
                ->execute() &&
            $this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Login method
     * 
     * @return null set user login in this session
     */
    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
            }
            $this->Flash->error(__('Incorrect! Try again'));
        }
    }

    /**
     * IsAuthorized method
     * 
     * @param $user Users user
     * @return boolean
     */
    public function isAuthorized($user)
    {
// Admin has full access
        if ($user['role'] == 'admin') {
            return true;
        }
// user login can view and change user
        if (in_array($this->request->action, ['view', 'delete', 'change'])) {
            if ($user['user_id'] == $this->request->param('pass.0')) {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }
}
