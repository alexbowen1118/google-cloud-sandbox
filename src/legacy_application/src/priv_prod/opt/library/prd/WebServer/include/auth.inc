<?php
$session_db=$database; //echo "d=$database";
if(!isset($_SESSION)){session_start();}


// echo "auth - $session_db<pre>";print_r($_SESSION);echo "</pre>";   exit;
//if($_REQUEST['tempID']=="McBean0615"){exit;}
if(!empty($_REQUEST['code_id'])){$tempID=str_rot13($_REQUEST['code_id']);}
if(@$_SESSION[$session_db]['level']<1)
	{
//echo "auth - $session_db<pre>";print_r($_SESSION);echo "</pre>";   exit;
	include("iConnect.inc");
	mysqli_select_db($connection,'divper');
	if(isset($_SESSION['tempID']))
		{
		$ftempID=$_SESSION['tempID'];
		}
		else
		{
		extract($_REQUEST);
		@$ftempID=$_SESSION['logname'];
		if(!isset($_SESSION['logname']))
			{
			@$ftempID=$tempID;
			if(empty($ftempID)){@$ftempID=$_SESSION[$database]['tempID'];}
			}
		}

	$sql = "SELECT $session_db, position.park as currPark, emplist.accessPark, concat(t3.Fname,' ',t3.Mname,' ',t3.Lname) as full_name, emplist.emid
	FROM emplist
	LEFT JOIN position on position.beacon_num=emplist.beacon_num
	LEFT JOIN empinfo as t3 on t3.tempID=emplist.tempID
	where emplist.tempID='$ftempID'";
// 	echo "$sql"; exit;
	$result = mysqli_query($connection,$sql) or die("Error 1: $sql");
	$row=mysqli_fetch_assoc($result);
	@extract($row);
	if(@$$session_db>0)
		{
		$_SESSION[$session_db]['level']=$$session_db;
		$_SESSION[$session_db]['select']=$currPark;
		$_SESSION[$session_db]['tempID']=$ftempID;
		$_SESSION[$session_db]['full_name']=$full_name;
		$_SESSION[$session_db]['emid']=$emid;
		if(!empty($accessPark))
			{
			$_SESSION[$session_db]['accessPark']=$accessPark;
			}
		}
		else
		{
		$sql = "SELECT $session_db, currPark FROM nondpr where tempID='$ftempID'";
		$result = mysqli_query($connection,$sql) or die("Error nonDPR"); //echo "$sql";
		$row=mysqli_fetch_assoc($result); //print_r($row);
		@extract($row);
			if(@$$session_db>0)
				{
				$_SESSION[$session_db]['level']=$$session_db;
				$_SESSION[$session_db]['select']=$currPark;
				}
				else
				{
				// allow entry to photos for any NRID user
				if(@$_SESSION['nrid']['level']>0)
					{
					$_SESSION[$session_db]['level']=$_SESSION['nrid']['level'];
					$level=$_SESSION[$session_db]['level'];
					}
				else
				{
				echo "You are NOT presently logged into the [$session_db] database. Try <a href='/$session_db/index.html'>logging</a> in. <br /><br />If you still get this message, then ";
				echo "you do not have access privileges for this database [$session_db]. Contact Database Support - database.support@ncparks.gov - if you wish to gain access.";
		//		echo "<pre>$sql";print_r($_SESSION);echo "</pre>";  // exit;
				exit;}
				}
		}
	}
	else
	{
	$level=$_SESSION[$session_db]['level'];
	}
?>