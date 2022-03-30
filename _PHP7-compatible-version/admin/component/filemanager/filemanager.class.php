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

class filemanager {
	public function listFolder($url,$order_by = "Abc") {
		$list	 = scandir ( $url, 0 );
		$result	 = array();
		// Natural order sorting (case-insensitive) - không phân biệt chữ hoa, thường
		if($order_by == "Abc" ) natcasesort ( $list );
		$i = 0;$j = 0;
		foreach ( $list as $file ) {
			if ($file != "." && $file != "..") {
				if (is_file ( $url . "/" . $file )) {
					// TYPE : file
					$result['file'][$i]['name'] = $file;
					$result['file'][$i]['size'] = $this->getFileSize($url . "/" . $file);
					$i++;
				}
				else {
					// TYPE : Folder
					$result['folder'][$j]['name'] = $file;
					$j++;
				}
			}
		}
		return $result;

	}
	
	public function changeName($url_file_old , $url_file_new) {
		if (( !is_dir ( $url_file_new )) && ( !is_file ( $url_file_new ))) {
			return rename ( $url_file_old, $url_file_new );
		}
		else return FALSE;
	}
	
	public function createFile($file_edit) {
		// returns the number of bytes that were written to the file, or FALSE on failure.
		if (! is_file ( $file_edit )) {
			return file_put_contents ( $file_edit, '' );
		} else return FALSE;
	
	}
	
	public function createFolder($folder,$chmod="0750") {
		if (! is_dir ( $folder ))
			return mkdir ( $folder, $chmod );
		else	return FALSE;
	}
	
	public function delete($del_url) {
		if (is_file ( $del_url )) {
			if (unlink ( $del_url ))
				return TRUE;
			else
				return FALSE;
		}
		elseif (is_dir ( $del_url )) {
			if (rmdir ( $del_url ))
				return TRUE;
			else
				return FALSE;
			
		}
		else return FALSE;
	}
	
	public function getContent($_file_edit,$encode= TRUE,$quote_style = ENT_COMPAT , $charset = "ISO-8859-1", $double_encode= TRUE) {
		/**
		* $quote_style, tells the function what to do with single and double quote characters. 
		* The default mode, ENT_COMPAT, is the backwards compatible mode which only translates the double-quote character and leaves the single-quote untranslated. 
		* If ENT_QUOTES is set, both single and double quotes are translated and
		* if ENT_NOQUOTES is set neither single nor double quotes are translated. 
		*/
		
		$file_edit = file_get_contents ( $_file_edit );
		if($encode == TRUE ) $file_edit = htmlspecialchars($file_edit,$quote_style  , $charset , $double_encode);
		return $file_edit;
	}
	
	public function setContent($_file_edit,$content,$decode= TRUE,$quote_style =ENT_COMPAT, $charset  = "UTF-8") {
		/**
		* The converted entities are: &amp;, &quot; (when ENT_NOQUOTES is not set), &#039; (when ENT_QUOTES is set), &lt; and &gt;. 	 
		*/
		
		if($decode == TRUE ) $content =html_entity_decode ( $content,$quote_style , $charset);
		file_put_contents ( $_file_edit, $content );
	}
	
	public function getFileSize($file_path,$round = TRUE) { 
		// Get filezize by rounding byte, KB,, MB or GB
		$dl_byte = filesize ( $file_path );
		if($round == TRUE ) {
			if ($dl_byte < 1023)
				$dl_cal = $dl_byte . ' bytes';
			elseif ($dl_byte < (1023 * 1024))
				$dl_cal = round ( $dl_byte / 1024, 1 ) . ' KB'; // Quy ra m?c cao hon v� l�m tr�n
			elseif ($dl_byte < (1023 * 1024 * 1024))
				$dl_cal = round ( $dl_byte / (1024 * 1024), 1 ) . ' MB';
			elseif ($dl_byte < (1023 * 1024 * 1024 * 1024))
				$dl_cal = round ( $dl_byte / (1024 * 1024 * 1024), 1 ) . ' GB';
		}
		return $dl_cal;
	}
	public function uploadFile($path,$overwrite = FALSE) {
		$fname = $_FILES ['name_file'] ['name'];
		$tmp_name = $_FILES ['name_file'] ['tmp_name'];
		if($overwrite == FALSE ) {
			$handle = opendir ( $path );
			if ($handle) {
				while ( false !== ($file = readdir ( $handle )) ) {
					if ($file != "." && $file != "..") {
						if ((! is_dir ( $path . "/" . $file )) && ($file == $fname)) {
							echo "<script>alert('Trùng tên với 1 file,vui lòng đổi tên hoặc xóa file cũ')</script>";
							return FALSE;
						}
					}
				}
				closedir ( $handle );
			}
		}
		
		if (copy ( $tmp_name, $path."/".$fname )) return TRUE;
		else return FALSE;
		
	}
	
}

?>