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

if(!isset($_URL)) global $_URL;
$url = $_URL->getNewUrl ();
if($_CONFIG == false) global $_CONFIG;
$type_user = $_CONFIG->getPermission();

if(!isset($com_params)) global $com_params; 
$filemanager =  new filemanager;


?>
<h3>File manager:</h3>
<?php


$url[2] = str_replace(":","/",$url[2]);
if ($url[2] == "/")			$url[2]  = "./";
if($url[2][0] 	!= "." || $url[2][1] 	!= "/") $address = "./"; 
$address .= $url[2] ;

if(is_dir($address) && ($url[1] == "rename" || $url[1] == "delete"))		{
	// if is renaming DIR, so back to its parent
	$new_address    = dirname($address);
	$edit_address = str_replace($new_address."/" ,"",$address );
	// return address of current directory
	$address	    = $new_address ;
}
// If this address is a FILE link, so it return DIR (parent)
if(is_file($address))   {
	// get name of file
	$new_address  = dirname($address);
	$edit_address = str_replace($new_address."/" ,"",$address );
	// return address of current directory
	$address	  = $new_address ;
}	
// If this address is still not a folder, It return home DIR
if( !is_dir($address))	$address = "./";


$back_address = dirname($address);
if($back_address == "." ) $back_address = ":";



echo "Giúp bạn quản lý file trên trang web như : ảnh, video, nhạc ,...<br>";
echo "<a href='".urlmanager::makeLink($url[0],'upload',str_replace("/",":",$address))."'><img src="._URL.IMA."/upload.png alt='upload file'>Upload file lên trang web </a>";
echo "<a href='".urlmanager::makeLink($url[0],'new_folder',str_replace("/",":",$address))."'><img src="._URL.IMA."/new.png alt='new folder'>Tạo thư mục mới</a>";
echo "<a href='".urlmanager::makeLink($url[0],'new_file',str_replace("/",":",$address))."'><img src="._URL.IMA."/file.jpg alt='new file' width=32px>Tạo file mới </a>";
echo "<br>";

if (($url[1]== 'new_file') && (! isset ( $_POST ['file_name'] ))) // Tạo file mới
{
	echo "<form action='".urlmanager::makeLink($url[0],'new_file',str_replace("/",":",$address))."' method=POST>";
	echo '<div align=center><h3>Tạo file mới</h3><input type=text name=file_name>';
	echo '<br>Tên file : <input type=submit value="Tạo"><input type=reset value="Reset">';
	echo '</form></div>';
}
if (($url[1]== 'new_file') && (isset ( $_POST ['file_name'] ))) {
	$file_edit = $address."/".$_POST ['file_name'];
	$create_file = $filemanager->createFile ( $file_edit);
	if ($create_file != FALSE) {
		echo "Tạo mới thành công file: " . $_POST ['file_rename'];
	}
}

if (($url[1]== 'new_folder') && (! isset ( $_POST ['folder_rename'] ))) // Tạo folder mới
{
	echo '<div align=center><h3>Tạo folder mới</h3><form method=post action="' . urlmanager::makeLink($url[0],'new_folder',str_replace("/",":",$address)) . '">
					Tên thư mục<input type=text name=folder_rename size=25><input type=submit value="Tạo mới">
					</form></div>';
}
if (($url[1]== 'new_folder') && (($_POST ['folder_rename'] != ""))) {
	$folder_name = $address."/".$_POST ['folder_rename'];
	$create_folder = $filemanager->createFolder ( $folder_name);
	if ($create_folder != FALSE) {
		echo "Tạo thành công thư mục: " . $_POST ['folder_rename'];
	}
}
if (($url[1] == 'rename')  && (! isset ( $_POST ['file_rename'] ))) {
	echo '<div align=center><h3>Đổi tên cho file ' . $_GET ['f'] . '</h3><form method=post action="' . urlmanager::makeLink($url[0],'rename',str_replace("/",":",$address."/".$edit_address)) . '">
					Tên mới<input type=text name=file_rename size=25 value="' . $edit_address . '"><input type=submit value="Đổi tên">
					</form></div>';
}

if (($url[1] == 'rename') && ($_POST ['file_rename'] != '')) {
	$address_file_old = $address."/".$edit_address;
	$address_file_new = $address . '/' . $_POST ['file_rename'];
	
	$rename = $filemanager->changeName ( $address_file_old, $address_file_new );
	if ($rename != FALSE) {
		echo "Đổi tên thành công từ " . $edit_address. " sang " . $_POST ['file_rename'];
	}
		
}

// Delete a file / directory
if ($url[1] == 'delete' )	{
	$delete  = $filemanager->delete($address."/".$edit_address);
	if ($delete != FALSE) {
		echo "Xóa thành công: " . $edit_address;
	}
}

// Edit a file content 
if (($url[1] == 'edit') && (! isset ( $_POST ['file_content'] ))) {
	// Display form
	$_file_edit = $address . "/" . $edit_address;
	$file_content  = $filemanager->getContent($_file_edit);
	echo "<form action='" . urlmanager::makeLink($url[0],'edit',str_replace("/",":",$address."/".$edit_address)). "' method=POST>";
	echo '<textarea rows=25 name=file_content cols=100>' . $file_content . '</textarea>';
	echo '<br><input type=submit value="Gửi lên"><input type=reset value="Reset">';
	echo '</form>';
}
if (($url[1]  == 'edit') && (isset ( $_POST ['file_content'] )))	{
	// updating...
	$put_content = $filemanager->setContent($address."/".$edit_address,$_POST ['file_content']);
	if($put_content != FALSE) echo "Sửa thành công";
}



// Upload new file
$list_folder = $filemanager->listFolder($address);
if(!empty($list_folder['folder']) )
	foreach ( $list_folder['folder'] as $file ) {
	$thu_muc_cu .= "<option value='".$file['name']."' >".$file['name']."</option>";
	}
	
echo '
			<form name="mainForm" method="POST" action="' . urlmanager::makeLink($url[0],'upload',str_replace("/",":",$address)) . '" enctype="multipart/form-data">
       <div align="center"><h3>Upload lên trang web</h3>
          <table  border=0>
            <tr>
              <td width="23%">Chọn file</td>
              <td width="77%"><input type="file" name="name_file" size="40"><br>
             </td>
            </tr>
            	<tr>
              <td > Chọn thư mục</td><td><input value="goc" name="thu_muc" checked="checked" type="radio">Thư mục gốc (thư mục hiện tại) :' . $address . '<br>
                ';
if ($thu_muc_cu != "")
	echo '
                <input value="cu" name="thu_muc" type="radio"> Thư mục con <select name="thu_muc_cu" >' . $thu_muc_cu . '</select >
                <br> ';

echo '
                <input value="moi" name="thu_muc"  type="radio">Tạo mới thư mục con<input type="text" name="folder" size="20"></td>
            </tr>
            <tr>
              <td colspan="2">
                <p align="center">
              <input name="B2" type="submit" value="Upload">&nbsp;          </td>
            </tr>
              </table>
      </div>
      </form>';
if (($url[1] == 'upload') && (isset ( $_POST ['thu_muc'] ))) // Thực hiện upload
{
	if ($_POST ['thu_muc'] == 'cu')
		$upload = $address."/".$_POST ['thu_muc_cu'];
	elseif (($_POST ['thu_muc'] == 'moi') && ($_POST ['folder'] != ''))
		$upload = $address."/".$_POST ['folder'] ;
	elseif (($_POST ['thu_muc'] == 'moi') && ($_POST ['folder'] != ''))
		$upload = $address."/".$_POST ['folder'] ;
	elseif ($_POST ['thu_muc'] == 'goc')
		$upload = $address;
	if ($upload == '/')
		$upload = '';
	
	if (($_POST ['thu_muc'] == 'moi') && ($_POST ['folder'] != ''))			$filemanager->createFolder ($address."/". $_POST ['folder'] );
	$upload_file = $filemanager->uploadFile($upload);
	if ($upload_file != FALSE) {
		echo "<script>alert('Upload thành công!')</script>";
	} else {
		echo "<script>alert('Upload Lỗi!')</script>";
	}

}


echo '<div align=center>
			<h3>Quản lí file</h3>
			<table border=1 width=80%>';
echo "<tr><td width=3% >&nbsp;</td><td  colspan=5><a href='".urlmanager::makeLink($url[0],'open',str_replace("/",":",$back_address))."'><img src="._URL.IMA."/back.jpg alt='upload file'>Trở lại thư mục trên </a><br></td></tr>";

// list all file and directory
$list_folder = $filemanager->listFolder($address);
if(!empty($list_folder['folder']) )
	foreach ( $list_folder['folder'] as $file ) {
		echo '<tr><td><img src='._URL.'/'.IMA.'/folder.jpg></td>
			<td width=65% ><a href="' .urlmanager::makeLink($url[0],'open', str_replace("/",":",$address."/".$file['name'])) . '">' . $file['name'] . '</a></td>
			<td>Thư mục</td>
		<td><a href="'.urlmanager::makeLink($url[0],'rename', str_replace("/",":",$address."/".$file['name'])) . '">Đổi tên</a></td>
			<td>Sửa</td>
		<td><a href="'.urlmanager::makeLink($url[0],'delete', str_replace("/",":",$address."/".$file['name']))  . '">Xóa</a></td>';
	}
	
if(!empty($list_folder['file']) )
	foreach ( $list_folder['file'] as $file ) {
		echo '<tr><td><img src='._URL.'/'.IMA.'/file.jpg></td>
			 <td><a href="' . $address . '/' . $file['name'] . '" target=_blank>' . $file['name'] . '</a></td>
			  <td> '  . $file['size']  . '   </td>
			 <td><a href="'.urlmanager::makeLink($url[0],'rename', str_replace("/",":",$address."/".$file['name']))  . '">Đổi tên</a></td>
			 <td><a href="'.urlmanager::makeLink($url[0],'edit', str_replace("/",":",$address."/".$file['name'])) . '">Sửa</a></td>
			 <td><a href="'.urlmanager::makeLink($url[0],'delete', str_replace("/",":",$address."/".$file['name'])) . '">Xóa</a></td>';
	}
echo '</table></div>';

?>
