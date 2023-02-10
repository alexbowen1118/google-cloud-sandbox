<?php
extract($_REQUEST);

//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  exit;

include("../../include/iConnect.inc");// database connection parameters
$database="fuel";
mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

if(empty($rep))
	{
	echo "<div align='center'><form action='menu.php?form_type=form_A_summary' method='POST'>";
	echo "<table>";

	echo "<tr>";

	mysqli_select_db($connection,"fuel");
			$sql="SELECT distinct year
			FROM `form_b`"; 
			$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SELECT. $sql");
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

	echo "<td> Excel:<input type='checkbox' name='rep' value='x'></td>";
	echo "<td><input type='submit' name='submit' value='Submit'></td>";
	echo "</tr></table></form>";
	//echo "<pre>"; print_r($_POST); echo "</pre>"; // exit;

	if(empty($_POST['year'])){echo "<font color='red'>Please select a Year.</font>"; exit;}
	}

$fld_list=" vehicle as vehicle_id, month, items.mileage, sum(`unleaded` + `E-10` + `E-85`) as gas, sum(`diesel` + `diesel_B10` + `diesel_B20`) as diesel, sum(other_fuel) as other_fuel, sum(`virgin_oil` + `re-refined` + `synthetic` + `other_oil`) as oil";

$level=$_SESSION['fuel']['level'];
if($level==2)
	{
	$dist=$_SESSION['fuel']['select'];
	include("../../include/get_parkcodes.php");
	$dist_array=${"array".$dist};
	//echo "<pre>"; print_r($dist_array); echo "</pre>"; // exit;
	$limit_dist=" and (";
	foreach($dist_array as $k=>$v)
		{
		$limit_dist.="center_code='".$v."' OR ";
		}
	$limit_dist=rtrim($limit_dist, " OR ").")";
	}
	else
	{$limit_dist="";}
	mysqli_select_db($connection,$database);
	$sql= "SELECT t2.center_code, t2.license, t2.make, t2.model, $fld_list
	from items 
	LEFT JOIN vehicle as t2 on items.vehicle=t2.id
	where items.year='$year'  and center_code!=''
	$limit_dist
	group by items.vehicle,items.month
	order by t2.center_code, items.vehicle,FIELD(items.month,'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec')";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql<br />".mysqli_error($connection));
			while($row=mysqli_fetch_assoc($result))
				{
				$ARRAY[]=$row;
				}
//	echo "$sql";
//	echo "$sql<pre>"; print_r($ARRAY); echo "</pre>";  exit;


foreach(range(1,12) as $v){
		$month_sort_array[]=date("M",mktime(0,0,0,$v,1,2000));
	}

if(@$rep=="x")
	{
	$title=$pass_year."_mileage.xls";
	header('Content-Type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=$title");
	}	
echo "<div align='center'>";
echo "<table border='1' cellpadding='5'>";
	
$skip=array("year","month","center_code");

// Data display
//echo "<pre>"; print_r($fieldArray); echo "</pre>"; // exit;
//echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;

//"mileage",
$zero_array=array("gas","diesel","other_fuel","oil");
$total_array=array("mileage","gas","diesel","other_fuel","oil");
foreach($ARRAY as $index=>$array)
	{
	if($index==0)
		{
		echo "<tr>";
		foreach($array as $fld=>$val)
			{
			echo "<th>$fld</th>";
			}
		echo "</tr>";
		}
	echo "<tr><th>$array[center_code]</th>";
	foreach($array as $fld=>$val)
		{
		if($fld=="center_code"){continue;}
		if(in_array($fld,$total_array))
			{
			${$fld."_total"}+=$val;
			${$fld."_subtotal"}+=$val;
			}
		if(in_array($fld,$zero_array) AND $val<1){$val="-";}
		echo "<td>$val</td>";
		}
	echo "</tr>";
		if($ARRAY[$index]['vehicle_id']!=$ARRAY[$index+1]['vehicle_id'])
			{
			$mileage_sub=$mileage_subtotal;
			$gas_sub=$gas_subtotal;
			$diesel_sub=$diesel_subtotal;
			$other_fuel_sub=$other_fuel_subtotal;
			$oil_sub=$oil_subtotal;
			echo "</tr><tr><td align='right' colspan='6'>Vehicle Totals:</td>
			<td>$mileage_sub</td><td>$gas_sub</td><td>$diesel_sub</td><td>$other_fuel_sub</td><td>$oil_sub</td></tr>";
			$mileage_subtotal="";
			$gas_subtotal="";
			$diesel_subtotal="";
			$other_fuel_subtotal="";
			$oil_subtotal="";
			}
	if($ARRAY[$index]['center_code'] != $ARRAY[$index+1]['center_code'])
		{echo "<tr><th bgcolor='green' colspan='11'> </th></tr>";}
}
$mileage_total=number_format($mileage_total,0);
$gas_total=number_format($gas_total,1);
$diesel_total=number_format($diesel_total,1);
$other_fuel_total=number_format($other_fuel_total,1);
$oil_total=number_format($oil_total,0);
echo "<tr><td align='right' colspan='7'>$mileage_total</td><td>$gas_total</td><td>$diesel_total</td><td>$other_fuel_total</td><td>$oil_total</td></tr>";
echo "<tr><th align='right' colspan='6'>Totals</th><td>Mileage</td><td>Gas</td><td>Diesel</td><td>Other Fuel</td><td>Oil</td></tr>";
	
echo "</table></html>";
?>