<?php
ini_set('display_errors',1);
echo "<div align='center'>";

echo "<table><tr>";

$sql = "SELECT distinct park from vol_stats ORDER BY park";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	while($row=mysqli_fetch_array($result)){
	extract($row);$park=strtoupper($park);
	$parkCode[]=$park;
	}

$sql = "SELECT distinct category
FROM vol_cat AS t1
LEFT  JOIN vol_stats AS t2 ON t1.cat_name = t2.category
WHERE t2.park=  '$parkcode'
ORDER  BY t1.id";  //echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
	while($row=mysqli_fetch_array($result))
	{
	$cat_array[]=$row['category'];
	}

// "<pre>"; print_r($cat_array); echo "</pre>";	
$categories=array("Administration"=>"admin_hours","Campground Host"=>"camp_host_hours","Trails"=>"trail_hours","I&E"=>"ie_hours","Maintenance"=>"main_hours","Research"=>"research_hours","Resource Management"=>"res_man_hours","Other"=>"other_hours");
//	print_r($categories);exit;
	
echo "<form action='r_vol_hours.php'><td>Park: <select name='parkcode'>";
echo "<option selected=''>\n";      
        for ($n=0;$n<count($parkCode);$n++)  
        {$scode=$parkCode[$n];$parkArray[]=$scode;
if($scode==$parkcode){$s="selected";}else{$s="value";}
echo "<option $s='$scode'>$scode\n";
          }
echo "</select></td>";

echo "<td>Activities: <select name='cat'>";
echo "<option $s=''>\n";
if(!isset($cat)){$cat="";}
        while (list($k,$v)=each($categories))  
			{
			$scode=$v;
			if($scode==$cat||$cat==$k)
				{$s="selected";}else{$s="value";}
			echo "<option $s='$scode'>$k\n";
			  }
echo "</select></td>";

echo "<td>Last name: <input type='text' name='Lname' value=\"\"></td>";

echo "</tr>";

echo "<tr><td>Categories: <select name='category'>";
echo "<option selected=''>\n";
if(!isset($category)){$category="";}
if(isset($cat_array))
	{
			foreach($cat_array as $k=>$v)
			{
			if($v==$category){$s="selected";}else{$s="value";}
			echo "<option $s='$v'>$v\n";
			}
	echo "</select>";
	}
if(@!$year_month){$year_month=date('Y');}
echo "</td><td>YearMonth or Year (e.g., 200607 or 2005):<input type='text' name='year_month' value='$year_month' size='10'></td><td>";
echo "<input type='submit' name='submit' value='Submit'>
</td></form>";

echo "<td><a href='/attend/a/vol_form.php'>Return</a></td></tr></table><hr>";


?>