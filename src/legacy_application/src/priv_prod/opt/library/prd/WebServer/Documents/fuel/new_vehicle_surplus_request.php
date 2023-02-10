<?php
ini_set('display_errors',1);
$database="work_comp";
include("../../include/auth.inc"); // used to authenticate users
include("../../include/iConnect.inc"); // database connection parameters
mysqli_select_db($connection,$database); // database
//echo "<pre>"; print_r($_POST); echo "</pre>";  //exit;

$query="SHOW 	COLUMNS from form19"; //echo "$query";exit;
$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row['Field'];
	}
//echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;

	
$rename1=array("park_code"=>"Park Code","employee_name"=>"Employee's Name","employee_street"=>"Employee's Street Address","employee_city"=>"Employee's City","employee_state"=>"Employee's State","employee_zip"=>"Employee's Zip","employee_home_phone"=>"Employee's Home Phone","employee_work_phone"=>"Employee's Work Phone","employee_sex"=>"Employee's Sex","employee_dob"=>"Employee's Date of Birth");

$rename2=array("location_2"=>"Location where injury occurred","county_2"=>"County","department_2"=>"Department","employer_premises_2"=>"State if employer's premises","date_of_injury_3"=>"Date of Injury","day_of_week_4"=>"Day of Week","hour_of_day_4"=>"Hour of Day","begin_disability_6"=>"Date disability began","knew_of_injury_7"=>"Date you or supervisor knew of injury","supervisor_name_8"=>"Supervisor Name");

$rename3=array("injure_occupation_9"=>"Occupation when injured","time_employed_10a"=>"Time employed by you");

$rename4=array("injury_descripton_12"=>"Describe how injury occurred and what <br />employee was doing when injured","list_all_injuries_13"=>"List all injuries and body part involved","return_to_work_14"=>"Date & hour returned to work","return_to_occupation_16"=>"At what occupation","employee_treated_physician_18"=>"Was employee treated by a physician","employee_died_19"=>"Has injured employee died");

$rename5=array("employer_name"=>"Employer name","date_completed"=>"Date completed","signed_by"=>"Signed by","official_title"=>"Official title");

$rename6=array("date_hired"=>"Date hired","began_work_date"=>"Time employee began work on <br />date of incident","name_of_facility"=>"Name of facility","facility_address"=>"Facility address and telephone","er_visit"=>"ER visit?","overnight_stay"=>"Overnight stay?","employee_sig"=>"Employee's Signature","employee_sig_date"=>"Date Employee Signed Form 19");

$rename=array_merge($rename1,$rename2,$rename3,$rename4,$rename5,$rename6);

$skip=array("wc_id","submit","timestamp","wc_approved","park_comments","hr_comments");

if(!empty($_POST))
	{
	$clause="set ";
	foreach($ARRAY as $index=>$fld)
		{
		if(in_array($fld,$skip)){continue;}
		if(!array_key_exists($fld, $_POST) OR $_POST[$fld]=="No")
			{ // track errors
			$error_fld[]=$fld;
			$error[]=$rename[$fld];
			}
			else
			{ // create update clause which will be used if no errors
			$val=$_POST[$fld];
			$val=addslashes($val);
			$clause.="$fld='$val', ";
			}
		}
	$clause=rtrim($clause,", ");
	}
//echo "<pre>"; print_r($error_fld); echo "</pre>"; // exit;
$na=array("employee_work_phone");

echo "<!DOCTYPE html>
<html>
<head>
<style>
form19
	{
	position:absolute;
	left:520px;
	top:100px;
	}
upload19
	{
	position:absolute;
	left:530px;
	top:200px;
	}
</style>
</head><body>";

if(!empty($_FILES))
	{
	$wc_id=$_POST['wc_id'];
	include("upload_file19.php");
	echo "<p>Success. You have completed the upload of Form 19.</p>";
	if(@$_POST['reupload']=="reupload")
		{
		echo "Return to the WC Review <a href='review_submission.php?wc_id=$wc_id'>Form</a>";
		}
		else
		{
		echo "<p>We will now work with the <b>WC Authorization | Physician's Report | Pharmacy Guide </b>form.</p>";
		echo "Go to next <a href='wc_authorization.php?wc_id=$wc_id'>Form</a>";
		}
	exit;
	}
	
echo "<table><tr><td><h2>Draft Worker's Comp Request Workflow (all items must be answered before proceeding.)</h2></td><td><form action='start.php'><input type='submit' name='submit' value='Home Page' style='background-color:orange; font-size:110%'></form></td></tr>
<tr><td>Every item listed below MUST be properly completed on the FORM 19. <font color='red'>If the form is not properly completed the request for <b>compensation will be rejected</b>.</font></td></tr></table>";

if(!empty($error) OR empty($_POST['submit']))
	{
	if(!empty($error))
		{
		echo "<font color='magenta'>Submission halted.</font> You failed to answer items <font color='red'>marked in red</font>.";
		}
	}
	else
	{
	// create a WC record
	if(empty($_POST['park_code']))
		{$park_code=$_SESSION['work_comp']['select'];}
		ELSE
		{$park_code=$_POST['park_code'];}
	
	$sql="INSERT INTO form19 set park_code='$park_code'"; // echo "$sql<br />";
	$result=mysqli_query($connection,$sql);
	$wc_id=mysqli_insert_id($connection);
	$sql="UPDATE form19 $clause where wc_id='$wc_id'"; //echo "$sql";
	$result=mysqli_query($connection,$sql);
	$show_form19_upload=1;
	}

if(empty($show_form19_upload) and empty($_POST['submit']))
	{
	echo "<form19>
	<img src='Form19_p1.jpg' width='800'><br />
	<img src='Form19_p2.jpg' width='800'>
	</form19>";
	}
	else
	{
//	echo "s=$show_form19_upload <pre>"; print_r($_POST); echo "</pre>"; // exit;
	echo "<upload19>You have indicated that all required Form 19 fields are properly completed.<br /><br />You can now upload the signed form that has been scanned and saved as a PDF.<br /><br />
	<form method='POST' action='new_wc_request.php' enctype='multipart/form-data'>
	<input type='hidden' name='wc_id' value='$wc_id'>
	<input type='file' name='files[]'>
	<input type=submit value='Upload File'>
	</form>
	</upload19>";
	}


echo "<form action='new_wc_request.php' method='POST'><table cellpadding='3'><tr>";
foreach($ARRAY AS $index=>$fld)
	{
	if(in_array($fld,$skip))
		{
		continue;
		}
	$fld_name=$fld;
	if(array_key_exists($fld,$rename))
		{$fld_name=$rename[$fld];}
	
	if($fld=="park_code")
		{
		echo "<td bgcolor='beige'><fieldset><legend>Employee Info</legend><table>";
		}
		
	if(@$_POST[$fld]=="Yes" or @$_POST[$fld]=="N/A")
		{
		if($_POST[$fld]=="Yes")
			{$cky="checked";}
			else
			{$ckn="checked";}
		$fc1="<font color='green'>";
		$fc2="</font>";
		}
		else
		{
		$cky="";
		$ckn="";
		if(@in_array($fld,$error_fld))
			{
			$fc1="<font color='red'>";
			$fc2="</font>";
			}
			else
			{
			$fc1="";
			$fc2="";
			}
		}
		$cky="checked";
		$ckn="";
	echo "<tr><td>$fc1$fld_name$fc2</td>";
	if($fld!="employee_name" and $fld!=="park_code")
		{
		echo "
		<td>
		<input type='radio' name='$fld' value='Yes' $cky>Answered&nbsp;&nbsp;
		<input type='radio' name='$fld' value='No'>Not answered&nbsp;&nbsp;";
		if(in_array($fld,$na))
			{echo "<input type='radio' name='$fld' value='N/A' $ckn>N/A";}
		}
		else
		{
		if($fld=="park_code"){$value=$_SESSION['work_comp']['select'];}else{$value="";}
		echo "
		<td>
		<input type='text' name='$fld' value=\"$value\" required>";
		}
		echo "</td>
		</tr>";
	
	if($fld=="employee_dob")
		{
		echo "</table></fieldset><tr>
		<td bgcolor='white'><fieldset><legend>Time and Place</legend><table>";
		}
	if($fld=="supervisor_name_8")
		{
		echo "</table></fieldset><tr>
		<td bgcolor='beige'><fieldset><legend>Person Injured</legend><table>";
		}
	if($fld=="time_employed_10a")
		{
		echo "</table></fieldset><tr>
		<td bgcolor='white'><fieldset><legend>Cause and Nature of Injury</legend><table>";
		}
	if($fld=="employee_treated_physician_18")
		{
		echo "</table></fieldset><tr>
		<td bgcolor='beige'><fieldset><legend>Fatal Cases</legend><table>";
		}
	if($fld=="employee_died_19")
		{
		echo "</table></fieldset><tr>
		<td bgcolor='white'><fieldset><legend>Employer Info</legend><table>";
		}
	if($fld=="official_title")
		{
		echo "</table></fieldset><tr>
		<td bgcolor='beige'><fieldset><legend>OSHA 301 Information</legend><table>";
		}
	if($fld=="overnight_stay")
		{
		echo "</table></fieldset><tr>
		<td bgcolor='white'><fieldset><legend>Employee Signature and Date</legend><table>";
		}
	}
echo "<tr><td colspan='2' align='center'>";

if(empty($wc_id))
	{
	echo "<input type='submit' name='submit' value='Submit'>";
	}
echo "</td></tr></table></fieldset></td>

</tr></table>";
echo "</td></tr></table></form>";

echo "</body></html>";
?>