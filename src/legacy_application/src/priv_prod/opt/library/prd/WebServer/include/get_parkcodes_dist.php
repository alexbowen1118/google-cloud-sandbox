<?php
//ini_set('display_errors',1);
// $database="dpr_system";
// $db=$database;
if(!isset($connection))
	{
	$db="dpr_system";
	include("/opt/library/prd/WebServer/include/iConnect.inc");
	}
mysqli_select_db($connection,'dpr_system'); // database

if(!isset($_SESSION))
	{
	session_start();
	}


$new_array_eadi_north=array("DISW","MEMI","PETT","JORI");
$new_array_eadi_south=array("CLNE","EADO","FOMA","GOCR","HABE");

$mountain_region=array("CRMO","GORG","CHRO","GRMO","HARO","PIMO","LAJA","MOMI","LANO","MOMO","NERI","ELKN","SOMO","STMO","MORE","PIVI","FOFL");
$piedmont_region=array("CACR","RARO","WEWO","ENRI","FALA","HARI","MARI","JORD","KELA","MEMO","WIUM","PIRE");
$coastal_region=array("CABE","FOFI","FOMA","GOCR","PETT","HABE","JONE","LAWA","SILA","JORI","LURI","MEMI","DISW","CORE");

$adm_region=array("ARCH","DPR_NRC", "NRC", "NRC/U", "WAHO", "WAHO/U", "YORK", "TRAILS");
$adm_district=array("ARCH","DPR_NRC", "NRC", "NRC/U", "WAHO", "WAHO/U", "YORK", "TRAILS");
// check attendance form_day_i.php for district/region

//EXIT;

$sql_parkcodes = "SELECT t1.parkcode, t1.county, t2.park_name, t2.district, t2.region, t1.ophone, t2.status
From dprunit_district as t1
LEFT JOIN parkcode_names_district as t2 on t1.parkcode=t2.park_code
WHERE 1 
ORDER by t1.parkcode";
//   echo "$sql_parkcodes"; exit;
$result_parkcodes = mysqli_query($connection,$sql_parkcodes) or die ("Couldn't execute query. $sql_parkcodes");

//$skip=array("ARCH","NCBL","YORK","EADI","NODI","SODI","WEDI","WARE");
$skip=array("ARCH","NCBL","YORK","CORE","PIRE","MORE","WARE");
// $region=array();
$district=array();
while ($row_parkcodes=mysqli_fetch_assoc($result_parkcodes))
	{
// 	print_r($row_parkcodes);
	if(!in_array($row_parkcodes['parkcode'],$skip))
		{
		$parkCode[]=$row_parkcodes['parkcode'];
		}
// echo "<pre>"; print_r($parkCode); echo "</pre>"; // exit;

// Staffed Parks
	if($row_parkcodes['ophone']!="")
		{
		$staffed_parks[$row_parkcodes['parkcode']]=$row_parkcodes['region'];
		}
// Park - District and new Region
	if($row_parkcodes['district']!="")
		{
		$district[$row_parkcodes['parkcode']]=$row_parkcodes['district']; // where $district not changed to $region
// 		$region[$row_parkcodes['parkcode']]=$row_parkcodes['region'];
		}
// 	$region['CORO']="CORE";
// 	$region['PIRO']="PIRE";
// 	$region['MORO']="MORE";
	$district['EADO']="EADI";
	$district['NODO']="NODI";
	$district['SODO']="SODI";
	$district['WEDO']="WEDI";
		
// Park Code - Park Name
	if($row_parkcodes['park_name']!="")
		{
		$parkCodeName[$row_parkcodes['parkcode']]=$row_parkcodes['park_name'];
		}
// Park Code - Park County
	if($row_parkcodes['county']!="")
		{
		$parkCounty[$row_parkcodes['parkcode']]=$row_parkcodes['county'];
		}

// District	arrays	
// Region	arrays	
	if($row_parkcodes['district']=="EADI")
// 	if($row_parkcodes['region']=="CORE")
		{
		$all_parks[]=$row_parkcodes['parkcode'];
		$arrayEADI[]=$row_parkcodes['parkcode'];
// 		$arrayCORE[]=$row_parkcodes['parkcode'];
		// if(in_array($row_parkcodes['parkcode'],$new_array_eadi_north))
// 			{
// 			$dist_hr_array[$row_parkcodes['parkcode']]=60032955; // Latasha Peele position
// 			}
// 		if(in_array($row_parkcodes['parkcode'],$new_array_eadi_south))
// 			{
// 			$dist_hr_array[$row_parkcodes['parkcode']]=60032785; // Teresa McCall position
// 			}
		}
	if($row_parkcodes['district']=="NODI")
		{
		$all_parks[]=$row_parkcodes['parkcode'];
		$arrayNODI[]=$row_parkcodes['parkcode'];
	//	$dist_hr_array[$row_parkcodes['parkcode']]=60032955; // Latasha Peele position
		}
	if($row_parkcodes['district']=="SODI")
		{
		$all_parks[]=$row_parkcodes['parkcode'];
		$arraySODI[]=$row_parkcodes['parkcode'];
// 		$dist_hr_array[$row_parkcodes['parkcode']]=60032785; // Teresa McCall position
		}
	if($row_parkcodes['district']=="WEDI")
		{
		$all_parks[]=$row_parkcodes['parkcode'];
		$arrayWEDI[]=$row_parkcodes['parkcode'];
//		$dist_hr_array[$row_parkcodes['parkcode']]=60032783; // Sheila Green position
		}
	}

		// $dist_hr_array['YORK']=60032785; // Teresa McCall position
// 		$dist_hr_array['ARCH']=60032783; // Sheila Green position
		
		if(!in_array("EADI",$arrayEADI)){$arrayEADI[]="EADI";}
		if(!in_array("NODI",$arrayNODI)){$arrayNODI[]="NODI";}
		if(!in_array("SODI",$arraySODI)){$arraySODI[]="SODI";}
		if(!in_array("WEDI",$arrayWEDI)){$arrayWEDI[]="WEDI";}

// 	echo "<pre>"; print_r($arrayNODI); echo "</pre>"; // exit;	
// 	echo "<pre>"; print_r($arraySODI); echo "</pre>"; // exit;	
// 	echo "<pre>"; print_r($arrayWEDI); echo "</pre>"; // exit;	
// 		if(!in_array("CORE",$arrayCORE)){$arrayCORE[]="CORE";}
// 		if(!in_array("PIRE",$arrayPIRE)){$arrayPIRE[]="PIRE";}
// 		if(!in_array("MORE",$arrayMORE)){$arrayMORE[]="MORE";}
// 	echo "<pre>"; print_r($district); echo "</pre>";  exit;		
?>