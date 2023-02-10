<?php
$database="staffdir"; 
$dbName="staffdir";
include("../../include/auth.inc");
include("../../include/iConnect.inc");
mysqli_select_db($connection,$database);
// extract($_REQUEST);
//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;

include("menu.php");

//echo "<pre>"; print_r($_POST); echo "</pre>"; // exit;

//  ************Start Menu form*************
if(!@$admin)
	{
	menuStuff();
	echo "</body></html>";
	exit;
	}// end if !$admin

// ********* DELETE A DIRECTIVE AND ASSOC. FILE(S) ***************************
  
if ($admin == "del")
	{
	$sql="DELETE FROM map where mid='$mid'";
	$result=mysqli_QUERY($connection,$sql);
	
	if($dirNum!="")
		{
		$sql="DELETE FROM map where dirNum='$dirNum'";
		$result=mysqli_QUERY($connection,$sql);
		$sql="DELETE FROM directive where dirNum='$dirNum'";
		$result=mysqli_QUERY($connection,$sql);
		header("Location: search.php?Submit=display&dirNum=$dirNum");
		}
	exit;
	}

// ****************ADD A File***************************
    // Show the form to submit a graphic
if (@$submit == "Add a File")
	{
	// print_r($_REQUEST); exit;
	   echo "<hr>
		<form method='post' action='$PHP_SELF' enctype='multipart/form-data'>
	
	Enter a Descriptive name: <textarea cols='40' rows='1' name='mapname'>$mapname</textarea><br>e.g., <font color='green'>SD 1992-02 as a PDF</font><hr>
		<INPUT TYPE='hidden' name='dirNum' value='$dirNum'>
		<INPUT TYPE='hidden' name='admin' value='graphic'>
		<INPUT TYPE='hidden' name='MAX_FILE_SIZE' value='6000000'>
		<br>1. Click the BROWSE button and select your PDF file or Graphic.<br>
		<input type='file' name='map'  size='40'>
		<p>2. Then click this button. 
		<input type='submit' name='submit' value='Add File'>
		</form>
	<br><br>Make sure your File is less than or equal to 6 MB. If you need to add a file larger than 6 MB, contact the administrator.";
	exit;
	}

if (@$submit == "Add File")
	{
	if(!$mapname){echo "You must enter the name of the file. Click your Browser's BACK button.";exit;}
	mysqli_select_db($connection,"staffdir.map");
	$mapname = strtoupper($mapname);
	extract($_FILES);
	$size = $_FILES['map']['size'];
	$type = $_FILES['map']['type'];
	$file = $_FILES['map']['name'];
	$ext = substr(strrchr($file, "."), 1);// find file extention, png e.g.
//	 echo "<pre>"; print_r($_FILES); echo "</pre>";  exit;
	if(!is_uploaded_file($map['tmp_name'])){print_r($_FILES);  print_r($_REQUEST);exit;}
	
	$result=mysqli_QUERY($connection,"INSERT INTO staffdir.map (filename,dirNum,mapname,filetype,filesize) "."VALUES ('$file','$dirNum','$mapname','$type','$size')");
		$mid= mysqli_insert_id($connection);
	$ext = explode("/",$type);
	$uploaddir = "graphics/"; // make sure www has r/w permissions on this folder
	//$numTime=time();
	//$uploadfile = $uploaddir.$file.".".$numTime.".".$ext[1];
	$uploadfile = $uploaddir.$file;
	move_uploaded_file($map['tmp_name'],$uploadfile);// create file on server
		
	  $sql = "UPDATE map set link='$uploadfile' where mid='$mid'";
	$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
		mysqli_CLOSE($connection);
	header("Location: search.php?Submit=display&dirNum=$dirNum");
		} 
	
// ********** Edit a SD *************
if($admin=="update")
	{
	// $title=addslashes($title);
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
		
		$sql = "SELECT directive.*,map.mid,map.mapname, map.link, t3.mid as policy_mid
		From directive
		LEFT JOIN map on directive.dirNum=map.dirNum
		LEFT JOIN policy as t3 on t3.directive=directive.dirNum
		 $where";
		$result = @mysqli_query($connection,$sql) or die("Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
//		echo "$sql";
		$numrow = mysqli_num_rows($result);
		if($numrow < 1)
			{
			$join_policy="LEFT JOIN policy as t3 on t3.directive=directive.dirNum";
			if(!empty($dirNum))  // workaround for directive.dirNum not padded, 2013-4
				{
				$exp=explode("-",$dirNum);
				$var_dn=$exp[0]."-".($exp[1]+0); 
				$where="	WHERE directive.dirNum='$var_dn'";
				$join_policy="LEFT JOIN policy as t3 on t3.alt_directive=directive.dirNum";
				}
			$sql = "SELECT directive.*,map.mid,map.mapname, map.link, t3.mid as policy_mid
			From directive
			LEFT JOIN map on directive.dirNum=map.dirNum
			$join_policy
			 $where";
			$result = @mysqli_query($connection,$sql) or die("Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
			echo "$sql";
			$numrow = mysqli_num_rows($result);
			if($numrow < 1)
				{
				echo "<br><hr>No Staff Directive found using: <font color='blue'>$where</font>";exit;
				}
			}
		if($numrow>1)
			{
			while ($row=mysqli_fetch_array($result))
				{
				extract($row);
				$midA[]="$mapname <a href='adminMenu.php?admin=del&mid=$mid'>Delete</a><br>";
				$midB[$mid]="$mapname";
				$midC[]="$policy_mid";
				$midD[$mid]="$link";
				//&dirNum=$dirNum
				}
			}
		else
			{
			$row=mysqli_fetch_array($result); extract($row);
			$midA[]="$mapname <a href='adminMenu.php?admin=del&mid=$mid'>Delete</a><br>";
			$midB[$mid]="$mapname";
			$midC[]="$policy_mid";
			$midD[$mid]="$link";
			//&dirNum=$dirNum'
			}
		$a="Directive";$b="Directive<br>Date";$c="To:";$d="From:";
		$e="Subject";$f="Body";$g="Source";$h="Attachment(s)";$i="Revision(s)";
		echo "<form method='post' action='adminMenu.php'><table border='1'><tr>
		<th>$a</th><th></th><th></th>
		<th></th><th>$e<br>$g</th><th>$f</th>
		<th>$h</th><th>$i</th></tr>";
		echo "<tr>
		<td align='center'>Number:<input type='text' size='4' name='dirNum' value='$dirNum'><br>Date:<input type='text' size='10' name='dateDir' value='$dateDir'></td>
		<td align='center'></td>
		<td>To:<textarea name='dirTo' cols='30' rows='5'>$dirTo</textarea><br>
		From:<textarea name='dirFrom' cols='30' rows='1'>$dirFrom</textarea></td>
		<td>&nbsp;</td><td><textarea name='title' cols='30' rows='5'>$title</textarea><br><input type='text' size='10' name='source' value='$source'></td>
		<td><textarea name='body' cols='40' rows='20'>$body</textarea></td>
		<td align='center'><textarea name='attach' cols='40' rows='20'>$attach</textarea></td>
		<td align='center'><textarea name='misc' cols='20' rows='10'>$misc</textarea><br>WebLink:<br><textarea name='webLink' cols='20' rows='10'>$webLink</textarea><br>Title1 @ Link1, Title2 @ Link2</td></tr>
		<tr><td colspan='4' align='center'>
		<input type='hidden' name='admin' value='update'>
		<input type='submit' name='submit' value='update'>
		<input type='hidden' name='dirNum' value='$dirNum'>
		<input type='submit' name='submit' value='Add a File'>
		</form>&nbsp;&nbsp;&nbsp;&nbsp;<a href='adminMenu.php?admin=del&dirNum=$dirNum'> Delete SD</a> and All of its attachments</td>";

//echo "<pre>"; print_r($midD); print_r($midC); echo "</pre>"; // exit;	
		echo "<td colspan='2'><form method='POST' action='policy.php'>Indicate which file is shown on the Policy Home page:<br />";
		$none="";
		$jj=0;
		foreach($midD as $k=>$v)
			{
			if(in_array($k,$midC)){$ck="checked"; $none=1;}else{$ck="";}
			echo "$jj<input type='radio' name='mid' value=\"$k\" $ck  onchange=\"this.form.submit();\"> $v<br />";
			$jj++;;
			}
			if($none!=""){$ck="";}
		echo "<input type='radio' name='mid' value=\"\" $ck  onchange=\"this.form.submit();\"> none";
		echo "<input type='hidden' name='dirNum' value='$dirNum'>";
		echo "</form></td>";
		
		echo "<td colspan='2'>";
		$j=count($midA);
		for($i=0;$i<$j;$i++)
			{
			echo "$i $midA[$i]";
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
		echo "<html><head><title>Staff Directives</title></head>
		<body bgcolor='beige'><font size='4' color='004400'>Search the NC DPR Staff Directives</font>
		<br><br>$success</font>
		<hr><form name='search' method='post' action='adminMenu.php'>
		
		<table width='500'><tr>
		<td>Enter SD Number, e.g., 1992-02<br><input type='text' name='dirNum' value=''></td>
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
	// $title=addslashes($title);
// 	$body=addslashes($body);
// 	$attach=addslashes($attach);
// 	$misc=addslashes($misc);
// 	$dirFrom=addslashes($dirFrom);
	if($dirFromAlt!="")
		{
		$dirFromAlt=$dirFrom;
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
	
	$sql="SELECT DISTINCT dirFrom FROM directive ORDER BY dirFrom";
	$resultFrom = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	
	menuStuff();
	$a="Directive<br>Number<br />yyyy-xx";$b="Directive<br>Date<br />yyyy-mm-dd";$c="To:";$d="From:";
	$e="Subject";$f="Body";$g="Source e.g., MM/ae";$h="Attachment(s)";$i="Revision(s)";
	
	echo "<form method='post' action='adminMenu.php'><table border='1'><tr>
	<th>$a</th><th>$b</th><th>$c</th>
	<th>$d</th><th>$e<br>$g</th><th>$f</th>
	<th>$h</th><th>$i</th>
	</tr>";
	
	}// end if $admin=addForm
		
	if(!isset($dirNum)){$dirNum="";}
	if(!isset($dateDir)){$dateDir="";}
	if(!isset($dirTo)){$dirTo="";}
	echo "
	<tr>
	<td align='center'><input type='text' size='8' name='dirNum' value='$dirNum'></td>
	<td align='center'><input type='text' size='10' name='dateDir' value='$dateDir'></td>
	
	<td><textarea name='dirTo' cols='30' rows='3'>$dirTo</textarea></td>
	<td align='center'>
	<select name='dirFrom'>
	<option value=''>\n";
	while ($row=mysqli_fetch_array($resultFrom))
		{
		extract($row);
				if(@$dir==$dirFrom){$s1="selected";}else{$s1="value";}
		echo "<option $s1='$dirFrom'>$dirFrom\n";
		}
		
	if(!isset($misc)){$misc="";}
	if(!isset($attach)){$attach="";}
	if(!isset($body)){$body="";}
	if(!isset($source)){$source="";}
	if(!isset($title)){$title="";}
	echo "</select><br>
	<input type='text' size='20' name='dirFromAlt' value=''>";
	echo "</td>
	<td>Subject:<br>
	<textarea name='title' cols='30' rows='4'>$title</textarea><br>Source:<br><input type='text' size='10' name='source' value='$source'></td>
	<td>
	<textarea name='body' cols='40' rows='20'>$body</textarea></td>
	
	<td align='center'><textarea name='attach' cols='30' rows='20'>$attach</textarea></td>
	<td align='center'><textarea name='misc' cols='30' rows='20'>$misc</textarea></td>
	</tr>
	<tr><td colspan='8' align='center'>
	<input type='hidden' name='admin' value='Add'>
	<input type='submit' name='submit' value='Add'>
	</form></td></tr></table>";
echo "<hr></body></html>";

// *************** Display Menu FUNCTION **************
function menuStuff()
	{
	$align="align='center'";
	echo "<html><head><title>Admin Menu</title>
	<STYLE TYPE=\"text/css\">
	<!--
	body
	{font-family:sans-serif;background:beige}
	td
	{font-size:90%;background:beige}
	th
	{font-size:95%; vertical-align: bottom}
	--> 
	</STYLE></head>
	<body><font size='4' color='004400'>NC State Parks System Staff Directives</font>
	<br><font size='5' color='blue'>Administrative Function Menu
	</font><hr><table>
	<tr><td colspan='2' width='300'><b>Choose Action:</b>
		<table>
			<tr><td width='25'></td><td $align><FORM>
	<INPUT TYPE='button' value='Add A Staff Directive' onClick=\"location='adminMenu.php?admin=addForm'\">
	</FORM></td></tr>
			<tr><td width='25'></td><td $align><FORM>
	<INPUT TYPE='button' value='Edit A Staff Directive' onClick=\"location='adminMenu.php?admin=edit'\">
	</FORM></td></tr>
	<tr><td colspan='3'>Guidelines are added to eFile.</td>
	</tr>
	</table><hr>";
	
	}
?>