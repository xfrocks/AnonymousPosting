<?php

class AnonymousPosting_DataWriter_Discussion_Thread extends XFCP_AnonymousPosting_DataWriter_Discussion_Thread
{
	protected function _discussionPreSave()
	{
		parent::_discussionPreSave();

		if (!empty($GLOBALS['AnonymousPosting_ControllerPublic_Forum']))
		{
			$GLOBALS['AnonymousPosting_ControllerPublic_Forum']->processAnonymousPosting($this, $this->getFirstMessageDw());
		}
	}

}
