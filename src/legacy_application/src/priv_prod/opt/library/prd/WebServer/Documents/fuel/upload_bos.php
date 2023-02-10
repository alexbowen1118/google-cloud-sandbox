<?php
//print_r($_FILES); 
// echo "<pre>"; print_r($_REQUEST); print_r($_FILES); echo "</pre>"; exit;
//exit;
ini_set('display_errors',1);
if (!EMPTY($_FILES['file_upload']['name']))
	{
	
// echo "<pre>";print_r($_FILES); echo "</pre>"; exit;
	$size = $_FILES['file_upload']['size'];
		
	if($size>10)
		{
		
		$type = $_FILES['file_upload']['name']; 
		$var = explode(".",$type);
		$ext=array_pop($var);
			
		$file = "BOS_".$_REQUEST['vin'].".".$ext;
		
// 	echo "$file<pre>";print_r($_FILES); print_r($_REQUEST);echo "</pre>"; exit;
		
		$test = $_FILES['file_upload']['tmp_name'];
// 		echo "t=$test";
		if(!is_uploaded_file($test))
			{
			echo "<pre>"; print_r($_FILES); echo "</pre>";  exit;
// 			print_r($_FILES);  print_r($_REQUEST);
			exit;
			}
		
		
		$folder="bill_of_sale"; //make sure www has r/w permissions on this folder
		if (!file_exists($folder)) {mkdir ($folder, 0777);}
		
		/*  not needed since we renamed the file above
				$file=str_replace("/","_",$file);
			//	$file=str_replace(",","_",$file);
				$file=str_replace(" ","_",$file);
			//	$file=str_replace("'","_",$file);
			//	$file=str_replace("#","_",$file);
				$remove_file=str_replace(" ","_",$remove_file);
		*/
			
		$uploadfile = $folder."/".$file;
// 	echo "$uploadfile";exit;
		
	// 	$delete_file=$folder."/".$remove_file;
// 		unlink($delete_file);
		
		move_uploaded_file($_FILES['file_upload']['tmp_name'],$uploadfile);// create file on server
		
		 if (!file_exists($uploadfile)) {echo "The file failed to upload."; exit;}
		 
		}
		else
		{
		$fmp="";
		}
	
	
	if(isset($uploadfile))
		{
		$cc=$_POST['center_code'];
		$sql = "UPDATE vehicle set `user`='$uploadfile', `previous`='$cc', `center_code`='SURP'
		where vin='$vin'";
	//	  echo "$sql";exit;
		$result = @mysqli_query($connection,$sql) or die();
		}
		
		if(empty($_FILES['file_upload_fs20']['name']))
			{
			header("Location: edit.php?table=vehicle&vi=$vin");
			exit;
			}
	}
?>