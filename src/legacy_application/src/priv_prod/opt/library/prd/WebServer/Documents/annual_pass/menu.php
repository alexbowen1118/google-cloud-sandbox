<?php

if(!isset($_SESSION))
	{
	session_start();
	}
	$level=$_SESSION['annual_pass']['level'];
	if($level<1){echo "You do not have access to this database.";exit;
	}

	ini_set('display_errors',1);


echo "
<table bgcolor='#ABC578' cellpadding='3'>";

echo "<tr><td><a href='welcome.php'>Welcome</a></td></tr>";
echo "<tr><td><a href='/annual_pass/action.php'>Add Pass</a></td></tr>";
echo "<tr><td><a href='/annual_pass/search.php'>Search</a></td></tr>";

// if($level>3) // 1
// 	{
// 	$append['------- Admin -------']="";
// 	$append['Reports']="/annual_pass/reports.php";
// 	}
// 	
// 
// if($level>3) // 0
// 	{
// //	echo "<tr><td>";
// 	foreach($append as $k=>$v)
// 		{
// 		echo "<tr><td><a href='$v'>$k</a></td></tr>";
// 	//	echo "<a href='$v'>$k</a><br />";
// 		}
// //	echo "</td></tr>";
// 	}


echo "</table>";


?>