<?php
	require_once 'c.php';
	
	$name = $_FILES["uploaded_file"]["name"];
	$target_path = "uploads/". basename($name);
	$imageFileType = pathinfo($target_path,PATHINFO_EXTENSION);
	$tmp =$_FILES['uploaded_file']['tmp_name'];	
	$imageName=mysql_real_escape_string($name);//THIS IS THE NAME OF THE FILE
	$imageData=mysql_real_escape_string(file_get_contents($tmp));
			
			$role ="off";
			$id= 0;

			
	
////////////////////////////////////////////////////////THIS PART UPLOADS THE IMAGES ON A DIRECTORY
	  if(move_uploaded_file($tmp , $target_path)) {
		echo "The file ".  basename($name).
			" has been uploaded";
	  } else{
			echo "There was an error uploading the file, please try again!";
			echo "filename: " .  basename( $name);
			echo "target_path: " .$target_path;
	 }

////////////////////////////////////////// THIS PART UPLOADS THE IMAGES ON THE DATABASE	
		//if($imageFileType=="jpg" || $imageFileType=="png" || $imageFileType=="gif"  ||
		//	$imageFileType=="JPG" || $imageFileType=="PNG" || $imageFileType=="GIF"  ||
		//	$imageFileType=="jpeg" || $imageFileType=="JPEG"  
		//){	
	
				mysql_query("INSERT INTO `facility_images` 	
				(fac_images_id,fac_images_name,fac_images_mime,fac_images_value,fac_images_role,fac_id) 
				VALUES('','$imageName','$imageFileType','".$imageData."','".$role."','".$id."')");
				
				//echo "The file ".  basename( $_FILES['uploaded_file']['name']).	" has been uploaded";		
		
		//}

?>
