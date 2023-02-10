<?php
ini_set('display_errors',1);
date_default_timezone_set('America/New_York');
$database="attend";
include("../../../include/auth.inc");

$database="park_use";
include("../../../include/get_parkcodes_reg.php");
include("park_code_areas.php"); // get subunits

extract($_REQUEST);

//  echo "<pre>"; print_r($_POST); echo "</pre>"; // exit;
if(!empty($_POST['submit_form']))
	{
	$exempt=$_POST;
	}
	else
	{
	$exempt=array();
	}
	
$database="park_use";
mysqli_select_db($connection,$database);

extract($_REQUEST);
if(@!$year){$year=date('Y');}
if(@!$month){$month=(date('m'))-1;}// DEFAULT to previous month

if($month==0)
	{
	$month=12;
	$year=$year-1;
	}

//echo "<pre>";print_r($_REQUEST);echo "</pre>";exit;
$month=str_pad($month,2,"0",STR_PAD_LEFT);
$menu['r_ytd']="r_ytd_by_month_day_dncr.php";
$menuM=$month;
$menuY=$year;
$varQuery="submit=Enter&year=$menuY&month=$menuM";

header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=file.csv");
	// Disable caching
	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
	header("Pragma: no-cache"); // HTTP 1.0
	header("Expires: 0"); // Proxies

// Get park totals for calendar year thru specified month
$nextMonth=$month+1;
$nextMonth=str_pad($nextMonth,2,"0",STR_PAD_LEFT);
if($month=="12"){$findYear=$year+1;$nextMonth="01";}else{$findYear=$year;}

$findYearB=$year.$month."00"; 
$findYearE=$findYear.$nextMonth."00";

if($year<date('Y')){$findYearE=$year."1300";}

if($findYear>2011)
	{
	$table="stats_day";
	$q_field="year_month_day";
	}
	else
	{
	$table="stats";
	$q_field="year_month_week";
	}

// returns numbers grouping subunits
$sql = "SELECT UPPER(LEFT(`park`,4)) as park, left($q_field,6) as yearMonth, sum(attend_tot) as sum 
FROM $table
where $q_field > '$findYearB' and $q_field < '$findYearE'
group by LEFT(`park`,4),yearMonth 
order by LEFT(`park`,4)";
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
	$check_park_array[]=$row['park'];
	}

// echo "<pre>"; print_r($parkTotYEAR); echo "</pre>";  exit;
// returns numbers NOT grouping subunits
$sql = "SELECT UPPER(`park`) as park, left($q_field,6) as yearMonth, sum(attend_tot) as sum 
FROM $table
where $q_field > '$findYearB' and $q_field < '$findYearE'
group by `park`,yearMonth 
order by `park`";
//echo "$sql"; //exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_assoc($result))
	{
//	echo "<pre>"; print_r($row); echo "</pre>";  exit;
	$parkID_1=$row['park'].$row['yearMonth'];
	@$parkNameArray_1[]=$row['park'];
	@$parkYearMonth_1[]=$row['yearMonth'];
	@$parkTotMonth_1[$parkID]=$row['sum'];
	@$parkTotYEAR_1[$row['park']][]=$row['sum'];
	@$parkSysMonth_1[$row['yearMonth']]+=$row['sum'];
	$check_park_array_1[]=$row['park'];
	}
$sql="SELECT distinct UPPER(`park`) as park
FROM $table";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_assoc($result))
	{
	$list_of_parks_array[]=$row['park'];
	}
//echo "<pre>"; print_r($list_of_parks_array); echo "</pre>"; // exit;
// checks for parks not entering any numbers
foreach($list_of_parks_array as $k=>$v)
	{
	if(!in_array($v,$check_park_array_1))
		{
		$missing[]=$v;
		}
	}
$date1 = $year.$month; $d = date_create_from_format('Ym',$date1); $last_day = date_format($d, 't'); 
$last_day_of_month=$year.$month.$last_day;
if(!empty($missing))
	{
	echo "<br />These parks have not entered attendance for $findYearB.";
	echo "<pre>"; print_r($missing); echo "</pre>";  exit;
	}

foreach($list_of_parks_array as $k=>$v)
	{
	$sql = "SELECT UPPER(LEFT(`park`,4)) as park, left($q_field,6) as yearMonth, sum(attend_tot) as sum 
	FROM $table
	where year_month_day='$last_day_of_month' and park='$v'
	group by LEFT(`park`,4),yearMonth order by LEFT(`park`,4)";

	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	while($row=mysqli_fetch_assoc($result))
		{
		if($row['sum']<1)
			{
			if(array_key_exists($v, $exempt)){continue;}
			$incomplete_array[]=$v;
			}
		}
	}

// if(!empty($incomplete_array) and empty($xls))
// 	{
// 	echo "Last day of month: $last_day_of_month<br />";
// 	echo "<br />These parks or park locations have entered INCOMPLETE attendance for month.";
// //  	echo "<pre>"; print_r($incomplete_array); echo "</pre>";  
// 	echo "<form action='r_ytd_by_month_day_dncr.php' method='POST'>";
// 	echo "<table border='1'>";
// 	foreach($incomplete_array as $k=>$v)
// 		{
// 		$name=$parkCodeName[$v];
// 		echo "<tr>
// 		<td><input type='checkbox' name='$v' value=\"x\"></td>
// 		<td>$v</td><td>$name</td></tr>";
// 		}
// 	echo "<tr><td colspan='2' align='center'><input type='submit' name='submit_form' value=\"Ignore\"></td></tr>";
// 	echo "</table>";
// 	echo "</form>";
// 	exit;
// 	}
	
@$mon=substr($mF,0,3);

// if(@$submit=="Enter" or @$submit_form=="Ignore")
// 	{
// 	echo "<body><div align='center'><table border='1' cellpadding='5'>";
	
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
// 		@$headers.="<th>$monthArray[$j]</th>";
		}
		$span=$count+2;
	// echo "<tr><th colspan='$span'>Attendance for $month for $year</th>";
// 	if(empty($xls))
// 		{echo "<td><a href='r_ytd_by_month_day_dncr.php?xls=excel&submit_form=Ignore'>Excel export</td>";}
// 	echo "</tr>
// 	<tr><th>NC STATE PARK UNIT</th>$headers</tr>";

//  echo "<pre>"; print_r($mainArray); echo "</pre>";  exit;	
	$j=0;
	foreach($mainArray as $i=>$kk){
	$getPark=substr($kk,0,4);
	$parkLongName=@$parkCodeName[$getPark];
	
	@$value=$parkTotMonth[$kk];
	@$val[$i]=$value;
	@$parkTot+=$value;
	@$sysTot+=$value;
	
	$monthPark=number_format($value,0);

		$ARRAY[$i]['parkLongName']=$parkLongName;

			$vv=$val[$i];
			$parkTot=number_format(array_sum($parkTotYEAR[$getPark]));
			$ARRAY[$i]['parkTot']=$parkTot;
	
	}
	
	//$sysTotal=array_sum($parkSysMonth);// Used to double check System Total
	
// 	@$parkTot=$parkTot+($val[$jv+count($monthArray)]);
	if(!isset($sysTot)){$sysTot="";}
	if(!isset($parkTot)){$parkTot="";}
	$sysTot=number_format($sysTot,0);
// 	$parkTot=number_format($parkTot,0);
	$parkTot=number_format(array_sum($parkTotYEAR[$getPark]));
// 	echo "<td align='right'><b>$parkTot</b></td></tr><tr><th>SYSTEMWIDE TOTAL</th>";
	for($j=0;$j<count($monthArray);$j++)
		{
		$st=number_format($parkSysMonth[$monthArray[$j]],0);
// 		@$footer.="<th>$st</th>";
		}
	if(!isset($footer)){$footer="";}
// 	echo "$footer</tr>";
// 	echo "</table></div></body>";
// 	}
// echo "</html>";

$year_month=substr($findYearB,0, -2);
$month=date("F", mktime(0,0,0,substr($findYearB, -4, -2),1,0));
$header_array_1=array(0=>array(0=>"NC State Park Attendance for ".$year_month));
$header_array_2=array(0=>array("NC State Park Unit","$month Attendance"));
$footer=array(0=>array("","$st"));
	function outputCSV($header_array_1, $header_array_2, $data, $footer) {
		$output = fopen("php://output", "w");
		foreach ($header_array_1 as $row) {
			fputcsv($output, $row); // here you can change delimiter/enclosure
		}
		foreach ($header_array_2 as $row) {
			fputcsv($output, $row); // here you can change delimiter/enclosure
		}
		foreach ($data as $row) {
			fputcsv($output, $row); // here you can change delimiter/enclosure
		}
		foreach ($footer as $row) {
			fputcsv($output, $row); // here you can change delimiter/enclosure
		}
		
		fclose($output);
	}

	outputCSV($header_array_1, $header_array_2, $ARRAY, $footer);

	exit;
?>