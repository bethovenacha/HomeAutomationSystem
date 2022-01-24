<?php 
	if(!isset($_SESSION)){
		session_start();
	}
	if(isset($_SESSION['islogin'])){
		$_SESSION['islogin']=true;
	}else{
		$_SESSION['islogin']=false;
	}
	?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Home Facilities Automation</title>
		
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="bootstrap-3.2.0/dist/css/bootstrap.min.css">

		<!-- Optional theme -->
		<link rel="stylesheet" href="bootstrap-3.2.0/dist/css/bootstrap-theme.min.css">

		<!-- Latest compiled and minified JavaScript -->
		<script type="text/javascript" src="javascript/jquery.js"></script>
		<script type="text/javascript" src="bootstrap-3.2.0/dist/js/bootstrap.min.js"></script>
		
	</head>		
	<body>
		<div class="container-fluid">
			<div class="row">
			  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin:0;padding:0;">
					<img src="resources/images/header.jpg" class="img-responsive">		
			  </div>			  		 
			</div>
			  <!--START OF TAB -->
			<div class="row">
								
				<?php
					require_once 'php/VERSION 1/VIEW/view.php';
					require_once 'php/VERSION 1/MODEL/model.php';
					$model = new AutomationModel;
					$view= new AutomationView; 
				
						if(isset($_POST['txtUsername']) && isset($_POST['txtPassword'])){
						$t= $model -> login($_POST['txtUsername'] , $_POST['txtPassword']);
						if($_SESSION['islogin']==true){
							
							if($_SESSION['role']=="admin")
							
							{
							
						?>
						<nav class="navbar navbar-inverse" role="navigation" style="margin:0;background-color:#990033;">	
							<div class="navbar-header">
							  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							  </button>
							
							</div>	
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav">
							  <li  ><a href="#">
									<span class="glyphicon glyphicon-home" id="home"  data-toggle="tooltip" data-placement="top" title="Go back to home." >
										Home
									</span>			
								</a></li>
							  <li><a href="#">
								<span class="glyphicon glyphicon-dashboard" id="settings"  data-toggle="tooltip" data-placement="top" title="Click here to set your facilities.">
								Settings
								</span>	
								</a></li>
								<li><a href="#">
									<span class="glyphicon glyphicon-tasks" id="facility"  data-toggle="tooltip" data-placement="top" title="Click here to on or off your facility.">
										Facility
									</span>	
								</a></li>
								<li><a href="#"> 
									<span class="glyphicon glyphicon-time" id="scheduling"  data-toggle="tooltip" data-placement="top" title="Click here to schedule your facility.">
										Scheduling
										</span>	
								</a></li>
								<li><a href="#">
									<span class="glyphicon glyphicon-info-sign"   data-toggle="modal" 
													data-target="#aboutModal" data-placement="top" title="Click here to know about home facilities automation.">
										About HFA
									</span>	
								</a></li>
								<li><a href="#">
								
									<span class="glyphicon glyphicon-phone" id="contactus"  data-toggle="modal" 
													data-target="#contactModal" data-placement="top" title="Click here to exit.">
										Contact Us
										</span>	
								</a></li>
								<li><a href="#">
									<span class="glyphicon glyphicon-log-out" id="logout"  data-toggle="tooltip" data-placement="top" title="Click here to exit.">
										Log Out
										</span>	
								</a></li>
								  </ul>			  	
						<!-- <input type="text" class="pull-right" size="50" placeholder="Search Cake here..." style="padding:6px;margin:6px;">	-->		
							</div>
						</nav>
						
						<div id="contactModal" style="overflow:auto;" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
							  <div class="modal-content" style="overflow:auto;">

								<div class="modal-header" style="background-color:black;">
								  <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white;">&times;</button>
								  <h4 class="modal-title" id="myModalLabel" style="color:white;">Contact Us</h4>
								</div>
									<div class="modal-body" id="modalFacilityPanel">		 
										Email: Bmacha2013@gmail.com <br>
										Mobile Number: +639094607150<br>
										Address: Gen. Malvar St. AMA Computer College Davao City<br>
								   </div>
								<div class="modal-footer" style="background-color:#900">
									<label class="label label-error"></label>
								  <button type="button" class="btn btn-danger" data-dismiss="modal" style="color:white;font-weight:bold;">Exit</button>
								</div>

							  </div>
							</div>
						  </div>
						
						<div id="aboutModal" style="overflow:auto;" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
							  <div class="modal-content" style="overflow:auto;">

								<div class="modal-header" style="background-color:black;">
								  <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white;">&times;</button>
								  <h4 class="modal-title" id="myModalLabel" style="color:white;">About Us</h4>
								</div>
									<div class="modal-body" id="modalFacilityPanel">		 
										<p>Home facilities automation started in 2014 as a thesis project of two students from AMA Computer College Davao City Philippines. It's main goal is to lessen the green house effect that intensifies global warming. By allowing home owners to control and schedule their facilities, a significant change in the consumption of energy is expected. Home facilities automation is looking forward to deal with the most advance technologies to cater you with only the best of systems. </p>
								   </div>
								<div class="modal-footer" style="background-color:#900">
									<label class="label label-error"></label>
								  <button type="button" class="btn btn-danger" data-dismiss="modal" style="color:white;font-weight:bold;">Exit</button>
								</div>

							  </div>
							</div>
						  </div>
						
						
						<?php
						
						}else if($_SESSION['role']=="superadmin"){
						
						?>
						<nav class="navbar navbar-inverse" role="navigation" style="margin:0;background-color:#990033;">	
							<div class="navbar-header">
							  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							  </button>
							
							</div>	
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav">
							
							 <li data-toggle="modal" data-target="#aboutModal"><a href="#">
									<span class="glyphicon glyphicon-home" id="manageFacility"  data-toggle="tooltip" data-placement="top" title="Click here to manage facilities." >
										DashBoard
									</span>			
								</a></li>
							 <li data-toggle="modal" data-target="#aboutModal"><a href="#">
									<span class="glyphicon glyphicon-tasks" id="manageAuxiliaries"  data-toggle="tooltip" data-placement="top" title="Click here to manage facilities." >
										Auxiliaries
									</span>			
								</a></li>
							 <li data-toggle="modal" data-target="#aboutModal"><a href="#">
									<span class="glyphicon glyphicon-log-out" id="manageLogOut"  data-toggle="tooltip" data-placement="top" title="Click here to manage facilities." >
										Log out
									</span>			
								</a></li>
							 
							
							  </ul>			  	
						<!-- <input type="text" class="pull-right" size="50" placeholder="Search Cake here..." style="padding:6px;margin:6px;">	-->		
							</div>
						</nav>
						<?php
						
						}
						}else{
							$_SESSION['islogin']=false;
							echo 'Login failed, please try again.';
						}
					}
					
											
						
										 
				?>
			  			 
			</div>
			<!--END OF TAB -->
			<!--START OF CONTENT -->
			<div class="row" id="mycontent" >

			<?php
			if(isset($_FILES)) 
			{
				if(isset($_FILES['myfile'])){

					$imageSize=mysql_real_escape_string($_FILES['myfile']['size']);
					// inserting all the image
					if(!empty($imageSize) ){
					
						$target_file = basename($_FILES["myfile"]["name"]);

						$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
					
						if($imageFileType=="jpg" || $imageFileType=="jpeg" || $imageFileType=="png"  ){		
							$imageName=mysql_real_escape_string($_FILES['myfile']['name']);
							$imageData=mysql_real_escape_string(file_get_contents($_FILES['myfile']['tmp_name']));
							$imageType=mysql_real_escape_string($_FILES['myfile']['type']);
							if(substr($imageType,0,5)=="image"){
								mysql_query("INSERT INTO `facility_images` 
											VALUES('','$imageName','$imageType','$imageData','on','0')");
								echo "Image Uploaded.<br>";
							}else{
								echo "Upload failed";
							}
						}

					}	
					else{
						echo "FILE IS TOO LARGE";
					}					
				}
			}
			?>
			<?php 
				if(isset($_POST['btnSetFacility'])){
						
					if(isset($_FILES)) 
					{		
						if(isset($_POST['hiddenId']) || isset($_POST['txtFacilityName'])
							|| isset($_POST['cmbFacilityType']) || isset($_POST['cmbFacilityStatus'])
							|| isset($_POST['cmbFacLine']) 
							|| isset($_POST['hiddenImageOn']) || isset($_POST['hiddenImageOff'])
							
							){
								$id=$_POST['hiddenId'];
								$name=$_POST['txtFacilityName'];
								$type=$_POST['cmbFacilityType'];
								$stat=$_POST['cmbFacilityStatus'];
								$line = $_POST['cmbFacLine'];
								
								
								$imageOn = $_FILES['imageOn']['name'];
								$imageOnTmp= $_FILES['imageOn']['tmp_name'];
								$imageOff = $_FILES['imageOff']['name'];
								$imageOffTmp= $_FILES['imageOff']['tmp_name'];
								$_SESSION['islogin']=true;
								
								if(empty($imageOn) && !empty($imageOff)){
									mysql_query("UPDATE facility SET 
												fac_name='".$name."',
												fac_type='".$type."',
												fac_status='".$stat."',
												fac_line='".$line."',
												
												fac_image='".$_POST['hiddenImageOn']."',
												fac_image_off='".$imageOff ."'
												WHERE fac_id='".$id."'
												
												");
									move_uploaded_file($imageOffTmp,"resources/facilityImages/".$imageOff);
									
								
								}
								else if(empty($imageOff) && !empty($imageOn)){
									mysql_query("UPDATE facility SET 
												fac_name='".$name."',
												fac_type='".$type."',
												fac_status='".$stat."',
												fac_line='".$line."',
												
												fac_image='".$imageOn."',
												fac_image_off='".$_POST['hiddenImageOff'] ."'
												WHERE fac_id='".$id."'
												
												");
									move_uploaded_file($imageOnTmp,"resources/facilityImages/".$imageOn);
									echo "d2";
								}else if(empty($imageOff) && empty($imageOn)){
									mysql_query("UPDATE facility SET 
												fac_name='".$name."',
												fac_type='".$type."',
												fac_status='".$stat."',
												fac_line='".$line."',
												
												fac_image='".$_POST['hiddenImageOn']."',
												fac_image_off='".$_POST['hiddenImageOff'] ."'
												WHERE fac_id='".$id."'
												
												");
											
								}
							
								else{
									move_uploaded_file($imageOnTmp,"resources/facilityImages/".$imageOn);
									move_uploaded_file($imageOffTmp,"resources/facilityImages/".$imageOff);
																			
								
									mysql_query("UPDATE facility SET 
												fac_name='".$name."',
												fac_type='".$type."',
												fac_status='".$stat."',
												fac_line='".$line."',
												
												fac_image='".$imageOn."',
												fac_image_off='".$imageOff ."'
												WHERE fac_id='".$id."'
												
												");
											
												
								}
							
								
								
						}										
						
					}else{
						if(isset($_POST['hiddenId']) || isset($_POST['txtFacilityName'])
							|| isset($_POST['cmbFacilityType']) || isset($_POST['cmbFacilityStatus'])
							|| isset($_POST['cmbFacLine']) 
							|| isset($_POST['hiddenImageOn']) || isset($_POST['hiddenImageOff'])
							){
							
							
								$id=$_POST['hiddenId'];
								$name=$_POST['txtFacilityName'];
								$type=$_POST['cmbFacilityType'];
								$stat=$_POST['cmbFacilityStatus'];
								$line = $_POST['cmbFacLine'];
								
								
								mysql_query("UPDATE facility SET 
												fac_name='".$name."',
												fac_type='".$type."',
												fac_status='".$stat."',
												fac_line='".$line."',
												
												fac_image='".$_POST['hiddenImageOn']."',
												fac_image_off='".$_POST['hiddenImageOff'] ."'
												WHERE fac_id='".$id."'
												
												");
												
								
							}
						
					}
			
			
							$view->showFacilitySettingsForm();
							
					}else{
						
						
						//$view->showAbout();
					}
					
					if($_SESSION['islogin']==false){
							$view->showLoginForm();
							$view->showRegistrationForm();
						}
						else{
							$view->showHome();
						}
				?>
				  		
					
			</div>
			
			
			<div class="row" id="mycontent2" >
			<?php
				if($_SESSION['islogin']==false){
				echo '
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"  >					
						<img src="resources/facilityImages/homeautomationlogo.png" class="img-responsive" 
						 />			
				  </div>
				  <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"  >	
						<img src="resources/facilityImages/gateclose.png" class="img-responsive" />	
									
				  </div>
				  <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"  >					
							<img src="resources/facilityImages/livingroom.png" class="img-responsive"/>			
				  </div>
			  ';
			  }
			 ?>
			</div>
			<!--END OF CONTENT -->
			<div class="row">
			  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin:0;padding:0;">
					<img src="resources/images/footer.jpg" class="img-responsive">		
			  </div>			  		 
			</div>
		</div>
		<script type="text/javascript" src="javascript/mine.js"></script>
		
	</body>
</html>