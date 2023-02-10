<?php
//ini_set('display_errors',1);
$database = "dprcal";
include("../../include/auth.inc");
include("../../include/connectROOT.inc");
mysqli_select_db($connection, $database);;
extract($_REQUEST);

if (@$rep == "" and empty($_REQUEST["Submit"])) {
	include("nav.php");
}

function slash(&$v, $key)
{ // needed to escape any quote(s) '"
	$v = addslashes($v);
}
// print_r($_REQUEST);//exit;

@$val = strpos($Submit, "Submit");
if ($val > -1) {  // works for both Submit and Submit Again
	// 	echo "<pre>"; print_r($_POST); echo "</pre>";  exit;
	$test = $facName . $q1 . $q2 . $q3 . $q4 . $q5 . $q6 . $q7 . $q8 . $q9 . $q10;
	if ($test == "" and !$evid) {
		echo "You must enter an Instructor's name and Rate both the Training and the Instructor.<br><br>Click your Browser's BACK button.";
		exit;
	}
	if (@!$evid) {
		$test = $_REQUEST;
		//	array_walk($test,'slash');
		extract($test);
		$sql = "INSERT INTO eval SET tid='$tid',clid='$clid',facName='$facName',q1='$q1',q2='$q2',q3='$q3',q4='$q4',q5='$q5',q6='$q6',q7='$q7',q8='$q8',q9='$q9',q10='$q10',qDriv='$qDriv',c1='$c1',c2='$c2',c3='$c3',c4='$c4',c5='$c5',c6='$c6',c7='$c7' ,c8='$c8',c9='$c9',aitCat='$aitCat'";
		//	echo "$sql";exit;
		$result = mysqli_query($connection, $sql) or die("Couldn't execute query. $sql");
		$evid = mysqli_insert_id($connection);
	} // end $evid=''

	//if($_SESSION['dprcal']['level']==1){
	if ($_SESSION['dprcal']['level'] > 0) {
		echo "Thank you for completing the questionnaire. It will help us improve the quality of our training classes.<br /><br />You may quit your web browser now.";
		exit;
	}

	$sql = "SELECT tid 
	From eval where tid=$tid";
	$result = mysqli_query($connection, $sql) or die("Couldn't execute query. $sql");
	$evalNum = mysqli_num_rows($result);

	$sql = "SELECT eval.*,train.*
	From eval
	LEFT JOIN train on eval.tid=train.tid
	where evid=$evid";
	// echo "$sql"; exit;
	$result = mysqli_query($connection, $sql) or die("Couldn't execute query. $sql");
	$row = mysqli_fetch_array($result);
	echo "<table>";
	extract($row);
	echo "<tr><td><font color='red' size='+1'>Thank You! This is evaluation number: $evalNum</font></td></tr><tr>
	<td>Title: <b>$title</b></td><td>$aitCat</td>
	<td>Begin: $dateBegin End: $dateEnd</td></tr>
	</table>
	<form method='post' action='eval.php'>
	  <table><tr><td>Instructor(s) Name(s):<input type='text' name='facName' value='$facName' size='50'></td></tr></table>
	<table>
	<tr><td><b>Please rate the training:</b> 
	5-Strongly agree, 4-Agree, 3-Undecided, 2-Disagree, 1-Strongly disagree
	</td></tr>
	<tr><td bgcolor='beige'>1. <input type='text' name='q1' value='$q1' size='3'> The content of this training was consistent with the description in the training database.</td></tr>
	
	<tr><td bgcolor='beige'>2. <input type='text' name='q2' value='$q2' size='3'> This training provided me with an adequate understanding of the training topic.</td></tr>
	
	<tr><td bgcolor='beige'>3. <input type='text' name='q3' value='$q3' size='3'> As a result of taking this training, my skills have improved or will improve.</td></tr>
	
	<tr><td bgcolor='beige'>4. <input type='text' name='q4' value='$q4' size='3'> The instructional materials and exercises (field or classroom) were useful/adequate.</td></tr>
	
	<tr><td bgcolor='beige'>5. <input type='text' name='q5' value='$q5' size='3'> I would recommend this training to others.</td></tr>
	
	<tr><td><b>Please rate the instructor(s):</b></td></tr>
	<tr><td bgcolor='beige'>1. <input type='text' name='q6' value='$q6' size='3'> The instructor was prepared and organized.</td></tr>
	
	<tr><td bgcolor='beige'>2. <input type='text' name='q7' value='$q7' size='3'> The instructor was knowledgeable about the subject matter.</td></tr>
	
	<tr><td bgcolor='beige'>3. <input type='text' name='q8' value='$q8' size='3'> The instructor was able to hold my interest.</td></tr>
	
	<tr><td bgcolor='beige'>4. <input type='text' name='q9' value='$q9' size='3'> The instructor was helpful and available.</td></tr>
	
	<tr><td bgcolor='beige'>5. <input type='text' name='q10' value='$q10' size='3'> The instructor was enthusiastic.</td></tr></table>";

	if ($qDriv == "y") {
		$qY = "checked";
	} else {
		$qN = "checked";
	}
	echo "<table><tr><td bgcolor='beige'>Was this training within a two-hour drive of your park and/or your home?</td></tr>
	<tr><td>
	<input type='radio' name='qDriv' value='y' $qY> Yes
	<input type='radio' name='qDriv' value='n'$qN> No
	</td></tr></table>
	<table>
	<tr><td>Any comments about location or facility?</td></tr>
	<tr><td><textarea name='c1' cols='80' rows='4'></textarea>
		  </td></tr>
	<tr><td>What did you like most about this training?</td></tr>
	<tr><td>
			<textarea name='c2' cols='80' rows='5'></textarea>
		  </td>
		</tr>
		<tr><td>How will you apply what you have learned in this workshop to your own situation in your park?</td></tr>
	<tr><td>
			<textarea name='c3' cols='80' rows='5'></textarea>
		  </td>
		</tr> <tr><td>How could this training be improved?</td></tr>
	<tr><td>
			<textarea name='c4' cols='80' rows='5'></textarea>
		  </td>
		</tr> <tr><td>Any additional comments? </td></tr>
	<tr><td>
			<textarea name='c5' cols='80' rows='5'></textarea>
		  </td>
		</tr>
		  </table> <table><tr> 
		  <td>For the <b>next evaluation</b>, just make any changes to the above values and click the Submit Again button; this will create the NEXT evaluation.<br>
		  <input type='hidden' name='aitCat' value='$aitCat'>
		  <input type='hidden' name='clid' value='$clid'>
		  <input type='hidden' name='tid' value='$tid'>
		  <input type='submit' name='Submit' value='Submit Again'></td>
		</tr>
	  </table>
	</form>
	";

	exit;
}

// Construct Query to be passed to Excel Export
foreach ($_REQUEST as $k => $v) {
	if ($v and $k != "PHPSESSID") {
		@$varQuery .= $k . "=" . $v . "&";
	}
}
@$passQuery = $varQuery;
@$varQuery .= "rep=excel";

echo "<html>";

if (@$rep == "") {
	echo "<head>
<title>Evaluate Class</title>

<script language=\"JavaScript\">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+\".location='\"+selObj.options[selObj.selectedIndex].value+\"'\");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>

</head>
<body>
<font size='5' color='004400'>Evaluate Class - NC DPR Training Calendar</font>
<br>
<form name='form1'>
 <select name='menu1' onChange=\"MM_jumpMenu('parent',this,0)\">
          <option selected>Choose a Category</option>
          <option value='eval.php?cat=adm'>Administration</option>
          <option value='eval.php?cat=cert'>EE Certification</option>
          <option value='eval.php?cat=skills'>I&E Skills</option>
          <option value='eval.php?cat=main'>Maintenance</option>
          <option value='eval.php?cat=safe'>Safety</option>
          <option value='eval.php?cat=law'>Law Enforcement</option>
          <option value='eval.php?cat=med'>Medical</option>
          <option value='eval.php?cat=res'>Resource Management</option>
          <option value='eval.php?cat=tra'>Trails</option>
        </select>
        </form>";

	if (@!$cat) {
		exit;
	}
	@$clid = $clidLink;
	$catArray = array(
		"adm" => array("Administration"), "cert" => array("EE Certification"),
		"skills" => array("I&E Skills"), "main" => array("Maintenance"),
		"safe" => array("Safety"), "law" => array("Law Enforcement"),
		"med" => array("Medical"), "res" => array("Resource Management"),
		"tra" => array("Trails")
	);

	$category = $catArray[$cat][0];

	echo "<h3>&nbsp;&nbsp;&nbsp;<font color='blue'>$category</font></h3>";
	if (@$yearClass) {
	} else {
		$yearClass = date("Y");
	}
	echo "<form method='post' action='eval.php'>";
	echo "<table>
<tr><td><b>Choose the Year of Class:</b><input type='text' name='yearClass' value='$yearClass' size='7'></td></tr>
<tr><td><b>Choose the Class Title:</b></td>";
	$where = "WHERE $cat = 1";
	$sql = "SELECT course.title,course.clid
From course
$where 
ORDER by title";
	// echo "$sql"; //exit;
	$result = mysqli_query($connection, $sql) or die("Couldn't execute query. $sql");

	echo "<td><select name='clidLink'>\n";
	echo "<option value=''>\n";
	while ($row = mysqli_fetch_array($result)) {
		extract($row);
		if (@$clidLink == $clid) {
			$s = "selected";
		} else {
			$s = "";
		}

		if ($clid == 312) {
			$s = "selected";
		}

		echo "<option value='$clid' $s>$title";
	}
	echo "</select></td></tr>

<tr><td>&nbsp;</td><td>
      <input type='hidden' name='cat' value='$cat'>
      <input type='submit' name='Submit' value='Show Class(es)'></form></td></tr></table>";

	if (@$clidLink) {
		//	if($clidLink==278){$clidLink=280;}

		$where = "WHERE train.clid = '$clidLink' and dateBegin LIKE '$yearClass%'";
		$sql = "SELECT train.tid, train.title,train.dateBegin,train.dateEnd,train.park, ELT(course.adm,'adm') as adm, ELT(course.cert,'cert') as cert,ELT(course.skills,'skills') as skills,ELT(course.main,'main') as main,ELT(course.safe,'safe') as safe, ELT(course.law,'law') as law,ELT(course.med,'med') as med,ELT(course.res,'res') as res,ELT(course.tra,'tra') as tra
	From train
	left join course on train.clid=course.clid
	$where 
	ORDER by dateBegin";
		// 	 echo "$sql<br>clidLink=$clidLink";//exit;
		$result = mysqli_query($connection, $sql) or die("Couldn't execute query. $sql");
		$tot = @mysqli_num_rows($result);
		if ($tot == 1) {
			$xx = "checked";
		} else {
			$xx = "";
		}

		//	$allow_array=array("280");  //,"279"
		//	$allow_array=array("258","298"); 
		// 	$allow_array=array("329"); 
		$allow_array = array("360");

		if ($tot < 1) {
			echo "<font color='red'>No class found with that title for that year.</font>";
			exit;
		}
		echo "<p><b><font color='red'>Select the correct Class:</font></b><table>
	<form method='post' action='eval.php'>";
		$num_class = mysqli_num_rows($result);
		while ($row = mysqli_fetch_array($result)) {
			extract($row);
			// 	echo "<pre>"; print_r($_POST); echo "</pre>"; // exit;
			if (!in_array($clidLink, $allow_array)) {
				echo "<br />Evaluation locked for $title. $clidLink";
				exit;
			}

			// 	 if($title!="APC 2017 Annual Training Human Resources 2nd Session April 3-5, 2017")
			// 	 	{
			// 	 	echo "<br />Evaluation locked for $title. $clidLink"; exit;
			// 	 	}

			$test = $title . $dateBegin . $dateEnd . $park;

			$aitArray = array($adm, $cert, $skills, $main, $safe, $law, $med, $res, $tra);
			$aitUnique = array_unique($aitArray);
			$aitImplode = implode(",", $aitUnique);

			// Kludge for Office Assistant training
			$pre_select = substr($test, 0, 18);
			// 	echo "ps=$pre_select<br />$test";
			// 	echo "$test<br />";
			if ($pre_select == "APC Human Resource & Budget Training2019-02-252019-02-28HARI") {
				$xx = "checked";
			}

			if (@$prev != $test) {
				// 		if($dateBegin=="2017-03-28"){continue;}
				if ($test == "APC Human Resource & Budget Training2019-02-252019-02-28HARI") {
					$xx = "checked";
					echo "
		<tr><td><font color='blue'><input type='radio' name='tid' value='$tid' $xx required><b>$title</b></font> begin: $dateBegin end: $dateEnd @ $park</td></tr>";
				}
			} //end if
			$prev = $test;
		} //end while
		echo "</table>";
	} // clidLink
} // rep==""

if (@!$clidLink) {
	exit;
}

if (@$rep == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=evaluation_form.xls');
	$blank = "____";
	$showTitle = "<td width='225' colspan='4'>$title</td></tr><tr><td>Date:___________  Park:______</td>";
}

echo "<table cellpadding='5'><tr>";
if (@$rep == "" and $level > 1) {
	$enTitle = urlencode($title);
	echo "<td>Excel <a href='eval.php?$varQuery&title=$enTitle'>Export</a></td>";
}

if (!isset($showTitle)) {
	$showTitle = "";
}
if (!isset($blank)) {
	$blank = "";
}
echo "$showTitle<td width='150'>Instructor(s) Name(s):<input type='text' name='facName' value='' size='75' required> </td></tr></table>
<table>
<tr><td><b>Please rate the training:</b> 
5-Strongly agree, 4-Agree, 3-Undecided, 2-Disagree, 1-Strongly disagree
</td></tr>
<tr><td bgcolor='beige'>1.$blank<input type='text' name='q1' value='' size='3' required> The content of this training was consistent with the description in the training database.</td></tr>

<tr><td bgcolor='beige'>2.$blank<input type='text' name='q2' value='' size='3' required> This training provided me with an adequate understanding of the training topic.</td></tr>

<tr><td bgcolor='beige'>3.$blank<input type='text' name='q3' value='' size='3' required> As a result of taking this training, my skills have improved or will improve.</td></tr>

<tr><td bgcolor='beige'>4.$blank<input type='text' name='q4' value='' size='3' required> The instructional materials and exercises (field or classroom) were useful/adequate.</td></tr>

<tr><td bgcolor='beige'>5.$blank<input type='text' name='q5' value='' size='3' required> I would recommend this training to others.</td></tr>

<tr><td><b>Please rate the instructor(s):</b></td></tr>
<tr><td bgcolor='beige'>1.$blank<input type='text' name='q6' value='' size='3' required> The instructor was prepared and organized.</td></tr>

<tr><td bgcolor='beige'>2.$blank<input type='text' name='q7' value='' size='3' required> The instructor was knowledgeable about the subject matter.</td></tr>

<tr><td bgcolor='beige'>3.$blank<input type='text' name='q8' value='' size='3' required> The instructor was able to hold my interest.</td></tr>

<tr><td bgcolor='beige'>4.$blank<input type='text' name='q9' value='' size='3' required> The instructor was helpful and available.</td></tr>

<tr><td bgcolor='beige'>5.$blank<input type='text' name='q10' value='' size='3' required> The instructor was enthusiastic.</td></tr></table>";

if (@$rep == "excel") {
	echo "<table>
<tr><td bgcolor='beige'> Yes&nbsp;&nbsp;&nbsp;&nbsp;No
&nbsp;&nbsp;&nbsp;Was this training within a two-hour drive of your park and/or your home?</td></tr></table>";
} else {
	echo "<table>
<tr><td bgcolor='beige'>
<input type='radio' name='qDriv' value='y' required> Yes
<input type='radio' name='qDriv' value='n' required> No
&nbsp;&nbsp;&nbsp;Was this training within a two-hour drive of your park and/or your home?</td></tr></table>";
}

echo "<table>
<tr><td>Any comments about the DPR HARI Training Facility? </td></tr>";
//Any comments about location or facility?

if (@$rep == "") {
	echo "<tr><td><textarea name='c1' cols='80' rows='4'></textarea>
      </td></tr>";
} else {
	echo "<tr><td><br><br><td></tr>";
}

if (in_array($clidLink, $allow_array)) {
	echo "<tr><td>Was the Budget / HR training helpful to you? </td></tr>";
	if (@$rep == "") {
		echo "<tr><td>
	<textarea name='c6' cols='80' rows='5'></textarea>
	</td>
	</tr>";
	} else {
		echo "<tr><td><br><br><td></tr>";
	}
}

echo "<tr><td>What did you like most about this training? </td></tr>";
if (@$rep == "") {
	echo "<tr><td>
        <textarea name='c2' cols='80' rows='5'></textarea>
      </td>
    </tr>";
} else {
	echo "<tr><td><br><br><td></tr>";
}


echo "<tr><td>How will you apply what you have learned in this workshop to your own situation in your park? </td></tr>";
if (@$rep == "") {
	echo "<tr><td>
        <textarea name='c3' cols='80' rows='5'></textarea>
      </td>
    </tr>";
} else {
	echo "<tr><td><br><br><td></tr>";
}

if (in_array($clidLink, $allow_array)) {
	echo "<tr><td>Did you receive the electronic APC Newsletters sent to your email address? </td></tr>";
	if (@$rep == "") {
		echo "<tr><td>
			<textarea name='c7' cols='80' rows='5'></textarea>
		  </td>
		</tr>";
	} else {
		echo "<tr><td><br><br><td></tr>";
	}
}

if (in_array($clidLink, $allow_array)) {
	echo "<tr><td>Do you have suggestions for future APC Newsletter topics? </td></tr>";
	if (@$rep == "") {
		echo "<tr><td>
			<textarea name='c8' cols='80' rows='5'></textarea>
		  </td>
		</tr>";
	} else {
		echo "<tr><td><br><br><td></tr>";
	}
}

if (in_array($clidLink, $allow_array)) {
	echo "<tr><td>Do you have any comments about the APC information packets , door prizes and give-away sponsors? </td></tr>";
	if (@$rep == "") {
		echo "<tr><td>
			<textarea name='c9' cols='80' rows='5'></textarea>
		  </td>
		</tr>";
	} else {
		echo "<tr><td><br><br><td></tr>";
	}
}

$text = "How could this training be improved? ";
if (in_array($clidLink, $allow_array)) {
	$text = "How would you improve the DPR Administrative Professional Training? Training topic suggestions? ";
}
echo "<tr><td>$text</td></tr>";
if (@$rep == "") {
	echo "<tr><td>
        <textarea name='c4' cols='80' rows='5'></textarea>
      </td>
    </tr>";
} else {
	echo "<tr><td><br><br><td></tr>";
}

$text = "Any additional comments? ";
if (in_array($clidLink, $allow_array)) {
	$text = "Any additional comments about this APC training experience? ";
}
echo "<tr><td>$text</td></tr>";
if (@$rep == "") {
	echo "<tr><td>
        <textarea name='c5' cols='80' rows='5'></textarea>
      </td>
    </tr>";
} else {
	echo "<tr><td><br><br><td></tr>";
}

echo "</table>";

if ($_SESSION['dprcal']['level'] == 1) {
	$eval = "<input type='hidden' name='rep' value='1'>";
}


if (@$rep == "") {
	echo "<table><tr> 
      <td><br>
      <input type='hidden' name='aitCat' value='$aitImplode'>
      <input type='hidden' name='clid' value='$clidLink'>
      <input type='submit' name='Submit' value='Submit'></td>
    </tr>";
}

echo "</table>
</form>
</body>
</html>
";
