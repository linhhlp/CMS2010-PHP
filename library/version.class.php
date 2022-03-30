<?php
/**
 * @author HLP4ever 2010 
 * @version		2010
 * @package		HLP4ever blog.
 * @copyright	Copyright (C) 2010 by HLP.
 * @license		Open Source and free for non-conmecial
*/
class version {
    private static $version = "1.0.0";
    static function getVersion(){
        return self::$version;
    }
    static function setVersion($nextVersion) {
        $class_file = LIB."/version.class.php";
        $file = file_get_contents($class_file);
        $file = str_replace(self::$version,$nextVersion,$file);
        file_put_contents($class_file, $file);
        return;
    }
    static function checkVersion(){
        global $_CONFIG;
        $site_config    = $_CONFIG->getSite();
        $url_server     = $site_config['UPDATESERVER'];
        $data = array("type"=>"check","version"=>self::$version);
        $result = sendData($url_server,$data,"POST","xml");
        $update = array("lastest"=>$result->lastest,"lastestVersion"=>$result->lastestVersion,"newestVersion"=>$result->newestVersion,"nextVersion"=>$result->nextVersion,"url"=>$result->url);
        return $update;
    }
    static function updateVersion() {
        global $_CONFIG;
        $site_config    = $_CONFIG->getSite();
        $url_server     = $site_config['UPDATESERVER'];
        $data = array("type"=>"update","version"=>self::$version);
        $result = sendData($url_server,$data,"POST","xml");
        //$update = array("lastest"=>$result->lastest,"lastestVersion"=>$result->lastestVersion,"newestVersion"=>$result->newestVersion,"url"=>$result->url);
        $zip = new ZipArchive;
        $download = file_get_contents($result->url);
        $downloaded = TMP."/".$result->nextVersion;
        file_put_contents($downloaded, $download);
        if ($zip->open($downloaded) === TRUE) {
        $zip->extractTo(getcwd());
        $zip->close();
        self::setVersion($result->nextVersion);
        return TRUE;
        } else {
            return FALSE;
        }
    }
}

?>