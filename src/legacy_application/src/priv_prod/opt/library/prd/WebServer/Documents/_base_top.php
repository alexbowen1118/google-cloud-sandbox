<?php
if(!isset($_SESSION)){session_start();}
// echo "db=$database<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
$level=@$_SESSION[$database]['level'];
if($level<1)
	{
// 	echo "7<pre>"; print_r($_SESSION); echo "</pre>"; // exit;
// 	echo "l=$level Access has not been granted for $database."; exit;
	echo "Access has not been granted to this database application."; exit;
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<style>
WCupload
	{
	position:absolute;
	left:550px;
	top:150px;
	}
instructions
	{
	position:absolute;
	left:700px;
	top:175px;
	width:500px;
	background-color:rgba(200, 200, 200, 1);
	}
body1
	{
	position:absolute;
	left:145px;
	top:143px;
	}
</style>
<?php
$wide_array=array("jeopardy","fixed_assets","facilities");
$calendar_array=array("summit","training");
$jquery_array=array("training","hr","ware","rema","dpr_proj","work_comp","lo_fo","climb","dpr_it","fire","annual_pass","public_contact","dpr_land");
$ajax_array=array("ware");
$skip_menu=array("pac","dpr_system","fixed_assets");
if(!isset($database)){$database="";}

$menu_bottom_shadow_width=143;
	if(in_array($database,$wide_array))
		{
		$ss_link="<link rel=\"stylesheet\" href=\"/css/style_wide.css\" type=\"text/css\" />";
		}
	else
		{
		$ss_link="<link rel=\"stylesheet\" href=\"/css/style.css\" type=\"text/css\" />";
		if($database=="work_comp")
			{
			$ss_link="<link rel=\"stylesheet\" href=\"/css/style.work_comp.css\" type=\"text/css\" />";
			$menu_bottom_shadow_width=147;
			}
		if($database=="rema" OR $database=="dpr_proj")
			{
			$ss_link="<link rel=\"stylesheet\" href=\"/css/style.rema.css\" type=\"text/css\" />";
			}
		if($database=="ware")
			{
			$ss_link="<link rel=\"stylesheet\" href=\"/css/style.ware.css\" type=\"text/css\" />";
			}
		}	
	echo "$ss_link";

	if(in_array($database,$calendar_array))
		{
		echo "
		<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"/jscalendar/calendar-brown.css\" title=\"calendar-brown.css\" />
		  <!-- main calendar program -->
		  <script type=\"text/javascript\" src=\"/jscalendar/calendar.js\"></script>
		  <!-- language for the calendar -->
		  <script type=\"text/javascript\" src=\"/jscalendar/lang/calendar-en.js\"></script>
		  <!-- the following script defines the Calendar.setup helper function, which makes adding a calendar a matter of 1 or 2 lines of code. -->
		  <script type=\"text/javascript\" src=\"/jscalendar/calendar-setup.js\"></script>
		";
		}
		
	if(in_array($database,$ajax_array))
		{
		echo "<script language=\"javascript\" type=\"text/javascript\">
	function ajaxFunction(){
		var ajaxRequest;  // The variable that makes Ajax possible!

		try{
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
		} catch (e){
			// Internet Explorer Browsers
			try{
				ajaxRequest = new ActiveXObject(\"Msxml2.XMLHTTP\");
			} catch (e) {
				try{
					ajaxRequest = new ActiveXObject(\"Microsoft.XMLHTTP\");
				} catch (e){
					// Something went wrong
					alert(\"Your browser broke!\");
					return false;
				}
			}
		}
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
			var ajaxDisplay = document.getElementById(\"ajaxDiv\");
				ajaxDisplay.innerHTML = ajaxRequest.responseText;	
			}
		}
		var product_title = document.getElementById(\"ware_product_title\").value;
		var queryString = \"?product_title=\" + product_title;
		ajaxRequest.open(\"GET\", \"ajax_product_title.php\" + queryString, true);
		ajaxRequest.send(null); 
		}
		</script>";
		}
	if(in_array($database,$jquery_array))
		{
		echo "<script src=\"../js/gen_validatorv4.js\" type=\"text/javascript\"></script>";

		echo "<link type=\"text/css\" href=\"../css/ui-lightness/jquery-ui-1.8.23.custom.css\" rel=\"Stylesheet\" />    
		<script type=\"text/javascript\" src=\"../js/jquery-1.8.0.min.js\"></script>
		<script type=\"text/javascript\" src=\"../js/jquery-ui-1.8.23.custom.min.js\"></script>

		<script>
		
		$(function() {";
		$var_iii=4;
		if($database=="dpr_proj"){ $var_iii=9;}
		for($iii=1;$iii<$var_iii;$iii++)
			{
			$tv="#datepicker".$iii;
			echo "
				$( \"$tv\" ).datepicker({ 
		changeMonth: true,
		changeYear: true, 
		dateFormat: 'yy-mm-dd',";
		if($database=="dpr_it")
			{echo "yearRange: \"-2yy:+1yy\",
		maxDate: \"+1yy\"";}
			else
			{
			if($database=="dpr_land")
				{echo "yearRange: \"-50yy:+100yy\",
			maxDate: \"+100yy\"";}
				else
				{echo "yearRange: \"-50yy:+0yy\",
			maxDate: \"+0yy\"";}
			
			}
		echo " });
		";
			}
		
		echo "
			});
			</script>
		
		<style>
		.ui-datepicker {
		  font-size: 80%;
		}
		</style>";
		}
?>

<script type="text/javascript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function JumpTo(theMenu){
                var theDestination = theMenu.options[theMenu.selectedIndex].value;
                var temp=document.location.href;
                if (temp.indexOf(theDestination) == -1) {
                        href = window.open(theDestination);
                        }
        }
        
function toggleDisplay(objectID) {
	var object = document.getElementById(objectID);
	state = object.style.display;
	if (state == 'none')
		object.style.display = 'block';
	else if (state != 'none')
		object.style.display = 'none'; 
}

function validate() {
if (document.mainform.cat[0].checked == true) {
alert('Photo was NOT entered. All plant and animal photos must first have an entry in NRID.');
event.returnValue=false;
	}
}
function checkCheckBoxes()
	{
	// get number of checkboxes
	var inputElems = document.getElementsByTagName("input"),
    	count = 0;
		for (var i=0; i<inputElems.length; i++) {
			if (inputElems[i].type === "checkbox") {
				count++;
			}
		}
		
//	alert( count );
	// keep a count of how many checked
	var boxeschecked=0;
	// cycle thru all checkbox ids - increment boxeschecked var if true
	for(var i=1; i<=count; i++)
		{
			document.getElementById("cat"+String(i)).checked == true ? boxeschecked++: null;
		}
	if(boxeschecked>0)
		{
		//	alert("passed");
		return true;	
		}
	else
		{
		alert("Please select at least one Category type.");
		return false;
		}
	}
function toggle(){
	var div1 = document.getElementById('div1')
	if (div1.style.display == 'none') {
		div1.style.display = 'block'
	} else {
		div1.style.display = 'none'
	}
}

function popitLatLon(url)
{   newwindow=window.open(url);
        if (window.focus) {newwindow.focus()}
        return false;
}

function popitup(url)
{   newwindow=window.open(url,"name","resizable=1,scrollbars=1,height=1024,width=1024,menubar=1,toolbar=1");
        if (window.focus) {newwindow.focus()}
        return false;
}        
function confirmLink()
	{
	 bConfirm=confirm('Are you sure you want to delete this record?')
	 return (bConfirm);
	}
function ScrollToBottom(){

window.scrollTo(0,document.body.scrollHeight);

}

function CheckAll()
{
count = document.frm.elements.length;
    for (i=0; i < count; i++) 
	{
    if(document.frm.elements[i].checked == 1)
    	{document.frm.elements[i].checked = 1; }
    else {document.frm.elements[i].checked = 1;}
	}
}
function UncheckAll(){
count = document.frm.elements.length;
    for (i=0; i < count; i++) 
	{
    if(document.frm.elements[i].checked == 1)
    	{document.frm.elements[i].checked = 0; }
    else {document.frm.elements[i].checked = 0;}
	}
}
function checkform ( form )
	{
	if (form.park.value == "")
		{
		alert( "Please enter your email address." );
		form.park.focus();
		return false ;
		}
	return true ;
	}

// for when the users scrolls down 20 pixels
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("myBtn").style.display = "block";
    } else {
        document.getElementById("myBtn").style.display = "none";
    }
}

function myFunction_enter() {
var x = document.querySelector('input[name="type_pass"]:checked').value;
// used to put text on a page
document.getElementById("prefix").innerHTML = x;
// used to put value into form field
// opener.document.pass_form.prefix.value = x;
}


// scrolls to the top of the document
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}
// -->
</script>

<?php	
if(!isset($title)){$title="";}
	echo "<title>$title</title>";

$bgcolor="white";
$var_scroll="";
if($title=="DPR IT Inventory")
	{$bgcolor="beige";}
if($title=="DPR Project Tracking Application" and $_SERVER['PHP_SELF']!="/dpr_proj/find_export.php")
	{
// 	echo "<pre>"; print_r($_SERVER); echo "</pre>"; // exit;
	$var_scroll="onload=\"ScrollToBottom();\"";
	}
	
echo "</head>
<body bgcolor=\"$bgcolor\" $var_scroll>";
	
?>

<div align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr bgcolor="#237B65">
  <td align="center" valign="top">

<?php
    echo "<a href='http://ncparks.gov' target='_blank'><img src=\"/inc/css/images/dpr_1.jpg\"></a>";
?>
    </td>
  </tr>
  
  <tr bgcolor='purple' height='9'><td> </td></tr>
</table>
				</div>
		
				
<?php	
if(!in_array($database,$skip_menu))
	{
		echo "<div id=\"page\" align=\"left\">
			<div id=\"content\" align=\"left\">
				<div id=\"menu\" align=\"left\">
					<div id=\"linksmenu\" align=\"center\">";

if($database=="facilities")
	{
	include($database."/menu_counter.php");
	}
	else
	{
	include($database."/menu.php");
	}
	
				echo "</div>
					<div align=\"left\" style=\"width:140px; height:8px;\"><img src=\"/css/mnu_bottomshadow.gif\" width=\"$menu_bottom_shadow_width\" height=\"8\" alt=\"mnubottomshadow\" /></div>
				</div>";
	}			
?>
<div id="contenttext">
			<div class="bodytext" style="padding:12px;">