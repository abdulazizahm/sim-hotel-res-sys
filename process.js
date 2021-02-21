// $("#check-in").datepicker({ dateFormat: "yy-mm-dd" }).val();
// $("#check-out").datepicker({ dateFormat: "yy-mm-dd" }).val();
// $(function(){
    // $('#numofroom').trigger('change'); //This event will fire the change event. 
    // $('#numofroom').change(function(){
	// $checkin=$("#check-in").val();
	// $checkout=$("#check-out").val();
    // if($checkin.length==0 || $checkout.length==0)
	// {
		// $(".copyright").html("<div class='alert alert-danger;'><strong>please enter check-out and check-in first to check if room is avaiable.</strong></div>");
		// if($checkin.length==0)
		// {
		   // $("#numofroom").val('null');
		   // $("#check-in").focus();
		   // return;
		// }else if($checkout.length==0)
		// {
			// $("#numofroom").val('null');
			// $("#check-out").focus();
			// return;
		// }
	// }else
	// {
		// $(".copyright").html(" ");
	// }            
    // });
// });
// $("#check-out").on("change",function()
// {
    // console.log($("#check-in").val());
	// console.log($("#check-out").val());
	// $(".copyright").html(" ");
	// if(($("#check-in").val()).length!=0)
	// {
		// function Appointment(checkin,checkout){
			// this.checkin = checkin;
			// this.checkout=checkout;
			// }
		// var appointment = new Appointment($("#check-in").val(), $("#check-out").val());
		// console.log(appointment);
		// $.ajax({
			// "type": "POST",
			// "url": "process.php",
			// "data": {"object":JSON.stringify(appointment)},
			// "success": function(response)
			// {
				// console.log(response);
			// }
			// });
	// }
		
// });
// $("#check-in").on("change",function()
// {
	// $(".copyright").html(" ");
	// if(($("#check-out").val()).length!=0)
	// {
		// function Appointment(checkin,checkout){
			// this.checkin = checkin;
			// this.checkout=checkout;
			// }
		// var appointment = new Appointment($("#check-in").val(), $("#check-out").val());
		// console.log(appointment);
		// $.ajax({
			// "type": "POST",
			// "url": "process.php",
			// "data": {"object":JSON.stringify(appointment)},
			// "success": function(response)
			// {
				// console.log(response);
			// }
			// });
	// }
		
// });
$("button").click(function(e)
{
	e.preventDefault();
	var name=$("#name").val();
	name=name.trim();
	var ch=name.split(" ");
	var credit=$("#credit").val();
	var checkin=$("#check-in").val();
	var checkout=$("#check-out").val();
	var room=$("#numofroom").val()+"";
	var errors=new Array();
	// vaildation of request
	// first vaildation if all fields is filled
	if(checkin.length==0)
	{
		$(".copyright").html("<div class='alert alert-danger;'><strong>please fill this field</strong></div>");
		$("#check-in").focus();
		return;
	}
	if(checkout.length==0)
	{
		$(".copyright").html("<div class='alert alert-danger;'><strong>please fill this field</strong></div>");
		$("#check-out").focus();
		return;
	}
	if(name.length==0)
	{
		$(".copyright").html("<div class='alert alert-danger;'><strong>please fill this field</strong></div>");
		$("#name").focus();
		return;
	}
	if(credit.length==0)
	{
		$(".copyright").html("<div class='alert alert-danger;'><strong>please fill this field</strong></div>");
		$("#credit").focus();
		return;
	}
	if(room == "null")
	{
		$(".copyright").html("<div class='alert alert-danger;'><strong>please select the number of room</strong></div>");
		$("#numofroom").focus();
		return;
	}else if(checkout.length==0 || checkin.length==0) // valid on the data that enterd
	{
		$(".copyright").html("<div class='alert alert-danger;'><strong>please enter check-out and check-in first to check if room is avaiable.</strong></div>");
		if(checkin.length==0)
		{
		   $("#check-in").focus();
		   return;
		}else if(checkout.length==0)
		{
			$("#check-out").focus();
			return;
		}
	}
	if((ch.length)<3)
	{
		
		//$(".copyright").html("<div class='alert alert-danger;'><strong>Customer Name must be triple</strong></div>");
		errors.push('Customer Name must be triple');
		$("#name").focus();
	}
	if( $("#check-in").val()>=$("#check-out").val() )
	{
		errors.push("please enter vaild duration for your registration");
		$("#check-out").focus();
	}
	//alert(errors.length);
	// display errors
	if((errors.length)>0)
	{
		var s="";
		for(var error in errors)
		{
			s=s+"<div class='alert alert-danger;'><strong>"+errors[error]+"</strong></div><br>";
		}
		$(".copyright").html(s);
	}else // send request by using json and jquery 
	{
		function Client(checkin,checkout, cname, credit,room){
		this.checkin = checkin;
		this.cname = cname;
		this.credit = credit;
		this.room=room;
		this.checkout=checkout;
		}
		var arr = $("#booking_form").serializeArray();
		console.log(arr);
		var client = new Client(arr[0].value, arr[1].value, arr[2].value, arr[3].value, arr[4].value);
		console.log(client);
		$.ajax({
		"type": "POST",
		"url": "process.php",
		"data": {"person":JSON.stringify(client)},
		"success": function(response)
		{
			console.log(response);
			if(response.includes("The room was booked successfully"))
			{
				$(".copyright").html("<span style='color:green;font-weight:bolder;'><i>"+response+"</i><green>");
			}
			else
			{
				$(".copyright").html("<div class='alert alert-success;'><strong>this room is already already registered in this appointment please select available room</strong></div>");
			}
		}
		});
	}
});
$("#request").click(function(e){
	
});