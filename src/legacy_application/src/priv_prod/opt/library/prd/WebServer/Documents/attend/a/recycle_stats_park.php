<?php
session_start();
$dbTable="recycle_stats";
$file="recycle_stats_park.php";
$fileMenu="../menu.php";

extract($_REQUEST);
if(!$thisYear){include("$fileMenu");
echo "<div align='center'>Enter Year for Recycle Report<form><input type='text' name='thisYear'>
<input type='submit' name='submit' value='Submit'>
</form></div>";exit;}


// include("../../../include/parkcodesDiv.inc");// database connection parameter
$database="park_use";
include("../../../include/iConnect.inc");// database connection parameters
mysqli_select_db($connection,$database);
//$prevYear=date(Y)-1;

$sql="SELECT park,sum(aluminum) as aluminum,sum(plastic) as plastic,sum(glass) as glass,sum(metal) as metal,sum(paper) as paper,sum(other_recycle) as other_recycle FROM `$dbTable`
WHERE substring(`year_month`,1,4)='$thisYear'
group by park
order by park";
//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("$sql.".mysqli_error($connection));

$num=mysqli_num_rows($result);

if($rep==""){include("$fileMenu");
$ex="<tr><td align='center'><a href='recycle_stats_park.php?rep=excel&thisYear=$thisYear'>Excel</a></td></tr>";}
else{header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=dpr_recycle_report.xls');
}

echo "<div align='center'><table>";
echo "<tr><th>Division of Parks and Recreation for $thisYear</th></tr>$ex<table>";

// *************** Show Form ****************
echo "<table border='1' cellpadding='1'>";
echo "<tr><th>Park</th><th>aluminum</th><th>plastic</th><th>glass</th><th>metal</th><th>paper</th><th>other_recycle</th></tr>";

while($row=mysqli_fetch_array($result)){
extract($row);
@$tot_alum+=$aluminum;
@$tot_plas+=$plastic;
@$tot_glas+=$glass;
@$tot_meta+=$metal;
@$tot_pape+=$paper;
@$tot_othe+=$other_recycle;
echo "<tr bgcolor='white'><td align='center'>$park</td><td align='right'>$aluminum</td><td align='right'>$plastic</td><td align='right'>$glass</td><td align='right'>$metal</td><td align='right'>$paper</td><td align='right'>$other_recycle</td></tr>";

}

$tot_alum=number_format($tot_alum,2);
$tot_plas=number_format($tot_plas,2);
$tot_glas=number_format($tot_glas,2);
$tot_meta=number_format($tot_meta,2);
$tot_pape=number_format($tot_pape,2);
$tot_othe=number_format($tot_othe,2);
echo "<tr><th>Pounds ==></th><th>$tot_alum</th><th>$tot_plas</th><th>$tot_glas</th><th>$tot_meta</th><th>$tot_pape</th><th>$tot_othe</th></tr>";
echo "</table>";

$sql="SELECT park,`year_month`, comments FROM `$dbTable`
WHERE substring(`year_month`,1,4)='$thisYear' and comments!=''
order by park";
$result = mysqli_query($connection,$sql) or die ("<br>$sql.");
$num=mysqli_num_rows($result);

echo "<hr><table><tr><td colspan='3' align='center'>Comments</td></tr>";
while($row=mysqli_fetch_array($result)){
extract($row);
echo "<tr bgcolor='white'><td align='center'>$park</td><td>$year_month</td><td>$comments</td></tr>";
}
echo "</table></div></body></html>";

?>