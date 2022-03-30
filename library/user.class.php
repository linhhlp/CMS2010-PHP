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


class user {
	public function __construct() {
		if(!isset($_CONFIG)) global $_CONFIG;
		$dbsetting = $_CONFIG->getDB();
		if($dbsetting['AUTOCHECK'] == TRUE) $this->createDB();
	}
	public function createDB() {
		$db		= new DBmanager();
		$query	=	"CREATE TABLE IF NOT EXISTS ".PREFIX."user (
					`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`user` VARCHAR( 256 ) NOT NULL ,
					`pass` VARCHAR( 256 ) NOT NULL ,
					`name_user` VARCHAR( 256 ) NOT NULL ,
					`birthday` VARCHAR( 256 ) NOT NULL ,
					`home` VARCHAR( 1000 ) NOT NULL ,
					`mobile` VARCHAR( 256 ) NOT NULL ,
					`tel` VARCHAR( 256 ) NOT NULL ,
					`address` VARCHAR( 1000 ) NOT NULL ,
					`type` VARCHAR( 256 ) NOT NULL ,
					`activation` VARCHAR( 256 ) NOT NULL ,
					`enable` VARCHAR( 256 ) NOT NULL ,
					`note` VARCHAR( 1000 ) NOT NULL 
					) ENGINE = MYISAM ;
				";
		$db->execute($query);
	}
	static public function getUserName($id='',$gettypr='user') {
		$db		  = new DBmanager();
		$user_arr = array();
		$user_name_arr = array();
		$id		= $db->escapeString($id);
		$user_que = 'SELECT id,user,name_user FROM '.PREFIX.'user ';
		if($id != '') $user_que .= ' WHERE `id` IN ('.$id.') ';
		$user_res = $db->execute($user_que);
		$user_num = $db->count();
		if($user_num > 0) {
			for($j=0;$j<$user_num; $j++) {
				$user_row = $db->getRow();
				if($gettypr =='user') {
					array_push($user_name_arr,$user_row['name_user']);
					array_push($user_arr,$user_row['user']);
				}
				elseif($gettypr =='id') {
					array_push($user_name_arr,$user_row['name_user']);
					array_push($user_arr,$user_row['id']);
				}
			}
			$user_check = array_combine($user_arr,$user_name_arr);
		}
		else $user_check = FALSE;
		return $user_check;
	}
	static public function getUserByID($id) {
		$db		  = new DBmanager();
		$id		= $db->escapeString($id);
		$user_que = 'SELECT user FROM '.PREFIX.'user WHERE `id` = "'.$id.'" ';
		$user_res = $db->execute($user_que);
		if($db->count() > 0) {
			$user_row = $db->getRow();
			return $user_row['user'];
		}
		else return FALSE;
	}
	
	static public function getIDByUser($user) {
		$db		  = new DBmanager();
		$user	  = $db->escapeString($user);
		$user_que = 'SELECT id FROM '.PREFIX.'user WHERE `user` = "'.$user.'" ';
		$user_res = $db->execute($user_que);
		if($db->count() > 0) {
			$user_row = $db->getRow();
			return $user_row['id'];
		}
		else return FALSE;
	}
	
	public function createUser() {
		
	}
	
	public function updateUser($id='',$user ,	$name_user, $pass ='' ,$birthday ='' ,	$home ='' ,	$mobile ='',	$tel ='' ,	$address='' ,$note ='',$type='',$enable= '',$activation= '') {
		$db     = new DBmanager;
		if(!isset($login) )global $login;
		if(!isset($login) ) $login = new login();
		$user_info = $login->getInfo();
		if($id  == '') $id = $user_info['user_id'];
		if($id  == '' ) die();
		$id		= $db->escapeString($id);
		$user	= $db->escapeString($user);
		$name_user= $db->escapeString($name_user);
		$pass 	= $db->escapeString($pass);
		$birthday= $db->escapeString($birthday);
		$home 	= $db->escapeString($home);
		$mobile = $db->escapeString($mobile);
		$tel 	= $db->escapeString($tel);
		$address= $db->escapeString($address);
		$note 	= $db->escapeString($note);
		$type	= $db->escapeString($type);
		$enable = $db->escapeString($enable);
		$activation=$db->escapeString($activation);
		$query	=	"UPDATE  ".PREFIX."user SET	user='$user' ,		name_user='$name_user' ,	`birthday`='$birthday' ,	home='$home' ,	mobile='$mobile' ,	tel='$tel' ,	`address`='$address' ,`note`='$note' 	";
		if($type != "") 		$query	.= ", `type`='".$type."'";
		if($enable != "") 		$query	.= " , `enable`='".$enable."'";
		if($activation != "")   $query	.= ", `activation`='".$activation."'";
		if($pass	!= "")		$query	.="	,pass='".$pass."'		";
		$query		.="	 WHERE `id`='".$id."'	";
		$db->execute($query);
	}
	
	
	static public function checkUserExist($user) {
        $db     = new DBmanager;
        $name   = $db->escapeString($user);
        $query  = 'SELECT user FROM '.PREFIX.'user WHERE user="'.$name.'"';
        $db->execute ( $query );
        if($db->count () == 1) {
            return TRUE;
        }
        else return FALSE;
    }
}