<?php

App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {

    /**
     * Primary key field
     *
     * @var string
     */
    public $primaryKey = 'user_id';

    /**
     * Each user can have many subjects
     * @var string
     */
    public $hasMany = array('Subject' => array(
            'className' => 'Subject',
            'foreignKey' => 'creator_id'
        )
    );
    /**
     * Each user has one role and one modifier (if modified)
     */
    public $belongsTo = array('Role', 'ModifiedBy' => array(
            'className' => 'User',
            'foreignKey' => 'modified_by'
        )
    );

    public function beforeSave($options = array()) {
        if (!empty($this->data[$this->alias]['password'])) {
            $passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                    $this->data[$this->alias]['password']
            );
        }
        return true;
    }

}

?>
