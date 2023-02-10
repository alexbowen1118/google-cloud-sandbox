<?php
extract($_REQUEST);

if(!isset($center_code)){$center_code="";}
if($center_code){$park_code=$center_code;}
if(!isset($park_code)){$park_code="";}

//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  exit;

include("../../include/iConnect.inc");// database connection parameters
$database="fuel";
mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

// FIELD NAMES are stored in $fieldArray
$sql = "SHOW COLUMNS FROM form_b";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql ");
while ($row=mysqli_fetch_assoc($result))
	{$fieldArray[]=$row['Field'];}

//echo "<pre>"; print_r($fieldArray); echo "</pre>";  //exit;

if(empty($rep))
	{
	echo "<div align='center'><form action='menu.php?form_type=form_B_summary' method='POST'>";
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

	echo "<td><input type='submit' name='submit' value='Submit'></td>";
	
	echo "<td><a href='menu.php?form_type=form_B_summary&rep=excel&year=$year'>Export</td></td>";
	
	echo "</tr></table></form>";
	//echo "<pre>"; print_r($_POST); echo "</pre>"; // exit;

	if(empty($_POST['year'])){echo "<font color='red'>Please select a Year.</font>"; exit;}
	echo "</div>";
	}
	
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
	$sql= "SELECT * from form_b 
	where year='$year' 
	$limit_dist
	order by center_code, month";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
			while($row=mysqli_fetch_assoc($result))
				{
				$ARRAY[$row['center_code']][$row['month']]=$row;
				}
//	echo "$sql";
//	echo "$sql<pre>"; print_r($ARRAY); echo "</pre>";  exit;


foreach(range(1,12) as $v){
		$month_sort_array[]=date("M",mktime(0,0,0,$v,1,2000));
	}

if($rep=="excel")
	{
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=form_B_summary.xls');
	}
//echo "<div align='center'>";
echo "<table border='1' cellpadding='5'>";
	
$skip=array("year","month","center_code");

// Table headers
	echo "<tr><th></th>
	<th colspan='7'  bgcolor='aliceblue'>Agricultural Equipment<br />(Farm Tractor, Combine)</th>
	<th colspan='7'>Heavy / Construction Equipment<br />(fulltrack, backhoe, grader, dozer)</th>
	<th colspan='7'  bgcolor='aliceblue'>Small Offroad Vehicles<br />(ATV, mule, gator, golfcart, snowmobile, riding mower)</th>
	<th colspan='7'>Utility<br />(fork lift, manlift)</th>
	<th colspan='7'  bgcolor='aliceblue'>Other<br />(push mower, power tools, blowers, generators)</th>
	<th colspan='7'>Boats</th>
	</tr>";
	
	echo "<tr><th></th>
	<th colspan='7'>Gallons</th>
	<th colspan='7'>Gallons</th>
	<th colspan='7'>Gallons</th>
	<th colspan='7'>Gallons</th>
	<th colspan='7'>Gallons</th>
	<th colspan='7'>Gallons</th>
	</tr>";
	
$row_header=array("unl_3","unl_5");
	echo "<tr><th><font color='blue'>$year</font></th>";
			foreach($fieldArray as $k1=>$v1){
				if(in_array($v1,$skip)){continue;}
				$var=explode("_",strtoupper($v1));
		//		if(in_array($v1,$row_header)){echo "<th><font color='blue'>$year</font></th>";}
				echo "<th>$var[0]</th>";
				}
	echo "</tr>";


// Data display
//echo "<pre>"; print_r($fieldArray); echo "</pre>"; // exit;
//echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;

//echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;
foreach($ARRAY as $center_code=>$month_array)
	{
	echo "<tr><th><font color='orange'>$center_code</font></th>";
	foreach($month_sort_array as $index=>$month)
		{
		$jj=0;
		echo "<tr><th>$month</th>";
		$month_val_array=$month_array[$month];
		
			foreach($month_val_array as $fld=>$value)
				{
				if(in_array($fld,$skip)){continue;}
				if($value==0.0){$val="-";}else{$val=$value;}
				echo "<td align='center'$th>$val</td>";
				@${"tot_".$fld}+=$value;
				}
		echo "</tr>";
		}
}
	
//echo "<pre>a"; print_r($tot_unl_1); echo "</pre>"; // exit;
echo "<tr><th>Total</th>";
	
	foreach($fieldArray as $k1=>$v1){
				if(in_array($v1,$skip)){continue;}
				if(empty($v1)){continue;}
				$var=${"tot_".$v1};
				if($var=="0"){$var="-";}else{$var=number_format($var,1);}
			//	if(in_array($v1,$row_header)){echo "<th>$v1 $v</th>";}
				echo "<th bgcolor='yellow'>$var</th>";
				}
echo "</tr>";
$row_header=array("unl_3","unl_5");
	echo "<tr><th><font color='blue'>$year</font></th>";
			foreach($fieldArray as $k1=>$v1){
				if(in_array($v1,$skip)){continue;}
				$var=explode("_",strtoupper($v1));
		//		if(in_array($v1,$row_header)){echo "<th><font color='blue'>$year</font></th>";}
				echo "<th>$var[0]</th>";
				}
	echo "</tr>";
// Table headers
	echo "<tr><th></th>
	<th colspan='7'  bgcolor='aliceblue'>Agricultural Equipment<br />(Farm Tractor, Combine)</th>
	<th colspan='7'>Heavy / Construction Equipment<br />(fulltrack, backhoe, grader, dozer)</th>
	<th colspan='8'  bgcolor='aliceblue'>Small Offroad Vehicles<br />(ATV, mule, gator, golfcart, snowmobile, riding mower)</th>
	<th colspan='7'>Utility<br />(fork lift, manlift)</th>
	<th colspan='8'  bgcolor='aliceblue'>Other<br />(push mower, power tools, blowers, generators)</th>
	<th colspan='7'>Boats</th>
	</tr>";
	
	echo "<tr><th></th>
	<th colspan='7'>Gallons</th>
	<th colspan='7'>Gallons</th>
	<th colspan='8'>Gallons</th>
	<th colspan='7'>Gallons</th>
	<th colspan='8'>Gallons</th>
	<th colspan='7'>Gallons</th>
	</tr>";
	
echo "</table></div></html>";
?>