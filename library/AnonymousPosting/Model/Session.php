<?php

class AnonymousPosting_Model_Session extends XFCP_AnonymousPosting_Model_Session
{
	public function addSessionActivityDetailsToList(array $activities)
	{
		$this->getModelFromCache('XenForo_Model_Forum')->AnonymousPosting_checkSeeUserPermission(true);
		
		$response = parent::addSessionActivityDetailsToList($activities);
		
		$this->getModelFromCache('XenForo_Model_Forum')->AnonymousPosting_checkSeeUserPermission(false);
		
		return $response;
	}
}
