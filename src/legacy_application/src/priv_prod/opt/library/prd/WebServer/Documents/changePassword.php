<?php
ini_set('display_errors',1);
 extract($_REQUEST);
 if(empty($db))
	{
	$dbName="divper";
	$db="divper";
	}
 if(empty($ftempID)){$ftempID="";}
 if(empty($fpassword)){$fpassword="";}
 if(empty($femid)){$femid="";}

//db=$dbName&ftempID=$ftempID&fpassword=password&femid=$femid

?>
<html>
<head><title>Change Password Form</title></head>
<body bgcolor="beige">
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<table width="100%" border="0" cellpadding="5" cellspacing="0">
 <tr><td colspan="3" bgcolor="gray" align="center">
  <font color="white" size="+5">
       <b>Change Password Form</b></font></td></tr>
 <tr>
  <td>
   <form action="dpr_login.php" method="post">

    <?php
      if (isset($message_new))  
           echo "<tr><td colspan='2'><font color='red'><b>$message_new</b></td></tr>";
    ?>

    <tr><td align="right"><b>Your Username is: </b></td>
     <td>

<?php
echo "<input type='text' name='ftempID'  value='$ftempID'></td></tr>";

if($fpassword=="password")
	{
	echo "<tr><td align='right'><b>Default Password is: </b></td>
	 <td><font color='red'>password</font>. Please enter a more secure one.</td></tr>";
	}
	else
	{
	echo "<tr><td align='right'><b>Enter your existing password: </b></td>
	 <td><input type='password' name='oldpassword' value='' size='20' maxlength='20'></td></tr>";
	}

echo "<tr><td align='right'><b>New Password</b></td>
     <td><input type='password' name='npassword0' 
         value='' size='20' maxlength='20'></td></tr>
    <tr><td align='right'><b>Retype New Password</b></td>
     <td><input type='password' name='npassword1' 
         value='' size='20' maxlength='20'></td></tr>";

?>    
   
    <tr><td>&nbsp;</td>
     <td align="left">

<?php
if(!isset($db)){$db="";}
if(!isset($ftempID)){$ftempID="";}
if(!isset($var)){$var="";}
//       <input type='hidden' name='ftempID' value='$ftempID'>
echo "
       <input type='hidden' name='var' value='$var'>
       <input type='hidden' name='dbName' value='$db'>
       <input type='submit' name='submit' value='Change Password'>";
?>      
       </td>
    </tr>
   </form>
  </td>
 </tr>
 <tr><td colspan="3" bgcolor="gray">&nbsp;</td></tr></table>
 <hr><div align="center"><font size="-1">
If you have questions, problems and/or suggestions for improvement, please send an email
to <a href="mailto:database.support@ncparks.gov">database.support@ncparks.gov</a>
</font></div>
</body></html>