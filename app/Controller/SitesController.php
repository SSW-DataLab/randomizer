<?php

App::uses('AppController', 'Controller');

/**
 * Sites Controller
 *
 */
class SitesController extends AppController {

    public $components = array('RequestHandler');

    /**
     * Scaffold
     *
     * @var mixed
     */
    public $scaffold;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index','available');
    }
    
    public function index() {
        $Sites = $this->Site->find('all');
        $this->set(array(
            'sites' => $Sites,
            '_serialize' => array('sites')
        ));
    }
    
    public function available() {
        $Sites = $this->Site->find('all',array(
            'conditions' => array('Site.disabled' => false)
        ));
        $this->set(array(
            'sites' => $Sites,
            '_serialize' => array('sites')
        ));
    }

    public function add() {
        if ($this->request->is('post')) {
            $saveObject = array(
                'site_name' => $this->request->data['site_name'],
                'site_ratio' => $this->request->data['site_ratio']
            );

            if ($this->Site->save($saveObject)) {
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

    public function edit($id) {
        if ($this->request->is('post')) {
            $oldSite = $this->Site->findBySiteId($this->request->data['site_id']);
            if (!isset($oldSite) || $oldSite == null) {
                $message = array(
                    'text' => 'Can\'t find site to modify.',
                    'type' => 'error'
                );
            } else {
                $saveObject = array(
                    'site_id' => $this->request->data['site_id'],
                    'site_name' => $this->request->data['site_name'],
                    'site_ratio' => $this->request->data['site_ratio'],
                    'disabled' => 0
                );
                if (isset($this->request->data['disabled']) && $this->request->data['disabled'] == 'true') {
                    $saveObject['disabled'] = 1;
                }

                if ($this->Site->save($saveObject)) {
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
}
