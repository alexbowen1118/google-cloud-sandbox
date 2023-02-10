<?php
$dbTable="recycle_stats";

extract($_REQUEST);

$database="park_use";
include("../../../include/iConnect.inc");

mysqli_select_db($connection,$database);

// ******** Enter Records ***********
if($submit=="Enter"){
//echo "<pre>";print_r($_REQUEST);echo "</pre>"; //exit;

foreach($_REQUEST['aluminum'] as $rk=>$rv){$ym[]=$rk;}
//print_r($ym);

$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_assoc($result))
	{
//	extract($row);
	$fieldName[]=$row['Field'];
	}

//echo "<pre>";print_r($fieldName);echo "</pre>"; //exit;


foreach($ym as $k=>$v)
	{
	$updateFields="set park='$parkPass',`year_month`='$v'";
	
	for($i=3;$i<count($fieldName);$i++)
		{
		$tf=$fieldName[$i];
		$val=$_REQUEST[$tf][$v];
		$val=addslashes($val);
		
		$updateFields.=",`".$tf."`='".$val."'";
		}
	
	//echo "$updateFields<br>";
	//exit;
	$query="REPLACE $dbTable $updateFields"; //echo "$query";exit;
	$result = mysqli_query($connection,$query);if($result){$e=1;}
	}
//echo "$updateFields";
header("Location: /attend/a/recycle_form.php?parkcode=$parkPass&e=$e&yearPass=$yearPass");
}

?>