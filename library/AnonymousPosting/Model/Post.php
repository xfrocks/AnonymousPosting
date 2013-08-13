<?php
class AnonymousPosting_Model_Post extends XFCP_AnonymousPosting_Model_Post {
	public function getAnonymousPosts(array $fetchOptions = array()) {
		$joinOptions = $this->preparePostJoinOptions($fetchOptions);
		$limitOptions = $this->prepareLimitFetchOptions($fetchOptions);

		return $this->fetchAllKeyed($this->limitQueryResults('
			SELECT
				post.*
				' . $joinOptions['selectFields'] . '
			FROM xf_post AS post' . $joinOptions['joinTables'] . '
			WHERE post.anonymous_posting_real_user_id > 0
		', $limitOptions['limit'], $limitOptions['offset']), 'post_id');
	}
	
	public function countAnonymousPosts(array $fetchOptions = array()) {
		$joinOptions = $this->preparePostJoinOptions($fetchOptions);

		return $this->_getDb()->fetchOne('
			SELECT COUNT(*)
			FROM xf_post AS post' . $joinOptions['joinTables'] . '
			WHERE post.anonymous_posting_real_user_id > 0
		');
	}
	
	public function deleteAnonymousLog() {
		$this->_getDb()->query("
			UPDATE `xf_post`
			SET anonymous_posting_real_user_id = 0
				, anonymous_posting_real_username = ''
			WHERE anonymous_posting_real_user_id > 0
		");
	}
}