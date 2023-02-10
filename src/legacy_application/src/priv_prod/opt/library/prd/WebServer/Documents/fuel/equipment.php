<?php
// extract($_REQUEST);
// echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;

if($level>4)
	{ini_set('display_errors',1);}
				
include("../../include/iConnect.inc");// database connection parameters
$database="fuel";
mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
       
// echo "<pre>"; print_r($_POST); echo "</pre>";  //exit;
if(empty($rep))
	{
	include_once("menu.php");
	}
$where="";

//**** PROCESS  a Search ******
if(@$search=="Find")
	{
	if(empty($_POST))
		{$_POST=$_GET;}
	
	$skip=array("search","PHPSESSID","sort","form_type","rep");
	
// 	echo "<pre>"; print_r($_POST); echo "</pre>";  //exit;
// 	echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;
		$like=array("park_id","make","model_year","engine","vin");
		foreach($_POST as $k=>$v){
			if(in_array($k,$skip)){continue;}
			if($v==""){continue;}
				if(in_array($k,$like)){
					$where.=" and (t1.`".$k."` like '%".$v."%')";
					}
				else
				{$where.=" and t1.`".$k."`='".$v."'";}		
			}
	}
// echo "w=$where";

if(empty($where))
	{$where=" and center_code='".$_SESSION['fuel']['select']."'";}
	
// Display
if(!isset($sort)){$sort="";}
if(!isset($park_code)){$park_code=$_SESSION[$database]['select'];}

// if($level==1){$where.=" and t1.center_code='$park_code'";}

//if($level==2){$where.=" and center_code='$park_code'";}

if($level>2 AND @$search=="" AND $sort=="")
	{
	//$limit="limit 100";
	}

$orderBy="order by center_code,equip_cat_code";
if($sort=="cc"){$orderBy="order by center_code";}
if($sort=="p"){$orderBy="order by park_id";}
if($sort=="m"){$orderBy="order by make";}
if($sort=="mi"){$orderBy="order by mileage desc";}
if($sort=="my"){$orderBy="order by model_year";}
if($sort=="d"){$orderBy="order by duty";}
if($sort=="t"){$orderBy="order by trans";}
if($sort=="dr"){$orderBy="order by drive";}
if($sort=="f"){$orderBy="order by fuel";}
if($sort=="ecc"){$orderBy="order by equip_cat_code";}
// if($sort=="e"){$orderBy="order by emergency";}


$database="fuel";
mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

if(!empty($_POST))
{
//t1.`emergency`,  t1.`hours_current`, 
$t1_flds="t1.id, t1.`equip_cat_code`, t1.`center_code`, t1.`equipment_id`, t1.`serial_number`, t1.`vin`, t1.`fas_num`, t1.`mileage`,t1.`date_of_last_service`, t1.`hours_at_last_service`,  t1.`condition`, t1.`make`, t1.`model_year`, t1.`engine`, t1.`drive`, t1.`fuel`, t1.`comment`";
$t2_flds="t2.equip_cat";
if(!isset($limit)){$limit="";}       
 $sql= "SELECT $t2_flds, $t1_flds 
 from equipment as t1
 left join equipment_category as t2 on t1.equip_cat_code=t2.equip_cat_code
 where 1
 $where
 $orderBy
 $limit
 "; 

// echo " $sql <br />";
$passWhere=str_replace("and ","&",$where);
$passWhere=str_replace("'","",$passWhere);
$passWhere=str_replace("`","",$passWhere);
$passWhere=str_replace(" ","",$passWhere);
//echo "<br />$where <br />$passWhere";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql ".mysqli_error($connection));
$num=mysqli_num_rows($result);
if($num<1){echo "No equipment found using $where";}

while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	}
// echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;

if(!empty($rep))
		{
		$header_array[]=array_keys($ARRAY[0]);
		$filename="Equipment_export.csv";
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=$filename");
		// Disable caching
		header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
		header("Pragma: no-cache"); // HTTP 1.0
		header("Expires: 0"); // Proxies


		function outputCSV($header_array, $data) {
		$output = fopen("php://output", "w");
		foreach ($header_array as $row) {
			fputcsv($output, $row); // here you can change delimiter/enclosure
		}
		foreach ($data as $row) {
			fputcsv($output, $row); // here you can change delimiter/enclosure
		}
		fclose($output);
		}

		outputCSV($header_array, $ARRAY);

		exit;
		}
	}
//**** PROCESS  a Reply ******
if(@$add=="Add")
	{
// 	echo "<pre>"; print_r($_POST); echo "</pre>";  exit;
		$skip=array("add");
		foreach($_POST as $k=>$v)
			{
			if(in_array($k,$skip)){continue;}
			$v=str_replace(",","",$v);
			if($k=="center_code"){$v=strtoupper($v);}
			if($v != ""){
				@$query.="`".$k."`='".$v."',";
			}
			}
			$query=rtrim($query,",");
			
		if(empty($_POST['equip_cat_code']))
			{
			$error[]="You must select an Equipment Category.";
			}
		if(empty($_POST['center_code']))
			{
			$error[]="You must select a Park Unit.";
				}
	if(empty($error))
		{
		$query = "INSERT INTO equipment set $query"; 
// 			echo "$query";exit;
		$result = mysqli_query($connection,$query) or die ("Couldn't execute query Insert. $query");
		$id=mysqli_insert_id($connection); //echo "$id";exit;
		$vi=str_pad($id,4,"0",STR_PAD_LEFT);
		$equipment_id=$equip_cat_code.$vi;

		$query = "UPDATE equipment set equipment_id='$equipment_id' where id='$id'";
		//echo "$query";exit;
		$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");

		echo "Adding the equipment item was successful. Click button to view all equipment items for $center_code. You can add any photo(s) by clicking on the link to his recently added item.";
		echo "<br /><br /><form method='POST' action='menu.php'>
		<input type='hidden' name='form_type' value=\"equipment\">
		<input type='hidden' name='center_code' value=\"$center_code\">
		<input type='submit' name='search' value=\"Find\">
		</form>";
		exit;
		}
		else
		{
		echo "<pre>"; print_r($error); echo "</pre>";  exit;
		}
	}

$dbTable="equipment";
//if($form_type=="atv"){$dbTable="equipment";}

// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$sql = "SHOW COLUMNS FROM $dbTable";   //echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql");
while ($row=mysqli_fetch_assoc($result))
	{
	$fieldArray[]=$row['Field'];
	}
//  echo "<pre>"; print_r($fieldArray); echo "</pre>";  //exit;

$skip_center=array("ADMI","ASRO","BOCA");
$sql = "SELECT distinct center_code from equipment order by center_code";   //echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql");
while ($row=mysqli_fetch_assoc($result))
	{
	IF(in_array($row['center_code'], $skip_center)){continue;}
	$park_code_array[$row['center_code']]=$row['center_code'];
	}
	
$sql = "SELECT * from equipment_category";   //echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql");
while ($row=mysqli_fetch_assoc($result))
	{
	$equipment_category_array[$row['equip_cat_code']]=$row['equip_cat'];
	}
	
// ,"emergency"=>"Emergency Use?"
$subName=array("serial_number"=>"Serial Number","center_code"=>"Park Unit","mileage"=>"Mileage","make"=>"Make/Model<br /><font size='-1'>(Include as much detail as possible (Example: John Deere Gator CX 4x2))</font>","model_year"=>"Model Year<br /><font size='-1'>Please correctly identify, as it is an important variable.</font>","engine"=>"Engine Size/Class<br /><font size='-1'>Example: 249cc Kawasaki 8HP</font>","duty"=>"Duty","trans"=>"Transmission","drive"=>"Drive","fuel"=>"Fuel Type", "equip_cat_code"=>"Equipment Category", "fas_num"=>"FAS Number", "condition"=>"Condition", "date_of_last_service"=>"Date of Last Service", "comment"=>"Comment", "hours_current"=>"Current Hours", "hours_at_last_service"=>"Hours at Last Service", "vin"=>"VIN (trailer)", "used_for"=>"Primary Use");
// if modified, also make changes to edit_equipment.php


$text=array("comment");

$radio_drive['zt']="Zero Turn";   // also modify edit_equipment.php ~line 213
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
	
$sql = "SELECT * FROM table_used_for";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql ");
while ($row=mysqli_fetch_assoc($result))
	{
	$radio_used_for[$row['purpose_code']]=$row['purpose_type'];
	}
		
$radio=array("drive","fuel","used_for");

// $radio_duty=array("l"=>"Light Duty","h"=>"Heavy Duty");
// $radio_trans=array("m"=>"Manual","a"=>"Automatic");

// $radio_emergency=array("y"=>"Yes","n"=>"No");

$condition_array=array("Good","Fair","Poor","Inoperable");  // also modify equipment.php ~line 125
if($level==1)
	{
	$parkList=explode(",",$_SESSION['fuel']['accessPark']);
	if($parkList[0]==""){$park_code=$_SESSION['fuel']['select'];}		
	}
	else
	{
	$parkList[0]="";
	}

include_once("../../include/get_parkcodes_dist.php");
$parkCode[]="FAMA";
sort($parkCode);
// echo "<pre>"; print_r($parkCode); echo "</pre>"; // exit;
$parkCodeName['FAMA']="Facility Maintenance";
// echo "<pre>"; print_r($parkCodeName); echo "</pre>"; // exit;

$skip=array("id","equipment_id");

// Form Header
// echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
echo "<div id='add_form' align='center'><table border='1' cellpadding='5'>";

echo "<tr><td align='center' colspan='2'>EQUIPMENT SPECIFICATIONS</td></tr>";
echo "<tr>
<td><a href='menu.php?form_type=equipment&action=add'>Add an Equipment Item</a></td>
<td><a href='menu.php?form_type=equipment&action=search'>Search</a></td>
</tr>";

// <a onclick=\"toggleDisplay('show_form');\" href=\"javascript:void('')\"><br />Add an Equipment Item</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick=\"toggleDisplay('search_form');\" href=\"javascript:void('')\">Search</a></td>";

// For Level 1 with privileges
	if(@$parkList[0]!=""){
		if($park_code AND in_array($park_code,$parkList)){
			$_SESSION['fuel']['select']=$park_code;
			}
		echo "<td><form><select name=\"center\" onChange=\"MM_jumpMenu('parent',this,0)\"><option selected></option>";
		foreach($parkList as $k=>$v){
			$con1="menu.php?form_type=equipment&park_code=$v";
			if($v==$_SESSION['fuel']['select'])
				{
				$park_code=$v;
				$s="selected";
				}
				else
				{$s="";}
			echo "<option value='$con1' $s>$v\n";
    		   }
  	 echo "</select></td></form>";
	}

// Level 2

if($level==2){
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
$distCode=$_SESSION['fuel']['select'];
if($tempID=="Jones8869")
	{$distCode="EADI";}
$menuList="array".$distCode; $parkArray=${$menuList};
sort($parkArray);

		if($park_code AND in_array($park_code,$parkArray)){
			$_SESSION['fuel']['temp']=$park_code;
			}
		echo "<td><form><select name=\"center\" onChange=\"MM_jumpMenu('parent',this,0)\"><option selected></option>";
		foreach($parkArray as $k=>$v){
			$con1="menu.php?form_type=equipment&park_code=$v";
			if($v==$_SESSION['fuel']['temp']){
				$park_code=$v;
				$s="selected";}else{$s="value";}
			echo "<option $s='$con1'>$v\n";
    		   }
  	 echo "</select></td></form>";
	
}

echo "</tr></table>
</div>";


// Input Form
if(!isset($park_code)){$park_code="";}
if(empty($action)){$action="search";}
if($action=="add"){$display_add="block";}else{$display_add="none";}
if($action=="search"){$display_search="block";}else{$display_search="none";}
if(!empty($ARRAY)){$display_add="none"; $display_search="none";}

echo "<div align='center' id=\"show_form\" style=\"display: $display_add\"><table border='1' cellpadding='5'><tr><form name='frmTest' action=\"equipment.php\" method=\"post\" onsubmit=\"return radio_button_checker()\"><td align='center' colspan='2'>$parkCodeName[$park_code]</td></tr>";

	foreach($fieldArray as $k=>$v){
		if(in_array($v,$skip)){continue;}
		$val=""; $RO="";
		$input="<input type='text' size='30' name='$v' value='$val'$RO>";
		
		if($v=="center_code"){
			@$val=$park_code;
			IF($level<3){$RO="READONLY";}
		$input="<input type='text' size='30' name='$v' value='$val'$RO>";
			
				if($level>=2)
					{					
					$database="dpr_system";
					mysqli_select_db($connection,$database)
					 or die ("Couldn't select database");
			
					$sql= "SELECT park_code AS parkCode FROM parkcode_names_district";
					$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
// 			echo "<pre>"; print_r($parkCode); echo "</pre>"; // exit;
			if(!isset($park_code)){$park_code="";}
					while($row=mysqli_fetch_assoc($result))
						{
						$allParks[]=$row['parkCode'];
						
						$input="<select name=\"center_code\" required><option selected></option>";
						foreach($parkCode as $kk=>$vv)
							{
								$vv=strtoupper($vv);
								$con1=$vv;
								if($vv==$park_code){
								$park_code=$vv;
								$s="selected";}else{$s="";}
								$input.="<option value='$con1' $s>$vv\n";
							}
							$input.="</select>";
						}
					}
			}
		if(in_array($v,$radio)){
			$var=${"radio_".$v};
			$r_input="";
				foreach($var as $k1=>$v1){
			$r_input.="<input type='radio' name='$v' value='$k1'>$v1 ";
					}
			$input=$r_input;
			}
		
		if($v=="condition")
			{
			$input="<select name='$v'><option value=\"\"></option>\n";
			foreach($condition_array as $k1=>$v1)
				{
				$input.="<option value='$v1'>$v1</option>\n";
				}
			$input.="</select>";
			}
		if($v=="equip_cat_code")
			{
			$input="<select name='$v' required><option value=\"\"></option>\n";
			foreach($equipment_category_array as $k1=>$v1)
				{
				$input.="<option value='$k1'>$v1</option>\n";
				}
			$input.="</select>";
			}
				
		if(in_array($v,$text))
			{
			$input="<textarea name='$v' rows='2' cols='35'></textarea>";
			}
		
		echo "<tr><td>$subName[$v]</td>
		<td>$input</td>
		</tr>";
		}

echo "<tr><td align='center' colspan='2' bgcolor='lightgreen'><input type='submit' name='add' value='Add'></td></tr>";
echo "</table></form></div>";

// echo "<pre>"; print_r($fieldArray); echo "</pre>"; // exit;
// Search Form
echo "<div align='center' id=\"search_form\" style=\"display: $display_search\"><table border='1' cellpadding='5'><tr><form name='frmSearch' action=\"menu.php?form_type=equipment\" method=\"post\">
<td align='center' colspan='2'>$parkCodeName[$park_code]</td></tr>";


	foreach($fieldArray as $k=>$v){
		if(in_array($v,$skip)){continue;}
		
		$input="<input type='text' size='30' name='$v' value='$val' $RO>";
		
		if(in_array($v,$radio))
			{
			$var=${"radio_".$v};
			$r_input="";
			foreach($var as $k1=>$v1)
				{
				$r_input.="<input type='radio' name='$v' value='$k1'>$v1 ";
				}
			$input=$r_input;
			}
		if($v=="center_code")
			{
			$input="<select name='$v'><option value=\"\"></option>\n";
			if($level<2)
				{$pc=$_SESSION[$database]['select'];}else{$pc="";}
			foreach($park_code_array as $k1=>$v1)
				{
				if($v1==$pc){$s="selected";}else{$s="";}
				$input.="<option value='$k1' $s>$v1</option>\n";
				}
			$input.="</select>";
			}
		if($v=="condition")
			{
			$input="<select name='$v'><option value=\"\"></option>\n";
			foreach($condition_array as $k1=>$v1)
				{
				$input.="<option value='$v1'>$v1</option>\n";
				}
			$input.="</select>";
			}
		if($v=="equip_cat_code")
			{
			$input="<select name='$v'><option value=\"\"></option>\n";
			foreach($equipment_category_array as $k1=>$v1)
				{
				$input.="<option value='$k1'>$v1</option>\n";
				}
			$input.="</select>";
			}

		
		echo "<tr><td>$subName[$v]</td>
		<td>$input</td>
		</tr>";
		}

echo "<tr><td align='center' colspan='2' bgcolor='aliceblue'><input type='submit' name='search' value='Find'></td></tr>";
echo "</table></form></div>";


if(isset($ARRAY))
	{
	
		
	$skip1=array("id","comment");
	
	if($level>3){$export_all=" or <a href=equipment.php?search=Find&rep=x>All Parks</a>";}
	else{$export_all="";}
	
	if(empty($center_code)){$var_cc="";}
	else
	{
	$var_cc="<a href=equipment.php?center_code=$center_code&search=Find&rep=x>$center_code</a> ";
	}
	echo "<div align='center'><table border='1' cellpadding='5'>";
	$ee="";
	if(!empty($export_all) or !empty($var_cc))
		{
		$ee="<th colspan='9'>Excel export $var_cc $export_all</th>";
		}
	
	echo "<tr><th colspan='7'>Equipment items: $num</td>$ee</tr>";
	
	if($level==1){echo "<tr><th colspan='16'>$parkCodeName[$park_code]</th></tr>";}
	
		$passWhere=str_replace("t1.", "", $passWhere);
	echo "<tr>";
	foreach($ARRAY[0] as $k=>$v){
		if(in_array($k,$skip1)){continue;}
	switch($k)
		{
		case 'equip_cat_code';
			$k="<a href='menu.php?search=Find&form_type=equipment&sort=ecc$passWhere'>equip_cat_code</a>";
			break;
		case 'center_code';
			$k="<a href='menu.php?search=Find&form_type=equipment&sort=cc$passWhere'>center code</a>";
			break;
		case 'park_id';
			$k="<a href='menu.php?search=Find&form_type=equipment&sort=p$passWhere'>License Plate</a>";
			break;
		case 'mileage';
			$k="<a href='menu.php?search=Find&form_type=equipment&sort=mi$passWhere'>mileage</a>";
			break;
		case 'make';
			$k="<a href='menu.php?search=Find&form_type=equipment&sort=m$passWhere'>$k</a>";
			break;
		case 'model_year';
			$k="<a href='menu.php?search=Find&form_type=equipment&sort=my$passWhere'>$k</a>";
			break;
		case 'duty';
			$k="<a href='menu.php?search=Find&form_type=equipment&sort=d$passWhere'>$k</a>";
			break;
		case 'trans';
			$k="<a href='menu.php?search=Find&form_type=equipment&sort=t$passWhere'>$k</a>";
			break;
		case 'drive';
			$k="<a href='menu.php?search=Find&form_type=equipment&sort=dr$passWhere'>$k</a>";
			break;
		case 'fuel';
			$k="<a href='menu.php?search=Find&form_type=equipment&sort=f$passWhere'>$k</a>";
			break;
		// case 'emergency';
// 			$k="<a href='menu.php?search=Find&form_type=equipment&sort=e$passWhere'>$k</a>";
// 			break;
	//   	default;
	//        	$k="<a href='menu.php?form_type=equipment&s=e'>$k</a>";
	//   		break;
		}
		
		echo "<th>$k</th>";
	}
	echo "</tr>";
// echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;	
	foreach($ARRAY as $num=>$array)
		{
		echo "<tr>";
		foreach($array as $k=>$v){
			if(in_array($k,$skip1)){continue;}
			$input=$v;
			if(in_array($k,$radio)){
				@$var=${"radio_".$k};
				if(empty($var[$v]))
					{$input="<font color='red'>Value is missing.</font>";}
					else
					{$input=$var[$v];}			
				}
				
			if($k=="equipment_id")
				{
				$var_id=$array['id'];
				$input="<a href='edit_equipment.php?vi=$var_id' target='_blank'>$input</a>";
				}
				
			echo "<td align='center'>$input</td>";
			}
		echo "</tr>";
		}
	
	
	echo "</table></div>";
	}

echo "</html>";


?>