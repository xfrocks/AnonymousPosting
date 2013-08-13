<?php

class AnonymousPosting_Option
{
	public static function validatePoster(&$value, XenForo_DataWriter $dw, $fieldName)
	{
		if (!empty($value))
		{
			$model = XenForo_Model::create('XenForo_Model_User');
			$user = $model->getUserByName($value);
			if (empty($user))
			{
				throw new XenForo_Exception(new XenForo_Phrase('anonymous_poster_invalid_poster_username'), true);
			}
		}

		return true;
	}

	public static function renderForums(XenForo_View $view, $fieldPrefix, array $preparedOption, $canEdit)
	{
		// simply get all nodes (instead of getting only nodes in list view)
		// since 1.4
		$nodes = XenForo_Model::create('XenForo_Model_Node')->getAllNodes();
		$preparedOption['formatParams'] = array();
		foreach ($nodes as $node)
		{
			if ($node['node_type_id'] != 'Forum')
				continue;
			$preparedOption['formatParams'][$node['node_id']] = str_repeat('&nbsp;&nbsp;', $node['depth']) . ' ' . $node['title'];
		}
		$preparedOption['option_value'] = array_shift($preparedOption['option_value']);

		$preparedOption['formatParams'] = XenForo_ViewAdmin_Helper_Option::prepareMultiChoiceOptions($fieldPrefix, $preparedOption);
		return XenForo_ViewAdmin_Helper_Option::renderOptionTemplateInternal('option_list_option_checkbox', $view, $fieldPrefix, $preparedOption, $canEdit);
	}

	public static function validateForums(&$value, XenForo_DataWriter $dw, $fieldName)
	{
		$correctValues = array('list' => array());

		foreach ($value as $nodeId => $selected)
		{
			if ($selected)
			{
				$correctValues['list'][$nodeId] = true;
			}
		}

		$value = $correctValues;

		return true;
	}

	public static function prepareResponse(XenForo_Controller $controller, XenForo_Input $input, $response)
	{
		if ($response instanceof XenForo_ControllerResponse_View)
		{
			$forum = $response->params['forum'];
			$thread = (isset($response->params['thread']) ? $response->params['thread'] : array());

			if ($controller->getModelFromCache('XenForo_Model_Forum')->AnonymousPosting_canPostAnonymouslyInForum($forum))
			{
				$hash = self::getAnonymousPostingHash($forum['node_id'], (isset($thread['thread_id']) ? $thread['thread_id'] : 0));

				$response->params['anonymous_posting'] = array(
					'checked' => $hash == $input->filterSingle('anonymous_posting', XenForo_Input::STRING),
					'hash' => $hash,
				);
			}
		}

		return $response;
	}

	public static function getAnonymousPostingHash($forumId, $threadId)
	{
		$visitor = XenForo_Visitor::getInstance();
		$salt = XenForo_Application::getConfig()->get('globalSalt');

		return md5($visitor->get('user_id') . $visitor->get('username') . $forumId . $threadId . $salt);
	}

	public static function processAnonymousPosting(XenForo_DataWriter_DiscussionMessage_Post $dw, $attachmentHash = '')
	{
		$posterUsername = XenForo_Application::get('options')->anonymous_posting_poster;
		$poster = array(
			'user_id' => 0,
			'username' => new XenForo_Phrase('anonymous_posting_poster'), /* there is no
			 * harm, it's cached */
		);
		if (!empty($posterUsername))
		{
			$looking = XenForo_Application::get('db')->fetchRow("
				SELECT user_id, username
				FROM xf_user
				WHERE username = ?
			", array($posterUsername));
			if (!empty($looking))
			{
				$poster = $looking;
			}
		}
		$visitor = XenForo_Visitor::getInstance();

		/* reset the user_id for the post */
		foreach ($poster as $key => $value)
		{
			$dw->set($key, $value);
			$dw->set('anonymous_posting_real_' . $key, $visitor->get($key));
		}

		/* update the attachments to clear user footprint */
		if (!empty($attachmentHash))
		{
			XenForo_Application::get('db')->query("
				UPDATE `xf_attachment_data` AS data
				INNER JOIN `xf_attachment` AS attachment ON (attachment.data_id = data.data_id)
				SET data.user_id = ?
				WHERE attachment.temp_hash = ?
			", array(
				$poster['user_id'],
				$attachmentHash
			));
		}

		return $poster;
	}

}
