<?php
session_start();
//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
// echo "<pre>"; print_r($_SESSION); echo "</pre>";  exit;

$level=$_SESSION['fuel']['level'];
$database="fuel";
include("../../include/iConnect.inc");// database connection parameters

  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
       
//echo "<pre>"; print_r($_SESSION); echo "</pre>";  //exit;

$dbTable="vehicle";
//if($form_type=="inventory"){$dbTable="vehicle";}

// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2.");
while ($row=mysqli_fetch_assoc($result))
	{$fieldArray[]=$row['Field'];}

//echo "<pre>"; print_r($fieldArray); echo "</pre>";  exit;

if(@$_POST['surplus']=="")
	{$skip=array("id","vehicle_id","surplus");}
	else
	{$skip=array("id","vehicle_id");}

$subName=array("park_id"=>"License Plate Number","vehicle_id"=>"DPR Vehicle ID Number","vin"=>"VIN number <font size='-1'>(Vehicle Identification Number)</font>","center_code"=>"Park Unit","mileage"=>"Starting Mileage","make"=>"Make/Model<br /><font size='-1'>(Include as much detail as possible (Example: Ford F250 Superduty))</font>","model_year"=>"Model Year<br /><font size='-1'>Please correctly identify, as it is an important variable.</font>","engine"=>"Engine Size/Class<br /><font size='-1'>Identify no. of cylinders (V6, V8, ...) <b>AND</b> engine size (4.0L, 5.8L, ...)</font>","duty"=>"Duty","trans"=>"Transmission","drive"=>"Drive","fuel"=>"Fuel Type","emergency"=>"Emergency Vehicle?","comment"=>"Comment","surplus"=>"Surplused");

// if modified, also make changes to edit.php
$radio=array("duty","trans","drive","fuel","emergency");
$radio_duty=array("l"=>"Light Duty","h"=>"Heavy Duty");
$radio_trans=array("m"=>"Manual","a"=>"Automatic");
$radio_drive=array("2"=>"2WD","4"=>"4WD","A"=>"AWD");
//$radio_fuel=array("u"=>"Unleaded","f"=>"Flex","d"=>"Diesel");
$radio_fuel=array("u"=>"Unleaded","f"=>"Flex","d"=>"Diesel","e"=>"Electric");
$radio_emergency=array("y"=>"Yes","n"=>"No");

if(!isset($level)){$level="";}
if($level==1){
		$parkList=explode(",",$_SESSION['fuel']['accessPark']);
		if($parkList[0]==""){$park_code=$_SESSION['fuel']['select'];}		
}


// Display
if($level==1){$where.=" and center_code='$park_code'";}

if($level==2)
	{
	$check_park=strtoupper($_POST['center_code']);
	if($check_park=="" OR !in_array($check_park,$parkArray))
		{$where.=" and center_code='$park_code'";}	
	}

if($level>2 AND $search=="" AND $sort=="")
	{
	//$limit="limit 100";
	}

$orderBy="order by center_code,vehicle_id";
$orderBy="order by vehicle_id";

if(!$_POST AND @$_SERVER['argv'][0]=="form_type=inventory"){EXIT;}

// include("../../include/iConnect.inc");// database connection parameters
$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

$where0="1";
//$where0="surplus=''";
//if($_POST['surplus']=="x"){$where0="surplus !=''";}

if(!empty($center_code))
	{
	$where0.=" and center_code='$center_code'";
	}
if(!isset($where)){$where="";}
if(!isset($limit)){$limit="";}
 $sql= "SELECT * from $dbTable
 where $where0
 $where
 $orderBy
 $limit
 "; 

//  echo " $sql<br /><br />"; exit;
$passWhere=str_replace("and ","&",$where);
$passWhere=str_replace("'","",$passWhere);
$passWhere=str_replace("`","",$passWhere);
$passWhere=str_replace(" ","",$passWhere);
//echo "<br />$where <br />$passWhere";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query.");
$num=mysqli_num_rows($result);
if($num<1){echo "No vehicle found using $where";}

while($row=mysqli_fetch_assoc($result)){
	$ARRAY[]=$row;
	$vehicle_id_array[]=$row['vehicle_id'];
	}
//echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;
//echo "<pre>"; print_r($vehicle_id_array); echo "</pre>";  exit;

// Get total mileage driven
 $sql= "SELECT t2.license, t2.vehicle_id, (sum(t1.mileage) + t2.mileage) as tot_mileage
 FROM items as t1
 left join `vehicle` as t2 on t1.vehicle=t2.id
 where 1
 group by vehicle
 order by vehicle
 "; 
// echo "$sql"; exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query.");
while($row=mysqli_fetch_assoc($result))
	{
	$total_mileage_array[$row['vehicle_id']]=$row['tot_mileage'];
	}
// echo "<pre>"; print_r($total_mileage_array); echo "</pre>";  exit;

// Get mileage driven for year
 $sql= "SELECT vehicle_id,sum(t1.mileage) as tot_mileage
 FROM items as t1
 LEFT JOIN vehicle on vehicle.id=t1.vehicle
 where t1.year='$pass_year'
 group by vehicle_id
 order by vehicle_id
 "; 
//echo "$sql"; exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query.");
while($row=mysqli_fetch_assoc($result))
	{
	$mileage_array[$row['vehicle_id']]=$row['tot_mileage'];
	$vehicle_id_array_2[]=$row['vehicle_id'];
	}
//print_r($mileage_array);
//echo "<pre>"; print_r($vehicle_id_array_2); echo "</pre>";  exit;
//$dif=array_diff($vehicle_id_array,$vehicle_id_array_2);
//echo "<pre>"; print_r($dif); echo "</pre>";  exit;

if(@$_POST['surplus']=="")
		{$skip1=array("id","surplus","previous");}
		else
		{$skip1=array("id","previous");}
	
if(!empty($ARRAY))
	{
	foreach($ARRAY as $num=>$array)
		{
		foreach($array as $k=>$v)
			{
			if(in_array($k,$skip1)){continue;}
			$new_array[$num][$k]=$v;
			if($k=="mileage")
				{
				$ym=$pass_year."_mileage";
				$input=@number_format($mileage_array[$array['vehicle_id']],0);
				$new_array[$num][$ym]=$input;
				$input=@number_format($total_mileage_array[$array['vehicle_id']],0);
				$new_array[$num]['total_mileage']=$input;
				
				unset($new_array[$num]['mileage']);
				}
			}
		}
	$ARRAY_1=$new_array;
// 	echo "<pre>"; print_r($ARRAY_1); echo "</pre>";  exit;

// 	if($rep=="x")
// 		{
		$title=$pass_year."_mileage";
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
// 		}
		
	}

?>