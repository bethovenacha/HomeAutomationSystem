<?php 
if(!isset($_SESSION)){
	session_start();
}
require_once __DIR__ . '/../../connection.php';

///////////////////////////////////////////////////////////////////MODEL/////////////////////////////////////////////////////////
class AutomationModel{

	public $LOC_ID="";
	
	function setLocationId($LOC_ID){
		$this->LOC_ID= $LOC_ID;
	}
	
	function showClientHomeLocationList($home_id,$reg_id){
		
		$qlocName = mysql_query("SELECT * FROM location 
								WHERE location_home_id='".$home_id."' 
								AND location_homeowner_id='".$reg_id."'
								");
		echo '<input type="button" class="btn btn-danger pull-right" value="Back" onclick="clientBackHome();"/>';
		//onclick="showClientFacilities(this.id,'.$home_id.','.$reg_id.');";
		echo '<input type="text" id="LOC_ID"/>';
		echo '<table class="table table-bordered">';
			echo '<th>Location List</th>';
			while($fq=mysql_fetch_array($qlocName)){
				echo '<tr>';		
				echo '<td 
						data-toggle="modal" 
						data-target="#facilitiesModal"
						onclick="setLocationIdSession('.$fq['location_id'].');";			
						>
						<span class="glyphicon glyphicon-chevron-right pull-right"></span>
						'.$fq['location_name'].'
						
						</td>';
			}
			echo '</tr>';
		echo '</table>';
		?>	
		<div id="facilitiesModal" style="overflow:auto;" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			  <div class="modal-content" style="overflow:auto;">

				<div class="modal-header" style="background-color:black;">
				  <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white;">&times;</button>
				  <h4 class="modal-title" id="myModalLabel" style="color:white;">Facility List</h4>
				</div>
					<div class="modal-body" id="modalFacilityPanel">		 
					
				   </div>
				<div class="modal-footer" style="background-color:#900">
					<label class="label label-error">Turning on or off facility with Scheduled switching status deactivates its schedule</label>
				  <button type="button" class="btn btn-danger" data-dismiss="modal" style="color:white;font-weight:bold;">Exit</button>
				</div>

			  </div>
			</div>
		  </div>
		
		<?php
	}
	function updateHomeName($homeName,$homeOwnerId,$homeId){
		$q=mysql_query("UPDATE home SET home_name='".$homeName."' WHERE home_owner_id='".$homeOwnerId."' AND home_id='".$homeId."'");
		if($q){
			echo 'Update Successful';
		}else{
			echo 'Update Failed';
		}
	}
	function addHome($homeownerid,$homename){
		$q=mysql_query("INSERT INTO home VALUES(
						'',
						'".$homename."',
						'".$homeownerid."'
						
						)"
					  );
		if($q){
			echo "Home Inserted.";
		}else{
			echo "Insertion failed.\n There must be an internet connection problem. \n Please try again";
		}
	}
	function updateClientInformation($id,$fname,$mname,$lname,$contact,$stat,$address,$email,$gender,$bdate,$rdate)
		{
			$q=mysql_query("UPDATE registration 
							SET
								reg_fname='".$fname."',
								reg_mname='".$mname."',
								reg_lname='".$lname."',
								reg_contact_no='".$contact."',
								reg_status='".$stat."',
								reg_address='".$address."',
								reg_email='".$email."',
								reg_gender='".$gender."',
								reg_birthday='".$bdate."',
								reg_date='".$rdate."'
							WHERE 
								reg_id='".$id."'
								
						  ");
			if($q){
				echo "Update Successful.";
			}else{
				echo "Update failed, please try again.";
			}
			
		}
	function getClientInformation($id){
			$q=mysql_query("SELECT * FROM registration WHERE reg_id='".$id."'");
			
			$row=mysql_fetch_array($q);
			
			echo $row['reg_id']."~".$row['reg_fname']."~".$row['reg_mname']."~".$row['reg_lname']."~"
				.$row['reg_contact_no']."~".$row['reg_status']."~".$row['reg_address']."~".$row['reg_email']
				."~".$row['reg_gender']."~".$row['reg_birthday']."~".$row['reg_date']
			;
	}
	function insertSchedule($facilityName,$fromDate,$toDate,
							$inputStartHour,$inputStartMinute,$inputStartMeridian,
							$inputEndHour,$inputEndMinute,$inputEndMeridian,$facility_id,$home_id,$location_id				
							)
			{	
			
			$clientId = 0;
				if(isset($_SESSION['clientId'])){
					$clientId = $_SESSION['clientId'];
				}
				
				$r = mysql_query("INSERT INTO schedule VALUES(
								'',
								'".$facility_id."',
								'".$fromDate."',
								'".$toDate."',
								'".$inputStartHour."',
								'".$inputEndHour."',
								'".$inputStartMinute."',
								'".$inputEndMinute."',
								'".$inputStartMeridian."',
								'".$inputEndMeridian."',
								'active',
								'".$clientId."',
								'".$home_id."',
								'".$location_id."'
								)");
				if($r){
					echo "Schedule Inserted.";
				}else{
					echo "Internet connection problem. Please try again.";
				}
			}
	//This function determinse the conflict in schedules
	//if conflict is found, the schedule is not inserted
	//otherwise
	function compareSchedule($facilityName,$fromDate,$toDate,
							$inputStartHour,$inputStartMinute,$inputStartMeridian,
							$inputEndHour,$inputEndMinute,$inputEndMeridian,$facility_id,$homeName,$locationName					
							)
			{	
			
			
			$bully= "true";
			$clientId = 0;
				if(isset($_SESSION['clientId'])){
					$clientId = $_SESSION['clientId'];
				}
				
				$inputMilitaryStartHour = -1;
				$inputMilitaryEndHour = -1;
		 //START OF CONVERTING INPUT TIME TO MILITARY TIME		
		if($inputStartMeridian=="am" && $inputStartHour==12){
             $mStartHour = $inputStartHour;
             $mStartHour = 0;
             $inputMilitaryStartHour = $mStartHour;
             //System.Windows.Forms.MessageBox.Show("INPUT military start hour am:" + inputMilitaryStartHour);
         }
         else if ($inputStartMeridian == "am" && $inputStartHour != 12)
         {
             $inputMilitaryStartHour = $inputStartHour;
             //System.Windows.Forms.MessageBox.Show("INPUT military start hour am:" + inputMilitaryStartHour);
         }

         else if ($inputStartMeridian == "pm" && $inputStartHour != 12)
         {
             $mStartHour = $inputStartHour;
             $mStartHour += 12;
             $inputMilitaryStartHour = $mStartHour;
             //System.Windows.Forms.MessageBox.Show("INPUT military start hour pm:" + inputMilitaryStartHour);
         }
         else if ($inputStartMeridian == "pm" && $inputStartHour == 12)
         {
             $inputMilitaryStartHour = $inputStartHour;
             //System.Windows.Forms.MessageBox.Show("INPUT military start hour pm:" + inputMilitaryStartHour);
         }
       /////////////////////////////////////////////////////////////////////////////////////////////////
	   if ($inputEndMeridian == "am" && $inputEndHour == 12)
         {
             $mEndHour = $inputEndHour;
             $mEndHour = 0;
             $inputMilitaryEndHour = $mEndHour;
             //System.Windows.Forms.MessageBox.Show("INPUT military end hour am:" + inputMilitaryEndHour);
         }
         else if ($inputEndMeridian == "am" && $inputEndHour != 12)
         {
             $inputMilitaryEndHour = $inputEndHour;
             //System.Windows.Forms.MessageBox.Show("INPUT military end hour am:" + inputMilitaryEndHour);
         }

         else if ($inputEndMeridian == "pm" && $inputEndHour != 12)
         {
             $mEndHour = $inputEndHour;
             $mEndHour += 12;
             $inputMilitaryEndHour = $mEndHour;
             //System.Windows.Forms.MessageBox.Show("INPUT military end hour pm:" + inputMilitaryEndHour);
         }
         else if ($inputEndMeridian == "pm" && $inputEndHour == 12)
         {
             $inputMilitaryEndHour = $inputEndHour;
             //System.Windows.Forms.MessageBox.Show("INPUT military end hour pm:" + inputMilitaryEndHour);
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
							 // System.Windows.Forms.MessageBox.Show("STORED military start hour am:" + storedMilitaryStartHour);
						  }
						  else if ($storedsampm == "am" && $storedStartHour != 12)
						  {
							  $storedMilitaryStartHour = $storedStartHour;
							 // System.Windows.Forms.MessageBox.Show("STORED military start hour am:" + storedMilitaryStartHour);
						  }

						  else if ($storedsampm == "pm" && $storedStartHour != 12)
						  {
							  $smHour2 = $storedStartHour;
							  $smHour2 += 12;
							  $storedMilitaryStartHour = $smHour2;
							 //System.Windows.Forms.MessageBox.Show("STORED military start hour pm:" + storedMilitaryStartHour);
						  }
						  else if ($storedsampm == "pm" && $storedStartHour == 12)
						  {
							  $storedMilitaryStartHour = $storedStartHour;
							 // System.Windows.Forms.MessageBox.Show("STORED military start hour pm:" + storedMilitaryStartHour);
						  }



              //END CONVERSION OF STORED START HOUR TO MILITARY TIME
              //START CONVERSION OF STORED END HOUR TO MILITARY TIME


						  if ($storedeampm == "am" && $storedEndHour == 12)
						  {
							  $smEndHour = $storedEndHour;
							  $smEndHour = 0;
							  $storedMilitaryEndHour = $smEndHour;
							  //System.Windows.Forms.MessageBox.Show("STORED military end hour am:" + storedMilitaryEndHour);
						  }
						  else if ($storedeampm == "am" && $storedEndHour != 12)
						  {
							  $storedMilitaryEndHour = $storedEndHour;
							  //System.Windows.Forms.MessageBox.Show("STORED military end hour am:" + storedMilitaryEndHour);
						  }

						  else if ($storedeampm == "pm" && $storedEndHour != 12)
						  {

							  $smEndHour2 = $storedEndHour;
							  $smEndHour2 += 12;
							  $storedMilitaryEndHour = $smEndHour2;
							  //System.Windows.Forms.MessageBox.Show("STORED military end hour pm:" + storedMilitaryEndHour);
						  }
						  else if ($storedeampm == "pm" && $storedEndHour == 12)
						  {
							  $storedMilitaryEndHour = $storedEndHour;
							  //System.Windows.Forms.MessageBox.Show("STORED end hour pm:" + storedMilitaryEndHour);
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
							  
									$bully = 'Conflict:From:' .$storedStartHour. ':' . $storedStartMinute .	  $storedsampm . ' ' . 'To:' . $storedEndHour . ':' .$storedEndMinute . $storedeampm;
							  break;

						  }
							  
						  else if(
									($inputMilitaryStartHour >= $storedMilitaryStartHour)&&
									($inputStartMinute >= $storedStartMinute) &&
									($inputMilitaryEndHour <= $storedMilitaryEndHour) &&
									($inputEndMinute <= $storedEndMinute)
								 )
						  {
							  $bully = 'Conflict:From:' .$storedStartHour. ':' . $storedStartMinute .	  $storedsampm . ' ' . 'To:' . $storedEndHour . ':' .$storedEndMinute . $storedeampm;
							 
							  break;
						  }
							 
						  else if (
								   ($inputMilitaryStartHour <= $storedMilitaryEndHour) &&
								   ($inputStartMinute <= $storedEndMinute) &&
									($inputMilitaryEndHour >= $storedMilitaryEndHour) &&
									($inputStartMinute >= $storedStartMinute)

								  )
						  {
							  $bully = 'Conflict:From:' .$storedStartHour. ':' . $storedStartMinute .	  $storedsampm . ' ' . 'To:' . $storedEndHour . ':' .$storedEndMinute . $storedeampm;
							
							  break;
						  }
						  else if(
									($inputMilitaryStartHour == $storedMilitaryStartHour) &&
									($inputStartMinute == $storedStartMinute) &&
									($inputMilitaryEndHour == $storedMilitaryEndHour) &&
									($inputEndMinute == $storedEndMinute)                        
								)
						  {
							
							  $bully = 'Conflict:From:' .$storedStartHour. ':' . $storedStartMinute .	  $storedsampm . ' ' . 'To:' . $storedEndHour . ':' .$storedEndMinute . $storedeampm;
							  break;
						  }
						  //END COMPARING 
		}					
          
		  echo $bully; 
			  
	}
		
	//This function filters unnecessary characters and returns the sanitized variable
	function filterAll($v){
		ini_set( 'register_globals', 0); 
		$s=mysql_real_escape_string($v);
		return $s;
	}
	//This function accepts query and returns the result of that query
	function q($query){
		$r=mysql_query($query);	
		return $r;
	}
	//This function accepts query results,counts the number of rows, and returns the result
	function cr($queryResult){
		$r=mysql_num_rows($queryResult);
		return $r;
	}
	//This function gets information from the facility and returns a string
	function getFacilityInfo($id){
		$q=$this->q("SELECT * FROM facility WHERE fac_id='".$id."'");
		$res="";
		while($r=mysql_fetch_array($q)){
			$selectType =mysql_query("SELECT * FROM facility_type WHERE fac_type_id='".$r['fac_type']."'");
			$fetch_type= mysql_fetch_array($selectType);
			
			$select_Status =mysql_query("SELECT * FROM facility_status WHERE facility_status_id='".$r['fac_status']."'");
			$fetch_stat = mysql_fetch_array($select_Status);
			$res .= $r['fac_name']."~".$fetch_type['fac_type_name']."~".$fetch_stat['facility_status_name']."~".$r['fac_line'];
		}
		return $res;
	}
	//This function gets information regarding schedule of the facilities
	function getScheduleInfo($id){	
	
		$qFID = mysql_query("SELECT * FROM schedule WHERE sched_id='".$id."'");
		$fqfid = mysql_fetch_array($qFID);
		
		$ress=$this->q("SELECT * FROM facility WHERE fac_id='".$fqfid['sched_fid']."'");
		$rr=mysql_fetch_array($ress);
		
		
		$qLOC = mysql_query("SELECT * FROM location WHERE location_id='".$fqfid['sched_location_id']."' AND location_homeowner_id='".$rr['fac_home_owner_id']."'");
		
		$fqLOC = mysql_fetch_array($qLOC);
				
		$qhome = mysql_query("SELECT home_name FROM home WHERE home_id='".$rr['fac_home_id']."'");
		$fetch_home=mysql_fetch_array($qhome);
		$res = $this->q("SELECT * FROM schedule WHERE sched_id='".$id."'");
			$r=mysql_fetch_array($res);
			
			echo $r['sched_id']."~".$r['sched_fid']."~".$r['sched_FromDate']."~".
				 $r['sched_ToDate']."~".$r['sched_shour']."~".$r['sched_ehour']."~"
				 .$r['sched_smin']."~".$r['sched_emin']."~"
				  .$r['sched_sampm']."~".$r['sched_eampm']."~".$r['sched_stat']."~".$rr['fac_name']."~".$fetch_home['home_name']."~".$fqLOC['location_name'];
	}
	//This function accepts receives username to $u and password to $pack
	//If the login is succesful, facilities are displayed
	function login($u,$p){
		$q=mysql_query("SELECT * FROM registration WHERE reg_username='".$u."' AND reg_password='".$p."'");
			$r=mysql_num_rows($q);
			if($r==0){			
				$_SESSION['islogin']=false;	
			}else{
				$_SESSION['islogin']=true;
				$f=mysql_fetch_array($q);
				$_SESSION['role']= $f['reg_type'];
				$_SESSION['clientId'] = $f['reg_id'];
				}
		return $_SESSION['islogin'];
	}
	//this function get the id of any facility
	function getFacilityId($name){
		$r=mysql_query("SELECT fac_id FROM facility Where fac_name='".$name."' ");
		$rr=mysql_fetch_array($r);
		return $rr['fac_id'];
	}
}
?>