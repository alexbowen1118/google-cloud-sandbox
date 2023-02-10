<?php

$db="park_use";
$database=$db;
ini_set('display_errors',1);

include("../../../include/iConnect.inc");  //sets $database
mysqli_select_db($connection,$database) or die ("Couldn't select database");
EXTRACT($_REQUEST);
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  exit;
date_default_timezone_set('America/New_York');
//$year=date("Y");  // these should be set in the calling file
//$month=date("m");
//$month="08";
//$mon="08";
$first = date('Y-m-d', mktime(0, 0, 0, $mon, 1, $year));
$last = date('Y-m-t', mktime(0, 0, 0, $mon, 1, $year));
$url="https://nc-climate.ncsu.edu/dynamic_scripts/cronos/getCRONOSdata.php?station=KRDU&start=$first&end=$last&obtype=D&parameter=tempmin,tempmax,precip&hash=79e7114a4a553b9a1b61198e165e6ec9d0f90fd121f7c581eef8c6c54757d";
//	echo "$url"; exit;
	$f = 1;
	$c = 2;//1 for header, 2 for body, 3 for both
	$r = NULL;
	$a = NULL;
	$cf = NULL;
	$pd = NULL;
//	$page = strip_tags(open_page($url,$f,$c,$r,$a,$cf,$pd));
	$page = open_page($url,$f,$c,$r,$a,$cf,$pd);

//	print $page;
	
	$exp=explode("|precip",$page); // pipe symbol |

if(empty($exp[1])){echo "There was a problem obtaining the weather data from NCSU. Contact Tom Howard."; exit;}

	$var=explode("\n",$exp[1]);
//	echo "<pre>"; print_r($var); echo "</pre>"; // exit;


$fld_array=array(1=>"new_date",3=>"min",4=>"max",5=>"precip");
foreach($var as $k=>$v)
	{
	if(empty($v)){continue;}
	$exp=explode("|",$v);
	$clause="";
	foreach($exp as $k1=>$v1)
		{
		if($k1==0){continue;}
		if($k1==2){continue;}
		$fld=$fld_array[$k1];
		if($fld=="min" OR $fld=="max"){$v1=(($v1 * 9)/5)+ 32;  // convert C to F
//echo "v=$v1";exit;
}
		$clause.=$fld."='".$v1."',";
		}
		$clause=rtrim($clause,",");
	$sql="REPLACE weather set $clause";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
// echo "$sql<br />";
	}


function open_page($url,$f=1,$c=2,$r=0,$a=0,$cf=0,$pd="")
	{
	 global $oldheader;
	 $url = str_replace("https://","",$url);
	 if (preg_match("#/#","$url"))
		 {
		  $page = $url;
		  $url = @explode("/",$url);
		  $url = $url[0];
		  $page = str_replace($url,"",$page);
		  if (!$page || $page == ""){
		   $page = "/";
		  }
		  $ip = gethostbyname($url);
		 }
		 else
		{
		$ip = gethostbyname($url);
		$page = "/";
		}
// 		echo "$ip"; exit;
// 	 $open = fsockopen($ip, 80, $errno, $errstr, 60);
	 $open = fsockopen($ip, 443, $errno, $errstr, 60);
	 
	 if ($pd)
		 {
		  $send = "POST $page HTTP/1.0\r\n";
		 }
		 else
		{
		$send = "GET $page HTTP/1.0\r\n";
		}
// 		echo "$send"; exit;
	 $send .= "Host: $url\r\n";
	 if ($r)
	 {
	  $send .= "Referer: $r\r\n";
	 }
	 else
	{
	if (@$_SERVER['HTTP_REFERER'])
		{
		$send .= "Referer: {$_SERVER['HTTP_REFERER']}\r\n";
		}
	}
	 if ($cf)
		{
		if (@file_exists($cf))
			{
			$cookie = urldecode(@file_get_contents($cf));
			if ($cookie)
				{
				$send .= "Cookie: $cookie\r\n";
				$add = @fopen($cf,'w');
				fwrite($add,"");
				fclose($add);
				}
			}
		}
	 $send .= "Accept-Language: en-us, en;q=0.50\r\n";
	 if ($a){
	  $send .= "User-Agent: $a\r\n";
	 }else{
	  $send .= "User-Agent: {$_SERVER['HTTP_USER_AGENT']}\r\n";
	 }
	 if ($pd){
	  $send .= "Content-Type: application/x-www-form-urlencoded\r\n";  
	  $send .= "Content-Length: " .strlen($pd) ."\r\n\r\n";
	  $send .= $pd;
	 }else{
	  $send .= "Connection: Close\r\n\r\n";
	 }
	 fputs($open, $send);
	 while (!feof($open)) {
	  @$return .= fgets($open, 4096);
	 }
	 fclose($open);
	 $return = @explode("\r\n\r\n",$return,2);
// 	 echo "<pre>"; print_r($return); echo "</pre>";  exit;
	 $header = $return[0];
	 if ($cf){
	  if (preg_match("/Set\-Cookie\: /i","$header")){
	   $cookie = @explode("Set-Cookie: ",$header,2);
	   $cookie = $cookie[1];
	   $cookie = explode("\r",$cookie);
	   $cookie = $cookie[0];
	   $cookie = str_replace("path=/","",$cookie[0]);
	   $add = @fopen($cf,'a');
	   fwrite($add,$cookie,strlen($read));
	   fclose($add);
	  }
	 }
	 if ($oldheader){
	  $header = "$oldheader<br /><br />\n$header";
	 }
	 $header = str_replace("\n","<br />",$header);
	 if ($return[1]){
	  $body = $return[1];
	 }else{
	  $body = "";
	 }
	 if ($c === 2){
	  if ($body){
	   $return = $body;
	  }else{
	   $return = $header;
	  }
	 }
	 if ($c === 1){
	  $return = $header;
	 }
	 if ($c === 3){
	  $return = "$header$body";
	 }
	 if ($f){
	  if (preg_match("/Location\:/","$header")){
	   $url = @explode("Location: ",$header);
	   $url = $url[1];
	   $url = @explode("\r",$url);
	   $url = $url[0];
	   $oldheader = str_replace("\r\n\r\n","",$header);
	   $l = "&#76&#111&#99&#97&#116&#105&#111&#110&#58";
	   $oldheader = str_replace("Location:",$l,$oldheader);
	   return open_page($url,$f,$c,$r,$a,$cf,$pd);
	  }else{
	   return $return;
	  }
	 }else{
	  return $return;
	 }
	}	
?>