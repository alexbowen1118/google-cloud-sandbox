<?php

//   ******* after a POST processing passes to menu.php ****************
extract($_REQUEST);
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  exit;
//echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;

include("../../include/iConnect.inc");// database connection parameters
include("../../include/get_parkcodes_reg.php");// database connection parameters

$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
       
//echo "<pre>"; print_r($_SESSION); echo "</pre>";  //exit;

//echo "<pre>"; print_r($_POST); echo "</pre>";  exit;
if(!empty($_POST))
	{
	session_start();
	$level=$_SESSION['fuel']['level'];
	foreach($_POST['comment'] as $vin=>$value)
		{
		$value=addslashes($value);
		$status=$_POST['status'][$vin];
		$park_code=$_POST['park_code'];
		$sql="REPLACE pasu_decide set comment='$value', status='$status', vin='$vin', park_code='$park_code'";
		$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql ");
		}
	header("Location: menu.php?form_type=pasu_decide&park_code=$park_code");
	exit;
	}


$sql = "SELECT vin,location
FROM pr10 
where 1 and bo_date='0000-00-00'
	";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql ");
while ($row=mysqli_fetch_assoc($result))
	{
	$in_process[$row['vin']]=$row['location'];
	}
//echo "<pre>"; print_r($in_process); echo "</pre>"; // exit;
$where="where 1 ";
if($level==1)
	{
	if(!empty($_SESSION['fuel']['accessPark']))
		{
		$ex=explode(",",$_SESSION['fuel']['accessPark']);
		echo "Select park: <select name='park_code' onChange=\"MM_jumpMenu('parent',this,0)\"><option selected=''></option>\n";
	foreach($ex as $k=>$v)
		{
		echo "<option value='menu.php?form_type=pasu_decide&park_code=$v'>$v</option>\n";
		}
	echo "</select>";
		}
	if(empty($_GET['park_code']))
		{$park_code=$_SESSION['fuel']['select'];}
		else
		{
		if(!in_array($_GET['park_code'],$ex)){exit;}
		}
	
	}
if($level==2)
	{
	$var_dist=$_SESSION['fuel']['select'];
	$dist_array=${"array".$var_dist};
	echo "Select park: <select name='park_code' onChange=\"MM_jumpMenu('parent',this,0)\"><option selected=''></option>\n";
	foreach($dist_array as $k=>$v)
		{
		echo "<option value='menu.php?form_type=pasu_decide&park_code=$v'>$v</option>\n";
		}
	echo "</select>";
	}
if($level>2)
	{
	$sql="SELECT distinct center_code from vehicle";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql");
	while($row=mysqli_fetch_assoc($result)){$v_array[]=$row['center_code'];}
	
	echo "Select park: <select name='park_code' onChange=\"MM_jumpMenu('parent',this,0)\"><option selected=''></option>\n";
	foreach($v_array as $k=>$v)
		{
		echo "<option value='menu.php?form_type=pasu_decide&park_code=$v'>$v</option>\n";
		}
	echo "</select>";
	}

if(empty($park_code))
	{
	exit;
	}
	else
	{
	$where.=" and center_code='$park_code'";
	}

$sql = "SELECT t2.status, t1.center_code, t1.license, t1.vehicle_id, t1.vin, t1.license, t1.park_id, t1.FAS_num, t1.year, t1.make, t1.model, (sum(t3.mileage) + (t1.mileage)) as total_mileage, t2.comment
FROM vehicle as t1 
left join pasu_decide as t2 on t1.vin=t2.vin 
left join items as t3 on t1.id=t3.vehicle
$where
group by t1.id
order by total_mileage desc
	";
// 	echo "$sql<BR /><br />";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql ");
while ($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	}
$c=count($ARRAY);
echo "<form name='input' method='POST' action='pasu_decide.php'>";

echo "<table border='1' cellpadding='3'><tr><td colspan='12'>$c vehicles</td></tr>";
foreach($ARRAY AS $index=>$array)
	{
	if($index==0)
		{
		echo "<tr>";
		foreach($ARRAY[0] AS $fld=>$value)
			{
			echo "<th>$fld</th>";
			}
		echo "</tr>";
		}
	
	echo "<tr>";
	if(array_key_exists ("status", $array))
		{
		$fld_status="status[$array[vin]]";
		if($array['status']=="" OR $array['status']=="Keep")
			{$ckk="checked"; $cks=""; $color="green";}
			else
			{$ckk=""; $cks="checked"; $color="red";}
		echo "<td><input type='radio' name='$fld_status' value='Keep' $ckk><font color='$color'>Keep</font><br />
		<input type='radio' name='$fld_status' value='Surplus' $cks><font color='$color'>Surplus</font></td>";
		}
	foreach($array as $fld=>$value)
		{
		if($fld=="status"){continue;}
		
		$fld_comment="comment[$array[vin]]";
		if($fld=="comment")
			{
			$value="<textarea name='$fld_comment' cols='45' rows='3'>$value</textarea>";
			}
		
		if($fld=="center_code" and $array['status']=="Surplus")
			{
			if(in_array($value, $in_process))
				{
			$value=$value."<br /><a href='start_vehicle_surplus.php?vin=$array[vin]&park_code=$value'>In Process</a>";}
				else
				{
			$value=$value."<br /><a href='start_vehicle_surplus.php?park_code=$park_code&vin=$array[vin]'>Start Surplus Process</a>";}
			}
			
		if($fld=="total_mileage")
			{
			$value>125000?$value="<font color='magenta'>".number_format($value,0)."</font>" : $value=number_format($value,0);
			}
		echo "<td>$value</td>";
		}
	echo "</tr>";
	}

if($c>0)
	{
	echo "<tr><td colspan='12' align='center'>
	<input type='hidden' name='park_code' value='$park_code'>
	<input type='submit' name='submit' value='Update'>
	</td></tr>";
	}
echo "</table></form>";

echo "</html>";


?>