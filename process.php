<?php
if(isset($_POST['cnames']))
{
	$conn = new mysqli("localhost", "root", "", "wproj");
	if($conn->connect_error)
	{
		die($conn->connect_error);
	}
	//$sql = "select distinct(custemer_name) from reservation where DATE(`check-in`) <= CURDATE() AND DATE(`check-out`)>=CURDATE();";
	$sql = "select distinct(custemer_name) from reservation";
	if($result = $conn->query($sql))
	{
		$rows = array();
		if($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				array_push($rows, $row["custemer_name"]);
			}
			echo json_encode($rows);
		}else
		{
			echo "no Guest today in the hotel";
		}
	}
	else
	{
		echo ("Sorry: Problem With selection ".mysqli_error($conn)); 
	}
}
// if(isset($_POST['object']))
// {
	// $object = json_decode($_POST['object'], true);
  // //Save In MySQL
  // $conn = new mysqli("localhost", "root", "", "wproj");
  // if($conn->connect_error){
 	// die($conn->connect_error);
  // }
  // $checkin = $object["checkin"];
  // $checkout = $object["checkout"];
  // $sql = "select room_number from reservation where check-in <='$checkin' AND check-out>='$checkin'  OR check-in <='$checkout' AND check-out>='$checkout';";
  // if($result = $conn->query($sql))
  // {
    // $rows = array();
   // if($result->num_rows > 0){
     // while($row = $result->fetch_assoc()){
      // array_push($rows, $row);
     // }
	 // echo json_encode($rows);
   // }else
   // {
	    // echo "all room are availabe in this appointment";
   // }
  // }else
  // {
	 // echo ("Sorry: Problem With selection ".mysqli_error($conn)); 
  // }
// }
if(isset($_POST['person'])){
  //Convert it to an Associative Array
  $person = json_decode($_POST['person'], true);
  //Save In MySQL
  $conn = new mysqli("localhost", "root", "", "wproj");
  if($conn->connect_error){
 	die($conn->connect_error);
  }
  //change the format of date 
  $checkin = date("Y-m-d", strtotime($person["checkin"])); 
  $checkout = date("Y-m-d", strtotime($person["checkout"]));
  
  $cname = $person["cname"];
  $credit = $person["credit"];
  $room = $person["room"];
  // check if a room is Reserved or not 
  $sql = "select * from reservation where room_number=$room";
  if($result = $conn->query($sql))
  {
    $rows = array();
   if($result->num_rows > 0){
     while($row = $result->fetch_assoc()){
      array_push($rows, $row);
     }
     $select=array();
	 //echo $checkin."\n";
	foreach($rows as $row)
	{
		
		//echo $row['check-in']."\n";
		if(($checkin >= $row['check-in'])&&($checkin <= $row['check-out']))
		{
			array_push($select, $row['room_number']);
		}else if($checkout >= $row['check-in'] && $checkout <= $row['check-out'])
		{
			array_push($select, $row['room_number']);
		}
	}
	if(count($select)==0)
	{
		$sql = "Insert Into reservation (`custemer_credit`,`custemer_name`,	`check-in`,`check-out`,`room_number`) values('$credit','$cname','$checkin', '$checkout', '$room')";
		$conn->query($sql);
		if($conn->affected_rows > 0){
			echo "The room was booked successfully";
		}
		else{
			echo ("Sorry: Problem With Insertion ".mysqli_error($conn));	
		}
	}else{
    //Convert to JSON Before Sending to Client
	echo json_encode($select);
	}
    }else{
		//$sql = "Insert Into reservation (`custemer_credit`,`custemer_name`,	`check-in`,`check-out`,`room_number`) values('$credit','$cname',STR_TO_DATE('$checkin', '%m/%d/%Y'), STR_TO_DATE('$checkout', '%m/%d/%Y') , '$room')";
		$sql = "Insert Into reservation (`custemer_credit`,`custemer_name`,	`check-in`,`check-out`,`room_number`) values('$credit','$cname','$checkin', '$checkout', '$room')";
		$conn->query($sql);
		if($conn->affected_rows > 0){
			echo "The room was booked successfully";
		}
		else{
			echo ("Sorry: Problem With Insertion ".mysqli_error($conn));	
		}
	}
  }
}
?>