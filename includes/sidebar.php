<link rel="stylesheet" href="css/sidenav.css">
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
