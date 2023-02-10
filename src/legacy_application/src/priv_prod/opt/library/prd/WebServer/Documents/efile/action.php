<?php
ini_set('display_errors',1);
// echo "<pre>"; print_r($_REQUEST); echo "</pre>";
// echo "4<pre>"; print_r($new_parkCode); echo "</pre>"; // exit;
$display_add="none";
$display_search="none";
if(!empty($cat_id))
	{
	$sql="SELECT t1.*, t2.cat_name, t2.cat_subject 
	from documents as t1
	LEFT JOIN efile_cat as t2 on t1.cat_id=t2.id
	where t2.id='$cat_id' limit 1";
// 	echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	while($row=mysqli_fetch_assoc($result))
		{
		$ARRAY[]=$row;
		}
	}

if(!empty($doc_id))
	{
//	echo "<pre>"; print_r($parkCode); echo "</pre>"; // exit;
	$sql="SELECT t1.*, t2.cat_name, t2.cat_subject, t3.file_link, t3.size
	from documents as t1
	LEFT JOIN efile_cat as t2 on t1.cat_id=t2.id
	LEFT JOIN file_links as t3 on t1.doc_id=t3.doc_id
	where t1.doc_id='$doc_id'"; 
// 	echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	while($row=mysqli_fetch_assoc($result))
		{
		$ARRAY[]=$row;
		if(array_key_exists($row['park_code'],$parkCode))
			{
			@$new_parkCode[$row['park_code']]=$parkCode[$row['park_code']];
			}
		}
	}
	else
	{$new_parkCode=$parkCode;}
	
// echo "42<pre>"; print_r($new_parkCode); echo "</pre>"; // exit;
//echo "<pre>"; print_r($ARRAY); echo "</pre>";	
if(@$search=="Search")
	{
//	echo "line 35 <pre>"; print_r($_REQUEST); echo "</pre>";
	$search_these=array("title","abstract");
	$where="where 1 ";

	foreach($_POST AS $fld=>$value)
		{
		if(!in_array($fld,$search_these) OR $value=="")
			{continue;}
		@$clause.=$fld." like '%".$_POST[$fld]."%' OR ";
		}
		if(!empty($clause))
		{
		$clause=" and (".rtrim($clause," OR ").") ";
		$where.=$clause;
		}
	@$where.=" AND cat_id='".$_POST['search_cat_id']."' ";
	
	if(!empty($_POST['park_code']))
		{
		$where.="AND park_code='$_POST[park_code]'";
		}
	if(!empty($_POST['guideline_group']))
		{
		$where.="AND guideline_group='$_POST[guideline_group]'";
		}
	if(!empty($_POST['added_by']))
		{
		$where.=" AND added_by like '%$_POST[added_by]%'";
		}
	if($level<3)
		{
	//	$where.=" and right(t3.file_link,3)='pdf'";
		}
	$sql="SELECT t1.doc_id, t1.cat_id, t1.park_code, t1.title, concat(left(t1.abstract,150),'...') as abstract, t1.web_link, t1.clemson_id, t1.guideline_group, t1.added_by, t2.cat_name, t2.cat_subject, t3.file_link, t3.size
	from documents as t1
	LEFT JOIN efile_cat as t2 on t1.cat_id=t2.id
	LEFT JOIN file_links as t3 on t3.doc_id=t1.doc_id
	$where
	order by t1.title"; 
//	echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
//	echo "$sql"; //exit;
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	$g_num=mysqli_num_rows($result);
	while($row=mysqli_fetch_assoc($result))
		{
		if($level<3 and $row['guideline_group']>0 and substr($row['file_link'],-3)!='pdf'){continue;}
		$ARRAY[]=$row;
		}
	IF(!empty($ARRAY)>0)
		{
		$cat_name=$ARRAY[0]['cat_name'];
		$cat_subject=$ARRAY[0]['cat_subject'];
		$cat_id=$ARRAY[0]['cat_id'];
		$guideline_group=$ARRAY[0]['guideline_group'];
		$where=str_replace("%","",$where);
		$where=str_replace("cat_id=","",$where);
		$where=str_replace($cat_id,($cat_name."=".$cat_subject),$where);
		echo "<div align='center'>File(s) found using <font color='red'>$where</font></div>";
		$display_search="block";
		}
		else
		{
		$where=str_replace("%","",$where);
		echo "<div align='center'>No file found using <font color='red'>$where</font></div>";
		$display_search="block";
		$cat_id=$search_cat_id;
		}
	}

// echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;
if(empty($ARRAY))
	{
	$sql="SELECT t2.cat_name, t2.cat_subject
	from efile_cat as t2
	where t2.id='$cat_id'";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	while($row=mysqli_fetch_assoc($result))
		{
		$ARRAY[]=$row;
		}
	$fieldArray=array("park_code","title","abstract","file_link","web_link","clemson_id");
	}
else
	{
	$fieldArray=array_keys($ARRAY[0]);
	if(!in_array("file_link",$fieldArray)){$fieldArray[]="file_link";}
	}

// echo "<pre>";print_r($ARRAY);  print_r($fieldArray); echo "</pre>$sql"; //exit;

$cat=$ARRAY[0]['cat_name'];
$sub=$ARRAY[0]['cat_subject'];

// Form Header
echo "<div id='add_form' align='center'><table border='1' cellpadding='5'>";

echo "<tr><td align='center' colspan='2'><font color='brown'>$cat</font> &nbsp;&nbsp;&nbsp;&nbsp;<----->&nbsp;&nbsp;&nbsp;&nbsp;<font color='green'>$sub</font>
<a onclick=\"toggleDisplaySwap('show_form');\" href=\"javascript:void('')\"><br />Add</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick=\"toggleDisplaySwap('search_form');\" href=\"javascript:void('')\">Search</a></td>";

echo "</tr></table>
</div>";


$rename=array("previous"=>"Previous Park");

// Input Form
echo "<div align='center' id=\"show_form\" style=\"display: $display_add\">
<table border='1' cellpadding='5'><tr><form name='frmTest' action=\"file_upload.php\" method=\"post\" enctype=\"multipart/form-data\"><td align='center' colspan='2'></td>";
if(!empty($doc_id))
	{
	echo "<td>doc_id=$doc_id</td>";
	}
echo "</tr>";

$skip_add=array("doc_id","cat_id","cat_name","cat_subject","size");
$skip_search=array("doc_id","cat_id","file_link","cat_name","cat_subject","size","web_link");
if(isset($pass_cat_id)){$cat_id=$pass_cat_id;}
foreach($fieldArray as $k=>$v)
	{
	if(in_array($v,$skip_add)){continue;}
	$size=100;
//	if($v=="web_link"){$size=50;}
	$input="<input type='text' size='$size' name='$v'>";
	if($v=="abstract")
		{
		$input="<textarea name='$v' rows='7' cols='100'></textarea>";
		}
	if($v=="file_link")
		{
		$input="<input type='file' name='file_upload[]'  size='40'><br />";
		$input.="<input type='file' name='file_upload[]'  size='40'><br />";
		$input.="<input type='file' name='file_upload[]'  size='40'><br />";
		$input.="<input type='file' name='file_upload[]'  size='40'>";
		}
	if($v=="park_code")
		{
		$input="<select name='$v'><option selected=''></option>\n";
		foreach($new_parkCode as $k1=>$k2)
			{
			$input.="<option value='$k1'>$k1-$k2</option>\n";
			}
		$input.="</select>";
		}
	echo "<tr>
	<td>$v</td>";
	
	if($v=="guideline_group" and ($cat_id==4 or @$seach_cat_id==4))
		{
//	echo "<pre>"; print_r($guideline_group_array); echo "</pre>"; // exit;
		$input="<select name='$v'><option selected=''></option>\n";
		$i=0;
		foreach($guideline_group_array as $k1=>$k2)
			{	$i++;
			$input.="<option value='$k1'>$i-$k2</option>\n";
			}
		$input.="</select>";
		}
	echo "<td>$input</td>
	</tr>";
	}

if(!isset($cat_id)){$cat_id=$ARRAY[0]['cat_id'];}
echo "<tr><td align='center' colspan='2' bgcolor='lightgreen'>
<input type='hidden' name='tempID' value='$tempID'>
<input type='hidden' name='cat_id' value='$cat_id'>
<input type='submit' name='add' value='Add'>
</td></tr>";
echo "</table></form></div>";


// Search Form
echo "<div align='center' id=\"search_form\" style=\"display: $display_search\"><table border='1' cellpadding='5'><tr><form name='frmSearch' action=\"files.php\" method=\"post\"><td align='center' colspan='2'></td></tr>";
//echo "<pre>"; print_r($fieldArray); echo "</pre>"; // exit;
foreach($fieldArray as $k=>$v)
	{
	if(in_array($v,$skip_search)){continue;}
	$input="<input type='text' size='30' name='$v' value=''>";
	
	if($v=="park_code")
		{
		$input="<select name='$v'><option value selected=''>Click \"Search\" to see all $num_docs_for_cat_id docs or pick a park.</option>\n";
		foreach($parkCode as $k1=>$k2)
			{		
			$input.="<option value='$k1'>$k1-$k2</option>\n";
			}
		$input.="</select>";
		}
	echo "<tr>
	<td>$v</td>";
	
	if($v=="guideline_group" and ($cat_id==4 or @$seach_cat_id==4))
		{
//	echo "<pre>"; print_r($guideline_group_array); echo "</pre>"; // exit;
		$input="<select name='$v'><option selected=''></option>\n";
		$i=0;
		foreach($guideline_group_array as $k1=>$k2)
			{	$i++;
			$input.="<option value='$k1'>$i-$k2</option>\n";
			}
		$input.="</select>";
		}
	echo "<td>$input</td>
	</tr>";
	}

if(isset($pass_cat_id)){$cat_id=$pass_cat_id;}
if(!isset($cat_id)){$cat_id="";}
echo "<tr><td align='center' colspan='2' bgcolor='aliceblue'>
<input type='hidden' name='search_cat_id' value='$cat_id'>
<input type='submit' name='search' value='Search'></td></tr>";
echo "</table></form></div>";

$skip=array("cat_id","cat_name","cat_subject");

		
if(@$search=="Search" AND !empty($cat_id))
		{
	//	echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;
		//$g_num=count($ARRAY);
	echo "<table class='tablecontainer' border='1' cellpadding='3'>";
	if(!empty($_POST['park_code']) or !empty($_POST['title']) or !empty($_POST['abstract']) or !empty($_POST['clemson_id']) or !empty($_POST['guideline_group']) or !empty($_POST['added_by']))
		{
		$num_docs_for_cat_id=$g_num;
		}
	echo "<tr><td colspan='10'>Number records: $num_docs_for_cat_id</td></tr>";
	echo "<tr id='headercontainer'>";
	foreach($ARRAY[0] AS $fld=>$val)
		{
		if(in_array($fld,$skip)){continue;}
		$fld=str_replace("_"," ",$fld);
		echo "<td>$fld</td>";
		}
	echo "</tr>";
//echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;
	foreach($ARRAY AS $index=>$array)
		{
		echo "<tr>";
		foreach($array as $fld=>$value)
			{
			if(in_array($fld,$skip)){continue;}
			if($fld=="doc_id")
				{
				if($level>2 or ($tempID==$ARRAY[$index]['added_by']))
					{$value="<a href='files.php?pass_cat_id=$cat_id&doc_id=$value'>Edit Record</a> $value";}
				}
			if($fld=="web_link")
				{
				$value="<a href='$value' target='_blank'>$value</a>";
				}
			if($fld=="file_link")
				{
				$value="<a href='$value' target='_blank'>$value</a>";
				}			
			if($fld=="size")
				{
				$size=round($array[$fld]/1000);
				$value="$size KB";
				if($size>999)
					{
					$size=round($size/1000);
					$value="$size MB";
					}
				}				
			if($fld=="added_by")
				{
				$value=substr($value,0,-3);
				}
				
			echo "<td valign='top'>$value</td>";
			}
		echo "</tr>";
		}
	echo "</table>";
	}

$skip=array("doc_id","cat_id","cat_name","cat_subject","size");
echo "<table align='center'>
<form action='file_upload.php' method='POST' enctype=\"multipart/form-data\">";
// send this file_upload.php and not update.php

if(!empty($doc_id))
	{
//	echo "<pre>"; print_r($ARRAY); echo "</pre>";

	foreach($ARRAY AS $index=>$array)
		{
		if($index>0){continue;}
		foreach($array as $fld=>$value)
			{
			if(in_array($fld,$skip)){continue;}
			$ro="";
			if($fld=="added_by")
				{
				$value=$_SESSION['efile']['tempID'];
				$ro="READONLY";
				}
				
			$input="$fld<br /><input type='text' name='$fld' value=\"$value\" size='105' $ro>";
			
			if($fld=="file_link" AND !empty($array[$fld]))
				{
				$input="";
				foreach($ARRAY as $var_k=>$var_array)
					{
					extract($var_array);
					$size=round($size/1000);
					$size="size $size KB";
					if($size>999)
						{
						$size=round($size/1000);
						$size="size $size MB";
						}
					$input.="<a href='$file_link' target='_blank'>View</a> File $size
					<br />[$file_link]";
					if($level>1)
						{
					$input.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href='unlink.php?file=$file_link&doc_id=$doc_id' onClick='return confirmLinkFile()'>Delete</a> File<br /><br />";}
					}
					
		//		$input.="<input type='file' name='file_upload[]'  size='40'><br />";
				$input.="<input type='file' name='file_upload[]'  size='40'><br />";
				}
			
			if($fld=="file_link"AND empty($array[$fld]))
				{
				$input="$fld<br /><input type='file' name='file_upload[]'  size='40'>";
				}
				
			if($fld=="guideline_group")
				{
				$i=0;
				$input="guideline_group <select name='$fld'><option selected=''></option>\n";
				foreach($guideline_group_array as $k1=>$k2)
					{
					$i++;
					if($array['guideline_group']==$k1){$s="selected";}else{$s="";}
					$input.="<option value='$k1' $s>$i-$k2</option>\n";
					}
				$input.="</select>";
				}
			if($fld=="park_code")
				{
				$input="park <select name='$fld'><option selected=''></option>\n";
				foreach($parkCode as $k1=>$k2)
					{
					if($array['park_code']==$k1){$s="selected";}else{$s="";}
					$input.="<option $s='$k1'>$k1-$k2</option>\n";
					}
				$input.="</select>";
				}
				
			if($fld=="abstract")
				{
				$input="$fld<br /><textarea name='$fld' rows='7' cols='100'>$value</textarea>";
				}
				
			echo "<tr><td valign='top'>$input</td></tr>";
			}
		}

	echo "<tr><td>";
	if(!empty($pass_link))
		{
		echo "<input type='hidden' name='uploadfile' value='$pass_link'>";
		}
	
	echo "
	<input type='hidden' name='cat_id' value='$cat_id'>
	<input type='hidden' name='doc_id' value='$doc_id'>
	<input type='submit' name='submit' value='Update'>
	</td>
	<td><input type='hidden' name='doc_id' value='$doc_id'>
	<input type='submit' name='submit' value='Delete' onClick=\"return confirmLink()\"></td>
	</tr>";
	echo "</form></table>";
	}
echo "</html>";

?>