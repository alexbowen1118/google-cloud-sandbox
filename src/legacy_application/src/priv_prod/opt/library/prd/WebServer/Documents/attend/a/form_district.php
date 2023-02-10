<?php
// echo "<pre>"; print_r($_POST); echo "</pre>"; // exit;
ini_set('display_errors',1);
date_default_timezone_set('America/New_York');

$database="attend"; // I messed up and used two different names for this app
include("../../../include/auth.inc");
$database="park_use";
include("../../../include/iConnect.inc");

include("../../../include/get_parkcodes_reg.php");

include("park_code_areas.php"); // get subunits


$dbTable="stats_day";
$file="form_day.php";
$fileMenu="../menu.php";

// echo "<pre>"; print_r($_SESSION); echo "</pre>";
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";

if(isset($parkcode)){$passPark=$parkcode;}else{$parkcode=$_SESSION['attend']['select'];}

include("$fileMenu");

$level=$_SESSION['attend']['level']; //echo "l=$level"; print_r($_SESSION);

$temp_year=date("Y");
$temp_month=date("m");
echo "<form action='form_district.php' method='post'>";
echo "<table><tr>";
echo "<td>District: <select name='district'><option value=\"\"></option>\n";
echo "<option value=\"WEST\">WEST</option>";
echo "<option value=\"NORTH\">NORTH</option>";
echo "<option value=\"SOUTH\">SOUTH</option>";
echo "<option value=\"EAST\">EAST</option>";
echo "</select></td>";
echo "<td>Year: <input type='text' name='year' value=\"$temp_year\" size='5'></td>";
echo "<td>Month: <input type='text' name='month' value=\"$temp_month\" size='5'></td>";
echo "<td><input type='submit' name='submit_form' value=\"Submit\"></td>";
echo "</tr><table>";

echo "</form>";

if(empty($district)){echo "Select a District"; exit;}


$database="dpr_system";
mysqli_select_db($connection,$database);

$sql="SELECT * FROM budget_center 
where dist='$district'";
$result = mysqli_query($connection,$sql) or die ();
while($row=mysqli_fetch_assoc($result))
	{
	$dist_array[]=$row;
	}

// echo "$sql<pre>"; print_r($dist_array); echo "</pre>";  exit;

// $dist_array=array("CABE"=>"east","HABE"=>"east","LAWA"=>"east");

foreach($dist_array as $index=>$array)
	{
	$temp[]="park like '".$array['parkCode']."%'";
	}
$clause=implode(" or ",$temp);

if($level>4)
	{
// echo "$clause<pre>"; print_r($temp); echo "</pre>";  exit;
	}
	
$y=$year;
$passM=$month;
$num_days=date('t', mktime(0, 0, 0, $month, 1, $year));

for($i=1; $i<=$num_days; $i++)
	{
	$day_array[]=$i;
	}
//print_r($day_array);

mysqli_select_db($connection,"park_use");

if(!$parkcode){exit;}

$database="park_use";
mysqli_select_db($connection,$database);

// *********** Get previously entered values *************
$fieldList=@$fieldName[0];
for($l=1;$l<count(@$fieldName);$l++)
	{
	$fieldList.=",".$fieldName[$l];
	}


$testYMW1=$y.str_pad($passM,2,"0",STR_PAD_LEFT)."01"; 
$testYMWx=$y.str_pad($passM,2,"0",STR_PAD_LEFT).end($day_array);
$testYMW2=($y-1).str_pad($passM,2,"0",STR_PAD_LEFT)."01"; 
$testYMWy=($y-1).str_pad($passM,2,"0",STR_PAD_LEFT).end($day_array);

$sql="SELECT park, year_month_day, attend_tot FROM stats_day 
where ($clause) and (year_month_day>='$testYMW1' and year_month_day<='$testYMWx')
order by park, year_month_day
";
// echo "109 $sql"; exit;
$result = mysqli_query($connection,$sql) or die ();
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
// 	$ARRAY_sum[][$row['park']]=$row['attend_tot'];
	}
if($level>4)
	{
// 	echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;
// 	$dist_sum=array_sum($ARRAY_sum);
// 	ECHO "$dist_sum";
	}
$skip=array();
$c=count($ARRAY);
echo "<table><tr><td></td></tr>";
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
		echo "<td>$value</td>";
		}
	echo "</tr>";
// 	if($ARRAY[$index]['park']!=$ARRAY[$index+1]['park'])
// 		{
// 		echo "<tr><td>***************</td></tr>";
// 		}
	}
echo "</table></div></body></html>";

?>