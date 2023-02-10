<?php
ini_set('display_errors', 1);
$database = "dprcal";
include("../../include/auth.inc");
include("../../include/connectROOT.inc");
mysqli_select_db($connection, $database);;
extract($_POST);

include("nav.php");

$sql = "SELECT * From cert order by certname";

//echo "$sql";
$total_result = @mysqli_query($connection, $sql) or die("Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
$total_found = @mysqli_num_rows($total_result);
if ($total_found < 1) {
	echo "<html><head><title></title></head>";
	echo "<body>The Certificate database does not contain any entry. You add one on here.
<form method='post' action='addCert.php'><table><tr>
<input type='text' name='certname' value='' size='50'></td></tr></table>
<input type='submit' name='submit' value='Add'>
</form>";
	echo "</body></html>";

	exit;
}

echo "<html><head><title></title></head>";
echo "<body>
<form method='post' action='addCert.php'><table><tr>If certification is not listed below, enter it here: 
<input type='text' name='certname' value='' size='50'></td></tr></table>
<input type='submit' name='submit' value='Add'>
</form><table>";
while ($row = mysqli_fetch_array($total_result)) {
	$activity = "";
	extract($row);

	$link = "<a href='cert.php?certid=$certid&Submit=cert'>Update</a> - $certname";
	echo "<tr><td>$link</td>
	</tr>";
}
echo "</table></body></html>";


// ************ Delete Name *************
if (@$Submit == "del") {
	$query = "DELETE FROM certstatus where certid='$certid'";
	$result = mysqli_query($connection, $query) or die("Couldn't execute query. $query");

	$query = "DELETE FROM cert where certid='$certid'";
	$result = mysqli_query($connection, $query) or die("Couldn't execute query. $query");
	header("Location: certVU.php");
}
