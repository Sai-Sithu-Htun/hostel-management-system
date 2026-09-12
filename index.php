<?php
session_start();
include('includes/config.php');

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
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet"  href="css/home.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/sidenav.css">
<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
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
<?php if (isset($_SESSION['id'])): ?>
    <style>
    .main_content {
        margin-top: 80px; /* Adjust as needed */
    }
    </style>
<?php endif; ?>






</head>
<body>

<?php if (isset($_SESSION['id']))
{ ?><div class="brand clearfix" style="background-color: rgb(29, 29, 29);">
<div style="position: relative;">
<div style="overflow:hidden;width:fit-content">
	<a  class="logo" style="display:flex; font-size:30px;width:auto;height:80px" >  <i style="padding-top: 3px; font-size:larger"onmouseover="this.style.color='blue';" onmouseout="this.style.color='grey';" class="closebtn fa fa-bars" onclick="openNav()" ></i>&nbsp;Hostel Registration System For UCSM</a>
	</div>
	<div style="position: absolute;right:10px;top:2px">
	<ul class="ts-profile-nav">
		<li class="ts-account">
			<a href="#" style="margin: 10px;"><img src="img/ts-avatar.jpg" class="ts-avatar hidden-side" alt=""> <?php echo htmlspecialchars(($_SESSION['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?> <i class="fa fa-angle-down hidden-side"></i></a>
			<ul>
				<li><a href="userAccount.php">My Account</a></li>
				<li><a href="update-Profile.php">Update Account</a></li>
				<li><form method="post" action="logout.php" style="display:inline;"><?php echo csrf_field(); ?><button type="submit" style="padding:0;border:none;background:none;color:inherit;cursor:pointer;">Logout</button></form></li>
			</ul>
		</li>
	</ul></div>
</div>
</div>

<?php
} else { ?>
<div class=" clearfix" style="background-color: rgb(29, 29, 29);">
<div style="position: relative;">
<div style="overflow:hidden;width:fit-content">
	<h1 style="display:flex;color:#a7ecff; padding:none; font-size:30px;width:auto;height:50px" > &nbsp;Hostel Registration System For UCSM</h1>
	</div>
	<div style="color:white;position: absolute;right:20px;top:20px;font-size:x-large">
  <a href="login.php"><i class="fa fa-sign-in"></i> Login</a>

 	</div>
</div>
</div>
	<?php } ?>



	<div class="main_content">
		

          <div class="video-container">
    <video autoplay muted loop>
        <source src="img/bakgVid.webm" type="video/mp4">
    </video>
    <div >
   
    <div class="caption">
        <h2>Welcome!</h2>
        <h4>University of Computer Studies Mandalay</h4>
      </div>
    </div>
</div>



<?PHP if(isset($_SESSION['id']))
				{ ?>
			<div id="mySidebar" class="sidebar ts-sidebar-menu">
  
  <i href="javascript:void(0)" class="closebtn fa fa-times" onclick="closeNav()" ></i>
  <ul>
   
  <li><a href="index.php"><i class="fa fa-home"></i>Home</a></li> 
  
  <li ><a href="dashboard.php"><i class="fa fa-desktop"></i>Dashboard</a></li>

<li><a href="myProfile.php"><i class="fa fa-user"></i>My Profile</a></li>

			
<li><form method="post" action="logout.php"><i class="fa fa-sign-out"></i><button type="submit" style="padding:0;border:none;background:none;color:inherit;cursor:pointer;font-size:inherit;">Logout</button></form></li> 

         
        </ul>
</div>

<?php } else { ?>
	
	<div id="mySidebar" class="sidebar ts-sidebar-menu">
  
  <i href="javascript:void(0)" class="closebtn fa fa-times" onclick="closeNav()" ></i>
  <ul>
   
  <li><a href="index.php"><i class="fa fa-home"></i>Home</a></li> 
  
					<li><a href="login.php"><i class="fa fa-users"></i>Login</a></li>
					<li><a href="registration.php"><i class="fa fa-files-o"></i>Registration</a></li>
					
         
        </ul>
</div>
				
				
				<?php } ?>



          <div class="slide w3-card-4">
<div class="slideshow-container">


  <div class="mySlides ">
    <div class="numbertext">1/4</div>
    <img src="img/M1.jpg" style="width:100%">
    <div class="text"><h4>M-1</h4></div>
  </div>

  <div class="mySlides ">
    <div class="numbertext">2/4</div>
    <img src="img/M2.jpg" style="width:100%">
    <div class="text"><h4>M-2</h4></div>
  </div>

  <div class="mySlides ">
    <div class="numbertext">3/4</div>
    <img src="img/N-hall.JPG" style="width:100%">
    <div class="text"><h4>N-Hall</h4></div>
  </div>
  <div class="mySlides ">
    <div class="numbertext">4/4</div>
    <img src="img/J-hall.JPG" style="width:100%">
    <div class="text"><h4>J-Hall</h4></div>
  </div>

  <!-- Next and previous buttons -->
  <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
  <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>
<br>

<!-- The dots/circles -->
<div style="text-align:center">
  <span class="dot" onclick="currentSlide(1)"></span>
  <span class="dot" onclick="currentSlide(2)"></span>
  <span class="dot" onclick="currentSlide(3)"></span>
  <span class="dot" onclick="currentSlide(4)"></span>
</div>

          </div>

  
  <hr>

  <div class="w3-row " id="menu" >
    <div class="w3-col l6 w3-padding-large">
      <h1 class="w3-center"style="font-weight:bold;font: size 110px;text-shadow: 2px 2px #a1e9ed;">Our Team &nbsp;<i class="fa fa-users"></i></h1><br>

    <div style="padding-left: 100px;">
      <h2>	&#x263A;Team Project &mdash; UCSM Computer Science</h2>
      <h3 class="w3-text-grey">Student Hostel Management System</h3><br>

      <h2>&#x2664;My Role: Team Leader</h2>
      <h3 class="w3-text-grey">Project planning &middot; Team coordination &middot; System flow design</h3><br>

      <h2>&#x2623;Responsibilities</h2>
      <h3 class="w3-text-grey">Communication &middot; Presentation &middot; Assisted with ERD and flowchart design</h3><br>

      <h2>&#x2726;Collaboration</h2>
      <h3 class="w3-text-grey">Built as a team project &mdash; student and administrator modules</h3><br>
    </div>
       
    </div>
    
    <div class="w3-col l6 w3-padding-large">
      <img src="img/logo1.jpg" class="w3-round w3-image w3-card-4" alt="Menu" style="width:100%">
    </div>
  </div>

  <hr>
  
  <div class="w3-row" style="padding-top:32px"  id="about">
    <div class="w3-col m6 w3-padding-large w3-hide-small">
     <img src="img/entrance.jpg" class="w3-round w3-image w3-card-4 " alt="Table Setting" width="600" height="750">
    </div>

    <div class="w3-col m6 w3-padding-large">
      <h1 class="w3-center"style=" text-shadow: 2px 2px #a1e9ed;">Introduction</h1><br>
      
      <p class="w3-large">Hostel Registration system is web based application to manage the students and hostel details. 
      <br>The system helps students to register and book hostel rooms online. 
      <br>The system is divided into two modules: Admins and Students.
      <br>Admin can manage room placement, student’s record, messages, rules and regulation easily.
      <br>Students can view room details which include room number, features, total fees, duration, and more.
      <br>The system helps students to save the time and room allocation of hostel placement and make booking easily and quickly.
</p>
<h1 class="w3-center"style=" text-shadow: 2px 2px #a1e9ed;">Advantages</h1><br>
      <p class="w3-large w3-text-grey ">&#x2714;Saving Time : This system makes room booking easy and fast for students and also saves time for admin in room placement.
<br>&#x2714;Accessibility: This system is simple and easy to use for the user.
<br>&#x2714;Ease Of Use: Can be used anywhere with an internet connection.
<br>&#x2714;User-Friendly: Easy navigation for both administrators and students. Familiarity between administrators and students. 
</p>
    </div>
  </div>

  <footer class="w3-center w3-aqua w3-padding-32">
  
  <div class="w3-xlarge w3-section">
    <i class="fa fa-facebook-official w3-hover-opacity"></i>
    <i class="fa fa-instagram w3-hover-opacity"></i>
    <i class="fa fa-snapchat w3-hover-opacity"></i>
    <i class="fa fa-pinterest-p w3-hover-opacity"></i>
    <i class="fa fa-twitter w3-hover-opacity"></i>
    <i class="fa fa-linkedin w3-hover-opacity"></i>
  </div>
  <i class="fa fa-copyright w3-hover-opacity">sensitive</i>
</footer>

  </div>
					
						
	
	<script>
let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace("active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}
</script>
<script type="text/javascript" src="js/sidebarPopUp.js"></script>
</body>

</html>