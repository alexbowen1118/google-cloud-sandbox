<?php 
$database="dprcal";
include("../../include/auth.inc");
?>

<html>
<head>
<title>Edit a Class</title>
</head>
<body>
<p><font size="5" font color="#004201"> NC DPR Training Calendar</font></p>
<p>
  Find the Class to Edit:</p>
<form method="get" action="findClass.php">

<table width="100%" cellpadding="7">
    
    <tr> 
      <td><b>Year: </b></td><td><input type="text" name="year" size="10" maxlength="4" value="<?php date_default_timezone_set('America/New_York'); $y=date('Y');echo "$y";?>"></td>
     </tr>
    <tr> 
      <td width="8%"><b>Class Title:</b></td>
      <td> 
        <input type="text" name="title" size="25" maxlength="50"> Any word or phrase from the title.
      </td>
    </tr></table>

<table width="100%" cellpadding="7"><tr><td><input type="submit" name="Submit" value="Search"></td>
   </tr></table>
</form>
</body>
</html>
