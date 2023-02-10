<?php

$database="dprcal";
include("../../include/auth.inc");
include("../../include/iConnect.inc");
mysqli_select_db($connection,$database);
//extract($_POST);

if($level>3)
	{ini_set('display_errors',1);}
	
if (@$title=="" AND @$yearRadio=="" AND @$yearText=="" AND @$month=="" AND @$body==""){echo "You did not enter any search item(s).<br><br>Click your Back button."; exit;}
// reformat any variables

date_default_timezone_set('America/New_York');
if ($month != ""){$month=substr("00$month",-2);} // add a zero before single digit months
if (@$yearRadio != "") {$year = "$yearRadio";}
if (@$yearText != "") {$year = "$yearText";}

$compare = "AND";
$search="";
//  create the WHERE clause using variables passed from index.php
if ($dist != ""){@$var4 = "(dist = '$dist') $compare "; $search="(district = $dist)";}

if (@$activity != "")
{@$var0 = "(activity LIKE '%$activity%') $compare "; $search="$search (activity = $activity)";}

if (@$title != "")
{@$var1 = "(title LIKE '%$title%') $compare "; $search="$search (title = $title)";}

if (@$keyword != "")
{@$var2 = "(keyword LIKE '%$keyword%' OR trainID LIKE '%$keyword%') $compare"; $search="$search (keyword = $keyword)";}

if (@$year != "" and @$month =="" and @$monthRadio =="")
{$var3 = "(dateCal LIKE '$year%') $compare "; $period="for the entire year.";}

if (@$year != "" and @$monthRadio !="")
	{
	$yearNext = $year +1; 
	$var3 = "(dateCal >= '$year-$monthRadio-01' and datecal < '$yearNext-01-01') $compare ";
		if(isset($longMonth)){$longMonth="$longMonth-01";}
		else
		{$longMonth=$monthRadio;}
	$period=" from $longMonth to end of year.";
	}

if ($year != "" and $month !=""){$yearNext = $year +1; $var3 = "(dateCal LIKE '$year-$month%') $compare "; $period="for the $month month.";}

@$find = $var0.$var1.$var2.$var3.$var4; // concat the search terms

$varFind = substr_replace($find, '', -4, -1); // removes the last OR or AND from WHERE clause
$group_by="";
if(@$format=="")
	{
	$varFind .= " and train.del != 'x'";
//	$group_by="group by train.clid";
	$group_by="group by train.title, train.dateBegin, train.park, train.startTime, train.enter_by, train.location";
	}

$sql = "SELECT * FROM calendar
LEFT JOIN train ON calendar.dateCal=train.dateFind
WHERE
$varFind
$group_by
ORDER BY calendar.dateCal,train.title";

// echo "$sql<br /><br />";
$total_result = @mysqli_query($connection,$sql) or die("Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
$total_found = @mysqli_num_rows($total_result);
if ($total_found < 1){echo "No training found using: <b>$title $keyword $year $month $activity</b><br>Click your browser's back button."; exit;}

if(@$format!="")
	{
	echo "<html><head><title>NC DPR Training Calendar</title>
	<STYLE TYPE=\"text/css\">
	<!--
	body
	{background-color:#CCCCCC; font-family:monospace;
	font-size:85%;}
	--> 
	</STYLE> </head>";
	include("nav.php");
	$space = "&nbsp;&nbsp;&nbsp;";
	$format="Show <a href='findJoin.php?dist=$dist&activity=$activity&title=$title&keyword=$keyword&monthRadio=$monthRadio&month=$month&yearRadio=$yearRadio'>Only Days</a> with Training $period";
	if($search){$search = "searched: ".$search;}
	echo "<body><h3>The $year<br>NC DPR Training Calendar</h3>
	$search $period $format
	<hr>";
	while ($row = mysqli_fetch_array($total_result))
		{
		extract($row);
		if(strpos($title, "Armorer")>0){continue;}
		$atime=strftime('%a, %b %d',strtotime($dateCal));
		if($title!=""){$trainIDu=urlencode(addslashes($trainID));
		$link="<a href='trainDetail.php?tid=$tid&trainID=$trainIDu'>$title($dist-$park)</a>";}
		else {$link="";}
		if(@$tempDay==$dateCal)
			{
			echo "$space $link";
			}
		else
		if (substr("$atime", 0, 1)=="S")
			{
			echo "<font color = 'red'><br>$atime $space $link</font>";	
			}
			ELSE 
			{echo "<br>$atime $space $link";}
		$tempDay=$dateCal;
		}
	echo "<hr>";
	include("nav.php");
	}

if(@$format=="")
	{
	echo "<html><head><title>NC DPR Training Calendar</title>
	<STYLE TYPE=\"text/css\">
	<!--
	body
	{background-color:#CCCCCC; font-family:sans-serif;
	font-size:100%;}
	td
	{font-family:monospace;font-size:110%;}
	th
	{font-family:sans-serif;font-size:90%;}
	--> 
	</STYLE> </head>";
	include("nav.php");
	if($dist=='' and $activity=="" and $title=="" and $keyword=="")
		{
		$format="Show <a href='findJoin.php?dist=$dist&activity=$activity&title=$title&keyword=$keyword&monthRadio=$monthRadio&month=$month&yearRadio=$year&format=1'>All Days</a> $period Dates in <font color='green'><b>green</b></font> are a single-day class. Dates in <font color='brown'><b>brown</b></font> are a multi-day class.";
		}
	
	if(isset($search)){$search = "searched: ".$search;}else{$search="";}
	if(!isset($format)){$format="";}
	echo "<body><h3>The $year<br>NC DPR Training Calendar</h3>
	$search $period $format
	<hr><table cellpadding='5'>";
	while ($row = mysqli_fetch_array($total_result))
		{
		extract($row);
// 		if(strpos($title, "Armorer")>0 and $level<4)
// 			{
// 			if(empty($var_t))
// 				{
// 				echo "An issue with the signup for $title  
// 				has been reported. It is being worked on. Sorry for the inconvenience.<br />";
// 				}
// 			 $var_t=1;
// 			continue;
// 			}
		$atime=strftime('%a, %b %d',strtotime($dateCal));
		$month=substr($atime,5,3);
		if($park==""){$loc="-".$location;}else{$loc="";}
		if($dateBegin==$dateEnd)
			{$start_end=" <font color='green'>".$dateBegin."</font>";}
			else
			{$start_end=" <font color='brown'>".$dateBegin." thru ".$dateEnd."</font>";}
		
		$link="<a href='trainDetail.php?tid=$tid'>$title</a> ($dist-$park$loc$start_end) $startTime";
		if(@$tempMonth!=$month){echo "<tr><th>$month</th><th></th></tr>";}
		if(@$tempDay==$dateCal)
			{
			echo "<tr><td>&nbsp;</td><td>$link</td></tr>";
			}
		else
		if (substr("$atime", 0, 1)=="S")
			{
			echo "<tr><td><font color = 'red'>$atime</font></td><td>$link</td><tr>";
			
			}
			ELSE
			{
			echo "<tr><td width='110' valign='top' align='right'>$atime</td><td>$link</td></tr>";
			}
		$tempDay=$dateCal;
		$tempMonth=$month;
		}// end while
	echo "</table><hr>";
	include("nav.php");
	}
?>
</body></html>