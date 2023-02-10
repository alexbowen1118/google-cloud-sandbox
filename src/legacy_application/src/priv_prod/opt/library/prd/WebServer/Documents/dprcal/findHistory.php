<?php

$database = "dprcal";
include("../../include/auth.inc");
$level = $_SESSION[$database]['level'];
if ($level > 3) {
	ini_set('display_errors', 1);
}
include("nav.php");
include("../../include/connectROOT.inc");
extract($_REQUEST);
// echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
//echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
mysqli_select_db($connection, 'divper');
// Get employees for park
$park = $_SESSION['dprcal']['parkS'];
$sql = "SELECT t1.Fname,t1.Lname, t1.tempID, t3.working_title
		From empinfo as t1
		LEFT JOIN emplist as t2 on t1.emid=t2.emid 
		LEFT JOIN position as t3 on t3.beacon_num=t2.beacon_num 
		where t3.park='$park'
		order by t1.Lname";
$result = @mysqli_query($connection, $sql) or die("Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
while ($row = mysqli_fetch_assoc($result)) {
	$park_emp[$row['tempID']] = $row['Lname'] . ", " . $row['Fname'] . " - " . $row['working_title'];
}

if (strpos($_SESSION['dprcal']['posTitle'], "Supervisor") > 0) {
	echo "<table>";
	foreach ($park_emp as $k => $value) {
		echo "<tr><td><a href='findHistory.php?personID=$k&Submit=Find'>$k</a></td><td>$value</td></tr>";
	}
	echo "</table>";
}

if (@$Submit == "Find") {
	if ($personID != "") {
		$var2 = "(signup.personID like '$personID%')";
	} else {
		echo "The combination of your Last Name and the last four numbers of your Social Security Number is required.";
		include("nav.php");
		exit;
	}

	//echo "l=$level";
	if ($level < 5) {
		if ($personID != $_SESSION['dprcal']['loginS'] and strpos($_SESSION['dprcal']['posTitle'], "Supervisor") < 1) {
			exit;
		}
		if ($level < 2 and !array_key_exists($personID, $park_emp)) {
			exit;
		}
	}

	$sql = "SELECT * From emplist where tempID='$personID'";
	$total_result = @mysqli_query($connection, $sql) or die("Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
	$total_found = @mysqli_num_rows($total_result);
	if ($total_found < 1) {
		echo "No person with the following Login was found: $personID";
		include("nav.php");
		exit;
	}
	$row = mysqli_fetch_array($total_result);
	extract($row);

	$sql = "SELECT Nname,Fname,Lname From empinfo where tempID='$personID'";
	$total_result = @mysqli_query($connection, $sql) or die("Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
	//echo "$sql<pre>";print_r($row);echo "</pre>";exit;

	$row = mysqli_fetch_array($total_result);
	extract($row);
	// ( a = b ) ? [do if true] : [do if false] ;
	($Nname != "") ? $firstName = $Nname : $firstName = $Fname;
	$lastName = $Lname;

	mysqli_select_db($connection, $database);
	$var2 = "signup.emid = $emid";

	$sql = "SELECT supid,train.park as parkT, train.tid,dateBegin,dateEnd,dateFind as DF, signup.completed,course.title,adm,cert,skills,main,safe,law,med,res, train.enter_by
	From signup
	left join train on signup.tid=train.tid
	left join course on course.clid=train.clid
	WHERE
	$var2
	order by dateBegin,signup.tid";

	// echo "$sql"; //exit;
	$total_result = @mysqli_query($connection, $sql) or die("Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
	$total_found = @mysqli_num_rows($total_result);
	if ($total_found < 1) {
		echo "No training entered for the following person: $personID";
		include("nav.php");
		exit;
	}
	echo "<html><head><title></title></head>";
	echo "<body>Training History: <table>";
	$i = 0;
	$cat = "";
	while ($row = mysqli_fetch_array($total_result)) {
		$i = $i + 1;
		extract($row);
		if (@$adm) {
			$cat = " [Administration]";
		}
		if (@$cert) {
			$cat .= " [EE Certification]";
		}
		if (@$skills) {
			$cat .= " [I&E Skills]";
		}
		if (@$main) {
			$cat .= " [Maintenance]";
		}
		if (@$safe) {
			$cat .= " [Safety]";
		}
		if (@$law) {
			$cat .= " [Law Enforcement]";
		}
		if (@$med) {
			$cat .= " [Medical]";
		}
		if (@$res) {
			$cat .= " [Resource Management]";
		}
		if (@$tra) {
			$cat .= " [Trails]";
		}

		$cat = "<br>" . $cat;

		if ($dateBegin != $dateEnd) {
			$dateBegin = "$dateBegin to $dateEnd <b>[$DF]</b>";
		}
		$com = "";
		if ($completed == "y") {
			$com = "<font color='orange'>[Class successfully completed.]</font>";
		} else {
			$com = "<font color='green'>[Enrolled]</font>";
		}

		if (@$classCheck != $tid) {
			$classCheck = "<br>$i $title @ $parkT (contact: $enter_by)<br>";
		} else {
			$classCheck = "";
		}
		$line = " <b>" . $classCheck . "</b> " . $firstName . " " . $lastName . " => &nbsp;" . $dateBegin . " " . $com . $cat;
		echo "<tr><td>$line</td></tr>";
		$classCheck = $tid;
		$cat = "";
	}
	echo "</table><hr>";
	// Search completed certifications
	$sql = "SELECT certname, Fname, Lname, currPark as Park, comment
	From certstatus as t1
	left join cert as t2 on t1.certid=t2.certid
	where t1.emid='$emid'
	";

	// echo "$sql"; //exit;
	$result = @mysqli_query($connection, $sql) or die("Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
	while ($row = mysqli_fetch_assoc($result)) {
		$ARRAY[] = $row;
	}
	if (empty($ARRAY)) {
		echo "No certifications found.";
		exit;
	}
	$c = count($ARRAY);
	echo "<table cellpadding='5'><tr><td>$c Certifications</td></tr>";
	foreach ($ARRAY as $index => $array) {
		if ($index == 0) {
			echo "<tr>";
			foreach ($ARRAY[0] as $fld => $value) {
				echo "<th>$fld</th>";
			}
			echo "</tr>";
		}
		echo "<tr>";
		foreach ($array as $fld => $value) {
			echo "<td>$value</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
	echo "</body></html>";
	include("nav.php");
	exit;

	$sql = "SELECT * From signup";

	$total_result = @mysqli_query($connection, $sql) or die("Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
	$total_found = @mysqli_num_rows($total_result);
	if ($total_found < 1) {
		echo "No one has enrolled in any class.";
		include("nav.php");
		exit;
	}
}
// ********** Find Form
//echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
@$logname = $_SESSION['dprcal']['loginS'];
$level = $_SESSION['dprcal']['level'];
if ($level < 2) {
	$RO = "readonly";
} else {
	$RO = "";
}
echo "<html><head><title>Find Training History</title></head>
<body>
<table width='100%' cellpadding='7'>
<form method='post' action='findHistory.php'>
<tr><td><font size='5' font color='#004201'>View Your Training History</font></td></tr>
<tr><td>Enter Last Name and the last four numbers of your Social Security Number (no spaces):<br>
<input type='text' name='personID' size='40' maxlength='40' value='$logname' $RO>
      </td></tr>
      <tr>
<td>A list of all completed classes will be returned.</td></tr></table>
<table width='100%' cellpadding='7'><tr><td><input type='submit' name='Submit' value='Find'></td>
   </tr></table>
</form>
</body>
</html>";
