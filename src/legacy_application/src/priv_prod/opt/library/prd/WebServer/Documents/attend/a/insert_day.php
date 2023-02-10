<?php
$dbTable="stats_day";
ini_set('display_errors',1);

$database="attend";
include("../../../include/auth.inc");
$database="park_use";
include("../../../include/iConnect.inc");

// extract($_REQUEST);
mysqli_select_db($connection,$database);

// ******** Enter Records ***********
if($submit=="Enter")
	{
	
	$comments=$comments;
	
//	echo "<pre>";print_r($_POST);echo "</pre>"; exit;
	$fields=array_keys($_POST);
	//echo "<pre>";print_r($fields);echo "</pre>"; //exit;
	
	$park=strtoupper($_POST['parkPass']); // echo "p=$park"; exit;
	$year=$_POST['yearPass'];
	$month=str_pad($_POST['monthPass'], 2, 0, STR_PAD_LEFT);
	
	$skip=array("comments","modPass","yearPass","monthPass","parkPass","submit");
	for($i=1; $i<=count($_POST['attend_tot']);$i++)
		{
		$day=str_pad($i, 2, 0, STR_PAD_LEFT);
		$updateFields="park='$park', year_month_day='".$year.$month.$day."'";
		$tot=0;
		foreach($fields as $k=>$v)
			{
			if(in_array($v, $skip)){continue;}
			$value=str_replace(",","",$_POST[$v][$day]);
			$updateFields.=", $v='".$value."'";
			$tot+=$value;
			}
		if($tot>-1)
			{
			$query="REPLACE stats_day set $updateFields";
	//		echo "$query<br />";exit;
			$result = mysqli_query($connection,$query) or die ("Couldn't execute query 1. $query");
			}
			
		}
	
		$query="SELECT id from stats_day where park='$park' and year_month_day='".$year.$month."01'";
		$result = mysqli_query($connection,$query) or die ("Couldn't execute query 1. $query");
		$row=mysqli_fetch_assoc($result);
		$c=mysqli_num_rows($result); //echo "c=$c $query"; exit;
		if($c>0)
			{
			extract($row);
			$query="UPDATE stats_day set comments='$comments' where id='$id'";
			$result = mysqli_query($connection,$query) or die ("Couldn't execute query 1. $query"); //echo "$query"; exit;
			}
			else
			{
			$query="INSERT into stats_day set comments='$comments', park='$park', year_month_day='".$year.$month."01'";
			$result = mysqli_query($connection,$query) or die ("Couldn't execute query 1. $query"); //echo "$query"; exit;
			}
	
	}// end submit
	
//	exit;
	header("Location: /attend/a/form_day.php?parkcode=$park&passM=$month&e=1&yearPass=$year");
	

?>