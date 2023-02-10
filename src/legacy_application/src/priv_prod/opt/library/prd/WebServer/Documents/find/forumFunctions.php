<?php
// *************** PMIS FUNCTIONS **************

function findMe($fld,$passSQL)
	{
	$findme=urlencode(" order by ");
	$pos=strpos($passSQL,$findme);
	if($pos>0){$flds = array("age","location","eeid");
	$removeFld = str_replace($flds, "", $passSQL);
	$passSQL=$removeFld.urlencode("$fld");}
	else{$passSQL=$passSQL.urlencode(" order by $fld");}
	return $passSQL;
	}


function forumHeader($passSQL,$passFields,$countKeys,$sumBy,$totHeader,$countHeader)
	{
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
	//$link="<a href='forum.php?park=$park'>$park</a>";
	echo "
	<table border='1' cellpadding='3'><tr>";
	for($x=0;$x<$numOfColumns;$x++){
	$var=strtoupper($fld[$x]);
	if($x==($numOfColumns-1)){$var="View";}
	echo "<th>$var</th>";}
	echo "</tr>";
	}

function getDecimalFields($varE){
global $fld0Dec,$fld1Dec,$fld2Dec;
$decimalKeys=array_keys($varE);
$fld0Dec=$decimalKeys[0];
$fld1Dec=$decimalKeys[1];
$fld2Dec=$decimalKeys[2];
}

function itemShow($row)
	{
	global $numFlds,$ii;
//	echo "<pre>";print_r($row);echo "</pre>"; exit;
	@$timeMod=$row['timeMod'];
	@$similarID=$row['similar'];
	@$forumID=$row['forumID'];
	@$personID=$row['personID'];
	@$submisID=$row['submisID'];
	@$related=$row['related'];
	$sub=substr($row['submitter'],0,-2);
	$message=nl2br($row['submission']);
	if(@is_finite(@$row['dirNum']))
		{
		@$dirNum="&dirNum=".$row['dirNum'];
		}
		else
		{
		$dirNum="";
		}
	
	
		if(@$row['weblink'])
			{
			
		//	$web=explode(",",$row['weblink_1']);
			$web=explode(",",$row['weblink']);
	//		echo "<pre>"; print_r($web); echo "</pre>";  exit;
			for($l=0;$l<count($web);$l++)
				{
				$br="<br /><br />";
				$trimWeb=trim($web[$l]); 
				// EDIT: replaced "auth.dpr.ncparks.gov" with ""
				$web[$l]=str_replace("149.168.1.195","",$web[$l]);
			//	echo "$trimWeb"; exit;
				
				$pre=substr($trimWeb,0,4);$n=$l+1;
				if($pre=="http")
					{
					@$link.="<a href='".$web[$l]."' target='_blank'>$web[$l]</a>$br";}
				else
					{
					@$link.="<a href='/find/".$web[$l]."' target='_blank'>$web[$l]</a>$br";}
				}
			}
		if(@$row['weblink_1'])
			{
			$link1="";
		$web=explode(",",$row['weblink_1']);
	//		echo "<pre>"; print_r($web); echo "</pre>";  exit;
			for($l=0;$l<count($web);$l++)
				{
				$br="<br /><br />";
				$trimWeb=trim($web[$l]); 
				// EDIT: replaced "auth.dpr.ncparks.gov" with ""
				$web[$l]=str_replace("149.168.1.195","",$web[$l]);
			//	echo "$trimWeb"; exit;
				
				$pre=substr($trimWeb,0,4);$n=$l+1;
				if($pre=="http")
					{
					@$link1.="<a href='".$web[$l]."' target='_blank'>$web[$l]</a>$br";}
				else
					{
					@$link1.="<a href='/find/".$web[$l]."' target='_blank'>$web[$l]</a>$br";}
				}
			}
	if($row['weblink_2'])
		{
		$web2=explode(",",$row['weblink_2']);
		for($l=0;$l<count($web2);$l++)
			{
			$br="<br /><br />";
			$pre2=substr(trim($web2[$l]),0,4);//$n=$l+1;
			
				// EDIT: replaced "auth.dpr.ncparks.gov" with ""
			$web2[$l]=str_replace("149.168.1.195","",$web2[$l]);
			if($pre2=="http")
				{
				@$link2.="<a href='".$web2[$l]."' target='_blank'>$web2[$l]</a>$br";
				}
			else
				{
				@$link2.="<a href='".$web2[$l]."' target='_blank'>$web2[$l]</a>";
				}
			}
		}
	
	
	$var=$row['forumID'];
	list($year,$month,$dayTime)=explode("-",$row['dateCreate']);
	list($day,$time)=explode(" ",$dayTime);
	$varDate=date("l, M d, Y", mktime(0, 0, 0, $month, $day, $year));
	$month=substr($timeMod,5,2);
	$day=substr($timeMod,8,2);
	$year=substr($timeMod,0,4);
	@$tm=date("l, M d, Y", mktime(0, 0, 0, $month, $day, $year));
	if($tm==$varDate)
		{$tm="";}
		else
		{$tm="<font color='brown'>Updated: ".$tm."</font>";}
	
//	if($row['replier']){$rep="[".substr($row['replier'],0,-2)."]";}
	
	if(!isset($rep)){$rep="";}
	echo "<tr><td bgcolor='darkviolet' align='center'><font color='white'><b>$sub</b></font></td>
	<td bgcolor='lavender' width='80%'><font size='+1'><b>$row[topic]</b></font> $rep<br>$tm Created: $varDate @ $time</td>
	
	<th bgcolor='lavender'><a href='forum.php?submit=add'>Add</a</th>
	<th bgcolor='lavender'><a href='forum.php?submit=find'>Search</a></th>
	<th bgcolor='lavender'>View <a href='forum.php'>Recent</a></th>
	<th bgcolor='lavender'><a href='forum.php?submit=edit&lastFld=forumID&var=$var$dirNum'>Edit</a></th>
	</tr>";

//	<th bgcolor='lavender'><a href='forum.php?submit=reply&lastFld=forumID&var=$var'>Reply</a></th>	

	if(!empty($related))
		{
		$var_r=explode(",",$related);
		$show_related="&nbsp;&nbsp;&nbsp; Related FIND entry: ";
		$r="submit=Go&forumID=";
		foreach($var_r as $kr=>$vr)
			{
			$r.="$vr%2C";
			}
			$show_related.="<a href='forum.php?$r'>$related</a>&nbsp;&nbsp;&nbsp;";
		
		}
	
	if(!isset($link2)){$link2="";}
	if(!isset($link1)){$link1="";}
	if(!isset($link)){$link="";}
	if(!isset($show_related)){$show_related="";}
	echo "<tr><td colspan='10'>";
	echo "<div id=\"topicTitle\">$var &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ... <a onclick=\"toggleDisplay('forumDetails[$ii]');\" href=\"javascript:void('')\"> details &#177</a> <font size='-1'</font>$show_related
	</div>
	
	<div id=\"forumDetails[$ii]\" style=\"display: none\"><br>$message
	<br><br>
	$link $link1 $link2</div></td></tr>";
	$ii++;
	}
?>