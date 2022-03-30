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
 * This class manages the module, gets and sets modules setting
 */

class managemodule {
	private $module  		= array();
    private $component  	= array();
	private $installmodule  = array();
	private $position 		= array();
	private $plugin 		= array();
	private $mod_id_insert;
	private $pos_id_insert;
	public function getModule($id ='') {
		//  look up in Database only.
		$db = new DBmanager();
		$query = "SELECT * FROM ".PREFIX."module ";
		if($id != '') {
			$id 	= $db->escapeString($id);
			$query .= ' WHERE `id`="'.$id.'" ';
		}
		$query .= " ORDER BY `id_pos`";
		$db->execute($query);
		$num = $db->count();
		if($num == 1 ) {
			$this->module = $db->getRow();
		}
		elseif($num > 1 ) {
			for($i = 0; $i<$num; $i++) {
				$row = $db->getRow();
				if($row['type'] == 'admin') {
					$path 			= ADM.'/'.MOD.'/'.$row['path'].'/index.php';
				}
				else {
					$path 			= MOD.'/'.$row['path'].'/index.php';
				}
					
				if(is_file($path)) 
					 $exist = TRUE;
				else $exist = FALSE;
				$row['exist'] 	= $exist;
				array_push($this->module,$row);
			}
		}
		return $this->module;
	}
    
    public function getComponent($id ='') {
		//  look up in Database only.
		$db = new DBmanager();
		$query = "SELECT * FROM ".PREFIX."component ";
		if($id != '') {
			$id 	= $db->escapeString($id);
			$query .= ' WHERE `id`="'.$id.'" ';
		}
		$db->execute($query);
		$num = $db->count();
		if($num == 1 && $id != '') {
			$this->component = $db->getRow();
		}
		elseif($num >= 1 ) {
			for($i = 0; $i<$num; $i++) {
				$row = $db->getRow();
				if($row['type'] == 'admin') {
					$path 	= ADM.'/'.COM.'/'.$row['path'].'/index.php';
				}
				else {
					$path 	= COM.'/'.$row['path'].'/index.php';
				}
					
				if(is_file($path)) 
					 $exist = TRUE;
				else $exist = FALSE;
				$row['exist'] 	= $exist;
				array_push($this->component,$row);
			}
		}
		return $this->component;
	}
    
	public function getPlugin($id ='') {
		//  look up in Database only.
		$db = new DBmanager();
		$query = "SELECT * FROM ".PREFIX."plugin ";
		if($id != '') {
			$id 	= $db->escapeString($id);
			$query .= ' WHERE `id`="'.$id.'" ';
		}
		$db->execute($query);
		$num = $db->count();
		if($num == 1 && $id != '') {
			$this->plugin = $db->getRow();
		}
		elseif($num >= 1 ) {
			for($i = 0; $i<$num; $i++) {
				$row = $db->getRow();
				if($row['type'] == 'admin') {
					$path 	= ADM.'/'.PLUG.'/'.$row['path'].'/index.php';
				}
				else {
					$path 	= PLUG.'/'.$row['path'].'/index.php';
				}
					
				if(is_file($path)) 
					 $exist = TRUE;
				else $exist = FALSE;
				$row['exist'] 	= $exist;
				array_push($this->plugin,$row);
			}
		}
		return $this->plugin;
	}
	
	public function getPosition($id ='') {
		$db 	= new DBmanager();
		$query  = 'SELECT * FROM '.PREFIX.'position ';
		if($id != '') {
			$id 	= $db->escapeString($id);
			$query .= ' WHERE `id`="'.$id.'" ';
		}
		$db->execute($query);
		$num = $db->count();
		if($num == 1 ) {
			$this->position = $db->getRow();
		}
		elseif($num > 1 ) {
			for($i = 0;$i< $num; $i++) {
				$row = $db->getRow();
				array_push($this->position,$row);
			}
		}
		else $this->position = FALSE;
		return $this->position;
	}
	
	public function setModule ($id,$title = '',$name = '',$id_pos = '',$descr = '',$order = '',$path = '',$type = 'blog',$permission='',$enable = '') {
		$db 	= new DBmanager();
		$id 	= $db->escapeString($id);
		$title 	= $db->escapeString($title);
		$name 	= $db->escapeString($name);
		$id_pos = $db->escapeString($id_pos);
		$descr 	= $db->escapeString($descr);
		$order 	= $db->escapeString($order);
		$path 	= $db->escapeString($path);
		$type 	= $db->escapeString($type);
		$permission = $db->escapeString($permission);
		$enable = $db->escapeString($enable);
		$query  = 'UPDATE '.PREFIX.'module SET  `title`="'.$title.'",
												`name`="'.$name.'",
												`id_pos`="'.$id_pos.'",
												`descr`="'.$descr.'",
												`order`="'.$order.'",
												`path`="'.$path.'",
												`type`="'.$type.'",
												`permission`="'.$permission.'",
												`enable`="'.$enable.'"
		 								WHERE `id`= "'.$id.'"';
		return $db->execute($query);
	}
    
    public function setComponent ($id,$title = '',$path = '',$descr = '',$order = '',$type = 'blog',$default = '',$permission='',$enable = '') {
		$db 	= new DBmanager();
		$id 	= $db->escapeString($id);
		$title 	= $db->escapeString($title);
		$default= $db->escapeString($default);
		$descr 	= $db->escapeString($descr);
		$order 	= $db->escapeString($order);
		$path 	= $db->escapeString($path);
		$type 	= $db->escapeString($type);
		$enable = $db->escapeString($enable);
		$permission = $db->escapeString($permission);
		$query  = 'UPDATE '.PREFIX.'component SET  `title`="'.$title.'",
												`default`="'.$default.'",
												`descr`="'.$descr.'",
												`order`="'.$order.'",
												`path`="'.$path.'",
												`type`="'.$type.'",
												`permission`="'.$permission.'",
												`enable`="'.$enable.'"
		 								WHERE `id`= "'.$id.'"';
		return $db->execute($query);
	}
    
	public function setPlugin ($id,$title = '',$path = '',$descr = '',$order = '',$type = 'blog',$name = '',$permission='',$enable = '') {
		$db 	= new DBmanager();
		$id 	= $db->escapeString($id);
		$title 	= $db->escapeString($title);
		$name	= $db->escapeString($name);
		$descr 	= $db->escapeString($descr);
		$order 	= $db->escapeString($order);
		$path 	= $db->escapeString($path);
		$type 	= $db->escapeString($type);
		$enable = $db->escapeString($enable);
		$permission = $db->escapeString($permission);
		$query  = 'UPDATE '.PREFIX.'plugin SET  `title`="'.$title.'",
												`name`="'.$name.'",
												`descr`="'.$descr.'",
												`order`="'.$order.'",
												`path`="'.$path.'",
												`type`="'.$type.'",
												`permission`="'.$permission.'",
												`enable`="'.$enable.'"
		 								WHERE `id`= "'.$id.'"';
		return $db->execute($query);
	}
	
	public function setPosition ($id,$title = '',$name = '',$descr = '',$order = '',$type = '',$permission='',$enable = '') {
		$db 	= new DBmanager();
		$id 	= $db->escapeString($id);
		$title 	= $db->escapeString($title);
		$name 	= $db->escapeString($name);
		$descr 	= $db->escapeString($descr);
		$order 	= $db->escapeString($order);
		$type 	= $db->escapeString($type);
		$enable = $db->escapeString($enable);
		$permission = $db->escapeString($permission);
		$query  = 'UPDATE '.PREFIX.'position SET  `title`="'.$title.'",
												`name`="'.$name.'",
												`descr`="'.$descr.'",
												`order`="'.$order.'",
												`type`="'.$type.'",
												`permission`="'.$permission.'",
												`enable`="'.$enable.'"
		 								WHERE `id`= "'.$id.'"';
		return $db->execute($query);
	}
	
	public function createModule($title = '',$name = '',$id_pos = '',$descr = '',$order = '',$path = '',$type = 'blog',$permission='',$enable = '') {
		$db 	= new DBmanager();
		$title 	= $db->escapeString($title);
		$name 	= $db->escapeString($name);
		$id_pos = $db->escapeString($id_pos);
		$descr 	= $db->escapeString($descr);
		$order 	= $db->escapeString($order);
		$path 	= $db->escapeString($path);
		$type 	= $db->escapeString($type);
		$permission= $db->escapeString($permission);
		$enable = $db->escapeString($enable);
		if($type  == '') $type = 'blog';
		if($enable  == '') $enable = 1;
		$query  = 'INSERT INTO '.PREFIX.'module 
							(`title`,`name`,`id_pos`,`descr`,`order`,`path`,`type`,`permission`,`enable`) 
					VALUES ("'.$title.'","'.$name.'","'.$id_pos.'","'.$descr.'","'.$order.'","'.$path.'","'.$type.'","'.$permission.'","'.$enable.'") ';
		$suc = $db->execute($query);
		//echo $db->error();
			if($suc != FALSE ) {
				return  $db->insertId();
			}
			else return FALSE;
	}
    
    public function createComponent($title = '',$path = '',$descr = '',$order = '',$type = 'blog',$default = '',$permission='',$enable = '') {
		$db 	= new DBmanager();
		$title 	= $db->escapeString($title);
		$default= $db->escapeString($default);
		$descr 	= $db->escapeString($descr);
		$order 	= $db->escapeString($order);
		$path 	= $db->escapeString($path);
		$type 	= $db->escapeString($type);
		$enable = $db->escapeString($enable);
		$permission= $db->escapeString($permission);
		if($type  == '') $type = 'blog';
		if($enable  == '') $enable = 1;
		$query  = 'INSERT INTO '.PREFIX.'component 
							(`title`,`default`,`descr`,`order`,`path`,`type`,`permission`,`enable`) 
					VALUES ("'.$title.'","'.$default.'","'.$descr.'","'.$order.'","'.$path.'","'.$type.'","'.$permission.'","'.$enable.'") ';
		$suc = $db->execute($query);
		//echo $db->error();
			if($suc != FALSE ) {
				return  $db->insertId();
			}
			else return FALSE;
	}
    
	public function createPlugin($title = '',$path = '',$descr = '',$order = '',$type = 'blog',$name = '',$permission='',$enable = '') {
		$db 	= new DBmanager();
		$title 	= $db->escapeString($title);
		$name	= $db->escapeString($name);
		$descr 	= $db->escapeString($descr);
		$order 	= $db->escapeString($order);
		$path 	= $db->escapeString($path);
		$type 	= $db->escapeString($type);
		$enable = $db->escapeString($enable);
		$permission= $db->escapeString($permission);
		if($type  == '') $type = 'blog';
		if($enable  == '') $enable = 1;
		$query  = 'INSERT INTO '.PREFIX.'plugin 
							(`title`,`name`,`descr`,`order`,`path`,`type`,`permission`,`enable`) 
					VALUES ("'.$title.'","'.$name.'","'.$descr.'","'.$order.'","'.$path.'","'.$type.'","'.$permission.'","'.$enable.'") ';
		$suc = $db->execute($query);
		//echo $db->error();
			if($suc != FALSE ) {
				return  $db->insertId();
			}
			else return FALSE;
	}
	
	public function createPosition ($title='',$name,$descr = '',$order = '',$type ='',$permission='',$enable ='') {
		$db 	= new DBmanager();
		$title 	= $db->escapeString($title);
		$name 	= $db->escapeString($name);
		$descr 	= $db->escapeString($descr);
		$order 	= $db->escapeString($order);
		$type 	= $db->escapeString($type);
		$permission= $db->escapeString($permission);
		$enable = $db->escapeString($enable);
		if($name =='') return FALSE;
		else {
			$query  = 'INSERT INTO '.PREFIX.'position   
									(`title`,`name`,`descr`,`order`,`type`,`permission`,`enable`) 
									VALUES ("'.$title.'","'.$name.'","'.$descr.'","'.$order.'","'.$type.'","'.$permission.'","'.$enable.'")
													';
			$suc = $db->execute($query);
			if($suc != FALSE ) {
				return  $db->insertId();
			}
			else return FALSE;
		}
		
	}
	public function deleteModule($id) {
		if($id == '') {
			return FALSE;
		}
		else {
			$db 	= new DBmanager();
			$id 	= $db->escapeString($id);
			$query  = 'DELETE FROM '.PREFIX.'module WHERE `id`="'.$id.'" LIMIT 1';
			return $db->execute($query);
		}
	}
    
    public function deleteComponent($id) {
		if($id == '') {
			return FALSE;
		}
		else {
			$db 	= new DBmanager();
			$id 	= $db->escapeString($id);
			$query  = 'DELETE FROM '.PREFIX.'component WHERE `id`="'.$id.'" LIMIT 1';
			return $db->execute($query);
		}
	}
	public function deletePlugin($id) {
		if($id == '') {
			return FALSE;
		}
		else {
			$db 	= new DBmanager();
			$id 	= $db->escapeString($id);
			$query  = 'DELETE FROM '.PREFIX.'plugin WHERE `id`="'.$id.'" LIMIT 1';
			return $db->execute($query);
		}
	}
	public function deletePosition($id) {
		if($id == '') {
			return FALSE;
		}
		else {
			$db 	= new DBmanager();
			$id 	= $db->escapeString($id);
			$query  = 'DELETE FROM '.PREFIX.'position WHERE `id`="'.$id.'" LIMIT 1';
			return $db->execute($query);
		}
	}
	
	public function getInstallModule($type = '',$path ='') {
		/**
		 * This function lists all modules in directory MODULE
		 */
		$result = array();
		if($path == '') {
			$result = array_merge($this->loopInstallModule('admin'),$this->loopInstallModule());
		}
		else {
			$result = $this->loopInstallModule($type ,$path);
		}
		return $result;
	}
    public function getInstallComponent($type = '',$path ='') {
		/**
		 * This function lists all Components in directory component
		 */
		$result = array();
		if($path == '') {
			$result = array_merge($this->loopInstallComponent('admin'),$this->loopInstallComponent());
		}
		else {
			$result = $this->loopInstallComponent($type ,$path);
		}
		return $result;
	}
    
	public function getInstallPlugin($type = '',$path ='') {
		/**
		 * This function lists all Plugins in directory plugin
		 */
		$result = array();
		if($path == '') {
			$result = array_merge($this->loopInstallPlugin('admin'),$this->loopInstallPlugin());
		}
		else {
			$result = $this->loopInstallPlugin($type ,$path);
		}
		return $result;
	}
	
	/**
	 * This function will loop follow type input.
	 * It only is accessed in this class.
	 * @param  $type
	 * @param  $path
	 */
	private function loopInstallModule($type = '',$path = '') {
		if($type == 'admin') $url = ADM.'/'.MOD;
		else $url = MOD;
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
							$name 	= $xml->type->detail->name;
								settype($name,"string");$row['name'] = $name;
							$params = $xml->type->install->params;
								settype($params,"string");$row['params'] = $params;
							$author = $xml->type->detail->author;
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
							$name 	= $xml->type->detail->name;
								settype($name,"string");
								$row['name'] = $name;
							$author 	= $xml->type->detail->author;
								settype($author,"string");$row['author'] = $author;
							$params = $xml->type->install->params;
								settype($params,"string");$row['params'] = $params;
							$version = $xml->type->detail->version;
								settype($version,"string");$row['version'] = $version;
			}
			$row['path'] 	= $file;
			$row['type'] 	= $type;
			if($row['name'] == '') $row['name'] = $row['path'];
			$result = $row;
		}
		return $result;
	}
	
    /**
	 * This function will loop follow type input.
	 * It only is accessed in this class.
	 * @param  $type
	 * @param  $path
	 */
	private function loopInstallComponent($type = '',$path = '') {
		if($type == 'admin') $url = ADM.'/'.COM;
		else $url = COM;
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
							$name 	= $xml->type->detail->name;
								settype($name,"string");$row['name'] = $name;
							$params = $xml->type->install->params;
								settype($params,"string");$row['params'] = $params;
							$author = $xml->type->detail->author;
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
							$name 	= $xml->type->detail->name;
								settype($name,"string");
								$row['name'] = $name;
							$author 	= $xml->type->detail->author;
								settype($author,"string");$row['author'] = $author;
							$params = $xml->type->install->params;
								settype($params,"string");$row['params'] = $params;
							$version = $xml->type->detail->version;
								settype($version,"string");$row['version'] = $version;
			}
			$row['path'] 	= $file;
			$row['type'] 	= $type;
			if($row['name'] == '') $row['name'] = $row['path'];
			$result = $row;
		}
		return $result;
	}
    
/**
	 * This function will loop follow type input.
	 * It only is accessed in this class.
	 * @param  $type
	 * @param  $path
	 */
	private function loopInstallPlugin($type = '',$path = '') {
		if($type == 'admin') $url = ADM.'/'.PLUG;
		else $url = PLUG;
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
							$name 	= $xml->type->detail->name;
								settype($name,"string");$row['name'] = $name;
							$params = $xml->type->install->params;
								settype($params,"string");$row['params'] = $params;
							$author = $xml->type->detail->author;
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
							$name 	= $xml->type->detail->name;
								settype($name,"string");
								$row['name'] = $name;
							$author 	= $xml->type->detail->author;
								settype($author,"string");$row['author'] = $author;
							$params = $xml->type->install->params;
								settype($params,"string");$row['params'] = $params;
							$version = $xml->type->detail->version;
								settype($version,"string");$row['version'] = $version;
			}
			$row['path'] 	= $file;
			$row['type'] 	= $type;
			if($row['name'] == '') $row['name'] = $row['path'];
			$result = $row;
		}
		return $result;
	}
}

?>