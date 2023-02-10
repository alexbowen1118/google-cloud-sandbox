<?php

ini_set('display_errors',1);
$database="fuel";
include("../../include/iConnect.inc");// database connection parameters
mysqli_select_db($connection,$database)
       or die ("Couldn't select database $database");
if(empty($_SESSION))
	{session_start();}
$tempID=$_SESSION['fuel']['tempID'];
$add_array=array();
$sql="select t1.* 
from dpr_radio_access as t1
where 1 "; 
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$add_array[$row['tempID']]=$row['access_level'];
	}
$temp_level=$_SESSION[$database]['level'];
if(array_key_exists($_SESSION['fuel']['tempID'], $add_array))
	{
	 $temp_level=$add_array[$tempID];
	}

if($temp_level<4){exit;}

$sql="select t1.* 
from dpr_radio_plugs as t1
where 1 
order by section"; 
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$section_array[$row['section']]=$row['section'];
	$make_array[$row['make']]=$row['make'];
	$model_array[$row['model']]=$row['model'];
	$software_array[$row['software']]=$row['software'];
	}
$search_array=array("section","make","model","software");
//  echo "<pre>"; print_r($make_array); echo "</pre>";
 if($_POST)
	{
	$pass_query="";
	$clause="";
// 	echo "<pre>"; print_r($_POST); echo "</pre>";
	$skip=array("rep","Submit","Find");
	foreach($_POST as $k=>$v)
		{
		if(!$v OR in_array($v,$skip)){continue;}
		$oper1="='";
		$oper2="' and ";
// 		$pass_query.=$k."=$v&";
		$clause.="t1.".$k.$oper1.$v.$oper2;
		}
	$clause="and ".rtrim($clause," and ");
// 	if(!empty($pass_query))
// 		{
// 		$pass_query=rtrim($pass_query,"&");
// 		}
// 	if(!empty($_POST['ncas_number']))
// 		{
// 		$pass_ncas_number=$_POST['ncas_number'];
// 		}
	}
 


$order_by="order by  t1.section, t1.id";

	
if($_SERVER['QUERY_STRING']!="form_type=dpr_radio_plugs")
	{
	$skip=array("rep");
	$exp1=explode("&",$_SERVER['QUERY_STRING']);
	if($level>4)
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
from dpr_radio_plugs as t1
where 1  $clause
$order_by"; 
// echo "$sql<br />"; exit;

$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");

if(mysqli_num_rows($result)<1)
	{
	echo "No items were found.";
	echo "<div align='center'><form method='post'><table border='1' cellpadding='5'>";
	if(!isset($pass_query)){$pass_query="";}

	echo "<tr><th><font color='black'>DPR Radio Plugs</th>";
	if(in_array($_SESSION['fuel']['tempID'], $add_array) OR $temp_level>2)
		{
		echo "<th><a href='edit_dpr_radio_plugs.php?submit=Add'>Add Plug</th>";
		}
	echo "<th><a href='menu.php?form_type=dpr_radio'>Radios</a></th></tr></table>";
		exit;
	}

while ($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
// 	if($row['active']=="YES"){@$active++;}
	}
if($level>4)
	{
// 	echo "<pre>"; print_r($ARRAY); echo "</pre>";
	}

$count_flds=count($ARRAY[0])-2;
$count_records=count($ARRAY);

if(@$_REQUEST['rep']=="")
	{
// 	echo $pass_query;
	echo "<div align='center'><form method='post'><table border='1' cellpadding='5'>";
	if(!isset($pass_query)){$pass_query="";}

	echo "<tr><th colspan='$count_flds'><font color='black'>$count_records DPR Radio Code Plugs</th>";
	if(in_array($_SESSION['fuel']['tempID'], $add_array) OR $temp_level>2)
		{
		echo "<th><a href='edit_dpr_radio_plugs.php?submit=Add'>Add</th>";
		}
	if($temp_level>3){echo "<td><a href='access.php'>Access</a></td>";}

	echo "<th colspan='1'><a href='dpr_radio_plugs.php?$pass_query&rep=1&sort=id'>Export</a></th>";
	if($level>4){echo "<td><a href='menu.php?form_type=dpr_radio_plugs'>Plugs</a></td>";}
	echo "</tr></table><table>";

	echo "<tr><td colspan='14'>Search: ";
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
	echo "<a href='menu.php?form_type=dpr_radio_plugs'>All Records</a></td></tr></table></form></div><table border='1' align='center' cellpadding='5'>";
// echo "</table></form></div>";
	}
	else
	{
	
// 	echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=dpr_radio_plugs.csv");
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
		$k1="<a href='menu.php?form_type=dpr_radio_plugs&sort=$k&sort_direction=$sort_direction'>$k1</a>";
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
				if($k=="id" and (in_array($_SESSION['beacon_num'], $add_array) OR $temp_level>2))
					{
					$v="<a href='edit_dpr_radio_plugs.php?id=$v'>$v</a>";
					}
				if($k=="file_link" and !empty($v))
					{
					$v="<a href='$v'>Download Plug</a>";
					}
				echo "<td>$v</td>";
				}
			echo "</tr>";
		}
		
	echo "</table>";
?>