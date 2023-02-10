<?php
extract($_REQUEST);
//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
//echo "<pre>"; print_r($_SERVER); echo "</pre>"; // exit;
//echo "<pre>"; print_r($_SESSION); echo "</pre>";  //exit;

include("../../include/connectROOT.inc");// database connection parameters
$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
       

//**** PROCESS  a Search ******
if(@$search=="Find")
	{
	//echo "<pre>"; print_r($_POST); echo "</pre>";  //exit;
	//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;
		$skip=array("search","PHPSESSID","sort","form_type");
		$like=array("park_id","make","model_year","engine","vin","comment");
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
	$query = "INSERT INTO vehicle set $query"; //echo "$query";exit;
	$result = mysqli_query($connection, $query) or die ("Couldn't execute query Update. $query");
	$id=mysqli_insert_id($connection); //echo "$id";exit;
		$vi=str_pad($id,4,"0",STR_PAD_LEFT);
	$vehicle_id="DPR".$vi;
	$query = "UPDATE vehicle set vehicle_id='$vehicle_id' where id='$id'";
	//echo "$query";exit;
	$result = mysqli_query($connection, $query) or die ("Couldn't execute query Update. $query");
	
	header("Location: menu.php?form_type=inventory");
	exit;
	}

$dbTable="travel_log";
//if($form_type=="inventory"){$dbTable="vehicle";}

// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
while ($row=mysqli_fetch_assoc($result))
	{$fieldArray[]=$row['Field'];}

//echo "<pre>"; print_r($fieldArray); echo "</pre>";  exit;

$skip=array("id","vehicle_id","surplus");
$subName=array("vehicle_number"=>"Vehicle Number","month_year"=>"Month & Year","leave"=>"Left at","return"=>"Returned at","from"=>"Started from","to"=>"Went to","purpose"=>"Purpose","inspect"=>"Last Inspeciton Date","fuel_amt"=>"Fuel Added","mileage_out"=>"Trip Start Mileage","mileage_in"=>"Trip End Mileage","center"=>"Center Code","begin_odometer"=>"Start Odometer Reading","begin_odometer"=>"Start Odometer Reading","begin_odometer"=>"Start Odometer Reading","end_odometer"=>"Ending Odometer Reading","fuel_type"=>"Fuel Type","fuel_type_other"=>"Fuel Type Other");

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

echo "<tr><td align='center' colspan='2'>Travel Log
<a onclick=\"toggleDisplay('show_form');\" href=\"javascript:void('')\"><br />Add a Vehicle</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick=\"toggleDisplay('search_form');\" href=\"javascript:void('')\">Search</a></td>";

// For Level 1 with privileges
if($parkList[0]!="")
	{
		if($park_code AND in_array($park_code,$parkList)){
			$_SESSION['fuel']['select']=$park_code;
			}
		echo "<td><form><select name=\"center\" onChange=\"MM_jumpMenu('parent',this,0)\"><option selected></option>";
		foreach($parkList as $k=>$v){
			$con1="menu.php?form_type=inventory&park_code=$v";
			if($v==$_SESSION['fuel']['select']){
				$park_code=$v;
				$s="selected";}else{$s="value";}
			echo "<option $s='$con1'>$v\n";
			   }
	 echo "</select></td></form>";
	}

// Level 2

if($level==2)
	{
	include_once("../../include/parkRCC.inc");
	
	$distCode=$_SESSION['fuel']['select'];
	$menuList="array".$distCode; $parkArray=${$menuList};
	sort($parkArray);
	
			if($park_code AND in_array($park_code,$parkArray)){
				$_SESSION['fuel']['temp']=$park_code;
				}
			echo "<td><form><select name=\"center\" onChange=\"MM_jumpMenu('parent',this,0)\"><option selected></option>";
			foreach($parkArray as $k=>$v){
				$con1="menu.php?form_type=inventory&park_code=$v";
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
echo "<div align='center' id=\"show_form\" style=\"display: none\"><table border='1' cellpadding='5'><tr><form name='frmTest' action=\"travel_log_edit.php\" method=\"post\" onsubmit=\"return radio_button_checker()\"><td align='center' colspan='2'>$park_code</td></tr>";

	foreach($fieldArray as $k=>$v){
		if(in_array($v,$skip)){continue;}
		$val=""; $RO="";
		$input="<input type='text' size='30' name='$v' value='$val'$RO>";
		
		if($v=="center_code"){
			$val=$park_code;
			IF($level<3){$RO="READONLY";}
		$input="<input type='text' size='30' name='$v' value='$val'$RO>";
			
				if($level>2)
				{
				$database = "dpr_system";
				mysqli_select_db($connection,$database);
				$sql= "SELECT park_code AS parkCode FROM parkcode_names_district";
				$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql");
		//echo "$sql";
				while($row=mysqli_fetch_assoc($result))
					{
					$allParks[]=$row['parkCode'];
					
					$input="<select name=\"center_code\"><option selected></option>";
					foreach($allParks as $kk=>$vv)
						{
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
echo "<div align='center' id=\"search_form\" style=\"display: none\"><table border='1' cellpadding='5'><tr><form name='frmSearch' action=\"menu.php?form_type=travel_log_edit\" method=\"post\"><td align='center' colspan='2'>$park_code</td></tr>";

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


// Display
if($level==1){$where.=" and center_code='$park_code'";}

if($level==2){$where.=" and center_code='$park_code'";}

if($level>2 AND $search=="" AND $sort=="")
	{
	//$limit="limit 100";
	}

$orderBy="order by id desc";
if($sort=="cc"){$orderBy="order by center_code";}
if($sort=="p"){$orderBy="order by park_id";}
if($sort=="m"){$orderBy="order by make";}
if($sort=="mi"){$orderBy="order by mileage desc";}
if($sort=="my"){$orderBy="order by model_year";}
if($sort=="d"){$orderBy="order by duty";}
if($sort=="t"){$orderBy="order by trans";}
if($sort=="dr"){$orderBy="order by drive";}
if($sort=="f"){$orderBy="order by fuel";}
if($sort=="e"){$orderBy="order by emergency";}

if($_POST['submit']=="Find Data" AND $_SERVER['QUERY_STRING']=="form_type=travel_log_edit")
{EXIT;}

include("../../include/connectROOT.inc");// database connection parameters
$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");
       
 $sql= "SELECT * from $dbTable
 where surplus=''
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
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql");
$num=mysqli_num_rows($result);
if($num<1){echo "No vehicle found using $where";}

while($row=mysqli_fetch_assoc($result)){
	$ARRAY[]=$row;
	}
//echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;

if($ARRAY){

$skip1=array("id","surplus");

	echo "<div align='center'><table border='1' cellpadding='5'>";
		echo "<tr><th colspan='16'>On-Road Inventory - $num vehicles</td></tr>";

	if($level==1){echo "<tr><th colspan='11'>$park_code</th></tr>";}
	
	echo "<tr>";
	foreach($ARRAY[0] as $k=>$v){
		if(in_array($k,$skip1)){continue;}
	switch($k)
		{
    	case 'center_code';
        	$k="<a href='menu.php?search=Find&form_type=inventory&sort=cc$passWhere'>center code</a>";
    		break;
    	case 'park_id';
        	$k="<a href='menu.php?search=Find&form_type=inventory&sort=p$passWhere'>License Plate</a>";
    		break;
    	case 'mileage';
        	$k="<a href='menu.php?search=Find&form_type=inventory&sort=mi$passWhere'>$k</a>";
    		break;
    	case 'make';
        	$k="<a href='menu.php?search=Find&form_type=inventory&sort=m$passWhere'>$k</a>";
    		break;
    	case 'model_year';
        	$k="<a href='menu.php?search=Find&form_type=inventory&sort=my$passWhere'>$k</a>";
    		break;
    	case 'duty';
        	$k="<a href='menu.php?search=Find&form_type=inventory&sort=d$passWhere'>$k</a>";
    		break;
    	case 'trans';
        	$k="<a href='menu.php?search=Find&form_type=inventory&sort=t$passWhere'>$k</a>";
    		break;
    	case 'drive';
        	$k="<a href='menu.php?search=Find&form_type=inventory&sort=dr$passWhere'>$k</a>";
    		break;
    	case 'fuel';
        	$k="<a href='menu.php?search=Find&form_type=inventory&sort=f$passWhere'>$k</a>";
    		break;
    	case 'emergency';
        	$k="<a href='menu.php?search=Find&form_type=inventory&sort=e$passWhere'>$k</a>";
    		break;
 //   	default;
//        	$k="<a href='menu.php?form_type=inventory&s=e'>$k</a>";
 //   		break;
		}
		
		echo "<th>$k</th>";
	}
	echo "</tr>";
	
	foreach($ARRAY as $num=>$array){
		echo "<tr>";
		foreach($array as $k=>$v){
			if(in_array($k,$skip1)){continue;}
			$input=$v;
			if(in_array($k,$radio)){
				$var=${"radio_".$k};
				$input=$var[$v];
				}
				
			if($k=="vehicle_id"){
				$input="<a href='' onclick=\"return popitup('edit.php?vi=$input')\">$input</a>";
				}
				
			echo "<td align='center'>$input</td>";
			}
		echo "</tr>";
	}
	
	
	echo "</table></div>";
}

echo "</html>";


?>