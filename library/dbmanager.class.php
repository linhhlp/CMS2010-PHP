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



class DBmanager {
	private $DB;
	private $dbengine;
	function __construct() {
		global $_CONFIG;
		$setting = $_CONFIG->getDB();
		$this->DB = 'DB_'.$setting['TYPE'];
		$this->dbengine = new $this->DB;
		
		
	}
	public function __call($method, $args)
	  {
	    if (empty($this->dbengine)) return 0;
	    if (!method_exists($this, $method))
	    return call_user_func_array(array($this->dbengine,
	                                           $method),$args);
	  }
	  /*private function __get($property)
	  {
	    if (property_exists($this->dbengine,$property))
	    return $this->dbengine->$property;
	  }*/
}

?>