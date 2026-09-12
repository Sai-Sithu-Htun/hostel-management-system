<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if(isset($_POST['submit']))
{

csrf_verify();

$Name=$_POST['name'];
$department=$_POST['department'];
$gender=$_POST['gender'];
$role="admin";
$contactno=$_POST['contact'];
$email=$_POST['email'];
$password=$_POST['password'];
$query="insert into  userRegistration(Name,gender,contactNo,email,password,role) values(?,?,?,?,?,?)";
$stmt = $mysqli->prepare($query);
$rc=$stmt->bind_param('ssisss',$Name,$gender,$contactno,$email,$password,$role);
$stmt->execute();

$parentID = $mysqli->insert_id;

// Step 3: Insert into child table
$sql = "INSERT INTO admin (adminID,Department) VALUES (?,?)"; // replace 'adminID' with the actual column name in your 'admin' table that should hold the ID of the admin
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("is", $parentID,$department); // bind the ID of the newly inserted user
$stmt->execute();

$stmt->close();
$mysqli->close();
echo "<script type='text/javascript'>alert('Successfully Registered!');</script>";

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
	<title>User Registration</title>
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
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="js/sidebarPopUp.js"></script>

<script type="text/javascript">
function valid()
{
if(document.registration.password.value!= document.registration.cpassword.value)
{
alert("Password and Re-Type Password Field do not match  !!");
document.registration.cpassword.focus();
return false;
}
return true;
}
</script>
<style>
	.box a,h2,form{
		color:white;
	}
	.box label{
		font-weight:lighter;
	}
.insideBox{
	margin-top:40px;
	margin-bottom:40px;
		border: 1px solid black;
		padding: 10px;
		background-color: rgba(21, 0, 255, 0.125);
	}
.box{
	display: flex; justify-content: center; align-items: center;
	padding: 10px;
}
	
	body{
		background-image: url(img/bodyBak.jpg);
		background-size:cover;

		
	}
	.btn:hover {
        background-color: aqua;
        color: black; }
		

</style>
</head>
<body>
	<?php include('includes/header.php');?>
	
		<?php include('includes/sidebar.php');?>
		
		

			<div class="box col-md-12" >
					<div  class=" insideBox col-md-8" >
					
						<h2 class="page-title ">Registration </h2>

						<div class="row">
							<div class="col-md-12" >
								<div class="panel " style="background-color: rgba(0, 0, 0, 0.134);">
									<div class="panel-heading" style="font-size:x-large;color:red">Fill all Info!</div>
									<div class="panel-body">
			<form method="post" action="" name="registration" class="form-horizontal" onSubmit="return valid();">
							<?php echo csrf_field(); ?>
											
										




<div class="form-group">
<label class="col-sm-3 control-label">Name : </label>
<div class="col-sm-6">
<input type="text" name="name" id="name" pattern="[a-zA-Z\s]+" title="Please enter only letters " class="form-control" required="required">

</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label">Department: </label>
<div class="col-sm-6">
<input type="text" name="department" id="department"  class="form-control" placeholder="Optional">

</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label">Gender : </label>
<div class="col-sm-6">
<select name="gender" id="gender" class="form-control" required="required">
<option value="">Select Gender</option>
<option value="male">Male</option>
<option value="female">Female</option>
</select>
</div>
</div>


<div class="form-group">
<label class="col-sm-3 control-label">Contact No : </label>
<div class="col-sm-6">
<input type="text" name="contact" id="contact" pattern="09[0-9]{9}" title="Please enter a valid contact number starting with 09 followed by nine digits" class="form-control" required="required">

</div>
</div>


<div class="form-group">
<label class="col-sm-3 control-label">Email: </label>
<div class="col-sm-6">
<input type="email" name="email" id="email"  class="form-control" onBlur="checkEmailAvailability() " required="required">
<span id="email-availability-status" style="font-size:12px;"></span>     
</div>
</div>





<div class="form-group">
<label class="col-sm-3 control-label">Password: </label>
<div class="col-sm-6">
<input type="password" name="password" id="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,}" title="Must contain at least one number, one uppercase letter, one lowercase letter, one special character, and at least 8 or more characters" class="form-control" required="required">

</div>
</div>


<div class="form-group">
<label class="col-sm-3 control-label">ConfirmPassword: </label>
<div class="col-sm-6">
<input type="password" name="cpassword" id="cpassword"  class="form-control" required="required">
</div>
</div>


<div style="font-size: xx-large;" class="col-sm-9 col-sm-offset-5">

<input type="submit" id="regBtn" name="submit" Value="Register" class="btn btn-primary" style="font-size: large;padding:15px">
</div>
</form>

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
	<script src="sidebarPopUp.js"></script>

<script>
var isEmailAvailable = false;

function checkEmailAvailability() {
    $("#loaderIcon").show();
    jQuery.ajax({
        url: "check_availability.php",
        data: {'email': $("#email").val()},
        type: "POST",
        success:function(data){
            $("#email-availability-status").html(data);
            $("#loaderIcon").hide();
            if (data.includes("Email already exist")) {
                isEmailAvailable = false;
            } else {
                isEmailAvailable = true;
            }
            checkRegBtnStatus();
        },
        error:function () {
            event.preventDefault();
            alert('error');
        }
    });
}

function checkRegBtnStatus() {
    if (isEmailAvailable) {
        $("#regBtn").prop("disabled", false);
    } else {
        $("#regBtn").prop("disabled", true);
    }
}
</script>





</script>


</body>
</html>