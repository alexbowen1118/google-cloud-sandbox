<?php
ini_set('display_errors',1);
$database="fuel";
include("../../include/iConnect.inc");// database connection parameters

mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
       
// echo "<pre>"; print_r($_POST); echo "</pre>";  //exit;

		include_once("menu.php");
//**** PROCESS  a Search ******
if(!empty($submit))
	{
	$sql="SELECT * FROM vehicle where license='$license'";
// 	echo "$sql";
	$result=mysqli_query($connection,$sql);
	while($row=mysqli_fetch_assoc($result))
		{
		$ARRAY[]=$row;
		}

if(!empty($ARRAY))
{
	$skip=array();
	$c=count($ARRAY);
	echo "<table><tr><td>$c</td></tr>";
	foreach($ARRAY AS $index=>$array)
		{
		if($index==0)
			{
			echo "<tr>";
			foreach($ARRAY[0] AS $fld=>$value)
				{
				if(in_array($fld,$skip)){continue;}
				echo "<th>$fld</th>";
				}
			echo "</tr>";
			}
		echo "<tr>";
		foreach($array as $fld=>$value)
			{
			if(in_array($fld,$skip)){continue;}
			$id=$array['id'];
			if($fld=="vin")
				{
				$value="<a href='edit.php?id=$id'>$value</a>";
				}
			echo "<td>$value</td>";
			}
		echo "</tr>";
		}
	}
}	


echo "<html><form method='POST' ACTION='find_any.php'><table>";
echo "<tr><td>
Plate Number: <input type='text' name='license' value=\"\"> e.g., P4RKL1F3
</td></tr>";
echo "<tr><td>
<input type='submit' name='submit' value=\"Find\">
Division-Owned Vehicle
</td></tr></table></form>";

if(empty($license)){exit;}

if(empty($ARRAY)){echo "No vehicle with plate number $license was found."; exit;}
echo "</html>";


?>