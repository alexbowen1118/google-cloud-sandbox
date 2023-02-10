<?php

// echo "l=$level<pre>"; print_r($_SESSION); echo "</pre>";  //exit;
// echo "<pre>"; print_r($_POST); echo "</pre>";  //exit;

include("../../include/get_parkcodes_dist.php");  // includes database connection parameters

@$pass_district=$district;
//@$pass_region=$region;
$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

	$JOIN="left join vehicle as t2 on t1.vehicle=t2.id";
	$GROUP_BY="group by year";

$sql="SELECT distinct year
FROM `items`"; 
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query.");
while($row=mysqli_fetch_assoc($result))
	{
	$year_array[]=$row['year'];
	}

if($level<2)
	{
// 	$limit_park="and t2.center_code='JORD'";
	}
if(!isset($pass_year)){$pass_year="";}
if($pass_year!="")
	{
	$year=$pass_year;
	$sql="SELECT distinct center_code
	FROM `vehicle`
	order by center_code"; 
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SELECT.");
	while($row=mysqli_fetch_assoc($result))
		{
		$center_code_array[]=$row['center_code'];
		}
	
	if(!isset($limit_park)){$limit_park="";}
	$sql="SELECT center_code,sum(t1.mileage) as mileage,
	sum(t1.unleaded) as unleaded,sum(t1.`E-10`) as 'E-10',sum(t1.`E-85`) as 'E-85',sum(t1.`diesel`) as diesel,sum(t1.`diesel_B10`) as 'diesel_B10',sum(t1.`diesel_B20`) as 'diesel_B20',sum(t1.`other_fuel`) as other_fuel, sum(t1.`virgin_oil`) as virgin_oil, sum(t1.`re-refined`) as re_refined, sum(t1.`synthetic`) as synthetic, sum(t1.`other_oil`) as other_oil
	FROM `items` AS t1 
	$JOIN
	where t1.year='$year'
	$limit_park
	group by t2.center_code,t2.id";
// echo "<br>$sql<br>";
	
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SELECT.");
	while($row=mysqli_fetch_assoc($result))
		{
		$ARRAY[]=$row;
		$total_mileage[]=$row['mileage'];
		$total_unleaded[]=$row['unleaded'];
		$total_e10[]=$row['E-10'];
		$total_e85[]=$row['E-85'];
		$total_diesel[]=$row['diesel'];
		$total_diesel_b10[]=$row['diesel_B10'];
		$total_diesel_b20[]=$row['diesel_B20'];
		$total_other_fuel[]=$row['other_fuel'];
		$total_virgin_oil[]=$row['virgin_oil'];
		$total_re_refined[]=$row['re_refined'];
		$total_synthetic_oil[]=$row['synthetic'];
		$total_other_oil[]=$row['other_oil'];
		}
	
	//echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;
	
		$answer1=number_format(array_sum($total_mileage),0);
		$answer2=number_format(array_sum($total_unleaded),1);
		$answer3=number_format(array_sum($total_e10),1);
		$answer4=number_format(array_sum($total_e85),1);
		$answer5=number_format(array_sum($total_diesel),1);
		$answer6=number_format(array_sum($total_diesel_b10),1);
		$answer7=number_format(array_sum($total_diesel_b20),1);
		$answer8=number_format(array_sum($total_other_fuel),1);
		$answer9=number_format(array_sum($total_virgin_oil),1);
		$answer10=number_format(array_sum($total_re_refined),1);
		$answer11=number_format(array_sum($total_synthetic_oil),1);
		$answer12=number_format(array_sum($total_other_oil),1);
		$total_gallons=array_sum($total_unleaded)+array_sum($total_e10)+array_sum($total_e85)+array_sum($total_diesel)+array_sum($total_diesel_b10)+array_sum($total_diesel_b20)+array_sum($total_other_fuel);
		
		if($total_gallons>0)
			{$mpg=round(array_sum($total_mileage)/$total_gallons,2);}
		
		$total_gallons_f=number_format($total_gallons,1);
	}

echo "<div>";
echo "<table>";
echo "<tr><td valign='top'>";
echo "<form method='POST' action='/fuel/menu.php'>
Select Year: <select name='pass_year' onChange=\"this.form.submit()\"><option checked=''></option>";
foreach($year_array as $k=>$v)
	{
	if($v==""){continue;}
// 	$link="/fuel/menu.php?form_type=report_menu&pass_year=$v";
	if($pass_year==$v){$s="selected";}else{$s="";}
	echo "<option value='$v' $s>$v</option>";
	}
echo "</select>";
echo "<input type='hidden' name='form_type' value=\"report_menu\">";
echo "</form></td>";

echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";

echo "<td>
<table><tr><td>Park/Mileage (Sort by mileage descending) <a href='park_mileage_summary.php?report=1' target='_blank'>link</a></td></tr>

<tr><td>Vehicle Assignment (Sort by park, staff) <a href='park_mileage_summary.php?report=2' target='_blank'>link</a></td></tr>

<tr><td>Staff/Vehicle Ratios <a href='staff_vehicle_ratio.php' target='_blank'>link</a></td></tr>

</table></td>

";

echo "</tr>";

if($pass_year==""){exit;}

echo "<tr>
<td>DPR Total <a href='menu.php?form_type=report_menu&report=miles&pass_year=$pass_year'>Miles</a> driven for $year.</td><td> </td><th align='right'>$answer1 miles</th></tr>
<tr><td>DPR Total <a href='menu.php?form_type=report_menu&report=unleaded&pass_year=$pass_year'>Unleaded</a> for $year.</td><td> </td><th align='right'>$answer2 gallons</th></tr>
<tr><td>DPR Total <a href='menu.php?form_type=report_menu&report=e10&pass_year=$pass_year'>E-10</a> for $year.</td><td> </td><th align='right'>$answer3 gallons</th></tr>
<tr><td>DPR Total <a href='menu.php?form_type=report_menu&report=e85&pass_year=$pass_year'>E-85</a> for $year.</td><td> </td><th align='right'>$answer4 gallons</th></tr>
<tr><td>DPR Total <a href='menu.php?form_type=report_menu&report=diesel&pass_year=$pass_year'>Diesel</a> for $year.</td><td> </td><th align='right'>$answer5 gallons</th></tr>
<tr><td>DPR Total <a href='menu.php?form_type=report_menu&report=diesel_b10&pass_year=$pass_year'>Diesel B-10</a> for $year.</td><td></td><th align='right'>$answer6 gallons</th></tr>
<tr><td>DPR Total <a href='menu.php?form_type=report_menu&report=diesel_b20&pass_year=$pass_year'>Diesel B-20</a> for $year.</td><td></td><th align='right'>$answer7 gallons</th></tr>
<tr><td>DPR Total <a href='menu.php?form_type=report_menu&report=other_fuel&pass_year=$pass_year'>Other Fuel</a> for $year.</td><td></td><th align='right'>$answer8 gallons</th></tr>
<tr><th align='right' colspan='3'>Total Gallons: $total_gallons_f</th><th>Fleet Miles per Gallon: $mpg</th></tr>

<tr><td>DPR Total <a href='menu.php?form_type=report_menu&report=virgin_oil&pass_year=$pass_year'>Virgin Oil</a> for $year.</td><td></td><th>$answer9 quarts</th></tr>
<tr><td>DPR Total <a href='menu.php?form_type=report_menu&report=re_refined&pass_year=$pass_year'>Re-refined</a> for $year.</td><td></td><th>$answer10 quarts</th></tr>
<tr><td>DPR Total <a href='menu.php?form_type=report_menu&report=synthetic&pass_year=$pass_year'>Synthetic</a> for $year.</td><td></td><th>$answer11 quarts</th></tr>
<tr><td>DPR Total <a href='menu.php?form_type=report_menu&report=other_oil&pass_year=$pass_year'>Other Oil</a> for $year.</td><td></td><th>$answer12 quarts</th></tr>


</table><hr />";
//exit;
echo "<form action='/fuel/menu.php' method='POST'><table>";
echo "<tr><td>Select a Park: <select name='center_code' onChange=\"this.form.submit()\"><option selected=''></option>";
$link="/fuel/menu.php?form_type=report_menu&report=1&pass_year=$year";
foreach($center_code_array as $k=>$v)
	{
	if($v==$center_code){$s="selected";}else{$s="";}
	echo "<option value='$v' $s>$v</option>";
	}
echo "</select>

<input type='hidden' name='form_type' value=\"report_menu\">
<input type='hidden' name='pass_report' value=\"1\">
<input type='hidden' name='pass_year' value=\"$year\"></form></td>";

$dist_array=array("EADI","NODI","SODI","WEDI","WARE");
$reg_array=array("CORE","PIRE","MORE","WARE");

// echo "<td>Select a District: <select name='district' onChange=\"MM_jumpMenu('parent',this,0)\"><option selected=''></option>\n";
// $link="/fuel/menu.php?form_type=report_menu&report=2&pass_year=$year";
// foreach($dist_array as $k=>$v)
// 	{
// 	echo "<option value='$link&district=$v'>$v</option>\n";
// 	}
// echo "</select></td>";

echo "<td><form action='/fuel/menu.php' method='POST'>
Select a Region: <select name='pass_region' onChange=\"this.form.submit()\"><option selected=''></option>\n";
$link="/fuel/menu.php?form_type=report_menu&report=3&pass_year=$year";
foreach($dist_array as $k=>$v)
	{
	if($v==$pass_region){$s="selected";}else{$s="";}
	echo "<option value='$v' $s>$v</option>\n";
	}
echo "</select>
<input type='hidden' name='form_type' value=\"report_menu\">
<input type='hidden' name='pass_report' value=\"3\">
<input type='hidden' name='pass_year' value=\"$year\">
</form></td>";
echo "</tr>";

$exp="";
if(!empty($center_code))
	{
	$exp="&center_code=".$center_code;
	}
echo "<tr><td>Form A Mileage for <a href='inventory_year_csv.php?pass_year=$pass_year&search=Find$exp'>$pass_year</a> Excel export</td></tr>";

echo "<tr><td>Form A consumables for <a href='form_A_yr_total.php?pass_year=$pass_year' target='_blank'>$pass_year</a></td></tr>";

echo "<tr><td>Form B consumables for <a href='form_B_yr_total.php?pass_year=$pass_year' target='_blank'>$pass_year</a></td></tr>";
echo "<tr><td>
<input type='hidden' name='form_type' value=\"report_menu\">

<input type='hidden' name='pass_year' value=\"$year\">
</td></tr>";
echo "</table><hr />";

if(!isset($report)){$report="";}
if($report=="miles"){include("report_miles.php");}
if($report=="unleaded"){include("report_unleaded.php");}
if($report=="e10"){include("report_e10.php");}
if($report=="e85"){include("report_e85.php");}
if($report=="diesel"){include("report_diesel.php");}
if($report=="diesel_b10"){include("report_diesel_b10.php");}
if($report=="diesel_b20"){include("report_diesel_b20.php");}
if($report=="other_fuel"){include("report_other_fuel.php");}
if($report=="virgin_oil"){include("report_virgin_oil.php");}
if($report=="re_refined"){include("report_re_refined.php");}
if($report=="synthetic"){include("report_synthetic.php");}
if($report=="other_oil"){include("report_other_oil.php");}
if($report=="miles_park"){include("report_miles_park.php");}
if($report=="park_fuel"){include("report_park_fuel.php");}
if($report=="park_unleaded"){include("report_park_unleaded.php");}
if(@$pass_report==1){include("report_1.php");}
// if($report==2){include("report_2.php");}
if(@$pass_report==3){include("report_3.php");}
	
?>
