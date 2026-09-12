<?php
session_start();
include('includes/config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
}

$studentId = $_SESSION['id']; // Get the student id from session

$sql = "SELECT stayHostel FROM student WHERE studentID = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $studentId); // 'i' indicates the variable type is integer

$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$row = $result->fetch_assoc(); // fetch data   

$stayHostel = $row['stayHostel'];
if(isset($_POST['submit']) && $_POST['message'] != null)
{
    $senderID=$_SESSION['id'];
    $message = $_POST['message'];
    $query = "insert into messages(msg,msgSender,hostel) values(?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sii', $message,$senderID,$stayHostel);
    $stmt->execute();

    header("Location: dashboard.php"); // redirect to the other PHP file
    exit;
}
if(isset($_POST['delete'])) {
    $msgID = $_POST['delete'];
    $senderID = $_SESSION['id'];
    $query = "DELETE FROM messages WHERE id = ? AND msgSender = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ii', $msgID, $senderID);
    $stmt->execute();
    
    header("Location: dashboard.php?"); // redirect to the other PHP file
    exit;
}
?>
  