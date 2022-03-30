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
 * This class proccess content.
 */

class contentmanager {
	private $num_contents;
	public function __construct() {
		// Before working, we have to check our DB exist or not.
		if(!isset($_CONFIG)) global $_CONFIG;
		$dbsetting = $_CONFIG->getDB();
		if($dbsetting['AUTOCHECK'] == TRUE) $this->createDB();
	}
	public function createDB() {
		$db		= new DBmanager();
		$query	=	"CREATE TABLE IF NOT EXISTS ".PREFIX."blog (
					`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`title` VARCHAR( 256 ) NOT NULL ,
					`alias_title` VARCHAR( 256 ) NOT NULL ,
					`introtext` MEDIUMTEXT NOT NULL ,
					`fulltext` MEDIUMTEXT NOT NULL ,
					`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
					`created_by` VARCHAR( 256 ) NOT NULL ,
					`modified` TIMESTAMP NOT NULL ,
					`modified_by` VARCHAR( 256 ) NOT NULL ,
					`hit` VARCHAR( 256 ) NOT NULL ,
					`category` VARCHAR( 256 ) NOT NULL ,
					`publish` VARCHAR( 256 ) NOT NULL ,
					`frontpage` VARCHAR( 256 ) NOT NULL
					) ENGINE = MYISAM ;
				";
		$db->execute($query);
	}
	public function getContent($id = '',$limit_from = 0,$limit_to= 1,$category ='',$publish = '',$created='',$created_by='', 	$modified =''	,$modified_by =''	,$hit 	= '',$frontpage = '') {
		$db		 	= new DBmanager();
		$id	 	 	= $db->escapeString($id);
		$publish 	= $db->escapeString($publish);
		$frontpage	= $db->escapeString($frontpage);
		$created 	= $db->escapeString($created);
		$created_by	= $db->escapeString($created_by);
		$modified	= $db->escapeString($modified);
		$modified_by= $db->escapeString($modified_by);
		$hit		= $db->escapeString($hit);
		$category	= $db->escapeString($category);
		$limit_from	= $db->escapeString($limit_from);
		$limit_to	= $db->escapeString($limit_to);
		$select	 	= 'FROM '.PREFIX.'blog ';
		$query	 	= $select; 	
		if($id != '')      	 	$query .= ($select==$query?' WHERE ':' AND ')." `id`='$id' ";
		if($publish == '')	{
			// Kiem tra quyen cua ng goi (ng doc)
			if(!isset($login) )global $login;
			$user_info = $login->getInfo();
			if($user_info['user_type'] == "4"||$user_info['user_type'] == "3") {
				$publish = '';
			}
			elseif($user_info['user_type'] == "2") {
				$publish = "'3','2','1'";
			}
			elseif($user_info['user_type'] == "1") {
				$publish = "'2','1'";
			}
			else {
				$publish = '1';
			}
		}
		if($publish != '')  	$query .= ($select==$query?' WHERE ':' AND ').'  `publish` IN ('.$publish.') ';
		if($frontpage != '') 	$query .= ($select==$query?' WHERE ':' AND ').'  `frontpage`="'.$frontpage.'" ';
		if($created != '') 		$query .= ($select==$query?' WHERE ':' AND ').'  `created`="'.$created.'" ';
		if($created_by != '') 	$query .= ($select==$query?' WHERE ':' AND ').'  `created_by`="'.$created_by.'" ';
		if($modified != '') 	$query .= ($select==$query?' WHERE ':' AND ').'  `modified`="'.$modified.'" ';
		if($modified_by != '') 	$query .= ($select==$query?' WHERE ':' AND ').'  `modified_by`="'.$modified_by.'" ';
		if($hit != '') 			$query .= ($select==$query?' WHERE ':' AND ').'  `hit`="'.$hit.'" ';
		if($category != '') 	$query .= ($select==$query?' WHERE ':' AND ').'  `category`="'.$category.'" ';
		// Static number of acticle
		$query_count	= 'SELECT count(id) '.$query;
		$db->execute($query_count);
		$temp = $db->getRow();
		$this->num_contents = $temp['count(id)'];
		// End Static number of acticle
		$query	 = 'SELECT * '.$query."  ORDER BY `created`  DESC LIMIT $limit_from,$limit_to";
		$result = $db->execute($query);
		$num = $db->count();
		if($num > 0) {
			$results = array();
			for($i=0; $i<$num; $i++) {
				$row = $db->FetchResult($result);
				array_push($results,$row);
				if($id != '' ) {
					// Update Hit
					$hit = $row['hit'] + 1;
					$query_update	 = "UPDATE  ".PREFIX."blog SET `hit`='$hit' WHERE `id`='$id'";
					$db->execute($query_update);
				}
			}
			return $results;
		}
		else {
			return FALSE;
		}
	}
	public function getNumContents() {
		return $this->num_contents;
	}
	/** 
	* this function for community : return new content
	*/
	public function getContentUpdate($id=0,$limit=10) {
		$db 		= new DBmanager();
		$limit	= $db->escapeString($limit);
		$id 		= (int) $id;
		$query		= 'SELECT * FROM `'.PREFIX.'blog` ';
		if($id > 0) $query .= 'WHERE `id` > '.$id;
		$query	   .= ' ORDER BY `created` DESC ';
		$query     .= 'LIMIT '.$limit;
		$db->execute($query);
		$num  		= $db->count();
		if($num > 0) {
			$results = array();
			for($i=0; $i< $num; $i++) {
				array_push($results,$db->getRow());
			}
			return $results;	
		}
		else return FALSE;
		
	}
	public function createContent($title,$category,$introtext = '',$fulltext,$publish = 1,$created = '',$hit = '1',$frontpage ='1') {
		$db = new DBmanager();
		$title		= $db->escapeString($title);
		$category	= $db->escapeString($category);
		$introtext 	= $db->escapeString($introtext);
		//$fulltext	= $db->escapeString($fulltext);
		$publish 	= $db->escapeString($publish);
		$created 	= $db->escapeString($created);
		$hit 		= $db->escapeString($hit);
		$frontpage 	= $db->escapeString($frontpage);
		$created_by = $_SESSION['info']['id'];
		if($hit == '') 	$hit = '1';
		//echo $fulltext;
		if($created == '') $create = 'CURRENT_TIMESTAMP';
		$query 		= 'INSERT INTO '.PREFIX."blog ( `title`, `introtext`, `fulltext`, `created`, `created_by`,  `hit`, `category`, `publish`, `frontpage`) VALUES ('$title',  '$introtext', '$fulltext', '$created', '$created_by',  '$hit', '$category', '$publish', '$frontpage')";
		$suc = $db->execute($query);
		if($suc == FALSE) return FALSE;
		else {
			$id = $db->insertId();
			$alias_title= $id.'-'.filter_str($title);
			$query 		= 'UPDATE INTO '.PREFIX."blog  SET `alias_title` = '$alias_title',
														WHERE `id` ='$id' ";
			$db->execute($query);
			return TRUE;
		}
	}
	public function updateContent($id,$title,$category,$introtext = '',$fulltext,$publish = 1,$created = '',$modified ='',$hit = '1',$frontpage ='1') {
		$db = new DBmanager();
		$id			= $db->escapeString($id);
		$title		= $db->escapeString($title);
		$category	= $db->escapeString($category);
		$introtext 	= $db->escapeString($introtext);
		//$fulltext	= $db->escapeString($fulltext);
		$fulltext	= str_replace("'",'"',$fulltext);
		$publish 	= $db->escapeString($publish);
		$created 	= $db->escapeString($created);
		$modified 	= $db->escapeString($modified);
		$hit 		= $db->escapeString($hit);
		$frontpage 	= $db->escapeString($frontpage);
		
		$modified_by = $_SESSION['info']['id'];
		$alias_title= $id.'-'.filter_str($title);
		if($modified == '') {
			$homnay=date(dmYHis);
				$modified=substr($homnay,4,4)."-".substr($homnay,2,2)."-".substr($homnay,0,2)." ".(substr($homnay,8,2)-1)."-".substr($homnay,10,2)."-".substr($homnay,12,2);
		}
		$query 		= 'UPDATE '.PREFIX."blog  
												SET `title` = '$title',
													`alias_title` = '$alias_title',
													`introtext` = '$introtext',
													`fulltext` = '$fulltext',
													`created`	= '$created',
													`modified` = '$modified',
													`modified_by` = '$modified_by',
													`hit` = '$hit',
													`category` = '$category',
													`publish` = '$publish',
													`frontpage` = '$frontpage'
												WHERE `id` ='$id'
															";
		$suc = $db->execute($query);
		if($suc == FALSE) return FALSE;
		else return TRUE;
		
	}
	public function deleteContent($id) {
		$db = new DBmanager();
		$id = $db->escapeString($id);
		$query = 'DELETE FROM '.PREFIX.'blog WHERE `id`="'.$id.'"';
		$suc = $db->execute($query);
		if($suc == FALSE ) {
			return FALSE;
		}
		else return TRUE;
	}
}
?>