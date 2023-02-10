<?php
ini_set('display_errors',1);
date_default_timezone_set('America/New_York');
//include("../../../include/get_parkcodes.php");

$database="attend";
include("../../../include/auth_i.inc");
$database="park_use";   // made the mistake of giving this app two different names
include("../../../include/iConnect.inc");

include("../../../include/get_parkcodes_dist.php");
//echo "parkCode<pre>";print_r($parkCode);echo "</pre>";    //exit;

//  echo "<pre>"; print_r($_POST); echo "</pre>";  //exit;
// if(!empty($_POST['submit_form']))
// 	{
// 	$exempt=$_POST;
// 	}
// 	else
// 	{
// 	$exempt=array();
// 	}
	
$database="park_use";
mysqli_select_db($connection,$database);

// echo "<pre>";print_r($_REQUEST);echo "</pre>";    //exit;

if(empty($year)){$year=date('Y');}
if(empty($month)){$month=(date('m'))-1;}// DEFAULT to previous month
$month=str_pad($month,2,"0",STR_PAD_LEFT);
$menu['r_ytd']="r_ytd_month_dncr.php";
$menuM=$month;
$menuY=$year;
$varQuery="submit=Enter&year=$menuY&month=$menuM";

if(empty($xls)){include("../menu.php");}// ignore menu for Excel export

// Get park totals for calendar year thru specified month
$nextMonth=$month+1;
$nextMonth=str_pad($nextMonth,2,"0",STR_PAD_LEFT);
if($month=="12")
	{
	$findYear=$year+1;$nextMonth="01";
	}
	else
	{$findYear=$year;}

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
// echo "$month $findYearB $findYearE";


// just park
$sql="SELECT distinct UPPER(LEFT(`park`,4)) as park
FROM $table";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_assoc($result))
	{
	$list_of_parks_array[]=$row['park'];
	}

// all park public park areas
$sql="SELECT distinct UPPER(`park`) as park
FROM $table";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_assoc($result))
	{
	$list_of_park_areas_array[]=$row['park'];
	}
// echo "<pre>"; print_r($list_of_park_areas_array); echo "</pre>"; // exit;	
// $sql = "SELECT UPPER(LEFT(`park`,4)) as park, left($q_field,6) as yearMonth, sum(attend_tot) as sum 
// FROM $table
// where $q_field > '$findYearB' and $q_field < '$findYearE'
// group by LEFT(`park`,4),yearMonth order by LEFT(`park`,4)";
// echo "$sql"; exit;
		
$sql = "SELECT LEFT(`park`,4) as park, sum(attend_tot) FROM $table
where $q_field > '$findYearB' and $q_field < '$findYearE'
group by LEFT(`park`,4) order by park"; 
// echo "$sql<br />";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
if(mysqli_num_rows($result)<1)
	{echo "No entries yet for $findYearB."; exit;}
while ($row=mysqli_fetch_array($result))
	{
	$parkNameYear[]=$row[0];
	$parkTotYear[$row[0]]=$row[1];
	}
foreach($parkCode as $index=>$pc)
	{
	if(!in_array($pc,$parkNameYear)){$parkNameYear[]=$pc;}
	}
// echo "<pre>"; print_r($parkNameYear); echo "</pre>";

//echo "$findYear $findYearB  <br />";
if($findYearB<20120100)
	{
	$table="stats";
	$q_field="year_month_week";
	}
	else
	{
	$table="stats_day";
	$q_field="year_month_day";
	}
// $sql = "SELECT park, sum(attend_tot) FROM $table
// where $q_field> '$findYearB' and $q_field < '$findYearE'
// group by park order by park"; 

// $where_exempt="and UPPER(LEFT(`park`,4)) !='ARCH' ";

// $exempt_array=array("ARCH");
$exempt_array=array();

if(!empty($exempt_park))
	{
	foreach($exempt_park as $k=>$v)
		{
		array_push($exempt_array,$v);
		}
	}
// echo "137<pre>"; print_r($exempt_array); echo "</pre>";
// exit;

$sql = "SELECT UPPER(LEFT(`park`,4)) as park, sum(attend_tot) FROM $table
where $q_field> '$findYearB' and $q_field < '$findYearE'

group by UPPER(LEFT(`park`,4)) order by park"; 
// echo "$sql"; 
// exit;
	
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
$num_found=mysqli_num_rows($result);
// echo "<br /><br />n=$num_found";
// $row=mysqli_fetch_array($result);
$missing=array();
while ($row=mysqli_fetch_array($result))
 	{
 	if(in_array($row['park'],$exempt_array)){continue;}
	$parkNamePreYear[]=$row[0];
	$parkTotPreYear[$row[0]]=$row[1];
	if($row[1]<1)
		{$missing[]=$row['park'];}
 	}
//  echo "<br /><br />154 <pre>"; print_r($missing); echo "</pre>"; //exit;
// echo "<pre>"; print_r($parkNamePreYear); echo "</pre>"; // exit;

// $missing[]="CABE";

$date1 = $year.$month; $d = date_create_from_format('Ym',$date1); $last_day = date_format($d, 't'); 
$last_day_of_month=$year.$month.$last_day;
if(!empty($missing))
	{
	echo "<form name='frm' method='POST' action='r_ytd_month_dncr.php'>";
	echo "<table><tr><td>These parks have not entered visitation for $findYearB.</td></tr>";
	foreach($missing as $k=>$v)
		{
		echo "<tr><td><input type='checkbox' name='exempt_park[]' value=\"$v\">$v</td></tr>";
		}
	if(!empty($exempt_park))
		{
		foreach($exempt_park as $k1=>$v1)
			{
			echo "<input type='hidden' name='exempt_park[]' value=\"$v1\">";
			}
		}
	echo "<tr><td>";
	if(!empty($xls))
		{
		echo "<input type='hidden' name='xls' value=\"excel\">";
		}
	echo "<input type='submit' name='submit_exempt' value=\"Exempt\">
	</td></tr>";
	echo "</table></form>";
// echo "172 <pre>"; print_r($missing); echo "</pre>";

//if($isset($_POST['exempt_park'])){
//$exempt_park = $_POST['exempt_park'];
//}
// echo "176 <pre>"; print_r($exempt_park); echo "<pre>";
	exit;
	}

// echo "180 exempt_park<pre>"; print_r($exempt_park); echo "<pre>";


// echo "<pre>"; print_r($list_of_park_areas_array); echo "</pre>"; // exit;
foreach($list_of_park_areas_array as $k=>$v)
	{
	$sql = "SELECT UPPER(`park`) as park, left($q_field,6) as yearMonth, attend_tot
	FROM $table
	where year_month_day='$last_day_of_month' and park='$v'
	group by `park`,yearMonth order by park";
// echo "$sql"; exit;
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	while($row=mysqli_fetch_assoc($result))
		{
		if(in_array($row['park'],$exempt_array))
			{
			continue;
			}
		if($row['attend_tot']<1)
			{
			$incomplete_array[$v]=$row['attend_tot'];
			}
		}
	}
// echo "<pre>"; print_r($incomplete_array); echo "</pre>"; // exit;
// echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
$var_year_month=substr($last_day_of_month, 0,-2)."%";
	$sql = "SELECT UPPER(`park`) as park,comments 
	FROM  `stats_day` 
	WHERE  `year_month_day` LIKE  '$var_year_month'
	AND  `comments` !=  ''
	";
// echo "$sql"; //exit;
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	while($row=mysqli_fetch_assoc($result))
		{
		$comment_array[$row['park']]=$row['comments'];
		}
// if(!empty($incomplete_array) and empty($submit_form) and empty($xls))
if(!empty($incomplete_array) and empty($submit_form) )
	{
	include("park_code_areas.php");
	if(!empty($xls))
		{
		echo "<script>function CheckAll()
	{
	count = document.frm.elements.length;
		for (i=0; i < count; i++) 
		{
		if(document.frm.elements[i].checked == 1)
			{document.frm.elements[i].checked = 0; }
		else {document.frm.elements[i].checked = 1;}
		}
	}
	function UncheckAll(){
	count = document.frm.elements.length;
		for (i=0; i < count; i++) 
		{
		if(document.frm.elements[i].checked == 1)
			{document.frm.elements[i].checked = 0; }
		else {document.frm.elements[i].checked = 1;}
		}
	}</script>";
	
		}
	echo "Last day of month: $last_day_of_month<br />";
	echo "<br />These parks or park locations have entered INCOMPLETE visitation for month.";
//  	echo "<pre>"; print_r($incomplete_array); echo "</pre>";  
	echo "<form name='frm' action='r_ytd_month_dncr.php' method='POST'>";
	echo "<table border='1'>";
	foreach($incomplete_array as $k=>$v)
		{
		$name=$parkCodeName[$k];
		@$comment=$comment_array[$k];
		echo "<tr>
		<td><input type='checkbox' name='$k' value=\"x\"></td>
		<td>$k</td><td>$name</td><td>$comment</td></tr>";
		}
	echo "<tr><td colspan='2' align='center'>
	<input type='hidden' name='year' value=\"$year\">
	<input type='hidden' name='month' value=\"$month\">";
	
	if(!empty($exempt_park))
		{
		foreach($exempt_park as $k1=>$v1)
			{
			echo "<input type='hidden' name='exempt_park[]' value=\"$v1\">";
			}
		}
		
	if(!empty($xls))
		{
		echo "<input type='hidden' name='xls' value=\"excel\">";
		}
	echo "<input type='submit' name='submit_form' value=\"Ignore\">
	</td><td><input name=\"btn\" type=\"button\" onclick=\"CheckAll()\" value=\"Check All\"> 
<input name=\"btn\" type=\"button\" onclick=\"UncheckAll()\" value=\"Uncheck All\"> </td></tr>";
	echo "</table>";
	echo "</form>";
	exit;
	}
// echo "<pre>"; print_r($parkCode); echo "</pre>"; // exit;
foreach($parkCode as $index=>$pc)
	{
	if(!in_array($pc,$parkNamePreYear)){$parkNamePreYear[]=$pc;}
	}
//echo "<pre>"; print_r($parkName
//echo "$sql $month $nextMonth";

// Uses $parkNameYear for park name

$y=$year; $m=$month; $mPad=str_pad($m,2,"0",STR_PAD_LEFT); $mPad_1=str_pad($m+1,2,"0",STR_PAD_LEFT);
$t=$y.$mPad."01";
$mF=strftime("%B",strtotime($t));

if($y>2011)
	{
	$table="stats_day";
	$q_field="year_month_day";
	}
	else
	{
	$table="stats";
	$q_field="year_month_week";
	}
// Get park totals for current month
$startMonth=$y.$mPad."00"; $endMonth=$y.$mPad_1."00";
	
// get comments
$sql = "SELECT LEFT(`park`,4) as park, comments 
FROM $table
where $q_field > '$startMonth' and $q_field < '$endMonth'
and `comments` !='' 
order by park"; //echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_array($result))
	{
	$park_comments[$row['park']]=$row['comments'];
	}
	
	
$sql = "SELECT LEFT(`park`,4) as park, sum(attend_tot) FROM $table
where $q_field > '$startMonth' and $q_field < '$endMonth'
group by LEFT(`park`,4)";
//echo " $sql";

$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_array($result))
	{
	$parkNameMonth[]=$row[0];
	$parkTotMonth[$row[0]]=$row[1];
	$parkCodes[$row[0]]=$row[0];
	}
// Uses $parkNameYear for park name
//echo "<pre>";print_r($parkCodes);print_r($parkTotMonth);echo "</pre>";

// Get park totals for current month PREVIOUS YEAR
$startMonth=($y-1).$mPad."00"; $endMonth=($y-1).$mPad_1."00";

if($y>2012)
	{
	$table="stats_day";
	$q_field="year_month_day";
	}
	else
	{
	$table="stats";
	$q_field="year_month_week";
	}
$sql = "SELECT LEFT(`park`,4) as park, sum(attend_tot) FROM $table
where $q_field > '$startMonth' and $q_field < '$endMonth'
group by LEFT(`park`,4)";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_array($result))
	{
	$parkNameMonthPreY[]=$row[0];
	$parkTotMonthPreY[$row[0]]=$row[1];
	}

$ly=$y-1; $mon=substr($mF,0,3);

// echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
if(@$submit=="Enter" or @$submit_form=="Ignore")
	{
	if(empty($xls))
		{
	echo "<body><div align='center'><table border='1' cellpadding='5'>";
// 	echo "<tr><th>NC STATE<BR>PARK</th><th>$mF<br>$y</th><th>TOTAL YTD<BR>$mF $y</th><th>$mF<br>$ly</th><th>TOTAL YTD<BR>$mF $ly</th><th>% CHANGE<br>($y/$ly)<br>$mon &nbsp; YTD</th></tr>";
	echo "<tr><th>NC STATE<BR>PARK</th>
	<th>$mF<br>$ly</th>
	<th>$mF<br>$y</th>
	<th>Variance</th>
	<th>% Variance</th>
	<th>Justification</th></tr>";
		}

	

// $combineParks=array("ENRI","NERI");// code below handles these two separately
$combineParks=array();// code below handles these two separately
// "OCMO","MOJE",
$skipParks=array("ARCH","BAIS","BATR","BECR","BEPA","BULA","DERI","LOHA","LEIS","MIMI","PIBO","BUMO","RUHI","SARU","SCRI","SUMO","THRO","WOED","YEMO","BOCR","FOFL","HINU","SACR","BOCR","WAMI","HINU","SACR","FRRI","CHSW","MAIS","NCMA","HEBL","HORI","EADI","LIRI","MOTS","NODI","NOPE","PIVI","SODI","WEDI","WIGA","SALA","WHLA","YARI","OVVI");
	
	for($i=0;$i<count($parkNameYear);$i++)
		{
		if(@$source!="pub" and @$level>0)
			{
			if($year>2011){$page="form_day.php";}else{$page="form.php";}
			$parkLink="<a href='$page?parkcode=$parkNameYear[$i]&passM=$m&yearPass=$year'>$parkNameYear[$i]</a>";
			}
		else
			{
			@$p=$parkCodeName[$parkNameYear[$i]];
			$parkLink=$p;
			}
		
		if(in_array($parkNameYear[$i],$skipParks)){continue;}
		
		$variance=$parkTotMonth[$parkNameYear[$i]]-$parkTotMonthPreY[$parkNameYear[$i]];
		
		@$monthPark=number_format($parkTotMonth[$parkNameYear[$i]],0);
		@$monthPreYearPark=number_format($parkTotMonthPreY[$parkNameYear[$i]],0);
		
		@$yearPark=number_format($parkTotYear[$parkNameYear[$i]],0);
		@$yearParkPreY=number_format($parkTotPreYear[$parkNameYear[$i]],0);
		
		@$perCentChangeM=number_format((($parkTotMonth[$parkNameYear[$i]]/$parkTotMonthPreY[$parkNameYear[$i]])-1)*100,1);
		
if($perCentChangeM=="-100" and $parkTotMonthPreY[$parkNameYear[$i]]==0)
	{
	$perCentChangeM="unkn";
	}

@$perCentChangeY=number_format((($parkTotYear[$parkNameYear[$i]]/$parkTotPreYear[$parkNameYear[$i]])-1)*100,1);
		
		if(!in_array($parkNameYear[$i],$combineParks))
			{
			@$parkMonthTot=$parkMonthTot+$parkTotMonth[$parkNameYear[$i]];
			@$yearMonthTot=$yearMonthTot+$parkTotYear[$parkNameYear[$i]];
			@$yearPrevTot=$yearPrevTot+$parkTotMonthPreY[$parkNameYear[$i]];
			@$yearPrevTotAll=$yearPrevTotAll+$parkTotPreYear[$parkNameYear[$i]];
			}
	
		
		//if(fmod($i,2)==0){$bg=" bgcolor='Silver'";}else{$bg="";}
		if(@$j==0){$bg=" bgcolor='Silver'";}else{$bg="";}

$park_name=$parkCodeName[$parkNameYear[$i]];		
		if(@$xls=="excel" AND @$source!="pub")
			{
			$parkLink=$park_name;
// 			if($parkLink=="ENRI"){$parkLink.="/OCMO";}
// 			if($parkLink=="NERI"){$parkLink.="/MOJE";}
			}
	
if(array_key_exists($parkNameYear[$i],$park_comments))
	{
	$var_comments=$park_comments[$parkNameYear[$i]];
	}
	else
	{
	$var_comments="";
	}
	
	$variance=number_format($variance, 0);
	$csv_array[]=array("$parkLink", "$monthPreYearPark", "$monthPark", "$variance", "$perCentChangeM%", "$var_comments");

		if(empty($xls))
			{
			echo "<tr$bg><td>$park_name</td>
			<td align='right'>$monthPreYearPark</td>
			<td align='right'>$monthPark</td>
			<td align='right'>$variance</td>
			<td align='center'>$perCentChangeM%</td>
			<td align='left'>$var_comments</td>
			</tr>";
			}
		@$j++; 
		if($j==2){$j=0;}
		}
	
// 	echo "test<pre>"; print_r($csv_array); echo "</pre>"; // exit;
	$perCentChangeMtot=@number_format((($parkMonthTot/$yearPrevTot)-1)*100,1);
	$perCentChangeYtot=@number_format((($yearMonthTot/$yearPrevTotAll)-1)*100);
	
	$varianceTot=number_format($parkMonthTot-$yearPrevTot);
	
	$parkMonthTot=number_format($parkMonthTot);
	$yearMonthTot=number_format($yearMonthTot);
	$yearPrevTot=number_format($yearPrevTot);
	$yearPrevTotAll=number_format($yearPrevTotAll);
	
	$var_pc=number_format($perCentChangeMtot,1)." %";
	$csv_array[]=array("         Systemwide Total", "$yearPrevTot", "$parkMonthTot", "$varianceTot", "$var_pc");
	if(empty($xls))
			{
			echo "<tr><td><b>SYSTEMWIDE TOTAL</b></td>
			<td align='right'>$yearPrevTot</td>
			<td align='right'>$parkMonthTot</td>
			<td align='right'>$varianceTot</td>
			<td align='center'>$perCentChangeMtot%</td>
			</tr>";
	
		echo "</table></div></body>";
		echo "</html>";
			}
	if(!empty($xls))
			{
	
	$header_array[]=array("NC STATE PARK"," $mF $ly",  " $mF $y",  "Variance",  "% Variance", "Justification");
			header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=file.csv");
	// Disable caching
	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
	header("Pragma: no-cache"); // HTTP 1.0
	header("Expires: 0"); // Proxies

	
	function outputCSV($header_array, $data) {
		$output = fopen("php://output", "w");
		foreach ($header_array as $row) {
			fputcsv($output, $row); // here you can change delimiter/enclosure
		}
		foreach ($data as $row) {
			fputcsv($output, $row); // here you can change delimiter/enclosure
		}
		fclose($output);
	}

	outputCSV($header_array, $csv_array);

	exit;
			}
	}
?>