<?php
function check_login()
{
	$sessionId   = isset($_SESSION['id'])   ? $_SESSION['id']   : '';
	$sessionRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';

	// Allow only authenticated admins. No session or a non-admin (e.g. a logged-in student)
	// is sent back to the admin landing page (admin/index.php). Student-side includes are unaffected.
	if (strlen($sessionId) == 0 || $sessionRole !== 'admin') {
		$host  = $_SERVER['HTTP_HOST'];
		$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = "index.php";
		$_SESSION["id"] = "";
		header("Location: http://$host$uri/$extra");
		exit;
	}
}
?>