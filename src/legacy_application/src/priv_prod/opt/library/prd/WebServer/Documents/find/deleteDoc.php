<?php
// ********* DELETE A Map Record AND ASSOC. FILE(S) ***************************
  
include("../../include/iConnect.inc");// database connection parameters
extract($_REQUEST); //print_r($_REQUEST);
if ($mid)
	{
	$database="find";
	  mysqli_select_db($connection,$database)
		   or die ("Couldn't select database");
	
	$sql="SELECT link from map where mid='$mid'";
// 	echo "$sql"; exit;
	$result=MYSQLI_QUERY($connection,$sql);
	$row=mysqli_fetch_array($result);
	extract($row);
	unlink($link);
	
	$sql="DELETE FROM map where mid='$mid'";
	$result=MYSQLI_QUERY($connection,$sql);
	
	$link="/find/".$link;
	$sql="UPDATE forum set weblink=trim(BOTH ',' from replace(weblink,'$link','')) where forumID='$forumID'";
	//ECHO "$sql";exit;
	$result=MYSQLI_QUERY($connection,$sql);
	
	header("Location: forum.php?forumID=$forumID&submit=Go");
	exit;
	}
?>