<?php
session_start();
extract($_REQUEST);
$level=$_SESSION['fuel']['level'];

$database="fuel";
include("../../include/iConnect.inc");// database connection parameters

include("../../include/get_parkcodes_dist.php");// database connection parameters


$type_array=array("","mobile","portable","base station","repeater");
// $frequency_array=array("","VHF","UHF","Dual Band","700/800mhz" ,"Dual Band and ADP", "700/800mhz and ADP");


mysqli_select_db($connection,"divper");
$query = "SELECT t1.email
from empinfo as t1
left join emplist as t2 on t1.tempID=t2.tempID
left join position as t3 on t2.beacon_num=t3.beacon_num
where t3.beacon_num='60032781'";
$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
$row=mysqli_fetch_assoc($result);
$budget_office_email=$row['email'];

$database="fuel";
mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

$sql="select t1.* 
from dpr_radio_access as t1
where 1 "; 
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$add_array[]=$row['tempID'];
	}

// echo "<pre>"; print_r($add_array); echo "</pre>";  //exit;	
$temp_level=$_SESSION[$database]['level'];
$tempID=$_SESSION[$database]['tempID'];
if(in_array($_SESSION['fuel']['tempID'], $add_array))
	{
	 $temp_level=4;
	}

if($temp_level<4)
	{
// 	echo "<pre>"; print_r($_SESSION); echo "</pre>";
	echo "No access. $temp_level"; 
	exit;
	}




//**** PROCESS  an ADD ******
if(@$update=="Add")
	{
	$query="";
// echo "<pre>"; print_r($_POST);print_r($_FILES); echo "</pre>";  exit;
		$skip=array("update","flle_link","id","table");
		foreach($_POST as $k=>$v){
			if(in_array(strtolower($k),$skip)){continue;}
// 			$v=addslashes($v);
			$query.="`".$k."`='".$v."',";
			}
		$query=rtrim($query,",");
			
	$query = "INSERT INTO dpr_radio_plugs set $query";
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
	$pass_id=mysqli_insert_id($connection);  
	$m=2;
	include("dpr_radio_plug_upload.php");
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
			
	$query = "UPDATE dpr_radio_plugs set $query where id='$id' ";
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
	$pass_id=$id;
	include("dpr_radio_plug_upload.php");
	}


//**** PROCESS  a Remove ******
if(@$submit=="Remove")
	{
	unlink($file_link);
	$query = "UPDATE dpr_radio_plugs set file_link='' where id='$id'";
		$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
	if(!empty($_POST['id']))
		{
		$query = "DELETE FROM dpr_radio_plugs where id='$_POST[id]'";
		$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
		}
	
	echo "<font color='blue'>That plug was successfully removed.</font><br /><br /><a href='menu.php?form_type=dpr_radio_plugs'>Return to list.</a>";
	exit;
	}


$dbTable="dpr_radio_plugs";

// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$sql = "SHOW COLUMNS FROM dpr_radio_plugs";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql ");
while ($row=mysqli_fetch_assoc($result))
	{$fieldArray[]=$row['Field'];}
//echo "<pre>"; print_r($fieldArray); echo "</pre>";  exit;

$sql = "SELECT make, model from dpr_radio order by make, model";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql ");
while ($row=mysqli_fetch_assoc($result))
	{
	$make_array[$row['make']]=$row['make'];
	$model_array[$row['model']]=$row['model'];
	}
	
$skip=array("id");

foreach($fieldArray as $k=>$v)
	{
	$v1=$v;
	if($v=="section"){$v="Section/Park";}
	if($v=="comments"){$v="Section/Park comments";}
	$subName[$v1]=strtoupper($v);
	}
//echo "<pre>"; print_r($subName); echo "</pre>";

if($temp_level==1){$park_code=$_SESSION['fuel']['select'];}

// parse table

@$dbTable=$new_table;
@$fld=$new_table."_id";
 
// Form Header

include_once("menu.php");

if(@$m==1){echo "<font color='magenta'>Update was successful.</font> <a href='menu.php?form_type=dpr_radio_plugs'>Return to list.</a>";}

if(@$m==2){echo "<font color='GREEN'>ADD was successful.</font> <a href='menu.php?form_type=dpr_radio_plugs'>Return to list.</a>";}
		
echo "<div id='add_form' align='center'>

<table border='1' cellpadding='5'>";

echo "<tr><td align='center' colspan='2'>DPR Radio Code Plugs<br /><a href='menu.php?form_type=dpr_radio_plugs'>Return</a></td></tr></table>
</div>";

if(!isset($park_code)){$park_code="";}
echo "<div align='center'>
<form name='frmEdit' action=\"edit_dpr_radio_plugs.php\" method=\"post\"  enctype='multipart/form-data'>
<table border='1' cellpadding='5'><tr><td align='center' colspan='2'>$park_code</td></tr>";

if(isset($pass_id)){$id=$pass_id;}
if(empty($id)){$id="";}

$sql= "SELECT * from dpr_radio_plugs where id='$id'";
 

if(@$submit=="Add")
	{
	$sql="select t1.* 
	from dpr_radio_plugs as t1
	where 1  limit 1"; 
	}
 
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
if(mysqli_num_rows($result)<1)
	{
// 	echo "<pre>"; print_r($fieldArray); echo "</pre>";
	$row=$fieldArray;
	}
	else
	{
	$row=mysqli_fetch_assoc($result);
}

$read_only=array();
IF($temp_level<3)
	{
$read_only=array("id","section","make","model","file_link","software","comments");
	}


foreach($row as $k=>$v)
	{
	if(is_numeric($k)){$k=$v;}
	if(in_array($k,$skip)){continue;}

	if(@$submit=="Add")
		{
		$v="";
		}

	$RO="";
	if(in_array($k,$read_only)){$RO="READONLY";}
// 	if($temp_level==1)
// 		{
// 		$v=$park_code;
// 		}
	$input="<input type='text' size='30' name='$k' value=\"$v\"$RO>";

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
	
	if($k=="make")
		{
		$input="<select name='$k'><option value=''></option>\n";
		foreach($make_array as $k1=>$v1)
			{
			if($v1==$v){$s="selected";}else{$s="value";}
			$input.="<option $s='$v1'>$v1</option>";
			}
		$input.="</select> If not listed, add radio to the radio database.";
		}	
	if($k=="model")
		{
		sort($model_array);
		$input="<select name='$k'><option value=''></option>\n";
		foreach($model_array as $k1=>$v1)
			{
			if($v1==$v){$s="selected";}else{$s="value";}
			$input.="<option $s='$v1'>$v1</option>";
			}
		$input.="</select> If not listed, add radio to the radio database.";
		}	
	if($k=="comments")
		{
		if($temp_level>2)
			{$input="<textarea name='$k' rows='5' cols='75'>$v</textarea>";}
			else
			{$input=$v;}
		}		
	if($k=="file_link")
		{
		$file_link=$v;
		if(empty($v))
			{
			$input="<input type='file' name='file_upload[]'>";
			}
			else
			{
			$input="<a href='$v'>Download</a> Plug File &nbsp;&nbsp;&nbsp;&nbsp;<a href='edit_dpr_radio_plugs.php?id=$id&file_link=$v&submit=Remove'  onclick=\"return confirm('Are you sure you want to delete this File?')\">Remove</a> this file.";
			}
		}	
	echo "<tr><td>$subName[$k]</td>
	<td>$input</td>
	</tr>";
	}
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
if($temp_level>2)  
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
<input type='hidden' name='table' value='dpr_radio_plugs'>
<input type='hidden' name='id' value='$id'>
<input type='submit' name='update' value='$update_value'>
</td></tr>";
}

echo "</table></form></div>";

if(@$submit!="Add" AND $temp_level>2)
	{
	echo "<hr><form method='POST' onclick=\"javascript:return confirm('Are you sure you want to delete this Plug?')\"><table align='center' border='1' cellpadding='5'>
	<tr><td align='center' colspan='3'>Remove this Plug Record from the inventory.</td></tr>
	
	<tr><td align='center' colspan='3'>
	<input type='hidden' name='file_link' value='$file_link'>
	<input type='hidden' name='table' value='dpr_radio_plugs'>
	<input type='hidden' name='id' value='$id'>
	<input type='submit' name='submit' value='Remove'>
	</td></tr>
	</table></form>";
	}

echo "</html>";
?>