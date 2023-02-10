<?php
session_start();

// extract($_REQUEST);
$level=$_SESSION['fuel']['level'];
$admin_position_number=$_SESSION['fuel']['beacon_num'];
$reduce_form=array("Administrative Officer III"=>"60032781","Processing Assistant IV"=>"60033242");  
// also in inventory.php line 88

if($level<1){exit;}
// echo "l=$level";
// echo "<pre>"; print_r($_POST); echo "</pre>";  exit;

include("../../include/get_parkcodes_dist.php");// include after connectROOT.inc so no inject doesn't get called twice
$database="fuel";
mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
       
// echo "<pre>"; print_r($_POST); print_r($_FILES); echo "</pre>";  exit;
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;

//**** PROCESS  an Update ******
if(@$update=="Submit")
	{
//  echo "<pre>"; print_r($_REQUEST); print_r($_FILES); echo "</pre>";  exit;
		$skip=array("id","add","vehicle_id","atv_id","utv_id","water_id","update","table","other_assigned");
		$query="";
		foreach($_POST as $k=>$v){
			if(in_array(strtolower($k),$skip)){continue;}
			if($k=="assigned_to")
				{
				$var_a=explode("-",$v);
				$v=$var_a[0];
				if(!empty($_POST['other_assigned']))
					{
					$v=$_POST['other_assigned'];
					}
				}
			$v=str_replace(",","",$v);
			//$v=addslashes($v);
			$query.="`".$k."`='".$v."',";
			}
			$query=rtrim($query,",");
			$table=strtolower($_POST['table']);
			$fld=$table."_id";
			$vi="";
			if($table=="vehicle")
				{ 
				$fld="id";
				if(empty($_POST[$fld]))
					{
					$fld="vin";
					}
				$vi=$id; // used to pass value to line 178
				}
	$query = "UPDATE $table set $query where $fld='$_POST[$fld]'";
//	echo "$query";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query<br /><br />");
// echo "$query";exit;
	
	include("upload_bos.php");
	include("upload_inspection.php");
      			
	if(!empty($_FILES['file_upload_fs20']['name']))
		{
		include("upload_fs20.php");
		}
//echo "<pre>"; print_r($_POST); print_r($_FILES); echo "</pre>";  exit;
	echo "<font color='blue'>Your update was successful.</font><br /><br />
	Return to the park's vehicle <a href='/fuel/menu.php?form_type=inventory'>inventory</a>.";
	
	exit;
	}


//**** PROCESS  a Remove ******
if(@$submit=="Remove")
	{
		$table=$_POST['table'];
			$fld=$table."_id";
			$v=$_POST['remove'];
	$query = "UPDATE $table set surplus='$v' where $fld='$_POST[$fld]'";
	//echo "$query";exit;
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


$dbTable="vehicle";

// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql");
while ($row=mysqli_fetch_assoc($result))
	{$fieldArray[]=$row['Field'];}

//echo "<pre>"; print_r($fieldArray); echo "</pre>";  exit;

$subName=array("serial_number"=>"Serial Number","atv_id"=>"ATV ID","utv_id"=>"UTV ID","wex_pin"=>"8. Wright Express PIN","inspected"=>"Last Inspection","center_code"=>"1. Current Park / Program","previous"=>"2. Previous Park","vin"=>"5. VIN number <font size='-1'>(Vehicle Identification Number) <font color='red'>READONLY</font></font>","FAS_num"=>"9. FAS_num", "make"=>"18. Make","engine"=>"14. Engine Size/Class<br /><font size='-1'>Identify no. of cylinders (V6, V8, ...) <b>AND</b> engine size (4.0L, 5.8L, ...)</font>","duty"=>"Duty","trans"=>"15. Transmission","drive"=>"16. Drive","fuel"=>"13. Fuel Type","emergency"=>"Emergency Vehicle?","comment"=>"28. Comment","status"=>"10. Status<br /><font size='-1'>\"Used for Parts\" <font color='red'>requires that the vehicle license plate be turned into the Budget Office.</font></font>","model"=>"19. Model<br /><font size='-1'>(Include as much detail as possible (Example: F250 Superduty))</font>","cost"=>"11. Initial Cost","year"=>"17. Year","license"=>"6. License Plate","title"=>"24. Title","body"=>"20. Body Style","cab"=>"21. Cab Type","purpose"=>"22. Equipped For (most specialized purpose)<br /><font size='-1'><b>Examples:</b> Truck equipped as Law Enforcement AND as a fire Pumper Unit would be considered - \"Pumper Unit\".<br />Truck equipped as Maintenance AND as a fire pumper unit would be considered - \"Pumper Unit\".</font>","park_id"=>"7. Park ID number (optional)","user"=>"4. Primary use by<br /><font size='-1'><b>Permanent Staff</b>-An individual is responsible for daily use of and/or maintenance.<br /><b>Seasonal Staff</b>-May be used by multiple staff but the <br /><b>Assigned To Individual would be responsible for overall assignment, use, and maintenance.</b></font>","cdl"=>"26. CDL required?<br /><font size='-1'>A single vehicle with a GVWR of 26,001 or more pounds requires a Commercial Driver's License.<br />This GVWR classification is ONLY for the vehicle.<br /><b>Please Note: the combination of vehicle and trailer can change the class of CDL required to operate.</b></font>","assigned_to"=>"3. Assigned to:","GVWR"=>"25. Gross Vehicle Weight Rating<br /><font size='-1'>GVWR is determined by the manufacturer and identified on the manufacturer's plate or sticker on each vehicle.<br />This plate or sticker is usually located on the vehicle driver's side door or nearby.</font>","used_for"=>"23. Primary Use <font size='-1'>(How vehicle is used the majority of the time.)<br /><b>Examples:</b> Truck is equipped with a fire pumper unit but assigned and used daily by a maintenance person.<br />The Primary use would be - \"Maintenance\".<br />Truck is equipped as Law Enforcement and has a fire Pumper Unit would be considered primary use - \"LE\".</font>","dot_key"=>"27. DOT Gas Key","justification"=>"30. Justification used to request the purchase of this vehicle.","sold_yyyy_mm"=>"29. Sold - yyyy-mm (2011-05)","recall"=>"30. Recall in Effect");


$text=array("comment");

$radio_duty=array("l"=>"Light Duty","h"=>"Heavy Duty");
$radio_trans=array("m"=>"Manual","a"=>"Automatic");
$radio_emergency=array("y"=>"Yes","n"=>"No");
$radio_title=array("Y"=>"Yes","N"=>"No");
$radio_cdl=array("Y"=>"Yes","N"=>"No");
$radio_recall=array("Y"=>"Yes","N"=>"No");
$radio_status=array("U"=>"In Use","P"=>"Used for Parts","W"=>"Request to be surplused","S"=>"Has been Surplused/Sold");
$radio_cab=array("2"=>"2-Door","X"=>"Xtend_cab","4"=>"4-Door (Crew Cab)");
$radio_user=array("permanent"=>"permanent staff","seasonal"=>"seasonal staff");

$drop_down=array("center_code","previous");

$sql = "SELECT * FROM table_make";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql");
while ($row=mysqli_fetch_assoc($result))
	{
	$make=strtoupper($row['make_code']);
	$radio_make[$make]=$row['make_type'];
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
$sql = "SELECT * FROM table_body_style";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql ");
while ($row=mysqli_fetch_assoc($result))
	{
	$radio_body[$row['body_code']]=$row['body_style'];
	}
//echo "<pre>"; print_r($radio_body); echo "</pre>";	
if($level==1){$park_code=$_SESSION['fuel']['select'];}

$skip=array("id","vehicle_id","surplus","wex_number","fs20","inspection_doc");

if($level<4)
	{$skip[]="sold_yyyy_mm";}


$num_char0=@STRLEN($vi); 
$num_char=$num_char0-4;
if(!empty($vi))
	{
	$new_table=substr($vi,0,$num_char);
	}
	else
	{
	$new_table="vehicle";
	}
$dbTable=$new_table;
$dbTable_upper=strtoupper($dbTable);
$dbTable_lower=strtolower($dbTable);
$fld=$new_table."_id";
if($num_char0>16)  // 17 chars in a VIN
	{
	$dbTable_upper="";
	$dbTable_lower="vehicle";
	$fld="vin";
	}
// echo "f=$fld v=$vi";

if($dbTable_lower!="atv")
	{
	$subName['mileage']="12. Initial Mileage";
	$radio=array("duty","trans","drive","fuel","emergency","body","purpose","cab","title","make","user","cdl","status","used_for","recall");
	}

if($dbTable_lower=="atv" OR $dbTable_lower=="utv")
	{
	$subName['mileage']="Initial Mileage/Hours";
	$radio=array("duty","trans","drive","fuel","emergency","status","used_for");
	}

// Form Header

			include_once("menu.php");
			
echo "<div id='add_form' align='center'><table border='1' cellpadding='5'>";
echo "<form name='frmEdit' action=\"edit.php\" method=\"post\" enctype='multipart/form-data'>";
echo "<tr><td align='center' colspan='2'>$dbTable_upper VEHICLE SPECIFICATIONS</td></tr></table>
</div>";

if(!isset($park_code)){$park_code="";}

echo "<div align='center'><table border='1' cellpadding='5'><tr><td align='center' colspan='2'>$park_code</td></tr>";

if(!empty($id))
	{
	$dbTable_lower="vehicle";
	$fld="id";
	$vi=$id;
//	$vi=$_GET['vehicle_id'];
	}
$sql= "SELECT * from $dbTable_lower where $fld='$vi'";  
// echo "222 $sql $sql0";
$result = mysqli_query($connection,$sql) or die ("Line 203 Couldn't execute query. $sql");
$row=mysqli_fetch_assoc($result);
$id=$row['id'];
$vin=$row['vin'];
$check_assigned=$row['assigned_to'];

$var_pc=$row['center_code'];
IF($var_pc=="ADMI"){$var_pc="ARCH";} // add by teh_20220607

$sql = "SELECT concat(t1.Lname,', ',t1.Fname) as name, t2.beacon_num
FROM divper.empinfo as t1
LEFT JOIN divper.emplist as t2 on t1.tempID=t2.tempID
LEFT JOIN divper.position as t3 on t2.beacon_num=t3.beacon_num
where t3.program_code='$var_pc'
order by t1.Lname"; 

// added join on B0149 
$sql = "SELECT concat(t1.Lname,', ',t1.Fname) as name, t2.beacon_num
FROM divper.empinfo as t1
LEFT JOIN divper.emplist as t2 on t1.tempID=t2.tempID
LEFT JOIN divper.position as t3 on t2.beacon_num=t3.beacon_num
LEFT JOIN divper.B0149 as t4 on t2.beacon_num=t4.position
where t3.program_code='$var_pc'
order by t1.Lname"; 
// echo "258 $sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql");
while ($row_assign=mysqli_fetch_assoc($result))
	{
	if($row_assign['beacon_num']==""){continue;}
	$assign[$row_assign['beacon_num']]=$row_assign['name'];
	}

/*
if($var_pc=="WARE")  // at some point tie this to DIVPER
	{
	$assign['60032852']="Davis, William";   // Maintenance Mechanic IV
	$assign['65020599']="Reavis, Raymond";   // Facility Maintenance Supervisor II
	$assign['65020598']="Parker, Dwayne";   // Facility Maintenance Supv II
	$assign['68000000']="DNCR-Kevin Cherry";   // Facility Maintenance Supv II
	}
	
if($var_pc=="FAMA")  // at some point tie this to DIVPER
	{
	$assign['60033012']="Howerton, Jerry";   // Facility Maintenance Supv II
	}	
if($var_pc=="OPAD" AND $var_pc=="OPAD")  // at some point tie this to DIVPER
	{
	$assign['60033018']="O'Neal, Adrian";   // Facility Maintenance Supv II
	}
*/
if(!empty($check_assigned))
	{
	if(!array_key_exists($check_assigned,$assign))
		{
		$sql = "SELECT concat(t1.Lname,', ',t1.Fname) as name, t2.beacon_num
		FROM divper.empinfo as t1
		LEFT JOIN divper.emplist as t2 on t1.tempID=t2.tempID
		where t2.beacon_num='$check_assigned'"; 
		//echo "292 $sql";
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
	if($row_current['center_code']=="SURP"){continue;}
	// if($row_current['center_code']=="EADI"){$row_current['center_code']="CORE";}
// 	if($row_current['center_code']=="NODI"){$row_current['center_code']="PIRE";}
// 	if($row_current['center_code']=="SODI"){$row_current['center_code']="PIRE";}
// 	if($row_current['center_code']=="WEDI"){$row_current['center_code']="MORE";}
	$temp[]=$row_current['center_code'];
	$park_list_search=array_unique($temp);
	sort($park_list_search);
	}
// echo "<pre>"; print_r($park_list_search); echo "</pre>"; // exit;
mysqli_select_db($connection,$database);
// list of previous park
/*
$sql = "SELECT distinct previous FROM vehicle order by previous";//echo "$sql";
$result = mysqli_query($sql) or die ("Couldn't execute query. $sql c=$connection");
while ($row_previous=mysqli_fetch_assoc($result))
	{
	if($row_previous['previous']==""){continue;}
	$previous_list_search[]=$row_previous['previous'];
	}
*/

$previous_list_search=$park_list_search;

// presently no distinction is made between green and blue
// was proposed at one time to separate PASU and staff
$park_edit_green=array("inspected","assigned_to","user","park_id","status","purpose","used_for","comment","dot_key");
$park_edit_blue=array("fuel","engine","trans","drive","body","cab","GVWR","cdl");

$park_edit=array_merge($park_edit_green,$park_edit_blue);

foreach($row as $k=>$v)
	{
	if(in_array($k,$skip)){continue;}
	$val=""; $RO="";
	if($k=="center_code" and $level<4)
		{
		$input=$park_code;
		}
		else
		{$center_code=$row['center_code'];}
	if($k=="mileage" and $level<1)
		{
		$v=number_format($v);
		$RO="READONLY";
		}
	if($k=="vin")
		{
		$RO="READONLY";
		}

	// Tammy requested a form with only certain fields editable
	// this allows database support and Tammy to edit all
	// see line 371 if($level>3 OR in_array($k,$park_edit)) for radio fields
	// Tom _20220224
	if($level>4 or in_array($admin_position_number,$reduce_form))
		{
		$input="<input type='text' size='30' name='$k' value='$v'$RO>";
		}
	else
		{
		if($level>3 and $k=="sold_yyyy_mm") // allows Heide at level 4 to edit this field
			{$input="<input type='text' size='30' name='$k' value='$v'>";}
			else
			{$input=$v;}		
		}
	if(in_array($k,$radio))
		{
		$var=${"radio_".$k};
		$r_input="";
			foreach($var as $k1=>$v1)
				{
				$rckN="";$rckY="";
				
				@$test_val=$var[$v];
				if($test_val==$v1){$rckN="checked";}
				if($level>3 OR in_array($k,$park_edit))
					{
					if($level<4 AND ($k1=="S" OR $k1=="W"))
						{ // using status codes
						if($k1=="S")
							{
							if($test_val=="Has been Surplused/Sold")
								{$r_input.=" <font color='red'>It $test_val</font>";}
								else
								{
								$r_input.="<br />Only the Budget Office can <b>officially</b> Surplus a vehicle. Contact that office if the status <br />of this vehicle should be \"has been surplused\".";
								}
							}
						if($k1=="W")
							{
							$r_input.=" [ <a href='surplus_request.php?vi=$vi&vin=$row[vin]&lp=$row[license]' target='_blank'>Request</a> to surplus vehicle. ]";
							}
						}
						else
						{
						$r_input.="[<input type='radio' name='$k' value='$k1' $rckY $rckN>$v1] ";
						if($level>3 AND $k=="status" AND $k1=="S")
							{
							$r_input.="<br />Upload Bill of Sale: <input type='file' name='file_upload'  size='20'>";
							$test=strpos($row['user'],"bill_");
							if($test===0)
								{
								$link=$row['user'];
								$r_input.=" link for <a href='$link' target='_blank'>BoS</a>";}
							}
						if($level>3 AND $k=="status" AND $k1=="P")
							{
							$r_input.="<br />Upload DMV FS20: <input type='file' name='file_upload_fs20'  size='20'>";
							$test=strpos($row['fs20'],"fs20");
							if($test===0)
								{
								$link=$row['fs20'];
								$r_input.=" link for <a href='$link' target='_blank'>FS_20</a><br />";}
							}
						}
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
		$len=strlen($v);
		$num_rows=$len/20;
		$input="<textarea name='$k' rows='$num_rows' cols='95'>$v</textarea>";
		}
		
	if(in_array($k,$drop_down) AND $level>3)
		{
		$park_list_search[]="SURP"; // Code for a surplused vehicle
		if($k=="center_code")
			{
			$p_array=$park_list_search;
			}
			else
			{
			$p_array=$previous_list_search;
			}
		$input="<select name='$k'><option selected=''></option>";
		foreach($p_array as $index=>$pc)
			{
			if($pc==$v){$s="selected";}else{$s="value";}
			$input.="<option $s='$pc'>$pc</option>";
			}
		$input.="</select>";
		}
	
	if($k=="inspected")
		{
		$input="<img src=\"../../jscalendar/img.gif\" id=\"f_trigger_c\" style=\"cursor: pointer; border: 1px solid red;\" title=\"Date selector\"
      		onmouseover=\"this.style.background='red';\" onmouseout=\"this.style.background=''\" />&nbsp;<input type='text' name='$k' value='$v' size='12' id=\"f_date_c\" READONLY>";
      		if($level>0)
			{
			$r_input="&nbsp;&nbsp;&nbsp;&nbsp;Upload Inspection Document: <input type='file' name='file_upload_inspection'  size='20'>";
			@$test=strpos($row['inspection_doc'],"inspection");
			if($test===0)
				{
				$link=$row['inspection_doc'];
				$FileDetails = stat($link);
// 				$test=$FileDetails['mtime'];
				$r_input.="link for <a href=$link?MT=". dechex($FileDetails['mtime']) ." />Document</a>";
// 				$r_input.=" link for <a href='$link' target='_blank'>Document</a>&nbsp;&nbsp;&nbsp;&nbsp;".$test;
				}
			$input.=$r_input;
			}
      		}
	
	if($k=="assigned_to")
		{
	//	echo "assign <pre>"; print_r($assign); echo "</pre>"; // exit;
		$input="<select name='$k'><option selected=''></option>";
		foreach($assign as $as_k=>$as_v)
			{
			if($v==$as_k){$s="selected";}else{$s="value";}
			$input.="<option $s='$as_k'>$as_k-$as_v</option>";
			}
		$input.="</select>";
// Get BEACON <a href='find_position_number.php' target='_blank'>Position Number</a> <input type='text' name='other_assigned' size='7'> for other employee";
      		}
      		
      		
	if(isset($subName[$k])){$subN=$subName[$k];}
	echo "<tr><td>$subN</td>
	<td>$input</td>
	</tr>";
	}

echo "<tr><td align='center' colspan='2'>
<input type='hidden' name='table' value='$dbTable_lower'>
<input type='hidden' name='vin' value='$vin'>
<input type='hidden' name='$fld' value='$vi'>
<input type='submit' name='update' value='Submit'>
</td></tr>";
echo "</table></form></div>";

if($level>4)
	{
/*	echo "<hr><table align='center' border='1' cellpadding='5'><form method='POST'>
	<tr><td align='center' colspan='3'>Remove this vehicle from the $center_code inventory.</td></tr>
	<tr><td align='center' colspan='3'>Enter a <b>reason</b> and a <b>date</b>. (Moved to park X for example)</td></tr>
	<tr><td align='center' colspan='3'><input type='text' name='remove' size='55'></td></tr>
	<tr><td align='center' colspan='3'>
	<input type='hidden' name='table' value='$dbTable_lower'>
	<input type='hidden' name='$fld' value='$vi'>
	<input type='submit' name='submit' value='Remove'>
	</td></tr>
	</form></table>";
*/	
	echo "This link will permanently remove record id=$id from the database.
	<form method='POST'>
	<input type='hidden' name='dbTable' value='$dbTable_lower'>
	<input type='hidden' name='id' value='$id'>
<input type='submit' name='submit' value='Delete' onClick=\"return confirmLink()\"></form>";
	}

/*
*/

if($table=="vehicle")
	{
	echo "<script type=\"text/javascript\">
		Calendar.setup({
			inputField     :    \"f_date_c\",     // id of the input field
			ifFormat       :    \"%Y-%m-%d\",      // format of the input field
			button         :    \"f_trigger_c\",  // trigger for the calendar (button ID)
			align          :    \"Tl\",           // alignment (defaults to \"Bl\")
			singleClick    :    true
			});
		</script>";
	}	
echo "</html>";
?>