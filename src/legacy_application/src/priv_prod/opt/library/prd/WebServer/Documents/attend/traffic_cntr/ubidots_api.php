<?php

$curl = curl_init();

$ubidots = "https://industrial.api.ubidots.com/api/v1.6/";

$ubidots_resource = array(
	"datasources/",
	//"?page=2",
	"devices/",
	"statistics/",
	"variables/",
	"utils/
	");

$url = $ubidots . $ubidots_resource[0];
//$url = $ubidots . $ubidots_PIMO;

echo "<br/>" . $url . "<br/>";

$curl = curl_init($url);
$c_header=array('X-Auth-Token: BBFF-1IJNxMPRiNoNvccDiaTJNbaN7adnb4',
	'Content-Type: application/json',
	'charset: utf-8'
	);

//echo "<br/>" . $curl . "<br>";

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_PORT, 443);
curl_setopt($curl, CURLOPT_HTTPHEADER,$c_header);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_VERBOSE, true);
curl_setopt($curl, CURLOPT_HTTPGET, true);
curl_setopt($curl, CURLOPT_HEADER, true);


//$http_code=curl_getinfo($curl,CURLINFO_HTTP_CODE);

//$response_info = curl_getinfo($curl);
//echo "<br/>" . $response_info . "<br/>";


$response = curl_exec($curl);

$header_size=curl_getinfo($curl,CURLINFO_HEADER_SIZE);
$response_header=substr($response,0,$header_size);
$response_body=substr($response, $header_size);

//echo "<br/> HTTP CODE: <br/>" . $http_code . "<br/>";
echo "<br/> RESPONSE HEADER: <br/>" . $response_header . "<br/>";
//echo "<br/> RESPONSE BODY: <br/>" . $response_body . "<br/>";



//$response_dump = $response;

//$response_contents=file_get_contents($url);
//$response_exploded=$response;
//echo "<br/> EXPLODED: <br/> ".$response_exploded."<br/>";
$json_data=stripslashes(html_entity_decode($response_body));
$response_decoded=json_decode($json_data,true);
//echo "<br/>" . $response_decoded . "<br/>";
//echo "<br/>"; print_r(var_dump($response_decoded)); echo "<br/>";
//var_dump($response_decoded);
//.$count "<br/>";

echo "<br/>"; print_r($response_decoded); echo "<br/>";

$json_file=fopen("ubidots_json.txt", "w") or die("Unable to create file.");
fwrite($json_file, $response_decoded);
fclose($json_file);



function print_recursive($arr)
{
	foreach ($arr as $key => $value)
	{
		if (is_array($val))
		{
			print_recursive($val);
		}
		else
		{
			echo "$key = $val <br/>";
		}
	}
	return;
}

print_recursive($response_decoded);

echo "<br/> END!!! <br/>";

/*//checks json for errors
switch (json_last_error) {
	case JSON_ERROR_NONE:
		echo '<br/> - NO ERRORS - ';
		break;
	case JSON_ERROR_DEPTH:
		echo '<br/> - MAX DEPTH for stack exceeded - ';
		break;
	case JSON_ERROR_STATE_MISMATCH:
		echo '<br/> - Underflow or the modes mismatch - ';
		break;
	case JSON_ERROR_CTRL_CHAR:
		echo '<br/> - Unexpected control character found - ';
		break;
	case JSON_ERROR_SYNTAX:
		echo '<br/> - Syntax error, malformed JSON - ';
		break;
	case JSON_ERROR_UTF8:
		echo '<br/> - Malformed UTF-8 characters, possibly incorrectly encoded - ';
		break;
	default:
		echo '<br/> - UNKNOWN ERROR - ';
		break;
}
*/
//$response_sanitized=filter_var($response,FILTER_SANITIZE_SPECIAL_CHARS);
//$response_replace=str_replace("'","\'", $response_sanitized);
//$response_rplc=preg_replace("'", "", $response_replace);
//echo "<br/>  <br/>" . $response_replace . "<br/>";


//$decode=json_decode($response_rplc);
//echo "<br/> ";  print_r(var_dump($decode));  echo"<br/>";
//var_dump($decode);
/*
$decode=json_decode($response,1,,JSON_OBJECT_AS_ARRAY);
//extract($decode);

echo "<br/> DECODED JSON: <br/>";

echo "<br/> " . $decode . "<br/>";

echo "<br/> DECODE ARRAY: <br/>";


*/
//$decode_obj = var_dump($response_replace);
//$temp=$decode_obj;

//$ubidots_json = fopen("ubidots_json.txt","w");
//fwrite($ubidots_json,$temp);
//fclose($ubidots_json);

/*
foreach ($decode_obj as $key => $value)
{
//	echo "<br/> DECODE OBJECTS: <br/>";
//	echo "<br/>" . $decode_obj . "<br/>";
	echo "<br/> OBJECTS PAIRS by Pair: <br/>";
	$$key = $value;
	echo "<br/>" . $key	. ": " . $value . "<br/><br/>";
}
*/
//echo "<br/>count: " . $decode_rplc["count"] . "<br/>";
//echo "next: " . $decode_rplc["next"] . "<br/>";
//echo "previous: " . $decode_rplc["previous"] . "<br/>";
//echo "results: " . $decode_rplc["results"] . "<br/>";


//echo $decode["next"] . "<br/>";

//$next_pg = $decode["next"];

//echo $next_pg . "<br/>";

//echo $next_pg "<br/>";
curl_close($curl);
/*
//echo $next_pg . "<br/>";

//echo "<br/>" . $response; 
//echo "<br/>" . $decode; 

//echo "<br/>next: " . print_r($next) . "<br/>";
echo "next: "; print_r($next); echo "<br/>";

$next_pg=$next;
$key=NULL;
$value=NULL;
$next=NULL;
$count=NULL;
$previous=NULL;

echo "next_pg";
print_r($next_pg);
echo "<br/>";

if (!is_null($next_pg))
{
	echo "if statement next_pg: "; print_r($next_pg); echo "<br/>";

	$curl_next=curl_init();
	$url_next=_toString($next_pg);
	$curl_next=curl_init($url_next);

	echo "initiate curl next_pg: "; print_r($next_pg); echo "<br/>";
	//echo "curl next as url next: "; print_r($curl_next); echo "<br/>";
	echo "url next: "; print_r($url_next); echo "<br/>";



	$options = array(CURLOPT_URL=>$url_next,
					CURLOPT_PORT=>443,
					CURLOPT_HTTPHEADER=>$c_header,
					CURLOPT_RETURNTRANSFER=>true,
					CURLOPT_HTTPGET=>true
				);

	curl_setopt_array($curl_next, $options);

	$response_next=curl_exec($curl_next);

	$decode_next=json_decode($response_next,true);
	//$decode_next_obj=var_dump($decode_next);
	extract($decode_next_obj);

	foreach ($key as $value)
	{
		echo "<br/> DECODED JSON #2: <br/>";
		echo "<br/> Decoded Objects by Pair: <br/>";
		
		$$key=$value;

		echo "<br/>" . $key . ": " . print_r($$key) "<br/>";
		//echo "<br/>".$$name.": "; print_r($$name); echo "<br/>";

	}

	print_r($count);
	print_r($previous);
	//print_r($next);
	//print_r($results);

	curl_close($curl_next);



}
*/

/*
if (!is_empty($decode["next"]))
{
	$curl2 = curl_init();
	$url2 = $decode["next"];
	echo $url2 "<br/>";
	$curl2 = curl_init($url2);

	curl_setopt($curl2, CURLOPT_URL, $url2);
	curl_setopt($curl2, CURLOPT_PORT, 443);
	curl_setopt($curl2, CURLOPT_HTTPHEADER,
		['X-Auth-Token: BBFF-1IJNxMPRiNoNvccDiaTJNbaN7adnb4',
		'Content-Type: application/json'
		]
	);
	curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	//curl_setopt($curl, CURLOPT_VERBOSE, 0);
	curl_setopt($curl2, CURLOPT_HTTPGET, true);

	$response2 = curl_exec($curl2);
	$decode2 = json_decode($response2,true);

	echo "count: " . $decode2["count"] . "<br/>";
	echo "next: " . $decode2["next"] . "<br/>";
	echo "previous: " . $decode2["previous"] . "<br/>";
	echo "results: " . $decode2["results"] . "<br/>";


}
*/

//curl_close($curl);
?>
