<?php
//These are placed outside of the webserver directory for security
include("../../include/authSD.inc"); // used to authenticate users
include("../../include/connectSD.inc"); // database connection parameters

/*
echo "<pre>";
print_r($_SESSION);
print_r($_REQUEST);
echo "</pre>";
exit;
*/
if($_SESSION['loginS'] != 'ADMIN'){echo "Access denied.<br>Administrative Login Required.<br><a href='login_form.php'>Login</a> ";exit;}

if($_SESSION['loginS'] == 'ADMIN'){
// $park = $_SESSION['parkS'];
echo "<html>
<head>
<title>NC State Parks System - Staff Directives Website</title>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
</head>

<body bgcolor='beige'>
<h2 align='center'><font face='Verdana, Arial, Helvetica, sans-serif'>Welcome 
  to the North Carolina Division of Parks and Recreation<br>
  </font><font face='Verdana, Arial, Helvetica, sans-serif'>Staff Directives Website</font></h2>
<hr><table><tr><td width='20%'><font size='+1'>Using this website you can:</font></td><td></td></tr>
<tr><td></td><td>1. Search Staff Directives</td></tr>
<tr><td></td><td>2. Enter/Edit Staff Directive Database</td></tr>

<tr><td width='50%'><font size='+1'>Select action from navigation bar on the left side of screen.</font></td><td></td></tr>
</table><hr><div align='center'><font size='-1'>
If you have questions, problems and/or suggestions for improvement, please send an email
to <a href='mailto:tom.howard@ncmail.net'>tom.howard@ncmail.net</a>
</font></div>
</body>
</html>";
exit;
   }
?>
<html>
<head>
<title>NC State Parks System - Temporary Payroll Website</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="beige">
<h2 align="center"><font face="Verdana, Arial, Helvetica, sans-serif">Welcome 
  to the North Carolina Division of Parks and Recreation<br>
  </font><font face="Verdana, Arial, Helvetica, sans-serif">Staff Directives Website</font></h2>
<hr><table><tr><td width='20%'><font size="+1">Using this website you can:</font></td><td></td></tr>
<tr><td></td><td>Search Staff Directives</td></tr>

<tr><td width='20%'><font size="+1">Instructions:</font></td><td></td></tr>
<tr><td></td><td>Click on the Login link in the navigation bar.</td></tr>

</table><hr><div align="center"><font size="-1">
If you have questions, problems and/or suggestions for improvement, please send an email
to <a href="mailto:tom.howard@ncmail.net">tom.howard@ncmail.net</a>
</font></div>
</body>
</html>
