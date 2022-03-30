<?php

/**
 * @author HLP4ever 2010;
 * @copyright 2010;
 * 
 * This is the website port which sends information
 * to another website
 */

/**
 * Name of Request
 * type = login
 * 		for POST data: check remote login
 * 
 * 	the rest:
 * type 	: type of information get : update info, content: comment, blog  ...
 * request	: type of request: comment, centent,...
 * options	: other requests 
 */
// firstly, running session_start() :

if(!isset($_SESSION['ss'])) {
	session_start();
	$_SESSION['ss'] = 1;

}

ini_set('display_errors',0);

define(START, "community");
include_once 'config.php';

$_CONFIG = new Config();
global $community_setting;
$community_setting = $_CONFIG->getCommunity();
// check setting
if($community_setting["ENABLE"] == FALSE) return;
// Startting :
	// Include necessary files
if(is_file(LIB.'/function.php')) {
	include_once (LIB.'/function.php');
}
// declare community class
$community = new community();
$_URL	= new urlmanager();
$str = '';
//header('Content-type: application/xml');
$str = "<?xml version='1.0' encoding='UTF-8' ?>"."\n";
// fisrt : port for echecking log-in:
if($_GET["type"] == "login") {
	if($_POST["login"] == "confirm") {
		//print_r($_POST);
		$community->loginConfirm($_POST['friendname'], $_POST['ip'],$_POST['session_id'],  $_POST['friendurl'], $_POST['securitykey']);
	}
	else {
		$name   = isset($_POST['name']) ? $_POST['name'] : '';
    	$pass   = isset($_POST['pass']) ? $_POST['pass'] : '';
		$community->login($name,$pass,$_GET['url'],$_GET['session_id']);
	}
    die;
}
elseif($_GET["type"] == "rss") {
	$str .= $community->getRSS($_GET["name"]);
	
}
elseif($_GET["type"] == "friendupdate") {
	$str .= $community->friendupdate($_GET["lastContentID"],$_GET["lastCommentID"],$_GET["lastCommentBlogID"]);
	
}
elseif($_GET["type"] == "community") {
	$str .= $community->getCommunity($_GET["name"]);
	
}
elseif($_GET["type"] == "postnewSave") {
	$str .= $community->postnewSave($_POST['friendname'],$_POST['friendurl'],$_POST['typecomment'],$_POST['title'],$_POST['content'],$_POST['securitykey'],$_POST['ip'],$_POST['session_id'],$_POST['id']);
	
}

echo $str;


?>