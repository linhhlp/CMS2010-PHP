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
 * This is content class, it will help us manage content, contact with database
 * 
 * 
 */

class content {
	private $content;
	private $type;
	private $query;
	
	public function __construct($type = '') {
		if($type == '') {
			// Load default module in front-page
			if(!isset($mod_params)) global $mod_params;
			$config = explode ( ',', $mod_params );
			$type = $config[0];
		}
		ob_start();
		$mod_path   = MOD.'/'.$type;
		$path_index = $mod_path.'/index.php';
		if(is_file($path_index)) {
			include_once($path_index);
		}
		else echo "Can not load module: ".$type ." path: ".$mod_path;
		$this->content = ob_get_contents();
        ob_end_clean();
	}
	public function  getContent () {
		/**
		 * This will help us get Data from DB
		 */
		return $this->content;
		
	}
}

?>