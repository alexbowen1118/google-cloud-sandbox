<?php
$dbTable="categories_day";
$file="cat_day.php";
$fileMenu="../menu.php";

include("../../../include/iConnect.inc");// database connection parameters

include("$fileMenu");
include("../../../include/get_parkcodes_reg.php");
include("park_code_areas.php"); // get subunits

$database="park_use";
mysqli_select_db($connection,$database);

// Get Fields for the Park
if(!empty($parkcode))
	{
	$sql = "SELECT t1.*,mod01,mod02,mod03,mod04,mod05,mod06,mod07,mod08,mod09,mod10,mod11,mod12,submodifier 
	FROM categories_day_test as t1
	left join park_category_day as t2 on t1.category_id=t2.category
	where t2.park_id='$parkcode' order by t1.sort_order";
// 	echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	while ($row=mysqli_fetch_array($result))
		{
		$cat_num[]=$row[0];
		$fieldName[]=$row[1];
		$catArray[$row[0]]=$row[2];
		
		$mod01Array[$row[0]]=$row[3];
		$mod02Array[$row[0]]=$row[4];
		$mod03Array[$row[0]]=$row[5];
		$mod04Array[$row[0]]=$row[6];
		
		$mod05Array[$row[0]]=$row[7];
		$mod06Array[$row[0]]=$row[8];
		$mod07Array[$row[0]]=$row[9];
		$mod08Array[$row[0]]=$row[10];
		$mod09Array[$row[0]]=$row[11];
		$mod10Array[$row[0]]=$row[12];
		$mod11Array[$row[0]]=$row[13];
		$mod12Array[$row[0]]=$row[14];
		
		$submodArray[$row[0]]=$row[15];
		
		}
//	echo "$sql<br />";
	//print_r($jun2augArray);exit;
	
	// Get all possible Fields
	$sql = "SELECT * FROM categories_day_test order by sort_order";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql".mysqli_error($connection));
	while ($row=mysqli_fetch_assoc($result))
		{
		$cat_id[]=$row['Category_ID'];
		$cat_name[]=$row['fld_name'];
		$cat_fld[]=$row['Category_Desc'];
// echo "$sql<pre>"; print_r($row); echo "</pre>";  exit;
		}
	}
// *************** Show Form ****************
// Headers
echo "<div align='center'><table cellpadding='11'><tr><th>Categories for <select name='parkcode' onChange=\"MM_jumpMenu('parent',this,0)\"><option selected=''></option>";

foreach($parkCode as $k=>$v)
	{
	if(in_array($v,$multi_area)){continue;}
	if($v==$parkcode)
		{$s="selected";}
		else
		{
		$s="value";
		}
	echo "<option $s='cat_day.php?parkcode=$v'>$v</option>\n";
	  }
echo "</select></form></td>";

if(isset($parkcode)){echo "<td>$parkCodeName[$parkcode]</td>";}

echo "</tr></table>";

if(empty($parkcode)){exit;}
//print_r($fieldName);exit;

$c1="";$d1="";$m1="";$m2="";$m3="";$m4="";$m5="";$m6="";$m7="";$m8="";$m9="";$m10="";$m11="";$m12="";$m13="";

// Column 1 
echo "<form action='update_park_cat_day.php' method='post'><table border='1'>";
echo "<tr><th colspan='15' align='center'>Modifiers</th></tr>
<tr><th>Add</th><th align='left'>Remove</th>
<th>Jan</th><th>Feb</th><th>Mar</th><th>Apr</th>
<th>May</th><th>Jun</th><th>Jul</th><th>Aug</th>
<th>Sep</th><th>Oct</th><th>Nov</th><th>Dec</th>
<th>Modifier Field</th></tr>";
for($i=0;$i<count($cat_id);$i++)
	{
	
	if(@in_array($cat_id[$i],@$cat_num))
		{
		$c=$catArray[$cat_id[$i]];
		$ck="checked";
		$nck="";
		$f1="<font color='green'>";$f2="</font>";
		}
		else
		{$ck="";$nck="checked";
	$f1="<font color='red'>";$f2="</font>";}
	
	$c1="<input type='radio' name='fld_id[$cat_id[$i]]' value='add' $ck>";
	$d=$cat_id[$i]." - ".$cat_fld[$i];
	$d1="<input type='radio' name='fld_id[$cat_id[$i]]' value='del' $nck> $f1$d$f2";
	
	$fldName="";
	if(strpos($cat_fld[$i],"DAY")>-1)
		{
		$fldName=$cat_name[$i];
		$m1v=@$mod01Array[$cat_id[$i]];
		$m1="<input type='text' name='mod_id_01[$cat_id[$i]]' value='$m1v' size='6'>";
		$m2v=@$mod02Array[$cat_id[$i]];
		$m2="<input type='text' name='mod_id_02[$cat_id[$i]]' value='$m2v' size='6'>";
		$m3v=@$mod03Array[$cat_id[$i]];
		$m3="<input type='text' name='mod_id_03[$cat_id[$i]]' value='$m3v' size='6'>";
		$m4v=@$mod04Array[$cat_id[$i]];
		$m4="<input type='text' name='mod_id_04[$cat_id[$i]]' value='$m4v' size='6'>";
		
		$m5v=@$mod05Array[$cat_id[$i]];
		$m5="<input type='text' name='mod_id_05[$cat_id[$i]]' value='$m5v' size='6'>";
		$m6v=@$mod06Array[$cat_id[$i]];
		$m6="<input type='text' name='mod_id_06[$cat_id[$i]]' value='$m6v' size='6'>";
		$m7v=@$mod07Array[$cat_id[$i]];
		$m7="<input type='text' name='mod_id_07[$cat_id[$i]]' value='$m7v' size='6'>";
		$m8v=@$mod08Array[$cat_id[$i]];
		$m8="<input type='text' name='mod_id_08[$cat_id[$i]]' value='$m8v' size='6'>";
		$m9v=@$mod09Array[$cat_id[$i]];
		$m9="<input type='text' name='mod_id_09[$cat_id[$i]]' value='$m9v' size='6'>";
		$m10v=@$mod10Array[$cat_id[$i]];
		$m10="<input type='text' name='mod_id_10[$cat_id[$i]]' value='$m10v' size='6'>";
		$m11v=@$mod11Array[$cat_id[$i]];
		$m11="<input type='text' name='mod_id_11[$cat_id[$i]]' value='$m11v' size='6'>";
		$m12v=@$mod12Array[$cat_id[$i]];
		$m12="<input type='text' name='mod_id_12[$cat_id[$i]]' value='$m12v' size='6'>";
		
		
		$m13v=@$submodArray[$cat_id[$i]];
		$m13="<input type='text' name='mod_id_submod[$cat_id[$i]]' value='$m13v' size='12'>";
		}
	
	echo "<tr><td>$c1</td><td>$d1 $fldName</td>
	<td>$m1</td><td>$m2</td><td>$m3</td><td>$m4</td><td>$m5</td>
	<td>$m6</td><td>$m7</td><td>$m8</td><td>$m9</td><td>$m10</td>
	<td>$m11</td><td>$m12</td><td>$m13</td>
	</tr>";
	$c1="";$d1="";$m1="";$m2="";$m3="";$m4="";$m5="";$m6="";$m7="";$m8="";$m9="";$m10="";$m11="";$m12="";$m13="";
	}
echo "</table>";

$parkPass=$parkcode;
if(!isset($y)){$y="";}
if(!isset($passM)){$passM="";}
//echo "</tr>";
echo "<table><tr>
<td>
<input type='hidden' name='yearPass' value='$y'>
<input type='hidden' name='passM' value='$passM'>
<input type='hidden' name='parkPass' value='$parkPass'>
<input type='submit' name='submit' value='Enter'></td></tr>";
echo "</form></table></div></body></html>";

?>