<?php
ini_set('display_errors',1);
$database="dprcal";
include("../../include/auth.inc");
include("../../include/iConnect.inc");
mysqli_select_db($connection,$database);
extract($_REQUEST);

include("nav.php");
?>

<html>
<head>
<title>Edit Class</title>
</head>
<body>
<font size="5" color="004400">Edit Class - NC DPR Training Calendar</font>
<br>
<font color='orange'>Please check the following information for correctness</font>:<br><br>
<form method="post" action="updateClass.php">
  <table width="100%" cellpadding="1">
  
<?php 
$sql = "SELECT * From train where `tid` = '$tid'";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
$row = mysqli_fetch_array($result);
if(is_array($row)){extract($row);}


if(@$public !=""){$check="value='x' checked";}else{$check="";}
// <td><input type='checkbox' name='public' $check> Check to allow public to view this class.
echo "<table><tr>

</td>";

if(@$online !=""){$checkO="value='x' checked";}else{$checkO="";}

if(!isset($title)){$title="";}
if(!isset($enter_by)){$enter_by="";}
if(!isset($reason)){$reason="";}
if(!isset($dateBegin)){$dateBegin="";}
if(!isset($dateEnd)){$dateEnd="";}
if(!isset($startTime)){$startTime="";}
if(!isset($maxClass)){$maxClass="";}
if(!isset($endTime)){$endTime="";}
if(!isset($minClass)){$minClass="";}
if(!isset($park)){$park="";}
if(!isset($dist)){$dist="";}
if(!isset($location)){$location="";}
if(!isset($contact)){$contact="";}
if(!isset($comment)){$comment="";}
if(!isset($trainID)){$trainID="";}
echo "<td><input type='checkbox' name='online' $checkO> Check to allow DPR employees to signup online.</td></tr></table>
<table><tr><td>Class Title:  <b>$title</b></td></tr>";

echo "<tr><td>Entered by:  $enter_by</td><td>To Cancel this Class, just enter the reason: <input type='text' name='reason' value='$reason' size='25'></td></tr>"; 
echo "</tr></table><table><tr>
<td>Beginning Date of Class: <input type='text' name='dateBegin' value='$dateBegin'> YYYY-mm-dd</td>
<tr><td>Ending Date of Class:  <input type='text' name='dateEnd' value='$dateEnd'></td>
</tr></table>

<table>
<tr><td>Starting Time:</td></tr>
<tr><td><textarea name='startTime' cols='25' rows='1'>$startTime</textarea>
      </td><td>Max. class size: <input type='text' name='maxClass' value='$maxClass' size='5'></td></tr>
<tr><td>Ending Time:</td></tr>
<tr><td>
        <textarea name='endTime' cols='25' rows='1'>$endTime</textarea>
      </td><td>Min. class size: <input type='text' name='minClass' value='$minClass' size='5'></td>
    </tr>
      </table>";
if($park){$parkP="&nbsp;&nbsp;Park: ".$park;} else{$parkP="";}
echo "<table><tr><td>District/Region:</td><td>$dist</td><td>$parkP</td></tr></table>
<table>
<tr><td>Location:</td></tr>
<tr><td><textarea name='location' cols='80' rows='4'>$location</textarea>
      </td></tr>
<tr><td>Contact Info:</td></tr>
<tr><td>
        <textarea name='contact' cols='80' rows='5'>$contact</textarea>
      </td>
    </tr>
    <tr><td>Comments:</td><td>Instructions:</td></tr>
<tr><td valign='top'>
        <textarea name='comment' cols='80' rows='10'>$comment</textarea>
      </td>
      <td>
        <textarea name='instructions' cols='70' rows='10'>$instructions</textarea>
      </td>
    </tr>
      </table>
    <table><tr>
    
      <td><br>
      <input type='hidden' name='trainID' value='$trainID'>
      <input type='hidden' name='tid' value='$tid'>
      <input type='submit' name='Submit' value='Submit'></td>
    </tr>
  </table>
</form>
<form method='post' action='updateClass.php'>
      <input type='hidden' name='tid' value='$tid'>
      <input type='submit' name='Submit' value='Delete'>
      </form>";
?>
</body>
</html>