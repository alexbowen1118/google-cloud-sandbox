<?php

date_default_timezone_set('America/New_York');
$database="dprcal";
include("../../include/auth_i.inc");
$level=$_SESSION[$database]['level'];
if($level>3)
	{ini_set('display_errors',1);}
include("../../include/iConnect.inc");
mysqli_select_db($connection,$database);
extract ($_REQUEST);

//print_r($_SESSION);//EXIT;
//session_start();
if(@$Submit == "Search")
	{
	
	if ($tid != "")
		{
		if(!is_numeric($tid)){exit;}
		$var1 = "(tid = $tid)";
		$var2 = "(tid = $tid)";
		}
	
	$sql = "SELECT clid,dateBegin,dateEnd,park as parkP From train WHERE `tid` = '$tid'";
// 	if($level>3){ echo "$sql";}
	
	$result1 = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	$row1 = mysqli_fetch_array($result1);
	extract($row1);
	$numDays = ((strtotime($dateEnd) - strtotime($dateBegin)) / (60 * 60 * 24)) + 1;
	
	if($numDays>1)
		{
		// Get the tid, or tids, for class - used to signup for multi-day classes
		$sql1 = "SELECT tid as multi_tid From train WHERE `clid` = '$clid' and tid='$tid' and `dateBegin`='$dateBegin' and park='$parkP'";
		$result1 = mysqli_query($connection,$sql1) or die ("Couldn't execute query. $sql1");
		while($row1 = mysqli_fetch_array($result1))
			{
			extract($row1);
			$tidArray[]=$multi_tid;
			}
		}
		else
		{$tidArray="";}
	
	if($level>3)
		{
// 		 echo "<br />$sql1";
// 		 echo "<pre>"; print_r($tidArray); echo "</pre>"; // exit;
		 }
	// Get Location of Training
	$sql = "SELECT * From train WHERE $var1";
	echo "$sql<br>";
	$total_result = @mysqli_query($connection,$sql) or die("Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
	$total_found = @mysqli_num_rows($total_result);
	if($total_found < 1){$classGone = "<font size='4' color='660000'>Class was deleted. Remove all enrollees.</font>";}
	$row = mysqli_fetch_array($total_result);
	extract($row);
	$parkLoc=$park;
	if(empty($parkLoc))
		{
		$parkLoc=$location;
		}
	
	if($tidArray)
		{
		$var2="";
		$join="LEFT JOIN train on train.tid=signup.tid";
		$flds=", dateFind";
		foreach($tidArray as $k=>$v)
			{
			$var2.=" signup.tid='$v' OR";
			}
		$var2=trim($var2," OR");
		}
		else
		{
		$join="";
		$flds="";
		}
	
	// Get info from signup
	$sql = "SELECT signup.* $flds From signup
	$join
	WHERE $var2
	order by signup.tid,personID";
	
	//echo "$sql<br>"; //exit;
	
	$total_result = @mysqli_query($connection,$sql) or die("Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
	while ($row = mysqli_fetch_array($total_result))
		{
		extract($row);
		$email=$contact;
		$arrayPerson[]=$emid."~".$park."~".$fName." ".$lName."~".$email."~".$supid."~".$personID."~".$dateBegin."~".$tid."~".$dateFind."~".$sup_approval."~".$ta_submitted;
		
		}
	//echo "<pre>";print_r($arrayPerson);echo "</pre>";exit;
	$count=@count($arrayPerson);
	
	echo "<html><head><title></title><script language='JavaScript'>
	
	function confirmLink()
	{
	 bConfirm=confirm('Are you sure you want to delete this record?')
	 return (bConfirm);
	}
	
	</script></head>";
	
	if($tidArray){$dateClass="from $dateBegin to $dateEnd";}
	
	if($tidArray){
	$thisPerson="this person ONLY for this day of multi-day class";
	$passMultiDay="&md=1";
	}else{$thisPerson="this person";}
	
	echo "<body><h2>Enrollees for $title $dateFind at $parkLoc: </h2><table border='1' cellpadding='5'>
	<tr><th>Class Date</th><th>Enrollee</th><th>Park</th><th>Supervisor Approval</th><th>TA Submitted</th><th colspan='2'>Contact</th></tr>";
	
	//print_r($arrayPerson);
	//$j=1;
	$all_email="";
	for($i=0;$i<$count;$i++)
		{
		$person=explode("~",$arrayPerson[$i]);
		$dateBegin=$person[6];
		$tid=$person[7];
		$dateFind=$person[8];
		$sup_approval=$person[9];
		$ta_submitted=$person[10];
		$con="<font color='red'>$person[3]</font>";
// 		echo "<pre>";print_r($person);echo "</pre>";//exit;
		
		mysqli_select_db($connection,'divper');
		$sql = "SELECT empinfo.Nname,empinfo.Fname,empinfo.Lname,emplist.currPark,empinfo.email
		From divper.empinfo 
		LEFT JOIN emplist on empinfo.tempID=emplist.tempID
		WHERE empinfo.emid='$person[0]'";
		//echo "$sql<br>";
		//WHERE empinfo.tempID='$person[0]'";
		$total_result = @mysqli_query($connection,$sql) or die("Error #". $sql);
		$numFound=mysqli_num_rows($total_result);
		if($numFound>0)
			{
			// Obtain info about DPR folks
			$row = mysqli_fetch_array($total_result);
			extract ($row);   //echo "<pre>";print_r($row);echo "</pre>";//exit;
			if($Fname!="")
				{
				if($Nname){$Fname=$Nname;}
				$fullName=$Fname." ".$Lname;}else{
			$fullName=$Nname." ".$Lname." - ".$currPark;}
			$atPark=$row['currPark'];
			$atPark=$currPark;
			//echo "$atPark<br>";
			$Fname="";
			if(@$parkT){$parkP=" at $parkT";}
			
			
			if(!isset($passMultiDay)){$passMultiDay="";}
			if($_SESSION['dprcal']['levelS']=="SUPERADMIN"||$_SESSION['dprcal']['levelS']=="ADMIN"){$link = "<a href='signupDelete.php?tid=$tid&supid=$person[4]$passMultiDay' onClick='return confirmLink()'>Delete</a> $thisPerson.";}else{$link="";}
			}
		else
			{
			// Obtain info about nonDPR folks
			if($person[2]!="")
				{$fullName=$person[5];}
				else
				{$fullName=$person[2];}
			
			$atPark=$person[1];
			$email=$person[3];
			if($_SESSION['dprcal']['levelS']=="SUPERADMIN"||$_SESSION['dprcal']['levelS']=="DIST"||$_SESSION['dprcal']['levelS']=="ADMIN"||$park=="nonDPR")
				{
				@$link = "<a href='signupDelete.php?tid=$tid&supid=$person[4]$passMultiDay' onClick='return confirmLink()'>Delete</a> $thisPerson.";}
				else
				{$link="";}
			}
		
		if(@$classCheck!=$signupID){$classCheck="<br>$class $dateClass $classGone $parkP<br>";}ELSE{$classCheck="";}
		
		if(@$ckTid!=$tid AND $i!=0){echo "<tr><td><font color='green'>$j enrollees</font></td></tr>";
		$j="";}
		@$j++;
		
		$line = "<td align='right'>$j - $dateFind</td>
		<td>".$fullName."</td>
		<td>".$atPark."</td>
		<td>".$sup_approval."</td>
		<td>".$ta_submitted."</td>";
		if($person[3]=="")
			{
			if(!isset($email)){$email="";}
			$line.="<td>".$email."</td>";
			$all_email.=$email.",";
			}
			else
			{
			$line.="<td></td>";
			$all_email.=$person[3].",";
			}
		
		$line.="<td>".$con."</td>
		<td>".$link;
		
		echo "<tr>$line</td></tr>";
		
		$classCheck = $signupID;
		$ckTid = $tid;
		}
	if(@$dupe==1)
		{
		$dupe="A person with that name has already enrolled for this class.";
		}
		else
		{$dupe="";}
	if(empty($j)){$j=0;}
	echo "
	<tr><td><font color='green'>$j enrollees</font></td>";
	if(!empty($j))
		{
		echo "<td>Email all <a href='mailto:$all_email?subject=$title $dateClass at $parkLoc'>enrollees</a></td></tr>";
		}
	echo "</table><hr>
	Click your browser's BACK button to return to class info.
	$dupe</body></html>";
	//include("nav.php");
	exit;
	}

$sql = "SELECT * From signup";

$total_result = @mysqli_query($connection,$sql) or die("Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
$total_found = @mysqli_num_rows($total_result);
if($total_found < 1){echo "No one has enrolled in any class.";
include("nav.php");
exit;}
?>

<html><head><title>Find Enrollees</title></head>
<body><p><font size="5" font color="#004201"> NC DPR Training Calendar</font></p>
<p>Enter Class Title:</p>
<form method="get" action="findSignup.php">

<table width="100%" cellpadding="7">
    <tr><td><b>Class Title:</b>
        <input type="text" name="signupID" size="25" maxlength="50"> Any word or phrase from the title.
      </td></tr>
      <tr><td>A list of all enrollees will be returned.</td></tr></table>
<table width="100%" cellpadding="7"><tr><td><input type="submit" name="Submit" value="Search"></td>
   </tr></table>
</form>
</body>
</html>

