<?phpsession_start();echo "<pre>";print_r($_SESSION);echo "</pre>";?><html><head><title>NC PDR - FIND Website</title><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head><body bgcolor="beige"><table cellpadding='5'><tr><td align='left'>NC DPR - FIND</td><td align="center"><font face="Verdana, Arial, Helvetica, sans-serif" color="green"><a href="search.php?Submit=Search&v=1">Show All</a></font></td><td><form name='search' method='post' action='search.php'>Find SD(s) containing this word:<input type='text' name='find' value=''></td><td>Find SD(s) by Year:<input type='text' name='dirNum' value='' size='8'></td><td> <input type='submit' name='Submit' value='Search'></form></td> <?phpif($_SESSION[find][level]>2){echo "<td><font face='Verdana, Arial, Helvetica, sans-serif' color='green'><a href='adminMenu.php'>Admin</a></font>";}?></td><td><font face="Verdana, Arial, Helvetica, sans-serif" color="green"><a href="logout.php?name=logout">Logout</a></font></td></tr></table><hr>