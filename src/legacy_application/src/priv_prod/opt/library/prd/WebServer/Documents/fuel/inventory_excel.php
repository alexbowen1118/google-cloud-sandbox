<?php
session_start();
$level=$_SESSION['fuel']['level'];

extract($_REQUEST);
//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
//echo "<pre>"; print_r($_SERVER); echo "</pre>"; // exit;


include("../../include/connectROOT.inc");// database connection parameters
include("../../include/get_parkcodes.php");// database connection parameters

$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
       
//echo "<pre>"; print_r($_SESSION); echo "</pre>";  //exit;

//**** PROCESS  a Search ******
if($search=="Find")
	{
	//echo "<pre>"; print_r($_POST); echo "</pre>";  //exit;
	//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;
		$skip=array("search","PHPSESSID","sort","form_type","surplus","rep");
		$like=array("vehicle_id","park_id","make","model_year","engine","vin","comment");
		foreach($_REQUEST as $k=>$v)
			{
			if(in_array($k,$skip)){continue;}
			if($v==""){continue;}
				if(in_array($k,$like))
					{
					$where.=" and (`".$k."` like '%".$v."%')";
					}
				else
				{$where.=" and `".$k."`='".$v."'";}		
			}
	}


$dbTable="vehicle";
//if($form_type=="inventory"){$dbTable="vehicle";}

// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
while ($row=mysqli_fetch_assoc($result))
	{$fieldArray[]=$row['Field'];}

//echo "<pre>"; print_r($fieldArray); echo "</pre>";  exit;

$skip=array("id","vehicle_id");

$subName=array("center_code"=>"Current Park","previous"=>"Previous Park","vin"=>"VIN number <font size='-1'>(Vehicle Identification Number)</font>","FAS_num"=>"FAS_num","make"=>"Make","engine"=>"Engine Size/Class<br /><font size='-1'>Identify no. of cylinders (V6, V8, ...) <b>AND</b> engine size (4.0L, 5.8L, ...)</font>","duty"=>"Duty","trans"=>"Transmission","drive"=>"Drive","fuel"=>"Fuel Type","emergency"=>"Emergency Vehicle?","comment"=>"Comment","status"=>"Status","model"=>"Model<br /><font size='-1'>(Include as much detail as possible (Example: F250 Superduty))</font>","cost"=>"Initial Cost","year"=>"Year","license"=>"License Plate","title"=>"Title","body"=>"Body Style","cab"=>"Cab Type","purpose"=>"Primary Purpose","mileage"=>"Initial Mileage","park_id"=>"Park ID","vehicle_id"=>"License");


// if modified, also make changes to edit.php
$radio=array("duty","trans","drive","fuel","emergency");
$radio_duty=array("l"=>"Light Duty","h"=>"Heavy Duty");
$radio_trans=array("m"=>"Manual","a"=>"Automatic");
$radio_drive=array("2"=>"2WD","4"=>"4WD","A"=>"AWD");
//$radio_fuel=array("u"=>"Unleaded","f"=>"Flex","d"=>"Diesel");
$radio_fuel=array("u"=>"Unleaded","f"=>"Flex","d"=>"Diesel","e"=>"Electric");
$radio_emergency=array("y"=>"Yes","n"=>"No");




// Display
// if($level==1){@$where.=" and center_code='$park_code'";}

if($level==2)
	{
	$distCode=$_SESSION['fuel']['select'];
	$menuList="array".$distCode; 
	$parkArray=${$menuList};
		$parkArray[]="All-$distCode";
//		echo "<pre>"; print_r($parkArray); echo "</pre>"; // exit;
	$check_park=strtoupper($_REQUEST['center_code']);
	$t=strpos($check_park,"ALL-");
	if(strpos($check_park,"ALL-")>-1)
		{
		foreach($parkArray as $k=>$v)
			{
			if(strpos($v,"All-")>-1){continue;}
			@$var_park.="center_code='".$v."' OR ";
			}
		$var_park=rtrim($var_park," OR ");
		$sort="cc";
		}
		else
		{
		if($check_park=="" OR !in_array($check_park,$parkArray))
			{@$where.=" and center_code='$park_code'";}	
		}
//	echo "2 $t $check_park<pre>"; print_r($var_park); echo "</pre>";  exit;
	$where=" and (".$var_park.")";
	}

if($level>2 AND $search=="" AND $sort=="")
	{
	//$limit="limit 100";
	}

$orderBy="order by id desc";
if(!isset($sort)){$sort="";}
if($sort=="cc"){$orderBy="order by center_code";}
if($sort=="p"){$orderBy="order by park_id";}
if($sort=="m"){$orderBy="order by make";}
if($sort=="mi"){$orderBy="order by mileage desc";}
if($sort=="my"){$orderBy="order by model_year";}
if($sort=="d"){$orderBy="order by duty";}
if($sort=="t"){$orderBy="order by trans";}
if($sort=="dr"){$orderBy="order by drive";}
if($sort=="f"){$orderBy="order by fuel";}
if($sort=="e"){$orderBy="order by emergency";}

if(!$_POST AND @$_SERVER['argv'][0]=="form_type=inventory"){EXIT;}

include("../../include/connectROOT.inc");// database connection parameters
$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

// Get mileage driven
 $sql= "SELECT vehicle_id,sum(items.mileage) as tot_mileage
 FROM items
 LEFT JOIN vehicle on vehicle.id=items.vehicle
 where vehicle.center_code='$center_code'
 group by vehicle_id
 "; 
//echo "$sql"; exit;
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql");
while($row=mysqli_fetch_assoc($result)){
	$mileage_array[$row['vehicle_id']]=$row['tot_mileage'];
	}
	
if(!isset($limit)){$limit="";}	

// used to reorder fields for display - purpose and used_for now come after user
$t1_fields="t1.`id`, t1.`inspected`, t1.`center_code`, t1.`previous`, t1.`assigned_to`, t1.`user`, t1.`purpose`, t1.`used_for`, t1.`fs20`, t1.`vehicle_id`, t1.`vin`, t1.`license`, t1.`park_id`, t1.`wex_pin`, t1.`FAS_num`, t1.`status`, t1.`cost`, t1.`mileage`, t1.`fuel`, t1.`engine`, t1.`trans`, t1.`drive`, t1.`year`, t1.`make`, t1.`model`, t1.`body`, t1.`cab`, t1.`title`, t1.`GVWR`, t1.`cdl`, t1.`dot_key`, t1.`comment`, t1.`sold_yyyy_mm`";

 $sql= "SELECT $t1_fields from $dbTable as t1
 where 1
 $where
 $orderBy
 $limit
 "; 

//echo " $sql s=$ts";
$passWhere=str_replace("and ","&",$where);
$passWhere=str_replace("'","",$passWhere);
$passWhere=str_replace("`","",$passWhere);
$passWhere=str_replace(" ","",$passWhere);
//echo "<br />$where <br />$passWhere";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql ".mysqli_error($connection));
$num=mysqli_num_rows($result);
if($num<1){echo "No vehicle found using $where<br />$sql";}

while($row=mysqli_fetch_assoc($result)){
	$ARRAY[]=$row;
	}
//echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;

if($ARRAY)
	{
/*	
	if($_POST['surplus']=="")
		{$skip1=array("id","surplus");}
		else
		{$skip1=array("id");}
*/
$skip1=array("id");

	if($rep=="x")
		{
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename=current_vehicle_inventory.xls');
		}
		echo "<table border='1' cellpadding='5'>";
			
		if($level==1){echo "<tr><th colspan='11'>$park_code</th></tr>";}
		
		echo "<tr>";
		foreach($ARRAY[0] as $k=>$v)
			{
			if(in_array($k,$skip1)){continue;}
			if($k=="mileage"){$k="cumulative mileage";}
			echo "<th>$k</th>";
			}
		echo "</tr>";
		
		foreach($ARRAY as $num=>$array){
			echo "<tr>";
			foreach($array as $k=>$v){
				if(in_array($k,$skip1)){continue;}
				$input=$v;
					if($k=="mileage")
						{
						// $v is starting mileage - array is cumulative miles driven
						$input=number_format($v+$mileage_array[$array['vehicle_id']],0);
						}
						
				if(in_array($k,$radio)){
					$var=${"radio_".$k};
					$input=$var[$v];
					}
				
				echo "<td align='center'>$input</td>";
				}
			echo "</tr>";
		}
		
		
		echo "</table>";
	}

echo "</html>";


?>