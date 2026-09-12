<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
}

if(isset($_POST['update']))
{
    $aid=$_SESSION['id'];
    $Name=$_POST['name'];
    $department=$_POST['department'];
    $gender=$_POST['gender'];
    $contactno=$_POST['contact'];

    $query="UPDATE userregistration, admin SET userregistration.Name=?, userregistration.gender=?, userregistration.contactNo=?, admin.department=? WHERE userregistration.userID=admin.adminID AND userregistration.userID=?";

    $stmt = $mysqli->prepare($query);
    $rc=$stmt->bind_param('ssisi',$Name,$gender,$contactno,$department,$aid);

    $stmt->execute();
    echo"<script>alert('Profile updated Successfully');</script>";
}

if(isset($_POST['changepwd']))
{
	$aid=$_SESSION['id'];
  $op=$_POST['oldpassword'];
  $np=$_POST['newpassword'];

	$sql="SELECT password FROM userregistration where userID=? AND password=?";
	$chngpwd = $mysqli->prepare($sql);
	$chngpwd->bind_param('is',$aid,$op);
	$chngpwd->execute();
	$chngpwd->store_result(); 
    $row_cnt=$chngpwd->num_rows;;
	if($row_cnt>0)
	{
		$con="update userregistration set password=?  where userID=?";
$chngpwd1 = $mysqli->prepare($con);
$chngpwd1->bind_param('si',$np,$aid);
  $chngpwd1->execute();
		$_SESSION['msg']="Password Changed Successfully !!";
	}
	else
	{
		$_SESSION['msg']="Old Password not match !!";
	}	
	

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
	<title>Update Profile</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
<script type="text/javascript" src="js/validation.min.js"></script>
<script type="text/javascript">






function valid()
{
if(document.changepwd.newpassword.value!= document.changepwd.cpassword.value)
{
alert("Password and Re-Type Password Field do not match  !!");
document.changepwd.cpassword.focus();
return false;
}
return true;
}
</script>








</head>
<body>
	<?php include('includes/header.php');?>
	<div class="ts-main-content" style="padding: 40px;">
		<?php include('includes/sidebar.php');?>


		

				<div class="row" style="margin-top: 40px;">




					<div class="col-md-12">
					
						<h2 class="page-title">Update Profile</h2>

	<?php	
$aid=$_SESSION['id'];
	$ret="SELECT userregistration.*, admin.*
    FROM userregistration
    INNER JOIN admin ON userregistration.userID = admin.adminID
    WHERE userregistration.userID=?";
		$stmt= $mysqli->prepare($ret) ;
	 $stmt->bind_param('i',$aid);
	 $stmt->execute() ;//ok
	 $res=$stmt->get_result();
	 //$cnt=1;
	   while($row=$res->fetch_object())
	  {
	  	?>	


						<div class="row">


							<div class="col-md-12">		

								<div class="panel panel-primary">
						<div class="panel-heading">

Welcome:
</div>
									

<div class="panel-body">
<form method="post" action="" name="registration" class="form-horizontal" onSubmit="return valid();">
<?php echo csrf_field(); ?>
<div class="form-group">
<label class="col-sm-2 control-label">Name : </label>
<div class="col-sm-8">
<input type="text" name="name" id="name"  class="form-control" value="<?php echo htmlspecialchars(($row->Name ?? ''), ENT_QUOTES, 'UTF-8');?>"   required="required" >
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Department: </label>
<div class="col-sm-8">
<input type="text" name="department" id="department"  class="form-control" value="<?php if(isset($row->Department)) {  echo htmlspecialchars($row->Department, ENT_QUOTES, 'UTF-8');}?>"   required="required" >
</div>
</div>


<div class="form-group">
<label class="col-sm-2 control-label">Gender : </label>
<div class="col-sm-8">
<select name="gender" class="form-control" required="required" >
<option value="male">Male</option>
<option value="female">Female</option>


</select>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Contact No : </label>
<div class="col-sm-8">
<input type="text" name="contact" id="contact"  class="form-control" maxlength="10" value="<?php echo htmlspecialchars(($row->contactNo ?? ''), ENT_QUOTES, 'UTF-8');?>" required="required">
</div>
</div>


<div class="col-sm-6 col-sm-offset-4">

<input type="submit" name="update" Value="Update Profile" class="btn btn-primary">
</div>
</form>

									</div>
									
								</div>
									<?php }  ?>







								<div class="col-md-6">
								<div class="panel panel-primary">


									<div class="panel-heading">


									Password Updation:&nbsp;</div>
									<div class="panel-body">
				<form method="post" class="form-horizontal" name="changepwd" id="change-pwd" onSubmit="return valid();">
<?php echo csrf_field(); ?>
    <?php            if(isset($_POST['changepwd']))
{ ?>
											<p style="color: red"><?php echo htmlentities($_SESSION['msg']); ?><?php echo htmlentities($_SESSION['msg']=""); ?></p>
                                            <?php } ?>
											<div class="hr-dashed"></div>
											<div class="form-group">
												<label class="col-sm-4 control-label">old Password </label>
												<div class="col-sm-8">
				<input type="password" value="" name="oldpassword" id="oldpassword" class="form-control" onBlur="checkpass()" required="required">
									 <span id="password-availability-status" class="help-block m-b-none" style="font-size:12px;"></span> </div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">New Password</label>
												<div class="col-sm-8">
											<input type="password" class="form-control" name="newpassword" id="newpassword" value="" required="required">
												</div>
											</div>
<div class="form-group">
									<label class="col-sm-4 control-label">Confirm Password</label>
									<div class="col-sm-8">
				<input type="password" class="form-control" value="" required="required" id="cpassword" name="cpassword" >
												</div>
											</div>



												<div class="col-sm-6 col-sm-offset-4">
													
													<input type="submit" name="changepwd" Value="Change Password" class="btn btn-primary" onclick="return showConfirmDialog();">
													<script>
														function showConfirmDialog() {
															return confirm("Are you sure you want to change your password?");
														}
													</script>
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
	
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
    <script type="text/javascript" src="js/sidebarPopUp.js"></script>
	<script>
function checkAvailability() {
$("#loaderIcon").show();
jQuery.ajax({
url: "check_availability.php",
data:'emailid='+$("#emailid").val(),
type: "POST",
success:function(data){
$("#user-availability-status").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}
</script>
<script>
function checkpass() {
$("#loaderIcon").show();
jQuery.ajax({
url: "check_availability.php",
data:'oldpassword='+$("#oldpassword").val(),
type: "POST",
success:function(data){
$("#password-availability-status").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}
</script>
</body>

</html>