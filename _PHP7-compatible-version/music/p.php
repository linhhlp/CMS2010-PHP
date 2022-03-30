
<?php
	$domain_url=	$_SERVER['SERVER_NAME'];
	$url_full = 'http://'.$_SERVER['SERVER_NAME'].str_replace('p.php','',$_SERVER['SCRIPT_NAME']);
	$url = "../music";
	$files2 = scandir($url,0) ;
natcasesort ($files2);
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '
<playlist version="1" xmlns="http://xspf.org/ns/0/">
	<title>HLP music -- HLP007.com</title>
	<meta rel="generator">HLP</meta>
	<meta rel="generatorurl">http://hlp007.com</meta>
  <trackList>
		';
	 foreach ($files2 as $file) {
             	 if ($file != "." && $file != "..")
             	if(!is_dir($url."/".$file)) 
             	if(substr($file,-3,3)	== "mp3"||substr($file,-3,3)	== "MP3"||substr($file,-3,3)	== "Mp3"||substr($file,-3,3)	== "FLV"||substr($file,-3,3)	== "flv"||substr($file,-3,3)	== "Flv"){
echo '
			<track>
			  <title>'.$file.'</title>
			  <location>'.$url_full/$file.'</location>
			   <info>http://hlp007.com</info>
			<creator>HLP4ever</creator>
				<annotation>Nhạc của HLP4ever</annotation>
			</track>
	';
				}
		}

echo "
  </trackList>
</playlist>";
?>