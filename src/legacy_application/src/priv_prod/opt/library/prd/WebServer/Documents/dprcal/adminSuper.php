<?php
$database="dprcal";
include("../../include/auth.inc");
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
echo "<html><head><title>SuperAdmin Page</title></head>";
echo "
<p><font size='5' font color='#004201'>SuperAdministrative Page for the NC DPR Training Calendar</font></p>
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
echo "<a href='cal_new.php'>Schedule</a> a Class";
echo "</td></tr>";
echo "<tr><td></td><td>";
echo "<a href='editFindClass.php?name=admin'>Edit, Cancel or Delete</a> an Existing Class";
echo "</td></tr>";
echo "<tr><td></td><td>
<a href='findCancel.php'>Find</a> all Cancelled Classes
</td></tr>";

echo "<tr><td><h7>&nbsp;</h7></td><td>";
echo "</td></tr>";

echo "<tr><td></td><td>";
echo "<a href='course_new.php'>Add</a> a New Course";
echo "</td></tr>";
echo "<tr><td></td><td>";
echo "<a href='findCourse.php?var=all'>Edit or VOID</a> an Existing Course";
echo "</td></tr>";

echo "<tr><td><h7>&nbsp;</h7></td><td>";
echo "</td></tr>";

echo "<tr><td></td><td>";
echo "<a href='findTrain.php'>Enrollees</a> in a Class";
echo "</td></tr>";

echo "<tr><td><h7>&nbsp;</h7></td><td>";
echo "</td></tr>";

echo "<tr><td></td><td>";
echo "Add a \"Grandfathered\" Class. (use \"View/Update tracked training.\")";
echo "</td></tr>";
echo "<tr><td></td><td>";
echo "<a href='findHistory.php'>Show</a> training history.";
echo "</td></tr>";
echo "<tr><td></td><td>";
echo "<a href='findTrain.php'>View/Update</a> tracked training.";
echo "</td></tr>";

echo "<tr><td><h7>&nbsp;</h7></td><td>";
echo "</td></tr>";

echo "<tr><td></td><td>";
echo "<a href='open_class.php'>Open</a> Class for Evaluation.";
echo "</td></tr>";
echo "<tr><td></td><td>";
echo "<a href='eval.php'>Evaluation</a> form for a Class.";
echo "</td></tr>";
echo "<tr><td></td><td>";
echo "<a href='view.php'>View</a> Evaluation for a Class.";
echo "</td></tr>";


echo "<tr><td><h7>&nbsp;</h7></td></tr>";
echo "<tr><td></td><td><a href='certVU.php'>View/Update</a> Certifications</td></tr>";
echo "<tr><td><h7>&nbsp;</h7></td></tr>";
echo "<tr><td></td><td>";
echo "<b>Instructors</b></td></tr>";
echo "<tr><td></td><td><a href='findInstruct.php'>List</a> of Instructors.";
echo "</td></tr>";
echo "<tr><td></td><td><a href='instruct_new.php'>Add</a> an Instructor.";
echo "</td></tr>
</table>";
?>