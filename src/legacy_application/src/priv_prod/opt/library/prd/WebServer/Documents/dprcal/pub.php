<?php 
extract ($_REQUEST);
ini_set('display_errors',1);
echo "
<html>
<head>
<title>Search Calendar</title>
</head>
<body>";

//include ("../../include/connectCal.inc");
include ("include/functions.php");
date_default_timezone_set('America/New_York');
?><img src='div_logo.gif'>
<font size='5' font color='#004201'>Public Search Form for the <br>NC Division of Parks and Recreation Training Calendar</font><hr>
To view classes open to the public, select the search period and click the "Search" button.
<form method="get" action="findpub.php">
<table width="100%" cellpadding="7">     
<tr><td>

<?php
$thisYear = date('Y'); 
$thisMonth = date('F');
$thisMonthNum = date('n');
$nextYear=$thisYear+1;
//$thisMonthNum = 12;  // for testing purpose

if($thisMonthNum==12){$beyond="";}else{$beyond="and beyond";}

if ($thisMonth == 1) {
        $thisYear = $thisYear-1; 
        echo "<input type='radio' name='yearRadio' value='$thisYear'>";
echo "$thisYear";

$thisYear = $thisYear+1; 
$thisYear = $thisYear; $nextYear = $thisYear+1;  
        echo "<input type='radio' name='yearRadio' value='$thisYear' checked>";
echo "$thisYear";
        echo "<input type='radio' name='yearRadio' value='$nextYear'>";
echo "$nextYear</td>";
}
elseif ($thisMonth != 1) {
$nextYear = $thisYear+1;  
        echo "<tr><td><input type='radio' name='yearRadio' value='cm'>";
echo "Current Month: $thisMonth</td></tr>";
        echo "<tr><td><input type='radio' name='yearRadio' value='cy' checked>";
echo "Current Year: $thisMonth $beyond</td></tr>";
        echo "<tr><td><input type='radio' name='yearRadio' value='ny'>";
echo "Next Year: $nextYear</td></tr></table>";
}
?> 
<table width="50%" cellpadding="7"><tr><td width = "25%"><input type="submit" name="Submit" value="Search">
</form></td>
   </tr></table>
   <table><tr><td><b>EE Certification:</b>  This certification is offered by the Department of Environment and Natural Resources and administered through the <a href='http://www.ee.enr.state.nc.us/'>Office of Environmental Education</a>.  Most of the workshops on this calendar can be applied toward EE certification in one of three categories:  Criteria I, II or III.</td></tr>
<tr><td>
<b>Advanced Interpretive Training (AIT):</b>  This certification is offered only to employees within the Division of Parks and Recreation.  However, other educators are welcome to attend the AIT workshops listed in this calendar.</td></tr>
<tr><td></td></tr><tr><td>For questions or problems regarding workshops on this calendar, contact Sean Higgins, Lead Interpretation and Education Specialist, at (919) 715-0047 or e-mail <a href='mailto:sean.higgins@ncdenr.gov'>sean.higgins@ncdenr.gov</a></td></tr></table>
</body>
</html>
