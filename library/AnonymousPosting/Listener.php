<?php

class AnonymousPosting_Listener
{
    const UPDATER_URL = 'https://xfrocks.com/api/index.php?updater';

    protected static $_originalHelperAvatarUrl = null;

    public static function load_class($class, array &$extend)
    {
        static $extends = array(
            'XenForo_ControllerPublic_Forum',
            'XenForo_ControllerPublic_Thread',

            'XenForo_DataWriter_DiscussionMessage_Post',
            'XenForo_DataWriter_Discussion_Thread',

            'XenForo_Model_Forum',
            'XenForo_Model_Session',
        );

        if (in_array($class, $extends)) {
            $extend[] = 'AnonymousPosting_' . $class;
        }
    }

    public static function init_dependencies(XenForo_Dependencies_Abstract $dependencies, array $data)
    {
        if (XenForo_Application::getOptions()->get('anonymous_roboHash') > 0) {
            self::$_originalHelperAvatarUrl = XenForo_Template_Helper_Core::$helperCallbacks['avatar'];
            if (!empty(self::$_originalHelperAvatarUrl)
                && self::$_originalHelperAvatarUrl[0] === 'self'
            ) {
                self::$_originalHelperAvatarUrl[0] = 'XenForo_Template_Helper_Core';
            }
            XenForo_Template_Helper_Core::$helperCallbacks['avatar'] = array(__CLASS__, 'helperRoboHashUrl');
        }

        AnonymousPosting_ShippableHelper_Updater::onInitDependencies($dependencies,
            self::UPDATER_URL, 'anonymous_posting');
    }

    public static function file_health_check(XenForo_ControllerAdmin_Abstract $controller, array &$hashes)
    {
        $hashes += AnonymousPosting_FileSums::getHashes();
    }

    public static function helperRoboHashUrl()
    {
        $args = func_get_args();

        $url = call_user_func_array(array('AnonymousPosting_Engine', 'helperRoboHashUrl'), $args);
        if (!empty($url)) {
            return $url;
        }

        if (!empty(self::$_originalHelperAvatarUrl)) {
            return call_user_func_array(self::$_originalHelperAvatarUrl, $args);
        }

        return '';
    }
}
