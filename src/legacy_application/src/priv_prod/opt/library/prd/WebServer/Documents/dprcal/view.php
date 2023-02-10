<?php 
ini_set('display_errors',1);
$database="dprcal";
include("../../include/auth.inc");
include("../../include/iConnect.inc");

mysqli_select_db($connection,$database);
extract($_REQUEST);
// echo "<pre>"; print_r($_POST); echo "</pre>"; // exit;
include("nav.php");
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
$access_array=array("60033136"); //Human Resource Manager
$level=$_SESSION['dprcal']['level'];

mysqli_select_db($connection,"div_cor");
// $exclude=array("60032988","60033148",);
$exclude=array();
$sql = "SELECT t1.apc_id, t3.Fname, t3.Lname
 FROM `access` as t1
left join divper.emplist as t2 on t2.beacon_num=t1.apc_id
left join divper.empinfo as t3 on t2.tempID=t3.tempID
where t1.apc_id !=''
";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql ".mysqli_error($connection));
while($row=mysqli_fetch_assoc($result))
	{
	if(in_array($row['apc_id'],$exclude)){continue;}
	$apc_array[]=$row['apc_id'];
	}
// echo "<pre>"; print_r($apc_array); echo "</pre>"; // exit;

if(in_array($_SESSION['beacon_num'],$apc_array))
	{
	$temp_level=4;
	$_SESSION['dprcal']['apc']=1;
	}

if(!empty($temp_level))
	{
	$level=$temp_level;
	}
// echo "l=$level t=$tidLink";
// ***** Functions
function question($q){
global $v5,$v4,$v3,$v2,$v1;
switch ($q) {
		case "5":
			$v5 = "checked";$v4="";$v3="";$v2="";$v1="";
			break;	
		case "4":
			$v4="checked";$v5="";$v3="";$v2="";$v1="";
			break;	
		case "3":
			$v3 ="checked";$v4="";$v5="";$v2="";$v1="";
			break;	
		case "2":
			$v2 ="checked";$v4="";$v3="";$v5="";$v1="";
			break;	
		case "1":
			$v1 ="checked";$v4="";$v3="";$v2="";$v5="";
			break;	
	}
	}// end function
	
function comment($c){
$i=1;
foreach ($c as $value) {
    echo "<font color='purple'>Comment $i:</font><font color='blue'> $value</font><br>\n"; $i++;
}
}// end function
// ***** End Functions

mysqli_select_db($connection,"dprcal");

if(@$Submit=="Submit")
	{
	$sql = "SELECT eval.*,train.*
	From eval
	LEFT JOIN train on eval.tid=train.tid
	where evid=$evid";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	$row = mysqli_fetch_array($result);
	echo "<html><head><title></title></head><body>
	
	<form method='post' action='updateEval.php'><table>";
	extract($row);
	echo "<tr>
	<td>Title: <b>$title</b></td><td>$aitCat</td>
	<td>Begin: $dateBegin End: $dateEnd</td></tr>
	<td>Instructor(s): $facName</td></tr></table>
	<table><tr><td><b>Please rate the training:</b></td></tr>
	<tr><td bgcolor='beige'>1. The content of this training was consistent with the description in the training database.</td></tr>
	<tr><td>";
	question($q1);
	echo "<input type='radio' name='q1' value='5' $v5> Strongly agree
	<input type='radio' name='q1' value='4' $v4> Agree
	<input type='radio' name='q1' value='3' $v3> Undecided
	<input type='radio' name='q1' value='2' $v2> Disagree
	<input type='radio' name='q1' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q2);
	echo"
	<tr><td bgcolor='beige'>2. This training provided me with an adequate understanding of the training topic.</td></tr>
	<tr><td>
	<input type='radio' name='q2' value='5' $v5> Strongly agree
	<input type='radio' name='q2' value='4' $v4> Agree
	<input type='radio' name='q2' value='3' $v3> Undecided
	<input type='radio' name='q2' value='2' $v2> Disagree
	<input type='radio' name='q2' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q3);
	echo"
	<tr><td bgcolor='beige'>3. As a result of taking this training, my skills have improved or will improve.</td></tr>
	<tr><td>
	<input type='radio' name='q3' value='5' $v5> Strongly agree
	<input type='radio' name='q3' value='4' $v4> Agree
	<input type='radio' name='q3' value='3' $v3> Undecided
	<input type='radio' name='q3' value='2' $v2> Disagree
	<input type='radio' name='q3' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q4);
	echo"
	<tr><td bgcolor='beige'>4. The instructional materials and exercises (field or classroom) were useful/adequate.</td></tr>
	<tr><td>
	<input type='radio' name='q4' value='5' $v5> Strongly agree
	<input type='radio' name='q4' value='4' $v4> Agree
	<input type='radio' name='q4' value='3' $v3> Undecided
	<input type='radio' name='q4' value='2' $v2> Disagree
	<input type='radio' name='q4' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q5);
	echo"
	<tr><td bgcolor='beige'>5. I would recommend this training to others.</td></tr>
	<tr><td>
	<input type='radio' name='q5' value='5' $v5> Strongly agree
	<input type='radio' name='q5' value='4' $v4> Agree
	<input type='radio' name='q5' value='3' $v3> Undecided
	<input type='radio' name='q5' value='2' $v2> Disagree
	<input type='radio' name='q5' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q6);
	echo"<tr><td><b>Please rate the instructor(s):</b></td></tr>
	<tr><td bgcolor='beige'>1. The instructor was prepared and organized.</td></tr>
	<tr><td>
	<input type='radio' name='q6' value='5' $v5> Strongly agree
	<input type='radio' name='q6' value='4' $v4> Agree
	<input type='radio' name='q6' value='3' $v3> Undecided
	<input type='radio' name='q6' value='2' $v2> Disagree
	<input type='radio' name='q6' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q7);
	echo"
	<tr><td bgcolor='beige'>2. The instructor was knowledgeable about the subject matter.</td></tr>
	<tr><td>
	<input type='radio' name='q7' value='5' $v5> Strongly agree
	<input type='radio' name='q7' value='4' $v4> Agree
	<input type='radio' name='q7' value='3' $v3> Undecided
	<input type='radio' name='q7' value='2' $v2> Disagree
	<input type='radio' name='q7' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q8);
	echo"
	<tr><td bgcolor='beige'>3. The instructor was able to hold my interest.</td></tr>
	<tr><td>
	<input type='radio' name='q8' value='5' $v5> Strongly agree
	<input type='radio' name='q8' value='4' $v4> Agree
	<input type='radio' name='q8' value='3' $v3> Undecided
	<input type='radio' name='q8' value='2' $v2> Disagree
	<input type='radio' name='q8' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q9);
	echo"
	<tr><td bgcolor='beige'>4. The instructor was helpful and available.</td></tr>
	<tr><td>
	<input type='radio' name='q9' value='5' $v5> Strongly agree
	<input type='radio' name='q9' value='4' $v4> Agree
	<input type='radio' name='q9' value='3' $v3> Undecided
	<input type='radio' name='q9' value='2' $v2> Disagree
	<input type='radio' name='q9' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q10);
	echo"
	<tr><td bgcolor='beige'>5. The instructor was enthusiastic.</td></tr>
	<tr><td>
	<input type='radio' name='q10' value='5' $v5> Strongly agree
	<input type='radio' name='q10' value='4' $v4> Agree
	<input type='radio' name='q10' value='3' $v3> Undecided
	<input type='radio' name='q10' value='2' $v2> Disagree
	<input type='radio' name='q10' value='1' $v1>Strongly disagree
	</td></tr></table>";
	if($qDriv=="y"){$qY="checked";}else{$qN="checked";}
	echo "<table><tr><td bgcolor='beige'>Was this training within a two-hour drive of your park and/or your home?</td></tr>
	<tr><td>
	<input type='radio' name='qDriv' value='y' $qY> Yes
	<input type='radio' name='qDriv' value='n'$qN> No
	</td></tr></table>
	<table>
	<tr><td>Any comments about location or facility?</td></tr>
	<tr><td><textarea name='c1' cols='80' rows='4'>$c1</textarea>
		  </td></tr>
	<tr><td>What did you like most about this training?</td></tr>
	<tr><td>
			<textarea name='c2' cols='80' rows='5'>$c2</textarea>
		  </td>
		</tr>
		<tr><td>How will you apply what you have learned in this workshop to your own situation in your park?</td></tr>
	<tr><td>
			<textarea name='c3' cols='80' rows='5'>$c3</textarea>
		  </td>
		</tr> <tr><td>How could this training be improved?</td></tr>
	<tr><td>
			<textarea name='c4' cols='80' rows='5'>$c4</textarea>
		  </td>
		</tr> <tr><td>Any additional comments? </td></tr>
	<tr><td>
			<textarea name='c5' cols='80' rows='5'>$c5</textarea>
		  </td>
		</tr>
		  </table>
		<table><tr> 
		  <td><br>
		  <input type='hidden' name='aitCat' value='$cat'>
		  <input type='hidden' name='tid' value='$tidLink'>
		  <input type='hidden' name='tidXX' value='$tid'>
		  <input type='hidden' name='evid' value='$evid'>
		  <input type='submit' name='Submit' value='Submit'></td>
		</tr>
		<tr><td>&nbsp;</td><td>
		  <input type='hidden' name='evid' value='$evid'>
		  <input type='submit' name='Submit' value='Delete This Eval'></td></tr>
	  </table>
	</form>
	</body>
	</html>
	";
	exit;
	}// end $Submit=Submit

// **************************Edit entry
if(@$Submit=="Edit")
	{
	// print_r($_SESSION); exit;
	
	$sql = "SELECT eval.*,train.*
	From eval
	LEFT JOIN train on eval.tid=train.tid
	where evid=$evid";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	$row = mysqli_fetch_array($result);
	echo "<table>";
	extract($row);
	echo "<tr>
	<td>Title: <b>$title</b></td><td>$aitCat</td>
	<td>Begin: $dateBegin End: $dateEnd</td></tr>
	<td>Instructor(s): $facName</td></tr></table>
	<table><tr><td><b>Please rate the training:</b></td></tr>
	<tr><td bgcolor='beige'>1. The content of this training was consistent with the description in the training database.</td></tr>
	<tr><td>";
	question($q1);
	echo "<input type='radio' name='q1' value='5' $v5> Strongly agree
	<input type='radio' name='q1' value='4' $v4> Agree
	<input type='radio' name='q1' value='3' $v3> Undecided
	<input type='radio' name='q1' value='2' $v2> Disagree
	<input type='radio' name='q1' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q2);
	echo"
	<tr><td bgcolor='beige'>2. This training provided me with an adequate understanding of the training topic.</td></tr>
	<tr><td>
	<input type='radio' name='q2' value='5' $v5> Strongly agree
	<input type='radio' name='q2' value='4' $v4> Agree
	<input type='radio' name='q2' value='3' $v3> Undecided
	<input type='radio' name='q2' value='2' $v2> Disagree
	<input type='radio' name='q2' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q3);
	echo"
	<tr><td bgcolor='beige'>3. As a result of taking this training, my skills have improved or will improve.</td></tr>
	<tr><td>
	<input type='radio' name='q3' value='5' $v5> Strongly agree
	<input type='radio' name='q3' value='4' $v4> Agree
	<input type='radio' name='q3' value='3' $v3> Undecided
	<input type='radio' name='q3' value='2' $v2> Disagree
	<input type='radio' name='q3' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q4);
	echo"
	<tr><td bgcolor='beige'>4. The instructional materials and exercises (field or classroom) were useful/adequate.</td></tr>
	<tr><td>
	<input type='radio' name='q4' value='5' $v5> Strongly agree
	<input type='radio' name='q4' value='4' $v4> Agree
	<input type='radio' name='q4' value='3' $v3> Undecided
	<input type='radio' name='q4' value='2' $v2> Disagree
	<input type='radio' name='q4' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q5);
	echo"
	<tr><td bgcolor='beige'>5. I would recommend this training to others.</td></tr>
	<tr><td>
	<input type='radio' name='q5' value='5' $v5> Strongly agree
	<input type='radio' name='q5' value='4' $v4> Agree
	<input type='radio' name='q5' value='3' $v3> Undecided
	<input type='radio' name='q5' value='2' $v2> Disagree
	<input type='radio' name='q5' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q6);
	echo"<tr><td><b>Please rate the instructor(s):</b></td></tr>
	<tr><td bgcolor='beige'>1. The instructor was prepared and organized.</td></tr>
	<tr><td>
	<input type='radio' name='q6' value='5' $v5> Strongly agree
	<input type='radio' name='q6' value='4' $v4> Agree
	<input type='radio' name='q6' value='3' $v3> Undecided
	<input type='radio' name='q6' value='2' $v2> Disagree
	<input type='radio' name='q6' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q7);
	echo"
	<tr><td bgcolor='beige'>2. The instructor was knowledgeable about the subject matter.</td></tr>
	<tr><td>
	<input type='radio' name='q7' value='5' $v5> Strongly agree
	<input type='radio' name='q7' value='4' $v4> Agree
	<input type='radio' name='q7' value='3' $v3> Undecided
	<input type='radio' name='q7' value='2' $v2> Disagree
	<input type='radio' name='q7' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q8);
	echo"
	<tr><td bgcolor='beige'>3. The instructor was able to hold my interest.</td></tr>
	<tr><td>
	<input type='radio' name='q8' value='5' $v5> Strongly agree
	<input type='radio' name='q8' value='4' $v4> Agree
	<input type='radio' name='q8' value='3' $v3> Undecided
	<input type='radio' name='q8' value='2' $v2> Disagree
	<input type='radio' name='q8' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q9);
	echo"
	<tr><td bgcolor='beige'>4. The instructor was helpful and available.</td></tr>
	<tr><td>
	<input type='radio' name='q9' value='5' $v5> Strongly agree
	<input type='radio' name='q9' value='4' $v4> Agree
	<input type='radio' name='q9' value='3' $v3> Undecided
	<input type='radio' name='q9' value='2' $v2> Disagree
	<input type='radio' name='q9' value='1' $v1>Strongly disagree
	</td></tr>";
	question($q10);
	echo"
	<tr><td bgcolor='beige'>5. The instructor was enthusiastic.</td></tr>
	<tr><td>
	<input type='radio' name='q10' value='5' $v5> Strongly agree
	<input type='radio' name='q10' value='4' $v4> Agree
	<input type='radio' name='q10' value='3' $v3> Undecided
	<input type='radio' name='q10' value='2' $v2> Disagree
	<input type='radio' name='q10' value='1' $v1>Strongly disagree
	</td></tr></table>";
	if($qDriv=="y"){$qY="checked";}else{$qN="checked";}
	echo "<table><tr><td bgcolor='beige'>Was this training within a two-hour drive of your park and/or your home?</td></tr>
	<tr><td>
	<input type='radio' name='qDriv' value='y' $qY> Yes
	<input type='radio' name='qDriv' value='n'$qN> No
	</td></tr></table>
	<table>
	<tr><td>Any comments about location or facility?</td></tr>
	<tr><td><textarea name='c1' cols='80' rows='4'>$c1</textarea>
		  </td></tr>
	<tr><td>What did you like most about this training?</td></tr>
	<tr><td>
			<textarea name='c2' cols='80' rows='5'>$c2</textarea>
		  </td>
		</tr>
		<tr><td>How will you apply what you have learned in this workshop to your own situation in your park?</td></tr>
	<tr><td>
			<textarea name='c3' cols='80' rows='5'>$c3</textarea>
		  </td>
		</tr> <tr><td>How could this training be improved?</td></tr>
	<tr><td>
			<textarea name='c4' cols='80' rows='5'>$c4</textarea>
		  </td>
		</tr> <tr><td>Any additional comments? </td></tr>
	<tr><td>
			<textarea name='c5' cols='80' rows='5'>$c5</textarea>
		  </td>
		</tr>
		  </table>
	";
	exit;
	}// end $Submit=Edit

// **************************Review entrie
if(@$Submit=="Review")
	{
	// print_r($_SESSION); exit;
	$sql = "SELECT eval.*,train.*
	From eval
	LEFT JOIN train on eval.tid=train.tid
	where eval.tid=$edit";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	$i=1;
	echo "<table>";
	while ($row = mysqli_fetch_array($result))
	{
	extract($row);
	echo "<tr>";
	if($level>4)
		{
		echo "<td><a href='view.php?Submit=Submit&evid=$evid'>Edit $i</a></td>";
		}
		else
		{
		echo "<td>$i</td>";
		}
	echo "<td>$title @ $park on $dateFind Instruct: $facName</td>
	<td>$q1</td>
	<td>$q2</td>
	<td>$q3</td>
	<td>$q4</td>
	<td>$q5</td>
	<td>$q6</td>
	<td>$q7</td>
	<td>$q8</td>
	<td>$q9</td>
	<td>$q10</td>
	<td>$qDriv</td>
	<td>$c1</td>
	<td>$c2</td>
	<td>$c3</td>
	<td>$c4</td>
	<td>$c5</td>
	</tr>";
	$i++;}
	echo "</table>";
	exit;
	}//end $Submit=Review

// ***********************Entry Form below
?>
<html><head><title>Evaluate Class</title>
<script language="JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
</head><body>
<font size="5" color="004400">Evaluate Class - NC DPR Training Calendar</font>

<?php
echo "<br>";
if($level>3){
echo "<form name=\"form1\">
 <select name=\"menu1\" onChange=\"MM_jumpMenu('parent',this,0)\">
          <option selected>Choose a Category</option>
          <option value='view.php?cat=adm'>Administration</option>
          <option value='view.php?cat=cert'>EE Certification</option>
          <option value='view.php?cat=skills'>I&E Skills</option>
          <option value='view.php?cat=main'>Maintenance</option>
          <option value='view.php?cat=safe'>Safety</option>
          <option value='view.php?cat=law'>Law Enforcement</option>
          <option value='view.php?cat=med'>Medical</option>
          <option value='view.php?cat=res'>Resource Management</option>
          <option value='view.php?cat=tra'>Trails</option>
        </select>
        </form>";
        }

if(@!$cat){exit;}
//print_r($_REQUEST);
//$tid=$tidLink;
$catArray = array(
"adm"=> array("Administration"),"cert"=> array("EE Certification"),
"skills"=> array("I&E Skills"),"main"=> array("Maintenance"),
"safe"=> array("Safety"),"law"=> array("Law Enforcement"),
"med"=> array("Medical"),"res"=> array("Resource Management"),
"tra"=> array("Trails"));

$category = $catArray[$cat][0];

//	echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
echo "<h3>&nbsp;&nbsp;&nbsp;<font color='blue'>$category</font></h3>";

echo "<form method='post' action='view.php' >"; 
echo "<table><tr><td><b>Choose the Class Title:</b></td>";
$where= "WHERE $cat = 1";

$sql = "SELECT DISTINCT train.title,train.dateFind,eval.tid,train.park
From eval
LEFT JOIN train on eval.tid=train.tid
where aitCat LIKE '%$cat%'
ORDER by train.title";

// echo "$sql"; exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");

if(!isset($tidLink)){$tidLink="";}
echo "<td>$cat<select name='tidLink' onchange=\"this.form.submit()\">\n";
 echo "<option value=''>\n";
while ($row = mysqli_fetch_array($result))
	{
	extract($row);
	if($tid==$tidLink){$s="selected";}else{$s="";}

	echo "<option value='$tid' $s>$title $dateFind $park";
	}
echo "</select></td></tr>";

if($level>3){echo "<tr><td>&nbsp;</td><td>
      <input type='hidden' name='cat' value='$cat'>
      <input type='submit' name='Submit' value='Show Evaluation'></form></td></tr>";}
      
      echo "</table>";

if(!empty($tidLink))
	{
	// q4 has a potential 0 value which should be used
	$where= "WHERE (eval.tid = $tidLink AND q4>0) GROUP by eval.tid";
	//$where= "WHERE (eval.tid = $tidLink) GROUP by eval.tid";
	$sql = "SELECT avg(q1) as q1A, avg(q2) as q2A, avg(q3) as q3A,avg(q4) as q4A, avg(q5) as q5A, avg(q6) as q6A, avg(q7) as q7A, avg(q8) as q8A,avg(q9) as q9A, avg(q10) as q10A, facName, count(evid) as respNum
	From eval
	LEFT JOIN train on eval.tid=train.tid
	$where ";
// 	echo "$sql $tid $title<br>";exit;
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	//echo "$sql"; exit;
	$tot=@mysqli_num_rows($result);
	if($tot==1){$xx="checked";}
	echo "<table>";
	while ($row = mysqli_fetch_array($result))
		{
		extract($row);
		}
	}
 if(empty($tidLink)){exit;}
 
// if(!empty($_SESSION['dprcal']['apc']))
// 	{
// 	if($tidLink!="3470" and $tidLink!="3465")
// 		{exit;}
// 	}
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;

// 		echo "$tidLink";
if(!empty($_SESSION['dprcal']['apc']))
	{
	if($tidLink!="4030" and $tidLink!="4034")
		{
		echo "test";
		exit;
		}
	}
echo "<form method='post' action='view.php'>
  <table width='100%' cellpadding='1'>
  <tr><td colspan='6'>Instructor(s) Name(s): <font color='blue'>$facName</font></td></tr>
<tr><td colspan='6'>Number of submitted Evaluations: <font color='blue'>$respNum</font></td></tr>

<tr><td>[5 - Strongly agree] 
[4 - Agree] 
[3 - Undecided] 
[2 - Disagree] 
[1 - Strongly disagree] 
</td></tr></table>

<table><tr><td><b>Training rating:</b></td></tr>";

$q1r=roundQ($q1A);
echo "
<tr><td bgcolor='beige'>1. The content of this training was consistent with the description in the training database. </td><td><font color='blue'> Avg = $q1r</font></td></tr>";

$q2r=roundQ($q2A);
echo "<tr><td bgcolor='beige'>2. This training provided me with an adequate understanding of the training topic.</td><td><font color='blue'> Avg = $q2r</font></td></tr>";

$q3r=roundQ($q3A);
echo "<tr><td bgcolor='beige'>3. As a result of taking this training, my skills have improved or will improve.</td><td><font color='blue'> Avg = $q3r</font></td></tr>";

$q4r=roundQ($q4A);
echo "<tr><td bgcolor='beige'>4. The instructional materials and exercises (field or classroom) were useful/adequate.</td><td><font color='blue'> Avg = $q4r</font></td></tr>";

$q5r=roundQ($q5A);
echo "<tr><td bgcolor='beige'>5. I would recommend this training to others.</td><td><font color='blue'> Avg = $q5r</font></td></tr>

<tr><td><b>Instructor rating:</b></td></tr>
<tr><td bgcolor='beige'>1. The instructor was prepared and organized.</td><td><font color='blue'> Avg = $q6A</font></td></tr>

<tr><td bgcolor='beige'>2. The instructor was knowledgeable about the subject matter.</td><td><font color='blue'> Avg = $q7A</font></td></tr>

<tr><td bgcolor='beige'>3. The instructor was able to hold my interest.</td><td><font color='blue'> Avg = $q8A</font></td></tr>

<tr><td bgcolor='beige'>4. The instructor was helpful and available.</td><td><font color='blue'> Avg = $q9A</font></td></tr>

<tr><td bgcolor='beige'>5. The instructor was enthusiastic.</td><td><font color='blue'> Avg = $q10A</font></td></tr></table>";

{
$where= "WHERE eval.tid = $tidLink";
$sql = "SELECT qDriv,c1,c2,c3,c4,c5
From eval
LEFT JOIN train on eval.tid=train.tid
$where ";
// echo "$tid $title<br>";exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
//echo "$sql"; exit;

while ($row = mysqli_fetch_array($result))
	{
extract($row);
$driveA[]=$qDriv;
$c1A[]=$c1;
$c2A[]=$c2;
$c3A[]=$c3;
$c4A[]=$c4;
$c5A[]=$c5;
	}
}
$drive=array_count_values($driveA);

echo "<table>
<tr><td><b>Was this training within a two-hour drive of your park and/or your home?</b></td><td><font color='blue'>Yes = $drive[y] No = $drive[n]</font></td></tr></table>";
echo "
<table>
<tr><td><b>Any comments about location or facility?</b></td></tr>
<tr><td>";
comment($c1A);
  echo "</td></tr>
<tr><td><b>What did you like most about this training?</b></td></tr>
<tr><td>";
comment($c2A);
echo "</td></tr>
    <tr><td><b>How will you apply what you have learned in this workshop to your own situation in your park?</b></td></tr>
<tr><td>";
comment($c3A);
echo "</td></tr>
 <tr><td><b>How could this training be improved?</b></td></tr>
<tr><td>";
comment($c4A);
echo "</td></tr>
 <tr><td><b>Any additional comments? </b></td></tr>
<tr><td>";
comment($c5A);
echo "</td></tr>
      </table>";
 if($level>3){
   echo" <table><tr> 
      <td><br>
      <input type='hidden' name='aitCat' value='$cat'>
      <input type='hidden' name='edit' value='$tidLink'>
      <input type='submit' name='Submit' value='Review'></td>
    </tr>
  </table></form>";
  }
  
echo "</body></html>";

function roundQ($q){
$rq=round($q,1);
return $rq;
}
?>