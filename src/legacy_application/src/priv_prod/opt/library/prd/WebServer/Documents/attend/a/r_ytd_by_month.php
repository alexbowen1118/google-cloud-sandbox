<?php
if(@$_GET['year']>2011 or @$_GET['y']>2011)
	{
	include("r_ytd_by_month_day.php");
	exit;
	}

ini_set('display_errors',1);
date_default_timezone_set('America/New_York');

include("../../../include/iConnect.inc");
// include("../../no_inject.php");

extract($_REQUEST);
include("../../../include/get_parkcodes_reg.php");
$database="park_use";
mysqli_select_db($connection,$database);

if(@!$year){$year=date('Y');}
if(@!$month){$month=(date('m'))-1;}// DEFAULT to previous month
//echo "<pre>";print_r($_REQUEST);echo "</pre>";exit;
$month=str_pad($month,2,"0",STR_PAD_LEFT);
$menu['r_ytd']="r_ytd_by_month.php";
$menuM=$month;
$menuY=$year;
$varQuery="submit=Enter&year=$menuY&month=$menuM";

if(@!$xls){include("../menu.php");}// ignore menu for Excel export

if(@$xls=="excel"){header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=NC_State_Park_Attendance.xls');
}

// Get park totals for calendar year thru specified month
$nextMonth=$month+1;
$nextMonth=str_pad($nextMonth,2,"0",STR_PAD_LEFT);
if($month=="12"){$findYear=$year+1;$nextMonth="01";}else{$findYear=$year;}
$findYearB=$year."0100"; $findYearE=$findYear.$nextMonth."00";

if($year<date('Y')){$findYearE=$year."1300";}
$sql = "SELECT park, left(year_month_week,6) as yearMonth, sum(attend_tot) FROM stats
where year_month_week > '$findYearB' and year_month_week < '$findYearE'
group by park,yearMonth order by park";

//echo "$sql"; exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_array($result))
	{
	$parkID=$row[0].$row[1];
	@$parkNameArray[]=$row[0];
	@$parkYearMonth[]=$row[1];
	@$parkTotMonth[$parkID]=$row[2];
	@$parkSysMonth[$row[1]]+=$row[2];
	}


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
	echo "<tr><th colspan='$span'>Attendance by Month for $year</th></tr>
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
			$parkTot=number_format($parkTot-$value+$vv,0);
			echo "<td align='right'><b>$parkTot</b></td></tr>";
			}
			echo "<tr><td>$parkLongName</td>";$parkTot="";
		}
	
	echo "<td align='right'>$monthPark</td>";
	
	$ckPark=$getPark;$j++;
	}
	
	//$sysTotal=array_sum($parkSysMonth);// Used to double check System Total
	
	@$parkTot=$parkTot+($val[$jv+count($monthArray)]);
	if(!isset($sysTot)){$sysTot="";}
	if(!isset($parkTot)){$parkTot="";}
	$sysTot=number_format($sysTot,0);
	$parkTot=number_format($parkTot,0);
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