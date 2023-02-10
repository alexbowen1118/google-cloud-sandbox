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
print_r($_FILES);
print_r($_REQUEST);
echo "</pre>";
exit;
*/
extract($_REQUEST);


	date_default_timezone_set('America/New_York');
if (@$submit == "Add File(s)")
		{
		foreach($_FILES['map']['error'] as $index=>$value)
			{
			if($value>0){continue;}
			
			$size = $_FILES['map']['size'][$index]; //echo "s=$size<br />";
			$type = $_FILES['map']['type'][$index];
			$file = $_FILES['map']['name'][$index];
			$file=str_replace("'","",$file);
			$file=str_replace("!","",$file);
			$file=str_replace(" ","_",$file);
			$file=str_replace(",","_",$file);
			$file=str_replace("[","",$file);
			$file=str_replace("]","",$file);
			$mapName = $file;
			$ext = substr(strrchr($file, "."), 1);// find file extention, png e.g.
			// print_r($_FILES); print_r($_REQUEST);exit;
			$up_file=$_FILES['map']['tmp_name'][$index];
			if(!is_uploaded_file($up_file))
				{
	//			echo "f=$up_file<pre>"; print_r($_FILES);  print_r($_REQUEST); echo "</pre>";			
	//			exit;
				}
// 			$mapName=html_entity_decode(htmlspecialchars_decode($mapName)); // not needed, not passed thru no_inject
			$mapName=mysqli_real_escape_string($connection,$mapName);
			if(!isset($dirNum)){$dirNum="";}
			$sql="INSERT INTO find.map (filename,forumID,dirNum,mapname,filetype,filesize) "."VALUES ('$file','$forumID','$dirNum','$mapName','$type','$size')";
	//		echo "$sql";exit;
			$result = @mysqli_query($connection,$sql) or die("$sql<br />Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
			
			$mid= mysqli_insert_id($connection);
			$year=date('Y');
			//	$ext = explode("/",$type);
			
			$folder="/opt/library/prd/WebServer/Documents/find/graphics/".$year;
			if (!file_exists($folder)) {mkdir ($folder, 0777);}
			
			$uploaddir = "graphics/".$year."/"; // make sure www has r/w permissions on this folder
			
			//$numTime=time();
			//$uploadfile = $uploaddir.$file.".".$numTime.".".$ext[1];
			$uploadfile = $uploaddir.$file;
			move_uploaded_file($_FILES['map']['tmp_name'][$index],$uploadfile);// create file on server
			
			$sql = "UPDATE map set link='$uploadfile' where mid='$mid'";
			$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
			
			$uploadfile="/find/".$uploadfile;
			if($exist=="y")
				{
				$varUP="concat_ws(',',weblink,'$uploadfile')";
				}
			else
				{
				$varUP="'$uploadfile'";
				}
			
			$sql = "UPDATE forum set weblink=$varUP where forumID='$forumID'";
			//  echo "$sql";exit;	
			
			}
	header("Location: forum.php?submit=edit&lastFld=forumID&var=$forumID");
	exit;
		}

?>