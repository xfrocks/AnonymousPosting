<?php

class AnonymousPosting_DataWriter_DiscussionMessage_Post extends XFCP_AnonymousPosting_DataWriter_DiscussionMessage_Post
{
	const OPTION_ANONYMOUS_POSTING_IS_ENABLED = '_AnonymousPosting_isEnabled';

	protected function _getDefaultOptions()
	{
		$options = parent::_getDefaultOptions();
		
		$options[self::OPTION_ANONYMOUS_POSTING_IS_ENABLED] = false;
		
		return $options;
	}
	
	protected function _getFields()
	{
		$fields = parent::_getFields();

		$fields['xf_post']['anonymous_posting_real_user_id'] = array('type' => self::TYPE_UINT);
		$fields['xf_post']['anonymous_posting_real_username'] = array('type' => self::TYPE_STRING);

		return $fields;
	}

	protected function _messagePreSave()
	{
		parent::_messagePreSave();

		if (!empty($GLOBALS['AnonymousPosting_ControllerPublic_Thread']))
		{
			$GLOBALS['AnonymousPosting_ControllerPublic_Thread']->processAnonymousPosting($this);
			
			if ($this->get('anonymous_posting_real_user_id') > 0)
			{
				$this->setOption(self::OPTION_ANONYMOUS_POSTING_IS_ENABLED, true);
			}
		}
	}

}
