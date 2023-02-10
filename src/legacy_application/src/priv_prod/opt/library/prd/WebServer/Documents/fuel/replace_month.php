<?php
//echo "<pre>"; print_r($_POST); echo "</pre>";  exit;

include("../../include/connectROOT.inc");// database connection parameters
$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
	
	$month=$_POST['month'];
	$year=$_POST['year'];
	if(empty($year))
		{
		echo "There was a problem. No year was submitted. Contact Tom Howard";
		exit;
		}
	$center_code=$_POST['center_code'];
	
	$skip=array("year","submit","center_code","month");
	foreach($_POST as $id=>$array)
		{
		if(!is_array($array)){continue;}
				$clause="vehicle='$id',";
			foreach($array as $field=>$value)
				{
				if(in_array($field,$skip)){continue;}
				$valid="";
				if($field=="mileage" AND $value=="")
					{
					$clause.="`".$field."`=NULL,";
					}
				elseif($value!="")
					{
					$value=str_replace(",","",$value);
					$clause.="`".$field."`='".$value."',";
					}
				}
				$clause.="year='$year',`month`='$month'";
				
	 $sql= "REPLACE items SET $clause"; //echo "$sql <br />";
	$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql");
			}
//exit;	
header("Location: menu.php?form_type=form_A_month&year=$year&month=$month&park_code=$center_code");
?>