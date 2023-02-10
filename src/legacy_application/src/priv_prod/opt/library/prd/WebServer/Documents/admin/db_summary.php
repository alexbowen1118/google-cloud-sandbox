<?php

$database="dpr_system";
include("../../include/auth.inc"); // used to authenticate users
// echo "<pre>"; print_r($_SESSION); echo "</pre>";

ini_set('display_errors',1);

include("../../include/iConnect.inc");
	mysqli_select_db($connection,$database);

if(!empty($_POST))
	{
	// echo "<pre>"; print_r($_POST); echo "</pre>";  exit;
	$skip=array("submit_form","edit");
	if(!empty($_POST['location']))
		{
		$array_keys=array_keys($_POST['location']);
		$temp=array();
		foreach($array_keys as $index=>$id)
			{
			foreach($_POST as $fld=>$array)
				{
				if(In_array($fld,$skip)){continue;}
			$value=html_entity_decode($array[$id]);
			$value=htmlspecialchars_decode($value);
				$temp[]="$fld='$value'";
				}
			$clause=implode(", ",$temp);
			$sql="UPDATE  dpr_system.db_summary
			set $clause
			where id='$id'
			";
			$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql ".mysqli_error($connection));
	// 		echo "$sql"; exit;
			}
		}
	}

$sql = "SELECT * FROM db_summary where db_name !='phone_bill'";
$result = mysqli_query($connection, $sql) or die ("Couldn't execute query. $sql");

while($row=mysqli_fetch_assoc($result))
	{
// 	if(in_array("phone_bill", $row)){continue;}
	$ARRAY[]=$row;
	}

// echo "<pre>";print_r($ARRAY);echo "</pre>"; exit;

echo "
<style>
tr:nth-child(even) {background: #CCC}
tr:nth-child(odd) {background: #FFF}
</style>";

$title="List of Databases";
// include("../inc/_base_top_dpr.php");
include("../_base_top.php");

$skip=array();
$text_flds=array("major_users","comments");
echo "<form method='POST'>";
$c=count($ARRAY);

if(!empty($_POST['edit']))
	{$ck="checked";}else{$ck="";}
	
	
// used to replace id in the display. Needed to hide the id value for phone_bill which is no longe in use
$i=0; 
$new_id="";


echo "<table width='125%'><tr><td colspan='7' align='right'><input type='checkbox' name='edit' value=\"x\" onchange=\"this.form.submit()\" $ck>Edit</td></tr>";
foreach($ARRAY AS $index=>$array)
	{
	if($index==0)
		{
		echo "<tr>";
		foreach($ARRAY[0] AS $fld=>$value)
			{
			if(in_array($fld,$skip)){continue;}
			echo "<th>$fld</th>";
			}
		echo "</tr>";
		}
	echo "<tr>";
	if(!empty($_POST['edit']))
		{
		foreach($array as $fld=>$value)
			{
			if(in_array($fld,$skip)){continue;}
			$fld_name=$fld."[".$array['id']."]";
			$size=16;
			if($fld=="location"){$size=10;}
			if($fld=="importance"){$size=20;}
			if($fld=="db_link_name"){$size=36;}
			$line="<input type='text' name='$fld_name' value=\"$value\" size='$size'>";
			if(in_array($fld,$text_flds))
				{
				$line="<textarea name='$fld_name' cols='36', rows='1'>$value</textarea>";
				}
			if($fld=="id")
				{
				$line=$value;
				if($array['importance']=="Critical")
					{$line="<font color='red'>$line</font>";}
				if($array['location']=="public_prod")
					{$line="<font color='green'>$line</font>";}
				if($array['importance']=="Very Important")
					{$line="<font color='magenta'>$line</font>";}
				}
			echo "<td>$line</td>";
			}
		}
		else
		{
		foreach($array as $fld=>$value)
			{
			$line=$value;
			if($fld=="id"){
			$new_id++;
			$line=$new_id;}
			if($fld=="db_link_name")
				{
				$line=$value;
				if($array['importance']=="Critical")
					{$line="<font color='red'>$line</font>";}
				if($array['location']=="public_prod")
					{$line="<font color='green'>$line</font>";}
				if($array['importance']=="Very Important")
					{$line="<font color='magenta'>$line</font>";}
				}
			if($fld=="db_name")
				{$line="<b>$line</b>";}
			if($fld=="comments"){$size='50';}else{$size=16;}
			echo "<td size='$size' style='vertical-align: text-top;'>$line</td>";
			}
		}
	echo "</tr>";
	}
echo "<tr><td colspan='3' align='right'>
<input type='submit' name='submit_form' value=\"Update\">
</td></tr>";
echo "</table></form></body></html>";
?>
