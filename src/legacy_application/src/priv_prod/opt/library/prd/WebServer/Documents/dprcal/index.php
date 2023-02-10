<?php

//extract ($_REQUEST);
$database = "dprcal";
include("../../include/auth.inc");
$level = $_SESSION[$database]['level'];
if ($level > 3) {
  ini_set('display_errors', 1);
}
include("nav.php");
//session_start();
echo "
<html>
<head>
<title>Search Calendar</title>
</head>
<body>";

//echo "<pre>";print_r($_SESSION);echo "</pre>"; //exit;
?>
<font size='5' font color='#004201'>Search Form for the NC DPR Training Calendar</font>
<hr>
<form method="post" action="findJoin.php">
  <table width="100%" cellpadding="7">
    <tr>
      <td></td>
      <td width="40%">Year:

        <?php
        $thisYear = date('Y');
        $thisMonth = date('M');
        $longMonth = date('F');
        $numberMonth = date('m');
        //$thisMonth = 1;  // for testing purpose
        if ($thisMonth == 1) {
          $thisYear = $thisYear - 1;
          echo "<input type='radio' name='yearRadio' value='$thisYear'>";
          echo "$thisYear";

          $thisYear = $thisYear + 1;
          $thisYear = $thisYear;
          $nextYear = $thisYear + 1;
          echo "<input type='radio' name='yearRadio' value='$thisYear' checked>";
          echo "$thisYear";
          echo "<input type='radio' name='yearRadio' value='$nextYear'>";
          echo "$nextYear</td><td><input type='test' name='yearRadio' value='$nextYear'>";
          echo "$nextYear";
        } elseif ($thisMonth != 1) {
          $thisYear = $thisYear;
          $nextYear = $thisYear + 1;
          echo "<input type='radio' name='yearRadio' value='$thisYear' checked>";
          echo "$thisYear";
          echo "<input type='radio' name='yearRadio' value='$nextYear'>";
          echo "$nextYear";
        }

        $testLevel = $_SESSION['dprcal']['levelS'];
        if ($level > 0) {
          echo " <input type='text' name='yearText' value=''>A previous year.";
        }
        ?>
      </td>
    </tr>
    <tr>
      <td width="8%"><b>Time Period Search:</b></td>
      <td width="45%" height="29">
        <?php
        echo "<input type='radio' name='monthRadio' value=''>";
        echo "Entire Year<br>";
        echo "<input type='radio' name='monthRadio' value='$numberMonth' checked>";
        if ($longMonth != "December") {
          $l = " and later";
        } else {
          $l = "";
        }
        echo "$longMonth$l<br>";
        ?>
        <input type="text" name="month" size="3" maxlength="3">
        Specific Month (number from 1 - 12)
      </td>
    </tr>
    <tr>
      <td></td>
      <td>Optional:</td>
    </tr>
    <tr>
      <td width="8%"><b>Title Search:</b></td>
      <td colspan="5">
        <input type="text" name="title" size="25" maxlength="50"> Any WORD or phrase from the title.
      </td>
    </tr>
    <tr>
      <td width="8%"><b>Keyword Search:</b></td>
      <td colspan="5">
        <input type="text" name="keyword" size="25" maxlength="50"> Any WORD that describes the training.
      </td>
    </tr>
    <tr>
      <td width="8%"><b>Activity Search:</b></td>
      <td colspan="5">

        <select name="activity" size=1>
          <option value="">
          <option value="Administration">Administration
          <option value="EE Certification">EE Certification
          <option value="Advanced Interpretive Training">Advanced Interpretive Training
          <option value="Maintenance">Maintenance
          <option value="Safety">Safety
          <option value="Law Enforcement">Law Enforcement
          <option value="Medical">Medical
          <option value="Resource Management">Resource Management
          <option value="Trails">Trails
        </select> You can limit search results by selecting an Activity.
      </td>
    </tr>
    <tr>
      <td width="8%"><b>District:</b></td>
      <td colspan="5">

        <select name="dist" size=1>
          <option value="">
          <option value="EADI">EADI
          <option value="NODI">NODI
          <option value="SODI">SODI
          <option value="WEDI">WEDI
          <option value="CORE">CORE
          <option value="PIRE">PIRE
          <option value="MORE">MORE
        </select> You can limit search results by selecting a District.
      </td>
    </tr>
  </table>

  <table width="50%" cellpadding="7">
    <tr>
      <td width="25%"><input type="reset" name="Reset" value="Reset"></td>
      <td width="25%">
        <input type="hidden" name="longMonth" value="<?php echo "$longMonth" ?>">
        <input type="hidden" name="name" value="<?php echo "$name" ?>">
        <input type="submit" name="Submit" value="Search">
      </td>
    </tr>
  </table>
  <hr>
</form>
<?php
echo "<a href='/dprcal/eval.php'>Evaluation</a>";
?>
</body>

</html>