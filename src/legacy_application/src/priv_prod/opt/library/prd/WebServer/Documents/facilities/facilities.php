<?php
 $userAddress = $_SERVER['REMOTE_ADDR']; //echo"u=$source"; 
//print_r($_REQUEST);
//echo "<pre>"; print_r($_SERVER); echo "</pre>"; // exit;

// called from Secure Server login.php
//if(empty($_SERVER['HTTP_COOKIE'])){exit;}

date_default_timezone_set('America/New_York');
$database="facilities"; 
$dbName="facilities";
include("../../include/auth.inc");
include("../../include/iConnect.inc");
extract($_REQUEST);

mysqli_select_db($connection,"divper");
$sql = "SELECT $dbName as level,emplist.tempID,emplist.currPark,accessPark,t2.working_title, concat(t3.Fname,' ',t3.Mname,' ',t3.Lname) as full_name, t2.beacon_num
FROM emplist 
LEFT JOIN position as t2 on t2.beacon_num=emplist.beacon_num
LEFT JOIN empinfo as t3 on t3.tempID=emplist.tempID
WHERE emplist.emid = '$emid' AND emplist.tempID='$tempID'";
$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
$num = @mysqli_num_rows($result);
if($num<1)
	{
	$sql = "SELECT $dbName as level,nondpr.currPark,nondpr.Fname,nondpr.Lname
	FROM nondpr 
	WHERE nondpr.tempID = '$tempID'";
	$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
	$num = @mysqli_num_rows($result);
	if($num<1){echo "Access denied";exit;}
	}
$row=mysqli_fetch_array($result);
//print_r($row);EXIT;
extract($row);

$dist2region=array("WEDI"=>"MORE","NODI"=>"PIRE","SODI"=>"PIRE","EADI"=>"CORE");
$_SESSION[$dbName]['level'] = $level;
$_SESSION[$dbName]['tempID'] = $tempID;
IF(array_key_exists($currPark, $dist2region))
	{
	$currPark=$dist2region[$currPark];
	}
$_SESSION[$dbName]['select'] = $currPark;
$_SESSION[$dbName]['accessPark'] = $accessPark;
$_SESSION[$dbName]['working_title'] = $working_title;
$_SESSION[$dbName]['full_name'] = $full_name;
$_SESSION[$dbName]['beacon_num'] = $beacon_num;

// $today = date("Y-m-d H:i:s");
//            $sql = "INSERT INTO $dbName.login (loginName,loginTime,userAddress,level)
//                    VALUES ('$tempID','$today','$userAddress','$level')";
//            mysqli_query($connection,$sql) or die("Can't execute query 3. $sql");
//            
header("Location: home.php");
?>
