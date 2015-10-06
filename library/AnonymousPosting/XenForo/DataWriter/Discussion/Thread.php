<?php

class AnonymousPosting_XenForo_DataWriter_Discussion_Thread extends XFCP_AnonymousPosting_XenForo_DataWriter_Discussion_Thread
{
    protected function _discussionPreSave()
    {
        parent::_discussionPreSave();

        if (!empty($GLOBALS['AnonymousPosting_XenForo_ControllerPublic_Forum::actionAddThread'])) {
            /** @var AnonymousPosting_XenForo_ControllerPublic_Forum $controller */
            $controller = $GLOBALS['AnonymousPosting_XenForo_ControllerPublic_Forum::actionAddThread'];
            $controller->AnonymousPosting_actionAddThread($this, $this->getFirstMessageDw());
        }
    }

}
