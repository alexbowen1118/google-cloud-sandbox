<?php
// This site is a complete mess. It was quickly thrown together by cloning the Staff Directive and then modifying. It needs major help. Tom H.

ini_set('display_errors',1);
$database="find";

session_start();
//echo "<pre>"; print_r($_SESSION); echo "</pre>"; //exit;
// echo "<pre>"; print_r($_REQUEST); echo "</pre>"; //exit;
if(empty($_REQUEST['searchterm']))
	{$_REQUEST['searchterm']="";}
	
IF($_REQUEST['searchterm']=="Merit Based" AND $_REQUEST['submit']=="Search")
	{
	$_SESSION['find']['level']=1;
	}

IF(@$_SESSION['find']['level']<1)
	{
	IF(empty($_SESSION))
		{
		include("../../include/auth.inc");
		}
		else
		{
		$_SESSION['find']['level']=1;
		$_SESSION['find']['tempID']="SIGN_DB";
		$_SESSION['find']['tempID']="Sec_Employ";
		}
	}


date_default_timezone_set('America/New_York');

include("../../include/iConnect.inc");// database connection parameters
mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

//print_r($_SERVER);exit;
//print_r($_SESSION);//exit;
//print_r($_REQUEST);//exit;
//$k=urldecode($_SERVER[QUERY_STRING]);
$level=$_SESSION['find']['level'];


include("menu.php");
if(@$submit=="Instructions"){$submit="Search";}

//echo "s=$submit";
//echo "<pre>";print_r($_REQUEST);print_r($_SESSION);echo "</pre>";//exit;
//********** SET Variables **********
$dbTable="forum";// TABLE NAME could be passed from URL, but we want to lock in a table for this file
@$checkName=$_SESSION['find']['tempID'];
//echo "c=$checkName";

//**** PROCESS  a Reply ******
if(@$submitReply=="Submit")
	{// no longer used
	$topic=html_entity_decode(htmlspecialchars_decode($topic));
	$submission=html_entity_decode(htmlspecialchars_decode($submission));
 	// $topic=htmlspecialchars_decode($topic);
// 	$submission=htmlspecialchars_decode($submission);
	
	$query = "INSERT INTO forum set topic='$topic',submitter='$checkName',submission='$submission', weblink='$weblink',weblink_2='$weblink_2',submisID='$submisID', personID='$checkName',dateCreate=now()";
	//,replier='$submitter'  this field changed to related
	//echo "$query";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
	$v=mysqli_insert_id($connection);
	
	$query = "UPDATE forum set submisID='$v' WHERE forumID='$submisID'";
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
	}

// FIELD NAMES are stored in $fieldArray
// FIELD TYPES and SIZES are stored in $fieldType
$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
$numFlds=mysqli_num_rows($result);
while ($row=mysqli_fetch_assoc($result))
{extract($row);$fieldArray[]=$row['Field'];$fieldType[]=$row['Type'];}

$recordIDfld=$fieldArray[$numFlds-1];

makeUpdateFields($fieldArray);// MAKE FIELD=VALUE FOR ADD/UPDATE

for($dk=0;$dk<count($fieldType);$dk++)
	{
	$varD=substr($fieldType[$dk],0,7);
	//if($varD=="decimal"){$fieldDecimal[]=$dk;}
	if($varD=="varchar"){$size=substr(substr($fieldType[$dk],8,7),0,-1);
	if($size>30){$size=30;}$fieldSize[]=$size;}
	else{$fieldSize[]=12;}
	}
//print_r($fieldSize);//exit;
 
//**** Process any Delete ******
if(@$submit=="Delete")
	{
	$query = "DELETE FROM $dbTable where $recordIDfld='$deleteRecordID'";
	//echo "$query";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Delete. $query");
	//header("Location: forum.php?action=del");
	//exit;
	}

//**** Formulate a Reply ******
if(@$submit=="reply")
	{
	$sql = "SELECT * from forum where forumID='$var' group by forumID";
	//echo "s=$sql";exit;
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query Select. $sql");
	$row=mysqli_fetch_array($result);extract($row);
	
	$displaySubmitter=substr($submitter,0,-2);
	echo "<table><tr><td colspan='3'>You are replying to this submission:</td></tr>
	<tr>
	<td>Topic: $topic</td>
	<td>Submitted by: $displaySubmitter</td></tr>
	<tr><td>Comment: $submission</td></tr>
	";
	
	echo "</table><hr>";
	//echo "<form action=\"forum.php\" method=\"post\">";
	echo "<form action=\"forum.php\"";// Used to debug
	
	echo "<table><tr><td colspan='3'>Enter your reply below:</td></tr>
	<tr><td>Reply from: $checkName</td></tr>
	<tr><td>Re: $topic</td></tr>
	<tr><td>Submission: <br><textarea name=\"submission\" cols=\"80\" rows=\"10\"></textarea></td></tr>";
	
	echo "<tr><td>Website(s):<input type=\"text\" name=\"weblink\" size=\"50\" value=\"\"></td></tr>";
	
	$pos=strpos($topic,"Re: ");
	if($pos>-1){$topic=$topic;}else{
	$topic="Re: ".$topic;}
	if($submisID>0){$forumID=$submisID;}
	echo "<td width='30%'>&nbsp;
	<input type='hidden' name='checkName' value='$checkName'>
	<input type='hidden' name='submitter' value='$submitter'>
	<input type='hidden' name='submisID' value='$forumID'>
	<input type='hidden' name='topic' value='$topic'>
	<input type='submit' name='submitReply' value='Submit'>
	</form></td>";
	
	echo "</tr></table>";
	exit;
	}
 

//**** Process any Edit or Add ******
if(@$submit=="Update")
	{
// 	echo "<pre>";print_r($_REQUEST);print_r($_SESSION);echo "</pre>";  //exit;
	$v=${$lastFld};
	//$updateThese="topic,submitter,submission,weblink";
	$updateThese="topic,submission,weblink_2,related,personID,submitter";
	$arr1=explode(",",$updateThese);
	for($j=0;$j<count($arr1);$j++)
		{
		$arr2=explode("=",$arr1[$j]);
		$arr3[]=$arr2[0];
		}
	for($j=0;$j<count($arr1);$j++)
		{
		@$val1=htmlspecialchars_decode($_REQUEST[$arr3[$j]]);
		$newQuery[$arr3[$j]]=$val1;
		}
	
	$arrKeys=array_keys($newQuery);
	$queryString=$arrKeys[0]."='".$newQuery[$arrKeys[0]]."'";
	for($j=1;$j<count($arrKeys);$j++)
		{
		
		switch ($arrKeys[$j])
			{
			case "dateCreate":
			$queryString.=", ".$arrKeys[$j]."=now()";
					break;	
			case "personID":
			$personID=$_SESSION['find']['tempID'];
			$queryString.=", ".$arrKeys[$j]."='$personID'";
				break;	
				default:
				$queryString.=", ".$arrKeys[$j]."='".$newQuery[$arrKeys[$j]]."'";
			}// end Switch
		
		}
	
	//echo "<pre>";print_r($arrKeys);print_r($newQuery);echo "</pre>$queryString<br>";
	if(!empty($_REQUEST['related']))
		{
		$related=$_REQUEST['related'];
		$var_r=explode(",",$related);
		foreach($var_r as $kr=>$vr)
			{
			$vr=trim($vr);
			$query = "Update $dbTable set related='$related'
	where $lastFld='$vr'";
	//echo "$query";exit;
			$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
			}
		
		}


	//print_r($newQuery);exit;
	$today=date('YmdHis');
	$query = "Update $dbTable set $queryString,timeMod='$today'
	where $lastFld='$v'";
// 	echo "$query";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
	
	$sql="SELECT forumID as checkID from category where forumID='$v'";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SELECT. $sql");
	$row=mysqli_fetch_array($result);extract($row);
	if($checkID!="")
		{
		if(!isset($acct_bud)){$acct_bud="";}
		if(!isset($admin_op)){$admin_op="";}
		if(!isset($apc)){$apc="";}
		if(!isset($dpr_ea)){$dpr_ea="";}
		if(!isset($hr)){$hr="";}
		if(!isset($ie)){$ie="";}
		if(!isset($law)){$law="";}
		if(!isset($safe)){$safe="";}
		if(!isset($ware)){$ware="";}
		if(!isset($other)){$other="";}
		$sql = "Update category set acct_bud='$acct_bud',admin_op='$admin_op',apc='$apc',dpr_ea='$dpr_ea',hr='$hr',ie='$ie',law='$law',safe='$safe',ware='$ware',other='$other'
		where $lastFld='$v'";
		//echo "$query";exit;
		$result = mysqli_query($connection,$sql) or die ("Couldn't execute query Update. $query");
		}
	else
		{
		$query = "INSERT into category set acct_bud='$acct_bud',admin_op='$admin_op',apc='$apc',dpr_ea='$dpr_ea',hr='$hr',ie='$ie',law='$law',safe='$safe',ware='$ware',other='$other',forumID='$v'";
		//echo "$query";exit;
		$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
		}
	
	//header("Location: forum.php?dbTable=$dbTable&$lastFld=$v");
	//exit;
	}

//**** Process any Add ******
if(@$submit=="Add")
	{
	// note capital A, see add with lower case
	$arr1=explode(",",$updateFields);
	for($j=0;$j<count($arr1);$j++)
		{
		$arr2=explode("=",$arr1[$j]);
		$arr3[]=$arr2[0];
		}
	for($j=0;$j<count($arr1);$j++)
		{
		@$val1=htmlspecialchars_decode($_REQUEST[$arr3[$j]]);
		$newQuery[$arr3[$j]]=$val1;
		}
	
	$arrKeys=array_keys($newQuery);
	$queryString=$arrKeys[0]."='".$newQuery[$arrKeys[0]]."'";
	for($j=1;$j<count($arrKeys);$j++){
	
	switch ($arrKeys[$j])
		{
				case "dateCreate":
		$queryString.=", ".$arrKeys[$j]."=now()";
					break;	
				case "timeMod":
		$queryString.=", ".$arrKeys[$j]."=now()";
					break;	
				case "personID":
		$personID=$_SESSION['find']['tempID'];
		$queryString.=", ".$arrKeys[$j]."='$personID'";
					break;	
					default:
					$queryString.=", ".$arrKeys[$j]."='".$newQuery[$arrKeys[$j]]."'";
		}// end Switch
	}
	
	//echo "<pre>";print_r($arrKeys);print_r($newQuery);echo "</pre>$queryString<br>";
	
	//print_r($queryString);exit;
	// the forumID autoincrements and the submisID is always 0, neither should be specified
	$queryString = str_replace("submisID='', ", "", $queryString);
	$queryString = str_replace(", forumID=''", "", $queryString);
	$query = "INSERT INTO $dbTable set $queryString";
	//echo "$query";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Insert. $query");
	$v=mysqli_insert_id($connection);
	
	if(!isset($acct_bud)){$acct_bud="";}
	if(!isset($admin_op)){$admin_op="";}
	if(!isset($apc)){$apc="";}
	if(!isset($dpr_ea)){$dpr_ea="";}
	if(!isset($hr)){$hr="";}
	if(!isset($ie)){$ie="";}
	if(!isset($law)){$law="";}
	if(!isset($safe)){$safe="";}
	if(!isset($ware)){$ware="";}
	if(!isset($other)){$other="";}
	$query = "INSERT into category set acct_bud='$acct_bud',admin_op='$admin_op',apc='$apc',dpr_ea='$dpr_ea',hr='$hr',ie='$ie',law='$law',safe='$safe',ware='$ware',other='$other',forumID='$v'";
	//echo "$query";exit;
	$result = mysqli_query($connection,$query) or die ("Couldn't execute query Update. $query");
	
	if(@$difFile)
		{
		header("Location: forumReport.php?dbReport=Center&center=$center&submit=Submit&rccYN=Y");
		}
	else
		{
		//ECHO "test";exit;
		//header("Location: /find/forum.php?action=Add");
		}
	//exit;
	}


//**** Prepare To Find, Update OR Delete******
$catFlds="category.acct_bud,category.admin_op,category.apc,category.dpr_ea,category.hr,category.ie,category.law,category.safe,category.ware,category.other";
$cat_array=array("acct_bud","admin_op","apc","dpr_ea","hr","ie","law","safe","ware","other");
if(@$lastFld)
	{
	//print_r($_REQUEST);exit;
	$formType="Update";
	@$passSQLedit=urlencode($passSQL);
	
	$addDeleteButton="<td><form><input type='hidden' name='passSQL' value='$passSQLedit'>
	<input type='hidden' name='$lastFld' value='$var'>
	<input type='hidden' name='deleteRecordID' value='$var'>
	<input type='submit' name='submit' value='Delete' onClick='return confirmLink()'></form></td>";
	
	
	
	$sql0 = "SELECT forum.*,map.mid,map.filename, $catFlds from $dbTable
	LEFT JOIN map ON map.forumID = forum.forumID
	LEFT JOIN category on category.forumID=forum.forumID
	where forum.$lastFld='$var'
	order by mid"; 
// 	echo "$sql0";
	$result = mysqli_query($connection,$sql0) or die ("Couldn't execute query 0. $sql0");
	while($row=mysqli_fetch_array($result))
		{
		extract($row);
		//$midArray[$filename]=$mid;
		$fileArray[$mid]=$filename;
//		$file_link_Array[$mid]=$file_link;
		}
	//print_r($row);exit;
//	print_r($fileArray);echo "<br />$sql0<br />"; print_r($file_link_Array);//exit;
	}


if(@$submit=="add")
	{
	$addButton="<td>
	<input type='submit' name='submit' value='Add'>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}

//if($submit=="find"){
// echo "<form action=\"forum.php\" method=\"post\">";

$ckAB="";
$ckAO="";
$ckAPC="";
$ckDPR="";
$ckHR="";
$ckIE="";
$ckLAW="";
$ckSAFE="";
$ckWARE="";
$ckOT="";
if(@$acct_bud){$ckAB="checked";}
if(@$admin_op){$ckAO="checked";}
if(@$apc){$ckAPC="checked";}
if(@$dpr_ea){$ckDPR="checked";}
if(@$hr){$ckHR="checked";}
if(@$ie){$ckIE="checked";}
if(@$law){$ckLAW="checked";}
if(@$safe){$ckSAFE="checked";}
if(@$ware){$ckWARE="checked";}
if(@$other){$ckOT="checked";}

echo "<div align='center'><form action=\"forum.php\" method=\"post\"><table border='1'><tr><td>Accounting/Budget <input type='checkbox' name='acct_bud' value='1' $ckAB></td><td>Admin_Operations <input type='checkbox' name='admin_op' value='1' $ckAO></td><td>Admin Pro Council (APC)<input type='checkbox' name='apc' value='1' $ckAPC></td><td>DPR Events/Articles <input type='checkbox' name='dpr_ea' value='1' $ckDPR></td><td>Human Resources <input type='checkbox' name='hr' value='1' $ckHR></td></tr>";

echo "<tr><td align='right'>I & E <input type='checkbox' name='ie' value='1' $ckIE></td><td align='right'>Law Enforcement <input type='checkbox' name='law' value='1' $ckLAW></td><td align='right'>Safety <input type='checkbox' name='safe' value='1' $ckSAFE></td><td align='right'>Warehouse <input type='checkbox' name='ware' value='1' $ckWARE></td><td align='right'>Other <input type='checkbox' name='other' value='1' $ckOT></td></tr></table>";

echo "<table align='center' border='1'><tr><td>View most recent 50 <a href='forum.php'>Forum entries</a></td><td>Enter search term: 
<input type='text' name='searchterm'>
<input type='submit' name='submit' value='Search'></form></td></tr></table></div>";
//exit;}

if(@$submit=="add" || @$submit=="edit")
	{
	if($submit=="add" and $level<1)
		{echo "<br><br><font color='red'>You do not the access level necessary to add an item.</font>";exit;}
		
	$temp_ID=$checkName;
	if($checkName=="Nealson7511")
		{
		$temp_ID="Dowdy5456";
		$personID="Dowdy5456";
		}
	if($checkName=="Williams5894" or $checkName=="Mitchener8455")
		{
		$temp_ID="Mitchener8455";
		$personID="Mitchener8455";
		}
	if($submit=="edit" AND ($temp_ID!=$personID) AND $level<4)
		{echo "<br><br><font color='red'>Reminder: You can only edit/delete messages which you have added.</font>";exit;}
	echo "<form action=\"forum.php\" method=\"post\" name='findForm'>";

	// Set fieldSizes - if not defined then it defaults
	$fieldSize[0]=55;// topic
	$fieldCol[2]=120;// col width submission
	$fieldRow[3]=15;// number of rows submission
	$fieldSize[3]=100;// weblink
	
	@$fileTitle=${$fieldArray[0]};
	$fileTitle=str_replace(" ","_",$fileTitle);
	$fileTitle=str_replace("\"","",$fileTitle);
	$fileTitle=str_replace("\'","",$fileTitle);
	
	$rem="<table><tr><td>1. Enter your Topic.<br> 2. Type in your Submission.<br>3. If you would like to attach a document, first Add the topic and then you will be able to add any documents.</td></tr></table><hr>";
	echo "$rem";
	
	//print_r($fieldArray);
	
	echo "<table><tr><td>$fieldArray[0]: ";
	@$topic=${$fieldArray[0]};
	echo "<textarea name=\"topic\" cols=\"40\" rows=\"1\">$topic</textarea>
	</td></tr>";
	
	if(!isset($weblink_2)){$weblink_2="";}
	if(!isset($submission)){$submission="";}
	echo "<tr><td colspan='3'>$fieldArray[2]: ";
	echo "<br><textarea name=\"$fieldArray[2]\" cols=\"$fieldCol[2]\" rows=\"$fieldRow[3]\">${$fieldArray[2]}</textarea></td></tr>";
	echo "<tr><td>Link to a web page(s): <textarea name=\"weblink_2\" cols=\"100\" rows=\"2\">$weblink_2</textarea><br />separate multiple links with a comma - ,</td></tr>";

if($level>4)
	{
	if(!isset($related)){$related="";}
	echo "<tr><td>Related FIND Number(s): <input type='text' name='related' value='$related'></td></tr>";
	}
	
	echo "</table>";
	if(@$acct_bud){$ckAB="checked";}
	if(@$admin_op){$ckAO="checked";}
	if(@$apc){$ckAPC="checked";}
	if(@$dpr_ea){$ckDPR="checked";}
	if(@$hr){$ckHR="checked";}
	if(@$ie){$ckIE="checked";}
	if(@$law){$ckLAW="checked";}
	if(@$safe){$ckSAFE="checked";}
	if(@$ware){$ckWARE="checked";}
	if(@$other){$ckOT="checked";}
	echo "<table border='1'><tr><td>Accounting/Budget <input type='checkbox' name='acct_bud' value='1' $ckAB></td><td>Admin_Operations <input type='checkbox' name='admin_op' value='1' $ckAO></td><td>Admin Pro Council <input type='checkbox' name='apc' value='1' $ckAPC></td><td>DPR Events/Articles <input type='checkbox' name='dpr_ea' value='1' $ckDPR></td><td>Human Resources <input type='checkbox' name='hr' value='1' $ckHR></td></tr>";
	
	echo "<tr><td align='right'>I & E <input type='checkbox' name='ie' value='1' $ckIE></td><td align='right'>Law Enforcement <input type='checkbox' name='law' value='1' $ckLAW></td><td align='right'>Safety <input type='checkbox' name='safe' value='1' $ckSAFE></td><td align='right'>Warehouse <input type='checkbox' name='ware' value='1' $ckWARE></td><td align='right'>Other <input type='checkbox' name='other' value='1' $ckOT></td></tr></table>";
	
	$checkName=$_SESSION['find']['tempID'];
	if(!isset($addButton)){$addButton="";}
	if(!isset($passSQLedit)){$passSQLedit="";}
	if(!isset($lastFld)){$lastFld="";}
	if(!isset($var)){$var="";}
	echo "<table><tr>
	<td><input type='hidden' name='dbTable' value='$dbTable'><input type='hidden' name='passSQL' value='$passSQLedit'>
	<input type='hidden' name='lastFld' value='$lastFld'>
	<input type='hidden' name='recordIDfld' value='$lastFld'>
	<input type='hidden' name='personID' value='$checkName'>
	<input type='hidden' name='submitter' value='$checkName'>
	<input type='hidden' name='var' value='$var'>
	</td>
	<td>$addButton</td>
	";
// 	echo "c=$checkName t=$personID tx=$temp_ID l=$level";
	if((@$submit=="edit" AND $checkName==$personID) OR ($submit=="edit" AND $level>3))
		{
		echo "
		<td width='30%'><input type='hidden' name='checkName' value='$checkName'>
		<input type='hidden' name='$lastFld' value='$var'>
		<input type='hidden' name='lastFld' value='$lastFld'>
		<input type='hidden' name='personID' value='$checkName'>
		<input type='submit' name='submit' value='Update'></form></td>
		<td>&nbsp;<form>$addDeleteButton</form></td></tr></table>";
		
		echo "<hr><table><tr bgcolor='brown'><td></td></tr>";
//		if(!empty($weblink) or !empty($file_link_Array))
		if(!empty($fileArray))
			{
			$fileTitle=""; $exist="y";
			$split=explode(",",$weblink);
			$listFiles="<tr><td>Existing documents (Click on doc to delete.):<br />";
			foreach($fileArray as $k=>$v)
				{
				$deleteLink="[<a href='deleteDoc.php?forumID=$forumID&mid=$k'  onclick=\"return confirm('Are you sure you want to delete this Document?')\"'>$fileArray[$k]</a>] ";
				$listFiles.=$deleteLink."<br /><br />";
				}
			
			echo "$listFiles</td></tr></table>";
			}
		
		if(!isset($exist)){$exist="";}
		echo "<table><tr><td>
			<form method='POST' action='add_multi.php' enctype='multipart/form-data'>
			<br />Click the BROWSE button and select your JPEG, PDF, WORD or EXCEL file. You can upload from 1 to 5 files at a time.
			<INPUT TYPE='hidden' name='exist' value='$exist'>
			<INPUT TYPE='hidden' name='forumID' value='$var'>
			<INPUT TYPE='hidden' name='admin' value='graphic'></td></tr>";
			
			for($i=1;$i<6; $i++)
				{
				echo "<tr><td>Upload File $i 
				<input type='file' name='map[$i]'  size='40'>	
				</td></tr>";
				}
		
		echo "<tr><td align='right'>Then click this button. <input type='submit' name='submit' value='Add File(s)'></form></td></tr></table>";
		exit;
		}
	
	echo "</tr></table>";
	EXIT;
	}


echo "</tr></table>";

  // ***** Pick display function and set sql statement

$co=count($_REQUEST); //print_r($_REQUEST);echo "c=$co";exit;

$from="*";// Default - gets used if not Group By

// ******* Group By Variables*******
// *** Make list of Fields to pass to GroupBy and Function forumHeader
$passFields=$fieldArray[0];
for($pf=1;$pf<count($fieldArray);$pf++){
$passFields.=",".$fieldArray[$pf];
}


$from.=" From $dbTable";

// ********* Assign passed Values by Field
for($j=0;$j<count($fieldArray);$j++)
	{
	$passVal[$j]=@${$fieldArray[$j]};
	}

//                     **************************         Create WHERE statement
for($k=0;$k<count($fieldArray);$k++)
	{
	if($passVal[$k]!="")
		{
		$dbFld=$fieldArray[$k];
// 		$dbVal=htmlspecialchars_decode($passVal[$k]);
		$dbVal=$passVal[$k];
		if(@$like[$k]==""){@$where.=" and $dbFld = '$dbVal'";}
		if(@$like[$k]==1){$where.=" and $dbFld like '%$dbVal%'";}
		if(@$like[$k]==2){
		$rangeDate=explode("*",$dbVal);
		if($rangeDate[0]!=""&&$rangeDate[1]==""){$where.=" and $dbFld='$rangeDate[0]'";}
		else{$where.=" and $dbFld>='$rangeDate[0]' and $dbFld<='$rangeDate[1]'";}
		}
		if(@$like[$k]=="3"){$where.=" and $dbFld != '$dbVal'";}
		
		}// order by $dbFld
	}// end for loop

if(@$where==" WHERE 1"){exit;}
if(@$where==" WHERE 1"&&$g=='Group by '&&$passSQL==''){exit;}

$sql="SET  group_concat_max_len = 2048";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SET $sql");
	
if(@$submit=="Search" || @$submit=="Go")
	{
//	echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
	
		$from="t1.*,group_concat(t2.link SEPARATOR ',') as weblink
from forum as t1";
		@$JOIN="left join map as t2 on t2.forumid=t1.forumid";
		if(!empty($forumID))
			{
			$go=explode(",",$forumID);
			
			if(in_array("133",$go))
				{
				if($level<5)
					{
					echo "Contact database.support@ncparks.gov if you need access to FIND 133 - DPR LOGO \"Naturally Wonderful\".";
					exit;
					}
				}
			foreach($go as $gk=>$gv)
				{
				@$go_clause.="t1.forumID='$gv' OR ";
				}
				$go_clause=rtrim($go_clause," OR ");
			$where="where $go_clause group by t1.forumID";
			}
			else
			{
			
			
			
			$a=$searchterm;
		if(strpos(strtolower($a),"dpr logo")>-1)
			{
			if($level<5)
				{
				echo "Contact database.support@ncparks.gov if you need access to FIND 133 - DPR LOGO \"Naturally Wonderful\".";
				exit;
				}
			}
		$varSearch=explode(" ",$a);
			$where="where ";
			foreach($varSearch as $k=>$v)
				{
				$where.="(topic like '%$v%' or submission like '%$v%' or submitter like '%$v%' or weblink like '%$v%') AND ";
				}
				$where=rtrim($where," AND ");
		
		
		foreach($_POST as $k1=>$v1)
			{

			if(in_array($k1, $cat_array))
				{
				$temp_array[]="$k1";
				}
				
			}
		if(!empty($temp_array))
			{	
			$JOIN_1="left join category as t3 on t3.forumID=t1.forumid";
			$where.="and (";
			foreach($temp_array as $k1=>$v1)
				{
				$where.=" t3.$v1='1' OR ";
				}
			
			$where=rtrim($where," OR ");
			$where.=")";
			}
		$where.=" group by t1.forumID";
			
		}
	
		

	
	if(!isset($JOIN)){$JOIN="";}
	if(!isset($JOIN_1)){$JOIN_1="";}
	$sql1 = "SELECT $from 
	$JOIN
	$JOIN_1
	$where 
	
	order by timeMod DESC";
// echo "<pre>"; print_r($_POST); echo "</pre>";
// echo "$sql1<br /><br />"; //exit;
	}
if(@$passSQL){$sql1=urldecode($passSQL);}

//echo "$sql1<br>";//echo "<pre>";print_r($fieldArray);echo "</pre>";exit;
if(!isset($where)){$where="";}
//echo "$where<br>";

if(@$sql1)
	{
	// ********** This displays the result **********
	include_once("forumFunctions.php");
	
	echo "<table border='1' cellpadding='3'>";
	
	echo "<form><tr>
	<th align='right' colspan='8'>Find Number(s): <input type='text' name='forumID' size='12'><input type='submit' name='submit' value='Go'> List multiple numbers separated by a comma.</th></tr></form><tr>";
	//if($checkName!=$personID){exit;}
	
	$result = mysqli_query($connection,$sql1) or die ("Couldn't execute query SQL1 2. $sql1");
	$num=mysqli_num_rows($result);
	
	//echo "$sql1";
	if($num>100)
		{
		$sql1 .= " limit 100";
		$result = mysqli_query($connection,$sql1) or die ("Couldn't execute query SQL1 3. $sql1");
		echo "<hr><font color='red'>$num</font> records were found. However, only the first <font color='red'>100</font> are being displayed. Let Tom Howard know if you need to view more than 100 at a time.<br>";
		}
		else
		{
		echo "<hr><font color='green'>$num Items Found</font>";
	}
	
	while ($row=mysqli_fetch_array($result))
		{//extract($row);
		//print_r($row);//exit;
		itemShow($row);
		}
	exit;
	}// end sql1

// ********** This displays all entries in DESC order **********
$order_by="order by t1.timeMod desc
";
if(@$limit=="all")
	{
	$show="<font color='brown'>Showing all entries.</font>";
	$limit="";
	$order_by="";
	}
	else
	{
	$show="<font color='brown'>Showing only the most recent 50 entries.</font> Show <a href='/find/forum.php?limit=all'>All</a>";$limit="LIMIT 50";
	}

// old query that caused issues resulting from move from 195 to zLinux
// links to uploaded files did not always work properly
//$sql1 = "SELECT *,substring_index(weblink,'=',-1) as dirNum from forum order by timeMod desc $limit";

$query = "SET SESSION group_concat_max_len=6144;";
$result = mysqli_query($connection,$query) or die ("Couldn't execute query	SHOW1. $sql");

$sql1="SELECT t1.*,group_concat(t2.link SEPARATOR ',') as weblink_1
from forum as t1
left join map as t2 on t2.forumid=t1.forumid
where 1 
group by t1.forumID
$order_by
$limit";
//echo "$sql1";

if($checkName=="Howard6318") // used for testing Howard6319
	{
//	$sql1=$sql2;
	echo "$sql1";
	$result = mysqli_query($connection,$sql1) or die ("Couldn't execute query SQL1. $sql1");
	while ($row=mysqli_fetch_array($result))
		{
		$ARRAY[]=$row;
		}
		$test=$ARRAY[0]['weblink_1'];
		$exp=explode(",",$test);
	echo "<pre>"; print_r($exp); echo "</pre>"; // exit;
	exit;
	}

include_once("forumFunctions.php");

echo "<table><tr><th>The FIND Forum: </th><th>Information to help you manage your state park operations.</th></tr>
</table>";

echo "<table border='1' cellpadding='5'><tr>";

$sql = "SHOW COLUMNS FROM $dbTable";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query	SHOW1. $sql");
$numFlds=mysqli_num_rows($result);

$result = mysqli_query($connection,$sql1) or die ("Couldn't execute query SQL1. $sql1");
$num=mysqli_num_rows($result);

if($limit==""){$show.=" ".$num;}
echo "<form><tr><th align='center' colspan='2'>$show</th>
<th align='center' colspan='4'>Find Number(s): <input type='text' name='forumID' size='12'><input type='submit' name='submit' value='Go'><br />Separate numbers by a comma</th></tr></form>";

$ii=0;
while ($row=mysqli_fetch_array($result))
	{
	itemShow($row);
	}
exit;


echo "</table></body></html>";
//}


// **************  FUNCTIONS *******************

function makeUpdateFields($fieldArray)
	{
	global $updateFields;
	for($i=0;$i<count($fieldArray);$i++){
	if($i!=0){
	$updateFields.=",".$fieldArray[$i]."=$".$fieldArray[$i];}
	else
	{$updateFields.=$fieldArray[$i]."=$".$fieldArray[$i];}
	}// end for
	}// end makeUpdateFields

// Make Group By selection checkboxes

function makeGroupBySelect($fieldArray,$ckbx){
global $updateFields;
echo "<table>";
for($i=0;$i<count($fieldArray);$i++){
$t=fmod($i,6);
$name="ckbx[".$i."]";
if($ckbx[$i]==$fieldArray[$i]){$c="checked";}else{$c="";}
echo "<td>
<input type='checkbox' name='$name' value='$fieldArray[$i]' $c>$fieldArray[$i]</td>";
if($i!=0 and $t==0){echo "<tr></tr>";}
}// end for

echo "</table>";
}// end makeGroupBySelect
?>