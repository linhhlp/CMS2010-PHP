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

class logindb {
	static $info;
	static $expire = 300;	// time (second) to expire 
	static $logged = FALSE;
	static $id_record;
	public function __construct() {
		global $_CONFIG;
		$setting = $_CONFIG->getSite();
		self::$expire 	 = $setting['LOGIN_TIME'];
	}
	public function checkLogin($arr = array()) {	// $user='',$pass='',$ip='',$session_id=''
		// check log-in COOKIE
		$cookie = loadCookie();//pre($cookie);
		if( empty($arr) && $cookie['remember_me'] == 1 && $cookie['code'] !=  '' ) {
			//if( $cookie['user'] == 'hlp4ever' ) {pre($cookie);die;}
			$arr = array();
			$arr['remember_me'] = '1';
			$arr['code'] =  $cookie['code'];
			$arr['user'] =  $cookie['user'];
			return $this->checkLogin($arr);
		}
		$user = '';
		if( isset( $arr['user'] ) ) 		$user = $arr['user'];
		if( isset( $arr['pass'] ) ) 		$pass = $arr['pass'];
		if( isset( $arr['ip'] ) ) 			$ip = $arr['ip'];
		if( isset( $arr['session_id'] ) ) 	$session_id = $arr['session_id'];
		if( isset( $arr['remember_me'] ) ) 	$remember_me = $arr['remember_me'];
		if( isset( $arr['code'] ) ) 		$code = $arr['code'];
		//if(self::$logged	== TRUE)	return TRUE;
		//note that: pass is in md5 format.
		$time_now 	= date('Y').'-'.date('m').'-'. date('d').' '.date('H').':'.date('i').':'.date('s');
		$time 		= date("Y-m-d H:i:s", mktime(date("H"),date("i") ,date("s") -  $this->getExpire() , date("m")  , date("d"), date("Y")) ) ;
		if(!isset($ip) || $ip == '' ) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		if( !isset($session_id) || $session_id == '' ) {
			$session_id = session_id();
		}
		
		//print_r($check_exist);
		//$check_exist['status'];
		//if($arr['test'] == 1 ) {pre($arr);echo $user . $check_exist['status'];die;}
		if( $user != '' && $code != '' ) {
			$check_exist = $this->checkExist( '','', '', $code,$user);
			if($check_exist != false ) {
				self::$id_record = $check_exist['id'];
				$this->updateRecord($check_exist['id'],'','',$time_now);
				$users = user::getUsers($user);
				$row = $users[0];
				self::$info 		= array('record_id'=>$check_exist['id'],'type'=>$row ['type'],'id'=>$row ['id'],'user'=>$row ['user'],'name_user'=>$row ['name_user']);;
				self::$logged		= TRUE;
				return TRUE;
			}
		}
		else {
			$check_exist = $this->checkExist( $ip,$session_id, $time);
			if($check_exist == false ) {
				$check_exist = array('id'=>$this->createNew(),'status'=>0,'user_type'=>'0','user_id'=> '0','session_id'=>$session_id);
			}
			else $this->updateRecord($check_exist['id'],'','',$time_now);	// update time
			self::$id_record = $check_exist['id'];
			if ($user != '') {
				if($check_exist['status'] < 1) $check_exist['status'] = 0;
				if ($check_exist['status'] < 4) {
					$db = new DBmanager();
					$name_admin = $db->escapeString( $user );
					$pass_admin = $db->escapeString( $pass );
					$dnhap = "select * from  `" . PREFIX . "user` where `user`='$name_admin' and `pass`='$pass_admin'  AND `enable`=1";
					$db->execute( $dnhap );
					//echo $dnhap;die;
					$sd = $db->count();
					if ($sd == 0) {
						self::$info = FAlSE;
						$check_exist['status']++;
						$this->updateRecord($check_exist['id'], $ip,$session_id,'','','','0','',$check_exist['status']);
						return FALSE;
					} 
					elseif ($sd == 1) // Dang nhap thanh cong
					{
						// luu lai gia tri dang nhap
						$row 				= $db->getRow();
						self::$info 		= array('record_id'=>$check_exist['id'],'type'=>$row ['type'],'id'=>$row ['id'],'user'=>$row ['user'],'name_user'=>$row ['name_user']);
						self::$logged		= TRUE;
						if( $remember_me == 1 ) {
							$code = md5($time_now.rand(999,99999).rand(999,99999));	// create random code for auto-login
						}
						else $code = '';
						saveCookie( array('captcha'=> md5( '307'.'Kh@ch') ));	// xac thuc thanh cong
						$this->updateRecord($check_exist['id'], $ip,$session_id,'',$row ['user'],$row ['id'],$row ['type'],$code,'on');
						// save cookie
						$cookies = array('code'=>$code,'user'=>$user,'remember_me'=>'1');
						saveCookie($cookies);
						return TRUE;
					}
					else
					{
						die; // Quit program because fatal error.
					}
				}
				 else {
					return FALSE;
				 }
				
			}	 
			// member of this website (admin,...)
			elseif($check_exist['status'] == 'on'){
				//$this->updateRecord($check_exist['id'],'','',$time_now);
				//self::$info 		= $check_exist;
				$user = $check_exist['user_name'];
				$users = user::getUsers($user);
				if(is_array($users ) && count($users ) == 1 ) {
					$row = $users[0];
					self::$info 		= array('record_id'=>$check_exist['id'],'type'=>$row ['type'],'id'=>$row ['id'],'user'=>$row ['user'],'name_user'=>$row ['name_user']);	// member
				}
				else self::$info 		= array('record_id'=>$check_exist['id'],'type'=>'1','id'=>'0','user'=>$check_exist['user_name']);
				self::$logged		= TRUE;
				return TRUE;
			}
			else {
				// friend log-in
				if($check_exist['user_name'] != '') {
					//$this->updateRecord($check_exist['id'],'','',$time_now);
					self::$info 		= array('record_id'=>$check_exist['id'],'type'=>'1','id'=>'0','user'=>$check_exist['user_name']);
					self::$logged		= true;
					saveCookie( array('captcha'=> md5( '307'.'Kh@ch') ));	// xac thuc thanh cong
					return true;
				}
				else {
					self::$info 		= array('record_id'=>$check_exist['id'],'type'=>'0');
					self::$logged		= false;
					return FALSE;
				}
			}
		}
		
	}
	public function LogRegistry($user_name='',$user_id='',$user_type='',$status='',$ip='',$session_id='') {
		//if()
		$updateRecord = $this->updateRecord(self::$id_record,$ip,$session_id,'',$user_name,$user_id,$user_type,'',$status);
		if($updateRecord != false) {
			self::$info 	= array('record_id'=>self::$id_record,'user_type'=>$user_type,'user_id'=>$user_id,'user_name'=>$user_name);
			return true;
		}
		else return false;
	}
	private function createNew($user_name='',$user_id='',$user_type='',$ip='',$session_id='',$data='',$status='') {
		if($session_id == '' ) {
			$session_id = session_id();
		}
		if($ip == '' ) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		$db = new DBmanager();
		$user_name	 = $db->escapeString( $user_name );
		$user_id	 = $db->escapeString( $user_id );
		$user_type	 = $db->escapeString( $user_type );
		$data	 	 = $db->escapeString( $data );
		$status		 = $db->escapeString( $status );
		$session_id	 = $db->escapeString( $session_id );
		$insert = "INSERT INTO `" . PREFIX . "session` (`user_name`,`user_id`,`user_type`,`ip`,`session_id`,`data`,`status`) VALUES 
					('$user_name' ,'$user_id' ,'$user_type' ,'$ip','$session_id','$data','$status' )";
		if($db->execute( $insert ) == false) return false;
		else return $db->insertId();
		//$db->execute( $insert ); echo $insert;echo $db->error();
	}
	private function updateRecord($id,$ip='',$session_id='',$time='',$user_name='',$user_id='',$user_type='',$data='',$status='') {
		$db = new DBmanager();
		if($time == '') $time = date('Y').'-'.date('m').'-'. date('d').' '.date('H').':'.date('i').':'.date('s');
		$user_name	 = $db->escapeString( $user_name );
		$user_id	 = $db->escapeString( $user_id );
		$user_type	 = $db->escapeString( $user_type );
		$data		 = $db->escapeString( $data );
		$status		 = $db->escapeString( $status );
		$user_type	 = $db->escapeString( $user_type );
		$session_id	 = $db->escapeString( $session_id );
		$update		 = "UPDATE `" . PREFIX . "session` SET "; 
		$update		.= " `time`='$time' ";
		$update		.= ($user_name=='')?'':" ,`user_name`='$user_name'";
		$update		.= (($user_id=='')?'':" ,`user_id`='$user_id'");
		$update		.= (($user_type=='')?'':" ,`user_type`='$user_type'");
		$update		.= (($data=='')?'':" ,`data`='$data'");
		$update		.= (($status=='')?'':" ,`status`='$status'");
		$update		.= (($ip=='')?'':" ,`ip`='$ip'");
		$update		.= (($session_id=='')?'':" ,`session_id`='$session_id'");
		$update		.=	" WHERE `id`='$id'  ";
		//echo $update;
		return $db->execute( $update );
	}
	
	private function checkExist($ip,$session_id,$time,$code = '',$user='') {
		$db = new DBmanager();
		$ip			 = $db->escapeString( $ip );
		$time		 = $db->escapeString( $time );
		$session_id	 = $db->escapeString( $session_id );
		$code	 	 = $db->escapeString( $code );
		$user	 	 = $db->escapeString( $user );
		$select		 = "SELECT * FROM `" . PREFIX . "session` WHERE ";
		if( $code != '' ) {
			$select .= " `data` = '".$code."' AND `user_name`='".$user."'";
		}
		else $select .= "  `ip`='$ip' AND  `time` > '$time' AND `session_id`='$session_id' ";
		$select .= "  ORDER BY `time` DESC LIMIT 1";
		if($db->execute( $select ) == false) {
			//return false;
		}
        //echo $db->error();
		return $db->getRow();
	}
	public function logout() {
		self::$logged = false;
		self::$info   = false;
		//session_destroy();
		// delete cookie
		$arr = array( 'user'=>'','code'=>'','remember_me'=>'' );
		saveCookie($arr);
		$time 		= date("Y-m-d H:i:s", mktime(date("H"),date("i") ,date("s") - 1 -   $this->getExpire() , date("m")  , date("d"), date("Y")) ) ;
		if(self::$id_record > 0) return $this->updateRecord(self::$id_record,'','',$time,'','','','_','off');
		//updateRecord($id,$ip='',$session_id='',$time='',$user_name='',$user_id='',$user_type='',$data='',$status='')
		else return FALSE;
	}
	public function getLogged() {
		return self::$logged;
	}
	public function getInfo() {
		return self::$info;
	}
	public function getExpire() {
		return self::$expire;
	}
    public function checkUserExist($user) {
        return user::checkUserExist($user);
    }
	
}

?>