<?php
//print_r($_REQUEST);
//print_r($_SESSION);EXIT;
// called from Secure Server login.php
//if(empty($_SERVER['HTTP_COOKIE'])){exit;}

include("../../include/iConnect.inc");

mysqli_select_db($connection,'divper');
extract($_REQUEST);

$sql = "SELECT $db as level,tempID FROM emplist WHERE emid = '$emid' and tempID = '$tempID'";
$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
$num = @mysqli_num_rows($result);
if($num<1)
	{
	$sql = "SELECT $db as level,nondpr.currPark,nondpr.Fname,nondpr.Lname
	FROM nondpr 
	WHERE nondpr.tempID = '$tempID'";
	$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
	$num = @mysqli_num_rows($result);
	if($num<1){echo "Access denied";exit;}
	}
$row=mysqli_fetch_array($result);
//print_r($row);EXIT;
extract($row);

session_start();
$_SESSION[$db]['level'] = $level;
$_SESSION[$db]['tempID'] = $tempID;

if(@$forumID)
	{
	header("Location: forum.php?forumID=$forumID&submit=Go");
	}
	else
	{
	header("Location: forum.php");
	}
?>
