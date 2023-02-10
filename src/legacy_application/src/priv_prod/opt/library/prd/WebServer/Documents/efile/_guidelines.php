<?php

$database="efile";
include("../../include/auth.inc");// database connection parameters
//echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
include("../../include/iConnect.inc");// database connection parameters
$db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database $database");
$title="eFile"; 
include("/opt/library/prd/WebServer/Documents/efile/_base_top_efile.php");

echo "<table align='center' cellpadding='10'><tr><td colspan='2'><h2>NC DPR Guidelines</h2></td></tr>";

$path="/efile/";

$menu_array[]="";

//$menu_array['Search Guidelines']=$path."search.php";


$menu_color=array("#EE82EE","#E9967A","#009900","#FFE4C4","#DAA520","#48D1CC","#C6E2FF","#FFB6C1","#FFFF66","#7FFFD4");

if($level>1)
	{
	//$menu_array['PAC Meeting Calendar']=$path."cal.php";
	}
	
if($level>3)
	{
//	$menu_array['PAC Meeting Calendar']=$path."cal.php";
	
//	$menu_array['PAC Summary']=$path."pac_summary.php";
	
	}
//echo "<table cellpadding='10' align='center'>";
$i=0;
foreach($menu_array as $k=>$v)
	{
	if(empty($v)){continue;}
		$color=$menu_color[$i]; $i++;
		if($k=="Guidelines/Instructions" || $k=="Instructions")
			{
			$v=htmlentities($v);
			echo "<tr><td align='left'><FORM method='POST' action=\"$v\" target=\"_blank\">
	<INPUT type=submit value=\"$k\" style=\"background-color:$color; font-size:larger\"></FORM></td></tr>";
			}
		else
		{
		$target="";
		echo "<tr><td align='left'>
		<form action='$v' $target>
		<input type='submit' name='submit' value='$k'  style=\"background-color:$color; font-size:larger\"></form>
		</td>";
		if($k=="Nominate a new PAC Member")
			{echo "<td align='left'>Normally this will be for a 1-year term.</td>";}
		if($k=="Renomination for an additional term")
			{echo "<td>Normally this will be for a 3-year term.</td>";}
		echo "</tr>";
		}
	}

	echo "</table>";
$sql="SELECT t1.*, t2.doc_id as doc_upload, t3.file_link, right(t3.file_link,4) as pdf_file
from guideline_index as t1
left join documents as t2 on t1.doc_id=t2.doc_id
left join file_links as t3 on t1.doc_id=t3.doc_id
where 1 
order by t1.guideline";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute select query. $sql ".mysqli_error($connection));
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	}

$skip=array("file_link","pdf_file","doc_upload","doc_id");
//$skip=array();
$c=count($ARRAY);
echo "<table><tr><td colspan='4'>$c entries</td></tr>";
foreach($ARRAY AS $index=>$array)
	{
	if($index==0)
		{
		echo "<tr>";
		foreach($ARRAY[0] AS $fld=>$value)
			{
			if(in_array($fld,$skip)){continue;}
			echo "<th>$fld</th>";
			}
		echo "</tr>";
		}
	echo "<tr>";
	foreach($array as $fld=>$value)
		{
		if(in_array($fld,$skip)){continue;}
		if($ARRAY[$index]['pdf_file']=="docx"){continue;}
		if($ARRAY[$index]['pdf_file']==".doc"){continue;}
		
		if($ARRAY[$index]['pdf_file']==".pdf" and $fld=="guideline_title")
			{
			$link=$ARRAY[$index]['file_link'];
			$value="<a href='$link' target='_blank'>$value</a>";
			}
		echo "<td>$value</td>";
		}
	echo "</tr>";
	}
echo "</table>";
	

echo "</body></html>";
?>