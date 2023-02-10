<?php
include("../../include/get_parkcodes_i.php");
date_default_timezone_set('America/New_York');
ini_set('display_errors',1);

include("/opt/library/prd/WebServer/include/iConnect.inc"); // connection parameters
$database="fixed_assets";
mysqli_select_db($connection, $database); // database 

if(isset($_GET['id']))
	{
	$sql="SELECT t1.*, t2.*
	from change_location as t1
	left join change_location_asset as t2 on t1.id=t2.id
	where t1.id=$_GET[id]";   //echo "$sql"; //exit;
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
	while($row=mysqli_fetch_assoc($result))
		{
		$asset_array[]=$row['asset_number'];
		$ARRAY[]=$row;
		}
	$ARRAY[0]['asset_number']=$asset_array;
	$_POST=$ARRAY[0];
//	echo "<pre>"; print_r($ARRAY);  echo "</pre>";  exit;
	}

if(!empty($_POST))
	{
	include("menu.php");
	extract($_POST);
//	echo "<pre>"; print_r($_POST); echo "</pre>";  //exit;
	IF(!empty($id))
	{
	@$clause=", id='$id', from_division='$from_division', from_date='$from_date', from_pasu_name='$from_pasu_name',to_division='$to_division',to_date='$to_date', to_pasu_name='$to_pasu_name'";
	}
	else{$clause="";}
	$sql="REPLACE change_location 
	set `denr_code_from`='$denr_code_from',`denr_code_to`='$denr_code_to' $clause";   //echo "$sql"; exit;
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
	$id=mysqli_insert_id($connection);
	
	if(!empty($_POST['asset_number']))
		{
		$sql = "DELETE FROM change_location_asset WHERE id='$id'";
		$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_error($connection));

		$cull_post=array_unique($_POST['asset_number']); // prevent duplicate entry
		$asset_number=$cull_post;
		$table="inventory_".date("Y");
		foreach($cull_post as $k=>$v)
			{
			if(empty($v)){continue;}
			//standard_description
			$sql = "SELECT asset_description as description, serial_number 
			from $table 
			where asset_num='$v'";  echo "$sql<br />";
			$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_error($connection));
			$row=mysqli_fetch_assoc($result);
			$desc=mysqli_real_escape_string($connection,$row['description']);
			$sn=mysqli_real_escape_string($connection,$row['serial_number']);
			$sql = "INSERT INTO change_location_asset set asset_number='$v', description='$desc', serial_number='$sn', id='$id'";  //echo "$sql<br />";
			$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_error($connection));
			}
		}
	}



$sql = "SELECT * FROM fixed_assets.change_location where bo_date!=''";
$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
while($row=mysqli_fetch_assoc($result))
	{
	$completed[]=$row;
	}	

if(empty($_SESSION['fuel']['accessPark']))
	{$ck_park_array[]=$_SESSION['fuel']['select'];}
	else
	{$ck_park_array=explode(",",$_SESSION['fuel']['accessPark']);
	}

//echo "<pre>"; print_r($ck_park_array); echo "</pre>"; // exit;
$sql = "SELECT t1.id as bnc, t1.denr_code_from as code_from, t1.denr_code_to as code_to, t2.asset_number as an, t2.description as 'desc', t2.serial_number as sn
FROM fixed_assets.change_location as t1
left join fixed_assets.change_location_asset as t2 on t1.id=t2.id
where bo_date='' and from_date!=''";
$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
while($row=mysqli_fetch_assoc($result))
	{
	$test_from=substr($row['code_from'],-4);
	$test_to=substr($row['code_to'],-4);
	if($level>2 or in_array($test_from,$ck_park_array)){$bo_not_completed[]=$row;}
	if($level>2 or in_array($test_to,$ck_park_array)){$bo_not_completed[]=$row;}
	}
//echo "$sql<pre>"; print_r($bo_not_completed); echo "</pre>"; // exit;
if(!empty($bo_not_completed))
	{
	echo "<table>";
	foreach($bo_not_completed as $k=>$array)
		{
		extract($array);
	//	echo "<tr><td>$bnc $code_from to $code_to $an $desc $sn</td></tr>";
		}
	echo "</table>";
	}
	
$sql = "SELECT * FROM fixed_assets.all_codes";
$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
while($row=mysqli_fetch_assoc($result))
	{
	@$denr_code_list.="\"".$row['dpr_codes']."\",";
	}
 mysqli_free_result($result);
 
 if(!empty($id))
 	{
	$sql = "SELECT * FROM fixed_assets.change_location where id='$id'";
//	echo "$sql";
	$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
	$row=mysqli_fetch_assoc($result);
		$location=$row['denr_code_from'];
 	}
 if(!isset($denr_code_from)){$denr_code_from="";}
 if(!isset($denr_code_to)){$denr_code_to="";}
echo "<table align='center' cellpadding='5'>";
echo "<tr><td align='center'>DEPARTMENT OF ENVIRONMENT & NATURAL RESOURCES</td></tr>
</table>";


if(!empty($from_date)){$ro="readonly";}else{$ro="";}
echo "<form method='POST' action='change_location.php'>";
echo "<table>";
echo "<tr><td colspan='2'><font color='brown' size='+1'>Change of Location Form</font></td></tr>";
echo "<tr><td><b>FROM</b> DENR Code:</td><td><input type='text' id='denr_code_from' name='denr_code_from' value='$denr_code_from' required $ro> FAS Location</td></tr>";

echo "<tr><td><b>TO</b> DENR Code:</td><td><input type='text' id='denr_code_to' name='denr_code_to' value='$denr_code_to' required $ro> FAS Location</td></tr></table>";
	echo "
	<script>
		$(function()
			{
			$( \"#denr_code_from\" ).autocomplete({
			source: [ $denr_code_list ]
				});
			});
		$(function()
			{
			$( \"#denr_code_to\" ).autocomplete({
			source: [ $denr_code_list ]
				});
			});
		</script>";

echo "Is this still needed now that we are in DNCR? Contact Tom Howard."; exit;

if(!empty($id))
	{
	$table="inventory_".date("Y");
	$sql = "SELECT asset_num as asset_number
	FROM $table 
	where location='$location'";
	$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_error($connection));
	while($row=mysqli_fetch_assoc($result))
		{
		@$asset_number_list.="\"".$row['asset_number']."\",";
		}
	if(empty($asset_number_list))
		{
		echo "You will have to download and manually complete the DENR OC36 form since we do not have access to an on-line inventory of the originating DENR Code. You will also need to have the form signed by an employee of the originating Division."; 
		
		echo "<br /><br /><a href='DENR_OC36.pdf'>Non-DPR Transfer</a>";
		
		$sql = "DELETE FROM change_location WHERE id='$id'";
		$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_error($connection));
		exit;
		}
	
	echo "<table align='center'><tr><th>Asset Number</th><th>Description</th><th>Serial Number</th></tr>";
	if(!empty($asset_number))
		{
		$num_asset=count($asset_number);
		foreach($asset_number as $k=>$v)
			{
			if(empty($from_date))
				{$ro="";}else{$ro="readonly";}
			echo "<tr>";
			$sql = "SELECT asset_description as description, serial_number
			FROM $table 
			where asset_num='$v'"; //echo $sql;
			$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_error($connection));
			while($row=mysqli_fetch_assoc($result))
				{
				extract($row);
				$var_id="asset_number".$k;
				echo "<td><input type='text' id='$var_id' name='asset_number[]' value='$v' size='10' $ro></td>";
				echo "
				<script>
					$(function()
						{
						$( \"#$var_id\" ).autocomplete({
						source: [ $asset_number_list ]
							});
						});
					</script>";
							echo "<td>$description</td>";
							echo "<td>$serial_number</td>";
				}
			echo "</tr>";
			
			}
		
		if(empty($from_date))
			{
		echo "<tr><td><input type='text' id='asset_number' name='asset_number[]' size='10'></td><td>";
		if(!empty($id)){echo "<input type='hidden' name='id' value='$id'>";}
		echo "<input type='submit' name='submit' value='Update'>
		</td></tr>";}
		}
		else
		{
		echo "<tr><td><input type='text' id='asset_number' name='asset_number[]' size='10'></td><td>";
if(!empty($id)){echo "<input type='hidden' name='id' value='$id'>";}
echo "<input type='submit' name='submit' value='Update'>
</td></tr>";
		}
	 mysqli_free_result($result);
	 
	echo "
	<script>
		$(function()
			{
			$( \"#asset_number\" ).autocomplete({
			source: [ $asset_number_list ]
				});
			});
		</script>";
	echo "</table>";
	}

if(!empty($num_asset))
	{
	$top=400+($num_asset*10)."px";
	$top_1=650+($num_asset*10)."px";
	}
	else
	{
	$top="";
	$top_1="270px";
	}
echo "<style>
from_source
	{
	position:absolute;
	left:70px;
	top:$top;
	}
to_destination
	{
	position:absolute;
	left:770px;
	top:$top;
	}
submit_form
	{
	position:absolute;
	left:500px;
	top:$top_1;
	}
</style>";
	echo "<from_source><table>";
	
if(!empty($asset_number))
	{
	$from_location=substr($denr_code_from, 3, 4);
	$to_location=substr($denr_code_to, 3, 4);
	
	if($level<3)
		{
	//	$where=" and t1.tempID='$tempID'";
		$where=" and t2.posTitle='Park Superintendent'";
		}
		else
		{
		$where=" and t2.posTitle='Park Superintendent'";
		}
	$sql="SELECT t3.link as from_sig, concat(t4.Fname, ' ', t4.Mname, ' ', t4.Lname) as from_pasu_name, t4.email as from_email 
	from divper.emplist as t1 
	left join divper.position as t2 on t1.beacon_num=t2.beacon_num 
	left join divper.empinfo as t4 on t1.tempID=t4.tempID 
	left join photos.signature as t3 on t4.tempID=t3.personID 
	where t1.currPark = '$from_location' 
	$where
		";
//		echo "$sql<br /><br />"; exit;
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
	$row=mysqli_fetch_assoc($result);
	extract($row);

	$sql="SELECT t3.link as to_sig, concat(t4.Fname, ' ', t4.Mname, ' ', t4.Lname) as to_pasu_name, t4.email as to_email
	from divper.emplist as t1 
	left join divper.position as t2 on t1.beacon_num=t2.beacon_num 
	left join divper.empinfo as t4 on t1.tempID=t4.tempID 
	left join photos.signature as t3 on t4.tempID=t3.personID 
	where t1.currPark = '$to_location' 
	and t2.posTitle='Park Superintendent'
		";
	//	echo "$sql"; //exit;
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
	$row=mysqli_fetch_assoc($result);
	if(mysqli_num_rows($result)>0)
		{extract($row);}
		else
		{
		$to_sig=$from_sig;
		$to_pasu_name=$from_pasu_name;
		$to_email=$from_email;
		}
	
	$sql="SELECT  concat(t4.Fname, ' ', t4.Mname, ' ', t4.Lname) as bo_name, t4.email as bo_email
	from divper.emplist as t1 
	left join divper.position as t2 on t1.beacon_num=t2.beacon_num 
	left join divper.empinfo as t4 on t1.tempID=t4.tempID 
	where t2.beacon_num='60036015'
		";
	//	echo "$sql"; exit;
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
	$row=mysqli_fetch_assoc($result);
	extract($row);

	echo "<tr><td><u>From: <b>$from_location</b></u></td></tr>";
	echo "<tr><td>Division:</td><td><input type='text' name='from_division' value='Div. of Parks & Recreation' size='33'></td></tr>";
		if(!empty($from_date))
			{
			echo "<input type='hidden' name='from_date' value='$from_date'>";
			if($level<3 and !in_array($from_location,$ck_park_array))
				{$ro="readonly";}else{$ro="";}
			}
			else
			{$from_date="";}
	echo "<tr><td>Date:</td><td><input type='text' name='from_date' value='$from_date' $ro></td></tr>";
	if(!empty($from_date))
		{
		echo "<tr><td>Signature:</td><td>$from_pasu_name <br /><img src='/photos/$from_sig' height='42'></td></tr>";
		$link="/fuel/change_location.php?id=$id";
		echo "<tr><td>Email $to_location:</td><td>$to_pasu_name <br /><a href='mailto:$to_email?subject=FAS Change of Location - $from_location to $to_location&body=$link'>$to_email</a></td></tr>";
		
	echo "</table></from_source>";
	
	
	if(!empty($to_date))
		{echo "<input type='hidden' name='to_date' value='$to_date'>";
		}
	
	$ro="readonly";
	if(($level<3 and in_array($to_location,$ck_park_array)) or $level>2)
		{
			$ro="";
	echo "<to_destination><table>";
	echo "<tr><td><u>To: <b>$to_location</b></u></td></tr>";
	echo "<tr><td>Division:</td><td><input type='text' name='to_division' value='Div. of Parks & Recreation' size='33'></td></tr>";
	if(empty($to_date)){$to_date="";}
	echo "<tr><td>Date:</td><td><input type='text' name='to_date' value='$to_date' $ro><br /><font color='red'>by submitting a date you are verifying recent of listed item(s).</font></td></tr>";
		if(!empty($to_date))
			{
		echo "<tr><td>Signature:</td><td>$to_pasu_name <br /><img src='/photos/$to_sig' height='42'></td></tr>";
		echo "<tr><td>Email<br />Budget Office:</td><td>$bo_name <br /><a href='mailto:$bo_email?subject=FAS Change of Location - $from_location to $to_location&body=$link''>$bo_email</a></td></tr>";}
	echo "</table></to_destination>";}
		}
	}
	echo "<submit_form><table><tr><td>";
	if(!empty($id))
		{
		echo "<input type='hidden' name='id' value='$id'>";
		if(!empty($from_pasu_name))
			{echo "<input type='hidden' name='from_pasu_name' value='$from_pasu_name'>";}
		if(!empty($to_pasu_name))
			{echo "<input type='hidden' name='to_pasu_name' value='$to_pasu_name'>";}
	
		}
	echo "<input type='submit' name='submit' value='Submit'>
	</td></tr>";
	echo "</table></submit_form>";

echo "</form>";
echo "</body></html>";

 ?>