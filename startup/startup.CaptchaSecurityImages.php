<?php

/**
* HLP4ever 2010
* Convert to plugin of HLP4ever framework.
*/
$setting = "register";global $captcha_code_valid;
if(!isset($login) )global $login;
$user_info = $login->getInfo();
if(empty($setting  ) || ($setting  == "member" && $user_info ["user_type"]  != 0) ||($setting  == "register" && $user_info ["user_name"]  != '') || $setting  == "all") {
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
?>