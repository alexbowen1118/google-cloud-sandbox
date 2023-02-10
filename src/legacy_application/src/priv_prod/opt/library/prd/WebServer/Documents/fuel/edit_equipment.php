<?php
session_start();

ini_set('display_errors',1);
$level=$_SESSION['fuel']['level'];
if($level<1){exit;}

// echo "<pre>"; print_r($_POST); echo "</pre>";  exit;

include("../../include/get_parkcodes_dist.php");// includes iConnect.inc

$parkCode[]="FAMA";
sort($parkCode);
// echo "<pre>"; print_r($parkCode); echo "</pre>"; // exit;
$parkCodeName['FAMA']="Facility Maintenance";
// echo "<pre>"; print_r($parkCodeName); echo "</pre>"; // exit;

$database="fuel";
mysqli_select_db($connection,$database) or die ("Couldn't select database");
       
//echo "<pre>"; print_r($_POST); print_r($_FILES); echo "</pre>";  exit;
// echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;

//**** PROCESS  delete photo ******
if(!empty($equipment_photo_id) and is_numeric($_GET['equipment_photo_id']))
	{
	extract($_GET);
	$query = "SELECT link FROM equipment_photos where equipment_photo_id='$equipment_photo_id'";
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query<br /><br />");
	$row=mysqli_fetch_assoc($result);
	$photo=$row['link'];
	unlink($photo);
	$exp=explode("/",$photo);
	$temp=array_pop($exp);
	$tn_photo=implode("/",$exp)."/ztn.".$temp;
	unlink($tn_photo);
	$query = "DELETE FROM equipment_photos where equipment_photo_id='$equipment_photo_id'";
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query<br /><br />");
	}
	
//**** PROCESS  an Update ******
if(@$update=="Submit")
	{
//  echo "<pre>"; print_r($_POST); print_r($_FILES); echo "</pre>";  exit;
		$skip=array("id","add","equipment_id","update","table");
		$not_empty_string=array("date_of_last_service","hours_at_last_service","mileage");
		$query="";
		foreach($_POST as $k=>$v){
			if(in_array(strtolower($k),$skip)){continue;}
		
			$v=str_replace(",","",$v);
			if(!($v=="" && in_array($k, $not_empty_string))){
				$query.="`".$k."`='".$v."',";
			}
			}
// 			$query=rtrim($query,",");
			$temp=$_POST['equipment_id'];
			$temp=$_POST['equip_cat_code'].substr($temp, -4);
			$query.="`equipment_id`='$temp'";
			$table="equipment";
			$fld="id";
		
	$query = "UPDATE $table set $query where $fld='$_POST[$fld]'";
// 	echo "t=$temp $query";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query<br /><br />".mysqli_error($connection));
	
	$query = "INSERT ignore into last_service set date_last_service='$_POST[date_of_last_service]', hours_last_service='$_POST[hours_at_last_service]', equip_id='$_POST[$fld]'";
// 	echo "t=$temp $query";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update line 66. $query<br /><br />");
	
      			
	if(!empty($_FILES['file_upload']['name']))
		{
		include("upload_equipment.php");
		}
//echo "<pre>"; print_r($_POST); print_r($_FILES); echo "</pre>";  exit;
// 	echo "<font color='blue'>Your update was successful.</font><br /><br />You can close this window.";
// 	echo "
// 	Return to the park's vehicle <a href='https://auth.dpr.ncparks.gov/fuel/menu.php?form_type=equipment'>equipment</a>.";
	
// 	exit;
	$vi=$id;
	}


//**** PROCESS  a Remove ******
if(@$submit=="Remove")
	{
		$table=$_POST['table'];
			$fld=$table."_id";
			$v=$_POST['remove'];
	$query = "UPDATE $table set surplus='$v' where $fld='$_POST[$fld]'";
	echo "$query";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
	
	echo "<font color='blue'>That vehicle was successfully removed.</font><br /><br />Please close this window and then click the \"Reload\" button on our web browser to view the change.";
	exit;
	}

//**** PROCESS  a Delete ******
if(@$submit=="Delete")
	{
	$id=$_POST['id'];
	$dbTable=strtolower($dbTable);
	$query = "DELETE from $dbTable where id='$id'";
//	echo "$query";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query. $query");
	
	echo "<font color='blue'>That vehicle was successfully deleted.</font><br /><br />Please close this window and then click the \"Reload\" button on our web browser to view the change.";
	exit;
	}


$dbTable="equipment";

mysqli_select_db($connection,"fuel") or die ("Couldn't select database");
// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql<br />".mysqli_error($connection));
while ($row=mysqli_fetch_assoc($result))
	{$fieldArray[]=$row['Field'];}

//   echo "<pre>"; print_r($fieldArray); echo "</pre>";  //exit;

$subName=array("serial_number"=>"Serial Number", "equip_cat_code"=>"Equipment Code", "center_code"=>"Park Code", "equipment_id"=>"Equipment ID", "fas_num"=>"FAS Number", "mileage"=>"Mileage", "make"=>"Make", "model_year"=>"Model Year", "engine"=>"Engine", "drive"=>"Drive", "fuel"=>"Fuel", "purpose"=>"Purpose", "comment"=>"Comment","condition"=>"Condition", "date_of_last_service"=>"Date of Last Service", "comment"=>"Comment", "hours_at_last_service"=>"Hours at Last Service", "vin"=>"VIN (trailer)", "link"=>"Photo(s)", "used_for"=>"Primary Use");
// if modified, also make changes to equipment.php


$text=array("comment");

$radio_emergency=array("y"=>"Yes","n"=>"No");
$radio_recall=array("Y"=>"Yes","N"=>"No");
$radio_status=array("U"=>"In Use","P"=>"Used for Parts","W"=>"Request to be surplused","S"=>"Has been Surplused/Sold");

$radio_emergency=array("y"=>"Yes","n"=>"No");

$drop_down=array("equip_cat_code","condition");
if($level>3)
	{$drop_down[]="center_code";}
$condition_array=array("Good","Fair","Poor","Inoperable");  // also modify equipment.php ~line 238

$sql = "SELECT * FROM equipment_category";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql");
while ($row=mysqli_fetch_assoc($result))
	{
	$code=strtoupper($row['equip_cat_code']);
	$equip_category[$code]=$row['equip_cat'];
	}
$sql = "SELECT * FROM table_make";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql");
while ($row=mysqli_fetch_assoc($result))
	{
	$make=strtoupper($row['make_code']);
	$radio_make[$make]=$row['make_type'];
	}

	$radio_drive['zt']="Zero Turn";   // also modify equipment.php ~line 135
	$radio_drive['rt']="Rubber Tracked";

$sql = "SELECT * FROM table_drive";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql ");
while ($row=mysqli_fetch_assoc($result))
	{
	$radio_drive[$row['drive_code']]=$row['drive_type'];
	}
$sql = "SELECT * FROM table_fuel_type";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql ");
while ($row=mysqli_fetch_assoc($result))
	{
	$radio_fuel[$row['fuel_code']]=$row['fuel_type'];
	}

$sql = "SELECT * FROM table_purpose";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql");
while ($row=mysqli_fetch_assoc($result))
	{
	$radio_purpose[$row['purpose_code']]=$row['purpose_type'];
	}
	
$sql = "SELECT * FROM table_used_for";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql ");
while ($row=mysqli_fetch_assoc($result))
	{
	$radio_used_for[$row['purpose_code']]=$row['purpose_type'];
	}

$sql = "SELECT date_last_service, hours_last_service FROM last_service where equip_id='$vi' order by id limit 1"; 
// echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql ");
while ($row=mysqli_fetch_assoc($result))
	{
	$last_service_array[]=$row;
	}
// echo "<pre>"; print_r($last_service_array); echo "</pre>"; // exit;
		
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
if($level==1){$park_code=$_SESSION['fuel']['select'];}

$skip=array("id","equipment_photo_id");

//"purpose"
$radio=array("drive","fuel","used_for");

// Form Header

			include_once("menu.php");
			
echo "<div id='add_form' align='center'><table border='1' cellpadding='5'>";
echo "<form name='frmEdit' action=\"edit_equipment.php\" method=\"post\" enctype='multipart/form-data'>";
echo "<tr><td align='center' colspan='2'>EQUIPMENT SPECIFICATIONS</td></tr>";
echo "<tr>
<td><a href='menu.php?form_type=equipment&action=add'>Add an Equipment Item</a></td>
<td><a href='menu.php?form_type=equipment&action=search'>Search</a></td>
</tr></table>
</div>";


$sql= "SELECT t1.*, t2.equipment_photo_id, t2.link 
from equipment as t1
left join equipment_photos as t2 on t1.equipment_id=t2.equipment_id
where t1.`id`='$vi'"; 
// echo " $sql ";

$result = mysqli_query($connection,$sql) or die ("Line 150 Couldn't execute query. $sql");

while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	}
// echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;
$c=count($ARRAY); // this is used to count number of linked photos
$row=$ARRAY[0];
$id=$row['id'];
$equipment_id=$row['equipment_id'];

$var_pc=$row['center_code'];
$sql = "SELECT concat(t1.Lname,', ',t1.Fname) as name, t2.beacon_num
FROM divper.empinfo as t1
LEFT JOIN divper.emplist as t2 on t1.tempID=t2.tempID
LEFT JOIN divper.position as t3 on t2.beacon_num=t3.beacon_num
where t3.program_code='$var_pc'
order by t1.Lname";  
// echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql");
while ($row_assign=mysqli_fetch_assoc($result))
	{
	if($row_assign['beacon_num']==""){continue;}
	$assign[$row_assign['beacon_num']]=$row_assign['name'];
	}

if($var_pc=="WARE")
	{
	$assign['60032852']="Davis, William";   // Maintenance Mechanic IV
	$assign['65020599']="Reavis, Raymond";   // Facility Maintenance Supervisor II
	$assign['65020598']="Parker, Dwayne";   // Facility Maintenance Supv II
	}
if(!empty($check_assigned))
	{
	if(!array_key_exists($check_assigned,$assign))
		{
		$sql = "SELECT concat(t1.Lname,', ',t1.Fname) as name, t2.beacon_num
		FROM divper.empinfo as t1
		LEFT JOIN divper.emplist as t2 on t1.tempID=t2.tempID
		where t2.beacon_num='$check_assigned'"; //echo "$sql";
		$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql ");
		while ($row_new_assign=mysqli_fetch_assoc($result))
			{
			if($row_new_assign['beacon_num']==""){continue;}
			$assign[$row_new_assign['beacon_num']]=$row_new_assign['name'];
			}
		}
	}
//echo "$check_assigned<pre>"; print_r($assign); echo "</pre>";

// list of current park
mysqli_select_db($connection,"dpr_system");
$sql="SELECT park_code AS center_code FROM parkcode_names_district";
//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql ");
while ($row_current=mysqli_fetch_assoc($result))
	{
	if($row_current['center_code']=="SURP"){continue;}  // added back below
	if(!in_array($row_current['center_code'],$parkCode)){continue;}
	$park_list_search[]=$row_current['center_code'];
	}
$park_list_search[]="CORE";
$park_list_search[]="PIRE";
$park_list_search[]="MORE";
sort($park_list_search);
// echo "<pre>"; print_r($park_list_search); echo "</pre>"; // exit;

mysqli_select_db($connection,$database);

$previous_list_search=$park_list_search;
	
$park_edit_green=array("park_id","condition","comment","mileage","date_of_last_service","hours_at_last_service","fas_num","used_for");
$park_edit_blue=array("equip_cat_code","fuel","engine","drive","year","make","model","GVWR","serial_number","vin","model_year");

$park_edit=array_merge($park_edit_green,$park_edit_blue);

$readonly=array("equip_cat_code","equipment_id");

if(!isset($park_name)){$park_name=$parkCodeName[$row['center_code']];}

echo "<div align='center'><table border='1' cellpadding='5'><tr><td align='center' colspan='2'>$park_name</td></tr>";

foreach($row as $k=>$v)
	{
	if(in_array($k,$skip)){continue;}
	$val=""; $RO="";
	if(in_array($k,$readonly)){$RO="readonly";}
	if($k=="center_code" and $level<4)
		{
		if(empty($park_code))
			{$input=$center_code;}
			else
			{$input=$park_code;}
		
		}
		else
		{
		if($k=="center_code"){echo $row['center_code'];}
		$center_code=$row['center_code'];
		}
		
	if($k=="mileage" and $level<1)
		{
		$v=number_format($v);
		$RO="READONLY";
		}
	if($level>3 OR in_array($k,$park_edit))
		{
		$input="<input type='text' size='30' name='$k' value='$v'$RO>";
		if($k=="date_of_last_service")
			{
			if(!empty($last_service_array[0]))
				{
				$prev_service=" Previous service on: ".$last_service_array[0]['date_last_service'];
				}
				else
				{$prev_service="";}
			$input="<input  id=\"datepicker1\" type='text' size='30' name='$k' value='$v'$RO> $prev_service";
			}
		if($k=="hours_at_last_service")
			{
			if(!empty($last_service_array[0]))
				{
				$prev_hours=" Previous hours: ".$last_service_array[0]['hours_last_service'];
				}
				else
				{$prev_hours="";}
			$input.=$prev_hours;
			}
		}
	else
		{
		$input=$v;
		}
		
	if(in_array($k,$radio))
		{
		$var=${"radio_".$k};
// 		echo "<pre>"; print_r($var); echo "</pre>"; // exit;
		$r_input="";
			foreach($var as $k1=>$v1)
				{
				$rckN="";$rckY="";
				
				@$test_val=$var[$v];
				if($test_val==$v1){$rckN="checked";}
				if($level>3 OR in_array($k,$park_edit))
					{	
					$r_input.="[<input type='radio' name='$k' value='$k1' $rckY $rckN>$v1] ";
					
					if($k1=="le"){$r_input.="<br />";}
					}
					else
					{
					$r_input=$test_val;
					}
				}
		$input=$r_input;
		}
		
	if(in_array($k,$text))
		{
		$input="<textarea name='$k' rows='2' cols='35'>$v</textarea>";
		}
		
	if(in_array($k,$drop_down) )
		{
		$park_list_search[]="SURP"; // Code for a surplused vehicle
		if($k=="center_code")
			{
			$p_array=$park_list_search;
			$input="<select name='$k'><option selected=''></option>";
			foreach($p_array as $index=>$pc)
				{
				if($pc==$v){$s="selected";}else{$s="";}
				$input.="<option value='$pc' $s>$pc</option>";
				}
			$input.="</select>";
			}
		if($k=="equip_cat_code")
			{
			$p_array=$equip_category;
			$input="<select name='$k'><option selected=''></option>";
			foreach($p_array as $index=>$pc)
				{
				if($index==$v){$s="selected";}else{$s="";}
				$input.="<option value='$index' $s>$index - $pc</option>";
				}
			$input.="</select>";

			}
		if($k=="condition")
			{
			$p_array=$condition_array;
			
			$input="<select name='$k'><option selected=''></option>";
			foreach($p_array as $index=>$pc)
				{
				if($pc==$v){$s="selected";}else{$s="";}
				$input.="<option value='$pc' $s>$pc</option>";
				}
			$input.="</select>";
			}
			
		}
	
	if($k=="link")
		{
		$input="<table><tr>";
		foreach($ARRAY as $index=>$link_array)
			{
			$equipment_photo_id=$link_array['equipment_photo_id'];
		if(empty($equipment_photo_id)){$input="No photo"; continue;}
		$image=$link_array['link'];
			$exp=explode("/",$image);
			$temp=array_pop($exp);
			$val=implode("/",$exp)."/ztn.".$temp;
			$input.="<td><a href='$image' target='_blank'><img src='$val'></a> ";
			
			if($var_pc==$_SESSION['fuel']['select'] or $level>1)
				{
				$input.="<a href='edit_equipment.php?vi=$vi&equipment_photo_id=$equipment_photo_id'  onclick=\"return confirm('Are you sure you want to delete this Photo?')\">delete</a>";
				}
			echo "</td>";
			}
		$input.="</tr></table>";
		}

	if(isset($subName[$k])){$subN=$subName[$k];}
	echo "<tr><td>$subN</td>
	<td>$input</td>
	</tr>";
	}

echo "<table><tr><td align='center' colspan='2'>If possible, upload at least 1 photo.</td></tr> ";

if($c==0){$c++;}

echo "<td>Photo $c <input type='file' name='file_upload[]'></td></tr>";
$c++;
echo "<td>Photo $c <input type='file' name='file_upload[]'></td></tr>";
$c++;
echo "<td>Photo $c <input type='file' name='file_upload[]'></td></tr>";

// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
if($var_pc==$_SESSION['fuel']['select'] or $level>1)
	{
	echo "<tr><td align='center' colspan='2'>
	<input type='hidden' name='id' value='$vi'>
	<input type='hidden' name='equipment_id' value='$equipment_id'>
	<input type='submit' name='update' value='Submit'>
	</td></tr>";
	}
echo "</table></form></div>";

if($level>3)
	{	
	echo "This link will permanently remove record id=$id from the equipment table of the fuel/equipment database. Be sure to delete any photos before deleting this record.
	<form method='POST'>
	<input type='hidden' name='dbTable' value='equipment'>
	<input type='hidden' name='id' value='$id'>
<input type='submit' name='submit' value='Delete' onClick=\"return confirmLink()\"></form>";
	}
$x=1;
echo "
<script>
    $(function() {";
    for($i=1;$i<=$x;$i++)
    	{
    	echo "
        $( \"#datepicker".$i."\" ).datepicker({
		changeMonth: true,
		changeYear: true, 
		dateFormat: 'yy-mm-dd',
		yearRange: \"-5yy\",
		maxDate: \"0\" });
   ";
    }
echo " });
</script>";
echo "</html>";
?>