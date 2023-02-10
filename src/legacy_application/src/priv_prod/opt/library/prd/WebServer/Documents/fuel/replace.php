<?php
//echo "<pre>"; print_r($_POST); echo "</pre>";  exit;

include("../../include/connectROOT.inc");// database connection parameters
$database="fuel";
$db = mysqli_select_db($connection,$database)
	or die ("Couldn't select database");

$id=$_POST['id'];
$year=$_POST['year'];
@$center_code=$_POST['center_code'];
$oft=$_POST['other_fuel_type'];
$oot=$_POST['other_oil_type'];
if(empty($year))
	{
	echo "There was a problem. No year was submitted. Contact Tom Howard";
	exit;
	}
$skip=array("year","id","submit","other_fuel_type","other_oil_type","center_code");
foreach($_POST as $month=>$v){
	if(in_array($month,$skip)){continue;}
	$clause="";
	$valid="";
	foreach($v as $field=>$value){
		if($value != ""){
		$value=str_replace(",","",$value);
		if($field=="mileage" AND $value=="")
			{$clause.="`".$field."`='".$value."',";}
		else
			{$clause.="`".$field."`='".$value."',";}
		
		$valid.=$value;
		}
	}
	if($valid==""){continue;}	
		
	$clause.="year='$year', `vehicle`='$id', `month`='$month',`other_fuel_type`='$oft',`other_oil_type`='$oot'";
		
	$sql= "REPLACE items SET $clause"; //echo "v=$valid $sql <br />";
	$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql");
}
//exit;	
header("Location: menu.php?form_type=form_A&year=$year&v_id=$id&center_code=$center_code");
	

?>