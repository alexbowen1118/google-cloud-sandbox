<?php
//print_r($_FILES); 
//    echo "<pre>"; print_r($_POST); print_r($_FILES); echo "</pre>"; exit;
//exit;
extract($_POST);

foreach($_FILES['file_upload']['error'] as $index=>$value)
	{
	if($value!==0){continue;}

		$type = $_FILES['file_upload']['type'][$index]; 
		if($type!="image/jpeg"){continue;}
		
		$name = $_FILES['file_upload']['name'][$index]; 
		$var = explode(".",$name);
		$ext=array_pop($var);
		
		$ran=rand(1,1000);
		$file = $equipment_id."_".$ran.".".$ext;
		
// 		echo "$file<pre>";print_r($_FILES); print_r($_POST);echo "</pre>"; exit;
		
		$test=$_FILES['file_upload']['tmp_name'][$index];
		if(!is_uploaded_file($test))
			{
			echo "$test<pre>"; print_r($_POST); print_r($_FILES); echo "</pre>";
			exit;
			}
		
		
		$folder="equipment_photos"; //make sure www has r/w permissions on this folder
		if (!file_exists($folder)) {mkdir ($folder, 0777);}
		
		$folder.="/".$equip_cat_code; //make sure www has r/w permissions on this folder
		if (!file_exists($folder)) {mkdir ($folder, 0777);}
			
		$uploadfile = $folder."/".$file;
	//	unlink($uploadfile);  exit;
		
		move_uploaded_file($_FILES['file_upload']['tmp_name'][$index],$uploadfile);// create file on server
	
		$image = new Imagick($uploadfile); 
		$image->thumbnailImage(150, 0); 
		$tn_image=$folder."/ztn.".$file;
		$image->writeImage($tn_image);
		$image->destroy();
	
	if(isset($uploadfile))
		{
		$sql = "INSERT into equipment_photos set `link`='$uploadfile', `equipment_id`='$equipment_id'";
	//	  echo "$sql";exit;
		$result = @mysqli_query($connection,$sql) or die(" $sql ");
		}
	
	}
		
?>