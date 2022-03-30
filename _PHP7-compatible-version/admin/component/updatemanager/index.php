<?php
/**
 * @author HLP4ever 2010 
 * @version		2010
 * @package		HLP4ever blog.
 * @copyright	Copyright (C) 2010 by HLP.
 * @license		Open Source and free for non-conmecial
*/
defined('START') or die;

if(!isset($_URL)) global $_URL;
$url = $_URL->getNewUrl(); 
if($url[1] == "autoupdate") {
$result = version::checkVersion();
if($result['lastest'] == "TRUE") echo "<h3>You are up-to-date.</h3>";
else {
    echo "<h3>Updating... please wait.</h3>";
    echo "update from ".version::getVersion()." to ".$result['nextVersion']." version.<br />";
    if(version::updateVersion()) {
        echo 'ok';
    } 
    else {
        echo 'failed';
    }
    driect_url($_URL->getOldUrl(),5);
}

}
else {
$result = version::checkVersion();
if($result['lastest'] == "TRUE") echo "<h3>You are up-to-date.</h3>";
else echo "Your version is: ".version::getVersion().".<br />Lastest vesion is: ".$result['lastestVersion'].".<br />";
echo "Lastest Version link <a href='".$result['url']."'>".$result['lastestVersion']."</a>.<br />";
echo "<a href='".urlmanager::makeLink($url[0],"autoupdate","")."'>Click here to auto-update</a>";
echo "<br />The newest vesion is: ".$result['newestVersion'].".<br />";
}
?>