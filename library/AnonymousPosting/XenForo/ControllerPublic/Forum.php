<?php

class AnonymousPosting_XenForo_ControllerPublic_Forum extends XFCP_AnonymousPosting_XenForo_ControllerPublic_Forum
{
    public function actionCreateThread()
    {
        return AnonymousPosting_Engine::prepareResponse($this, $this->_input, parent::actionCreateThread());
    }

    public function actionAddThread()
    {
        $GLOBALS['AnonymousPosting_XenForo_ControllerPublic_Forum::actionAddThread'] = $this;
        return parent::actionAddThread();
    }

    public function AnonymousPosting_actionAddThread(XenForo_DataWriter_Discussion_Thread $dw, XenForo_DataWriter_DiscussionMessage $postDw)
    {
        $poster = AnonymousPosting_Engine::processAnonymousPosting($dw->get('node_id'), 0, $this, $postDw);

//        if (!empty($poster)) {
//            $dw->set('user_id', $poster['user_id']);
//            $dw->set('username', $poster['username']);
//            $dw->set('last_post_user_id', $poster['user_id']);
//            $dw->set('last_post_username', $poster['username']);
//        }
    }

}
