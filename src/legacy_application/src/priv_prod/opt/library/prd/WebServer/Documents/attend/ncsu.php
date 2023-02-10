<?php

/*
echo "
<form action='http://www.nc-climate.ncsu.edu/dynamic_scripts/cronos/query.php' method='post' name='DataQuery'>

<input name='period' type='hidden' value='90'>
<input name='hourlyOnly' type='hidden' value='true'>


<input type='hidden' name='tempMaxASOS' value='true'> max<br>
<input type='hidden' name='tempMinASOS' value='true'> min
<input type='hidden' name='tempUnit' value='F'>




<input name='precip' type='hidden' value='true'> Precipitation</font>

<input name='precipSum' type='hidden' value='true'> 
<input name='precipUnit' type='hidden' value='in'> 
<input name='precipHeight' type='hidden' value='2m'>

<input name='orderBy' type='hidden' value='ASC'>

<input type='hidden' name='commaFile' value='tab'>

<input type='hidden' name='bot_check' value='272ff98a6a7ed5c9cb18af7acdc1b1ac'>
<input type='hidden' name='bot_check_more' value='26771578816559537511888'>
<input type='hidden' name='bot_check_final' value='f7e8b281fe02348136ac443ade909b85'>
<input name='temporal' type='hidden' value='D'>
<input name='station' type='hidden' value='KRDU'>
<input type='submit' name='Submit' value='RETRIEVE DATA'>
</form>";

*/
ini_set('display_errors',1);

$url="http://www.nc-climate.ncsu.edu/dynamic_scripts/cronos/query.php";

$data=array(
"period"=>"90",
"hourlyOnly"=>"true",
"tempMaxASOS"=>"F",
"orderBy"=>"ASC",
"commaFile"=>"tab",
"bot_check"=>"272ff98a6a7ed5c9cb18af7acdc1b1ac",
"bot_check_more"=>"26771578816559537511888",
"bot_check_final"=>"f7e8b281fe02348136ac443ade909b85",
"temporal"=>"D",
"station"=>"KRDU",
"Submit"=>"RETRIEVE DATA"
);

do_post_request($url,$data);

function do_post_request($url, $data, $optional_headers = null)
{
  $params = array('http' => array(
              'method' => 'POST',
              'content' => $data
            ));
  if ($optional_headers !== null) {
    $params['http']['header'] = $optional_headers;
  }
  $ctx = stream_context_create($params);
  $fp = @fopen($url, 'rb', false, $ctx);
  if (!$fp) {
    throw new Exception("Problem with $url, $php_errormsg");
  }
  $response = @stream_get_contents($fp);
  if ($response === false) {
    throw new Exception("Problem reading data from $url, $php_errormsg");
  }
  return $response;
}
echo "$response";
?>