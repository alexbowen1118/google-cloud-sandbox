<?php

if(@!$_GET['$xls']){include("../menu.php");}// ignore menu for Excel export
ini_set('display_errors',1);
date_default_timezone_set('America/New_York');

$database="park_use";

include("../../../include/iConnect.inc");

include("../../../include/get_parkcodes_reg.php");

mysqli_select_db($connection,$database);

$dbTable="stats";
$file="use_summary.php";
@$passPark=$parkcode;

$level=$_SESSION['attend']['level'];
if($level==1){$parkcode=$_SESSION['attend']['select'];
$parkCode=array("","",$parkcode);}

if($level==2)
	{
	$distCode=$_SESSION['attend']['select'];
	$menuList="array".$distCode; $parkCode=${$menuList};
	sort($parkCode);
	}


// Workaround for ENRI and OCMO
if($_SESSION['attend']['select']=="ENRI"||$_SESSION['attend']['select']=="OCMO"){
	$parkCode=array("ENRI","OCMO");
	$parkcode=$passPark;
	}
// Workaround for MOJE and NERI
if($_SESSION['attend']['select']=="NERI"||$_SESSION['attend']['select']=="MOJE"){
	$parkCode=array("MOJE","NERI");
	$parkcode=$passPark;
	}

@$year=$y;

if($passPark!="Division")
	{
	$parkVar="and park='$passPark'";
	$where="WHERE park_id =  '$passPark'";
	$group=" GROUP  BY `year_month`";
	}
else
	{
	$where="WHERE 1";
	$group=" GROUP  BY park";
	$entireYear=1;
	}

$sql="SELECT distinct fld_name
FROM  `categories` 
LEFT  JOIN park_category ON categories.category_id = park_category.category
$where and (fld_name!='campers_vehicle' and fld_name!='campers_backpack')";
$result = mysqli_query($connection,$sql);
while($row=mysqli_fetch_array($result))
	{
	$fldName[]=$row[0];
	}


if(!$year){$year=date('Y');}

if($year>2011)
{
$table="stats_day";
$sql="SELECT left( year_month_day, 6 ) as `year_month`,park";
}
else
{
$table="stats";
$sql="SELECT left( year_month_week, 6 ) as `year_month`,park";
}


if(isset($fldName))
	{
	for($i=0;$i<count($fldName);$i++)
		{
		switch($fldName[$i])
			{
			case "comm_hi_temp";
			$sql.=",max(".$fldName[$i].") as $fldName[$i]";
			break;
			case "comm_low_temp";
			$sql.=",min(".$fldName[$i].") as $fldName[$i]";
			break;
			default:
			$sql.=",sum(".$fldName[$i].") as $fldName[$i]";
			}
		}
	}

if(isset($fiscal))
	{
	$next_year=$year+1;
	$year1=$year."06";
	$year2=$next_year."07";
	$where="WHERE (";

	if($year>2011)
		{
			$where.="left( year_month_day, 6  ) > '$year1'";
			$where.=" and left( year_month_day, 6  ) < '$year2')";
		}
	else
		{
			$where.="left( year_month_week, 6  ) > '$year1'";
			$where.=" and left( year_month_week, 6  ) < '$year2')";
		}
	}
else
	{

	if($year>2011)
		{
			$where="WHERE left( year_month_day, 4  ) = '$year'";
		}
	else
		{
			$where="WHERE left( year_month_week, 4  ) = '$year'";
		}
	
	}


$sql.=" FROM  $table 
$where $parkVar
$group";

//echo "$sql";//exit;
$result = mysqli_query($connection,$sql) or die ("Query failed. $sql<br />".mysqli_error($connection));

if(@$xls=="excel"){header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=NC_State_Park_Attendance.xls');
}

echo "<div align='center'><table>";
echo "<tr><th>Division of Parks and Recreation</th></tr>";

if(@!$xls)
	{
	// Menu 1
	echo "<tr>
	<td align='center'>Activity for ";
	echo " <select name='parkcode' onChange=\"MM_jumpMenu('parent',this,0)\">"; 
	echo "<option value='' selected>";
	if(!isset($parkcode)){$parkcode="";}
	if(!isset($y)){$y="";}
	if(!isset($month)){$month="";}
	foreach($parkCode as $index=>$park_code) 
		{
		if($park_code=="nonDPR"){$park_code="Division";}
		if(@$park_code==$parkcode)
			{$s="selected";}else{$s="value";}
		echo "<option $s='$file?y=$y&parkcode=$park_code&passM=$month'>$park_code</option>\n";
		  }
		  if(isset($parkcode))
		  	{$park_name=$parkCodeName[$parkcode];}
	echo "</select><font color='blue'> $park_name</font> ";
	
	// Menu 2
	$curYear=date('Y');
	echo "<select name='year' onChange=\"MM_jumpMenu('parent',this,0)\">
	<option selected=''></option>";       
	for ($n=2000;$n<=$curYear;$n++)       
		{
		$scode=$n;
		if($scode==$y){$s="selected";}else{$s="value";}
		echo "<option $s='$file?y=$scode&parkcode=$parkcode'>$scode\n";
		}
	echo "</select> </form></td>";
	if(isset($y))
		{
		echo "<td><a href='use_summary.php?y=$year&parkcode=$parkcode&fiscal=1'>Fiscal Year</a></td>";
		}
	echo "<td>Excel <a href='use_summary.php?y=$y&parkcode=$parkcode&xls=excel'>export</a></td>";
	}

echo "</tr></table>";

if(!$parkcode){exit;}

// *********** Show Results *************
//print_r($fldName);
@$numFld=count($fldName)+2;
echo "<hr><table cellpadding='1' border='1'>";
echo "<tr><td colspan='$numFld' align='center'><font color='purple'>$parkcode for Year $y</font></td></tr>";

echo "<tr>";
if($parkcode=="Division"){echo "<th>Year</th><th>Park</th>";}else
{echo "<th>Year_Month</th><th>Park</th>";}

for($j=0;$j<$numFld-2;$j++)
	{
	echo "<th>$fldName[$j]</th>";
	}
echo "</tr>";

while ($row=mysqli_fetch_assoc($result))
	{// get ASSOC array
	echo "<tr>";
	for($z=0;$z<count($row);$z++)
		{
		list($key,$val)=each($row);
		
		switch($key){
		case "year_month";
			if(@$entireYear==1)
				{
				$yr=substr($val,0,4);
				echo "<td align='right'>$yr</td>";
				}
				else
				{echo "<td align='right'>$val</td>";}
			
			break;
		case "park";
			echo "<td align='right'>$val</td>";
			break;
		case "comm_precip";
			@$tot_comm_precip+=$val;
			$val=number_format($val,1);
		case "csw_hours";
			@$tot_csw_hours+=$val;
			$val=number_format($val,1);
			echo "<td align='right'>$val</td>";
			break;
		default:
			$f="tot_".$key;
			@${$f}+=$val;
			@$val=number_format($val);
		echo "<td align='right'>$val</td>";}
		
			}// end field for
	echo "</tr>";
	}// end while

echo "<tr><td>&nbsp;</td><td>&nbsp;</td>";
for($j=0;$j<$numFld-2;$j++)
	{	
	switch($fldName[$j])
		{
		case "comm_precip";
		$f="tot_".$fldName[$j];
		$v=@number_format(${$f},1);
		break;
		case "csw_hours";
		$f="tot_".$fldName[$j];
		$v=@number_format(${$f},1);
		break;
		
		default:
		$f="tot_".$fldName[$j];
		$v=@number_format(${$f});
		}
	echo "<th>$v</th>";
	}
echo "</tr>";

if(@!$xls)
	{
	echo "<tr>";
	if($parkcode=="Division"){echo "<th>Year</th><th>Park</th>";}else
	{echo "<th>Year_Month</th><th>Park</th>";}
	
	for($j=0;$j<$numFld-2;$j++){
	echo "<th>$fldName[$j]</th>";}
	echo "</tr>";
	}

echo "</table></div></body></html>";

?>