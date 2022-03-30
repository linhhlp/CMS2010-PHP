<?php
	$url = "hinh_anh";
	$url_di ="thumb";
	$url_sl ="slide";
	$files2 = scandir($url,0) ;
	natcasesort ($files2);
		function setMemoryForImage( $filename ){
	$imageInfo = getimagesize($filename);
	$MB = 1048576; // number of bytes in 1M
	$K64 = 65536; // number of bytes in 64K
	$TWEAKFACTOR = 1.85; // Or whatever works for you
	$memoryNeeded = round( ( $imageInfo[0] * $imageInfo[1]* $imageInfo['bits']* $imageInfo['channels'] / 8+ $K64) * $TWEAKFACTOR );
	$memoryLimit = 32 * $MB;
	if (function_exists('memory_get_usage') &&	memory_get_usage() + $memoryNeeded > $memoryLimit)
		{
				$newLimit = $memoryLimit + ceil( ( memory_get_usage()+ $memoryNeeded- $memoryLimit) / $MB);
				$newLimit=$newLimit + 3000000;
				ini_set( 'memory_limit', $newLimit . 'M' );
				return true;
		} 
	else 
		{
		return false;
		}
}
?>


<flash_parameters copyright="anvsoftPFMTheme">
    <preferences>
        <global>
            <basic_property html_title="HLP picture slideshow" loadStyle="Pie" hideAdobeMenu="false" enableURL="false" anvsoftMenu="false" startAutoPlay="true" screenfullButton="true"/>
            <music_property path="" stream="true" loop="true"/>
            <description_property enable="true" backgroundColor="" backgroundAlpha="30" cssText="a:link{text-decoration: underline;} a:hover{color:#ff0000; text-decoration: none;} a:active{color:#0000ff;text-decoration: none;} .blue {color:#0000ff; font-size:15px; font-style:italic; text-decoration: underline;} .body{color:#ff5500;font-size:20px;}" align="top"/>
            <background_property backgroundColor="" mode="tile"/>
        </global>
        <photo>
            <basic_property photoBackColor="0xffffff" photoTitleColor="0x000000" PhotoMaxWidth="584" PhotoMaxHeight="402" EnableColorTransform="false"/>
        </photo>
        <thumbnail>
            <basic_property thumWidth="140" thumHeight="100" enableVerticalMove="true" verticalDiameter="100" thumbnailColor="0xffffff" thumbnailbgAlpha="90" thumborder="2" thumradius="300" Enableshadow="true" shadowSpacing="0" shadowAlpha="80" rotationspeed="6" Rdirection="inward"/>
        </thumbnail>
    </preferences>
    <album>

		<?php
	 foreach ($files2 as $file) {
             	 if ($file != "." && $file != "..")
             	if(!is_dir($url."/".$file)) 
             	if(substr($file,-3,3)	== "jpg"||substr($file,-3,3)	== "JPG"||substr($file,-4,4)	== "JPEG"||substr($file,-4,4)	== "jpeg"){
			$filename=$url."/".$file;
		// Content type
	//	header('Content-type: image/jpeg');

		// Get new sizes
		list($width, $height) = getimagesize($filename);
if($width > $height)
{
$percent	=	140 / $width;
	$newwidth = 140;
	$newheight = $height * $percent;
}
else {
$percent	=	100 / $height;
	$newwidth = $width * $percent;
	$newheight = 100;
}
		// Load
		$thumb_url = $url_di."/".$file;
	//	copy($filename, $thumb_url);
$thumb = imagecreatetruecolor($newwidth, $newheight);
setMemoryForImage($filename);
$source = imagecreatefromjpeg($filename);

// Resize
imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
imagejpeg($thumb,$thumb_url);


if($width > $height)
{
$percent	=	540 / $width;
	$width2 = 540;
	$height2 = $height * $percent;
}
else {
$percent	=	380 / $height;
	$width2 = $width * $percent;
	$height2 = 380;
}
	
		$slide_url = $url_sl."/".$file;
	//	copy($filename, $thumb_url);
	$slide= imagecreatetruecolor($width2, $height2);
	$source = imagecreatefromjpeg($filename);

// Resize
imagecopyresized($slide, $source, 0, 0, 0, 0, $width2, $height2, $width, $height);
imagejpeg($slide,$slide_url);
	
	?>

 <slide jpegURL="<?php echo $thumb_url;	?>" d_URL="<?php echo	$filename;	?>" transition="0" panzoom="1" URLTarget="0" phototime="2" url="http://hlp007.com" title="" width="<?php echo $width2	?>" height="<?php echo $height2	?>"/>

<?php	
		}
	}
 ?>
 </album>
</flash_parameters>