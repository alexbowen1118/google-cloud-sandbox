<?php
session_start();
//echo "<pre>"; print_r($_SESSION); echo "</pre>";  exit;
//echo "<pre>"; print_r($_SERVER); echo "</pre>";  //exit;
extract($_REQUEST);
$level=$_SESSION['fuel']['level'];
$tempID=$_SESSION['fuel']['tempID'];

//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
//echo "<pre>"; print_r($_SERVER); echo "</pre>"; // exit;
//echo "<pre>"; print_r($_SESSION); echo "</pre>";  //exit;


include("../../include/connectROOT.inc");// database connection parameters
$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

$sql = "SELECT vehicle_number FROM motor_fleet where tempID='$tempID'";
//echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
while ($row=mysqli_fetch_assoc($result))
	{
	$vehicle_num[]=$row['vehicle_number'];
	}
	echo "<pre>"; print_r($vehicle_num); echo "</pre>"; // exit;
// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$sql = "SHOW COLUMNS FROM travel_log";//echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
while ($row=mysqli_fetch_assoc($result))
	{$fieldArray[]=$row['Field'];}

//echo "<pre>"; print_r($fieldArray); echo "</pre>";  exit;

$skip=array("id","vehicle_id","surplus");
$subName=array("park_id"=>"License Plate Number","vin"=>"VIN number <font size='-1'>(Vehicle Identification Number)</font>","center_code"=>"Park Unit","mileage"=>"Starting Mileage","make"=>"Make/Model<br /><font size='-1'>(Include as much detail as possible (Example: Ford F250 Superduty))</font>","model_year"=>"Model Year<br /><font size='-1'>Please correctly identify, as it is an important variable.</font>","engine"=>"Engine Size/Class<br /><font size='-1'>Identify no. of cylinders (V6, V8, ...) <b>AND</b> engine size (4.0L, 5.8L, ...)</font>","duty"=>"Duty","trans"=>"Transmission","drive"=>"Drive","fuel"=>"Fuel Type","emergency"=>"Emergency Vehicle?","comment"=>"Comment");

echo "<html><head><script language='JavaScript'>
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+\".location='\"+selObj.options[selObj.selectedIndex].value+\"'\");
  if (restore) selObj.selectedIndex=0;
}

</script></head>";

// Form Header
echo "<div id='add_form' align='center'><table border='1' cellpadding='5'>";

// More than one vehicle per person
	if(count($vehicle_num)>1)
	{
		echo "<tr><td><form>Vehicle Number: <select name=\"vehicle_number\" onChange=\"MM_jumpMenu('parent',this,0)\"><option selected></option>";
		foreach($vehicle_info as $num=>$array)
		{
			foreach($array as $k=>$v)
				{
					if($v==$_SESSION['travel_log']['vehicle_number'])
						{$s="selected";}else{$s="value";}
						echo "<option $s='travel_log_form?vehicle_number=$v'>$v\n";
    		  		 }
				
		}
  				 echo "</select></td></form>";
	}
	else
	{
	$vehicle_number=$vehicle_num[0];
		
	$sql = "SELECT plate_number,description,center,cost FROM motor_fleet where tempID='$tempID' and vehicle_number='$vehicle_number'";
	//echo "$sql";
	$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
		$row=mysqli_fetch_assoc($result); extract($row);
	}
	
if($_REQUEST['vehicle_number']!=="" OR $vehicle_number==$_REQUEST['vehicle_number'])
	{	
	$sql = "SELECT vehicle_number,plate_number,description,center,cost FROM motor_fleet where tempID='$tempID' and vehicle_number='$_REQUEST[vehicle_number]'";
	echo "$sql";
	$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
		while ($row=mysqli_fetch_assoc($result))
		{
			$vehicle_info[]=$row;
		}
	}
	else
	{
	exit;
	}

	echo "<pre>"; print_r($vehicle_info); echo "</pre>"; // exit;
	
echo "</tr></table>
</div>";


// Input Form
echo "<div align='center'><form name='frmTest' action=\"travel_log_edit.php\" method=\"post\">

<table border='1' cellpadding='5'>
<tr>
<td><font size='-1'>FM-12e</font></td>
<td> </td><td> </td><td> </td><td> </td><td> </td>
<th colspan='8'>STATE OF NORTH CAROLINA</th>
<td colspan='2'>Total Miles</td>
<td colspan='2'>javascript sum</td>
</tr>";

$rate=$cost;

echo "<tr>
<td colspan='2'>Plate # </td>
<td colspan='5' align='right'> $plate_number</td>
<th colspan='8'>DEPARTMENT OF ADMINISTRATION</th>
<td colspan='2'>Rate Per Mile</td>
<td colspan='2' align='right'>$rate</td></tr>";

echo "<tr>
<td colspan='2'>Vehicle # </td>
<td colspan='5' align='right'> $vehicle_number</td>
<th colspan='8'>MOTOR FLEET TRAVEL LOG</th>
<td colspan='2'>Mileage Charge</td>
<td colspan='2' align='right'>miles x rate</td></tr>";
$model=$vehicle_info[0]['description'];
echo "<tr>
<td colspan='2'>Vehicle Model </td>
<td colspan='5' align='right'> $description</td>
<th colspan='8'> </th>
<td colspan='2'>Minimum Amount</td>
<td colspan='2' align='right'>441</td></tr>";
$oil="75,000";
echo "<tr>
<td colspan='2'>Next Oil Change Due</td>
<td colspan='5' align='right'> $oil</td>
<th colspan='8'>For Permanently Assigned Vehicles</th>
<td colspan='2'>Total Amount</td>
<td colspan='2' align='right'>441</td></tr>";

echo "<tr>
<th colspan='6'>Agency</th>
<th colspan='6'>Division/Section</th>
<th>Company</th>
<th>Fund</th>
<th>RCC</th>
<th>Prog</th>
<th>Dept./ Off. No.</th></tr>";
$rcc=$vehicle_info[0]['center'];

echo "<tr>
<th colspan='6'>DENR</th>
<th colspan='6'>Parks & RECREATION</th>
<th>1601</th>
<th>1280</th>
<th>$rcc</th>
<th>0</th>
<th>4300</th></tr>";

echo "<tr>
<th colspan='6'>Individual Responsible for Vehicle</th>
<th colspan='6'>Approval of Agency Head or Supervisor</th>
<th colspan='4'>Vehicle No.</th>
<th colspan='2'>For Month of</th></tr>";

$rcc=$vehicle_info[0]['center'];
$full_name=$_SESSION['fuel']['full_name'];
$working_title=$_SESSION['fuel']['working_title'];
$month_=date('M-y');
echo "<tr>
<td colspan='6'>$full_name, $working_title</td>
<td colspan='6'> </td>
<td colspan='4' align='center'>$vn</td>
<td colspan='2' align='center'><input type='text' name='month_' value='$month_' size='8'></td></tr>";

/*
echo "<tr><td align='center' colspan='2' bgcolor='lightgreen'><input type='submit' name='add' value='Add'></td></tr>";
*/
echo "</table></form></div>";


echo "</html>";


?>