<?php
extract($_REQUEST);
//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
if(!isset($park_code)){$park_code="";}

include("../../include/iConnect.inc");// database connection parameters
$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
       
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;

//**** PROCESS  a Search ******
if(@$search=="Find")
	{
	//echo "<pre>"; print_r($_POST); echo "</pre>";  //exit;
	//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;
		$skip=array("search","PHPSESSID","sort","form_type");
		$like=array("park_id","make","model_year","engine","vin");
		foreach($_REQUEST as $k=>$v){
			if(in_array($k,$skip)){continue;}
			if($v==""){continue;}
				if(in_array($k,$like)){
					$where.=" and (`".$k."` like '%".$v."%')";
					}
				else
				{$where.=" and `".$k."`='".$v."'";}		
			}
	include_once("menu.php");
	}
//echo "$where";
//**** PROCESS  a Reply ******
if(@$add=="Add")
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
	$query = "INSERT INTO dot_keys set $query"; //echo "$query";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
	// $id=mysqli_insert_id($connection); //echo "$id";exit;
// 		$vi=str_pad($id,4,"0",STR_PAD_LEFT);
// 	$dot_id="water".$vi;
// 	$query = "UPDATE water set dot_id='$water_id' where id='$id'";
// 	//echo "$query";exit;
// 	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
// 	
	header("Location: menu.php?form_type=dot_keys");
	exit;
	}

$dbTable="dot_keys";

// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql ".mysqli_error($connection));
while ($row=mysqli_fetch_assoc($result))
	{$fieldArray[]=$row['Field'];}

//echo "<pre>"; print_r($fieldArray); echo "</pre>";  exit;

$skip=array("id","dot_id");
$subName=array("dot_key"=>"DOT Key","dot_reported_description"=>"DOT Reported Description","dot_reported_year"=>"DOT Reported Year","dpr_agency"=>"DPR Agency Code","dot_reported_plate"=>"DOT Reported Plate","correct_park"=>"Correct Park","correct_description"=>"Correct Description","correct_plate"=>"Correct Plate","fas_num_if_no_plate"=>"FAS # if no plate","comment"=>"Comments");

// if modified, also make changes to edit.php
$text_flds=array("correct_description","comment");

if($level==1)
	{
	$parkList=explode(",",$_SESSION['fuel']['accessPark']);
	if($parkList[0]==""){$park_code=$_SESSION['fuel']['select'];}		
	}
	else
	{
	$parkList[0]="";
	}

// Form Header
echo "<div id='add_form' align='center'><table border='1' cellpadding='5'>";

echo "<tr><td align='center' colspan='2'>DOT Keys SPECIFICATIONS
<a onclick=\"toggleDisplay('show_form');\" href=\"javascript:void('')\"><br />Add a DOT Key</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick=\"toggleDisplay('search_form');\" href=\"javascript:void('')\">Search</a></td>";

// For Level 1 with privileges
	if($parkList[0]!=""){
		if($park_code AND in_array($park_code,$parkList)){
			$_SESSION['fuel']['select']=$park_code;
			}
		echo "<td><form><select name=\"center\" onChange=\"MM_jumpMenu('parent',this,0)\"><option selected></option>";
		foreach($parkList as $k=>$v){
			$con1="menu.php?form_type=dot_keys&park_code=$v";
			if($v==$_SESSION['fuel']['select']){
				$park_code=$v;
				$s="selected";}else{$s="value";}
			echo "<option $s='$con1'>$v\n";
    		   }
  	 echo "</select></td></form>";
	}

// Level 2

if($level==2){
include_once("../../include/parkRCC.inc");

$distCode=$_SESSION['fuel']['select'];
$menuList="array".$distCode; $parkArray=${$menuList};
sort($parkArray);

		if($park_code AND in_array($park_code,$parkArray)){
			$_SESSION['fuel']['temp']=$park_code;
			}
		echo "<td><form><select name=\"center\" onChange=\"MM_jumpMenu('parent',this,0)\"><option selected></option>";
		foreach($parkArray as $k=>$v){
			$con1="menu.php?form_type=dot_keys&park_code=$v";
			if($v==$_SESSION['fuel']['temp']){
				$park_code=$v;
				$s="selected";}else{$s="value";}
			echo "<option $s='$con1'>$v\n";
    		   }
  	 echo "</select></td></form>";
	
}

echo "</tr></table>
</div>";


// Input Form
echo "<div align='center' id=\"show_form\" style=\"display: none\"><table border='1' cellpadding='5'><tr><form name='frmTest' action=\"dot_keys.php\" method=\"post\" onsubmit=\"return radio_button_checker()\"><td align='center' colspan='2'>$park_code</td></tr>";

	foreach($fieldArray as $k=>$v){
		if(in_array($v,$skip)){continue;}
		$val=""; $RO="";
		$input="<input type='text' size='30' name='$v' value='$val'$RO>";
		
		if($v=="correct_park"){
			$val=$correct_park;
			IF($level<3){$RO="READONLY";}
		$input="<input type='text' size='30' name='$v' value='$val'$RO>";
			
				if($level>2)
					{		
					$db = mysqli_select_db($connection,"dpr_system")
					 or die ("Couldn't select database");
	
					$sql= "SELECT park_code AS parkCode FROM parkcode_names_district";
					$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
			//echo "$sql";
					while($row=mysqli_fetch_assoc($result)){
					$allParks[]=$row['parkCode'];
					
					$input="<select name=\"center_code\"><option selected></option>";
			foreach($allParks as $kk=>$vv){
				$vv=strtoupper($vv);
				$con1=$vv;
				if($vv==$park_code){
					$park_code=$vv;
					$s="selected";}else{$s="value";}
				$input.="<option $s='$con1'>$vv\n";
				   }
		 $input.="</select>";
						}
					}
			}
		if(in_array($v,$radio)){
			$var=${"radio_".$v};
			$r_input="";
				foreach($var as $k1=>$v1){
			$r_input.="$v1<input type='radio' name='$v' value='$k1'> ";
					}
			$input=$r_input;
			}
		echo "<tr><td>$subName[$v]</td>
		<td>$input</td>
		</tr>";
		}

echo "<tr><td align='center' colspan='2' bgcolor='lightgreen'><input type='submit' name='add' value='Add'></td></tr>";
echo "</table></form></div>";


// Search Form
echo "<div align='center' id=\"search_form\" style=\"display: none\"><table border='1' cellpadding='5'><tr><form name='frmSearch' action=\"menu.php?form_type=dot_keys\" method=\"post\"><td align='center' colspan='2'>$park_code</td></tr>";

	foreach($fieldArray as $k=>$v){
		if(in_array($v,$skip)){continue;}
		$val=""; $RO="";
		if($v=="center_code"){
			$val=$park_code;
			IF($level==1){$RO="READONLY";}
			}
		$input="<input type='text' size='30' name='$v' value='$val'$RO>";
		if(in_array($v,$radio)){
			$var=${"radio_".$v};
			$r_input="";
				foreach($var as $k1=>$v1){
			$r_input.="$v1<input type='radio' name='$v' value='$k1'> ";
					}
			$input=$r_input;
			}
		echo "<tr><td>$subName[$v]</td>
		<td>$input</td>
		</tr>";
		}

echo "<tr><td align='center' colspan='2' bgcolor='aliceblue'><input type='submit' name='search' value='Find'></td></tr>";
echo "</table></form></div>";

if(!isset($sort)){$sort="";}
if(!isset($search)){$search="";}
// Display
if($level==1){$where.=" and center_code='$park_code'";}

if($level==2){$where.=" and center_code='$park_code'";}

if($level>2 AND $search=="" AND $sort=="")
	{
	//$limit="limit 100";
	}

$orderBy="order by dot_id ";
if($sort=="di"){$orderBy="order by dot_id";}
if($sort=="cc"){$orderBy="order by correct_park";}
if($sort=="m"){$orderBy="order by dot_reported_description";}
if($sort=="my"){$orderBy="order by dot_reported_year";}

if($sort=="dr"){$orderBy="order by dot_reported_plate";}

//include("../../include/connectROOT.inc");// database connection parameters
$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

if(!isset($where)){$where="";}     
if(!isset($limit)){$limit="";}       

$flds="
t1.`dot_id`, t1.`dot_key`, t1.`dot_reported_description`, concat(t2.`year`,' ',t2.`make`,' ',t2.`model`) as dpr_description, t1.`correct_description`, t1.`dot_reported_year`, t1.`dpr_agency`, t1.`dot_reported_plate`, t1.`correct_plate`, t2.center_code, t1.`correct_park`, t1.`fas_num_if_no_plate`, t1.`comment`";

 $sql= "SELECT $flds from $dbTable as t1
 left join vehicle as t2 on t1.dot_reported_plate=t2.license
 where 1
 $where
 $orderBy
 $limit
 "; 

//echo " $sql s=$ts";
$passWhere=str_replace("and ","&",$where);
$passWhere=str_replace("'","",$passWhere);
$passWhere=str_replace("`","",$passWhere);
$passWhere=str_replace(" ","",$passWhere);
//echo "<br />$where <br />$passWhere";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
$num=mysqli_num_rows($result);
if($num<1){echo "No DOT Key found using $where";}

while($row=mysqli_fetch_assoc($result)){
	$ARRAY[]=$row;
	}
//echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;

if(isset($ARRAY))
	{	
	$skip1=array("id");
	
		echo "<div align='center'><table border='1' cellpadding='5'>";
			echo "<tr><th colspan='13'>$num DOT Keys</td></tr>";
	
		if($level==1){echo "<tr><th colspan='11'>$park_code</th></tr>";}
		
		echo "<tr>";
		foreach($ARRAY[0] as $k=>$v){
			if(in_array($k,$skip1)){continue;}
		switch($k)
			{
			case 'dot_id';
				$k="<a href='menu.php?search=Find&form_type=dot_keys&sort=di$passWhere'>$k</a>";
				break;
			case 'correct_park';
				$k="<a href='menu.php?search=Find&form_type=dot_keys&sort=cc$passWhere'>$k</a>";
				break;
			case 'dot_reported_description';
				$k="<a href='menu.php?search=Find&form_type=dot_keys&sort=m$passWhere'>$k</a>";
				break;
			case 'dot_reported_year';
				$k="<a href='menu.php?search=Find&form_type=dot_keys&sort=my$passWhere'>$k</a>";
				break;
			case 'dot_reported_plate';
				$k="<a href='menu.php?search=Find&form_type=dot_keys&sort=dr$passWhere'>$k</a>";
				break;
			}
			
			echo "<th>$k</th>";
		}
		echo "</tr>";
		
		foreach($ARRAY as $num=>$array){
			echo "<tr>";
			foreach($array as $k=>$v){
				if(in_array($k,$skip1)){continue;}
				$input=$v;
					
				if($k=="dot_key" and $level>4){
					$input="<a href='' onclick=\"return popitup('dot_key_edit.php?dot_key=$v')\">$input</a>";
					}
					
				echo "<td align='center'>$input</td>";
				}
			echo "</tr>";
		}
		
		
		echo "</table></div>";
	}

echo "</html>";


?>