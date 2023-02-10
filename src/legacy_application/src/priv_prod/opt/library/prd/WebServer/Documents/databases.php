<?php

// echo "hello"; exit;
ini_set('display_errors', 1);

$database = "dpr_system";
include("../include/iConnect.inc");

$res = mysqli_query($connection, "SHOW DATABASES");

while ($row = mysqli_fetch_assoc($res)) {
	$ARRAY[] = $row['Database'];
}

// echo "<pre>";print_r($ARRAY);echo "</pre>";

$show = array("website" => "State Park Website");
$skip_link = array("CJLEADS Login", "Friends of State Parks", "PD 107");
mysqli_select_db($connection, 'dpr_system');
$sql = "SELECT * from outside_links where 1 order by title";
$result = mysqli_query($connection, $sql) or die("Couldn't execute query. $sql");
while ($row = mysqli_fetch_assoc($result)) {
	if (in_array($row['title'], $skip_link)) {
		continue;
	}
	$outside_link[$row['title']] = $row['link'];
}

$sql = "SELECT * from database_list where level=1 order by title";
$result = mysqli_query($connection, $sql) or die("Couldn't execute query. $sql");
while ($row = mysqli_fetch_assoc($result)) {
	$show[$row['database']] = $row['title'];
	$file_link[$row['database']] = $row['link'];
}

$show['photos1'] = "The ID (Image Database-Scenics, Plants, Animals, etc.";
//echo "<pre>";print_r($show);echo "</pre>";
//echo "<pre>";print_r($file_link);echo "</pre>";

//$database="admin";
foreach ($ARRAY as $index => $db) {
	@$new_array[$db] = $show[$db];
}

//asort($new_array);
//echo "<pre>";print_r($new_array);echo "</pre>";

$title = "List of Databases";
include("inc/_base_top_dpr.php");
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;

if (empty($_SESSION['divper']['level'])) {
	$level = 1;
} else {
	$level = $_SESSION['divper']['level'];
}

$num = count($show);
// $break2=ceil($num/3.6);  echo "n=$num b=$break";
// $break3=ceil($num/3.6);  echo "n=$num b=$break3";
// $break4=ceil($num/4.6);  echo "n=$num b=$break4";

if (file_exists("setup.data")) {
	$filename = "setup.data";
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	$exp = explode("\n", $contents);
	echo "<table>";
	foreach ($exp as $k => $v) {
		echo "<tr><td>$v</td></tr>";
	}
	echo "</table>";
	fclose($handle);
}

$i = 0;
echo "<table align='center'><tr><td colspan='1'><h2>Applications currently hosted on Linux.</h2></td>";
echo "<td colspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name='outside_link' onChange=\"window.open(this.options[this.selectedIndex].value);\"><option value=\"\"></option>\n";
foreach ($outside_link as $k => $v) {
	echo "<option value='$v'>$k</option>\n";
}
echo "</select> Links to Other Sites/Files</td>";

$hostname = getenv("DB_HOST");
if ($level > 4) {
	echo "<td><a href='/admin/db_summary.php'>DB Summary</a></td>";
}

echo "</tr>";
echo "<tr>
	<td colspan='4'>For security reasons only computers on the State Network can access the db applications. If you need access from home or on the road, contact <a href=mailto:carl.jeeter@ncparks.gov>Carl Jeeter</a>, <a href=mailto:tom.howard@ncparks.gov>Tom Howard</a>, or <a href=mailto:john.carter@ncaprks.gov>John Carter</a>.</td></tr>
	<tr>
	<td colspan='4'> John Carter, Cathy Cooper, and Tom Howard can also be contacted at <a href=mailto:database.support@ncparks.gov>database.support@ncparks.gov</a></td>
</tr>";

// "dprcoe",,"public_contact","pr_news","mar"
$dont_skip = array("website", "budget", "divper", "park_use", "fuel", "le", "sap", "eeid", "state_lakes", "wiys", "hr", "fire", "div_cor", "dprcal", "facilities", "ware", "find", "sign", "fixed_assets", "fofi", "inspect", "dpr_proj", "parking", "program_share", "retail", "staffdir", "training", "travel", "exhibits", "work_comp", "pac", "donation", "efile", "annual_report", "award", "cmp", "crs", "partf", "photo_point", "publications", "second_employ", "dpr_system", "system_plan", "sysexp", "lo_fo", "irecall", "dpr_it", "nrid", "photos", "photos1", "rap", "annual_pass", "job_fair", "video", "dpr_tests", "dpr_land", "phone_bill", "mar");
// , "photos"


// $allow_access=array("carter5486","colwell4739");
// if(in_array(strtolower($_SESSION['logname']),$allow_access))
// 	{
// 	$dont_skip[]="dpr_land";
// 	}

// $hr_perm_access=array("tarver0002","blue7128");
// if(in_array(strtolower($_SESSION['logname']),$hr_perm_access))
// 	{
// 	$dont_skip[]="hr_perm";
// 	}

if ($level > 4) {
	$dont_skip[] = "climb";
	$dont_skip[] = "dpr_ops";
	$dont_skip[] = "dpr_rema";
	$dont_skip[] = "dpr_land";
	$dont_skip[] = "hr_perm";
	$dont_skip[] = "dpr_overview";
	// 	$dont_skip[]="annual_pass";
}

// echo "<pre>"; print_r($show); echo "</pre>"; // exit;
// budget and visitation float to top because of a space before the title in table dpr_system.database_list
$pub_array = array("nrid", "photos1", "rap", "closure", "video");
echo "<tr><td valign='top'><table valign='top' cellpadding='3' >";
foreach ($show as $db => $title) {
	if ($title == "") {
		continue;
	}
	if (!in_array($db, $dont_skip)) {
		continue;
	}
	$i++;
	if ($db == "park_use") {
		$db = "attend";
	}
	$default_file = "index.html";
	if ($db == "rap") {
		$default_file = "private.php";
	}
	if ($db == "staffdir") {
		$default_file = "policy.html";
	}

	$file = "/" . $db . "/" . $default_file;

	if (in_array($db, $pub_array)) {
		if ($db == "photos1") {
			$db = "photos";
		}
		$file = "/login_form.php?db=" . $db;
	}

	if ($db == "exhibits") {
		$file = $file_link['exhibits'];
	}
	if ($db == "website") {
		$file = "http://ncparks.gov";
	}
	if ($db == "attend") {
		$title = "Visitation/CSW/Vols/Litter/Recycle";
	}
	$v = "<a href='$file'>$title</a>";
	//<td>$title</td>
	echo "<tr><td><font size='+1'>$v</font></td></tr>";
	if ($i == 20) {
		echo "</table></td><td valign='top'><table valign='top' cellpadding='3'>";
	}
	if ($i == 40) {
		echo "</table></td><td valign='top'><table valign='top' cellpadding='3'>";
	}
	// 	if($i==($break4)){echo "</table></td><td valign='top'><table valign='top' cellpadding='3'>";}
}

echo "</table>";
mysqli_close($connection);
