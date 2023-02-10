<?php
ini_set('display_errors',1);
$dbTable="litter_events";
$file="litter_form.php";
$fileMenu="../menu.php";

date_default_timezone_set('America/New_York');

$database="park_use";

include("../../../include/iConnect.inc");

extract($_REQUEST);
include("../../../include/get_parkcodes_reg.php");

$database="park_use";
mysqli_select_db($connection,$database);


@$passPark=$parkcode;

$level=$_SESSION['attend']['level'];
if($level==1){$parkcode=$_SESSION['attend']['select'];
$parkCode=array("","",$parkcode);}

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
	if(isset($passPark)){$parkcode=$passPark;}
	}

if(@!$y and @!$yearPass)
	{
	$y=date('Y');//$m=date(m);
	}
if(@$yearPass){$y=$yearPass;}
$curYear=date('Y');
$year=$y;
$testYM=$y; 

$sql="SHOW COLUMNS FROM $dbTable from park_use";
$result = mysqli_query($connection,$sql);
while($array=mysqli_fetch_array($result)){
$keyName[]=$array[0];}

if(@$id)
	{
	$sql="SELECT * FROM $dbTable where id='$id'";
	}
	else
	{
	if(isset($parkcode))
		{
		$sql="SELECT * FROM $dbTable where park='$parkcode' and `date_` like '$testYM%' order by date_ DESC";
		}
	}
//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("<br>Input form for $parkcode has not been created.<br><br>$sql.");


include("$fileMenu");
echo "<div align='center'><table>";
echo "<tr><th>Division of Parks and Recreation</th></tr>";

echo "<tr>
<td align='center'>Litter Report for ";
echo " <select name='parkcode' onChange=\"MM_jumpMenu('parent',this,0)\">"; 
echo "<option value='' selected>";        
for ($n=0;$n<count($parkCode);$n++)  
	{
	$scode=$parkCode[$n];
	if($scode==@$parkcode)
		{$s="selected";}
	else
		{
		$s="value";
		}
		if(!isset($month)){$month="";}
	echo "<option $s='$file?y=$y&parkcode=$scode&passM=$month'>$scode\n";
	}
	if(!isset($parkcode))
		{
		$parkcode="";
		$pc="";
		}
		else
		{$pc=$parkCodeName[$parkcode];}
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

while ($row=mysqli_fetch_assoc($result)){
$values[]=$row;
}// end while

//echo "<pre>";print_r($values);echo "</pre>";

// *************** Show Form ****************
// Headers
$message="";
if(!isset($e)){$e="";}
if($e===0){$message="<font color='red'>Entry NOT successful.</font>";}
if($e==1){$message="<font color='green'>Entry successful.</font>";}
if($e==2){$message="<font color='red'>Deletion successful.</font>";}

echo "<hr><form action='litter_insert.php' method='post' name='litterForm'><table cellpadding='1' border='1'>";

echo "<tr><td colspan='10' align='center'><font color='purple'>Litter Report for $parkcode for Year $y</font></td></tr>
<tr><td colspan='10' align='center'>$message</td></tr>";

echo "<tr>";
foreach($keyName as $k=>$v){
if($v!="id"){echo "<th>$v</th>";}
}
echo "</tr>";

echo "<tr>";
foreach($keyName as $k=>$v)
	{
	$RO="";
	if($v=="park")
		{$val=$parkcode;$RO="DISABLED";}
	else
		{$val=@$values[0][$v];
	if(@$id==""){$val="";}
	}
	
	
	if($v=="date_"){$size='10';}else{$size='6';}
	$echoThis="<td align='center'><input type='text' name='$v' value='$val' size='$size'$RO></td>";
	if($v=="comments"){$echoThis="<td align='center'><textarea name='$v' cols='25'  rows='3'>$val</textarea></td>";}
	
	if($v=="date_" AND @$id==""){$echoThis="<td align='center'><img src=\"../../jscalendar/img.gif\" id=\"f_trigger_c\" style=\"cursor: pointer; border: 1px solid red;\" title=\"Date selector\"
		  onmouseover=\"this.style.background='red';\" onmouseout=\"this.style.background=''\" /><br /><input type='text' name='date_' value='$val' size='10' id=\"f_date_c\" READONLY><br /></td>";}
		  
	if($v!="id"){echo "$echoThis";}
	}
echo "</tr>";

echo "<tr><td colspan='10' align='center'>In addition to other comments, please include special programs, grants received, unique partnerships and success stories in the comments field.</td></tr>";

if(@$id){$type="Update";$delete="<input type='submit' name='submit' value='Delete'><input type='hidden' name='id' value='$id'>";}else{$type="Enter";$delete="";}

echo "</table><table><tr><td>
<input type='hidden' name='yearPass' value='$y'>
<input type='hidden' name='parkPass' value='$parkcode'>
<input type='submit' name='submit' value='$type'>
$delete</tr>";//
echo "</form></table>";

if(@$id){echo "</body></html>";exit;}

// ********* Display previous data **************
echo "<hr><table cellpadding='1' border='1'>";

echo "<tr><td colspan='10' align='center'><font color='purple'>Previous Entries</font></td></tr>";

echo "<tr>";
foreach($keyName as $k=>$v){
if($v!="id"){echo "<th>$v</th>";}
}
echo "</tr>";

if(isset($values))
	{
	foreach($values as $k0=>$v0)
		{
		//echo "<pre>";print_r($v0);echo "</pre>";
		
		echo "<tr>";
		foreach($v0 as $k1=>$v1){
		if($k1=="park")
			{
			$val="<a href='litter_form.php?parkcode=$parkcode&id=$v0[id]'>$parkcode</a>";
			}
		else
			{
			$val="$v0[$k1]";
			}
		$echoThis="<td align='center'>$val</td>";
		if($k1!="id"){echo "$echoThis";}
		}
		echo "</tr>";
		
		}
	}

echo "</table></div>";

echo "<script type=\"text/javascript\">
    Calendar.setup({
        inputField     :    \"f_date_c\",     // id of the input field
        ifFormat       :    \"%Y-%m-%d\",      // format of the input field
        button         :    \"f_trigger_c\",  // trigger for the calendar (button ID)
        align          :    \"Tl\",           // alignment (defaults to \"Bl\")
        singleClick    :    true
    });
</script>";

echo "</body></html>";

?>