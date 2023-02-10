<?php
ini_set('display_errors',1);
$database="dprcal";
include("../../include/auth.inc");

include("../../include/iConnect.inc");
mysqli_select_db($connection,$database);
include("nav.php");
extract($_REQUEST);

// ********* non-DPR form ************
if(!isset($clid)){$clid="";}
echo "<table>
<form method='post' action='addPersonNonDPR.php'>
<tr><td>For non-DPR participants use this section:</td></tr>
<tr><td>First Name: <input type='text' name='fName' value=''></td></tr>
<tr><td>Last Name: <input type='text' name='lName' value=''></td></tr>
<tr><td>Phone or Email: <input type='text' name='contact' value=''> Needed if class is cancelled, rescheduled, etc.</td></tr>
<tr><td>
<input type='hidden' name='f' value='1'>
<input type='hidden' name='type' value='nonDPR'>
<input type='hidden' name='tid' value='$tid'>
<input type='hidden' name='clid' value='$clid'>
<input type='hidden' name='dateBegin' value='$dateBegin'>
<input type='submit' name='Submit' value='Enroll'>
</form></td><tr>
</table>";
// ************ Add Name *************
if($Submit == "Enroll")
	{
	// ************** check for duplicate entry first
	if($type=="nonDPR"){$personID=$lName."-".$fName;}
	$find = "tid = '$tid' and personID='$personID'";
	$sql = "SELECT * From dprcal.signup WHERE $find";
	//echo "$sql"; exit;
	$total_result = @mysqli_query($connection,$sql) or die("$sql Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
	$total_found = @mysqli_num_rows($total_result);
	
	if($total_found > 0){
	echo "$personID is already signed up for this class.";
	exit;}
	
	$query = "INSERT INTO signup (dateClass, personID, tid, clid, contact,park) VALUES ('$dateBegin','$personID','$tid', '$clid','$contact','$type')";
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query. $query");
	header("Location: findTrain.php?tid=$tid&Submit=Search");
	}
?>