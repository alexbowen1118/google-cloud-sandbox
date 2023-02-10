<?php

$title="Facility Website"; 
include("/opt/library/prd/WebServer/Documents/facilities/_base_top_fac.php");

echo "<table><tr><td>Menu: 
<select name=\"menu2\" onChange=\"MM_jumpMenu('parent',this,0)\">
<option value=\"\" selected></option>\n
<option value='home.php'>Facility Home</option>\n
</select></td></tr></table>";
?>