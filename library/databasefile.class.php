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
 * Manage files
 */

class databasefile {
	public function uploadFile() {
		if($_FILES['name_file']['name'] != "")	 $name_file = $this->processFile('name_file');
		else $name_file = array();
		if($_FILES['image_file']['name'] != "")	 $image_file = $this->processFile('image_file');
		else $image_file = array();
		if($_FILES['preview_file']['name'] != "")$preview_file = $this->processFile('preview_file');
		else $preview_file = array();
		if($_FILES['viewonline']['name'] != "") $viewonline = $this->processFile('viewonline');
		else $preview_file = array();
		$db 		= new DBmanager();
		$descr 		= $db->escapeString($_POST['descr']);
		$title 		= $db->escapeString($_POST['title']);
		$price 		= $db->escapeString($_POST['price']);
		$category   = $db->escapeString($_POST['category']);
		$extension  = $db->escapeString($_POST['extension']);
		$author  	= $db->escapeString($_POST['author']);
		$factory  	= $db->escapeString($_POST['factory']);
		$yearpublish= $db->escapeString($_POST['yearpublish']);
		$series	    = $db->escapeString($_POST['series']);
		
		$query  = "INSERT INTO `".PREFIX."files` (`name`,`extension`, `size`, `type`, `content`,`viewonline`,`descr`,`title`,`price`,`category`,`author`,`factory`,`yearpublish`,`series`,`uploader`,`settings`, `image_size`, `image_type`, `image_content`, `preview_size`, `preview_type`, `preview_content` )  
			VALUES ('$name_file[0]','$extension', '$name_file[1]', '$name_file[2]', '$name_file[3]','$viewonline[3]','$descr','$title','$price','$category','$author','$factory','$yearpublish','$series','".$_SESSION['info']['id']."','1', '$image_file[1]', '$image_file[2]', '$image_file[3]', '$preview_file[1]', '$preview_file[2]', '$preview_file[3]')";		
		$result = $db->execute($query);
		//echo $db->error();
		return  $result; 

	}
	/**
	 * 
	 * This function processes a upload file to string to put in Database  
	 * @param string $file_name : name file process 
	 * @return array $result	: 0: name	1:size	2:type	3:content
	 */
	private function processFile($file_name) {
		$fileName = $_FILES[$file_name]['name'];
		$tmpName  = $_FILES[$file_name]['tmp_name'];
		$fileSize = $_FILES[$file_name]['size'];
		$fileType = $_FILES[$file_name]['type'];
		
		$fp      = fopen($tmpName, 'r');
		$content = fread($fp, filesize($tmpName));
		$content = addslashes($content);
		fclose($fp);
		if(!get_magic_quotes_gpc())
		{
		    $fileName = addslashes($fileName);
		}
		$result = array($fileName,$fileSize,$fileType,$content);
		return $result;
	}
	
	/**
	 * 
	 * Get a file content: content, image or preview
	 * @param int $id 		: id of request file
	 * @param string $type	: content, image, preview
	 */
	public function getAfile($id,$type){
		// return content of a request file
		$db 	= new DBmanager();
		$id 	= $db->escapeString($id);
		if($id >0 ) {
			$type 	= $db->escapeString($type);
			$query  = "SELECT ";
			if($type == "image") {
				$query  .= " `image_size`,`image_type`,`image_content` ";		
			}
			elseif($type == "preview") {
				$query  .= " `preview_size`,`preview_type`,`preview_content` ";		
			}
			elseif($type == "content") {
				$query  .= " `name`,`size`,`type`,`content`,`download` ";		
			}
			elseif($type == "viewonline") {
				$query  .= " `viewonline` ";		
			}
			else return FALSE;
			$query  .= " FROM `".PREFIX."files` WHERE `id`='$id'";
			$result = $db->execute($query);
			$row	= $db->getRow();
			if($type == "image") {
				$row['size'] = $row['image_size'];
				$row['type'] = $row['image_type'];
				$row['content'] = $row['image_content'];		
			}
			elseif($type == "preview") {
				$row['size'] = $row['preview_size'];
				$row['type'] = $row['preview_type'];
				$row['content'] = $row['preview_content'];		
			}
			elseif($type == "viewonline") {
				$row['content'] = $row['viewonline'];		
			}
			if($type == "content" && $row != FALSE ) {
				// It means that this is downloading process
				$download = $row['download'] + 1;
				$query_update	 = "UPDATE  `".PREFIX."files`  SET `download`='$download' WHERE `id`='$id'";
				$db->execute($query_update);
			}
			return $row;
		}
		else return FALSE;
	}
	public function getFiles($id='',$orderby='created',$order='DESC',$limitfrom=0,$limit=20,$extension='',$category='',$sizefrom='',$sizeto='',$pricefrom='',$priceto='',$viewfrom='',$viewto='',$downloadfrom='',$downloadto='',$author='',$factory='',$yearpublish='',$series='',$uploader='',$settings=''){
		$db 		= new DBmanager();
		$id 		= $db->escapeString($id);
		$extension 	= $db->escapeString($extension);
		$orderby 	= $db->escapeString($orderby);
		$order	 	= $db->escapeString($order); //ASC  or DESC
		$limitfrom 	= $db->escapeString($limitfrom);
		$limit	 	= $db->escapeString($limit);
		$category 	= $db->escapeString($category);
		$sizefrom 	= $db->escapeString($sizefrom);
		$sizeto 	= $db->escapeString($sizeto);
		$pricefrom 	= $db->escapeString($pricefrom);
		$priceto 	= $db->escapeString($priceto);
		$author 	= $db->escapeString($author);
		$factory 	= $db->escapeString($factory);
		$yearpublish= $db->escapeString($yearpublish);
		$series 	= $db->escapeString($series);
		$uploader 	= $db->escapeString($uploader);
		$settings 	= $db->escapeString($settings);
		$viewfrom 	= $db->escapeString($viewfrom);
		$viewto 	= $db->escapeString($viewto);
		$downloadfrom=$db->escapeString($downloadfrom);
		$downloadto = $db->escapeString($downloadto);
		$query		= "SELECT  	`id`, `name`, `extension`, `size`, `type`, `title`, `descr`, `category`, `price`,`author`,`factory`,`yearpublish` ,`series` ,`created` ,`uploader` ,`view` ,`download` ,`settings` FROM `".PREFIX."files` ";
		$select		= $query;
		if($id != '')  			$query .= ($select==$query?' WHERE ':' AND ').'  `id` 		 = "'.$id.'" ';
		if($extension != '')  	$query .= ($select==$query?' WHERE ':' AND ').'  `extension` = "'.$extension.'" ';
		if($category != '')  	$query .= ($select==$query?' WHERE ':' AND ').'  `category`  = "'.$category.'" ';
		if($author != '')  		$query .= ($select==$query?' WHERE ':' AND ').'  `author` 	 = "'.$author.'" ';
		if($factory != '')  	$query .= ($select==$query?' WHERE ':' AND ').'  `factory`	 = "'.$factory.'" ';
		if($yearpublish != '')  $query .= ($select==$query?' WHERE ':' AND ').'  `yearpublish` = "'.$yearpublish.'" ';
		if($series != '')  		$query .= ($select==$query?' WHERE ':' AND ').'  `series`	= "'.$series.'" ';
		if($uploader != '')  	$query .= ($select==$query?' WHERE ':' AND ').'  `uploader` = "'.$uploader.'" ';
		if($settings != '')  	$query .= ($select==$query?' WHERE ':' AND ').'  `settings` = "'.$settings.'" ';
		if($sizefrom != '')  {
			if($sizeto != '') 	$query .= ($select==$query?' WHERE ':' AND ')."HAVING (`size` BETWEEN  '$sizefrom'  AND '$sizeto' )";
			else				$query .= ($select==$query?' WHERE ':' AND ')." `size` =  '$sizefrom' ";
		}
		if($pricefrom != '')  {
			if($priceto != '')  $query .= ($select==$query?' WHERE ':' AND ')."HAVING (`price` BETWEEN  '$pricefrom'  AND '$priceto' )";
			else				$query .= ($select==$query?' WHERE ':' AND ')." `price` =  '$pricefrom' ";
		}
		if($viewfrom != '')  {
			if($viewto != '')  $query .= ($select==$query?' WHERE ':' AND ')."HAVING (`view` BETWEEN  '$viewfrom'  AND '$viewto' )";
			else			   $query .= ($select==$query?' WHERE ':' AND ')." `view` =  '$viewfrom' ";
		}
		if($downloadfrom != '')  {
			if($downloadto !='')$query .= ($select==$query?' WHERE ':' AND ')."HAVING (`download` BETWEEN  '$downloadfrom'  AND '$priceto' )";
			else				$query .= ($select==$query?' WHERE ':' AND ')." `download` =  '$downloadfrom' ";
		}
		$query	.= " 	ORDER BY `$orderby` $order  LIMIT $limitfrom,$limit";
		// execute the Database request
		$result = $db->execute($query);
		$num = $db->count();
		if($num > 0) {
			$results = array();
			for($i=0; $i<$num; $i++) {
				$row = $db->FetchResult($result);
				$row['size'] = $this->roundSize($row['size']);
				$results[] = $row;
				if($id != '' ) {
					// Update Hit
					$view = $row['view'] + 1;
					$query_update	 = "UPDATE  `".PREFIX."files`  SET `view`='$view' WHERE `id`='$id'";
					$db->execute($query_update);
				}
			}
			return $results;
		}
		else return FALSE;	

	}
	/**
	 * 
	 * Convert from byte to KB, MB, GB ...
	 * @param int $dl_byte : capacity of file by byte
	 */
	public function roundSize($dl_byte) {
		if ($dl_byte < 1023)
			$dl_cal = $dl_byte . ' bytes';
		elseif ($dl_byte < (1023 * 1024))
			$dl_cal = round ( $dl_byte / 1024, 1 ) . ' KB'; // Quy ra m?c cao hon v� l�m tr�n
		elseif ($dl_byte < (1023 * 1024 * 1024))
			$dl_cal = round ( $dl_byte / (1024 * 1024), 1 ) . ' MB';
		elseif ($dl_byte < (1023 * 1024 * 1024 * 1024))
			$dl_cal = round ( $dl_byte / (1024 * 1024 * 1024), 1 ) . ' GB';
		return $dl_cal;
	}
	
	/**
	 * 
	 * Get information about all categories ...
	 * @param int $id		: if we only want to find a category
	 * @param string $listby:
	 * @param string $list: if it is set, it will return an array with key is $listby and value is $list
	 * for example: if $list = 'title' and $listby='id' ->  $value[$id] = 'title value'
	 */
	public function getCategory($id='',$list='',$listby='') {
		$query	= "SELECT * FROM `".PREFIX."filecategory` ";
		$db 	= new DBmanager();
		$id 	= $db->escapeString($id);
		if($id != '') $query .= " WHERE `id`='".$id."' " ;
		$result = $db->execute($query);
		$num = $db->count();
		if($num > 0) {
			$results = array();
			for($i=0; $i<$num; $i++) {
				$row = $db->FetchResult($result);
				if($listby != '') {
					$results[$row[$listby]] = $row[$list];
				}
				else $results[] = $row;
			}
			return $results;
		}
		else return FALSE;
	}
	public function viewonline($content,$page) {
		// cut content by page
		return $content;
	}	
}