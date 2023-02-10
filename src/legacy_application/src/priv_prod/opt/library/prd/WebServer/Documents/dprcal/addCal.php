<?php
// extract ($_REQUEST);
session_start();
include("nav.php");

ini_set('display_errors',1);
$database="dprcal";
include("../../include/auth.inc");
include("../../include/iConnect.inc");
mysqli_select_db($connection,$database);

// echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
$pass_enter_by="";
if(!empty($enter_by))
	{
	$pass_enter_by=$enter_by;
	}
	
if ($monthBegin == "") {echo "Click your BACK button and enter a BEGINNING MONTH before submitting a report."; exit;}
if ($dayBegin == "") {echo "Click your BACK button and enter a BEGINNING DAY before submitting a report."; exit;}
if ($yearBegin == "") {echo "Click your BACK button and enter a BEGINNING YEAR before submitting a report."; exit;}

if ($monthEnd == "") {echo "Click your BACK button and enter a ENDING MONTH before submitting a report."; exit;}
if ($dayEnd == "") {echo "Click your BACK button and enter a ENDING DAY before submitting a report."; exit;}
if ($yearEnd == "") {echo "Click your BACK button and enter a ENDING YEAR before submitting a report."; exit;}
if ($title == "" and $title_alt=="") {echo "Click your BACK button and enter a CLASS TITLE before submitting a report."; exit;}
if ($yearBegin != $yearEnd) {echo "Click your BACK button and make sure both the Beginning and Ending Year are the same."; exit;}
if ($dist == "") {echo "Click your BACK button and enter a DISTRICT before submitting a report."; exit;}

//if ($adm.$cert.$skills.$main.$safe.$law.$med.$res.$tra == ""){echo "Click your BACK button and enter a ACTIVITY before submitting a report."; exit;}
///*
echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
//*/
echo "<html>
<head>
<title>NC DPR Calendar - New Class</title>
</head>
<body>";
if (checkdate($monthBegin, $dayBegin, $yearBegin)!= "true") {echo "Invalid beginning date. Click your BACK button.";exit;}

if (checkdate($monthEnd, $dayEnd, $yearEnd)!= "true") {echo "Invalid ending date. Click your BACK button.";exit;}

$newdateBegin = date ("Y-m-d", mktime(0,0,0,$monthBegin,$dayBegin,$yearBegin));
$newdateEnd = date ("Y-m-d", mktime(0,0,0,$monthEnd,$dayEnd,$yearEnd));
$week = date ("W", mktime(0,0,0,$monthBegin,$dayBegin,$yearBegin));

if ($newdateBegin > $newdateEnd) {echo "Invalid beginning or ending date. Click your BACK button.";exit;}

$checkDate = date ("Y-m-d");
if($_SESSION['dprcal']['levelS']!="SUPERADMIN")
	{
	if($newdateBegin < $checkDate){echo "Begin date is earlier than today!<br><br>Click your browser's BACK button and make the correction to the date.";exit;}
	}
$title=urldecode($title);
// $title=addslashes($title);

// Get keywords, activities from class
$sql = "SELECT * from course where title = '$title'";
if(!empty($title_alt))
	{
	$exp=explode("*", $title_alt);
	$clid=$exp[1];
	$sql = "SELECT * from course where clid = '$clid'";
	}
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
while ($row = mysqli_fetch_array($result))
	{
	extract($row);
	}
	
if(!isset($concat)){$concat="";}
if ($adm != ""){$concat = "Administration ";}
if ($cert != ""){$concat = $concat." EE Certification";}
if ($skills != ""){$concat = $concat." Advanced Interpretive Training";}
if ($main != ""){$concat = $concat." Maintenance";}
if ($safe != ""){$concat = $concat." Safety";}
if ($law != ""){$concat = $concat." Law Enforcement";}
if ($med != ""){$concat = $concat." Medical";}
if ($res != ""){$concat = $concat." Resource Management";}
if ($tra != ""){$concat = $concat." Trails";}

$date2 = mktime(0,0,0,$monthBegin,$dayBegin,$yearBegin);
$date1 = mktime(0,0,0,$monthEnd,$dayEnd,$yearEnd);
$numDays = ( date( "Y", $date1 ) * 366 + date( "z", $date1 ) ) -
       ( date( "Y", $date2 ) * 366 + date( "z", $date2 ) );
$count = (1 + $numDays);
// $title=addslashes($title);
@$trainID=urldecode($trainID);
$location=str_replace("\r", " ", $location );
$location=str_replace("\n", " ", $location );
// $comment=addslashes($comment);
// $contact=addslashes($contact);
$trainID=date("h-m-s",mktime()).$title;
$dayFind = $dayBegin;
$newdateFind = $newdateBegin;
if(!isset($public)){$public="";}
if(!isset($online)){$online="";}
for ($i = 1; $i <= $count; $i++)
	{
	$query = "INSERT INTO train (dateFind,dateBegin, dateEnd, enter_by, title, public, dist, location, contact, comment, startTime, endTime, trainID, activity, keyword,park,maxClass,minClass,clid,online) VALUES ('$newdateFind', '$newdateBegin','$newdateEnd','$pass_enter_by', '$title', '$public', '$dist', '$location', '$contact', '$comment' ,'$startTime', '$endTime', '$trainID', '$concat', '$keyword','$park','$maxClass','$minClass','$clid','$online')";
	
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query. $query");
	$test=mysqli_insert_id($connection);
	$dayFind = $dayFind +1;
	$newdateFind = date ("Y-m-d", mktime(0,0,0,$monthBegin,$dayFind,$yearBegin));
	}
	
if($test!="")
	{
	header("Location: /dprcal/findJoin.php?dist=$dist&title=$title&month=$monthBegin&year=$yearBegin");
	}
else
{echo "No go. Class not added.";$query;}
