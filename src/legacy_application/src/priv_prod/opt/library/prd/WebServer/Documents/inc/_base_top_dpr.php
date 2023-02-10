<?php
if(!isset($_SESSION)){session_start();}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="/inc/css/images/style.css" type="text/css" />

<?php
if(!empty($add_cal))
	{
	echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"../../jscalendar/calendar-brown.css\" title=\"calendar-brown.css\" />
	  <!-- main calendar program -->
	  <script type=\"text/javascript\" src=\"../../jscalendar/calendar.js\"></script>
	  <!-- language for the calendar -->
	  <script type=\"text/javascript\" src=\"../../jscalendar/lang/calendar-en.js\"></script>
	  <!-- the following script defines the Calendar.setup helper function, which makes adding a calendar a matter of 1 or 2 lines of code. -->
	  <script type=\"text/javascript\" src=\"../../jscalendar/calendar-setup.js\"></script>";
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

function toggleDisplaySwap(objectID) {
	var inputs=document.getElementsByTagName('div');
		for(i = 0; i < inputs.length; i++) {
		
	var object = inputs[i];
		state = object.style.display;
			if (state == 'block')
		object.style.display = 'none';	
		}
		
	var object = document.getElementById(objectID);
	state = object.style.display;
	if (state == 'none')
		object.style.display = 'block';
	else if (state != 'none')
		object.style.display = 'none'; 
}

function popitup(url)
	{	newwindow=window.open(url,"name","resizable=1,scrollbars=1,height=1024,width=1024,menubar=1,toolbar=1");
			if (window.focus) {newwindow.focus()}
			return false;
	}        
function confirmLink()
	{
	 bConfirm=confirm('Are you sure you want to delete this record?')
	 return (bConfirm);
	}        
   
function confirmDeleteFMP()
	{
	 bConfirm=confirm("Are you sure you want to delete this Management Plan? It will also delete all associated documents!")
	 return (bConfirm);
	}    
function confirmDeleteMap(delUrl)
	{
	  if (confirm("Are you sure you want to delete"))
		{
		document.location = delUrl;
		}
	}    
function confirmLinkFile()
	{
	 bConfirm=confirm('Are you sure you want to delete this file?')
	 return (bConfirm);
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
function UncheckAll()
	{
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
  if (form.park.value == "") {
    alert( "Please enter your email address." );
    form.park.focus();
    return false ;
  }
  return true ;
}

function checkFMP_year ()
{
var x1=document.forms["form_fmp"]["year_plan"].value;
  if (x1 == "") {
    alert( "Please enter the year." );
    return false ;
  }
}
// -->
</script>
	
	<title><?php echo "$title";?></title>
</head>
<body>


<div id="contenttext">
			<div class="bodytext" style="padding:10px;">
			
			
<?php
if(@$_REQUEST['submit']!="Show All")
	{
	echo "<div align=\"center\">
		<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
		<tr>
			<td align=\"center\" valign=\"top\" bgcolor=''><img src=\"/inc/css/images/dpr_1.jpg\" alt=\"pano image\"></td>
		 </tr>
		<tr height='5' bgcolor='#800080'>
			<td> </td>
		 </tr>
		</table>
	</div>";
	}
?>