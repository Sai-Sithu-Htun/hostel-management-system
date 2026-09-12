<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
}
$hostel=$_SESSION['hostelID'];
if(isset($_POST['submit']) && $_POST['rules'] != null)
{
 
    $rules = $_POST['rules'];
    $query = "insert into rules(rules) values(?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('s', $rules);
    $stmt->execute();
    header("Location: dashboard.php?hostelID=$hostel"); 
    exit;
}
if(isset($_POST['delete'])) {
    $rules = $_POST['delete'];
    $query = "DELETE FROM rules WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $rules);
    $stmt->execute();
    
    header("Location: dashboard.php?hostelID=$hostel"); 
    exit;
}
?>