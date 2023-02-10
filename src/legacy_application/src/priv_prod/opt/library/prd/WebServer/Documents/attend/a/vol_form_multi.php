<?php
ini_set('display_errors',1);
$dbTable="vol_stats";
$file="vol_form_multi.php";
$fileMenu="../menu.php";

//echo "Call Tom at 919-552-2976 if you get this error message.";
//exit;

$database="park_use";
include("../../../include/iConnect.inc");

date_default_timezone_set('America/New_York');
include("../../../include/get_parkcodes_reg.php");

mysqli_select_db($connection,$database);

@$passPark=$parkcode;
$sql="SELECT * FROM vol_cat where 1";
$result = mysqli_query($connection,$sql);
while($row=mysqli_fetch_array($result))
	{
	$category_array[]=$row['cat_name'];
	}

$level=$_SESSION['attend']['level']; //echo "l=$level"; print_r($_SESSION);
if($level==1)
	{
	$parkcode=$_SESSION['attend']['select'];
	$parkCode=array("","",$parkcode);
	}

if($level==2)
	{
	$distCode=$_SESSION['attend']['select'];
	$menuList="array".$distCode;
	$parkCode=${$menuList};
	sort($parkCode);
	}


// Workaround for ENRI and OCMO and other multi-parks
if(isset($_SESSION['attend']['accessPark']) and $_SESSION['attend']['accessPark']!="")
	{
	$parkCode=explode(",",$_SESSION['attend']['accessPark']);
	$parkcode=$passPark;
	}

if(@$_SESSION['attend']['accessPark'])
	{
	$parkCode=explode(",",$_SESSION['attend']['accessPark']);
	$parkcode=$passPark;
	}

$parkCode[]="MTST";
$parkCodeName['MTST']="Mountains To Sea Trail";
sort($parkCode);
//echo "<pre>"; print_r($parkCode); echo "</pre>";

if(@$passM)
	{
	if(@$passM==0){$passM=12;}
	$M=str_pad($passM,2,"0",STR_PAD_LEFT);
	$modField=",mod".$M;
	}

else{$M=date('m');$passM=$M;
$modField=",mod".$M;}

if(@!$y and @!$yearPass)
	{
	$m=date('m');
	$y=date('Y');
	}

if(@$yearPass){$y=$yearPass;}
$curYear=date('Y');
if(empty($yearPass)){$yearPass=$curYear;}

if($passM)
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
	if($month>1){$passM=$month-1;}
	}

$testYM=$y.str_pad($passM,2,"0",STR_PAD_LEFT); 

$sql="SHOW COLUMNS FROM vol_stats from park_use";
$result = mysqli_query($connection,$sql);
while($array=mysqli_fetch_array($result)){
$keyName[]=$array[0];}

$sql="SELECT * FROM vol_stats where park='$parkcode' and `year_month`='$testYM' order by Lname,Fname";
//echo "$sql";
$result = mysqli_query($connection,$sql);

$num=mysqli_num_rows($result);
include("$fileMenu");
$passNext=$passM+1;$passPrev=$passM-1;$yT=$y;$yY=$y;$yX=$y;
if($passM=="01"){$passPrev=12;$yY=$y-1;$yT=$y;}
if($passM==12){$passNext=1;$yX=$y+1;$yT=$yX;}

echo "<div align='center'><table>";
echo "<tr><th>Division of Parks and Recreation</th></tr>";

@$next="&nbsp;&nbsp;&nbsp;&nbsp;Next <a href='vol_form_multi.php?yearPass=$yX&passM=$passNext&parkcode=$parkcode'>>></a> ";
echo "<tr>
<td align='center'>Report Volunteer Hours for ";
echo " <select name='parkcode' onChange=\"MM_jumpMenu('parent',this,0)\">"; 
echo "<option value='' selected>";        
        for ($n=0;$n<count($parkCode);$n++)  
			{
			$scode=$parkCode[$n];
				if($scode==$passPark){$s="selected";}else{
				$s="value";}
			echo "<option $s='vol_form_multi.php?y=$y&parkcode=$scode&passM=$month'>$scode\n";
			  }
	if(isset($parkCodeName[$passPark]))
		{$parkname=$parkCodeName[$passPark];}else{$parkname="";}
echo "</select><font color='blue'> $parkname</font> </form>";

echo "<select name='year' onChange=\"MM_jumpMenu('parent',this,0)\">";       
        for ($n=2000;$n<=$curYear;$n++)       
      //  for ($n=$curYear;$n>=1984;$n--)  
			{$scode=$n;
				if($scode==$y){$s="selected";}
				else{$s="value";}
	if(!isset($parkcode)){$parkcode="";}
			echo "<option $s='vol_form_multi.php?y=$scode&passM=$month&parkcode=$parkcode'>$scode\n";
			  }
echo "</select>";

$monthLong=strftime("%B",strtotime($y.$m."01"));
echo "<br><br><a href='vol_form_multi.php?yearPass=$yY&passM=$passPrev&parkcode=$parkcode'><<</a> Prev&nbsp;&nbsp;&nbsp;&nbsp; for <b><font color='red'>$monthLong</font> $y</b> $next</td><td>";

$query_string="?y=$yY&passM=$month&parkcode=$parkcode";

echo "</td></tr></table>";

if(!$parkcode){exit;}
// *********** Get previously entered values *************

	$skip=array("id");
	$auto=array("park"=>$parkcode,"year_month"=>$testYM);
	$auto_size=array("Lname"=>"18","Fname"=>"14","year_month"=>"5");
	echo "<form name='multi_add' action='vol_multi_add.php'>
	<table border='1'><tr>";
	foreach($keyName as $k=>$v)
		{
		if(in_array($v,$skip)){continue;}
		if(array_key_exists($v,$auto)){$value=$auto[$v]; $ro="READONLY";}else{$value="";$ro="";}
		if(array_key_exists($v,$auto_size)){$size=$auto_size[$v]; }else{$size=3;}
		$fld_name=str_replace("_"," ",$v);
		if($v=="comments")
			{
			echo "<td>$fld_name<br /><textarea name='$v' cols='10' rows='1'></textarea></td>";}
			else
			{
			echo "<td>$fld_name<br /><input type='text' name='$v' value=\"$value\" size='$size' $ro></td>";
			}
		}
	echo "</tr><tr><td align='center' colspan='15'><input type='submit' name='submit' value='Add'></td></tr>";
	echo "</table></form>";




while ($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	}// end 
if(empty($ARRAY)){exit;}
//echo "<br>$sql";
//echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;

// *************** Show Form ****************
// Headers
if(@$e==1){$message="<font color='purple'>Entry successful.</font>";}else{$message="";}
echo "$message";

$skip=array("id");
$auto['Lname']="";
$auto['Fname']="";
if(empty($_SESSION['attend']['category']))
	{$pass_category="";}else{$pass_category=$_SESSION['attend']['category'];}
if(empty($_SESSION['attend']['comments']))
	{$pass_comments="";}else{$pass_comments=$_SESSION['attend']['comments'];}
echo "<form action='vol_add_multi.php' method='post' name='volForm'><table cellpadding='1'><tr>";
$c=count($ARRAY);
echo "<table border='1'><tr><td>$c</td></tr>";
foreach($ARRAY AS $index=>$array)
	{
	if($index==0)
		{
		echo "<tr>";
		foreach($ARRAY[0] AS $fld=>$value)
			{
			if(in_array($fld,$skip)){continue;}
			$fld_name=str_replace("_hours"," ",$fld);
			echo "<th>$fld_name</th>";
			}
		echo "</tr>";
		}
	echo "<tr>";
	foreach($array as $fld=>$value)
		{
		if(in_array($fld,$skip)){continue;}
		if(array_key_exists($fld,$auto))
			{		
			$value=$array[$fld]; $ro="READONLY";
			}
			else
			{$value="";$ro="";}
		if(array_key_exists($fld,$auto_size)){$size=$auto_size[$fld]; }else{$size=3;}
		
		if($fld=="comments")
			{
			echo "<td align='center'><textarea name='$fld' cols='10' rows='1'></textarea></td>";}
			else
			{
			echo "<td align='center'><input type='text' name='$fld' value=\"$value\" size='$size' $ro></td>";
			}
		}
	echo "</tr>";
	}
echo "</table>";
echo "<table><tr><td valign='bottom'><input type='submit' name='submit' value='Update'></td></tr><tr></table></form><hr />";


echo "</form></table></div></body></html>";

?>