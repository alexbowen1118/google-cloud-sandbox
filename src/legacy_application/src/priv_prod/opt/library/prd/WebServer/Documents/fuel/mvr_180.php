<?php
ini_set('display_errors',1);
extract($_REQUEST);
if(empty($tempID)){echo "No VIN was given."; exit;}
$database="photos";
include("/opt/library/prd/WebServer/include/iConnect.inc"); // connection parameters
mysqli_select_db($connection, $database); // database 

$database="fuel";
mysqli_select_db($connection, $database); // database 

$sql="SELECT t1.*, t2.year, t2.make, t2.license, t1.location, t3.link as sig, concat(t4.Fname, ' ', t4.Mname, ' ', t4.Lname) as full_name, (t2.mileage + sum(t5.mileage)) as mileage
	from fuel.pr10 as t1
	left join fuel.vehicle as t2 on t1.vin=t2.vin
	left join fuel.items as t5 on t5.vehicle=t2.id
	left join divper.empinfo as t4 on t1.emid=t4.emid
	left join photos.signature as t3 on t4.tempID=t3.personID
	where t1.vin = '$vin'
	group by t1.vin
	";
//	echo "$sql"; exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
$row=mysqli_fetch_assoc($result);
extract($row);
//echo "<pre>"; print_r($row); echo "</pre>";  exit;

$pdf = PDF_new(); include("/opt/library/prd/WebServer/include/pdf_key_23.php");

// open new PDF file; insert a file name to create the PDF on disk

if (PDF_begin_document($pdf, "", "") == 0) {
    die("Error: " . PDF_get_errmsg($pdf));
}

PDF_set_info($pdf, "Creator", "surplus_vehicle.php");
PDF_set_info($pdf, "Author", "Tom Howard");
PDF_set_info($pdf, "Title", "Surplus Vehicle");

PDF_begin_page_ext($pdf, 595, 842, "");

$font = PDF_load_font($pdf, "Helvetica-Bold", "winansi", "");
PDF_setfont($pdf, $font, 12.0);


$img = new Imagick("MVR-180A_1.pdf"); 
	$img->setImageFormat("jpg");
//	$img->scaleImage(700, 0); 
$new_file="base.jpg";
$file_loc="/opt/library/prd/WebServer/Documents/fuel/".$new_file;
	$img->writeImage($file_loc);
	//echo "$img"; exit;   // for testing
	$img->clear();
	$img->destroy();

$img=PDF_load_image($pdf, "jpeg", "/opt/library/prd/WebServer/Documents/fuel/base.jpg", "");
PDF_fit_image ( $pdf , $img , 1 , 30 , "scale 1" );

date_default_timezone_set('America/New_York');
PDF_set_text_pos($pdf, 490, 744); // Date
PDF_show($pdf, date("Y-m-d"));

PDF_set_text_pos($pdf, 42, 719); // Make
PDF_show($pdf, $make);
PDF_set_text_pos($pdf, 110, 719); // Body Style
PDF_show($pdf, $model);
PDF_set_text_pos($pdf, 222, 719); // Year
PDF_show($pdf, $year);
PDF_set_text_pos($pdf, 372, 719); // VIN
PDF_show($pdf, $vin);

PDF_set_text_pos($pdf, 362, 629); // Mileage
$mileage=number_format($mileage,0);
PDF_show($pdf, $mileage);

PDF_setfont($pdf, $font, 10.0);
if(!empty($parts_damaged))
	{
	$foo = wordwrap($parts_damaged,82,"|");
	$exp = explode("|",$foo);
	$line_1=array_shift($exp);
	$line_2=implode(" ",$exp);
	PDF_set_text_pos($pdf, 196, 343);
	pdf_continue_text($pdf,$line_1);
	PDF_set_text_pos($pdf, 55, 331);
	pdf_continue_text($pdf,$line_2);
	}
if(!empty($salvage_state))
	{
	PDF_set_text_pos($pdf, 196, 293);
	pdf_continue_text($pdf,$salvage_state);
	}
if(!empty($theft_vehicle))
	{
	$foo = wordwrap($theft_vehicle,82,"|");
	$exp = explode("|",$foo);
	$line_1=array_shift($exp);
	$line_2=implode(" ",$exp);
	PDF_set_text_pos($pdf, 196, 231);
	pdf_continue_text($pdf,$line_1);
	PDF_set_text_pos($pdf, 55, 220);
	pdf_continue_text($pdf,$line_2);
	}
	

PDF_setfont($pdf, $font, 12.0);
if(!empty($not_mileage))
	{
	$var="X";
	if($not_mileage=="not_actual"){$y1=603;}
	if($not_mileage=="in_excess"){$y1=590;}
	if($not_mileage=="actual"){$var=""; $y1=590;}
	PDF_set_text_pos($pdf, 56, $y1); // VIN
	PDF_show($pdf, $var);
	}

if(!empty($damage_1))
	{
	$var="X";
	if($damage_1=="NO"){$x1=487;}
	if($damage_1=="YES"){$x1=437;}
	PDF_set_text_pos($pdf, $x1, 344); 
	PDF_show($pdf, $var);
	}

if(!empty($damage_2))
	{
	$var="X";
	if($damage_2=="NO"){$x1=487;}
	if($damage_2=="YES"){$x1=437;}
	PDF_set_text_pos($pdf, $x1, 294); 
	PDF_show($pdf, $var);
	}
if(!empty($damage_3))
	{
	$var="X";
	if($damage_3=="NO"){$x1=487;}
	if($damage_3=="YES"){$x1=437;}
	PDF_set_text_pos($pdf, $x1, 256); 
	PDF_show($pdf, $var);
	}
if(!empty($damage_4))
	{
	$var="X";
	if($damage_4=="NO"){$x1=487;}
	if($damage_4=="YES"){$x1=437;}
	PDF_set_text_pos($pdf, $x1, 232); 
	PDF_show($pdf, $var);
	}
if(!empty($damage_5))
	{
	$var="X";
	if($damage_5=="NO"){$x1=487;}
	if($damage_5=="YES"){$x1=437;}
	PDF_set_text_pos($pdf, $x1, 195); 
	PDF_show($pdf, $var);
	}


//$scale="";
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
			
			$x_1="40";
			$y_1="530";
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
		PDF_fit_image($pdf,$image,($x_1+70),($y_1-385),$scale_bottom); // sig bottom
		pdf_close_image($pdf, $image);

PDF_set_text_pos($pdf, 180, 575); // Full Name
PDF_show($pdf, $full_name);




PDF_end_page_ext($pdf, "");

PDF_end_document($pdf, "");

$buf = PDF_get_buffer($pdf);
$len = strlen($buf);

//exit;


header("Content-type: application/pdf");
header("Content-Length: $len");
$filename=$make."_".$vin.".pdf";
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