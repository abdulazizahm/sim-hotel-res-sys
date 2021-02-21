
<!------ Include the above in your HEAD tag ---------->
<?php
$conn = new mysqli("localhost", "root", "", "wproj");
if($conn->connect_error){
	die($conn->connect_error);
}
$out=1;
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Awesome Search Box</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="styles/bootstrap-4.1.2/bootstrap.min.css">
	<link rel="stylesheet" href="styles/sty.css">
	<link rel="stylesheet" type="text/css" href="styles/css.css">
	<style>
		
.autocomplete-items {
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}
.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}
.autocomplete-items div:hover {
  /*when hovering an item:*/
  background-color: #e9e9e9; 
}
.autocomplete-active {
  /*when navigating through the items using the arrow keys:*/
  background-color: DodgerBlue !important; 
  color: #ffffff; 
}
	</style>
  </head>
  <!-- Coded with love by Mutiullah Samim-->
  <body>
    <div class="container h-100">
	<form method="post" id="form">
      <div class="d-flex justify-content-center h-100">
        <div class="searchbar">
				<input class="search_input" id="myInput" type="text" name="customername" placeholder="Customer Name">
				<a href="#" class="search_icon"><i class="fas fa-search"></i></a>
				<input type="hidden" name="check" value="1">
        </div>
      </div>
	<form>
<?php
if(!empty($_POST))
{
	if($_POST["check"]=="1")
	{
		$GLOBALS['out']=0;
	}
	$cname=$_POST["customername"];
	$sql = "SELECT * FROM reservation WHERE custemer_name='$cname';";
	if($result = $conn->query($sql))
	{
		//echo "welcome";
	    if($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			$cid=$row["id"];
			$credit=$row["custemer_credit"];
			$checkin=$row["check-in"];
			$checkout=$row["check-out"];
			$room=$row["room_number"];
			$now = strtotime($checkout); // or your date as well
			$your_date = strtotime($checkin);
			$datediff = $now - $your_date;
			$nights= $datediff / (60 * 60 * 24);
			//echo $cid;			
			$sql = "SELECT * FROM orders WHERE cid='$cid';";
			if($result = $conn->query($sql))
			{
				$rows = array();
				 if($result->num_rows > 0)
				 {
					 while($row = $result->fetch_assoc())
					 {
						 array_push($rows, $row);
					 }
				 }else{}
				
			}else
			{
				echo ("Sorry: Problem With selection ".mysqli_error($conn)); 
			}
	   }else{
		   //echo "welcome2";
		    
		}
	}else
	{
		echo ("Sorry: Problem With selection ".mysqli_error($conn)); 
	}
	
}	
?>
	<div id="invoice" style="<?php if($out==1){echo "display:none";}else{}?>">
    <div class="toolbar hidden-print">
        <div class="text-right">
            <button id="printInvoice" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
            <button class="btn btn-info"><i class="fa fa-file-pdf-o"></i> Export as PDF</button>
        </div>
        <hr>
    </div>
    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            <header>
                <div class="row">
                    <div class="col">
                        <a target="_blank" href="#">
                            <img src="images/lobiadmin-logo-text-64.png" data-holder-rendered="true" />
                            </a>
                    </div>
                    <div class="col company-details">
                        <h2 class="name">
                            <a target="_blank" href="#">
                            The River
                            </a>
                        </h2>
                        <div>455 Foggy Heights, AZ 85004, US</div>
                        <div>0183-12345678</div>
                        <div>river_hotel@gamil.com</div>
                    </div>
                </div>
            </header>
            <main>
                <div class="row contacts">
                    <div class="col invoice-to">
                        <div class="text-gray-light">INVOICE TO:</div>
                        <h2 class="to" id="cname"><?php echo $cname;?></h2>
                        <div class="address">credit number : <span id="credit"><?php echo $credit;?></span></div>
                        <div class="email" >the number of room : <span id="room_number"><?php echo $room;?></span></div>
                        <div class="email" > room price for one night = $200 </div>
                    </div>
                    <div class="col invoice-details">
                        <h1 class="invoice-id">INVOICE <span id="cid"><?php echo $cid;?> </span></h1>
                        <div class="date" >Date of Invoice : <span id="check-in"><?php echo $checkin;?></span></div>
                        <div class="date" id="check-out">Due Date : <span id="check-in"><?php echo $checkout; ?></span></div>
                        <div class="date" id="check-out">the number of days : <span id="check-in"><?php echo $nights; ?></span></div>
                    </div>
                </div>
				<div class="table-responsible">
                <table border="0" cellspacing="0" cellpadding="0" class=>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-left">order name</th>
							<th class="text-right">the time of order</th>
                            <th class="text-right">order price</th>
                        </tr>
                    </thead>
                    <tbody>
					<?php
					$htotal=$nights*200;
					$rtotal=0;
					foreach($rows as $row)
					{
						echo "<tr>";
						echo "<td class='no'><span id='id'>".$row["id"]."</span></td>";
						echo "<td class='text-left'><h3 id='ordername'>".$row["orderName"]."</h3></td>";
						echo "<td class='unit' id='time'>".$row["time"]."</td>";
						echo "<td class='qty' id='orderprice'>".$row["orderPrice"]."</td><tr>";
						$rtotal+=$row["orderPrice"];
					}
					if(count($rows==0))
					{
						echo "<tr>";
						echo "<td colspan='4' style='text-align:center;color:red;'>NO Orders from restaurant</td>";
						echo "</tr>";
						
					}
					
					
					
					?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td > TOTAL restraunt</td>
                            <td ><?php echo $rtotal;?></td>
                        </tr>
						<tr>
                            <td colspan="2"></td>
                            <td >TOTAL roombooking</td>
                            <td ><?php echo $htotal;?></td>
                        </tr>
						<tr>
                            <td colspan="2"></td>
                            <td >Total Reservation</td>
                            <td ><?php echo $rtotal+$htotal;?></td>
                        </tr>
                    </tfoot>
                </table>
				</div>
            </main>
            <footer>
                Invoice was created on a computer and is valid without the signature and seal.
            </footer>
        </div>
        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
        <div></div>
    </div>
</div>
    </div>
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="styles/bootstrap-4.1.2/bootstrap.min.js"></script>

	<script>
	$.ajax({
		"type": "POST",
		"url": "process.php",
		"data": {"cnames":""},
		"success": function(response)
		{
			
			console.log(response);
			console.log(typeof(response));
			response=response.replace(/\[/g, '');
			response=response.replace(/\]/g, '');
			response=response.replace(/\"/g, '');
			var countries= response.split(",");
			console.log(countries);
			autocomplete(document.getElementById("myInput"),countries);
			
		}
	});
  function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
              b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
      x[i].parentNode.removeChild(x[i]);
    }
  }
}
/*execute a function when someone clicks in the document:*/
document.addEventListener("click", function (e) {
    closeAllLists(e.target);
});
}
$(".search_icon").click(function(e)
{
	if(($("#myInput").val()).length==0)
	{
		alert("please enter the name of customer");
	}
	else
	{
		alert("submit");
		$('#form').submit();
	}
});

	</script>
  </body>
</html>