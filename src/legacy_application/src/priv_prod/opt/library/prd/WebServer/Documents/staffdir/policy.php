<?php
ini_set('display_errors',1);
$database="staffdir";
include("../../include/auth.inc");// database connection parameters
//echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
include("../../include/iConnect.inc");// database connection parameters

mysqli_select_db($connection, $database)
       or die ("Couldn't select database $database");
       
extract($_POST);
//echo "<pre>"; print_r($_POST); echo "</pre>";  exit;
$sql="SELECT *  FROM policy where directive='$dirNum'";//echo "$sql"; exit;
$result=MYSQLI_QUERY($connection,$sql); 
if(mysqli_num_rows($result)<1)
	{
	$sql="INSERT INTO policy set mid='$mid', directive='$dirNum'";//echo "$sql"; exit;
	$result=MYSQLI_QUERY($connection,$sql); 
	}
if(!empty($mid))
		{
		$exp=explode("-",$dirNum);
		$var=str_pad($exp[1], "2","0",STR_PAD_LEFT);
		$dirNum=$exp[0]."-".$var;
		$sql="UPDATE policy set mid='$mid' where directive='$dirNum'";//echo "$sql"; exit;
		$result=MYSQLI_QUERY($connection,$sql); 
		}
		else
		{
		$exp=explode("-",$dirNum);
		$var=str_pad($exp[1], "2","0",STR_PAD_LEFT);
		$dirNum=$exp[0]."-".$var;
		$sql="UPDATE policy set mid='' where directive='$dirNum'";
		$result=MYSQLI_QUERY($connection,$sql);
		}
header("Location: adminMenu.php?admin=edit&dirNum=$dirNum");
	exit;
?>