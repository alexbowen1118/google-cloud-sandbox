<?php
ini_set('display_errors',1);
extract($_REQUEST);
if(empty($vin)){echo "No VIN was given."; exit;}
$database="photos";
include("/opt/library/prd/WebServer/include/iConnect.inc"); // connection parameters
mysqli_select_db($connection, $database); // database 

$database="fuel";
mysqli_select_db($connection, $database); // database 

$sql="SELECT t1.*, t2.year, t2.make,  t2.license, t1.location, t3.link as sig, concat(t4.Fname, ' ', t4.Mname, ' ', t4.Lname) as full_name
	from fuel.pr10 as t1
	left join fuel.vehicle as t2 on t1.vin=t2.vin
	left join divper.empinfo as t4 on t1.emid=t4.emid
	left join photos.signature as t3 on t4.tempID=t3.personID
	where t1.vin = '$vin'
	";
//	echo "$sql"; exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
$row=mysqli_fetch_assoc($result);
extract($row);
//echo "<pre>"; print_r($row); echo "</pre>";  exit;

//$total_miles=$mileage+$miles_since_start+$miles_this_year;

$pdf = PDF_new(); include("/opt/library/prd/WebServer/include/pdf_key_23.php");

// open new PDF file; insert a file name to create the PDF on disk

if (PDF_begin_document($pdf, "", "") == 0) {
    die("Error: " . PDF_get_errmsg($pdf));
}

PDF_set_info($pdf, "Creator", "surplus_vehicle_checklist.php");
PDF_set_info($pdf, "Author", "Tom Howard");
PDF_set_info($pdf, "Title", "Surplus Vehicle");

PDF_begin_page_ext($pdf, 595, 842, "");

$font = PDF_load_font($pdf, "Helvetica-Bold", "winansi", "");
PDF_setfont($pdf, $font, 14.0);

PDF_set_text_pos($pdf, 200, 784);
$d="VEHICLE CHECKLIST";
PDF_show($pdf, $d);

date_default_timezone_set('America/New_York');

PDF_set_text_pos($pdf, 250, 744); // Date
$text="Agency: DENR - DPR";
PDF_show($pdf, $text);
	pdf_continue_text($pdf, "");
$text="vin: $vin";
	pdf_continue_text($pdf, $text);

PDF_set_text_pos($pdf, 50, 744); // Date
$text="Date: ".date("Y-m-d");
PDF_show($pdf, $text);
	pdf_continue_text($pdf, "");

$show=array("fas_num","model","year","make","mileage","keys","runs","wrecked","flooded","seats", "tire","antenna","hubcaps","windows","windshield","trim","rust","paint_OK","paint_Scratches","paint_Peeling","paint_Faded","dents","other","checked_by");
foreach($row as $fld=>$val)
	{
	if(!in_array($fld, $show)){continue;}
//	if($fld=="mileage"){$val=number_format($val);}
	if($fld=="tire"){$fld="spare tire";}
	$text=$fld.": ".$val;
	pdf_continue_text($pdf, $text);
	pdf_continue_text($pdf, "");
	}

$y_1=pdf_get_value($pdf, "texty", 0);

		$formats=array("jpg"=>"jpeg","tif"=>"tiff");
			$load_image="/opt/library/prd/WebServer/Documents/photos/".$sig; 
	//		echo "l=$load_image<br />";
			$img_size=getimagesize($load_image);
			$height=$img_size[1];
			if($height >100 and $height < 200)
				{$var_scale="0.30";}
			if($height >199 and $height < 300)
				{$var_scale="0.45";}
			if($height >299 and $height < 400)
				{$var_scale="0.60";}
			if($height >401)
				{$var_scale="0.50";}
		//	echo "$scale<pre>"; print_r($img_size); echo "</pre>";  exit;
			$var=explode("/", $sig);
			$ext=array_pop($var); 
			$var=explode(".", $ext);
			$ext=array_pop($var);
	//	echo "$load_image f1=$ext"; //exit;
			$format=$formats[$ext];
			
			
			if(@$emid=="301")  // Greg Schneider
				{$var_scale="0.15";}
			if($format=="tiff")
				{$var_scale="0.90";}
			
			
			$x_1="330";
		$image = PDF_load_image($pdf,$format,$load_image,"");
			//echo "s=$scale<pre>"; print_r($img_size); echo "</pre>";  exit;
		if(empty($var_scale))
			{
			$scale_top="";
			$scale_bottom="scale=0.50";
			}
			else
			{
			$scale_top="scale=$var_scale";
			$var_scale=$var_scale/2;
			$scale_bottom="scale=$var_scale";
			}
		PDF_fit_image($pdf,$image,$x_1,$y_1,$scale_top); // sig top
		pdf_close_image($pdf, $image);



PDF_end_page_ext($pdf, "");

PDF_end_document($pdf, "");

$buf = PDF_get_buffer($pdf);
$len = strlen($buf);

//exit;


header("Content-type: application/pdf");
header("Content-Length: $len");
$filename="checklist_".$make."_".$vin.".pdf";
header("Content-Disposition: inline; filename=$filename");
print $buf;

PDF_delete($pdf);


/*This function is the replacement for the depracated PDF_find_font()

And also here is the 'core font' list, for PDF files, these do not need to be embeded:
- Courier
- Courier-Bold
- Courier-Oblique
- Courier-BoldOblique
- Helvetica
- Helvetica-Bold
- Helvetica-Oblique
- Helvetica-BoldOblique
- Times-Roman
- Times-Bold
- Times-Italic
- Times-BoldItalic
- Symbol
- ZapfDingbats
*/
?>