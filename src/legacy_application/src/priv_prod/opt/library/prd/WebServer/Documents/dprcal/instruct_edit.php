<?php
ini_set('display_errors', 1);
$database = "dprcal";
include("../../include/auth.inc");
include("../../include/connectROOT.inc");
mysqli_select_db($connection, $database);;
extract($_REQUEST);
include("nav.php");
?>

<html>

<head>
  <title>Edit Instructor</title>
</head>

<body>
  <?php
  $sql = "SELECT * From instructor WHERE
inID = '$inID'";
  echo "Edit or Delete an Instructor - NC DPR Training Calendar<br>";
  $total_result = @mysqli_query($connection, $sql) or die("Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));

  while ($row = mysqli_fetch_array($total_result)) {
    extract($row);
  }

  echo "<font size='5' color='004400'>$title $Fname $Lname</font>
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
<form method='post' action='updateInstruct.php'>
<table>";
  if ($adm != '') {
    $checkedA = "checked";
  } else {
    $checkedA = "";
  }
  echo "
<tr><td><input type='checkbox' name='adm' value='1' $checkedA>Administration</td>";
  if ($cert != '') {
    $checkedC = "checked";
  } else {
    $checkedC = "";
  }
  echo "
<td><input type='checkbox' name='cert' value='1' $checkedC>EE Certification</td></tr>";
  if ($skills != '') {
    $checkedSK = "checked";
  } else {
    $checkedSK = "";
  }
  echo "
<tr><td><input type='checkbox' name='skills' value='1' $checkedSK>AIT</td>";
  if ($main != '') {
    $checkedMA = "checked";
  } else {
    $checkedMA = "";
  }
  echo "
<td><input type='checkbox' name='main' value='1' $checkedMA>Maintenance</td></tr>";
  if ($safe != '') {
    $checkedS = "checked";
  } else {
    $checkedS = "";
  }
  echo "
<tr><td><input type='checkbox' name='safe' value='1' $checkedS>Safety</td>";
  if ($law != '') {
    $checkedL = "checked";
  } else {
    $checkedL = "";
  }
  echo " 
<td><input type='checkbox' name='law' value='1' $checkedL>Law Enforcement</td></tr>";
  if ($med != '') {
    $checkedM = "checked";
  } else {
    $checkedM = "";
  }
  echo "<tr><td><input type='checkbox' name='med' value='1' $checkedM>Medical</td>";
  if ($res != '') {
    $checkedR = "checked";
  } else {
    $checkedR = "";
  }
  echo "<td><input type='checkbox' name='res' value='1' $checkedR>Resource Management</td></tr>";
  if ($tra != '') {
    $checkedT = "checked";
  } else {
    $checkedT = "";
  }
  echo "<tr><td><input type='checkbox' name='tra' value='1' $checkedT>Trails</td></tr>
</table>";
  echo "
<hr><table> 

    <tr> 
      <td width='9%' height='39'><b>Title:</b></td>
      <td colspan='5' height='39'> 
        <input type='text' name='title' value='$title'>
      </td>
    </tr></table>
      <table>
    <tr> 
      <td><b>First Name:</b></td>
      <td>
        <textarea name='Fname' cols='80' rows='1'>$Fname</textarea>
      </td>
    </tr>
    <tr> 
      <td><b>Last Name:</b></td>
      <td>
        <input type='text' name='Lname' size='80' maxlength='100' value='$Lname'>
      </td>
    </tr>
    <tr> 
      <td><b>Address 1:</b></td>
      <td>
        <textarea name='add1' cols='80' rows='1'>$add1</textarea>
      </td>
    </tr></table>
    
    <table>
    <tr> 
      <td><b>Address 2:</b></td>
      <td>
        <textarea name='add2' cols='80' rows='1'>$add2</textarea>
      </td>
    </tr>
    <tr> 
      <td><b>City:</b></td>
      <td>
        <textarea name='city' cols='80' rows='1'>$city</textarea>
      </td>
    </tr>
    <tr> 
      <td><b>State:</b></td>
      <td>
        <textarea name='state' cols='25' rows='1'>$state</textarea>
      </td>
    </tr>
    <tr> 
      <td><b>Zip:</b></td>
      <td>
        <textarea name='zip' cols='25' rows='1'>$zip</textarea>
      </td>
    </tr>
    <tr> 
      <td><b>phone:</b></td>
      <td>
        <textarea name='phone' cols='25' rows='1'>$phone</textarea>
      </td>
    </tr>
    <tr> 
      <td><b>Extension (if any):</b></td>
      <td>
        <textarea name='extension' cols='25' rows='1'>$extension</textarea>
      </td>
    </tr>
    <tr> 
      <td><b>fax:</b></td>
      <td>
        <textarea name='fax' cols='25' rows='1'>$fax</textarea>
      </td>
    </tr>
    <tr> 
      <td><b>email:</b></td>
      <td>
        <textarea name='email' cols='80' rows='1'>$email</textarea>
      </td>
    </tr>
    <tr> 
      <td><b>website:</b></td>
      <td>
        <textarea name='website' cols='80' rows='1'>$website</textarea>
      </td>
    </tr>
    <tr> 
      <td><b>Subject:</b></td>
      <td>
        <textarea name='subject' cols='80' rows='5'>$subject</textarea>
      </td>
    </tr>
    <tr> 
      <td><br><input type='submit' name='Submit' value='Submit'>
    </tr>
  </table>
</form>
<form method='post' action='updateInstruct.php'>
<input type='hidden' name='inID' value='$inID'>
<input type='submit' name='Submit' value='Delete'>
</form>";
  ?>
</body>

</html>