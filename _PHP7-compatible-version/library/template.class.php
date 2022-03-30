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
 * This class is used for managing template, style for web.
 * It will call, set template setting.
 * 
 * Note: In DataBase, It must be satisfied:
 * 		Table  Template: PATH field is unique
 * 		Table  Postion: NAME field is unique
 */
class template {
	private $setting;
	private $path = array();
	private $id   = array();
	private $type;
	private $temp_path;
	private $mod_path;
	private $user_per;	// the current user permisson
		// Get setting in config file

	function __construct($admin = '') {
		$this->type = $admin;
		// Check if on mobile or not
		global $_URL;
		$url = $_URL->getNewUrl();
		if($url[0] == "mobile_check" && $url[1] == "DO_NOT_CHECK" ) {
			$_SESSION['mobile_check'] = "DO_NOT_CHECK";
			header('Location: '._URL);
		}
		global $_CONFIG;
		$siteconfig = $_CONFIG->getSite();
		if($siteconfig['MOBILE'] != FALSE && $_SESSION['mobile_check'] != "DO_NOT_CHECK"  ) {
			$mobile_check = mobile_device_detect(false,true,true,true,true,true,true,false,false);
			if( $mobile_check != FALSE ) {
			   //echo 'You are on mobile. This feature is under contruction! Please wait!';
			   echo "You are on mobile. Trang web được hiển thị để hỗ trợ cho mobile. <br />";
			   echo urlmanager::makeLink("mobile_check", "DO_NOT_CHECK", "","remove mobile support","Nếu bạn muốn bỏ chế độ hỗ trợ cho mobile, Click vào đây!");
			 }
		}
		// End Check if on mobile or not
		
		//find active position
		$db	  	  = new DBmanager();
		//print_r($db->error() );
		$query    = 'SELECT * FROM '.PREFIX.'position';
		if($this->type == 'admin')  $query 		.= '  WHERE (`type` = "admin")';		// For Loading Admin Page
		$p_re     = $db->execute($query);
		//echo $query;
		$p_num 	  = $db->count();
		//print_r($db->error() );
		if($p_num > 0) {
			$query_mod = 'SELECT * FROM '.PREFIX.'module  ORDER BY `order` ASC';
			$m_re = $db->execute($query_mod);
			$mod_num = $db->count();
			$all_mod_path = array();
			if($mod_num > 0) {
				for ($j = 0; $j<$mod_num ;$j++) {
					$m_row = $db->FetchResult($m_re);
					$all_mod_path[$m_row['id_pos']][$j] = $m_row;
				}
				
			}
			$pos_name = array();		 // Name of Postions: they are key for our Module array.
			$pos_of_mod = array();
			$mod_path = array();		//  It contains all modules which they are belonged.
			for ($i = 0; $i<$p_num ;$i++) {
				$p_row = $db->FetchResult($p_re);
				array_push($pos_name,$p_row['name']);
				//call and load modules which are belong to each position
				foreach($all_mod_path as $key=>$value) { 
					if($key == $p_row['id']) $mod_path [$p_row['id']] = $value;
				}
				if(empty($mod_path[$p_row['id']] )) $mod_path[$p_row['id']] =array();
			}
			
			$pos_path = array_combine($pos_name,$mod_path);
			/*	Testing...
			foreach ($pos_path as $key=>$value) {
				echo 'Khóa '.$key.' có<PRE>';
				print_r($value);
				echo '</PRE>';
			}
			*/
			$this->path = $pos_path;
		}
		else {
			echo 'You do not config Position FOR Template, please contact your temp designer.';
			exit;
		}
		global $login;
		$user_info = $login->getInfo();//pre($user_info);
		$this->user_per = (string)(int)$user_info['type'];//var_dump($user_info);die;
		$this->loadStartUp();
		
	}
	
	public function getPath() {
		return $this->path;
	}
	public function getId() {
		return $this->id;
	}
	
	function setSetting() {
		
	}
	public function loadStartUp() {
		/**
		 * 	 Load files which need first running, such as: check login- check captcha, ..
		 */
		 // Can IT load from DataBase?
		 // but now, simply it load auto from startup directory
		 $list	 = @scandir ( STUP, 0 );
		 if($list != false) {
			foreach ( $list as $file ) {
				if ($file != "." && $file != ".." && is_file (  STUP . "/" . $file ) ) include_once( STUP . "/" . $file);
			}
		}
	}
	
	
	/**
	 * 	 Call template and load it, start our website
	 */
	function loadTemp() {
		global $_timeloadtemp;
		$start_time = microtime(true);
		//load template setting in DB
		global $_CONFIG;
		//set temp location of theme - normal or admin site
		if($this->type == 'admin') {
			$this->temp_path  = ADM.'/'.TEMP;
		}
		else $this->temp_path = TEMP;
			
		$db	    = new DBmanager();
		$query  = 'SELECT * FROM '.PREFIX.'template WHERE enable=1';
		if($this->type == 'admin') {
			$query .= ' AND `type`="admin"';
		}
		$db->execute($query);
		$_SESSION['temp'] 		  = array();
		if($db->count()  == 1) {
			$temp		= $db->getRow();
			$path 		= $this->temp_path.'/'.$temp['path'].'/index.php';
			$temp_path  = $this->temp_path.'/'.$temp['path'].'/';
		}
		elseif($db->count() > 1) {
			throw new myException('There are more than 1 template setting.');
			exit;
		}
		else {
			$path		= $this->temp_path.'/default/index.php';
			$temp_path  = $this->temp_path.'/default/';
		}
		$_SESSION['temp']['path'] = _URL.$temp_path;
		global $THEME_PATH;
		$THEME_PATH = $_SESSION['temp']['path'];
		// Start loading:
		ob_start();
		if(is_file($path)) {
			include_once($path);
		}
		else echo "Can not load Template, please check the path";
		$web = ob_get_contents();
        ob_end_clean();
		$web = $this->loadModule($web);
        $web = $this->loadComponent($web);
        $web = $this->loadPlugin($web);
		$web = $this->loadMeta($web);
		// Loaded completed
		$end_time = microtime(true);
		$_timeloadtemp = ($end_time - $start_time)."<br />";	
		// Display:
		return $web;
		
	
		
	}
	/**
	 * This template has modules, so it will replace which position they are belong (from get(Seting))
	 * @param $position : name of position
	 */
	
	private function loadModule($web)	  {
		global $_timeloadmodule,$_timeloadmoduledetail;
		$start_time = microtime(true);
		foreach ($this->path as $key=>$value) {
			
			$contents = '';
			// Position will have position like:
			// 		<!--hlp_position_top-->
			// Firstly, check if in webpage there exist this position:
			$compare  = "<!--hlp_position_".$key."-->";
			if(strrpos($web,$compare) != FALSE && $key != 'main_content') {
				global $mod_params;
				foreach ($value as $m) {
					/**
					 * First, checking the setting
					 * this setting is contained in 'params'
					 */
					if($m['permission'] != '') {
						$permission_list = explode(',',$m['permission']);
						if(in_array($this->user_per,$permission_list,TRUE)) {
							$permission = TRUE;
						}
						else $permission = FALSE;
					}
					else $permission = TRUE;
					//var_dump($permission_list);die;
					if($m['enable']&& $permission) {
						$start_time1 = microtime(true);
						ob_start();
						global $mod_path,$mod_params;	// Global setting for each module
						$mod_params =$m['params'];
						//echo $mod_params;
						if($this->type 	 == 'admin'&&$m['type'] == 'admin') $mod_path = ADM."/".MOD.'/'.$m['path'];
						else $mod_path   = MOD.'/'.$m['path'];
						$path_index = $mod_path.'/index.php';
						if(is_file($path_index)) {
							include($path_index);
						}
						else echo "Can not load module: ".$m['name'] ." path: ".$mod_path;
						$contents .= ob_get_contents();
						unset($mod_path);
						unset($mod_params);
	        			ob_end_clean();
						$end_time1 = microtime(true);
					$_timeloadmoduledetail .= "Tim loading for: <strong>".$m['name']."</strong> need: ".($end_time1 - $start_time1)."<br />";
					}
				}
				$web = str_replace ($compare,$contents,$web);
				
			}
		}
		$end_time = microtime(true);
		$_timeloadmodule = ($end_time - $start_time)."<br />";
		return $web; 
	}
    private function loadComponent($web) {
		global $_timeloadcomponent;
		$start_time = microtime(true);
        $compare  = "<!--hlp_position_main_content-->";
        $db = new DBmanager();
        ob_start();
        $query = 'SELECT * FROM '.PREFIX.'component WHERE ';
        if(!isset($_URL)) global $_URL;
        $url = $_URL->getNewUrl ();
        if(empty($url[0] ) || $url[0] == '') {
            //load default component
            $query .= ' `default`="1"';
            if($this->type == 'admin') $path = ADM.'/'.COM;
            else $path = COM;
            if($this->type == 'admin') $query .= ' AND type="admin"';
            $db->execute($query);
            if($db->count() >0) {
                $row = $db->getRow();
                global $com_path;
                $com_path = $path.'/'.$row['path'];
                if(is_file($com_path.'/index.php')) {
                    global $com_params;
                    $com_params = $row['params'];
                    if($row['permission'] != '') {
						$permission_list = explode(',',$row['permission']);
						if(in_array($this->user_per,$permission_list,TRUE)) {
							$permission = TRUE;
						}
						else $permission = FALSE;
					}
					else $permission = TRUE;
					if($row['enable']&& $permission) {
                    	include($path.'/'.$row['path'].'/index.php');
					}
					else echo 'You have no permission to access this component';
                }
                else {
                    echo 'Can not find default component: '.$row['path'];
                }
                //reset global variables 
                    $com_params	 = "";
                    $com_path	 = "";
            }
        }
        else {
            $name_com = $db->escapeString($url[0]);
            if($this->type == 'admin') $path = ADM.'/'.COM;
            else $path = COM;
			$query .= ' `path`="'.$name_com.'"';
            if($this->type == 'admin') $query .= ' AND type="admin"';
            $db->execute($query);
            if($db->count() >0) {
                    $row = $db->getRow();
                    global $com_params;
                    $com_params = $row['params'];
                    if($row['permission'] != '') {
						$permission_list = explode(',',$row['permission']);
						if(in_array($this->user_per,$permission_list,TRUE)) {
							$permission = TRUE;
						}
						else $permission = FALSE;
					}
					else $permission = TRUE;
					if($row['enable']&& $permission) {
						global $com_path;
                		$com_path = $path.'/'.$name_com;
                		if(is_file($com_path.'/index.php'))
                    		include($com_path.'/index.php');
                    		else echo 'Component does not exist: '.$com_path;
					}
					else echo 'You have no permission to access this component';
					//reset global variables 
                    $com_params	 = "";
                    $com_path	 = "";
            }
            else {
             	echo 'Can not find component: '.$name_com;
            }
        }
        $content = ob_get_contents();
		ob_end_clean();
        $web = str_replace ($compare,$content,$web);
		$end_time = microtime(true);
		$_timeloadcomponent = ($end_time - $start_time)."<br />";
        return $web;
    }
    private function loadPlugin($web) {
		global $_timeloadplugin;
		$start_time = microtime(true);
    	// load all enabled plugin
    	$db 		  = new DBmanager();
    	$plugin_query = 'SELECT * FROM '.PREFIX.'plugin WHERE `enable`="1"';
    	$plugin__re	  = $db->execute($plugin_query);
		$plugin__num  = $db->count();
		$all_plugin__path = array();
		if($plugin__num > 0) {
			for ($j = 0; $j<$plugin__num ;$j++) {
					$plugin_row = $db->FetchResult($plugin__re);
					$all_plugin__path[$plugin_row['name']][$j] = $plugin_row;
			}
			foreach($all_plugin__path as $key=>$value) {
				$compare  = "<!--hlp_plugin_".$key."-->";
				echo $compare;
				if(strrpos($web,$compare) != FALSE ) {
					$contents = "";
					foreach($value as $plugins) {
						ob_start();
						global $plugin_path,$plugin_params;	// Global setting for each module
						$plugin_params = $plugins;
						if($this->type 	 == 'admin'&& $plugins['type'] == 'admin') $plugin_path = ADM."/".PLUG.'/'.$plugins['path'];
						else $plugin_path   = PLUG.'/'.$plugins['path'];
						$path_index = $plugin_path.'/index.php';
						if(is_file($path_index)) {
							include($path_index);
						}
						else echo "Can not load module: ".$plugins['title'] ." path: ".$plugin_path;
						$contents .= ob_get_contents();
						unset($plugin_path);
						unset($plugin_params);
				        ob_end_clean();
					}
			        $web = str_replace ($compare,$contents,$web);
				}
			}

			
		}
    	$end_time = microtime(true);
		$_timeloadplugin = ($end_time - $start_time)."<br />";
    	return $web;
    }
	private function loadMeta($web) {
		// load title
		if(!isset($title_meta_data_temp)) global $title_meta_data_temp;
		if($title_meta_data_temp != "" ) {
			$title_meta_data_temp = filter_str($title_meta_data_temp);
			global $_CONFIG;
			$old_title = $_CONFIG->getSite();
			$title_meta_data_temp = $title_meta_data_temp."-".$old_title["TITLE"];
			// find and replace
			if(is_int (stripos($web,'<title>'))) {
				// replace the old TITLE tag
				$compare = substr($web,stripos($web,'<title>') ,stripos($web,'</title>') - stripos($web,'<title>') + 8);
				$web = str_replace ($compare,"<title>".$title_meta_data_temp."</title>",$web);
			}
			elseif(is_int (stripos($web,'<head'))) {
				// auto insert a TITLE tag after HEAD tag
				$start = stripos(substr($web,stripos($web,'<head')),'>');
				$web = substr($web,0,$start)."<title>".$title_meta_data_temp."</title>".substr($web,$start);
			}
			else {
				// There is no HEAD tag, so insert at the top of page
				$web = "<title>".$title_meta_data_temp."</title>".$web;
			}
			
		}
		// load meta
		if(!isset($meta_data_temp)) global $meta_data_temp;
		if($meta_data_temp != "" ) {
			if(is_int (stripos($web,'<head'))) {
				// auto insert a TITLE tag after HEAD tag
				$head_start_po = stripos($web,"<head");
				$start = $head_start_po + stripos(substr($web,$head_start_po),">") + 1;
				$web = substr($web,0,$start).$meta_data_temp.substr($web,$start);
			}
			else {
				// There is no HEAD tag, so insert at the top of page
				$web = $meta_data_temp.$web;
			}
		}
		return $web;
	}
	
}

?>