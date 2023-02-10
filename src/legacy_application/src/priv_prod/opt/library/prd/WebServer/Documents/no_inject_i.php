<?php
 // needs to come after iConnect.inc
$new_HTML=array();
$new_VAR=array();
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
						$_vv=stripslashes(htmlspecialchars(htmlentities($_vv)));
						$_vv=mysqli_real_escape_string($connection, $_vv);
						$new_VAR[$key][$_k][$_kk]=$_vv;
						}
					}
					else
					{
					if(substr($_v,0,2)=="0x"){exit;}
					$_v=stripslashes(htmlspecialchars(htmlentities($_v)));
					$_v=mysqli_real_escape_string($connection, $_v);
					$new_VAR[$key][$_k]=$_v;
					}
				}
			}
			else
			{
			if(substr($val,0,2)=="0x"){exit;}
			$val=mysqli_real_escape_string($connection, stripslashes($val));
		//	$val=str_replace("\\r\\n","\n",$val);  // added _20151015
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
				$_v=mysqli_real_escape_string($connection,$_v);
				$new_VAR[$key][$_k]=$_v;
				}
			}
			else
			{
//	if (ctype_xdigit($val) ) {exit;}0x627564676574
			if(substr($val,0,2)=="0x"){exit;}
			$val=mysqli_real_escape_string($connection,$val);
			$new_VAR[$key]=$val;
			$new_HTML=stripslashes(htmlspecialchars(htmlentities($val)));
			}
		$_GET=$new_VAR;
		$_GET_HTML=$new_HTML;
		}
	$_REQUEST=$_GET;
	extract($_GET);
	}
extract($_REQUEST);
?>