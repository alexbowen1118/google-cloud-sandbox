<?php

$database = "dprcal";
include("../../include/auth.inc");
$level = $_SESSION[$database]['level'];
if ($level > 3) {
	ini_set('display_errors', 1);
}
include("../../include/iConnect.inc");
mysqli_select_db($connection, $database);

include("nav.php");
//echo "<pre>";print_r($_SESSION);echo "</pre>";
if (!is_numeric($tid)) {
	exit;
}

$sql = "SELECT * From signup WHERE signup.tid ='$tid' AND signup.del !='x'";

//$sql = "SELECT * From signup WHERE signup.clid ='$tid' AND signup.del !='x'";
$total_result = @mysqli_query($connection, $sql) or die("$sql Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
$total_found = mysqli_num_rows($total_result);
//echo "$sql";exit;

$sql = "SELECT * From train
WHERE train.tid ='$tid' AND train.del !='x'";
// echo "24 $sql";
$total_result1 = @mysqli_query($connection, $sql) or die("$sql Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
$total_found1 = mysqli_num_rows($total_result1);
echo "<html><head><title></title></head><body>";
echo "<h3>Class Information</h3>";
while ($row1 = mysqli_fetch_array($total_result1)) {
	extract($row1);

	$location = nl2br($location);
	$contact = nl2br($contact);
	if ($public != "") {
		$p = "<font color='purple'>This class is also open to the Public.</font>";
	} else {
		$p = "<font color='green'>This class is only offered to DPR staff.</font>";
	}
	echo "$p<hr>
	<table>";
}

$sql = "SELECT * From course WHERE `clid` = '$clid'";
$result = @mysqli_query($connection, $sql) or die("Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
//echo "$sql"; exit;
$concat = "";
while ($row = mysqli_fetch_array($result)) {
	extract($row);
	if ($adm != "") {
		$concat = "Administration<br>";
		$cat = "adm";
	}
	if ($cert != "") {
		$concat = $concat . " EE Certification<br>";
		$cat = "cert";
	}
	if ($skills != "") {
		$concat = $concat . " Advanced Interpretive Training<br>";
		$cat = "skills";
	}
	if ($main != "") {
		$concat = $concat . " Maintenance<br>";
		$cat = "main";
	}
	if ($safe != "") {
		$concat = $concat . " Safety<br>";
		$cat = "safe";
	}
	if ($law != "") {
		$concat = $concat . " Law Enforcement<br>";
		$cat = "law";
	}
	if ($med != "") {
		$concat = $concat . " Medical<br>";
		$cat = "med";
	}
	if ($res != "") {
		$concat = $concat . " Resource Management<br>";
		$cat = "res";
	}
	if ($tra != "") {
		$concat = $concat . " Trails";
		$cat = "tra";
	}
}

$Bdate = strftime('%a, %b %d, %Y', strtotime($dateBegin));
$Edate = strftime('%a, %b %d, %Y', strtotime($dateEnd));
$today = date("Y-m-d");

echo "<tr><td valign='top'><b>Title:</b></td><td><h3>$title</h3></td></tr>
<tr><td> </td><td> </td></tr>
<tr><td><b>Activity: </b></td><td>$concat</td></tr>
<tr><td> </td><td> </td></tr></table>
<hr>
<table><tr><td><b>Beginning Date:</b>&nbsp;&nbsp;</td>
<td> <font color='purple'>$Bdate</font> &nbsp;&nbsp;<b>Ending Date:</b>&nbsp;&nbsp;<font color='purple'>$Edate</font></td></tr>
<tr><td> </td><td> </td></tr>
<tr><td><b>Start Time: </b></td><td>$startTime&nbsp;&nbsp;&nbsp;&nbsp;<b>End Time: </b>&nbsp;&nbsp;$endTime</td></tr></table>
<hr>
<table>
<tr><td><b>District/Region: </b>$dist&nbsp;&nbsp;";
if ($park) {
	$parkL = strtolower(str_replace(" ", "-", $location));


	$link = "<a href='http://ncparks.gov/$parkL' target='_blank'>$park</a>";

	echo "
<b>Park: </b>&nbsp;$link&nbsp;&nbsp;";
}
if ($maxClass == 0) {
	$maxPrint = "None";
} else {
	$maxPrint = $maxClass;
}
if ($total_found < 1) {
	$classList = "Num. enrolled to date: ";
} else {
	$classList = "<a href='findEnrollee.php?tid=$tid&Submit=Search'>Class List</a> of: ";
}

if (empty($prereq)) {
	$prereq = "";
}
echo "</td></tr><tr><td><b>Location: </b>&nbsp;&nbsp;$location</td></tr></table>
<hr>
<table><tr><td> </td><td> </td></tr>
<tr><td><b>Prerequisite: </b></td><td>$prereq</td></tr>
<tr><td> </td><td> </td></tr></table>
<table border='1' cellpadding='5'><tr><td><b>Class Size&nbsp;&nbsp;</b></td><td>Max:  $maxPrint&nbsp;&nbsp;&nbsp;&nbsp;</td><td>Min: $minClass</td><td>&nbsp;&nbsp;&nbsp;&nbsp;$classList $total_found</td>";

if ($today > $dateEnd) {
	$class_over = 1;
	$where = "WHERE (eval.tid = $tid AND q4>0) GROUP by eval.tid";
	//$where= "WHERE (eval.tid = $tidLink) GROUP by eval.tid";
	$sql = "SELECT count(evid) as respNum
	From eval
	LEFT JOIN train on eval.tid=train.tid
	$where ";
	$result = mysqli_query($connection, $sql) or die("Couldn't execute query. $sql");
	if (mysqli_num_rows($result) > 0) {
		echo "<td align='center'> View <a href='/dprcal/view.php?tidLink=$tid&cat=$cat&Submit=Show+Evaluation'>Evaluation</a></td>";
	}
}

if (empty($description)) {
	$description = "";
} else {
	$description = nl2br($description);
}
if (empty($comment)) {
	$comment = "";
} else {
	$comment = nl2br($comment);
}
if (empty($courseCert)) {
	$courseCert = "";
}
if (empty($nondprCert)) {
	$nondprCert = "";
}
echo "</tr></table>
<table><tr><td> </td><td> </td></tr>

<tr><td><b>Description: </b></td><td>$description</td></tr>
<tr><td> </td><td> </td></tr></table>
<table><tr><td> </td><td> </td></tr>

<tr><td><b>DPR Certification: </b></td><td>$courseCert</td></tr>
<tr><td><b>Office of EE Cert.: </b></td><td>$nondprCert</td></tr>
<tr><td> </td><td> </td></tr></table>
<hr>
<table><tr><td><b>Contact Info: </b></td><td>$contact</td></tr>
<tr><td> </td><td> </td></tr>
<tr><td><b>Comments: </b></td><td>$comment</td></tr>";
//echo "<tr><td>Test: m=$maxClass t=$total_found o=$online c=$class_over</td></tr>";
echo "</table>";
if ($maxClass > $total_found or $maxClass == 0) {
	echo "
	<form method='post' action='signup.php'>";
	// 	if(@$online AND @$class_over!=1 and $tid!=4030)
	// 		{
	echo "<input type='hidden' name='trainID' value='$trainID'>
		<input type='hidden' name='park' value='$park'>
		<input type='hidden' name='tid' value='$tid'>
		<input type='submit' name='Submit' value='Signup'>
		</form>";
	// 		}
	// 		else
	// 		{
	// 		echo "<font color='red'>This class is closed.</font><br /><br />";
	// 		}
	echo "If you need to remove your name from this class, call or email the contact person listed above. Be sure to specify Class Title, Date and Location.";
} else {
	echo "<font color='green'>
The maximun class size has been reached. Contact the class administrator if you have questions.</font>";
}
//Get correct clidLink number 
//echo "<br /><br />Link to Evaluation Form: <a href='eval.php?clidLink=$clid&cat=adm'>link</a>";
?>
</body>

</html>