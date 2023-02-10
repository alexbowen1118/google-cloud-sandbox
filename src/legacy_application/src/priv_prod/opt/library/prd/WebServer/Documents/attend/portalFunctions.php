<?php
// *************** PMIS FUNCTIONS **************

function findMe($fld,$passSQL){
$findme=urlencode(" order by ");
$pos=strpos($passSQL,$findme);
if($pos>0){$flds = array("age","location","eeid");
$removeFld = str_replace($flds, "", $passSQL);
$passSQL=$removeFld.urlencode("$fld");}
else{$passSQL=$passSQL.urlencode(" order by $fld");}
return $passSQL;
}

/*
function portalHeader($passSQL,$passFields,$countKeys,$sumBy,$totHeader,$countHeader){
global $numOfColumns,$fld0Dec,$fld1Dec,$fld2Dec,$fld,$countKeys,$lastFld;// pass to itemShow

//echo "t=$totHeader";exit;
//print_r($passFields);//exit;
$fld=explode(",",$passFields);// Put Field Names in an Array
$c=count($fld)-1;$lastFld=$fld[$c];

for($zz=0;$zz<$countKeys;$zz++){
$q="fld".$zz."Dec";
$r=${$q};
$zSum[]="SUM".$r;
}
if($totHeader){
array_unshift($fld, "Total");
}
if($countHeader){
for($zz=0;$zz<$countHeader;$zz++){
array_unshift($fld, "Count");}
}
//$zSum=array_reverse($zSum);
for($zz=0;$zz<count($zSum);$zz++){
array_unshift($fld, $zSum[$zz]);}


if($countKeys<1 and $sumBy){
//array_unshift($fld, "Count");
}
//print_r($fld);//exit;

$numOfColumns=count($fld);
//$link="<a href='portal.php?park=$park'>$park</a>";
echo "
<table border='1' cellpadding='3'><tr>";
for($x=0;$x<$numOfColumns;$x++){
$var=strtoupper($fld[$x]);echo "<th>$var</th>";}
echo "</tr>";
}
*/

function getDecimalFields($varE)
	{
	global $fld0Dec,$fld1Dec,$fld2Dec;
	if(isset($varE))
		{
		$decimalKeys=array_keys($varE);
		}
	
	$fld0Dec=$decimalKeys[0];
	$fld1Dec=$decimalKeys[1];
	$fld2Dec=$decimalKeys[2];
	}

function itemShow($row,$passSQL,$dbTable,$sumBy,$decimalValues)
	{
	global $total1,$numOfColumns,$totFld1,$fld0Tot,$fld1Tot, $fld2Tot,$fld0Dec,$fld1Dec,$fld2Dec,$countKeys,$lastFld,$totHeader;
	
	$totFld1=$totFld1+$fld1;
	
	extract($row);
	$t0=${$fld0Dec};$t1=${$fld1Dec};$t2=${$fld2Dec};
	$fld0Tot=$fld0Tot+$t0;
	$fld1Tot=$fld1Tot+$t1;
	$fld2Tot=$fld2Tot+$t2;
	
	if(isset($decimalValues))
		{
		$dv=array_values($decimalValues);
		}
		//echo "d=$totHeader";
	//print_r($decimalValues);exit;
	
	if($sumBy){
	$total1=$total1+$row[0];
	if($countKeys==1){$colPositionA=$dv[0]+$countKeys;}
	if($countKeys==2){
	$colPositionA=$dv[0]+$countKeys;$colPositionB=$dv[1]+$countKeys;
	if($totHeader){$colPositionA=$$colPositionA+1;$colPositionB=$colPositionB+1;}}
	
	}else{$total1=$total1+$amt;
	$col0=$dv[0];
	$col1=$dv[1];
	$col2=$dv[2];
	}
	
	
	echo "<tr>";
	for($x=0;$x<$numOfColumns;$x++)
		{
		$var=$row[$x];
		if($x==$numOfColumns-1)
			{
			$var="<a href='portal.php?dbTable=$dbTable&lastFld=$lastFld&var=$var'>$var</a>";
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
?>