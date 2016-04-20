<?php

App::uses('AppController', 'Controller');

/**
 * Sites Controller
 *
 */
class RolesController extends AppController {

    public $components = array('RequestHandler');

    /**
     * Scaffold
     *
     * @var mixed
     */
    public $scaffold;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index');
    }

    public function index() {
        $Roles = $this->Role->find('all', array(
            'conditions' => array('Role.role_id > ' => 1)
        ));
        $this->set(array(
            'roles' => $Roles,
            '_serialize' => array('roles')
        ));
    }

}
