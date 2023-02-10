<?php
ini_set('display_errors',1);
$database="dprcal";
include("../../include/auth.inc");
include("../../include/iConnect.inc");
mysqli_select_db($connection,$database);
// extract($_POST);

include("nav.php");
echo "<html>
<head>
<title>NC DPR Calendar - Create New Course</title>
</head>
<body>";
@$test=$adm.$cert.$skills.$main.$safe.$law.$med.$res.$tra.$fire;
if($test == "")
	{
	echo "Click your BACK button and designate at least one Activity category"; exit;}
if($title == ""){echo "Click your BACK button, NOT the Admin Page, and definitely enter a Title."; exit;}
$title=addslashes($title);
$description=addslashes($description);
$courseCert=addslashes($courseCert);
$nondprCert=addslashes($nondprCert);
$keyword=addslashes($keyword);
@$query = "INSERT INTO course (adm,cert,skills,main,safe,law,med,res,tra,fire, enter_by, title, description, prereq,keyword,courseCert,nondprCert) VALUES ('$adm','$cert','$skills','$main','$safe','$law','$med','$res','$tra','$fire','$enter_by', '$title', '$description', '$prereq','$keyword','$courseCert','$nondprCert')";
//echo "$query";exit;
$result = mysqli_query($connection,$query) or die ("Couldn't execute query. $query");
 $lastIDcourse = mysqli_insert_id($connection);
 
echo "<font color='green'>New Course successfully entered.</font><br><br>";
$titleS = stripslashes($title);
$descriptionS = stripslashes($description);
echo "Title: $titleS<br><br>";
echo "Prerequisite: $prereq<br><br>";
echo "Description: $descriptionS<br><br>";
echo "DPR Certification: $courseCert<br><br>";
echo "Office of EE Certification: $nondprCert<br><br>";
echo "Keywords: $keyword<br><br>";

echo "
<table>";
if (@$adm != ''){$checkedA="checked";}else{$checkedA="";}
echo"
<tr><td><input type='checkbox' name='adm' value='1' $checkedA>Administration</td>";
if (@$cert != ''){$checkedC="checked";}else{$checkedC="";}
echo "
<td><input type='checkbox' name='cert' value='1' $checkedC>EE Certification</td></tr>";
if (@$skills != ''){$checkedSK="checked";}else{$checkedSK="";}
echo "
<tr><td><input type='checkbox' name='skills' value='1' $checkedSK>AIT</td>";
if (@$main != ''){$checkedMA="checked";}else{$checkedMA="";}
echo "
<td><input type='checkbox' name='main' value='1' $checkedMA>Maintenance</td></tr>";
if (@$safe != ''){$checkedS="checked";}else{$checkedS="";}
echo "
<tr><td><input type='checkbox' name='safe' value='1' $checkedS>Safety</td>";
if (@$law != ''){$checkedL="checked";}else{$checkedL="";}
echo " 
<td><input type='checkbox' name='law' value='1' $checkedL>Law Enforcement</td></tr>";
if (@$med != ''){$checkedM="checked";}else{$checkedM="";}
echo "<tr><td><input type='checkbox' name='med' value='1' $checkedM>Medical</td>";
if (@$res != ''){$checkedR="checked";}else{$checkedR="";}
echo "<td><input type='checkbox' name='res' value='1' $checkedR>Resource Management</td></tr>";
if (@$tra != ''){$checkedT="checked";}else{$checkedT="";}
echo "<tr><td><input type='checkbox' name='tra' value='1' $checkedT>Trails</td>";
if (@$fire != ''){$checkedF="checked";}else{$checkedF="";}
echo "<td><input type='checkbox' name='fire' value='1' $checkedF>Fire Management</td></tr>
</table>";
echo "<br>
If any changes are necessary, click this link. <a href='course_edit.php?clid=$lastIDcourse'> Edit Course</a><br>If no changes are necessary, click the Admin Page link at top of page.";
?> 
</body>
</html>
