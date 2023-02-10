<?php
echo "<a href='menu.php?form_type=pasu_decide'>Go back (this page uses pdfLib to create a document)</a>";
exit;
ini_set('display_errors',1);
include("../../include/get_parkcodes_reg.php");
extract($_REQUEST);
if(empty($vin)){echo "No VIN was given."; exit;}
$database="photos";
include("/opt/library/prd/WebServer/include/iConnect.inc"); // connection parameters
mysqli_select_db($connection, $database); // database 

$database="fuel";
mysqli_select_db($connection, $database); // database 

$sql="SELECT t1.*, t2.year, t2.make, t2.mileage, t2.license, t1.location, t3.link as sig, concat(t4.Fname, ' ', t4.Mname, ' ', t4.Lname) as pasu_name
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
$sig_array['pasu']=$sig;
$sig_date['pasu']=$pasu_date;
$sig_date['disu']=$disu_date;
$sig_date['chop']=$chop_date;
//echo "<pre>"; print_r($row); echo "</pre>";  exit;

// get DISU

$dist=$region[$location]; //echo "d=$dist"; exit;
$sql="SELECT park as dist, concat(t3.Fname, ' ', t3.Mname, ' ', t3.Lname) as disu_name, t4.link as disu_sig
from divper.position as t1
left join divper.emplist as t2 on t2.beacon_num=t1.beacon_num
left join divper.empinfo as t3 on t3.emid=t2.emid
left join photos.signature as t4 on t3.tempID=t4.personID
where beacon_title='Law Enforcement Manager' and park_reg='$dist'
";
//	echo "$sql"; exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
$row=mysqli_fetch_assoc($result);
extract($row);
$sig_array['disu']=$disu_sig;

$sql="SELECT concat(t3.Fname, ' ', t3.Mname, ' ', t3.Lname) as chop_name, t4.link as chop_sig
from divper.position as t1
left join divper.emplist as t2 on t2.beacon_num=t1.beacon_num
left join divper.empinfo as t3 on t3.emid=t2.emid
left join photos.signature as t4 on t3.tempID=t4.personID
where t1.beacon_num='60033018'
";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
$row=mysqli_fetch_assoc($result);
extract($row);
$sig_array['chop']=$chop_sig;

/*
$sql="SELECT concat(t3.Fname, ' ', t3.Mname, ' ', t3.Lname) as bo_name, t4.link as bo_sig
from divper.position as t1
left join divper.emplist as t2 on t2.beacon_num=t1.beacon_num
left join divper.empinfo as t3 on t3.emid=t2.emid
left join photos.signature as t4 on t3.tempID=t4.personID
where t1.beacon_num='60036015'
";  echo "$sql"; exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql ".mysqli_error($connection));
$row=mysqli_fetch_assoc($result);
extract($row); 
//$sig_array['bo']=$bo_sig;

*/



// ****************** Start PDf *****************************
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
$text="DPR PR10 SURPLUS VEHICLES";
PDF_show($pdf, $text);

date_default_timezone_set('America/New_York');

PDF_set_text_pos($pdf, 50, 744); // Date
//,"pasu_date","disu_date","chop_date","pasu_name"
$show=array("vin","license","fas_num","model","year","make","mileage","justification");
foreach($show as $k=>$v)
	{
	$fld=$v;
	$val=${$v};
	if(!in_array($fld, $show)){continue;}
	if($fld=="mileage"){$val=number_format($val);}
	
	if($fld=="justification")
		{
		$x = pdf_get_value($pdf, "textx", 0);
		$y = pdf_get_value($pdf, "texty", 0);

		$lines = explode("\n",("justification: ".$val));
		pdf_set_text_pos($pdf,$x ,$y);
		foreach($lines as $line)
			{
			$foo = $line;
			$foo = wordwrap($foo,75,"|");
			$Arrx = explode("|",$foo);
			$i = 0;
			while (@$Arrx[$i] != "")
				{
				pdf_continue_text($pdf,$Arrx[$i]);
				$i++;
				}
			$texty = pdf_get_value($pdf, "texty", 0);
			pdf_fit_textline($pdf,"\n",$x,$texty-14,"");
			}
		continue;
		}
	$text=$fld.": ".$val;
	pdf_continue_text($pdf, $text);
	pdf_continue_text($pdf, "");
	}

$y_1=pdf_get_value($pdf, "texty", 0)-70;

		$formats=array("jpg"=>"jpeg","tif"=>"tiff");
		
		foreach($sig_array as $index=>$sig)
			{
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
		
		PDF_set_text_pos($pdf, 50, $y_1+15);
		
		$temp=$index."_name";
		$name=${$temp};
		$temp=$index."_date";
		$date=${$temp};
		$text="$index: ".$name." ".$date;
		PDF_show($pdf, $text);
		
		PDF_fit_image($pdf,$image,$x_1,$y_1,$scale_top); // sig top
		pdf_close_image($pdf, $image);
		$y_1=$y_1-55;
			}


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