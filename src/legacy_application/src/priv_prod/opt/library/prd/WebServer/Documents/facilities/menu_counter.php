<?php

if(!isset($_SESSION))
	{
	session_start();
// 	echo "<pre>"; print_r($_SESSION); echo "</pre>"; exit;
	}
	$level=$_SESSION['facilities']['level'];
	if($level<1){
	echo "Either you are not logged in or you do not have access to this database.";
	echo "<a href='/login_form.php?db=facilities'>login</a>";
	
	exit;
	}

// 	echo "<pre>"; print_r($_SESSION); echo "</pre>";  exit;
	ini_set('display_errors',1);


echo "<div align='center'>
<table bgcolor='#ABC578' cellpadding='3'>";

echo "<tr><td><a href='counters.php'>Overview</a></td></tr>";

echo "<tr><td><a href='park_counters.php'>Park Counters</a></td></tr>";
// 
echo "<tr><td><a href='counter_summary.php'>Summary</a></td></tr>";
// 
// echo "<tr><td><a href='proj_find_summary_incomplete.php'>Incomplete Projects</a></td></tr>";
// 
// echo "<tr><td><a href='proj_find_summary.php'>Active Projects</a></td></tr>";

	
if($level>2) // 3
	{
//	$append['Enter Record']="/moths/private_submit.php";
	}




echo "</table></div>";


?>