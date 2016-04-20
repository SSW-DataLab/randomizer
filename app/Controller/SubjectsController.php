<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Subjects Controller
 *
 */
class SubjectsController extends AppController {

    public $components = array('RequestHandler', 'Paginator', 'CsvView.CsvView');

    /**
     * Scaffold
     *
     * @var mixed
     */
    public $scaffold;
    public $paginate = array(
        'limit' => 10,
        'order' => array(
            'Subject.created' => 'desc'
        )
    );

    public function isAuthorized($user) {
        if ($user['disabled'] === true || $user['disabled'] === '1') {
            return $this->redirect($this->Auth->logout());
        }

        // All registered users can add subjects
        if ($this->action === 'add') {
            return true;
        } else if (isset($user['role_id']) && $user['role_id'] === '3') {
            return $this->redirect(array('controller' => 'subjects', 'action' => 'add'));
        }
        return parent::isAuthorized($user);
    }

    public function beforeFilter() {
        parent::beforeFilter();
        // Allow users to logout.
        $this->Auth->allow('logout');
    }

    public function export() {
        $results = $this->Subject->find('all');
        $excludePaths = array(
            'User.password', 
            'User.modified', 
            'User.modified_by', 
            'User.role_id', 
            'User.disabled', 
            'User.created', 
            'Disabler.password', 
            'Disabler.modified', 
            'Disabler.modified_by', 
            'Disabler.role_id', 
            'Disabler.disabled', 
            'Disabler.created', 
            'Subject.creator_id', 
            'Subject.group_id', 
            'Subject.modified_by', 
            'Subject.site_id', 
            'Site.disabled'); // Exclude all id fields
        $customHeaders = array(
            'User.user_id' => 'Creator ID',
            'User.fullname' => 'Creator Name',
            'User.email' => 'Creator Email',
            'User.username' => 'Creator Username',
            'User.created' => 'Creator Created',
            'Disabler.disabler_id' => 'Disabled By ID',
            'Disabler.fullname' => 'Disabled By Name',
            'Disabler.email' => 'Disabled By Email',
            'Disabler.username' => 'Disabled By Username',
            'Site.site_ratio' => 'Current Site Ratio'
        );
        $_null = "";
        $this->CsvView->quickExport($results, $excludePaths, $customHeaders);
    }

    public function index() {
        $subjects = $this->Subject->find('all');
        $this->set(array(
            'subjects' => $subjects,
            '_serialize' => array('subjects')
        ));
    }

    public function page() {
        $this->Paginator->settings = $this->paginate;
        // similar to findAll(), but fetches paged results
        $subjects = $this->Paginator->paginate('Subject');
        $paging = $this->request->params['paging'];
        $this->set(array(
            'subjects' => $subjects,
            'paging' => $paging,
            '_serialize' => array('subjects', 'paging')
        ));
    }

    public function view($id) {
        $subject = $this->Subject->findById($id);
        $this->set(array(
            'subject' => $subject,
            '_serialize' => array('subject')
        ));
    }

    /**
     * Return the next random number in the sequence 
     * @param type $offset int last position in random sequence
     * @return type float next random number in sequence between 0 and 1
     */
    private function getNextRandom($seed, $offset) {
        mt_srand($seed); // Random seed.
        $nextRandom = -1;
        for ($i = 0; $i <= $offset; $i++) { // Loop through the sequence until we reach the next new number
            $nextRandom = mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax();
        }
        return $nextRandom;
    }

    /**
     * For validation purposes, print out next random: e.g., /subjects/getRandom/2.json
     * @param type $offset
     */
    public function getRandom($offset) {        
        $this->loadModel('Setting');
        $seed = $this->Setting->findByKey("random_seed");
        $this->set(array(
            'random' => $this->getNextRandom((int)$seed['Setting']['value'], $offset),
            '_serialize' => array('random')
        ));
    }

    public function add() {
        if ($this->request->is('post')) {
            $subjects = $this->Subject->find('all', array(
                'conditions' => array(
                    'LOWER(Subject.external_id)' => $this->request->data['externalID'],
                    'record_invalid' => false
                )
                    )
            );
            if (count($subjects) > 0) {
                $message = array(
                    'text' => 'Identifier already in system and record not invalidated.',
                    'type' => 'error'
                );
            } else {
                $this->loadModel('Site');
                $this->loadModel('Setting');
                $projectMail = $this->Setting->findByKey("project_email");
                $projectMail = $projectMail['Setting']['value'];
                $fromMail = $this->Setting->findByKey("email_from_address");
                $fromMail = $fromMail['Setting']['value'];
                $fromMailName = $this->Setting->findByKey("email_from_name");
                $fromMailName = $fromMailName['Setting']['value'];
                $externalIDLabel = $this->Setting->findByKey("external_id_label");
                $externalIDLabel = $externalIDLabel['Setting']['value'];
                $site = $this->Site->findBySiteId($this->request->data['siteID']);
                $totalSubjects = $this->Subject->find('count');
                
                $seed = $this->Setting->findByKey("random_seed");
                $seed = (int) $seed['Setting']['value'];
                $nextRandom = $this->getNextRandom($seed, $totalSubjects);
                if ($nextRandom < $site['Site']['site_ratio']) {
                    $groupID = 1; // experimental group
                } else {
                    $groupID = 2; // control group
                }
                
                $user = $this->Auth->user();
                $saveObject = array(
                    'external_id' => $this->request->data['externalID'],
                    'creator_id' => $user['user_id'],
                    'group_id' => $groupID,
                    'site_id' => $site['Site']['site_id'],
                    'site_ratio' => $site['Site']['site_ratio']
                );
                if ($this->Subject->save($saveObject)) {
                    $savedSubject = $this->Subject->read(null, $this->Subject->id);
                    $UserEmail = new CakeEmail();
                    $UserEmail->from(array($fromMail => $fromMailName));
                    $UserEmail->to($user['email']);
                    $UserEmail->subject("$externalIDLabel Assignment");
                    $UserEmail->emailFormat('both');
                    $UserEmail->template('email_user');
                    $UserEmail->viewVars(array('subject' => $savedSubject,'settings'=>$this->settings));
                    $UserEmail->send('');

                    $ProjectEmail = new CakeEmail();
                    $ProjectEmail->from(array($fromMail => $fromMailName));
                    $ProjectEmail->to($projectMail);
                    $ProjectEmail->subject("$externalIDLabel Assignment");
                    $ProjectEmail->emailFormat('both');
                    $ProjectEmail->template('email_project');
                    $ProjectEmail->viewVars(array('subject' => $savedSubject,'settings'=>$this->settings));
                    $ProjectEmail->send('');

                    CakeLog::write('debug', 'Random: ' . $nextRandom . ' Sequence: ' . $totalSubjects . ' Assignment: ' . $groupID . ' Ratio: ' . $site['Site']['site_ratio']);

                    $message = array(
                        'subject' => $savedSubject,
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
            $oldSubject = $this->Subject->findBySubjectId($this->request->data['subject_id']);
            $subjects = $this->Subject->find('all', array(
                    'conditions' => array(
                        'LOWER(Subject.external_id)' => $oldSubject['Subject']['external_id'],
                        'record_invalid' => false
                    )
                )
            );
            $modifier = $this->Auth->user();
            if ($modifier['user_id'] != 1) {
                $message = array(
                    'text' => 'Only superuser can modify record validity.',
                    'type' => 'error'
                );
            } else if (count($subjects) > 0 && $this->request->data['record_invalid'] != 'true') {
                $message = array(
                    'text' => 'Can\'t have two valid records with the same user ID - not revalidating this record.',
                    'type' => 'error'
                );
            } else if (!isset($oldSubject) || $oldSubject == null) {
                $message = array(
                    'text' => 'Can\'t find subject to modify.',
                    'type' => 'error'
                );
            } else {
                $saveObject = array(
                    'subject_id' => $this->request->data['subject_id'],
                    'record_invalid' => 0
                );
                if (isset($this->request->data['record_invalid']) && $this->request->data['record_invalid'] == 'true') {
                    $saveObject['record_invalid'] = 1;
                    $modified_by = $this->Auth->user();
                    $saveObject['invalidated_by'] = $modified_by['user_id'];
                }

                if ($this->Subject->save($saveObject)) {
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
        /* DISABLED.
          if ($this->Subject->delete($id)) {
          $message = array(
          'text' => __('Deleted'),
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
         */
    }

}
