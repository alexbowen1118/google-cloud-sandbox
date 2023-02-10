<?php
session_start();
extract($_REQUEST);
$level=$_SESSION['fuel']['level'];

$database="fuel";
include("../../include/iConnect.inc");// database connection parameters

include("../../include/get_parkcodes_reg.php");// database connection parameters

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
       
//echo "<pre>"; print_r($_POST); echo "</pre>";  //exit;

//**** PROCESS  an ADD ******
if(@$update=="Add")
	{
	$query="";
// echo "<pre>"; print_r($_POST); echo "</pre>";  exit;
		$skip=array("update","table","id");
		foreach($_POST as $k=>$v){
			if(in_array(strtolower($k),$skip)){continue;}
			$v=addslashes($v);
			$query.="`".$k."`='".$v."',";
			}
			$query=rtrim($query,",");
			
	$query = "INSERT INTO dpr_doa_parking set $query";
//	echo "$query";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
	$pass_id=mysqli_insert_id($connection);  
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
			$v=addslashes($v);
			$query.="`".$k."`='".$v."',";
			}
			$query=rtrim($query,",");
			
	$query = "UPDATE dpr_doa_parking set $query where id='$_POST[id]'";
//	echo "$query";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
	header("Location:edit_dpr_doa_parking.php?id=$id&m=1");
	exit;
	}


//**** PROCESS  a Remove ******
if(@$submit=="Remove")
	{
		$table=$_POST['table'];
	$query = "DELETE FROM dpr_doa_parking where id='$_POST[id]'";
	//echo "$query";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
	
	echo "<font color='blue'>That vehicle was successfully removed.</font><br /><br />Please close this window and then click the \"Reload\" button on our web browser to view the change.";
	exit;
	}


$dbTable="dpr_doa_parking";

// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$sql = "SHOW COLUMNS FROM dpr_doa_parking";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql ");
while ($row=mysqli_fetch_assoc($result))
	{$fieldArray[]=$row['Field'];}

//echo "<pre>"; print_r($fieldArray); echo "</pre>";  exit;

$skip=array("id","district");

foreach($fieldArray as $k=>$v)
	{
	$v1=$v;
	if($v=="comments"){$v="Headquarters comments";}
	if($v=="condition_comment"){$v="Park condition comments";}
	$subName[$v1]=strtoupper($v);
	}
//echo "<pre>"; print_r($subName); echo "</pre>";

if($level==1){$park_code=$_SESSION['fuel']['select'];}

// parse table

@$dbTable=$new_table;
@$fld=$new_table."_id";
 
// Form Header

include_once("menu.php");

if(@$m==1){echo "Update was successful. You may close this window/tab.";}
		
echo "<div id='add_form' align='center'>

<table border='1' cellpadding='5'>";

echo "<tr><td align='center' colspan='2'>DPR / DOA Vehicle</td></tr></table>
</div>";

if(!isset($park_code)){$park_code="";}
echo "<div align='center'>
<form name='frmEdit' action=\"edit_dpr_doa_parking.php\" method=\"post\">
<table border='1' cellpadding='5'><tr><td align='center' colspan='2'>$park_code</td></tr>";

if(isset($pass_id)){$id=$pass_id;}
if(empty($id)){$id="";}

$sql= "SELECT * from dpr_doa_parking where id='$id'";
 

if(@$submit=="Add")
	{
	$sql="select t1.* 
	from dpr_doa_parking as t1
	where 1  limit 1"; 
	}
 
//s echo " $sql ";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
$row=mysqli_fetch_assoc($result);

$read_only=array();
IF($level<4)
	{
$read_only=array("id","active","district","region","park_or_section","center","license_plate","year_make_model","class","vehicle","assign_type","rate_per_mile");
	}


	foreach($row as $k=>$v){
		if(in_array($k,$skip)){continue;}
		
		if(@$submit=="Add")
			{
			$v="";
			}
		
		$RO="";
		if(in_array($k,$read_only)){$RO="READONLY";}
		if($k=="center_code" and $level==1){
			$v=$park_code;
			}
		$input="<input type='text' size='30' name='$k' value=\"$v\"$RO>";
		
		if($k=="active" AND $level>3)
			{
			$menu_array=array("YES","NO");
			$input="<select name='$k'>";
			foreach($menu_array as $k1=>$v1)
				{
				if($v1==$v){$s="selected";}else{$s="value";}
				$input.="<option $s='$v1'>$v1</option>";
				}
			$input.="</select>";
			}
// 		if($k=="district" AND $level>3)
// 			{
// 			$menu_array=array("","EADI","NODI","SODI","WEDI","STWD");
// 			$input="<select name='$k'>";
// 			foreach($menu_array as $k1=>$v1)
// 				{
// 				if($v1==$v){$s="selected";}else{$s="value";}
// 				$input.="<option $s='$v1'>$v1</option>";
// 				}
// 			$input.="</select>";
// 			}
		if($k=="region" AND $level>3)
			{
			$menu_array=array("","CORE","PIRE","MORE","STWD");
			$input="<select name='$k'>";
			foreach($menu_array as $k1=>$v1)
				{
				if($v1==$v){$s="selected";}else{$s="value";}
				$input.="<option $s='$v1'>$v1</option>";
				}
			$input.="</select>";
			}
		if($k=="assign_type" AND $level>3)
			{
			$menu_array=array("","Agency","Individual");
			$input="<select name='$k'>";
			foreach($menu_array as $k1=>$v1)
				{
				if($v1==$v){$s="selected";}else{$s="value";}
				$input.="<option $s='$v1'>$v1</option>";
				}
			$input.="</select>";
			}
		if($k=="condition")
			{
			$menu_array=array("","Good","Fair","Poor");
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
		if($k=="park_or_section" AND $level>3)
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
			if($level>3)
				{$input="<textarea name='$k' rows='15' cols='45'>$v</textarea>";}
				else
				{$input=$v;}
			}	
		echo "<tr><td>$subName[$k]</td>
		<td>$input</td>
		</tr>";
		}
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
if($_SESSION['beacon_num']=="60032781" OR $level>4)  // Budget Officer
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
	<input type='hidden' name='table' value='dpr_doa_parking'>
	<input type='hidden' name='id' value='$id'>
	<input type='submit' name='update' value='$update_value'>
	</td></tr>";
	}

$sub="$row[section] DPR/DOA vehicle with plate $row[plate]";
echo "<tr><td colspan='2'>
<font color='red'>Email</font> <a href='mailto:$budget_office_email?subject=$sub'>$budget_office_email</a>, and courtesy copy DISU, with questions or additional comments for headquarters.
</td></tr>";
echo "</table></form></div>";

if(@$submit!="Add" AND ($_SESSION['beacon_num']=="60032781" OR $level>4))
	{
	echo "<hr><form method='POST'><table align='center' border='1' cellpadding='5'>
	<tr><td align='center' colspan='3'>Remove this vehicle from the inventory.</td></tr>
	
	<tr><td align='center' colspan='3'>
	<input type='hidden' name='table' value='dpr_doa_parking'>
	<input type='hidden' name='id' value='$id'>
	<input type='submit' name='submit' value='Remove'>
	</td></tr>
	</table></form>";
	}

echo "</html>";
?>