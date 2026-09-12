<?php
session_start();
// Prevent the browser from caching this page (incl. bfcache/back-forward cache),
// so a previous student's rendered page can never be shown to the next student.
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include('includes/config.php');
include('includes/checklogin.php');
include('getName.php');
check_login();

if (isset($_SESSION['message'])) {
  $_SESSION['display_message'] = $_SESSION['message'];
  unset($_SESSION['message']);  // Unset the message after storing it in a different session variable
  header("Location: dashboard.php");
  exit;
}


?>





<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="theme-color" content="#3e454c">

	<title>DashBoard</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/dashboard.css">

 


	



</head>

<body style="margin-top: 50px;">
<?php include("includes/header.php");?>

	<div class="ts-main-content" style="padding: 20px;">
		<?php include("includes/sidebar.php");?>
		




    <?php

$studentId = $_SESSION['id']; // assuming 'id' is the session variable holding the student id

$sql = "SELECT stayHostel FROM student WHERE studentID = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $studentId); // 'i' indicates the variable type is integer

$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$row = $result->fetch_assoc(); // fetch data   

$stayHostel = $row['stayHostel']; // store stayHostel in a variable



if ($stayHostel == 4) {
    include("ApplyBtn.php");
} else {
    include("Modal.php");
}
?>

				

<?php if (isset($_SESSION['display_message'])): ?>
    <span id="message-span" style="font-size:20px; font-weight:bold; color:red;">
        <?php echo htmlspecialchars(($_SESSION['display_message'] ?? ''), ENT_QUOTES, 'UTF-8'); unset($_SESSION['display_message']); // Display the message and unset it ?>
    </span>
<?php endif; ?>








</div>










							<div id="photoSlide"  class="col-md-12">

              <div class="textBox">
          <p>
          Hostel Registration system is a web based booking platform for education hostel booking online. 
<br><br>The system allows students to easily select and book a room without having to visit in person just by having an internet connection. 
<br><br>The system makes effortless understanding of student needs for administrators, easily displaying rules for students and manages room placement conveniently.
<br><br>This hostel registration system represents a significant advancement in booking room process.
<br>
<br>Using this system, room placement and room selection can be done in a time-saving manner.
<br>
<br>Admin and students can communicate by sending messages.
<br>
<br>It is very easy too use and less time consuming.


          </p>
            </div>
            <?php

$studentId = $_SESSION['id']; 

$sql = "SELECT stayHostel FROM student WHERE studentID = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $studentId); 

$stmt->execute();
$result = $stmt->get_result(); 
$row = $result->fetch_assoc(); 
$stayHostel = $row['stayHostel'];

if ($stayHostel == 4) {
    include("phpslideF.php");
} 
 if ( $stayHostel==3){
    include("phpslideTh.php");
}
if ( $stayHostel==2){
    include("phpslideT.php");
}
if ( $stayHostel==1){
    include("phpslideO.php");
}
	?>
           

						</div>
                        <div class="col-md-12" style="margin:30px;padding:30px; border: 1px solid black;background-image: url(img/DashBak.jpg);background-size:cover;width :900px;height:auto">
    <div style="width:auto;height: auto; background: linear-gradient(to bottom, rgba(105, 233, 255, 0.855), rgba(255, 255, 255, 0.655));">
        <div>
            <ul style="
               
                padding: 10px; 
                margin-top:10px;
                margin-bottom: 10px;
                min-width:400px;
                min-height: 244px;">
            <?php
            $studentId = $_SESSION['id']; // Get the student id from session

            $sql = "SELECT stayHostel FROM student WHERE studentID = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("i", $studentId); // 'i' indicates the variable type is integer

            $stmt->execute();
            $result = $stmt->get_result(); // get the mysqli result
            $row = $result->fetch_assoc(); // fetch data   

            $stayHostel = $row['stayHostel']; // store stayHostel in a variable

            $query = "SELECT messages.*, userregistration.Name, userregistration.role FROM messages JOIN userregistration ON messages.msgSender = userregistration.userID WHERE messages.hostel = ?"; // Add the condition to the query
            $stmt2 = $mysqli->prepare($query);
            $stmt2->bind_param("s", $stayHostel); // Bind the stayHostel to the query
            $stmt2->execute();
            $res = $stmt2->get_result();
            while($row = $res->fetch_object())
            {
                $color = ($row->role == 'admin') ? 'red' : 'blue';
                $icon = ($row->role == 'admin') ? 'fa fa-user-secret' : 'fa fa-user';
            ?>
               <li style="position: relative;">
  <i class="<?php echo $icon; ?>"> </i>
  <span style="color: <?php echo $color; ?>;"><?php echo '('. htmlspecialchars(($row->Name ?? ''), ENT_QUOTES, 'UTF-8').')'; ?></span> <?php echo htmlspecialchars(($row->msg ?? ''), ENT_QUOTES, 'UTF-8'); ?>
  <!-- Add a delete button, only shown for the student's own message -->
  <?php if ($row->msgSender == $_SESSION['id']): ?>
  <form method="post" action="sendMessage.php" style="display: none; position: absolute; top: 0; right: 0;" class="delete-button">
    <button type="submit" name="delete" value="<?php echo htmlspecialchars(($row->id ?? ''), ENT_QUOTES, 'UTF-8'); ?>"><i class="fa fa-trash"></i></button>
    <?php echo csrf_field(); ?>
  </form>
  <?php endif; ?>
</li>
            <?php
            }
            ?>
            </ul>
        </div>
        <form method="post" action="sendMessage.php" name="messages">
            <?php echo csrf_field(); ?>
            <div class="col-md-11" style="display: flex; justify-content: space-between;">
                <input type="text" class="form-control" name="message" id="message" style=" height: 50px;" placeholder="Send Message!" autocomplete="off" required>
                <input type="submit" class="btn btn-primary" value="submit✉" name="submit" id="submit" style="padding: 10px; margin-left:10px;font-size:large" >
            </div>
        </form>
    </div>
</div>


<div class="col-md-10" style="margin:30px;padding:30px; border: 1px solid black;background-image: url(img/rulebak.jpg);background-size:cover;height:auto">
    <div style="width:auto;height: auto; background: linear-gradient(to bottom,rgba(252, 214, 150, 0.871), rgba(255, 255, 255, 0.655));">
        <form method="post" action="setRules.php" name="messages"  onSubmit="return valid();">
            <div>
                <ul style="
                  
                    padding: 10px; 
                    margin-top:10px;
                    margin-bottom: 10px;
                    
                    min-height: 244px;">
                <?php
                $query = "SELECT rules.rules FROM rules";
                $stmt2 = $mysqli->prepare($query);
                $stmt2->execute();
                $res = $stmt2->get_result();
                while($row = $res->fetch_object())
                {
                ?>
                    <li>
<i class="fa fa-star"></i>
                      <?php echo htmlspecialchars(($row->rules ?? ''), ENT_QUOTES, 'UTF-8'); ?>
 </li>
                <?php
                }
                ?>
                </ul>
            </div>
            
        </form>
    </div>
</div>


						</div>





				

		
		
	

	<!-- Loading Scripts -->
  <script type="text/javascript" src="js/sidebarPopUp.js"></script>
	
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
 
	<script>

	window.onload = function(){

		// Line chart from swirlData for dashReport
		var ctx = document.getElementById("dashReport").getContext("2d");
		window.myLine = new Chart(ctx).Line(swirlData, {
			responsive: true,
			scaleShowVerticalLines: false,
			scaleBeginAtZero : true,
			multiTooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
		});

		// Pie Chart from doughutData
		var doctx = document.getElementById("chart-area3").getContext("2d");
		window.myDoughnut = new Chart(doctx).Pie(doughnutData, {responsive : true});

		// Dougnut Chart from doughnutData
		var doctx = document.getElementById("chart-area4").getContext("2d");
		window.myDoughnut = new Chart(doctx).Doughnut(doughnutData, {responsive : true});

	}
	</script>
      <script>
  var listItems = document.getElementsByTagName("li");
  for (var i = 0; i < listItems.length; i++) {
    listItems[i].addEventListener("mouseover", function() {
      this.getElementsByClassName('delete-button')[0].style.display = "block";
    });
    listItems[i].addEventListener("mouseout", function() {
      this.getElementsByClassName('delete-button')[0].style.display = "none";
    });
  }
  
</script>

</div>

  <footer class="col-md-12" style="border: 1.5px solid;padding:30px;font-size:larger;display: flex; justify-content: center; align-items: center;background-color: black;color:white">
  <div >
    <i class="fa fa-facebook-official "></i>
    <i class="fa fa-instagram "></i>
    <i class="fa fa-snapchat "></i>
    <i class="fa fa-pinterest-p"></i>
    <i class="fa fa-twitter "></i>
    <i class="fa fa-linkedin "></i><br>
  <i class="fa fa-copyright ">sensitive</i>
</div>
</footer>


</body>




<style> .foot{text-align: center; border: 1px solid black;padding-top: 20px;}</style>

</html>
