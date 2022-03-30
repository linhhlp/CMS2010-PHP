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
 * class manage newest infomation.
 * If you like, you can add more features
 * 
 */

class newset {
	private $newest;
	private $number;
	public function getNewest() {
		$db = new DBmanager();
		// find all category
		$query = 'SElECT * FROM '.PREFIX.'category WHERE publish=1';
		$db->execute($query);
		$num = $db->count();
		$this->number = $num;
		if($num >0) {
			$results = $db->results();
			$key = array();
			$newest  = array();
			for($i=0 ; $i<$num; $i++) {
				$row = mysql_fetch_assoc($results);
				array_push($key,$row['name']);
				// For each category, find content info 
				$query2 = "SELECT * FROM ".PREFIX."blog 
							WHERE `id_pos`='".$row['id']."' 
							ORDER BY `created`  DESC 
							LIMIT 	0 , 1";
				$db->execute($query2);
				$num2 = $db->count();
				if($num2 == 1) {
						$results2 = $db->results();
						$row2 	  = mysql_fetch_assoc($results2);
						
				}
				else $row2 = array(); 		// make rows be empty. It means that There has no new content.
				array_push($newest,$row2);
			}
			$this->newest = array_combine($key,$newest);
		}
		else $this->newest = FALSE;
		return $this->newest;
	}
	public function getNumber(){
		return $this->number;
	}
	
	
}


?>