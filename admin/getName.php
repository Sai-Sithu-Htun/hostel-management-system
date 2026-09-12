<?php
if (!function_exists('check_login')) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include('includes/config.php');
    include('includes/checklogin.php');
}
check_login();

if(isset($_SESSION['id'])) {
    $stdID = $_SESSION['id'];
    $query = "SELECT Name FROM userregistration WHERE userID = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $stdID);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $_SESSION['name'] = $row['Name'];
          
        }
    } else {
        echo "No results found.";
    }
}
?>
<?php

// Only an authenticated admin may switch the admin hostel context, and only to an
// existing hostel. Students can no longer set $_SESSION['hostelID'] via this file.
if (isset($_GET['hostelID']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $requested = intval($_GET['hostelID']);

    $stmt = $mysqli->prepare("SELECT hostelName FROM hostel WHERE hostelID = ?");
    $stmt->bind_param("i", $requested);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $_SESSION['hostelID'] = $requested;
            $_SESSION['hostelName'] = $row['hostelName'];
        }
    } 
     $stmt->close();
    
} 

?>  

