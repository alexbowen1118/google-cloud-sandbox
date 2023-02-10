<?php
ini_set('display_errors',1);

date_default_timezone_set('America/New_York');
extract($_REQUEST);

$database="attend";
include("../../../include/auth.inc");

// echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;

$database="park_use";
include("../../../include/iConnect.inc");

include("../../../include/get_parkcodes_reg.php");

$parkCode[]="MTST";
$parkCodeName['MTST']="Mountains To Sea Trail";
sort($parkCode);

mysqli_select_db($connection,$database);


$varQuery="parkcode=$parkcode";

if(@!$xls and @!$Lname)
	{
	include("r_vol_hours_menu.php");
	}// ignore menu for Excel export

include("../css/TDnull.inc");

if(@$xls=="excel")
	{
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=Volunteer_Hours.xls');
	}

if(@!$Lname)
	{
	$WHERE = "WHERE park='$parkcode'";
	$len=strlen($year_month);
	if($year_month and $len>4){$WHERE.=" AND `year_month`='$year_month'";}
	if($year_month and $len==4){$WHERE.=" AND `year_month` LIKE '$year_month%'";}
	
	if($cat)
		{
		$test="_";
		$pos=strpos($cat,$test);
		if($pos>0){$cat=$cat;}else{$cat=$categories[$cat];}
		$WHERE.=" AND `$cat`>'0'";
		}
		
	if($category){$WHERE.=" AND `category`='$category'";}
	
	// Get park totals for Everyone
	echo "<html><head></head><body><div align='center'><table>";
	$testMinMonth=date("Ym");// seed min month
	$sql = "SELECT category,comments,Lname, Fname, max(`year_month`) as maxMonth, min(`year_month`) as minMonth, sum(admin_hours+camp_host_hours+trail_hours+ie_hours+main_hours+research_hours+res_man_hours+other_hours) as tot FROM `vol_stats` $WHERE  group by Lname, Fname order by tot desc";
// echo "$sql";
	
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	while ($row=mysqli_fetch_array($result))
		{
		extract($row);
		if($Lname){
		@$totalHours+=$tot;
		if($maxMonth>@$testMaxMonth){$testMaxMonth=$maxMonth;}
		if($minMonth<$testMinMonth){$testMinMonth=$minMonth;}
		$LnameU=urlencode($Lname);
		$LnameLink="<a href='r_vol_hours.php?Lname=$LnameU&Fname=$Fname&parkcode=$parkcode' target='_blank'>$Lname</a>";
		echo "<tr>
		<td align='center'>$parkcode</td>
		<td>$category</td>
		<td>$LnameLink</td>
		<td>$Fname</td>
		<td>$comments</td>
		<td align='right'>$tot</td>
		</tr>";}
		}
	@$totalHours=number_format($totalHours,1);
	if(!isset($testMaxMonth)){$testMaxMonth="";}
	echo "<tr><td><b>PARK TOTAL</b> from $testMinMonth to $testMaxMonth</td>
	<td align='right' colspan='3'>$totalHours</td>
	</tr>";
	echo "</table></div></body></html>";
	exit; 
	}

if($Lname and $parkcode)
	{
	// Get park totals for Individual
	echo "<html><head></head><body><div align='center'><table cellpadding='3'><tr>";
	
	$sql="SHOW COLUMNS FROM vol_stats from park_use";
	$result = mysqli_query($connection,$sql);
	while($array=mysqli_fetch_array($result)){
	$keyName[]=$array[0];}
	for($i=2;$i<count($keyName);$i++){
	$k=str_replace("_","<br>",$keyName[$i]);
	echo "<th>$k</th>";
	}
	echo "<th>Month<br>Total</th></tr>";
	
	if(!empty($Fname))
		{
	$sql = "SELECT * FROM `vol_stats` WHERE Lname='$Lname' and Fname='$Fname' and park='$parkcode' order by `year_month` desc";}
		else
		{
	$sql = "SELECT * FROM `vol_stats` WHERE Lname='$Lname' and park='$parkcode' order by `year_month` desc";}
	
// 	echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	
	while ($row=mysqli_fetch_array($result))
	{extract($row);
	@$admin_hours_tot+=$admin_hours;
	@$camp_host_hours_tot+=$camp_host_hours;
	@$trail_hours_tot+=$trail_hours;
	@$ie_hours_tot+=$ie_hours;
	@$main_hours_tot+=$main_hours;
	@$research_hours_tot+=$research_hours;
	@$res_man_hours_tot+=$res_man_hours;
	@$other_hours_tot+=$other_hours;
	
	$monthTotal=$admin_hours+$camp_host_hours+$trail_hours+$ie_hours+$main_hours+$research_hours+$res_man_hours+$other_hours;
	@$grandTotal+=$monthTotal;
	echo "<tr>
	<td>$year_month</td>
	<td>$Lname</td>
	<td>$Fname</td>
	<td align='right'>$admin_hours</td><td align='right'>$camp_host_hours</td><td align='right'>$trail_hours</td><td align='right'>$ie_hours</td><td align='right'>$main_hours</td><td align='right'>$research_hours</td><td align='right'>$res_man_hours</td><td align='right'>$other_hours</td>
	<td>$comments</td>
	<td align='right'>$monthTotal</td>
	</tr>";
	}
	echo "<tr><td></td>
	<td align='right' colspan='2'>Total Hours: </td>
	<td align='right'>$admin_hours_tot</td><td align='right'>$camp_host_hours_tot</td><td align='right'>$trail_hours_tot</td><td align='right'>$ie_hours_tot</td><td align='right'>$main_hours_tot</td><td align='right'>$research_hours_tot</td><td align='right'>$res_man_hours_tot</td><td align='right'>$other_hours_tot</td>
	<td align='right'>Grand Total:</td><td align='right'>$grandTotal</td>
	</tr>";
	echo "</table></div></body></html>";
	}


if($Lname and $parkcode=="")
	{
	// Get park totals for Individual
	echo "<html><head></head><body><div align='center'><table cellpadding='3'><tr>";
	
	$sql="SHOW COLUMNS FROM vol_stats from park_use";
	$result = mysqli_query($connection,$sql);
	while($array=mysqli_fetch_array($result)){
	$keyName[]=$array[0];}
	for($i=2;$i<count($keyName);$i++){
	$k=str_replace("_","<br>",$keyName[$i]);
	echo "<th>$k</th>";
	}
	echo "<th>Month<br>Total</th></tr>";
	
	$Fname=str_replace("*","&",$Fname);
	
	$sql = "SELECT * FROM `vol_stats` WHERE Lname='$Lname' and Fname='$Fname' order by `year_month`";    //echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	$sql1 = "SELECT * FROM `vol_stats` WHERE Lname='$Lname' and Fname='$Fname' order by `year_month` desc";    //echo "$sql1";
	$result1 = mysqli_query($connection,$sql1) or die ("Couldn't execute query 1. $sql1");
	
	while ($row=mysqli_fetch_array($result))
		{
		extract($row);
		@$admin_hours_tot+=$admin_hours;
		@$camp_host_hours_tot+=$camp_host_hours;
		@$trail_hours_tot+=$trail_hours;
		@$ie_hours_tot+=$ie_hours;
		@$main_hours_tot+=$main_hours;
		@$research_hours_tot+=$research_hours;
		@$res_man_hours_tot+=$res_man_hours;
		@$other_hours_tot+=$other_hours;
		$monthTotal=$admin_hours+$camp_host_hours+$trail_hours+$ie_hours+$main_hours+$research_hours+$res_man_hours+$other_hours;
			@$total+=$monthTotal;
			if($total>39 and @$level0=="")
				{
				$running_total[$row['year_month']]=40;
				$level0="1";
				}
			if($total>99 and @$level1=="")
				{
				$running_total[$row['year_month']]=100;
				$level1="1";
				}
			if($total>199 and @$level2=="")
				{
				$running_total[$row['year_month']]=200;
				$level2="1";
				}
			if($total>299 and @$level3=="")
				{
				$running_total[$row['year_month']]=300;
				$level3="1";
				}
		}
	//echo "test<pre>"; print_r($running_total); echo "</pre>"; // exit;
	
	unset($admin_hours_tot,$camp_host_hours_tot,$trail_hours_tot,$main_hours_tot,$ie_hours_tot,$research_hours_tot,$res_man_hours_tot,$other_hours_tot);
	
	while ($row=mysqli_fetch_array($result1))
		{
		extract($row);
		@$admin_hours_tot+=$admin_hours;
		@$camp_host_hours_tot+=$camp_host_hours;
		@$trail_hours_tot+=$trail_hours;
		@$ie_hours_tot+=$ie_hours;
		@$main_hours_tot+=$main_hours;
		@$research_hours_tot+=$research_hours;
		@$res_man_hours_tot+=$res_man_hours;
		@$other_hours_tot+=$other_hours;
		
		$monthTotal=$admin_hours+$camp_host_hours+$trail_hours+$ie_hours+$main_hours+$research_hours+$res_man_hours+$other_hours;
		
		@$parkArray[$park]+=$monthTotal;
		
		@$grandTotal+=$monthTotal;
		
		@$check=$running_total[$year_month];
		
		if($check>0 AND $check!=$checkCheck){$tr=" bgcolor='aliceblue'";}else{$tr="";}
		$checkCheck=$check;
		if($tr==""){$check="";}
		echo "<tr$tr>
		<td>$year_month</td>
		<td>$Lname</td>
		<td>$Fname</td>
		<td align='right'>$admin_hours</td><td align='right'>$camp_host_hours</td><td align='right'>$trail_hours</td><td align='right'>$ie_hours</td><td align='right'>$main_hours</td><td align='right'>$research_hours</td><td align='right'>$res_man_hours</td><td align='right'>$other_hours</td>
		<td>$comments</td>
		<td align='right'>$monthTotal</td>
		<td align='right'>$check</td>
		</tr>";
		}
	echo "<tr><td></td>
	<td align='right' colspan='2'>Total Hours: </td>
	<td align='right'>$admin_hours_tot</td><td align='right'>$camp_host_hours_tot</td><td align='right'>$trail_hours_tot</td><td align='right'>$ie_hours_tot</td><td align='right'>$main_hours_tot</td><td align='right'>$research_hours_tot</td><td align='right'>$res_man_hours_tot</td><td align='right'>$other_hours_tot</td>
	<td align='right'>Grand Total:</td><td align='right'>$grandTotal</td>
	</tr>";
	echo "<tr><td>Parks: </td>";
	foreach($parkArray as $k=>$v){
		$link="<a href='/attend/a/r_vol_hours.php?Lname=$Lname&Fname=$Fname&parkcode=$k'>$k</a>";
		echo "<td><b>$link</b>=$v</td>";}
	echo "</tr>";
	echo "</table></div></body></html>";
	}
?>