<?php 
	if(!isset($_SESSION)){
		session_start();
	}
	require_once __DIR__ .'/../../connection.php';
	require_once '../MODEL/model.php';
	require_once '../VIEW/view.php';
	
	
///////////////////////////////////////////////////////////////////CONTROLLER/////////////////////////////////////////////////////////
$model= new AutomationModel;
$view= new AutomationView;
//ScheduleIdDelete
if(isset($_POST['ScheduleIdDelete'])){
	$id=$_POST['ScheduleIdDelete'];
	$del = mysql_query("DELETE FROM schedule WHERE sched_id='".$id."'");
	if($del)
	{
		echo "Schedule Deleted.";		
	}else{		
		echo "Error Deleting schedule, please try again.";
	}
}

if(
	isset($_POST['SettingsHome']) && 
	isset($_POST['SettingsLocation']) && 
	isset($_POST['SettingsId']) 
){
	$home_owner_id = $_POST['SettingsId'];
	$home = $_POST['SettingsHome'];
	$location = $_POST['SettingsLocation'];
	
	$view->filterSettings($home,$location,$home_owner_id);
}

if(isset($_POST['command']) && isset($_POST['id']) && isset($_POST['location']) && isset($_POST['home'])){
	if($_POST['command'] == "filterFacilities")
	{
		$view->filterFacilities($_POST['home'],$_POST['location'],$_POST['id']);
	}
}
//This conditional statement waits for mine.js to pass a variable named id 
//It checks to see the value of id
//if id is equal to login it displays the login form
	if(isset($_POST['id'])){
		if($_POST['id']=="login"){
			$_SESSION['activity']='login';
			$view->showLoginForm();
			$view->showRegistrationForm();
		}
		else if($_POST['id']=="scheduling"){
			$_SESSION['activity']='scheduling';
			$view->showSchedulingForm();
			
		}else if($_POST['id']=="facility"){
			$_SESSION['activity']='facility';
			$view->showFacilities();			
		}
		else if($_POST['id']=="settings"){
			$_SESSION['activity']='settings';
			$view->showFacilitySettingsForm();
			
		}else if($_POST['id']=="about"){
			$_SESSION['activity']='about';
			$view->showAbout();
			
		}else if($_POST['id']=="home"){		
			$view->showHome();
			$_SESSION['activity']='home';	
		}
		else if($_POST['id']=="getActivity"){	
			if(isset($_SESSION['activity'])){
				echo $_SESSION['activity'];
			}			
		}
		else if($_POST['id']=="manageFacility"){
			
			$view->showClientForm();
		}
	}
	if(isset($_POST['username']) && isset($_POST['password'])){
			$u=$model->filterAll($_POST['username']);
			$p=$model->filterAll($_POST['password']);
			$true = $model->login($u,$p);	
			if($true ){
				$_SESSION['islogin']=true;
				$view->showAbout();
				
			}else{
				$_SESSION['islogin']=false;
				echo "Login failed.Please try again.";
			}
	}
	if(isset($_POST['lineOut']) && isset($_POST['facidOut'])){
		$q=$model->q("SELECT * FROM facility where fac_line='".$_POST['lineOut']."'
						AND fac_id='".$_POST['facidOut']."'
					");
		$r=mysql_fetch_array($q);
		
		if(isset($_SESSION['clientId'])){
			$qu = mysql_query("DELETE FROM schedule WHERE 
								sched_fid='".$_POST['facidOut']."'
							AND 
								sched_client_id='".$_SESSION['clientId']."'
						");
		}
		
		$fr = mysql_query("UPDATE facility set fac_bit='0',fac_switching_status='3'
					WHERE fac_line='".$_POST['lineOut']."'
					AND fac_id='".$_POST['facidOut']."'
					");
			if($fr){
				echo 'Facility turned Off';	
			}	
	}
	if(isset($_POST['line']) && isset($_POST['facid'])){
		$q=$model->q("SELECT * FROM facility where fac_line='".$_POST['line']."'
						AND fac_id='".$_POST['facid']."'
					");
		$r=mysql_fetch_array($q);		
		$fr = mysql_query("UPDATE facility SET fac_bit='1',fac_switching_status='3'
					WHERE fac_line='".$_POST['line']."'
					AND fac_id='".$_POST['facid']."'
					");
			if($fr){
				echo 'Facility turned On';	
			}	
	}
	if(isset($_POST['cmd']) && isset($_POST['id'])){
	$id=$_POST['id'];
	$cmd=$_POST['cmd'];
		if($_POST['cmd']=="facrowclicked"){
			echo $model->getFacilityInfo($id);
		}else if($_POST['cmd']=="schedClicked"){			
			 $model->getScheduleInfo($id);			
		}
		else if($cmd=="getClientInformation"){
			$model->getClientInformation($id);
		}
	}
	//sched
	if(isset($_POST['facilityName']) && isset($_POST['fromDate']) &&
		isset($_POST['toDate']) && isset($_POST['startHour']) &&
		isset($_POST['startMinute']) && isset($_POST['cmbAmPmStart']) &&
		isset($_POST['endHour']) && isset($_POST['endMinute']) &&
		isset($_POST['cmbAmPmEnd']) && isset($_POST['facilityId']) &&
		isset($_POST['homeNAME']) && isset($_POST['locationNAME'])
	){
		$model->compareSchedule($_POST['facilityName'],$_POST['fromDate'],
										$_POST['toDate'],$_POST['startHour'],
										$_POST['startMinute'],$_POST['cmbAmPmStart'],
										$_POST['endHour'],$_POST['endMinute'],
										$_POST['cmbAmPmEnd'],$_POST['facilityId'],$_POST['homeNAME'],$_POST['locationNAME']
								);
		

	}

	if(isset($_POST['command']) && isset($_POST['facName']) && isset($_POST['fDate']) &&
		isset($_POST['tDate']) && isset($_POST['sHour']) &&
		isset($_POST['sMinute']) && isset($_POST['sMeridian']) &&
		isset($_POST['eHour']) && isset($_POST['eMinute']) && 
		isset($_POST['eMeridian']) && isset($_POST['facId']) && 
		isset($_POST['homeNAME']) && isset($_POST['locationNAME']) 
	)
	{
		$client_id="";
		if(isset($_SESSION['clientId'])){
			$client_id = $_SESSION['clientId'];
		}
		$homid=mysql_query("SELECT home_id FROM home WHERE home_name='".$_POST['homeNAME']."' AND home_owner_id='".$client_id."'");
		$hfid = mysql_fetch_array($homid);
		$hid = $hfid['home_id'];
		
		$locid = mysql_query("SELECT location_id FROM location WHERE location_name='".$_POST['locationNAME']."' AND location_homeowner_id='".$client_id."'");
		$flocid = mysql_fetch_array($locid);
		$lid = $flocid['location_id'];
		
		if($_POST['command']=="insertSchedule"){
			$model->insertSchedule($_POST['facName'],$_POST['fDate'],$_POST['tDate'],
							$_POST['sHour'],$_POST['sMinute'],$_POST['sMeridian'],
							$_POST['eHour'],$_POST['eMinute'],$_POST['eMeridian'],$_POST['facId'],$hid,$lid					
							);
		}
	
	}
	
	if(isset($_POST['facilityN'])){
		echo $model->getFacilityId($_POST['facilityN']);
	}
	
	if(isset($_POST['id']) && isset($_POST['command'])
			&& isset($_POST['classs'])
			){
		$home_id=$_POST['classs'];
		$id=$_POST['id'];
		$command = $_POST['command'];
		if($command=="showHome"){
			$view->showClientHome($id);
		}
		else if($command=="showHomeLocation"){
			$view->showClientHomeLocation($id,$home_id);
		}
		
	}
	
	if(	isset($_POST['id']) && isset($_POST['fname']) && 
		isset($_POST['lname']) && isset($_POST['contact']) && 
		isset($_POST['stat']) && isset($_POST['address']) && 
		isset($_POST['email']) && isset($_POST['gender']) && 
		isset($_POST['bdate']) && isset($_POST['rdate']) && isset($_POST['mname']) 
		
		){
		$id = $_POST['id'];
		$fname =$_POST['fname'];
		$mname =$_POST['mname'];
		$lname =$_POST['lname'];
		$contact =$_POST['contact'];
		$stat =$_POST['stat'];
		$address = $_POST['address'];
		$email = $_POST['email'];
		$gender = $_POST['gender'];
		$bdate = $_POST['bdate'];
		$rdate =$_POST['rdate'];
		
		$model->updateClientInformation($id,$fname,$mname,$lname,$contact ,
										$stat ,$address ,$email ,$gender ,
										$bdate,$rdate 
										);
		
		
	
	}
	
	if(isset($_POST['homeowner_id']) && isset($_POST['homename'])){
		$model->addHome($_POST['homeowner_id'],$_POST['homename']);
	}
	if(isset($_POST['homename']) && isset($_POST['homOwnerId'])
		&& isset($_POST['homId'])
		){
		$model->updateHomeName($_POST['homename'],$_POST['homOwnerId'],$_POST['homId']);	
	}
	if(isset($_POST['delhomeOwnerId']) && isset($_POST['delhomeId'])){
		$q=mysql_query("DELETE FROM home WHERE home_id='".$_POST['delhomeId']."' AND home_owner_id='".$_POST['delhomeOwnerId']."'");
		if($q){
			echo "Home is deleted.";
		}else{
			echo "Deleting failed.";
		}
	}
	if(isset($_POST['locationHomeId']) && isset($_POST['locationHomeOwnerId'])
		&& isset($_POST['locationName']) && isset($_POST['location_id'])
		){
		$q=mysql_query("UPDATE location 
						SET 
							location_name='".$_POST['locationName']."' 
						WHERE 
							location_home_id='".$_POST['locationHomeId']."' 
						AND 
							location_homeowner_id='".$_POST['locationHomeOwnerId']."'
						AND 
							location_id='".$_POST['location_id']."'
					  ");
		if($q){
			echo "Updating successful.";
		}else{
			echo "Updating failed.";
		}
	}
	if(isset($_POST['delLocationId'])){
		$q=mysql_query("DELETE FROM location WHERE location_id='".$_POST['delLocationId']."'");
		if($q){
			echo "Location Deleted";
		}else{
			echo "Deleting failed.";
		}
	}
	//locname:locname,addHid:addHid,addHoId:addHoId
	if(isset($_POST['locname']) && isset($_POST['addHid']) && isset($_POST['addHoId'])){
		$q=mysql_query("INSERT INTO location VALUES(
						'',
						'".$_POST['locname']."',
						'".$_POST['addHoId']."',
						'".$_POST['addHid']."'
						)");
		if($q){
			echo "Location added.";
		}else{
			echo "Add failed.";
		}
	}
	
	//hoid:hoid,hid:hid,locid:locid
	if(isset($_POST['hoid']) && isset($_POST['hid']) && isset($_POST['locid'])){
		$view->viewFacilities($_POST['hoid'],$_POST['hid'],$_POST['locid']);
	}
	/*
		fname:fname,
					mi:mi,
					lname:lname,
					username:username,
					pword:pword,
					vpword:vpword,
					address:address,
					contact:contact,
					email:email,
					gender:gender,
					bday:bday
	*/
	if(
		isset($_POST['fname']) &&
		isset($_POST['mi']) &&
		isset($_POST['lname']) &&
		isset($_POST['username']) &&
		isset($_POST['pword']) &&
		isset($_POST['address']) &&
		isset($_POST['contact']) &&
		isset($_POST['email']) &&
		isset($_POST['gender']) &&
		isset($_POST['bday']) 
		){
		$fname= sanitization($_POST['fname']);
		$mi = sanitization($_POST['mi']);
		$lname = sanitization($_POST['lname']);
		$username = sanitization($_POST['username']);
		$password = sanitization($_POST['pword']);
		$address = sanitization($_POST['address']);
		$contact = sanitization($_POST['contact']);
		$email = sanitization($_POST['email']);
		$gender = sanitization($_POST['gender']);
		$bday = sanitization($_POST['bday']);
		
		
		$check = mysql_query("SELECT reg_username,reg_password 
								FROM registration 
								WHERE reg_username='".$username."' 
								AND reg_password='".$password."'
								");
		
		$num = mysql_num_rows($check);
		if($num==0){
			$insert =mysql_query("INSERT INTO registration VALUES(
								'',
								'".$username."',
								'".$password."',
								'".$fname."',
								'".$mi."',
								'".$lname."',
								'admin',
								'inactive',
								'".$address."',
								'".$contact."',
								'".$email."',
								'".$gender."',
								'".$bday."',
								'NOW()'
								)");
			if($insert){
				echo "Registration Successful. An email is sent to you for verification.";
			}		
		}else{
			
			echo "This account is already taken.Please try a new username or password.";
		}
	}
	
	if(isset($_POST['logout'])){
		session_destroy();
	}
	function sanitization($var){
		$v = mysql_real_escape_string($var);
		return $v;
	}
	if(isset($_POST['updateFacName']) &&
		isset($_POST['updateFacType']) &&
		isset($_POST['updateFacStat']) &&
		isset($_POST['updateFacLine']) &&
		isset($_POST['OriginalLine'])
		)
	{
		$fname = $model->filterAll($_POST['updateFacName']);
		$ftype = $_POST['updateFacType'];
		$fstat = $_POST['updateFacStat'];
		$fline = $_POST['updateFacLine'];
		$originalLine = $_POST['OriginalLine'];
		
		$updLINE = mysql_query("UPDATE facility SET
									fac_name='Empty Line',
									fac_type='5',
									fac_status='5'
								WHERE 
									fac_line='".$originalLine."'
								AND 
									fac_home_owner_id='".$_SESSION['clientId']."'
								");
		
		$q = mysql_query("SELECT * FROM facility_type WHERE fac_type_name='".$ftype."'");	
		$fq=mysql_fetch_array($q);
		$TYPEid = $fq['fac_type_id'];
		
		$qq = mysql_query("SELECT facility_status_id FROM facility_status WHERE facility_status_name='".$fstat."'");
		$fqq = mysql_fetch_array($qq);
		$STATUSid = $fqq['facility_status_id'];

		$q=mysql_query("UPDATE facility SET 
						fac_name='".$fname."',
						fac_type='".$TYPEid."',
						fac_status='".$STATUSid."'
						WHERE 
							fac_line = '".$_POST['updateFacLine']."'
						AND 
							fac_home_owner_id='".$_SESSION['clientId']."'
						");
		if($q)
		{
			echo "Facility details are set.";
		}	
	}
	
	if(isset($_POST['clientHomeId']) &&
		isset($_POST['clientRegId'])
		){	
		$home_id = $_POST['clientHomeId'];
		$reg_id = $_POST['clientRegId'];	
		$model->showClientHomeLocationList($home_id,$reg_id);
			
	}
	
	//modallocid
	if(isset($_POST['modallocid']) 		
	){
		$model->setLocationId($_POST['modallocid']);
	}
	?>