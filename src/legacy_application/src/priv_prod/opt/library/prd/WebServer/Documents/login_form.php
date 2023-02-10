<?php
extract($_REQUEST);

//echo "ip=$ip";exit;
if(!isset($_SESSION))
	{
	 session_start();
	}

// echo "<pre>";print_r($_REQUEST); print_r($_SESSION); echo "</pre>"; exit;

if(@!$dbName)
	{
	@$dbName=strtolower($db);
	} // specify Database

if(empty($db)){$db=$dbName;}
switch (@$db)
	{
			case "dprcal":
	$Title="NC DPR Training Calendar Website";
				break;	
			case "wiys":
	$Title="NC DPR What Is Your Status?";
				break;	
			case "seapay":
	$Title="NC DPR Temporary Payroll Website";
				break;	
			case "dprcoe":
	$Title="NC DPR Calendar of Events Website";
				break;	
			case "divper":
	$Title="NC DPR Personnel Website";
				break;	
			default:
				$Title="NC DPR Website";
	}
	
// ******* Check for existing SESSION *********
if(@$_SESSION['logname']!="" AND $_SESSION['logpass']!="" )
	{
	$ftempID=@$_SESSION['logname'];
	$fpassword=urlencode($_SESSION['logpass']);
// 	echo "<pre>"; print_r($_SESSION); print_r($_REQUEST); echo "</pre>$ftempID $fpassword";  //exit;
//	if($ftempID=="Brown2233"){echo "f=$fpassword"; exit;}  // test for passing the # character

	$_POST['ftempID']=addslashes($ftempID);
	$_POST['fpassword']=addslashes($fpassword);
	$_POST['dbName']=addslashes($dbName);
		if(!empty($forumID))
			{
			$_POST['forumID']=addslashes($_GET['forumID']);
			echo "<form action='dpr_login.php' method='post' name='doLogin'>";
                foreach ($_POST as $a => $b) {
                echo "<input type='hidden' name='".htmlentities($a)."' value='".htmlentities($b)."'>";
                }
                echo "<noscript><input type='submit' value='Click here if you are not redirected.'/></noscript>
                </form>
                <script language=\"JavaScript\">
                document.doLogin.submit();
                </script>";
			}
		else
			{
	//		header("Location: dpr_login.php?ftempID=$ftempID&fpassword=$fpassword&dbName=$dbName");
// 	  echo "<pre>"; print_r($_POST); echo "</pre>";  exit;
	  echo "<form action='dpr_login.php' method='post' name='doLogin'>";
                foreach ($_POST as $a => $b) {
                echo "<input type='hidden' name='".htmlentities($a)."' value='".htmlentities($b)."'>";
                }
                echo "<noscript><input type='submit' value='Click here if you are not redirected.'/></noscript>
                </form>
                <script language=\"JavaScript\">
                document.doLogin.submit();
                </script>";
			}
	exit;
	}

if(empty($db))
	{
	if(!isset($table)){$table="";}
	echo "We have moved databases from YORK, zLinux, to DIT Linux. Contact <a href='mailto:database.support@ncparks.gov'>database.support@ncparks.gov</a>, and let them know you were unable to login to the $dbName $table database.";
	exit;
	}


echo "<html>
<head><title>Login Form - $Title</title></head>";
?>

<body  bgcolor="#CCCCCC" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<table width="100%" border="0" cellpadding="5" cellspacing="0">
 <tr><td colspan="3" bgcolor="#CCCCCC" align="center">
  <font color="white" size="+3">
       <b><?php echo $Title ?></b></font></td></tr>
       <tr><td colspan="3" bgcolor="#CCCCCC" align="center">
  <font color="white">
       <b>Login Form</b></font></td></tr>
 <tr>
  <td>
   <form action="dpr_login.php" method="POST">
  <table border="0" width="100%">
    <?php
      if (isset($message_new))  
//            echo "<tr><td colspan='2'><font color='red'><b>".htmlentities($message_new)."</b></td></tr>";
           echo "<tr><td colspan='2'><b>".$message_new."</b></td></tr>";
    ?>
    <tr><td align="right"><b>Login</b></td>
     <td><input type="text" name="ftempID" 
               value="<?php echo htmlentities(@$ftempID) ?>" 
               size="30" maxlength="30"></td></tr>
    <tr><td align="right"><b>Password</b></td>
     <td><input type="password" name="fpassword" 
         value="" 
             size="30" maxlength="30"> First login use <b>password</b>. Subsequent logins use the password you specify.</td></tr>
<?php

if(!isset($file)){$file="";}else{$file=htmlentities($file);}
if(!empty($table)){$file=$table;}
if(!isset($ip)){$ip="";}else{$ip=htmlentities($ip);}
if(!isset($dbName)){$dbName="";}else{$dbName=htmlentities($dbName);}
echo "<tr><td>&nbsp;</td>
     <td>
       <input type='hidden' name='ip' value='$ip'>
       <input type='hidden' name='file' value='$file'>
       <input type='hidden' name='dbName' value='$dbName'>
       <input type='submit' name='Login' value='Login'></form></td>

       <td><a href='changePassword.php?dbName=$dbName'>Change</a> Password</td></tr>
   </table>";
?>
   

 <hr><div align="center"><font size="-1">
If you have questions, problems and/or suggestions for improvement, please send an email
to <a href="mailto:database.support@ncparks.gov">database.support@ncparks.gov</a>
</font></div>
</body></html>