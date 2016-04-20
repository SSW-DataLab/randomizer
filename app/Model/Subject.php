<?php

App::uses('AppModel', 'Model');

/**
 * Subject Model
 *
 */
class Subject extends AppModel {

    /**
     * Primary key field
     *
     * @var string
     */
    public $primaryKey = 'subject_id';

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'external_id';

    /**
     * Each Subject belongs to one creator
     * @var array 
     */
    public $belongsTo = array('User' => array(
            'className' => 'User',
            'dependent' => false,
            'foreignKey' => 'creator_id'
        ),
        'Group',
        'Site',
        'Disabler'=> array(
            'className' => 'User',
            'dependent' => false,
            'foreignKey' => 'invalidated_by'
        )
    );

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'subject_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
//'allowEmpty' => false,
//'required' => false,
//'last' => false, // Stop validation after this rule
//'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'external_id' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
            //'message' => 'Your custom message here',
//'allowEmpty' => false,
//'required' => false,
//'last' => false, // Stop validation after this rule
//'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'creator_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
//'allowEmpty' => false,
//'required' => false,
//'last' => false, // Stop validation after this rule
//'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'group_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
//'allowEmpty' => false,
//'required' => false,
//'last' => false, // Stop validation after this rule
//'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'site_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
//'allowEmpty' => false,
//'required' => false,
//'last' => false, // Stop validation after this rule
//'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );

}
