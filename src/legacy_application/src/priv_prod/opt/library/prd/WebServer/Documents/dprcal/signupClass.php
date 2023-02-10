<?php
// echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;
$database="dprcal";
include("../../include/auth_i.inc");
$level=$_SESSION[$database]['level'];
if($level>0)
	{ini_set('display_errors',1);}
include("../../include/iConnect.inc");

// echo "<pre>"; print_r($_REQUEST); echo "</pre>";  exit;

extract($_REQUEST);
$tempID=$_SESSION['dprcal']['loginS'];
mysqli_select_db($connection,'divper');

if(!is_numeric($tid)){exit;}
if(!is_numeric($clid)){exit;}
if(!empty($emid) and !is_numeric($emid)){exit;}

$exp=explode("-",$dateBegin);
$var=checkdate($exp[1],$exp[2],$exp[0]);

if($var!=1)
	{EXIT;}

@$tempEMID=$emid; // rename $emid so it doesn't get overwritten in query of signup table

 if (@$supervisor == "" AND @$f != 1)
 	{
 	echo "You cannot register for a class without Supervisor Approval. Click your BACK button and indicate approval if training has been authorized."; exit;
 	}

@$title = stripslashes(urldecode($title));
//echo "$title"; exit;
// *****Show one or more people with that Last Name
if(!isset($f))
	{
	$sql = "SELECT tempID,Nname,Fname,Mname,Lname,emid
	FROM divper.empinfo
	WHERE tempID = '$tempID'";
	//WHERE tempID = '$_SESSION[logname]'
	//echo "$sql"; 
	$result = @mysqli_query($connection,$sql) or die("$sql Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
	$total_found = @mysqli_num_rows($result);
	if($total_found > 1)
		{
		while ($row = mysqli_fetch_array($result))
			{
			@$i = $i+1;
			extract($row);
			if($Nname){$Fname=$Fname." ($Nname) ";}
			$link = "Click <a href='signupClass.php?personID=$tempID&emid=$emid&tid=$tid&clid=$clid&dateBegin=$dateBegin&f=1&parkP=$parkP'>Add</a>";
			echo "$link: to place $Fname $Mname $Lname on the roster for <h2>$title</h2> <h2>$dateBegin at $parkP</h2><br><br>";
			}// end while
		exit;
		}// end if $total
	$row = mysqli_fetch_array($result);
	extract($row);
	$personID=$tempID;
	$f=1;
	}// end if $f

if($f==1)
	{
	// ************** check for duplicate entry
	if(@$type=="nonDPR"){$personID=$lName."-".$fName;}
mysqli_select_db($connection,$database);
	$find = "tid = '$tid' and personID='$personID'";
	$sql = "SELECT * From dprcal.signup WHERE $find";
	//echo "$sql"; exit;
	$total_result = @mysqli_query($connection,$sql) or die("$sql Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
	$total_found = @mysqli_num_rows($total_result);
	
	if($total_found > 0)
		{
		//header("Location:findSignup.php?tid=$tid&Submit=Search&dupe=1");
		echo "$personID is already signed up for this class.";
		exit;
		}

	
	include("nav.php");
	echo "<html>
	<head>
	<title>NC DPR Calendar - New Class</title>
	</head>
	<body>";
	
// 	echo "<pre>"; print_r($_POST); echo "</pre>"; // exit;
	// Get the tid, or tids, for class - used to signup for multi-day classes
// 	$location=addslashes($location);
	$sql1 = "SELECT tid as multi_tid, startTime From train WHERE `clid` = '$clid' and `dateBegin`='$dateBegin' and park='$parkP' and location='$location'";
	
// 	echo "$sql1<br />";//exit;
	if(@$type=="nonDPR"){$parkP="nonDPR";}
	
	$result1 = mysqli_query($connection,$sql1) or die ("97 Couldn't execute query. $sql1");
	while($row1 = mysqli_fetch_array($result1))
	{
	extract($row1);
	
	$tidArray[]=$multi_tid;
	$var_time[$multi_tid]=$startTime;
	}
	
// 	print_r($var_time);
// 	print_r($tidArray);exit;
	if(empty($tidArray))
		{
		echo "The signup was not successful. Please contact database.support@ncparks.gov and include the following informagion.<br /><br />$sql1<br /><br />$sql"; exit;
		}
	@$signup_comment=addslashes($signup_comment);
	for($i=0;$i<count($tidArray);$i++)
		{
		$tidSingle=$tidArray[$i];
		@$query = "INSERT INTO signup (dateClass, personID, tid, clid, contact,park,emid,signup_comment,ta_submitted,sup_approval) VALUES ('$dateBegin','$personID','$tidSingle', '$clid','$contact','$parkP','$tempEMID','$signup_comment','$ta_submitted','y')";
// 	echo "$query<br>"; exit;
		$result = mysqli_query($connection,$query) or die ("Couldn't execute query. $query");
		}
	//exit;
	
	$find = "tid = '$tid'";
	$sql = "SELECT * From dprcal.train WHERE $find";
	//echo "$sql"; exit;
	$total_result = @mysqli_query($connection,$sql) or die("$sql Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
	$row = mysqli_fetch_array($total_result);
	extract($row);
	
	echo "<h2>$personID has been successfully enrolled in $title at $park starting $dateBegin.<br><br>If you haven't already printed out the class info, click on the button below to return to the class info page and print a copy for your records.</h2>
	<form method='post' action='trainDetail.php'>
	<input type='hidden' name='tid' value='$tid'>
	<input type='submit' name='Submit' value='Class Info'>
	</form><br>";
	}
?> 
</body>
</html>
