<?php

App::uses('AppController', 'Controller');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

/**
 * Users Controller
 *
 */
class UsersController extends AppController {

    public $components = array('RequestHandler', 'Paginator');

    /**
     * Scaffold
     *
     * @var mixed
     */
    public $scaffold;
    public $paginate = array(
        'limit' => 10,
        'order' => array(
            'User.username' => 'asc'
        )
    );

    public function isAuthorized($user) {
        if ($user['disabled'] === true || $user['disabled'] === '1') {
            return $this->redirect($this->Auth->logout());
        }
        // All registered users can add subjects
        if ($this->action === 'login' || $this->action == 'logout') {
            return true;
        } else if (isset($user['role_id']) && $user['role_id'] === '3') {
            return $this->redirect(array('controller' => 'subjects', 'action' => 'add'));
        }
        return parent::isAuthorized($user);
    }

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('logout');
    }

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $user = $this->Auth->user();
                if (isset($user['role_id']) && $user['role_id'] === '3') {
                    return $this->redirect(array('controller' => 'subjects', 'action' => 'add'));
                }
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Session->setFlash(
                        __('Username or password is incorrect'), 'default', array(), 'auth'
                );
            }
        }
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

    public function index() {
        $users = $this->User->find('all');
        $this->set(array(
            'users' => $users,
            '_serialize' => array('users')
        ));
    }

    public function page() {
        $this->Paginator->settings = $this->paginate;
        // similar to findAll(), but fetches paged results
        $users = $this->Paginator->paginate('User');
        $paging = $this->request->params['paging'];
        $this->set(array(
            'users' => $users,
            'paging' => $paging,
            '_serialize' => array('users', 'paging')
        ));
    }

    public function view($id) {
        $user = $this->User->findById($id);
        $this->set(array(
            'user' => $user,
            '_serialize' => array('user')
        ));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->loadModel('Role');
            $role = $this->Role->findByRoleId($this->request->data['role_id']);
            if (count($this->User->findByUsername($this->request->data['username'])) > 0) {
                $message = array(
                    'text' => 'Username already in system.',
                    'type' => 'error'
                );
            } else if (!isset($role) || $role == null) {
                $message = array(
                    'text' => 'No such role in system.',
                    'type' => 'error'
                );
            } else if (isset($this->request->data['role_id']) && $this->request->data['role_id'] === 1) {
                $message = array(
                    'text' => 'Only one superuser allowed.',
                    'type' => 'error'
                );
            } else {
                $saveObject = array(
                    'username' => $this->request->data['username'],
                    'fullname' => $this->request->data['fullname'],
                    'email' => $this->request->data['email'],
                    'role_id' => $this->request->data['role_id'],
                    'password' => $this->request->data['password']
                );

                if ($this->User->save($saveObject)) {
                    $message = array(
                        'subject' => __('Saved'),
                        'type' => 'success'
                    );
                } else {
                    $message = array(
                        'text' => __('Error'),
                        'type' => 'error'
                    );
                }
            }
            $this->set(array(
                'message' => $message,
                '_serialize' => array('message')
            ));
        }
    }

    public function edit($id) {
        if ($this->request->is('post')) {
            $this->loadModel('Role');
            $oldUser = $this->User->findByUserId($this->request->data['user_id']);
            $role = $this->Role->findByRoleId($this->request->data['role_id']);
            $modifier = $this->Auth->user();
            if ($this->request->data['user_id'] == 1 && $modifier['user_id'] != 1) {
                $message = array(
                    'text' => 'Only superuser can modify superuser.',
                    'type' => 'error'
                );
            } else if (!isset($oldUser) || $oldUser == null) {
                $message = array(
                    'text' => 'Can\'t find user to modify.',
                    'type' => 'error'
                );
            } else if ($oldUser['User']['username'] != $this->request->data['username'] && count($this->User->findByUsername($this->request->data['username'])) > 0) {
                $message = array(
                    'text' => 'Username already in system.',
                    'type' => 'error'
                );
            } else if (!isset($role) || $role == null) {
                $message = array(
                    'text' => 'No such role in system.',
                    'type' => 'error'
                );
            } else if (isset($this->request->data['role_id']) && $this->request->data['role_id'] === 1) {
                $message = array(
                    'text' => 'Only one superuser allowed.',
                    'type' => 'error'
                );
            } else {
                $saveObject = array(
                    'user_id' => $this->request->data['user_id'],
                    'username' => $this->request->data['username'],
                    'fullname' => $this->request->data['fullname'],
                    'email' => $this->request->data['email'],
                    'role_id' => $this->request->data['role_id'],
                    'modified_by' => $modifier['user_id'],
                    'disabled' => 0
                );
                if (isset($this->request->data['password']) && $this->request->data['password'] != '') {
                    $saveObject['password'] = $this->request->data['password'];
                }

                if (isset($this->request->data['disabled']) && $this->request->data['disabled'] == true) {
                    $saveObject['disabled'] = 1;
                }

                if ($this->User->save($saveObject)) {
                    $message = array(
                        'subject' => __('Saved'),
                        'type' => 'success'
                    );
                } else {
                    $message = array(
                        'text' => __('Error'),
                        'type' => 'error'
                    );
                }
            }
            $this->set(array(
                'message' => $message,
                '_serialize' => array('message')
            ));
        }
    }

    public function delete($id) {
        // Do not delete.
        /*
          if ($this->User->delete($id)) {
          $message = 'Deleted';
          } else {
          $message = 'Error';
          }
          $this->set(array(
          'message' => $message,
          '_serialize' => array('message')
          ));
         */
    }

}
