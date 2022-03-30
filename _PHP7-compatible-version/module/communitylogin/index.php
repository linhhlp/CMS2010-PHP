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
 * This is special feature I develop for new Blog.
 * It manages Guest who visit blog, group users: registerd user and guest.
 * Beside that, Some users will have special bonus which was sent from Admin :)
 */
global $_URL; $url = $_URL->getNewUrl(); $ACTION = $url[1];
if( !isset($mod_path)) global $mod_path;
if( !isset($_CONFIG)) global $_CONFIG;
$type_user = $_CONFIG->getPermission();
if(!isset($login) )global $login;
$user_info = $login->getInfo();
$cookies = loadCookie();
// setting: set PREFIX for ajax load
if(!isset($_SESSION['socialnetwork'][PREFIX]['privatekey']))	{
	// create a private key to check you are in this blog or not
	// to get exact Prefix DB
	$possible = '23456789bcdfghjkmnpqrstvwxyz';
	$code = '';
	$i = 0;
	while ($i < $characters) { 
		$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
		$i++;
	}
	$_SESSION['socialnetwork'][PREFIX]['privatekey'] = $code;	
}
?>

    <div class="userbonus"> 
<?php
if($_SESSION['mobile_type'] == FALSE) { 
	// This is normal display : full capability browser (in windows or Linux ...)
?>
<div ></div>
        <div id="mess_userbonus"></div>
		<br />
		<div id="comment_news_userbonus"></div>
		<?php if($url[1] != 'new') {	?>
		<div id="friend_news_userbonus"></div>
		<?php } ?>
	<script type="text/javascript">
	//Define variables
	var welcome_str ='';
	<?php 
			if( !empty($user_info['user']))  {	
				if( $user_info['id'] > 0) { $type_user_display = $type_user[$user_info['type']];$code = 0;}
				else { $type_user_display = $user_info['id']; $code = 2;}
				if($type_user_display === 0 || $type_user_display === '0') $type_user_display = $type_user[0];
				echo "var logged = 1 \n welcome_str='<?xml version=\"1.0\" encoding=\"UTF-8\" ?><community><code>".$code."</code><type>".$type_user_display.'</type><name>'.$user_info['user']."</name></community>'";
			}
			else {
				
				echo 'var logged = 0'; 
				
				// yêu cầu Xác thực
				
				
				if( $cookies['captcha'] != md5( 'X307'.'Kh@ch') ) {
					$cau_hoi = 'Xác thực: Go~ thu? do^ Ha` no^i Kho^ng da^u';
					//$cau_hoi = 'Xác thực ko phải spam\n'.$cau_hoi.'\n'.'Không dấu';
				}
			}
			echo "\n";
	?>
	//alert(welcome_str);
	var captcha = '<?php echo $cau_hoi;?>';
	var Result_login = welcome_str;
	var url 	= '<?php echo $mod_path;?>';
	var weburl  = '<?php echo _URL;?>';
	var fullweburl = '<?php echo $_URL->getOldUrl();?>';
	var address = url + '/communitylogin.php';
	var session_id =  '<?php echo session_id(); ?>';
	var prefix  = '<?php echo PREFIX; ?>';
	var privatekey= '<?php echo $_SESSION['socialnetwork'][PREFIX]['privatekey'] ?>';
	var loading_str = '<image src="'+url+'/loading.gif" />';
	</script>
<script type="text/javascript" src="<?php echo $mod_path.'/'?>js.js"></script>
<script type="text/javascript">	 
	action_login(Result_login);	
	xacthuc( captcha );
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
		return;
	}
	else {
	   $name = $_POST['name'] ;
	   $pass = md5($_POST['pass']);
	    if(!empty($pass)) {
			$arr = array('user'=>$name,'pass'=>$pass);
			$login->checkLogin($arr);
		    if($login->getLogged() == TRUE) {
		    	$user_info = $login->getInfo();
		        $welcome = $type_user[$user_info['type']].': '.$user_info['name'];
		
		    }
		    else {
		        $error = 'code_return_1';
		    }
	    	
	    }
	    elseif($login->checkUserExist($name) == TRUE) {
	    	$error =  'code_return_3';
	    }
	    else {
	    	$login->LogRegistry($name,0,0);
	        $welcome = $type_user[$user_info['type']].': '.$user_info['user'];
	    }
	}
	if($error != "" || $user_info['user'] == "") {
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
	elseif($user_info['user'] != "") {
		echo $welcome;
		echo "<br />. " . urlmanager::makeLink("logout","logout","logout","logout" ," Nhấn vào đây để thoát (log-out)");
	}
}
?>

</div>