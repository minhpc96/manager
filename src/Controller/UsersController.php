<?php
namespace App\Controller;

use App\Controller\AppController;

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
        
        $this->Auth->allow('add');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Managers', 'ParentUsers']
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
            'contain' => ['Users', 'ParentUsers', 'ChildUsers']
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            echo $user->name;
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $users = $this->Users->Managers->Departments->find('list', ['limit' => 200]);
        $parentUsers = $this->Users->ParentUsers->find('list', ['limit' => 200]);
        $this->set(compact('user', 'users', 'parentUsers'));
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
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $users = $this->Users->Managers->Departments->find('list', ['limit' => 200]);
        $parentUsers = $this->Users->ParentUsers->find('list', ['limit' => 200]);
        $this->set(compact('user', 'users', 'parentUsers'));
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
        if ($this->Users->delete($user)) {
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
    public function login(){
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            echo $user->name;
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
