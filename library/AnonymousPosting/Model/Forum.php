<?php

class AnonymousPosting_Model_Forum extends XFCP_AnonymousPosting_Model_Forum
{
	protected static $_AnonymousPosting_checkSeeUserPermission = 0;
	
	public function AnonymousPosting_canPostAnonymouslyInForum(array $forum, &$errorPhraseKey = '', array $nodePermissions = null, array $viewingUser = null)
	{
		$this->standardizeViewingUserReferenceForNode($forum['node_id'], $viewingUser, $nodePermissions);

		return XenForo_Permission::hasContentPermission($nodePermissions, 'anonymous_posting_post');
	}
	
	public function AnonymousPosting_checkSeeUserPermission($enabled)
	{
		if ($enabled)
		{
			self::$_AnonymousPosting_checkSeeUserPermission++;
		}
		else
		{
			self::$_AnonymousPosting_checkSeeUserPermission--;
		}
	}
	
	public function canViewForum(array $forum, &$errorPhraseKey = '', array $nodePermissions = null, array $viewingUser = null)
	{
		$canView = parent::canViewForum($forum, $errorPhraseKey, $nodePermissions, $nodePermissions, $viewingUser);
		
		if ($canView AND self::$_AnonymousPosting_checkSeeUserPermission > 0)
		{
			$this->standardizeViewingUserReferenceForNode($forum['node_id'], $viewingUser, $nodePermissions);

			return XenForo_Permission::hasContentPermission($nodePermissions, 'anonymous_posting_seeUser');
		}
		
		return $canView;
	}

}
