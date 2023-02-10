<?php
$database="staffdir";
include("../../include/auth.inc");

//print_r($_SESSION);
?>
<html>
<head>
<title>NC State Parks System - Staff Directive Website</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="beige">

<table cellpadding='5'>
<tr>
<td align='left'>NC DPR - Staff Directives</td>
<td align="center">
<font face="Verdana, Arial, Helvetica, sans-serif" color="green">
<a href="search.php?Submit=Search&v=2">Show All</a>&nbsp;&nbsp;&nbsp;
<a href="search.php?Submit=Search&v=1">List by Year</a></font></td>
<td><a href="list_policies.php">Policy Home Page</a></td>
</tr>
<tr><td><form name='search' method='post' action='search.php'>

Find SD(s) containing this word:<input type='text' name='findSD' value=''></td>
<td>Find SD(s) by Year (2014) or Number (2014-4):<input type='text' name='dirNum' value='' size='8'></td>
<td><input type='submit' name='Submit' value='Search'></form></td>
 
<?php
if($_SESSION['staffdir']['level']>2)
	{
	echo "<td>
	<font face='Verdana, Arial, Helvetica, sans-serif' color='green'><a href='adminMenu.php'>Admin</a></font>";
	}
?>
</td>
</tr></table><hr>
