<?php
ini_set('display_errors', 1);
$database = "dprcal";
include("../../include/auth.inc");

include("../../include/connectROOT.inc");
mysqli_select_db($connection, $database);;

updateTrain($_POST);  // function to update "completed" field
extract($_POST);
//echo "<pre>"; print_r($_POST); echo "</pre>";
function updateTrain($updateStuff)
{
	//	reset($printStuff);
	while ($array_cell = each($updateStuff)) {
		$currValue = $array_cell['value'];
		$currKey = $array_cell['key'];
		if ($currKey != "submit") {
			$query = "UPDATE signup SET `completed`='$currValue' WHERE supid = '$currKey'";
			$result = mysqli_query($connection, $query) or die("Couldn't execute query. $query");
		} // end if $currKey != submit
	} // end while
} // end function

// exit;

header("Location: findTrain.php?s=1&tid=$tid&Submit=Search");
exit;
?>
</body>

</html>