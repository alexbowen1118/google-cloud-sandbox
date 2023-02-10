<?php
ini_set('display_errors',1);
$dbTable="recycle_stats";
$file="recycle_form.php";
$fileMenu="../menu.php";

date_default_timezone_set('America/New_York');

$database="park_use";

include("../../../include/iConnect.inc");

extract($_REQUEST);
include("../../../include/get_parkcodes_reg.php");

mysqli_select_db($connection,$database);

@$passPark=$parkcode;

$level=$_SESSION['attend']['level'];
if($level==1){$parkcode=$_SESSION['attend']['select'];
$parkCode=array("","",$parkcode);}

if($level==2){
$distCode=$_SESSION['attend']['select'];
$menuList="array".$distCode; $parkCode=${$menuList};
sort($parkCode);
}


// Workaround for ENRI and OCMO and other multi-parks
if(isset($_SESSION['attend']['accessPark']) and $_SESSION['attend']['accessPark']!="")
	{
	$parkCode=explode(",",$_SESSION['attend']['accessPark']);
	if(isset($passPark)){$parkcode=$passPark;}
	}

if(@!$y and @!$yearPass){$y=date('Y');
if(date('nd')<"115"){$y=$y-1;}
}
if(@$yearPass){$y=$yearPass;}
$curYear=date('Y');

$year=$y;

$testYM=$y; 

$sql="SHOW COLUMNS FROM $dbTable from park_use";
$result = mysqli_query($connection,$sql);
while($array=mysqli_fetch_array($result)){
$keyName[]=$array[0];}

if(isset($parkcode))
	{
	$sql="SELECT * FROM $dbTable where park='$parkcode' and `year_month`>'$testYM'";
	//echo "$sql";
	$result = mysqli_query($connection,$sql) or die ("<br>Input form for $parkcode has not been created.<br><br>Go <a href='cat.php?parkcode=$parkcode'> here</a>$sql.");
	}

include("$fileMenu");
echo "<div align='center'><table>";
echo "<tr><th>Division of Parks and Recreation</th></tr>";

echo "<tr>
<td align='center'>Recycle Report for ";
echo " <select name='parkcode' onChange=\"MM_jumpMenu('parent',this,0)\">"; 
echo "<option value='' selected>";        
for ($n=0;$n<count($parkCode);$n++)  
        {
        $scode=$parkCode[$n];
	if($scode==@$parkcode){$s="selected";}else{
	$s="value";}
	if(!isset($month)){$month="";}
	echo "<option $s='$file?y=$y&parkcode=$scode&passM=$month'>$scode\n";
          }
          if(!isset($parkcode)){$parkcode="";$pc="";}
          	else{$pc=$parkCodeName[$parkcode];}
echo "</select><font color='blue'> $pc</font> ";

echo "<select name='year' onChange=\"MM_jumpMenu('parent',this,0)\">";       
        for ($n=2000;$n<=$curYear;$n++)       
      //  for ($n=$curYear;$n>=1984;$n--)  
        {$scode=$n;
if($scode==$y){$s="selected";}else{
$s="value";}
echo "<option $s='$file?y=$scode&parkcode=$parkcode'>$scode\n";
          }
echo "</select> </form>";

echo "</td></tr></table>";

if(!$parkcode){exit;}
// *********** Get previously entered values *************

while ($row=mysqli_fetch_array($result))
	{
	//echo "<pre>";print_r($row);echo "</pre>";
	$ym=$row[2];
	$valArray[$ym]=$row;
	
	}// end while
//echo "<pre>";print_r($valArray);echo "</pre>";
// *************** Show Form ****************
// Headers
if(@$e==1){$message="<font color='red'>Entry successful.</font>";}else{$message="";}

echo "<hr><form action='recycle_insert.php' method='post' name='recycleForm'><table cellpadding='1'>";

echo "<tr><td colspan='10' align='center'><b>Pounds</b> of recyclables from <font color='purple'>$parkcode for Year $y</font> <font color='red'>*** If you measure in a unit other than pounds,</font> such as 32 gallon trash cans, please indicate the units in the comments field.</td></tr>
<tr><td colspan='5' align='center'>$message</td></tr>";
echo "<tr>
<th>YearMonth</th>
<th>Aluminum</th>
<th>Plastic</th>
<th>Glass</th>
<th>Metal</th>
<th>Paper</th>
<th>Cardboard</th>
<th>Co-mingled (mixed)</th>
<th>Other</th>
<th>Comments</th>
</tr>";

for($i=1;$i<=12;$i++){$ymArray[]=$y.str_pad($i,2,'0',STR_PAD_LEFT);}
//print_r($ymArray);

foreach($ymArray as $k=>$v)
	{
	@$al=$valArray[$v]['aluminum']; @$alTot+=$al;
	@$pl=$valArray[$v]['plastic']; @$plTot+=$pl;
	@$gl=$valArray[$v]['glass']; @$glTot+=$gl;
	@$me=$valArray[$v]['metal']; @$meTot+=$me;
	@$pa=$valArray[$v]['paper']; @$paTot+=$pa;
	@$ca=$valArray[$v]['cardboard']; @$caTot+=$ca;
	@$co_m=$valArray[$v]['co_mingled']; @$co_mTot+=$co_m;
	@$or=$valArray[$v]['other_recycle']; @$orTot+=$or;
	@$co=$valArray[$v]['comments'];
	echo "<tr>
	<td><input type='text' name='year_month' value='$v' size='8' READONLY></td>
	<td align='center'><input type='text' name='aluminum[$v]' value='$al' size='5'></td>
	<td align='center'><input type='text' name='plastic[$v]' value='$pl' size='5'></td>
	<td align='center'><input type='text' name='glass[$v]' value='$gl' size='5'></td>
	<td align='center'><input type='text' name='metal[$v]' value='$me' size='5'></td>
	<td align='center'><input type='text' name='paper[$v]' value='$pa' size='5'></td>
	<td align='center'><input type='text' name='cardboard[$v]' value='$ca' size='5'></td>
	<td align='center'><input type='text' name='co_mingled[$v]' value='$co_m' size='5'></td>
	<td align='center'><input type='text' name='other_recycle[$v]' value='$or' size='5'></td>
	<td align='center'><textarea name='comments[$v]' cols='35' rows='2'>$co</textarea></td>
	</tr>";
	}
echo "<tr>
<th>Pounds ==></th>
<th>$alTot</th>
<th>$plTot</th>
<th>$glTot</th>
<th>$meTot</th>
<th>$paTot</th>
<th>$caTot</th>
<th>$co_mTot</th>
<th>$orTot</th>
<th></th>
</tr>";
echo "</table>";

echo "<table><tr><td align='center'>
<input type='hidden' name='yearPass' value='$y'>
<input type='hidden' name='parkPass' value='$parkcode'>
<input type='submit' name='submit' value='Enter'></tr>";

echo "<tr><td align='center'>In addition to other comments, please include special programs, grants received, unique partnerships and success stories in the comments field.</td></tr></form></table></div></body></html>";

?>