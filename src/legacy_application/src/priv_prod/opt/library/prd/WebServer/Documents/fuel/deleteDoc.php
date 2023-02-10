<?php
// ********* DELETE A Upload Record AND ASSOC. FILE(S) ***************************
 
include("../../include/connectROOT.inc");// database connection parameters
       
extract($_REQUEST); //print_r($_REQUEST);
if ($mid)
{
$database="dpr_forum";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

$sql="SELECT link from map where mid='$mid'";
$result=mysqli_query($connection, $sql);
$row=mysqli_fetch_array($result);
extract($row);unlink($link);

$sql="DELETE FROM map where mid='$mid'";
$result=mysqli_query($connection, $sql);

$link="/dpr_forum/".$link;
$sql="UPDATE forum set weblink=trim(BOTH ',' from replace(weblink,'$link','')) where forumID='$forumID'";
//ECHO "$sql";exit;
$result=mysqli_query($connection, $sql);

header("Location: forum.php?submit=edit&lastFld=forumID&var=$forumID");
exit;
}
?>