<?php 
ini_set('display_errors',1);
$database="dprcal";
include("../../include/auth.inc");
include("../../include/iConnect.inc");
mysqli_select_db($connection,$database);
// extract($_REQUEST);

include("nav.php");
 ?>

<html>
<head>
<title>Edit Existing Course</title>
</head>
<body>
<?php
$sql = "SELECT * From course WHERE
clid = '$clid'";
@$title=stripslashes($title);
echo "Edit or Delete an Existing Course - NC DPR Training Calendar<br>";
$total_result = @mysqli_query($connection,$sql) or die("Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));

while ($row = mysqli_fetch_array($total_result))
{
extract($row);
}

echo "<font size='5' color='004400'>$title</font>
<br>
  Please fill in the following information:
<br><br><b>Activity:</b>
  <script type='text/javascript'> 

function checkUncheckAll(oCheckbox) { 
var el, i = 0, bWhich = oCheckbox.checked, oForm = oCheckbox.form; 
while (el = oForm[i++]) if (el.type == 'checkbox') el.checked = bWhich; 
}
</script> 
</head> 
<body onload='document.forms[0].reset()'> 
<form method='post' action='updateCourse.php'>
<table>";
if ($adm != ''){$checkedA="checked";}else{$checkedA="";}
echo "
<tr><td><input type='checkbox' name='adm' value='1' $checkedA>Administration</td>";
if ($cert != ''){$checkedC="checked";}else{$checkedC="";}
echo "
<td><input type='checkbox' name='cert' value='1' $checkedC>EE Certification</td></tr>";
if ($skills != ''){$checkedSK="checked";}else{$checkedSK="";}
echo "
<tr><td><input type='checkbox' name='skills' value='1' $checkedSK>AIT</td>";
if ($main != ''){$checkedMA="checked";}else{$checkedMA="";}
echo "
<td><input type='checkbox' name='main' value='1' $checkedMA>Maintenance</td></tr>";
if ($safe != ''){$checkedS="checked";}else{$checkedS="";}
echo "
<tr><td><input type='checkbox' name='safe' value='1' $checkedS>Safety</td>";
if ($law != ''){$checkedL="checked";}else{$checkedL="";}
echo " 
<td><input type='checkbox' name='law' value='1' $checkedL>Law Enforcement</td></tr>";
if ($med != ''){$checkedM="checked";}else{$checkedM="";}
echo "<tr><td><input type='checkbox' name='med' value='1' $checkedM>Medical</td>";
if ($res != ''){$checkedR="checked";}else{$checkedR="";}
echo "<td><input type='checkbox' name='res' value='1' $checkedR>Resource Management</td></tr>";
if ($tra != ''){$checkedT="checked";}else{$checkedT="";}
echo "<tr><td><input type='checkbox' name='tra' value='1' $checkedT>Trails</td>";
if ($fire != ''){$checkedF="checked";}else{$checkedF="";}
echo "<td><input type='checkbox' name='fire' value='1' $checkedF>Fire Management</td></tr>
</table>";
$description=str_replace("\\r\\n", "\n",$description);
$description=str_replace("\\r", "\n",$description);
$descripton=nl2br($description);
echo "<hr>
<table> 
    <tr> 
      <td width='25%' height='39'><b>Entered by:</b></td>
      <td> 
        <input type='text' name='enter_by' value='$enter_by'>
      </td>
    </tr></table>
      <table>
    <tr> 
      <td><b>Course Title:</b></td>
      <td>
        <textarea name='title' cols='80' rows='1'>$title</textarea>
      </td>
    </tr>
    <tr> 
      <td><b>Prerequisite(s):</b></td>
      <td>
        <textarea name='prereq' cols='80' rows='3'>$prereq</textarea>
      </td>
    </tr>
    <tr> 
      <td><b>Course Description:</b></td>
      <td>
        <textarea name='description' cols='80' rows='10'>$description</textarea><br>For EE courses be sure to include the component(s) in the Certification section.
      </td>
    </tr>
    <tr> 
      <td><b>DPR Certification:</b></td>
      <td>
        <textarea name='courseCert' cols='80' rows='3'>$courseCert</textarea>
      </td>
    </tr>
    <tr> 
      <td><b>Office of EE Certification:</b></td>
      <td>
        <textarea name='nondprCert' cols='80' rows='3'>$nondprCert</textarea>
      </td>
    </tr>
    <tr> 
    </table>
    <table>
      <td><b>Keywords:</b></td>
      <td>
        <textarea name='keyword' cols='80' rows='5'>$keyword</textarea>
      </td>
    </tr>
    <tr> 
      <td><br>
<input type='hidden' name='clid' value='$clid'><input type='submit' name='Submit' value='Submit'></td>
<td></td>
    </tr>
  </table>
</form>
<form method='post' action='updateCourse.php' onclick=\"return confirm('Are you sure you want to VOID this Course?')\">
<input type='hidden' name='clid' value='$clid'>
<input type='submit' name='Submit' value='VOID'>
</form>";
?> 
</body>
</html>