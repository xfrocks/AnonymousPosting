<?php
class AnonymousPosting_Listener {
	private static $_templates = array(
		'thread_view' => array(
			'anonymous_posting_checkbox_lite',
			'anonymous_posting_reveal',
		),
		'thread_reply' => 'anonymous_posting_checkbox',
		/* added 31-01-2011 */
		'thread_create' => 'anonymous_posting_checkbox',
	);
	
	public static function load_class($class, array &$extend) {
		static $extends = array(
			'XenForo_ControllerPublic_Thread',
			'XenForo_DataWriter_DiscussionMessage_Post',
			/* added 31-01-2011 */
			'XenForo_ControllerPublic_Forum',
			'XenForo_DataWriter_Discussion_Thread',
			/* added 10-04-2011 */
			'XenForo_Model_Post',
			/* added 24-08-2012 */
			'XenForo_Model_Forum',
		);
		
		if (in_array($class, $extends)) {
			$extend[] = str_replace('XenForo_', 'AnonymousPosting_', $class);
		}
	}
	
	public static function create_template(&$templateName, array &$params, XenForo_Template_Abstract $template) {
		if (isset(self::$_templates[$templateName])) {
			if (!is_array(self::$_templates[$templateName])) {
				$templates = array(self::$_templates[$templateName]);
			} else {
				$templates = self::$_templates[$templateName];
			}
			
			foreach ($templates as $preloadTemplate) {
				$template->preloadTemplate($preloadTemplate);
			}
		}
	}
	
	public static function template_hook($hookName, &$contents, array $hookParams, XenForo_Template_Abstract $template) {
		if ($hookName == 'message_content') {
			if (XenForo_Visitor::getInstance()->hasPermission('general', 'anonymous_posting_reveal')) {
				$ourTemplate = $template->create('anonymous_posting_reveal', $hookParams);
				$contents = $ourTemplate->render() . $contents;
			}
		}
	}
	
	public static function template_post_render($templateName, &$output, array &$containerData, XenForo_Template_Abstract $template) {
		if (isset(self::$_templates[$templateName])) {
			$ourTemplateNames = self::$_templates[$templateName];
			if (is_array($ourTemplateNames)) {
				$ourTemplateName = array_shift($ourTemplateNames);
			} else {
				$ourTemplateName = $ourTemplateNames;
			}
			
			$html = $template->create($ourTemplateName, $template->getParams())->render();
			$comment = '<!-- search-and-replace -->';
			$pattern = '/' . preg_quote($comment, '/') . '((.|\r|\.)+)' . preg_quote($comment, '/') . '/';
			if (preg_match($pattern, $html, $matches, PREG_OFFSET_CAPTURE)) {
				$search = $matches[1][0];
				$replace = substr_replace($html, $search, $matches[0][1], strlen($matches[0][0]));
				$pos = strpos($output, $search);
				if ($pos !== false) {
					$output = substr_replace($output, $replace, $pos, strlen($search));
				}
			}
		}
	}
}