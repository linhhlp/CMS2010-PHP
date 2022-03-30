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

class menu {
	private $query;
	private static $menus = array();
	private static $listall = array();
	private $info = array();
	private $ton_tai_bt;
	private $ton_tai_info;
	public function __construct() {
		if(!isset($_CONFIG)) global $_CONFIG;
		$dbsetting = $_CONFIG->getDB();
		if($dbsetting['AUTOCHECK'] == TRUE) $this->createDB();
		$this->query = "SELECT  `id`,
								`title`,
								`alias`,
								`link`,
								`parent`,
								`descr`,
								`image`,
								`order`,
								`type`,
								`public`
								FROM ".PREFIX."menu ";
	}
	public function createDB() {
		$db = new DBmanager();
		$query	=	"CREATE TABLE IF NOT EXISTS `".PREFIX."menu` (
				  `id` int(10) NOT NULL AUTO_INCREMENT,
				  `title` varchar(256) NOT NULL,
				  `alias` varchar(256) NOT NULL,
				  `link` varchar(256) NOT NULL,
				  `descr` varchar(256) NOT NULL,
				  `image` varchar(256) NOT NULL,
				  `order` varchar(256) NOT NULL,
				  `parent` varchar(256) NOT NULL,
				  `type` varchar(256) NOT NULL,
				  `public` varchar(256) NOT NULL,
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `alias_title` (`alias`)
				) ENGINE=MyISAM ;
				";
		$db->execute($query);
		
	}

	function list_menu($parent = "0") {
		/**
		 * List sub-menus.
		 */
		if(empty(self::$menus)) {
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
					$row = $db->FetchResult($results);
					$res = $this->list_cate($row['id']);
					if($res != FALSE) {
						array_push($row,$res);
					}
					array_push($parent_array ,$row);
				}
				$menus = $parent_array;
			}
			else $menus = FALSE;
		}
		self::$menus = $menus;
		return self::$menus;
		
	}
	public function menu_info ($id) {
		/*
		 * Tìm thông tin về 1 menu nào đó
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

	public function listAll($type = '') {
		// $type: type of menu such as: top menu, left menu,...
			$db = new DBmanager();
			$type		= $db->escapeString($type);
			$liet_ke = array();
			if($type != '') $query = $this->query ." WHERE `type`='$type'  ORDER BY `order` ASC";
			else $query = $this->query." ORDER BY `type` ASC";
			$db->execute($query);
			$num = $db->count();
			if($num > 0 ) {
				for($i = 0;$i <$num;$i++) {
					$row = $db->getRow();
					array_push($liet_ke,$row);
				}
			}
			else $liet_ke = FALSE;
		return $liet_ke;
	}
	public function CreateMenu($title,$link,$public = 1,$image = '',$order = '1',$descr = '',$parent ='0',$type='') {
		$db 		= new DBmanager();
		$title		= $db->escapeString($title);
		$link		= $db->escapeString($link);
		$type		= $db->escapeString($type);
		$image 		= $db->escapeString($image);
		$order		= $db->escapeString($order);
		$public 	= $db->escapeString($public);
		$descr 		= $db->escapeString($descr);
		$parent 	= $db->escapeString($parent);
		$query 		= 'INSERT INTO '.PREFIX."menu ( `title`,  `image`, `link`,`order`, `parent`,   `public`,`descr`,`type`)
									 VALUES ('$title',   '$image', '$link','$order', '$parent',   '$public', '$descr','$type')";
		$suc = $db->execute($query);
		if($suc == FALSE) return FALSE;
		else {
			$id = $db->insertId();
			$alias_title= $id.'-'.filter_str($title);
			$query 		= 'UPDATE  '.PREFIX."menu  SET `alias` = '$alias_title'
														WHERE `id` ='$id' ";
			$db->execute($query);
			return TRUE;
		}
	} 
	public function UpdateMenu($id,$title,$link,$alias,$public = 1,$image = '',$order = '1',$descr = '',$parent ='0',$type='') {
		$db 		= new DBmanager();
		$id			= $db->escapeString($id);
		$title		= $db->escapeString($title);
		$link		= $db->escapeString($link);
		$type		= $db->escapeString($type);
		$image 		= $db->escapeString($image);
		$order		= $db->escapeString($order);
		$public 	= $db->escapeString($public);
		$descr 		= $db->escapeString($descr);
		$parent 	= $db->escapeString($parent);
		$alias		= $db->escapeString($alias);
		$query 		= 'UPDATE '.PREFIX."menu  
												SET `title` = '$title',
													`alias` = '$alias',
													`link`	= '$link',
													`image` = '$image',
													`order`	= '$order',
													`parent` = '$parent',
													`public` = '$public',
													`type`	= '$type',
													`descr` = '$descr'
												WHERE `id` ='$id'
															";
		$suc = $db->execute($query);
		if($suc == FALSE) return FALSE;
		else return TRUE;
		
	}
	public function DeleteMenu($id) {
		$db 	= new DBmanager();
		$id		= $db->escapeString($id);
		$query  = 'DELETE FROM '.PREFIX.'menu WHERE `id`="'.$id.'" ';
		$suc = $db->execute($query);
		if($suc == FALSE) return FALSE;
		else return TRUE;
	}

	
}

?>