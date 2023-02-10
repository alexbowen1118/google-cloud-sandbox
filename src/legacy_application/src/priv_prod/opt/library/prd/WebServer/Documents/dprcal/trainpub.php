<?php

//ini_set('display_errors',1);
$database = "dprcal";
include("../../include/connectROOT.inc");
//include("../../include/get_parkcodes.php");
mysqli_select_db($connection, $database);;
extract($_REQUEST);

$trainID = mysqli_real_escape_string($connection, urldecode($trainID));
$sql = "SELECT * From train WHERE trainID = '$trainID'";
$total_result = @mysqli_query($connection, $sql) or die("Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));

echo "<html><head><title></title></head><body>";
include("navpub.php");
echo "<h3>Class Information</h3><hr>
<table>";
while ($row = mysqli_fetch_array($total_result)) {
	extract($row);

	$location = nl2br($location);
	$contact = nl2br($contact);
}

//Find number enrolled
if (!isset($tid)) {
	$tid = "";
}
$sql1 = "SELECT supid From signup WHERE signup.tid ='$tid' AND signup.del !='x'";
$total_result1 = @mysqli_query($connection, $sql1) or die("$sql Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
$enrolled = mysqli_num_rows($total_result1);

@$title = mysqli_real_escape_string($connection, $title);
$sql = "SELECT * From course WHERE title = '$title'";
//echo "<br>$sql<br>";
$result = @mysqli_query($connection, $sql) or die("Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
$concat = "";
while ($row = mysqli_fetch_array($result)) {
	extract($row);
	if ($adm != "") {
		$concat = "Administration<br>";
	}
	if ($cert != "") {
		$concat = $concat . " EE Certification<br>";
	}
	//if ($skills != ""){$concat = $concat." Advanced Interpretive Training<br>";}
	if ($main != "") {
		$concat = $concat . " Maintenance<br>";
	}
	if ($safe != "") {
		$concat = $concat . " Safety<br>";
	}
	if ($law != "") {
		$concat = $concat . " Law Enforcement<br>";
	}
	if ($med != "") {
		$concat = $concat . " Medical<br>";
	}
	if ($res != "") {
		$concat = $concat . " Resource Management<br>";
	}
	if ($tra != "") {
		$concat = $concat . " Trails";
	}
}
if (!isset($maxClass)) {
	$maxClass = "";
}
if ($enrolled >= $maxClass) {
	$mx = "<font color='red'>Maximum class size ($maxClass) has been reached.</font> You might consider placing your name on the waiting list. (Contact the person listed in \"Contact Info\".)";
}

if (!isset($mx)) {
	$mx = "";
}
if (!isset($dateBegin)) {
	$dateBegin = "";
}
if (!isset($dateEnd)) {
	$dateEnd = "";
}
if (!isset($startTime)) {
	$startTime = "";
}
if (!isset($endTime)) {
	$endTime = "";
}
if (!isset($dist)) {
	$dist = "";
}
echo "<tr><td valign='top'><b>Title:</b></td><td><h3>$title</h3>$mx</td></tr>
<tr><td> </td><td> </td></tr>
<tr><td><b>Activity: </b></td><td>$concat</td></tr>
<tr><td> </td><td> </td></tr></table>
<hr>
<table><tr><td><b>Beginning Date:</b>&nbsp;&nbsp;</td><td> $dateBegin &nbsp;&nbsp;<b>Ending Date:</b>&nbsp;&nbsp;$dateEnd</td></tr>
<tr><td> </td><td> </td></tr>
<tr><td><b>Start Time: </b></td><td>$startTime&nbsp;&nbsp;&nbsp;&nbsp;<b>End Time: </b>&nbsp;&nbsp;$endTime</td></tr></table>
<hr>
<table>
<tr><td><b>District: </b>$dist&nbsp;&nbsp;";
if (!isset($park)) {
	$park = "";
}
if ($park and $park != "DISW") {
	$parkL = strtolower($park);
	$link = "<a href='http://ncparks.gov/Visit/parks/$parkL/directions.php' target='_blank'>$park</a>";
	echo "
	<b>Park: </b>&nbsp;$link&nbsp;&nbsp;";
} else {
	if ($park == "DISW") {
		echo "Call the Dismal Swamp SP Park Office at 252-771-6582 for park information.";
	}
}

if (!isset($nondprCert)) {
	$nondprCert = "";
}
if (!isset($description)) {
	$description = "";
}
if (!isset($prereq)) {
	$prereq = "";
}
if (!isset($location)) {
	$location = "";
}
if (!isset($contact)) {
	$contact = "";
}
if (!isset($comment)) {
	$comment = "";
}
echo "</td></tr><tr><td><b>Location: </b>&nbsp;&nbsp;$location</td></tr></table>
<hr>
<table><tr><td> </td><td> </td></tr>
<tr><td><b>Prerequisite: </b></td><td>$prereq</td></tr>
<tr><td> </td><td> </td></tr>
<tr><td><b>Description: </b></td><td>$description</td></tr>
<tr><td> </td><td> </td></tr>
<tr><td><b>Certification: </b></td><td>$nondprCert</td></tr>
<tr><td> </td><td> </td></tr>
</table>
<hr>
<table><tr><td><b>Contact Info: </b></td><td>$contact</td></tr>
<tr><td> </td><td> </td></tr>
<tr><td><b>Comments: </b></td><td>$comment</td></tr>";
echo "</table></body></html>";
