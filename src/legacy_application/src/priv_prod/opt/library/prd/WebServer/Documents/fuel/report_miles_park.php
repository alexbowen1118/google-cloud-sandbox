<?php
	
$sql="SELECT vehicle_id, concat(t2.make,' - ',t2.license) as make, sum(t1.mileage) as mileage
FROM `items` AS t1 
$JOIN
where t1.year='$year'
and center_code='$center_code'
group by t1.vehicle
order by t2.make"; //echo "<br>$sql<br>"; exit;

$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SELECT. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$park_array[]=$row;
	}

//echo "<pre>"; print_r($park_array); echo "</pre>";  exit;	

echo "<table>";

echo "<tr>
<th>Vehicle ID</th><th>Vehicle</th><th>Mileage</th></tr>";

$link="/fuel/menu.php?form_type=report_menu&report=miles_park&pass_year=$pass_year";
foreach($park_array as $k=>$array)
	{
		echo "<tr>";
	foreach($array as $fld=>$value)
		{
		if($fld=="mileage")
			{
			@$total+=$value;
			$value=number_format($value,0);
			}
	//	$cc="<a href='$link&center_code=$center_code'>$center_code</a>";
		echo "<td align='right'>$value</td>";
		
		}
		echo "</tr>";
	}
$total=number_format($total,0);
echo "<tr><td colspan='3' align='right'><b>$center_code $total</b></td></tr>";
echo "</table></div></body></html>";
?>
