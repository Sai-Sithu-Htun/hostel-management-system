<?php
session_start();
include('includes/config.php');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}
csrf_verify();
unset($_SESSION['id']);
session_destroy();
header('Location:index.php');
exit;
?>