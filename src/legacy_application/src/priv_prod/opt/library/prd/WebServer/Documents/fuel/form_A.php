<?php
extract($_REQUEST);
if(@$center_code){$park_code=$center_code;}

// echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
//   echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;

include("../../include/iConnect.inc");// database connection parameters
$database="fuel";
mysqli_select_db($connection,$database)
or die ("Couldn't select database");

// Get list of vehicles by park

if($level==1)
	{
		$parkList=explode(",",$_SESSION['fuel']['accessPark']);
			// if($_SESSION['fuel']['beacon_num']=="60032780") //Interpretation & Education Manager  
// 				{$_SESSION['fuel']['select']="INED";}
// 			if($_SESSION['fuel']['beacon_num']=="60091483") // Inventory Biologist
// 				{$_SESSION['fuel']['select']="REMA";}
// 			if($_SESSION['fuel']['beacon_num']=="65020685") // Environmental Specialist - J. Short
// 				{$_SESSION['fuel']['select']="REMA";}
// 			if($_SESSION['fuel']['beacon_num']=="60032832") // Environmental Specialist - T. Crate
// 				{$_SESSION['fuel']['select']="REMA";}
// 			if($_SESSION['fuel']['beacon_num']=="60032828") // Environmental Senior Spec - J. Blanchard
// 				{$_SESSION['fuel']['select']="REMA";}
				
		if($parkList[0]==""){$park_code=$_SESSION['fuel']['select'];}
		
		if(empty($park_code)){$park_code=$_SESSION['fuel']['select'];}
		
	}

if($parkList[0]==""){$parkList[0]=$_SESSION['fuel']['select'];}
			
// if(!isset($park_code)){$park_code="";}

if(!isset($park_code))
	{
	$park_code=$_SESSION['fuel']['select'];
	}

if($_SESSION['fuel']['select']=="PIRE"){$park_code="PIRE";}
$where="and center_code='$park_code'";

$order=" order by park_id,make,license";
if($park_code=="JORD")
	{
	$order=" order by sort";
	}
$sql= "SELECT id,center_code,vehicle_id, park_id, license,make, concat(`year`,'-',`model`) as model_year, right(license,4) as sort
from vehicle
where center_code!='SURP'
$where
$order
";
// echo " $sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query.");
	$vehicles[0]="Select a Vehicle";
	while($row=mysqli_fetch_assoc($result))
		{
		if($row['park_id']!=""){$pi=$row['park_id']."_";}else{$pi="";}
		$vehicles[$row['id']]=$pi.$row['license']."_".$row['make']."_".$row['model_year']."_".$row['vehicle_id'];
		}
//echo "<pre>"; print_r($vehicles); echo "</pre>"; // exit;

// FIELD NAMES are stored in $fieldArray
$sql = "SHOW COLUMNS FROM items";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2.");
while ($row=mysqli_fetch_assoc($result)){$fieldArray[]=$row['Field'];}

//echo "<pre>"; print_r($fieldArray); echo "</pre>";  //exit;

foreach(range(1,12) as $v){
		$months[]=date("M",mktime(0,0,0,$v,1,2000));
	}

echo "<div align='center'><form name='overview' action='menu.php?form_type=form_A' method='POST'>";
echo "<table>";

echo "<tr>";

// For Level 1 with privileges
if(@$parkList[0]!="")
	{
		if($park_code AND in_array($park_code,$parkList)){
			$_SESSION['fuel']['select']=$park_code;
			}
		echo "<td><select name=\"center\" onChange=\"MM_jumpMenu('parent',this,0)\"><option selected></option>";
		foreach($parkList as $k=>$v){
			$con1="menu.php?form_type=form_A&park_code=$v";
			if($v==$_SESSION['fuel']['select']){
				$park_code=$v;
				$s="selected";}else{$s="value";}
			echo "<option $s='$con1'>$v\n";
    		   }
  	 echo "</select></td>";
	}

// Level 2
if($level==2){
include_once("../../include/get_parkcodes_dist.php");

$database="fuel";
mysqli_select_db($connection,$database)
or die ("Couldn't select database");

// $distCode=$_SESSION['reg'];
$distCode=$_SESSION['fuel']['selectR'];  // this works for Julie
// 	$distCode=$_SESSION['fuel']['select'];  works for someone where selectR doesn't   fix it when that person calls - also form_A_month.php
if(empty($park_code)){$park_code=$distCode;}
$menuList="array".$distCode; $parkArray=${$menuList};
sort($parkArray);
// echo "<pre>"; print_r($parkArray); echo "</pre>"; // exit;
		if( in_array($park_code,$parkArray))
			{
			$_SESSION['fuel']['temp']=$park_code;
			}
		echo "<td><select name=\"center_code\" onChange=\"MM_jumpMenu('parent',this,0)\"><option selected></option>";
		foreach($parkArray as $k=>$v){
			$con1="menu.php?form_type=form_A&park_code=$v";
			if($v==$_SESSION['fuel']['temp']){
				$park_code=$v;
				$s="selected";}else{$s="value";}
			echo "<option $s='$con1'>$v\n";
    		   }
  	 echo "</select></td>";
}

// Level >2
if($level>2)
	{
	$database="dpr_system";
	$db = mysqli_select_db($connection,$database)
	or die ("Couldn't select database");
	
	$skip_district=array("eadi","nodi","sodi","wedi");
	$sql= "SELECT park_code AS parkCode FROM parkcode_names_district";
		$result = mysqli_query($connection,$sql) or die ("Couldn't execute query.");
		//echo "$sql";
		while($row=mysqli_fetch_assoc($result))
			{
			$allParks[]=$row['parkCode'];		
			}
	// ADD  regions   budget.center table still has old districts in parkCode
			$allParks[]="CORE";
			$allParks[]="PIRE";
			$allParks[]="MORE";
			$allParks[]="YORK";
				
	$database="fuel";
	  $db = mysqli_select_db($connection,$database)
		   or die ("Couldn't select database");
		   
if(!isset($park_code)){$park_code="";}	
	$sql= "SELECT distinct center_code from vehicle where 1 order by center_code";
		$result = mysqli_query($connection,$sql) or die ("Couldn't execute query.");
		//echo "$sql";
		while($row=mysqli_fetch_assoc($result))
			{
			$someParks[]=$row['center_code'];		
			}
			
			foreach($allParks as $k=>$v)
			{
			if(in_array($v,$someParks)){$parkArray[]=$v;}
				else{$parkArray[]=strtolower($v);}
			}
			
			if($park_code AND in_array($park_code,$parkArray))
			{
			$_SESSION['fuel']['temp']=$park_code;
			}
// echo "<pre>"; print_r($parkArray); echo "</pre>"; // exit;
			echo "<td><select name=\"center_code\" onChange=\"MM_jumpMenu('parent',this,0)\"><option selected></option>";
			foreach($parkArray as $k=>$v){
				$lower_v=$v;
				$v=strtoupper($v);
				$con1="menu.php?form_type=form_A&park_code=$v";
			//	$con1=$v;
				if($v==$park_code){
					$park_code=$v;
					$s="selected";}else{$s="value";}
				echo "<option $s='$con1'>$lower_v\n";
				   }
		 echo "</select></td>";	
	}

		echo "<td>";
			
			
		if($level>0)
			{			
			$sql="SELECT distinct year
			FROM `items`"; 
			$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SELECT.");
			while($row=mysqli_fetch_assoc($result))
				{
				$years[]=$row['year'];
				}
			if(!in_array(date('Y'),$years)){$years[]=date('Y');}
			}
			else
			{
			if(date("n")<3){$years[]=date("Y")-1;}
			$years[]=date("Y");
			}
	// Year
		echo "<select name='year'>";
			foreach($years as $k=>$v){
			if($year==$v){$s="selected";}else{$s="value";}
			echo "<option $s='$v'>$v</option>";
			}
		echo "</select></td>";
	// Vehicles
		echo "<td><select name='v_id'>";
			foreach($vehicles as $k=>$v){
	//		if($v_id==$k){$s="selected";}else{$s="value";}
			echo "<option value='$k'>$v</option>";
			}
		echo "</select>";
echo "</td>";
echo "<td><input type='submit' name='submit' value='Submit'></td>";
echo "</tr></table></form></div>";

if(empty($yr_vehicle)){
		if(!isset($v_id)){$v_id="";}
		if($v_id=="Select a Vehicle" OR !$v_id){exit;}
		$yr_vehicle=$_POST['year'].$_POST['v_id'];
			}
		else
		{
		$v_id=substr($yr_vehicle,4);		
		$year=substr($yr_vehicle,0,4);
		}

// Get data for vehicle
	$sql= "SELECT * from vehicle where id='$v_id'";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query.");
	$rowVehicle=mysqli_fetch_assoc($result);
	
//	echo "$sql<pre>"; print_r($rowVehicle); echo "</pre>";  exit;

// Get person assigned
	$sql= "SELECT concat(t1.Lname, ', ', t1.Fname) as name, t2.beacon_num 
	from divper.empinfo as t1
	LEFT JOIN divper.emplist as t2 on t1.tempID=t2.tempID
	LEFT JOIN divper.emplist as t3 on t2.beacon_num=t3.beacon_num
	LEFT JOIN fuel.vehicle as t4 on t3.beacon_num=t4.assigned_to
	where t4.id='$v_id'"; //echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query.");
	$rowPerson=mysqli_fetch_assoc($result);
	if($rowPerson['beacon_num']!="")
		{
		$person[$v_id]=$rowPerson['name'];
		}
		else
		{		
		$person[$v_id]="Vehicle NOT assigned.";
		}
	
	
// Get mileage since start
	$this_year=date('Y');
	$sql= "SELECT year, sum(mileage) as miles_since_start 
	from items 
	where `vehicle`='$v_id' and year< '$this_year'
	group by year
	";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. ");
//	echo "$sql";
	while($row=mysqli_fetch_assoc($result))
		{
		$miles_since_start=$row['miles_since_start'];
		$yearly_mileage_array[$row['year']]=$miles_since_start;
		}
//echo "<pre>";print_r($yearly_mileage_array);echo "</pre>";
	
	$sql= "SELECT * from items 
	where `year`='$year' and `vehicle`='$v_id'";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query.");
	//	$vehicles[0]="Select a Vehicle";
			while($row=mysqli_fetch_assoc($result)){
		$ARRAY[$row['month']]=$row;
	}

//echo "$sql";	
//	echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;
	
$radio_fuel=array("u"=>"Unleaded","f"=>"Flex","d"=>"Diesel","e"=>"Electric");

echo "<table>";
$sm=$rowVehicle['mileage'];
$smf=number_format($sm,0);
echo "<tr><td>Starting Mileage 1 Jan. '10 (or when acquired by DPR ) => $smf</td></tr>";
foreach($yearly_mileage_array as $k=>$v)
	{
	@$tym+=$v;
	$smy_array[$k]=$tym;
	$v=number_format($v,0);
	echo "<tr><td align='right'>miles driven in $k => $v</td></tr>";
	}
echo "</td></tr>";
if(isset($tym)){$tym=number_format($tym+$sm,0);}

echo "<tr><th align='right'>$tym</th></tr></table>";

echo "<div align='center'><form action='replace.php' method='POST'>";
echo "<table border='1' cellpadding='5'>";
	$skipVehicle=array("id","previous","duty","trans","drive","emergency","comment","title","used_for","purpose","GVWR","cdl","wex_number","wex_pin","FAS_num","status",'cost');
	echo "<tr><th colspan='13'><font color='brown'>";
if($rowVehicle)
	{
	foreach($rowVehicle as $k=>$v)
		{
		if(in_array($k,$skipVehicle)){continue;}
		if($k=="mileage")
			{			
		//	$totMileage=$tm;
		//		echo "<pre>";print_r($smy_array);echo "</pre>";
			$smy=$sm+@$smy_array[$year-1];
			$smyf=number_format($sm+@$smy_array[$year-1],0);
			if($v_id==44 and $year=="2013")
				{ // odometer change @ JORD in 2013
				$smy=$sm+@$smy_array[$year-1]-32145;
				$smyf=number_format($smy,0);
				}
			$v="<font color='green'>Starting Mileage for year $year: ".$smyf."</font><br />";
			}
		if($k=="inspected")
			{
			$inspect="<a href='edit.php?table=vehicle&id=$v_id' target='_blank'>$v</a>";
			$v="<font color='red'> Last inspection on: ".$inspect."</font>";
			}
		if($k=="fuel")
			{
			$v=$radio_fuel[$v]; 
			$passFuel=$v;
			}
		if($k=="assigned_to"){$v=$person[$rowVehicle['id']];}
		echo "<b>&nbsp;&nbsp;&nbsp;$v</b>";
		}
	}	
	echo "</font></th></tr>";

foreach($months as $k=>$v)
		{
		@$monthMileage+=$ARRAY[$v]['mileage'];
		if(!empty($ARRAY[$v]['mileage'])){$this_month=$v;}
		}
$totMileage=number_format($monthMileage+$smy);
//echo "<pre>"; print_r($totMileage); echo "</pre>"; // exit;
echo "<tr><th colspan='".count($fieldArray)."'>Ending Mileage for $this_month should equal <font color='magenta'>$totMileage</font></th></tr>";
	
echo "<tr><th>$year</th>";

//	echo "<pre>"; print_r($months); print_r($ARRAY); echo "</pre>";  exit;
	foreach($months as $k=>$v)
		{
// 		@$monthMileage+=$ARRAY[$v]['mileage'];
		echo "<th>$v</th>";
		}
echo "</tr>";
// $totMileage=number_format($monthMileage+$smy);
//echo "<pre>"; print_r($totMileage); echo "</pre>"; // exit;

$skip=array("id","year","vehicle","month","other_fuel_type","other_oil_type");
$subName=array("mileage"=>"Mileage - <font color='brown' size='-1'>$totMileage</font>","unleaded"=>"Unleaded (gallons)","E-10"=>"E-10 (gallons)","E-85"=>"E-85 (gallons)","diesel"=>"Diesel","diesel_B10"=>"Diesel_B10","diesel_B20"=>"Diesel_B20","other_fuel"=>"Other Fuel (gallons)<br />specify type ","electric"=>"Electric","virgin_oil"=>"Virgin Oil (quarts)","re-refined"=>"Re-refined (quarts)","synthetic"=>"Synthetic (quarts)","other_oil"=>"Other Oil (quarts)<br />specify type ","lbs_refrig-1"=>"Pounds of<br />Refrigerant R-12","lbs_refrig-2"=>"Pounds of<br />Refrigerant 134a");
$subValue=array("other_fuel","other_oil");

$fuel_array=array("unleaded","E-10","E-85","diesel","diesel_B10","diesel_B20","electric","other_fuel");
$limit_fuel['unleaded']=array("E-85","diesel","diesel_B10","diesel_B20","electric");
$limit_fuel['flex']=array("diesel","diesel_B10","diesel_B20","electric");
$limit_fuel['diesel']=array("unleaded","E-10","E-85","electric");
$limit_fuel['electric']=array("unleaded","E-10","E-85","diesel","diesel_B10","diesel_B20","other_fuel");

				$passFuel=strtolower($passFuel);
				
$non_format=array("mileage","virgin_oil","re-refined","synthetic","other_oil");

$i=0;
foreach($fieldArray as $k=>$v){
	if(in_array($v,$skip)){continue;}
	if(fmod($i,2)==0){$tr=" bgcolor='aliceblue'";}else{$tr="";}
	echo "<tr$tr><th align='left'>";
		if(in_array($v,$subValue)){
			$v1=$v."_type";
			@$value=$ARRAY['Apr'][$v1]; // month doesn't matter
			echo "$subName[$v] <input type='text' name='$v1' value='$value' size='10'>";
			}
		else{echo "$subName[$v]";}
	echo "</th>";
		foreach($months as $k1=>$v1){
		$blank="";
			if(in_array($v,$fuel_array)){
				if($passFuel=="unleaded"){
					if(in_array($v,$limit_fuel[$passFuel])){$blank=1;}
					}
				if($passFuel=="flex"){
					if(in_array($v,$limit_fuel[$passFuel])){$blank=1;}
					}
				if($passFuel=="diesel"){
					if(in_array($v,$limit_fuel[$passFuel])){$blank=1;}
					}
				if($passFuel=="electric"){
					if(in_array($v,$limit_fuel[$passFuel])){$blank=1;}
					}
				
				}
			$name=$v1."[".$v."]";
			@$value=$ARRAY[$v1][$v];
			//	if($value==="0.0"){$value="";}
				
				if($blank==""){
					echo "<td><input type='text' name='$name' value='$value' size='7'></td>";}
					else{echo "<td></td>";}
					
			@$tot+=$value;
			}
			if(in_array($v,$non_format)){}else{$tot=number_format($tot,1);}
		
		if($tot==0){$tot="-";}
	echo "<td align='right' bgcolor='yellow'>$tot</td></tr>";
	@$i++; $tot="";
	}

echo "<tr><td align='center' colspan='13'>";
if(!empty($center_code))
	{
	echo "<input type='hidden' name='center_code' value='$center_code'>";
	}
echo "<input type='hidden' name='year' value='$year'>
<input type='hidden' name='id' value='$v_id'>
<input type='submit' name='submit' value='Submit'>
</td></tr>";
echo "</table></div></form>";


?>