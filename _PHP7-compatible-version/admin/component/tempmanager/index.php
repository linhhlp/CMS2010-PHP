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
 * Setting
 */
global $admin_HLP;
global $PARAS,$ACTION,$CONTROL;
global $_CONFIG;
?>
<br /><br />
<?php 
$tempmanager = new tempmanager();
if($ACTION == '0')  {
	echo "<h3>Manage templates.</h3>";
	echo "<h3 align=right>".urlmanager::makeLink($PARAS,'list','','install new','Install new Template.')."</h3>";
	echo "<h4>Website templates.</h4>";
	$normal_tem = $tempmanager->getTemp();
	if($normal_tem != FALSE) {
		echo '<table border=1 width=100%>';
		echo '<tr>
				<td width=3%>#</td><td width=20%>Title</td><td width=15%>Name</td><td>Descripton</td><td>Path</td><td>Author</td><td>Version</td><td>Enable</td><td>Edit</td>
				</tr>';
		$i=1;
		foreach($normal_tem as $key=>$values) {
			if($values['enable'] == 1) {
				$values['enable'] = 'Yes';
			}
			else $values['enable'] = 'No';
			echo '<tr><td>'.$i.'</td><td>'.$values['title'].'</td><td>'.$values['name'].'</td><td>'.$values['descr'].'</td><td>'.$values['path'].'</td><td>'.$values['author'].'</td><td>'.$values['version'].'</td><td>'.$values['enable'].'</td><td>'.urlmanager::makeLink($PARAS,'update',$values['id'],'update','Edit').'</td></tr>';
			$i++;
		}
		echo '</table>';
	}
	else echo 'You have not configured template. Use default.';
	
	echo "<h4>Admin tempaltes.</h4>";
	
	$admin_tem  = $tempmanager->getTemp('admin');
	if($admin_tem != FALSE) {
		echo '<table border=1 width=100%>';
		echo '<tr>
				<td width=5%>#</td><td width=30%>Title</td><td width=20%>Name</td><td>Descripton</td><td>Path</td><td>Author</td><td>Version</td><td>Enable</td>
				</tr>';
		$i=1;
		foreach($admin_tem as $key=>$values) {
			if($values['enable'] == 1) {
				$values['enable'] = 'Yes';
			}
			else $values['enable'] = 'No';
			echo '<tr><td>'.$i.'</td><td>'.$values['title'].'</td><td>'.$values['name'].'</td><td>'.$values['descr'].'</td><td>'.$values['path'].'</td><td>'.$values['author'].'</td><td>'.$values['version'].'</td><td>'.$values['enable'].'</td></tr>';
			$i++;
		}
		echo '</table>';
	}
	else echo 'You have not configured template. Use default.';
}
elseif($ACTION == 'list')  {
		echo "<h3>Install new Blog tempaltes.</h3>";
		$installTemp = $tempmanager->getInstall('blog');
?>
		
		<font color=red >Hint</font>: copy folder module into TEMPLATE directory.<br />
		After that, go to control panel and install it.
		<table id="managemodule">
		<tr class="title">
			<td>#</td><td width=30%>Path</td><td>Title</td><td>Name</td><td>Author</td><td>Version</td><td>Install</td>
		</tr>
		<?php
			$i = 1;
			foreach ($installTemp as $key=>$value) {
				echo "<tr>
					<td>$i</td><td>".$value['path']."</td><td>".$value['title']."</td><td>".$value['name']."</td><td>".$value['author']."</td><td>".$value['version']."</td>
					<td>";
				if($value['install'] != FALSE) echo $value['install'];
				else echo urlmanager::makeLink($PARAS,'install','blog-'.$value['path'],'make new blog template','install');
				echo "</td>
					</tr>";
				$i++;	
				}
			
		?>
		</table>
<?php		
		echo "<h3>Install new Admin tempaltes.</h3>";
		$installTempAdmin = $tempmanager->getInstall('admin');
		?>
				<table id="managemodule">
		<tr class="title">
			<td>#</td><td width=30%>Path</td><td>Title</td><td>Name</td><td>Author</td><td>Version</td><td>Install</td>
		</tr>
		<?php
			$i = 1;
			foreach ($installTempAdmin as $key=>$value) {
				echo "<tr>
					<td>$i</td><td>".$value['path']."</td><td>".$value['title']."</td><td>".$value['name']."</td><td>".$value['author']."</td><td>".$value['version']."</td>
					<td>";
				if($value['install'] != FALSE) echo $value['install'];
				else echo urlmanager::makeLink($PARAS,'install','admin-'.$value['path'],'make new admin template','install');
				echo "</td>
					</tr>";
				$i++;	
				}
			
		?>
		</table>
<?php	
	
}
elseif($ACTION == 'install') {
	echo '<h3>Install new Template</h3>';
	if(!empty($_POST)) {
		$suc = $tempmanager->Install($_POST['title'],$_POST['name'],$_POST['descr'],$_POST['path'],$_POST['type'],$_POST['enable'],$_POST['author'],$_POST['version']);
		if ($suc != FALSE) {
			echo 'Make new successfull.<br />Note that: old setting was overwrite.';
		}
		else {
			echo 'Fail to make new setting.';
		}
	}
	else {
		list($type,$path) = explode('-',$CONTROL);
		$edit_temp = $tempmanager->getInstall($type,$path);
		echo '<form action="'.urlmanager::makeLink($PARAS,$ACTION,$CONTROL).'" method=POST><table border=0 width=50%>';
		echo '<tr><td width=50%>Title</td><td><input type=text name=title value="'.$edit_temp['title'].'"></td></tr>';
		echo '<tr><td width=50%>Name (advanced)</td><td><input type=text name=name value="'.$edit_temp['name'].'"></td></tr>';
		echo '<tr><td width=50%>Enable</td><td>
				<select name="enable">
				<option value="1" selected="selected" >Enable</option>
				<option value="0" >Disable</option>
				</select></td></tr>';
		echo '<tr><td width=50%>Type</td><td>
				<input type=text name="type" value="'.$edit_temp['type'].'">
				</td></tr>';
		echo '<tr><td width=50%>Description</td><td><input type=text name=descr value="'.$edit_temp['descr'].'"></td></tr>';
		echo '<tr><td width=50%>Path</td><td><input type=text name=path value="'.$edit_temp['path'].'"></td></tr>';
		echo '<input type=hidden name="author"value="'.$edit_temp['author'].'"><input type=hidden name="version"value="'.$edit_temp['version'].'">';
		echo '<tr><td width=50%>Ok</td><td><input type=submit value="Submit"><input type=reset value="Reset"></td></tr>';
		echo '</table></form>';
	}
}
elseif($ACTION == 'update') {
	if(!empty($_POST)) {
		$success = $tempmanager->Update ($CONTROL,$_POST['title'],$_POST['name'],$_POST['descr'],$_POST['path'],$_POST['type'],$_POST['enable']);
		if($success != FALSE) {
			echo 'Update successfully.';
		}
		else echo '<font color=red>Update Fail.</font>';
	}
	else {
		$gettemp = $tempmanager->getTemp('',$CONTROL);
	echo '<form action="'.urlmanager::makeLink($PARAS,$ACTION,$CONTROL).'" method=POST><table border=0 width=50%>';
	echo '<tr><td width=50%>Title</td><td><input type=text name=title value="'.$gettemp['title'].'"></td></tr>';
	echo '<tr><td width=50%>Name (advanced)</td><td><input type=text name=name value="'.$gettemp['name'].'"></td></tr>';
	echo '<tr><td width=50%>Enable</td><td>
			<select name="enable">
			<option value="1" '.(($gettemp['enable']==1)?'selected="selected"':'').'>Enable</option>
			<option value="0" '.(($gettemp['enable']==0)?'selected="selected"':'').'>Disable</option>
			</select></td></tr>';
	echo '<tr><td width=50%>Type</td><td>
			<select name="type">
			<option value="admin" '.(($gettemp['type']=='admin')?'selected="selected"':'').'>Admin</option>
			<option value="blog" '.(($gettemp['type']=='blog')?'selected="selected"':'').'>Blog</option>
			</select> 
			</td></tr>';
	echo '<tr><td width=50%>Path</td><td><input type=text name=path value="'.$gettemp['path'].'"></td></tr>';
	echo '<tr><td width=50%>Description</td><td><input type=text name=descr value="'.$gettemp['descr'].'"></td></tr>';
	echo '<tr><td width=50%>Ok</td><td><input type=submit value="Submit"><input type=reset value="Reset"></td></tr>';
	echo '</table></form>';
	echo '<br /> <font color=red>Note: If you change Type, so make sure that It is in Exact Folder (Admin template or Blog template).</font>';
	}
}

?>