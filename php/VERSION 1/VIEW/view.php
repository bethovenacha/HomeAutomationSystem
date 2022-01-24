<?php 
if(!isset($_SESSION)){
	session_start();
}
	
require_once __DIR__ . '/../../connection.php';
////////////////////////////////////////////////////////////////////VIEW//////////////////////////////////////////////////////////
class AutomationView{	
	//This function displays all the facilities available to 
	//it contains javascript on click event that updates the facility bit status and toggles its' on and off image
	function filterFacilities($home,$location,$homeOwnerId){
		//echo $home. $location.$homeOwnerId;
		
		
			echo '
				<input type="hidden" value="'.$homeOwnerId.'" id="hiddenHomeOwnerId"/>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div style="padding:5px;margin-top:10px;height:450px;scroll:auto;background-color:#fff;">
				';
			echo '
				
				<div class="panel panel-danger" id="facilityManagementPanel" style="background-color:#ECEDF0;margin-top:30px;height:390px;overflow:auto;">
					<div class="panel panel-heading">
						Facility Management Panel
						<label class="label label-info" id="filterFacilitiesInformer"></label>
					</div>
				';
		
		echo '
				<div class="container-fluid">
					<div class="row">									
						<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="margin:10px;padding:0;">
							<div class="form-group has-error has-feedback">
								<label class="label label-danger">Home</label>
								<div class="input-group">
									 <span class="input-group-addon">
										<span class="glyphicon glyphicon-tasks"></span>
									 </span>
									 <select class="form-control" id="cmbFacilitiesHomeFilter">
										';
											$QH= mysql_query("SELECT * FROM home WHERE home_owner_id='".$homeOwnerId."'");
											echo '<option></option>';
											while($R=mysql_fetch_array($QH)){
											
												echo '<option>'.$R['home_name'].'</option>';
											}
										echo'
									 </select>
																		
								</div>
							 </div>
						</div>
									
				<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="margin:10px;padding:0;">
					<div class="form-group has-error has-feedback">
						<label class="label label-danger">Location</label>
						<div class="input-group">
							 <span class="input-group-addon">
								<span class="glyphicon glyphicon-tasks"></span>
							 </span>
							 <select class="form-control" id="cmbFacilitiesLocationFilter">
								<option></option>
								';
								$QL=mysql_query("SELECT * FROM location WHERE location_homeowner_id='".$homeOwnerId."'");
								while($rQL=mysql_fetch_array($QL))
								{
									echo '<option>'.$rQL['location_name'].'</option>';
								}
								echo'
							 </select>
																
						</div>
					 </div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="margin:30px;padding:0;">
					<div class="form-group has-error has-feedback">
						<div class="input-group">
							<button class="btn btn-danger" id="btnFacilitiesFilter" onclick="filterFacilities();">Filter</button>
														
						</div>
					 </div>
				</div>
			
			</div>
			</div>
				';
				
				
		$qH= mysql_query("SELECT home_id FROM home WHERE home_name='".$home."' AND home_owner_id='".$homeOwnerId."'");
		$hom_id = mysql_fetch_array($qH);
		$hid = $hom_id['home_id'];
		
		$qL=mysql_query("SELECT location_id FROM location WHERE location_name='".$location."' AND location_homeowner_id='".$homeOwnerId."'");
		$location_id=mysql_fetch_array($qL);
		$locid = $location_id['location_id'];
		
		$QF = mysql_query("SELECT * FROM facility WHERE fac_home_id='".$hid."' AND fac_location_id='".$locid."' AND fac_home_owner_id='".$homeOwnerId."'");
	
		echo '<table class="table table-bordered">';
				echo '<th>Facility Name</th>';
					echo '<th>Home</th>';
					echo '<th>Location</th>';
					echo '<th>Facility On/Off Status</th>';
					echo '<th>Facility Switching Status</th>';
					echo '<th>Action</th>';
					$i=2;
			while($rQF=mysql_fetch_array($QF))
			{
				if($i % 2 == 0)
				{
					echo '<tr style="background-color:lightblue;">';
				}
				else
				{
					echo '<tr>';
				}
				
					
				echo '<td>'.$rQF['fac_name'].'</td>';
				echo '<td>'.$home.'</td>';
				echo '<td>'.$location.'</td>';
				if($rQF['fac_bit']=="1")
				{
					echo '<td>On</td>';
				}
				else{
					echo '<td>Off</td>';
				}
				$qSS= mysql_query("SELECT * FROM switching_status WHERE switching_status_id='".$rQF['fac_switching_status']."'");
				$fqSS=mysql_fetch_array($qSS);
				echo '<td>'.$fqSS['switching_status_name'].'</td>';
				
					echo '<td>
								<div class="container-fluid">
									<div class="row">
									  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="margin:0;padding:0;">
										<img src="resources/facilityImages/btnOn.jpg" 
										id="'.$rQF['fac_id'].'"
										class="img-responsive" width="40" height="40"
										
										onclick="updateFacilityOn('.$rQF['fac_line'].','.$rQF['fac_id'].');"
										/>
									  </div>
									  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="margin:0;padding:0;">
										<img src="resources/facilityImages/btnOff.jpg" class="img-responsive" width="40" height="40"
										
										onclick="updateFacilityOff('.$rQF['fac_line'].','.$rQF['fac_id'].');"
										/>
									  </div>
									</div>
								</div>
							  </td>';
			}
		echo '</tr>';
		echo '</table>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		
	}
	
	function showFacilities(){
		
		if(isset($_SESSION['islogin'])){
				
				if($_SESSION['islogin']==true){
				?>
					
				<?php
					$homeOwnerId="";
					if(isset($_SESSION['clientId'])){
						$homeOwnerId=$_SESSION['clientId'];
					}
					
					echo '
							<input type="hidden" value="'.$homeOwnerId.'" id="hiddenHomeOwnerId"/>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div style="padding:5px;margin-top:10px;height:450px;scroll:auto;background-color:#fff;">
					';
					echo '
						
						<div class="panel panel-danger" id="facilityManagementPanel" style="background-color:#ECEDF0;margin-top:30px;height:390px;overflow:auto;">
								<div class="panel panel-heading">
									Facility Management Panel
									<label class="label label-info" id="filterFacilitiesInformer"></label>
								</div>
						';
						echo '
							<div class="container-fluid">
								<div class="row">									
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="margin:10px;padding:0;">
										<div class="form-group has-error has-feedback">
											<label class="label label-danger">Home</label>
											<div class="input-group">
												 <span class="input-group-addon">
													<span class="glyphicon glyphicon-tasks"></span>
												 </span>
												 <select class="form-control" id="cmbFacilitiesHomeFilter">
													';
														$QH= mysql_query("SELECT * FROM home WHERE home_owner_id='".$homeOwnerId."'");
														echo '<option></option>';
														while($R=mysql_fetch_array($QH)){
														
															echo '<option>'.$R['home_name'].'</option>';
														}
													echo'
												 </select>
																					
											</div>
										 </div>
									</div>
									
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="margin:10px;padding:0;">
										<div class="form-group has-error has-feedback">
											<label class="label label-danger">Location</label>
											<div class="input-group">
												 <span class="input-group-addon">
													<span class="glyphicon glyphicon-tasks"></span>
												 </span>
												 <select class="form-control" id="cmbFacilitiesLocationFilter">
													<option></option>
													';
													$QL=mysql_query("SELECT * FROM location WHERE location_homeowner_id='".$homeOwnerId."'");
													while($rQL=mysql_fetch_array($QL))
													{
														echo '<option>'.$rQL['location_name'].'</option>';
													}
													echo'
												 </select>
																					
											</div>
										 </div>
									</div>
									
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="margin:30px;padding:0;">
										<div class="form-group has-error has-feedback">
											<div class="input-group">
												<button class="btn btn-danger" id="btnFacilitiesFilter" onclick="filterFacilities();">Filter</button>
																			
											</div>
										 </div>
									</div>
									
									
								
							  </div>
							</div>
										';
						
						echo '<div class="panel-body" id="panelHomeListBody">';
					$homeOwnerId="";
					if(isset($_SESSION['clientId'])){
						$homeOwnerId=$_SESSION['clientId'];
					}
					$qq=mysql_query("SELECT * FROM facility WHERE fac_home_owner_id='".$homeOwnerId."'");
					
					echo '<table class="table table-bordered">';
					echo '<th>Facility Name</th>';
					echo '<th>Home</th>';
					echo '<th>Location</th>';
					echo '<th>Facility On/Off Status</th>';
					echo '<th>Facility Switching Status</th>';
					echo '<th>Action</th>';
						$i=2;
					while($fq=mysql_fetch_array($qq)){
						if($i%2 == 0)
						{
							echo '<tr style="background-color:lightblue;">';
						}
						else{
							echo '<tr>';
						}
						
						$i++;
						echo '<td>'.$fq['fac_name'].'</td>';
						echo '<td>';
							$qhom = mysql_query("SELECT * FROM home WHERE home_id='".$fq['fac_home_id']."'");
							$fqhom=mysql_fetch_array($qhom);
							echo $fqhom['home_name'];
							
						echo '</td>';
						echo '<td>';
							$floc = mysql_query("SELECT * FROM location WHERE location_id='".$fq['fac_location_id']."'");
							$fetch_loc = mysql_fetch_array($floc);
							echo $fetch_loc['location_name'];
						echo '</td>';
						
						echo '<td>';
							if($fq['fac_bit'] == "1")
							{
								echo 'On';
							}else{
								echo 'Off';
							}
						echo'</td>';
						echo '<td>'; 
							$ss = mysql_query("SELECT * FROM switching_status WHERE switching_status_id='".$fq['fac_switching_status']."'");
							$fss = mysql_fetch_array($ss);
							
							echo $fss['switching_status_name'];
						echo'</td>';
						//data-toggle="modal" 
										//data-target="#facilitiesModal"
										
						echo '<td>
								<div class="container-fluid">
									<div class="row">
									  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="margin:0;padding:0;">
										<img src="resources/facilityImages/btnOn.jpg" 
										id="'.$fq['fac_id'].'"
										class="img-responsive" width="40" height="40"
										
										onclick="updateFacilityOn('.$fq['fac_line'].','.$fq['fac_id'].');"
										/>
									  </div>
									  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="margin:0;padding:0;">
										<img src="resources/facilityImages/btnOff.jpg" class="img-responsive" width="40" height="40"
										
										onclick="updateFacilityOff('.$fq['fac_line'].','.$fq['fac_id'].');"
										/>
									  </div>
									</div>
								</div>
							  </td>';
							
					}
						echo '</tr>';
					echo '</table>';
						echo '</div>';
					echo '</div>';
						/*
						if($rs['fac_bit']=="1"){			
							$img=mysql_query("SELECT * FROM facility_images WHERE fac_id='".$rs['fac_image_id']."' 
											AND fac_images_role='on'
											");
							$imgs=mysql_fetch_array($img);		
							
							$home =mysql_query("SELECT * FROM home WHERE home_id='".$rs['fac_home_id']."'");
							
							$hom=mysql_fetch_array($home);
							
								$data = $imgs['fac_images_value'];
									
										$type = $imgs['fac_images_mime'];
										echo '<div class="col-xs-3 col-sm-2 col-md-2 col-lg-2">';
											echo '<img src="data:image/'.$type.';base64,'.base64_encode($imgs['fac_images_value']).'" width="250" height="350" class='."img-responsive".'
											id='.$rs['fac_line'].' onclick='."updateFacility(this.id);".'
											/>
											<tab>';		
										echo "<img src='resources/images/buttonImage/On.jpg' width='50' height='30' />";
										echo "  <img src='resources/images/buttonImage/btnOff.jpg' width='50' height='30' />";
											echo "<br>";
										echo $hom['home_name'];
										echo "<br>";
										echo $rs['fac_name'];
										echo '</div>';
																										
																													
						}else{
							$img=mysql_query("SELECT * FROM facility_images WHERE fac_id='".$rs['fac_image_id']."' 
											AND fac_images_role='off'
											");
							$imgs=mysql_fetch_array($img);						
							$home =mysql_query("SELECT * FROM home WHERE home_id='".$rs['fac_home_id']."'");
							$hom=mysql_fetch_array($home);
							$data = $imgs['fac_images_value'];
								
									$type = $img['fac_images_mime'];
									echo '<div class="col-xs-3 col-sm-2 col-md-2 col-lg-2">';
											echo '<img src="data:image/'.$type.';base64,'.base64_encode( $imgs['fac_images_value'] ).'" width="150" height="150" class='."img-responsive".'
											id='.$rs['fac_line'].' onclick='."updateFacility(this.id);".'
											/>
											<tab>';		
										echo $hom['home_name']."<br>";
										echo $rs['fac_name'];
									echo '</div>';
								
							
						}
						*/
				
					
					
					
				echo '
								</div>
							</div>							
					';
					?>
						<div id="facilitiesModal" style="overflow:auto;" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
							  <div class="modal-content" style="overflow:auto;">

								<div class="modal-header" style="background-color:black;">
								  <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white;">&times;</button>
								  <h4 class="modal-title" id="myModalLabel" style="color:white;"></h4>
								</div>
									<div class="modal-body" id="modalFacilityPanel">		 
										<div id="MyOwnInformer"></div>
								   </div>
								<div class="modal-footer" style="background-color:#900">
									<label class="label label-error">Turning on or off facility with Scheduled switching status deactivates its schedule</label>
								  <button type="button" class="btn btn-danger" data-dismiss="modal" style="color:white;font-weight:bold;">Exit</button>
								</div>

							  </div>
							</div>
						  </div>
					<?php
				echo '<script type="text/javascript" src="javascript/mine.js"></script>';
				
			}
		}

	}
	
	/*
	<div class="inner-addon left-addon">
    <i class="glyphicon glyphicon-user"></i>
    <input type="text" class="form-control" />
</div>
	*/
	function showRegistrationForm(){
		echo '<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
			<div class="panel" style="margin-top:30px;overflow:auto;background-color:#ECEDF0;">
					<div class="panel panel-heading" style="background-color:#900;">
						<span style="color:white;font-weight:bold;">Registration
						</span> 
						<img src="resources/facilityImages/circular87.png" width="50" height="60" class="pull-right"/>
						</div>
						<div class="panel-body">	
						
						<div class="form-horizontal">
							
										<div class="form-group has-error has-feedback">
											<div class="input-group">
												 <span class="input-group-addon">
													<span class="glyphicon glyphicon-user"></span>
												 </span>
												<input type="text" class="form-control" maxlength="100" id="txtFirstName" placeholder="First Name..." required />											
											</div>
										</div>
									
										<div class="form-group has-error has-feedback">
											<div class="input-group">
													 <span class="input-group-addon">
														<span class="glyphicon glyphicon-user"></span>
													 </span>
													<input type="text" class="form-control" maxlength="1" id="txtMiddleInitial" 
													placeholder="Middle Initial"
													required/>
											</div>
										</div>
									
										<div class="form-group has-error has-feedback">
											<div class="input-group">
												 <span class="input-group-addon">
													<span class="glyphicon glyphicon-user"></span>
												</span>
												
													<input type="text" class="form-control" maxlength="100" id="txtLastName" placeholder="Last Name" required/>
												
											</div>
										</div>
									
										<div class="form-group has-error has-feedback">
											<div class="input-group">
												 <span class="input-group-addon">
													<span class="glyphicon glyphicon-tasks"></span>
												</span>
												<input type="text" class="form-control" maxlength="100" id="txtRegUsername" 
												placeholder="Username..."
												required/>
											</div>
										</div>
									
										<div class="form-group has-error has-feedback">
											<div class="input-group">
												 <span class="input-group-addon">
													<span class="glyphicon glyphicon-tasks"></span>
												</span>
												<input type="password" class="form-control" maxlength="100" id="txtRegPassword" placeholder="Password..." required/>
											</div>
										</div>
									
										<div class="form-group has-error has-feedback">
											<div class="input-group">
												 <span class="input-group-addon">
													<span class="glyphicon glyphicon-tasks"></span>
												</span>
												<input type="password" class="form-control" maxlength="100" id="txtVerifyPassword" placeholder="Verify Password" required />
										</div>
										</div>
									
										<div class="form-group has-error has-feedback">
											<div class="input-group">
												 <span class="input-group-addon">
													<span class="glyphicon glyphicon-globe"></span>
												</span>
												<input type="text" class="form-control" maxlength="100" id="txtAddress" 
												placeholder="Address..."
												required />
											</div>
										</div>
									
										<div class="form-group has-error has-feedback">
											<div class="input-group">
												 <span class="input-group-addon">
													<span class="glyphicon glyphicon-phone"></span>
												</span>
												<input type="text" class="form-control" maxlength="100" id="txtContactNumber" 
												placeholder="Contact Number..."
												required />
											</div>
										</div>
									
										<div class="form-group has-error has-feedback">
											<div class="input-group">
												 <span class="input-group-addon">
													<span class="glyphicon glyphicon-envelope"></span>
												</span>
												<input type="text" class="form-control" maxlength="100" id="txtEmail" 
												placeholder="Email..."
												required />
											</div>
										</div>
									
										<div class="form-group has-error has-feedback">
											<div class="input-group">
												 <span class="input-group-addon">
													<span class="glyphicon glyphicon-tasks"></span>
												</span>
										
												<select class="form-control" id="cmbGender">
													<option>Gender...</option>
													<option>Male</option>
													<option>Female</option>
												</select>
											</div>
										</div>
									
										<div class="form-group has-error has-feedback">
											<div class="input-group">
												 <span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
										
												<input type="date" class="form-control" maxlength="100" id="dtBirthday" 
												
												required />
											</div>
										</div>
									
										<button class="btn btn-danger" id="btnRegistration">
										<span class="glyphicon glyphicon-pencil"></span>
										Register
										</button>
										<label id="informer" class="label label-danger pull-right"></label>
									
							</form>
							
						</div>
			</div>
		</div>
				';
				echo '<script type="text/javascript" src="javascript/mine.js"></script>';
	}
	function showClientHomeLocation($id,$home_id){
		$loc= mysql_query("SELECT * FROM location WHERE 
									location_homeowner_id='".$id."'
									AND location_home_id='".$home_id."'
									");
		echo '
			<input type="text" class="form-control" id="txtAddLocation" placeholder="Add location here..." required/>
			<input type="button" value="Set location for configuration" class="btn btn-danger" onClick="addLocation('.$home_id.','.$id.');"/>
			<table class="table table-bordered">		
				<th>House Locations</th>
				<th>Action</th>
				';		
			
			while($floc=mysql_fetch_array($loc)){
				echo '<tr>';
				echo '<td>
						<input type="text" id="txtHomeLocation'.$floc['location_id'].'" value="'.$floc['location_name'].'" class="form-control" required/>
						</td>';
				echo '<td>
						<label class="label label-info" 
							id="'.$floc['location_home_id'].'" 
							onClick="clickLocation(this.id,'.$id.','.$floc['location_id'].');"
						>
						Update Location</label>
							<label class="label label-success"
							id="'.$floc['location_id'].'"
							onClick="delLocation(this.id);"
							>
								Delete Location
							</label>
							<label class="label label-primary" 
								onClick="viewFacilities('.$id.','.$home_id.','.$floc['location_id'].');"
							>
							
								View Facilities
							
							</label>
					  </td>';
			}
		
	
			echo '
				</tr>
				</table>	
			';	
			echo '<input type="button" id="'.$id.'" onClick="clientClick(this.id)" class="pull-right btn btn-danger" value="Back"/>';
			echo '<script type="text/javascript" src="javascript/mine.js"></script>';
		
		
	}
	function showClientHome($id){
		$q=mysql_query("SELECT * FROM home WHERE home_owner_id='".$id."'");	
						echo '	
							<input type="text" id="hiddenHomeOwnerId" value="'.$id.'"/>
						';
				
						echo '
						<div class="form-group has-error">
							<input type="text" class=" form-control" id="txtAddHomeName" size="15" required/>
						</div>
					';
						echo '<input type="button" id="btnAddHome" class="btn btn-danger" value="Set home for configuration"/><br><br>';
						echo '<table class="table table-bordered">		
								<th>Home Address</th>
								<th>Action</th>
							';				
							while($r=mysql_fetch_array($q)){
								echo '<tr>						
									';
								echo '<td>		
										
										<input type="text" class="form-control" value="'.$r['home_name'].'" id="homename'.$r['home_id'].'" required/>
										
										</td>';
										//first param of update Home Name is the home owner id
										//2nd param is the home id
								echo '<td>
											<label class="label label-info" 
												onClick="updateHomeName('.$id.','.$r['home_id'].');">Update home
											</label>
											<label class="label label-success"
												onClick="deleteHome('.$id.','.$r['home_id'].');"
											>
												
													Delete home
												
											</label>
												<label class="label label-primary"
													id="'.$r['home_owner_id'].'" 					
													onClick="homeClick(this.id,'.$r['home_id'].');"
													>
														View Facilities Location
												</label>
												<span id="informer"></span>
												
										  </td>';
							}
							echo '</tr>';
						echo '
							</table>			
					';				
				echo '<input type="button" id="backmanageFacility" class="pull-right btn btn-danger" value="Back"/>';
				echo '<script type="text/javascript" src="javascript/mine.js"></script>';
	}
	function showClientForm(){
		echo '
			<div id="manageFacilities" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
				
				  <div class="modal-content">
					<div class="modal-header" style="background-color:black;">
					  <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white;">&times;</button>
					  <h4 class="modal-title" id="myModalLabel" style="color:white;">
					  
					  Edit Home Owner
					  
					  </h4>
					</div>
					<div class="modal-body">
						<input type="hidden" id="hiddenClientId"/>
						<table class="table table-bordered">
							<tr>
								<td>
									<label class="label label-default">First Name:</label>
								</td>
								<td>
									<div class="form-group has-error">
										<input type="text" class="form-control" maxlength="100" id="txtFirstName" required />
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label class="label label-default">Middle Initial:</label>
								</td>
								<td>
									<div class="form-group has-error">
										<input type="text" class="form-control" maxlength="1" id="txtMiddleName" required />
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label class="label label-default">Last Name:</label>
								</td>
								<td>
									<div class="form-group has-error">
										<input type="text" class="form-control"  maxlength="100" id="txtLastName" required />
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label class="label label-default">Contact Number:</label>
								</td>
								<td>
									<div class="form-group has-error">
										<input type="text" class="form-control"  maxlength="100" id="txtContact" required />
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label class="label label-default">Status:</label>
								</td>
								<td>
									<div class="form-group has-error">
										<select class="form-control" id="cmbStatus" required>
											<option></option>
											<option>active</option>
											<option>inactive</option>
										</select>
										
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label class="label label-default">Address:</label>
								</td>
								<td>
									<div class="form-group has-error">
										<input type="text" class="form-control"  maxlength="100" id="txtAddress" required />
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label class="label label-default">Email:</label>
								</td>
								<td>
									<div class="form-group has-error">
										<input type="text" class="form-control"  maxlength="100" id="txtEmail" required />
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label class="label label-default">Gender:</label>
								</td>
								<td>
									<div class="form-group has-error">
										<select class="form-control" id="cmbGender">
											<option></option>
											<option>Male</option>
											<option>Female</option>
										</select>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label class="label label-default">Birthday:</label>
								</td>
								<td>
									<div class="form-group has-error">
										<input type="date" class="form-control"  maxlength="100" id="dtBdate" required />
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label class="label label-default">Registration Date:</label>
								</td>
								<td>
									<div class="form-group has-error">
										<input type="date" class="form-control"  maxlength="100" id="dtRegDate" required />
									</div>
								</td>
							</tr>
						  
						 
						</table>
					</div>
					<div class="modal-footer" style="background-color:#900">
					  <button type="button" id="btnUpdateClientInfo" 
					  class="btn btn-danger" data-dismiss="modal" style="color:white;font-weight:bold;"
					  >Update</button>
					</div>

					</div>
				</div>
			</div>
		';
		echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">	
				<input type="text" id="txtHiddenHomeOwnerIdd"/>
				<input type="text" id="txtHiddenHomeIdd"/>
				<input type="text" id="txtHiddenLocationIdd"/>
				<input type="text" id="txtHiddenFacilityIdd"/>
				
				<div class="panel panel-danger" style="margin-top:30px;overflow:auto;">
					<div class="panel panel-heading">
					Facility Management Panel
					
					</div>
						<div class="panel-body" id="clientContainer" style="overflow:auto;">	
						   
						   <select id="cmbAction">
							<option>Select All</option>
							<option>Unselect All</option>
						   </select>
						   
						   <input type="button" class="btn btn-danger" value="Perform"/>
						   
						    <input type="text"   placeholder="Search Name..." >		
						    <input type="button" class="btn btn-danger" value="Search"/>
								<table class="table table-bordered" style="overflow:auto;">
									<th>All<input type="checkbox" id="maincheck"/></th>
									<th>Action</th>
									<th>Name</th>
									<th>Contact Number </th>
									<th>Status</th>
									<th>Address</th>
									
									<th>Email</th>
									<th>Gender</th>
									<th>Birthday</th>
									<th>Registration Date</th>
									
								';
								
							
								$q=mysql_query("SELECT * FROM registration");
								while($r=mysql_fetch_array($q)){
								echo '<tr 
								id="'.$r['reg_id'].'" 
								>';
								echo '<td><input type="checkbox" id="'.$r['reg_id'].'" class="myClientCheck"
									</td>';
									echo '<td>
											  <label class="label label-primary" 
													 id="'.$r['reg_id'].'" 
													 data-toggle="modal" 
													data-target="#manageFacilities"
													 onClick="updateClientInfor(this.id);"
											  >Edit
											  </label>
											  <br>
											  <label id="'.$r['reg_id'].'" class="label label-default" onClick="clientClick(this.id)">View Home(s)</label>
									</td>';
									echo '<td > 
											 '.$r['reg_fname']." ".$r['reg_mname']." ".$r['reg_lname'].'
										  </td>';
									echo '<td>'
											.$r['reg_contact_no'].'
										 </td>';
									echo '<td>
											'.$r['reg_status'].'
										</td>';
									echo '<td>'
											.$r['reg_address'].'
										</td>';
									echo '<td>
												'.$r['reg_email'].'
										</td>';
									
									echo '<td>
												'.$r['reg_gender'].'											
										</td>';
									echo '<td>											
												'.$r['reg_birthday'].'											
										</td>';
									echo '<td>
											'.$r['reg_date'].'											
										  </td>';
															
								}
								
									echo '</tr>';
										
									
										
									
									echo '
									
								</table>
							
					</div>
				</div>
			</div>
				';
				echo '<script type="text/javascript" src="javascript/mine.js"></script>';
	
	}
	//This function show the login form	
	function showLoginForm(){
			
			echo '	
			
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<div class="panel" style="background-color:#ECEDF0;margin-top:30px;height:510px;overflow:auto;">
					<div class="panel panel-heading" style="background-color:#900;">
					<span style="color:white;font-weight:bold;">Login</span> 
						<img src="resources/facilityImages/circular87.png" width="50" height="60" class="pull-right"/>				
					</div>
						<div class="panel-body">	
						    <form action="index.php" method="POST">
								<div class="form-horizontal" style="padding:10px;">						
										
										<div class="form-group has-error has-feedback">
											<div class="input-group">
												 <span class="input-group-addon">
													<span class="glyphicon glyphicon-tasks"></span>
												</span>
												<input type="text" class="form-control" maxlength="100" placeholder="Username..." id="txtUsername" name="txtUsername" required/>
											</div>
										</div>
																		
										<div class="form-group has-error has-feedback">
											<div class="input-group">
												 <span class="input-group-addon">
													<span class="glyphicon glyphicon-tasks"></span>
												</span>
												<input type="password" class="form-control" maxlength="100" placeholder="Password..." id="txtPassword" name="txtPassword" required/>
											</div>
										</div>									
										
										<div class="form-group has-error has-feedback">
											<div class="input-group">
													
														<button class="btn btn-danger" type="submit" name="btnLogin" 
														>
													
													<span class="glyphicon glyphicon-log-in"></span> Login</button>
													
											</div>
										</div>
								</div>
									
								
							</form>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<h4>Welcome</h4><br>
						Home facilities automation allows you to 
						turn on and off the electrical devices in your house
						from anywhere around the world. You can use website,desktop,
						and android phone to manipulate. Reduce your effort,
						and electrical bills. 
					</div>
				</div>
				
			</div>
				';
				echo '<script type="text/javascript" src="javascript/mine.js"></script>';
	}	
	//This function shows the scheduling form 
	function showSchedulingForm(){
		
		if(isset($_SESSION['islogin'])){
				if($_SESSION['islogin']==true){
				
					echo '
					<div class="col-sm-12 col-xs-12 col-md-4 col-lg-4">
						<div class="panel panel-danger" style="margin-top:10px;overflow:auto;background-color:#ECEDF0;height:700px;"  >
								<div class="panel panel-heading" >
									Scheduling Panel
									<span class="glyphicon glyphicon-chevron-down pull-right" 
									width="50" height="40">
									</span>
								</div>
									<div class="panel-body">
										<table class="table table-bordered" style="background-color:#FFF;">
											<tr>
												<td><label>Home</label></td>
												
												<td>
												';
												$client_id="";
												if(isset($_SESSION['clientId'])){
													$client_id = $_SESSION['clientId'];
													$c=mysql_query("SELECT * FROM home WHERE home_owner_id='".$_SESSION['clientId']."'");
													echo '<select class="form-control" id="cmbSchedulingHome"><option></option>';
													while($fc=mysql_fetch_array($c)){
														echo '<option>'.$fc['home_name'].'</option>';
													}
													echo '</select>';
												}
												echo '
													
												</td>
											</tr>
											<tr>
												<td><label>Location</label></td>
												<td>';
												$qloC = mysql_query("SELECT * FROM location WHERE location_homeowner_id='".$client_id."'");
												echo '<select class="form-control" id="cmbSchedulingLocation">';
												echo '<option></option>';
													while($fqloC = mysql_fetch_array($qloC)){
														echo '<option>'.$fqloC['location_name'].'</option>';
													}
												echo '</select>';
												echo '
												</td>
											</tr>
											
											<tr>
												<td><label for="txtFacility">Facility Name:</label>
													<input type="hidden" id="schedId" name="schedId"/>
													<input type="hidden" id="schedFid" name="schedFid"/>
												</td>
												<td>';
												$qf="";
												if(isset($_SESSION['clientId'])){
													$qf=mysql_query("SELECT DISTINCT(fac_name) FROM facility 
																	WHERE
																		fac_home_owner_id='".$_SESSION['clientId']."'
																	");
												}
												echo '<select class="form-control" id="schedFacilityName"><option></option>';
													while($rf=mysql_fetch_array($qf)){
														echo '<option>'.$rf['fac_name'].'</option>';
													}	
												echo '</select>';												
												echo '</td>
											</tr>
											<tr>
												<td> <label for="txtFacility">From Date:</label>
												</td>
												<td>
													<input type="date" class="form-control" id="txtFromDate" name="txtSchedDate" />
												</td>
											</tr>
											<tr>
												<td> <label for="txtFacility">To Date:</label>
												</td>
												<td>
													<input type="date" class="form-control" id="txtToDate" name="txtSchedDate" />
												</td>
											</tr>
											<tr>
												<td><label for="txtStartTime">Start Time:</label></td>
												<td>
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
													<label class="label label-info">(hour)</label>
													<input type="number" class="form-control" id="nmbStartHour" name="nmbStartHour" min="0" max="12" value="0"/>
												</div>
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
													<label class="label label-info">(min)</label>
													<input type="number" class="form-control" id="nmbStartMin" name="nmbStartMin" min="0" max="59" value="0"/>
												</div>
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
													<label class="label label-info">
													(am/pm):
													</label>
													<select class="form-control" id="cmbAmPmStart" name="cmbAmPmStart">
														<option>am</option>
														<option>pm</option>
													</select>
												</div>
												</td>
											</tr>										
																					
											<tr>
												<td><label for="txtStartTime">End Time:</label></td>
												<td>
													<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
													<label class="label label-info">(hour)</label>
														<input type="number" class="form-control" id="txtEndHour" name="txtEndHour" min="0" max="12" value="0"/>
													</div>
													<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
														<label class="label label-info">(min)</label>
														<input type="number" class="form-control" id="txtEndMin" name="txtEndMin" min="0" max="59" value="0"/>
													</div>
													<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
														<label class="label label-info">(am/pm)</label>
													<select id="cmbAmPmEnd" name="cmbAmPmEnd" class="form-control">
														<option>am</option>
														<option>pm</option>
													</select>
													</div>
												</td>
											</tr>								
											
											<tr>
												<td></td>
												<td>
												<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
													<input class="btn btn-danger" type="button" name="btnSetSched" id="btnSetSched" value="Set"/>	
												</div>
												<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
													<input class="btn btn-danger" type="button" name="btnAddSched" id="btnAddSched" value="Add" onclick="addSchedule();"/>
												</div>	
												<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
													<input class="btn btn-danger" type="button" name="btnDelSched" id="btnDelSched" value="Delete"/>
												</div>
												</td>
											</tr>
										</table>
									</div>
								</div>	
						</div>
						
						<div class="col-sm-12 col-xs-12 col-md-8 col-lg-8">
							<div class="panel panel-danger" style="margin-top:10px;height:700px;overflow:auto;background-color:#ECEDF0;"  >
								<div class="panel panel-heading">Schedule List
								<span class="glyphicon glyphicon-chevron-down pull-right" 
									width="50" height="40">
									</span>
								</div>
									<div id="scheduleListBody" class="panel-body" style="overflow:auto;">
									
										<div class="col-sm-12 col-xs-12 col-md-2 col-lg-2" style="padding:2px;margin-top:30px;">
											<select class="form-control" style="margin:5px;"> 
												<option>Select All</option>
												<option>Unselect All</option>
												<option>Delete</option>
											</select>
										</div>
										
										<div class="col-sm-12 col-xs-12 col-md-2 col-lg-2" style="padding:2px;margin-top:30px;">
												<input type="button" class="btn btn-danger" id="btnExecuteFilterSchedule" value="Execute" style="margin:5px;"/>
										</div>
										
										<div class="col-sm-12 col-xs-12 col-md-2 col-lg-2" style="padding:5px;margin-top:10px;">
											<label clas="label label-default">Start Date:</default>
											<input type="date" class="form-control" style="margin:5px;" id="txtSchedulingStartDateFilter"/>
										</div>
										
										<div class="col-sm-12 col-xs-12 col-md-2 col-lg-2" style="padding:5px;margin-top:10px;">
											<label clas="label label-default">Home:</default>
											<select class="btn btn-default " style="margin:5px;" id="cmbSchedulingFilterHome"> 
												<option></option>';
												$qho = mysql_query("SELECT * FROM home WHERE home_owner_id='".$client_id."'");
												while($rqho=mysql_fetch_array($qho)){
													echo '<option>'.$rqho['home_name'].'</option>';
												}
												echo'
											</select>
										</div>
										
										<div class="col-sm-12 col-xs-12 col-md-2 col-lg-2" style="padding:5px;margin-top:10px;">
											<label clas="label label-default">Location:</default>
											<select class="btn btn-default " style="margin:5px;" id="cmbSchedulingFilterLocation"> 
												<option></option>';
												$qlo = mysql_query("SELECT * FROM location WHERE location_homeowner_id='".$client_id."'");
												while($rqlo=mysql_fetch_array($qlo))
												{
													echo '<option>'.$rqlo['location_name'].'</option>';
												}
												echo'
											</select>
										</div>
										
										<div class="col-sm-12 col-xs-12 col-md-2 col-lg-2" style="padding:10px;margin-top:30px;">
											<input type="button" class="btn btn-danger" value="Filter" id="btnSchedulingFilter"/>
											
										</div>
											
										
										<table class="table table-bordered" style="background-color:#FFF;">
											';
												$q=mysql_query("SELECT * FROM schedule WHERE sched_stat='active'
																AND 
																	sched_client_id='".$_SESSION['clientId']."'
																");
												echo '<th >All</th>';
												echo '<th>Facility Name</th>';
												echo '<th>Home</th>';
												echo '<th>Location</th>';
												echo '<th >From Date</th>';
												echo '<th >To Date</th>';
												echo '<th >Start Time</th>';
												echo '<th >End Time</th>';
												echo '<th >Status</th>';
												
													$i=2;
												while($r=mysql_fetch_array($q)){
													
													if($i % 2 == 0){
														echo '<tr id="'.$r['sched_id'].'" style="background-color:lightblue;"
														onClick="clickRowSchedule(this.id);" 
														onMouseEnter="hoverScheduleRow(this.id);" onMouseOut="hoverOutScheduleRow(this.id);">';
													
													}else{
														echo '<tr id="'.$r['sched_id'].'" style="background-color:white;"
														onClick="clickRowSchedule(this.id);" 
														onMouseEnter="hoverScheduleRow(this.id);" onMouseOut="hoverOutScheduleRow(this.id);">';
													}
													$i++;
													
														$qq=mysql_query("SELECT * FROM facility WHERE fac_id='".$r['sched_fid']."'");
														$res=mysql_fetch_array($qq);
													echo '<td><input type="checkbox" /></td>';
													echo '<td>'.$res['fac_name'].'</td>';
													$qhom = mysql_query("SELECT * FROM home WHERE home_id='".$r['sched_home_id']."' AND home_owner_id='".$r['sched_client_id']."'");
													$fhom=mysql_fetch_array($qhom);
													echo '<td>'.$fhom['home_name'].'</td>';
													
													$qloc = mysql_query("SELECT * FROM location WHERE location_homeowner_id='".$r['sched_client_id']."' AND location_id='".$r['sched_location_id']."'");
													$fqloc = mysql_fetch_array($qloc);
													echo '<td>'.$fqloc['location_name'].'</td>';
													
													echo '<td>'.$r['sched_FromDate'].'</td>';
													echo '<td>'.$r['sched_ToDate'].'</td>';
													echo '<td>'.$r['sched_shour'].":".$r['sched_smin']." ".$r['sched_sampm'].'</td>';
													echo '<td>'.$r['sched_ehour'].":".$r['sched_emin']." ".$r['sched_eampm'].'</td>';
													echo '<td>'.$r['sched_stat'].'</td>';
													
												}	
													echo '</tr>';
												
											echo '
										</table>
									</div>
							</div>
						</div>
						';
						echo '<script type="text/javascript" src="javascript/mine.js"></script>';
				}
			}else{
				echo "You need to login.";
			}
	}
	
	function filterSettings($home,$location,$homeOwnerId)
	{
		echo '<input type="hidden" value="'.$homeOwnerId.'" id="hiddenSettingsHomeOwnerId">';
		
		echo '
				<div class="container-fluid">
					<div class="row">
						<label class="label label-info" id="lblSettingsInformer"></label>
						  <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="margin:0;padding:10px;">
								<label>Home:</label>
								<div class="form-group has-default has-feedback">
									<div class="input-group">
										 <span class="input-group-addon">
											<span class="glyphicon glyphicon-tasks"></span>
										</span>
											<select class="form-control" id="cmbSettingsHomeFilter" name="cmbFacilityStatus">
												<option></option>
												';
												$qh=mysql_query("SELECT * FROM home WHERE home_owner_id='".$homeOwnerId."'");
												
												while ($fqh=mysql_fetch_array($qh))
														{
															echo '<option>'.$fqh['home_name'].'</option>';
														}
												
												
												echo '
											</select>
									</div>
								</div>												
							</div>
														
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="margin:0;padding:10px;">
								<label>Location:</label>
								<div class="form-group has-default has-feedback">
									<div class="input-group">
										 <span class="input-group-addon">
											<span class="glyphicon glyphicon-tasks"></span>
										</span>
											<select class="form-control" id="cmbSettingsLocationFilter" name="cmbFacilityStatus">
												<option></option>
												';
													$qL=mysql_query("SELECT * FROM location WHERE location_homeowner_id='".$homeOwnerId."'");
													
													while($fqL=mysql_fetch_array($qL))
													{
														echo '<option>'.$fqL['location_name'].'</option>';
													}
													echo '
											</select>
									</div>
								</div>												
							</div>
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding-top:35px;">
								
											<button class="btn btn-info" id="btnSettingsFilter" onclick="filterSettingsFacility();">Filter</button>
															
							</div>
					</div>
				</div>
			';
		
		echo '<table class="table table-bordered">';
		echo '<th>Name</th>';
		echo '<th>Type</th>';
		echo '<th>Line</th>';
		echo '<th>Status</th>';
		echo '<th>Home</th>';
		echo '<th>Location</th>';
		$qh = mysql_query("SELECT home_id FROM home WHERE home_name='".$home."' AND home_owner_id='".$homeOwnerId."'");
		$fqh=mysql_fetch_array($qh);
		$home_id=$fqh['home_id'];
		
		$ql=mysql_query("SELECT location_id FROM location WHERE location_name='".$location."' AND location_homeowner_id='".$homeOwnerId."'");
		$fql=mysql_fetch_array($ql);
		$location_id = $fql['location_id'];
		
		$qf=mysql_query("SELECT * FROM facility WHERE fac_home_id='".$home_id."' AND fac_location_id='".$location_id."' AND fac_home_owner_id='".$homeOwnerId."'");
		
		$index = 1;
		while($rqf=mysql_fetch_array($qf))
		{
			if($index % 2 == 0){
				echo '<tr id="'.$rqf['fac_id'].'" 
				onclick="facilityRowClick(this.id );" 
				onMouseEnter="facilityRowHover(this.id);" onMouseOut="facilityMout(this.id);"
				>';
			}
			else
			{
				echo '<tr id="'.$rqf['fac_id'].'" 
				style="background-color:lightblue;"
				onclick="facilityRowClick(this.id );" 
				onMouseEnter="facilityRowHover(this.id);" onMouseOut="facilityMout(this.id);"
				>';
			}
			$index++;
			echo '<td>'.$rqf['fac_name'].'</td>';
			$qt= mysql_query("SELECT fac_type_name FROM facility_type WHERE fac_type_id='".$rqf['fac_type']."'");
			$fqt = mysql_fetch_array($qt);
			echo '<td>'.$fqt['fac_type_name'].'</td>';
			echo '<td>'.$rqf['fac_line'].'</td>';
			$qs = mysql_query("SELECT * FROM facility_status WHERE facility_status_id='".$rqf['fac_status']."'");
			$fqs = mysql_fetch_array($qs);
			echo '<td>'.$fqs['facility_status_name'].'</td>';
			echo '<td>'.$home.'</td>';
			echo '<td>'.$location.'</td>';
		}
		echo '</tr>';
		echo '</table>';
	}
	//This function shows the settings Form
	function showFacilitySettingsForm(){
		
		if(isset($_SESSION['islogin'])){
				if($_SESSION['islogin']==true){	
					
					echo '				
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" style="margin-top:40px;">
						<div class="panel panel-danger" style="overflow:auto;background-color:#ECEDF0;height:490px;">
								<div class="panel panel-heading">Set Facility
									<span class="glyphicon glyphicon-chevron-down pull-right" 
									width="50" height="40">
									</span>						
								</div>
									<div class="panel-body">
										 
										<table class="table table-bordered" style="background-color:#FFF;">
											<tr>
												<td>
													
													<label>Name:</label>
													<input type="hidden" name="hiddenId" id="hiddenId"/>
													
												</td>
												<td>
												';
													
												echo '
												
												<div class="form-group has-error has-feedback">
														<div class="input-group">
															 <span class="input-group-addon">
																<span class="glyphicon glyphicon-tasks"></span>
															</span>
															<input type="text"  id="txtSettingsFname" class="form-control" maxlength="100" placeholder="Facility Name..........." required/>
														</div>
													</div>
												';
													
												echo'
													
												</td>
											</tr>
											
											<tr>
												<td>
													<label> Type:</label>
												</td>
												<td>
													<div class="form-group has-error has-feedback">
														<div class="input-group">
															 <span class="input-group-addon">
																<span class="glyphicon glyphicon-tasks"></span>
															</span>
												';
													$q=mysql_query("SELECT * FROM facility_type");
													
													
														echo "<select class='form-control' id='cmbFacilityType' name='cmbFacilityType'>";
														echo "<option></option>";
													while($r=mysql_fetch_array($q)){
														echo "<option>".$r['fac_type_name']."</option>";
													}
														echo "</select>";
												echo '
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<label> Status:</label>
												</td>
												<td>
												
													<div class="form-group has-error has-feedback">
														<div class="input-group">
															 <span class="input-group-addon">
																<span class="glyphicon glyphicon-tasks"></span>
															</span>
																<select class="form-control" id="cmbFacilityStatus" name="cmbFacilityStatus">
																	<option></option>
																	<option>Used</option>
																   <option>Not Used</option>													
																</select>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<label> Line:</label>
												</td>
												<td>
												
													<div class="form-group has-error has-feedback">
														<div class="input-group">
															 <span class="input-group-addon">
																<span class="glyphicon glyphicon-tasks"></span>
															</span>
												';
													$qq=mysql_query("SELECT fac_line From facility  ");
														echo "<select class='form-control' name='cmbFacLine'  id='cmbLine'>";
														echo "<option > &nbsp;</option>";
													while($r=mysql_fetch_array($qq)){
														echo "<option>".$r['fac_line']."</option>";
													}
														echo "</select>";
												echo'
														</div>
													</div>
												</td>
											</tr>									
											
											<tr>
												<td>
												</td>
												<td>
													<input type="button" value="Set" id="btnSetFACILITY"  name="btnSetFacility" class="btn btn-danger" 
													onclick="clickBtnSetFacility();";
													/>
													<label id="informerSetFacility" class="label label-info pull-right"> 
													</label>
												</td>
											</tr>
										</table>
										
									</div>
								</div>
							</div>';
							$homeOwnerId="";
								if(isset($_SESSION['clientId'])){
									$homeOwnerId=$_SESSION['clientId'];
								}
							echo '
							
							<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" style="margin-top:40px;">						
									<div class="panel panel-danger" style="overflow:auto;height:490px;background-color:#ECEDF0;">
										<div class="panel panel-heading">Facility List
											<span class="glyphicon glyphicon-chevron-down pull-right" 
											width="50" height="40">
											</span>
										</div>
											<input type="hidden" value="'.$homeOwnerId.'" id="hiddenSettingsHomeOwnerId">
											<div class="panel-body" id="pnlSettingsBody">	
												
												<div class="container-fluid">
													<div class="row">
														<label class="label label-info" id="lblSettingsInformer"></label>
													  <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="margin:0;padding:10px;">
															<label>Home:</label>
															<div class="form-group has-default has-feedback">
																<div class="input-group">
																	 <span class="input-group-addon">
																		<span class="glyphicon glyphicon-tasks"></span>
																	</span>
																		<select class="form-control" id="cmbSettingsHomeFilter" name="cmbFacilityStatus">
																			<option></option>
																			';
																			$qh=mysql_query("SELECT * FROM home WHERE home_owner_id='".$homeOwnerId."'");
																			
																			while ($fqh=mysql_fetch_array($qh))
																					{
																						echo '<option>'.$fqh['home_name'].'</option>';
																					}
																			
																			
																			echo '
																		</select>
																</div>
															</div>												
														</div>
														
														<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="margin:0;padding:10px;">
															<label>Location:</label>
															<div class="form-group has-default has-feedback">
																<div class="input-group">
																	 <span class="input-group-addon">
																		<span class="glyphicon glyphicon-tasks"></span>
																	</span>
																		<select class="form-control" id="cmbSettingsLocationFilter" name="cmbFacilityStatus">
																			<option></option>
																			';
																				$qL=mysql_query("SELECT * FROM location WHERE location_homeowner_id='".$homeOwnerId."'");
																				
																				while($fqL=mysql_fetch_array($qL))
																				{
																					echo '<option>'.$fqL['location_name'].'</option>';
																				}
																				echo '
																		</select>
																</div>
															</div>												
														</div>
														<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding-top:35px;">
															
																		<button class="btn btn-info" id="btnSettingsFilter" onclick="filterSettingsFacility();">Filter</button>
																						
														</div>
														
														
														
														
													</div>
												</div>
												
												
												<table class="table table-bordered" style="background-color:#FFF;">
													';
													echo '<input type="hidden" id="hiddenOriginalLine"/>';
														$qs=mysql_query("SELECT * FROM facility
																		WHERE fac_home_owner_id='".$_SESSION['clientId']."'");
															echo '<th>Name</th>'; 
															echo '<th>Type</th>'; 
															echo '<th>Line</th>'; 
															echo '<th>Status</th>';
															echo '<th>Home</th>';
															echo '<th>Location</th>';
															
															$index=1;
														while($rs=mysql_fetch_array($qs)){
															
															if($index % 2 == 0){
																echo '<tr id="'.$rs['fac_id'].'" 
																onclick="facilityRowClick(this.id );" 
																onMouseEnter="facilityRowHover(this.id);" onMouseOut="facilityMout(this.id);"
																>';
															}
															else
															{
																echo '<tr id="'.$rs['fac_id'].'" 
																style="background-color:lightblue;"
																onclick="facilityRowClick(this.id );" 
																onMouseEnter="facilityRowHover(this.id);" onMouseOut="facilityMout(this.id);"
																>';
															}
															$index++;
															echo '<td>'.$rs['fac_name'].'</td>';
															$typ=mysql_query("SELECT fac_type_name FROM 
																			facility_type WHERE fac_type_id='".$rs['fac_type']."'
																			");
															echo '<td>';
															while($ftyp=mysql_fetch_array($typ)){
																echo $ftyp['fac_type_name'];
															}
															echo'</td>';
															
															echo '<td>'.$rs['fac_line'].'</td>';
															$stats=mysql_query("SELECT facility_status_name FROM
																				facility_status WHERE facility_status_id='".$rs['fac_status']."'
																				");
															echo '<td>';
															while($fstats=mysql_fetch_array($stats)){
																echo $fstats['facility_status_name'];
															}
															echo '</td>';
															
															echo '<td>';
															$hm = mysql_query("SELECT * FROM home WHERE home_id='".$rs['fac_home_id']."'");
															while($hr=mysql_fetch_array($hm)){
																echo $hr['home_name'];
															}
															echo '</td>';
															
															echo '<td>';
																$lc = mysql_query("SELECT * FROM location WHERE location_id='".$rs['fac_location_id']."' AND location_homeowner_id='".$_SESSION['clientId']."'");
																while($flc=mysql_fetch_array($lc)){
																	echo $flc['location_name'];
																}
															echo '</td>';
															
														}
														echo '</tr>';
														
													echo'
												</table>
											</div>
									</div>	
							</div>						
						';
					echo '<script type="text/javascript" src="javascript/mine.js"></script>';
				}
			
			}
		
	}	
	function showAbout(){
		echo '
		<div class="panel panel-danger" style="margin:20px;"  >
			<div class="panel panel-heading">About Home Facilities Automation (HFA) </div>
				<div class="panel-body">
			Home facilities automation is a way by which home owners can control their facilities.
			 It schedules the tripping off of power lines, turning off and on of the lights, and opening and closing the gate.
			It lessens human effort, saves energy and time, and 
			avoids unnecessary accident.
				</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<img src="resources/facilityImages/content.png" class="img-responsive" width="100%" height="300px">
		</div>
		';
	}
	function showHome(){
		?>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin:0;padding:0;">					
				<div>
					<img src="resources/facilityImages/content.png" width="100%" height="600"/>
				</div>			
			</div>
		<?php
	}
	
	function viewFacilities($homeOwnerId,$homeid,$locationId){
	//START OF HOME LOCATION MODAL
		echo '
				<div id="updateFacilityModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
					  <div class="modal-content">
						<div class="modal-header" style="background-color:black;">
						  <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white;">&times;</button>
						  <h4 class="modal-title" id="myModalLabel" style="color:white;"> + Manage Facility</h4>
						</div>
						<div class="modal-body">
							<table class="table table-bordered">
								<tr>
									<td><label class="label label-default">Facility Name:</label></td>
									<td>
										<div class="form-group has-error">
											<input type="text" class="form-control" id="txtViewFacilityName"/>
										</div>
									</td>
								</tr>
								<tr>
									<td><label class="label label-default">Facility Type:</label></td>
									<td>
										<div class="form-group has-error">
											';
											$typeq=mysql_query("SELECT DISTINCT(fac_type_name) FROM facility_type");
											echo '<select id="cmbUpdateFacilityType" class="form-control"><option></option>';
												while($r=mysql_fetch_array($typeq)){
													echo '<option>'.$r['fac_type_name'].'</option>';
												}
											echo '</select>';
											echo
											'
										</div>
									</td>
								</tr>
								
								<tr>
									<td><label class="label label-default">Home:</label></td>
									<td>
										<div class="form-group has-error">
											';
											$qhomes=mysql_query("SELECT DISTINCT(home_name) FROM home WHERE home_owner_id='".$homeOwnerId."'");
											echo '<select id="cmbAdminHome" class="form-control"><option></option>';
												while($rqhomes=mysql_fetch_array($qhomes)){
													echo '<option>'.$rqhomes['home_name'].'</option>';
												}
											echo '</select>';
											echo
											'
										</div>
									</td>
								</tr>
								
								<tr>
									<td><label class="label label-default">Location:</label></td>
									<td>
										<div class="form-group has-error">
											';
											$qloc = mysql_query("SELECT DISTINCT(location_name) From location WHERE location_homeowner_id='".$homeOwnerId."'");
												echo '<select class="form-control" id="cmbViewLocation"><option></option>';
													while($rport= mysql_fetch_array($qloc)){
														echo '<option>'.$rport['location_name'].'</option>';
													}
												echo '</select>';
											echo'
										</div>
									</td>
								</tr>
								
								<tr>
									<td><label class="label label-default">Facility Line:</label></td>
									<td>
										<div class="form-group has-error">
											';
											echo '<input class="form-control" type="text" id="txtAdminLine"/>';
											echo '
										</div>
									</td>
								</tr>
								
								<tr>
									<td><label class="label label-default">Facility Status:</label></td>
									<td>
										<div class="form-group has-error">
											';
											echo '<select class="form-control" id="cmbAdminFacilityStatus">';
												echo '<option></option>';
												
												$qSTAT = mysql_query("SELECT * FROM facility_status");
												while ($fqSTAT = mysql_fetch_array($qSTAT))
												{
													echo '<option>'.$fqSTAT['facility_status_name'].'</option>';
												}
												
											echo '</select>';
											echo '
										</div>
									</td>
								</tr>
								
								<tr>
									<td><label class="label label-default">Facility Connector:</label></td>
									<td>
										<div class="form-group has-error">
											';
											echo '<select class="form-control" id="cmbAdminFacilityConnector">';
												echo '<option></option>';
												
												$qconnector = mysql_query("SELECT * FROM facility_connector");
												while ($fqconnector = mysql_fetch_array($qconnector))
												{
													echo '<option>'.$fqconnector['facility_connector_name'].'</option>';
												}
												
											echo '</select>';
											echo '
										</div>
									</td>
								</tr>
								
								<tr>
									<td><label class="label label-default">Facility Port:</label></td>
									<td>
										<div class="form-group has-error">
											';
											$qport = mysql_query("SELECT facility_port_name FROM facility_port");
												echo '<select class="form-control" id="cmbViewPort"><option></option>';
													while($rport= mysql_fetch_array($qport)){
														echo '<option>'.$rport['facility_port_name'].'</option>';
													}
												echo '</select>';
											echo'
										</div>
									</td>
								</tr>
								<tr>
									<td><label class="label label-default">Facility Port Address:</label></td>
									<td>
										<div class="form-group has-error">
											';
											$qport = mysql_query("SELECT * FROM facility_port_address");
												echo '<select class="form-control" id="cmbViewAddress">
														<option></option>';
													while($rport= mysql_fetch_array($qport)){
														echo '<option>'.$rport['facility_portaddress_name'].'</option>';
													}
												echo '</select>';
											echo'
										</div>
									</td>
								</tr>
								<tr>
									<td><label class="label label-default">Facility Port Status Reader:</label></td>
									<td>
										<div class="form-group has-error" id="cmbFpsr">';
											$qfpsr = mysql_query("SELECT * FROM facility_port_status_reader");
											echo '<select class="form-control">';
											echo '<option></option>';
											while($rqfpsr=mysql_fetch_array($qfpsr)){
												echo '<option>'.$rqfpsr['fpsr_name'].'</option>';
											}
											echo '</select>';
										echo '</div>
									</td>
								</tr>
								
							</table>
						</div>
						<div class="modal-footer" style="background-color:#900">
							<button type="button" class="btn btn-danger" data-dismiss="modal" style="color:white;font-weight:bold;">Set for configuration</button>
							
							<button type="button" class="btn btn-danger" data-dismiss="modal" style="color:white;font-weight:bold;">Update</button>
							
							<button type="button" class="btn btn-danger" data-dismiss="modal" style="color:white;font-weight:bold;">Delete</button>
							
							<button type="reset" class="btn btn-danger" style="color:white;font-weight:bold;">Clear</button>
						</div>

					  </div>
					</div>
				  </div>
			';
		//END OF UPDATE HOME LOCATION MODAL
		$q=mysql_query("SELECT * FROM facility 
						WHERE 
							fac_home_owner_id='".$homeOwnerId."'
						AND 
							fac_home_id='".$homeid."'
						AND 
							fac_location_id='".$locationId."'
						");
			echo '<label class="label label-danger"
					 data-toggle="modal" 
					data-target="#updateFacilityModal"
					> + Set facility for configuration</label>';
			echo '<table class="table table-bordered">		
					<th>Facilities</th>
					<th>Action</th>
					
				';				
					while($r=mysql_fetch_array($q)){
						echo '<tr>';
						echo '<td >'.$r['fac_name'].'</td>';
						echo '<td >
								<label class="label label-info" id="'.$r['fac_id'].'"
								 data-toggle="modal" 
								 data-target="#updateFacilityModal"
								 onclick="adminManageFacility(this.id);"
								>
								+ Manage Facility
								</label>
								
								
							</td>';
						$qhome=mysql_query("SELECT home_name FROM home WHERE home_id='".$r['fac_home_id']."'");
						$fhome=mysql_fetch_array($qhome);
						
					}
			echo '</tr>';
			echo '</table>';
			
			echo '<input type="button" value="Back" class="pull-right btn btn-danger" onclick="homeClick('.$homeOwnerId.','.$homeid.');"/>';
			
	}
}

?>