<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
}
$hostel = $_SESSION['hostelID'];

if(isset($_POST['submit']) && $_POST['message'] != null)
{
    $senderID = $_SESSION['id'];
    $message = $_POST['message'];
    $query = "INSERT INTO messages(msg, msgSender, hostel) VALUES(?, ?, ?)"; // Add the hostel column to the query
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sii', $message, $senderID, $hostel); // Bind the hostel to the query
    $stmt->execute();

    header("Location: dashboard.php?hostelID=$hostel"); // redirect to the other PHP file
    exit;
}

if(isset($_POST['delete'])) {
    $msgID = $_POST['delete'];
    $query = "DELETE FROM messages WHERE id = ? AND hostel = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ii', $msgID, $hostel);
    $stmt->execute();
    
    header("Location: dashboard.php?hostelID=$hostel"); // redirect to the other PHP file
    exit;
}
$hostel = $_SESSION['hostelID'];
if(isset($_POST['clear'])) {
    $query = "DELETE FROM messages WHERE messages.hostel = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $hostel); // 'i' indicates the variable type is integer
    $stmt->execute();
    
    header("Location: dashboard.php?hostelID=$hostel"); // redirect to the other PHP file
    exit;
}


?>
  