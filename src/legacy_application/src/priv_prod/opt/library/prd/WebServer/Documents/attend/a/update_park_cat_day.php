<?php
$dbTable="park_category_day";
$database="park_use";

include("../../../include/iConnect.inc");// database connection parameters
mysqli_select_db($connection,$database);

//echo "<pre>";print_r($_REQUEST);echo "</pre>";exit;

extract($_REQUEST);

// ******** Enter Records ***********
if($submit=="Enter"){
//echo "<pre>";print_r($_REQUEST);echo "</pre>"; //exit;
//echo "t=$attend_tot[37]";exit;

$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_assoc($result))
	{
	$fieldName[]=$row['Field'];
	}


reset($fld_id);
while (list($key, $val) = each($fld_id))
	{
	if($val=="add")
		{
		$updateFields="SET park_id='$parkPass',category='$key'";
		$query="INSERT park_category_day $updateFields";
	//	echo "$query c=$key";exit;
		$result = mysqli_query($connection,$query);
		//print_r($mod_id_01);//exit;
		$m01=$mod_id_01[$key];
		$m02=$mod_id_02[$key];
		$m03=$mod_id_03[$key];
		$m04=$mod_id_04[$key];
		$m05=$mod_id_05[$key];
		$m06=$mod_id_06[$key];
		$m07=$mod_id_07[$key];
		$m08=$mod_id_08[$key];
		$m09=$mod_id_09[$key];
		$m10=$mod_id_10[$key];
		$m11=$mod_id_11[$key];
		$m12=$mod_id_12[$key];
		$submod=$mod_id_submod[$key];
		
		$updateMods="SET 
		mod01='$m01',mod02='$m02',mod03='$m03', mod04='$m04', 
		mod05='$m05',mod06='$m06',mod07='$m07', mod08='$m08', 
		mod09='$m09',mod10='$m10',mod11='$m11',mod12='$m12',
		submodifier='$submod' where park_id='$parkPass' and category='$key'";
		$query="UPDATE park_category_day $updateMods";
	//	echo "$query";exit;
		//if($key=="051"){echo "$query k=$keyField l=$key";exit;}
		$result = mysqli_query($connection,$query);
		}
	
	if($val=="del")
		{
		$updateFields="WHERE park_id='$parkPass' AND category='$key'";
		$query="DELETE FROM park_category_day $updateFields";
		//echo "$query";exit;
		$result = mysqli_query($connection,$query) or die ("Couldn't execute query 1. $query");
		}
	}// end while



header("Location: /attend/a/cat_day.php?parkcode=$parkPass");
}

?>