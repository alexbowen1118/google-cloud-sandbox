<?php
extract($_REQUEST);

if(!isset($center_code)){$center_code="";}
if($center_code)
	{
	$park_code=$center_code;
	}
if(!isset($park_code)){$park_code="";}

//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  exit;

include("../../include/iConnect.inc");// database connection parameters
$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");



if($level==1)
	{
	$parkList=explode(",",$_SESSION['fuel']['accessPark']);
			// if($_SESSION['fuel']['beacon_num']=="60032780")
// 				{$_SESSION['fuel']['select']="INED";}
// 			if($_SESSION['fuel']['beacon_num']=="60091483")
// 				{$_SESSION['fuel']['select']="REMA";}
	if($parkList[0]==""){$park_code=$_SESSION['fuel']['select'];}
	if(!$park_code){$park_code=$_SESSION['fuel']['select'];}
	}
	else
	{
	$parkList[0]="";
	}

	$where="and center_code='$park_code'";


// FIELD NAMES are stored in $fieldArray
$sql = "SHOW COLUMNS FROM form_b";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2.");
while ($row=mysqli_fetch_assoc($result)){$fieldArray[]=$row['Field'];}

//echo "<pre>"; print_r($fieldArray); echo "</pre>";  //exit;

foreach(range(1,12) as $v){
		$months[]=date("M",mktime(0,0,0,$v,1,2000));
	}

echo "<div align='center'><form action='menu.php?form_type=form_B' method='POST'>";
echo "<table>";

echo "<tr>";

// For Level 1 with privileges
	if($parkList[0]!=""){
		if($park_code AND in_array($park_code,$parkList)){
			$_SESSION['fuel']['select']=$park_code;
			}
		echo "<td><select name=\"center_code\"><option selected></option>";
		foreach($parkList as $k=>$v){
			$con1=$v;
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
	include_once("../../include/get_parkcodes_reg.php");

	$regCode=$_SESSION['reg'];
	$menuList="array".$regCode; $parkArray=${$menuList};
	sort($parkArray);
	

			if($park_code AND in_array($park_code,$parkArray)){
				$_SESSION['fuel']['temp']=$park_code;
				}
			echo "<td><select name=\"center_code\"><option selected></option>";
			foreach($parkArray as $k=>$v){
				$con1=$v;
				if($v==$_SESSION['fuel']['temp']){
					$park_code=$v;
					$s="selected";}else{$s="value";}
				echo "<option $s='$con1'>$v\n";
				   }
		 echo "</select></td>";
	}

// Level >3
if($level>3)
	{
	$skip_rcc=array("BOCA","HARP","MTST","WAYN","WLSL",);
	mysqli_select_db($connection,"dpr_system");
	$sql= "SELECT park_code AS parkCode FROM parkcode_names_district";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	while($row=mysqli_fetch_assoc($result))
		{
		$allParks[]=$row['parkCode'];		
		}
		// Do any district/region have any Form-B items?
// 	$parkRCC['EADI']="2805";
// 	$parkRCC['NODI']="2901";
// 	$parkRCC['SODI']="2830";
// 	$parkRCC['WEDI']="2850";
	
	array_push($allParks,"WARE");		
			if($park_code AND in_array($park_code,$allParks))
				{
				$_SESSION['fuel']['temp']=$park_code;
				}
			echo "<td><select name=\"center_code\"><option selected></option>";
			foreach($allParks as $k=>$v)
				{
				$lower_v=$v;
				$v=strtoupper($v);
				$con1=$v;
				if($v==$_SESSION['fuel']['temp'])
					{
					$park_code=$v;
					$s="selected";
					}
					else
					{$s="value";}
				echo "<option $s='$con1'>$v\n";
				}
		 echo "</select></td>";	
	}

mysqli_select_db($connection,"fuel");
		$sql="SELECT distinct year
		FROM `form_b`"; 
		$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SELECT.");
		while($row=mysqli_fetch_assoc($result))
			{
			$years[]=$row['year'];
			}
		if(!in_array(date('Y'),$years)){$years[]=date('Y');}
		
// Year
	echo "<td><select name='year'>";
		foreach($years as $k=>$v){
		if($year==$v){$s="selected";}else{$s="value";}
		echo "<option $s='$v'>$v</option>";
		}
	echo "</select></td>";

echo "<td><input type='submit' name='submit' value='Submit'></td>";
echo "</tr></table></form>";


if(!$park_code){echo "<font co$connectionlor='red'>Please select a Park.</font>"; exit;}

	mysqli_select_db($connection,$database);
	$sql= "SELECT * from form_b where year='$year' and center_code='$park_code'";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
		$vehicles[0]="Select a Vehicle";
			while($row=mysqli_fetch_assoc($result)){
		$ARRAY[$row['month']]=$row;
	}
//	echo "$sql";
//	echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;
	
echo "<div align='center'><form action='replace_form_b.php' method='POST'>";
echo "<table border='1' cellpadding='5'>";
	
$skip=array("year","month","center_code");

// Table headers
	echo "<tr><th></th>
	<th colspan='7'  bgcolor='aliceblue'>Agricultural Equipment<br />(Farm Tractor, Combine)</th>
	<th colspan='7'>Heavy / Construction Equipment<br />(fulltrack, backhoe, grader, dozer)</th>
	<th colspan='8'  bgcolor='aliceblue'>Small Offroad Vehicles<br />(ATV, mule, gator, golfcart, snowmobile, riding mower)</th>
	<th colspan='7'>Utility<br />(fork lift, manlift)</th>
	<th colspan='8'  bgcolor='aliceblue'>Other<br />(push mower, power tools, blowers, generators)</th>
	<th colspan='7'>Boats</th>
	</tr>";
	
	echo "<tr><th><font color='magenta'>$park_code</font></th>
	<th colspan='7'>Gallons</th>
	<th colspan='7'>Gallons</th>
	<th colspan='8'>Gallons</th>
	<th colspan='7'>Gallons</th>
	<th colspan='8'>Gallons</th>
	<th colspan='7'>Gallons</th>
	</tr>";
	
$row_header=array("unl_3","unl_5");
	echo "<tr><th><font color='blue'>$year</font></th>";
			foreach($fieldArray as $k1=>$v1){
				if(in_array($v1,$skip)){continue;}
				$var=explode("_",strtoupper($v1));
				if(in_array($v1,$row_header)){echo "<th><font color='blue'>$year</font></th>";}
				echo "<th>$var[0]</th>";
				}
	echo "</tr>";


// Data entry
//echo "<pre>"; print_r($fieldArray); echo "</pre>"; // exit;
	foreach($months as $k=>$v){
		echo "<tr><th>$v</th>";
		$jj=0;
			foreach($fieldArray as $k1=>$v1)
				{
				$jj++;
				if(in_array($v1,$skip)){continue;}
				if(in_array($v1,$row_header)){echo "<th>$v</th>";}
				
				$fld=$v."[".$v1."]";
				@$value=$ARRAY[$v][$v1];
				if($value=="0.0"){$value="";}
				$th="";
				if($jj<11){$th=" bgcolor='aliceblue'";}
				if($jj>17 and $jj<25){$th=" bgcolor='aliceblue'";}
				if($jj>31 and $jj<39){$th=" bgcolor='aliceblue'";}
				echo "<td align='center'$th><input type='text' name='$fld' value='$value' size='3'></td>";
				@${"tot_".$v1}+=$value;
				}
		echo "</tr>";
		}
//echo "<pre>a"; print_r($tot_unl_1); echo "</pre>"; // exit;
echo "<tr><th>Total</th>";
	
	foreach($fieldArray as $k1=>$v1){
				if(in_array($v1,$skip)){continue;}
				$var=${"tot_".$v1};
				if($var=="0"){$var="-";}else{$var=number_format($var,1);}
				if(in_array($v1,$row_header)){echo "<th>$v</th>";}
				echo "<th bgcolor='yellow'>$var</th>";
				}
echo "</tr>";

echo "<tr>
<td align='center' colspan='15'>
<input type='hidden' name='year' value='$year'>
<input type='hidden' name='center_code' value='$park_code'>
<input type='submit' name='submit' value='Submit'>
</td>
<td align='center' colspan='15'>
<input type='submit' name='submit' value='Submit'>
</td>
<td align='center' colspan='16'>
<input type='submit' name='submit' value='Submit'>
</td>
</tr>";

echo "</table></html>";
?>