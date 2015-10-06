<?php

class AnonymousPosting_Listener
{
    public static function load_class($class, array &$extend)
    {
        static $extends = array(
            'XenForo_ControllerPublic_Forum',
            'XenForo_ControllerPublic_Thread',

            'XenForo_DataWriter_DiscussionMessage_Post',
            'XenForo_DataWriter_Discussion_Thread',

            'XenForo_Model_Post',
            'XenForo_Model_Forum',
            'XenForo_Model_Session',
        );

        if (in_array($class, $extends)) {
            $extend[] = 'AnonymousPosting_' . $class;
        }
    }

    public static function file_health_check(XenForo_ControllerAdmin_Abstract $controller, array &$hashes)
    {
        $hashes += AnonymousPosting_FileSums::getHashes();
    }
}
