<?php
extract($_REQUEST);
//echo "<pre>"; print_r($_POST); echo "</pre>";  exit;

include("../../include/connectROOT.inc");// database connection parameters
$database="dpr_forum";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
       //**** Process any Edit or Add ******

//**** Process any Add ******
if($submit=="Add"){// note capital A, see add with lower case
session_start();
$personID=$_SESSION['dpr_forum']['tempID'];
//echo "<pre>"; print_r($_POST); echo "</pre>";  //exit;

$sql="SHOW COLUMNS FROM category";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SELECT. $sql");
while($row=mysqli_fetch_array($result)){
if($row['Field']!="forumID"){
$fld=$row['Field']; $value=${$fld};
$fldString.=$fld."='".$value."',";}
}
$fldString=trim($fldString,",");
//echo "$fldString $sql";exit;

$skip=array("dateCreate","personID","timeMod");
$sql="SHOW COLUMNS FROM forum";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SELECT. $sql");
while($row=mysqli_fetch_array($result)){
if(!in_array($row['Field'],$skip)){

$fld=$row['Field']; $value=${$fld};
$updateFields.=$fld."='".$value."',";}
}

//$updateFields=trim($updateFields,",");
//echo "$updateFields <br /><br />";    //exit;


//print_r($queryString);exit;
$query = "INSERT INTO $dbTable set $updateFields personID='$personID', dateCreate=NOW(),timeMod=NOW()";
//echo "$query <br /><br />";   //exit;
$result = mysqli_query($connection, $query) or die ("Couldn't execute query Insert. $query");
$v=mysqli_insert_id($connection);

$query = "UPDATE forum set submisID='$v' WHERE forumID='$v'";
$result = mysqli_query($connection, $query) or die ("Couldn't execute query Update. $query");

$query = "INSERT into category set $fldString,forumID='$v'";
//echo "$query";exit;
$result = mysqli_query($connection, $query) or die ("Couldn't execute query Update. $query");
header("Location: forum.php?forumID=$v&submit=Go");
exit;
}
?>
