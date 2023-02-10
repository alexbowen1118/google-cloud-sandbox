<?php
//print_r($_FILES); 
//echo "<pre>"; print_r($_REQUEST); print_r($_FILES); echo "</pre>"; //exit;
//exit;

if (!EMPTY($_FILES['file_upload']))
	{
	
	extract($_FILES);
	$size = $_FILES['file_upload']['size'];
		
	if($size>10)
		{
		
		$type = $_FILES['file_upload']['name']; 
		$var = explode(".",$type);
		$ext=array_pop($var);
			
		$file = "justification_".$_REQUEST['vin'].".".$ext;
		
//		echo "$file<pre>";print_r($_FILES); print_r($_REQUEST);echo "</pre>"; exit;
		
		if(!is_uploaded_file($file_upload['tmp_name']))
			{
			print_r($_FILES);  print_r($_REQUEST);
			exit;
			}
		
		
		$folder="justification"; //make sure www has r/w permissions on this folder
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
	//	echo "$uploadfile";exit;
		
		$delete_file=$folder."/".$remove_file;
		unlink($delete_file);
		
		move_uploaded_file($file_upload['tmp_name'],$uploadfile);// create file on server
		
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
		$result = @mysqli_query($connection, $sql) or die("c=$connection $sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
		}
//	echo "$delete_file";
	
		header("Location: edit.php?table=vehicle&vi=$vin");
	exit;
	}
?>