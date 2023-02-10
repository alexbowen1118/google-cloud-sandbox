<?php
mysqli_select_db($connection, $database);
ini_set('display_errors', 1);
$database = "dprcal";
include("../../include/auth.inc");
include("../../include/connectROOT.inc");
mysqli_select_db($connection, $database);

$sql = "SELECT * From instructor order by Lname";

$total_result = @mysqli_query($connection, $sql) or die("Error #" . mysqli_errno($connection) . ": " . mysqli_error($connection));
$total_found = @mysqli_num_rows($total_result);

echo "<html><head><title></title></head>";
echo "<body>Instructors: (Ability to search by subject, etc. is a future feature.)<table>";
$i = 0;
while ($row = mysqli_fetch_array($total_result)) {
    $i = $i + 1;
    extract($row);
    $link = " - <a href='instruct_edit.php?inID=$inID'>Edit</a> this instructor.";
    echo "<tr><td>$title $Fname $Lname</td><td>$link</td></tr>";
}
echo "</table><hr></body></html>";
include("nav.php");
