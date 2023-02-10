<?php
ini_set('display_errors',1);
$database="fuel";
include("menu.php");

include("/opt/library/prd/WebServer/include/iConnect.inc"); // connection parameters
mysqli_select_db($connection, $database); // database 

// Process Update
if($_POST)
	{
//	echo "<pre>"; print_r($_POST); echo "</pre>";
	if(!empty($_POST['pass_title']))
		{
		foreach($_POST['pass_title'] AS $number=>$value)
			{
			$clause="UPDATE web_links set ";
			$clause.="title='".$value."'";
			$sql=rtrim($clause,",")." where id='$number'";
						
		//		echo "<br />$sql";
				$result = mysqli_query($connection,$sql);
			}
			
		foreach($_POST['pass_link'] AS $number=>$value)
			{
			$clause="UPDATE web_links set ";
			$clause.="link='".$value."'";
			$sql=rtrim($clause,",")." where id='$number'";
						
		//		echo "<br />$sql";
				$result = mysqli_query($connection,$sql);
			}
		}
		if($_POST['new_title']!="")
			{
			$title=addslashes($_POST['new_title']);
			$sql="INSERT INTO web_links set title='$title', link='$_POST[new_link]'";
			//	echo "<br />$sql";
				$result = mysqli_query($connection,$sql);
			}
				
	//	exit;
		}

echo "<table align='center'><tr><th>
<h2><font color='purple'>NC DPR Vehicle Surplus Application</font></h2></th></tr></table>";

echo "<table align='center' cellpadding='5'><tr><th>
Vehicle Surplus Instructions (to be added)</th></tr>";


echo "</table>";

@$vin=$_GET['vin'];
if($level>3){echo "<form method='POST'>";}

echo "<hr /><table align='center' cellpadding='5'><tr>
<td>
<table border='1' cellpadding='5'><tr><td colspan='3'>Listed are the 3 forms that will need to be completed for this vehicle. <b>By clicking the VIN a single online form will cover all the contents of these separate forms.</b><br />However, you may want to print them out and complete them before starting the online form to make sure you have all the information handy.</td></tr>";

$i=0;
$sql="SELECT * from web_links where 1 order by sort_id";
		$result = mysqli_query($connection,$sql);
		while($row=mysqli_fetch_assoc($result))
		{
		$i++;
			extract($row);
			if(!empty($fas_num))
				{
				$link_ref="<a href='$link' target='_blank'>Get Form</a>";
				}
				else
				{
				$link_ref="Select the vehicle to surplus from the \"Keep, Surplus, Request Vehicle\" menu item.";
				}
		echo "<tr><td align='right'>$sort_id</td><td>$title</td>
		<td>$link_ref</td>
		</tr>";
	
		}
echo "</table>
</td>
</tr>
</table>";

if($level>6)
	{
	echo "<table align='center'><tr><td colspan='2'>Add a new link.</td></tr><tr>
	<tr><td></td><td>Title<br /><input type='text' name='new_title' value='' size='80'></td>
	<td>Link<br /><input type='text' name='new_link' value='' size='80'></td>
	</tr><tr><td></td><td><input type='submit' name='submit' value='Update'></td></tr></form>";
	}

$text="in process of being surplused.";
$sql="SELECT * from pr10 where 1 and location='$park_code' and vin='$vin'";
$result = mysqli_query($connection,$sql);
while ($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	}
if(empty($ARRAY))
	{
	$sql="SELECT center_code, vehicle_id, vin, license from vehicle where 1 and vin='$vin'";
	$result = mysqli_query($connection,$sql);
	while ($row=mysqli_fetch_assoc($result))
		{
		$ARRAY[]=$row;
		}
	
	$text="to be surplused.";
	}
	
if(empty($ARRAY)){exit;}
$c=count($ARRAY);
echo "<table border='1' cellpadding='3'><tr><td colspan='14'>$c vehicle $text</td></tr>";
foreach($ARRAY AS $index=>$array)
	{
	if($index==0)
		{
		echo "<tr>";
		foreach($ARRAY[0] AS $fld=>$value)
			{
			echo "<th>$fld</th>";
			}
		echo "</tr>";
		}
	echo "<tr>";
	foreach($array as $fld=>$value)
		{
		if($fld=="vin")
			{$value="<a href='pr10.php?vin=$value'>$value</a>";}
		echo "<td>$value</td>";
		}
	echo "</tr>";
	}
echo "</table>";	
	
	
echo "</table>";
?>
