<?php
mysqli_select_db($connection, $database);
//ini_set('display_errors',1);
$database = "dprcal";
//include("../../include/auth.inc");
include("../../include/connectROOT.inc");
include("../../include/get_parkcodes.php");
mysqli_select_db($connection, $database);
extract($_REQUEST);

if (@$title == "" and @$yearRadio == "" and @$yearText == "" and @$month == "" and @$body == "") {
	echo "You did not enter any search item(s).<br><br>Click your Back button.";
	exit;
}
date_default_timezone_set('America/New_York');
// reformat any variables
if ($yearRadio != "") {
	$year = date('Y');
	$month = date('m');
	$monthName = date('M');
	$yearPrevious = $year - 1;
	$yearNext = $year + 1;
	$currMonth = $year . "-" . $month . "%";
}

if ($yearRadio == "cm") {
	$varFind = "dateCal LIKE '$currMonth' AND public != \"\"";
}

if ($yearRadio == "cy") {
	$varFind = "((dateCal > '$year-$month-00' and datecal < '$yearNext-01-01')) AND (public != \"\")";
}

if ($yearRadio == "ny") {
	$varFind = "((dateCal > '$yearNext-01-00' and datecal < '$yearNext-12-31')) AND (public != \"\")";
	$year = $yearNext;
}

$sql = "SELECT * FROM calendar 
LEFT JOIN train ON calendar.dateCal=train.dateFind 
WHERE
$varFind ORDER BY calendar.dateCal,train.title";

// echo "$sql";
$total_result = @mysqli_query($connection, $sql) or die("Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
$total_found = @mysqli_num_rows($total_result);
if ($total_found < 1) {
	echo "No training has been entered for: <b>$year </b><br>Click your browser's back button.";
	exit;
}
echo "<html><head><title>NC State Parks System Training Calendar</title>
<STYLE TYPE=\"text/css\">
<!--
body
{background-color:#CCCCCC; font-family:monospace;
font-size:100%;}
--> 
</STYLE> </head>";
include("navpub.php");
$space = "&nbsp;&nbsp;&nbsp;";
$space15 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$search = "Search results for: <h3>NC State Parks System Training Calendar</h3>";
echo "<body>
$search
<hr>";
while ($row = mysqli_fetch_array($total_result)) {
	extract($row);
	$atime = strftime('%a, %b %d', strtotime($dateCal));
	if ($park == "") {
		$loc = "at " . $location;
	} else {
		$loc = " at ($parkCodeName[$park])";
	}
	if ($title != "") {
		$trainID = urlencode($trainID);
		$link = "<a href='trainpub.php?trainID=$trainID'>$title</a> $loc";
	} else {
		$link = "";
	}
	if (@$tempDay == $dateCal) {
		echo "<br>$space15 $link";
	} else
	if (substr("$atime", 0, 1) == "S") {
		echo "<font color = 'red'><br><br>$atime</font> $space $link";
	} else {
		echo "<br><br>$atime $space $link";
	}
	$tempDay = $dateCal;
}
echo "<hr>";
include("navpub.php");
?>
</body>

</html>