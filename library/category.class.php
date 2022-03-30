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
 * This class manage category for our website
 * Get, set, create category, such as:
 * Blog, music or video ... category
 * This is class file.
 */

class category {
	private $query;
	private static $categories = array();
	private static $listall = array();
	private $info = array();
	private $ton_tai_bt;
	private $ton_tai_info;
	public function __construct() {
		if(!isset($_CONFIG)) global $_CONFIG;
		$dbsetting = $_CONFIG->getDB();
		if($dbsetting['AUTOCHECK'] == TRUE) $this->createDB();
		$this->query = "SELECT  `id`,
								`name`,
								`title`,
								`alias`,
								`parent`,
								`descr`,
								`image`,
								`order`,
								`public`
								FROM ".PREFIX."category ";
	}
	public function createDB() {
		$db		= new DBmanager();
		$query	=	"CREATE TABLE IF NOT EXISTS ".PREFIX."category (
					`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`title` VARCHAR( 256 ) NOT NULL ,
					`alias` VARCHAR( 256 ) NOT NULL ,
					`name` VARCHAR( 256 ) NOT NULL ,
					`descr` VARCHAR( 256 ) NOT NULL ,
					`image` VARCHAR( 256 ) NOT NULL ,
					`order` VARCHAR( 256 ) NOT NULL ,
					`parent` VARCHAR( 256 ) NOT NULL ,
					`public` VARCHAR( 256 ) NOT NULL  
					) ENGINE = MYISAM ;
				";
		$db->execute($query);
	}

	function list_cate($parent = "0") {
		/**
		 * List sub-categories.
		 */
		if(empty(self::$categories)) {
			$db = new DBmanager();
			$parent = $db->escapeString($parent);
			$query = $this->query." WHERE `parent`='$parent'";
			$db->execute($query);
			$num = $db->count();
			if($num > 0 ) {
				$results = $db->results();
				$parent_array = array();
				$this->ton_tai_bt = TRUE;
				for($i = 0;$i <$num;$i++) {
					$row = mysql_fetch_assoc($results);
					$res = $this->list_cate($row['id']);
					if($res != FALSE) {
						array_push($row,$res);
					}
					array_push($parent_array ,$row);
				}
				$categories = $parent_array;
			}
			else $categories = FALSE;
		}
		self::$categories = $categories;
		return self::$categories;
		
	}
	public function cate_info ($id) {
		/*
		 * Tìm thông tin về 1 category nào đó
		 */
			$db = new DBmanager();
			$id = $db->escapeString($id);
			$query = $this->query ." WHERE `id`='$id' ";
			$db->execute($query);
			$num = $db->count();
			if($num == 1 ) {
				$this->ton_tai_info = TRUE;
				return $db->getRow();
			}
			else return $this->ton_tai_info = FALSE;
	}

	public function listAll() {
			$db = new DBmanager();
			$liet_ke = array();
			$db->execute($this->query);
			$num = $db->count();
			if($num > 0 ) {
				for($i = 0;$i <$num;$i++) {
					$row = $db->getRow();
					array_push($liet_ke,$row);
				}
			}
		return $liet_ke;
	}
	/**
	* When we have ID, we need Title name of this ID
	* so, function we return an array (ID=>Title)
	*/
	static function IDtoTITLE() {
		$result = array();
		$db = new DBmanager();
		$query = "SELECT  `id`,`title` FROM ".PREFIX."category ";
		$db->execute($query);
		$num = $db->count();
		if($num > 0 ) {
				for($i = 0;$i <$num;$i++) {
					$row = $db->getRow();
					$result[$row['id']] = $row['title'];
				}
		}
		return $result;
	}
	public function CreateCate($title,$name,$public = 1,$image = '',$order = '1',$descr = '',$parent ='0') {
		$db 		= new DBmanager();
		$title		= $db->escapeString($title);
		$name		= $db->escapeString($name);
		$image 		= $db->escapeString($image);
		$order		= $db->escapeString($order);
		$public 	= $db->escapeString($public);
		$descr 		= $db->escapeString($descr);
		$parent 	= $db->escapeString($parent);
		$query 		= 'INSERT INTO '.PREFIX."category ( `title`, `name`, `image`, `order`, `parent`,   `public`,`descr`)
									 VALUES ('$title',  '$name', '$image', '$order', '$parent',   '$public', '$descr')";
		$suc = $db->execute($query);
		//echo $db->error();
		if($suc == FALSE) return FALSE;
		else {
			$id = $db->insertId();
			$alias_title= $id.'-'.filter_str($title);
			$query 		= 'UPDATE  '.PREFIX."category  SET `alias` = '$alias_title'
														WHERE `id` ='$id' ";
			$db->execute($query);
			return TRUE;
		}
	} 
	public function UpdateCate($id,$title,$name,$alias,$public = 1,$image = '',$order = '1',$descr = '',$parent ='0') {
		$db 		= new DBmanager();
		$id			= $db->escapeString($id);
		$title		= $db->escapeString($title);
		$name		= $db->escapeString($name);
		$image 		= $db->escapeString($image);
		$order		= $db->escapeString($order);
		$public 	= $db->escapeString($public);
		$descr 		= $db->escapeString($descr);
		$parent 	= $db->escapeString($parent);
		$alias		= filter_str($alias);
		$query 		= 'UPDATE '.PREFIX."category  
												SET `title` = '$title',
													`alias` = '$alias',
													`name` = '$name',
													`image` = '$image',
													`order`	= '$order',
													`parent` = '$parent',
													`public` = '$public',
													`descr` = '$descr'
												WHERE `id` ='$id'
															";
		$suc = $db->execute($query);
		echo $db->error();
		if($suc == FALSE) return FALSE;
		else return TRUE;
		
	}
	public function DeleteCate($id) {
		$db 	= new DBmanager();
		$id		= $db->escapeString($id);
		$query  = 'DELETE FROM '.PREFIX.'category WHERE `id`="'.$id.'" ';
		$suc = $db->execute($query);
		if($suc == FALSE) return FALSE;
		else return TRUE;
	}

	
}

?>