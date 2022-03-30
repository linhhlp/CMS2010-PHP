<?php
if($_POST['host'] == '') {
?> 

<form action="" method="post">
Host<input type=text name="host"><br />
User<input type=text name="user"><br />
Pass<input type=password name="pass"><br />
From DB<input type=text name="fromdb"><br />
From DB prefix<input type=text name="fromdbpr"><br />
To DB<input type=text name="todb"><br />
To DB prefix<input type=text name="todbpr"><br />
Blog Convert<input type=checkbox name="blogconvert" value="1"><br />
Pic Convert<input type=checkbox name="picconvert" value="1"><br />
Music Convert<input type=checkbox name="musicconvert" value="1"><br />
Comment Front Convert<input type=checkbox name="comfrconvert" value="1"><br />
Comement Blog Convert<input type=checkbox name="comblogconvert" value="1"><br />
Frontpage Convert<input type=checkbox name="frontpageconvert" value="1"><br />
Aboutme Convert<input type=checkbox name="aboutmeconvert" value="1"><br />
<input type=submit value="Convert Now" />
</form>

<?php 	
}
else {
	

	$host	= $_POST['host'];
	$user	= $_POST['user'];
	$pass	= $_POST['pass'];
	$from	= $_POST['fromdb'];
	$to		= $_POST['todb'];
	$from_prefix= $_POST['fromdbpr'];
	$to_prefix	= $_POST['todbpr'];
	// COnnect:
	$conn = mysql_connect($host,$user,$pass);
	
	define('INSTALL','convert');
	define('PREFIX',$to_prefix);
	define('NAMEDB',$to);
	include('create_db.php');//install/
	$select_from=mysql_select_db($from,$conn);
	$select_to=mysql_select_db($to,$conn);
	function blog_convert() {
		global $from,$from_prefix,$to,$to_prefix;
		$select_from_query   = 'SELECT * FROM `'.$from.'`.`'.$from_prefix.'blog`';
		$to_insert_query	 = "INSERT INTO `".$to.'`.`'.$to_prefix."blog` (`id`, `title`, `alias_title`, `introtext`, `fulltext`, `created`, `created_by`, `modified`, `modified_by`, `hit`, `category`, `publish`, `frontpage`)	VALUES ";
		$select_result= mysql_query($select_from_query);
		$num = mysql_num_rows($select_result);
		for($i = 1;$i <=$num ; $i++) {
			$row = mysql_fetch_array($select_result);
			$to_insert_query .= '("'.mysql_real_escape_string ($row["id"]).'", "'.mysql_real_escape_string ($row["title"]).'", "", "'.mysql_real_escape_string ($row["descr"]).'", "'.mysql_real_escape_string ($row["content"]).'", "'.mysql_real_escape_string ($row["date_cr"]).'", "1", "'.mysql_real_escape_string ($row["date_md"]).'", "", "'.mysql_real_escape_string ($row["hit"]).'", "1", "'.mysql_real_escape_string ($row["publish"]).'", "'.mysql_real_escape_string ($row["front_page"]).'")';
			if($i != $num) $to_insert_query .= ",";
		}
		mysql_query($to_insert_query);
		if(mysql_error() == '') echo '<br />Convert blog successfully';
		else echo '<br />Convert blog fail';
	}
	function pic_convert() {
		global $from,$from_prefix,$to,$to_prefix;
		$select_from_query   = 'SELECT * FROM `'.$from.'`.`'.$from_prefix.'pic`';
		$to_insert_query	 = "INSERT INTO `".$to.'`.`'.$to_prefix."blog` ( `title`, `alias_title`, `introtext`, `fulltext`, `created`, `created_by`, `modified`, `modified_by`, `hit`, `category`, `publish`, `frontpage`)	VALUES ";
		
		$select_result= mysql_query($select_from_query);
		$num = mysql_num_rows($select_result);
		for($i = 1;$i <=$num ; $i++) {
			$row = mysql_fetch_array($select_result);
			$to_insert_query .= '( "'.mysql_real_escape_string ($row["descr"]).'", "", "'.mysql_real_escape_string ($row["pic"]).' - '.mysql_real_escape_string ($row["descr"]).'", "'.mysql_real_escape_string ($row["name"]).'", "'.mysql_real_escape_string ($row["date"]).'", "1", "0000-00-00 00:00:00", "", "'.mysql_real_escape_string ($row["hit"]).'", "2", "'.mysql_real_escape_string ($row["publish"]).'", "0")';
			if($i != $num) $to_insert_query .= ",";
		}
		mysql_query($to_insert_query);
		if(mysql_error() == '') echo '<br />Convert pic successfully';
		else echo '<br />Convert pic fail';
	}
	function music_convert() {
		global $from,$from_prefix,$to,$to_prefix;
		$select_from_query   = 'SELECT * FROM `'.$from.'`.`'.$from_prefix.'music`';
		$to_insert_query	 = "INSERT INTO `".$to.'`.`'.$to_prefix."blog` ( `title`, `alias_title`, `introtext`, `fulltext`, `created`, `created_by`, `modified`, `modified_by`, `hit`, `category`, `publish`, `frontpage`)	VALUES ";
		
		$select_result= mysql_query($select_from_query);
		$num = mysql_num_rows($select_result);
		for($i = 1;$i <=$num ; $i++) {
			$row = mysql_fetch_array($select_result);
			if(in_array(substr($row["name"],-4), array('.flv','.FLV') )) $link = '[VIDEO]'.$row["name"].'[/VIDEO]';
			elseif(in_array(substr($row["name"],-4), array('.mp3','.MP3') )) $link = '[AUDIO]'.$row["name"].'[/AUDIO]';
			else   $link = '[PLAYLIST]'.$row["name"].'[/PLAYLIST]';
			$to_insert_query .= '( "'.mysql_real_escape_string ($row["ten"]).'", "", "'.mysql_real_escape_string ($row["descr"]).'", "'.mysql_real_escape_string ($link).'", "'.mysql_real_escape_string ($row["date"]).'", "1", "0000-00-00 00:00:00", "", "'.mysql_real_escape_string ($row["hit"]).'", "3", "'.mysql_real_escape_string ($row["publish"]).'", "0")';
			if($i != $num) $to_insert_query .= ",";
		}
		mysql_query($to_insert_query);
		if(mysql_error() == '') echo '<br />Convert music successfully';
		else echo '<br />Convert music fail';
	}
	function comment_frontpage_convert() {
		global $from,$from_prefix,$to,$to_prefix;
		$select_from_query   = 'SELECT * FROM `'.$from.'`.`'.$from_prefix.'comemt`';
		$to_insert_query	 = "INSERT INTO `".$to.'`.`'.$to_prefix."comment` (`title`, `comment`, `type`, `belong`, `date`, `ip`, `user`, `publish`) VALUES";
		
		$select_result= mysql_query($select_from_query);
		$num = mysql_num_rows($select_result);
		for($i = 1;$i <=$num ; $i++) {
			$row = mysql_fetch_array($select_result);
			$to_insert_query .= "('".mysql_real_escape_string ($row["name"])."', '".mysql_real_escape_string ($row["comment"])."', 'Front page', '', '".mysql_real_escape_string ($row["date"])."', '".mysql_real_escape_string ($row["ip"])."', '".mysql_real_escape_string ($row["name"])."', '".mysql_real_escape_string ($row["publish"])."')";
	//		'( "'.mysql_real_escape_string ($row["ten"]).'", "", '".mysql_real_escape_string ($row["descr"])."', "'.mysql_real_escape_string ($row["name"]).'", "'.mysql_real_escape_string ($row["date"]).'", "1", "0000-00-00 00:00:00", "", "'.mysql_real_escape_string ($row["hit"]).'", "3", "'.mysql_real_escape_string ($row["publish"]).'", "0")';
			if($i != $num) $to_insert_query .= ",";
		}
		mysql_query($to_insert_query);
		if(mysql_error() == '') echo '<br />Convert Comment Frontpage successfully';
		else echo '<br />Convert Comment FrontPage fail';
	}
	function comment_blog_convert() {
		global $from,$from_prefix,$to,$to_prefix;
		$select_from_query   = 'SELECT * FROM `'.$from.'`.`'.$from_prefix.'comment`';
		$to_insert_query	 = "INSERT INTO `".$to.'`.`'.$to_prefix."comment` (`title`, `comment`, `type`, `belong`, `date`, `ip`, `user`, `publish`) VALUES";
		
		$select_result= mysql_query($select_from_query);
		$num = mysql_num_rows($select_result);
		for($i = 1;$i <=$num ; $i++) {
			$row = mysql_fetch_array($select_result);
			$to_insert_query .= "('".mysql_real_escape_string ($row["name"])."', '".mysql_real_escape_string ($row["comment"])."', 'blog', '".mysql_real_escape_string ($row["belong"])."', '".mysql_real_escape_string ($row["date"])."', '".mysql_real_escape_string ($row["ip"])."', '".mysql_real_escape_string ($row["name"])."', '".mysql_real_escape_string ($row["publish"])."')";
	//		'( "'.mysql_real_escape_string ($row["ten"]).'", "", '".mysql_real_escape_string ($row["descr"])."', "'.mysql_real_escape_string ($row["name"]).'", "'.mysql_real_escape_string ($row["date"]).'", "1", "0000-00-00 00:00:00", "", "'.mysql_real_escape_string ($row["hit"]).'", "3", "'.mysql_real_escape_string ($row["publish"]).'", "0")';
			if($i != $num) $to_insert_query .= ",";
		}
		mysql_query($to_insert_query);
		if(mysql_error() == '') echo '<br />Convert Comment blog successfully';
		else echo '<br />Convert Comment blog fail';
	}
	function frontpage_convert() {
		global $from,$from_prefix,$to,$to_prefix;
		$select_from_query   = 'SELECT * FROM `'.$from.'`.`'.$from_prefix.'frontpage`';
		$to_insert_query	 = "INSERT INTO `".$to.'`.`'.$to_prefix."frontpage` ( `title`, `tl`, `link`, `descr`, `date`, `publish`) VALUES ";
		
		$select_result= mysql_query($select_from_query);
		$num = mysql_num_rows($select_result);
		for($i = 1;$i <=$num ; $i++) {
			$row = mysql_fetch_array($select_result);
			$to_insert_query .= "('".mysql_real_escape_string ($row["title"])."', '".mysql_real_escape_string ($row["tl"])."',  '".mysql_real_escape_string ($row["link"])."', '".mysql_real_escape_string ($row["descr"])."', '".mysql_real_escape_string ($row["date"])."', '".mysql_real_escape_string ($row["publish"])."')";
			if($i != $num) $to_insert_query .= ",";
		}
		mysql_query($to_insert_query);
		if(mysql_error() == '') echo '<br />Convert FrontPage successfully';
		else echo '<br />Convert FrontPage fail';
	}
	function aboutme_convert() {
		global $from,$from_prefix,$to,$to_prefix;
		$select_from_query   = 'SELECT * FROM `'.$from.'`.`'.$from_prefix.'aboutme`';
		$to_insert_query	 = "INSERT INTO `".$to.'`.`'.$to_prefix."aboutme` ( `user_id`, `info`, `picture`, `aboutme`, `love`, `biogra`, `history`, `lastupdate`) VALUES ";
		
		$select_result= mysql_query($select_from_query);
		$num = mysql_num_rows($select_result);
		for($i = 1;$i <=$num ; $i++) {
			$row = mysql_fetch_array($select_result);
			$to_insert_query .= "('".mysql_real_escape_string ($row["user_id"])."','".mysql_real_escape_string ($row["info"])."','".mysql_real_escape_string ($row["picture"])."','".mysql_real_escape_string ($row["aboutme"])."','".mysql_real_escape_string ($row["love"])."','".mysql_real_escape_string ($row["biogra"])."','".mysql_real_escape_string ($row["history"])."','".mysql_real_escape_string ($row["note"])."')";
			if($i != $num) $to_insert_query .= ",";
		}
		mysql_query($to_insert_query);
		if(mysql_error() == '') echo '<br />Convert Aboutme successfully';
		else echo '<br />Convert Aboutme fail';
	}
	if($_POST['blogconvert'] == "1") blog_convert();
	if($_POST['picconvert'] == "1") pic_convert();
	if($_POST['musicconvert'] == "1") music_convert();
	if($_POST['comfrconvert'] == "1") comment_frontpage_convert();
	if($_POST['comblogconvert'] == "1") comment_blog_convert();
	if($_POST['frontpageconvert'] == "1") frontpage_convert();
	if($_POST['aboutmeconvert'] == "1") aboutme_convert();
	
}