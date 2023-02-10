<?php

$database="dprcal";
include("../../include/auth.inc");
$level=$_SESSION[$database]['level'];
if($level>3)
	{
	ini_set('display_errors',1);
// 	echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
	}
include("../../include/iConnect.inc");
// $_POST['trainID']="09-02-00Art in Nature AIT Workshop";
// $_POST['park']="ELKN";
// $_POST['tid']="4104";
// $_POST['Submit']="Signup";

extract($_POST);
include("nav.php");
//echo "<pre>";print_r($_SESSION);echo "</pre>";
// echo "<pre>";print_r($_REQUEST);echo "</pre>"; exit;

if($Submit == "Signup")
	{
	include("../../include/get_parkcodes_reg.php");
	
mysqli_select_db($connection,'divper');
	//session_start();
	$tempID=$_SESSION['dprcal']['loginS'];
	$level=$_SESSION['dprcal']['level'];
	if(!is_numeric($tid)){exit;}
	
	$sql = "SELECT empinfo.emid,empinfo.Fname,empinfo.Lname, emplist.currPark, position.posTitle, empinfo.email
	From empinfo
	LEFT JOIN emplist on empinfo.tempID=emplist.tempID
	LEFT JOIN position on emplist.posNum=position.posNum
	WHERE empinfo.tempID = '$tempID'";
	// echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	if(mysqli_num_rows($result)>0)
		{
		$row = mysqli_fetch_array($result);
		extract($row);
		$yourPark=$currPark;
		$_SESSION['dprcal']['posTitle']=$posTitle;
		$test1=strpos($posTitle,"Office Assistant");
		$test2=strpos($posTitle,"Park Super");
		if($test1>-1 || $test2>-1){$level=2;}// Allows these folks to signup anyone
		}
		else
		{
		$emid=$_SESSION['dprcal']['emid'];
		$sql = "SELECT nondpr.Fname,nondpr.Lname, nondpr.currPark, nondpr.email
		From nondpr
		WHERE nondpr.emid = '$emid'";
		$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql.".mysqli_error($connection));
		$row = mysqli_fetch_array($result); extract($row);
		$yourPark="nondpr";
		}
	
mysqli_select_db($connection,$database);
	$sql = "SELECT *,park as parkP, location From train WHERE `tid` = '$tid'";
	// echo "$sql";
	$result1 = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	$row1 = mysqli_fetch_array($result1);
	extract($row1);

	$titleU = urlencode(addslashes($title));
// 	$location = addslashes($location);
	//if($parkP){$parkP="at ".$parkP;}
	
	$slashTID=addslashes($trainID);
	$sql = "SELECT dateFind From train WHERE `trainID` = '$slashTID' order by dateFind";
	// echo "$sql";
	$result1 = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	while($row1 = mysqli_fetch_array($result1))
		{
		extract($row1);
		$dayArray[]=$dateFind;
		}
	
	
	if(count($dayArray)>1)
		{
		$day="(";
		foreach($dayArray as $k=>$v){$day.=" ".$v;}
		$day.=" )";
		
		$multiDay="<br><font size='+1' color='blue'><b>&nbsp;&nbsp;&nbsp;&nbsp;This is a multi-day class</font> - $day.</b><br>By <font color='red'>Default</font> you will automatically be signed up for ALL days in the class.<br><font color='green'><b>IF this is not what you want</b></font>, contact the instructor to determine whether attendance at only some of the class is acceptable before signing up.<br><br>";
		}
	
	if(!isset($multiDay)){$multiDay="";}
	echo "
	<html>
	<head>
	<title>Schedule Class</title>
	</head>
	<body>
	<p><font size='5' color='004400'>Signup for a Class - NC DPR Training Calendar</font></p>
	<p>
	<form method='post' action='signupClass.php'>
	
	  <table width='100%' cellpadding='1'>
	  <tr><td>Class:</td></tr>
	  <tr><td style='text-indent:15px;'><font size='4' color = 'blue'><b>$title</b></font> at $parkP</td></tr>
	  <tr><td><br /></td></tr>
	  <tr><td>Location and Date:</td></tr>
	  <tr><td style='text-indent:15px;'>$location</td></tr>
	  <tr><td style='text-indent:15px;'>from $dateBegin to $dateEnd</td></tr>
	<tr><td style='text-indent:15px;'>$multiDay <br /></td></tr>";
	  
	  echo "<tr><td><b>All training requires Supervisor Approval.</b></td></tr>
	  <tr><td style='text-indent:15px;'><input type='checkbox' name='supervisor' required><font color = 'red'>  Check if you have such approval.</font></td></tr> <tr><td><br /></td></tr>";
	  
	  echo "<tr><td><b>Does this training require a Travel Authorization? If \"Yes\", enter your TA number:</b></td></tr>";
	  
	  echo" <tr><td style='text-indent:15px;'>If required and no TA was submitted, reimbursement may be denied.</td></tr>
	  <tr><td style='text-indent:15px;'><font color = 'red'>Copy and paste the complete TA number here (accuracy is important): </font></td></tr>
	  <tr><td style='text-indent:25px;'>TA #: <input type='test' name='ta_submitted'><br />
	       <br /></td></tr>
	  <tr><td><br /></td></tr>";
	  
	  $comment=nl2br($comment);
	  $instructions=nl2br($instructions);
	echo "<tr><td>Class Comments: </td></tr>
		<tr><td style='text-indent:15px;'>$comment</td></tr>
	<tr><td><br /></td></tr>
	<tr><td>Make sure the following info is correct:</td></tr>
	  <tr><td style='text-indent:15px;'>First Name: <b>$Fname</b></td></tr>
	  <tr><td style='text-indent:15px;'>Last Name: <b>$Lname</b></td></tr>
	  <tr><td style='text-indent:15px;'>Email: <b>$email</b></td></tr>
	  <tr><td style='text-indent:15px;'>TempID: <font color='blue'><b>$tempID  </b></font>  *(Used to track your completed training.)
		  </td></tr>";
		@$parkname=$parkCodeName[$currPark];
		if($currPark=="ARCH"){$parkname="Archdale";}
	echo "<tr><td style='text-indent:15px;'>Park Name: <b>$parkname</b></td></tr>
		<tr><td><br /></td></tr>
		  </table>";
 
	if(!isset($numDays)){$numDays="";}
	if(!empty($yourPark))
		{
		echo "<table><tr>";
	
		echo "<td colspan='2'>INSTRUCTIONS: (answer any questions/preferences in the space provided)<br />
		<font color='magenta'>$instructions</font></td></tr>";
	
		echo "<tr><td style='text-indent:15px;'><textarea name='signup_comment' cols='45' rows='4'></textarea></td>";
	
		echo "</tr><tr><td><br /></td></tr></table>";
	
	$location = addslashes($location);
		echo "<table><tr><td><font color='green'>Click to: 
		<input type='hidden' name='emid' value='$emid'>
	<input type='hidden' name='location' value=\"$location\">
		<input type='hidden' name='parkP' value='$parkP'>
		<input type='hidden' name='dateBegin' value='$dateBegin'>
		<input type='hidden' name='title' value='$titleU'>
		<input type='hidden' name='tid' value='$tid'>
		<input type='hidden' name='clid' value='$clid'>
		<input type='hidden' name='numDays' value='$numDays'>
		<input type='submit' name='Submit' value='Enroll'> Yourself</font>
		</form></td></tr>";
		if($level>1){echo "<tr><td>Click to Enroll some other DPR <a href='addPerson.php?tid=$tid&dateBegin=$dateBegin&Submit=Signup'>employee</a>.</td></tr>";}
		echo "</table><hr>";
		}
	// ********* non-DPR form ************
	$var_FN="";
	$var_LN="";
	if(!empty($Fname) and !empty($yourPark)){$var_FN=$Fname;}
	if(!empty($Lname) and !empty($yourPark)){$var_LN=$Lname;}
	$location=addslashes($location);
	echo "<table>
	<form method='post' action='signupClass.php'>
	<tr><td>For non-DPR participants or non-permanent employees use this section:</td></tr>
	<tr><td>First Name: <input type='text' name='fName' value=\"$var_FN\"></td></tr>
	<tr><td>Last Name: <input type='text' name='lName' value=\"$var_LN\"></td></tr>
	<tr><td>Phone or Email: <input type='text' name='contact' value=''> Needed if class is cancelled, rescheduled, etc.</td></tr>
	<tr><td>
	<input type='hidden' name='f' value='1'>
	<input type='hidden' name='type' value='nonDPR'>
	<input type='hidden' name='tid' value='$tid'>
	<input type='hidden' name='clid' value='$clid'>
	<input type='hidden' name='dateBegin' value='$dateBegin'>
	<input type='hidden' name='location' value='$location'>
	<input type='hidden' name='parkP' value='$parkP'>
	<input type='submit' name='Submit' value='Enroll'>
	</form></td><tr>
	</table></body></html>";
	exit;
	}

?>