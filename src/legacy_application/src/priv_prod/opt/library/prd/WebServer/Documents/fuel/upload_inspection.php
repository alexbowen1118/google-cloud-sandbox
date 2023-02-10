<?php
//print_r($_FILES); 
// echo "<pre>"; print_r($_REQUEST); print_r($_FILES); echo "</pre>"; exit;
//exit;

if (!EMPTY($_FILES['file_upload_inspection']['name']))
	{
	
// 	extract($_FILES);
	$size = $_FILES['file_upload_inspection']['size'];
		
	if($size>10)
		{
		
		$type = $_FILES['file_upload_inspection']['name']; 
		$var = explode(".",$type);
		$ext=array_pop($var);
			
		$file = "inspecton_".$_REQUEST['vin'].".".$ext;
		
// 	echo "$file<pre>";print_r($_FILES); print_r($_REQUEST);echo "</pre>"; exit;
		
		if(!is_uploaded_file($_FILES['file_upload_inspection']['tmp_name']))
			{
			print_r($_FILES);  print_r($_REQUEST);
			exit;
			}
				
		$folder="inspection"; //make sure www has r/w permissions on this folder
		if (!file_exists($folder)) {mkdir ($folder, 0777);}
			
		$uploadfile = $folder."/".$file;
// 	echo "$uploadfile";exit;
		
	//	$delete_file=$folder."/".$remove_file;
	//	unlink($delete_file);
		
		move_uploaded_file($_FILES['file_upload_inspection']['tmp_name'],$uploadfile);// create file on server
		
		}
		else
		{
		$fmp="";
		}
	
	
	if(isset($uploadfile))
		{
		$sql = "UPDATE vehicle set `inspection_doc`='$uploadfile'
		where vin='$vin'";
// 		  echo "$sql";exit;
		$result = @mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
		}
//	echo "$delete_file";
	
	header("Location: edit.php?table=vehicle&vi=$vin");
//	exit;
	}
?>