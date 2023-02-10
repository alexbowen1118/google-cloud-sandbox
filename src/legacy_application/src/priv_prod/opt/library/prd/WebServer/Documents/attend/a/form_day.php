<?php

// if edit access to previous month(s) is needed look around  line 538
// if($tempID=="Davis9471")
ini_set('display_errors',1);
date_default_timezone_set('America/New_York');

$database="attend"; // I messed up and used two different names for this app
include("../../../include/auth.inc");
$database="park_use";
include("../../../include/iConnect.inc");

include("../../../include/get_parkcodes_dist.php");

$database="park_use";
mysqli_select_db($connection,$database);

include("park_code_areas.php"); // get subunits


$dbTable="stats_day";
$file="form_day.php";
$fileMenu="../menu.php";

// echo "<pre>"; print_r($_SESSION); echo "</pre>";
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";

if(isset($parkcode)){$passPark=$parkcode;}else{$parkcode=$_SESSION['attend']['select'];}

include("$fileMenu");

$level=$_SESSION['attend']['level']; //echo "l=$level"; print_r($_SESSION);

//$multi_area defined in park_code_areas.php
//echo "<pre>"; print_r($parkCode); echo "</pre>"; // exit;
if($level==1)
	{
	if(@$yearPass>2011 OR @$y>2011 or (empty($yearPass) and empty($y)))
		{
		$exp=array($_SESSION['attend']['select']); 
		if(!empty($_SESSION['attend']['accessPark']))
			{
			$exp=explode(",",$_SESSION['attend']['accessPark']);
			}
		foreach($parkCode as $k1=>$v1)
			{
			foreach($exp as $v=>$start_parkcode)
				{
				if(strpos($v1,$start_parkcode)>-1)
					{
					$new_parkCode[]=$v1;
					}
				}
			}
		$parkCode=$new_parkCode;
// 		echo "53 $parkcode<pre>"; print_r($exp); print_r($parkCode); echo "</pre>"; // exit;
		if(in_array($parkcode,$multi_area))
			{
			$parkcode=$parkcode."_ADMI";
			if($parkcode=="KELA_ADMI")
				{
				$parkcode="KELA_SAPO";
				}
			if($parkcode=="HARI_ADMI")
				{
				$parkcode="HARI_SUMM";
				}
			}
		}
		else
		{
		
		$start_parkcode=$_SESSION['attend']['select'];
		foreach($parkCode as $k1=>$v1)
			{
			if(strpos($v1,$start_parkcode)>-1)
				{
				$new_parkCode[]=$v1;
				}
			}
		$parkCode=$new_parkCode;
		if(in_array($parkCode,$multi_area))
			{
			if($parkCode=="KELA")
				{
				$parkCode=$parkCode."_SAPO";
				}else
				{
				$parkCode=$parkCode."_ADMI";
				}
			}
		}
	}

if($level==2)
	{
// $mountain_region=array("CRMO","GORG","CHRO","GRMO","HARO","PIMO","LAJA","MOMI","LANO","MOMO","NERI","ELKN","SOMO","STMO","MORE");
// $piedmont_region=array("CACR","RARO","WEWO","ENRI","FALA","HARI","MARI","JORD","KELA","MEMO","WIUM","PIRE");
// $coastal_region=array("CABE","FOFI","FOMA","GOCR","PETT","HABE","JONE","LAWA","SILA","JORI","LURI","MEMI","DISW","CORE");
// 	$distCode=$_SESSION['attend']['select'];
	$distCode=$_SESSION['reg'];
// 	echo "<pre>"; print_r($parkCode); echo "</pre>"; // exit;
	$parkCode=${"array".$distCode};   //$parkCode now contains Regional code
	$del_val="MORE";  // remove this from $parkCode
	if(($key = array_search($del_val, $parkCode)) !== false)
		{
		unset($parkCode[$key]);
		}
	$del_val="CORE";
	if(($key = array_search($del_val, $parkCode)) !== false)
		{
		unset($parkCode[$key]);
		}
	$del_val="PIRE";
	if(($key = array_search($del_val, $parkCode)) !== false)
		{
		unset($parkCode[$key]);
		}
		
// 	echo "<pre>"; print_r($parkCode); echo "</pre>"; // exit;
	if($distCode=="PIRE")
		{	
		$parkCode[]="FALA_ADMI";
		$parkCode[]="FALA_BW";
		$parkCode[]="FALA_BD";
		$parkCode[]="FALA_HP";
		$parkCode[]="FALA_HW50";
		$parkCode[]="FALA_RV";
		$parkCode[]="FALA_RVM";
		$parkCode[]="FALA_SB";
		$parkCode[]="FALA_SL";
		
		$parkCode[]="KELA_BULL";
		$parkCode[]="KELA_COLI";
		$parkCode[]="KELA_HEPO";
		$parkCode[]="KELA_HIBR";
		$parkCode[]="KELA_KIPO";	
		$parkCode[]="KELA_NBNO";
		$parkCode[]="KELA_NBSO";
		$parkCode[]="KELA_SAPO";
		
		$parkCode[]="JORD_ADMI";
		$parkCode[]="JORD_CROS";
		$parkCode[]="JORD_EBDU";
		$parkCode[]="JORD_EBBR";
		$parkCode[]="JORD_NHOL";
		$parkCode[]="JORD_PACR";
		$parkCode[]="JORD_POPO";
		$parkCode[]="JORD_RCCA";
		$parkCode[]="JORD_RCBR";
		$parkCode[]="JORD_SEAF";
		$parkCode[]="JORD_VIPO";
		$parkCode[]="JORD_WHOA";		
		
		}
	if($distCode=="MORE")
		{
		$parkCode[]="HARO_ADMI";
		$parkCode[]="HARO_DARI";
		$parkCode[]="HARO_LOCA";
		$parkCode[]="HARO_MOWA";
		$parkCode[]="HARO_TODE";
		}
	if($distCode=="CORE")
		{
		$parkCode[]="GOCR_ADMI";
		$parkCode[]="GOCR_DILA";
		
		$parkCode[]="MEMI_ADMI";
		$parkCode[]="MEMI_BORA";
		$parkCode[]="MEMI_CAMP";	
		}
	sort($parkCode);
	
	if(in_array(@$parkcode,$multi_area))
		{
		if(strlen($parkcode)==4)
			{
			if($parkcode=="KELA")
				{
				$parkcode=$parkcode."_SAPO";
				}else
				{
				$parkcode=$parkcode."_ADMI";
				}
			}
		}
	}

//echo "p=$parkcode";
if($level>2)
	{
	if(in_array(@$parkcode,$multi_area))
		{
		if(strlen($parkcode)==4)
			{
			if($parkcode=="KELA")
				{
				$parkcode=$parkcode."_SAPO";
				}
				else
				{
				$parkcode=$parkcode."_ADMI";
				}
			}
		}
		
	}

/*
//echo "p=$parkcode";
//echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
// Workaround for ENRI and OCMO and other multi-parks
if(isset($_SESSION['attend']['accessPark']) and $_SESSION['attend']['accessPark']!="")
	{	
	$parkCode=explode(",",$_SESSION['attend']['accessPark']);
	if(isset($passPark)){$parkcode=$passPark;}
	}
*/

if(@$passM)
	{
	$M=str_pad($passM,2,"0",STR_PAD_LEFT);
	$modField=",mod".$M;
	}
	else
	{
	$M=date('m');
	$modField=",mod".$M;
	}

if(!isset($parkcode)){$parkcode="";}

mysqli_select_db($connection,"park_use");
// Get appropriate Fields for the Park
$sql = "SELECT fld_name,category_desc $modField,submodifier
FROM categories_day
left join park_category_day on categories_day.category_id=park_category_day.category
where park_category_day.park_id='$parkcode' order by category_id";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
if($level>4)
	{
// 	echo "$sql m=$M"; //exit;
	}
	
// exclude these for PIMO
$exclude_pimo=array("pimo_cor","fuel_moisture_1_hour","transport_winds","air_temp","fuel_moisture_live","KBDI","fuel_moisture_100_hour","fuel_moisture_10_hour","burn_category","mixing_height","midflame_windspeed","wind_direction","wind_20_ft","rel_humidity","energy_release_component","burn_index");

$fieldName=array();
while ($row=mysqli_fetch_array($result))
	{
	if(in_array($row[0],$exclude_pimo)){continue;}
	$fieldName[]=$row[0];
	$titleArray[$row[0]]=$row[1];
	$modArray[$row[0]]=$row[2];// Get Modifier values
	$submodArray[$row[0]]=$row[3];// Get Modifier values
	}
if($level>4)
	{
// 	echo "<pre>"; print_r($fieldName); echo "</pre>"; //exit;
	}


if(@!$y and @!$yearPass){$y=date('Y');$m=date('m');}

if(!empty($yearPass)){$y=$yearPass;}
$curYear=date('Y');
if(@$passM)
	{
	$m=str_pad($passM,2,"0",STR_PAD_LEFT);
	$d=date('d');
	$month = $passM;
	$year=$y;
	$mM2=date('m');
	$monthpad=$m;
	}

else
	{
	$m=date('m');
	$d=date('d');
	$month = date('m');
	$year=$y;
	$mM2=$month;
	$passM=$month-1;
	$passM=$month;$monthpad=$m;
	}

     $first_week_no = date("W", mktime(1, 1, 1, $month, 1, $year));
     $day_of_week = date("w", mktime(1, 1, 1, $month, 1, $year));
     $date_week_start[1]=date("D jS", mktime(1, 1, 1, $month, 1, $year));
     $sec_week=1+(abs($day_of_week-7)+1);

     $second_week_no = date("w", mktime(1, 1, 1, $month, $sec_week, $year));
     
     $last_week_no = date("W", mktime(1, 1, 1, $month, date('t',mktime(0,0,0,$month,1,$year)), $year));
    if($last_week_no < $first_week_no)
		{
		$weeks_of_month = 53 - $first_week_no + $last_week_no;
		}
    else
		{
		$weeks_of_month = $last_week_no - $first_week_no + 1;
		}

for($i=2;$i<=$weeks_of_month;$i++)
	{
	$date_week_start[$i] = date("D jS", mktime(1, 1, 1, $month, $sec_week, $year));
	$sec_week=$sec_week+7;
	}
$firstDay = date("D jS", mktime(1, 1, 1, $month, 1, $year));
$lastDay = date("D jS", mktime(1, 1, 1, $month+1, 0, $year));

	if($firstDay=="Fri 1st" and $month==1)
		{
		$weeks_of_month=5;
		// hack to deal with rollover of weeks from one year to next on a Friday
		}

// **************
$num_days=date('t', mktime(0, 0, 0, $month, 1, $year));

for($i=1; $i<=$num_days; $i++)
	{
	$day_array[]=$i;
	}
//print_r($day_array);

echo "<div align='center'><table>";
//$second_week_no $day_of_week $sec_week
echo "<tr><th>Division of Parks and Recreation </th></tr>";
$mM2=str_pad($mM2,2,"0",STR_PAD_LEFT);
$testM=str_pad($month,2,"0",STR_PAD_LEFT);
$testM1=$y.$testM;  $testM2=$curYear.$mM2;

$passNext=$testM+1;

if($passNext==13)
	{
	$passNext=1;
	$yNext=$y+1;
	}
	else
	{$yNext=$y;}

$next="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Next <a href='form_day.php?y=$yNext&passM=$passNext&parkcode=$parkcode'>>></a>";

$passPrev=$passM-1;

//echo "309<pre>"; print_r($parkCode); echo "</pre>"; // exit;
echo "<tr>
<td align='center'>Monthly Use Report for ";
echo " <select name='parkcode' onChange=\"MM_jumpMenu('parent',this,0)\">"; 
echo "<option value='' selected>";

//$multi_area defined in park_code_areas.php
foreach($parkCode as $index=>$pc)
		{
		if(in_array($pc,$multi_area)){continue;}
		if($pc==$parkcode)
			{$s="selected";}
			else
			{$s="value";}
		echo "<option $s='form_day.php?y=$y&parkcode=$pc&passM=$month'>$pc</option>\n";
		}
if(isset($parkCodeName[$parkcode]))
	{
	$park_name=$parkCodeName[$parkcode];
	}
	else
	{$park_name="";}
echo "</select><font color='blue'> $park_name</font> </form>";

echo "<select name='year' onChange=\"MM_jumpMenu('parent',this,0)\">";       
        for ($n=2000;$n<=$curYear;$n++)       
      //  for ($n=$curYear;$n>=1984;$n--)  
        {$scode=$n;
if($scode==$y){$s="selected";}else{
$s="value";}
echo "<option $s='form_day.php?y=$scode&passM=$month&parkcode=$parkcode'>$scode\n";
          }
echo "</select>";

$monthLong=strftime("%B",strtotime($y.$m."01"));

if($passPrev==-1 || $passPrev==0)
	{
	$passPrev=12;
	$yPrev=$y-1;
	}
	else
	{$yPrev=$y;}

if($yPrev<2012){$var_form="form.php";}else{$var_form="form_day.php";}
echo "<br><br><a href='$var_form?yearPass=$yPrev&passM=$passPrev&parkcode=$parkcode'><<</a> Prev&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; for <b><font color='red'>$monthLong</font> $y</b> $next</td><td>";

$query_string="?y=$y&passM=$month&parkcode=$parkcode";

echo "</tr></table>";

if(!$parkcode){exit;}
// *********** Get previously entered values *************
$fieldList=@$fieldName[0];
for($l=1;$l<count(@$fieldName);$l++)
	{
	$fieldList.=",".$fieldName[$l];
	}

$testYMW1=$y.str_pad($passM,2,"0",STR_PAD_LEFT)."01"; 
$testYMWx=$y.str_pad($passM,2,"0",STR_PAD_LEFT).end($day_array);
$testYMW2=($y-1).str_pad($passM,2,"0",STR_PAD_LEFT)."01"; 
$testYMWy=($y-1).str_pad($passM,2,"0",STR_PAD_LEFT).end($day_array);

$this_year=$y."_total";
$prev_year=($y-1)."_total";
$var_park=substr($parkcode,0,4);
$sql="SELECT sum(attend_tot) as $this_year FROM stats_day where park like '$var_park%' and (year_month_day>='$testYMW1' and year_month_day<='$testYMWx')";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
$row=mysqli_fetch_array($result);
$this_yr_month_total=$row[$this_year];

$sql="SELECT sum(attend_tot) as $prev_year FROM stats_day where park like '$var_park%' and (year_month_day>='$testYMW2' and year_month_day<='$testYMWy')";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
$row=mysqli_fetch_array($result);
$prev_yr_month_total=$row[$prev_year];


$sql="SELECT year_month_day,comments FROM stats_day where park='$parkcode' and (year_month_day>='$testYMW1' and year_month_day<='$testYMWx')";
//echo "$sql<br>";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_array($result))
	{
	$dayXarray[]=$row[0];
	if(empty($comments4month))
		{
		$comments4month=$row['comments'];
		}
	}

//Get appropriate Fields for the Park from $fieldList
// categories_day and park_category_day

$sql="SELECT $fieldList FROM stats_day where park='$parkcode' and (year_month_day>='$testYMW1' and year_month_day<='$testYMWx')";
// echo "429 $sql"; exit;
$result = mysqli_query($connection,$sql) or die ("<br>Input form for $parkcode has not been created. Contact database.support at <a href='mailto:database.support@ncparks.gov'> database.support@ncparks.gov </a> for support at database if visitation needs to be tracked.");


$x=0;
while ($row=mysqli_fetch_array($result))
	{
	$dayX=$dayXarray[$x];
	for($z=0;$z<count($fieldName);$z++)
		{
		$key=$fieldName[$z].$dayX;
		$keyName[$key]=$row[$z];
			}// end field for
	$x=$x+1;
	}// end while

if(isset($keyName)){$count=count($keyName);}
//
//echo "<br>$sql";
//echo "array keyName<pre>";print_r($keyName);echo "</pre>";  //exit;

// *************** Show Form ****************
// Headers
if(@$e==1)
	{
	$message="<font color='purple'>Entry successful.</font>";
	}
	else
	{$message="";}
echo "$message";

echo "<form action='insert_day.php' method='post' name='attendForm'><table cellpadding='1'>
<tr><th> </th><th colspan='5'>Days in $monthLong $year</th></tr>
<tr><th>CATEGORY</th>";
foreach($day_array as $dk=>$dv)
	{
	$se="<font color='blue'>".$dv."</font>";
	echo "<th>";
	echo "$se</th>";
	}
echo "<th align='center'>Totals</th></tr>";


// ******* Display results **********
//if($angle=="h"){

include("horiz_day.php");

//}else{include("vertical_day.php");}


$parkPass=$parkcode;
if(@$use_mod!="")
	{
	$warn="<tr><td><font color='red'>Please click the Enter button again to lock in the calculated values for DAY-use.</font></td></tr>";
	}
	else
	{
	$use_mod="";
	$warn="<font color='brown'>NOTE: Use 30 as the multiplier for buses unless an actual count is available.</font>";
	}
@$perCentChangeM=number_format((($this_yr_month_total/$prev_yr_month_total)-1)*100);
// $warn.="$perCentChangeM";
// $warn.=date('d');
// $warn.=$passM." ".date('m');
if((abs($perCentChangeM)>=10 ))
	{
	$warn.="<br />This year/month=".number_format($this_yr_month_total)."  Last year/month=".number_format($prev_yr_month_total)."<br /><font color='red'>There is a $perCentChangeM% difference between this month's visitation and the same month last year.</font><br />At the <strong>end of the month</strong> please enter an explanation into the Comments box if the difference is 10% or greater.";
	}

if(!isset($comments4month)){$comments4month="";}
echo "<table>
$warn
<tr>";
if(!empty($avg_hi) and !empty($avg_lo))
	{
	$avg_temp=($avg_hi+$avg_lo)/2;
	echo "<td>Average Temp. for Month=$avg_temp</td>";
	}
echo "<td valign='top'>Comments: <textarea name='comments' cols='105' ";
$length=strlen($comments4month);
$row_h=ceil($length/20);
if($row_h<1){$row_h=5;}
echo "rows='$row_h'>";

$a_date = date("Y-m-d");
$b_date=$y."-".str_pad($passM,2,"0",STR_PAD_LEFT)."-01";

$last_day_prev_month=date("Y-m-t",strtotime($b_date));
$cut_off_before=date('Y-m-d', strtotime($last_day_prev_month. ' + 6 days'));

$test_date=$y."-".$passM."-01";
$cut_off_after=date('Y-m-d', strtotime($a_date. ' + 1 days'));

if($tempID=="username")
	{
	//Enter users "tempID" in the if statement above
	//to allow temporary access edit previous months {edit by COOPER}
	$cut_off_before=date('Y-m-d', strtotime($last_day_prev_month. ' + 33 days'));
	}

// a=$b_date c=$cut_off_after
echo "$comments4month</textarea></td>";
// level > 3
$temp_level=$level;
if($_SESSION['attend']['select']=="GRMO")
	{
	$temp_level=4;
	}
If(($temp_level>3 OR $a_date<=$cut_off_before) AND $b_date<=$cut_off_after)
	{
	echo "<td>
	<input type='hidden' name='modPass' value='$use_mod'>
	<input type='hidden' name='yearPass' value='$y'>
	<input type='hidden' name='monthPass' value='$passM'>
	<input type='hidden' name='parkPass' value='$parkPass'>
	<input type='submit' name='submit' value='Enter'></td></tr>";
	echo "</form>";
	}
echo "<tr><td colspan='3'><a href='/attend/d/park_daily.php?parkcode=$parkcode&yearPass=$y&passM=$passM' target='_blank'>Graph</a> daily visitation for month for $parkcode.</td>";

// 	echo "<pre>"; print_r($exp); echo "</pre>"; // exit;
if(strpos($parkcode,"_")>0)
	{
	$exp1=explode("_",$parkcode);
	echo "<td><a href='/attend/d/park_daily.php?parkcode=$exp1[0]&passM=$passM' target='_blank'>Graph</a> daily visitation for month for $exp1[0].</td>";
	}
echo "</tr>";

if(!empty($exp1[0]))
	{
// 	echo "<pre>"; print_r($exp); echo "</pre>"; // exit;
	$pc=$exp1[0]; $ra=" by individual recreation area.";
	}
	else
	{$pc=$parkcode; $ra=".";}
echo "<tr><td colspan='3'><a href='/attend/d/park_yearly.php?parkcode=$pc&yearPass=$y' target='_blank'>Yearly</a> visitation for $pc$ra</td></tr>";
echo "<tr><td align='middle'>

<script type='text/javascript' src='https://bi.nc.gov/javascripts/api/viz_v1.js'></script><div class='tableauPlaceholder' style='width: 1000px; height: 827px;'><object class='tableauViz' width='1000' height='827' style='display:none;'><param name='host_url' value='https%3A%2F%2Fbi.nc.gov%2F' /> <param name='embed_code_version' value='3' /> <param name='site_root' value='&#47;t&#47;NCParksandRec' /><param name='name' value='VisitationDashboard&#47;NCParkAttendanceDashboard' /><param name='tabs' value='no' /><param name='toolbar' value='yes' /><param name='showAppBanner' value='false' /></object></div>
</td></tr>";

if($level>2)
	{
	echo "<tr><td colspan='3' align='right'>Other <a href='/attend/d/other_graphs.php' target='_blank'>Graph</a> options.</td>
	</tr>";
/*
	echo "<tr><td colspan='3' align='middle'><a href='https://bi.nc.gov/t/NCParksandRec/views/visitation-draft/Dashboard1?:origin=card_share_link&:embed=n'><stong>Visitation Dashboard</strong></a></td></tr>";
*/
	}
echo "</table></div></body></html>";

?>
