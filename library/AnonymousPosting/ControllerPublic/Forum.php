<?php

class AnonymousPosting_ControllerPublic_Forum extends XFCP_AnonymousPosting_ControllerPublic_Forum
{
	public function actionCreateThread()
	{
		return AnonymousPosting_Option::prepareResponse($this, $this->_input, parent::actionCreateThread());
	}

	public function actionAddThread()
	{
		$GLOBALS['AnonymousPosting_ControllerPublic_Forum'] = $this;
		return parent::actionAddThread();
	}

	public function processAnonymousPosting(XenForo_DataWriter_Discussion_Thread $dw, XenForo_DataWriter_DiscussionMessage_Post $postDw)
	{
		$input = $this->_input->filter(array(
			'attachment_hash' => XenForo_Input::STRING,
			'anonymous_posting' => XenForo_Input::STRING,
		));

		$forumId = $dw->get('node_id');
		$threadId = 0;
		// new thread, no id for now...

		if ($input['anonymous_posting'] == AnonymousPosting_Option::getAnonymousPostingHash($forumId, $threadId))
		{
			$poster = AnonymousPosting_Option::processAnonymousPosting($postDw, $input['attachment_hash']);

			if (!empty($poster))
			{
				$dw->set('user_id', $poster['user_id']);
				$dw->set('username', $poster['username']);
				$dw->set('last_post_user_id', $poster['user_id']);
				$dw->set('last_post_username', $poster['username']);
			}
		}

		// do not unset the global variable, see AnonymousPosting_ControllerPublic_Thread
		// unset($GLOBALS['AnonymousPosting_ControllerPublic_Forum']);
	}

}
