<?php
//echo "<pre>"; print_r($_POST); echo "</pre>";  exit;

include("../../include/connectROOT.inc");// database connection parameters
$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
	
	$year=$_POST['year'];
	$center_code=$_POST['center_code'];
	
	$skip=array("year","center_code","submit");
	foreach($_POST as $month=>$v){
		if(in_array($month,$skip)){continue;}
		$clause="";
			foreach($v as $field=>$value){
				if($value != ""){
					$clause.="`".$field."`='".$value."',";
				}
			}
			
			$clause.="`year`='$year', `month`='$month',`center_code`='$center_code'";
			
 $sql= "REPLACE form_b SET $clause"; //echo " $sql <br />";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql");
		}
	
header("Location: menu.php?form_type=form_B&year=$year&center_code=$center_code");
	

?>