<?php

/**
 * @author HLP4ever 2010
 * @copyright 2010.
 * 
 * This is class contains functions to communicate with other site
 */

defined('START') or die;


class community {
	public function checkLogin($name='',$pass='') {
	    if($pass != '') $pass  = md5($pass);
	    $login  = new login();
	    session_destroy();session_start();
		return $login->checkLogin($name,$pass);
	}
	public function getUserInfo() {
		$login  = new login();
		$login->checkLogin();
		return $login->getInfo();
	}
	public function formLogin($user=''){
		$str = '<form method=post action="" >Name:<input name=name type=text value="'.$user.'" /><br />Pass:<input name=pass type=password /><br />
		<input type=submit value="Check Login" />';
		return $str;
	}
	public function loggedRegistry($name='',$user_id=0,$user_type=0,$ip='',$session_id='') {
		if( !isset($login)) global $login;
		$login  = new login();
		$login->checkLogin();
		return $login->LogRegistry($name,$user_id,$user_type,'',$ip,$session_id);
	}
	public static function createDB() {
		$query = '
		CREATE TABLE IF NOT EXISTS `'.PREFIX.'community` (
		  `id` int(10) NOT NULL AUTO_INCREMENT,
		  `name` varchar(256) NOT NULL,
		  `type` varchar(256) NOT NULL,
		  `value1` mediumtext NOT NULL,
		  `value2` mediumtext NOT NULL,
		  `value3` mediumtext NOT NULL,
		  `value4` mediumtext NOT NULL,
		  `settings` varchar(256) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;';
		$db 	 	= new DBmanager();
		$que_result	 = $db->execute($query);
		
	}
	public function getRSS($name) {
		$db 	 	= new DBmanager();
		$name 	 	= $db->escapeString($name);
		$query 	 	= "SELECT * FROM ".PREFIX."community where `type`='RSS' and `name`='".$name."'";
		$que_result	 = $db->execute($query);
		if($que_result != FALSE ) {
			// load module responding to request
			ob_start();
			include_once(ADM.'/'.COM.'/'.'communityupdate'.'/'.'rss.'.$name.'.php');
			$contents = ob_get_contents();
			ob_end_clean();
			return $contents ;
		}
		else return FALSE;
	}
	/**
	* This function will return Data : Blog content, Comment, Comment blog from last connent (ID)
	*/
	public function friendupdate($lastContentID=0,$lastCommentID=0,$lastCommentBlogID=0) {
		// default value
		$lastContentID 		= (int) $lastContentID;		if( !($lastContentID >0) ) 		$lastContentID 		= 0;
		$lastCommentID 		= (int) $lastCommentID;		if( !($lastCommentID >0) ) 		$lastCommentID 		= 0;
		$lastCommentBlogID  = (int) $lastCommentBlogID; if( !($lastCommentBlogID >0) )  $lastCommentBlogID	= 0;
		$str = '<friendupdate>';
		$user_id = user::getUserName('','id');
		$cate_id = category::IDtoTITLE();
		$blog 	 = new contentmanager();
		$results = $blog->getContentUpdate($lastContentID);
		if (!empty($results) ) {
			$str .= '<numberblog>'.count($results).' </numberblog>';
			$str .= '<lastContentID>'.$lastContentID.' </lastContentID>';
			foreach($results as $key=>$value) {
				$last_id_blog = ($last_id_blog > $value['id'])? $last_id_blog : $value['id'];
				$str .= '<blog>';
				$str .= '<id>'.htmlspecialchars($value['id']).'</id>';
				$str .= '<title>'.htmlspecialchars($value['title']).'</title>';
				$str .= '<link>'.htmlspecialchars(urlmanager::makeLink('blog', $value['id'], '')).'</link>';
				if($value ['introtext'] == '') $introtext = wrap_content($value ['fulltext'],0,500);
				else $introtext = $value ['introtext'];
				$str .= '<introtext>'.htmlspecialchars($introtext ).'</introtext>';
				$str .= '<created>'.htmlspecialchars($value['created']).'</created>';
				$user = $user_id[$value ['created_by']];
				if(empty($user) ) $user = 'user_id'.$value ['created_by'];
				$str .= '<created_by>'.htmlspecialchars($user).'</created_by>';
				$str .= '<hit>'.htmlspecialchars($value['hit']).'</hit>';
				$str .= '<category>'.htmlspecialchars($cate_id[$value['category']]).'</category>';
				$str .= '</blog>';
			}
			$str .= '<last_id_blog>'.$last_id_blog.'</last_id_blog>';
		}
		else $str .= '<numberblog>0</numberblog>';
		$comment 	 = new comment();
		$results = $comment->getcommentUpdate($lastCommentID,'Front page');
		if (!empty($results) ) {
			$str .= '<numbercom>'.count($results).'</numbercom>';
			foreach($results as $key=>$value) {
				$last_id_com = ($last_id_com > $value['id'])? $last_id_com : $value['id'];
				$str .= '<commentfg>';
				$str .= '<id>'.htmlspecialchars($value['id']).'</id>';
				$str .= '<title>'.htmlspecialchars($value['title']).'</title>';
				$introtext = wrap_content($value ['comment'],0,500);
				$str .= '<comment>'.htmlspecialchars($introtext ).'</comment>';
				$str .= '<date>'.htmlspecialchars($value['date']).'</date>';
				$str .= '<user>'.htmlspecialchars($value ['user']).'</user>';
				$str .= '</commentfg>';
			}
			$str .= '<last_id_com>'.$last_id_com.'</last_id_com>';
		}
		else $str .= '<numbercom>0</numbercom>';
		$results = $comment->getcommentUpdate($lastCommentBlogID,'blog');
		$blog_title=$comment->getBlogTitle();
		if (!empty($results) ) {
			$str .= '<numbercomblog>'.count($results).'</numbercomblog>';
			foreach($results as $key=>$value) {
				$last_id_comblog = ($last_id_comblog > $value['id'])? $last_id_comblog : $value['id'];
				$str .= '<commentblog>';
				$str .= '<id>'.htmlspecialchars($value['id']).'</id>';
				$str .= '<title>'.htmlspecialchars($value['title']).'</title>';
				$introtext = wrap_content($value ['comment'],0,200);
				$str .= '<comment>'.htmlspecialchars($introtext ).'</comment>';
				$str .= '<belongid>'.htmlspecialchars($value['belong']).'</belongid>';
				$str .= '<belong>'.htmlspecialchars($blog_title[$value['belong']]['title']).'</belong>';
				$str .= '<date>'.htmlspecialchars($value['date']).'</date>';
				$str .= '<user>'.htmlspecialchars($value ['user']).'</user>';
				$str .= '</commentblog>';
			}
			$str .= '<last_id_comblog>'.$last_id_comblog.'</last_id_comblog>';
		}
		else $str .= '<numbercomblog>0</numbercomblog>';
		$str .= '</friendupdate>';
		
		return $str;
	}
    function loginConfirm($name,$ip,$session_id,$url,$securitykey,$die = true) {
        if($this->getSecuritykey($name,$url) == $securitykey ) {
			$this->loggedRegistry($name,$url,1,$ip,$session_id);
		}
		if($die == true) die;                                                      
            
    }        
    function login($name='',$pass='',$url='',$session_id='') {
        if($pass == '') $name = '';
        $check	= $this->checkLogin($name,$pass);
    	if($check == false) {
        	echo $this->formLogin($name); //$_GET['user']
        	die;
    	}
    	else {
    		// lấy khóa bảo mật giữa 2 website
    		$securitykey = $this->getSecuritykey('',$url);
    		$user_info = $this->getUserInfo();
        	$data = array('friendname'=>$user_info['user_name'],'friendurl'=>_URL,'logged'=>'true','login'=>'confirm','ip'=>$_SERVER['REMOTE_ADDR'],'session_id'=>$session_id,'securitykey'=>$securitykey);
        	//print_r($data);
        	//echo $_GET['url']."xml.php?type=login";
        	$url = parse_url($url);
        	$http_client = new HttpClient($url['host']);		
        	$http_client->post($url['path']."/xml.php?type=login",$data);		
        	//echo $http_client->getContent();
        	echo '<script>window.close();</script>';
        }
        die;
            
    }
    public function postnew($friendname,$friendurl,$type,$title,$content,$id='') {
    	$user_info = $this->getUserInfo();
        //echo $_GET['url']."xml.php?type=login";
        $url = parse_url($friendurl);
        $http_client = new HttpClient($url['host']);
        //$http_client->setDebug(true);
        // lấy khóa bảo mật giữa 2 website
    	$securitykey = $this->getSecuritykey($friendname,$friendurl);
    	if($session_id == '' ) {
			$session_id = session_id();
		}
		$data = array('friendname'=>$user_info['user_name'],'friendurl'=>_URL,'typecomment'=>$type,'id'=>$id,'securitykey'=>$securitykey,'title'=>$title,'content'=>$content,'session_id'=>$session_id,'ip'=>$_SERVER['REMOTE_ADDR']);
        $http_client->post($url['path']."/xml.php?type=postnewSave",$data);
        return $http_client->getContent();	
    	//return print_r($_POST);
    }
    public function postnewSave($friendname,$friendurl,$type,$title,$content,$securitykey,$ip,$session_id,$id='') {
		if($this->getSecuritykey($friendname, $friendurl)==$securitykey ) {
			if($type == 'comment') 	$type = 'Front page';
			else $type = 'blog';
			$comment = new comment();
			$friendname =  "<a href='".$friendurl."' title='friend'>".$friendname."</a>";
			if($comment->createComment($title, $content, $type,$id,$friendname,'',$ip) == false) {
				// echo $comment->getError();
				return '<post><completed>false</completed></post>';
			}
			else {
				return '<post><completed>true</completed></post>';
			}
		}
		return '<post><completed>false</completed></post>';
    }
	public function getCommunity($name) {
		$db 	 	= new DBmanager();
		$name 	 	= $db->escapeString($name);
		$query 	 	= "SELECT * FROM ".PREFIX."community where `type`='community' and `name`='".$name."'";
		$que_result	 = $db->execute($query);
		if($que_result != FALSE && $db->count() >0 ) {
			// load module responding to request
			ob_start();
			include_once(ADM.'/'.COM.'/'.'communityupdate'.'/'.'community.'.$name.'.php');
			$contents = ob_get_contents();
			ob_end_clean();
			return $contents ;
		}
		else return FALSE;
	}
	/**
	 * lấy khóa bảo mật giữa 2 website
	 * Khóa này dùng để chứng thực khi 2 website tương tác với nhau
	 * Khóa này là ngẫu nhiên và duy nhất với từng cặp website
	 */
	public function getSecuritykey($friendname,$friendurl){
		// lấy khóa bảo mật giữa 2 website
    	$conf = $this->getConfig('makefriend','community','','friend',$friendname);
		//var_dump($conf);
		if(!empty($conf)) {
				foreach($conf as $value) {
					$get_info	 	= explode(":-:",$value['value3']);
					if($get_info[2] == $friendurl ) {
						return $get_info[3];
						//break;
					}
				}
		}
		return false;
	}
	function createConfig($name='',$type='',$value1='',$value2='',$value3='',$value4='',$settings='') {
		// Save config
		$db 	 	= new DBmanager();
		$name 	 	= $db->escapeString($name);
		$type 	 	= $db->escapeString($type);
		$value1 	= $db->escapeString($value1);
		$value2 	= $db->escapeString($value2);
		$value3 	= $db->escapeString($value3);
		$value4  	= $db->escapeString($value4);
		$settings 	= $db->escapeString($settings);
		$query 	 	= "INSERT INTO  ".PREFIX."community ( `name`,`type` , `value1`,`value2`,`value3`,`value4`,`settings`) VALUES ('".$name."','".$type."','".$value1."','".$value2."','".$value3."','".$value4."','".$settings."')";	  
		$que_result	 = $db->execute($query);
		if( $que_result != FALSE ) $complete = $db->insertId();
		else $complete = FALSE;	
		// return the ID which has just inserted.
		return $complete;
	}
	
	function setConfig($id,$name='',$type='',$value1='',$value2='',$value3='',$value4='',$settings='') {
		$db 	 	= new DBmanager();
		$id 	 	= $db->escapeString($id);
		$name 	 	= $db->escapeString($name);
		$type 	 	= $db->escapeString($type);
		$value1 	= $db->escapeString($value1);
		$value2 	= $db->escapeString($value2);
		$value3 	= $db->escapeString($value3);
		$value4  	= $db->escapeString($value4);
		$settings 	= $db->escapeString($settings);
		$select 	 	= "UPDATE `".PREFIX."community` SET ";
		$query	 	= $select; 
		if($name != '')      	 	 $query .= ($select==$query?' ':' , ')." `name`  = '$name' ";
		if($type != '')      	 	 $query .= ($select==$query?' ':' , ')." `type`  = '$type' ";
		if($value1 != '')      	 	 $query .= ($select==$query?' ':' , ')." `value1`= '$value1' ";
		if($value2 != '')      	 	 $query .= ($select==$query?' ':' , ')." `value2`= '$value2' ";
		if($value != '')      	 	 $query .= ($select==$query?' ':' , ')." `value3`= '$value3' ";
		if($value4 != '')      	 	 $query .= ($select==$query?' ':' , ')." `value4`= '$value4' ";
		if(settings != '')      	 $query .= ($select==$query?' ':' , ')." `settings`='$settings' ";
		$query 	 	.= " WHERE `id` ='".$id."' LIMIT 1 ";
		$que_result	 = $db->execute($query);
		if( $que_result != FALSE ) $complete = TRUE;
		else $complete = FALSE;	
	}
	
	function deleteConfig($id) {
		$db 	 = new DBmanager();
		$id 	 = $db->escapeString($id);
		$query 	 = "DELETE FROM  ".PREFIX."community WHERE `id`='".$id."' LIMIT 1";	  
		$que_result= $db->execute($query);
		if( $que_result != FALSE ) $complete = TRUE;
		else $complete = FALSE;
		return $complete;
	}
	function getConfig($name='',$type='',$id = '',$value1='',$value2='',$value3='',$value4='',$settings='',$limit=array(0,10)){
		// Load community config
		$db 	 	= new DBmanager();
		$name 	 	= $db->escapeString($name);
		$type 	 	= $db->escapeString($type);
		$id 	 	= $db->escapeString($id);
		$value1 	= $db->escapeString($value1);
		$value2 	= $db->escapeString($value2);
		$value3  	= $db->escapeString($value3);
		$value4  	= $db->escapeString($value4);
		$settings 	= $db->escapeString($settings);
		$select 	= "SELECT * FROM ".PREFIX."community ";
		$query	 	= $select; 
		if($id != '')      	 	 $query .= ($select==$query?' WHERE ':' AND ')." `id`='$id' ";
		if($name != '')      	 $query .= ($select==$query?' WHERE ':' AND ')." `name`='$name' ";
		if($type != '')      	 $query .= ($select==$query?' WHERE ':' AND ')." `type`='$type' ";
		if($value1 != '')      	 $query .= ($select==$query?' WHERE ':' AND ')." `value1`='$value1' ";
		if($value2 != '')      	 $query .= ($select==$query?' WHERE ':' AND ')." `value2`='$value2' ";
		if($value3 != '')      	 $query .= ($select==$query?' WHERE ':' AND ')." `value3`='$value3' ";
		if($value4 != '')      	 $query .= ($select==$query?' WHERE ':' AND ')." `value4`='$value4' ";
		if($settings != '')      $query .= ($select==$query?' WHERE ':' AND ')." `settings`='$settings' ";
		if(is_array($limit) )	 $query	.= ' LIMIT '.$limit[0].','.$limit[1]; 
		$que_result	= $db->execute($query);
		$num		= $db->count();
		if($num > 0 ) {
			$result = array();
			for($i=0; $i < $num;$i++) {
				$result[$i] = $db->FetchResult($que_result);
			}
			return $result;
		}
		else return FALSE;
	}
}

?>