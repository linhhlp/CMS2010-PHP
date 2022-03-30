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



class login {
	private $engine;
	function __construct() {
		global $_CONFIG;
		$setting = $_CONFIG->getSite();
		$class 	 = 'login'.$setting['LOGIN_TYPE'];
		$this->engine = new $class;
	}
	public function checkLogin($arr = array()) {
		//if($arr['test'] == 1 ) {pre($arr);die;}
		return call_user_func_array(array($this->engine, 'checkLogin'),array($arr));
	}
	public function __call($method, $args)
	  {
	    if (empty($this->engine)) return 0;
	    if (!method_exists($this, $method))
	    return call_user_func_array(array($this->engine, $method),$args);
	  }
	  /*private function __get($property)
	  {
	    if (property_exists($this->dbengine,$property))
	    return $this->dbengine->$property;
	  }*/
}

?>