<?php
//echo "<pre>"; print_r($_FILES);  print_r($_POST);echo "</pre>";  exit;

include("../../include/connectROOT.inc");// database connection parameters
$database="dpr_forum";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

EXTRACT($_REQUEST);
// ********* DELETE A DIRECTIVE AND ASSOC. FILE(S) ***************************
  
if ($admin == "del")
{
if($dirNum!=""){
$sql="SELECT link from map where dirNum='$dirNum'";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_array($result)){
extract($row);unlink($link);
}

$sql="DELETE FROM map where dirNum='$dirNum'";
$result=mysqli_query($connection, $sql);
$sql="DELETE FROM directive where dirNum='$dirNum'";
$result=mysqli_query($connection, $sql);
header("Location: search.php?Submit=display&dirNum=$dirNum");}
else{
$sql="SELECT link,dirNum from map where mid='$mid'";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_array($result)){
extract($row);unlink($link);
}
$sql="DELETE FROM map where mid='$mid'";
$result=mysqli_query($connection, $sql);
header("Location: search.php?Submit=display&dirNum=$dirNum");}
exit;
}


if ($submit == "Add File") {
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
extract($_FILES);
$size = $_FILES['map']['size'];
$type = $_FILES['map']['type'];
$file = $_FILES['map']['name'];
$mapName = $file;
$ext = substr(strrchr($file, "."), 1);// find file extention, png e.g.
// print_r($_FILES); print_r($_REQUEST);exit;
if(!is_uploaded_file($map['tmp_name'])){print_r($_FILES);  print_r($_REQUEST);
exit;}

$sql="INSERT INTO map (filename,forumID,dirNum,mapname,filetype,filesize) "."VALUES ('$file','$forumID','$dirNum','$mapName','$type','$size')";
//echo "$sql";exit;
$result = @mysqli_query($connection, $sql) or die("$sql<br />Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));

    $mid= mysqli_insert_id($connection);
$ext = explode("/",$type);
$uploaddir = "uploads"; // make sure www has r/w permissions on this folder

//    echo "$folder"; exit;
if (!file_exists($uploaddir)) {mkdir ($uploaddir, 0777);}

$yearMonth=date("Ym");
    $folder = $uploaddir."/".$yearMonth;
    
//    echo "$folder"; exit;
if (!file_exists($folder)) {mkdir ($folder, 0777);}
//exit;

$uploadfile = $folder."/".$file;
move_uploaded_file($map['tmp_name'],$uploadfile);// create file on server
    chmod($uploadfile,0777);
    
  $sql = "UPDATE map set link='$uploadfile' where mid='$mid'";
$result = @mysqli_query($connection, $sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));

$uploadfile="/dpr_forum/".$uploadfile;
if($exist=="y"){$varUP="concat_ws(',',weblink,'$uploadfile')";}
else {$varUP="'$uploadfile'";}

  $sql = "UPDATE forum set weblink=$varUP where forumID='$forumID'";
//  echo "$sql";exit;
$result = @mysqli_query($connection, $sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
    mysqli_close($connection);
header("Location: forum.php?submit=edit&lastFld=forumID&var=$forumID");
exit;	} 
	
?>