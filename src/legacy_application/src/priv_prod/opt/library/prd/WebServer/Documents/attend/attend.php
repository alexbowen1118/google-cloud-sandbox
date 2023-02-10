<?php
ini_set('display_errors',1);

extract($_REQUEST);
$database=$db;
include("../../include/auth.inc");
// echo "<pre>";print_r($_SESSION);echo "</pre>";    //EXIT;
$db="park_use";
include("../../include/iConnect.inc");
// include("../no_inject.php");

mysqli_select_db($connection,'divper');
$sql = "SELECT $db as level,emplist.currPark,empinfo.Nname,empinfo.Fname,empinfo.Lname,position.posTitle,emplist.accessPark
FROM emplist 
LEFT JOIN empinfo on empinfo.tempID=emplist.tempID
LEFT JOIN position on position.posNum=emplist.posNum
WHERE emplist.tempID = '$tempID'";
//if($tempID=="Jackson5451"){echo "$sql";exit;}
$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
$num = @mysqli_num_rows($result);
if($num<1)
	{
	$sql = "SELECT $db as level, tempID,currPark FROM nondpr
	WHERE tempID='$tempID'";
	$result2 = mysqli_query($connection,$sql) or die("Couldn't execute query.");
	$row = mysqli_fetch_array($result2);
	$num2 = mysqli_num_rows($result2);
	extract($row);
	if($num2<1)
		{
		echo "Access denied $sql";exit;
		}
	}
$row=mysqli_fetch_array($result);
// echo "<pre>"; print_r($row); echo "</pre>";  exit;
extract($row);

if($level>1)
	{
	$_SESSION[$db]['level'] = $level;
	$_SESSION[$db]['tempID'] = $tempID;
	$_SESSION[$db]['select']=$currPark;
	}
else
	{
	//$level=0; // used to prevent most users from gaining access
	
	$_SESSION[$db]['tempID'] = $tempID;
	$_SESSION[$db]['select']=$currPark;$level=1;
	$_SESSION[$db]['level'] = $level;
	$_SESSION[$db]['accessPark'] = @$accessPark;
	
	//if($tempID=="huband1234"){$level = '3';
	//$_SESSION[$db]['level'] = $level;}
	
	$posTitle=strtolower($posTitle);
	$suptString="superintendent";
	$posSupt=strpos($posTitle,$suptString);
	if($posSupt !== false)
		{
		$level=1;
		$_SESSION[$db]['level'] = $level;
		}
	
	$oaString="office assistant";
	$posOA=strpos($posTitle,$oaString);
	if($posOA !== false)
		{
		$level=1;
		$_SESSION[$db]['level'] = $level;
		}
	
	$paString="processing assistant";
	$posPA=strpos($posTitle,$paString);
	if($posPA !== false)
		{
		$level=1;
		$_SESSION[$db]['level'] = $level;
		}
	
	$dsString="district superintendent";
	$posDS=strpos($posTitle,$dsString);
	if($posDS !== false)
		{
		$level=2;
		$_SESSION[$db]['level'] = $level;
		}
	
	$posTitle=strtolower($posTitle);
	$suptString="superintendent";
	$posSupt=strpos($posTitle,$suptString);
	if($posSupt !== false)
		{
		$level=1;
		$_SESSION[$db]['level'] = $level;
		}
	
	
	}// end level = 1

if($level<1){echo "You do not have access privileges for this database [$db]. Contact Tom Howard tom.howard@ncdenr.gov if you wish to gain access.<br><br>attend.php";exit;}

$database=$db;

//echo "<pre>";print_r($_SESSION);echo "</pre>p=$posOA $posTitle";EXIT;
$page="/attend/a/form_day.php";
header("Location: $page");

?>
