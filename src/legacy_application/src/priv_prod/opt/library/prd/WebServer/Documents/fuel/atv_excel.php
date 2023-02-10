<?php

extract($_REQUEST);
//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;


include("../../include/connectROOT.inc");// database connection parameters
$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
       
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;

//**** PROCESS  a Search ******
if($search=="Find")
	{
	//echo "<pre>"; print_r($_POST); echo "</pre>";  //exit;
	//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;
		$skip=array("search","PHPSESSID","sort","form_type","rep");
		$like=array("park_id","make","model_year","engine","vin");
		foreach($_REQUEST as $k=>$v){
			if(in_array($k,$skip)){continue;}
			if($v==""){continue;}
				if(in_array($k,$like)){
					$where.=" and (`".$k."` like '%".$v."%')";
					}
				else
				{$where.=" and `".$k."`='".$v."'";}		
			}
		//	include_once("menu.php");
	}


$dbTable="atv";
//if($form_type=="atv"){$dbTable="atv";}

// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
while ($row=mysqli_fetch_assoc($result))
	{$fieldArray[]=$row['Field'];}

//echo "<pre>"; print_r($fieldArray); echo "</pre>";  exit;

$skip=array("id","atv_id","comment");
$subName=array("serial_number"=>"Serial Number","center_code"=>"Park Unit","mileage"=>"Starting Mileage/Hours","make"=>"Make/Model<br /><font size='-1'>(Include as much detail as possible (Example: John Deere Gator CX 4x2))</font>","model_year"=>"Model Year<br /><font size='-1'>Please correctly identify, as it is an important variable.</font>","engine"=>"Engine Size/Class<br /><font size='-1'>Example: 249cc Kawasaki 8HP</font>","duty"=>"Duty","trans"=>"Transmission","drive"=>"Drive","fuel"=>"Fuel Type","emergency"=>"Emergency ATV?",);

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


	if($rep=="x")
		{
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename=current_atv_inventory.xls');
		}

$orderBy="order by id desc";
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


if(!$_POST AND $_SERVER['argv'][0]=="form_type=atv"){EXIT;}

include("../../include/connectROOT.inc");// database connection parameters
$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
       
 $sql= "SELECT * from $dbTable
 where comment=''
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
if($num<1){echo "No ATV found using $where";}

while($row=mysqli_fetch_assoc($result)){
	$ARRAY[]=$row;
	}
//echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;

if($ARRAY){

$skip1=array("id","comment");

	echo "<table border='1'>";

	if($level==1){echo "<tr><th colspan='11'>$park_code</th></tr>";}
	
	echo "<tr>";
	foreach($ARRAY[0] as $k=>$v)
	{
		if(in_array($k,$skip1)){continue;}
		echo "<th>$k</th>";
	}
	echo "</tr>";
	
	foreach($ARRAY as $num=>$array){
		echo "<tr>";
		foreach($array as $k=>$v){
			if(in_array($k,$skip1)){continue;}
			echo "<td align='center'>$v</td>";
			}
		echo "</tr>";
	}
	
	
	echo "</table></div>";
}

echo "</html>";


?>