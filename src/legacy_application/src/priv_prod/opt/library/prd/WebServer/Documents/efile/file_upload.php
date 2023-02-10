<?php
$database="efile";
include("../../include/iConnect.inc"); // database connection parameters
mysqli_select_db($connection,$database);

date_default_timezone_set('America/New_York');

extract($_REQUEST);

//print_r($_FILES); 
//
//echo "<pre>"; print_r($_REQUEST); print_r($_FILES); echo "</pre>"; exit;
//exit;

if (@$add == "Add")
	{
	// ADD
	

	if(empty($cat_id) OR $cat_id==0)
		{
		echo "Please send an email to <a href='mailto:tom.howard@ncdenr.gov?subject=EFILE error'>Tom Howard</a> and include the error message. There is an issue that will need to be corrected.<br />
		Error message: ";
		echo "<pre>"; print_r($_REQUEST); print_r($_FILES); echo "</pre>";exit;
		}
	if(is_null($guideline_group)){
		$guideline_group = 0;
	}
	$sql = "INSERT documents
		set cat_id='$cat_id', park_code='$park_code', title='$title', abstract='$abstract',  web_link='$web_link', clemson_id='$clemson_id', added_by='$tempID', guideline_group='$guideline_group'";
	//	  echo "$sql";exit;
		$result = @mysqli_query($connection,$sql) or die(" $sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
	
	$doc_id=mysqli_insert_id($connection);

	if(!empty($_FILES['file_upload']['name']))
		{
		foreach($_FILES['file_upload']['tmp_name'] as $index=>$tmp_name)
			{
			$size = $_FILES['file_upload']['size'][$index];
			if($size>10)
				{
				$name = $_FILES['file_upload']['name'][$index]; 

				$name=str_replace(" ","_", $name);
				$name=str_replace("'","", $name);
				$name=str_replace("\"","", $name);
				$name=str_replace("&","_", $name);
				$name=str_replace("=","_", $name);
				$name=str_replace(" ","_", $name);

				$folder="file_uploads"; //make sure www has r/w permissions on this folder
				if (!file_exists($folder)) {mkdir ($folder, 0777);}

				$folder.="/".date('Y');
				if (!file_exists($folder)) {mkdir ($folder, 0777);}

				@$uploadfile = $folder."/".$doc_id."_".$name;
					//		echo "$uploadfile";exit;
				// create file on server
				move_uploaded_file($_FILES['file_upload']['tmp_name'][$index],$uploadfile);
				$sql = "INSERT into file_links
						set doc_id='$doc_id', file_link='$uploadfile', size='$size'";
					//	  echo "$sql";exit;
				$result = @mysqli_query($connection,$sql) or die(" $sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
				}
			}
		}

			header("Location: files.php?doc_id=$doc_id");
		exit;
	}


if ($submit == "Update")
	{
	extract($_FILES);
// Prescription
	@$size = $_FILES['file_upload']['size'];
	if($size>10)
		{
		$name = $_FILES['file_upload']['name']; 
		
		$name=str_replace(" ","_", $name);
		$name=str_replace("'","", $name);
		$name=str_replace("\"","", $name);
		$name=str_replace("&","_", $name);
		$name=str_replace("=","_", $name);
		$name=str_replace(" ","_", $name);
		
		$folder="file_uploads"; //make sure www has r/w permissions on this folder
		if (!file_exists($folder)) {mkdir ($folder, 0777);}
		
		$folder.="/".date('Y');
		if (!file_exists($folder)) {mkdir ($folder, 0777);}
			
		if(!empty($_FILES['file_upload']['name']))
		{
		foreach($_FILES['file_upload']['tmp_name'] as $index=>$tmp_name)
			{
			$size = $_FILES['file_upload']['size'][$index];
			if($size>10)
				{
				$name = $_FILES['file_upload']['name'][$index]; 

				$name=str_replace(" ","_", $name);
				$name=str_replace("'","", $name);
				$name=str_replace("\"","", $name);
				$name=str_replace("&","_", $name);
				$name=str_replace("=","_", $name);
				$name=str_replace(" ","_", $name);

				$folder="file_uploads"; //make sure www has r/w permissions on this folder
				if (!file_exists($folder)) {mkdir ($folder, 0777);}

				$folder.="/".date('Y');
				if (!file_exists($folder)) {mkdir ($folder, 0777);}

				@$uploadfile = $folder."/".$doc_id."_".$name;
					//		echo "$uploadfile";exit;
				// create file on server
				move_uploaded_file($_FILES['file_upload']['tmp_name'][$index],$uploadfile);
				$sql = "INSERT into file_links
						set doc_id='$doc_id', file_link='$uploadfile', size='$size'";
					//	  echo "$sql";exit;
				$result = @mysqli_query($connection,$sql) or die(" $sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
				}
			}
		}
		}



// UPDATE

if(!isset($uploadfile)){$uploadfile="";}
if(!isset($park_code))
	{$park_code="";}
	else
	{
	$pc=explode("-",$park_code);
	$park_code=$pc[0];
	}
	if(is_null($guideline_group)){
		$guideline_group = 0;
	}
	$sql = "UPDATE documents
	set park_code='$park_code', title='$title', abstract='$abstract', web_link='$web_link', clemson_id='$clemson_id', added_by='$added_by', guideline_group='$guideline_group' where doc_id='$doc_id'";
//	  echo "$sql";exit;
	$result = @mysqli_query($connection,$sql) or die(" $sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
	
	$go_file="doc_id=$doc_id";
	if(!empty($cat_id)){$go_file.="&pass_cat_id=$cat_id";}
	header("Location: files.php?$go_file");
	exit;
	}
	
if ($submit == "Delete")
	{
	@unlink($uploadfile);
	$sql = "DELETE FROM documents where doc_id='$doc_id'";
//	  echo "$sql";exit;
	$result = @mysqli_query($connection,$sql) or die(" $sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));
	
	header("Location: files.php");
	exit;
	}
?>