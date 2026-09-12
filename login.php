<?php
session_start();
include('includes/config.php');

if(isset($_POST['login']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a SQL statement to select the email, password, id, and role from the 'userregistration' table where the email and password match the ones provided
    $stmt = $mysqli->prepare("SELECT email,password,userID,role FROM userregistration WHERE email=? AND password=?");
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $stmt->bind_result($email, $password, $id, $role);
    $rs = $stmt->fetch();
    $stmt->close();

    if($rs)
    {
        // Store the user's id, email, and role in the session
        $_SESSION['id'] = $id;
        $_SESSION['login'] = $email;
        $_SESSION['role'] = $role;

        // Clear stale room-selection state from any previous session
        unset($_SESSION['room_id']);
        unset($_SESSION['roomNo']);

        // Redirect the user based on their role
        switch ($role) {
            case 'admin':
                header("location: admin/chooseHostel.php");
                break;
            default:
                header("location: dashboard.php");
                break;
        }
    }
    else
    {
        // Handle login failure
$errorMessage="Invalid Email or Password";

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
	<title>Student Hostel Registration</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
	
	
<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
<script type="text/javascript" src="js/sidebarPopUp.js"></script>
<script type="text/javascript" src="js/validation.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
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
		
		margin-top :50px; 
		border: 1px solid black;
		padding: 10px;
background-color: rgba(21, 0, 255, 0.125);
	padding: 10px;
		padding-top: 10px;}
.box{
	padding: 10px;
	display: flex; justify-content: center; align-items: center;
}
	
	body{
		background-image: url(img/bodyBak.jpg);
	background-size:cover;
		
	}

	.btn:hover {
        background-color: aqua;
        color: black; }
		.forget_p:hover{
			color:red;
		}

</style>
</head>
<body>
	<?php include('includes/header.php');?>

		<?php include('includes/sidebar.php');?>
		
			

			<div class="box col-md-12">
					<div  class="insideBox col-md-7">
					
						<h2 class="page-title">Login </h2>

						<div class="">
						<span style="color: red;font-size: 30px;;font-family:cursive"><?php if (isset($errorMessage)) {echo "$errorMessage";}?></span>
					<div class="col-md-6 col-md-offset-3">
						<div class=" row pt-2x pb-3x ">
							<div class="col-md-8 col-md-offset-2">
							
								<form action="" class="mt" method="post">
									<label for="" class="text-uppercase text-sm">Email</label>
									<input type="text" placeholder="Email" name="email" class="form-control mb">
									<label for="" class="text-uppercase text-sm">Password</label>
									<input type="password" placeholder="Password" name="password" class="form-control mb">
									

									<input type="submit" name="login" class="btn btn-primary btn-block" value="login" >
								</form>
							</div>
						</div>
						
						<div class="text-center " >
							<div style="font-size: large;color:white">Don't have Account?&nbsp;&nbsp;&nbsp;<i class="fa fa-arrow-right"></i>&nbsp;&nbsp;<a class="btn "  style="box-shadow: 2px 2px 5px white; border: 1px solid; padding: 2px;" href="registration.php" >register<i class="fa fa-hand-peace-o"></i></a><br><br><a class="forget_p"href="forgot-password.php" >Forgot password?</a></div>
							
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