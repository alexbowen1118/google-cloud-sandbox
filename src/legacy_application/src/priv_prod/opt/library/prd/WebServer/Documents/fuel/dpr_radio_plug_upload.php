<?php

	// ********** File
	if(!empty($_FILES))
		{
// 		echo "<pre>"; print_r($_FILES); echo "</pre>"; exit;
		$num=count($_FILES['file_upload']['tmp_name']);
		for($i=0;$i<$num;$i++)
			{
			$temp_name=$_FILES['file_upload']['tmp_name'][$i];
			if($temp_name==""){continue;}

			if(!is_uploaded_file($_FILES['file_upload']['tmp_name'][$i])){exit;}

			$original_file_name = $_FILES['file_upload']['name'][$i];
			$exp=explode(".",$original_file_name);
			$ext=array_pop($exp);


			$uploaddir = "dpr_radio_plug_upload"; // make sure www has r/w permissions on this folder

			if (!file_exists($uploaddir)) {mkdir ($uploaddir, 0777);}

			$sub_folder=$uploaddir."/".date("Y");
			if (!file_exists($sub_folder)) {mkdir ($sub_folder, 0777);}

			$ts=time();
			$file_name=$section."_".$make."_".$model.".".$ext;
			$file_name=str_replace("/", "_",$file_name);
			$file_name=str_replace(" ", "_",$file_name);
			$uploadfile = $sub_folder."/".$file_name;
			move_uploaded_file($temp_name,$uploadfile);// create file on server
			chmod($uploadfile,0777);

			$sql="UPDATE dpr_radio_plugs set file_link='$uploadfile' where id='$pass_id' "; 
			$result = mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));

			}

		}

?>