<?php

ini_set('display_errors',1);
$database="fuel";
include("../../include/iConnect.inc");// database connection parameters
mysqli_select_db($connection,"divper")
       or die ("Couldn't select database $database");

$add_array=array();       
$sql="SELECT t1.position, t2.tempID, t2.currPark as park, t2.fuel as access_level
FROM `B0149` as t1 
left join emplist as t2 on t1.position=t2.beacon_num
WHERE t1.`position_desc` LIKE '%Superintendent%'
";
$result = mysqli_query($connection,$sql) or die ("15 Couldn't execute query 1. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$add_array[$row['tempID']]=$row['access_level'];
	}

mysqli_select_db($connection,$database)
       or die ("Couldn't select database $database");

if(empty($_SESSION))
	{session_start();}
$tempID=$_SESSION['fuel']['tempID'];
	
$temp_level=$_SESSION[$database]['level'];
if(array_key_exists($_SESSION['fuel']['tempID'], $add_array))
	{
	 $temp_level=$add_array[$tempID];
	}

if($_SESSION['fuel']['tempID']=="Crate5973"){$temp_level=4;}

	if($level>4)
		{
// 		echo "<pre>"; print_r($_REQUEST); echo "</pre>";
		}
$vehicle_license_array=array();
$sql="select t1.license
from vehicle as t1
where 1 
order by license"; 
$result = mysqli_query($connection,$sql) or die ("29 Couldn't execute query 1. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$vehicle_license_array[]=$row['license'];
	}

if($level>4)
	{
// 	echo "67<pre>"; print_r($vehicle_license_array); echo "</pre>";
	}
	
$sql="select t1.* 
from dpr_radio as t1
where 1 
order by section"; 
$result = mysqli_query($connection,$sql) or die ("60 Couldn't execute query 1. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$table_flds=array_keys($row);
	$section_array[$row['section']]=$row['section'];
	$type_array[$row['type']]=$row['type'];
	$make_array[$row['make']]=$row['make'];
	$model_array[$row['model']]=$row['model'];
	$frequency_array[$row['frequency']]=$row['frequency'];
	$license_array[$row['vehicle_license']]=$row['vehicle_license'];
	$condition_array[$row['condition_']]=$row['condition_'];   // condition is a reserved work in MariaDB
	}

$search_array=array("section","type","make","model","frequency","condition");
$pass_query_array=array();

// echo "<pre>"; print_r($table_flds); echo "</pre>";
//  echo "<pre>"; print_r($make_array); echo "</pre>";

// echo "58<pre>"; print_r($_POST); print_r($_GET); echo "</pre>";


$temp_x=array_keys($_GET);
if($level>4)
	{
// 	echo "85<pre>"; print_r($temp_x); print_r($_GET); echo "</pre>";
	}
foreach($table_flds as $k=>$v)
	{
	if(in_array($v, $temp_x) and empty($_POST[$v]))
		{
		$_POST[$v]=$_GET[$v];
		}
	}
 if($_POST)
	{
	$pass_query="";
	$clause="";
	$clause_no_section="";
// 	echo "78<pre>"; print_r($_POST); echo "</pre>";
	$skip=array("rep","Submit","Find","submit_form");
	foreach($_POST as $k=>$v)
		{
		if(!$v OR in_array($v,$skip)){continue;}
		if($k=="rep"){continue;}
		if($k=="condition"){$k="condition_";}  // condition is a reserved work in MariaDB
		$oper1="='";
		$oper2="' and ";
		$pass_query_array[$k]=$v;
		$clause.="t1.".$k.$oper1.$v.$oper2;
		if($k!="section")
			{
			$clause_no_section.="t1.".$k.$oper1.$v.$oper2;
			}
		}
	$clause="and ".rtrim($clause," and ");
	$clause_no_section="and ".rtrim($clause_no_section," and ");

// 	echo "<pre>"; print_r($pass_query_array); echo "</pre>";

	}

$order_by="order by  t1.section, t1.id";

	
if($_SERVER['QUERY_STRING']!="form_type=dpr_radio")
	{
	$skip=array("rep","submit_form");
	$exp1=explode("&",$_SERVER['QUERY_STRING']);
	if($temp_level>4)
		{
// 		echo "<pre>"; print_r($exp1); echo "</pre>";
		}
	$pass_query=$_SERVER['QUERY_STRING'];
	
	if(@$sort=="comments")
		{
		$desc="DESC";
		$order_by="order by $sort $desc";
		}
		else
		{
		if(!empty($sort))
			{$sort="t1.$sort ";}
			else
			{$sort="id";}
		@$order_by="order by $sort $sort_direction";
		}
	}

if(!isset($clause)){$clause="";}

$sql="select t1.* 
from dpr_radio as t1
where 1  $clause
$order_by"; 
$result = mysqli_query($connection,$sql) or die ("154 Couldn't execute query 1. $sql");
if($level>4)
	{
// 	echo "134 $sql<br />";
	}
if(mysqli_num_rows($result)<1)
	{
	$sql="select t1.* 
	from dpr_radio as t1
	where 1  $clause_no_section
	$order_by"; 
	$result = mysqli_query($connection,$sql) or die ("165 Couldn't execute query 1. $sql");
	if(mysqli_num_rows($result)<1)
		{
		echo "No items were found.";exit;
		}
	}

while ($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	}
if($temp_level>4)
	{
// 	echo "<pre>"; print_r($ARRAY); echo "</pre>";
	}

$count_flds=count($ARRAY[0])-2;
$count_records=count($ARRAY);

if(@$_REQUEST['rep']=="")
	{
// 	echo $clause;
	echo "<div align='center'><form method='post'><table border='1' cellpadding='5'>";

	echo "<tr><th colspan='$count_flds'><font color='brown'>$count_records DPR Mobile, Portable, Base Station, and Repeater Radios</th>";
	if(in_array($_SESSION['fuel']['tempID'], $add_array) OR $temp_level>0)
		{
		echo "<th><a href='edit_dpr_radio.php?submit=Add'>Add</th>";
		}
	if($temp_level>3){echo "<td><a href='access.php'>Access</a></td>";}

	if($level>4)
		{
// 		echo "<pre>"; print_r($_REQUEST); echo "</pre>";
	echo "<th colspan='1'>";
	foreach($_POST as $k=>$v)
		{
		echo "<input type='hidden' name='$k' value=\"$v\">";
		}
// 	echo "<input type='hidden' name='rep' value=\"1\">";
	echo "<input type='submit' name='submit_form' value=\"Export\"></th>";
		}

// 	<a href='dpr_radio.php?$pass_query&rep=1&sort=id'>Export</a></th>";
	
	if($temp_level>3){echo "<td><a href='menu.php?form_type=dpr_radio_plugs'>Plugs</a></td>";}
	echo "</tr></table>";

	echo "<table><tr><td colspan='14'>Search: ";
	foreach($search_array as $k=>$v)
		{
		$temp_array=${$v."_array"};
		sort($temp_array);
		echo "$v
		<select name='$v' onchange=\"this.form.submit()\"><option value='' selected></option>\n";
		foreach($temp_array as $k=>$v)
			{
			if(empty($v)){continue;}
			echo "<option value='$v'>$v</option>\n";
			}
		echo "</select>&nbsp;&nbsp;&nbsp;";
		}
	echo "<a href='menu.php?form_type=dpr_radio'>All Records</a></td></tr></table></form>
	<table border='1' cellpadding='5' bgcolor='beige'>";
// echo "</table></form></div>";
	}
	else
	{
	
// 	echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=dpr_radio.csv");
		// Disable caching
		header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
		header("Pragma: no-cache"); // HTTP 1.0
		header("Expires: 0"); // Proxies
		
		
		function outputCSV($header_array, $data) {
		
		$comment_line[]=array("To prevent Excel from converting the long SN to scientific notation an apostrophe is prepended to those values and only to those values.");
			$output = fopen("php://output", "w");
			foreach ($comment_line as $row) {
				fputcsv($output, $row); // here you can change delimiter/enclosure
			}
			foreach ($header_array as $row) {
				fputcsv($output, $row); // here you can change delimiter/enclosure
			}
			foreach ($data as $row) {
			$row['serial_number']="'".$row['serial_number'];
				fputcsv($output, $row); // here you can change delimiter/enclosure
			}
		fclose($output);
		}

		$header_array[]=array_keys($ARRAY[0]);
// 		echo "<pre>"; print_r($header_array); print_r($comment_line); echo "</pre>";  exit;
		outputCSV($header_array, $ARRAY);
		exit;
		
		
	}
if(empty($sort_direction) or $sort_direction=="desc")
	{
	$sort_direction="asc";
	}
	else
	{
	$sort_direction="desc";
	}

if(empty($pass_query))
	{$pass_query="";}
	else
// 	{$pass_query="&".$pass_query;}
// echo "<div><table border='1' cellpadding='5'>";
echo "<tr>";
foreach($ARRAY[0] as $k=>$v)
	{
	$k1=str_replace("_"," ",$k);
	if($temp_level>0)
		{
		$link="form_type=dpr_radio&sort=$k&sort_direction=$sort_direction";
		if(!empty($clause))
			{
			$clause=str_replace("and t1.", "",$clause);
			$clause=str_replace("'", "",$clause);
			$link.="&".$clause;
			}
		$k1="<a href='menu.php?$link'>$k1</a>";
		}

	echo "<th>$k1 </th>";
	}
	echo "</tr>";

// echo "<table>";
foreach($ARRAY as $num=>$value_array)
		{
			echo "<tr>";
			foreach($value_array as $k=>$v)
				{
				if($k=="id" and (in_array($_SESSION['beacon_num'], $add_array) OR $temp_level>0))
					{
					if($temp_level>1 or $value_array['section']==$_SESSION['fuel']['select'])
						{
						$v="<a href='edit_dpr_radio.php?id=$v'>$v</a>";
						}
					}
				if($k=="vehicle_license" and in_array($v, $license_array))
					{
					if($level>1)
						{
						
						if(in_array($v, $vehicle_license_array))
							{
							$v="<form method='post' action='menu.php?form_type=inventory' target='_blank'>
							<input type='hidden' name='license' value=\"$v\">
							<input type='submit' name='search' value=\"Find\">
							</form><font color='brown'>$v</font>";
							}
							else
							{
							if(!empty($v))
								{
								$v="License $v NOT found in Vehicle Inventory.";
								}
							}
						}
						else
						{
	// 					if(empty($_POST['section']))
// 							{
// 							$_POST['section']=$_SESSION['fuel']['select'];
// 							}
// 						if(!empty($v) and $value_array['section']==$_POST['section'])
						if(in_array($v, $vehicle_license_array))
							{
							$v="<form method='post' action='menu.php?form_type=inventory' target='_blank'>
	
							<input type='hidden' name='license' value=\"$v\">
							<input type='submit' name='search' value=\"Find\">
							</form><font color='brown'>$v</font>";
							}
							else
							if(!empty($v))
								{
								$v="License $v NOT found in Vehicle Inventory.";
								}
						}
					}
				echo "<td>$v</td>";
				}
			echo "</tr>";
		}
		
	echo "</table>";
?>