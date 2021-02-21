<?php
$conn = new mysqli("localhost", "root", "", "wproj");
if($conn->connect_error){
	die($conn->connect_error);
}
$out=0;
if(isset($_POST['submit']))
{
	//echo "welcomeasssasaasas";
	$cname=$_POST['cname'];
	$room=$_POST['numofroom'];
	$ordername=$_POST['ordername'];
	$sql = "SELECT `id` FROM reservation WHERE custemer_name='$cname' and room_number='$room';";
	if($result = $conn->query($sql))
	{
		//echo "welcome";
	    if($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			$cid=$row["id"];
			//echo $cid;
			$sql = "INSERT into orders(`cid`,`orderName`,`orderPrice`,`time`) VALUES('$cid','$ordername',20,now());";
			$conn->query($sql);
			if($conn->affected_rows > 0){
				$out=2;
			}
			else{
				echo ("Sorry: Problem With Insertion ".mysqli_error($conn));	
			}
									
	   }else{
		   //echo "welcome2";
		    $out=1;
		}
	}else
	{
		echo ("Sorry: Problem With selection ".mysqli_error($conn)); 
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<title>Booking Form HTML Template</title>

	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700" rel="stylesheet">

	<!-- Bootstrap -->
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" />

	<!-- Custom stlylesheet -->
	<link type="text/css" rel="stylesheet" href="css/style.css" />
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

</head>

<body>
	<div id="booking" class="section">
		<div class="section-center">
			<div class="container">
				<div class="row">
					<div class="booking-form">
						<form method="post" name="form">
							<div class="form-group">
								<span class="form-label">Customer Name</span>
								<select class="form-control" name="cname" required>
								
								<?php
									$sql = "select distinct(custemer_name) from reservation where DATE(`check-in`) <= CURDATE() AND DATE(`check-out`)>=CURDATE();";
									if($result = $conn->query($sql))
									{
									   $rows = array();
									   if($result->num_rows > 0){
										 while($row = $result->fetch_assoc())
										 {
											array_push($rows, $row);
										 }
										 echo "<option value='' selected hidden>Guest Name</option>";
										foreach($rows as $row)
										{
											echo "<option style='color:blue;'>".$row['custemer_name']."</option>";
										}
									   }else
									   {
										   echo "<option> NO Guest today in the hotel</option>";
									   }
									}else
									{
										echo ("Sorry: Problem With selection ".mysqli_error($conn)); 
									}
								?>
								</select>
								<span class="select-arrow"></span>
							</div>
							<div class="form-group">
								<span class="form-label">room Number</span>
								<select class="form-control" name="numofroom"required>
									<option value="" selected hidden>the Number of room</option>
									<?php
									$sql = "select distinct(room_number) from reservation where DATE(`check-in`) <= CURDATE() AND DATE(`check-out`)>=CURDATE();";
									if($result = $conn->query($sql))
									{
									   $rows = array();
									   if($result->num_rows > 0)
									   {   
										 while($row = $result->fetch_assoc())
										 {
											array_push($rows, $row);
										 }
										foreach($rows as $row)
										{
											echo "<option style='color:blue;' value=".$row['room_number'].">room ".$row['room_number']."</option>";
										}
									   }else
									   {
										   echo "<option> NO Guest today in the hotel</option>";
									   }
									}
								?>
								</select>
								<span class="select-arrow"></span>
							</div>
							<div class="form-group">
								<span class="form-label" >food Name</span>
								<select class="form-control" name="ordername" required>
									<option value="" selected hidden>	order Name</option>
									<option>meat</option>
									<option>chiken</option>
									<option>fish</option>
								</select>
								<span class="select-arrow"></span>
							</div>
							<div class="form-btn">
								<button class="submit-btn" name="submit" value='order' id="request">request Now</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<footer id="output" style='color:red;font-weight:bolder;text-align:center'>
			<?php
				if($out==1)
				{
					echo "<br><br><div class='alert alert-danger' style='font-size:20px'><strong>Error!</strong>in input please make sure from registeration of this Guest.</div>";
				}else if($out==2)
				{
					echo "<br><br><div class='alert alert-success' style='font-size:20px'><strong>the order is done</strong></div>";
				}
			?>

		</div>
	</div>
	
	<!--<script src="js/jquery-3.3.1.min.js"></script>
	<script src="process.js"></script>-->
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>