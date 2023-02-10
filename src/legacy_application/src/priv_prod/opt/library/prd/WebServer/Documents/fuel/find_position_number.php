<?php

include("../../include/get_parkcodes.php");// database connection parameters

include("../../include/connectROOT.inc");// database connection parameters
$database="fuel";
$db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

if($_POST['name']!="")
	{
	extract($_POST);
	$sql = "SELECT concat(t1.Lname,', ',t1.Fname, ' ', Nname) as name, t2.beacon_num
	FROM divper.empinfo as t1
	LEFT JOIN divper.emplist as t2 on t1.tempID=t2.tempID
	where t1.Lname='$name'
	order by t1.Lname";//echo "$sql";
	
	$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SHOW2. $sql c=$connection");
	echo "<table>";
	while ($row=mysqli_fetch_assoc($result))
		{
		extract($row);
		$ARRAY[$beacon_num]=$name;
		}
	echo "</table>";
	}	


include_once("menu.php");
			
echo "<table border='1' cellpadding='5'>";
echo "<form action='find_position_number.php' method='POST'>";
echo "<tr><td align='center' colspan='2'>Find a BEACON position number</td></tr></table>";

echo "<table>";
echo "<tr><td>Enter just their Last Name <input type='text' name='name' value='$name'></td><td><input type='submit' name='submit' value='Find'></td></tr></table>";

if(isset($ARRAY))
	{
	echo "<table border='1'>";
		echo "<tr>";
		foreach($ARRAY as $bn=>$name)
			{
			echo "<tr><td>Name = $name</td><td>BEACON Number = $bn</td>";
			}
			echo "</tr>";
	echo "<tr><td>Copy the number, close this window, and paste into the previous form.</td></tr>";
	echo "</table>";
	}
?>