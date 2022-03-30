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
echo 'Userbonus was not used. Find communitylogin instead';die;
/**
 * This is special feature I develop for new Blog.
 * It manages Guest who visit blog, group users: registerd user and guest.
 * Beside that, Some users will have special bonus which was sent from Admin :)
 */
global $_URL; $url = $_URL->getNewUrl(); $ACTION = $url[1];
if($mod_path == false) global $mod_path;
if($_CONFIG == false) global $_CONFIG;
$type_user = $_CONFIG->getPermission();
if(!isset($login) )global $login;
$user_info = $login->getInfo();
?>
<div class="wrapper">
    <div class="userbonus"> 
<?php
if($_SESSION['mobile_type'] == FALSE) { 
	// This is normal display : full capability browser (in windows or Linux ...)
?>
        <div id="mess_userbonus"></div>
	<script type="text/javascript">
	//Define variables
	var welcome_str ='';
	<?php 
			if( !empty($user_info['user_name']))  {
				echo "var logged = 1 \n
						welcome_str=' ".$type_user[$user_info['user_type']].': '.$user_info['user_name']."'";
			}
			else echo 'var logged = 0'; 
			echo "\n";
	?>
	var Result_login = welcome_str;
	var url 	= '<?php echo $mod_path;?>';
	var weburl  = '<?php echo _URL;?>';
	var address = url + '/login.php';
	</script>
<script type="text/javascript" src="<?php echo $mod_path.'/'?>js.js"></script>
<script type="text/javascript">	 
	action_login(Result_login);	
</script>
<?php 
}
else {
	// This is mobile support - special for my kun
	$welcome = ""	; 	// Set default to display login form.
		// login
	$login = new login();
	if( $url[0] == "logout") {
		$login->logout();
		echo "<br /><a href='"._URL."' >Bấm vào đây để trở lại trang chủ </a>";
		break 100;
	}
	else {
	    $_POST['name'] != "" ? $name = $_POST['name'] : $name  = $user_info['user_name'];
	    $_POST['pass'] != "" ? $pass = md5($_POST['pass']) : '' ; //$pass  = $_SESSION['pass']
	    if(!empty($pass)) {
			$login->checkLogin($name,$pass);
		    if($login->getLogged() == TRUE) {
		        $welcome = $type_user[$user_info['user_type']].': '.$user_info['user_name'];
		
		    }
		    else {
		        $error = 'code_return_1';
		    }
	    	
	    }
	    elseif($login->checkUserExist($name) == TRUE) {
	    	$error =  'code_return_3';
	    }
	    else {
	        $_SESSION['user'] = $name;
	        $_SESSION['type'] = '0';
	        $welcome = $type_user[$_SESSION['type']].': '.$_SESSION['user'];
	    }
	}
	if($error != "" || $_SESSION['user'] == "") {
		if($error != "") {
			switch($error) {
				case 'code_return_1';
				echo "Đăng nhập sai tên/mật khẩu";
				break;
				case 'code_return_3';
				echo "Đăng nhập trùng tên thành viên, vui lòng nhập mật khẩu";
				break;
				default;
				echo $error;
				break;
			}
			
		}
	?>
<br />
<form method="post" id="form_userbonus" name="form_userbonus" action="<?php echo _URL; ?>">
<label for="name">Tên của bạn</label><input name="name" value="" type="text" /><br />
Chỉ thành viên mới cần mật khẩu:<input name="pass" value="" type="password" />
<label for="submit"><input type="submit" value="Đăng nhập" name="submit" /></label>
</form>

<?php
	}
	elseif($_SESSION['user'] != "") {
		echo $welcome;
		echo "<br />. " . urlmanager::makeLink("logout","logout","logout","logout" ," Nhấn vào đây để thoát (log-out)");
	}
}
?>
   </div>
</div>