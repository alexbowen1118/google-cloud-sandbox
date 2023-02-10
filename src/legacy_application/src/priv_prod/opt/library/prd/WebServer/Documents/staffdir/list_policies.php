<?php
ini_set('display_errors',1);
$database="staffdir";
include("../../include/auth.inc");// database connection parameters
//echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
include("../../include/iConnect.inc");// database connection parameters

mysqli_select_db($connection, $database)
       or die ("Couldn't select database $database");
$pass_title="Policies - Staff Directives/Guidelines"; 
include("/opt/library/prd/WebServer/Documents/efile/_base_top_efile.php");

echo "<table align='center'><tr><td colspan='2'><h3>NC DPR Policies - Staff Directives and Guidelines</h3></td>";

if($level>2){echo "<td><a href='menu.php'>Staff Directives</a></td>";}

echo "</tr></table>";

$order_by="policy_category,directive desc,guideline";
$where="where 1";
if(@$sort=="pc")
	{$order_by="policy_category,directive,guideline";}
if(@$sort=="dir")
	{
	$where.=" and directive!=''";
	$order_by="directive desc,policy_category";
	}
if(@$sort=="gl")
	{
	$where.=" and guideline!=''";
	$order_by="concat(policy_category,directive)";
	}
$sql="SELECT t1.*, t2.link, t3.file_link
from policy as t1 
left join map as t2 on t1.mid=t2.mid 
left join efile.file_links as t3 on t1.guideline=t3.doc_id and t3.file_link like '%.pdf'
$where
order by $order_by";  
//  echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute select query. $sql ".mysqli_error($connection));
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	}
//echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;
$skip=array("mid","link","alt_directive","file_link");

$c=count($ARRAY);
echo "<table border='1' cellpadding='5'><tr><td colspan='7'><font color='magenta'>$c Titles</font> Click \"directive\" to only view Staff Directives or \"guideline\" to only view Guidelines.</td></tr>";
$c=count($ARRAY);
foreach($ARRAY AS $index=>$array)
	{
	if($index==0)
		{
		echo "<tr>";
		foreach($ARRAY[0] AS $fld=>$value)
			{
			if(in_array($fld,$skip)){continue;}
			$var_fld=$fld;
			if($fld=="policy_category")
				{
				$var_fld="<a href=list_policies.php?sort=pc>$fld</a>";
				}
			if($fld=="guideline")
				{
				$var_fld="<a href=list_policies.php?sort=gl>$fld</a>";
				}
			if($fld=="directive")
				{
				$var_fld="<a href=list_policies.php?sort=dir>$fld</a>";
				}
			echo "<th>$var_fld</th>";
			}
		echo "</tr>";
		}
	$tr="";
	if(empty($ARRAY[$index]['link']) and empty($ARRAY[$index]['file_link'])){$tr=" bgcolor='aliceblue'";}
	echo "<tr$tr>";
	foreach($array as $fld=>$value)
		{
		if(in_array($fld,$skip)){continue;}
		if($fld=="id" and $level>2)
			{
			$value="<a href='edit_policy.php?id=$value'>[&nbsp;$value&nbsp;]</a>";
			}
		if($fld=="directive" and $value!="" and $level>2)
			{
			$value="<a href='/staffdir/adminMenu.php?admin=edit&dirNum=$value'>$value</a>";
			}
		
		if($fld=="document_link")
			{
			$value="";
			if(!empty($array['link']))
				{
				$link=$array['link'];
				$value="<a href='/staffdir/$link'>Directive</a><br />";
				}
			if(!empty($array['file_link']))
				{
				$link=$array['file_link'];
				$title_fld=$array['title/description'];
				$link_name="Guideline";
				if(strpos($link, "Procedure")>-1)
					{
					$link_name="Procedure";
					}
				if(strpos($title_fld, "Procedure")>-1)
					{
					$link_name="Procedure";
					}
				$value.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='/efile/$link'>$link_name</a>";
				}
			}
			
		if($fld=="FIND" and !empty($value))
			{
			$value="<a href='$value' target='_blank'>FIND link</a>";
			}
		echo "<td align='left'>$value</td>";
		}
	echo "</tr>";
	}
echo "</table>";

echo "</body></html>";
?>