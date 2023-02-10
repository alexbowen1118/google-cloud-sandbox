<?php
ini_set('display_errors',1);
$database="dprcal";
include("../../include/auth.inc");
//echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;

$beacon_num=$_SESSION['beacon_num'];

include("../../include/iConnect.inc");
mysqli_select_db($connection,"div_cor");

$exclude=array("60032988","60033148","60095523","",);
$sql = "SELECT t1.apc_id, t3.Fname, t3.Lname
 FROM `access` as t1
left join divper.emplist as t2 on t2.beacon_num=t1.apc_id
left join divper.empinfo as t3 on t2.tempID=t3.tempID
where t1.apc_id !=''
";
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql ".mysqli_error($connection));
while($row=mysqli_fetch_assoc($result))
	{
	if(in_array($row['apc_id'],$exclude)){continue;}
	$ARRAY[]=$row;
	$apc_array[]=$row['apc_id'];
	}
// echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;
// echo "<pre>"; print_r($apc_array); echo "</pre>"; // exit;

// mysqli_select_db($connection,$database);	
// $sql = "SELECT t1.* , t2.title
// 	From permit_eval as t1
// 	left join course as t2 on t1.clidLink=t2.clid
// 	where 1";
// 	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql ".mysqli_error($connection));
// 	$row=mysqli_fetch_array($result);
// 	extract($row);
echo "
<p><font size='5' font color='#004201'>Administrative Page for the NC DPR Training Calendar</font></p>
<table>";
echo "<tr><td width='20%'></td><td>";
echo "<a href='index.php?name=admin'>View</a> the Calendar";
echo "</td></tr>";
echo "<tr><td></td><td>";
echo "<a href='pub.php?name=admin'>View</a> the Public Calendar";
echo "</td></tr>";

echo "<tr><td><h7>&nbsp;</h7></td><td>";
echo "</td></tr>";

echo "<tr><td></td><td>";
echo "<a href='cal_new.php?name=admin'>Schedule</a> a Class";
echo "</td></tr>";
echo "<tr><td></td><td>";
echo "<a href='editFindClass.php?name=admin'>Edit, Cancel or Delete</a> an Existing Class";
echo "</td></tr>";

echo "<tr><td><h7>&nbsp;</h7></td><td>";
echo "</td></tr>";
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
$add_course_array=array("Crate5973"=>"60032832","Short8972"=>"65020685","Sasser9288"=>"60032943");

if(in_array($_SESSION['beacon_num'],$add_course_array))
	{
	echo "<tr><td></td><td>";
	echo "<a href='course_new.php'>Add</a> a New Course";
	echo "</td></tr>";
	echo "<tr><td></td><td>";
	echo "<a href='findCourse.php?var=all'>Edit or Delete</a> an Existing Course";
	echo "</td></tr>";
	}

/*
echo "<tr><td><h7>&nbsp;</h7></td><td>";
echo "</td></tr>";

echo "<tr><td></td><td>";
echo "<a href='findSignup.php'>Enrollees</a> in a Class";
echo "</td></tr>";

echo "<tr><td></td><td>";
echo "<a href='findTrain.php'>View/Update</a> tracked training.";
echo "</td></tr>";

echo "<tr><td><h7>&nbsp;</h7></td><td>";
echo "</td></tr>";
*/

// echo "<tr><td></td><td>";
// echo "<a href='findSignup.php'> </a>View your training history. (Not implemented yet because of questions about who enters and verifies completed training.)";
// echo "</td></tr>";

if(in_array($beacon_num,$apc_array))
	{
	$_SESSION['dprcal']['level']=4;
	$_SESSION['dprcal']['apc']=4;
	echo "<tr><td></td><td>";
	echo "<a href='/dprcal/view.php?cat=adm'>View evaluations</a>";
	 echo "</td></tr>";
	}
echo "<tr><td><h7>&nbsp;</h7></td><td>";
echo "</td></tr>";
echo "<tr><td></td><td>";
echo "<b>Instructors</b></td></tr>";
echo "<tr><td></td><td><a href='findInstruct.php'>List</a> of Instructors.";
echo "</td></tr>";
echo "<tr><td></td><td><a href='instruct_new.php'>Add</a> an Instructor.";
echo "</td></tr>";


echo "<tr><td><h7>&nbsp;</h7></td><td>";
echo "</td></tr>";
// echo "<tr><td></td><td>";
// echo "<b>$title</b></td></tr>";
// echo "<tr><td></td><td><a href='eval.php?clidLink=$clidLink&cat=$cat&Submit=Show Class(es)'>Complete</a> Evaluation.</td></tr>";

echo "
</table>";
// echo "<a href='/dprcal/eval.php'>Evaluation</a>";
