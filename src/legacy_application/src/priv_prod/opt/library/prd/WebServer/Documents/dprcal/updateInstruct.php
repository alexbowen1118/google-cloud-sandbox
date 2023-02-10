<?php
ini_set('display_errors', 1);
$database = "dprcal";
include("../../include/auth.inc");
include("../../include/connectROOT.inc");
mysqli_select_db($connection, $database);;
extract($_POST);

include("nav.php");
$title = urldecode($title);
echo "<html>
<head>
<title>NC DPR Calendar - Edit Course</title>
</head>
<body>";
if ($Submit == "Submit") {
	@$query = "UPDATE instructor SET title='$title',adm='$adm', cert='$cert',skills='$skills',main='$main',safe='$safe',law='$law',med='$med',res='$res',tra='$tra', Fname='$Fname', Lname='$Lname', add1='$add1', add2='$add2',city='$tra', state='$Fname', zip='$Lname', phone='$add1', extension='$add2',fax='$tra', email='$Fname', website='$Lname', subject='$add1' WHERE inID = '$inID'";

	$result = mysqli_query($connection, $query) or die("Couldn't execute query. $query");

	echo " Instructor successfully edited.<hr><table><tr><td>Activity:</td></tr>";

	if (@$adm != '') {
		echo "<tr><td><font color='green'>Administration</font></td></tr>";
	}
	if (@$cert != '') {
		echo "<tr><td><font color='green'>EE Certification</font></td></tr>";
	}
	if (@$skills != '') {
		echo "<tr><td><font color='green'>AIT</font></td></tr>";
	}
	if (@$main != '') {
		echo "<tr><td><font color='green'>Maintenance</font></td></tr>";
	}
	if (@$safe != '') {
		echo "<tr><td><font color='green'>Safety</font></td></tr>";
	}
	if (@$law != '') {
		echo "<tr><td><font color='green'>Law Enforcement</font></td></tr>";
	}
	if (@$med != '') {
		echo "<tr><td><font color='green'>Medical</font></td></tr>";
	}
	if (@$res != '') {
		echo "<tr><td><font color='green'>Resource Management</font></td></tr>";
	}
	if (@$tra != '') {
		echo "<tr><td><font color='green'>Trails</font></td></tr>";
	}
	echo "</table>
	<hr>";

	echo "$title $Fname $Lname<br>";
	echo "$add1<br>$add2<br>$city, $state $zip<br>";
	echo "Phone: $phone $extension Fax: $fax<br><br>";
	echo "$email<br>$website<br>Subject(s): $subject";
}
if ($Submit == "Delete") {
	$query = "DELETE from instructor WHERE inID = '$inID'";

	$result = mysqli_query($connection, $query) or die("Couldn't execute query. $query");

	include("findInstruct.php");;
}

?>
</body>

</html>