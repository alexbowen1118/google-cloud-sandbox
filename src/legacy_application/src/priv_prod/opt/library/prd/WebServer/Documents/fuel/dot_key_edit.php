<?php
session_start();

// extract($_REQUEST);
$level=$_SESSION['fuel']['level'];
if($level<1){exit;}

// echo "<pre>"; print_r($_POST); echo "</pre>";  exit;

include("../../include/connectROOT.inc");// database connection parameters
include("../../include/get_parkcodes.php");// include after connectROOT.inc so no inject doesn't get called twice
$database="fuel";
$db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
       
//echo "<pre>"; print_r($_POST); print_r($_FILES); echo "</pre>";  exit;
// echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;

//**** PROCESS  an Update ******
if(@$update=="Submit")
	{
//  echo "<pre>"; print_r($_REQUEST); print_r($_FILES); echo "</pre>";  //exit;
$query="correct_description='$correct_description',correct_plate='$correct_plate',correct_park='$correct_park',fas_num_if_no_plate='$fas_num_if_no_plate',`comment`='$comment'";
		
	$query = "UPDATE dot_keys set $query where dot_id='$_POST[dot_id]'";
// 	echo "$query";  exit;
	$result = mysqli_query($connection, $query) or die ("Couldn't execute query Update. $query<br /><br />".mysqli_error($connection));

	echo "<font color='blue'>Your update was successful.</font><br /><br />
	Close this window.";
	
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
	$result = mysqli_query($connection, $query) or die ("Couldn't execute query Update. $query");
	
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
	$result = mysqli_query($connection, $query) or die ("Couldn't execute query. $query");
	
	echo "<font color='blue'>That vehicle was successfully deleted.</font><br /><br />Please close this window and then click the \"Reload\" button on our web browser to view the change.";
	exit;
	}


$dbTable="dot_keys";

// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
while ($row=mysqli_fetch_assoc($result))
	{$fieldArray[]=$row['Field'];}

//echo "<pre>"; print_r($fieldArray); echo "</pre>";  exit;

$subName=array("dot_key"=>"DOT Key","dot_reported_description"=>"DOT Reported Description","dot_reported_year"=>"DOT Reported Year","dpr_agency"=>"DPR Agency Code","dot_reported_plate"=>"DOT Reported Plate","correct_park"=>"Correct Park","correct_description"=>"Correct Description","correct_plate"=>"Correct Plate","fas_num_if_no_plate"=>"FAS # if no plate","comment"=>"Comments","center_code"=>"DOT Park");


$text=array("comment");

$drop_down=array("correct_park","previous");

if($level==1){$park_code=$_SESSION['fuel']['select'];}

$skip=array("id","vehicle_id","surplus","wex_number","fs20");

// Form Header

			include_once("menu.php");
			
echo "<div id='add_form' align='center'><table border='1' cellpadding='5'>";
echo "<form name='frmEdit' action=\"dot_key_edit.php\" method=\"post\" enctype='multipart/form-data'>";
echo "<tr><td align='center' colspan='2'>$dbTable_upper VEHICLE SPECIFICATIONS</td></tr></table>
</div>";

if(!isset($park_code)){$park_code="";}

echo "<div align='center'><table border='1' cellpadding='5'><tr><td align='center' colspan='2'>$park_code</td></tr>";

if(!empty($dot_key))
	{
	$dbTable_lower="dot_keys";
	}
	
$flds="
t1.`dot_id`, t1.`dot_key`, t1.`dot_reported_description`, t1.`correct_description`, t1.`dot_reported_year`, t1.`dpr_agency`, t1.`dot_reported_plate`, t1.`correct_plate`, t2.center_code, t1.`correct_park`, t1.`fas_num_if_no_plate`, t1.`comment`";

$sql= "SELECT $flds from $dbTable_lower as t1
 left join vehicle as t2 on t1.dot_reported_plate=t2.license
 where t1.dot_key='$dot_key'"; //echo " $sql";
$result = mysqli_query($connection, $sql) or die ("Line 142 Couldn't execute query. $sql");
$row=mysqli_fetch_assoc($result);
$dot_id=$row['dot_id'];

// list of current park
$database = "dpr_system";
mysqli_select_db($connection,$database);
$sql="SELECT park_code AS center_code FROM parkcode_names_district";
//echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql c=$connection");
while ($row_current=mysqli_fetch_assoc($result))
	{
	if($row_current['center_code']=="SURP"){continue;}
	$park_list_search[]=$row_current['center_code'];
	}

$previous_list_search=$park_list_search;
	
$park_edit_green=array("inspected","assigned_to","user","park_id","status","purpose","used_for","comment","dot_key");
$park_edit_blue=array("fuel","engine","trans","drive","year","make","model","body","cab","GVWR","cdl");

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
	if($level>3 OR in_array($k,$park_edit))
		{
		$input="<input type='text' size='30' name='$k' value='$v'$RO>";
		}
	else
		{
		$input=$v;
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
		$input="<textarea name='$k' rows='2' cols='35'>$v</textarea>";
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
			if($pc==$row['center_code']){$s="selected";}else{$s="value";}
			$input.="<option value='$pc' $s>$pc</option>";
			}
		$input.="</select>";
		}


	if(isset($subName[$k])){$subN=$subName[$k];}
	echo "<tr><td>$subN</td>
	<td>$input</td>
	</tr>";
	}

echo "<tr><td align='center' colspan='2'>
<input type='submit' name='update' value='Submit'>
</td></tr>";
echo "</table></form></div>";

if($level>6)
	{
	
	echo "This link will permanently remove record id=$id from the database.
	<form method='POST'>
	<input type='hidden' name='dbTable' value='$dbTable_lower'>
	<input type='hidden' name='dot_id' value='$dot_id'>
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