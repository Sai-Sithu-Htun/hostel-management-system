<?php if (isset($_SESSION['id']))
{ ?><div class="brand clearfix" style="background-color: rgb(29, 29, 29);position: fixed;z-index: 9999;">
<div style="position: relative;">
<div style="overflow:hidden;width:fit-content">
	<a  class="logo" style="display:flex; font-size:30px;width:auto;height:80px" >  <i style="padding-top: 3px; font-size:larger"onmouseover="this.style.color='blue';" onmouseout="this.style.color='grey';" class="closebtn fa fa-bars" onclick="openNav()" ></i>&nbsp;Hostel Registration System For UCSM</a>
	</div>
	<div style="position: absolute;right:10px;top:2px">
	<ul class="ts-profile-nav">
		<li class="ts-account">
			<a href="#" style="margin: 10px;"><img src="img/ts-avatar.jpg" class="ts-avatar hidden-side" alt=""> <?php echo htmlspecialchars(($_SESSION['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>  <i class="fa fa-angle-down hidden-side"></i></a>
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
<div class="brand clearfix" style="overflow:hidden;background-color: rgb(29, 29, 29);">
		<a  class="logo" style="display:flex; font-size:30px;width:auto;height:80px" >  <i style="padding-top: 3px; font-size:larger"onmouseover="this.style.color='blue';" onmouseout="this.style.color='grey';" class="closebtn fa fa-bars" onclick="openNav()" ></i>&nbsp;Hostel Registration System For UCSM</a>
		
	</div>
	<?php } ?>