<?php
/*
THIS CLASS IS MEANT TO RECEIVE ANY SIGNAL FROM THE CONTROLLER AND PROCESSES DATA FROM DATABASE
*/
	class AndroidModel{
		//This function finds conflict
		// returns the schedule where the conflict happened
		function compareSchedule($facilityName,$fromDate,$toDate,
							$inputStartHour,$inputStartMinute,$inputStartMeridian,
							$inputEndHour,$inputEndMinute,$inputEndMeridian,$homeName,$locationName,$clientId					
							)
			{	
				$qH = mysql_query("SELECT home_id FROM home WHERE home_name='".$homeName."'");
				$fqH = mysql_fetch_array($qH);
				$HID = $fqH['home_id'];//THIS IS THE HOME ID
				
				$qL = mysql_query("SELECT location_id FROM location WHERE location_name='".$locationName."'");
				$fqL = mysql_fetch_array($qL);
				$LID = $fqL['location_id'];//THIS IS THE  LOCATION ID
				
				$qFID = mysql_query("SELECT * FROM facility WHERE fac_home_owner_id='".$clientId."' AND fac_home_id='".$HID."' AND fac_location_id='".$LID."'");
				
				$fqFID = mysql_fetch_array($qFID);
				//THIS IS THE FACILITY ID
				$facility_id = $fqFID['fac_id'];//THIS IS THE FACILITY ID				
				
				$inputMilitaryStartHour = -1;
				$inputMilitaryEndHour = -1;
				
		 //START OF CONVERTING INPUT TIME TO MILITARY TIME		
		if($inputStartMeridian=="am" && $inputStartHour==12){
             $mStartHour = $inputStartHour;
             $mStartHour = 0;
             $inputMilitaryStartHour = $mStartHour;
         }
         else if ($inputStartMeridian == "am" && $inputStartHour != 12)
         {
             $inputMilitaryStartHour = $inputStartHour;
         }

         else if ($inputStartMeridian == "pm" && $inputStartHour != 12)
         {
             $mStartHour = $inputStartHour;
             $mStartHour += 12;
             $inputMilitaryStartHour = $mStartHour;
         }
         else if ($inputStartMeridian == "pm" && $inputStartHour == 12)
         {
             $inputMilitaryStartHour = $inputStartHour;
         }
	   if ($inputEndMeridian == "am" && $inputEndHour == 12)
         {
             $mEndHour = $inputEndHour;
             $mEndHour = 0;
             $inputMilitaryEndHour = $mEndHour;
         }
         else if ($inputEndMeridian == "am" && $inputEndHour != 12)
         {
             $inputMilitaryEndHour = $inputEndHour;
         }

         else if ($inputEndMeridian == "pm" && $inputEndHour != 12)
         {
             $mEndHour = $inputEndHour;
             $mEndHour += 12;
             $inputMilitaryEndHour = $mEndHour;
         }
         else if ($inputEndMeridian == "pm" && $inputEndHour == 12)
         {
             $inputMilitaryEndHour = $inputEndHour;
         }
	   //END OF CONVERTING INPUT TIME TO MILITARY TIME
	   /////////////////////////////////////////////////////////////////////////////////////////////////
	   
	    $d = mysql_query("SELECT * FROM schedule WHERE sched_client_id='" . $clientId . "' AND sched_fid='". $facility_id ."' AND sched_stat='active' AND sched_FromDate>='" . $fromDate . "' AND sched_ToDate<='" . $toDate ."'");
	   
		while($dr=mysql_fetch_array($d)){
						  $storedStartHour = $dr['sched_shour'];
						  $storedEndHour = $dr['sched_ehour'];
						  $storedStartMinute = $dr['sched_smin'];
						  $storedEndMinute = $dr['sched_emin'];
						  $storedsampm = $dr['sched_sampm'];
						  $storedeampm = $dr['sched_eampm'];

						 $storedMilitaryStartHour = -1;
						 $storedMilitaryEndHour = -1;
              //START CONVERTING STORED HOURS TO MILITARY TIME
              //START CONVERSION OF STORED START HOUR TO MILITARY TIME
						  if ($storedsampm == "am" && $storedStartHour == 12)
						  {
							  $smHour = $storedStartHour;
							  $smHour = 0;
							  $storedMilitaryStartHour = $smHour;
						  }
						  else if ($storedsampm == "am" && $storedStartHour != 12)
						  {
							  $storedMilitaryStartHour = $storedStartHour;
						  }

						  else if ($storedsampm == "pm" && $storedStartHour != 12)
						  {
							  $smHour2 = $storedStartHour;
							  $smHour2 += 12;
							  $storedMilitaryStartHour = $smHour2;
						  }
						  else if ($storedsampm == "pm" && $storedStartHour == 12)
						  {
							  $storedMilitaryStartHour = $storedStartHour;
						  }

              //END CONVERSION OF STORED START HOUR TO MILITARY TIME
              //START CONVERSION OF STORED END HOUR TO MILITARY TIME

						  if ($storedeampm == "am" && $storedEndHour == 12)
						  {
							  $smEndHour = $storedEndHour;
							  $smEndHour = 0;
							  $storedMilitaryEndHour = $smEndHour;
						  }
						  else if ($storedeampm == "am" && $storedEndHour != 12)
						  {
							  $storedMilitaryEndHour = $storedEndHour;
						  }

						  else if ($storedeampm == "pm" && $storedEndHour != 12)
						  {

							  $smEndHour2 = $storedEndHour;
							  $smEndHour2 += 12;
							  $storedMilitaryEndHour = $smEndHour2;
						  }
						  else if ($storedeampm == "pm" && $storedEndHour == 12)
						  {
							  $storedMilitaryEndHour = $storedEndHour;
						  }
              //END CONVERSION OF STORED END HOUR TO MILITARY TIME
             //START COMPARING OF INPUT MILITARY TIME AND STORED MILITARY TIME
						if (
							   
							  ($inputMilitaryEndHour >= $storedMilitaryStartHour) &&
							  ($inputEndMinute >= $storedStartMinute) &&
							  ($inputMilitaryStartHour <= $storedMilitaryEndHour) &&
							  ($inputStartMinute <= $storedStartMinute)
										  
							  )
						  {

			
									
							header('Content-type: application/json');
							echo json_encode(
												array(
														'sched_stat'=>'conflict','fn'=>$facilityName,
														'fd'=>$fromDate,'td'=>$toDate,'sh'=>$storedStartHour,
														'sm'=>$storedStartMinute,'sampm'=>$storedsampm,
														'eh'=>$storedEndHour,'em'=>$storedEndMinute,
														'eampm'=>$storedeampm
														
													)
											);
							  //exit();

						  }
							  
						  else if(
									($inputMilitaryStartHour >= $storedMilitaryStartHour)&&
									($inputStartMinute >= $storedStartMinute) &&
									($inputMilitaryEndHour <= $storedMilitaryEndHour) &&
									($inputEndMinute <= $storedEndMinute)
								 )
						  {
								header('Content-type: application/json');
								echo json_encode(
													array('sched_stat'=>'conflict','fn'=>$facilityName,
															'fd'=>$fromDate,'td'=>$toDate,'sh'=>$storedStartHour,
															'sm'=>$storedStartMinute,'sampm'=>$storedsampm,
															'eh'=>$storedEndHour,'em'=>$storedEndMinute,
															'eampm'=>$storedeampm
															
														)
												);
							 // exit();
						  }
							 
						  else if (
								   ($inputMilitaryStartHour <= $storedMilitaryEndHour) &&
								   ($inputStartMinute <= $storedEndMinute) &&
									($inputMilitaryEndHour >= $storedMilitaryEndHour) &&
									($inputStartMinute >= $storedStartMinute)

								  )
						  {
							  header('Content-type: application/json');
									echo json_encode(
														array('sched_stat'=>'conflict','fn'=>$facilityName,
																'fd'=>$fromDate,'td'=>$toDate,'sh'=>$storedStartHour,
																'sm'=>$storedStartMinute,'sampm'=>$storedsampm,
																'eh'=>$storedEndHour,'em'=>$storedEndMinute,
																'eampm'=>$storedeampm
																
															)
													);
							 // exit();
						  }
						  else if(
									($inputMilitaryStartHour == $storedMilitaryStartHour) &&
									($inputStartMinute == $storedStartMinute) &&
									($inputMilitaryEndHour == $storedMilitaryEndHour) &&
									($inputEndMinute == $storedEndMinute)                        
								)
						  {
							
							  header('Content-type: application/json');
							echo json_encode(
												array('sched_stat'=>'conflict','fn'=>$facilityName,
														'fd'=>$fromDate,'td'=>$toDate,'sh'=>$storedStartHour,
														'sm'=>$storedStartMinute,'sampm'=>$storedsampm,
														'eh'=>$storedEndHour,'em'=>$storedEndMinute,
														'eampm'=>$storedeampm
														
													)
											);
								//exit();
						  }
						  else
						  {
							header('Content-type: application/json');
									echo json_encode(
														array('sched_stat'=>'no conflict','fn'=>$facilityName,
																'fd'=>$fromDate,'td'=>$toDate,'sh'=>$storedStartHour,
																'sm'=>$storedStartMinute,'sampm'=>$storedsampm,
																'eh'=>$storedEndHour,'em'=>$storedEndMinute,
																'eampm'=>$storedeampm
																
															)
													);
						  }
						  //END COMPARING 
		}					
      
			  
	}
		
		//This function is used to login	
		function login($username,$password){
			require_once 'c.php';	
			$q= mysql_query("SELECT * FROM registration 
					 WHERE reg_username='".$username."' 
					 AND reg_password='".$password."'"
					);
						
			$c=mysql_num_rows($q);
			
			
			if($c==0){
				header('Content-type: application/json');
				echo json_encode(array('loginfailed'=>'failed'));
			}else{
				header('Content-type: application/json');
				echo json_encode(mysql_fetch_array($q));
			}	
		}
		//This function gets information from the facility
		function getFacilityInfo($id){
			require_once 'c.php';	
			$q=mysql_query("SELECT * FROM facility WHERE fac_home_owner_id='".$id."'");
							
			$facilityName=array();
			$home=array();
			//$image=array();
			$id=array();
			$type = array();
			$line = array();
			$status = array();
			$location = array();
			
			while($data=mysql_fetch_array($q)){
				
					
						$facilityName[]=$data['fac_name'];
						$id[]=$data['fac_line'];
						$h=mysql_query("SELECT * FROM home WHERE home_id='".$data['fac_home_id']."'");
						$hm=mysql_fetch_array($h);
						$home[]=$hm['home_name'];
						
						//$img=mysql_query("SELECT * FROM facility_images WHERE fac_images_role='on' AND //fac_id='".$data['fac_image_id']."'");
						//$f=mysql_fetch_array($img);
						//$image[]=base64_encode($f['fac_images_value']);//imag longblob
						
			}
				
			header('Content-type: application/json');
			echo json_encode(
								array('fac_name'=>$facilityName,
									'home_name'=>$home,'ids'=>$id
									)
									
							);			
		}
		
		function getSingleImage($id){
			require_once 'c.php';	
			$qq = mysql_query("SELECT * FROM facility WHERE fac_image_id='".$id."'");
			$res = mysql_fetch_array($qq);
			
			if($res['fac_bit']==1){
				mysql_query("Update facility SET fac_bit='0' WHERE fac_image_id='".$id."'");
				$q=mysql_query("SELECT * From facility_images WHERE fac_id='".$id."' AND fac_images_role='off'");
				$r=mysql_fetch_array($q);
				header('Content-type: application/json');
				echo json_encode(array('sImage'=>base64_encode($r['fac_images_value'])));
			}else{
				mysql_query("Update facility SET fac_bit='1' WHERE fac_image_id='".$id."'");
				$q=mysql_query("SELECT * From facility_images WHERE fac_id='".$id."' AND fac_images_role='on'");
				$r=mysql_fetch_array($q);
				header('Content-type: application/json');
				echo json_encode(array('sImage'=>base64_encode($r['fac_images_value'])));
			}
			
		}
	
		function getDistinctHome($id){
			$distinctHome=array();
			require_once 'c.php';
			
			$qq = mysql_query("SELECT DISTINCT(home_name) FROM home WHERE home_owner_id='".$id."'");
			
			while($hn=mysql_fetch_array($qq)){
				$distinctHome[] = $hn['home_name'];
			}
			header('Content-type: application/json');
			echo json_encode(
							array('distinctHomeName'=>$distinctHome)
							);
			
		}
	
		function getFacilityType($id){
			require_once 'c.php';
			$type = array();
			$q=mysql_query("SELECT * FROM facility
							WHERE fac_line='".$id."'
							");
							
			$f=mysql_fetch_array($q);			
			
			$qqq=mysql_query("SELECT fac_type_name FROM facility_type WHERE fac_type_id='".$f['fac_type']."'");
			$ftype=mysql_fetch_array($qqq);
			$type[0]=$ftype['fac_type_name'];
			$qq=mysql_query("SELECT DISTINCT(fac_type_name) FROM facility_type");
			
			
			while($ff=mysql_fetch_array($qq)){
				$type[] = $ff['fac_type_name'];
			}
			
			header('Content-type: application/json');
				echo json_encode(array('type'=>$type));
		}
	
		function getFacilityStatus($id){
			require_once 'c.php';
				$status = array();
			$q=mysql_query("SELECT * FROM facility
							WHERE fac_line='".$id."'
							");
			$f=mysql_fetch_array($q);				
				$qqq=mysql_query("SELECT facility_status_name FROM facility_status WHERE facility_status_id='".$f['fac_status']."'");
			$fstatus=mysql_fetch_array($qqq);				
				
			$status [0]=$fstatus['facility_status_name'];
				
			$qq=mysql_query("SELECT DISTINCT(facility_status_name) FROM facility_status
							");
			while($ff=mysql_fetch_array($qq)){
				$status[] = $ff['facility_status_name'];
			}
			header('Content-type: application/json');
				echo json_encode(array('status'=>$status));
			
		}
		function getFacilityLine($id){
			require_once 'c.php';
			$qq=mysql_query("SELECT DISTINCT(fac_line) FROM facility");
			$line = array();
			$line[0]=$id;
			while($ff=mysql_fetch_array($qq)){
				$line[] = $ff['fac_line'];
			}
				header('Content-type: application/json');
				echo json_encode(array('line'=>$line));
		}
		function getFacilityLocation($id){
			require_once 'c.php';
			$loc = array();
			$q=mysql_query("SELECT * FROM facility
							WHERE fac_line='".$id."'
							");
				$f=mysql_fetch_array($q);		
			$qqq=mysql_query("SELECT location_name FROM location WHERE location_id='".$f['fac_location_id']."'");
			$floc=mysql_fetch_array($qqq);				
				$loc[0]=$floc['location_name'];	
			$qq=mysql_query("SELECT DISTINCT(location_name) FROM location");
			
			while($ff=mysql_fetch_array($qq)){
				$loc[] = $ff['location_name'];
			}
				header('Content-type: application/json');
				echo json_encode(array('location'=>$loc));
		}
		function checkFacility($id,$line,$status,$type,$location,$name,$reg_id){
			require_once 'c.php';
				
				$q=mysql_query("SELECT * FROM facility WHERE fac_line='".$line."' AND fac_home_owner_id='".$reg_id."'");
				$r=mysql_fetch_array($q);
				
			
					$qStat=mysql_query("SELECT * FROM facility_status WHERE facility_status_name='".$status."'");
					$stat=mysql_fetch_array($qStat);
					$statID=$stat['facility_status_id'];
			
					$qType=mysql_query("SELECT fac_type_id FROM facility_type WHERE fac_type_name='".$type."'");
					$typeA=mysql_fetch_array($qType);
					$typeID=$typeA['fac_type_id'];
			
					$qLoc=mysql_query("SELECT location_id FROM location WHERE location_name='".$location."'");
					$locA=mysql_fetch_array($qLoc);
					$locID=$locA['location_id'];
					
					$u=mysql_query("UPDATE facility 
									SET 
										fac_name='".$name."',
										fac_status='".$statID."',
										fac_type='".$typeID."',
										fac_location_id='".$locID."'
									WHERE	
										fac_line='".$line."'
									AND 
										fac_home_owner_id='".$reg_id."'
					");
					$message="";
					if($u){
						$message="Facility Updated.";
					}
						header('Content-type: application/json');
						echo json_encode(array('updateMessage'=>$message));
				
				
			
			
			
		}
		
		function setFacility($IdTobeOverriden,$lineId,$typeId,$locId,$statId,$idOfNewFacility,$newFacilityName){
			require_once 'c.php';
			
			$updateNewFacility = mysql_query("UPDATE facility SET
									fac_name='Empty Line',
									fac_status='1',
									fac_bit='0',
									fac_switching_status='0'
									WHERE fac_line='".$idOfNewFacility."'
								");
			if($updateNewFacility){
				$updateOldFacility=mysql_query("UPDATE facility SET 
						fac_name='".$newFacilityName."',
						fac_type='".$typeId."',
						fac_status='".$statId."',
						fac_bit='0',
						fac_location_id='".$locId."'
						
						WHERE fac_id='".$IdTobeOverriden."'
						");
				if($updateOldFacility){
					header('Content-type: application/json');
							echo json_encode(array('settingFacility'=>'true'));
				}else{
					header('Content-type: application/json');
							echo json_encode(array('settingFacility'=>'false'));
				}
			}		
		}
	
		function getLocationFromHome($home,$reg_id){
			require_once 'c.php';
			
			$q=mysql_query("SELECT home_id FROM home WHERE home_name='".$home."' AND home_owner_id='".$reg_id."'");
			$f=mysql_fetch_array($q);
			$homeID=$f['home_id'];
			//$qq=mysql_query("SELECT fac_location_id FROM facility WHERE fac_home_id='".."' ");
			$qqq=mysql_query("SELECT DISTINCT(fac_location_id) From facility WHERE fac_home_id='".$homeID."'");
			$loc = array();
			while($r=mysql_fetch_array($qqq)){
				$l=mysql_query("SELECT location_name FROM location WHERE location_id='".$r['fac_location_id']."'");
				$rr=mysql_fetch_array($l);
				$loc[]=$rr['location_name'];
			}
			header('Content-type: application/json');
			echo json_encode(array('locFromHome'=>$loc));
		}
		function getFac($home,$location,$reg_id){
			require_once 'c.php';
			
			$q=mysql_query("SELECT * FROM home WHERE home_name='".$home."' AND home_owner_id='".$reg_id."'");
			$f=mysql_fetch_array($q);
			
			$hID=$f['home_id'];
			
			$qq=mysql_query("SELECT * FROM location WHERE location_name='".$location."' AND location_homeowner_id='".$reg_id."'");
			$ff=mysql_fetch_array($qq);
			
			$lID=$ff['location_id'];
			
			$s=mysql_query("SELECT fac_name FROM facility WHERE fac_home_id='".$hID."'
							AND fac_location_id='".$lID."' AND fac_home_owner_id='".$reg_id."'
							");
			$n=array();
			while($r=mysql_fetch_array($s)){
				$n[]=$r['fac_name'];
			}
			header('Content-type: application/json');
			echo json_encode(array('fnames'=>$n));
		}
		function changeBit($n){
			require_once 'c.php';
			$q=mysql_query("SELECT fac_bit FROM facility WHERE fac_name='".$n."'");
			$b=mysql_fetch_array($q);
			
			if($b['fac_bit']==0){
				$u=mysql_query("UPDATE facility SET fac_bit='1' WHERE fac_name='".$n."'");
				header('Content-type: application/json');
				echo json_encode(array('facbitupdate'=>'on'));
			}else{
				$u=mysql_query("UPDATE facility SET fac_bit='0' WHERE fac_name='".$n."'");
				header('Content-type: application/json');
				echo json_encode(array('facbitupdate'=>'off'));
			}
		}
	
		function getSchedule($reg_id){
			require_once 'c.php';
			$q=mysql_query("SELECT * FROM schedule WHERE sched_stat='active' AND sched_client_id='".$reg_id."'");
			
			
			$fid=array();
			$fromDate=array();
			$toDate=array();
			$startHour=array();
			$endHour=array();
			$startMin=array();
			$endMin=array();
			$sampm=array();
			$eampm=array();
			
			$homeName = array();
			$locationName = array();
			
			$facilityName=array();
			
			while($r=mysql_fetch_array($q)){
				$fid[]=$r['sched_fid'];
				$fromDate[]=$r['sched_FromDate'];
				$toDate[]=$r['sched_ToDate'];
				$startHour[]=$r['sched_shour'];
				$endHour[]=$r['sched_ehour'];
				$startMin[]=$r['sched_smin'];
				$endMin[]=$r['sched_emin'];
				$sampm[]=$r['sched_sampm'];
				$eampm[]=$r['sched_eampm'];
				$qq=mysql_query("SELECT DISTINCT(fac_name),fac_home_id,fac_location_id From facility WHERE fac_id='".$r['sched_fid']."'");
				$ff=mysql_fetch_array($qq);
				$facilityName[]=$ff['fac_name'];
				
				$qHome = mysql_query("SELECT * FROM home WHERE home_id='".$ff['fac_home_id']."' AND home_owner_id='".$reg_id."'");
				
				$fhome=mysql_fetch_array($qHome);
				$homeName[] = $fhome['home_name'];
				
				$qLocation = mysql_query("SELECT * FROM location WHERE location_id='".$ff['fac_location_id']."'");
				$fLocation = mysql_fetch_array($qLocation);
				$locationName[] = $fLocation['location_name'];
				
			}
			
			header('Content-type: application/json');
				echo json_encode(
								array(
									  'facilityName'=>$facilityName,
									  'fid'=>$fid,
									  'fromDate'=>$fromDate,
									  'toDate'=>$toDate,
									  'startHour'=>$startHour,
									  'endHour'=>$endHour,
									  'startMinute'=>$startMin,
									  'endMinute'=>$endMin,
									  'sampm'=>$sampm,
									  'eampm'=>$eampm,
									  'homeName'=>$homeName,
									  'locationName'=>$locationName
									)
								
								);
		}
	
		
	function checkSchedule($fname,$fromDate,$toDate,
						$startHour,$startMinute,$startAmpm,
						$endHour,$endMinute,$endAmpm
						)
	{			
			require_once 'c.php';
			$qf=mysql_query("SELECT fac_id From facility WHERE fac_name='".$fname."'");
			$fid=mysql_fetch_array($qf);
			
			$q=mysql_query("SELECT * FROM schedule 
							WHERE sched_FromDate >='".$fromDate."'
							AND sched_ToDate <='".$toDate."'
							AND sched_id='".$fid['fac_id']."'
							AND sched_sampm='".$startAmpm."'
							AND sched_eampm='".$endAmpm."'
							"
							);
																
				while($r=mysql_fetch_array($q)){														
					//Try to find the conflict
					if(
						$startHour <= $r['sched_ehour'] &&	
						$startMinute <= $r['sched_emin'] &&
						 $endHour >= $r['sched_shour']  &&
						$endMinute >= $r['sched_smin']   &&
						$endHour != 12
					 )
					{					
						return "conflict";			
					}
					else if(
						$startHour >= $r['sched_shour'] &&
						$startMinute >= $r['sched_smin'] &&
						$endHour <= $r['sched_ehour'] &&
						$endMinute < $r['sched_emin'] 	 
					)
					{	
						return "conflict";
					}
					else if(
							$startHour <= $r['sched_ehour'] &&
							$startMinute <= $r['sched_emin'] &&
							$endHour >= $r['sched_shour'] &&
							$endMinute >= $r['sched_smin'] &&
							$endHour != 12 
							)
					{
						return "conflict";
					}
												
												
				}
										
	}
	 
	 function updateFacility(
				$fn ,
				$fd ,
				$td,
				$sh,
				$sm,
				$sampm, 
				$eh ,
				$em ,
				$eampm 
			)
			{
				require_once 'c.php';
				$q=mysql_query("SELECT fac_id FROM facility WHERE fac_name='".$fn ."'");
				$facid=mysql_fetch_array($q);
				
				$u=mysql_query("UPDATE schedule SET 
									sched_FromDate='".$fd."',
									sched_ToDate='".$td."',
									sched_shour='".$sh."',
									sched_smin='".$sm."',
									sched_sampm='".$sampm."',
									sched_ehour='".$eh."',
									sched_emin='".$em."',
									sched_eampm='".$eampm."'
								WHERE 
									sched_fid='".$facid['fac_id']."'
								");
				if($u){
					header('Content-type: application/json');
					echo json_encode(
								array(
									  'update_stat'=>"successful"  
									 )
								);
				}
				else{
					header('Content-type: application/json');
					echo json_encode(
								array(
									  'update_stat'=>"failed"  
									 )
								);
				}
				
			}
	
	function realAddSchedule($fname,$fromDate,$toDate,
								$startHour,$startMin,$startAmpm,
								$endHour,$endMin,$endAmpm
							)
							{
							
							require_once 'c.php';
							
							$q = mysql_query("
								SELECT fac_id From facility WHERE fac_name='".$fname."'
							");
							$n=mysql_num_rows($q);
							if($n!=0){
								$fid=mysql_fetch_array($q);
							
								$insert = mysql_query("INSERT INTO schedule VALUES(
													'',
													'".$fid['fac_id']."',
													'".$fromDate."',
													'".$toDate."',
													'".$startHour."',
													'".$endHour."',
													'".$startMin."',
													'".$endMin."',
													'".$startAmpm."',
													'".$endAmpm."',
													'active'
													)");
								if($insert){
									header('Content-type: application/json');
									echo json_encode(
												array(
													  'real_insertion'=>"successful"
													 )
												);
								}
							}else{
									header('Content-type: application/json');
									echo json_encode(
												array(
													  'real_insertion'=>"failed"
													 )
												);
							}
							
						}
	}
	
?>