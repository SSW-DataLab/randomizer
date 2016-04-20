<?php

App::uses('AppController', 'Controller');

/**
 * Sites Controller
 *
 */
class GroupsController extends AppController {
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
        $Groups = $this->Group->find('all');
        $this->set(array(
            'groups' => $Groups,
            '_serialize' => array('groups')
        ));
    }
}
