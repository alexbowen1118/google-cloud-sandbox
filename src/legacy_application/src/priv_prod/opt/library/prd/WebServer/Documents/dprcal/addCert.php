<?php

ini_set('display_errors',1);
$database="dprcal";
include("../../include/auth.inc");
include("../../include/connectROOT.inc");
mysqli_select_db($connection, $database);
extract($_POST);


// ************ Add Name *************
if($submit == "Add" AND $certname!=""){
$query = "INSERT INTO cert SET certname='$certname'";
$result = mysqli_query($connection, $query) or die ("Couldn't execute query. $query");
$certid=mysqli_insert_id($connection);
header("Location: cert.php?certid=$certid&Submit=cert");
}
else
{header("Location: certVU.php");}
