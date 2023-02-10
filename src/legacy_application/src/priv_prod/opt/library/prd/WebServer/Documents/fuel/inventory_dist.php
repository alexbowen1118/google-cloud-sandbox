<?php
//session_start();
extract($_REQUEST);
//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
//   echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;

include("../../include/connectROOT.inc");// database connection parameters
include("../../include/get_parkcodes.php");// database connection parameters

$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
       
//echo "<pre>"; print_r($_SESSION); echo "</pre>";  //exit;

//**** PROCESS  a Search ******
if(@$search=="Find")
	{
//	echo "<pre>"; print_r($_POST); echo "</pre>";  exit;
	//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;
		$skip=array("search","PHPSESSID","sort","form_type","surplus","rep","fs20","order");
		$like=array("vehicle_id","park_id","make","model_year","engine","vin","comment","assigned_to");
		$where="";
		foreach($_POST as $k=>$v)
			{
				if(in_array($k,$skip)){continue;}
				if($v==""){continue;}
					if(in_array($k,$like))
						{
						if($k=="assigned_to")
							{$where.=" and (t2.`tempID` like '".$v."%')";}
							else
							{$where.=" and (`".$k."` like '%".$v."%')";}				
						}
					else
					{$where.=" and `".$k."`='".$v."'";}		
			}
	
			include_once("menu.php");
	}
//echo "$where";

//**** PROCESS  a Reply ******
if(@$add=="Add")
	{
	//echo "<pre>"; print_r($_POST); echo "</pre>";  //exit;
		$skip=array("add","id");
		foreach($_POST as $k=>$v){
			if(in_array($k,$skip)){continue;}
			$v=str_replace(",","",$v);
			$v=addslashes($v);
			if($k=="center_code"){$v=strtoupper($v);}
			$query.=$k."='".$v."',";
			}
			$query=rtrim($query,",");
	$query = "INSERT INTO vehicle set $query"; //echo "$query";exit;
	$result = mysqli_query($connection, $query) or die ("Couldn't execute query Update. $query");
	$id=mysqli_insert_id($connection); //echo "$id";exit;
		$vi=str_pad($id,4,"0",STR_PAD_LEFT);
	$vehicle_id="DPR".$vi;
	$query = "UPDATE vehicle set vehicle_id='$vehicle_id' where id='$id'";
	//echo "$query";exit;
	$result = mysqli_query($connection, $query) or die ("Couldn't execute query Update. $query");
	
	header("Location: menu.php?form_type=inventory");
	exit;
	}

$dbTable="vehicle";
//$dbTable="vehicle_truck";

// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
while ($row=mysqli_fetch_assoc($result))
	{$fieldArray[]=$row['Field'];}

//echo "<pre>"; print_r($fieldArray); echo "</pre>";  exit;


$skip=array("id","vehicle_id","inspected");

$subName=array("inspected"=>"Last Inspection","center_code"=>"Current Park","previous"=>"Previous Park","vin"=>"VIN number <font size='-1'>(Vehicle Identification Number)</font>","FAS_num"=>"FAS_num","mileage"=>"Starting Mileage","make"=>"Make","engine"=>"Engine Size/Class<br /><font size='-1'>Identify no. of cylinders (V6, V8, ...) <b>AND</b> engine size (4.0L, 5.8L, ...)</font>","duty"=>"Duty","trans"=>"Transmission","drive"=>"Drive","fuel"=>"Fuel Type","emergency"=>"Emergency Vehicle?","comment"=>"Comment","status"=>"Status","model"=>"Model<br /><font size='-1'>(Example: F250 Superduty)</font>","cost"=>"Initial Cost","year"=>"Year","license"=>"License Plate","title"=>"Title","body"=>"Body Style","cab"=>"Cab Type","purpose"=>"Primary Purpose","mileage"=>"Initial Mileage","park_id"=>"Park ID number (optional)","user"=>"Primary use by","cdl"=>"CDL required?","assigned_to"=>"Assigned to:","GVWR"=>"Gross Vehicle Weight Rating","wex_number"=>"Wright Express Number","wex_pin"=>"Wright Express PIN","used_for"=>"Primary Use","dot_key"=>"DOT Key","recall"=>"Recall in Effect");


// if modified, also make changes to edit.php
$text=array("comment");
$radio=array("duty","trans","drive","fuel","emergency","cab","purpose","body","title","make","used_for","status","recall");

$radio_duty=array("l"=>"Light Duty","h"=>"Heavy Duty");
$radio_trans=array("m"=>"Manual","a"=>"Automatic");
$radio_drive=array("2"=>"2WD","4"=>"4WD","A"=>"AWD");
$radio_fuel=array("u"=>"Unleaded","f"=>"Flex","d"=>"Diesel","e"=>"Electric");
$radio_emergency=array("y"=>"Yes","n"=>"No");
$radio_recall=array("Y"=>"Yes");
$radio_cab=array("2"=>"2-Door","4"=>"4-Door","X"=>"Xtend_cab");
$radio_title=array("Y"=>"Yes","N"=>"No");
$radio_status=array("U"=>"In Use","P"=>"Used for Parts","W"=>"Request to be surplused","S"=>"Has been Surplused/Sold");

$drop_down=array("center_code","previous");

$sql = "SELECT * FROM table_make";//echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
while ($row=mysqli_fetch_assoc($result))
	{
	$make=strtoupper($row['make_code']);
	$radio_make[$make]=$row['make_type'];
	}
$sql = "SELECT * FROM table_purpose";//echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
while ($row=mysqli_fetch_assoc($result))
	{
	$radio_purpose[$row['purpose_code']]=$row['purpose_type'];
	}

$sql = "SELECT * FROM table_used_for";//echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
while ($row=mysqli_fetch_assoc($result))
	{
	$radio_used_for[$row['purpose_code']]=$row['purpose_type'];
	}	
$sql = "SELECT * FROM table_body_style";//echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
while ($row=mysqli_fetch_assoc($result))
	{
	$radio_body[$row['body_code']]=$row['body_style'];
	}
	
if($level==1)
	{
		$parkList=explode(",",$_SESSION['fuel']['accessPark']);
		if($parkList[0]=="")
			{
			// if($_SESSION['fuel']['beacon_num']=="60032780")
// 				{$_SESSION['fuel']['select']="INED";}
			$park_code=$_SESSION['fuel']['select'];
			$center_code=$park_code;
			}
		// if($_SESSION['fuel']['working_title']=="Inventory & Monitoring Specialist")
// 			{$park_code="REMA";$center_code=$park_code;}
// 		if($_SESSION['fuel']['working_title']=="Environmental Specialist")
// 			{$park_code="REMA";$center_code=$park_code;}		
	}

if(!isset($parkList)){$parkList[0]="";}
// Form Header
echo "<div id='add_form' align='center'><table border='1' cellpadding='5'>";
// OR $_SESSION['fuel']['select']=="SODI"
if($level>3)
	{
	echo "<tr><td align='center' colspan='2'>ON-ROAD VEHICLE SPECIFICATIONS
	<a onclick=\"toggleDisplay('show_form');\" href=\"javascript:void('')\"><br />Add a Vehicle</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}

echo "<a onclick=\"toggleDisplay('search_form');\" href=\"javascript:void('')\">Search</a></td>";


// For Level 1 with privileges
	if($parkList[0]!="")
		{
			if($park_code AND in_array($park_code,$parkList)){
				$_SESSION['fuel']['select']=$park_code;
				}
			echo "<td><form><select name=\"center\" onChange=\"MM_jumpMenu('parent',this,0)\"><option selected></option>";
			foreach($parkList as $k=>$v){
				$con1="menu.php?form_type=inventory&park_code=$v";
				if($v==$_SESSION['fuel']['select']){
					$park_code=$v;
					$s="selected";}else{$s="value";}
				echo "<option $s='$con1'>$v\n";
				   }
		 echo "</select></td></form>";
		}


// Level 2

if($level==2)
	{
	$distCode=$_SESSION['fuel']['select'];
	$menuList="array".$distCode; 
	$parkArray=${$menuList};
	sort($parkArray);
	
			if(@$park_code AND in_array($park_code,$parkArray))
				{
				$_SESSION['fuel']['temp']=$park_code;
				}
		$parkArray[]="All-$distCode";
			echo "<td><form><select name=\"center\" onChange=\"MM_jumpMenu('parent',this,0)\"><option selected></option>";
			foreach($parkArray as $k=>$v){
				$con1="menu.php?form_type=inventory&center_code=$v&search=Find";
				if($v==@$center_code)
					{$park_code=$v;$s="selected";}else{$s="value";}
				echo "<option $s='$con1'>$v\n";
				   }
		 echo "</select></td></form>";
		
	}

echo "</tr></table>
</div>";



// Input Form
if(!isset($park_code)){$park_code="";}
echo "<div align='center' id=\"show_form\" style=\"display: none\"><table border='1' cellpadding='5'><tr><form name='frmTest' action=\"inventory.php\" method=\"post\" onsubmit=\"return radio_button_checker()\"><td align='center' colspan='2'>$park_code</td></tr>";

	foreach($fieldArray as $k=>$v)
		{
			if(in_array($v,$skip)){continue;}
			$val=""; $RO="";
			$input="<input type='text' size='30' name='$v' value='$val'$RO>";
			
			if($v=="center_code")
				{
					$val=$park_code;
					IF($level<3){$RO="READONLY";}
				$input="<input type='text' size='30' name='$v' value='$val'$RO>";
					
						if($level>2)
						{
						
						$database="dpr_system";
						$db = mysqli_select_db($connection,$database)
						 or die ("Couldn't select database");
		
						$sql= "SELECT park_code AS parkCode FROM parkcode_names_district";
						$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql");
				//echo "$sql";
						while($row=mysqli_fetch_assoc($result))
							{
							$allParks[]=$row['parkCode'];
							$allParks[]="YORK";
							$input="<select name=\"center_code\"><option selected></option>";
							foreach($allParks as $kk=>$vv)
								{
								$vv=strtoupper($vv);
								$con1=$vv;
								if($vv==$park_code)
									{
									$park_code=$vv;
									$s="selected";
									}
									else
									{$s="value";}
								$input.="<option $s='$con1'>$vv\n";
								 }
							 $input.="</select>";
							}
						}
					}
			if(in_array($v,$radio))
				{
				$var=${"radio_".$v};
				$r_input="";
					foreach($var as $k1=>$v1){
				$r_input.="[<input type='radio' name='$v' value='$k1'>$v1] ";
						}
				$input=$r_input;
				}
				
			if(in_array($v,$text))
				{
				$input="<textarea name='$v' rows='2' cols='35'>$k1</textarea>";
				}
			echo "<tr><td>$subName[$v]</td>
			<td>$input</td>
			</tr>";
			}

echo "<tr><td align='center' colspan='2' bgcolor='lightgreen'><input type='submit' name='add' value='Add'></td></tr>";
echo "</table></form></div>";

$database="fuel";
mysqli_select_db($connection,$database);
$sql = "SELECT distinct center_code FROM vehicle order by center_code";//echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql c=$connection");
while ($row=mysqli_fetch_assoc($result))
	{
	if($row['center_code']=="SURP"){continue;}
	$park_list_search[]=$row['center_code'];
	}
$sql = "SELECT distinct previous FROM vehicle order by previous";//echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql c=$connection");
while ($row=mysqli_fetch_assoc($result))
	{
	if($row['previous']==""){continue;}
	$previous_list_search[]=$row['previous'];
	}
// Search Form
echo "<div align='center' id=\"search_form\" style=\"display: none\"><table border='1' cellpadding='5'><tr><form name='frmSearch' action=\"menu.php?form_type=inventory\" method=\"post\"><td align='center' colspan='2'>$park_code</td></tr>";

foreach($fieldArray as $k=>$v)
	{
	if(in_array($v,$skip)){continue;}
	$val=""; $RO="";
	if($v=="center_code"){
		$val=$park_code;
		IF($level==1){$RO="READONLY";}
		}
	$input="<input type='text' size='30' name='$v' value='$val'$RO>";
	if(in_array($v,$radio)){
		$var=${"radio_".$v};
		$r_input="";
			foreach($var as $k1=>$v1){
		$r_input.="[<input type='radio' name='$v' value='$k1'>$v1]&nbsp; ";
				}
		$input=$r_input;
		}
	if(in_array($v,$drop_down) AND $level>3)
		{
		$park_list_search[]="SURP"; // Code for a surplused vehicle
		if($v=="center_code")
			{
			$p_array=$park_list_search;
			}
			else
			{
			$p_array=$previous_list_search;
			}
		$input="<select name='$v'><option selected=''></option>";
		foreach($p_array as $index=>$pc)
			{
			if($pc==$v){$s="selected";}else{$s="value";}
			$input.="<option $s='$pc'>$pc</option>";
			}
		$input.="</select>";
		}
	echo "<tr><td>$subName[$v]</td>
	<td>$input</td>
	</tr>";
	}

echo "<tr><td align='center' bgcolor='aliceblue'>
Check to ONLY view surplused vehicles. <input type='checkbox' name='surplus' value='x'></td>
<td align='center' bgcolor='green'><input type='submit' name='search' value='Find'>
</td></tr>";
echo "</table></form></div>";


// Display
if(!isset($sort)){$sort="";}
if(!isset($search)){$search="";}

if($level==1)
	{
	$center_code=$park_code;
	@$where.=" and center_code='$park_code'";
	}

$left_join_district="";
$dist_fld="";
$where_dist="";
if($level==2)
	{
	if(strpos(@$center_code,"All")>-1)
		{
		$where="";
		$exp_cc=explode("-",$center_code);
		$district=$exp_cc[1];
		
		@$where.=" and district='$district'";	
		}
	else
		{
		$district=$_SESSION['fuel']['select'];
		
		@$where.=" and district='$district'";	
		}
	$orderBy="t1.center_code";
	}

if($level>1 AND $search=="" AND $sort=="")
	{
	exit;
	}

$orderBy="order by park_id, license";
if(!empty($sort))
	{
	if(!empty($order)){$order="DESC";}ELSE {$order="";}
	$orderBy="order by $sort $order";
	}

if(!empty($district) AND empty($sort)){$orderBy="order by center_code";}

if(!$_POST AND @$_SERVER['argv'][0]=="form_type=inventory"){EXIT;}

include("../../include/connectROOT.inc");// database connection parameters
$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

//$where0="surplus=''";
$where0="1 ";
if(@$_POST['surplus']=="x")
	{$where0="center_code='SURP'";}
	else
	{$where0="center_code!='SURP'";}
	
if(!isset($limit)){$limit="";}

// used to reorder fields for display - purpose and used_for now come after user
$t1_fields="t1.`id`, t1.`inspected`, t1.`center_code`, t1.`previous`, t1.`assigned_to`, t1.`user`, t1.`purpose`, t1.`used_for`, t1.`fs20`, t1.`vehicle_id`, t1.`vin`, t1.`license`, t1.`park_id`, t1.`wex_pin`, t1.`FAS_num`, t1.`status`, t1.`cost`, t1.`mileage`, t1.`fuel`, t1.`engine`, t1.`trans`, t1.`drive`, t1.`year`, t1.`make`, t1.`model`, t1.`body`, t1.`cab`, t1.`title`, t1.`GVWR`, t1.`cdl`, t1.`dot_key`, t1.`comment`, t1.`sold_yyyy_mm`,t1.`recall`";

 $sql= "SELECT t4.district as district, $t1_fields, t3.Lname
from vehicle as t1
 LEFT JOIN divper.emplist as t2 on t2.beacon_num=t1.assigned_to and t2.beacon_num!=''
 LEFT JOIN divper.empinfo as t3 on t2.tempID=t3.tempID
LEFT JOIN dpr_system.parkcode_names as t4 on t4.park_code=t1.center_code
 where $where0
 $where
$where_dist
 $orderBy
 $limit
 "; 
// echo "c=$center_code";
//echo " $sql s=$ts";
$passWhere=str_replace("and ","&",$where);
$passWhere=str_replace("'","",$passWhere);
$passWhere=str_replace("`","",$passWhere);
$passWhere=str_replace(" ","",$passWhere);
//echo "<br />$where <br />$passWhere";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql");
$num=mysqli_num_rows($result);
if($num<1 AND $search!=""){echo "No vehicle found using $where";}

while($row=mysqli_fetch_assoc($result)){
	$ARRAY[]=$row;
	$vehicle_id_array=$row['vehicle_id'];
	}
//echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;

// Get mileage driven
if(empty($center_code)){$center_code=$ARRAY[0]['center_code'];}
 $sql= "SELECT vehicle_id,sum(items.mileage) as tot_mileage
 FROM items
 LEFT JOIN vehicle on vehicle.id=items.vehicle
 where vehicle.center_code='$center_code'
 group by vehicle_id
 "; 
//echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql");
while($row=mysqli_fetch_assoc($result)){
	$mileage_array[$row['vehicle_id']]=$row['tot_mileage'];
	}
//echo "mileage<pre>"; print_r($mileage_array); echo "</pre>"; // exit;

date_default_timezone_set('America/New_York');

$two_month_early=time() - (61 * 24 *60 * 60); // days hours min sec
$today=time();
$date2=date('Y-m-d',$two_month_early);

if(@$ARRAY)
	{
	
	if(@$_POST['surplus']=="")
		{$skip1=array("surplus","Lname","fs20");}
	//	{$skip1=array("id","surplus");}
		else
		{$skip1=array("id","Lname");}
	
	if($level>3)
		{
		$export_all="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=inventory_excel.php?search=Find&rep=x>All Parks</a>";
		}
		else
		{$export_all="";}
	
		if(!empty($center_code))
			{$pc=$center_code;}
			else
			{$pc=$park_code;}
		echo "<div align='center'><table border='1' cellpadding='5'>";
			echo "<tr><th colspan='10'>On-Road Inventory - $num vehicles</th><th colspan='4'>Excel export <a href=inventory_excel.php?center_code=$pc&search=Find&rep=x>Park</a> $export_all</th></tr>";
	
		if($level==1){echo "<tr><th colspan='11'>$park_code</th></tr>";}
		
		echo "<tr>";
		foreach($ARRAY[0] as $k=>$v){
			if(in_array($k,$skip1)){continue;}
			if(empty($order)){$passorder="&order=desc";}else{$passorder="";}
			$k="<a href='menu.php?search=Find&form_type=inventory&sort=$k$passWhere$passorder'>$k</a>";
		
			
			echo "<th>$k</th>";
			@$header.="<th>$k</th>";
		}
		echo "</tr>";
		
		foreach($ARRAY as $num=>$array)
		{
		if(fmod($num,20)==0 AND $num>0){echo "<tr bgcolor='aliceblue'>$header</tr>";}
			echo "<tr>";
			foreach($array as $k=>$v){
				if(in_array($k,$skip1)){continue;}
				$input=$v;
				if($k=="mileage")
					{
					// $v is starting mileage - array is cumulative miles driven
					@$input=number_format($v+$mileage_array[$array['vehicle_id']],0);
					}
				
				if(in_array($k,$radio))
					{
					$var=${"radio_".$k};
					@$input=$var[$v];
					}
				
				if($k=="assigned_to" AND $v!="")
					{
					$input=$v."-".$ARRAY[$num]['Lname'];
					}
					
				if($k=="license" AND $ARRAY[$num]['recall']=="Y")
					{
					$input=$v." <font color='red'>Recall issued.</font>";
					}
				if($k=="vin")
					{
					$id=$ARRAY[$num]['id'];
					$input="<a href=\"edit.php?table=vehicle&id=$id\" target='_blank'>$input</a>";
					}
					
				if($k=="user")
					{
					if(strpos($array['user'],"bill_")>-1)
						{
						$bos="BOS_".$array['vin'].".pdf";
						$input="<a href='/fuel/bill_of_sale/$bos'>Bill of Sale</a>";
						}
						else
						{
						$input=$v;
						}
					}

				if($k=="inspected")
					{
					$date1=strtotime("$v 00:00:00"); // last inspection
					$diff=round(abs($date1-$today)/60/60/24);
					if($diff>330)
						{
						$input=$v." <font color='red'>Inspection is needed.</font>";
						}
						else
						{$input=$v;}
					}
					
				echo "<td align='center'>$input</td>";
				}
			echo "</tr>";
		}
		
		
		echo "</table></div>";
	}

echo "</html>";


?>