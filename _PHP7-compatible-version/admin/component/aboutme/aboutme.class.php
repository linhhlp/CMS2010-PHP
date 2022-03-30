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

class aboutme {
	public function __construct() {
		// Before working, we have to check our DB exist or not.
		if(!isset($_CONFIG)) global $_CONFIG;
		$dbsetting = $_CONFIG->getDB();
		if($dbsetting['AUTOCHECK'] == TRUE) $this->createDB();
	}
	public function createDB() {
		$db		= new DBmanager();
		$query	=	"CREATE TABLE IF NOT EXISTS `".PREFIX."aboutme` (
				  `id` int(8) NOT NULL auto_increment,
				  `user_id` varchar(8) NOT NULL,
				  `info` varchar(10000) NOT NULL,
				  `picture` varchar(1000) default NULL,
				  `aboutme` varchar(10000) default NULL,
				  `love` varchar(10000) default NULL,
				  `biogra` varchar(10000) default '',
				  `history` varchar(10000) default '',
				  `last_update` timestamp NULL default CURRENT_TIMESTAMP,
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM;
				";
		$db->execute($query);
	}
	public function  getinfo($user_id = 1) {
		$result = array();
		$db		= new DBmanager();
		$user_id=$db->escapeString($user_id);
		$_about_sel = "SELECT * FROM  ".PREFIX."user WHERE id='".$user_id."' ";
		$_about_qu = $db->execute($_about_sel);
		if($_about_qu != FALSE) {
			$_about_hienthi = $db->getRow();
			$result =  $_about_hienthi;
		}
		$_more_sel = "SELECT 	*  FROM  ".PREFIX."aboutme WHERE user_id='".$user_id."' ";
		$_more_qu = $db->execute($_more_sel);
		if($db->count() > 0 ) { 
			$_more_hienthi = $db->getRow();
			$result = array_merge($result,$_more_hienthi);
		}
		return $result;
	}
	public function updateInfo(	$user_id ='' ,	$info ='',	$picture ='',	$aboutme ='',	$love='' ,	$biogra ='',	$history ='' ,	$last_update ='') {
		$db     = new DBmanager;
		if($user_id  == '') $user_id = $_SESSION['info']['id'];
		if($user_id  == '' ) die();
		$user_id	= $db->escapeString($user_id);
		$info		= $db->escapeString($info);
		$picture	= $db->escapeString($picture);
		$aboutme	= $db->escapeString($aboutme);
		$love		= $db->escapeString($love);
		$biogra		= $db->escapeString($biogra);
		$history	= $db->escapeString($history);
		$last_update= $db->escapeString($last_update);
		$_more_sel  = "SELECT 	id   FROM  ".PREFIX."aboutme WHERE user_id='".$user_id."' ";
		$_more_qu   = $db->execute($_more_sel);
		if($db->count() == 0)			//		Ki?m tra trong CSDL dã có tru?ng thông tin nào chua (recorder) N?u chua có thì m?i t?o m?i
		{
			$_about_ab_up	=	"INSERT INTO  ".PREFIX."aboutme 	(user_id, info ,	picture ,	aboutme ,	love ,	biogra ,	history ) VALUES	('".$user_id."',	'$info',		'$picture',		'$aboutme',		'$love',		'$biogra',		'$history'	)";
			$_about_ab_ins_que	=$db->execute($_about_ab_up);
		}
		else
		{
			$_about_ab_ins	=	"UPDATE  ".PREFIX."aboutme SET	info='$info' ,	picture='$picture' ,	aboutme='$aboutme' ,	love='$love' ,	biogra='$biogra' ,	history='$history' ,	`lastupdate`='$last_update'	WhERE user_id='".$user_id."'	";
				$_about_ab_ins_que	=$db->execute($_about_ab_ins);
		}
	}
}



?>