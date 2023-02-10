<?php
ini_set('display_errors',1);

//These are placed outside of the webserver directory for security
$database="find";
include("../../include/auth.inc"); // used to authenticate users
include("../../include/iConnect.inc"); // database connection parameters
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
include("menu.php");

//if($_SESSION['userType'] != 'admin'){echo "Access denied.<br>Administrative Login Required.<br><a href='login_form.php'>Login</a> ";exit;}
/*
echo "<pre>";
print_r($_SESSION);
print_r($_REQUEST);
echo "</pre>";
exit;
*/
extract($_REQUEST);
//  ************Start Menu form*************
if(@!$admin)
	{
	menuStuff();
	echo "</body></html>";
	exit;
	}// end if !$admin

// ********* DELETE A DIRECTIVE AND ASSOC. FILE(S) ***************************
  
if ($admin == "del")
	{
	if($dirNum!="")
		{
		$sql="SELECT link from map where dirNum='$dirNum'";
		$result=mysqli_QUERY($conneciton,$sql);
		while ($row=mysqli_fetch_array($result))
			{
			extract($row);unlink($link);
			}
		
		$sql="DELETE FROM map where dirNum='$dirNum'";
		$result=mysqli_QUERY($conneciton,$sql);
		$sql="DELETE FROM directive where dirNum='$dirNum'";
		$result=mysqli_QUERY($conneciton,$sql);
		header("Location: search.php?Submit=display&dirNum=$dirNum");
		}
	else
		{
		$sql="SELECT link,dirNum from map where mid='$mid'";
		$result=mysqli_QUERY($conneciton,$sql);
		while ($row=mysqli_fetch_array($result))
			{
			extract($row);unlink($link);
			}
		$sql="DELETE FROM map where mid='$mid'";
		$result=mysqli_QUERY($conneciton,$sql);
		header("Location: search.php?Submit=display&dirNum=$dirNum");
		}
	exit;
	}

	
// ****************ADD A File***************************
    // Show the form to submit a file
if (@$submit == "Add a File")
	{
//	 print_r($_REQUEST); exit;
	   echo "<hr>
		<form method='post' action='$PHP_SELF' enctype='multipart/form-data'>
	
	Enter a Title for the file: <textarea cols='40' rows='1' name='mapName'>$mapName
	</textarea><hr>
		<INPUT TYPE='hidden' name='dirNum' value='$dirNum'>
		<INPUT TYPE='hidden' name='admin' value='graphic'>
		<br>1. Click the BROWSE button and select your JPEG, PDF, WORD or EXCEL file.<br>
		<input type='file' name='map'  size='40'>
		<p>2. Then click this button. 
		<input type='submit' name='submit' value='Add File'>
		</form>
	<br><br>Make sure your File is less than or equal to 3 MB. If you need to add a file larger than 3 MB, contact the administrator.";
	exit;
	}

if (@$submit == "Add File")
	{
	date_default_timezone_set('America/New_York');
	mysqli_select_db($connection,"find.map");
	extract($_FILES);
	$size = $_FILES['map']['size'];
	$type = $_FILES['map']['type'];
	$file = $_FILES['map']['name'];
	$file=str_replace("'","",$file);
	$file=str_replace("!","",$file);
	$file=str_replace(" ","_",$file);
	$file=str_replace("[","",$file);
	$file=str_replace("]","",$file);
	$mapName = $file;
	$ext = substr(strrchr($file, "."), 1);// find file extention, png e.g.
	// print_r($_FILES); print_r($_REQUEST);exit;
	if(!is_uploaded_file($map['tmp_name']))
		{
		print_r($_FILES);  print_r($_REQUEST);
		exit;
		}
	
	$mapName=mysqli_real_escape_string($connection,$mapName);
	if(!isset($dirNum)){$dirNum="";}
	$sql="INSERT INTO find.map (filename,forumID,dirNum,mapname,filetype,filesize) "."VALUES ('$file','$forumID','$dirNum','$mapName','$type','$size')";
	//echo "$sql";exit;
	$result = @mysqli_query($connection,$sql) or die("$sql<br />Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
	
		$mid= mysqli_insert_id($connection);
	$year=date('Y');
	$ext = explode("/",$type);
	
	$folder="/opt/library/prd/WebServer/Documents/find/graphics/".$year;
	if (!file_exists($folder)) {mkdir ($folder, 0777);}
	
	$uploaddir = "graphics/".$year."/"; // make sure www has r/w permissions on this folder
	
	//$numTime=time();
	//$uploadfile = $uploaddir.$file.".".$numTime.".".$ext[1];
	$uploadfile = $uploaddir.$file;
	move_uploaded_file($map['tmp_name'],$uploadfile);// create file on server
		
	  $sql = "UPDATE map set link='$uploadfile' where mid='$mid'";
	$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
	
	$uploadfile="/find/".$uploadfile;
	if($exist=="y")
		{
		$varUP="concat_ws(',',weblink,'$uploadfile')";
		}
	else {
		$varUP="'$uploadfile'";
		}
	
	  $sql = "UPDATE forum set weblink=$varUP where forumID='$forumID'";
	//  echo "$sql";exit;
	$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
	
	header("Location: forum.php");
	exit;
	} 
	
// ********** Edit a Document *************
if($admin=="update")
	{
	$title=html_entity_decode(htmlspecialchars_decode($title));
	$body=html_entity_decode(htmlspecialchars_decode($body));
	$attach=html_entity_decode(htmlspecialchars_decode($attach));
	$dirFrom=html_entity_decode(htmlspecialchars_decode($dirFrom));
	$misc=html_entity_decode(htmlspecialchars_decode($misc));
// 	$title=addslashes($title);
// 	$body=addslashes($body);
// 	$attach=addslashes($attach);
// 	$dirFrom=addslashes($dirFrom);
// 	$misc=addslashes($misc);
	
	$sql="UPDATE directive SET `dirNum`='$dirNum',`dateDir`='$dateDir',`dirTo`='$dirTo',`dirFrom`='$dirFrom',`title`='$title',`body`='$body',`source`='$source',`attach`='$attach',`misc`='$misc',`webLink`='$webLink' where dirNum='$dirNum'";
	$result = @mysqli_query($connection,$sql) or die("Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
	header("Location: search.php?Submit=display&dirNum=$dirNum");
	exit;
	}
if($admin=="edit")
	{
	if(@$dirNum)
		{
		$where = "WHERE directive.dirNum='$dirNum'";
		
		$sql = "SELECT directive.*,map.mid,map.mapname From directive
		LEFT JOIN map on directive.dirNum=map.dirNum
		 $where";
		$result = @mysqli_query($connection,$sql) or die("Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
		//echo "$sql";
		$numrow = mysqli_num_rows($result);
		if($numrow < 1){echo "<br><hr>No Staff Directive found using: <font color='blue'>$query</font>";exit;}
		
		if($numrow>1)
			{
			while ($row=mysqli_fetch_array($result))
				{
				extract($row);
				$midA[]="$mapname <a href='adminMenu.php?admin=del&mid=$mid'>Delete</a><br>";
				//&dirNum=$dirNum
				}
			}
		else
			{
			$row=mysqli_fetch_array($result); extract($row);
			$midA[]="$mapname <a href='adminMenu.php?admin=del&mid=$mid'>Delete</a><br>";
			//&dirNum=$dirNum'
			}
		$a="Directive";$b="Directive<br>Date";$c="To:";$d="From:";
		$e="Subject";$f="Body";$g="Source";$h="Attachment(s)";$i="Revision(s)";
		echo "<form method='post' action='adminMenu.php'><table border='1'><tr>
		<th>$a</th><th></th><th></th>
		<th></th><th>$e<br>$g</th><th>$f</th>
		<th>$h</th><th>$i</th></tr>";
		echo "<tr>
		<td align='center'>Number:<input type='text' size='8' name='dirNum' value='$dirNum'><br>Date:<input type='text' size='10' name='dateDir' value='$dateDir'></td>
		<td align='center'></td>
		<td>To:<textarea name='dirTo' cols='30' rows='5'>$dirTo</textarea><br>
		From:<textarea name='dirFrom' cols='30' rows='1'>$dirFrom</textarea></td>
		<td>&nbsp;</td><td><textarea name='title' cols='30' rows='5'>$title</textarea><br><input type='text' size='10' name='source' value='$source'></td>
		<td><textarea name='body' cols='40' rows='20'>$body</textarea></td>
		<td align='center'><textarea name='attach' cols='40' rows='20'>$attach</textarea></td>
		<td align='center'><textarea name='misc' cols='20' rows='10'>$misc</textarea><br>WebLink:<br><textarea name='webLink' cols='20' rows='10'>$webLink</textarea><br>Title1 @ Link1, Title2 @ Link2</td></tr>
		<tr><td colspan='5'>Forum link: /find/search.php?Submit=display&dirNum=$dirNum</td><td colspan='2' align='center'>
		<input type='hidden' name='admin' value='update'>
		<input type='submit' name='submit' value='update'>
		<input type='hidden' name='dirNum' value='$dirNum'>
		<input type='submit' name='submit' value='Add a File'>
		</form>&nbsp;&nbsp;&nbsp;&nbsp;<a href='adminMenu.php?admin=del&dirNum=$dirNum'> Delete Doc</a> and All of its attachments</td>
		
		<td>";
		$j=count($midA);
		for($i=0;$i<$j;$i++)
			{
			echo "$midA[$i]";
			}
		echo "</td></tr>
		</table>
		</body></html>";
		//print_r($midA);
		exit;
		}// end if dirNum
	else
		{
		if(!isset($success)){$success="";}
		echo "<html><head><title>NC DPR Documents</title></head>
		<body bgcolor='beige'><font size='4' color='004400'>Edit a NC DPR Document</font>
		<br><br>$success</font>
		<hr><form name='search' method='post' action='adminMenu.php'>
		
		<table width='500'><tr>
		<td>Enter Doc Number, e.g., 2<br><input type='text' name='dirNum' value=''></td>
		<td> <input type='hidden' name='admin' value='edit'>
		 <input type='submit' name='Submit' value='Search'></form></td></tr></table>
		<hr>";
		echo "</table></body></html>";
		exit;
		}// end elseIF dirNum
	
	} // end admin=edit



// ********** ADD SD *************
if($admin=="Add")
	{
	if($dirFromAlt){$dirFrom=$dirFromAlt;}
	$title=html_entity_decode(htmlspecialchars_decode($title));
	$body=html_entity_decode(htmlspecialchars_decode($body));
	$attach=html_entity_decode(htmlspecialchars_decode($attach));
	$misc=html_entity_decode(htmlspecialchars_decode($misc));
	$dirFrom=html_entity_decode(htmlspecialchars_decode($dirFrom));
	// $title=addslashes($title);
// 	$body=addslashes($body);
// 	$attach=addslashes($attach);
// 	$misc=addslashes($misc);
// 	$dirFrom=addslashes($dirFrom);
	if($dirFromAlt!=""){
	$dirFromAlt=html_entity_decode(htmlspecialchars_decode($dirFromAlt));
// 	$dirFromAlt=addslashes($dirFrom);
	$dirFrom=$dirFromAlt;
	}
	
	$sql="REPLACE directive SET `dirNum`='$dirNum',`dateDir`='$dateDir',`dirTo`='$dirTo',`dirFrom`='$dirFrom',`title`='$title',`body`='$body',`source`='$source',`attach`='$attach',`misc`='$misc'";
	//echo "$sql";exit;
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	
	header("Location: search.php?Submit=display&dirNum=$dirNum");
	exit;
	}

//  ************Start Input form for Add SD ************
if($admin=="addForm")
	{
	$sql="SELECT dirNum FROM directive ORDER BY dirNum DESC LIMIT 1";
	$resultFrom = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	$row=mysqli_fetch_array($resultFrom);extract($row);
	
	menuStuff();
	$a="Document<br>Number";$b="Document<br>Date";$c="To:";$d="From:";
	$e="Subject";$f="Body";$g="Source";$h="Attachment(s)";$i="Revision(s)";
	echo "<form method='post' action='adminMenu.php'><table border='1'><tr>
	<th>$a</th><th>$b</th><th>$c</th>
	<th>$d</th>
	</tr>";
	
	}// end if $admin=addForm

if($dirNum<1){$dirNum=1;}else{$dirNum=$dirNum+1;}
if(!isset($dateDir)){$dateDir="";}
if(!isset($dirTo)){$dirTo="";}
if(!isset($dirFrom)){$dirFrom="";}
if(!isset($title)){$title="";}
if(!isset($source)){$source="";}
if(!isset($body)){$body="";}
if(!isset($attach)){$attach="";}
if(!isset($misc)){$misc="";}
echo "
<tr>
<td align='center'><font color='blue' size='+2'>$dirNum</font></td>
<td align='center'><input type='text' size='10' name='dateDir' value='$dateDir'></td>

<td><textarea name='dirTo' cols='25' rows='3'>$dirTo</textarea></td>
<td align='center'><textarea name='dirFrom' cols='20' rows='3'>$dirFrom</textarea></td></tr>";

echo "<tr><th>$e<br>$g</th><th>$f</th>
<th>$h</th><th>$i</th></tr>
<tr><td>Subject:<br>
<textarea name='title' cols='25' rows='4'>$title</textarea><br>Source:<br><input type='text' size='10' name='source' value='$source'></td>
<td>
<textarea name='body' cols='40' rows='20'>$body</textarea></td>

<td align='center'><textarea name='attach' cols='25' rows='20'>$attach</textarea></td>
<td align='center'><textarea name='misc' cols='20' rows='20'>$misc</textarea></td>
</tr>
<tr><td colspan='8' align='center'>
<input type='hidden' name='dirNum' value='$dirNum'>
<input type='hidden' name='admin' value='Add'>
<input type='submit' name='submit' value='Add'>
</form></td></tr></table>";
echo "<hr></body></html>";

// *************** Display Menu FUNCTION **************
function menuStuff(){
echo "<html><head><title>Admin Menu</title></head>
<body><font size='4' color='004400'>NC State Parks System Documents</font>
<br><font size='5' color='blue'>Administrative Function Menu
</font><hr><table>
<tr><td colspan='2' width='300'><b>Choose Action:</b>
	<table>
		<tr><td width='25'></td><td><FORM>
<INPUT TYPE='button' value='Add A Document' onClick=\"location='adminMenu.php?admin=addForm'\">
</FORM> Be sure to <font color='red'>FIRST</font> add the Subject to the FORUM before adding any document. Call Denise if you have any questions.</td></tr>
		<tr><td width='25'></td><td><FORM>
<INPUT TYPE='button' value='Edit A Document' onClick=\"location='adminMenu.php?admin=edit'\">
</FORM></td></tr>
</table><hr></body></html>";

}
?>