<?php
foreach($ARRAY as $k=>$array)
	{	
		@$center_mileage[$array['center_code']]+=$array['mileage'];
/*		$center_unleaded[]=$array['unleaded'];
		$center_e10[]=$array['E-10'];
		$center_e85[]=$array['E-85'];
		$center_diesel[]=$array['diesel'];
		$center_diesel_b20[]=$array['diesel_B20'];
		$center_other_fuel[]=$array['other_fuel']; */
	}

//echo "<pre>"; print_r($center_mileage); echo "</pre>";  exit;
echo "<table>";

echo "<tr>
<th>Center Code</th><td> </td><th>Mileage</th></tr>";

$link="/fuel/menu.php?form_type=report_menu&report=miles_park";
foreach($center_mileage as $center_code=>$mileage)
	{
	@$total+=$mileage;
	$mileage=number_format($mileage,0);
	$cc="<a href='$link&center_code=$center_code&pass_year=$pass_year'>$center_code</a>";
	echo "<tr>
	<td align='center'>$cc</td><td> </td><th align='right'>$mileage</th></tr>";
	}
$total=number_format($total,0);
echo "<tr><td colspan='3' align='right'>$total</td></tr>";
echo "</table></div></body></html>";
?>
