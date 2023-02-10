<?php
extract($_REQUEST);

include("../../include/connectROOT.inc");// database connection parameters
$database="divper";
$db = mysqli_select_db($connection,$database)
or die ("Couldn't select database");
       
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;

//**** PROCESS  a Search ******
if(@$search=="Find" OR @$search=="edit")
	{
	//echo "<pre>"; print_r($_POST); echo "</pre>";  //exit;
//	echo "<pre>"; print_r($_REQUEST); echo "</pre>";  exit;
		$skip=array("search","PHPSESSID","form_type");
		$like=array("beacon_title","Fname","Lname");
		$t1_array=array("beacon_num");
		$where="where 1";
		foreach($_REQUEST as $k=>$v)
			{
			if(in_array($k,$skip)){continue;}
			if($v==""){continue;}
			$t="t1.";
			if($k=="Lname"){$t="t4.";}
			if($k=="cdl" AND $v=="N"){$v="";}
				if(in_array($k,$like))
					{
					$where.=" and (".$t."`".$k."` like '%".$v."%')";
					}
					else
					{$where.=" and ".$t."`".$k."`='".$v."'";}		
			}
	include_once("menu.php");
	}
//echo "$where";
//**** PROCESS  a Reply ******
if(@$search=="Update")
	{
	//echo "<pre>"; print_r($_POST); echo "</pre>";  //exit;
		$skip=array("add");
		foreach($_POST as $k=>$v){
			if(in_array($k,$skip)){continue;}
			$v=str_replace(",","",$v);
			$v=addslashes($v);
			if($k=="center_code"){$v=strtoupper($v);}
			$query.=$k."='".$v."',";
			}
			$query=rtrim($query,",");
	$query = "INSERT INTO cdl set $query"; //echo "$query";exit;
	$result = mysqli_query($connection, $query) or die ("Couldn't execute query Update. $query");
	$id=mysqli_insert_id($connection); //echo "$id";exit;
		$vi=str_pad($id,4,"0",STR_PAD_LEFT);
	$water_id="water".$vi;
	$query = "UPDATE water set water_id='$water_id' where id='$id'";
	//echo "$query";exit;
	$result = mysqli_query($connection, $query) or die ("Couldn't execute query Update. $query");
	
	header("Location: menu.php?form_type=cdl_required.php");
	exit;
	}

if($search=="edit")
	{
	$where="where t1.beacon_num='$beacon_num'";
	}
	
	
$sql = "SELECT t1.cdl, t1.beacon_num, t1.beacon_title, t1.code, t4.Fname, t4.Lname, t4.drivenum, t4.dbmonth, t4.dbday
from position t1
LEFT JOIN emplist as t3 on t1.beacon_num=t3.beacon_num
LEFT JOIN empinfo as t4 on t3.emid=t4.emid
$where
";//echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
while ($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	}
$fieldArray=array_keys($ARRAY[0]);

//echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;

$skip=array("id","water_id","comment");
$subName=array("cdl"=>"Position requires CDL<br />HR edit","beacon_num"=>"Position Number<br />HR edit","beacon_title"=>"Position Title<br />HR edit","code"=>"Park<br />HR edit","Fname"=>"First Name<br />HR edit","Lname"=>"Last Name<br />HR edit","drivenum"=>"License Number","dbmonth"=>"Birth Month<br />Employee edit","dbday"=>"Birth Day<br />Employee edit",);

$radio=array("cdl");
$radio_cdl=array("Y"=>"Yes","N"=>"No");

// Form Header
echo "<div id='add_form' align='center'>";

if(@$search!="edit")
	{
	echo "<table border='1' cellpadding='5'><tr><td align='center' colspan='2'>Commercial Drivers License
	<a onclick=\"toggleDisplay('show_form');\" href=\"javascript:void('')\"><a onclick=\"toggleDisplay('search_form');\" href=\"javascript:void('')\">Search</a></td></tr></table>";
	}

echo "</div>";

$action="Find";
if(@$search=="edit")
	{
	$display="block";
	$sql = "SELECT t1.cdl, t1.beacon_num, t1.beacon_title, t1.code, t4.Fname, t4.Lname, t4.drivenum, t4.dbmonth, t4.dbday
	from position t1
	LEFT JOIN emplist as t3 on t1.beacon_num=t3.beacon_num
	LEFT JOIN empinfo as t4 on t3.emid=t4.emid
	where t1.beacon_num='$beacon_num'
	";//echo "$sql";
	$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
	$row=mysqli_fetch_assoc($result);
	extract($row);
	$action="Update";
	}
	else
	{
	$display="none";
	}
//echo "<pre>"; print_r($row); echo "</pre>";
// Search/Edit Form
echo "<div align='center' id=\"search_form\" style=\"display: $display\"><table border='1' cellpadding='5'><tr><form name='frmSearch' action=\"menu.php?form_type=cdl_required\" method=\"post\"><td align='center' colspan='2'>$park_code</td></tr>";

$edit_array=array("drivenum");
foreach($fieldArray as $k=>$v)
	{
	if(in_array($v,$skip)){continue;}
	$val=""; $RO="";
	if($search=="edit")
		{
		$val=${$v};
		if($val=="" and $v=="cdl"){$val="N";}
		}
	if(in_array($v,$edit_array))
		{
		$input="<input type='text' size='30' name='$v' value='$val'>";
		}
		else
		{
		$input=$val;
		if(@$search=="" OR @$search=="Find")
			{			
			$input="<input type='text' size='30' name='$v' value=''>";
			}
		}
	if(in_array($v,$radio) AND (@$search=="Find" OR @$search==""))
		{
		$var=${"radio_".$v};
		$r_input="";
		foreach($var as $k1=>$v1)
			{
			if($val==$k1){$ck="checked";}else{$ck="";}
			$r_input.="$v1<input type='radio' name='$v' value='$k1' $ck> ";
			}
		$input=$r_input;
		}
	echo "<tr><td>$subName[$v]</td>
	<td>$input</td>
	</tr>";
	}

echo "<tr><td align='center' colspan='2' bgcolor='aliceblue'><input type='submit' name='search' value='$action'></td></tr>";
echo "</table></form></div>";

if(!isset($sort)){$sort="";}
if(!isset($search)){$search=""; exit;}
// Display



$orderBy="order by code";

include("../../include/connectROOT.inc");// database connection parameters
$database="divper";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

if(!isset($where)){$where="";}     
if(!isset($limit)){$limit="";}       


$sql = "SELECT t1.cdl, t1.beacon_num, t1.beacon_title, t1.code, t4.Fname, t4.Lname, t4.drivenum, t4.dbmonth, t4.dbday
from position as t1
LEFT JOIN emplist as t3 on t1.beacon_num=t3.beacon_num
LEFT JOIN empinfo as t4 on t3.emid=t4.emid
$where
order by t1.code
 "; 

//echo " $sql";

//echo "<br />$where <br />$passWhere";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql");
$num=mysqli_num_rows($result);
if($num<1){echo "No record found using $where";}

unset($ARRAY);
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	}
//echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;

if(isset($ARRAY))
	{
	$skip1=array("id","comment");
	
		echo "<div align='center'><table border='1' cellpadding='5'>";
			echo "<tr><th colspan='12'>CDL - $num</td></tr>";
	
		if($level==1){echo "<tr><th colspan='11'>$park_code</th></tr>";}
		
	echo "<tr>";
	foreach($ARRAY[0] as $k=>$v)
		{
		if(in_array($k,$skip1)){continue;}
		
		echo "<th>$k</th>";
		}
	echo "</tr>";
	
	foreach($ARRAY as $num=>$array)
		{
		echo "<tr>";
		foreach($array as $k=>$v)
			{
			if(in_array($k,$skip1)){continue;}
			$input=$v;
		//	if(in_array($k,$radio)){
		//		$var=${"radio_".$k};
		//		$input=$var[$v];
		//		}
			
			if($k=="cdl" AND $v=="")
				{
				$input="N";
				}
			if($k=="beacon_num")
				{
				$input="<a href='cdl_required.php?search=edit&beacon_num=$input'>$input</a>";
				}
				
			echo "<td align='center'>$input</td>";
			}
		echo "</tr>";
		}
	
	echo "</table></div>";
	}

echo "</html>";


?>