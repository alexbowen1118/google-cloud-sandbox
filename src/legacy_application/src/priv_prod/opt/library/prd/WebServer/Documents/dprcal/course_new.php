<?php
//ini_set('display_errors',1);
$database="dprcal";
include("../../include/auth.inc");
$enter_by=$_SESSION[$database]['loginS'];

include("nav.php");
?>
<html>
<head>
<title>Add New Course</title>
</head>
<body>
<font size="5" color="004400">Add New Course - NC DPR Training Calendar</font><br>

  Please fill in the following information.
<br><br><b>Activity:</b>
  <script type="text/javascript"> 

function checkUncheckAll(oCheckbox) { 
var el, i = 0, bWhich = oCheckbox.checked, oForm = oCheckbox.form; 
while (el = oForm[i++]) if (el.type == 'checkbox') el.checked = bWhich; 
}
</script> 
</head> 
<body onload="document.forms[0].reset()"> 
<form method="post" action="addCourse.php">
<table>
<tr><td><input type="checkbox" name="adm" value="1" />Administration</td> 
<td><input type="checkbox" name="cert" value="1" />EE Certification</td></tr>
<tr><td><input type="checkbox" name="skills" value="1" />AIT</td> 
<td><input type="checkbox" name="main" value="1" />Maintenance</td></tr>
<tr><td><input type="checkbox" name="safe" value="1" />Safety</td> 
<td><input type="checkbox" name="law" value="1" />Law Enforcement</td></tr>
<tr><td><input type="checkbox" name="med" value="1" />Medical</td> 
<td><input type="checkbox" name="res" value="1" />Resource Management</td></tr>
<tr><td><input type="checkbox" name="tra" value="1" />Trails</td>
<td><input type="checkbox" name="fire" value="1" />Fire Management</td></tr>
</table>
<hr><table> 

    <tr> 
      <td width="9%" height="39"><b>Entered by:</b></td>
      <td colspan="5" height="39"> 
        
        <?php echo "<input type=\"text\" name=\"enter_by\" value='$enter_by'>";?>
        
      </td>
    </tr></table>
      <table>
    <tr> 
      <td><b>Prerequisite(s):</b></td>
      <td>
        <textarea name="prereq" cols="80" rows="3">None</textarea>
      </td>
    </tr>
    <tr> 
      <td><b>Course Title:</b></td>
      <td>
        <input type="text" name="title" size="80" maxlength="100">
      </td>
    </tr>
    <tr> 
      <td><b>Course Description:</b></td>
      <td>
        <textarea name="description" cols="80" rows="10"></textarea>
      </td>
    </tr>
    <tr> 
      <td><b>DPR Certification:</b></td>
      <td>
        <textarea name="courseCert" cols="80" rows="3"></textarea>
      </td>
    </tr>
    <tr> 
      <td><b>Office of EE Certification:</b></td>
      <td>
        <textarea name="nondprCert" cols="80" rows="3"></textarea>
      </td>
    </tr>
    </table>
    
    <table>
    <tr> 
      <td><b>Keywords:</b></td>
      <td>
        <textarea name="keyword" cols="80" rows="5"></textarea>
      </td>
    </tr>
    <tr> 
      <td><br><input type="submit" name="Submit" value="Submit">
    </tr>
  </table>
</form>
</body>
</html>