<?php
session_start();
$dbTable="vol_stats";
$file="vol_stats_year_park_month.php";
$fileMenu="../menu.php";

extract($_REQUEST);
include("../../../include/parkcodesDiv.inc");// database connection parameter
include("../../../include/iConnect.inc");// database connection parameters
mysqli_select_db($connection,"park_use");

$prevYear=date('Y')-1; if($year){$prevYear=$year;}
$prevMonth=str_pad(date('m')-1, 2, '0', STR_PAD_LEFT);
 if($month){$prevMonth=str_pad($month, 2, '0', STR_PAD_LEFT);}

echo"<form>
Enter year: <input type='text' name='year' value='$prevYear'><br />
Enter month 1-12: <input type='text' name='month' value='$prevMonth' size='3'><br />
<input type='submit' name='submit' value='Show'>
</form>";
if($year==""){exit;}
if($month==""){exit;}

// $month="04";
// $sql="SELECT park,category, count(id) as cat_num
// FROM `vol_stats`
// WHERE substring(`year_month`,1,4)='$year' and substring(`year_month`,-2,2)='$prevMonth' and category!=''
// and (admin_hours+camp_host_hours+trail_hours+ie_hours+main_hours+research_hours+res_man_hours+other_hours)!=0
// group by park,`category`";
// echo "$sql";
// 
// $result = mysqli_query($connection,$sql);
// while($row=mysqli_fetch_assoc($result)){$cat_array[]=$row;}
// echo "<pre>"; print_r($cat_array); echo "</pre>";  exit;

$sql="SELECT park,`year_month`, sum(admin_hours + camp_host_hours + trail_hours + ie_hours + main_hours + research_hours + res_man_hours + other_hours) as total FROM `vol_stats`
WHERE substring(`year_month`,1,4)='$year' and substring(`year_month`,-2,2)='$prevMonth'
and (admin_hours+camp_host_hours+trail_hours+ie_hours+main_hours+research_hours+res_man_hours+other_hours)!=0
group by park,`year_month`";
//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("<br>Input form for $parkcode has not been created. Go <a href='cat.php?parkcode=$parkcode'> here</a>$sql.");
while($row=mysqli_fetch_assoc($result)){$ARRAY[]=$row;}
// echo "<pre>"; print_r($ARRAY); echo "</pre>"; 
// exit;
$num=mysqli_num_rows($result);

include("$fileMenu");
echo "<div align='center'><table>";
echo "<tr><th>Division of Parks and Recreation Volunteer Hours</th></tr><table>";

$skip=array();
$grand_total=0;
$c=count($ARRAY);
echo "<table><tr><td>$c parks</td></tr>";
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
		if($fld=="total")
			{
			$grand_total+=$value;
			}
		}
	echo "</tr>";
	}
echo "<tr><td colspan='3' align='right'>$grand_total</td></tr>";
echo "</table></div></body></html>";

?>