<?php
$database="facilities";
include("../../include/auth.inc");// database connection parameters
$tempID=$_SESSION[$database]['tempID'];

if(!empty($_SESSION[$database]['accessPark']))
	{
	$multi_park=explode(",",$_SESSION[$database]['accessPark']);
	}

//echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
include("../../include/iConnect.inc");// database connection parameters
mysqli_select_db($connection,$database)
       or die ("Couldn't select database $database");
$title="Facility Website"; 
include("/opt/library/prd/WebServer/Documents/facilities/_base_top_fac.php");

if($level==2)
	{
	$dist=$_SESSION[$database]['select'];
	include("../../include/get_parkcodes_reg.php");
	$limit_dist=${"array".$dist};
// 	echo "$dist<pre>"; print_r($limit_dist); echo "</pre>"; // exit;
	$dist_parks="and (";
	foreach($limit_dist as $k=>$v)
		{
		$dist_parks.="park_abbr='$v' OR ";
		}
	$dist_parks=rtrim($dist_parks," OR ").")";
	mysqli_select_db($connection,"facilities"); // database
	}

if($level==1)
	{
	$dist_parks="and park_abbr='".$_SESSION[$database]['select']."'";
	if(!empty($multi_park))
		{
		$var="and (";
		foreach($multi_park as $k=>$v)
			{
			$v=trim($v);
			$var.="park_abbr='$v' OR ";
			}
		$dist_parks=rtrim($var," OR ").")";
		}
	}	
	
$sql="SELECT COUNT( * ) AS `Rows` , `fac_type`
	FROM `spo_dpr`
	GROUP BY `fac_type`
	ORDER BY `fac_type`
	";

if($level<3)
	{
	$sql = "SELECT COUNT( * ) AS `Rows` , `fac_type`
	FROM spo_dpr
	where 1 $dist_parks		
	GROUP BY `fac_type`
	ORDER BY `fac_type`"; 
	}
	
// 	echo "$sql";	exit;

$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. Check to make sure of the district. <br />$sql<br />".mysqli_error($connection));
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY_fac_type[$row['fac_type']]=$row['Rows'];
	}


$sql="SELECT COUNT( * ) AS `Rows` , `park_abbr`
	FROM `spo_dpr`
	GROUP BY `park_abbr`
	ORDER BY `park_abbr`
	";

if($level<3)
	{
	$sql = "SELECT COUNT( * ) AS `Rows` , `park_abbr`
	FROM spo_dpr
	where 1 $dist_parks		
	GROUP BY `park_abbr`
	ORDER BY `park_abbr`";//echo "$sql";	
	}
		
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY_park_abbr[$row['park_abbr']]=$row['Rows'];
	}

echo "<table align='center'><tr><td></td><td><h2>NC State Parks System - Facility  Website</h2></td></tr>";

$path="/facilities/";

$restrict_residences_array=array("60033042","60033112");
// 60033042 GIS
// Technology Support Analyst
$hide_projects=1;
$test_bn=$_SESSION['facilities']['beacon_num'];
if(!in_array($test_bn,$restrict_residences_array))  // provide GIS specialist access to all parks but not the park residence rents
	{
	$menu_array['Park Residences']=$path."find.php";
	$hide_projects="";
	}
	
$menu_color=array("#EE82EE","#E9967A","#009900","#FFE4C4","#DAA520","#48D1CC","#C6E2FF","#FFB6C1","#7FFFD4");

if($level>2 and empty($hide))
	{
	$menu_array['Project Numbers']=$path."partf_project_numbers.php";
	}

if($level>3 or $_SESSION['facilities']['working_title']=="Personnel Technician")
	{
	$menu_array['Upload Rent']=$path."import_text.php";
	}
	
if($level>3)
	{
	$menu_array['Park Comments']=$path."park_comments.php";
	$menu_array['Uploaded Housing Agreements']=$path."housing_agreements.php";
	}

// $tc_array=array("Shimel1060","Mumford5889", "Head1611");
// if($level>4 or in_array($tempID, $tc_array))
// 	{
// 	$menu_array['Traffic Counters']=$path."counters.php";
// 	}

$menu_array['Traffic Counters']=$path."counters.php";
		
$i=0;
foreach($menu_array as $k=>$v)
	{
	
	$color=$menu_color[$i]; $i++;
	$target="";
	echo "<tr><td align='left'><form action='$v' $target>
	<input type='submit' name='fac_type' value='$k'  style=\"background-color:$color; font-size:larger\"></form></td></tr>";
	}
echo "</table><hr />";
echo "<table align='center'><tr><td>All Facilities by <strong>Facility Type</strong> or by <strong>Park</strong>:</td></tr>";

	echo "<tr><td align='left'><select name='fac_type' onChange=\"MM_jumpMenu('parent',this,0)\"><option selected=''>Facility Type - # facilities</option>\n";
	foreach($ARRAY_fac_type as $k=>$v)
		{
		echo "<option value='find_fac_type.php?submit_label=Find&fac_type=$k'>$k-$v</option>\n";
		}
	echo "</select></td></tr>";
	
	
	echo "<tr><td align='left'><select name='park_abbr' onChange=\"MM_jumpMenu('parent',this,0)\"><option selected=''>Park - # facilities</option>\n";
	foreach($ARRAY_park_abbr as $k=>$v)
		{
		echo "<option value='find_park_abbr.php?park_abbr=$k'>$k-$v</option>\n";
		}
	echo "</select></td></tr>";
	
echo "</table>";
echo "</body></html>";
?>