<?php
extract($_REQUEST);

include("../../include/iConnect.inc");// database connection parameters
mysqli_select_db($connection,"efile")
       or die ("Couldn't select database");
       
unlink($file);

$sql="DELETE FROM  file_links where file_link='$file' and doc_id='$doc_id'";
//echo "$sql"; exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
header("Location: files.php?doc_id=$doc_id");
?>