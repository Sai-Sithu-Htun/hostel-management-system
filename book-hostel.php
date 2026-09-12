<?php
session_start();
// Prevent the browser from caching this page (incl. bfcache/back-forward cache),
// so a previous student's rendered form can never be shown to the next student.
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include('includes/config.php');
include('includes/checklogin.php');
include('getName.php');
check_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
}
//code for registration
$studentId = $_SESSION['id']; 

if (isset($_POST['applyRoom'])) {
    if (isset($_POST['floor']) && isset($_POST['position']) && isset($_SESSION['hostelID'])) {
        $floor = filter_var($_POST["floor"]);
        $position = filter_var($_POST["position"]);
        $hostelID = $_SESSION['hostelID'];
        $stmt = $mysqli->prepare("SELECT roomNo, roomID FROM rooms WHERE position = ? AND floor = ? AND hostelID = ?");
        $stmt->bind_param("ssi", $position, $floor, $hostelID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $_SESSION['room_id'] = $row['roomID'];
                $_SESSION['roomNo'] = $row['roomNo'];
            }
            // Check if stdid exists in the room
			$stmt2 = $mysqli->prepare("SELECT stdID FROM rooms WHERE roomID = ? AND stdID IS NOT NULL");
			$stmt2->bind_param("i", $_SESSION['room_id']);
			$stmt2->execute();
			$result2 = $stmt2->get_result();
			
			$studentId = $_SESSION['id']; 

$sql = "SELECT stayHostel FROM student WHERE studentID = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $studentId); // 'i' indicates the variable type is integer

$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$row = $result->fetch_assoc(); // fetch data   

$hostelID = $row['stayHostel']; // store stayHostel in a variable
  // Get the hostelID from the session

if ($hostelID == 4) {
    // If hostelID is 4, check only stdID
    $stmt2 = $mysqli->prepare("SELECT stdID FROM rooms WHERE roomID = ? AND stdID IS NOT NULL");
} else {
    // If hostelID is not 4, check both stdID and stdIdTwo
	$stmt2 = $mysqli->prepare("SELECT stdID, stdIdTwo FROM rooms WHERE roomID = ? AND (stdID IS NOT NULL AND stdIdTwo IS NOT NULL)");
}
	$stmt2->bind_param("i", $_SESSION['room_id']);
	$stmt2->execute();
	$result2 = $stmt2->get_result();
	
	if ($result2->num_rows > 0) {
		// Room is taken
		echo "<script type='text/javascript'>alert('Already Taken!');</script>";
		// Redirect to another page after 0.01 seconds
		header("refresh:0.01;url=dashboard.php");
		exit;
	}
	
			
            $stmt2->close();
        } else {
			$_SESSION['message'] = "This Room doesn't exist, you should choose the other floor at the same position or other room!";
			header("Location: dashboard.php");
			exit;
		}
		
        $stmt->close();
    }
}


if(isset($_POST['submit']))
{

$studentId = $_SESSION['id'];

// Fix 2: server-side check - a student must not already occupy any room
$dup_stmt = $mysqli->prepare("SELECT roomID FROM rooms WHERE stdID = ? OR stdIdTwo = ? LIMIT 1");
$dup_stmt->bind_param("ii", $studentId, $studentId);
$dup_stmt->execute();
$dup_res = $dup_stmt->get_result();
if ($dup_res->num_rows > 0) {
	$dup_stmt->close();
	echo "<script type='text/javascript'>alert('You have already booked a room!');</script>";
	header("refresh:0.1;url=myProfile.php");
	exit;
}
$dup_stmt->close();

$name=$_POST['name'];
$nrcNo=$_POST['nrcNo'];
$fatherName=$_POST['fatherName'];
$motherName=$_POST['motherName'];
$nationality=$_POST['nationality'];
$contactNo=$_POST['contactNo'];
$contactEmail=$_POST['contactEmail'];
$stayFrom=$_POST['stayFrom'];
$duration=$_POST['duration'];
$religion=$_POST['religion'];
$pAddress=$_POST['pAddress'];
$cAddress=$_POST['cAddress'];
$gName=$_POST['gName'];
$gRelation=$_POST['gRelation'];
$gContactNum=$_POST['gContactNum'];

$sql = "SELECT stayHostel FROM student WHERE studentID = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $studentId); // 'i' indicates the variable type is integer

$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$row = $result->fetch_assoc(); // fetch data   
$stayHostel = $row['stayHostel']; // store stayHostel in a variable
$stmt->close();

if ($stayHostel == 4) {
   $room_id=$_POST['roomID'];
} else {
	$room_id = $_SESSION['room_id'];
}

// Fix 3B: verify the selected room belongs to the student's hostel
$room_stmt = $mysqli->prepare("SELECT roomID FROM rooms WHERE roomID = ? AND hostelID = ?");
$room_stmt->bind_param("ii", $room_id, $stayHostel);
$room_stmt->execute();
$room_res = $room_stmt->get_result();
if ($room_res->num_rows == 0) {
	$room_stmt->close();
	echo "<script type='text/javascript'>alert('Invalid room selection!');</script>";
	header("refresh:0.1;url=dashboard.php");
	exit;
}
$room_stmt->close();

// Fix 3C: keep the room claim and application insertion consistent
$mysqli->autocommit(FALSE);

// Fix 3: claim a room slot atomically; the UPDATE itself carries the NULL condition
if ($stayHostel == 4) {
	// J-Hall rooms hold a single student
	$slot_stmt = $mysqli->prepare("UPDATE rooms SET stdID = ? WHERE roomID = ? AND stdID IS NULL");
	$slot_stmt->bind_param("ii", $studentId, $room_id);
	$slot_stmt->execute();
	$slotAffected = $mysqli->affected_rows;
	$slot_stmt->close();
	$roomFull = ($slotAffected == 0);
} else {
	// Hostels 1-3 rooms hold two students: first slot stdID, second slot stdIdTwo
	$slot_stmt = $mysqli->prepare("UPDATE rooms SET stdID = ? WHERE roomID = ? AND stdID IS NULL");
	$slot_stmt->bind_param("ii", $studentId, $room_id);
	$slot_stmt->execute();
	$slotAffected = $mysqli->affected_rows;
	$slot_stmt->close();
	if ($slotAffected == 0) {
		$slot_stmt = $mysqli->prepare("UPDATE rooms SET stdIdTwo = ? WHERE roomID = ? AND stdID IS NOT NULL AND stdIdTwo IS NULL");
		$slot_stmt->bind_param("ii", $studentId, $room_id);
		$slot_stmt->execute();
		$slotAffected = $mysqli->affected_rows;
		$slot_stmt->close();
	}
	$roomFull = ($slotAffected == 0);
}

if ($roomFull) {
	$mysqli->rollback();
	echo "<script type='text/javascript'>alert('Room is full or unavailable');</script>";
	header("refresh:0.1;url=dashboard.php");
	exit;
}

// Only upsert studentinformation after a room slot has been successfully claimed.
// id is the same logical ID as userregistration.userID (PRIMARY KEY, no AUTO_INCREMENT).
// ON DUPLICATE KEY UPDATE updates the existing row instead of fataling on a duplicate PK
// when the same student submits the booking form again.
$query="insert into studentinformation(id,Name,stayfrom,duration,nrcNo,FatherName,MotherName,Nationality,contactno,ContactEmail,religion,guardianName,guardianRelation,guardianContactno,corresAddress,pmntAddress) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) on duplicate key update Name=VALUES(Name),stayfrom=VALUES(stayfrom),duration=VALUES(duration),nrcNo=VALUES(nrcNo),FatherName=VALUES(FatherName),MotherName=VALUES(MotherName),Nationality=VALUES(Nationality),contactno=VALUES(contactno),ContactEmail=VALUES(ContactEmail),religion=VALUES(religion),guardianName=VALUES(guardianName),guardianRelation=VALUES(guardianRelation),guardianContactno=VALUES(guardianContactno),corresAddress=VALUES(corresAddress),pmntAddress=VALUES(pmntAddress)";
$stmt = $mysqli->prepare($query);
$rc=$stmt->bind_param('ississssissssiss',$studentId,$name,$stayFrom,$duration,$nrcNo,$fatherName,$motherName,$nationality,$contactNo,$contactEmail,$religion,$gName,$gRelation,$gContactNum,$cAddress,$pAddress);
$stmt->execute();
if ($stmt->errno) {
	$stmt->close();
	$mysqli->rollback();
	echo "<script type='text/javascript'>alert('Application could not be submitted, please try again!');</script>";
	header("refresh:0.1;url=book-hostel.php");
	exit;
}
$stmt->close();

$mysqli->commit();

// Fix 1: clear the room selection after a successful booking so it cannot be reused
unset($_SESSION['room_id']);
unset($_SESSION['roomNo']);

echo "<script type='text/javascript'>alert('Your form has been submitted!');</script>";

// Redirect to another page after 0.1 seconds
header("refresh:0.1;url=myProfile.php");
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
	<title>Student Hostel Registration</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
	<script type="text/javascript" src="js/sidebarPopUp.js"></script>
<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
<script type="text/javascript" src="js/validation.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>


<script>
function getSeater(val) {
$.ajax({
type: "POST",
url: "get_seater.php",
data:'roomid='+val,
success: function(data){
//alert(data);
$('#seater').val(data);
}
});

$.ajax({
type: "POST",
url: "get_seater.php",
data:'rid='+val,
success: function(data){
//alert(data);
$('#fpm').val(data);
}
});
}
</script>

</head>
<body>
	<?php include('includes/header.php');?>
	<div class="ts-main-content" style="margin: 20px;margin-top :70px;">
		<?php include('includes/sidebar.php');?>
	

				<div class="row">
					<div class="col-md-12">
					
						<h2 class="page-title">Room Application </h2>

						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-primary">
									<div class="panel-heading" style="font-size: x-large;color:red">Fill all Info!</div>
									<div class="panel-body">
										<form method="post" action="" class="form-horizontal" autocomplete="off">
										<?php echo csrf_field(); ?>
							<?php
$uid=$_SESSION['id'];
							 $stmt=$mysqli->prepare("SELECT roomID FROM rooms WHERE stdID = ? OR stdIdTwo = ? LIMIT 1 ");
				$stmt->bind_param('ii',$uid,$uid);
				$stmt->execute();
				$stmt -> bind_result($uid);
				$rs=$stmt->fetch();
				$stmt->close();
				if($rs)
				{ ?>
			<h3 style="color: red" allign="left">Room already booked by you!</h3>
				<?php }
				else{
							echo "";
							}			
							?>		


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
    include("RoomChoose.php");
} else {
    include("RoomAutoChoose.php");
}
?>

<div class="form-group">
<label class="col-sm-2 control-label">Stay From</label>
<div class="col-sm-8">
<input type="date" name="stayFrom" id="stayFrom"  class="form-control" required>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Duration</label>
<div class="col-sm-8">
<select name="duration" id="duration" class="form-control" required>
<option value="">Select Duration in Month</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
</select>
</div>
</div>


<div class="form-group">
<label class="col-sm-2 control-label"><h4 style="color: green" allign="left">Personal info </h4> </label>
</div>



<?php	
$aid=$_SESSION['id'];
$ret="SELECT userregistration.*, student.*
FROM userregistration
INNER JOIN student ON userregistration.userID = student.studentID
WHERE userregistration.userID=?";
		$stmt= $mysqli->prepare($ret) ;
	 $stmt->bind_param('i',$aid);
	 $stmt->execute() ;//ok
	 $res=$stmt->get_result();
	 //$cnt=1;
	   while($row=$res->fetch_object())
	  {
	  	?>



<div class="form-group">
<label class="col-sm-2 control-label">Name</label>
<div class="col-sm-8">
<input type="text" name="name" id="name"  class="form-control" value="<?php echo htmlspecialchars(($row->Name ?? ''), ENT_QUOTES, 'UTF-8');?>"  readonly>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Gender: </label>
<div class="col-sm-8">
<input type="text" name="gender" id="gender" value="<?php echo htmlspecialchars(($row->gender ?? ''), ENT_QUOTES, 'UTF-8');?>" class="form-control" readonly>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label">mkpt: </label>
<div class="col-sm-8">
<input type="text" name="mkpt" id="mkpt" value="<?php echo htmlspecialchars(($row->mkpt ?? ''), ENT_QUOTES, 'UTF-8');?>" class="form-control" readonly>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">NRC: </label>
<div class="col-sm-8">
<input type="text" name="nrcNo" id="nrcNo" pattern="^[0-9]{1,2}/[A-Za-z]{1,9}\(N\)[0-9]{6}$" title="Format: stateNo/districtName(N)sixNumbers" class="form-control" placeholder="Format: stateNo/districtName(N)sixNumbers" required>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">FatherName: </label>
<div class="col-sm-8">
<input type="text" name="fatherName" id="fatherName" class="form-control" pattern="[a-zA-Z\s]+" titile="Please enter only alphabets" required>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">MotherName: </label>
<div class="col-sm-8">
<input type="text" name="motherName" id="motherName" pattern="[a-zA-Z\s]+" title="Please enter only letters" class="form-control" required>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Nationality: </label>
<div class="col-sm-8">
<input type="text" name="nationality" id="nationality" pattern="[a-zA-Z]+" title="Please enter only letters" class="form-control" required>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Contact No: </label>
<div class="col-sm-8">
<input type="text" name="contactNo" id="contactNo" value="<?php echo htmlspecialchars(($row->contactNo ?? ''), ENT_QUOTES, 'UTF-8');?>"  class="form-control" readonly>
</div>
</div>


<div class="form-group">
<label class="col-sm-2 control-label">ContactEmail: </label>
<div class="col-sm-8">
<input type="email" name="contactEmail" id="contactEmail"  class="form-control" value="<?php echo htmlspecialchars(($row->email ?? ''), ENT_QUOTES, 'UTF-8');?>"  readonly>
</div>
</div>
<?php } ?>


<div class="form-group">
<label class="col-sm-2 control-label">Religion: </label>
<div class="col-sm-8">
<input type="text" name="religion" id="religion" pattern="[a-zA-Z\s]+" title="Please enter only letters " class="form-control" required="required">
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Guardian Name: </label>
<div class="col-sm-8">
<input type="text" name="gName" id="gName" pattern="[a-zA-Z\s]+" title="Please enter only letters"  class="form-control" required="required">
</div>
</div>


<div class="form-group">
<label class="col-sm-2 control-label">Guardian Relation: </label>
<div class="col-sm-8">
<input type="text" name="gRelation" id="gRelation" pattern="[a-zA-Z\s]+" title="Please enter only letters"  class="form-control" required="required">
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Guardian Contact no : </label>
<div class="col-sm-8">
<input type="text" name="gContactNum" id="gContactNum" pattern="09[0-9]{9}" title="Please enter a valid contact number starting with 09 followed by nine digits" class="form-control" placeholder="09---------" required="required">
</div>
</div>	

					

<div class="form-group">
<label class="col-sm-2 control-label" >Permanent Address: </label>
<div class="col-sm-8">
<input type="text" name="pAddress" id="pAddress"  class="form-control" required="required">
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label" >Correspondense Address:</label>
<div class="col-sm-8">
<input type="text" name="cAddress" id="cAddress"  class="form-control" required="required">
</div>
</div>


<div class="col-sm-6 col-sm-offset-4">
<?php
$uid=$_SESSION['id'];
							 $stmt=$mysqli->prepare("SELECT roomID FROM rooms WHERE stdID = ? OR stdIdTwo = ? LIMIT 1 ");
				$stmt->bind_param('ii',$uid,$uid);
				$stmt->execute();
				$stmt -> bind_result($uid);
				$rs=$stmt->fetch();
				$stmt->close();
				if(!$rs)
				{
					echo '<input type="submit" name="submit" Value="Apply Room" class="btn btn-primary">';  
				 }			
							?>	

</div>
</form>

									
								</div>
							</div>
						</div>
							</div>
						</div>
					</div>
				</div> 	
			</div>
		</div>
	</div>
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
</body>

	<script>
// If the browser restores this page from its back/forward cache, reload it so
// the form is always re-rendered fresh for the current session — a cached
// snapshot of a previous student's form can never be shown.
window.addEventListener('pageshow', function (event) {
    if (event.persisted) {
        window.location.reload();
    }
});
function checkAvailability() {
$("#loaderIcon").show();
jQuery.ajax({
url: "check_availability.php",
data:'roomno='+$("#room").val(),
type: "POST",
success:function(data){
$("#room-availability-status").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}
</script>


<script type="text/javascript">

$(document).ready(function() {
	$('#duration').keyup(function(){
		var fetch_dbid = $(this).val();
		$.ajax({
		type:'POST',
		url :"ins-amt.php?action=userid",
		data :{userinfo:fetch_dbid},
		success:function(data){
	    $('.result').val(data);
		}
		});
		

})});
</script>

</html>