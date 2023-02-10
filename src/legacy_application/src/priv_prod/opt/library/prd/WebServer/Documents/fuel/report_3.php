<?php
//$arrayWARE=array("WARE");

$a1="array";
$region_array=${$a1.$pass_region};
//  echo "<pre>$pass_region"; print_r($region_array);echo "</pre>";  exit;

//echo "<pre>$district"; print_r($ARRAY); echo "</pre>";  exit;
foreach($ARRAY as $k=>$array)
	{
	
//echo "<pre>"; print_r($array); echo "</pre>";  exit;

	foreach($region_array as $k1=>$v1)
		{
		if(in_array($v1,$array))
			{
			@$center_mileage[$v1]+=$array['mileage'];
			@$center_unleaded[$v1]+=$array['unleaded'];
			@$center_e10[$v1]+=$array['E-10'];
			@$center_e85[$v1]+=$array['E-85'];
			@$center_diesel[$v1]+=$array['diesel'];
			@$center_diesel_b10[$v1]+=$array['diesel_B10'];
			@$center_diesel_b20[$v1]+=$array['diesel_B20'];
			@$center_other_fuel[$v1]+=$array['other_fuel'];
			}
		}
	}
	
//echo "<pre>"; print_r($center_mileage); print_r($center_unleaded); echo "</pre>";  exit;
	

$fuel_type_array=array("unleaded","e10","e85","diesel","diesel_b10","diesel_b20","other_fuel","electric");

echo "<table border='1' cellpadding='5'>";

$answer1=number_format(array_sum($center_mileage),0);
echo "<tr>
<td colspan='10' align='center'>$pass_region Total <b>Miles</b> driven for $year - <b>$answer1</b> miles</td></tr>";

if(!isset($center_code)){$center_code="";}
$link="/fuel/menu.php?form_type=report_menu&report=park_fuel&center_code=$center_code";

echo "<tr><th>Park</th><th>Mileage</th>";
foreach($fuel_type_array as $k=>$fuel)
	{
	echo "<th>$fuel</th>";
	}
echo "</tr>";

foreach($region_array as $k=>$park)
	{
	@$total_district+=$center_mileage[$park];
	@$m=number_format($center_mileage[$park],0);
	echo "<tr><td>$park</td><td align='right'><b>$m</b></td>";
	foreach($fuel_type_array as $k1=>$fuel)
		{
		@$val=${"center_".$fuel};
		@$total[$fuel]+=$val[$park];
		@$val1=number_format($val[$park],1);
		echo "<td align='right'>$val1</td>";
		}
	echo "</tr>";
	}
	$td=number_format($total_district,0);
echo "<tr><td></td><td>$td</td>";

	foreach($fuel_type_array as $k1=>$fuel)
		{
		$val1=number_format($total[$fuel],1);
		echo "<td align='right'>$val1</td>";
		}
	echo "</tr>";
echo "</tr></table></div></body></html>";
?>
