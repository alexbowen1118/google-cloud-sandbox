<?php
//ini_set('display_errors',1);
$dbTable="vol_stats";

// echo "<pre>"; print_r($_POST); echo "</pre>";  exit;
$database="attend";
include("../../../include/auth.inc");
$database="park_use";
include("../../../include/iConnect.inc");
foreach($_POST as $index=>$array)
	{
	foreach($array as $fld=>$value)
		{
		$value=htmlspecialchars_decode($value);
		$_POST[$index][$fld]=$value;
		}
	}
// echo "<pre>"; print_r($_POST); echo "</pre>";  exit;

mysqli_select_db($connection,$database);

// ******** Delete Record ***********
if(@$v=="del")
	{
	$sql = "DELETE from $dbTable where id='$id'";//echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	header("Location: /attend/a/vol_form.php?parkcode=$parkcode&passM=$passM&yearPass=$yearPass");
	}

// ******** Enter Records ***********
if(@$submit=="Enter")
	{
// echo "<pre>";print_r($_POST);echo "</pre>"; exit;
	
	$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	while ($row=mysqli_fetch_assoc($result))
		{
		IF($row['Field']=="id"){continue;}
		$fieldName[]=$row['Field'];
		}
	//echo "<pre>";print_r($fieldName);echo "</pre>"; //exit;
	
	for($m=0;$m<count($_POST['Lname']);$m++)
		{
		$monthpad=str_pad($monthPass,2,"0",STR_PAD_LEFT);
		$year_month=$yearPass.$monthpad;
		$updateFields="SET park='$parkPass',`year_month`='$year_month'";
		
		for($i=3;$i<count($fieldName);$i++)
			{
			$tf=$fieldName[$i];
			$val=$_POST[$tf][$m];
// 			if($tf=="comments"||$tf="Lname"){$val=addslashes($val);}
			$val=str_replace(",","",$val);// remove any commas
			//echo "v=$val $tf i=$i m=$m<br>";
			if($val!="" and $val!="0.0")
				{
				$updateFields.=",`".$fieldName[$i]."`='".$val."'";
				}
			}// end field for
		
		if($_POST['Lname'][$m]!="")
			{// always keep a blank record
// 			$query="REPLACE $dbTable $updateFields";
			$id=$_POST['id_array'][$m];
			$query="UPDATE $dbTable $updateFields where id='$id'";
// 		echo "$query<br>";exit;
			$result = mysqli_query($connection,$query) or die ("Couldn't execute query 1. $query");
			if($result){$e=1;}else{$e="";}
			}
		}// end day for m
	//echo "u=$updateFields";
	//exit;
	if(!isset($e)){$e="";}
	header("Location: /attend/a/vol_form.php?parkcode=$parkPass&passM=$monthPass&e=$e&yearPass=$yearPass");
	}

?>