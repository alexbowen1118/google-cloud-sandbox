<?php
include("../../include/authTrainCal.inc");
include("../../include/connectTrainCal.inc");
//session_start();
//print_r($_REQUEST);
//print_r($_SESSION);
if ($Submit == "Search") {
  if ($tid) {
    header("Location:findEnrollee.php?Submit=Search&tid=$tid");
    exit;
  } else {
    $var1 = "(title LIKE '%$title%' and dateFind LIKE '$yearClass%')";
  }

  $sql = "SELECT * From train WHERE
$var1
ORDER BY dateFind";
  //echo "$sql";exit;
  $total_result = @mysqli_query($connection, $sql) or die("$sql Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
  $total_found = @mysqli_num_rows($total_result);
  if ($total_found < 1) {
    $classGone = "<font size='4' color='660000'>Class was deleted. Remove all enrollees.</font>";
  }

  echo "<html><head><title></title></head>";
  echo "<body>Enrollees for: <table>";
  while ($row = mysqli_fetch_array($total_result)) {
    extract($row);
    echo "<br><a href='findSignup.php?Submit=Search&tid=$tid'>$dateFind</a> at $park for $title";
  }
  if ($dupe == 1) {
    $dupe = "A person with that name has already enrolled for this classe.";
  }
  echo "</table><hr>$dupe</body></html>";
  include("nav.php");
  exit;
}


$sql = "SELECT * From signup";

$total_result = @mysqli_query($connection, $sql) or die("Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
$total_found = @mysqli_num_rows($total_result);
if ($total_found < 1) {
  echo "No one has enrolled in any class.";
  include("nav.php");
  exit;
}
?>

<html>

<head>
  <title>Find Enrollees</title>
</head>

<body>
  <p>
    <font size="5" font color="#004201"> NC DPR Training Calendar</font>
  </p>
  <p>Enter Class Title:</p>
  <form method="post" action="findSignup.php">

    <?php
    if ($yearClass) {
    } else {
      $yearClass = date("Y");
    }

    echo "<table width='100%' cellpadding='7'>
<tr><td><b>Choose the Year of Class:</b><input type='text' name='yearClass' value='$yearClass' size='7'></td></tr>
    <tr><td><b>Class Title:</b>
        <input type='text' name='title' size='25' maxlength='50'> Any word or phrase from the title.
      </td></tr>
      <tr><td>A list of all enrollees will be returned.</td></tr></table>
<table width='100%' cellpadding='7'><tr><td><input type='submit' name='Submit' value='Search'></td>
   </tr></table>
</form>
</body>
</html>";
    ?>