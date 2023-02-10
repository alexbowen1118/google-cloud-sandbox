<?php
 $userAddress = $_SERVER['REMOTE_ADDR']; //echo"u=$source"; 
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  exit;
//echo "<pre>"; print_r($_SERVER); echo "</pre>"; // exit;
// 
// session_start();
// echo "<pre>"; print_r($_SESSION); echo "</pre>";  exit;

date_default_timezone_set('America/New_York');
$database="fuel"; 
$dbName="fuel";
include("../../include/auth_i.inc");
include("../../include/iConnect.inc");


if(empty($_SESSION[$database]))
	{
	mysqli_select_db($connection,"divper");
	$sql = "SELECT $dbName as level,emplist.tempID,emplist.currPark,accessPark,t2.working_title, concat(t3.Fname,' ',t3.Mname,' ',t3.Lname) as full_name, t2.program_code, t2.beacon_num, emplist.emid
	FROM emplist 
	LEFT JOIN position as t2 on t2.beacon_num=emplist.beacon_num
	LEFT JOIN empinfo as t3 on t3.tempID=emplist.tempID
	WHERE emplist.emid = '$emid' AND emplist.tempID='$tempID'";
// 	echo "$sql"; exit;
	$result = @mysqli_query($connection,$sql) or die("$sql Error 1#");
	$num = @mysqli_num_rows($result);
	if($num<1)
		{
		$sql = "SELECT $dbName as level,nondpr.currPark,nondpr.Fname,nondpr.Lname
		FROM nondpr 
		WHERE nondpr.tempID = '$tempID'";
		$result = @mysqli_query($connection,$sql) or die("$sql Error 1#");
		$num = @mysqli_num_rows($result);
		if($num<1){echo "Access denied";exit;}
		}
	$row=mysqli_fetch_array($result);
// 	echo "<pre>"; print_r($row); echo "</pre>";  //exit;
	extract($row);

	$_SESSION[$dbName]['level'] = $level;
	$_SESSION[$dbName]['tempID'] = $tempID;
	IF(!empty($currPark)){$_SESSION[$dbName]['select'] = $currPark;}
	if($currPark=="ARCH")
	{
	IF(!empty($program_code)){$_SESSION[$dbName]['select'] = $program_code;}
	}

	$_SESSION[$dbName]['accessPark'] = @$accessPark;
	if($beacon_num=="60032881")  //Radio Engineer I
		{
		$_SESSION[$dbName]['accessPark'] = "RALE";
		}
	$_SESSION[$dbName]['working_title'] = $working_title;
	$_SESSION[$dbName]['full_name'] = $full_name;
	$_SESSION[$dbName]['beacon_num'] = $beacon_num;
	$_SESSION[$dbName]['emid'] = $emid;

// 	echo "<pre>"; print_r($_SESSION); echo "</pre>";  exit;

	$today = date("Y-m-d H:i:s");
		   $sql = "INSERT INTO $dbName.login (loginName,loginTime,userAddress,level)
				   VALUES ('$tempID','$today','$userAddress','$level')";
		   mysqli_query($connection,$sql) or die("Can't execute query 3.");
	}
	else
	{
	// workaround to allow SODI access to NODI during conversion to Regions
	// special login in dpr_login.php
	$_SESSION[$dbName]['tempID'] = $_SESSION['logname'];
	$_SESSION[$dbName]['working_title'] = $_SESSION['position'];
	$_SESSION[$dbName]['full_name'] = $_SESSION['full_name'];
	$_SESSION[$dbName]['beacon_num'] = $_SESSION['beacon_num'];
	$_SESSION[$dbName]['emid'] = $_SESSION['logemid'];
	
	}
             
		if($_SESSION['fuel']['beacon_num']=="60033019") // Parks District Superintendent SODI - J. Greenwood
				{$_SESSION['fuel']['select']="SODI";}             
		if($_SESSION['fuel']['beacon_num']=="60033135") // Parks District I&E Spec. NODI - B. Bockhahn
				{$_SESSION['fuel']['select']="INED";}             
		if($_SESSION['fuel']['beacon_num']=="60032907") // Parks District I&E Spec. SODI - B. Hurtado
				{$_SESSION['fuel']['select']="INED";}             
				
		if($_SESSION['fuel']['beacon_num']=="60032913") // Parks District Superintendent WEDI - S. McElhone
			{$_SESSION['fuel']['select']="WEDI";}         	
		if($_SESSION['fuel']['beacon_num']=="60032875") // Parks District I&E Spec. WEDI - S. Becker
			{$_SESSION['fuel']['select']="WEDI";}         
			      
		if($_SESSION['fuel']['beacon_num']=="60032912") // Parks District Superintendent EADI - J. Fullwood
				{$_SESSION['fuel']['select']="EADI";}      
				
		if($_SESSION['fuel']['beacon_num']=="65030652") // Parks District Superintendent NODI - K. Woodruff
				{$_SESSION['fuel']['select']="NODI";}          
	
		if($_SESSION['fuel']['beacon_num']=="60032780") //Interpretation & Education Manager  - S. Higgins
				{$_SESSION['fuel']['select']="INED";}
		if($_SESSION['fuel']['beacon_num']=="60091483") // Inventory Biologist - E. Corey
				{$_SESSION['fuel']['select']="REMA";}
		if($_SESSION['fuel']['beacon_num']=="65020685") // Environmental Specialist - J. Short
				{$_SESSION['fuel']['select']="REMA";}
		if($_SESSION['fuel']['beacon_num']=="65027685") // Environmental Specialist - T. Crate
				{$_SESSION['fuel']['select']="REMA";}
		if($_SESSION['fuel']['beacon_num']=="60032828") // Environmental Senior Spec - J. Blanchard
				{$_SESSION['fuel']['select']="REMA";}         
		if($_SESSION['fuel']['beacon_num']=="60032943") // Environmental Spec II - J. Sasser
				{$_SESSION['fuel']['select']="REMA";}             
		if($_SESSION['fuel']['beacon_num']=="65027686") // Environmental Senior Specialist - J. Dodson
				{$_SESSION['fuel']['select']="REMA";}
				    
		 // Environmental Senior Specialist - C.  Farrell
		 if($_SESSION['fuel']['beacon_num']=="60092633")
				{$_SESSION['fuel']['select']="REMA";}
				       
		if($_SESSION['fuel']['beacon_num']=="60032832") // Environmental Senior Specialist - A. Slack
				{$_SESSION['fuel']['select']="REMA";}         
		
		if($_SESSION['fuel']['beacon_num']=="60032881")  //Radio Engineer I
			{
			$_SESSION['fuel']['accessPark'] = "RALE";
			$_SESSION['fuel']['select'] = "RALE";
			}
		if($_SESSION['fuel']['beacon_num']=="60032784")  //OA to Director
			{
			$_SESSION['fuel']['accessPark'] = "OPAD";
			$_SESSION['fuel']['select'] = "OPAD";
			}
				
header("Location: menu.php");
?>
