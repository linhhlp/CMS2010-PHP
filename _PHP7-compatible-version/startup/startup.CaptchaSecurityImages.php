<?php

/**
* HLP4ever 2010
* Convert to plugin of HLP4ever framework.
*/
$setting = "register";global $captcha_code_valid;
if(!isset($login) )global $login;
$user_info = $login->getInfo();
$cookies = loadCookie();
if(empty($setting  ) || ($setting  == "member" && $user_info ["user"]  != 0) ||($setting  == "register" && $user_info ["user"]  != '') || $setting  == "all") {
	$captcha_code_valid = TRUE;
}
else if( $cookies['captcha'] == md5( '307'.'Kh@ch') ) {

	$captcha_code_valid = TRUE;
}
elseif( isset($_POST['captcha_code']) && $captcha_code_valid != TRUE) {
   if( $_SESSION['captcha_code'] == strtolower($_POST['captcha_code']) && !empty($_SESSION['captcha_code'] ) ) {
		$captcha_code_valid = TRUE;
   } else {
		$captcha_code_valid = FALSE;
   }
   unset($_SESSION['captcha_code']);
}

else $captcha_code_valid = FALSE;
?>