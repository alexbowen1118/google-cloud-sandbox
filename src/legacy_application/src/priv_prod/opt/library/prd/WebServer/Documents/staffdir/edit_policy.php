<?php
ini_set('display_errors',1);
$database="staffdir";
include("../../include/auth.inc");// database connection parameters
//echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
include("../../include/iConnect.inc");// database connection parameters

mysqli_select_db($connection, $database)
       or die ("Couldn't select database $database");
       
if(!empty($_POST))
	{
//	echo "<pre>"; print_r($_POST); echo "</pre>";  exit;
	
	if($_POST['submit']=="Delete")
		{
		$id=$_POST['id'];
		$sql = "DELETE FROM policy where id='$id'"; //echo "$sql"; exit;
		$result = mysqli_query($connection, $sql) or die ("Couldn't execute select query. $sql ".mysqli_error($connection));
		echo "Return to Policy <a href='http://www.dpr.ncparks.gov/staffdir/list_policies.php'>page</a>";
		exit;
		}
	
	foreach($_POST AS $fld=>$val)
		{
		if($fld=="submit" || $fld=="id"){continue;}
		@$clause.="`".$fld."`='".$val."', ";
		}
		$clause=rtrim($clause,", ");
//	echo "$clause";
	$id=$_POST['id'];
	$sql = "update policy set $clause where id='$id'"; //echo "$sql"; exit;
	$result = mysqli_query($connection, $sql) or die ("Couldn't execute select query. $sql ".mysqli_error($connection));
	}
$title="Policy"; 
include("/opt/library/prd/WebServer/Documents/efile/_base_top_efile.php");
echo "<table align='center'><tr><td colspan='2'><h3>NC DPR Policy</h3></td>";

echo "<td><a href='list_policies.php'>Policy Home Page</a></td>";
echo "</tr>";

	echo "</table>";
extract($_REQUEST);
$sql = "SELECT * from policy where id='$id'";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute select query. $sql ".mysqli_error($connection));
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	}
	
$sql = "SELECT distinct policy_category from policy where 1";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute select query. $sql ".mysqli_error($connection));
while($row=mysqli_fetch_assoc($result))
	{
	$policy_cat[]=$row['policy_category'];
	}
//echo "<pre>"; print_r($policy_cat); echo "</pre>"; // exit;
$skip=array("alt_directive", "document_link", "mid");
$edit=array("policy_category","directive", "guideline", "title/description", "comments","FIND");
echo "<form method='POST' action='edit_policy.php'><table align='center'>";
foreach($ARRAY AS $index=>$array)
	{
	foreach($array as $fld=>$value)
		{
		if(in_array($fld, $skip)){continue;}
		if(in_array($fld, $edit))
			{
			$size="";
			$add="";
			if($fld=="title/description"){$size=66;}
			if($fld=="directive"){$add=" Enter the year and two-digit number, e.g., 2014-04"; $size="8";}
			if($fld=="guideline"){$add=" Enter the eFile number for document, e.g., 1097"; $size="8";}
			if($fld=="FIND"){$add=" Enter link for FIND entry, e.g., 600"; $size="68";}
			$temp="<input type='text' name='$fld' value=\"$value\" size='$size'>".$add;
			if($fld=="policy_category")
				{
				$temp="<select name='$fld'><option value=\"\"></option>\n";
				foreach($policy_cat as $k=>$v)
					{
					if($v==$value){$s="selected";}else{$s="";}
					$temp.="<option value='$v' $s>$v</option>\n";
					}
				$temp.="</select>";
				}
			if($fld=="comments"){$temp="<textarea name='$fld' cols='44' rows='3'>$value</textarea>";}
			if($fld=="title/description"){$size=66;}
			$value=$temp;
			}
		if($fld=="guideline"){$fld="<font color='red'>$fld</font>";}
		if($fld=="directive"){$fld="<font color='blue'>$fld</font>";}
		echo "<tr><td>$fld</td><td>$value</td
		></tr>";
		}
	}
echo "<tr><td colspan='2' align='center'>
<input type='hidden' name='id' value=\"$id\">
<input type='submit' name='submit' value=\"Update\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type='submit' name='submit' value=\"Delete\" onclick=\"return confirm('Are you sure you want to delete this record?')\">
</td></tr>";

echo "<tr><td colspan='2'>
To have the link to a SD show up on the Policy page, the Directive number must be entered correctly. This is a four-digit year, dash, and usually a two-digit number. Sometimes it's a single digit number. Use whatever was entered when the SD was created.<br /><br />
To show a link to a Guideline, first enter the .doc and .pdf files into eFile \"Historical Documents\" ==> (LEGISLATION, POLICIES AND DIRECTIVES ==> Guidelines). This will create an eFile \"id\" number. Enter that number in the guideline box.
</td></tr>";
echo "</table></form></body></html>";


?>