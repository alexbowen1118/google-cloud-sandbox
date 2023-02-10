<?php
if($fuel=="re_refined"){$fuel="re-refined";}
$sql="SELECT concat(t2.make,' - ',t2.license) as make, sum(`$fuel`) as fuel
FROM `items` AS t1 
$JOIN
where t1.year='$year'  
and center_code='$center_code'
group by t1.vehicle
order by fuel desc"; //echo "<br>$sql<br>"; exit;

$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SELECT. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$park_array[]=$row;
	}

//echo "<pre>"; print_r($park_array); echo "</pre>";  exit;	

echo "<table>";

$oil_array=array("VIRGIN_OIL","RE-REFINED","SYNTHETIC","OTHER_OILs");
$quantity="Gallons";
if(in_array($fuel,$oil_array)){$quantity="Quarts";}

echo "<tr>
<th><font color='red'>$center_code</font> Vehicle</th><th>$quantity ($fuel)</th></tr>";

$link="/fuel/menu.php?form_type=report_menu&report=miles_park&pass_year=$pass_year";
foreach($park_array as $k=>$array)
	{
		echo "<tr>";
	foreach($array as $fld=>$value)
		{
		if($fld=="fuel")
			{
			@$total+=$value;
			$value=number_format($value,1);
			}
	//	$cc="<a href='$link&center_code=$center_code'>$center_code</a>";
		echo "<td align='right'>$value</td>";
		
		}
		echo "</tr>";
	}
$total=number_format($total,1);
echo "<tr><td colspan='3' align='right'>$total</td></tr>";
echo "</table></div></body></html>";
?>
