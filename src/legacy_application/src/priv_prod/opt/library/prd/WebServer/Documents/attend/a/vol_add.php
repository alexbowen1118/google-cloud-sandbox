<?php
//ini_set('display_errors',1);
$dbTable="vol_stats";

// extract($_REQUEST);
$database="attend";
include("../../../include/auth.inc");

$database="park_use";
include("../../../include/iConnect.inc");

mysqli_select_db($connection,$database);

// ******** Delete Record ***********
if(@$v=="del")
	{
	$sql = "DELETE from $dbTable where id='$id'";//echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	header("Location: /attend/a/vol_form.php?parkcode=$parkcode&passM=$passM&yearPass=$yearPass");
	}

// ******** Enter Records ***********
// echo "<pre>"; print_r($_POST); echo "</pre>";  exit;
$skip=array("submit");
if(@$submit=="Add")
	{
//	echo "<pre>";print_r($_REQUEST);echo "</pre>"; //exit;
	if(empty($_REQUEST['Lname'])){echo "You must enter a name. Click your browser's back button."; exit;}
	
		$updateFields="SET ";
	foreach($_REQUEST as $fld=>$val)
		{
		if(in_array($fld, $skip)){continue;}
		if($fld=="comments"||$fld=="category")
				{
				$_SESSION['attend'][$fld]=$val;
				}
		// 	if($fld=="comments"||$fld=="Lname")
// 				{$val=addslashes($val);}
				
				
			$val=str_replace(",","",$val);// remove any commas
		
			if($val!="" and $val!="0.0")
				{
				$updateFields.="`".$fld."`='".$val."', ";
				}
		
			}
	$updateFields=rtrim($updateFields,", ");
//echo "<pre>"; print_r($_SESSION); echo "</pre>";  exit;

	$query="INSERT INTO $dbTable $updateFields";
//		echo "$query<br>";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query 1. $query ".mysqli_error($connection));
	if($result){$e=1;}else{$e="";}
	extract($_REQUEST);	
	$monthPass=substr($year_month,-2);
	$yearPass=substr($year_month,0,4);   //echo "$monthPass $yearPass";  exit;
	
	$query="delete from vol_stats where Lname=''";
//		echo "$query<br>";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query 1. $query ".mysqli_error($connection));
	
	header("Location: /attend/a/vol_form.php?parkcode=$park&passM=$monthPass&e=$e&yearPass=$yearPass");
	}

?>