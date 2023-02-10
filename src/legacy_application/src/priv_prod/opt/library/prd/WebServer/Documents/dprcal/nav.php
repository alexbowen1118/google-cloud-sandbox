<?php
/*
echo "<pre>";
print_r($_SESSION); 
print_r($_REQUEST); 
echo "</pre>";
//echo "d=$dbName";exit;
*/
 
if(!isset($name)){$name="";}
if(!isset($eval)){$eval="";}


//$eval="<tr><td></td><td><h2><a href='eval.php?clidLink=312&cat=adm&Submit=Show Class(es)'>Complete</a> Evaluation.</h2></td></tr>";
$eval="";
$tempID=$_SESSION['dprcal']['loginS'];
date_default_timezone_set('America/New_York');

if($_SESSION['dprcal']['levelS']=="PARK")
	{
	echo "<table><tr><td>
	<form method='link' action='index.php'>
	<input type='hidden' name='name' value='$name'>
	<input type='submit' value='Search'>
	</form></td>
	<td>
	<form method='link' action='findHistory.php'>
	<input type='hidden' name='personID' value='$tempID'>
	<input type='submit' name='submit' value='Your Training History'>
	</form></td>";
	
	echo "$eval";
	
	echo "</tr></table>";
	}

if($_SESSION['dprcal']['levelS']=="DIST")
	{
	echo "<table><tr><td>
	<form method='link' action='admin.php'>
	<input type='hidden' name='Submit' value='Admin Page'>
	<input type='submit' value='Admin Page'>
	</form></td>$eval</tr></table>";
	}

if($_SESSION['dprcal']['levelS']=="ADMIN")
	{
	echo "<table><tr><td>
	<form method='link' action='admin.php'>
	<input type='hidden' name='Submit' value='Admin Page'>
	<input type='submit' value='Admin Page'>
	</form></td>$eval</tr></table>";
	}

if($_SESSION['dprcal']['levelS']=="SUPERADMIN")
	{
	echo "<table><tr><td>
	<form method='link' action='adminSuper.php'>
	<input type='hidden' name='name' value='$name'>
	<input type='submit' value='SuperAdmin'>
	</form></td>$eval</tr></table>";
	}
?>
