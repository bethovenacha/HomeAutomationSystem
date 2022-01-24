function adminManageFacility(id)
{
	document.getElementById("txtHiddenFacilityIdd").value = id;
	var home_owner_id = document.getElementById("txtHiddenHomeOwnerIdd").value;
	var home_id = document.getElementById("txtHiddenHomeIdd").value; 
	var location_id = document.getElementById("txtHiddenLocationIdd").value;
}
function filterSettingsFacility()
{
	var home = $("#cmbSettingsHomeFilter").val();
	var location = $("#cmbSettingsLocationFilter").val();
	var id = $("#hiddenSettingsHomeOwnerId").val();
	if(home==""){
		$("#lblSettingsInformer").html("Please select a home.");
	}else if(location == "")
	{
		$("#lblSettingsInformer").html("Please select a location.");
	}
	else
	{
		
			$.post('php/VERSION 1/CONTROLLER/controller.php',{SettingsHome:home,SettingsLocation:location,SettingsId:id},
				function(data){
				$("#lblSettingsInformer").html("");
				$("#pnlSettingsBody").html(data);
			});
		
	}
}
function filterFacilities(){
	var home = $("#cmbFacilitiesHomeFilter").val();
	var location = $("#cmbFacilitiesLocationFilter").val();
	var id = $("#hiddenHomeOwnerId").val();
	
	if(home == ""){
		$("#filterFacilitiesInformer").html("Please select a home.");
	}else if(location == "")
	{
		$("#filterFacilitiesInformer").html("Please select a location.");
	}else{		
		$("#filterFacilitiesInformer").html("");
		$.post('php/VERSION 1/CONTROLLER/controller.php',{home:home,location:location,id:id,command:"filterFacilities"},function(data){		
			$("#mycontent").html(data);
		});
	}
	
	
}
function viewFacilitySchedule(){
		sendData("facility");
		
}
function hellow(id){
	alert(id);
}
function setLocationIdSession(locid){
	
	
	$.post('php/VERSION 1/CONTROLLER/controller.php',
			{
				modallocid:locid
			},
			function(data){
		
	});
	
	
}
function clientBackHome(){
	sendData("facility");
		setInterval(function () {getActivity()}, 3000);	
}
function facilityHomeClick(homeId,hOwnerId){
	$.post('php/VERSION 1/CONTROLLER/controller.php',{clientHomeId:homeId,clientRegId:hOwnerId},function(data){
		$("#panelHomeListBody").html("");
		$("#panelHomeListBody").html(data);
	});
}
function clickBtnSetFacility(){
	var fname = document.getElementById("txtSettingsFname").value;
	var type = document.getElementById("cmbFacilityType").value;
	var stat = document.getElementById("cmbFacilityStatus").value;
	var line = document.getElementById("cmbLine").value;
	
	if(fname.length == 0 || type.length ==0 || stat.length == 0 || line.length == 0 ){
		$("#informerSetFacility").html("Complete all the inputs.");	
	}else{
		var OriginalLine = document.getElementById("hiddenOriginalLine").value;
	
		$.post('php/VERSION 1/CONTROLLER/controller.php',
			{
			updateFacName:fname,
			updateFacType:type,
			updateFacStat:stat,
			updateFacLine:line,
			OriginalLine:OriginalLine
			},
			function(data){
					
					clearInterval("",7000);
					sendData("settings");
					
		});
	}
	
	
} 
function viewFacilities(hoid,hid,locid){
	document.getElementById("txtHiddenLocationIdd").value = locid;
	//hoid is homeowner id
	//hid = home id
	//locid is locid
	$.post('php/VERSION 1/CONTROLLER/controller.php',{hoid:hoid,hid:hid,locid:locid},function(data){
		$("#clientContainer").html(data);
	});
  }
function addLocation(addhomeId,addhomeownerId){
	var locname = document.getElementById("txtAddLocation").value;//location name
	var addHid = addhomeId;	// home id
	var addHoId = addhomeownerId; //home owner id
	$.post('php/VERSION 1/CONTROLLER/controller.php',{locname:locname,addHid:addHid,addHoId:addHoId},function(data){
		alert(data);
	});
  }
function deleteHome(delhomeOwnerId,delhomeId){
		var c= confirm("Are you sure you want to delete this home?");
		if(c){
			$.post('php/VERSION 1/CONTROLLER/controller.php',{delhomeOwnerId:delhomeOwnerId,delhomeId:delhomeId},function(data){
				alert(data);
				clientClick(delhomeOwnerId);
			});
		}	
   }
function updateHomeName(homeownerid,homeid){
		
		var homId = homeid;		
		var homOwnerId = homeownerid;
		var homenameid = "#homename" + homeid;
		var homename = $(homenameid).val();
				
		$.post('php/VERSION 1/CONTROLLER/controller.php',{homId:homId,homOwnerId:homOwnerId,homename:homename},function(data){
				alert(data);
		});
				
   }
function updateClientInfor(id){
	$.post('php/VERSION 1/CONTROLLER/controller.php',{id:id,cmd:"getClientInformation"},function(data){
		var dat = Array();	
		dat = data.split("~");
		document.getElementById('hiddenClientId').value= dat[0];
		document.getElementById('txtFirstName').value = dat[1];
		document.getElementById('txtMiddleName').value= dat[2];
		document.getElementById('txtLastName').value = dat[3];
		document.getElementById('txtContact').value= dat[4];
		
		document.getElementById('cmbStatus').value = dat[5];
		
		document.getElementById('txtAddress').value= dat[6];
		document.getElementById('txtEmail').value = dat[7];
		
		document.getElementById('cmbGender').value= dat[8];
		
		document.getElementById('dtBdate').value = dat[9];
		document.getElementById('dtRegDate').value= dat[10];
		
	});
	
	
  }
function addSchedule(){
	//this function adds schedule of a facility
	var facilityName= document.getElementById('schedFacilityName').value;
	var fromDate= document.getElementById('txtFromDate').value;
	var toDate= document.getElementById('txtToDate').value;
	var startHour= document.getElementById('nmbStartHour').value;
	var startMinute= document.getElementById('nmbStartMin').value;
	var cmbAmPmStart= document.getElementById('cmbAmPmStart').value;
	var endHour= document.getElementById('txtEndHour').value;
	var endMinute= document.getElementById('txtEndMin').value;
	var cmbAmPmEnd= document.getElementById('cmbAmPmEnd').value;
	var facilityId= document.getElementById('schedFid').value;
	var homeNAME =  document.getElementById('cmbSchedulingHome').value;
	var locationNAME = document.getElementById('cmbSchedulingLocation').value;
	
	if(homeNAME == ""){
		alert("Please input the home name");
	}
	else if(locationNAME == "")
	{
		alert("Please input the location.")
	}
	else if(facilityName == ""){
		alert("Please type the facility name");
	}
	else if(fromDate == ""){
		alert("Please type the starting date.");
	}else if(toDate == ""){
		alert("Please type the end date.");
	}else if(startHour == ""){
		alert("Please type the start hour.");
	}else if(startMinute == ""){
		alert("Please type the start minute.");
	}else if(cmbAmPmStart == ""){
		alert("Please type the start meridian.");
	}else if(endHour == ""){
		alert("Please type the end hour.");
	}else if(endMinute == ""){
		alert("Please type the end minute.");
	}else if(cmbAmPmEnd == ""){
		alert("Please type the end meridian.");
	}else {
		$.post('php/VERSION 1/CONTROLLER/controller.php',{
		facilityName:facilityName,
		fromDate:fromDate,
		toDate:toDate,
		startHour:startHour,
		startMinute:startMinute,
		cmbAmPmStart:cmbAmPmStart,
		endHour:endHour,
		endMinute:endMinute,
		cmbAmPmEnd:cmbAmPmEnd,
		facilityId:facilityId,
		homeNAME:homeNAME,
		locationNAME:locationNAME
		},function(data){
			if(data == "true"){		
				
				$.post('php/VERSION 1/CONTROLLER/controller.php',{
					command:"insertSchedule",
					facName:facilityName,
					fDate:fromDate,
					tDate:toDate,
					sHour:startHour,
					sMinute:startMinute,
					sMeridian:cmbAmPmStart,
					eHour:endHour,
					eMinute:endMinute,
					eMeridian:cmbAmPmEnd,
					facId:facilityId,
					homeNAME:homeNAME,
					locationNAME:locationNAME
					
				},function(data){
					sendData("scheduling");
					alert(data);
				});
				
			}else{
				alert(data);
			}
			//$('#scheduleListBody').html(" ");
			//$('#scheduleListBody').html(data);
		});		
	}
	
	
} 
function getActivity(){
	
	/*$.post('php/VERSION 1/CONTROLLER/controller.php',{id:"getActivity"},function(data){	
			var d = data + "";
			
			if(data=="facility"){
				sendData(data);	
				
			}
	});
	*/
}
function hoverScheduleRow(id){
	document.getElementById(id).bgColor="lightblue";
}
function hoverOutScheduleRow(id){
	document.getElementById(id).bgColor="white";
}
function clickRowSchedule(scheduleId){
	document.getElementById(scheduleId).bgColor="pink";
	var d= new Array(20);
	$.post('php/VERSION 1/CONTROLLER/controller.php',{id:scheduleId,cmd:"schedClicked"},function(data){
		d=data.split("~");
		var schedId=(d[0]);
		var facId=(d[1]);
		var fromDate=(d[2]);
		var toDate=(d[3]);
		var startHour=(d[4]);
		var endHour=(d[5]);
		var startMinute=(d[6]);
		var endMinute=(d[7]);
		var startAmPm=(d[8]);
		var endAmPm=(d[9]);
		var schedStatus=(d[10]);
		var facName=(d[11]);
		var homeName = d[12];
		var locationName = d[13];
				
		document.getElementById('schedId').value=schedId;
		document.getElementById('schedFid').value=facId;
		document.getElementById('schedFacilityName').value=facName;
		document.getElementById('txtFromDate').value=fromDate;
		document.getElementById('txtToDate').value=toDate;
		document.getElementById('nmbStartHour').value=startHour;
		document.getElementById('txtEndHour').value=endHour;
		document.getElementById('nmbStartMin').value=startMinute;
		document.getElementById('txtEndMin').value=endMinute;
		document.getElementById('cmbAmPmStart').value=startAmPm;
		document.getElementById('cmbAmPmEnd').value=endAmPm;	
		document.getElementById('cmbSchedulingHome').value = homeName;
		document.getElementById('cmbSchedulingLocation').value = locationName; 
	});
}
function sendData(id){
	$.post('php/VERSION 1/CONTROLLER/controller.php',{id:id},function(data){
			$('#mycontent').html(data);	
	});
}
function updateFacilityOn(line,facid){
	
	$.post('php/VERSION 1/CONTROLLER/controller.php',{line:line,facid:facid},function(data){			
		sendData("facility");
		//$("#MyOwnInformer").html(data);
	});	
	
	//setInterval(function () {sendData("facility")}, 5000);	
} 
function updateFacilityOff(line,facid){
	$.post('php/VERSION 1/CONTROLLER/controller.php',{lineOut:line,facidOut:facid},function(data){
		//$("#MyOwnInformer").html(data);	
			sendData("facility");
	});	
	//setInterval(function () {sendData("facility")}, 5000);	
} 
function enterClient(id){
	document.getElementById(id).bgColor="lightblue";
}
function outClient(id){
	document.getElementById(id).bgColor="white";
}
function sendDataTwo(id,classs,command){
	$.post('php/VERSION 1/CONTROLLER/controller.php',{id:id,classs:classs,command:command},function(data){
		$("#clientContainer").html("");
		$("#clientContainer").html(data);
	});
}
function clientClick(id){
	document.getElementById("txtHiddenHomeOwnerIdd").value = id;
	sendDataTwo(id,"","showHome");
}
function locationClick(id){
	sendData(id,"showFacilitiesFromLocation");
}
function delLocation(id){
	var c= confirm("Are you sure you want to delete this location?");
	if(c){
		$.post('php/VERSION 1/CONTROLLER/controller.php',{delLocationId:id},function(data){
			alert(data);
		});
	}
}
function homeClick(id,classs){
//classs is location_home_id
//id is reg id
	sendDataTwo(id,classs,"showHomeLocation");
	document.getElementById("txtHiddenHomeIdd").value = classs;
}
function homeEnter(id){
	document.getElementById(id).bgColor="lightblue";
}
function homeOut(id){
	document.getElementById(id).bgColor="white";
}
function clickLocation(homeid,homeownerid,locId){
	var locationHomeId= homeid;
	var locationHomeOwnerId= homeownerid;
	var location_id = locId;
	var locationName= document.getElementById("txtHomeLocation"+location_id).value;
	
	$.post('php/VERSION 1/CONTROLLER/controller.php',
			{locationHomeId:locationHomeId,locationHomeOwnerId:locationHomeOwnerId,
			location_id:location_id,locationName:locationName
	},function(data){
		alert(data);
	});
}
function facilityRowClick(id){
	//$("#btnSetFACILITY").prop('enabled',false);
	document.getElementById(id).bgColor="pink";
	$.post('php/VERSION 1/CONTROLLER/controller.php',{id:id,cmd:"facrowclicked"},function(data){	
		$("#hidden").val(id);
		var d= new Array(6);
		d= data.split("~");			
			var name = d[0];
			var type = d[1];
			var stat = d[2];
			var line = d[3] + "";	
			$("#txtSettingsFname").val(name);
			$("#cmbFacilityType").val(type);
			$("#cmbFacilityStatus").val(stat);
		    $("#cmbLine").val(line);
		$("#hiddenOriginalLine").val(line);
		
	});
	event.preventDefault();
}
function facilityRowHover(id){
	document.getElementById(id).bgColor="lightpink";
}
function facilityMout(id){
	document.getElementById(id).bgColor="white";
}
function login(username,password){
	$.post('php/VERSION 1/CONTROLLER/controller.php',{username:username,password:password},function(data){
		//window.location.href="index.php";
		$('#mycontent').html(data);
			
		
	})
}
$(document).ready(function(){	
	$('#login').click(function(){	
		sendData(this.id);
	});	
	$('#home').click(function(){
		//clearInterval("",7000);
		sendData(this.id);}	
	);
	$('#scheduling').click(function(){
		//clearInterval("",7000);
		sendData(this.id);
	
	});
	$('#facility').click(function(){
		sendData(this.id);
		//setInterval(function () {getActivity()}, 3000);	
	});
	$('#settings').click(function(){
		//clearInterval("",7000);
		sendData(this.id);
	});
	$('#about').click(function(){
		//clearInterval("",7000);
		sendData(this.id);
		
	});
	
	$('#schedFacilityName').change(function(){
		var name= $('#schedFacilityName').val();		
		$.post('php/VERSION 1/CONTROLLER/controller.php',{facilityN:name},function(data){
			document.getElementById("schedFid").value=data;
		});
	});
	
	$("#manageFacility").click(function(){
		
		sendData(this.id);
	});
	$("#backmanageFacility").click(function(){
		sendData("manageFacility");
	});
	
	$("#btnUpdateClientInfo").click(function(){
		var id= $("#hiddenClientId").val();
		var fname = $("#txtFirstName").val();
		var mname= $("#txtMiddleName").val();
		var lname = $("#txtLastName").val();
		var contact= $("#txtContact").val();
		var stat = $("#cmbStatus").val();
		var address= $("#txtAddress").val();
		var email = $("#txtEmail").val();
		var gender = $("#cmbGender").val();
		var bdate = $("#dtBdate").val();
		var rdate= $("#dtRegDate").val();
		
		$.post('php/VERSION 1/CONTROLLER/controller.php',
					{id:id,
					 fname:fname,
					 mname:mname,
					 lname:lname,
					 contact:contact,
					 stat:stat,
					 address:address,
					 email:email,
					 gender:gender,
					 bdate:bdate,
					 rdate:rdate
		
					},function(data){
			sendData("manageFacility");
		});	
	});
	
	$("#btnAddHome").click(function(){
		var homeowner_id = document.getElementById("hiddenHomeOwnerId").value;
		var homename = document.getElementById("txtAddHomeName").value;
		$.post('php/VERSION 1/CONTROLLER/controller.php',{homeowner_id:homeowner_id,homename:homename},function(data){
			$("#informer").html(data);
			clientClick(homeowner_id);
		});
	});
	
	$("#btnRegistration").click(function(){
	
		var fname = $("#txtFirstName").val();
		var mi = $("#txtMiddleInitial").val();
		var lname = $("#txtLastName").val();
		var username = $("#txtRegUsername").val();
		var pword = $("#txtRegPassword").val();
		var vpword = $("#txtVerifyPassword").val();
		var address = $("#txtAddress").val();
		var contact = $("#txtContactNumber").val();
		var email = $("#txtEmail").val();
		var gender = $("#cmbGender").val();
		var bday = $("#dtBirthday").val();
	

		if(pword!=vpword){
			$("#informer").html("Password did not match.");
		}else if(fname == ""){
			$("#informer").html("Type your first name.");
		}else if(mi==""){
			$("#informer").html("Type your middle initial.");
		}else if(lname==""){
			$("#informer").html("Type your last name.");
		}else if(username==""){
			$("#informer").html("Type your username.");
		}else if(pword==""){
			$("#informer").html("Type your password.");
		}else if(vpword==""){
			$("#informer").html("Type your password verification.");
		}else if(address==""){
			$("#informer").html("Type your password address.");
		}else if(contact==""){
			$("#informer").html("Type your contact number.");
		}else if(email==""){
			$("#informer").html("Type your email.");
		}else if(gender==""){
			$("#informer").html("Select you gender.");
		}else if(bday==""){
			$("#informer").html("Select you birthday.");
		}else{
			$.post('php/VERSION 1/CONTROLLER/controller.php',
					{
					fname:fname,
					mi:mi,
					lname:lname,
					username:username,
					pword:pword,
					address:address,
					contact:contact,
					email:email,
					gender:gender,
					bday:bday
					},
					function(data){
					
					$("#informer").html(data);
					event.preventDefault();
			});
		}
		
		
	});
	$("#logout").click(function(){
		$.post('php/VERSION 1/CONTROLLER/controller.php',{logout:"logout"},function(){
			window.location.href="index.php";
		});
	});
	
	$("#btnDelSched").click(function(){
		
		var id = $("#schedId").val();
		
		if(id==""){
			alert("Please select an item on the list.");			
		}else{
			var c = confirm("This action is irreversible,do you wish to continue?");
			if(c)
			{
				$.post('php/VERSION 1/CONTROLLER/controller.php',{ScheduleIdDelete:id},function(data){
					alert(data);
						sendData("scheduling");
				});
			}
		}
		
	});
		
	$("#btnSchedulingFilter").click(function(){
		var home = $("#cmbSchedulingFilterHome").val();
		var location = $("#cmbSchedulingFilterLocation").val();
		var startDate = $("#txtSchedulingStartDateFilter").val();
		if(home == ""){
			alert("Please select a home to filter.");
		}else if(location == ""){
			alert("Please select a location to filter.");
		}
		else if(startDate == "")
		{
			alert("Please select a start date.")
		}
		else{
			alert("ok to filter");
		}
		
	});
});