<?php
ini_set('display_errors',1);
$database="dprcal";
include("../../include/auth.inc");
include("../../include/iConnect.inc");
mysqli_select_db($connection,$database);
// extract($_POST);

// echo "<pre>"; print_r($_POST); echo "</pre>";  exit;

include("nav.php");
@$title=urldecode($title);
echo "<html>
<head>
<title>NC DPR Calendar - Edit Course</title>
</head>
<body>";
if ($Submit == "Submit")
	{
// 	$titleS=addslashes($title);
// 	$descriptionS=addslashes($description);
// 	$prereqS=addslashes($prereq);
// 	$courseCert=addslashes($courseCert);
// 	$nondprCert=addslashes($nondprCert);
	@$query = "UPDATE course SET title='$title',adm='$adm', cert='$cert',skills='$skills',main='$main',safe='$safe',law='$law',med='$med',res='$res',tra='$tra', enter_by='$enter_by', description='$description', prereq='$prereq', keyword='$keyword', courseCert='$courseCert', nondprCert='$nondprCert' WHERE clid = '$clid'";
	
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query. $query");
	
	echo "<font color='green'>Course successfully edited.</font><hr><table><tr><td>Activity:</td></tr>";
	
	if (@$adm != ''){
	echo "<tr><td>Administration</td></tr>";}
	if (@$cert != ''){
	echo "<tr><td>EE Certification</td></tr>";}
	if (@$skills != ''){
	echo "<tr><td>AIT</td></tr>";}
	if (@$main != ''){
	echo "<tr><td>Maintenance</td></tr>";}
	if (@$safe != ''){
	echo "<tr><td>Safety</td></tr>";}
	if (@$law != ''){
	echo "<tr><td>Law Enforcement</td></tr>";}
	if (@$med != ''){
	echo "<tr><td>Medical</td></tr>";}
	if (@$res != ''){
	echo "<tr><td>Resource Management</td></tr>";}
	if (@$tra != ''){
	echo "<tr><td>Trails</td></tr>";}
	echo "</table>
	<hr>";
	
	echo "Title: $title<br><br>";
	echo "Prerequisite: $prereq<br><br>";
	$description=str_replace("\\r\\n", "<br/>",$description);
// 	$description=str_replace("\\r", "\n",$description);
// 	$descripton=nl2br($description);
	echo "Description: $description<br><br>";
	echo "DPR Certification: $courseCert<br><br>";
	echo "Office of EE Certification: $nondprCert<br><br>";
	echo "Keywords: $keyword<br><br>";
	}
// if ($Submit == "Delete")
// 	{
// 	$query = "DELETE from course WHERE clid = '$clid'";
	
if ($Submit == "VOID")
	{
	$query = "UPDATE course set del='x' WHERE clid = '$clid'";
// 	echo "$query"; exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query. $query");
	
// 	echo " Course successfully Deleted.<hr>";
	echo " Course successfully Voided.<hr>";
	}

?> 
</body>
</html>
