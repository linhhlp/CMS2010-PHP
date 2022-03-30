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
 * Send mail by PHP function 
 * 		mail()
 */

class mailphp extends notify {
	private $config;
	private $from;
	private $to;
	private $message;
	private $header;
	private $paras;
	private $subject;
	private $error;
	public function __construct($from,$to,$subject,$message,$header='',$paras='') {
		global $_CONFIG;
		$this->config = $_CONFIG->getSite();
		if($this->config['NOTIFY'] == '0') {
			$this->error .= 'Disable to notify.';
		} 
		if($from =='')	$from = $this->config['ADMIN_EMAIL'];
		$this->from 	= $from;
		if($to == '') 	$this->error .= 'Can not find email address.';
		$this->to 		= $to;
		if($message == '') 	$this->error .= 'Message is empty.';
		$this->message	= $message;
		$this->paras	= $paras;
		if($header =="") { 		
			$header  = 'MIME-Version: 1.0' . "\r\n";
			$header .= 'Content-type: text/html; charset=UTF-8' . "\r\n";	//Send inform HTML With Unicode 8
			$header .=   'From: Admin <'.$from.'>	' . "\r\n";
		}
		$this->header	= $header;
	}
	public function filter($string) {
		return $string;
	}
	public function send() {
		if($this->error == ''|| empty($this->error)) {
		 	$suc = @mail($this->to,$this->subject,$this->message,$this->header);
		 	if($suc == FALSE) $this->error .= 'Can not send email.';
		}
		if($this->error == ''|| empty($this->error)) return TRUE;
		else return FALSE;
	}
	public function getError() {
		return $this->error;
	}
}