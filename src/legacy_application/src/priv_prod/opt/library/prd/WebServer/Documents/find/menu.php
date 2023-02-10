<?php
//include("../../include/auth.inc");
//print_r($_SESSION);exit;
//print_r($_REQUEST);//exit;
//echo "<pre>";print_r($_SERVER);echo "<pre>";//exit;

echo "<html><head><script language='JavaScript'>

<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+\".location='\"+selObj.options[selObj.selectedIndex].value+\"'\");
  if (restore) selObj.selectedIndex=0;
}

function confirmLink()
{
 bConfirm=confirm('Are you sure you want to delete this record?')
 return (bConfirm);
}

function toggleDisplay(objectID) {
	var object = document.getElementById(objectID);
	state = object.style.display;
	if (state == 'none')
		object.style.display = 'block';
	else if (state != 'none')
		object.style.display = 'none'; 
}

function checkCheckBoxes()
	{
	var myform = document.getElementById('findForm');
	var inputTags = myform.getElementsByTagName('input');
	var checkboxCount_category = 0;
	for (var i=0, length = inputTags.length; i<length; i++) {
		 if (inputTags[i].type == 'checkbox' && inputTags[i].name == 'category[]') {
			 checkboxCount_category++;
		 }
	}
// 	alert(checkboxCount_category);
	
	
	var boxeschecked_category=0;
	
	for(var i=1; i<=checkboxCount_category; i++)
		{
		document.getElementById(\"category_ck\"+String(i)).checked == true ? boxeschecked_category++: null;
		}

	if(boxeschecked_category>0)
		{
		return true;	
		}
	else
		{
		if(boxeschecked_category==0 )
			{
			alert(\"Please select at least one Organizational Categorty.\");
			}

		return false;
		}
	
	}
//-->

</script>
<STYLE TYPE=\"text/css\">
<!--
body
{font-family:sans-serif;background:beige}
td
{font-size:90%}
th
{font-size:90%; vertical-align: bottom}
--> 
</STYLE>
<title>NC DPR FIND Website</title>";
// include("css/TDnull.inc");

echo "<body>";

echo "<div align='center'>
<table border='1' cellpadding='5'><tr><td><b>F</b>orum and <b>I</b>nformation <b>N</b>etwork <b>D</b>atabase</td></tr>
<tr>";

echo "</table></div>";

?>