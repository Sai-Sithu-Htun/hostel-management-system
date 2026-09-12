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
	<title>Admin Profile</title>
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









</head>
<body>
	<?php include('includes/header.php');?>
	<div class="ts-main-content" style="padding: 40px;">
		<?php include('includes/sidebar.php');?>


		

				<div class="row" style="margin-top: 40px;">




					<div class="col-md-12">
					
						<h2 class="page-title">Account Profile </h2>

						<?php
$aid = $_SESSION['id'];
$ret = "SELECT * FROM userregistration u JOIN admin a ON u.userID = a.adminID WHERE a.adminID = ?";
$stmt = $mysqli->prepare($ret);
$stmt->bind_param('i', $aid);
$stmt->execute();
$res = $stmt->get_result();
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$res) {
    echo "Fetch failed: (" . $stmt->errno . ") " . $stmt->error;
}

while($row=$res->fetch_object())
{
    // You can access the columns from both tables here


?>


						<div class="row">


							<div class="col-md-12">		

								<div class="panel panel-primary">
						<div class="panel-heading">

My Account:
</div>
									

<div class="panel-body">
<form method="post" action="" name="registration" class="form-horizontal" onSubmit="return valid();">
								
								




<div class="form-group">
<label class="col-sm-2 control-label">Name : </label>
<div class="col-sm-8">
<input type="text" name="name" id="name"  class="form-control" value="<?php echo htmlspecialchars(($row->Name ?? ''), ENT_QUOTES, 'UTF-8');?>"   required="required" readonly>
</div>
</div>



<div class="form-group">
<label class="col-sm-2 control-label">Department: </label>
<div class="col-sm-8">
<input type="text" name="department" id="department" value="<?php echo htmlspecialchars(($row->Department ?? ''), ENT_QUOTES, 'UTF-8');?>"   class="form-control" value="<?php
if(isset($row->Department)) {
    echo htmlspecialchars($row->Department, ENT_QUOTES, 'UTF-8');
}
?>
"   required="required" readonly>
</div>
</div>




<div class="form-group">
<label class="col-sm-2 control-label">Gender: </label>
<div class="col-sm-8">
<input type="text" name="gender" id="gender"  class="form-control" value="<?php echo htmlspecialchars(($row->gender ?? ''), ENT_QUOTES, 'UTF-8');?>" readonly>
</div>
</div>




<div class="form-group">
<label class="col-sm-2 control-label">Contact No : </label>
<div class="col-sm-8">
<input type="text" name="contact" id="contact"  class="form-control" maxlength="10" value="<?php echo htmlspecialchars(($row->contactNo ?? ''), ENT_QUOTES, 'UTF-8');?>" required="required" readonly>
</div>
</div>


<div class="form-group">
<label class="col-sm-2 control-label">Email: </label>
<div class="col-sm-8">
<input type="email" name="email" id="email"  class="form-control" value="<?php echo htmlspecialchars(($row->email ?? ''), ENT_QUOTES, 'UTF-8');?>" readonly>
<span id="user-availability-status" style="font-size:12px;"></span>
</div>
</div>

</form>

									</div>
									
								</div>
									<?php }  ?>







							
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

</html>