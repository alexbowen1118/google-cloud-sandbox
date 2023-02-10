<?php
ini_set('display_errors',1);
$title="EFILE";
include("../inc/_base_top_dpr.php");
$database="efile";

// echo "<pre>"; print_r($_REQUEST); echo "</pre>";

include("../../include/auth.inc"); // used to authenticate users

$level=$_SESSION[$database]['level'];
$tempID=$_SESSION[$database]['tempID'];
date_default_timezone_set('America/New_York');

include("../../include/iConnect.inc");// database connection parameters

mysqli_select_db($connection,$database)
   or die ("Couldn't select database");

$sql="SELECT parkcode as park_code, center_desc from `center_efile` where 1 order by parkcode";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$parkCode[$row['park_code']]=$row['center_desc'];
	}
	
$sql="SELECT distinct id,`group`, `guideline`
from guideline_group order by `group`,`guideline`";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$guideline_group_array[$row['id']]=$row['group'].":".$row['guideline'];
	}
				
extract($_REQUEST);
if(!empty($search_cat_id) or !empty($cat_id) or !empty($pass_cat_id))
	{
	$var=(empty($search_cat_id)?@$cat_id:$search_cat_id);
	if(!empty($pass_cat_id))
		{
		$var=$pass_cat_id;
		}
	$sql="SELECT *
	from documents as t1
	where t1.cat_id='$var'";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	$num_docs_for_cat_id=mysqli_num_rows($result);  //echo "$sql $num_docs_for_cat_id";
	
	$sql="SELECT distinct park_code
	from documents as t1
	where t1.cat_id='$var' order by park_code";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	while($row=mysqli_fetch_assoc($result))
		{
		if(empty($row['park_code'])){continue;}
		$cat_park_code[$row['park_code']]=$parkCode[$row['park_code']];
		}
//		echo "<br />$sql<pre>"; print_r($cat_park_code); echo "</pre>"; // exit;
	if(!empty($cat_park_code))
		{
		$parkCode=$cat_park_code;
		}
		else
		{
		$parkCode=array("No park listed for this category. However, click \"Search\" to see them all.");
		}
	
	}


$sql="SELECT * from efile_cat where 1 order by cat_name,cat_subject";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	@$ARRAY_cat_subject[$row['cat_name']][$row['id']]=$row['cat_subject'];
	}
	
//echo "<pre>"; print_r($ARRAY_cat_subject); echo "</pre>";

$tr_array=array("I & E","PARK OPS","TRAILS");
echo "<table border='1' align='center' cellpadding='5'>";

echo "<tr><th colspan='7'><font color='gray'>Welcome to the DPR - <font color='brown'>E</font>lectronic <font color='brown'>F</font>ile <font color='brown'>I</font>nformation <font color='brown'>L</font>inks <font color='brown'>E</font>nvironment</font></th></tr>";

	echo "<tr>";
foreach($ARRAY_cat_subject as $cat=>$array)
	{
	echo "<td align='left'><font size='-1'><b>$cat</b><br />";
	
	if(!empty($array[0]))
		{$sub="subjects";}else{$sub="subject";}
		echo "<a onclick=\"toggleDisplay('subject[$cat]');\" href=\"javascript:void('')\">$sub</a>
		
		 <div id=\"subject[$cat]\" style=\"display: none\">";		 			
		foreach($array as $index=>$subject)
			{
			if(empty($subject)){$subject="$cat";}
			echo "<p>&nbsp;&nbsp; <a href='files.php?cat_id=$index'>---></a> $subject</p>";
			}
		echo "</font></div>";
		
	echo "</td>";
	if(in_array($cat,$tr_array)){echo "</tr><tr>";}
	}
echo "<th><font color='magenta'>All Category</font> <a href='files.php?search=all'>Search</a></th></tr>";
echo "</table>";

extract($_REQUEST);
if(!empty($cat_id))
	{
	include("action.php");
	}

if(!empty($doc_id))
	{
	include("action.php");
	}
	
if(@$search=="Search")
	{
	include("action.php");
	}
if(@$search=="all")
	{
	$sql="SELECT distinct park_code from documents where park_code!='' order by park_code";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	while($row=mysqli_fetch_assoc($result))
		{
		@$ARRAY_park_code_docs[]=$row['park_code'];
		}
	$sql="SELECT distinct cat_id from documents where 1 order by cat_id";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	while($row=mysqli_fetch_assoc($result))
		{
		@$ARRAY_cat_id_docs[]=$row['cat_id'];
		}
//		echo "<pre>"; print_r($ARRAY_cat_id_docs); echo "</pre>"; // exit;
	include("search_form.php");
	}
?>