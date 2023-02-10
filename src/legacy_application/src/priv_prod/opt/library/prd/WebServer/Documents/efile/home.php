<?php
ini_set('display_errors',1);
$title="EFILE";
include("../inc/_base_top_dpr.php");
$database="efile";

echo "
<style>
a:link {
color: blue;
text-decoration: none;
}

div.one {
font-family: Verdana, Geneva, sans-serif;
color: #cc6600;
font-size: 15px;
margin-left: 50px;

}
div.two {
font-family: Verdana, Geneva, sans-serif;
 font-size: 15px;
    margin-left: 100px;
}
</style>
";

include("../../include/auth.inc"); // used to authenticate users

$level=$_SESSION[$database]['level'];
$tempID=$_SESSION[$database]['tempID'];
date_default_timezone_set('America/New_York');

echo "<table border='1' align='center' cellpadding='5'>";

echo "<tr><th colspan='7'><font color='gray'>Welcome to the DPR - <font color='brown'>E</font>lectronic <font color='brown'>F</font>ile <font color='brown'>I</font>nformation <font color='brown'>L</font>inks <font color='brown'>E</font>nvironment</font></th></tr></table>";


echo "<div class='one'>
<p>Here are pointers to some of the most frequently used sections of eFile.</p>
</div>
<div class='two'>
<p><a href='files.php?cat_id=125'>Historical Documents</a></p>

<p><a href='files.php?cat_id=71'>The Steward</a></p>

<p><a href='files.php'>Complete Listing of Categories</a></p>
</div>";

echo "</body></html>";
?>