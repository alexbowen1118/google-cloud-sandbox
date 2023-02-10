<?php
session_start();
$level=$_SESSION['fuel']['level'];

//echo "<pre>"; print_r($_SERVER); echo "</pre>"; // exit;



$database="fuel";
include("../../include/iConnect.inc");// database connection parameters
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
 
// echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
include("../../include/get_parkcodes_reg.php");// database connection parameters      
//echo "<pre>"; print_r($_SESSION); echo "</pre>";  //exit;

//**** PROCESS  a Search ******
if($search=="Find")
	{
	$where="";
	//echo "<pre>"; print_r($_POST); echo "</pre>";  //exit;
// 	echo "<pre>"; print_r($_REQUEST); echo "</pre>";  exit;
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
					{
					$where.=" and `".$k."`='".$v."'";
					}		
			}
	}

// echo "w=$where";

$dbTable="vehicle";
mysqli_select_db($connection,"fuel");
//if($form_type=="inventory"){$dbTable="vehicle";}

// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql");
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
// 	echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
	$distCode=$_SESSION['fuel']['selectR'];
	$menuList="array".$distCode; 
	$parkArray=${$menuList};
		$parkArray[]="All-$distCode";
// 		echo "<pre>"; print_r($parkArray); echo "</pre>"; // exit;
	$check_park=strtoupper($_REQUEST['center_code']);
// 	echo "$where <br /> cp=$check_park<br />";
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
// 		echo "98";
		if($check_park=="" OR !in_array($check_park,$parkArray))
			{@$where.=" and center_code='$park_code'";}
			else
			{$var_park="center_code='$check_park'";}
		}
// 	echo "2 t=$t cp=$check_park<pre>"; print_r($var_park); echo "</pre>";  exit;
	$where=" and (".$var_park.")";
	}
// echo "$where"; exit;
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

// include("../../include/connectROOT.inc");// database connection parameters
$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

// Get mileage driven
if(empty($center_code))
	{
	$var_center_code="!='SURP'";
	$where=" and center_code!='SURP'";
	}
	else
	{
	$var_center_code="='$center_code'";
	}
 $sql= "SELECT vehicle_id, (sum(t1.mileage) + t2.mileage) as tot_mileage
 FROM items as t1
 LEFT JOIN vehicle as t2 on t2.id=t1.vehicle
 where t2.center_code $var_center_code
 group by vehicle_id
 "; 
//   echo "$sql"; exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
while($row=mysqli_fetch_assoc($result))
	{
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

// echo " $sql ";  exit;
$passWhere=str_replace("and ","&",$where);
$passWhere=str_replace("'","",$passWhere);
$passWhere=str_replace("`","",$passWhere);
$passWhere=str_replace(" ","",$passWhere);
//echo "<br />$where <br />$passWhere";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql ");
$num=mysqli_num_rows($result);
if($num<1){echo "No vehicle found using $where";}

while($row=mysqli_fetch_assoc($result)){
	$ARRAY[]=$row;
	}
// echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;

if(!empty($ARRAY))
	{
	foreach($ARRAY as $num=>$array)
		{
		foreach($array as $k=>$v)
			{
		//	if(in_array($k,$skip1)){continue;}
			$new_array[$num][$k]=$v;
			if($k=="mileage")
				{
				$cc=$array['center_code'];
				$ym=$cc."_mileage";
				$input=@number_format($mileage_array[$array['vehicle_id']],0);
				$new_array[$num][$ym]=$input;
// 				$input=@number_format($total_mileage_array[$array['vehicle_id']],0);
// 				$new_array[$num]['total_mileage']=$input;
// 				
				unset($new_array[$num]['mileage']);
				}
			}
		}
	$ARRAY_1=$new_array;
// 	echo "<pre>"; print_r($ARRAY_1); echo "</pre>";  exit;

	if($rep=="x")
		{
		if(empty($center_code)){$center_code="All";}
		$title=$center_code."_inventory";
		$header_array[]=array_keys($ARRAY_1[0]);

		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=$title.csv");
		// Disable caching
		header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
		header("Pragma: no-cache"); // HTTP 1.0
		header("Expires: 0"); // Proxies

	
		function outputCSV($header_array, $data)
			{
				$output = fopen("php://output", "w");
				foreach ($header_array as $row) {
					fputcsv($output, $row); // here you can change delimiter/enclosure
				}
				foreach ($data as $row) {
					fputcsv($output, $row); // here you can change delimiter/enclosure
				}
				fclose($output);
			}

		outputCSV($header_array, $ARRAY_1);

		exit;
		}
		
	}


echo "</html>";


?>