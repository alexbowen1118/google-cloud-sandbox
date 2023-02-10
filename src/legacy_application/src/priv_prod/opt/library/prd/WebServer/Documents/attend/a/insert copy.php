<?php
$dbTable="stats";
//$file="form.php";
//$fileMenu="menu.php";

include("../../../include/connectATTEND.inc");// database connection parameters

//echo "<pre>";print_r($_REQUEST);echo "</pre>";exit;

extract($_REQUEST);

// ******** Enter Records ***********
if($submit=="Enter")
	{
	//echo "<pre>";print_r($_REQUEST);echo "</pre>"; exit;
	//echo "t=$attend_tot[37]";exit;
	
	$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
	$result = mysql_query($sql) or die ("Couldn't execute query 1. $sql");
	while ($row=mysql_fetch_assoc($result))
			{$fieldName[]=$row['Field'];}
			
	$comments=addslashes($comments);
	$week=1;
	for($m=1;$m<=$weeksPass;$m++)
		{
		$weekpad=str_pad($week,2,"0",STR_PAD_LEFT);
		$monthpad=str_pad($monthPass,2,"0",STR_PAD_LEFT);
		$year_month_week=$yearPass.$monthpad.$weekpad;
		$updateFields="SET park='$parkPass',year_month_week='$year_month_week',comments='$comments'";
		
		for($i=3;$i<count($fieldName);$i++)
			{
			$key=$yearPass.$monthpad.str_pad($week,2,"0",STR_PAD_LEFT);
			$tf=$fieldName[$i];
			$eField=${$tf};$val=$eField[$key];
			$val=str_replace(",","",$val);// remove any commas
			if($val!=""){$updateFields.=",`".$fieldName[$i]."`='".$val."'";}
			}// end field for
		
		$query="REPLACE stats $updateFields";
		//echo "$query";exit;
		$result = mysql_query($query) or die ("Couldn't execute query 1. $query");
		$week=$week+1;
		}// end day for
	
	header("Location: /attend/a/form.php?parkcode=$parkPass&passM=$monthPass&e=1&yearPass=$yearPass");
	}

?>