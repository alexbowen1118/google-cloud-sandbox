<?php
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;
$new_VAR=array();
$new_HTML=array();
if(!empty($_POST))
	{

	foreach($_POST AS $key=>$val)
		{
		if(is_array($val))
			{
			foreach($val as $_k=>$_v)
				{
				if(is_array($_v))
					{
					foreach($_v as $_kk=>$_vv)
						{
						if(substr($_vv,0,2)=="0x"){exit;}
						
						$esc_post[$key][$_k][$_kk]=mysql_real_escape_string($_vv);
						$_vv=stripslashes(htmlspecialchars(htmlentities($_vv)));
						$_vv=mysql_real_escape_string($_vv);
						$new_VAR[$key][$_k][$_kk]=$_vv;
						}
					}
					else
					{
					if(substr($_v,0,2)=="0x"){exit;}
						$esc_post[$key][$_k]=mysql_real_escape_string($_v);
					$_v=stripslashes(htmlspecialchars(htmlentities($_v)));
					$_v=mysql_real_escape_string($_v);
					$new_VAR[$key][$_k]=$_v;
					}
				}
			}
			else
			{
			if(substr($val,0,2)=="0x"){exit;}
			$val=mysql_real_escape_string(stripslashes($val));
			$esc_post[$key]=$val;
			$new_VAR[$key]=$val;
			$new_HTML[$key]=stripslashes(htmlspecialchars(htmlentities($val)));
			}
	$_POST=$new_VAR;
	$_POST_HTML=$new_HTML;
		}
	$_REQUEST=$_POST;
	extract($_POST);
	}
//echo "<pre>"; print_r($_POST); echo "</pre>"; // exit;
$new_VAR=array();
if(!empty($_GET))
	{
	foreach($_GET AS $key=>$val)
		{
		if(is_array($val))
			{
			foreach($val as $_k=>$_v)
				{
				if(substr($_v,0,2)=="0x"){exit;}
				$_v=stripslashes(htmlspecialchars(htmlentities($_v)));
				$_v=mysql_real_escape_string($_v);
				$new_VAR[$key][$_k]=$_v;
				}
			}
			else
			{
//	if (ctype_xdigit($val) ) {exit;}0x627564676574
			if(substr($val,0,2)=="0x"){exit;}
			$val=stripslashes(htmlspecialchars(htmlentities($val)));
			$val=mysql_real_escape_string($val);
			$new_VAR[$key]=$val;
			}
	$_GET=$new_VAR;
		}
	$_REQUEST=$_GET;
	extract($_GET);
	}
extract($_REQUEST);
//echo "72<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
?>