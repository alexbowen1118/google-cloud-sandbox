<?php
ini_set('display_errors', 1);
$database = "dprcal";
include("../../include/auth.inc");
include("../../include/connectROOT.inc");
mysqli_select_db($connection, $database);;
extract($_POST);
include("nav.php");
echo "<html>
<head>
<title>NC DPR Calendar - Add New Instructor</title>
</head>
<body>";
@$test = $adm . $cert . $skills . $main . $safe . $law . $med . $res . $tra;
if ($test == "") {
    echo "Click your BACK button and designate at least one Activity category";
    exit;
}
if ($Lname == "") {
    echo "Click your BACK button, NOT the Admin Page, and definitely enter a Last Name.";
    exit;
}
//$title=addslashes($title);
@$query = "INSERT INTO instructor (adm,cert,skills,main,safe,law,med,res,tra, title,Fname, Lname, add1, add2,city,state,zip,phone,extension, fax,email, website, subject) VALUES ('$adm','$cert','$skills','$main','$safe','$law','$med','$res','$tra','$title','$Fname', '$Lname', '$add1', '$add2','$city', '$state', '$zip','$phone', '$extension', '$fax','$email', '$website', '$subject')";
//echo "$query";exit;
$result = mysqli_query($connection, $query) or die("Couldn't execute query. $query");
$inID = mysqli_insert_id($connection);

echo "New Instructor successfully entered.<br><br>";

echo "Title: $title First Name: $Fname Last Name: $Lname<br>";
echo "Address 1: $add1<br>";
echo "Address 2: $add2<br>";
echo "City, State Zip: $city, $state $zip<br><br>";
echo "Phone: $phone<br>";
echo "Extension: $extension<br><br>";
echo "Fax: $fax<br><br>";
echo "email: $email<br>";
echo "website: $website<br>";
echo "Subject(s): $subject<br><br>";

echo "
<table>";
if (@$adm != '') {
    $checkedA = "checked";
} else {
    $checkedA = "";
}
echo "
<tr><td><input type='checkbox' name='adm' value='1' $checkedA>Administration</td>";
if (@$cert != '') {
    $checkedC = "checked";
} else {
    $checkedC = "";
}
echo "
<td><input type='checkbox' name='cert' value='1' $checkedC>EE Certification</td></tr>";
if (@$skills != '') {
    $checkedSK = "checked";
} else {
    $checkedSK = "";
}
echo "
<tr><td><input type='checkbox' name='skills' value='1' $checkedSK>AIT</td>";
if (@$main != '') {
    $checkedMA = "checked";
} else {
    $checkedMA = "";
}
echo "
<td><input type='checkbox' name='main' value='1' $checkedMA>Maintenance</td></tr>";
if (@$safe != '') {
    $checkedS = "checked";
} else {
    $checkedS = "";
}
echo "
<tr><td><input type='checkbox' name='safe' value='1' $checkedS>Safety</td>";
if (@$law != '') {
    $checkedL = "checked";
} else {
    $checkedL = "";
}
echo " 
<td><input type='checkbox' name='law' value='1' $checkedL>Law Enforcement</td></tr>";
if (@$med != '') {
    $checkedM = "checked";
} else {
    $checkedM = "";
}
echo "<tr><td><input type='checkbox' name='med' value='1' $checkedM>Medical</td>";
if (@$res != '') {
    $checkedR = "checked";
} else {
    $checkedR = "";
}
echo "<td><input type='checkbox' name='res' value='1' $checkedR>Resource Management</td></tr>";
if (@$tra != '') {
    $checkedT = "checked";
} else {
    $checkedT = "";
}
echo "<tr><td><input type='checkbox' name='tra' value='1' $checkedT>Trails</td></tr>
</table>";
echo "<br>
If any changes are necessary, click this link. <a href='instruct_edit.php?inID=$inID'> Edit Instructor</a><br>If no changes are necessary, click the Admin Page link at top of page.";
?>
</body>

</html>