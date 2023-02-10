<?php
ini_set('display_errors',1);
$dbTable="vol_stats";
$file="vol_form.php";
$fileMenu="../menu.php";

//echo "Call Tom at 919-552-2976 if you get this error message.";
//exit;

$database="park_use";
include("../../../include/iConnect.inc");
date_default_timezone_set('America/New_York');
include("../../../include/get_parkcodes_reg.php");
extract($_REQUEST);


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

@$sql="SELECT * FROM vol_stats where park='$parkcode' and `year_month`='$testYM' order by Lname,Fname";
//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("<br>Input form for $parkcode has not been created. Go <a href='cat.php?parkcode=$parkcode'> here</a>$sql.");

$num=mysqli_num_rows($result);
if($num<1 AND @$parkcode!="")
	{// *********** IF MONTH doesn't exist, create a record
	$passM=str_pad($passM,2,"0",STR_PAD_LEFT);
	$ym=$y.$passM;
	$updateFields="SET park='$parkcode',`year_month`='$ym'";
	$query="REPLACE $dbTable $updateFields";
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query 1. $query");
	header("Location: /attend/a/vol_form.php?parkcode=$parkcode&passM=$passM&yearPass=$y");
	exit;
	}

include("$fileMenu");
$passNext=$passM+1;$passPrev=$passM-1;$yT=$y;$yY=$y;$yX=$y;
if($passM=="01"){$passPrev=12;$yY=$y-1;$yT=$y;}
if($passM==12){$passNext=1;$yX=$y+1;$yT=$yX;}

echo "<div align='center'><table>";
echo "<tr><th>Division of Parks and Recreation</th></tr>";

@$next="&nbsp;&nbsp;&nbsp;&nbsp;Next <a href='vol_form.php?y=$yX&passM=$passNext&parkcode=$parkcode'>>></a> ";
echo "<tr>
<td align='center'>Report Volunteer Hours for ";
echo " <select name='parkcode' onChange=\"MM_jumpMenu('parent',this,0)\">"; 
echo "<option value='' selected>";        
        for ($n=0;$n<count($parkCode);$n++)  
			{
			$scode=$parkCode[$n];
				if($scode==$passPark){$s="selected";}else{
				$s="value";}
			echo "<option $s='vol_form.php?y=$y&parkcode=$scode&passM=$month'>$scode\n";
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
			echo "<option $s='vol_form.php?y=$scode&passM=$month&parkcode=$parkcode'>$scode\n";
			  }
echo "</select>";

$monthLong=strftime("%B",strtotime($y.$m."01"));
echo "<br><br><a href='vol_form.php?yearPass=$yY&passM=$passPrev&parkcode=$parkcode'><<</a> Prev&nbsp;&nbsp;&nbsp;&nbsp; for <b><font color='red'>$monthLong</font> $y</b> $next</td><td>";

$query_string="?y=$yY&passM=$month&parkcode=$parkcode";

echo "</td></tr></table>";

if(!$parkcode){exit;}
// *********** Get previously entered values *************

$values=array();
while ($row=mysqli_fetch_assoc($result))
	{// get ASSOC array
	for($z=0;$z<count($row);$z++)
		{
		list($key,$val)=each($row);
		if($key!="id")
			{
			$values[]=$val;
			$fieldName[]=$key;
			}
			else
			{$idArray[]=$val;}
		}// end field for
	
	}// end while

$count=(count($keyName)-1);// want to ignore id field
//echo "<br>$sql";
//echo "<pre>";print_r($idArray);echo "</pre>";exit;

// *************** Show Form ****************
// Headers
if(@$e==1){$message="<font color='purple'>Entry successful.</font>";}else{$message="";}
echo "$message";

echo "<form action='vol_insert.php' method='post' name='volForm'><table cellpadding='1'><tr>";
for($z=0;$z<count($keyName);$z++)
	{
	if($keyName[$z]=="Lname"){$r="<br>(required)";}else{$r="";}
	
	if($keyName[$z]=="main_hours"){$keyName[$z]="maintenance_hours";}
	if($keyName[$z]=="res_man_hours"){$keyName[$z]="resource_management_hours";}
	$h=strtoupper(str_replace("_","<br>",$keyName[$z]));
	echo "<th>$h$r</th>";}
echo "<th align='center'>Totals</th></tr><tr>";

for($z=0;$z<count($values);$z++)
	{
	$v=$values[$z];
	$f=$z/$count;
	if(is_integer($f))
		{
		$id=$idArray[$f];
		if($z>0){$del="<a href='vol_insert.php?v=del&id=$id&parkcode=$parkcode&passM=$passM&yearPass=$y'>Delete</a>";}else{$del="";}
		@$t1="<td align='right'>$tot</td></tr><tr><td>$del</td>";
		$tot="";
		}
	$pos=strpos($fieldName[$z],'hours');
	if($pos>0)
		{
		@$tot+=$v;
		@$totHours+=$v;
		}
	// Default display
	$val=$v;
	// Modify display
	// First record allows entry of name
	if($fieldName[2]=='Lname' and $fieldName[3]=='Fname' AND $z<4)
		{
		$val="<input type='text' name='$fieldName[$z][]' value='$v' size='10'";}
	if($fieldName[$z]=='park' || $fieldName[$z]=='year_month')
		{
		if($fieldName[$z]=='park'){$parkcode=$v;}
		$val=$v;
		}
	if($fieldName[$z]=='comments')
		{
		$val="<textarea name='$fieldName[$z][]' cols='22' rows'2'>$v</textarea>";}
	if(($fieldName[$z]=='Lname' || $fieldName[$z]=='Fname') and $z>4)
		{
		$val="<input type='text' name='$fieldName[$z][]' value='$v' size='10' READONLY";}
	
	if($fieldName[$z]=='category')
		{
		$val="<select name='category[]'><option selected=''></option>";
		foreach($category_array as $cat_i=>$cat_v)
			{
			if($cat_v==$v){$s="selected";}else{$s="";}
			$val.="<option $s='$cat_v'>$cat_v</option>";
			}
		$val.="</selection>";
		}
		
	echo "$t1<td align='center'>$val</td>";
	$t1="";
	}

$parkPass=$parkcode;
if(!isset($tot)){$tot="";}
if(!isset($totHours)){$totHours="";}
echo "<td align='right'>$tot</td></tr><tr><td colspan='4' align='center'><a href='r_vol_hours.php?parkcode=$parkcode'>Report</a> for $parkcode
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Eligible for <a href='vol_sum_hours.php?parkcode=$parkcode'>Awards</a></td><td>
<td colspan='15' align='right'>$totHours</td></tr></table><table><tr><td>
<input type='hidden' name='yearPass' value='$y'>
<input type='hidden' name='monthPass' value='$passM'>
<input type='hidden' name='parkPass' value='$parkPass'>
<input type='submit' name='submit' value='Enter'></tr>";
echo "</form></table></div></body></html>";

?>