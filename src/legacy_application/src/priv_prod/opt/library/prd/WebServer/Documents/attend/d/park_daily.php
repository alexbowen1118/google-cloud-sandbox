<?php
ini_set('display_errors',1);
extract ($_REQUEST);
date_default_timezone_set('America/New_York');
//print_r($_REQUEST);exit;


if(empty($year) OR empty($month) OR empty($day))
	{
	$db="attend";
	$database=$db;
	include("../../_base_top.php");
	$year=date('Y');
	$month="01";
	if(!empty($passM)){$month=$passM;}
	if(!empty($yearPass))
		{$year=$yearPass;}
	if(!empty($parkcode))
		{
		$park="<input type='hidden' name='parkcode' value='$parkcode'>";
		}
		else
		{$parkcode="";}
	
	echo "<form action='park_daily.php' method='POST'><table>";
	echo "<tr><th colspan='3'>Start Date</th></tr>";
	echo "<tr><td>Year <input type='text' name='year' value='$year' size='5'></th>";
	echo "<th>Month<input type='text' name='month' value='$month' size='5'></th>";
	echo "<th>Day<input type='text' name='day' value='01' size='5'></th>";
	echo "<th>Park<input type='text' name='parkcode' value='$parkcode' size='5'></th>";
	echo "<td>
	<font color='blue' size='+1'>$parkcode</font>
	<input type='submit' name='submit' value='Submit'></td></tr>";
	echo "</table>";
	exit;
	}

header('Location: https://localhost/attend/d/daily_graph_example.pdf');
exit();
$year=$_POST['year'];
$mon=str_pad($_POST['month'],2,'0',STR_PAD_LEFT);
$day=str_pad($_POST['day'],2, '0',STR_PAD_LEFT);
$start_date=$year.$mon.$day; // echo "$start_date";exit;
// Set the constants and variables.

include("../../../include/get_parkcodes_reg.php"); // get park name
// include("get_weather.php"); // get weather data from NCSU
include("/opt/library/prd/WebServer/Documents/attend/a/park_code_areas.php"); // get subunits

define ("PAGE_WIDTH", 792); // 11 inches
define ("PAGE_HEIGHT",612); // 8.5 inches

// Create the Page.	
$pdf = pdf_new(); include("/opt/library/prd/WebServer/include/pdf_key_23.php"); // license key for private server
pdf_open_file ($pdf, "");

// Set the different PDF values.
pdf_set_info ($pdf, "Author", "Tom Howard");
pdf_set_info ($pdf, "Title", "NC State Parks System - Natural Resource Inventory Database");
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
		

	include("make_attend_pdf.php");

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
	pdf_show_xy ($pdf,$text,275,$y);
	}
if($year<2012){$var_p="Weekly";}else{$var_p="Daily";}
$text="NC State Parks System - $var_p Visitation for month";
	//	pdf_show_xy ($pdf,$text,250,$y);
		pdf_show_xy ($pdf,$text,50,$y);
$text="https://auth.dpr.ncparks.gov/attend/d/park_daily.php";
		pdf_show_xy ($pdf,$text,530,$y);




// Finish the page
pdf_end_page ($pdf);

// Close the PDF
pdf_close ($pdf);

//echo "This is used to find any PHP warnings that prevents a successful PDF.  If all errors/warnings are fixed the PDF should be created."; exit;

// Send the PDF to the browser.
$buffer = pdf_get_buffer ($pdf);
header ("Content-type: application/pdf");
header ("Content-Length: " . strlen($buffer));
header ("Content-Disposition: inline; filename=Daily_Visitation.pdf");
echo $buffer;

// Free the resources
pdf_delete ($pdf);

?>