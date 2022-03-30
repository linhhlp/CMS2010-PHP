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
 * This class will finds information about website such as:
 * The number of visits, new article...
 * 
 */
/*
class visitor {
	private $visit;
	private $name_id;
	private $ip;
	private $info;
	public function __construct() {
		$this->ip = $_SERVER['REMOTE_ADDR'];
		if(!isset($_CONFIG)) global $_CONFIG;
		$dbsetting = $_CONFIG->getDB();
		if($dbsetting['AUTOCHECK'] == TRUE) $this->createDB();
	}
	public function createDB() {
		$db		= new DBmanager();
		$query	=	"CREATE TABLE IF NOT EXISTS `".PREFIX."visit` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `ip` varchar(256) NOT NULL,
				  `time` varchar(256) NOT NULL,
				  `name` varchar(256) NOT NULL,
				  `info` varchar(256) NOT NULL,
				  `number` varchar(256) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM;
				";
		$db->execute($query);
	}
	
	public function Visit() {
		$db = new DBmanager();
		$this->ip = $db->escapeString($this->ip);
		$select = "SELECT `number`,`info` FROM  ".PREFIX."visit WHERE `id`=1";
		if(!empty($_SESSION['status_ip'])) {
			$select .= " OR `id`='". $db->escapeString($_SESSION['status_ip'])."' ORDER BY `id` ASC";
		}
		$result = $db->execute($select);
		if($db->count() != 0){
			$row1 = $db->FetchResult($result);
			$this->visit = $row1['number'];
		}
		else {
			$set_1st = "INSERT INTO   ".PREFIX."visit   (`number` , `ip`) VALUES (0 , '$this->ip')";
			$db->execute($set_1st);
			$this->visit = 0;
		}
		if(!isset($_SESSION['status_ip']))		// This is new visit
		{
			$this->visit =  $this->visit +1;
			$db->execute("UPDATE   ".PREFIX."visit   SET   `number`='$this->visit' , ip='$this->ip'   WHERE id=1");
			// 2009-04-16 11:13:50
			$time = date(Y).'-'.date(m).'-'.date(d).' '.date(H).':'.date(i).':'.date(s);
			global $_URL;
			$last_url = $_URL->getOldUrl();
			$last_url = $db->escapeString($last_url);
			$Them_IP = "INSERT INTO   ".PREFIX."visit   (`time` , `ip`,`name`,`info`) VALUES ('".$time."' , '$this->ip','".$_SESSION['user']."','$last_url')";
			$db->execute($Them_IP);
			$_SESSION['status_ip']=$db->insertID();
		}
		else {
			$row2 = $db->FetchResult($result);
			global $_URL;
			$last_url = $_URL->getOldUrl();
			$info = $row2['info'].';'.$last_url;
			$info = $db->escapeString($info);
			$db->execute("UPDATE   ".PREFIX."visit   SET   `name`='".$db->escapeString($_SESSION['user'])."' , `info`='$info'  WHERE `id`='".$db->escapeString($_SESSION['status_ip'])."'");
		}
		return $this->visit;
	}
	public function setVisit($ip,$time) {
		
	}
	public function getIp() {
		return $this->ip;
	}
}
*/
?>