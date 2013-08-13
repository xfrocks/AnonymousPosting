<?php
class AnonymousPosting_Installer {
	public static function install() {
		$db = XenForo_Application::get('db');

		$postColumns = array(
			'anonymous_posting_real_user_id' => 'INT(10) UNSIGNED DEFAULT 0',
			'anonymous_posting_real_username' => 'VARCHAR(50) DEFAULT \'\'',
		);
		foreach ($postColumns as $column => $type) {
			$existed = $db->fetchOne("SHOW COLUMNS FROM `xf_post` LIKE '$column'");
			if (empty($existed)) {
				$db->query("ALTER TABLE `xf_post` ADD COLUMN `$column` $type");
			}
		}
	}
	
	public static function uninstall() {
		// TODO
	}
}