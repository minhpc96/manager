<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Mailer\Email;
use Cake\Routing\Router;
use Cake\Utility\Security;

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

        $this->Auth->allow(['active', 'logout']);
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
        if (!empty($this->request->data)) {
            $reset = $this->request->data();
            $this->resetPassword($reset);
        }

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
        $relatedusers = $this->Users->Managers->find('all', ['contain' => ['Users']]);
        $this->set(compact('user', 'relatedusers'));
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
        $content .= "\n Username: " . $user->username;
        if (!empty($this->request->data['password'])) {
            $content .= "\n Password: " . $this->request->data['password'];
        } else {
            $content .= "\n Password: abcd1234";
        }
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
            $user = $this->Users->patchEntity($user, $this->request->data);
            //add avatar
            $filename = $this->request->data('username');
            $uploadpath = 'img/';
            $uploadFile = $uploadpath . $filename;
            if (move_uploaded_file($this->request->data['avatar']['tmp_name'], $uploadFile)) {
                $user->avatar = $filename;
            }
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
     * Reset password method
     * 
     * @param array $reset
     * @return Update password of records
     */
    public function resetPassword($reset = null)
    {
        if (!empty($reset['resetAll'])) {
            //Send email reset password
            $users = $this->paginate($this->Users);
            echo $users;
            foreach ($users as $user) {
                echo $user->email;
                if ($user->role == 'user') {
                    //create token and send email to active
                    $key = Security::hash(uniqid());
                    //create timeout check 1 day
                    $timeout = time() + DAY;
                    $url = 'http://192.168.56.56:8080' . Router::url(['controller' => 'Users', 'action' => 'active']) . '/' . $key;
                    $user->token = $key;
                    $user->timeout = $timeout;
                    $user->password = 'abcd1234';
                    if ($this->Users->save($user)) {
                        $this->sendEmail($user, $url);
                    }
                }
            }
            return $this->redirect(['action' => 'index']);
        } else {
            foreach ($reset as $resetId) {
                $user = $this->Users->get($resetId);
                //create token and send email to active
                $key = Security::hash(uniqid());
                //create timeout check 1 day
                $timeout = time() + DAY;
                $url = 'http://192.168.56.56:8080' . Router::url(['controller' => 'Users', 'action' => 'active']) . '/' . $key;
                $user->token = $key;
                $user->timeout = $timeout;
                $user->password = 'abcd1234';
                if ($this->Users->save($user)) {
                    $this->sendEmail($user, $url);
                }
            }
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * Change password method
     * 
     * @return Redirect on successful
     */
    public function changepassword()
    {
        $user = $this->Users->get($this->Auth->user('user_id'));
        //Check old password and method request
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, [
                'oldpassword' => $this->request->data['oldpassword'],
                'password' => $this->request->data['newpassword'],
                'newpassword' => $this->request->data['newpassword']
                ], ['validate' => 'password']
            );
            if ($this->Users->save($user)) {
                $this->Flash->success(__('New password has been saved.'));
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error(__('The password could not be saved. Please, try again.'));
            }
        }
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
                if ($user['role'] == 'admin') {
                    $this->Auth->setUser($user);
                    return $this->redirect(['controller' => 'Users', 'action' => 'index']);
                } else {
                    $this->Auth->setUser($user);
                    return $this->redirect(['controller' => 'Users', 'action' => 'view', $user['user_id']]);
                }
            }
            $this->Flash->error(__('Incorrect! Try again'));
        }
    }

    /**
     * Logout method
     * 
     * @return Redirect on successful
     */
    public function logout()
    {
        $this->Flash->success('You has been logged out.');
        return $this->redirect($this->Auth->logout());
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
        //All user can view info
        if ($this->request->action == 'view') {
            return true;
        }
        // user login can edit info and change password
        if (in_array($this->request->action, ['edit', 'changepassword'])) {
            if ($user['user_id'] == $this->request->param('pass.0')) {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }
}
