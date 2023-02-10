<?php
//These are placed outside of the webserver directory for security
$database="facilities";
// include("../../include/auth.inc"); // used to authenticate users

include("../../include/get_parkcodes_dist.php"); 
// echo "<pre>"; print_r($parkCode); echo "</pre>"; // exit;

// echo "<pre>";print_r($_POST);echo "</pre>";
// echo "<pre>";print_r($_SESSION);echo "</pre>";
$database="facilities";
$level=$_SESSION['facilities']['level'];

include("../_base_top.php");
$ignore[]="tempID";

echo "<style>
td{vertical-align: text-top;}
</style>";

mysqli_select_db($connection,$database); // database

if(@$submit_form=="Update")
	{
// 	echo "<pre>";print_r($_POST);echo "</pre>";   //exit;
	$skip=array("id","park_code","num_exist","num_need","submit_form","counter_type_need","counter_function_need","comments_need", "distance_from_VC_need", "time_to_check_need","date_u");
	$park_code=$_POST['park_code'];
	$sql="DELETE FROM counters WHERE park_code='$park_code'"; 
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	
	$sql="DELETE FROM counter_needs WHERE park_code='$park_code'"; 
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");

	for($i=1;$i<=$_POST['num_exist'];$i++)
		{
		$temp=array();
		$temp[]="park_code='$park_code'";
		foreach($_POST as $fld=>$array)
			{
			if(in_array($fld,$skip)){continue;}
// 			if(in_array($fld,$skip_need)){continue;}
			if(!empty($array[$i]))
				{
				$value=htmlspecialchars_decode($array[$i]);
				$temp[]="$fld='$value'";
				}
			}

		$clause=implode(",",$temp).", counter_num='$i'";
		$sql="INSERT INTO counters
		set $clause
		"; 
		$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
// 			echo "$sql<br />"; //exit;
		}
	$sql="SELECT * from counters where park_code='$park_code'";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	while($row=mysqli_fetch_assoc($result))
		{
		$ARRAY_values[$row['counter_num']]=$row;
		}
	
	$skip_need=array("id","park_code","num_exist","num_need","submit_form","counter_name","time_to_check","comments","lat","lon","counter_function","counter_type","distance_from_VC","method","counter_brand","see_insight_id", "multiplier");
$skip_need_fld=array("time_to_check_need","distance_from_VC_need");
	for($i=1;$i<=$_POST['num_need'];$i++)
		{
		$temp=array();
		$temp[]="park_code='$park_code'";
		foreach($_POST as $fld=>$array)
			{
			if(in_array($fld,$skip_need)){continue;}
			if(in_array($fld,$skip_need_fld) and @$_POST['counter_function_need'][$i]=="VC"){continue;}
			if(!empty($array[$i]))
				{
				$value=htmlspecialchars_decode($array[$i]);
				$temp[]="$fld='$value'";
				}
			}

		$clause=implode(",",$temp).", num_need='$i'";
		$sql="INSERT INTO counter_needs
		set $clause
		"; 
		$result = mysqli_query($connection,$sql) or die ("84 Couldn't execute query. $sql". mysqli_error($connection));
// 			echo "$sql<br />"; //exit;
		}
	$sql="SELECT * from counter_needs where park_code='$park_code'";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	while($row=mysqli_fetch_assoc($result))
		{
		$ARRAY_values_needs[$row['num_need']]=$row;
		}
// 		exit;
	}


$sql = "SHOW COLUMNS FROM counters";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
$numFlds=mysqli_num_rows($result);
while ($row=mysqli_fetch_assoc($result))
	{
	if(in_array($row['Field'],$ignore)){continue;}
	$ARRAY_fields[]=$row['Field'];
	}
echo "<form name='editForm' method='POST' ACTION='park_counters.php'>";
echo "<table><tr><td bgcolor='#c6ecc6'>
<strong>Select the park:</strong> 
<select name='park_code' onchange=\"this.form.submit()\"><option value='' selected></option>\n";
foreach($parkCode as $k=>$v)
	{
	if(@$park_code==$v){$s="selected";}else{$s="";}
	echo "<option value='$v' $s>$v</option>\n";
	}
echo "</select>
</td></tr>";

if(!empty($park_code))
	{
	$sql = "SELECT latoff, lonoff FROM dpr_system.dprunit_region where parkcode='$park_code'";//echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	$numFlds=mysqli_num_rows($result);
	$row=mysqli_fetch_assoc($result);
	extract($row);

	$sql = "SELECT * FROM counters where park_code='$park_code'";//echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	if($result)
		{
		$num_exist=0;
		while($row=mysqli_fetch_assoc($result))
			{
			$num_exist++;
			foreach($row as $fld=>$val)
				{
				$ARRAY_values[$row['counter_num']][$fld]=$val;
				}
			}
// 		echo " $sql $num_exist<pre>"; print_r($ARRAY_values); echo "</pre>";  exit;
		}
	if(empty($num_exist)){$num_exist="";}
	if(empty($num_need)){$num_need="";}
	echo "<tr><td bgcolor='#e6ccb3'><strong>Enter the number of EXISTING counters:</strong> <input type='text' name='num_exist' value=\"$num_exist\" size='3'></td></tr>";
	echo "<tr><td>";
	if(!empty($num_exist))
		{
		$tot_time=0;
		$tot_distance=0;
		$array_exist_function=array("traffic","trail","VC");
		$array_exist_type=array("pneumatic","Inductive-loop","Infra-Red","cellular");
		$array_method=array("Manual (Visit counter)","Automatic (Direct upload)");
		echo "<table border='1' cellpadding='3'>";
		for($i=1;$i<=$num_exist;$i++)
			{
			$counter_name=$ARRAY_values[$i]['counter_name'];
			$counter_function=$ARRAY_values[$i]['counter_function'];
			$counter_type=$ARRAY_values[$i]['counter_type'];
			$method=$ARRAY_values[$i]['method'];
			$time_to_check=$ARRAY_values[$i]['time_to_check'];
			$counter_brand=$ARRAY_values[$i]['counter_brand'];
			$distance_from_VC=$ARRAY_values[$i]['distance_from_VC'];
			$lat=$ARRAY_values[$i]['lat'];
			$multiplier=$ARRAY_values[$i]['multiplier'];
			$lon=$ARRAY_values[$i]['lon'];
			$comments=$ARRAY_values[$i]['comments'];
			$see_insight_id=$ARRAY_values[$i]['see_insight_id'];
			$date_update=$ARRAY_values[$i]['date_u'];
			echo "<tr>
			<td>$i</td>
			<td><strong>Counter Name:</strong><br /><input type='text' name='counter_name[$i]' value=\"$counter_name\"></td>";
			echo "<td>";
			echo "<strong>Function: </strong><br />";
			foreach($array_exist_function as $k=>$v)
				{
				if($v==$counter_function){$ck="checked";}else{$ck="";}
				echo "
			<input type='radio' name='counter_function[$i]' value=\"$v\" $ck>".ucwords($v)."<br />";
				}
			echo "</td>";
			
			echo "<td>";
			echo "<strong>Type&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong><br />";
			foreach($array_exist_type as $k=>$v)
				{
				if($v==$counter_type){$ck="checked";}else{$ck="";}
				echo "&nbsp<input type='radio' name='counter_type[$i]' value=\"$v\" $ck>".ucwords($v)."<br />";
				}
			echo "</td>";
			
			echo "<td>";
			echo "<strong>Method: </strong><br />";
			foreach($array_method as $k=>$v)
				{
				if($v==$method){$ck="checked";}else{$ck="";}
				echo "
			<input type='radio' name='method[$i]' value=\"$v\" $ck>".ucwords($v)."<br />";
				}
			echo "</td>";
			
			echo "<td><font color='red'>Only needed if NOT a See Insights counter.</font> <strong>Time needed to read daily:</strong><br />
			<font size='-2'>in half-hour increment, e.g., 0.5 or 1 or 1.5</font>
			<input type='text' name='time_to_check[$i]' value=\"$time_to_check\" size='5' ><br />
			<strong>Distance from Visitor Center(VC) in Miles:</strong>
			<input type='text' name='distance_from_VC[$i]' value=\"$distance_from_VC\" size='5' >
			</td>";
			
			if(!empty($time_to_check))
				{
				$tot_time+=$time_to_check;
				}
			if(!empty($distance_from_VC))
				{
				$tot_distance+=$distance_from_VC;
				}
			
			echo "<td><strong>Counter Brand<br />(if known):</strong><br />
			<input type='text' name='counter_brand[$i]' value=\"$counter_brand\" size='13'><br />If brand is <b>See Insights</b> please enter its ID, e.g. 5cb8c6d60ff4c30b98b89c9a
			<input type='text' name='see_insight_id[$i]' value=\"$see_insight_id\" size='25'></td>";
			
			echo "<td><strong>Multipler:</strong><br />
			<font size='-2'>Describe complete methodology used for all multipliers throughout the year.</font><br />
			<textarea name='multiplier[$i]' rows='4' cols='23'>$multiplier</textarea><br /><font size='-2'>Contact your DISU if you have a question of which multiplier to use.</font></td>";
			
			echo "<td><strong>Comments:</strong><br />
			<textarea name='comments[$i]' rows='4' cols='23'>$comments</textarea></td>";
			
			echo "<td><strong>Latitude:</strong><br />
			<input type='text' name='lat[$i]' value=\"$lat\" size='8'></td>";
			
			echo "<td><strong>Longitude:</strong><br />
			<input type='text' name='lon[$i]' value=\"$lon\" size='8'></td>";
			
			echo "<td><strong>Date Updated:</strong><br />
			<input type='text' name='date_u[$i]' value=\"$date_update\" size='8' readonly></td>";
			
			if($level>4)
				{
				// if($lat<1){$lat=$latoff; $lon=$lonoff;}
// 				echo "<td><input type='button' value='Map It!' onclick=\"return popitLatLon('lat_long_multi.php?pp_code=$park_code&park=$park_code&lat=$lat&lon=$lon')\"></td>";
				}
			
			echo "</tr>";
			}
		if(!empty($tot_time))
			{
			echo "<tr><td colspan='5' align='right'>
			$tot_time hours per day<br />
			$tot_distance miles per day<br />
			</td></tr>";
			}
		echo "</table>";
		echo "</td></tr>";
		}
	
$tot_time_need=0;
$tot_distance_need=0;
	$sql = "SELECT * FROM counter_needs where park_code='$park_code'";//echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
		$num_need=0;
	if($result)
		{
		while($row=mysqli_fetch_assoc($result))
			{
				$num_need++;
			foreach($row as $fld=>$val)
				{
				$ARRAY_values_needs[$row['num_need']][$fld]=$val;
				}
			}
// 		echo "$num_need<pre>"; print_r($ARRAY_values_needs); echo "</pre>"; // exit;
		}
		$array_need_function=array("traffic","trail","VC");
		$array_need_type=array("pneumatic","IR","celluar");
	
			
	echo "<tr><td bgcolor='#e6ccb3'><strong>Enter the number of ADDITIONAL counters (IF needed): </strong><input type='text' name='num_need' value=\"$num_need\" size='3'></td></tr>";
	echo "<tr><td>";
	echo "<table border='1' cellpadding='3'>";
	if($num_need>0)
		{
	for($i=1;$i<=$num_need;$i++)
		{
			$time_to_check_need=$ARRAY_values_needs[$i]['time_to_check_need'];
			$distance_from_VC_need=$ARRAY_values_needs[$i]['distance_from_VC_need'];
			$counter_function_need=$ARRAY_values_needs[$i]['counter_function_need'];
			$counter_type_need=$ARRAY_values_needs[$i]['counter_type_need'];
		$comments_need=$ARRAY_values_needs[$i]['comments_need'];
		echo "<tr>
		<td>";
		echo "<strong>Function: </strong><br />";
		foreach($array_need_function as $k=>$v)
			{
			if($v==$counter_function_need){$ck="checked";}else{$ck="";}
			echo "
		<input type='radio' name='counter_function_need[$i]' value=\"$v\" $ck>".ucwords($v)."<br />";
			}
		echo "</td>";
			
		echo "<td>";
		
		echo "<strong>Type: </strong><br />";
		foreach($array_need_type as $k=>$v)
			{
			if($v==$counter_type_need){$ck="checked";}else{$ck="";}
			echo "
		<input type='radio' name='counter_type_need[$i]' value=\"$v\" $ck>".ucwords($v)."<br />";
			}
		echo "</td>";
		
		if($counter_function_need!="VC")
			{
			echo "<td><strong>Time needed to read daily:</strong><br />
			<font size='-2'>in half-hour increment, e.g., 0.5 or 1 or 1.5</font>
			<input type='text' name='time_to_check_need[$i]' value=\"$time_to_check_need\" size='5'><br />
			<strong>Distance from VC in Miles:</strong>
			<input type='text' name='distance_from_VC_need[$i]' value=\"$distance_from_VC_need\" size='5'>
			</td>";
			}
			else
			{echo "<td></td>";}
			
			if(!empty($time_to_check_need))
				{
				$tot_time_need+=$time_to_check_need;
				}
			if(!empty($distance_from_VC_need))
				{
				$tot_distance_need+=$distance_from_VC_need;
				}
			
			echo "<td><strong>Comments:</strong><br />
			<textarea name='comments_need[$i]' cols='55' rows='3'>$comments_need</textarea></td>";
		echo "</tr>";
		}
		}
	echo "</table>";
	echo "</td></tr>";

echo "<tr><td colspan='5' align='center'>
<input type='submit' name='submit_form' value=\"Update\">
</td></tr></table></form>";
	}




?>