<?php
session_start();
extract($_REQUEST);
$level=$_SESSION['fuel']['level'];

$database="fuel";
include("../../include/iConnect.inc");// database connection parameters

include("../../include/get_parkcodes_dist.php");// database connection parameters
// mysqli_close($connection);
mysqli_select_db($connection,"divper");
$query = "SELECT t1.Lname, t1.Fname, t1.emid, t1.tempID, t2.currPark
from empinfo as t1
left join emplist as t2 on t1.tempID=t2.tempID
where 1 and t2.currPark is not NULL
order by t1.Lname";
$result = mysqli_query($connection,$query) or die ("Couldn't execute query. $query".mysqli_error($connection));
$source_name="";
while($row=mysqli_fetch_assoc($result))
	{
	$array_tempID[$row['emid']]=$row['tempID'];
	$var=$row['Lname'].", ".$row['Fname']." - ".$row['currPark']." * ".$row['emid'];
	$source_name.="\"".$var."\",";
	}

$database="fuel";
mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
if(!empty($_POST['submit_form']))
	{
	$exp=explode("* ", $name);
	$tempID=$array_tempID[$exp[1]];
	$emid=$exp[1];
	$exp=explode("- ", $exp[0]);
	$park_code=$exp[1];
	$sql="REPLACE dpr_radio_access
	SET tempID='$tempID', emid='$emid', access_level='$access_level', park_code='$park_code'";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql".mysqli_error($connection));

// 	$sql="DELETE FROM dpr_radio_access where access_level=0";
// $result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql".mysqli_error($connection));
	}

include_once("menu.php");

echo "
	<script>
		$(function()
			{
			$( \"#Lname\" ).autocomplete({
			source: [ $source_name ]
				});
			});
		</script>";
echo "<form method='POST'>";
echo "<table><tr><td align='center'><a href='menu.php?form_type=dpr_radio'>Return</a></td></tr>";
echo "<tr>
<td>Name: <input id='Lname' type='text' name='name' value=\"\" size='35'></td>
<td>Access Level: <input id='level' type='text' name='access_level' value=\"\" size='3'></td>
</tr>";
echo "<tr><td align='center'><input type='submit' name='submit_form' value=\"Add\"></td></tr>
</table>";
echo "</form>";

echo "<table>
<tr><td>Access to the Fuel/Vehicle/Equipment db has access to the Radio page.</td></tr>
<tr><td>Access level 3 or higher can Add/Edit radios.</td></tr>
<tr><td>Access level 4 can grant Access.</td></tr>
</table>";

$sql="select t1.id, t1.emid, t1.park_code, t1.tempID, t1.access_level
from dpr_radio_access as t1
where 1 "; 
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	}
$skip=array();
$c=count($ARRAY);
echo "<table><tr><td>$c</td></tr>";
foreach($ARRAY AS $index=>$array)
	{
	if($index==0)
		{
		echo "<tr>";
		foreach($ARRAY[0] AS $fld=>$value)
			{
			if(in_array($fld,$skip)){continue;}
			$var_fld=$fld;
			If($fld=="tempID"){$var_fld="Last Name";}
			echo "<th>$var_fld</th>";
			}
		echo "</tr>";
		}
	echo "<tr>";
	foreach($array as $fld=>$value)
		{
		if(in_array($fld,$skip)){continue;}
		if($fld=="tempID"){$value=substr($value, 0,-4);}
		echo "<td>$value</td>";
		}
	echo "</tr>";
	}
echo "</table>";
exit;
	

$temp_level=$level;
if(in_array($_SESSION['beacon_num'], $add_array))
	{
	 $temp_level=3;
	}
// echo "<pre>"; print_r($add_array); echo "</pre>";  //exit;

//**** PROCESS  an ADD ******
if(@$update=="Add")
	{
	$query="";
// echo "<pre>"; print_r($_POST); echo "</pre>";  exit;
		$skip=array("update","table","id");
		foreach($_POST as $k=>$v){
			if(in_array(strtolower($k),$skip)){continue;}
// 			$v=addslashes($v);
			$query.="`".$k."`='".$v."',";
			}
			$query=rtrim($query,",");
			
	$query = "INSERT INTO dpr_radio set $query";
//	echo "$query";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
	$pass_id=mysqli_insert_id($connection);  
	$m=2;
	//echo "id=$id<br /><br />";exit;
	}
	
//**** PROCESS  an Update ******
if(@$update=="Submit")
	{
$query="";
//	echo "<pre>"; print_r($_POST); echo "</pre>";  exit;
		$skip=array("update","table","id");
		$id=$_POST['id'];
		foreach($_POST as $k=>$v){
			if(in_array(strtolower($k),$skip)){continue;}
			if($k!="DRIVER"){$v=str_replace(",","",$v);}
// 			$v=addslashes($v);
			$query.="`".$k."`='".$v."',";
			}
			$query=rtrim($query,",");
			
	$query = "UPDATE dpr_radio set $query where id='$_POST[id]'";
//	echo "$query";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
	$m=1;
// 	header("Location:edit_dpr_radio.php?id=$id&m=1");
// 	exit;
	}


//**** PROCESS  a Remove ******
if(@$submit=="Remove")
	{
		$table=$_POST['table'];
	$query = "DELETE FROM dpr_radio where id='$_POST[id]'";
	//echo "$query";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
	
	echo "<font color='blue'>That vehicle was successfully removed.</font><br /><br /><a href='menu.php?form_type=dpr_radio'>Return to list.</a>";
	exit;
	}


$dbTable="dpr_radio";

// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$sql = "SHOW COLUMNS FROM dpr_radio";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql ");
while ($row=mysqli_fetch_assoc($result))
	{$fieldArray[]=$row['Field'];}

//echo "<pre>"; print_r($fieldArray); echo "</pre>";  exit;

$skip=array("id","district");

foreach($fieldArray as $k=>$v)
	{
	$v1=$v;
	if($v=="section"){$v="Section/Park";}
	if($v=="comments"){$v="Section/Park comments";}
// 	if($v=="condition_comment"){$v="Park condition comments";}
	$subName[$v1]=strtoupper($v);
	}
//echo "<pre>"; print_r($subName); echo "</pre>";

if($temp_level==1){$park_code=$_SESSION['fuel']['select'];}

// parse table

@$dbTable=$new_table;
@$fld=$new_table."_id";
 
// Form Header

include_once("menu.php");

if(@$m==1){echo "<font color='magenta'>Update was successful.</font> <a href='menu.php?form_type=dpr_radio'>Return to list.</a>";}

if(@$m==2){echo "<font color='GREEN'>ADD was successful.</font> <a href='menu.php?form_type=dpr_radio'>Return to list.</a>";}
		
echo "<div id='add_form' align='center'>

<table border='1' cellpadding='5'>";

echo "<tr><td align='center' colspan='2'>DPR Mobile, Portable, and Base Station Radios<br /><a href='menu.php?form_type=dpr_radio'>Return</a></td></tr></table>
</div>";

if(!isset($park_code)){$park_code="";}
echo "<div align='center'>
<form name='frmEdit' action=\"edit_dpr_radio.php\" method=\"post\">
<table border='1' cellpadding='5'><tr><td align='center' colspan='2'>$park_code</td></tr>";

if(isset($pass_id)){$id=$pass_id;}
if(empty($id)){$id="";}

$sql= "SELECT * from dpr_radio where id='$id'";
 

if(@$submit=="Add")
	{
	$sql="select t1.* 
	from dpr_radio as t1
	where 1  limit 1"; 
	}
 
//s echo " $sql ";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
$row=mysqli_fetch_assoc($result);

$read_only=array();
IF($temp_level<3)
	{
$read_only=array("id","active","district","section","make","model","serial_number","assigned_to","condition");
	}


foreach($row as $k=>$v)
	{
	if(in_array($k,$skip)){continue;}

	if(@$submit=="Add")
		{
		$v="";
		}

	$RO="";
	if(in_array($k,$read_only)){$RO="READONLY";}
	if($k=="center_code" and $temp_level==1){
		$v=$park_code;
		}
	$input="<input type='text' size='30' name='$k' value=\"$v\"$RO>";


	if($k=="type")
		{
		$input="";
		foreach($type_array as $k1=>$v1)
			{
			if($v1==$v){$ck="checked";}else{$ck="";}
			$input.="<input type='radio' name='$k' value=\"$v1\" $ck>$v1 ";
			}
		}

	if($k=="condition_")
		{
		$menu_array=array("","Excellent","Good","Fair","Poor");
		$input="<select name='$k'>";
		foreach($menu_array as $k1=>$v1)
			{
			if($v1==$v){$s="selected";}else{$s="value";}
			$input.="<option $s='$v1'>$v1</option>";
			}
		$input.="</select>";
		}
	if($k=="replace")
		{
		$menu_array=array("","Yes","No");
		$input="<select name='$k'>";
		foreach($menu_array as $k1=>$v1)
			{
			if($v1==$v){$s="selected";}else{$s="value";}
			$input.="<option $s='$v1'>$v1</option>";
			}
		$input.="</select>";
		}
	if($k=="section" AND $temp_level>2)
		{
		$menu_array=$parkCode;
		// TRWD = TRAIL west district
	array_unshift($menu_array,"ADMN","DEDE","OPAD","RALE","REMA","TRED","TRWD","TRND","EADI","NODI","SODI","WEDI","CORE","PIRE","MORE","INED","STPA","NARA","GRSS","LAND");
		$input="<select name='$k'>";
		foreach($menu_array as $k1=>$v1)
			{
			if($v1==$v){$s="selected";}else{$s="value";}
			$input.="<option $s='$v1'>$v1</option>";
			}
		$input.="</select>";
		}
	
	if($k=="condition_comment")
		{
		$input="<textarea name='$k' rows='15' cols='45'>$v</textarea>";
		}
	if($k=="comments")
		{
		if($temp_level>2)
			{$input="<textarea name='$k' rows='5' cols='75'>$v</textarea>";}
			else
			{$input=$v;}
		}	
	echo "<tr><td>$subName[$k]</td>
	<td>$input</td>
	</tr>";
	}
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
if($_SESSION['beacon_num']=="60032781" OR $temp_level>2)  // Budget Officer
{	
if(@$submit!="Add")
	{
	$update_value="Submit";
	}
	else
	{
	$update_value="Add";
	}
echo "<tr><td align='center' colspan='2'>
<input type='hidden' name='table' value='dpr_radio'>
<input type='hidden' name='id' value='$id'>
<input type='submit' name='update' value='$update_value'>
</td></tr>";
}

$sub="$row[section] DPR vehicle with plate $row[plate]";
// echo "<tr><td colspan='2'>
// <font color='red'>Email</font> <a href='mailto:$budget_office_email?subject=$sub'>$budget_office_email</a>, and courtesy copy DISU, with questions or additional comments for headquarters.
// </td></tr>";
echo "</table></form></div>";

if(@$submit!="Add" AND $temp_level>2)
	{
	echo "<hr><form method='POST'><table align='center' border='1' cellpadding='5'>
	<tr><td align='center' colspan='3'>Remove this radio from the inventory.</td></tr>
	
	<tr><td align='center' colspan='3'>
	<input type='hidden' name='table' value='dpr_radio'>
	<input type='hidden' name='id' value='$id'>
	<input type='submit' name='submit' value='Remove'>
	</td></tr>
	</table></form>";
	}

echo "</html>";
?>