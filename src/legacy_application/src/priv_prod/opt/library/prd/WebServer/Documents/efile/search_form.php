<?php
ini_set('display_errors',1);

//echo "<pre>"; print_r($ARRAY_cat_subject); echo "</pre>";
foreach($ARRAY_cat_subject as $key=>$array)
	{
	$cat_array[]=$key;
	}
sort($cat_array); 
echo "<form><table align='center' border='1'>";

echo "<tr><th colspan='2'>Search Form</th></tr>";

echo "<tr><td>
Category:</td><td><select name='cat_name'><option selected=''></option>\n";
foreach($cat_array as $index=>$cat)
	{
	$check_cat_array=$ARRAY_cat_subject[$cat];
	$check="";
	foreach($ARRAY_cat_id_docs as $var_i=>$var_v)
		{
		if(!array_key_exists($var_v,$check_cat_array)){$check=1;}		
		}
//	if($check==1){continue;}
	if($cat==@$cat_name){$s="selected";}else{$s="value";}
	echo "<option $s='$cat'>$cat</option>\n";
	}
echo "</select></td></tr>";

echo "<tr><td>Park Code:</td><td><select name='park_code'><option selected=''></option>\n";
foreach($parkCode as $k1=>$k2)
	{
	if(!in_array($k1,$ARRAY_park_code_docs)){continue;}
	if(@$park_code==$k1){$s="selected";}else{$s="value";}
	echo "<option $s='$k1'>$k1-$k2</option>\n";
	}
echo "</select></td></tr>";

echo "<tr><td>Title: </td><td><input type='text' name='title'></td></tr>";

echo "<tr><td>Abstract: </td><td><input type='text' name='abstract'></td></tr>";

echo "<tr><td colspan='2' align='center'>
<input type='hidden' name='search' value='all'>
<input type='submit' name='submit' value='Search'>
</td></tr>";

echo "</table></form>";

$id_list="";
if(!empty($cat_name))
	{
	$id_list="and (";
	$sql="SELECT id from efile_cat where cat_name='$cat_name'";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	while($row=mysqli_fetch_assoc($result))
		{
		@$id_list.="t1.cat_id='".$row['id']."' OR ";
		}
	$id_list=rtrim($id_list," OR ").")";
	}

if(!empty($park_code))
	{
	@$id_list.=" AND (t1.park_code='$park_code')";
	}

if(!empty($title))
	{
	@$id_list.=" AND (t1.title like '%$title%')";
	}
	
if(!empty($abstract))
	{
	@$id_list.=" AND (t1.abstract like '%$abstract%')";
	}
	
$sql="SELECT t1.*, t2.cat_name, t2.cat_subject, t3.file_link, t3.size
from documents as t1
LEFT JOIN efile_cat as t2 on t1.cat_id=t2.id
LEFT JOIN file_links as t3 on t1.doc_id=t3.doc_id
where 1 $id_list";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	}

@$num=count($ARRAY);

if(empty($cat_name) AND empty($park_code) AND empty($title) AND empty($abstract))
	{exit;}
if(!empty($title) AND $title=="EFILE")
	{exit; /// this hack is necessary because the _base_top_dpr.php file needs a var named "title"
	}

if(empty($ARRAY))
	{
	if(!empty($cat_name))
		{$cat_name="Category like $cat_name.";}
	if(!empty($park_code))
		{$park_code="Park like $park_code.";}
	if(!empty($title))
		{$title="Title like $title.";}
	if(!empty($abstract))
		{$abstract="Abstract like $abstract.";}
	ECHO "&nbsp;&nbsp;&nbsp;&nbsp;No file was found for <b>$cat_name $park_code $title $abstract</b>";
//	echo "n=$cat_name p=$park_code t=$title a=$abstract";
	exit;
	}

//echo "<pre>"; print_r($ARRAY); echo "</pre>";
$skip=array("doc_id","cat_id");
$rename=array("cat_name"=>"category","cat_subject"=>"subject");

echo "<table border='1' class='tablecontainer' align='center' cellpadding='5'>";
echo "<tr><td colspan='10'>$num records</td></tr><tr>";
foreach($ARRAY[0] AS $fld=>$value)	
	{
	if(in_array($fld,$skip)){continue;}
	if(array_key_exists($fld,$rename)){$fld=$rename[$fld];}
	$fld=str_replace("_"," ",$fld);
	echo "<th>$fld</th>";
	}
echo "</tr>";
//echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;
foreach($ARRAY AS $index=>$array)	
	{
	echo "<tr>";
	foreach($array as $fld=>$value)
		{
		if(in_array($fld,$skip)){continue;}
		if($fld=="file_link" and !empty($value))
			{
			$var_file=explode("/",$value);
			$file=array_pop($var_file);
			$file=str_replace("_"," ",$file);
			$value="&nbsp;<a href='$value'>link</a><br />$file";
			}
		if($fld=="web_link" and !empty($value))
			{$value="&nbsp;<a href='$value'>link</a>";}
		if($fld=="abstract" and !empty($value))
			{
			if(strlen($value)>100)
				{
				$temp=substr($value,0,100);
				$var="<a onclick=\"toggleDisplay('$fld');\" href=\"javascript:void('')\">show/hide</a>
				$temp
				<div id=\"$fld\" style=\"display: none\">$value</div>";
				$value=$var;
				}
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
			if($size==0){$value="";}
			}					
		if($fld=="added_by")
			{
			$value=substr($value,0,-3);
			}
		echo "<td valign='top'>$value</td>";
		}
	echo "</tr>";
	}
?>