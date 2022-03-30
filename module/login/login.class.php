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
 * This is class
 */

class login {
	private $info;
	private $logged = FALSE;
	public function checkLogin($user='',$pass='') {
		//note that: pass is md5 form.
		if($user == '' && isset($_SESSION['user'])) {
			$user = $_SESSION['user'];
			$pass = $_SESSION['pass'];
		}
		if ($user != '') {
			if ($_SESSION ['loi'] < 4) {
				$db = new DBmanager();
				$name_admin = $db->escapeString( $user );
				$pass_admin = $db->escapeString( $pass );
				$dnhap = "select * from  " . PREFIX . "user where user='$name_admin' and pass='$pass_admin'";
				$db->execute( $dnhap );
				$sd = $db->count();
				if ($sd == 0) {
					$this->info = FAlSE;
					if(isset($_POST['user'])&&isset($_POST['pass'])) {
						if (! isset ( $_SESSION ['loi'] )) $_SESSION ['loi'] = "1";
						else $_SESSION ['loi'] ++;
					}
					return FALSE;
				} 
	
				elseif ($sd == 1) // Dang nhap thanh cong
				{
					// luu lai gia tri dang nhap
					$row 				= $db->getRow();
					$this->info 		= $row;
					$this->logged		= TRUE;
					$_SESSION ['user']  = $row ['user'];
					$_SESSION ['pass']  = $row ['pass'];
					$_SESSION ['type']  = $row ['type'];
					$_SESSION ['loi'] 	= 0;
					$_SESSION ['info']	= $row;
					return TRUE;
				}
				else
				{
					exit; // Quit program because fatal error.
				}
			}
			 else return FALSE;
		}
		else return FALSE;
		
	}
	public function logout() {
		if(!isset($_SESSION['ss'])) {	 
			session_start();
			$_SESSION['ss']=1;
		}
		$loi_dn=	$_SESSION['loi'];
		unset($_SESSION['user']);
		unset($_SESSION['pass']);
		unset($_SESSION['type']);
		unset($_SESSION['info']);
		$_SESSION = '';
		$this->info = '';
		session_destroy();
		$_SESSION['loi']=$loi_dn;
		if(isset($_SESSION['user'])) {
			$this->logout();
			// If there still exist $_SESSION['user'], so it re-loop.
			//Carefully, It is good protect but maybe it will makes ENDLESS LOOP if it can not unset.
		}
	}
	public function getLogged() {
		return $this->logged;
	}
    public function checkUserExist($user) {
        return user::checkUserExist($user);
    }
	
}

?>