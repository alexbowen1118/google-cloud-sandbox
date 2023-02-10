<?php

extract($_REQUEST);
//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
//echo "<pre>"; print_r($_SERVER); echo "</pre>"; // exit;


include("../../include/connectROOT.inc");// database connection parameters
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
		foreach($_REQUEST as $k=>$v){
			if(in_array($k,$skip)){continue;}
			if($v==""){continue;}
				if(in_array($k,$like)){
					$where.=" and (`".$k."` like '%".$v."%')";
					}
				else
				{$where.=" and `".$k."`='".$v."'";}		
			}
		include_once("menu.php");
	}
//echo "$where";


$dbTable="vehicle";
//if($form_type=="inventory"){$dbTable="vehicle";}

// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
while ($row=mysqli_fetch_assoc($result))
	{$fieldArray[]=$row['Field'];}

//echo "<pre>"; print_r($fieldArray); echo "</pre>";  exit;

if($_POST['surplus']=="")
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

if(!$_POST AND $_SERVER['argv'][0]=="form_type=inventory"){EXIT;}

include("../../include/connectROOT.inc");// database connection parameters
$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

$where0="surplus=''";
if($_POST['surplus']=="x")
	{$where0="surplus !=''";}

 $sql= "SELECT * from $dbTable
 where $where0
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
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql");
$num=mysqli_num_rows($result);
if($num<1){echo "No vehicle found using $where";}

while($row=mysqli_fetch_assoc($result)){
	$ARRAY[]=$row;
	$vehicle_id_array=$row['vehicle_id'];
	}
//echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;

// Get mileage driven
 $sql= "SELECT vehicle_id,sum(items.mileage) as tot_mileage
 FROM items
 LEFT JOIN vehicle on vehicle.id=items.vehicle
 where year='$pass_year'
 group by vehicle_id
 order by vehicle.center_code
 "; 
//echo "$sql"; exit;
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql");
while($row=mysqli_fetch_assoc($result)){
	$mileage_array[$row['vehicle_id']]=$row['tot_mileage'];
	}
//print_r($mileage_array);


if($ARRAY)
	{
	
	if($_POST['surplus']=="")
		{$skip1=array("id","surplus");}
		else
		{$skip1=array("id");}
	
	if($level>3){$export_all="<a href=inventory_year_excel.php?pass_year=$pass_year&search=Find&rep=x>All Parks</a>";}
	
		echo "<div align='center'><table border='1' cellpadding='5'>";
			echo "<tr><th colspan='10'>On-Road Inventory for $pass_year - $num vehicles</th><th colspan='4'>$export_all</th></tr>";
	//Excel export <a href=inventory_excel.php?center_code=$center_code&search=Find&rep=x>Park</a> 
	
		if($level==1){echo "<tr><th colspan='11'>$park_code</th></tr>";}
		
		echo "<tr>";
		foreach($ARRAY[0] as $k=>$v){
			if(in_array($k,$skip1)){continue;}
			if($k=="mileage"){$k=$pass_year." mileage";}
			echo "<th>$k</th>";
		}
		echo "</tr>";
		
		foreach($ARRAY as $num=>$array){
			echo "<tr>";
			foreach($array as $k=>$v){
				if(in_array($k,$skip1)){continue;}
				$input=$v;
				if($k=="mileage")
					{$input=number_format($mileage_array[$array['vehicle_id']],0);
					}
				
				if(in_array($k,$radio)){
					$var=${"radio_".$k};
					$input=$var[$v];
					}
					
				if($k=="vehicle_id"){
					$input="<a href='' onclick=\"return popitup('edit.php?vi=$input')\">$input</a>";
					}
					
				echo "<td align='center'>$input</td>";
				}
			echo "</tr>";
		}
		
		
		echo "</table></div>";
	}

echo "</html>";


?>