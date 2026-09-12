<?php

include('includes/config.php');
$hostel=$_SESSION['hostelID'];?>
<link rel="stylesheet" href="css/sidenav.css">

			<div id="mySidebar" class="sidebar ts-sidebar-menu">
  
  <i href="javascript:void(0)" class="closebtn fa fa-times" onclick="closeNav()" ></i>
  <ul>
   
  <li><a href="index.php"><i class="fa fa-home"></i>Home</a></li> 
  <li><a href="chooseHostel.php"><i class="fa fa-bank"></i>Choose Hostel</a></li>
  <li ><a href="dashboard.php?hostelID=<?php echo $hostel; ?>"><i class="fa fa-desktop"></i>Dashboard</a></li>
<li><a href="manage-students.php"><i class="fa fa-cogs"></i>Manage Student</a></li>
<li><a href="registration.php"><i class="fa fa-user"></i>Register Admins</a></li>
<li><form method="post" action="logout.php"><i class="fa fa-sign-out"></i><button type="submit" style="padding:0;border:none;background:none;color:inherit;cursor:pointer;font-size:inherit;">Logout</button></form></li> 
			
         
        </ul>
</div>

