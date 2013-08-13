<?php
class AnonymousPosting_Model_Forum extends XFCP_AnonymousPosting_Model_Forum {
	public function AnonymousPosting_canPostAnonymouslyInForum(array $forum, &$errorPhraseKey = '', array $nodePermissions = null, array $viewingUser = null) {
		$this->standardizeViewingUserReferenceForNode($forum['node_id'], $viewingUser, $nodePermissions);

		return XenForo_Permission::hasContentPermission($nodePermissions, 'anonymous_posting_post');
	}
}