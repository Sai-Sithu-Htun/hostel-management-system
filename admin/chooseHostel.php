<?php
if (!function_exists('check_login')) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once('includes/config.php');
    require_once('includes/checklogin.php');
}
check_login();
?>
<!DOCTYPE html>
<html>
<head>
<style>
body {
    background-image: url('img/bak.jpg');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: cover;
  display: flex;
  flex-direction: column;
  align-items: center;
  height: 100vh;
  margin: 0;
}

header {
  align-self: flex-start;
  text-align: center;
  width: 100%;
  color:#6f00ff;
  padding-top: 20px; /* Adjust this value to move the header up or down */
  font-size: 2.5em; /* Adjust this value to increase or decrease the font size */
}

svg {
  width: 190vmin; /* Relative to the smaller dimension (width or height) of the viewport */
  height: 190vmin; /* Relative to the smaller dimension (width or height) of the viewport */
}
#header {
  position: absolute;
  z-index: 1;
}

#mysvg {
  position: absolute;
  z-index: 2;
}
.hostel:hover {
  width: 210px; /* Adjust as needed */
  height: 190px; /* Adjust as needed */
}
.hostel {
  transition: all 0.3s ease-in-out;
}


</style>
</head>
<body>

<header id="header">
  <h1>Choose Hostel!</h1>
</header>

<svg viewBox="0 80 880 540" id="mysvg">
<a href="dashboard.php?hostelID=1" title="M1-Hall">
  <image href="img/m1.jpg" x="0" y="75" width="200" height="180" class="hostel"/>
  <text x="40" y="80" fill="aqua" font-size="35">M1-hall</text>
</a>
  
<a href="dashboard.php?hostelID=2" title="M2-Hall">
  <image href="img/m2.jpg" x="225" y="75" width="200" height="180" class="hostel"/>
  <text x="280" y="80" fill="aqua" font-size="35">M2-hall</text>
</a>
  
<a href="dashboard.php?hostelID=3" title="N-Hall">
  <image href="img/N-hall.JPG" x="450" y="75" width="200" height="180" class="hostel"/>
  <text x="500" y="80" fill="aqua" font-size="35">N-hall</text>
</a>
  
<a href="dashboard.php?hostelID=4" title="J-Hall">
  <image href="img/J-hall.jpg" x="670" y="75" width="200" height="180" class="hostel"/>
  <text x="720" y="80" fill="aqua" font-size="35">J-hall</text>
</a>

</svg>


</body>
</html>
