<?php
ini_set('display_errors',1);
date_default_timezone_set('America/New_York');

$database="attend";
include("../../../include/auth.inc");
$database="park_use";
include("../../../include/iConnect.inc");

extract($_REQUEST);

include("../../../include/get_parkcodes_reg.php");
mysqli_select_db($connection,$database);

// Make f_year
if(@$f_year=="")
	{
	$testMonth=date('n');
	if($testMonth >0 and $testMonth<7){$year2=date('Y')-1;}
	if($testMonth >6){$year2=date('Y');}
	$yearNext=$year2+1;
	$yx=substr($year2,2,2);
	$year3=$yearNext;$yy=substr($year3,2,2);
	$f_year=$yx.$yy;
	}

if(@!$year){$year=date('Y');}
if(@!$month){$month=date('m');}

// echo "<pre>";print_r($_REQUEST);echo "</pre>";  //exit;
$month=str_pad($month,2,"0",STR_PAD_LEFT);
include("../menu.php");

// Get park totals for calendar year thru specified month
$nextMonth=$month+1;
$nextMonth=str_pad($nextMonth,2,"0",STR_PAD_LEFT);
if($month=="12"){$findYear=$year+1;$nextMonth="01";}else{$findYear=$year;}
$findYearB="19840100"; $findYearE=$findYear.$nextMonth."00";

if(@$yearType=="b")
	{
	$ckC="";$ckF="";$ckB="checked";
	$sql = "truncate table stats_f_year_b";//echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	
	$lastDate=date('Ymd');
	$sql = "SELECT min(year_month_week) as min, max(year_month_week) as max from stats where year_month_week>'19840000' and year_month_week<'20120000'";
//echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	$row=mysqli_fetch_array($result);
		$minYear=substr($row['min'],0,4);
//		$maxYear=substr($row['max'],0,4); echo "$minYear $maxYear";
	
	$sql = "SELECT min(year_month_day) as min, max(year_month_day) as max 
		from stats_day
		 where year_month_day>'20120000' and year_month_day<'$lastDate'";
//echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	$row=mysqli_fetch_array($result);
//		$minYear=substr($row['min'],0,4);
		$maxYear=substr($row['max'],0,4); //echo "$minYear $maxYear";

	for($i=$minYear;$i<=$maxYear;$i++){
		$yearArray[]=$i;
		}
//		echo "<pre>"; print_r($yearArray); echo "</pre>"; // exit;
	
	foreach($yearArray as $k=>$v)
		{
		$var1=substr($v,2,2);
		$j=$k+1;
		@$var2=substr($yearArray[$j],2,2);
		$fyear=$var1.$var2;
		
		$sql = "replace stats_f_year_b set `f_year`= '$fyear'";//echo "$sql<br />";
		$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
		
		$start=$yearArray[$k]."0700";
		@$end=$yearArray[$j]."0700";

//echo "$test<br />";
		if(@$yearArray[$j]<"2012")
			{
			$sql = "SELECT sum(attend_tot) as attendance FROM `stats` WHERE year_month_week>'$start' and year_month_week<'$end'"; 
//echo "$sql";
			$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
			$row=mysqli_fetch_array($result); extract($row);		
			}
		
		if(@$yearArray[$j]=="2012")
			{
			// first half of fiscal year
			$end="20111231";
			$sql = "SELECT sum(attend_tot) as attendance FROM `stats` WHERE year_month_week>'$start' and year_month_week<'$end'";//echo "$sql";
			$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
			$row=mysqli_fetch_array($result); 
			$var_1=$row['attendance'];

			$end="20120700";
			$sql = "SELECT sum(attend_tot) as attendance 
			FROM `stats_day` 
			WHERE year_month_day>'$start' and year_month_day<'$end'";
	//echo "$sql";
			$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
			$row=mysqli_fetch_array($result); 
			$var_2=$row['attendance'];
			
			$attendance=$var_1+$var_2;		
			}

		if(@$yearArray[$j]>"2012")
			{
			$sql = "SELECT sum(attend_tot) as attendance FROM `stats_day` WHERE year_month_day>'$start' and year_month_day<'$end'";//echo "$sql";
			$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
			$row=mysqli_fetch_array($result); extract($row);
			}

		if($fyear == '22'){
			$attendance = 1;
		}
		$sql = "UPDATE stats_f_year_b set attendance='$attendance' WHERE f_year='$fyear'";//echo "$sql";
		$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
		
		}
	
	
	$sql = "SELECT f_year, attendance FROM `stats_f_year_b` WHERE 1 ";//echo "$sql";
	
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	while ($row=mysqli_fetch_array($result))
	{$aYear[]=$row[0];$parkTotYear[]=$row[1];}
	
	echo "<html><body><div align='center'>
	<table><tr><td>
	<form action='annual_attend.php'>Calendar Year:
	<input type='hidden' name='yearType' value='c'>
	<input type='submit' name='submit' value=''>
	</form></td>
	 <td><form action='annual_attend.php'>Fiscal Year - A (by Park): 
	<input type='hidden' name='yearType' value='f'>
	<input type='submit' name='submit' value=''>
	</form></td>
	 <td><form action='annual_attend.php'>Fiscal Year - B (for all Parks): 
	<input type='hidden' name='yearType' value='b'>
	<input type='submit' name='submit' value=''>
	</form>
	 <td><form action='annual_attend.php'> 
	 Fiscal Year: <input type='text' name='f_year' value=\"\" size='5'>
	<input type='hidden' name='yearType' value='f'>
	<input type='submit' name='submit' value='CAFR Report'>
	</form>
	</td></tr></table>
	<table border='1' cellpadding='5'>";
	echo "<tr><th colspan='3'>Fiscal Year - B (for all Parks)</th></tr>";
	echo "<tr><th>Fiscal<br>Year</th><th>TOTAL<br>VISITATION</th><th> % CHANGE<br>from<br>Previous Year</th></tr>";
	
	$j=0;
	for($i=0;$i<count($aYear);$i++)
		{
		$yearAttend=number_format($parkTotYear[$i],0);
		@$perCentChangeM=number_format((($parkTotYear[$i]/$parkTotYear[$i-1])-1)*100);
		
		@$systemTot+=$parkTotYear[$i];
		
		echo "<tr><td align='center'>$aYear[$i]</td>
		<td align='right'>$yearAttend</td>";
		
		echo "<td align='center'>$perCentChangeM%</td>";
		echo "</tr>";
		}
	
	$systemTot=number_format($systemTot,0);
	echo "<tr><td></td>
	<td align='right'>$systemTot</td>
	</tr></table>";
	echo "<table><tr><td colspan='3'>Ignore F_year 9900. Years prior to 2000 are calendar year only. We do not have the attendance broken out by month and therefore cannot produce a true fiscal year. Years starting in 2000 have attendance by month and are true fiscal years.</td>
	
	</tr>";
	}



if(@$yearType=="f")
	{
	$ckC="";$ckF="checked";$ckB="";
	$sql = "truncate table stats_f_year";//echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	
// data for year_month_week
	$sql = "INSERT INTO stats_f_year 
	SELECT left(year_month_week,4) as cutYear, substring(year_month_week,5,2) as cutMonth,park,sum(attend_tot) as attendance,''
	FROM stats 
	where year_month_week > '20000100' and year_month_week < '20120000' group by park,cutYear,cutMonth order by cutYear,cutMonth";  //echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");

// data for year_month_day
	$lastDate=date('Ymd');
	$sql = "INSERT INTO stats_f_year 
	SELECT left(year_month_day,4) as cutYear, substring(year_month_day,5,2) as cutMonth,park,sum(attend_tot) as attendance,''
	FROM stats_day
	where year_month_day > '20120000' and year_month_day < '$lastDate' 
	group by park,cutYear,cutMonth 
	order by cutYear,cutMonth";  //echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	
	$sql = "update stats_f_year set `f_year`= CASE `cutMonth`
	when '01' then concat(right(cutYear-1,2),right(cutYear,2))
	when '02' then concat(right(cutYear-1,2),right(cutYear,2))
	when '03' then concat(right(cutYear-1,2),right(cutYear,2))
	when '04' then concat(right(cutYear-1,2),right(cutYear,2))
	when '05' then concat(right(cutYear-1,2),right(cutYear,2))
	when '06' then concat(right(cutYear-1,2),right(cutYear,2))
	when '07' then concat(right(cutYear,2),right(cutYear+1,2))
	when '08' then concat(right(cutYear,2),right(cutYear+1,2))
	when '09' then concat(right(cutYear,2),right(cutYear+1,2))
	when '10' then concat(right(cutYear,2),right(cutYear+1,2))
	when '11' then concat(right(cutYear,2),right(cutYear+1,2))
	when '12' then concat(right(cutYear,2),right(cutYear+1,2))
	end";//echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	
	$sql = "SELECT park,f_year,sum(attendance) FROM `stats_f_year` WHERE 1 group by f_year,park order by park,cutYear";
	if(!empty($f_year))
		{
	$sql = "SELECT park,f_year,sum(attendance) FROM `stats_f_year` WHERE 1 and f_year='$f_year'
	group by f_year,park order by park,cutYear";
	}
//	echo "$sql";
	
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	while ($row=mysqli_fetch_array($result))
	{$aYear[]=$row[1];$aPark[]=$row[0];$parkTotYear[]=$row[2];}
	
	echo "<html><body><div align='center'>
	<table><tr><td>
	<form action='annual_attend.php'>Calendar Year:
	<input type='hidden' name='yearType' value='c'>
	<input type='submit' name='submit' value=''>
	</form></td>
	 <td><form action='annual_attend.php'>Fiscal Year - A (by Park): 
	<input type='hidden' name='yearType' value='f'>
	<input type='submit' name='submit' value=''>
	</form></td>
	 <td><form action='annual_attend.php'>Fiscal Year - B (for all Parks): 
	<input type='hidden' name='yearType' value='b'>
	<input type='submit' name='submit' value=''>
	</form>
	</td>
	 <td><form action='annual_attend.php'> 
	 Fiscal Year: <input type='text' name='f_year' value=\"\" size='5'>
	<input type='hidden' name='yearType' value='f'>
	<input type='submit' name='submit' value='CAFR Report'>
	</form>
	</td></tr></table>
	<table border='1' cellpadding='5'>";
	echo "<tr><th colspan='4'>Fiscal Year - A (by Park)</th></tr>";
	echo "<tr><th>Fiscal<br>Year</th><th>Park</th><th>TOTAL<br>VISITATION</th>";
	if(empty($f_year))
		{echo "<th> % CHANGE<br>from<br>Previous Year</th>";}
	
	echo "</tr>";
	
	$j=0;
	for($i=0;$i<count($aYear);$i++)
		{
		$yearAttend=number_format($parkTotYear[$i],0);
		@$perCentChangeM=number_format((($parkTotYear[$i]/$parkTotYear[$i-1])-1)*100);
		
		@$systemTot+=$parkTotYear[$i];
		
		echo "<tr><td align='center'>$aYear[$i]</td>
		<td align='center'>$aPark[$i]</td>
		<td align='right'>$yearAttend</td>";
		if(empty($f_year))
			{
		if($aYear[$i]!="9900")
			{
			if($aYear[$i]==$f_year){echo "<td align='center'>(Jul $year to Today)</td>";}else{
			echo "<td align='center'>$perCentChangeM%</td>";}
			}
			else
			{echo "<td align='center' bgcolor='goldenrod'>(Jan-Jun 2000)</td>";}
			}
		
		echo "</tr>";
		}
	
	$systemTot=number_format($systemTot,0);
	echo "<tr><td></td><td></td>
	<td align='right'>$systemTot</td>
	</tr>";
	}// end Fiscal Year A

// ********** Calendar Year *************
if(@!$yearType OR @$yearType=="c")
	{
	$ckC="checked";$ckF="";$ckB="";
// pre 2012	
	$sql = "SELECT left(year_month_week,4) as cutYear, sum(attend_tot) FROM stats
	where (year_month_week > '$findYearB' and year_month_week < '$findYearE')
	AND left(year_month_week,4)<2012
	group by cutYear order by cutYear";  //echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	while ($row=mysqli_fetch_array($result))
		{
		$aYear[]=$row[0];
		$parkTotYear[$row[0]]=$row[1];
		}
// post 2011	
	$sql = "SELECT left(year_month_day,4) as cutYear, sum(attend_tot) FROM stats_day
	where (year_month_day > '20120000' and year_month_day < '$findYearE')
	group by cutYear order by cutYear";  //echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	while ($row=mysqli_fetch_array($result))
		{
		$aYear[]=$row[0];
		$parkTotYear[$row[0]]=$row[1];
		}
//	echo "<pre>"; print_r($parkTotYear); echo "</pre>"; // exit;
	
	echo "<html><body><div align='center'>
	<table><tr><td>
	<form action='annual_attend.php'>Calendar Year:
	<input type='hidden' name='yearType' value='c'>
	<input type='submit' name='submit' value=''>
	</form></td>
	 <td><form action='annual_attend.php'>Fiscal Year - A (by Park): 
	<input type='hidden' name='yearType' value='f'>
	<input type='submit' name='submit' value=''>
	</form></td>
	 <td><form action='annual_attend.php'>Fiscal Year - B (for all Parks): 
	<input type='hidden' name='yearType' value='b'>
	<input type='submit' name='submit' value=''>
	</form>
	 <td><form action='annual_attend.php'> 
	 Fiscal Year: <input type='text' name='f_year' value=\"\" size='5'>
	<input type='hidden' name='yearType' value='f'>
	<input type='submit' name='submit' value='CAFR Report'>
	</form>
	</td></tr></table>
	<table border='1' cellpadding='5'>";
	
	echo "<tr><th colspan='3'>Calendar Year for all Parks</th></tr>";
	echo "<tr><th>YEAR</th><th>TOTAL<br>VISITATION</th><th> % CHANGE<br>from<br>Previous Year</th></tr>";
	
	for($i=0;$i<count($parkTotYear);$i++)
		{
		$yearAttend=number_format($parkTotYear[$aYear[$i]],0);
		@$perCentChangeM=number_format((($parkTotYear[$aYear[$i]]/$parkTotYear[$aYear[$i-1]])-1)*100);
		
		@$systemTot+=$parkTotYear[$aYear[$i]];
		
		echo "<tr><td align='center'>$aYear[$i]</td>
		<td align='right'>$yearAttend</td>";
		if($i>0){
		if($aYear[$i]==$year){$pcc="year not complete";}else{$pcc=$perCentChangeM."%";}
		echo "<td align='center'>$pcc</td>";}else{
		echo "<td align='center'></td>";}
		echo "</tr>";
		}
	
	$systemTot=number_format($systemTot,0);
	echo "<tr><td><b>$i YEAR TOTAL</b></td>
	<td align='right'>$systemTot</td>
	</tr>";
	}
echo "</table></div></body></html>";
?>