<?php
extract ($_REQUEST);
ini_set('display_errors',1);
$database="dprcal";
include("../../include/auth.inc");
include("../../include/iConnect.inc");
mysqli_select_db($connection,$database);


$query = "DELETE from signup WHERE supid = '$supid'";
//echo "$query";exit;
$result = mysqli_query($connection,$query) or die ("Couldn't execute query. $query");

header("Location: findEnrollee.php?tid=$tid&Submit=Search");
exit;
?> 
</body>
</html>
