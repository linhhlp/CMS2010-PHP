<?php
/**
 * @author HLP4ever .
 * @version		2010
 * @package		HLP4ever blog.
 * @copyright	Copyright (C) 2010 by HLP.
 * @license		Open Source and free for non-conmecial
 */
// No direct access
defined ( 'START' ) or die ();
if($_CONFIG == false) global $_CONFIG;
$type_user = $_CONFIG->getPermission();
$aboutme = new aboutme ();
$_about_hienthi = $aboutme->getInfo ();
?>

<table border="0" width="95%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10" valign="bottom"><img src="<?php
		echo IMA;
		?>/tl_10.GIF"></td>
		<td style="border-top: 2px solid blue"><img src="spacer.gif" width="1"
			height="1"></td>
		<td width="10" align="right" valign="bottom"><img
			src="<?php
			echo IMA;
			?>/tr_10.GIF"></td>
	</tr>
	<tr>
		<td width="8" style="border-left: 2px solid blue" height="91%">&nbsp;</td>
		<td>
		<p align="center"><font   size="5"><b>Thông tin
		về chủ blog</b></font></p>
		</td>
		<td width="8" align="right" style="border-right: 2px solid blue"
			height="91%">&nbsp;</td>
	</tr>
	<tr>
		<td width="10" valign="top"><img src="<?php
		echo IMA;
		?>/bl_10.GIF"></td>
		<td style="border-bottom: 2px solid blue"><img src="spacer.gif"
			width="1" height="1"></td>
		<td width="10" align="right" valign="top"><img
			src="<?php
			echo IMA;
			?>/br_10.GIF"></td>
	</tr>
</table>
<br>
<br>
<table border="0" width="95%" id="table4">
	<tr>
		<td align="center" valign="top">
		<table border="0" width="95%" cellspacing="0" cellpadding="0">
			<tr>
				<td width="10" valign="bottom"><img
					src="<?php
					echo IMA;
					?>/tl_10.GIF"></td>
				<td style="border-top: 2px solid blue"><img src="spacer.gif"
					width="1" height="1"></td>
				<td width="10" align="right" valign="bottom"><img
					src="<?php
					echo IMA;
					?>/tr_10.GIF"></td>
			</tr>
			<tr>
				<td width="8" style="border-left: 2px solid blue" height="91%">&nbsp;</td>
				<td height="91%"><font size="5"  >Thông tin
				nho nhỏ</font>
				<p></p>
				<p>
<?php
echo "<table border=0>";
echo "<tr><td width=40%>Tên sử dụng </td><td>" . $_about_hienthi ['user'] . "</td></tr>";
echo "<tr><td width=40%>Tên đầy đủ </td><td>" . $_about_hienthi ['name_user'] . "</td></tr>";
echo "<tr><td width=40%>Sinh ngày </td><td>" . $_about_hienthi ['birthday'] . "</td></tr>";
echo "<tr><td width=40%>Quê quán </td><td>" . $_about_hienthi ['home'] . "</td></tr>";
echo "<tr><td width=40%>Hiện tại ở </td><td>" . $_about_hienthi ['address'] . "</td></tr>";
echo "<tr><td width=40%>Điện thoại di động </td><td>" . $_about_hienthi ['mobile'] . "</td></tr>";
echo "<tr><td width=40%>Điện thoại nhà riêng </td><td>" . $_about_hienthi ['tel'] . "</td></tr>";
echo "<tr><td width=40%>Loại thành viên </td><td>" . $type_user[$_about_hienthi ['type'] ]. "</td></tr>";
echo "<tr><td width=40%>Bật mí nhỏ </td><td>" . $_about_hienthi ['note'] . "</td></tr>";
echo "</table>";
?>

</p>
				</td>
				<td width="8" align="right" style="border-right: 2px solid blue"
					height="91%">&nbsp;</td>
			</tr>
			<tr>
				<td width="10" valign="top"><img src="<?php
				echo IMA;
				?>/bl_10.GIF"></td>
				<td style="border-bottom: 2px solid blue"><img src="spacer.gif"
					width="1" height="1"></td>
				<td width="10" align="right" valign="top"><img
					src="<?php
					echo IMA;
					?>/br_10.GIF"></td>
			</tr>
		</table>
		</td>
		<td align="center" width="50%" valign="top">
		<table border="0" width="95%" cellspacing="0" cellpadding="0">
			<tr>
				<td width="10" valign="bottom"><img
					src="<?php
					echo IMA;
					?>/tl_10.GIF"></td>
				<td style="border-top: 2px solid blue"><img src="spacer.gif"
					width="1" height="1"></td>
				<td width="10" align="right" valign="bottom"><img
					src="<?php
					echo IMA;
					?>/tr_10.GIF"></td>
			</tr>
			<tr>
				<td width="8" style="border-left: 2px solid blue" height="91%">&nbsp;</td>
				<td height="91%">
				<p align="center">
<?php

echo "<img src='" . $_about_hienthi ['picture'] . "' width=100px hieght=100px>";

?>

</p>
				</td>
				<td width="8" align="right" style="border-right: 2px solid blue"
					height="91%">&nbsp;</td>
			</tr>
			<tr>
				<td width="10" valign="top"><img src="<?php
				echo IMA;
				?>/bl_10.GIF"></td>
				<td style="border-bottom: 2px solid blue"><img src="spacer.gif"
					width="1" height="1"></td>
				<td width="10" align="right" valign="top"><img
					src="<?php
					echo IMA;
					?>/br_10.GIF"></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td align="center">&nbsp;</td>
		<td align="center" width="50%">&nbsp;</td>
	</tr>
	<tr>
		<td colspan=2 align="center">
		<table border="0" width="91%" cellspacing="0" cellpadding="0">
			<tr>
				<td width="10" valign="bottom"><img
					src="<?php
					echo IMA;
					?>/tl_10.GIF"></td>
				<td style="border-top: 2px solid blue"><img src="spacer.gif"
					width="1" height="1"></td>
				<td width="10" align="right" valign="bottom"><img
					src="<?php
					echo IMA;
					?>/tr_10.GIF"></td>
			</tr>
			<tr>
				<td width="8" style="border-left: 2px solid blue" height="91%">&nbsp;</td>
				<td height="91%"><font   size="5"> Thông tin
				nho nhỏ: </font><br>
				<br>
	<?php
	echo $_about_hienthi ['info'];
	?>
	</td>
				<td width="8" align="right" style="border-right: 2px solid blue"
					height="91%">&nbsp;</td>
			</tr>
			<tr>
				<td width="10" valign="top"><img src="<?php
				echo IMA;
				?>/bl_10.GIF"></td>
				<td style="border-bottom: 2px solid blue"><img src="spacer.gif"
					width="1" height="1"></td>
				<td width="10" align="right" valign="top"><img
					src="<?php
					echo IMA;
					?>/br_10.GIF"></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td align="center" valign="top" width="91%">
		<table border="0" width="95%" cellspacing="0" cellpadding="0">
			<tr>
				<td width="10" valign="bottom"><img
					src="<?php
					echo IMA;
					?>/tl_10.GIF"></td>
				<td style="border-top: 2px solid blue"><img src="spacer.gif"
					width="1" height="1"></td>
				<td width="10" align="right" valign="bottom"><img
					src="<?php
					echo IMA;
					?>/tr_10.GIF"></td>
			</tr>
			<tr>
				<td width="8" style="border-left: 2px solid blue" height="91%">&nbsp;</td>
				<td height="91%"><font   size="5">Tự sự</font>
				<p>
			
			<?php
			echo $_about_hienthi ['aboutme'];
			?>
			
			</p>
				</td>
				<td width="8" align="right" style="border-right: 2px solid blue"
					height="91%">&nbsp;</td>
			</tr>
			<tr>
				<td width="10" valign="top"><img src="<?php
				echo IMA;
				?>/bl_10.GIF"></td>
				<td style="border-bottom: 2px solid blue"><img src="spacer.gif"
					width="1" height="1"></td>
				<td width="10" align="right" valign="top"><img
					src="<?php
					echo IMA;
					?>/br_10.GIF"></td>
			</tr>
		</table>
		</td>
		<td align="center" width="50%" valign="top">
		<table border="0" width="95%" cellspacing="0" cellpadding="0">
			<tr>
				<td width="10" valign="bottom"><img
					src="<?php
					echo IMA;
					?>/tl_10.GIF"></td>
				<td style="border-top: 2px solid blue"><img src="spacer.gif"
					width="1" height="1"></td>
				<td width="10" align="right" valign="bottom"><img
					src="<?php
					echo IMA;
					?>/tr_10.GIF"></td>
			</tr>
			<tr>
				<td width="8" style="border-left: 2px solid blue" height="91%">&nbsp;</td>
				<td height="91%"><font size="5"  >Sở thích cá
				nhân</font>

				<p>
			<?php
			echo $_about_hienthi ['love'];
			?>
			 </p>
				</td>
				<td width="8" align="right" style="border-right: 2px solid blue"
					height="91%">&nbsp;</td>
			</tr>
			<tr>
				<td width="10" valign="top"><img src="<?php
				echo IMA;
				?>/bl_10.GIF"></td>
				<td style="border-bottom: 2px solid blue"><img src="spacer.gif"
					width="1" height="1"></td>
				<td width="10" align="right" valign="top"><img
					src="<?php
					echo IMA;
					?>/br_10.GIF"></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<br>
<table border="0" width="95%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10" valign="bottom"><img src="<?php
		echo IMA;
		?>/tl_10.GIF"></td>
		<td style="border-top: 2px solid blue"><img src="spacer.gif" width="1"
			height="1"></td>
		<td width="10" align="right" valign="bottom"><img
			src="<?php
			echo IMA;
			?>/tr_10.GIF"></td>
	</tr>
	<tr>
		<td width="8" style="border-left: 2px solid blue" height="91%">&nbsp;</td>
		<td height="91%"><font   size="5"> Tiểu sử: </font><br>
		<br>
	<?php
	echo $_about_hienthi ['biogra'];
	?>
	</td>
		<td width="8" align="right" style="border-right: 2px solid blue"
			height="91%">&nbsp;</td>
	</tr>
	<tr>
		<td width="10" valign="top"><img src="<?php
		echo IMA;
		?>/bl_10.GIF"></td>
		<td style="border-bottom: 2px solid blue"><img src="spacer.gif"
			width="1" height="1"></td>
		<td width="10" align="right" valign="top"><img
			src="<?php
			echo IMA;
			?>/br_10.GIF"></td>
	</tr>
</table>
<br>
<table border="0" width="95%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10" valign="bottom"><img src="<?php
		echo IMA;
		?>/tl_10.GIF"></td>
		<td style="border-top: 2px solid blue"><img src="spacer.gif" width="1"
			height="1"></td>
		<td width="10" align="right" valign="bottom"><img
			src="<?php
			echo IMA;
			?>/tr_10.GIF"></td>
	</tr>
	<tr>
		<td width="8" style="border-left: 2px solid blue" height="91%">&nbsp;</td>
		<td height="91%"><font   size="5">Thành tích nổi
		bật</font><br>
		<br>
	<?php
	echo $_about_hienthi ['history'];
	?>
	</td>
		<td width="8" align="right" style="border-right: 2px solid blue"
			height="91%">&nbsp;</td>
	</tr>
	<tr>
		<td width="10" valign="top"><img src="<?php
		echo IMA;
		?>/bl_10.GIF"></td>
		<td style="border-bottom: 2px solid blue"><img src="spacer.gif"
			width="1" height="1"></td>
		<td width="10" align="right" valign="top"><img
			src="<?php
			echo IMA;
			?>/br_10.GIF"></td>
	</tr>
</table>
<p align="left">
<p align=center><font face="verdana" size="3" color="#0D0795"><b> Thank
you for visiting! </b></font></p>
<br>

<div align=center><font size=2>
<?php
echo " Lần cập nhập cuối:  " . $_about_hienthi ['lastupdate'];
?>
</font></div>