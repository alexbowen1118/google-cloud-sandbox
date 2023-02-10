<?php
ini_set('display_errors',1);
$database="dprcal";
include("../../include/auth.inc");
include("../../include/iConnect.inc");
mysqli_select_db($connection,$database);
extract($_REQUEST);

if(@$rep==""){include("nav.php");}
$level=$_SESSION['dprcal']['level'];
if($level<3){exit;}
//echo "<pre>";print_r($_REQUEST);echo "</pre>"; //exit;

if(!empty($_POST))
	{
	$supid=$_POST['supid'];
	$signup_comment=addslashes($_POST['signup_comment']);
	$sql = "UPDATE signup 
	set signup_comment='$signup_comment'
	where supid='$supid'";
	$result = @mysqli_query($connection,$sql) or die("Error 1 $sql #". mysqli_errno($connection) . ": " . mysqli_error($connection));
	echo "After completing the edit you may close this window. You will need to refresh the class list.";
	}

extract($_REQUEST);
$sql = "SELECT * from signup where supid='$supid'";
$result = @mysqli_query($connection,$sql) or die("Error 1 $sql #". mysqli_errno($connection) . ": " . mysqli_error($connection));
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	}

$show=array("personID","signup_comment","dateClass");
echo "<form method='POST' action='edit_signup_comment.php'><table>";
foreach($ARRAY AS $index=>$array)
	{
	foreach($array as $fld=>$value)
		{
		if(!in_array($fld, $show)){continue;}
		echo "<tr><td>$fld</td>";
		if($fld=="signup_comment")
			{$value="<textarea name='$fld' cols='50' rows='3'>$value</textarea>";}
		echo "<td>$value</td>";
		echo "</tr>";
		}
	}
echo "<tr><td colspan='2' align='center'>
<input type='hidden' name='supid' value='$supid'>
<input type='submit' name='submit' value='Update'>
</td></tr>";
echo "</table>";
echo "</body>
</html>";
?>

