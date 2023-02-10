<?php 
$database="staffdir"; 
$dbName="staffdir";
include("../../include/auth.inc");
include("../../include/iConnect.inc");
// extract($_REQUEST);

include("menu.php");
// Process input
mysqli_select_db($connection,$database);
// *********** SEARCH **********
if(@$Submit =="Search")
	{
	
	// Create the WHERE clause
	if(!@$v)
		{
		if(@$mode=="nl")
			{$where = "WHERE MATCH (title,body,attach) AGAINST ('$query')";}
		if(@$mode=="b")
			{$where = "WHERE MATCH (title,body,attach) AGAINST ('+$query' in boolean mode)";}
		}
		else
		{
		$where = "ORDER BY dirNum DESC";
		}
	
	if(@$findSD)
		{
		$where = "WHERE (title LIKE '%$findSD%' or body LIKE '%$findSD%' or attach LIKE '%$findSD%')";
		$query=$findSD;
		}
	if(@$dirNum){$where = "WHERE (directive.dirNum LIKE '$dirNum%')";$query=$dirNum;}
	
	
	$sql = "SELECT * From directive $where";
	
	$result = @mysqli_query($connection,$sql) or die("$sql<br>Error 1 #". mysqli_errno($connection) . ": " . mysqli_error($connection));
	//echo "$sql"; //exit;
	$numrow = mysqli_num_rows($result);
	if($numrow < 1){echo "No Staff Directive found using: <font color='blue'>$query</font>
	<br><br>There are two possibilities:<br>1. Neither the Subject nor the Body nor any Attachements of any Staff Directive contains that search term
	<br>&nbsp;&nbsp;&nbsp;OR<br>
	2. That search term is so common that more than 50% of all Staff Directives contain it.<br>a. Try using a word, or term that is more specific. <br>b. If you would still like to search for that term, use the Binary search method.<hr>";
	
	 exit;}
	
	if($numrow>1)
		{
		if(@$v==1)
			{
			$a="The database contains $numrow SDs.";
			
			$sql = "SELECT count(dirNum) as num,left(dirNum,4) as year
			From directive where 1 group by left(dirNum,4)
			order by year desc";
			
			$result = @mysqli_query($connection,$sql) or die("$sql<br>Error 2 #". mysqli_errno($connection) . ": " . mysqli_error($connection));
			echo "<div align='center'><table border='1' cellpadding='2'><tr>
			<td colspan='2' align='center'>$a</td></tr><tr><th>Number of SDs</th><th>Year</th></tr>";
			while ($row1 = mysqli_fetch_array($result))
				{
				extract($row1); 
				echo "<tr><td align='center'>$num</td><td><a href='search.php?dirNum=$year&Submit=Search'>$year</a></td></tr>";
				}
			echo "</table></div>";
			exit;
			
			}
		else
			{
			if(!isset($query)){$query="";}
			$a="<td colspan='5'>$numrow SDs found using the search term: <font color='green'>$query</font></td>";
			}
		}
		else
		{
		$a="<td colspan='7'>$numrow SD found using the search term: <font color='green'>$query</font></td>";
		}
	
	
	echo "<table width='100%' border='1'>
	<tr>$a</tr>
	<tr><th width='5%' align='center'>SD Number</th>
	<th width='7%' align='center'>Date</th>
	<th width='10%' align='center'>Subject</th>
	<th width='30%' align='center'>First 400 letters of Body<br>(click SD Number for complete text)</th>
	<th width='40%' align='center'>First 400 letters of Attachment(s)<br>(click SD Number for complete text)</th>
	<th width='10%' align='center'>Revision(s)</th>
	</tr>";
	
	//<td valign='top'><a href='edit.php?eid=$eid'>$title</a></td>
	
	while ($row = mysqli_fetch_array($result))
		{
		extract($row);
		$linkDisplay="";
		
		$sql1 = "SELECT link,mapname,SUBSTRING_INDEX(filename, '.', -1) as ext
		 From map where dirNum='$dirNum'";
		$result1 = @mysqli_query($connection,$sql1) or die("Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
		while ($row1 = mysqli_fetch_array($result1))
			{
			extract($row1);
			$linkDisplay.="<font size='-1'><a href='$link' target='_blank'>$mapname</a> as a $ext</font><br><br>";
			
			}// end map while
		
		if($linkDisplay!=""){$l=$linkDisplay;}else{$l="";}
		
		$body=substr(nl2br($body),0,400)."...";
		$varAttach=substr(nl2br($attach),0,400)."...";
		
		if($misc!="" and @$rev=="")
			{
			$varMisc=explode(",",$misc);
			for($m=0;$m<count($varMisc);$m++)
				{
				$d=trim($varMisc[$m]);
				$pos=strpos($d,"SD");
				if($pos===0)
					{
					$d1=substr($d,2);
					@$rev.="<a href='search.php?Submit=display&dirNum=$d1'>$d</a><br>";}
					else
					{$rev.=$d;}
				}// end for
			}
		
		$w="";
		if($webLink!=""){
		$linkArray=explode(",",$webLink);
		for($wi=0;$wi<count($linkArray);$wi++){
		list($name[$wi],$wl[$wi])=explode("@",trim($linkArray[$wi]));
		$wlTrim=trim($wl[$wi]);$nameTrim=trim($name[$wi]);
		$w.="<a href='$wlTrim'>$nameTrim</a> from web<br><br>";}
		}
		
		if(!isset($rev)){$rev="";}
		echo "
		<td valign='top'><a href='search.php?Submit=display&dirNum=$dirNum'>SD$dirNum</a> as text<br><br>$l<br>$w</td>
		
		<td valign='top'>$dateDir</td>
		<td valign='top'>$title</td>
		<td valign='top'>$body</td>
		<td valign='top'>$varAttach</td>
		<td valign='top'>$rev</td>
		</tr>";
		
		$rev="";
		}// end SC while
	echo "</table></body></html>";
	exit;
	} // end Search

// ************** DISPLAY *************

if(@$Submit =="display")
	{
	
	// Create the WHERE clause
	$where = "LEFT JOIN map on directive.dirNum=map.dirNum WHERE directive.dirNum='$dirNum'";
	
	$sql = "SELECT directive.*,link,mapname From directive $where";
	$result = @mysqli_query($connection,$sql) or die("Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
	//echo "$sql";
	$numrow = mysqli_num_rows($result);
	if($numrow < 1){echo "<br><hr>No Staff Directive found using: <font color='blue'>$query</font>
	<br><br>There are two possibilities:<br>1. Neither the Subject nor the body of any Staff Directive contains that search term
	<br>&nbsp;&nbsp;&nbsp;OR<br>
	2. That search term is so common that more than 50% of all Staff Directives contain it.<hr>";
	
	exit;}
	
	$sql1 = "SELECT link,mapname,SUBSTRING_INDEX(filename, '.', -1) as ext
	 From map where dirNum='$dirNum'";
	$result1 = @mysqli_query($connection,$sql1) or die("Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
	while ($row1 = mysqli_fetch_array($result1))
		{
		extract($row1);
		@$linkDisplay.="<font size='-1'><a href='$link' target='_blank'>$mapname</a> as a $ext</font><br><br>";
		}
	
	$lev=$_SESSION['staffdir']['level'];
	if($lev>2)
		{
		echo "<br>Edit <a href='adminMenu.php?admin=edit&dirNum=$dirNum'>$dirNum</a>";
		}
	echo "<hr><table width='100%' border='1'>
	<td width='5%' align='center'>Number</td>
	<td width='7%' align='center'><u>Date</u></td>
	<td width='10%' align='center'><u>To/From</u></td>
	<td width='10%' align='center'><u>Subject</u></td>
	<td width='25%' align='center'><u>Body</u></td>
	<td width='30%' align='center'><u>Attachment(s)</u></td>
	<td width='13%' align='center'><u>Revision(s)</u></td>
	</tr>";
	
	//<td valign='top'><a href='edit.php?eid=$eid'>$title</a></td>
	
	while ($row = mysqli_fetch_array($result))
	{
	extract($row);
	$body=nl2br($body);
	$varAttach=str_replace("#","",$attach);
	if($misc!="" and @$rev=="")
		{
		$varMisc=explode(",",$misc);
			for($m=0;$m<count($varMisc);$m++)
			{
			$d=trim($varMisc[$m]);
			$pos=strpos($d,"SD");
			if($pos===0)
				{
				$d1=substr($d,2);
				@$rev.="<a href='search.php?Submit=display&dirNum=$d1'>$d</a><br>";
				}
				else{$rev.=$d;}
			}// end for
		}// end if
	}
	
	if($webLink!="")
		{
		$linkArray=explode(",",$webLink);
		for($wi=0;$wi<count($linkArray);$wi++)
			{
			list($name[$wi],$wl[$wi])=explode("@",trim($linkArray[$wi]));
			$wlTrim=trim($wl[$wi]);
			$nameTrim=trim($name[$wi]);
			@$w.="<a href='$wlTrim'>$nameTrim</a> from web<br><br>";
			}
		}
		
	if(!isset($w)){$w="";}
	if(!isset($rev)){$rev="";}
	echo "<td valign='top'>$dirNum<br><br>$linkDisplay
	<br><br>$w</td>
	
	<td valign='top'>$dateDir</td>
	<td valign='top'>To: $dirTo<br><br>From: $dirFrom</td>
	<td valign='top'>$title</td>
	<td valign='top'>$body</td>
	<td valign='top'>$varAttach</td>
	<td valign='top'>$rev</td>
	</tr>";
	echo "</table></body></html>";
	exit;
	} // end Display

// ************ Show Search Form

$sql = "SELECT min(dirNum) as minNum,max(dirNum) as maxNum From directive GROUP BY ''";
$result = @mysqli_query($connection,$sql) or die("Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
//echo "$sql";
$row=mysqli_fetch_array($result);extract($row);
$success="From SD-$minNum through SD-$maxNum";
echo "$success
<form name='search' method='post' action='search.php'>

<table width='500'>
<tr><td>Find SD(s) containing this word:<input type='text' name='findSD' value=''></td><td>Find SD(s) by Year:<input type='text' name='dirNum' value='' size='3'></td><td>
 <input type='submit' name='Submit' value='Search'></form></td></tr></table>";
 

echo "<hr>
</body></html>";

?>