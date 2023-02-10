<?php
//if(empty($_SERVER['HTTP_COOKIE'])){exit;}

include("../../include/iConnect.inc");

//echo "<pre>"; print_r($_SERVER); echo "</pre>";exit;

// extract($_REQUEST);
$database="staffdir";
mysqli_select_db($connection,'divper');
$sql="SELECT $database as level, currPark, accessPark 
from emplist where emid='$emid'";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute select query. $sql ");
$row=mysqli_fetch_assoc($result);

session_start();

$_SESSION[$database]['level']=$row['level'];
$_SESSION[$database]['tempID']=$tempID;
$_SESSION[$database]['emid']=$emid;
$_SESSION[$database]['select']=$row['currPark'];
if(!empty($row['accessPark']))
	{
	$_SESSION[$database]['accessPark']=$row['accessPark'];
	}

if($row['level']>2)
	{header("Location: menu.php");}
	else
	{header("Location: list_policies.php");}

?>
