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

class userbonus {
	private $num_message;
	public function __construct() {
		// Before working, we have to check our DB exist or not.
		if(!isset($_CONFIG)) global $_CONFIG;
		$dbsetting = $_CONFIG->getDB();
		if($dbsetting['AUTOCHECK'] == TRUE) $this->createDB();
	}
	public function createDB() {
		$db		= new DBmanager();
		$query	=	"CREATE TABLE IF NOT EXISTS `".PREFIX."userbonus` (
				  `id` int(10) NOT NULL AUTO_INCREMENT,
				  `title` varchar(256) NOT NULL,
				  `content` varchar(1000) NOT NULL,
				  `created_by` varchar(256) NOT NULL,
				  `belong` varchar(256) NOT NULL,
				  `status` varchar(256) NOT NULL,
				  `public` varchar(256) NOT NULL,
				  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM;
				";
		$db->execute($query);
	}
	public function getBonus($id ='',$belong = '' , $start=0 , $limit=3, $public= 1,$created_by='') {
		$db		= new DBmanager();
		$id		= $db->escapeString($id);
		$belong = $db->escapeString($belong);
		$start	= $db->escapeString($start);
		$limit	= $db->escapeString($limit);
		$select	= ' FROM '.PREFIX.'userbonus ';
		$query	 	= $select; 
		if($public != '')  		$query .= ($select==$query?' WHERE ':' AND ').'  `public` IN ('.$public.') ';
		if($id != '') 			$query .= ($select==$query?' WHERE ':' AND ').'  `id`="'.$id.'" ';
		//if($created != '') 	$query .= ($select==$query?' WHERE ':' AND ').'  `created`="'.$created.'" ';
		if($created_by != '') 	$query .= ($select==$query?' WHERE ':' AND ').'  `created_by`="'.$created_by.'" ';
		//if($modified != '') 	$query .= ($select==$query?' WHERE ':' AND ').'  `modified`="'.$modified.'" ';
		if($belong != '') 		$query .= ($select==$query?' WHERE ':' AND ').'  `belong`="'.$belong.'" ';
		$query_count	= 'SELECT count(id) '.$query;
		$db->execute($query_count);
		$temp = $db->getRow();
		$this->num_message = $temp['count(id)'];
		$query	 = 'SELECT * '.$query;
		$query	.= "  ORDER BY `created`  DESC LIMIT $start,$limit";
		$result  = $db->execute($query);
		$num	 = $db->count();
		if($num > 0) {
			$results = array();
			for($i=0; $i<$num; $i++) {
				$row = $db->FetchResult($result);
				array_push($results,$row);
				if($_SESSION['info']['id'] == $row['belong']) {
					// Update Status if open a messeage.
					$query_update	 = "UPDATE  ".PREFIX."userbonus SET `status`='1' WHERE `id`='$id'";
					$db->execute($query_update);
				}
			}
			return $results;
		}
		else {
			return FALSE;
		}
	}
	public function getNumMessage() {
		return $this->num_message;
	}
	public function isNew($user_id = '' ) {
		$db		= new DBmanager();
		$id		= $db->escapeString($id);
		$user_id= $db->escapeString($user_id);
		$query	= 'SELECT count(id) FROM '.PREFIX.'userbonus ';
		if($user_id != '') $query .= ' WHERE `belong`="'.$user_id.'" AND `status`="0"';
		else $query .= ' WHERE `status`="0"';
		$result  = $db->execute($query);
		$row	 = $db->getRow();
		echo $db->error();
		return $row['count(id)'];
	}
	public function updateBonus($id,$title,$content,$created,$modified,$belong,$status = 0,$public = 1) {
		$db 		= new DBmanager();
		$id			= $db->escapeString($id);
		$title		= $db->escapeString($title);
		$content	= $db->escapeString($content);
		$created 	= $db->escapeString($created);
		$modified 	= $db->escapeString($modified);
		$belong 	= $db->escapeString($belong);
		$status 	= $db->escapeString($status);
		$public 	= $db->escapeString($public);
		if($modified == '') {
			$homnay=date(dmYHis);
				$modified=substr($homnay,4,4)."-".substr($homnay,2,2)."-".substr($homnay,0,2)." ".(substr($homnay,8,2)-1)."-".substr($homnay,10,2)."-".substr($homnay,12,2);
		}
		$query 		= 'UPDATE '.PREFIX."userbonus  
						SET `title` 	 = '$title',
							`content`	 = '$content',
							`created`	 = '$created',
							`modified`   = '$modified',
							`belong` 	 = '$belong',
							`status` 	 = '$status',
							`public` 	 = '$public'
						WHERE `id` ='$id'
									";
		$suc = $db->execute($query);
		if($suc == FALSE) return FALSE;
		else return TRUE;
	}
	public function createBounus($title,$content,$created,$belong,$public=1) {
		$db = new DBmanager();
		$title		= $db->escapeString($title);
		$content	= $db->escapeString($content);
		$created 	= $db->escapeString($created);
		$belong 	= $db->escapeString($belong);
		$public 	= $db->escapeString($public);
		$created_by = $_SESSION['info']['id'];
		//echo $fulltext;
		if($created == '') $create = 'CURRENT_TIMESTAMP';
		$query 		= 'INSERT INTO '.PREFIX."userbonus ( `title`, `content`,  `created`, `created_by`, `public`,`belong`,`status`) VALUES ('$title',  '$content', '$created', '$created_by',   '$public', '$belong','0')";
		$suc = $db->execute($query);
		if($suc == FALSE) return FALSE;
		else {
			return TRUE;
		}
	}
	public function deleteBonus($id) {
		$db = new DBmanager();
		$id = $db->escapeString($id);
		if($_SESSION['type'] != '3' && $_SESSION['type'] != '4' ) {
			$info = $this->getBonus($id);
			if($info[0]['belong'] != $_SESSION['info']['id']) return FALSE;
		}
		$query = 'DELETE FROM '.PREFIX.'userbonus WHERE `id`="'.$id.'" LIMIT 1';
		$suc = $db->execute($query);
		if($suc == FALSE ) {
			return FALSE;
		}
		else return TRUE;
		
	}
}







?>