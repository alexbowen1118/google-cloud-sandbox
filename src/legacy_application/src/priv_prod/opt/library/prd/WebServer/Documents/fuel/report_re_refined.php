<?php
$consumable="re_refined";
$a="center_";
$center_array=${$a.$consumable};
foreach($ARRAY as $k=>$array)
	{
	$center_array[$array['center_code']]+=$array[$consumable]; 
	}

//echo "<pre>$name"; print_r($center_array); echo "</pre>";  exit;
echo "<table>";

echo "<tr>
<th>Center Code</th><td> </td><th>Virgin Oil (quarts)</th></tr>";

$link="/fuel/menu.php?form_type=report_menu&report=park_fuel&fuel=$consumable";
foreach($center_array as $center_code=>$consumable)
	{
	@$total+=$consumable;
	$consumable=number_format($consumable,1);
	$cc="<a href='$link&center_code=$center_code&pass_year=$pass_year'>$center_code</a>";
	echo "<tr>
	<td align='right'>$cc</td><td> </td><th align='right'>$consumable</th></tr>";
	}
$total=number_format($total,1);
echo "<tr><td colspan='3' align='right'>$total</td></tr>";
echo "</table></div></body></html>";
?>
