<?php
//ini_set('display_errors',1);

$database="dpr_system";
include("../include/connectROOT.inc");
extract($_REQUEST);

mysql_select_db('dpr_system',$connection);
if(!empty($find_db))
	{
	$sql="SELECT id,cat_name from home_page where db_name like '% $find_db%'";
	$result = mysql_query($sql) or die ("Couldn't execute query. $sql");
	while($row=mysql_fetch_assoc($result))
		{
		$ARRAY_find_cat[$row['id']]=$row['cat_name'];
		}
	
	$sql="SELECT cat_name,cat_subject from home_page_subject where cat_subject like '%$find_db%'";
	$result = mysql_query($sql) or die ("Couldn't execute query. $sql");
	while($row=mysql_fetch_assoc($result))
		{
		$ARRAY_find_subject[]=$row['cat_name'];
		}
//	echo "<pre>"; print_r($ARRAY_find_subject); echo "</pre>$sql<br />";
	}

$display="none";
$sql="SELECT * from home_page where 1 order by cat_name, db_name";
if(isset($ARRAY_find_subject))
	{
	$display="block";
	$term="and (";
	foreach($ARRAY_find_subject as $k=>$v)
		{
		@$term.="cat_name='$v' OR ";
		}
	$term=rtrim($term,' OR ').")";
	$sql="SELECT * from home_page where 1 $term order by cat_name, db_name";
//	echo "$sql";
	}
$result = mysql_query($sql) or die ("Couldn't execute query. $sql");
while($row=mysql_fetch_assoc($result))
	{
	$ARRAY[$row['id']]=$row;
	$cat_array[$row['id']]=$row['cat_name'];
	}

$sql="SELECT * from home_page_subject where 1";
$result = mysql_query($sql) or die ("Couldn't execute query. $sql");
while($row=mysql_fetch_assoc($result))
	{
	$ARRAY_subject[$row['id']]=$row;
//	$subject_array[$row['id']]=$row['cat_subject'];
	$cat_subject_array[$row['id']]=$row['cat_name'];
	}
	
//echo "<pre>"; print_r($ARRAY); echo "</pre>";
//echo "<pre>"; print_r($cat_array); echo "</pre>";

//echo "<pre>"; print_r($cat_subject_array); echo "</pre>";
//echo "<pre>"; print_r($ARRAY_subject); echo "</pre>";

$title="List of Databases";
include("inc/_base_top_dpr.php");

if(!empty($find_db)){$all="- <a href='home.php'>Show all</a>";}else{$all="";}
echo "<table cellpadding='5' border='1' align='center'><tr><td colspan='3'><b>NC DPR Databases<b> $all</td><td colspan='2'><form>Search: <input type='text' name='find_db'> <input type='submit' name='submit' value='Find'></form></td></tr>";

$check="";
echo "<tr valign='top'>";
foreach($ARRAY as $id=>$array)
	{
	$cat=$array['cat_name'];
	if($cat==$check){continue;}
	@$i++;
	$category=array_keys($cat_array,$cat);
//	echo "<pre>"; print_r($category); echo "</pre>"; exit;
	echo "<td valign='top'><b>$cat</b><br />";
	foreach($category as $k1=>$v1)
		{
		$var_2=$ARRAY[$v1]['db_name'];
		$db=$ARRAY[$v1]['db'];
		$web_link=$ARRAY[$v1]['web_link'];
		$link=$var_2;
		if(!empty($db))
			{
			$file="/".$db."/index.html";
			$link="<a href='$file'>$var_2</a>";
			}
		
		if(!empty($web_link))
			{
			$link="<a href='$web_link'>$var_2 website</a>";
			}
			
		$subject=array_keys($cat_subject_array,$cat);
//		echo "<pre>"; print_r($subject); echo "</pre>"; exit;
		$content=array();
		foreach($subject as $k2=>$v2)
			{
			if($ARRAY_subject[$v2]['db']==$db)
				{
				$content[]=$ARRAY_subject[$v2]['cat_subject'];
				}
			}
		sort($content);
		$content1="";
		foreach($content as $k3=>$v3){$content1.=$v3."<br />";}
		$toggle="<div id=\"fieldName\" align='right'><a onclick=\"toggleDisplay('fieldDetails[$v1]');\" href=\"javascript:void('')\">---</a></div>";
		$color="brown";
		if(@strpos(strtolower($content1),strtolower($find_db))>-1){$color="red";}
		$toggle.="<div id=\"fieldDetails[$v1]\" style=\"display: $display\"><font color='$color'>$content1</font></div>";
		
		echo "&nbsp;&nbsp;$link $toggle";
		}
	echo "</td>";
	$check=$cat;
	if(fmod($i,5)==0){echo "</tr><tr>";}
	}
echo "</tr>";

echo "</table>";
?>
