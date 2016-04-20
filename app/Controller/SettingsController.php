<?php

App::uses('AppController', 'Controller');

/**
 * Sites Controller
 *
 */
class SettingsController extends AppController {
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
        $Settings = $this->Setting->find('all');
        $this->set(array(
            'settings' => $Settings,
            '_serialize' => array('settings')
        ));
    }
    
    public function save() {
        if ($this->request->is('post')) {
            $saveObject = array(
                'key' => $this->request->data['key'],
                'value' => $this->request->data['value']
            );

            if ($this->Setting->save($saveObject)) {
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
            $this->set(array(
                'message' => $message,
                '_serialize' => array('message')
            ));
        }
    }
}
