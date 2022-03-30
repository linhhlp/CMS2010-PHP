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
 *  This class will help you except error and log it in file
 *  The log file is default : log.php in the root directory.
 *  
 *  This function has 2 feature:
 *  	1 is catch error with $en = 1 (default)
 *  	2 is log everything	  $en = 0
 */

class myException extends Exception {
	private $log_content;
	function __construct($mess,$en=1,$type='') {
		if($en == 1) {
			parent::__construct($mess);
			$this->log_content  = "\n\t\t Message is: ".$this->getMessage();
			$this->log_content .= "\n\t\t in line: ".$this->getLine();
			$this->log_content .= "\n\t\t of file: ".$this->getFile();
		}
		else {
			$this->log_content = $mess;
		}
		$this->log($type);
	}
	function log ($type = ''){
		if($type == 'file' ||$type == '') {
			/**
			 * You can define link of log file:
			 * @var url
			 */
			$url = 'log.php';
			if( !is_file($url)) { $content = "<?php \n /* \n "; file_put_contents ($url,$content);}
			$content  = "\n\n".date("Y-m-d h:i:s");
			$content .= "\n\t REQUEST ".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."\t FROM ". $_SERVER['REMOTE_ADDR'];
			$content .= "\n\t With CONTENT: ";
			$content .= $this->log_content;
			file_put_contents($url, $content, FILE_APPEND);
			
		}
	}
}


?>