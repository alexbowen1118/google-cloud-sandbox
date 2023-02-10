<?php
extract($_REQUEST);
//echo "<pre>"; print_r($_POST); echo "</pre>";  exit;

include("../../include/connectROOT.inc");// database connection parameters
$database="dpr_forum";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
       //**** Process any Edit or Add ******
 
//**** Process any Delete ******
if($submit=="Delete"){
$query = "DELETE FROM forum where forumID='$deleteRecordID'";
//echo "$query";exit;
$result = mysqli_query($connection, $query) or die ("Couldn't execute query Delete. $query");


$query = "DELETE FROM category where forumID='$deleteRecordID'";
//echo "$query";exit;
$result = mysqli_query($connection, $query) or die ("Couldn't execute query Delete. $query");

header("Location: forum.php"); exit;
}

?>
