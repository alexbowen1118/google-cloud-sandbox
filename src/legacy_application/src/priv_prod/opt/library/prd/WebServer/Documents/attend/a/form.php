<?php
ini_set('display_errors',1);
date_default_timezone_set('America/New_York');
include("../../../include/get_parkcodes_reg.php");
extract($_REQUEST);
$database="attend";
include("../../../include/auth.inc");
if(empty($connection))
	{
	include("../../../include/iConnect.inc");
	}

$database="park_use";
mysqli_select_db($database,$connection);

$dbTable="stats";
$file="form.php";
$fileMenu="../menu.php";

//echo "<pre>"; print_r($_SESSION); echo "</pre>";

extract($_REQUEST);

if(isset($parkcode)){$passPark=$parkcode;}

include("$fileMenu");

$level=$_SESSION['attend']['level']; //echo "l=$level"; print_r($_SESSION);

if($level==1)
	{
	$parkcode=$_SESSION['attend']['select'];
	$parkCode=array("","",$parkcode);
	}

if($level==2)
	{
	$distCode=$_SESSION['attend']['select'];
	$menuList="array".$distCode; $parkCode=${$menuList};
	sort($parkCode);
	}

@$parkcode=substr($parkcode,0,4);

// Workaround for ENRI and OCMO and other multi-parks
if(isset($_SESSION['attend']['accessPark']) and $_SESSION['attend']['accessPark']!="")
	{	
	$parkCode=explode(",",$_SESSION['attend']['accessPark']);
	if(isset($passPark)){$parkcode=$passPark;}
	}


if(@$passM)
	{
	$M=str_pad($passM,2,"0",STR_PAD_LEFT);
	$modField=",mod".$M;
	}
else{$M=date('m');
$modField=",mod".$M;}

if(!isset($parkcode)){$parkcode="";}

mysqli_select_db($connection,"park_use");
// Get appropriate Fields for the Park
$sql = "SELECT fld_name,category_desc $modField,submodifier
FROM categories
left join park_category on categories.category_id=park_category.category
where park_category.park_id='$parkcode' order by category_id";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
//echo "$sql m=$M";
while ($row=mysqli_fetch_array($result))
	{
	$fieldName[]=$row[0];
	$titleArray[$row[0]]=$row[1];
	$modArray[$row[0]]=$row[2];// Get Modifier values
	$submodArray[$row[0]]=$row[3];// Get Modifier values
	}
//print_r($_REQUEST);
//echo "<pre>";print_r($_SERVER);echo "</pre>";//exit;

if(@!$y and @!$yearPass){$y=date('Y');$m=date('m');}

if(@$yearPass){$y=$yearPass;}
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

echo "<div align='center'><table>";
echo "<tr><th>Division of Parks and Recreation $second_week_no $day_of_week $sec_week</th></tr>";
$mM2=str_pad($mM2,2,"0",STR_PAD_LEFT);
$testM=str_pad($month,2,"0",STR_PAD_LEFT);
$testM1=$y.$testM;  $testM2=$curYear.$mM2;

$passNext=$testM+1;

if($passNext==13){$passNext=1;$yNext=$y+1;}else{$yNext=$y;}


if($yNext<2012){$var_form="form.php";}else{$var_form="form_day.php";}
$next="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Next <a href='$var_form?y=$yNext&passM=$passNext&parkcode=$parkcode'>>></a>";

$passPrev=$passM-1;

echo "<tr>
<td align='center'>Monthly Use Report for ";
echo " <select name='parkcode' onChange=\"MM_jumpMenu('parent',this,0)\">"; 
echo "<option value='' selected>";        
foreach($parkCode as $index=>$pc)
		{
		if($pc==$parkcode)
			{$s="selected";}
			else
			{$s="value";}
		echo "<option $s='form.php?y=$y&parkcode=$pc&passM=$month'>$pc</option>\n";
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
echo "<option $s='form.php?y=$scode&passM=$month&parkcode=$parkcode'>$scode\n";
          }
echo "</select>";

$monthLong=strftime("%B",strtotime($y.$m."01"));

if($passPrev==-1||$passPrev==0){$passPrev=12;$yPrev=$y-1;}else{$yPrev=$y;}

echo "<br><br><a href='form.php?yearPass=$yPrev&passM=$passPrev&parkcode=$parkcode'><<</a> Prev&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; for <b><font color='red'>$monthLong</font> $y</b> $next</td><td>";

$query_string="?y=$y&passM=$month&parkcode=$parkcode";

if(@$_SESSION['attend']['angle'])
	{
	$var1=$_SESSION['attend']['angle'];
	}

$var=substr($_SERVER['REQUEST_URI'],-1,1);
if($var=="v"||$var=="h"){
$angle=$var; if($var=="v"){$hck="";$vck="checked";}else{$hck="checked";$vck="";}
$base=substr($_SERVER['REQUEST_URI'],0,strlen($_SERVER['REQUEST_URI'])-2);
$qh="http://".$_SERVER['SERVER_NAME'].$base."&h";
$qv="http://".$_SERVER['SERVER_NAME'].$base."&v";
}
else{
if($_SERVER['QUERY_STRING']=="")
	{
	$qh="http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'].$query_string."&h";
	$qv="http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'].$query_string."&v";
	}
	else
	{
	$qh="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."&h";
	$qv="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."&v";
	}

if(isset($var1))
	{
	$angle=$var1;
	if($var1=="h"){$vck="";$hck="checked";}else{$hck="";$vck="checked";}
	}
	else
	{$angle="v";$vck="checked";}
}
$_SESSION['attend']['angle']=$angle;
//print_r($_SESSION);

if(!isset($hck)){$hck="";}
if(!isset($vck)){$vck="";}
echo "<form action='form.php'>Tab: &nbsp;H<INPUT TYPE='radio' name='angle' OnClick='window.location=\"$qh\";' VALUE='h'$hck>
V<INPUT TYPE='radio' name='angle' OnClick='window.location=\"$qv\";' VALUE='v'$vck>
</form></td></tr></table>";

if(!$parkcode){exit;}
// *********** Get previously entered values *************
$fieldList=@$fieldName[0];
for($l=1;$l<count(@$fieldName);$l++)
	{
	$fieldList.=",".$fieldName[$l];
	}

$testYMW1=$y.str_pad($passM,2,"0",STR_PAD_LEFT)."01"; 
$testYMW2=$y.str_pad($passM,2,"0",STR_PAD_LEFT)."02"; 
$testYMW3=$y.str_pad($passM,2,"0",STR_PAD_LEFT)."03"; 
$testYMW4=$y.str_pad($passM,2,"0",STR_PAD_LEFT)."04"; 
$testYMW5=$y.str_pad($passM,2,"0",STR_PAD_LEFT)."05"; 
$testYMW6=$y.str_pad($passM,2,"0",STR_PAD_LEFT)."06"; 
$testYMWx=$y.str_pad($passM,2,"0",STR_PAD_LEFT).str_pad($weeks_of_month,2,"0",STR_PAD_LEFT);

$sql="SELECT year_month_week,comments FROM stats where park='$parkcode' and (year_month_week>='$testYMW1' and year_month_week<='$testYMWx')";
//echo "$sql<br>";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_array($result))
{$weekXarray[]=$row[0];$comments4month=$row[1];}

$sql="SELECT $fieldList FROM stats where park='$parkcode' and (year_month_week>='$testYMW1' and year_month_week<='$testYMWx')";
//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("<br>Input form for $parkcode has not been created. Go <a href='cat.php?parkcode=$parkcode'> here</a>.");
$x=0;
while ($row=mysqli_fetch_array($result))
	{
	$weekX=$weekXarray[$x];
	for($z=0;$z<count($fieldName);$z++)
		{
		$key=$fieldName[$z].$weekX;
		$keyName[$key]=$row[$z];
			}// end field for
	$x=$x+1;
	}// end while

if(isset($keyName)){$count=count($keyName);}
//
//echo "<br>$sql";
//echo "<pre>";print_r($keyName);echo "</pre>";//exit;

// *************** Show Form ****************
// Headers
if(@$e==1){$message="<font color='purple'>Entry successful.</font>";}else{$message="";}
echo "$message";
$wn=$first_week_no;
echo "<form action='insert.php' method='post' name='attendForm'><table cellpadding='11'><tr><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CATEGORY&nbsp;&nbsp;&nbsp;&nbsp;</th>";
for($i=1;$i<=$weeks_of_month;$i++)
	{
	$fn=str_pad($wn,2,"0",STR_PAD_LEFT);
	$weekNum[]=$fn;
	
		$se="<br /><font color='blue'>".$date_week_start[$i]."</font>";;

	echo "<th width='50'>WEEK<br>$i$se</th>";
	$wn=$wn+1;
	}
echo "<th align='center'>Totals</th></tr></table>";


// ******* Display results **********
if($angle=="h"){include("horiz.php");}else{include("vertical.php");}


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
if(!isset($comments4month)){$comments4month="";}
echo "<table>
$warn
<tr><td>Comments: <textarea name='comments' cols='45' rows='5'>$comments4month</textarea></td><td>
<input type='hidden' name='modPass' value='$use_mod'>
<input type='hidden' name='yearPass' value='$y'>
<input type='hidden' name='monthPass' value='$passM'>
<input type='hidden' name='weeksPass' value='$weeks_of_month'>
<input type='hidden' name='parkPass' value='$parkPass'>
<input type='submit' name='submit' value='Enter'></td></tr>";
echo "</form></table></div></body></html>";

?>