<?php

class AnonymousPosting_DevHelper_Config extends DevHelper_Config_Base
{
    protected $_dataClasses = array();
    protected $_dataPatches = array(
        'xf_post' => array(
            'anonymous_posting_real_user_id' => array('name' => 'anonymous_posting_real_user_id', 'type' => 'uint', 'required' => true, 'default' => 0),
            'anonymous_posting_real_username' => array('name' => 'anonymous_posting_real_username', 'type' => 'string', 'length' => 50, 'required' => true, 'default' => 0),
        ),
    );
    protected $_exportPath = '/Users/sondh/XenForo/AnonymousPosting';
    protected $_exportIncludes = array();
    protected $_exportExcludes = array();
    protected $_exportAddOns = array();
    protected $_exportStyles = array();
    protected $_options = array();

    /**
     * Return false to trigger the upgrade!
     **/
    protected function _upgrade()
    {
        return true; // remove this line to trigger update

        /*
        $this->addDataClass(
            'name_here',
            array( // fields
                'field_here' => array(
                    'type' => 'type_here',
                    // 'length' => 'length_here',
                    // 'required' => true,
                    // 'allowedValues' => array('value_1', 'value_2'),
                    // 'default' => 0,
                    // 'autoIncrement' => true,
                ),
                // other fields go here
            ),
            array('primary_key_1', 'primary_key_2'), // or 'primary_key', both are okie
            array( // indeces
                array(
                    'fields' => array('field_1', 'field_2'),
                    'type' => 'NORMAL', // UNIQUE or FULLTEXT
                ),
            ),
        );
        */
    }
}