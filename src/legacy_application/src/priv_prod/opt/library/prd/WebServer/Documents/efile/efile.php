<?php
if(empty($_SERVER['HTTP_COOKIE'])){exit;}

include("../../include/iConnect.inc");
mysqli_select_db($connection,'divper');
extract($_REQUEST);

$database="efile";
$sql="SELECT $database as level, currPark, accessPark 
from emplist where emid='$emid'";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute select query. $sql ");
$row=mysqli_fetch_assoc($result);
//echo "<pre>"; print_r($row); echo "</pre>";  exit;
session_start();

$_SESSION[$database]['level']=$row['level'];
$_SESSION[$database]['tempID']=$tempID;
$_SESSION[$database]['emid']=$emid;
$_SESSION[$database]['select']=$row['currPark'];
if(!empty($row['accessPark']))
	{
	$_SESSION[$database]['accessPark']=$row['accessPark'];
	}
header("Location: home.php");
?>
