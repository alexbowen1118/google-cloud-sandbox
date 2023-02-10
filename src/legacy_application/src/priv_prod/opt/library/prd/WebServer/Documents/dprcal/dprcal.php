<?php
//print_r($_REQUEST);print_r($_SESSION);EXIT;
// called from Secure Server login.php
// Secure Server logs user and their level into $dbL.login
// This page then sets user access variables
extract($_REQUEST);
$dbL=$db;// $db used in connectXXXX.inc, original $db gets overwritten
$u=strtoupper($db);
$file="../../include/iConnect.inc";
include("$file");
mysqli_select_db($connection,'divper');
session_start();

$sql = "SELECT $dbL as level, currPark as park, emid, jobtitle as posTitle 
FROM emplist WHERE tempID = '$tempID'";
$result = @mysqli_query($connection,$sql) or die("$sql Error 1# $file". mysqli_errno($connection) . ": " . mysqli_error($connection));
$num = @mysqli_num_rows($result);
if($num<1)
	{
	$sql = "SELECT $dbL as level, currPark as park, emid
	FROM nondpr WHERE tempID = '$tempID'";
	$result = @mysqli_query($connection,$sql) or die("$sql Error 1# $file". mysqli_errno($connection) . ": " . mysqli_error($connection));
	$num = @mysqli_num_rows($result);
	if($num<1)
		{
		echo "Access denied";exit;
		}
	}
$row=mysqli_fetch_array($result);//print_r($row);exit;
extract($row);
$_SESSION[$dbL]['loginS']=$tempID;
$_SESSION[$dbL]['parkS']=$park;
$_SESSION[$dbL]['emid']=$emid;
$_SESSION[$dbL]['posTitle']=@$posTitle;
$_SESSION[$dbL]['level']=$level;

switch ($level) {
		case "4":
$_SESSION[$dbL]['levelS']="SUPERADMIN";
header("Location: adminSuper.php");
			break;	
		case "3":
$_SESSION[$dbL]['levelS']="ADMIN";
header("Location: admin.php");
			break;
		case "2":
$_SESSION[$dbL]['levelS']="DIST";
header("Location: admin.php");
			break;
		case "1":
$_SESSION[$dbL]['levelS']="PARK";
header("Location: index.php");
			break;	
		default:
			ECHO "Access denied.";
	}// end switch
//header("Location: index.php");

?>