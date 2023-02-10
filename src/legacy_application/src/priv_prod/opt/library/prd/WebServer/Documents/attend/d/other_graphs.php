<?php
ini_set('display_errors',1);
//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;

date_default_timezone_set('America/New_York');
include("../../../include/get_parkcodes_reg.php");

extract($_REQUEST);
$database="attend"; // I messed up and used two different names for this app
include("../../../include/auth.inc");
if(empty($connection))
	{include("../../../include/iConnect.inc");}

$title="Graph Daily Visitation";
//include("../../_base_top.php");

$database="park_use";
mysqli_select_db($connection,$database);

include("/opt/library/prd/WebServer/Documents/attend/a/park_code_areas.php"); // get subunits


if(empty($year) OR empty($month) OR empty($day))
	{
	$year=date('Y');
	$month="1";
	if(!empty($passM)){$month=$passM;}
	if(!empty($parkcode))
		{
		$park="<input type='hidden' name='parkcode' value='$parkcode'>";
		}
	
	echo "<form action='other_graphs.php' method='POST'><table border='1'>";
	echo "<tr><th>Start Date:</th>";
	echo "<td>Year <input type='text' name='year' value='$year' size='5'></th>";
	echo "<th>Month<input type='text' name='month' value='$month' size='5'></th>";
	echo "<th>Day<input type='text' name='day' value='1' size='5'></th></tr>";
	echo "<tr><th>Park:</th><td>";


echo " <select name='parkcode'>"; 
echo "<option value='' selected>";	
foreach($parkCode as $index=>$pc)
		{
	//	if(in_array($pc,$multi_area)){continue;}
		if($pc==@$parkcode)
			{$s="selected";}
			else
			{$s="value";}
		echo "<option $s='$pc'>$pc</option>\n";
		}
echo "</select></td><td><input type='checkbox' name='division' value='d'> For all Parks </td></tr>";
echo "<tr><th>Period:</th>
<td colspan='4'>
[Month<input type='radio' name='period' value='month' checked>]<br /><br />
 [Quarter 1 Jan-Mar<input type='radio' name='period' value='quarter1'>]
 [Quarter 2 Apr-Jun<input type='radio' name='period' value='quarter2'>]
 [Quarter 3 Jul-Sep<input type='radio' name='period' value='quarter3'>]
 [Quarter 4 Oct-Dec<input type='radio' name='period' value='quarter4'>]
</td></tr>";

	echo "<tr><td colspan='4' align='right'>
	<input type='submit' name='submit' value='Submit'></td></tr>";
	echo "</table>";

echo "<table>
<tr><td valign='top'>
For \"park by month\"</td>
<td><br />1. enter numerical month
<br />2. select park from dropdown menu
<br />3. have \"Month\" selected for Period
<br />4. Click \"Submit\"
</td></tr>
<tr><td valign='top'>
For \"division by month\"</td>
<td><br />1. enter numerical month
<br />2. check the \"For all Parks\" checkbox
<br />3. have \"Month\" selected for Period
<br />4. Click \"Submit\"
</td></tr>

<tr><td valign='top'>
For \"park by quarter\"</td>
<td><br />1. month can be any value - ignore
<br />2. select park from dropdown menu
<br />3. click the desired Quarter for Period
<br />4. Click \"Submit\"
</td></tr>

<tr><td valign='top'>
For \"division by quarter\"</td>
<td><br />1. month can be any value - ignore
<br />2. check the \"For all Parks\" checkbox
<br />3. click the desired Quarter for Period
<br />4. Click \"Submit\"
</td></tr>
</table>";
	exit;
	}

$year=$_POST['year'];
$mon=str_pad($_POST['month'],2,'0',STR_PAD_LEFT);
$day=str_pad($_POST['day'],2, '0',STR_PAD_LEFT);
$start_date=$year.$mon.$day;  //echo "$start_date";exit;
// Set the constants and variables.

define ("PAGE_WIDTH", 792); // 11 inches
define ("PAGE_HEIGHT",612); // 8.5 inches

// Create the Page.	
$pdf = pdf_new(); include("/opt/library/prd/WebServer/include/pdf_key_23.php");
pdf_open_file ($pdf, "");

// Set the different PDF values.
pdf_set_info ($pdf, "Author", "Tom Howard");
pdf_set_info ($pdf, "Title", "NC State Parks System - Visitation Database");
pdf_set_info ($pdf, "Creator", "See Author");

// Create the page.
PDF_begin_page_ext ($pdf, PAGE_WIDTH, PAGE_HEIGHT,"");

		// Set the fonts
		$helveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "winansi", "");
		$helveticaItalic = PDF_load_font($pdf, "Helvetica-Oblique", "winansi", "");
		$helvetica = PDF_load_font($pdf, "Helvetica", "winansi", "");
		$times = PDF_load_font($pdf, "Times", "winansi", "");
		$timesItalic = PDF_load_font($pdf, "Times-Italic", "winansi", "");
		$timesBoldItalic = PDF_load_font($pdf, "Times-BoldItalic", "winansi", "");
		
if($period=="month" AND (@$division=="d" OR !empty($parkcode)))
	{
	include("make_attend_pdf.php");
	$file="State_Park_Daily_Visitation_by_month";}
	else
	{
	include("make_attend_quarter_pdf.php");
	$file="State_Park_Daily_Visitation_by_quarter";}
	
	

// Add Footer

	pdf_setfont ($pdf, $times, 10);
	$y=20;

if(@$no_date=="")
	{
	$today=getdate();
	$wd=$today['weekday'];
	$d=$today['mday'];
	$m=$today['month'];
	$yr=$today['year'];
	$h=$today['hours'];
	$min=$today['minutes'];
	$s=$today['seconds'];
	$s=str_pad($s, 2, "0", STR_PAD_LEFT);
	$min=str_pad($min, 2, "0", STR_PAD_LEFT);
	$h=str_pad($h, 2, "0", STR_PAD_LEFT);
	$local=localtime();
	$st=$local['8'];
	if($st==0){$st="EST";}else{$st="EDST";}
	$text="Created on ".$wd.", ".$d." ".$m." ".$yr." @ ".$h.":".$min.":".$s." ".$st;
	pdf_show_xy ($pdf,$text,280,$y);
	}
$text="NC State Parks System - Daily Visitation by month";
	//	pdf_show_xy ($pdf,$text,250,$y);
		pdf_show_xy ($pdf,$text,50,$y);
// $text="https://auth.dpr.ncparks.gov/attend/d/other_graphs.php";
// 		pdf_show_xy ($pdf,$text,530,$y);


//echo "This is used to find any PHP warnings that prevents a successful PDF.  If all errors/warnings are fixed the PDF should be created."; exit;


// Finish the page
pdf_end_page ($pdf);

// Close the PDF
pdf_close ($pdf);

// Send the PDF to the browser.
$buffer = pdf_get_buffer ($pdf);
if(!empty($parkcode))
	{
	$file.="_".$parkcode;
	}
if(!empty($full_month))
	{
	$file.="_".$full_month;
	}
$file.=".pdf";
header ("Content-type: application/pdf");
header ("Content-Length: " . strlen($buffer));
header ("Content-Disposition: inline; filename=$file");
echo $buffer;

// Free the resources
pdf_delete ($pdf);

?>