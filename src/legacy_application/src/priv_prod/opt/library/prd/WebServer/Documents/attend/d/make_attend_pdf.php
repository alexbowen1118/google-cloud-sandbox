<?php
$db="park_use";
$database=$db;
ini_set('display_errors',1);
$domain="auth.dpr.ncparks.gov";
include("../../../include/iConnect.inc");  //sets $database
mysqli_select_db($connection,$database) or die ("Couldn't select database");
EXTRACT($_REQUEST);
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;

if(empty($parkcode) and @$division!="d")
	{
	echo "You must select a value from the Park dropdown menu. Click you back button."; exit;
	}
IF(empty($start_date))
	{
	$start_date=$year.$month.$day;
	}
$var_d=$start_date;
$year=substr($var_d,0,4);
$month=substr($var_d,4,2);
$end_date=$year.$month."31";
//$end_date=$year."03"."25";

if(@$period=="month" or empty($period))
	{
	$full_month=$day_name=date("F", mktime(0,0,0,$month,1,$year));
	$time_period="year_month_day>='$start_date' and year_month_day<='$end_date'";
	}

$sql = "SELECT * 
FROM `weather` 
order by new_date";  //echo "$sql"; exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$var_1=str_replace("-","",$row['new_date']);
	$weather[$var_1]=$row;
	}

IF(!empty($parkcode)){$where=" and park like '$parkcode%'";}
IF(!empty($parkcode) and $parkcode=='division'){$where="";}
IF(!empty($parkcode) and @$division=='d'){$parkcode="";$where="";}
IF(empty($parkcode) and @$division=='d'){$where="";}

if($year>"2011")
	{
	$sql = "SELECT year_month_day as date, sum(attend_tot) as tot 
	FROM `stats_day` 
	where $time_period
	$where
	group by year_month_day";  //echo "$sql"; exit;
	}
else
	{
	$time_period="year_month_week>='$start_date' and year_month_week<='$end_date'";
	$sql = "SELECT year_month_week as date, sum(attend_tot) as tot 
	FROM `stats` 
	where $time_period
	$where
	group by year_month_week";  //echo "$sql"; exit;
	}
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");

if(mysqli_num_rows($result)<1){echo "No Visitation was recorded for $parkcode. Click your back button.";
if($level>3){echo "$sql";}
 exit;}

$result = @mysqli_query($connection,$sql) or die("$sql<br>Error #". mysqli_errno($connection) . ": " . mysqli_error($connection));
$total_1 = mysqli_num_rows($result);

while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	@$period_total+=$row['tot'];
	// Get max value for graph X axis
	if($row['tot']>@$maxD)
		{
		$maxD=$row['tot'];
		}
	}
//echo "$maxD"; exit;

function multimax( $array) {
     // use foreach to iterate over our input array.
     foreach( $array as $value ) {
        
         // check if $value is an array...
         if( is_array($value) ) {
            
             // ... $value is an array so recursively pass it into multimax() to
            // determine its highest value.
             $subvalue = multimax($value);
            
             // if the returned $subvalue is greater than our current highest value,
            // set it as our $return value.
             if( $subvalue > @$return ) {
                 $return = $subvalue;
            }
        
        } elseif($value > @$return) {
             // ... $value is not an array so set the return variable if it's greater
            // than our highest value so far.
             $return = $value;
        }
    }
    
     // return (what should be) the highest value from any dimension.
     return $return;
 }



if($maxD>0 and $maxD<10){$maxX=10;$bandIncrement=1;}
if($maxD>9 and $maxD<100){$maxX=ceil($maxD/10)*10;$bandIncrement=10;}
if($maxD>99 and $maxD<1000){$maxX=ceil($maxD/100)*100;$bandIncrement=100;}
if($maxD>999 and $maxD<10000){$maxX=ceil($maxD/1000)*1000;$bandIncrement=1000;}
if($maxD>9999 and $maxD<100000){$maxX=ceil($maxD/10000)*10000;$bandIncrement=10000;}
if($maxD>99999 and $maxD<1000000){$maxX=ceil($maxD/100000)*100000;$bandIncrement=100000;}

//echo "m=$maxX<br />";

// Make the graph
// Set the fonts
$path="/opt/library/prd/WebServer/Documents/inc/fonts/";
$times = PDF_load_font($pdf, $path."Times_New_Roman", "winansi", "");
$helvetica = PDF_load_font ($pdf, $path."Arial", "winansi", "");
$helveticaBold = PDF_load_font ($pdf, $path."arial_bold", "winansi", "");

//echo "<pre>"; print_r($parkCodeName); echo "</pre>";  exit;

if($year<2012){$var_p="Weekly";}else{$var_p="Daily";}
$Sname="$var_p Visitation for $full_month $year";
if(!empty($parkcode) and $parkcode!="division")
	{
	$full_name=@$parkCodeName[$parkcode];
	$Sname.=" for $full_name";
	}
if(!empty($parkcode) and $parkcode=="division")
	{$Sname.=" for the Division";}
if(empty($parkcode) and $division=="d")
	{$Sname.=" for all Parks";}

//echo "s=$Sname $parkcode"; exit;
PDF_setfont($pdf, $timesBoldItalic, 12.0);
pdf_set_value ($pdf, "textrendering", 0);
$width = (pdf_stringwidth ($pdf,$Sname, $timesBoldItalic, 12)/2);
pdf_show_xy ($pdf, $Sname, (PAGE_WIDTH/2)-$width, PAGE_HEIGHT-25);


pdf_setlinewidth($pdf, 0.5);// chart axis
$x1=75;$y1=350; 
$frameHeight=480; // vert. axis $y2line - $y? should equal $frameHeight
$gap=9;
$barW=12.75;
$gapBar=$gap+$barW;
$increment=(6*$gapBar);
	$mDownC=268;


	pdf_setfont ($pdf, $helvetica, 6);
// Make days
$m1=88;

//echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;
for($i=1;$i<=count($ARRAY);$i++)
	{
	$var_date=$ARRAY[$i-1]['date'];
	$day=substr($var_date,-2,2);
	$day_name=date("D", mktime(0,0,0,$month,$day,$year));
	if(substr($day_name,0,1)=="S")
		{
		pdf_setcolor ($pdf, 'both', 'rgb', 1, 0, 0, 0); 
		}
		else
		{
		pdf_setcolor ($pdf, 'both', 'rgb', 0, 0, 0, 0); 
		}

	$text=$day_name;
	@$var_hi=$weather[$var_date]['max'];
	@$var_lo=$weather[$var_date]['min'];
	@$var_pr=$weather[$var_date]['precip'];
		pdf_show_xy ($pdf, $day,$m1, 75);
	if($year>2011)
		{
		pdf_show_xy ($pdf, $text,$m1, 68);
		
		pdf_setcolor ($pdf, 'both', 'rgb', 0, 0, 0, 0); 
		pdf_show_xy ($pdf, $var_hi,$m1, 60);
		pdf_show_xy ($pdf, $var_lo,$m1, 53);
		pdf_show_xy ($pdf, $var_pr,$m1, 46);
		}
		$m1+=21.7;
	}
	if($year>2011)
		{
		pdf_show_xy ($pdf, "RDU weather",30, 68);
		pdf_show_xy ($pdf, "High Temp.",43, 60);
		pdf_show_xy ($pdf, "Low Temp.",43, 53);
		pdf_show_xy ($pdf, "Precipitation",43, 46);
		}
pdf_setcolor ($pdf, 'both', 'rgb', 0, 0, 0, 0); 

// Total for period

$pt=number_format($period_total,0);
$text="Total for $full_month $year: $pt";

	pdf_setfont ($pdf, $helvetica, 12);
		pdf_show_xy ($pdf, $text,$x1, 33);

	pdf_setfont ($pdf, $helvetica, 6);		
// Vertical legend Description
PDF_set_value ( $pdf, "leading", 7 );
$text="N u m b e r

o f

V i s t o r s";
$xBox=48;$yBox=-105;
$width=5;$height=500;$just="center";$feature="blind";
$leftOver=pdf_show_boxed($pdf,$text,$xBox,$yBox,$width,$height,$just,$feature);
if(!isset($mode)){$mode="";}
if($leftOver==0){pdf_show_boxed($pdf,$text,$xBox,$yBox,$width,$height,$just,$mode);}


	$x2line=598; // ************* end point for axis and dashes

// Make base horiz. axis for Coastal Plain
$yc=349.5-$mDownC;
pdf_moveto($pdf, $x1-.249, $yc);
$y2line=$yc;
$x2line=755;
pdf_lineto($pdf, $x2line, $y2line); pdf_stroke($pdf);
$leg="0";
pdf_setfont ($pdf, $helvetica, 5);
pdf_show_xy ($pdf, $leg,$x1-4.5, $yc-2);


// Make dashes lines for Coastal Plain   $x2line=595;
pdf_setdash($pdf,.5,.5);
$n=$maxX/$bandIncrement;$band=$frameHeight/$n;

for($inc=1;$inc<=$n;$inc++)
	{
	@$bandLevel+=$band;
	// echo "max=$maxX n=$n  b=$band bl=$bandLevel<br>";exit;
	$ytc=$yc+$bandLevel; pdf_moveto($pdf, $x1-.249, $ytc);
	$y2line=$ytc;
	pdf_lineto($pdf, $x2line, $y2line); pdf_stroke($pdf);
	$leg+=$bandIncrement;
	$wxl=pdf_stringwidth($pdf,$leg, $helveticaBold, 5);
	pdf_show_xy ($pdf, $leg,$x1-$wxl-2, $ytc-2);
	}

		pdf_setcolor ($pdf, 'both', 'rgb', 0, 0, 0, 0); 
pdf_setdash($pdf,0,0);
		pdf_setfont ($pdf, $timesItalic, 8);
		pdf_set_value ($pdf, "textrendering", 0);
		if(!@$numC){$numC=0;}
		$Ptext="n=$numC";
	//	pdf_show_xy ($pdf, $Ptext,$x1+540, $ytc-10);
	//	pdf_show_xy ($pdf, 'Coastal Plain',$x1+550, $ytc-18);




// Make vertical axis for Coastal Plain
pdf_moveto($pdf, $x1, $yc);
$x2line=$x1;$y2line=(2*$frameHeight)-398;//$y2line=600.25;
pdf_lineto($pdf, $x2line, $y2line); pdf_stroke($pdf);

// chart columns
pdf_setlinewidth($pdf, 0.25);

//PDF_rect ($pdf, float $x, float $y, float $width, float $height )

$xx=$x1-13;$xy=$y1-268-.25;

foreach($ARRAY as $index=>$row)
	{
	$day=substr($row['date'],-2,2);
	$day_name=date("D", mktime(0,0,0,$month,$day,$year));
	if(substr($day_name,0,1)=="S")
		{
		PDF_setrgbcolor_fill($pdf,1,0,0);
		}
		else
		{
		PDF_setrgbcolor_fill($pdf,0,0,1);
		}
	$a=$row['tot'];
	if($a<10){$numA=" ".$a;}else{$numA=$a;}
	if($maxX>100 and $a<2){$a=2;}
	$a=($frameHeight * $a / $maxX);
//	echo "a=$a"; exit;
	$xx=$xx+$barW+$gap;
		pdf_show_xy ($pdf, number_format($numA,0),$xx-3, $a+85);
				
		// drill down link
		if(@isset($linkDB1[$jk]))
			{
			$db1=@$jk."_b";
			}
		if(!isset($park)){$park="";}
		
		$link="http://auth.dpr.ncparks.gov/attend/a/r_ytd.php?year=$year&month=$mon&submit=Enter";
			$starting_xpos = $xx-5;
			$starting_ypos = $a+83;
			pdf_add_weblink($pdf, $starting_xpos, $starting_ypos, $starting_xpos + 25, $starting_ypos + 10, $link);
			
	pdf_rect($pdf,$xx,$xy,$barW,$a);pdf_fill($pdf);
	}
	
pdf_setcolor ($pdf, 'both', 'rgb', 0, 0, 0, 0); 

?>