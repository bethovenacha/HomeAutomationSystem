<?php
		
	$json = file_get_contents('php://input');
	$obj = json_decode($json);

	require_once 'c.php';
	require_once 'model.php';
	$Model= new AndroidModel;
	
	
	/////////
	if(
			isset($obj->{'compareSched'}) 	
			)
	{
	/*
	$Model->compareSchedule("Living Room Light","2015-03-18","2015-03-18",
								"1","2","am",
								"2","2","am","Sultan Kudarat","Bedroom","1"
								);	
								*/
		if(
			isset($obj->{'fname'}) &&
			isset($obj->{'fromDate'}) &&
			isset($obj->{'toDate'}) &&
			isset($obj->{'startHour'}) &&
			isset($obj->{'startMin'}) &&
			isset($obj->{'startAmpm'}) &&
			isset($obj->{'endHour'}) &&
			isset($obj->{'endMin'}) &&
			isset($obj->{'endAmpm'}) &&
			isset($obj->{'reg_id'}) &&
			isset($obj->{'homeName'}) &&
			isset($obj->{'locationName'})
		)
		{
			
			$fname = $obj->{'fname'};
			$fromDate = $obj->{'fromDate'};
			$toDate = $obj->{'toDate'};
			$startHour = $obj->{'startHour'};
			$startMin = $obj->{'startMin'};
			$startAmpm = $obj->{'startAmpm'};
			$endHour = $obj->{'endHour'};
			$endMin= $obj->{'endMin'};
			$endAmpm = $obj->{'endAmpm'};
			$reg_id = $obj->{'reg_id'};
			$homeName = $obj->{'homeName'};
			$locationName = $obj->{'locationName'};
			
			
			$Model->compareSchedule($fname,$fromDate,$toDate,
								$startHour,$startMin,$startAmpm,
								$endHour,$endMin,$endAmpm,$homeName,$locationName,$reg_id
								);
								
		}
	}
	
	
	
	
	/////////
	
	if(isset($obj->{'HomeGrid'})
		&& isset($obj->{'LocationGrid'})
		&& isset($obj->{'reg_id'})
	){
		$home = $obj->{'HomeGrid'};
		$location = $obj->{'LocationGrid'};
		$id = $obj->{'reg_id'};
		$Model->getFac($home,$location,$id);
	}
	
	if(isset($obj->{'Home'}) && isset($obj->{'reg_id'})){
		$home=$obj->{'Home'};
		$reg_id = $obj->{'reg_id'};
		
		$Model->getLocationFromHome($home,$reg_id);
	}
	
	
	if(isset($obj->{'username'}) && isset($obj->{'password'})){ //THIS CONDITIONAL IS FOR LOGIN	
		$Model->login($obj->{'username'},$obj->{'password'});	
	}else if(isset($obj->{'command'}) || isset($obj->{'id'})){
		//THIS CONDITIONAL GETS THE FACILITY DATA
		$id = $obj->{'id'};
		if($obj->{'command'}=="getFacility"){
			$Model->getFacilityInfo($id);
		}else if($obj->{'command'}=="getSingleImage"){
			$Model->getSingleImage($id);
		}else if($obj->{'command'}=="getFacilityDistinctHome"){
			$Model->getDistinctHome($id);
		}else if($obj->{'command'}=="getFacilityType"){
			$Model->getFacilityType($id);
		}else if($obj->{'command'}=="getFacilityStatus"){
			$Model->getFacilityStatus($id);
		}
		else if($obj->{'command'}=="getFacilityLine"){
			$Model->getFacilityLine($id);
		}
		else if($obj->{'command'}=="getFacilityLocation"){
			$Model->getFacilityLocation($id);
		}
		
	}
	
	else if(
			isset($obj->{'checkFacility'}) || isset($obj->{'idFac'}) ||
			isset($obj->{'line'}) || isset($obj->{'status'}) ||
			isset($obj->{'type'}) || isset($obj->{'location'}) ||
			isset($obj->{'name'}) || isset($obj->{'reg_id'})
			){
			$name= $obj->{'name'};
			$command=$obj->{'checkFacility'};
			$id=$obj->{'idFac'};
			$line =$obj->{'line'};
			$status=$obj->{'status'};
			$type=$obj->{'type'};
			$location = $obj->{'location'};
			$reg_id = $obj->{'reg_id'};
			$Model->checkFacility($id,$line,$status,$type,$location,$name,$reg_id);
	}else if(
			isset($obj->{'overrideFacility'})
			&& isset ($obj->{'idTobeOverriden'})
			&& isset ($obj->{'lineOverride'})
			&& isset ($obj->{'typeOverride'})
			&& isset ($obj->{'locationOverride'})
			&& isset ($obj->{'statusOverride'})
			&& isset ($obj->{'idOfNewFacility'})
			&& isset ($obj->{'newFacilityName'})
			){
		$IdTobeOverriden= $obj->{'idTobeOverriden'};
		$lineId= $obj->{'lineOverride'};
		$typeId= $obj->{'typeOverride'};
		$locId = $obj->{'locationOverride'};
		$statId= $obj->{'statusOverride'};
		$idOfNewFacility= $obj->{'idOfNewFacility'};
		$newFacilityName = $obj->{'newFacilityName'};
		$Model->setFacility($IdTobeOverriden,$lineId,$typeId,$locId,$statId,$idOfNewFacility,$newFacilityName);
	}
	
	
	if(isset($obj->{'Facname'})){
		$n=$obj->{'Facname'};
		$Model->changeBit($n);
	}
	else if(isset($obj->{'getSchedule'}) && isset($obj->{'id'})){
			$id = $obj->{'id'};
			$Model->getSchedule($id);
		}
	
	if(isset($obj->{'updSched'})){
			if(isset($obj->{'fn'}) &&
				isset($obj->{'fd'}) &&
				isset($obj->{'td'}) &&
				isset($obj->{'sh'}) &&
				isset($obj->{'sm'}) &&
				isset($obj->{'sampm'}) &&
				isset($obj->{'eh'}) &&
				isset($obj->{'em'}) &&
				isset($obj->{'eampm'}) 
			
			)
			{
				$fn = $obj->{'fn'} ;
				$fd = $obj->{'fd'};
				$td= $obj->{'td'};
				$sh = $obj->{'sh'}; 
				$sm = $obj->{'sm'};
				$sampm = $obj->{'sampm'};
				$eh = $obj->{'eh'};
				$em = $obj->{'em'}; 
				$eampm = $obj->{'eampm'};
				
					$Model->updateFacility(
						$fn,
						$fd,
						$td,
						$sh,
						$sm,
						$sampm, 
						$eh,
						$em,
						$eampm 
					);
				
			}
		}
	else if(
			isset($obj->{'raddSched'}) 	
			)
	{
		if(
			isset($obj->{'rfname'}) ||
			isset($obj->{'rfromDate'}) ||
			isset($obj->{'rtoDate'}) ||
			isset($obj->{'rstartHour'}) ||
			isset($obj->{'rstartMin'}) ||
			isset($obj->{'rstartAmpm'}) ||
			isset($obj->{'rendHour'}) ||
			isset($obj->{'rendMin'}) ||
			isset($obj->{'rendAmpm'}) 
		)
		{
			$fname = $obj->{'rfname'};
			$fromDate = $obj->{'rfromDate'};
			$toDate = $obj->{'rtoDate'};
			$startHour = $obj->{'rstartHour'};
			$startMin = $obj->{'rstartMin'};
			$startAmpm = $obj->{'rstartAmpm'};
			$endHour = $obj->{'rendHour'};
			$endMin= $obj->{'rendMin'};
			$endAmpm = $obj->{'rendAmpm'};
			
			$Model->realAddSchedule($fname,$fromDate,$toDate,
								$startHour,$startMin,$startAmpm,
								$endHour,$endMin,$endAmpm
								);
		}
	}
	/*
	$Model->compareSchedule("Living Room Light","2015-03-18","2015-03-18",
								"1","2","am",
								"2","2","am","Sultan Kudarat","Bedroom","1"
								);	
								*/
								
	
?>