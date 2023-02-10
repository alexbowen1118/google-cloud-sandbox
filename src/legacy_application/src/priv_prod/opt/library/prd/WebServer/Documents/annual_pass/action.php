<?php
ini_set('display_errors',1);
date_default_timezone_set('America/New_York');
$database="annual_pass";
include_once("../_base_top.php");// includes session_start();

if($level==1)
	{
	$issuing_park=$_SESSION[$database]['select'];
	}
	else
	{
	$issuing_park="";
	}
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;

include("../../include/iConnect.inc");// database connection parameters
include("../../include/get_parkcodes_dist.php");// database connection parameters

mysqli_select_db($connection,$database) or die ("Couldn't select database $database");
//************ FORM ****************
//TABLE
$TABLE="passes";
$void="";


// *********** INSERT *************
IF(!empty($_POST))
	{
	//echo "<pre>"; print_r($_POST); echo "</pre>";
	//exit;
	//$skip=array("update","Void");
	IF(!empty($_POST['Void']))
		{
		$sql="UPDATE $TABLE set void='x' where id='$update'";
		$result = @mysqli_QUERY($connection,$sql);
		
		$verb="UPDATE";
		$where="where id='$update'";
		}
	IF(!empty($_POST['UnVoid']))
		{
		$sql="UPDATE $TABLE set void='' where id='$update'";
		$result = @mysqli_QUERY($connection,$sql);
		
		$verb="UPDATE";
		$where="where id='$update'";
		}
	IF(empty($_POST['edit']))
		{
		$exp=explode("-",$pass_number);
		if(!empty($exp[1]))
			{$pass_number=$exp[1];}
		
// 		echo "$pass_number"; exit;
		$_POST['pass_number']=str_replace(",", "",$pass_number);
		$_POST['pass_number']=str_replace("F19-", "", $pass_number);
		$_POST['pass_number']=str_replace("f19-", "", $pass_number);
		$_POST['pass_number']=str_replace("19-", "", $pass_number);
		
		// This will cause pass_number beginning with a 0 to converted to an integer w/o the O
// 		if( stripos($_POST['pass_number'], "F")===FALSE)
// 			{$_POST['pass_number']=$_POST['pass_number']+0;}
		
		if(!is_numeric($_POST['pass_number']))
			{
// 			$error_array[$type_pass]=$_POST['pass_number'];
			}
		foreach($_POST as $fld=>$value)
			{
			if(in_array($fld,$skip)){continue;}
			
			if($_POST['type_pass']=="Annual" and $_POST['sub_type_pass']=="parking_SRA")
				{
				$error_array["sub_type_pass"]="An Annual pass cannot be a Sub Type Pass of parking_SRA.";
				}
			if($_POST['type_pass']=="Seasonal" and $_POST['sub_type_pass']!="parking_SRA")
				{
				$error_array["sub_type_pass"]="A Seasonal pass requires that the Sub Type Pass be parking_SRA.";
				}	
			if($_POST['type_pass']=="FOFI_FWD" and $_POST['sub_type_pass']=="parking_SRA")
				{
				$error_array["sub_type_pass"]="An FOFI_FWD pass cannot be a Sub Type Pass of parking_SRA.";
				}
					
			if($fld=="pass_number" and $_POST['type_pass']=="FOFI_FWD")
				{
				$pad_pass_number=str_pad($value, 5, '0', STR_PAD_LEFT);
				$value="F".substr($year, -2)."-".$pad_pass_number;
				}
					
			if($fld=="pass_number" and $_POST['type_pass']=="Annual")
				{
				$pad_pass_number=str_pad($value, 5, '0', STR_PAD_LEFT);
				$value=substr($year, -2)."-".$pad_pass_number;
				}
					
			if($fld=="pass_number" and $_POST['type_pass']=="Seasonal")
				{
				$pad_pass_number=str_pad($value, 5, '0', STR_PAD_LEFT);
				$value="P".substr($year, -2)."-".$pad_pass_number;
				}
				
				
			if($fld!="submit" && $fld!="update")
				{
				@$string.="$fld='$value', ";
				}
			else
				{
				if($value=="Submit")
					{
					$verb="INSERT";
					$where="";
					}
				else
					{
					$verb="UPDATE";
					$where="where id='$update'";
					}
				}
			}
		$string=trim($string,", ");
		if(empty($error_array))
			{
			$query="$verb $TABLE SET $string $where"; 
// 			echo "$query";  exit;
			$result = @mysqli_query($connection,$query);
			if(mysqli_errno($connection)==1062)
				{
				echo "<font color='red'>You have attempted to enter a duplicate Pass Number for $year.</font> Use a different number.<br />";
				$void="";
				}
			
			if($verb=="INSERT"){$update=mysqli_insert_id($connection);}
			
			if(!empty($edit)){$update=$edit;}
			$sql="SELECT * FROM  $TABLE where id='$update'";
// 			echo "$sql<br />";
			$result = @mysqli_QUERY($connection,$sql);
			if(mysqli_num_rows($result)>0)
				{
// 				echo "$sql<br />";
				$row=mysqli_fetch_assoc($result);
				extract($row); 
				}
			
			$message="<font color='brown'>$type_pass pass has been entered.</font>";
			}
			else
			{
			$message="<font color='red'>There was an error.</font>";
			$void="";
			}
		}
		else
		{
		// used for getting record to edit
		if(!empty($_POST["edit"]))
			{
			$id=$_POST['edit'];
			if(!is_numeric($id)){exit;}
			$sql="SELECT * FROM  $TABLE where id='$id'";
// 			echo "$sql";
			$result = @mysqli_QUERY($connection,$sql);
			$row=mysqli_fetch_assoc($result);
			extract($row); 
			}
		}
	
	}



// ********** Get Field Types *********

$sql="SHOW COLUMNS FROM  $TABLE";  //echo "$sql"; exit;
 $result = @mysqli_QUERY($connection,$sql);
while($row=mysqli_fetch_assoc($result))
	{
	$allFields[$row['Field']]=$row['Field'];
	$allTypes[$row['Field']]=$row['Type'];
	if(strpos($row['Type'],"decimal")>-1){
		$decimalFields[]=$row['Field'];
		$tempVar=explode(",",$row['Type']);
		$decPoint[$row['Field']]=trim($tempVar[1],")");
		}
	if(strpos($row['Type'],"char")>-1 || strpos($row['Type'],"varchar")>-1){
		$charFields[]=$row['Field'];
		$tempVar=explode("(",$row['Type']);
		$charNum[$row['Field']]=trim($tempVar[1],")");
		}
	if(strpos($row['Type'],"text")>-1){
		$textFields[]=$row['Field'];
		}
	if(strpos($row['Type'],"date")>-1){
		$charNum[$row['Field']]="16";
		}
	if(strpos($row['Type'],"int")>-1){
		$charNum[$row['Field']]="16";
		}
	}
//print_r($charNum);

// ******** Show Form here **********
// $exclude=array("id");
$exclude=array("id");
$rename=array();

$include=array_diff($allFields,$exclude);
// echo "<br />allFields<pre>";print_r($allFields); print_r($include);echo "</pre>include";
// echo "i=$issuing_park<pre>"; print_r($_POST); echo "</pre>"; // exit;

echo "<table class='table' border='1' cellpadding='5'>";
echo "<tr><th colspan='4'>Information associated with this Pass:</th></tr>";
if(!empty($error_array))
	{
	echo "$message<pre>"; 
	print_r($error_array); echo "</pre>"; // exit;
	foreach($error_array as $k=>$v)
		{
		if($k=="Individual_10000")
			{
			echo "<font color='red' size='+1'>The Pass Number for an Individual Pass must be a number and NOT greater than 10,000</font>";
			}
		if($k=="Individual_F")
			{
			echo "<font color='red' size='+1'>The Pass Number for an Individual Pass must NOT include the \"F\".</font>";
			}
		if($k=="Family")
			{
			echo "<font color='red' size='+1'>The Pass Number for a Family Pass must start with an \"F\".</font>";
			}
		if($k=="pass_number_19")
			{
			echo "<font color='red' size='+1'>The Pass Number must NOT include 19-.</font>";
			}
		}
	$include=array_keys($_POST);
	extract($_POST);
	}
// echo "i=$issuing_park<pre>include";  print_r($include); print_r($allTypes); echo "allTypes</pre>";  //exit;

echo "<form method='POST' name='pass_form'>";
if(!empty($message)){echo "<tr><td colspan='4'><strong>$message</strong></td></tr>";}

// $type_pass_array=array("Individual","Family");
$type_pass_array=array("Annual","Seasonal","FOFI_FWD");

$skip=array("submit","update","void");
if(empty($_POST))
	{$void="";}
foreach($include as $k=>$v)
	{
	if(in_array($v, $skip)){continue;}
	$type=$allTypes[$v];
	if(array_key_exists($v,$rename)){$r=$rename[$v];}else{$r=$v;}
	$r=strtoupper(str_replace("_"," ",$r));
	$value="";
// 	if(!empty($id))
// 		{$value=${$v};}
	@$value=${$v};
	if($type=="text")
		{
		$line="<tr><th align='right'>$r</th><td><textarea name='$v' cols='54' rows='5'>$value</textarea></td></tr>";
		}
		else
		{
		@$size=$charNum[$v];
		if($v=="year")
			{
			if(date("n")<11)
				{$value=date("Y");}
				else
				{$value=date("Y")+1;}
			
			}
// 		if($value==2017){$value=2018;}
		$line="<tr><th align='right'>$r</th><td><input type='text' name='$v' value=\"$value\" size='$size' required></td></tr>";
		if($v=="type_pass")
			{
			$com="<font size='-1' color='brown'>There are three types of passess -- Annual, Seasonal, and FOFI FWD. 
			<br />";
			$line="<tr><th align='right'>$r</th><td>$com";
			foreach($type_pass_array as $k1=>$v1)
				{
				$com1="";
				if($v1==@$type_pass){$s="checked";}else{$s="";}
				if($v1=="Annual"){$com1="Pass is for a year."; $fc="#ff6666";}
				if($v1=="Seasonal"){$com1="Pass is for less than a year."; $fc="orange";}
				if($v1=="FOFI_FWD"){$com1="Pass is for a year."; $fc="blue";}
				$line.="<input id='type_pass' type='radio' name='$v' value=\"$v1\" $s  onclick='myFunction_enter()';><font color='$fc'>".STRTOUPPER($v1)."</font> - $com1<br />";
				
				}
			$line.="</td>
			<td rowspan='9' align='center'>Year 2021 is for example purposes only.<br />
				<img src='NCSP_AnnPass_2021.jpg' width='45%'>
				<img src='NCSP_RecreationPass_2021.jpg' width='45%'>
				<img src='NCSP_4WDPass_2021.jpg' width='45%'>
				</td>
				</tr>";
			}	
		if($v=="sub_type_pass")
			{
			$com="If Seasonal, select this:<br />";
			$sub_type_pass_array=array("parking_SRA","");
			$line="<tr><th align='right'>$r</th><td>$com";
			foreach($sub_type_pass_array as $k1=>$v1)
				{
				if($v1==@$sub_type_pass){$s="checked";}else{$s="";}
				if($v1=="parking_SRA")
					{
					$com1="Seasonal Parking for FALA, JORD, and KELA"; $fc="green";
					}
				if($v1=="")
					{
					$com1="Blank"; $fc="green";
					}
				$line.="<input id='radios_sub_type_pass' type='radio' name='$v' value=\"$v1\" $s><font color='$fc'>$v1</font> - $com1<br />";
				}
			$line.="</td></tr>";
			}	
		if($v=="issuing_park")
			{
			array_unshift($parkCode, "ARCH");
			if($level==1)
				{
				$disabled="disabled";
				}
				else
				{
				$disabled="";
				}
			$line="<tr><th align='right'>$r</th><td><select name=$v required $disabled> <option value=\"\" selected></option>\n";
			foreach($parkCode as $k1=>$v1)
				{
				if($v1==@$issuing_park){$s="selected";}else{$s="";}
				$line.="<option value=$v1 $s>$v1</option>?n";
				}
				
			$line.="</select>";
			if($level==1)
				{
				$line.="<input type='hidden' name='issuing_park' value=\"$issuing_park\">";
				}
					
			echo "</td></tr>";
			}
		if($v=="date_issued")
			{
			$line="<tr><th align='right'>$r</th><td><input type='text' id='datepicker1' name='$v' value=\"$value\" size='$size' required></td></tr>";
			}
		if($v=="pass_number")
			{
// 			$com="<br /><font size='-2'>If this is a <font color='red'>Family and Friend</font> pass, <strong>BE SURE</strong> to include the \"F\" as the first character of the Pass Number, e.g. <font color='magenta'>F23 or F995</font>.<br />If this is an <font color='red'>Individual</font> pass, <strong>DO NOT</strong> include a \"F\" as the first character of the Pass Number, e.g. <font color='magenta'>3 or 4701</font> are correct.</font><br />
// 			NEVER include the \"20-\"</font> for either type of pass.";
			
			if(${"void"}=="x")
				{
				$line="<tr style='background-color:#ffcccc'><th align='right'>$r</th><td><input type='text' name='$v' value=\"$value\" size='$size' readonly> VOID</td></tr>";
				}
				else
				{
				if(empty(${'id'}))
					{
					$ro="";
					$re="required";
					}
					else
					{
					$ro="readonly";
					$re="";
					}
// 				$current_year=date("Y");
				$current_year_2=date("y");
				$line="<tr><th align='right'>$r</th>";
				
				$line.=" 
				<td><p id='prefix'></p><input id=\'$v\" type='text' name='$v' value=\"$value\" size='10' $ro $re>";
				$line.=" <font size='2'>Just enter the number - no padding 0's.<br />42 would become $current_year_2-00042 or 42 ==> P".$current_year_2."-00042 or 42 ==> F".$current_year_2."-00042 depending on Type Pass.</font>";
				$line.="</tr>";
				}
			}
		}
	echo "$line";
	}

if(empty($id))
	{$action="Submit";}
	else
	{$action="Update";}

if(!empty($error_array))
	{$action="Submit";}
	
echo "<tr><td colspan='2' align='center'>";
if($action=="Update")
	{
	if(!empty($edit)){$update=$edit;}else{$update="";}
	echo "<input type='hidden' name='update' value='$update'>";
	}
echo "<input type='submit' name='submit' value='$action'>
</td>";
if($action!="Submit")
	{
	if(${"void"}=="x")
		{echo "<td><input type='submit' name='UnVoid' value='UnVoid'></td>";}
		else
		{echo "<td><input type='submit' name='Void' value='Void'></td>";}
	
	}
echo "</tr>";
echo "</form></table>";


echo "</body></html>";

?>