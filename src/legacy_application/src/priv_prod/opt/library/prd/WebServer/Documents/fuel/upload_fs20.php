<?php
//print_r($_FILES); 
//echo "<pre>"; print_r($_REQUEST); print_r($_FILES); echo "</pre>"; exit;
//exit;

if (!EMPTY($_FILES['file_upload_fs20']))
	{
	
	extract($_FILES);
	$size = $_FILES['file_upload_fs20']['size'];
		
	if($size>10)
		{
		
		$type = $_FILES['file_upload_fs20']['name']; 
		$var = explode(".",$type);
		$ext=array_pop($var);
			
		$file = "FS20_".$_REQUEST['vin'].".".$ext;
		
//		echo "$file<pre>";print_r($_FILES); print_r($_REQUEST);echo "</pre>"; exit;
		
		if(!is_uploaded_file($file_upload_fs20['tmp_name']))
			{
			print_r($_FILES);  print_r($_REQUEST);
			exit;
			}
		
		
		$folder="fs20"; //make sure www has r/w permissions on this folder
		if (!file_exists($folder)) {mkdir ($folder, 0777);}
			
		$uploadfile = $folder."/".$file;
	//	unlink($uploadfile);  exit;
		
		move_uploaded_file($file_upload_fs20['tmp_name'],$uploadfile);// create file on server
		
		}
		else
		{
		$fmp="";
		}
	
	
	if(isset($uploadfile))
		{
		$cc=$_POST['center_code'];
		$sql = "UPDATE vehicle set `fs20`='$uploadfile', `previous`='$cc', `center_code`='SURP'
		where vin='$vin'";
// 	  echo "$sql";exit;
		$result = mysqli_query($connection,$sql) or die();
		}
//	echo "$delete_file";
	
		header("Location: edit.php?table=vehicle&vi=$vin");
	exit;
	}
?>