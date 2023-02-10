<?php
ini_set('display_errors',1);
$database="dprcal";
include("../../include/auth.inc");
include("../../include/iConnect.inc");
mysqli_select_db($connection,$database);
extract($_REQUEST);

if (@$title=="" AND @$yearRadio=="" AND @$yearText=="" AND @$month=="" AND @$body==""  AND @$clid=="")
	{
	echo "You did not enter any search item(s).<br><br>Click your Back button."; exit;}

if ($title != ""){$var1 = "title LIKE '%$title%'";}
if ($year != ""){$var1 .= " and dateFind LIKE '$year%'";}


@$find = $var1.$var2.$var3.$var4; // concat the search terms

// echo "$find";

//$today=date("Y-m-d");
/*
$sql = "SELECT DISTINCT trainID,dist,title,park,dateBegin From train WHERE
($find and dateBegin>'$today') order by dateBegin DESC, title";
*/

$sql = "SELECT tid,dist,title,park,dateFind, enter_by From train WHERE
$find order by dateFind , title";

// echo "$sql";

$total_result = @mysqli_query($connection,$sql) or die("Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
$total_found = @mysqli_num_rows($total_result);
if($total_found < 1){echo "No Class with that name has been entered.";
include("nav.php");
exit;}
echo "<html><head><title></title></head>";
echo "<body><H2>Class(es) for $year $title:</h2><table>";
$i=0;
while ($row = mysqli_fetch_array($total_result))
{
$i++;
extract($row);
//$trainIDu = urlencode($trainID);
//$link = "<a href='class_edit.php?trainID=$trainIDu'>$title - $dist - $park - $dateFind</a>";
$link = "$i <a href='class_edit.php?tid=$tid'>$title - $dist - $park - $dateFind</a>";
echo "<tr><td>$link</td><td>".substr($enter_by,0,-4)."</td>
</tr>";
}
echo "</table><br /><br />Click your browser's back button to return to search form.</body></html>";

?>
