<?php
ini_set('display_errors',1);
date_default_timezone_set('America/New_York');
include("../../../include/get_parkcodes_i.php");
extract($_REQUEST);
$database="attend";
include("../../../include/auth.inc");
include("../../../include/iConnect.inc");

$database="park_use";
mysqli_select_db($connection,$database);

//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  exit;

if(@!$start_fy){$start_fy=date('Y')-1;}
if(@!$end_fy){$end_fy=(date('Y'));}
//echo "<pre>";print_r($_REQUEST);echo "</pre>";exit;

$menu['r_ytd']="r_ytd_by_month_day_fiscal_year.php";
$menuM=$start_fy;
$menuY=$end_fy;
$varQuery="submit=Enter&begin_fy=$menuY&end_fy=$end_fy";

if(@!$xls){include("../menu.php");}// ignore menu for Excel export

if(@$xls=="excel"){header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=NC_State_Park_Attendance.xls');
}

// Get park totals for calendar year thru specified month


	$table="stats_day";
	$q_field="year_month_day";

$begin_fy=$start_fy."0700";

if($end_fy == date("Y"))
	{
$end_month=date('m');  
$finish_fy=$end_fy.str_pad($end_month,2,"0",STR_PAD_LEFT)."00"; //echo "t=$end_fy"; exit;
}
else
{
$finish_fy=$end_fy."0700"; //echo "t=$end_fy"; exit;
}
$sql = "SELECT UPPER(LEFT(`park`,4)) as park, left($q_field,6) as yearMonth, sum(attend_tot) as sum FROM $table
where `year_month_day`>'$begin_fy' and `year_month_day`<'$finish_fy'

group by LEFT(`park`,4),yearMonth order by LEFT(`park`,4)";

//echo "$sql"; //exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_assoc($result))
	{
//	echo "<pre>"; print_r($row); echo "</pre>";  exit;
	$parkID=$row['park'].$row['yearMonth'];
	@$parkNameArray[]=$row['park'];
	@$parkYearMonth[]=$row['yearMonth'];
	@$parkTotMonth[$parkID]=$row['sum'];
	@$parkTotYEAR[$row['park']][]=$row['sum'];
	@$parkSysMonth[$row['yearMonth']]+=$row['sum'];
	}
// $tot=array_sum($parkTotYEAR['WIUM']);
//  echo "$tot<pre>"; print_r($parkTotYEAR); echo "</pre>"; // exit;

//$ly=$y-1;
@$mon=substr($mF,0,3);

if(@$submit=="Enter")
	{
	echo "<body><div align='center'><table border='1' cellpadding='5'>";
	
	$monthArray=array_unique($parkYearMonth);
	$arrayPark=array_unique($parkNameArray);
	sort($monthArray);
	//print_r($monthArray);
	//print_r($parkTotMonth);
	
	$count=count($monthArray);
	for($j=0;$j<$count;$j++)
		{
		foreach($arrayPark as $km=>$kv){
		$mainArray[]=$kv.$monthArray[$j];}
		}
	sort($mainArray);
	//echo "<pre>";print_r($parkSysMonth);echo "</pre>";
	
	for($j=0;$j<count($monthArray);$j++)
		{
		@$headers.="<th>$monthArray[$j]</th>";
		}
		$span=$count+2;
	echo "<tr><th colspan='$span'>Attendance by Month for FY $start_fy - $end_fy</th></tr>
	<tr><th>NC STATE PARK UNIT</th>$headers</tr>";
	
	$j=0;
	foreach($mainArray as $kk){
	$getPark=substr($kk,0,4);
	$parkLongName=@$parkCodeName[$getPark];
	
	@$value=$parkTotMonth[$kk];
	@$val[$j]=$value;
	@$parkTot+=$value;
	@$sysTot+=$value;
	
	$monthPark=number_format($value,0);
	
	//if(fmod($i,2)==0){$bg=" bgcolor='Silver'";}else{$bg="";}
	
	if(@$ckPark!=$getPark)
		{
		if(@$ckPark!="")
			{
			$jv=$j-count($monthArray);
			$vv=$val[$jv];
// 			$parkTot=number_format($parkTot-$value+$vv,0);
			$parkTot=number_format(array_sum($parkTotYEAR[$ckPark]));
			echo "<td align='right'><b>$parkTot</b></td></tr>";
			}
			echo "<tr><td>$parkLongName</td>";$parkTot="";
		}
	
	echo "<td align='right'>$monthPark</td>";
	
	$ckPark=$getPark;$j++;
	}
	
	//$sysTotal=array_sum($parkSysMonth);// Used to double check System Total
	
// 	@$parkTot=$parkTot+($val[$jv+count($monthArray)]);
	if(!isset($sysTot)){$sysTot="";}
	if(!isset($parkTot)){$parkTot="";}
	$sysTot=number_format($sysTot,0);
// 	$parkTot=number_format($parkTot,0);
	$parkTot=number_format(array_sum($parkTotYEAR[$ckPark]));
	echo "<td align='right'><b>$parkTot</b></td></tr><tr><th>SYSTEMWIDE TOTAL</th>";
	for($j=0;$j<count($monthArray);$j++)
		{
		$st=number_format($parkSysMonth[$monthArray[$j]],0);
		@$footer.="<th>$st</th>";
		}
	if(!isset($footer)){$footer="";}
	echo "$footer<td><font color='blue'>$sysTot</font></td></tr>";
	echo "</table></div></body>";
	}
echo "</html>";
?>