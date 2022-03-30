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
 * Note: Only 4 comments a day per a IP.
 * 
 *
 */
class comment {
	private $error;
				// Code number 1: Get Limit comments
				// Code number 2: Can not query DB
				// Code number 3: Your name or comment empty
				// Code number 4: Exist user name (registered user)
	private $num_comment;
	private $id_belongs;
	private $user_per;	// get the current user permission
	public function __construct() {
		if(!isset($_CONFIG)) global $_CONFIG;
		$dbsetting = $_CONFIG->getDB();
		if($dbsetting['AUTOCHECK'] == TRUE) $this->createDB();
	}
	public function createDB() {
		$db = new DBmanager();
		$query	=	"CREATE TABLE IF NOT EXISTS `".PREFIX."comment` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `title` varchar(256) NOT NULL,
				  `comment` mediumtext NOT NULL,
				  `type` varchar(256) NOT NULL,
				  `belong` varchar(256) NOT NULL,
				  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  `ip` varchar(256) NOT NULL,
				  `user` varchar(256) NOT NULL,
				  `publish` varchar(256) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM;
				";
		$db->execute($query);
	}
	public function getAcomment($id) {
		// Get only A comment by ID.
		$db		 = new DBmanager();
		$id 	 = $db->escapeString ( $id );
		$query	= 'SELECT * FROM '.PREFIX.'comment WHERE `id`="'.$id.'"  ';
		if($db->execute($query) != FALSE) {
			$row = $db->getRow();
			$this->id_belongs = $row['belong'];
			return $row;
		}
		else return FALSE;
	}
	
	/** new function for more convenient but compatible with old version
	// function getContent($array)
	$array include key => value
	example: $array( 'type'=>15,'limit_start'=>'20', ....  )
	
	*/
	
	public function getComments($types, $limit_start =0, $limit = 3,$belong ='',$publish ='', $ip ='',$user = '',$orderby='DESC') {
		$db		 = new DBmanager();
		if( is_array($types) ) {
			if(isset($types['title'] ) )	 	 $title		= $db->escapeString($types['title']);
			if(isset($types['publish'] ) )	 	 $publish	= $db->escapeString($types['publish']);
			if(isset($types['comment'] ) )	 	 $comment	= $db->escapeString($types['comment']);
			if(isset($types['type'] ) )	 	 	 $type		= $db->escapeString($types['type']);
			if(isset($types['belong'] ) )	 	 $belong	= $db->escapeString($types['belong']);
			if(isset($types['user'] ) )	 		 $user		= $db->escapeString($types['user']);
			if(isset($types['date'] ) )		 	 $date		= $db->escapeString($types['date']);
			if(isset($types['ip'] ) )	 		 $ip		= $db->escapeString($types['ip']);
			if(isset($types['orderby'] ) )		 $orderby	= $db->escapeString($types['orderby']);
			if(isset($types['parent'] ) )	 	 $parent	= $db->escapeString($types['parent']);
			if(isset($types['limit_start'] ) )	 $limit_start	= $db->escapeString($types['limit_start']);
			if(isset($types['limit'] ) )	 	 $limit	= $db->escapeString($types['limit']);
			if(isset($types['order'] ) )	 	 $order	= $db->escapeString($types['order']);
			if(isset($types['orderby'] ) )	 	 $orderby	= $db->escapeString($types['orderby']);
		}
		else {
			$type 	 = $db->escapeString ( $types );
			$limit 	 = $db->escapeString ( $limit );
			$user 	 = $db->escapeString ( $user );
			$belong  = $db->escapeString ( $belong );
			$ip		 = $db->escapeString ( $ip );
			$publish = $db->escapeString ( $publish );
			$orderby = $db->escapeString ( $orderby );
		}
		$arr = array('"',"'"," ",";");	// tự d?ng b? ' vࠢ v࠱ s? k𭀿 d?c bi?t ra kh?i string
		if( !empty($belong ) ) {
			$belong	= str_replace($arr,'',$belong);
			$belong	= trim($belong,',');
			$belong	= $db->escapeString($belong);
		}
		if( !empty($parent ) ) {
			$parent	= str_replace($arr,'',$parent);
			$parent	= trim($parent,',');
			$parent	= $db->escapeString($parent);
		}
		$parent_count = count(explode(',',$parent));
		
		// Default setting
		if($limit_start =='') 	$limit_start = 0;
		$query_count	= 'SELECT count(id) FROM '.PREFIX.'comment WHERE `type`="'.$type.'" ';
		if($publish == '') {
			if(!isset($login) ) global $login;
			$user_info = $login->getInfo();
			$this->user_per = $user_info['type'];
		  if($this->user_per['type'] == 3 || $this->user_per['type'] == 4 ) $publish = '';
		  elseif($this->user_per['type'] == 2 || $this->user_per['type'] == 1 ) $publish = '"1","2"';
		  else $publish = '"1"';
		}
		$condition = '';
		if($publish != '') $query_count.= ' AND `publish` IN ('.$publish.') ';
		if($belong != '') 	$condition .= ' AND `belong` IN ('.$belong.') ';
		if($ip != '') 		$condition .= ' AND `ip`="'.$ip.'" ';
		if($user != '') 	$condition .= ' AND `user`="'.$user.'" ';
		if		( !empty($parent) && $parent_count == 1 ) $condition .= ' AND `parent`="'.$parent.'" ';
		elseif	( !empty($parent) && $parent_count > 1  ) $condition .= ' AND `parent` IN ('.$parent.') ';
		else $condition .= ' AND `parent`="0" ';
		$db->execute($query_count.$condition);
		$temp = $db->getRow();
		$this->num_comment = $temp['count(id)'];
		$query	= 'SELECT * FROM '.PREFIX.'comment WHERE `type`="'.$type.'" ';
		if($publish != '') 		$query 	 .= ' AND`publish` IN ('.$publish.') ';
		if( $order == 'last_change' ) {
			$limit_query .=  ' ORDER BY GREATEST( `last_change`,`date` )';
		}
		else $limit_query .= ' ORDER BY `date` ';
		if( $orderby != 'DESC' ) $limit_query .= '  ASC';
		else $limit_query .= ' DESC  ';
		if(!empty($limit))	$limit_query .= '  LIMIT '.$limit_start.','.$limit.' ';
		//else 	$limit_query .= ' ORDER BY `date`  DESC ';
		$query = $query.$condition.$limit_query;
		$result = $db->execute($query);
		$num = $db->count();
		//echo $query; //echo $condition;
		if($num > 0) {
			$results = array();
			$parents = array();	// get all ID of parent comment, check if they have children comment
			// Find All user name who posted comment
			//$user_check = $this->getUserName();
			//
			for($i=0; $i<$num; $i++) {
				$row = $db->FetchResult($result);
				//$row['comment'] = html_entity_decode($row['comment']); 
				//if($user_check[$row['user']] != '') $row['user'] = $user_check[$row['user']];
				// this is for find title of blog which comment belong ( ID of blog: 17,18,20...
				$i==0?$this->id_belongs .= $row['belong']:$this->id_belongs .= ','.$row['belong'];
				array_push($results,$row);
				$parents[] = $row['id'];
			}
			
			// Loop only one, not support nest comment
			if( is_array($types) && empty($parent)  ) {
				// only for new function
				$types['parent'] = implode(',',$parents);
				$types['orderby'] =  'ASC';
				$types['limit'] =  '1000';
				$children = $this->getComments($types);//pre($children);
				$childrens = array();
				if( !empty($children ) ) {
					$j = 0;
					foreach( $children as $r_1 ) {
						$childrens[$r_1['parent']][] = $r_1;
						$j++;
					}
				}
				$total = $temp['count(id)'] + $j;
				return array( 'total'=>$total , 'parent'=> $results ,'children'=>$childrens );
			}
			return $results;
		}
		else {
			return FALSE;
		}
	}
	public function getUserName($id='') {
		if($id == ''	&&	$this->id_belongs != '') $id = $db->escapeString($this->id_belongs);
		return user::getUserName($id);
	}
	public function getBlogTitle($id='') {
		$db		  = new DBmanager();
		if($id == '') $id = $db->escapeString($this->id_belongs);
		if($id == '') return array();
		$query	  = 'SELECT id,title,created_by FROM '.PREFIX.'blog WHERE `id` IN ('.$id.') ';
		$db->execute($query);
		$num =$db->count();
		//echo $num;
		if($num > 0) {
			$result = array();
			$id_arr = array();
			for($i=0; $i< $num; $i++) {
				$row = $db->getRow();	
				array_push($result,$row);
				array_push($id_arr,$row['id']);
			}
			$blog_title = array_combine($id_arr,$result);
			return $blog_title;
		}
		else return FALSE;
	}
	
	public function getNumCom(){
		return $this->num_comment;
	}
	
	public function updateComment($id,$title, $comment, $belong = '', $user = '', $date = '',  $publish = '') {
		$db		 = new DBmanager();
		$id 	 = $db->escapeString ( $id );
		$title 	 = $db->escapeString ( $title );
		$comment = $db->escapeString ( $comment );
		$belong  = $db->escapeString ( $belong );
		$user 	 = $db->escapeString ( $user );
		$date  	 = $db->escapeString ( $date );
		$publish = $db->escapeString ( $publish );
		// Default setting			
		$query	="UPDATE ".PREFIX."comment SET `title`='$title',`comment`='$comment' ,`user`='$user',`date`='$date' ,`publish`='$publish',belong='$belong'  WHERE id='$id'";
		$suc	= $db->execute($query);
		return $suc;
	}
	
	public function deleteComment($id) {
		$db = new DBmanager();	
		$xoa ="DELETE FROM  ".PREFIX."comment WHERE id='$id' LIMIT 1";	
		$suc	= $db->execute($xoa);
		return $suc;
	}
	
	/** new function for more convenient but compatible with old version
	// function getContent($array)
	$array include key => value
	example: $array( 'title'=>'abc','comment'=>'test afbaga bal a', ....  )
	
	*/
	public function createComment($titles, $comment="", $type="", $belong = '', $user = '', $date = '', $ip = '', $publish = '',$max_comment = 20) {
		if(!isset($login) )global $login;
		if(!isset($login) ) $login = new login();
		$user_info = $login->getInfo();
		$db = new DBmanager ();
		global $_CONFIG;
		if (! isset ( $_CONFIG ))	$_CONFIG = new config ();
		
		// new function tiện lợi hơn nhưng vẫn tương thích với bản cũ
		if( is_array($titles) ) {
			if(isset($titles['title'] ) )	 	 $title		= $db->escapeString($titles['title']);
			if(isset($titles['publish'] ) )	 	 $publish	= $db->escapeString($titles['publish']);
			if(isset($titles['comment'] ) )	 	 $comment	= $db->escapeString($titles['comment']);
			if(isset($titles['type'] ) )	 	 $type		= $db->escapeString($titles['type']);
			if(isset($titles['belong'] ) )	 	 $belong	= $db->escapeString($titles['belong']);
			if(isset($titles['user'] ) )	 	 $user		= $db->escapeString($titles['user']);
			if(isset($titles['date'] ) )		 $date		= $db->escapeString($titles['date']);
			if(isset($titles['ip'] ) )	 		 $ip		= $db->escapeString($titles['ip']);
			if(isset($titles['max_comment'] ) )	 $max_comment	= $db->escapeString($titles['max_comment']);
			if(isset($titles['parent'] ) )	 	 $parent	= $db->escapeString($titles['parent']);
		}
		else {
			$title 	 = $db->escapeString ( $titles );
			$comment = $db->escapeString ( $comment );
			$type 	 = $db->escapeString ( $type );
			$user 	 = $db->escapeString ( $user );
			$belong  = $db->escapeString ( $belong );
			$date 	 = $db->escapeString ( $date );
			$ip		 = $db->escapeString ( $ip );
			$publish = $db->escapeString ( $publish );
		}
		//echo "Title 1: ". $title;
		if ($user == '') $user = $this->user_per['user'];
		if ($user == '') $user = $user_info['user'];
		if($user_info['id'] > 0 ) $user = $user_info['user'];
		elseif(is_string($user_info['id']) && !empty($user_info['id'])) $user = "<a href='".$user_info['id']."' title='friend'>".$user."</a>";
		if ($user == '')	$user = 'Guest';
		$title	 = htmlspecialchars($title);
		//echo "Title 2: ". $title;
		//$comment = htmlspecialchars($comment);
		// Default setting
		if( empty($ip) ) 		$ip = $_SERVER['REMOTE_ADDR'];
		if(empty($publish )) 	$publish = 1;
		if(empty($parent )) 	$parent = 0;
		if(empty($date )) {
			$homnay	=date(dmYHis);
			$date	=substr($homnay,4,4)."-".substr($homnay,2,2)."-".substr($homnay,0,2)." ".(substr($homnay,8,2)-1)."-".substr($homnay,10,2)."-".substr($homnay,12,2);
		}
		//echo "Title 3: ". $title;
		$com_hlp = $this->filter ( $comment );
		$com_hlp = removeXSStag($com_hlp);
		$title 	 = removeXSStag($title);
		$type 	 = removeXSStag($type);
		$date 	 = removeXSStag($date);
		//Start
		//echo "Title 4: ". $title;
		if (($title != '') && ($com_hlp != '')) {
			// Check name user exists or not 
				/*
				if($_SESSION ['user'] != $user) {
					$user_check = 'SELECT id FROM '.PREFIX.'user WHERE user="'.$user.'" ';
					$user_exist = $db->execute($user_check);
					if($user_exist != FALSE) $this->error = 4;	// Code number 4: Exist user name (registered user)
				}
				*/
			if(empty($this->error)) {
				$com_query = "INSERT INTO  " . PREFIX . "comment  (`title` , `comment` , `ip`,`type`,`belong`,`publish`,`user`,`parent`) VALUES ('$title' , '$com_hlp' , '$ip','$type' , '$belong','1','$user','$parent')";
				$suc = $db->execute ( $com_query );echo $db->error();
				//echo $com_query;
				if ($suc != FALSE) {
					/*include_once ("thu_vien/mail.php");
					//gui_mail ( "", "", "Blog vừa có thêm bình luận của bài viết mới, <br><a href=http://" . $_SERVER ['SERVER_NAME'] . "/?hlp=blog&id=" . $id . "> Nhấn vào đây để xem</a> ", "", "__1___" );
					echo 'Bạn đã gửi bình luận thành công<br>';
					*/
					
					if( $belong > 0 ) {
						// save last change for blog item
						$last_change = getTimeStamp();
						//$content = new contentmanager;
						//updateContent($id,$title,$category,$introtext = '',$fulltext,$publish = 1,$created = '',$modified ='',$hit = '1',$frontpage ='1')
						//$content->contentmanager($belong);
						$query 		= 'UPDATE '.PREFIX."blog  SET `last_change` = '$last_change' WHERE `id` ='$belong'	";
						$suc = $db->execute($query);
					}
					if( $parent > 0 ) {
						// update last change for parent comment
						$last_change = getTimeStamp();
						$query 		= 'UPDATE '.PREFIX."comment  SET `last_change` = '$last_change' WHERE `id` ='$parent'	";
						$suc = $db->execute($query);
					}
					return TRUE;
				} else
					$this->error = 2; // Code number 2: Can not query DB
			}
			
		} 
		else
			$this->error = 3; // Code number 3 : Your name or comment empty
	}
	
	public function filter($string) {
		$string = nl2br ( $string );
		return $string;
	}
	public function getError() {
		return $this->error;
	}
	
	/** 
	* this function for community : return new content
	*/
	public function getCommentUpdate($id='',$type,$limit=10) {
		$db 		= new DBmanager();
		$limit	= $db->escapeString($limit);
		$type	= $db->escapeString($type);
		$id 		= (int) $id;
		$query		= 'SELECT * FROM '.PREFIX.'comment ';
		$query		.= " WHERE `type`='".$type."'"; 
		if($id > 0) $query .= ' AND `id` > '.$id;
		$query	   .= ' ORDER BY date DESC ';
		$query     .= 'LIMIT '.$limit;
		$result		= $db->execute($query);
		$num  		= $db->count();
		if($num > 0) {
			$results = array();
			$this->id_belongs = '';
			for($i=0; $i< $num; $i++) {
				$row = $db->FetchResult($result);
				array_push($results,$row);
				// this is for find title of blog which comment belong ( ID of blog: 17,18,20...
				$i==0?$this->id_belongs .= $row['belong']:$this->id_belongs .= ','.$row['belong'];
			}
			return $results;	
		}
		else return FALSE;
		
	}
}
?>