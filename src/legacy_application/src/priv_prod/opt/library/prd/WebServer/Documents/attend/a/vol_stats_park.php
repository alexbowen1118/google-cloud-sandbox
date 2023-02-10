<?php
session_start();
$dbTable="vol_stats";
$file="vol_stats_park.php";
$fileMenu="../menu.php";

extract($_REQUEST);
include("../../../include/parkcodesDiv.inc");// database connection parameter
include("../../../include/iConnect.inc");// database connection parameters
mysqli_select_db($connection,"park_use");

$prevYear=date('Y')-1; if($year){$prevYear=$year;}
echo"<form>Enter year: <input type='text' name='year' value='$prevYear'>
<input type='submit' name='submit' value='Show'></fomr>";
if($year==""){exit;}

$sql="SELECT park,category, count(id) as cat_num
FROM `vol_stats`
WHERE substring(`year_month`,1,4)='$year' and category!=''
and (admin_hours+camp_host_hours+trail_hours+ie_hours+main_hours+research_hours+res_man_hours+other_hours)!=0
group by park,`category`";
//echo "$sql";
$result = mysqli_query($connection,$sql);
while($row=mysqli_fetch_assoc($result)){$cat_array[]=$row;}
//print_r($cat_array);

$sql="SELECT park,`year_month`, sum(admin_hours) as admin, sum(camp_host_hours) as camp, sum(trail_hours) as trail, sum(ie_hours) as ie, sum(main_hours) as main, sum(research_hours) as res, sum(res_man_hours) as res_man, sum(other_hours) as other FROM `vol_stats`
WHERE substring(`year_month`,1,4)='$year' 
and (admin_hours+camp_host_hours+trail_hours+ie_hours+main_hours+research_hours+res_man_hours+other_hours)!=0
group by park,`year_month`";
//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("<br>Input form for $parkcode has not been created. Go <a href='cat.php?parkcode=$parkcode'> here</a>$sql.");

$num=mysqli_num_rows($result);

include("$fileMenu");
echo "<div align='center'><table>";
echo "<tr><th>Division of Parks and Recreation Volunteer Hours</th></tr><table>";

// *************** Show Form ****************
echo "<table cellpadding='1' border='1'>";
echo "<tr><th>Park</th><th>Category</th><th>Number</th></tr>";
foreach($cat_array as $k=>$array)
	{
	extract($array);
	echo "<tr><td>$park</td><td>$category</td><td>$cat_num</td></tr>";
	}
echo "</table>";

echo "<table cellpadding='1' border='1'>";
echo "<tr><th>Park</th><th>Year_Month</th><th>Admin</th><th>Camp Host</th><th>Trail</th><th>I&E</th><th>Maintenance</th><th>Research</th><th>Resource<br>Management</th><th>Other</th></tr>";

while($row=mysqli_fetch_array($result)){
extract($row);
@$adminGranTot+=$admin;
@$campGranTot+=$camp;
@$trailGranTot+=$trail;
@$ieGranTot+=$ie;
@$mainGranTot+=$main;
@$resGranTot+=$res;
@$res_manGranTot+=$res_man;
@$otherGranTot+=$other;

if(@$ckPark =="" or @$ckPark==$park){
@$adminTot+=$admin;
@$campTot+=$camp;
@$trailTot+=$trail;
@$ieTot+=$ie;
@$mainTot+=$main;
@$resTot+=$res;
@$res_manTot+=$res_man;
@$otherTot+=$other;

echo "<tr><td align='center'>$park</td><td align='center'>$year_month</td><td align='right'>$admin</td><td align='right'>$camp</td><td align='right'>$trail</td><td align='right'>$ie</td><td align='right'>$main</td><td align='right'>$res</td><td align='right'>$res_man</td><td align='right'>$other</td>";
echo "<tr>";}
else{
echo "<tr bgcolor='white'><td align='center'></td><td align='center'></td><td align='right'>$adminTot</td><td align='right'>$campTot</td><td align='right'>$trailTot</td><td align='right'>$ieTot</td><td align='right'>$mainTot</td><td align='right'>$resTot</td><td align='right'>$res_manTot</td><td align='right'>$otherTot</td></tr>";


$adminTot="";
$campTot="";
$trailTot="";
$ieTot="";
$mainTot="";
$resTot="";
$res_manTot="";
$otherTot="";

echo "<tr><td align='center'>$park</td><td align='center'>$year_month</td><td align='right'>$admin</td><td align='right'>$camp</td><td align='right'>$trail</td><td align='right'>$ie</td><td align='right'>$main</td><td align='right'>$res</td><td align='right'>$res_man</td><td align='right'>$other</td>";
echo "<tr>";

$adminTot+=$admin;
$campTot+=$camp;
$trailTot+=$trail;
$ieTot+=$ie;
$mainTot+=$main;
$resTot+=$res;
$res_manTot+=$res_man;
$otherTot+=$other;

}
$ckPark=$park;
}
echo "<tr bgcolor='white'><td align='center'></td><td align='center'></td><td align='right'>$adminTot</td><td align='right'>$campTot</td><td align='right'>$trailTot</td><td align='right'>$ieTot</td><td align='right'>$mainTot</td><td align='right'>$resTot</td><td align='right'>$res_manTot</td><td align='right'>$otherTot</td></tr>";

$grandTotal=number_format($adminGranTot+$campGranTot+$trailGranTot+$ieGranTot+$mainGranTot+$resGranTot+$res_manGranTot+$otherGranTot,1);

echo "<tr><th align='center'></th><th align='center'></th><th align='right'>$adminGranTot</th><th align='right'>$campGranTot</th><th align='right'>$trailGranTot</th><th align='right'>$ieGranTot</th><th align='right'>$mainGranTot</th><th align='right'>$resGranTot</th><th align='right'>$res_manGranTot</th><th align='right'>$otherGranTot</th>
<th>$grandTotal</th></tr>";

echo "<tr><th>Park</th><th>Year_Month</th><th>Admin</th><th>Camp Host</th><th>Trail</th><th>I&E</th><th>Maintenance</th><th>Research</th><th>Resource<br>Management</th><th>Other</th></tr>";

echo "</table></div></body></html>";

?>