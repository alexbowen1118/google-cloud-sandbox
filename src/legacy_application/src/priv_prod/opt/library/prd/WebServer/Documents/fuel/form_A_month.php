<?php
extract($_REQUEST);
//session_start();
if(!isset($center_code)){$center_code="";}
if($center_code){$park_code=$center_code;}

//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  exit;
//echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;

include("../../include/iConnect.inc");// database connection parameters
$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

// Get list of vehicles by park


// FIELD NAMES are stored in $fieldArray
$sql = "SHOW COLUMNS FROM items";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2.");
while ($row=mysqli_fetch_assoc($result)){$fieldArray[]=$row['Field'];}

//echo "<pre>"; print_r($fieldArray); echo "</pre>";  //exit;

foreach(range(1,12) as $v){
		$months[]=date("M",mktime(0,0,0,$v,1,2000));
	}

echo "<div align='center'><form name='overview' action='menu.php?form_type=form_A_month' method='POST'>";
echo "<table>";

echo "<tr>";

if($level==1)
	{
		$parkList=explode(",",$_SESSION['fuel']['accessPark']);
			// if($_SESSION['fuel']['beacon_num']=="60032780")
// 				{$_SESSION['fuel']['select']="INED";}
// 			if($_SESSION['fuel']['beacon_num']=="60091483")
// 				{$_SESSION['fuel']['select']="REMA";}
		if($parkList[0]==""){$park_code=$_SESSION['fuel']['select'];}
		if(!$park_code){$park_code=$_SESSION['fuel']['select'];}
		
//	if($_SESSION['fuel']['working_title']=="Inventory & Monitoring Specialist")
//		{$park_code="REMA";$center_code="REMA";}	
	}

// For Level 1 with privileges
if(@$parkList[0]!="")
	{
		if($park_code AND in_array($park_code,$parkList)){
			$_SESSION['fuel']['select']=$park_code;
			}
			//onChange=\"MM_jumpMenu('parent',this,0)\"
		echo "<td><select name=\"center\"><option selected></option>";
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
if($level==2)
	{
// 	echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
	include_once("../../include/get_parkcodes_reg.php");
	
	$database="fuel";
	  $db = mysqli_select_db($connection,$database)
		   or die ("Couldn't select database");
		   
	$distCode=$_SESSION['fuel']['selectR'];
// 	$distCode=$_SESSION['fuel']['select'];  works for someone where selectR doesn't   fix it when that person calls - also form_A.php
	$menuList="array".$distCode; $parkArray=${$menuList};
	sort($parkArray);
	
			if(@$park_code AND in_array($park_code,$parkArray)){
				$_SESSION['fuel']['temp']=$park_code;
				}
				//onChange=\"MM_jumpMenu('parent',this,0)\"
			echo "<td><select name=\"park_code\"><option selected></option>";
			foreach($parkArray as $k=>$v){
			//	$con1="menu.php?form_type=form_A&park_code=$v";
				$con1=$v;
				if($v==@$_SESSION['fuel']['temp'])
					{
					$park_code=$v;
					$s="selected";
					}
					else{$s="value";}
				echo "<option $s='$con1'>$v\n";
				   }
		 echo "</select></td>";
	}

// Level >3
if($level>3)
	{
			
	$database="dpr_system";
	$db = mysqli_select_db($connection,$database)
	 or die ("Couldn't select database");
	
	$sql= "SELECT park_code AS parkCode FROM parkcode_names_district";
		$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. ");
		//echo "$sql";
		while($row=mysqli_fetch_assoc($result)){
				$allParks[]=$row['parkCode'];		
			}
				
// 	include("../../include/connectROOT.inc");// database connection parameters
	$database="fuel";
	  $db = mysqli_select_db($connection,$database)
		   or die ("Couldn't select database");

	if(!isset($park_code)){$park_code="";}
	
	$sql= "SELECT distinct center_code from vehicle where 1 order by center_code";
		$result = mysqli_query($connection,$sql) or die ("Couldn't execute query.");
		//echo "$sql";
		while($row=mysqli_fetch_assoc($result)){
				$someParks[]=strtoupper($row['center_code']);		
			}
			
			foreach($allParks as $k=>$v){
				if(in_array($v,$someParks)){$parkArray[]=$v;}
					else{$parkArray[]=strtolower($v);}
				}
			
			if($park_code AND in_array($park_code,$parkArray)){
				$_SESSION['fuel']['temp']=$park_code;
				}
				//onChange=\"MM_jumpMenu('parent',this,0)\"
			echo "<td><select name=\"park_code\"><option selected></option>";
			foreach($parkArray as $k=>$v){
				$lower_v=$v;
				$v=strtoupper($v);
			//	$con1="menu.php?form_type=form_A_month&park_code=$v";
				$con1=$v;
				if($v==$park_code){
					$park_code=$v;
					$s="selected";}else{$s="value";}
				echo "<option $s='$con1'>$lower_v\n";
				   }
		 echo "</select></td>";	
	}
	
if(!empty($park_code))
	{$where="and center_code='$park_code'";}
	else
	{$where="";}

$park_order=", make, year, model";
if(@$park_code=="KELA" OR @$park_code=="JORD" OR @$park_code=="FALA")
	{$park_order=",park_id";}
 $sql= "SELECT * from vehicle
 where center_code!='SURP'
 $where
 order by center_code $park_order
 ";
// echo " $sql $sql0";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query.");
	while($row=mysqli_fetch_assoc($result))
		{
		$vehicle_array[$row['id']]=$row;
		}
	$vehicle_count=@count($vehicle_array);
	if($vehicle_count<1)
		{
		$message="<font color='red'>There has not been any vehicle assigned to $park_code.</font>";
		}
//echo "$sql<pre>"; print_r($vehicle_array); echo "</pre>";  exit;

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
	// Month
	if(!isset($month)){$month="";}
	array_unshift($months,"Select a Month");
		echo "<td><select name='month'>";
			foreach($months as $k=>$v){
			if($month==$v){$s="selected";}else{$s="value";}
			echo "<option $s='$v'>$v</option>";
			}
		echo "</select>";
echo "</td>";
echo "<td><input type='submit' name='submit' value='Submit'></td>";
echo "</tr></table></form></div>";

if(@$_POST['month']=="" AND $month==""){exit;}

if(@$message!=""){echo "$message";exit;}

//echo "<pre>"; print_r($vehicle_array); echo "</pre>"; // exit;
$where="(";
	foreach($vehicle_array as $k=>$v)
		{
		$where.="vehicle='".$k."' OR ";
		}
		$where=rtrim($where," OR ").")";
		
	$sql= "SELECT * from items 
	where `year`='$year' and `month`='$month'
	and $where
	order by mileage desc
	"; 
// 	echo "$sql";//exit;
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query.");
		while($row=mysqli_fetch_assoc($result))
			{
			$ARRAY[$row['vehicle']]=$row;
			}

//	echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;

	if(isset($ARRAY))
		{
		$this_year=date('Y');
		foreach($ARRAY as $k=>$v)
			{
			$sql="SELECT vehicle, sum(mileage) as tm from items where `vehicle`='$k'
			group by vehicle";$result = mysqli_query($connection,$sql) or die ("Couldn't execute query.");
	//		echo "$sql<br />";
			$row=mysqli_fetch_assoc($result);
			$total_mileage[$row['vehicle']]=$row['tm'];
			}
		}

//	echo "<pre>"; print_r($total_mileage); echo "</pre>";  exit;
	
$skip=array("id","year","vehicle","month","other_fuel_type","other_oil_type");

$ColumnName=array("mileage"=>"Miles this month","unleaded"=>"Unleaded (gallons)","E-10"=>"E-10 (gallons)","E-85"=>"E-85 (gallons)","diesel"=>"Diesel (gallons)","diesel_B10"=>"Diesel_B10 (gallons)","diesel_B20"=>"Diesel_B20 (gallons)","other_fuel"=>"Other Fuel","electric"=>"Electric","virgin_oil"=>"Virgin Oil (quarts)","re-refined"=>"Re-refined (quarts)","synthetic"=>"Synthetic (quarts)","other_oil"=>"Other Oil","lbs_refrig-1"=>"Pounds of<br />Refrigerant R-12","lbs_refrig-2"=>"Pounds of<br />Refrigerant 134a");

$subName=array("other_fuel"=>"(gallons) ","other_oil"=>"(quarts)");
$subValue=array("other_fuel","other_oil");

$fuel_array=array("unleaded","E-10","E-85","diesel","diesel_B10","diesel_B20","electric","other_fuel");
$limit_fuel['unleaded']=array("E-85","diesel","diesel_B10","diesel_B20","electric");
$limit_fuel['flex']=array("diesel","diesel_B10","diesel_B20","electric");
$limit_fuel['diesel']=array("unleaded","E-10","E-85","electric");
$limit_fuel['electric']=array("unleaded","E-10","E-85","diesel","diesel_B10","diesel_B20","other_fuel");

$radio_fuel=array("u"=>"Unleaded","f"=>"Flex","d"=>"Diesel","e"=>"Electric");

$skipVehicle=array("id","duty","trans","drive","emergency","comment");
					
$non_format=array("mileage","virgin_oil","re-refined","synthetic","other_oil");

if($month=="Select a Month"){exit;}

echo "<div align='center'><form action='replace_month.php' method='POST'>";
echo "<table border='1' cellpadding='5'>";
	echo "<tr><th colspan='16'>
	Enter values for any of the <font color='blue'>$vehicle_count $park_code</font> vehicles driven during <font color='blue'>$month $year</font>.
	</th></tr>";
	
//	echo "<pre>"; print_r($vehicle_array); echo "</pre>";  exit;
//	echo "<pre>"; print_r($fieldArray); echo "</pre>";  //exit;

//$BASE_ARRAY is list of ALL active vehicles for park
	$new_array=array_values($fieldArray);
	foreach($vehicle_array as $k=>$v)
		{
		$BASE_ARRAY[$k]=$new_array;
		}

// echo "<pre>"; print_r($BASE_ARRAY); echo "</pre>"; // exit;

// Work through $BASE_ARRAY and grab values from $ARRAY where there's a match
$i=0;
		foreach($BASE_ARRAY as $index=>$array)
			{
			@$array=$ARRAY[$index];
			$flip="";
			if($array=="") // no vehicle record for that park/month combo
				{
				$flip=1;
				$array=array_flip($BASE_ARRAY[$index]);// create blank fields
				}
	// header
			if(fmod($i,7)==0)
				{
				
			echo "<tr><th><font size='+2' color='blue'>$month</font></th></td>";
				foreach($array as $fld=>$value)
					{
					if(in_array($fld,$skip)){continue;}
					echo "<th><font color='brown'>$ColumnName[$fld]</font></th>";
					}		
			echo "</tr>";				
				}
				if($level>4)
					{
				//	echo "<pre>"; print_r($vehicle_array[412]); echo "</pre>"; // exit;
					}
				$var_tm1=$vehicle_array[$index]['mileage'];
				@$var_tm2=$total_mileage[$index];
				$totMileage=number_format($var_tm1+$var_tm2);
			if($vehicle_array[$index]['vin']=="1HTSCABN2YH247695" and $year=="2013")
				{ // odometer change @ JORD in 2013
				$totMileage=number_format($var_tm1+$var_tm2-32145);
				}
				$license=$vehicle_array[$index]['license'];
				$vehicle_link="<a href='menu.php?form_type=form_A&park_code=$park_code&year=$year&v_id=$index'>$license</a>";
				
				$park_id="<font color='red'>".$vehicle_array[$index]['park_id']."</font>";
				$vehicle_info=$vehicle_array[$index]['make']." ".$vehicle_array[$index]['model']."<br />".$vehicle_array[$index]['year']." ".$vehicle_link."<br /><font color='green'>".$totMileage."</font>";
				
			@$fuel_type=strtolower($radio_fuel[$vehicle_array[$index]['fuel']]);
			$inspect=$vehicle_array[$index]['inspected'];
			$vin=$vehicle_array[$index]['vin'];
			echo "<tr><td>$park_id $vehicle_info <font color='brown'>$fuel_type</font>
			<br />Inspected on: <a href='edit.php?table=vehicle&vi=$vin' target='_blank'>$inspect</a></td>";
	// values		
	//echo "<pre>"; print_r($array); echo "</pre>"; // exit;
				foreach($array as $fld=>$value)
					{
					if(in_array($fld,$skip)){continue;}
					$name=$index."[".$fld."]";
					if($flip==1 || ($value<1 AND $fld!="mileage")){$value="0";}
					@$item="$subName[$fld] <input type='text' name='$name' value='$value' size='8'>";
					if($fld=="other_fuel" || $fld=="other_oil")
						{
						$name=$index."[".$fld."_type]";;
						@$value=$ARRAY[$index][$v1];
						$item.="<br />specify type<input type='text' name='$name' value='$value' size='8'>";
						}
					if(in_array($fld,$fuel_array))
						{
						if(@in_array($fld,$limit_fuel[$fuel_type]))
							{$item="";}
						}
					
					echo "<td>$item</td>";
					}
					
			echo "</tr>";
			@$i++;
			}
	echo "</table>";
		

echo "<tr><td align='center' colspan='13'>
<input type='hidden' name='center_code' value='$park_code'>
<input type='hidden' name='year' value='$year'>
<input type='hidden' name='month' value='$month'>
<input type='submit' name='submit' value='Submit'>
</td></tr>";
echo "</table></div></form>";


?>