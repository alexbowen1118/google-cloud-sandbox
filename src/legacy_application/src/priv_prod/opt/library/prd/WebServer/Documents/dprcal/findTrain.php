<?php
ini_set('display_errors',1);
$database="dprcal";
include("../../include/auth.inc");
include("../../include/iConnect.inc");
mysqli_select_db($connection,$database);
extract($_REQUEST);

if(@$rep==""){include("nav.php");}
//echo "<pre>";print_r($_REQUEST);echo "</pre>";//exit;

if(@$Submit == "Search")
	{
	if(@$tid)
		{
		$select="SELECT DISTINCT signup.tid
		From signup
		LEFT JOIN train on signup.tid=train.tid";
		$var1= "train.tid = '$tid'";
		$order="order by tid";
		}
		else
		{
		if ($signupID != "")
		{
		$select="SELECT title,tid,clid From train";
		$var1="trainID LIKE '%$signupID%' and dateFind LIKE '$yearClass%'";
		$order="order by tid";
		if(@$s){$d=date("H:i:s");
		$comment="<font color='red'>Updated</font> at $d";}
		$passVar=$signupID;
		}
	}
	
	if(!isset($order)){$order="";}
	if(!isset($var1)){$var1="";}
	if(!isset($select)){$select="";}
	@$varQuery="rep=excel&tid=$tid&Submit=Search";
	$sql = "$select WHERE $var1 $order";
	$result2 = @mysqli_query($connection,$sql) or die("Error 1 $sql #". mysqli_errno($connection) . ": " . mysqli_error($connection));
	$total_found2 = @mysqli_num_rows($result2);
//	echo "$sql t=$total_found2"; exit;
	
	if($total_found2 < 1)
		{
		if(!isset($testCat)){$testCat="";}
		if(!isset($dateFind)){$dateFind="";}
		if(!isset($tid)){$tid="";}
		echo "<h2>No enrollees found for that class.</h2>
		<a href='addPerson.php?cat=$testCat&tid=$tid&dateBegin=$dateFind&Submit=Signup'>Add
		</a>";exit;
		}
	
	if($total_found2 == 1)
		{
		$row=mysqli_fetch_array($result2);extract($row);
		$var1= "signup.tid='$tid'";
		$sql = "SELECT title,supid,dateFind,train.park as parkT,trainID,dateClass, enter_by,train.tid, completed,train.activity,emid,personID, signup.contact, signup.signup_comment
		From signup
		LEFT JOIN train on signup.tid=train.tid
		WHERE $var1 order by train.tid,lName";
		//echo "<br>$sql";
		$result3 = @mysqli_query($connection,$sql) or die("Error 2#". mysqli_errno($connection) . ": " . mysqli_error($connection));
		$total_found3 = @mysqli_num_rows($result3);
		// script continues below
		}
	///*
	if($total_found2 > 1)
		{
		$row=mysqli_fetch_array($result2);extract($row);
		
		$sql = "SELECT title,train.park as parkT,trainID,train.tid,dateFind
		From train
		WHERE $var1 order by train.tid";
		//echo "<br>2 $sql";// exit;
		$result = @mysqli_query($connection,$sql) or die("Error 3# $sql". mysqli_errno($connection) . ": " . mysqli_error($connection));
		
		if(!isset($comment)){$comment="";}
		echo "<html><head><title></title></head>
		<body><form method='post' action='signupTrain.php'>
		Class Completion Status $comment for:<table>";
		
		while ($row = mysqli_fetch_array($result))
		{
		extract($row);
		if($parkT){$parkP=" at $parkT";}else{$parkP=" not at a Park";}
		if(@$classCheck!=$tid)
			{
			if(!isset($classGone)){$classGone="";}
			$classCheck="<td colspan='6'><hr><b>$title $dateFind $classGone $parkP</b></td><td>&nbsp;&nbsp;&nbsp;<a href='findTrain.php?tid=$tid&Submit=Search'>View
			</a></td></tr>";}else{$classCheck="";}
		$line = "<tr>$classCheck</tr>";
		echo "$line";
		$classCheck = $tid;
		}
		echo "</form></table>";
		exit;
		}
	//*/
	
	// continuation of $result3
	if(!isset($comment)){$comment="";}
	if(@$rep==""){echo "<html><head><title></title></head>
	<body><form method='post' action='signupTrain.php'>
	<font color='blue'>Completion Status $comment for</font>:";}
	if(@$rep=="excel"){header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=signup_sheet.xls');
	}
	
	echo "<table>";
	
	$i=0;
	while ($row = mysqli_fetch_array($result3))
		{
		$i = $i+1;
		extract($row);  //echo "<pre>"; print_r($row); echo "</pre>"; // exit;
		if($parkT){$parkP=" at $parkT";}
		
		if ($completed == 'y'){
		$link = "
		<input type='radio' name='$supid' value='y' checked>Yes
		<input type='radio' name='$supid' value='n' >No";
		}
		else
		{
		$link = "
		<input type='radio' name='$supid' value='y'>Yes
		<input type='radio' name='$supid' value='n' checked>No";
		}// end if $completed
		
		if ($activity == " EE Certification"){$testCat="cert";}
		//echo "c=$testCat ($activity)"; exit;
		
		if(@$classCheck!=$tid)
			{
			$i=1;
			if(!isset($classGone)){$classGone="";}
			if(!isset($parkP)){$parkP="";}
			$classCheck="</tr><tr><td colspan='6'><b>$title $dateFind $classGone $parkP - $enter_by</b></td></tr>";
			
			if(@$rep=="")
				{
				@$classCheck.="<tr><td>
				<a href='addPerson.php?cat=$testCat&tid=$tid&dateBegin=$dateFind&Submit=Signup'>Add</a> a DPR employee</td><td><a href='addPersonNonDPR.php?cat=$testCat&tid=$tid&dateBegin=$dateFind&Submit=Signup'>Add</a> a nonDPR person</td><td><a href='findTrain.php?$varQuery'>Excel</a></td></tr></table><hr>";
				}
			@$classCheck.="<table><tr><td></td>";
			}
		ELSE
			{
			$classCheck="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			}
		
	mysqli_select_db($connection,'divper');
		if(@$rep=="")
			{
			$del="<td><a href='signupDelete.php?tid=$tid&supid=$supid&Submit=Delete'> Delete</a></td>";
			}
		else
			{
			$link="";
			$del="";
			}
		
		$sql1 = "SELECT empinfo.Nname,empinfo.Fname,empinfo.Lname,emplist.currPark
		From divper.empinfo
		LEFT JOIN emplist on empinfo.emid=emplist.emid
		WHERE empinfo.emid='$emid'";
		$result = @mysqli_query($connection,$sql1) or die("Error #". $sql1);
		$row1 = mysqli_fetch_array($result);
		@extract ($row1);
		if(@$Nname){$Fname=$Nname;}
		if($emid<1)
			{
			$exp=explode("-",$personID);
			$Fname=$exp[1];
			$Lname=$exp[0];
			$sql1 = "SELECT tempID, Fname, Lname, currPark
		From divper.nondpr
		WHERE Fname='$Fname' and Lname='$Lname'";
		$result = @mysqli_query($connection,$sql1) or die("Error #". $sql1);
		$row_name = mysqli_fetch_array($result);
		@extract ($row_name);
		
			$type=" (non-DPR)";
			$line = "<tr>$classCheck<td>$i</td><td>$personID $type</td><td>$contact</td><td></td>";
			if($level>1)
				{
				$line.="<td>$signup_comment";
				}
			$line.="</td>$del
			<td>$Fname $Lname</td></tr>";
			}
			else
			{
			$line = "<tr>$classCheck<td>$i</td><td>$Fname $Lname</td><td>$currPark</td><td>$link</td>";
			if($level>1)
				{
				$line.="<td>$signup_comment - <a href='edit_signup_comment.php?supid=$supid' target='_blank'>edit</a>";
				}
			$line.="</td>$del
			</tr>";
			}
		echo "$line";
		
		$classCheck = $tid;
		}
	if(@$dupe==1){$dupe="A person with that name has already enrolled for this class.";}
	echo "</table>";
	if(@$rep=="")
		{
		if(!isset($passVar)){$passVar="";}
		echo "<input type='hidden' name='tid' value='$tid'>
		<input type='hidden' name='signupID' value='$passVar'>
		<input type='submit' name='submit' value='Update Completion Status'></form><hr>";
		}
	echo "</body></html>";
	if(@$rep==""){include("nav.php");}
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
<form method="get" action="findTrain.php">

<?php
if(@$yearClass)
{}
else
{
$yearClass=date("Y");}

echo "<table width='100%' cellpadding='7'>
<tr><td><b>Choose the Year of Class:</b><input type='text' name='yearClass' value='$yearClass' size='7'></td></tr>
    <tr><td><b>Class Title:</b>
        <input type='text' name='signupID' size='25' maxlength='50'> Any word or phrase from the title.
      </td></tr>
      <tr><td>A list of all enrollees will be returned.</td></tr></table>
<table width='100%' cellpadding='7'><tr><td><input type='submit' name='Submit' value='Search'></td>
   </tr></table>
</form>
</body>
</html>";
?>

