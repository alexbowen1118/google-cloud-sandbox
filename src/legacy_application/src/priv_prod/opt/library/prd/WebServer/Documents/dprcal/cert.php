<?php
ini_set('display_errors', 1);
$database = "dprcal";
include("../../include/auth.inc");
include("../../include/connectROOT.inc");
mysqli_select_db($connection, $database);;
extract($_REQUEST);

include("nav.php");

//echo "<pre>";print_r($_REQUEST);echo "</pre>";   //exit;

// **************** Form to Find Names *************
if ($Submit == "cert") {
	$sql = "SELECT * From cert WHERE certid=$certid";
	// echo "$sql"; exit;
	$result = @mysqli_query($connection, $sql) or die("Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
	$row = mysqli_fetch_array($result);
	extract($row);
	$letter = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

	echo "
	<form method='post' action='cert.php'><table>
	<tr><td>Certify Completion for <font color='blue'>$certname</font></td></tr>
	<tr><td>1. Select Beginning Letter of Last Name --></td></tr></table><table>";

	for ($i = 0; $i < count($letter); $i++) {
		if (@$cap == $letter[$i]) {
			$capC = "checked";
		} else {
			$capC = "";
		}
		echo "
		<td><input type='radio' name='cap' value='$letter[$i]' $capC>$letter[$i]</td>";
	}
	if (@$show == "all") {
		$showCY = "checked";
	} else {
		$showCN = "checked";
	}

	echo "</tr></table>
	<table><td>2. Click Refresh--> 
	<input type='hidden' name='certname' value='$certname'>
	<input type='hidden' name='certid' value='$certid'>
	<input type='submit' name='Submit' value='Refresh'></td></tr>
	</table></form><hr><table>";


	//include("../../include/connectTrainCal.inc");
	$sql2 = "SELECT emid,comment,updated From certstatus where certid=$certid";

	$result2 = @mysqli_query($connection, $sql2) or die("Error 2 $sql #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
	$total_found2 = @mysqli_num_rows($result2);
	if ($total_found2 < 1) {
		echo "No one certified for this class.";
		exit;
	}

	while ($row2 = mysqli_fetch_array($result2)) {
		extract($row2);
		$commentArray[$emid] = $comment;
		$updateArray[$emid] = $updated;
	}
	//echo "<pre>";print_r($commentArray);echo "</pre>";exit;


	mysqli_select_db($connection, 'divper');
	$sql = "SELECT divper.empinfo.emid as varEmid,divper.empinfo.Nname,divper.empinfo.Fname,divper.empinfo.Lname,divper.emplist.currPark
	From divper.empinfo
	LEFT JOIN divper.emplist on divper.empinfo.emid=divper.emplist.emid
	order by divper.empinfo.Lname,divper.empinfo.Fname";

	$total_result = @mysqli_query($connection, $sql) or die("Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
	//echo "$sql";print_r($row);echo "</pre>";exit;


	echo "<html><head><title></title>
	<script language=\"JavaScript\">
	<!--
	function confirmLink()
	{
	 bConfirm=confirm('Are you sure you want to delete this record?')
	 return (bConfirm);
	}
	//-->
	</script>
	</head>
	<body><table>
	<tr><td>Certification List for: </td><td colspan='4'><font color='blue'>$certname</font></td></tr>
	<tr><td colspan='2' align='right'>Name</td><td>Certified</td><td>Updated</td></tr>";
	$i = 1;
	while ($row = mysqli_fetch_array($total_result)) {
		extract($row);
		@$comment = $commentArray[$varEmid];
		@$updated = $updateArray[$varEmid];

		if ($comment != "") {
			if ($currPark == "") {
				$currPark = "*";
			}
			$linkEdit = "<a href='cert.php?emid=$varEmid&certid=$certid&Submit=Edit'>Edit</a>";
			$link = "<a href='cert.php?emid=$varEmid&certid=$certid&Submit=del' onClick='return confirmLink()'>Delete</a>";
			$line = "<tr><td align='right'> $i</td><td>- $Fname $Lname [$currPark]</td><td>$comment</td><td>$updated</td><td> --> $linkEdit</td><td>$link</td></tr>";

			echo "$line";
			$i++;
		} // end if
	} // end while
	echo "<tr><td></td><td>* = No longer with DPR.</td></tr>";
	echo "</table>";
	exit;
} // end Submit

// ************ List Names *************
if ($Submit == "Refresh") {
	echo "<html><head><title></title></head><body><table>
	<tr><td>
	<a href='cert.php?certid=$certid&Submit=cert'>List</a></td>
	<td><a href='certVU.php'>Menu</a></td></tr>
	<tr><td>
	<form method='post' action='cert.php'><table><tr>Select a person to certify for <font color='blue'>$certname</font></td></tr>";
	$tempArrayEmid = array();

	if ($cap) {
		$where = "WHERE divper.empinfo.Lname LIKE '$cap%'";
	}

	mysqli_select_db($connection, 'divper');
	$sql = "SELECT divper.empinfo.emid,divper.empinfo.Fname, divper.empinfo.Lname, divper.empinfo.tempID,divper.emplist.currPark
	From divper.empinfo
	LEFT JOIN emplist on emplist.emid=empinfo.emid
	$where
	ORDER by divper.empinfo.Lname,divper.empinfo.Fname";
	//echo "$sql";exit;
	$result = mysqli_query($connection, $sql) or die("Couldn't execute query. $sql $host");
	while ($row = mysqli_fetch_array($result)) {
		extract($row);
		$arrayEmid[] = $emid;
		$arrayFname[$emid] = addslashes($Fname);
		$arrayLname[$emid] = addslashes($Lname);
		$arraytempID[$emid] = $tempID;
		$arraycurrPark[$emid] = $currPark;
	}

	mysqli_select_db($connection, $database);;

	$query = "TRUNCATE TABLE tempcert";
	$result = mysqli_query($connection, $query) or die("Couldn't execute query. $query $host");
	for ($i = 0; $i < count($arrayEmid); $i++) {
		$emid = $arrayEmid[$i];
		$query = "INSERT tempcert set emid='$arrayEmid[$i]', Fname='$arrayFname[$emid]', Lname='$arrayLname[$emid]', currPark='$arraycurrPark[$emid]', tempID='$arraytempID[$emid]'";
		$result = mysqli_query($connection, $query) or die("Couldn't execute query. $sql");
	}

	//print_r($arrayEmid);
	//exit;
	$where = "WHERE tempcert.Lname LIKE '$cap%'";

	$sql = "SELECT tempcert.EMID,certstatus.emid,tempcert.Fname, tempcert.Lname, tempID,tempcert.currPark,certstatus.comment,certid as completed, certstatus.ee_continue_ed_date
	From tempcert
	LEFT JOIN dprcal.certstatus on (dprcal.certstatus.emid=tempcert.emid and dprcal.certstatus.certid=$certid)
	$where
	ORDER by tempcert.Lname,tempcert.Fname";

	//echo "$sql";//exit;
	$result = mysqli_query($mysqli, $sql) or die("Couldn't execute query. $sql");
	$maxNum = mysqli_num_rows($result);
	while ($row = mysqli_fetch_array($result)) {
		extract($row);
		$currPark = strtoupper($currPark);
		$FnameSL = urlencode($Fname);
		$LnameSL = urlencode($Lname);
		$tempEmid = $EMID . "~" . $FnameSL . "~" . $LnameSL . "~" . $currPark;

		//if($completed==$certid || $completed==NULL){
		@$pNum = $pNum + 1;
		if ($completed == $certid) {
			$v = " checked";
		} else {
			$v = "";
			$comment = "";
		}
		if ($certid == 20) // NC EE Certification Continuing Education
		{
			$ee_field = "<td>Enter date for EE Continue Ed. <input type='text' name='tempEE_CE[$EMID]' value='$ee_continue_ed_date' size='10'><font color='red'> format yyyy-mm-dd, e.g., 2012-01-09</font></td>";
		} else {
			$ee_field = "";
		}
		echo "<tr><td><input type='checkbox' name='tempEmid[$EMID]' value='$tempEmid'$v> $Lname, $Fname - $currPark</td><td><input type='text' name='tempComment[$EMID]' value='$comment' size='40'></td>
	$ee_field
	</tr>";
	}
	//}
	echo "</tr></table>
	<table>
	<input type='hidden' name='certid' value='$certid'>
	<input type='submit' name='Submit' value='Add'></td></tr>
	</table><hr><table>
	</form></body></html>";
	exit;
}

// ************ Add Name *************
if ($Submit == "Add") {
	//echo "<pre>";print_r($tempEmid);print_r($tempComment);
	//echo "</pre>";exit;

	while (list($key, $val) = each($tempEmid)) {
		list($emid, $Fname, $Lname, $currPark) = explode("~", $val);
		$comment = $tempComment[$emid];
		include("../../include/connectTrainCal.inc");
		$Fname = addslashes(urldecode($Fname));
		$Lname = addslashes(urldecode($Lname));
		$query = "REPLACE certstatus SET certid='$certid', emid='$emid',Fname='$Fname',Lname='$Lname',currPark='$currPark',comment='$comment'";
		//echo "$query";exit;
		$result = mysqli_query($connection, $query) or die("Couldn't execute query. $query<br><br>Check to make sure that $Fname $Lname hasn't already been entered for this Certification.");
	}
	header("Location: cert.php?certid=$certid&Submit=cert");
}

// ************ Delete Name *************
if ($Submit == "del") {
	include("../../include/connectTrainCal.inc");
	$query = "DELETE FROM certstatus where csid='$csid'";
	$result = mysqli_query($connection, $query) or die("Couldn't execute query. $query");
	header("Location: cert.php?certid=$certid&Submit=cert");
}


// ************ Edit Person *************
if ($Submit == "Edit") {
	include("../../include/connectTrainCal.inc");
	$sql = "SELECT * From certstatus where emid='$emid' and certid='$certid'";
	$result = mysqli_query($mysqli, $sql) or die("Couldn't execute query. $sql");
	//echo "$sql"; exit;
	$row = mysqli_fetch_array($result);
	extract($row);
	echo "<form name='cert.php'><table>
<tr><td>Name</td><td>Certified</td><td>Updated</td></tr>
<tr>
<td>$Fname $Lname</td>
<td><input type='text' name='comment' value='$comment'></td>
<td><input type='text' name='updated' value='$updated'></td>
<td><input type='hidden' name='csid' value='$csid'>
<input type='hidden' name='certid' value='$certid'>
<input type='submit' name='submit' value='Update'></td></tr></table></form>";
}


// ************ Update Person *************
if ($submit == "Update") {
	include("../../include/connectTrainCal.inc");
	$sql = "UPDATE certstatus SET comment='$comment', updated='$updated'  where csid='$csid'";
	//echo "$sql"; exit;
	$result = mysqli_query($mysqli, $sql) or die("Couldn't execute query. $sql");

	header("Location: cert.php?certid=$certid&Submit=cert");
}
