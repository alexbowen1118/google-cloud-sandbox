<?php
ini_set('display_errors',1);
$database="dprcal";
include("../../include/auth.inc");
include("../../include/iConnect.inc");
mysqli_select_db($connection,$database);
include("nav.php");

extract($_REQUEST);
// **************** Form to Find Names *************
if($Submit == "Signup")
	{	
	$sql="SELECT signup.personID
	From signup
	LEFT JOIN train on signup.tid=train.tid
	where train.tid = '$tid'
	order by personID";
	
	$result2 = @mysqli_query($connection,$sql) or die("Error 1 $sql #". mysqli_errno($connection) . ": " . mysqli_error($connection));
	echo "<FONT color='orange'>Enrollees: ";
	while($row=mysqli_fetch_array($result2)){
	extract($row);
	/*
	include_once("../../include/connectDIVPER.62.inc");
	$sql1 = "SELECT empinfo.Nname,empinfo.Fname,empinfo.Lname,emplist.currPark
	From divper.empinfo
	LEFT JOIN emplist on empinfo.emid=emplist.emid
	WHERE empinfo.emid='$emid'";
	$result = @mysqli_query($sql1, $connection) or die("Error #". $sql1);
	$row1 = mysqli_fetch_array($result);
	extract ($row1);
	*/
	$pID=substr($personID,0,-2);
	echo "[$pID]&nbsp;&nbsp;&nbsp;";}
	echo "</font><br /><br />";
	
	$sql = "SELECT * From train WHERE tid=$tid";
	// echo "$sql"; exit;
	$result = @mysqli_query($connection,$sql) or die("Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
	$row=mysqli_fetch_array($result);extract($row);
	$letter=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
	
	echo"
	<form method='post' action='addPerson.php'><table>
	<tr><td>Add a Person to Class Roster for <font color='blue'>$title</font> @ $park</td></tr>
	<tr><td>1. Select Beginning Letter of Last Name --></td></tr></table><table>";
	
	for($i=0;$i<count($letter);$i++)
		{
		if(@$cap==$letter[$i])
			{
			$capC="checked";}else{$capC="";}
		echo "
		<td><input type='radio' name='cap' value='$letter[$i]' $capC>$letter[$i]</td>";
		}
	if(@$show=="all"){$showCY="checked";}else{$showCN="checked";}
	
	echo "</tr></table>
	<table><td>2. Click Refresh--> 
	<input type='hidden' name='title' value='$title'>
	<input type='hidden' name='park' value='$park'>
	<input type='hidden' name='tid' value='$tid'>
	<input type='hidden' name='clidGrandfather' value='$clid'>
	<input type='submit' name='Submit' value='Refresh'></td></tr>
	</table><hr><table>
	</form>";
	exit;
	}// end Submit

// ************ List Names *************
if($Submit == "Refresh")
	{
	mysqli_select_db($connection,'divper');
	echo "<form method='post' action='addPerson.php'><table><tr>Select person to add to class roster for <font color='blue'>$title</font> @ $park</td></tr>";
	$tempArrayEmid=array();
	
	if($cap){$where= "WHERE emplist.tempID LIKE '$cap%'";}
	$sql = "SELECT emplist.emid,empinfo.Fname,empinfo.Lname,emplist.tempID,emplist.currPark
	From divper.emplist
	LEFT JOIN empinfo on empinfo.tempID=emplist.tempID
	$where
	ORDER by empinfo.Lname,empinfo.Fname";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	//echo "$sql"; //exit;
	while ($row=mysqli_fetch_array($result)){
	extract($row);
	$tempEmid=$emid."~".$tempID;
	echo "<tr><td><input type='radio' name='tempEmid' value='$tempEmid'> $Lname, $Fname - $currPark</td></tr>";
		}
		echo "</tr></table>
	<table>
	<input type='hidden' name='tid' value='$tid'>
	<input type='submit' name='Submit' value='Add'></td></tr>
	</table><hr><table>
	</form>";
	}

// ************ Add Name *************
if($Submit == "Add")
	{
	$list=explode("~",$tempEmid);
	$query = "INSERT signup SET tid='$tid', emid='$list[0]',personID='$list[1]'";
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query. $query");
	header("Location: findTrain.php?tid=$tid&Submit=Search");
	}
?>