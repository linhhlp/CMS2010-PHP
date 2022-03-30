<?php
/**
 * @author HLP4ever .
 * @version		2010
 * @package		HLP4ever blog.
 * @copyright	Copyright (C) 2010 by HLP.
 * @license		Open Source and free for non-conmecial
 */
// No direct access
defined('START') or die;

/**
 * This is abstract class which find exact way to notify :
 * 		mail(), STM ,...
 */

	abstract class notify {
		private $from;
		private $to;
		private $message;
		private $header;
		private $paras;
		private $subject;
		private $error;
		public function __construct($from,$to,$subject,$message,$header='',$paras='') {
			return FALSE;
		}
		public function filter($string) {
			return $string;
		}
		public function send() {
			return FALSE;
		}
		public function getError() {
			return FALSE;
		}
		
	}

?>