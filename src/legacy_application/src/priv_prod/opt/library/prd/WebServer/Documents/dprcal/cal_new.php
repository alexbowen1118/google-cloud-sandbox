<?php 

ini_set('display_errors',1);
$database="dprcal";
include("../../include/auth.inc");
include("../../include/iConnect.inc");
mysqli_select_db($connection,$database);
extract($_POST);
date_default_timezone_set('America/New_York');
?>
<html>
<head>
<link type="text/css" href="../css/ui-lightness/jquery-ui-1.8.23.custom.css" rel="Stylesheet" />    
<script type="text/javascript" src="../js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.23.custom.min.js"></script>

<title>New Class</title>
</head>
<body>
<?php
include("nav.php");
?>

<font size="5" color="004400">New Class - NC DPR Training Calendar</font>
<br>
  Please fill in the following information:
<form method="post" action="addCal.php">

  <table width="100%" cellpadding="1">
<?php 
///*
$enter_by=$_SESSION['dprcal']['loginS'];
echo "
<tr><td>&nbsp;</td></tr>
  <tr><td>Entered by:<input type='text' name='enter_by' value='$enter_by' READONLY></td></tr></table>"; 

echo "<table>
    <tr> 
      <td><b>Class Title:</b></td>";

$sql = "SELECT clid, title From course where del='' ORDER by title";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
$source_title="";
echo "<td><select name='title'>\n";
 echo "<option value=''>\n";
while ($row = mysqli_fetch_array($result))
	{
	extract($row);
	$new_title=str_replace("\"", "_", $title);
	$titleE=urlencode($title);
	$new_title.="*".$clid;
	$source_title.="\"".trim($new_title)."\",";
	echo "<option value='$titleE'>$title";
}

echo "</select></td></tr>";
echo "<tr><td></td><td><font color='green'>or type class title below</font></td></tr>";
echo "<tr><td><b>Class Title:</b></td>
<td><input id='title_alt' type='text' name='title_alt' value=\"\" size='82'> (If your title isn't listed, contact <a href='mailto:database.support@ncparks.gov?subject=New Training Course'>database support</a>.)</td>
</tr>";
echo "</table>";

echo "<table><tr>";
    $monthArray = range(1,12); 
    $monthArrayName = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',);
//*/
?>
      <td><b>Beginning Date of Class:</b></td>
     <td>
       
<?php 
//  Enter Beginning date for Class
echo "<select name='monthBegin'>\n";
 echo "<option value=''>\n";
for ($i=0; $i <=11; $i++)
{$month = $monthArray[$i];echo "<option value='$month'>$monthArrayName[$i]";}
echo "</select> Month &nbsp;&nbsp; </td>";

$dayArray = range(1,31);
echo "<td><select name='dayBegin'>\n";
 echo "<option value=''>\n";
for ($i=0; $i <=30; $i++)
{$day = $dayArray[$i];echo "<option value='$day'>$day";}
echo "</select> Day &nbsp;</td>";

 $thisYear = date('Y'); 
// $prevYear = $thisYear - 1;
 $nextYear = $thisYear + 1;

//$yearArray = array("$prevYear", "$thisYear", "$nextYear");
$yearArray = array("$thisYear", "$nextYear");

echo "<td><select name='yearBegin'>\n";
 echo "<option value=''>\n";
foreach($yearArray as $index=>$year)
	{
	echo "<option value='$year'>$year";
	}
echo "</select> Year &nbsp;</td></tr>";

echo "<tr><td><b>Ending Date of Class:</b></td>";
echo "<td><select name='monthEnd'>\n";
 echo "<option value=''>\n";
for ($i=0; $i <=11; $i++)
{$month = $monthArray[$i];echo "<option value='$month'>$monthArrayName[$i]";}
echo "</select> Month &nbsp;&nbsp; </td>";

$dayArray = range(1,31);
echo "<td><select name='dayEnd'>\n";
 echo "<option value=''>\n";
for ($i=0; $i <=30; $i++)
{$day = $dayArray[$i];echo "<option value='$day'>$day";}
echo "</select> Day &nbsp;</td>";

 $thisYear = date('Y'); 
 $nextYear = $thisYear + 1;

echo "<td><select name='yearEnd'>\n";
 echo "<option value=''>\n";
foreach($yearArray as $index=>$year)
	{
	echo "<option value='$year'>$year";
	}
echo "</select> Year &nbsp;</td></tr></table>";

echo "<table>
<tr><td>Starting Time:<br><textarea name='startTime' cols='25' rows='1'></textarea></td>
<td>Ending Time:<br><textarea name='endTime' cols='25' rows='1'></textarea>
</tr></table>
<table>
<tr></td><td>Min. class size: <input type='text' name='minClass' value='1' size='5'> Enter a size greater than 1 if desired.</td></tr>
<tr><td>Max. class size: <input type='text' name='maxClass' value='' size='5'> Leave blank if unlimited, otherwise enter a number.</td></tr></table>";
      
$distArrayName = array('EADI','NODI','SODI','WEDI');
$regArrayName = array('CORE','PIRE','MORE');
echo "<table><tr><td><select name='dist'>\n";
 echo "<option value=''>\n";
for ($i=0; $i <=(count($distArrayName)-1); $i++)
	{
	$dist = $distArrayName[$i];
	echo "<option value='$dist'>$dist";
	}
echo "</select> District (enter District of class even if not held in a park)</td></tr>";
// ***** Park Input
include("../../include/get_parkcodes_dist.php");

echo "<tr><td><select name='park'>\n";
 echo "<option value=''></option>\n"; 
foreach($parkCode as $index=>$parkcode)
	{
		 echo "<option value='$parkcode'>$parkcode</option>\n";
	}
echo "</select>";


echo " Select Park (leave blank if class not held at park)</td></tr>
<tr><td>&nbsp;</td></tr><tr><td><input type='checkbox' name='online' value='x' checked><font color = 'green'>When checked, DPR employees are able to signup online.</font> (On rare occasions online signup will need to be disabled.)</td></tr>";

// echo "<tr><td>&nbsp;</td></tr><tr><td><input type='checkbox' name='public'><font color = 'red'>Check to allow public to view the availability of this class in the publically viewable calendar.</font></td></tr>";

echo "</table><hr>";
?>
<table>
<tr><td>Location: (keep location text on one line)</td></tr>
<tr><td><textarea name='location' cols='80' rows='1'></textarea>
      </td></tr>
<tr><td>Contact Info:</td></tr>
<tr><td>
        <textarea name='contact' cols='80' rows='5'></textarea>
      </td>
    </tr>
    <tr><td>Comments:</td></tr>
<tr><td>
        <textarea name='comment' cols='80' rows='5'></textarea>
      </td>
    </tr>
      </table>
    <table><tr> 
      <td><br><input type="submit" name="Submit" value="Submit"></td>
    </tr>
  </table>
</form>
</body>
</html>

<?php

	echo "<script>
		$(function()
			{
			$( \"#title_alt\" ).autocomplete({
			source: [ $source_title ]
				});
			});
		</script>";
?>