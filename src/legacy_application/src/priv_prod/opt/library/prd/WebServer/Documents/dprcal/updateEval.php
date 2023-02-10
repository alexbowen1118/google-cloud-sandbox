<?phpmysqli_fetch_array($result)
// session_start();
include("../../include/authTrainCal.inc");
include("../../include/connectTrainCal.inc");
include("nav.php");
	
//print_r($_REQUEST);exit;

if($Submit=="Delete This Eval"){
$sql="DELETE FROM eval where evid=$evid";
//echo "$sql"; exit;
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql");
echo "That Evaluation has been deleted.";
exit;
}


$val = strpos($Submit, "Submit");
if($val > -1){  // works for both Submit and Submit Again
// print_r($_REQUEST); exit;
$test = $facName.$q1.$q2.$q3.$q4.$q5.$q6.$q7.$q8.$q9.$q10;
if($test=="" and !$evid){echo "You must enter an Instructor's name and Rate both the Training and the Instructor.<br><br>Click your Browser's BACK button.";exit;}
if(!$evid){echo "No eval ID sent"; exit;}
$c1=addslashes($c1);$c2=addslashes($c2);
$c3=addslashes($c3);$c4=addslashes($c4);$c5=addslashes($c5);
$sql="UPDATE eval SET q1='$q1',q2='$q2',q3='$q3',q4='$q4',q5='$q5',q6='$q6',q7='$q7',q8='$q8',q9='$q9',q10='$q10',qDriv='$qDriv',c1='$c1',c2='$c2',c3='$c3',c4='$c4',c5='$c5'
WHERE evid=$evid";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql");


$sql = "SELECT eval.*,train.*
From eval
LEFT JOIN train on eval.tid=train.tid
where evid=$evid";
// echo "$sql"; exit;
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql");
$row = mysqli_fetch_array($result);
echo "<table>";
extract($row);
echo "<tr>
<td>Title: <b>$title</b></td><td>$aitCat</td>
<td>Begin: $dateBegin End: $dateEnd</td></tr>
</table>
<form method='post' action='updateEval.php'>
  <table><tr><td>Instructor(s) Name(s):<input type='text' name='facName' value='$facName' size='50'></td></tr></table>
<table>
<tr><td><b>Please rate the training:</b> 
5-Strongly agree, 4-Agree, 3-Undecided, 2-Disagree, 1-Strongly disagree
</td></tr>
<tr><td bgcolor='beige'>1. <input type='text' name='q1' value='$q1' size='3'> The content of this training was consistent with the description in the training database.</td></tr>

<tr><td bgcolor='beige'>2. <input type='text' name='q2' value='$q2' size='3'> This training provided me with an adequate understanding of the training topic.</td></tr>

<tr><td bgcolor='beige'>3. <input type='text' name='q3' value='$q3' size='3'> As a result of taking this training, my skills in interpretation and education have improved or will improve.</td></tr>

<tr><td bgcolor='beige'>4. <input type='text' name='q4' value='$q4' size='3'> The instructional materials and exercises (field or classroom) were useful/adequate.</td></tr>

<tr><td bgcolor='beige'>5. <input type='text' name='q5' value='$q5' size='3'> I would recommend this training to other rangers/educators.</td></tr>

<tr><td><b>Please rate the instructor(s):</b></td></tr>
<tr><td bgcolor='beige'>1. <input type='text' name='q6' value='$q6' size='3'> The instructor was prepared and organized.</td></tr>

<tr><td bgcolor='beige'>2. <input type='text' name='q7' value='$q7' size='3'> The instructor was knowledgeable about the subject matter.</td></tr>

<tr><td bgcolor='beige'>3. <input type='text' name='q8' value='$q8' size='3'> The instructor was able to hold my interest.</td></tr>

<tr><td bgcolor='beige'>4. <input type='text' name='q9' value='$q9' size='3'> The instructor was helpful and available.</td></tr>

<tr><td bgcolor='beige'>5. <input type='text' name='q10' value='$q10' size='3'> The instructor was enthusiastic.</td></tr></table>";

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
      </table> <table><tr> 
      <td>For the next evaluation, make any changes and click the Submit Again button<br>
      <input type='hidden' name='aitCat' value='$aitCat'>
      <input type='hidden' name='clid' value='$clid'>
      <input type='hidden' name='evid' value='$evid'>
      <input type='submit' name='Submit' value='Submit Again'></td>
    </tr>
  </table>
</form>
";

exit;
}

?>