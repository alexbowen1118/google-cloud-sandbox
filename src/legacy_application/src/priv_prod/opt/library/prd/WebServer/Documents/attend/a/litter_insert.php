<?php
$dbTable="litter_events";

//date_default_timezone_set('America/New_York');

extract($_REQUEST);


$database="park_use";
include("../../../include/iConnect.inc");

mysqli_select_db($connection,$database);


// ******** Delete Record ***********
if($submit=="Delete")
	{
	//echo "<pre>";print_r($_REQUEST);echo "</pre>"; //exit;
	
	$sql = "DELETE FROM $dbTable where id='$id'";//echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	header("Location: /attend/a/litter_form.php?parkcode=$parkPass&e=2&yearPass=$yearPass");
	exit;
	}


// ******** Enter Records ***********
if($submit=="Enter"||$submit=="Update")
	{
	//echo "<pre>";print_r($_REQUEST);echo "</pre>"; //exit;
	
	$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	while ($row=mysqli_fetch_assoc($result))
		{
//		extract($row);
		$fieldName[]=$row['Field'];
		}
	//echo "<pre>";print_r($fieldName);echo "</pre>"; //exit;
	
	$updateFields="set park='$parkPass'";
	
	for($i=2;$i<count($fieldName);$i++)
		{
		$tf=$fieldName[$i];
		$val=$_REQUEST[$tf];
		$val=addslashes($val);
		
		if($tf=="date_" and $val==""){$abort=1;}
		
		$updateFields.=",`".$fieldName[$i]."`='".$val."'";
		
		}
	//echo "$updateFields";exit;
	$query="REPLACE $dbTable $updateFields"; //echo "$query";exit;
	$result = mysqli_query($connection,$query);
	
	if(@$abort!=1){$e=1;}else{$e=0;}
	//echo "e=$e a=$abort";exit;
	header("Location: /attend/a/litter_form.php?parkcode=$parkPass&e=$e&yearPass=$yearPass");
	}

?>