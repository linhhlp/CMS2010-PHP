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
 * 	
 */

class frontpage  {
	private $results;
	private $config;
	private $list = array();
	function __construct(){
		$db = new DBmanager();
		if(!isset($_CONFIG)) global $_CONFIG;
		$dbsetting = $_CONFIG->getDB();
		if($dbsetting['AUTOCHECK'] == TRUE) $this->createDB();
		$select_pront="SELECT * FROM  ".PREFIX."frontpage WHERE publish=1";
		$chon_pront = $db->execute($select_pront) or die("ko the truy cap table frontpage ");
		$sodong_pront=$db->count() ;
		if($sodong_pront!=0)
		{
			$results = array();
			$chon_pront = $db->results();
			for($i=0;$i<$sodong_pront;$i++){
				$result = $db->FetchResult($chon_pront);
				if($result['tl'] == 'nhac') $result['link'] = player::makePlayer($result['link'],'audio','nhac_'.$result['id']);
				elseif($result['tl'] == 'video') $result['link'] = player::makePlayer($result['link'],'video','video_'.$result['id']);
				$results[] = $result;
			}
		}
		$this->results = $results;
	}
	public function createDB() {
		$db = new DBmanager();
		$query	=	"CREATE TABLE IF NOT EXISTS ".PREFIX."frontpage (
					`id` int(3) NOT NULL AUTO_INCREMENT,
					  `title` varchar(256) NOT NULL,
					  `tl` varchar(256) NOT NULL,
					  `link` varchar(2000) NOT NULL,
					  `descr` varchar(2000) DEFAULT NULL,
					  `date` datetime NOT NULL,
					  `publish` varchar(20) NOT NULL DEFAULT '',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM;
				";
		$db->execute($query);
	    
	}
	
	function getResults() {
		return $this->results;
	}
	public function getList($id = '') {
		$db = new DBmanager();
		$query = "SELECT id,title,link,descr ,date,tl,publish FROM  " . PREFIX . "frontpage";
		if($id != '') {
			$id 	= $db->escapeString($id);
			$query .= ' WHERE id="'.$id.'"';
		}
		$db->execute( $query );
		$num = $db->count();
		if($num > 0) {
			for($i=0; $i<$num;$i++) {
				$row = $db->getRow();
				if($id != '') $this->list = $row; 
				else array_push($this->list,$row);
			}
		}
		else $this->list = FALSE;
		return $this->list;
	}
	public function createFront ($title  ,$tl , $descr , $link ,  $date , $publish ) {
		$db = new DBmanager();
		$title  = $db->escapeString ( $_POST ['title'] );
		$tl 	= $db->escapeString ($_POST ['tl']);
		$descr  = $db->escapeString ( $_POST ['descr'] );
		$link 	= $db->escapeString ( $_POST ['link'] );
		$date 	= $db->escapeString ($_POST ['date']);
		$publish= $db->escapeString (  $_POST ['publish'] );
		$INSERT = "INSERT INTO  " . PREFIX . "frontpage (title , link, descr , publish , tl , date  ) VALUES ('$title'  , '$link'  , '$descr' , '$publish' , '$tl' , '$date' )";
		$suc = $db->execute ( $INSERT );
		if($suc == FALSE) return FALSE;
		else return TRUE;
	}
	public function updateFront ($id,$title,$tl,$descr,$link,$date,$publish) {
		$db = new DBmanager();
		$title 	 = $db->escapeString ( $title );
		$tl 	 = $db->escapeString ( $tl );
		$descr   = $db->escapeString ( $descr );
		$link 	 = $db->escapeString ( $link );
		$date 	 = $db->escapeString ($date);
		$publish = $db->escapeString ( $publish );
		$fr_page = "UPDATE   " . PREFIX . "frontpage SET title='$title',descr='$descr' ,publish='$publish',tl='$tl',date='$date',link='$link' WHERE id='$id'";
		$qer = $db->execute ( $fr_page );
		if($qer == FALSE ) return FALSE;
		else return TRUE;
	}
	public function deleteFront ($id) {
		$db = new DBmanager();
		$id = $db->escapeString($id);
		if($id =='') return FALSE;
		else {
			$delete = "DELETE FROM  " . PREFIX . "frontpage WHERE id='$id'";
			$chon 	= $db->execute ( $delete );
			if($chon != FALSE) return TRUE;
			else return FALSE;
		}
		
	}
	
}



?>