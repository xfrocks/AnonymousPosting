<?php

class AnonymousPosting_ControllerPublic_Thread extends XFCP_AnonymousPosting_ControllerPublic_Thread
{
	public function actionIndex()
	{
		return AnonymousPosting_Option::prepareResponse($this, $this->_input, parent::actionIndex());
	}

	public function actionReply()
	{
		return AnonymousPosting_Option::prepareResponse($this, $this->_input, parent::actionReply());
	}

	public function actionAddReply()
	{
		$GLOBALS['AnonymousPosting_ControllerPublic_Thread'] = $this;
		return parent::actionAddReply();
	}

	public function processAnonymousPosting(XenForo_DataWriter_DiscussionMessage_Post $dw)
	{
		$input = $this->_input->filter(array(
			'attachment_hash' => XenForo_Input::STRING,
			'anonymous_posting' => XenForo_Input::STRING,
		));

		$forum = $dw->getExtraData(XenForo_DataWriter_DiscussionMessage_Post::DATA_FORUM);
		$forumId = (empty($forum) ? 0 : $forum['node_id']);
		$threadId = $dw->get('thread_id');

		if ($input['anonymous_posting'] == AnonymousPosting_Option::getAnonymousPostingHash($forumId, $threadId))
		{
			AnonymousPosting_Option::processAnonymousPosting($dw, $input['attachment_hash']);
		}

		// do not unset the global variable because it makes sense to anonymize all posts
		// coming our way
		// unset($GLOBALS['AnonymousPosting_ControllerPublic_Thread']);
	}

}
