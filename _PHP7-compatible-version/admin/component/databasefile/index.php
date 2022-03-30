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
$databasefile =  new databasefile;

echo "Giúp bạn quản lý file upload vào DataBase như : ảnh, tài liệu,...<br>";
echo "<a href='".urlmanager::makeLink($url[0],'upload',"")."'><img src="._URL.IMA."/upload.png alt='upload file'>Upload a file</a><br />";
echo "<a href='".urlmanager::makeLink($url[0],'list','')."'><img src="._URL.IMA."/new.png alt='list'>Xem danh sách file đã upload</a>";
//echo "<a href='".urlmanager::makeLink($url[0],'new_file',str_replace("/",":",$address))."'><img src="._URL.IMA."/file.jpg alt='new file' width=32px>Tạo file mới </a>";
echo "<br>";
if($url[1] == "upload" && $_POST["title"] != "") {
	$upload_file = $databasefile->uploadFile();
	if ($upload_file != FALSE) {
		echo "<script>alert('Upload thành công!')</script>";
	} else {
		echo "<script>alert('Upload Lỗi!')</script>";
	}
}
elseif($url[1] == "upload") {

	echo textEdittor('exact','descr','advanced');
	echo '
			<form name="mainForm" method="POST" action="' . urlmanager::makeLink($url[0],'upload',str_replace("/",":",$address)) . '" enctype="multipart/form-data">
       <div align="center"><h3>Upload lên trang web</h3>
          <table  border="0" width="100%">
          	<tr>
              <td width="15%">Tiêu đề </td>
              <td><input type="text" name="title" size="20"><br>
             </td>
            </tr>
            <tr>
              <td>Giá cả</td>
              <td><input type="text" name="price" size="20"><br>
             </td>
            </tr>
            <tr>
              <td>Thể loại</td>
              <td><input type="text" name="category" size="20"><br>
             </td>
            </tr>
            <tr>
              <td>Tác giả</td>
              <td><input type="text" name="author" size="20"><br>
             </td>
            </tr>
            <tr>
              <td>Nhà phát hành</td>
              <td><input type="text" name="factory" size="20"><br>
             </td>
            </tr>
            <tr>
              <td>Năm phát hành</td>
              <td><input type="text" name="yearpublish" size="20"><br>
             </td>
            </tr>
            <tr>
              <td>Mã sơ-ri</td>
              <td><input type="text" name="series" size="20"><br>
             </td>
            </tr>
            <tr>
              <td>Giới thiệu ngắn</td>
              <td><textarea name="descr"  rows="15" cols="75" id="fulltext"></textarea><br>
             </td>
            </tr>
            <tr>
              <td>Chọn file</td>
              <td><input type="file" name="name_file" size="40"><br>
             </td>
            </tr>
            <tr>
              <td>Phần mở rộng (pdf,doc,chm,...)</td>
              <td><input type="text" name="extension" size="20"><br>
             </td>
            </tr>
            <tr>
              <td>Online content (HTML)</td>
              <td><input type="file" name="viewonline" size="40"><br>
             </td>
            </tr>
            <tr>
            <tr>
              <td>Chọn ảnh đại diện</td>
              <td><input type="file" name="image_file" size="40"><br>
             </td>
            </tr>
            <tr>
              <td>Chọn file trích dẫn</td>
              <td><input type="file" name="preview_file" size="40"><br>
             </td>
            </tr>
            <tr>
              <td colspan="2">
                <p align="center">
              <input name="B2" type="submit" value="Upload">&nbsp;          </td>
            </tr>
              </table>
      </div>
      </form>';
}
elseif($url[1] == "list") {
	$files = $databasefile->getFiles();
	echo "<table border='1' width='100%' align='center'><tr><td width='5%' >STT</td><td width='15%'>Tiêu đề</td><td>Tên file</td><td>Dung lượng</td><td>Đuôi</td><td>Mục</td><td>Giá</td><td>Tác giả</td><td>Uploader</td><td>Xem</td><td>Download</td></tr>";
	$i = 1;
	$category	= $databasefile->getCategory('','name','id');
	$user		= user::getUserName('','id');
	foreach ($files as $key=>$value ) {
		echo "<tr><td width='5%' > $i </td><td width='15%'>".$value['title']."</td><td>".$value['name']."</td><td>".$value['size']."</td><td>".$value['extension']."</td><td>".$category[$value['category']]."</td><td>".$value['price']."</td><td>".$value['author']."</td><td>".$user[$value['uploader']]."</td><td>".$value['view']."</td><td>".$value['download']."</td></tr>";
		$i++;
	}
	echo "</table>";
}

?>