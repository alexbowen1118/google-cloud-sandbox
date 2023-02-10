<?php
ini_set('display_errors',1);
$database="dprcal";
include("../../include/auth.inc");
include("../../include/iConnect.inc");
mysqli_select_db($connection,$database);
extract($_POST);

include("nav.php");
//echo "<pre>";print_r($_REQUEST);echo "</pre>";  // exit;
if($Submit == "Submit")
	{
	//print_r($_REQUEST);//exit;
// 	$location=addslashes($location);
// 	$comment=addslashes($comment);
// 	$contact=addslashes($contact);
// 	$instructions=addslashes($instructions);
	
	if(!empty($reason)){$del="x";}
	else{$reason="";$del="";}
	
	$yearBegin=substr($dateBegin,0,4);
	$monthBegin=substr($dateBegin,5,2);
	$day=substr($dateBegin,8,2);
	
	$dayBegin=$day;
	$dayFind=$day;
	
	//echo "$year $month $day.";
	
	$year1=substr($dateEnd,0,4);
	$month1=substr($dateEnd,5,2);
	$day1=substr($dateEnd,8,2);
	
	$date2 = mktime(0,0,0,$monthBegin,$day,$yearBegin);
	$date1 = mktime(0,0,0,$month1,$day1,$year1);
	$numDays = ( date( "Y", $date1 ) * 366 + date( "z", $date1 ) ) -
		   ( date( "Y", $date2 ) * 366 + date( "z", $date2 ) );
	$count = (1 + $numDays);
	
//	echo "<br />$date1 $date2 n=$numDays  c=$count<br />"; //exit;
	
	//$dayFind = $dateBegin;
	$newdateBegin = date ("Y-m-d", mktime(0,0,0,$monthBegin,$dayBegin,$yearBegin));
	$newdateFind = $newdateBegin;
	
	if(!isset($public)){$public="";}
	if(!isset($online)){$online="";}
	// Get records to update
	$sql = "SELECT tid from train where trainID = '$trainID'";
	//echo "$sql<br>";//exit;
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	while ($row = mysqli_fetch_array($result))
		{
		extract($row);
		$query = "UPDATE train set public='$public',location='$location', contact='$contact', comment='$comment', startTime='$startTime', endTime='$endTime', maxClass='$maxClass', minClass='$minClass', dateFind='$newdateFind', dateBegin='$dateBegin', dateEnd='$dateEnd', online='$online', reason='$reason', del='$del' , instructions='$instructions' WHERE tid='$tid'";
//		echo "$query<br>";exit;
		$result0 = mysqli_query($connection,$query) or die ("Couldn't execute query. $query");
		
		$dayFind = $dayFind +1;
		$newdateFind = date ("Y-m-d", mktime(0,0,0,$monthBegin,$dayFind,$yearBegin));
		
		$query1 = "UPDATE signup set dateClass='$dateBegin' WHERE tid='$tid'";
		$result1 = mysqli_query($connection,$query1) or die ("Couldn't execute query1. $query1");
		}// end while
	
	echo "<h2>Update successful.</h2>";
	
	}// end Submit

if($Submit == "Delete"){
//$trainID=urldecode($trainID);
$query = "DELETE from train where tid ='$tid'";

$result = mysqli_query($connection,$query) or die ("Couldn't execute query. $query");
echo "Class has been deleted.";}
?> 

