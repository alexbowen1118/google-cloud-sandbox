<?php
session_start();
//echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
include("../../include/get_parkcodes_reg.php");
date_default_timezone_set('America/New_York');
ini_set('display_errors',1);
include("menu.php");
include("/opt/library/prd/WebServer/include/iConnect.inc"); // connection parameters

if(!empty($_POST))
	{
	extract($_POST);
	$skip=array("submit","vehicle_id");
	//these fields cannot have an empty string as the value
	$no_empty_strings=array("pasu_date","disu_date","chop_date","bo_date","emid");
	$array_flds=array("paint");
	$paint_array=array("OK","Scratches","Peeling","Faded");
//	echo "<pre>"; print_r($_POST); print_r($_FILES); echo "</pre>";  exit;
$database="fuel";
mysqli_select_db($connection, $database); // database 
	$clause="";
	foreach($_POST as $fld=>$val)
		{
		if(in_array($fld, $skip)){continue;}
		if(is_array($val))
			{
			$subclause="";
			foreach($paint_array as $k=>$v)
				{
				$new_fld=$fld."_".$v;
				if(in_array($v,$_POST[$fld]))
					{
					$subclause.="`".$new_fld."`='".$v."',";
					}
				else
					{$subclause.="`".$new_fld."`='',";}
				}
		//	$subclause=rtrim($subclause,",");
			$clause.=$subclause;
			continue;
			}
			if(!($val=="" && in_array($fld, $no_empty_strings))){
				$clause.="`".$fld."`='".mysqli_real_escape_string($connection,$val)."',";
			}
		}
	$clause=rtrim($clause,",");
	$sql="REPLACE pr10 set $clause";   //echo "$sql"; exit;
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
	IF(!empty($_FILES))
		{	
		   $num=count($_FILES['file_upload']['name']);
//	unlink("surplus_photos/2014/1B7HF16Y4WS715882.jpg");
//	unlink("surplus_photos/2014/tn1B7HF16Y4WS715882.jpg");
		for($i=0;$i<$num;$i++)
			{
			$temp_name=$_FILES['file_upload']['tmp_name'][$i];
			if($temp_name==""){continue;}

			$file_type = $_FILES['file_upload']['type'][$i];
			if($file_type!="image/jpeg"){echo "Photo must be a JPG"; exit;}
			
			$vin=$_POST['vin'];
			$vin_photo_id=$vin."_".$i;
			$file_name = $vin."_".$i.".jpg";
		
	
			$uploaddir = "surplus_photos"; // make sure www has r/w permissions on this folder

			//    echo "$uploaddir"; exit;
			if (!file_exists($uploaddir)) {mkdir ($uploaddir, 0777);}

			$sub_folder=$uploaddir."/".date("Y");
			if (!file_exists($sub_folder)) {mkdir ($sub_folder, 0777);}
				//echo "$sub_folder"; exit;


			$uploadfile = $sub_folder."/".$file_name;
			move_uploaded_file($temp_name,$uploadfile);// create file on server
			chmod($uploadfile,0777);
			
			$get_size=getimagesize($uploadfile); //echo "<pre>"; print_r($get_size); echo "</pre>";  exit;
			$w=$get_size[0];
			$h=$get_size[1];
			if($w>$h)
				{$rw="640"; $rh="0";}
				else
				{$rw="0"; $rh="640";}
			
			$v_640=$sub_folder."/".$file_name;
			$image = new Imagick($uploadfile); 
			$image->thumbnailImage($rw,$rh); 
			$image->writeImage($v_640);
			$image->destroy();
			
			$tn=$sub_folder."/tn.".$file_name;
			$image = new Imagick($uploadfile); 
			$image->thumbnailImage(150, 0); 
			$image->writeImage($tn);
			$image->destroy();
			
			$sql = "REPLACE pr10_photos set vin_photo_id='$vin_photo_id', photo_link='$uploadfile', vin='$vin'";
			$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
			}
		}
	if($bo_date!="0000-00-00" and !empty($bo_date))
		{
		$surp_message="Go to vehicle inventory to complete the surplus process. <a href='edit.php?table=vehicle&id=$vehicle_id'>Click</a>";
		}
		else
		{$surp_message="";}
	}
	
$database="fuel";
mysqli_select_db($connection, $database); // database 

//	echo "<pre>"; print_r($_REQUEST); echo "</pre>";  exit;
echo "
<script>
    $(function() {
        $( \"#datepicker1\" ).datepicker({ dateFormat: 'yy-mm-dd' });
        $( \"#datepicker2\" ).datepicker({ dateFormat: 'yy-mm-dd' });
        $( \"#datepicker3\" ).datepicker({ dateFormat: 'yy-mm-dd' });
        $( \"#datepicker4\" ).datepicker({ dateFormat: 'yy-mm-dd' });
    });
</script>
<style>
.ui-datepicker {
  font-size: 80%;
}
</style>";

$table="inventory_".date("Y");
extract($_REQUEST);

if(!empty($fas_num))
	{
	$sql="SELECT *
	from fuel.pr10
	where fas_num like '%$fas_num%'
	";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
	if(mysqli_num_rows($result)<1)
		{
		$sql="SELECT id,center_code, fas_num, vin, model, NULL as center, t1.make, t1.year, t1.license, t1.mileage
		from fuel.vehicle as t1
		where t1.fas_num like '%152066%''
		";}
		else
		{
		$row=mysqli_fetch_assoc($result);
		$vin=$row['vin'];
		}
	}
if(!empty($vin))
	{
		
// Get mileage since start
	$this_year=date('Y');
	$sql= "SELECT sum(t1.mileage) as miles_since_start 
	from items as t1
	left join vehicle as t2 on t1.vehicle=t2.id
	where t2.`vin`='$vin' and t1.year< '$this_year'
	group by t2.vin
	";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
//	echo "$sql<br />";
	while($row=mysqli_fetch_assoc($result))
		{
		$miles_since_start=$row['miles_since_start'];
		}
	$sql= "SELECT sum(t1.mileage) as miles_this_year 
	from items as t1
	left join vehicle as t2 on t1.vehicle=t2.id
	where t2.`vin`='$vin' and t1.year= '$this_year'
	group by t2.vin
	";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
//	echo "$sql<br />";
	while($row=mysqli_fetch_assoc($result))
		{
		$miles_this_year=$row['miles_this_year'];
		}
//	echo "$sql<br />";
		
	if(!empty($del))
		{
		unlink($del);
		$exp=explode("/",$del);
			$pn=array_pop($exp);
			$pl=implode("/",$exp)."/tn.".$pn;
		unlink($pl);
		$sql="DELETE FROM pr10_photos where photo_link='$del'";
		$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
		}
	$sql="SELECT t1.*, t2.id as vehicle_id, t2.year, t2.make, t2.mileage, t2.license, t1.location, group_concat(t3.photo_link) as photo_list
	from fuel.pr10 as t1
	left join fuel.vehicle as t2 on t1.vin=t2.vin
	left join fuel.pr10_photos as t3 on t1.vin=t3.vin
	where t1.vin = '$vin'
	group by t1.vin
	";
	}
//	echo "$sql<br><br>"; //exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
if(mysqli_num_rows($result)<1)
	{
	$sql="SELECT t2.*, NULL as center, t2.FAS_num as fas_num from fuel.vehicle as t2 where t2.vin = '$vin'";

//		echo "$sql<br><br>"; //exit;
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
	}
$row=mysqli_fetch_assoc($result);

extract($row);

if(!empty($photo_list))
	{
	$photo_array=explode(",",$photo_list); //echo "<pre>"; print_r($photo_array); echo "</pre>"; // exit;
	}

if(!empty($center_code)){$location=$center_code;}
if(!empty($location)){$center_code=$location;}

mysqli_select_db($connection, "divper"); // database 
$sql="SELECT t3.Fname, t3.Lname, t3.Nname
from position as t1
left join emplist as t2 on t2.beacon_num=t1.beacon_num
left join empinfo as t3 on t3.emid=t2.emid
where beacon_title='Law Enforcement Supervisor'
and park='$center_code'
order by current_salary desc
";
//	echo "$sql"; //exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
$row=mysqli_fetch_assoc($result);
extract($row);

$dist=$region[$center_code]; //echo "d=$dist";
$sql="SELECT park as dist, t3.Fname as disu_Fname, t3.Lname as disu_Lname, t3.Nname as disu_Nname, t3.email as disu_email
from position as t1
left join emplist as t2 on t2.beacon_num=t1.beacon_num
left join empinfo as t3 on t3.emid=t2.emid
where beacon_title='Law Enforcement Manager' and park='$dist'
";
// echo "$sql"; //exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
$row=mysqli_fetch_assoc($result);
extract($row);

$sql="SELECT t3.Fname as chop_Fname, t3.Lname as chop_Lname, t3.Nname as chop_Nname, t3.email as chop_email
from position as t1
left join emplist as t2 on t2.beacon_num=t1.beacon_num
left join empinfo as t3 on t3.emid=t2.emid
where t1.beacon_num='60033018'
";
// Deputy Dir. acting as temp CHOP
// CHOP = 60033018
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
$row=mysqli_fetch_assoc($result);
extract($row);
	
$sql="SELECT t3.Fname as bo_Fname, t3.Lname as bo_Lname, t3.Nname as bo_Nname, t3.email as bo_email
from position as t1
left join emplist as t2 on t2.beacon_num=t1.beacon_num
left join empinfo as t3 on t3.emid=t2.emid
where t1.beacon_num='60036015'
";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
$row=mysqli_fetch_assoc($result);
extract($row); 
echo "<table align='center' cellpadding='5'>";
echo "<tr><td align='center'>DIVISION OF PARKS AND RECREATION</td></tr>";
echo "<tr><td align='center'>PROPERTY ACCOUNTABILITY DROP SLIP (SURPLUS)</td></tr>";
echo "<tr><td align='center'>LOCATION CODE: <u>$center_code</u></td></tr>";
echo "<tr><td align='center'>CENTER: <u>$center</u></td></tr>
</table>";

echo "<form action='pr10.php' method='POST' enctype='multipart/form-data'>";
echo "<table cellpadding='3' border='1'><tr><th>FAS #</th><th>VIN #</th><th>MAKE</th><th>MODEL #</th><th>QTY</th><th>DESCRIPTION</th><th>CONDITION</th></tr>";

$condition_array=array("Poor","Fair","Good");
if(!isset($description)){$description="";}
echo "<tr><td>$fas_num</td><td>$vin</td><td>$make</td><td>$model</td><td>1</td>
<td><input type='text' name='description' value='$description'></td>
<td><select name='condition' required><option value=''></option>\n";
foreach($condition_array as $k=>$v)
	{
	if($v==$condition){$s="selected";}else{$s="";}
	echo "<option value='$v' $s>$v</option>\n";
	}
echo "</select></td></tr>";
echo "</table>";
echo "<p></p>";

//************ Vehicle Checklist goes here *******************
$check_array=array("keys","runs","wrecked","flooded","seats","tire","antenna","hubcaps","windows","windshield","trim","rust","paint","dents","other","checked_by","emid","pasu_date","make","mileage","site_loc");

foreach($check_array as $k=>$v)
	{
	if(!isset(${$v}))
		{${$v}="";}
	}
if(empty($pasu_date)){$pasu_date=date("Y-m-d");}
$f_mileage=number_format($mileage,0);

// ************ Checklist ***************
include("vehicle_checklist.php");


// ************ Odomenter ***************
include("vehicle_odometer.php");


if(!empty($photo_array))
	{
	for($i=0;$i<5;$i++)
		{
		if(array_key_exists($i,$photo_array))
			{
			$photo_link=$photo_array[$i];
			if(!empty($photo_link))
				{
				$exp=explode("/",$photo_link);
				$pn=array_pop($exp);
				$pl=implode("/",$exp)."/tn.".$pn;
				$display_tn[$i]="<td><a href='$photo_link' target='_blank'><img src='$pl'></a><br /><a href='pr10.php?vin=$vin&del=$photo_link' onclick=\"return confirm('Are you sure you want to delete this Photo?')\">delete</a></td>";
				}
				else
				{$display_tn[$i]="<td><font color='red'>Upload this photo.</font></td>";}	
			}
			else
			{$display_tn[$i]="<td><font color='red'>Upload this photo.</font></td>";}
		}
	}

if(!isset($display_tn[0])){$display_tn[0]="";}
if(!isset($display_tn[1])){$display_tn[1]="";}
if(!isset($display_tn[2])){$display_tn[2]="";}
if(!isset($display_tn[3])){$display_tn[3]="";}
if(!isset($display_tn[4])){$display_tn[4]="";}
echo "<hr /><table><tr><td>Photos:</td>
<td align='right'>Front <input type='file' name='file_upload[]'></td>$display_tn[0]
<td align='right'>Rear <input type='file' name='file_upload[]'></td>$display_tn[1]
<td align='right'>Interior <input type='file' name='file_upload[]'></td>$display_tn[2]
</tr>
<tr><td></td>
<td align='right'>Left Side <input type='file' name='file_upload[]'></td>$display_tn[3]
<td align='right'>Right Side <input type='file' name='file_upload[]'></td>$display_tn[4]
</tr>
</table><hr />";

echo "<table><tr><td colspan='3'>CHOOSE ONE OF THE OPTIONS BELOW:</td></tr>";

if($site_loc=="on_site"){$cko="checked";$ckd="";}
if($site_loc=="deliver"){$cko="";$ckd="checked";}
echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>Surplus On Site: <INPUT type='radio' name='site_loc' value='on_site' required $cko>&nbsp;</td>";
echo "<td>Deliver To State Surplus:&nbsp;<INPUT type='radio' name='site_loc' value='deliver' $ckd></td></tr>

<tr><td colspan='3'>(TRUCKS/TRAILERS REQUIRE  COMPLETED  MVR-180A, REGARDLESS OF AGE OR VEHICLE CONDITION)</td></tr></table>";

echo "<p></p>";
echo "<table><tr><td>(<font color='red'>MAY ONLY BE DELIVERED TO STATE SURPLUS OR REQUESTED FOR THE PARKS WAREHOUSE TO PICK UP AFTER ALL DEPARTMENTAL AND STATE SURPLUS PAPERWORK/APPROVALS HAVE BEEN COMPLETED.</font> <u>THE DPR SURPLUS COORIDNATOR WILL SEND YOU NOTIFICATION TO PROCEED AND WILL ADVISE YOU OF WHAT TO DO WITH A STATE LICENCE PLATE IF SURPLUSING A TRUCK/TRAILER</u>).</td></tr></table>";
echo "<p></p>";
echo "<table cellpadding='5'>";

if(!isset($pasu_date)){$pasu_date="0000-00-00";}
if(!isset($disu_date)){$disu_date="0000-00-00";}
if(!isset($chop_date)){$chop_date="0000-00-00";}
if(!isset($bo_date)){$bo_date="0000-00-00";}

if(!empty($Nname)){$Nname="($Nname)";}
if(!empty($bo_Nname)){$bo_Nname="($bo_Nname)";}
if($pasu_date=="0000-00-00")
	{$email_disu="";}
	else
	{
	$email_disu="<a href='mailto:$disu_email?Subject=Request%20to%20surplus%20a%20$location%20vehicle&body=/fuel/pr10.php?vin=$vin'>
	Email DISU</a>";
	}
if($disu_date=="0000-00-00")
	{$email_chop="";}
	else
	{
	$email_chop="<a href='mailto:$chop_email?cc=denise.williams.ncparks.gov&Subject=Request%20to%20surplus%20a%20$location%20vehicle&body=/fuel/pr10.php?vin=$vin'>
	Email CHOP</a>";
	}
if($chop_date=="0000-00-00" or $chop_date=="")
	{$email_bo="";}
	else
	{
	$email_bo="<a href='mailto:$bo_email?Subject=Request%20to%20surplus%20a%20$location%20vehicle&body=/fuel/pr10.php?vin=$vin'>
	Email Business Office</a>";
	}
if($bo_date=="0000-00-00"){$bo_date="";}
echo "<tr><td>REQUESTED BY:  SUPERINTENDENT</td><td>$Fname $Nname $Lname on <input  id=\"datepicker1\" type='text' name='pasu_date' value='$pasu_date' size='12'></td><td>$email_disu</td></tr>";

echo "<tr><td>APPROVED BY:  DIST. SUPERINTENDENT</td><td>$disu_Fname $disu_Lname on <input  id=\"datepicker2\" type='text' name='disu_date' value='$disu_date' size='12'><td>$email_chop</td></tr>";

echo "<tr><td>APPROVED BY: CHIEF OF OPERATIONS</td><td>$chop_Fname $chop_Lname on <input  id=\"datepicker3\" type='text' name='chop_date' value='$chop_date' size='12'><td>$email_bo</td></tr>";

echo "<tr><td>TO BE PROCESSED BY: DPR SUPRLUS COORDINATOR</td><td>$bo_Fname $bo_Nname $bo_Lname on <input  id=\"datepicker4\" type='text' name='bo_date' value='$bo_date' size='12'></td>";

if(!empty($surp_message))
	{
	echo "<td>$surp_message</td>";
	}

if(empty($email_bo))
	{echo "<td><a href='pr10_pdf.php?vin=$vin' target='_blank'>Create PR10</a></td>";}
	
echo "</tr>";
echo "<tr><td>
<input type='hidden' name='fas_num' value='$fas_num'>
<input type='hidden' name='model' value='$model'>
<input type='hidden' name='description' value='$description'>
<input type='hidden' name='location' value='$location'>
<input type='hidden' name='center' value='$center'>
<input type='hidden' name='vin' value='$vin'>
<input type='hidden' name='vehicle_id' value='$vehicle_id'>
<input type='submit' name='submit' value='Submit'>
</td></tr>";
echo "</table></form></body></html>";

 ?>