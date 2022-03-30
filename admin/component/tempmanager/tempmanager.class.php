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




class tempmanager {
	public function __construct() {
		if(!isset($_CONFIG)) global $_CONFIG;
		$dbsetting = $_CONFIG->getDB();
		if($dbsetting['AUTOCHECK'] == TRUE) $this->createDB();
	}
	public function createDB() {
		$db		= new DBmanager();
		$query	=	"CREATE TABLE IF NOT EXISTS `".PREFIX."template` (
				  `id` int(10) NOT NULL AUTO_INCREMENT,
				  `title` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `author` varchar(256) NOT NULL,
				  `version` varchar(256) NOT NULL,
				  `descr` varchar(255) NOT NULL,
				  `path` varchar(255) NOT NULL,
				  `type` varchar(256) NOT NULL,
				  `setting` varchar(255) NOT NULL,
				  `enable` varchar(255) NOT NULL,
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `path` (`path`)
				) ENGINE=MyISAM;
				";
		$db->execute($query);
	}
	public function getTemp($type= 'blog', $id = '') {
		$db		= new DBmanager();
		$query  = ' SELECT * FROM '.PREFIX.'template ';
		$id		= $db->escapeString($id);
		$type	= $db->escapeString($type);
		if($type != '')
		$query .= 'WHERE `type`="'.$type.'"';
		if($id != '') {
			$query .= ' WHERE `id`="'.$id.'"';
		}
		$db->execute($query);
		$num = $db->count();
		if($num > 0) {
			$results = array();
			if($id != '') {
				$results = $db->getRow();
			}
			else {
				for($i=0 ; $i<$num ; $i++) {
					array_push($results,$db->getRow());
				}
			}
		}
		else $results = FALSE;
		return $results;
	}
	public function getInstall($type='blog',$path = '') {
		if($type == 'admin') $url = ADM.'/'.TEMP;
		else $url = TEMP;
		$result = array();
		if($path == '') {
			// Find all Template
			$temp_arr = $this->getTemp($type);
			$install_arr =  array();
			if($temp_arr != FALSE) {
				foreach ($temp_arr as $key=>$value) {
					array_push($install_arr,$value['path']);
				}
			}
			$handle = opendir ( $url );
			if ($handle) {
				while ( false !== ($file = readdir ( $handle )) ) {
					if ($file != "." && $file != "..") {
						if (is_dir ( $url . "/" . $file ) && is_file ( $url . "/$file/config.xml" )) {
							$xml = simplexml_load_file( $url . "/$file/config.xml" );
							$title 	= $xml->type->detail->title;
								settype($title,"string");
								$row['title'] = $title;
							$name 	= $xml->type->detail->name;
								settype($name,"string");
								$row['name'] = $name;
							$author 	= $xml->type->detail->author;
								settype($author,"string");
								$row['author'] = $author;
							$version = $xml->type->detail->version;
								settype($version,"string");
								$row['version'] = $version;
							$row['path'] = $file;
							$row['type'] = $type;
							if(in_array($file,$install_arr)) $row['install']= 'Installed';
							else $row['install'] = FALSE;
							array_push($result,$row);
						}
						elseif (is_dir ( $url . "/" . $file ) && !is_file ( $url . "/$file/config.xml" )) {
							$xml = array();
							$row['name'] = $file;
							$row['path'] = $file;
							$row['type'] = $type;
							if(in_array($file,$install_arr)) $row['install']= 'Installed';
							else $row['install'] = FALSE;
							array_push($result,$row);
						}
					}
				}
				closedir ( $handle );
			
			}
		}
		else {
			//find information the given path template
			if (is_dir ( $url . "/" . $path ) && is_file ( $url . "/$path/config.xml" )) {
				$xml = simplexml_load_file( $url . "/$path/config.xml" );
				$title 	= $xml->type->detail->title;
					settype($title,"string");
					$row['title'] = $title;
				$name 	= $xml->type->detail->name;
					settype($name,"string");
					$row['name'] = $name;
				$author 	= $xml->type->detail->author;
					settype($author,"string");
					$row['author'] = $author;
				$version = $xml->type->detail->version;
					settype($version,"string");
					$row['version'] = $version;
				$row['path'] = $path;
				$row['type'] = $type;
			}
			elseif (is_dir ( $url . "/" . $path )) {
				$row['name'] = $path;
				$row['path'] = $path;
				$row['type'] = $type;
			}
			$result = $row;
		}
		
		if(empty($result)) return FALSE;
		else return $result;
	}
	public function Install($title = '',$name = '',$descr = '',$path = '',$type = '',$enable = '',$author='',$version='') {
		$db 	= new DBmanager();
		$title 	= $db->escapeString($title);
		$name 	= $db->escapeString($name);
		$descr 	= $db->escapeString($descr);
		$path 	= $db->escapeString($path);
		$type 	= $db->escapeString($type);
		$enable = $db->escapeString($enable);
		$author = $db->escapeString($author);
		$version = $db->escapeString($version);
		// Remove old setting
		$remove = 'UPDATE '.PREFIX.'template SET `enable`="0" WHERE `enable`="1" ';
		$db->execute($remove);
		//Make new setting
		$query  = 'INSERT INTO '.PREFIX.'template 
							(`title`,`name`,`descr`,`path`,`type`,`enable`,`author`,`version`) 
					VALUES ("'.$title.'","'.$name.'","'.$descr.'","'.$path.'","'.$type.'","'.$enable.'","'.$author.'","'.$version.'") ';
		$suc = $db->execute($query);
		echo $db->error();
		return $suc;
	}
	public function Update	($id,$title = '',$name = '',$descr = '',$path = '',$type = '',$enable = '') {
		$db 	= new DBmanager();
		$id 	= $db->escapeString($id);
		$title 	= $db->escapeString($title);
		$name 	= $db->escapeString($name);
		$descr 	= $db->escapeString($descr);
		$path 	= $db->escapeString($path);
		$type 	= $db->escapeString($type);
		$enable = $db->escapeString($enable);
		if($enable == 1) {
			// Remove old setting
			$remove = 'UPDATE '.PREFIX.'template SET `enable`="0" WHERE `enable`="1" ';
			$db->execute($remove);
		}
		$query  = 'UPDATE '.PREFIX.'template SET    `title`="'.$title.'",
													`name`="'.$name.'",
													`descr`="'.$descr.'",
													`path`="'.$path.'",
													`type`="'.$type.'",
													`enable`="'.$enable.'"
		 								WHERE `id`= "'.$id.'"';
		return $db->execute($query);
		
	}
	public function deleteModule($id) {
		if($id == '') {
			return FALSE;
		}
		else {
			$db 	= new DBmanager();
			$id 	= $db->escapeString($id);
			$query  = 'DELETE FROM '.PREFIX.'module WHERE `id`="'.$id.'"';
			return $db->execute($query);
		}
	}
}

/**
 * 
		$handle = opendir ( UTEMP );
		if ($handle) {
			while ( false !== ($file = readdir ( $handle )) ) {
				if ($file != "." && $file != "..") {
					if (is_dir ( UTEMP . "/" . $file ) && is_file ( UTEMP . "/$file/config.xml" )) {
						$xml = simplexml_load_file ( UTEMP . "/$file/config.xml" );
						echo "<tr><td>" . $xml->type->detail->name . "</td><td>" . $xml->type->detail->author . "</td><td>$file</td><td><img src='" . _UURL . TEMP . "/$file/thumb.jpg'></td><td><input type=radio name=dong_y value='$file'	";
						if ($_theme_csdl_num != 0 && $_theme_csdl_ht ['path'] == $file) {
							echo " checked ><input type=hidden name=Update value=ok></td></tr>"; //  Theme trong CSDL có tồn tại thư mục trên host
						} elseif ($_theme_csdl_num != 0 && $_theme_csdl_ht ['path'] != $file)
							echo "><input type=hidden name=Update value=ok></td></tr>";
						$so_theme ++;
					}
				}
			}
			closedir ( $handle );
		
		}
		if($type == 'admin') $url = MOD;
		else $url = UMOD;
		$result = array();
		if($path == '') {
			$normal = opendir ( $url );
			if ($normal) {
				while ( false !== ($file = readdir ( $normal )) ) {
					if ($file != "." && $file != "..") {
						if (is_file ( $url . "/" . $file ."/index.php" )) {
							if(is_file($url. "/" . $file.'/config.xml')) {
								$xml = simplexml_load_file( $url. "/" . $file.'/config.xml' );
							}
							else $xml = array();  	// Empty array
							$row = array();
							$title 	= $xml->type->detail->title;
								settype($title,"string");$row['title'] = $title;
							$name 	= $xml->type[0]->detail[0]->name;
								settype($name,"string");$row['name'] = $name;
							$author 	= $xml->type->detail->author;
								settype($author,"string");$row['author'] = $author;
							$version = $xml->type->detail->version;
								settype($version,"string");$row['version'] = $version;
							$row['path'] 	= $file;
							$row['type'] 	= $type;
							
							array_push($result,$row);
						}
					}
				}
				closedir ( $normal );
			}
		}
		else {
			$file = $path;
			$row = array();
			if (is_file ( $url . "/" . $file ."/index.php" )) {
				
							if(is_file($url. "/" . $file.'/config.xml')) {
								$xml = simplexml_load_file( $url. "/" . $file.'/config.xml' );
							}
							else $xml = array();  	// Empty array
							$title 	= $xml->type->detail->title;
								settype($title,"string");$row['title'] = $title;
							$name 	= $xml->type[0]->detail[0]->name;
								settype($name,"string");
								$row['name'] = $name;
							$author 	= $xml->type->detail->author;
								settype($author,"string");$row['author'] = $author;
							$version = $xml->type->detail->version;
								settype($version,"string");$row['version'] = $version;
			}
			$row['path'] 	= $file;
			$row['type'] 	= $type;
			if($row['name'] == '') $row['name'] = $row['path'];
			$result = $row;
		}
		return $result;
*/
?>