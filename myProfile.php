<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
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
	<title>Profile</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
	<script type="text/javascript" src="js/sidebarPopUp.js"></script>

<style>
	#tabblee td,tr{
		border-color: black;
	}
	.head {
    background-color:#dbf7ff;
	
    

    color: red;
}
.name {
  
	color: blue;
}
.info {
    background-color: #ffffd9;
    color: black;
}

</style>
</head>

<body>
	<?php include('includes/header.php');?>

	<div class="ts-main-content col-md-12">
			<?php include('includes/sidebar.php');?>
	
				
					<div class="col-md-12" style="margin-top: 80px;">
						<h2 class="page-title">Profile</h2>
						<div class="panel panel-default">
							<div class="panel-heading">profile Details</div>
							<div class="panel-body">
								<table id="zctb" class="table table-bordered " cellspacing="0" width="100%">
									
									
									<tbody>
									<?php	
$aid = $_SESSION['id'];

// Select from rooms table
$ret_rooms = "SELECT * FROM rooms WHERE stdID = ? OR stdIdTwo = ?";
$stmt_rooms = $mysqli->prepare($ret_rooms);
$stmt_rooms->bind_param('ss', $aid, $aid);
$stmt_rooms->execute();
$res_rooms = $stmt_rooms->get_result();
// Select from studentinformation table
$ret_studentinfo = "SELECT * FROM studentinformation WHERE id = ?";
$stmt_studentinfo = $mysqli->prepare($ret_studentinfo);
$stmt_studentinfo->bind_param('s', $aid);
$stmt_studentinfo->execute();
$res_studentinfo = $stmt_studentinfo->get_result();

$ret_A = "SELECT * FROM userregistration WHERE userID = ?";
$stmt_A = $mysqli->prepare($ret_A);
$stmt_A->bind_param('s', $aid);
$stmt_A->execute();
$res_A = $stmt_A->get_result();

while (($row_rooms = $res_rooms->fetch_object()) && ($row_studentinfo = $res_studentinfo->fetch_object()) && ($row_A=$res_A->fetch_object())) {
	?>

<tr>
<td colspan="6" class="head"><h4 style="font-weight:bolder">Room Related Info</h4></td>
</tr>




<tr>
<td class="name"><b>Room ID :</b></td>
<td><?php echo htmlspecialchars(($row_rooms->roomID ?? ''), ENT_QUOTES, 'UTF-8');?></td>
<td class="name"><b>Room No :</b></td>
<td><?php echo htmlspecialchars(($row_rooms->roomNo ?? ''), ENT_QUOTES, 'UTF-8');?></td>
	  </tr>

<tr>

<td class="name"><b>Stay From :</b></td>
<td><?php echo htmlspecialchars(($row_studentinfo->stayfrom ?? ''), ENT_QUOTES, 'UTF-8');?></td>
<td class="name"><b>Duration:</b></td>
<td><?php echo htmlspecialchars(($row_studentinfo->duration ?? ''), ENT_QUOTES, 'UTF-8');?> Months</td>
</tr>

<tr><td colspan="6" style="line-height:10px; border:none"></td></tr>

<tr>
<td colspan="6" class="head"><h4 style="font-weight:bolder">Personal Info Info</h4></td>
</tr>
<tr>
<td class="name"><b>Full Name :</b></td>
<td><?php echo htmlspecialchars(($row_studentinfo->Name ?? ''), ENT_QUOTES, 'UTF-8');?></td>
<td class="name"><b>Email:</b></td>
<td><?php echo htmlspecialchars(($row_studentinfo->ContactEmail ?? ''), ENT_QUOTES, 'UTF-8');?></td>
</tr>

<tr>
<td class="name"><b>Contact No. :</b></td>
<td><?php echo htmlspecialchars(($row_studentinfo->contactno ?? ''), ENT_QUOTES, 'UTF-8');?></td>
<td class="name"><b>Gender :</b></td>
<td><?php echo htmlspecialchars(($row_A->gender ?? ''), ENT_QUOTES, 'UTF-8');?></td>
</tr>
<tr> </tr>

<tr>
<td class="name"><b>FatherName :</b></td>
<td><?php echo htmlspecialchars(($row_studentinfo->FatherName ?? ''), ENT_QUOTES, 'UTF-8');?></td>
<td class="name"><b>MotherName :</b></td>
<td><?php echo htmlspecialchars(($row_studentinfo->MotherName ?? ''), ENT_QUOTES, 'UTF-8');?></td>
</tr>

<tr>
<td class="name"><b>Nationality :</b></td>
<td ><?php echo htmlspecialchars(($row_studentinfo->Nationality ?? ''), ENT_QUOTES, 'UTF-8');?></td>
<td class="name"><b>Religion :</b></td>
<td ><?php echo htmlspecialchars(($row_studentinfo->religion ?? ''), ENT_QUOTES, 'UTF-8');?></td>
</tr>

<tr>
<td class="name"><b>Guardian Name:</b></td>
<td><?php echo htmlspecialchars(($row_studentinfo->guardianName ?? ''), ENT_QUOTES, 'UTF-8');?></td>
<td class="name"><b>Guardian Relation :</b></td>
<td><?php echo htmlspecialchars(($row_studentinfo->guardianRelation ?? ''), ENT_QUOTES, 'UTF-8');?></td>
</tr>


<tr>
<td class="name"><b>NRC No :</b></td>
<td ><?php echo htmlspecialchars(($row_studentinfo->nrcNo ?? ''), ENT_QUOTES, 'UTF-8');?></td>
<td class="name"><b>Guardian Contact No. :</b></td>
<td ><?php echo htmlspecialchars(($row_studentinfo->guardianContactno ?? ''), ENT_QUOTES, 'UTF-8');?></td>
</tr>


<tr><td colspan="6" style="line-height:10px;border:none"></td></tr>

<tr>
<td colspan="6" class="head"><h4 style="font-weight:bolder">Addresses</h4></td>
</tr>

<tr>
<td class="name"><b>Permanent Address</b></td>
<td colspan="6">
<?php echo htmlspecialchars(($row_studentinfo->pmntAddress ?? ''), ENT_QUOTES, 'UTF-8');?>
</td>
</tr>

<tr>
<td class="name"><b>Correspondense Address</b></td>
<td colspan="6">
<?php echo htmlspecialchars(($row_studentinfo->corresAddress ?? ''), ENT_QUOTES, 'UTF-8');?></td>
</tr>
<?php

} ?>
</tbody>
</table>


</div>
</div>
</div>
</div>

	<!-- Loading Scripts -->

	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
	<script src="sidebarPopUp.js"></script>

</body>

</html>
