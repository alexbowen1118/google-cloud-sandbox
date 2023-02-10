<?php
ini_set('display_errors',1);
session_start();
$dbTable="vol_stats";
$fileMenu="../menu.php";

extract($_REQUEST);
$database="park_use";
include("../../../include/iConnect.inc");// database connection parameters
include("../../../include/get_parkcodes_reg.php");// database connection parameter

$parkCode[]="MTST";
$parkCodeName['MTST']="Mountains To Sea Trail";
sort($parkCode);

$level=$_SESSION['attend']['level'];
if($level<1){exit;}

mysqli_select_db($connection,$database);

if(!empty($_POST))
	{
//	echo "<pre>"; print_r($_POST); echo "</pre>"; // exit;

		$sql="UPDATE vol_awards set hr_40='', hr_100='', hr_200='', hr_300='' where park_code='$_POST[parkcode]'";
			$result = mysqli_query($connection,$sql) or die ("<br>$sql. ".mysqli_error($connection));
		
	foreach($_POST as $name=>$array)
		{
		if(!is_array($array)){continue;}
			$exp=explode("*",$name);
			$Lname=$exp[0];
			$Fname=$exp[1];
			
		$result = mysqli_query($connection,$sql) or die ("<br>$sql. ".mysqli_error($connection));
		if(!is_array($array)){continue;}
		$clause="";
		foreach($array as $fld=>$value)
			{
			$clause.=$fld."='x', ";
			}
		$clause=rtrim($clause,", ");
		$sql="REPLACE vol_awards set park_code='$_POST[parkcode]', Lname='$Lname', Fname='$Fname', $clause";
			$result = mysqli_query($connection,$sql) or die ("<br>$sql. ".mysqli_error($connection));
		}
	}


$sql="SHOW COLUMNS FROM vol_stats from park_use";
$result = mysqli_query($connection,$sql);
while($array=mysqli_fetch_array($result))
	{
	$keyName[]=$array[0];
	}

$sql="SELECT CONCAT(Lname, '*', Fname) as name, hr_40, hr_100, hr_200, hr_300
from vol_awards
where park_code='$parkcode'";
$result = mysqli_query($connection,$sql);
while($row=mysqli_fetch_assoc($result))
	{
	$award_name[$row['name']]=$row;
	}
//echo "<pre>"; print_r($award_name); echo "</pre>"; // exit;

if($parkcode){$where="and t1.park='$parkcode'";}

//if(@$Lname){$where="and t1.Lname='$Lname' and t1.Fname='$Fname'";}

$sql="SELECT t1.Lname,t1.Fname, sum(admin_hours+camp_host_hours+trail_hours+ie_hours+main_hours+research_hours+res_man_hours+other_hours) as tot_hours
FROM vol_stats as t1
where 1 $where
group by t1.Lname,t1.Fname
having tot_hours>40
order by tot_hours desc,t1.Lname,t1.Fname";
//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("<br>$sql. ".mysqli_error($connection));


include("$fileMenu");
echo "<div align='center'>
<form method='POST'>
<table border='1' cellpadding='5'>";
echo "<tr><th colspan='7' align='center'>Division of Parks and Recreation</th></tr>";

if($parkcode){echo "<tr><th colspan='3'>$parkcode</th><th colspan='4'>Award Given</th></tr>";}

while ($row=mysqli_fetch_assoc($result)){
	$ARRAY[]=$row;
}// end while

// ******* Headers ********	
		echo "<tr>";
	foreach($ARRAY[0] as $fld=>$val){
		echo "<th>$fld</th>";
		}
		echo "<th>hr_40</th><th>hr_100</th><th>hr_200</th><th>hr_300</th></tr>";
	
	
	foreach($ARRAY as $i=>$array){
		echo "<tr>";
			foreach($array as $fld=>$value){
				$color='';
				if($value>=40){$color="brown";}
				if($value>=100){$color="orange";}
				if($value>=200){$color="green";}
				if($value>=300){$color="red";}
			
				$full_name=$array['Lname']."*".$array['Fname']; 
					
				if($fld=="Lname")
					{
					$Fname=$array['Fname'];
					$new=str_replace("&","*",$Fname);
					$value="<a href='r_vol_hours.php?parkcode=$parkcode&Lname=$array[Lname]&Fname=$new' target='_blank'>$value</a>";
					}
					
				echo "<td><font color='$color'>$value</font></td>";
				
					}
				
		
				if(@$award_name[$full_name]['hr_40']=="x"){$ck="checked";}else{$ck="";}
		
				$fld=$full_name."[hr_40]";
				$value="<input type='checkbox' name='$fld' value='x' $ck>";	
				echo "<td>$value</td>";
				
				if(@$award_name[$full_name]['hr_100']=="x"){$ck="checked";}else{$ck="";}
		
				$fld=$full_name."[hr_100]";
				$value="<input type='checkbox' name='$fld' value='x' $ck>";	
				echo "<td>$value</td>";
				
				if(@$award_name[$full_name]['hr_200']=="x"){$ck="checked";}else{$ck="";}
			
				$fld=$full_name."[hr_200]";
				$value="<input type='checkbox' name='$fld' value='x' $ck>";	
				echo "<td>$value</td>";
				
				if(@$award_name[$full_name]['hr_300']=="x"){$ck="checked";}else{$ck="";}
			
				$fld=$full_name."[hr_300]";
				$value="<input type='checkbox' name='$fld' value='x' $ck>";	
				echo "<td>$value</td>";
		echo "</tr>";
		}
	ECHO "<tr><td colspan='7' align='right'>
	<input type='hidden' name='parkcode' value='$parkcode'>
	<input type='submit' name='submit' value='Update'>
	</td></tr>";
	echo "</table></form>";

echo "</body></html>";

?>