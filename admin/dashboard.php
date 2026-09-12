<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
include('getName.php');
check_login();



if (isset($_GET['hostelID'])) {
  $requested = intval($_GET['hostelID']);
  $vh = $mysqli->prepare("SELECT hostelName FROM hostel WHERE hostelID = ?");
  $vh->bind_param("i", $requested);
  $vh->execute();
  $vh->store_result();
  if ($vh->num_rows === 1) {
    $_SESSION['hostelID'] = $requested;
  }
  $vh->close();
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

	<div class="ts-main-content" style="padding: 20px;margin-right:0px">
		<?php include("includes/sidebar.php");?>
		
					
						<h2 class="page-title">Dashboard &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlspecialchars(($_SESSION['hostelName'] ?? ''), ENT_QUOTES, 'UTF-8');?></h2>
            <div class="col-md-12" >
<div class="col-md-6">
										<div class="panel panel-default">
											<div class="panel-body bk-primary text-light">
												<div class="stat-panel text-center">
                                                <?php
if (isset($_GET['hostelID'])) {
    $requested = intval($_GET['hostelID']);
    $vh = $mysqli->prepare("SELECT hostelName FROM hostel WHERE hostelID = ?");
    $vh->bind_param("i", $requested);
    $vh->execute();
    $vh->store_result();
    if ($vh->num_rows === 1) {
        $_SESSION['hostelID'] = $requested;
    }
    $vh->close();
    $hostelID = $_SESSION['hostelID'];
}
$stmt = $mysqli->prepare("SELECT SUM(CASE WHEN stdID IS NOT NULL THEN 1 ELSE 0 END) + SUM(CASE WHEN stdIdTwo IS NOT NULL THEN 1 ELSE 0 END) FROM rooms WHERE hostelID = ?");
$stmt->bind_param("i", $hostelID);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();
?>

													<div class="stat-panel-number h1 "><?php echo $count;?></div>
													<div class="stat-panel-title text-uppercase"> Students</div>
												</div>
											</div>
										</div>
  </div>

  <div class="col-md-6">
										<div class="panel panel-default">
											<div class="panel-body bk-success text-light">
												<div class="stat-panel text-center">
<?php
	$hostelID = $_SESSION['hostelID'];
 $stmt = $mysqli->prepare("SELECT count(*) FROM rooms WHERE hostelID = ?");
 $stmt->bind_param("i", $hostelID);
 $stmt->execute();
 $stmt->bind_result($count1);
 $stmt->fetch();
 $stmt->close();
?>
													<div class="stat-panel-number h1 "><?php echo $count1;?></div>
													<div class="stat-panel-title text-uppercase">Total 
<?php
$hostel=$_SESSION['hostelID'];
if ($hostel == 4) {
   echo "Beds";
} else {
    echo "Rooms";
}
?>
 </div>
												</div>
											</div>
											
									
									</div>
						</div>
</div>
           

<?php
$hostel=$_SESSION['hostelID'];
if ($hostel == 4) {
    include("testphp.php");
} else {
    include("Modal.php");
}
?>


<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
<script src="js/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // Attach a click event handler to each <rect>
    for (let i = 1; i <= 84; i++) {
        $('#' + i).click(function() { 
            $.ajax({
                url: 'checkStudent.php', // Replace with the path to your PHP script
                type: 'POST',
                data: { posi: i },
                success: function(response) {
    console.log(response); // This will log the response to the console
    $("#room-availability-status").html(response); // This will display the response in the span
}

            });
        });
    }
});

</script>







							<div id="photoSlide" class="col-md-12" style="margin-top: 10px;">

              <div class="textBox">
          <p>
          Hostel Registration system is a web based booking platform for education hostel booking online. 
<br><br>The system allows 
students to easily select and book a room without having to visit in person just by having an internet connection. 
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
            <?php $hostel=$_SESSION['hostelID'];
if ($hostel == 4) {
    include("../phpslideF.php");
} 
 if ( $hostel==3){
    include("../phpslideTh.php");
}
if ( $hostel==2){
    include("../phpslideT.php");
}
if ( $hostel==1){
    include("../phpslideO.php");
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
            $hostel = $_SESSION['hostelID']; // Get the student id from session

            $query = "SELECT messages.*, userregistration.Name, userregistration.role FROM messages JOIN userregistration ON messages.msgSender = userregistration.userID WHERE messages.hostel = ?"; // Add the condition to the query
            $stmt2 = $mysqli->prepare($query);
            $stmt2->bind_param("s", $hostel); // Bind the stayHostel to the query
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
  <!-- Add a delete button -->
  <form method="post" action="sendMessage.php" style="display: none; position: absolute; top: 0; right: 0;" class="delete-button">
    <button type="submit" name="delete" value="<?php echo htmlspecialchars(($row->id ?? ''), ENT_QUOTES, 'UTF-8'); ?>"><i class="fa fa-trash"></i></button>
    <?php echo csrf_field(); ?>
  </form>
</li>



            <?php
            }
            ?>
            </ul>
        </div>
        <form method="post" action="sendMessage.php" name="messages">
            <?php echo csrf_field(); ?>
            <div class="col-md-11" style="display: flex; justify-content: space-between;">
                <input type="text" class="form-control" name="message" id="message" style=" height: 50px;" placeholder="Send Messages" autocomplete="off" required>
                <input type="submit" class="btn btn-primary" value="submit✉" name="submit" id="submit" style="padding: 10px; margin-left:10px;font-size:large" >
            </div>
        </form>
        <form method="post" action="sendMessage.php" name="messages" style="position: absolute; bottom: 30px; right:15px;" onsubmit="return confirm('Are you sure?');">
    <?php echo csrf_field(); ?>
    <input type="submit" class="btn btn-danger" value="Clear All" name="clear" id="clear" style="padding: 13px; margin-left:10px;font-size:large" >
</form>

    </div>
</div>


<div class="col-md-10" style="margin:30px;padding:30px; border: 1px solid black;background-image: url(img/rulebak.jpg);background-size:cover;height:auto">
    <div style="width:auto;height: auto; background: linear-gradient(to bottom,rgba(252, 214, 150, 0.871), rgba(255, 255, 255, 0.655));">
        <div>
            <ul style="
               
                padding: 10px; 
                margin-top:10px;
                margin-bottom: 10px;
               
                min-height: 244px;">
            <?php
            $query = "SELECT rules.id, rules.rules FROM rules";
            $stmt2 = $mysqli->prepare($query);
            $stmt2->execute();
            $res = $stmt2->get_result();
            while($row = $res->fetch_object())
            {
            ?>
                <li>
    <i class="fa fa-star"></i>
                  <?php echo htmlspecialchars(($row->rules ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                <form method="post" action="setRules.php" style="display: inline;">
                    <button type="submit" name="delete" value="<?php echo htmlspecialchars(($row->id ?? ''), ENT_QUOTES, 'UTF-8'); ?>"><i class="fa fa-trash"></i></button>
                    <?php echo csrf_field(); ?>
                </form>
                </li>
            <?php
            }
            ?>
            </ul>
        </div>
        <form method="post" action="setRules.php" name="rules"  onSubmit="return valid();">
            <?php echo csrf_field(); ?>
            <div class="col-md-11" style="display: flex; justify-content: space-between;">
                <input type="text" class="form-control" name="rules" id="rules" style=" height: 50px;" placeholder="Set Rules!" required>
                <input type="submit" class="btn btn-primary" value="submit" name="submit" id="submit" style="padding: 10px; margin-left:10px;font-size:large" >
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
    <script src="js/jquery-1.11.3-jquery.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
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
