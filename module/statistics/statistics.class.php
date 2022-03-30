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
 * This is static class
 */
class statistics {
	function getUser($user_id= "1") {
		$db = new DBmanager ();
		$_about_sel = "SELECT * FROM  " . PREFIX . "user WHERE id='" . $user_id . "' ";
	
		$db->execute ( $_about_sel );
		if ($db->count () > 0) {
			$_about_hienthi = $db->getRow ();
		} else {
			$_about_hienthi = array ();
		}
		return $_about_hienthi;
	}
	public function getCategory() {
		$db = new DBmanager ();
		$new_cate = new category ();
		$all_cate = $new_cate->listAll ();
		$result = array();
		foreach ( $all_cate as $key => $value ) {
			$db->execute ( "SELECT count(id) FROM  " . PREFIX . "blog WHERE `category`='" . $value ['id'] . "'" );
			$so_bai_viet = $db->getRow ();
			$result[$value ['title']] = $so_bai_viet['count(id)'];
		}
		/*
		$db->execute ( "SELECT count(id) FROM  " . PREFIX . "comment WHERE `frontpage='1'" );
		if ($db->count () > 0) {
			$front_co = $db->getRow ();
			$front = $front_co ['count(id)'];
		} else
			$front = 0;
		*/
		return $result;
	}
	public function getComment() {
		$db = new DBmanager ();
		$db->execute ( "SELECT count(id) FROM  " . PREFIX . "comment" );
		$result =$db->getRow ();
		$total = $result['count(id)'];
		$db->execute ( "SELECT count(id) FROM  " . PREFIX . "comment WHERE `belong`=''" );
		$result2 =$db->getRow ();
		$frontpage = $result2['count(id)'];
		return $total.'-'.$frontpage;
	}
}