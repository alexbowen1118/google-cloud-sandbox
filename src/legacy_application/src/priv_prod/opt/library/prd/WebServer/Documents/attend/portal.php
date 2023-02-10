<?php
session_start();
//echo "<pre>"; print_r($_SESSION); print_r($_SERVER); echo "</pre>"; // exit;
//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
extract($_REQUEST);
$level=$_SESSION[$database]['level'];
$server=$_SERVER['HTTP_HOST'];

if($level<1)
	{
	echo "You do not have portal access to the $database database. Click your browser's back button.";exit;
	}

extract($_REQUEST);
	// $database gets passed in URL
	include("../../include/connectROOT.inc");// database connection parameters
	$db = mysql_select_db("park_use",$connection)
       or die ("Couldn't select database $database");
//       session_start();
//	$_SESSION[$database]['level']=5;   // used to get past menu.php page for any database that checks $level

	
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;

//**** Process any Delete ******
if($deleteRecord=="Delete")
	{
//	print_r($_REQUEST);exit;
	$query = "DELETE FROM $dbTable where $fld='$fld_val'";
//	echo "$query";exit;
	$result = mysql_query($query) or die ("Couldn't execute query Delete. $query");
	header("Location: portal.php?dbTable=$dbTable");
	exit;
	}

 
//**** Process any Update or Add ******
if($submit=="Update")
	{
//	echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
	$skip=array("like","IDfield","dbTable","database","passSQL","deleteRecordID","submit","PHPSESSID",);
	foreach($_REQUEST as $k=>$v)
		{
		if(in_array($k,$skip)){continue;}
		if($k=="nrid_id" AND $v=="")
			{$queryString.=$k."=NULL,";}
			else
			{$queryString.=$k."='".$v."',";}		
		}
	$qs=rtrim($queryString,",");
	//echo "<pre>";print_r($arrKeys);print_r($newQuery);echo "</pre>$queryString<br>";
	
	//print_r($newQuery);exit;
	$v=${$IDfield};
	$query = "Update $dbTable set $qs
	where $IDfield='$v'";
//	echo "$query";exit;
	$result = mysql_query($query) or die ("Couldn't execute query Update. $query");
	header("Location: portal.php?database=$database&dbTable=$dbTable&$IDfield=$v");
	exit;
	}

//**** Process any Add ******
if($submit=="Add")
	{
	$arr1=explode(",",$updateFields);
	for($j=0;$j<count($arr1);$j++){
	$arr2=explode("=",$arr1[$j]);
	$arr3[]=$arr2[0];
	}
	for($j=0;$j<count($arr1);$j++){
	$val1=$_REQUEST[$arr3[$j]];
	$newQuery[$arr3[$j]]=$val1;
	}
	
	$arrKeys=array_keys($newQuery);
	$queryString=$arrKeys[0]."='".$newQuery[$arrKeys[0]]."'";
	for($j=1;$j<count($arrKeys);$j++){
	$queryString.=", ".$arrKeys[$j]."='".$newQuery[$arrKeys[$j]]."'";
	}
	
	//echo "<pre>";print_r($arrKeys);print_r($newQuery);echo "</pre>$queryString<br>";
	
	print_r($newQuery);exit;
	
	$query = "INSERT INTO $dbTable set $queryString";
	$result = mysql_query($query) or die ("Couldn't execute query Update. $query");
	$v=mysql_insert_id();
	
	header("Location: portal.php?dbTable=$dbTable&$recordIDfld=$v");
	exit;
	}

//include("menu.php");
	@include("css/TDnull.inc");

//echo "<pre>";print_r($_REQUEST);echo "</pre>";//exit;

 
//********** SET Variables **********
//$dbTable="table name";// TABLE NAME should be passed from URL
$portal_database=$database; // necessary because I messed up and renamed 
// the attend database as park_use

if($database=="attend"){$portal_database="park_use";}

echo "<table><tr><td>";
	
		$db_list = mysql_list_dbs($connection);
		echo "<form>
		 <select name=\"menu1\" onChange=\"MM_jumpMenu('parent',this,0)\">
         	 <option selected>Choose a Database</option>";
		while ($row = mysql_fetch_object($db_list))
		{
     		foreach($row as $k=>$v)
     			{
     			if($level<5 and $portal_database!=$v){continue;}
     			if($portal_database=="$v"){$s="selected";}else{$s="value";}
     			echo "<option $s='http://$server/attend/portal.php?database=$v'>$v</option>";
     			}
		}
		echo "</select></form></td>";
				
		$sql = "SHOW TABLES FROM $portal_database";//echo "$sql";
		$result = mysql_query($sql) or die ("Couldn't execute query. $sql");
		echo "<td><form>
		 <select name=\"menu1\" onChange=\"MM_jumpMenu('parent',this,0)\">
         	 <option selected>Choose a Table</option>";
		while ($row = mysql_fetch_object($result))
		{
     		foreach($row as $k=>$v)
     			{
     			echo "<option value='http://$server/attend/portal.php?database=$portal_database&dbTable=$v'>$v</option>";
     			}
		}
		echo "</select></form></td></tr></table>";
	if($dbTable=="")
		{exit;}
	
// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$from=$portal_database.".".$dbTable;
$sql = "SHOW COLUMNS FROM $from";//echo "$sql";
$result = mysql_query($sql) or die ("Couldn't execute query. $sql");
$numFlds=mysql_num_rows($result);
while ($row=mysql_fetch_assoc($result))
	{
	$fieldArray[]=$row['Field'];
	$fieldType[]=$row['Type'];
	}

$recordIDfld=$fieldArray[$numFlds-1];

makeUpdateFields($fieldArray);// MAKE FIELD=VALUE FOR ADD/UPDATE

for($dk=0;$dk<count($fieldType);$dk++)
	{
	$varD=substr($fieldType[$dk],0,7);
	if($varD=="decimal"){$fieldDecimal[]=$dk;}
	if($varD=="varchar"){$size=substr(substr($fieldType[$dk],8,7),0,-1);
	if($size>30){$size=30;}$fieldSize[]=$size;}
	else{$fieldSize[]=12;}
	}
//print_r($fieldSize);//exit;

// Find number fields
for($dk=0;$dk<count($fieldDecimal);$dk++)
	{
	$t=$fieldDecimal[$dk];
	$varE[$fieldArray[$t]]=$fieldDecimal[$dk];
	}
//print_r($varE);//exit;  //$varE = Any field(s) defined as Decimal

//**** Prepare To Find, Update OR Delete******
if($action=="edit")
	{
	//print_r($_REQUEST);exit;
	$fld=$IDfield;
	$fld_val=${$fld};
	$sql0 = "SELECT * from $portal_database.$dbTable where $fld='$fld_val'";
	$result = mysql_query($sql0) or die ("Couldn't execute query 0. $sql0");
	$num_row=mysql_num_rows($result);
	if($num_row!=1)
		{
		echo "Unable to edit that record. More than a single record was returned using $fld=$fld_val";
		$action="";
		}
		else
		{
		$formType="Update";
		$passSQLedit=urlencode($passSQL);
		$row=mysql_fetch_array($result);
		extract($row);
		}
	}
else
	{
	if($addRecord=="Add a Record"){$formType="Add";}
	if($addRecord==""){$formType="Find";
	$addAddButton="<td><input type='hidden' name='dbTable' value='$dbTable'><input type='hidden' name='recordIDfld' value='$recordIDfld'>
	<input type='submit' name='addRecord' value='Add a Record'></form></td>";}
	}


function pullDown($m)
	{global $like;
	$menu1=array("Like","Range","Not");
	if($like[$m]=="Like"){$like[$m]=1;}
	if($like[$m]=="Range"){$like[$m]=2;}
	if($like[$m]=="Not"){$like[$m]=3;}
	echo "<select name=\"like[$m]\"><option selected></option>";
	for ($n=0;$n<count($menu1);$n++)
		{
		$con=$n+1;
		if($con==$like[$m]){$s="selected";}else{$s="value";}
		echo "<option $s='$con'>$menu1[$n]\n";
		}
	  echo "</select>";
	  }

// ******************* Form ****************
// Used \" instead of ' because of lastname or vendor name containing '
//echo "<pre>"; print_r($fieldArray); echo "</pre>"; // exit;

echo "<form action=\"portal.php\" method=\"POST\">";
//echo "<form action=\"portal.php\"";// Used to debug

echo "<table><tr><td colspan='6'>You are working with database => <font color=\"red\">$database</font> table => <font color=\"blue\">$dbTable</font>$rangeOfDates</td></tr>";

	echo "<tr>";
for($i=0;$i<$numFlds;$i++)
	{	
	echo "<td>$fieldArray[$i]:</td><td>"; pullDown($i);
	echo "<input type=\"text\" name=\"$fieldArray[$i]\" size=\"$fieldSize[$i]\" value=\"${$fieldArray[$i]}\"></td>";
	if(fmod(($i+1),4)==0){echo "</tr><tr>";}
	}
	echo "</tr></table>";

// Select GroupBy Fields
setIDfield($fieldArray,$IDfield);


echo "<table><tr>
<td valign='top'><input type='hidden' name='dbTable' value='$dbTable'>
<input type='hidden' name='database' value='$database'>";


if($action=="")
	{	
	echo "
	Enter an * to find all values for that field.<br /><input type='checkbox' name='all' value='all'>Check to Find <b>All</b> Records&nbsp;&nbsp;&nbsp;&nbsp;
	<input type='submit' name='submit' value='Find'></form></td";
	}


if($action=="edit")
	{	
	echo "<td valign='top'><form><input type='hidden' name='passSQL' value='$passSQLedit'>
	<input type='hidden' name='dbTable' value='$dbTable'>
	<input type='hidden' name='$fld' value='$fld_val'>
	<input type='hidden' name='deleteRecordID' value='$var'>
	<input type='submit' name='submit' value='Update'></form></td>";
	
	echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
	
	echo "<td><form>
	<input type='hidden' name='dbTable' value='$dbTable'>
	<input type='hidden' name='fld' value='$fld'>
	<input type='hidden' name='fld_val' value='$fld_val'>
	<input type='submit' name='deleteRecord' value='Delete' onClick='return confirmLink()'></form></td>";
	}

echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

echo "<td><form action='portal.php'>
<input type='hidden' name='database' value='$database'>
<input type='hidden' name='dbTable' value='$dbTable'>
<input type='submit' name='' value='Reset'></form</td>";

echo "</tr></table>";

  // ***** Pick display function and set sql statement

$co=count($_REQUEST); 
//print_r($_REQUEST);echo "c=$co";//exit;

//$table="eedata0405";
$from="*";// Default - gets used if not Group By

// ******* Group By Variables*******
// *** Make list of Fields to pass to GroupBy and Function portalHeader
$passFields=$fieldArray[0];
for($pf=1;$pf<count($fieldArray);$pf++)
	{
	$passFields.=",".$fieldArray[$pf]; // used in portalHeader function
	}





$from.=" From $portal_database.$dbTable";

// ********* Assign passed Values by Field
for($j=0;$j<count($fieldArray);$j++)
	{
	$passVal[$j]=${$fieldArray[$j]};
	}
// ********* Where **********
if($co>0){$where=" WHERE 1";}else{ echo "c=$co";exit;}


//                     **************************         Create WHERE statement
for($k=0;$k<count($fieldArray);$k++)
	{
	if($passVal[$k]!="")
		{
		$dbFld=$fieldArray[$k];
		$dbVal=addslashes($passVal[$k]);
		if($dbVal=="*" AND $like[$k]=="")
			{
			$distinct="distinct $dbFld "; $from=" From $portal_database.$dbTable";
			$where.=" and $dbFld !=''"; continue;
			}
		if($like[$k]==""){$where.=" and $dbFld = '$dbVal'";}
		if($like[$k]==1){$where.=" and $dbFld like '%$dbVal%'";}
		if($like[$k]==2){
		$rangeDate=explode("*",$dbVal);
		if($rangeDate[0]!=""&&$rangeDate[1]=="")
			{$where.=" and $dbFld='$rangeDate[0]'";}
			else
			{$where.=" and $dbFld>='$rangeDate[0]' and $dbFld<='$rangeDate[1]'";}
		}
		
		if($like[$k]=="3"){$where.=" and $dbFld != '$dbVal'";}
		
		
		}// order by $dbFld
	}// end for loop


if($where==" WHERE 1" AND $all==""){exit;}
//if($where==" WHERE 1"&&$g=='Group by '&&$passSQL==''){exit;}


$sql1 = "SELECT $distinct $from $where $g $order_clause";


//echo "1 $sql1";


if($sql1)
	{
	// ********** This displays the result **********
	include_once("portalFunctions.php");
	
	$passSQL=urlencode($sql1);
	
	getDecimalFields($varE);
	portalHeader($passSQL,$passFields,$countKeys,$sumBy,$totHeader,$countHeader);// Make Header
	
	$result = mysql_query($sql1) or die ("Couldn't execute query. $sql1");
	
	$num=mysql_num_rows($result);
	//echo "n=$num";
	if($num>1000)
		{
		$sql1 = "SELECT $from $where $g limit 1000";
		$result = mysql_query($sql1) or die ("Couldn't execute query. $sql1");
		echo "<font color='red'>$num</font> records were found. However, only the first <font color='red'>1000</font> are being displayed. Let Tom Howard know if you need to view more than 1000 at a time.<br>";}
		else
		{
		echo "$num <font color='green'>$z Items</font> $sql1<hr>";
		}
	
	
	if(isset($varE))
		{
		$decimalValues=array_values($varE);
		}
		//print_r($decimalValues);//exit;
	

		while ($row=mysql_fetch_array($result))
			{
				{				
				$totFld1=$totFld1+$fld1;
				foreach($row as $k=>$v)
					{
					if(is_int($k)){continue;}
					$keys[]=$k;
					}
					
				extract($row);
				$t0=${$fld0Dec};$t1=${$fld1Dec};$t2=${$fld2Dec};
				$fld0Tot=$fld0Tot+$t0;
				$fld1Tot=$fld1Tot+$t1;
				$fld2Tot=$fld2Tot+$t2;
				
				if(isset($decimalValues))
					{
					$dv=array_values($decimalValues);
					}
				
				if($sumBy)
					{
					$total1=$total1+$row[0];
					if($countKeys==1){$colPositionA=$dv[0]+$countKeys;}
					if($countKeys==2)
						{						$colPositionA=$dv[0]+$countKeys;$colPositionB=$dv[1]+$countKeys;
						if($totHeader)
							{							$colPositionA=$$colPositionA+1;$colPositionB=$colPositionB+1;
							}
						}
					
					}
					else
					{
					$total1=$total1+$amt;
					$col0=$dv[0];
					$col1=$dv[1];
					$col2=$dv[2];
					}
				
				
				echo "<tr>";
				for($x=0;$x<$numOfColumns;$x++)
					{
					$var=$row[$x];
					
					if($keys[$x]=="id" OR $keys[$x]=="$IDfield")
						{
						if($IDfield==""){$IDfield="id";}
						$var="<a href='portal.php?database=$database&dbTable=$dbTable&$keys[$x]=$var&IDfield=$IDfield&action=edit'>$var</a>";
						}
					
					$pos=strpos($var,".");
					$testVar=@is_finite($var);
					if($pos>-1 and $testVar)
						{
						$var1=number_format($var,2);
						if($var<0)
							{$bn="<font color='red'>";$bd="</font>";}else{$bn="";$be="";}
							echo "<td align='right'>$bn$var1$be</td>";
						}
						else
						{
						echo "<td>$var</td>";
						}
					
					}
				echo "</tr>";
				}
	
			}
	
	$total1=number_format($total1,2);
	$fld0Tot=number_format($fld0Tot,2);
	$fld1Tot=number_format($fld1Tot,2);
	$fld2Tot=number_format($fld2Tot,2);
	//$timegivenTot=number_format($timegivenTot,0);
	
	if(!$g){
	echo "$fld0Dec = <font color='blue'>$fld0Tot&nbsp;&nbsp;&nbsp;</font>";
	if($fld1Dec){echo "$fld1Dec = <font color='teal'>$fld1Tot&nbsp;&nbsp;&nbsp;</font>";}
	if($fld2Dec){echo "$fld2Dec = <font color='purple'>$fld2Tot</font>";}
	}
	if($g){echo "<font color='purple'>[$g]</font>";}
	
	echo "</table></body></html>";
	}
// **************  FUNCTIONS *******************

function makeUpdateFields($fieldArray){
global $updateFields;
for($i=0;$i<count($fieldArray);$i++){
if($i!=0){
$updateFields.=",".$fieldArray[$i]."=$".$fieldArray[$i];}
else
{$updateFields.=$fieldArray[$i]."=$".$fieldArray[$i];}
}// end for
}// end makeUpdateFields

// Make ID field radion buttons
function setIDfield($fieldArray,$IDfield)
	{
	echo "<table>";
	for($i=0;$i<count($fieldArray);$i++)
		{
		$t=fmod($i,6);
		$name="IDfield";
		if($IDfield==$fieldArray[$i]){$c="checked";}else{$c="";}
		echo "<td>
		<input type='radio' name='$name' value='$fieldArray[$i]' $c>$fieldArray[$i]</td>";
		if($i!=0 and $t==0){echo "<tr></tr>";}
		}// end for
	
	echo "</table>";
	}// end setIDfield
	
function portalHeader($passSQL,$passFields,$countKeys,$sumBy,$totHeader,$countHeader)
	{
	global $numOfColumns,$fld0Dec,$fld1Dec,$fld2Dec,$fld,$countKeys,$lastFld;// pass to itemShow
	
	//echo "t=$totHeader";exit;
	//print_r($passFields);//exit;
	$fld=explode(",",$passFields);// Put Field Names in an Array
	$c=count($fld)-1;$lastFld=$fld[$c];
	
	for($zz=0;$zz<$countKeys;$zz++)
		{
		$q="fld".$zz."Dec";
		$r=${$q};
		$zSum[]="SUM".$r;
		}
	if($totHeader)
		{
		array_unshift($fld, "Total");
		}
	if($countHeader)
		{
		for($zz=0;$zz<$countHeader;$zz++)
			{
			array_unshift($fld, "Count");
			}
		}
	//$zSum=array_reverse($zSum);
	for($zz=0;$zz<count($zSum);$zz++)
		{
		array_unshift($fld, $zSum[$zz]);
		}
	
	
	if($countKeys<1 and $sumBy)
		{
		//array_unshift($fld, "Count");
		}
	//print_r($fld);//exit;
	
	$numOfColumns=count($fld);
	//$link="<a href='portal.php?park=$park'>$park</a>";
	echo "
	<table border='1' cellpadding='3'><tr>";
	for($x=0;$x<$numOfColumns;$x++)
		{
		$var=str_replace("_"," ",strtoupper($fld[$x]));
		echo "<th>$var</th>";
		}
	echo "</tr>";
	}

?>