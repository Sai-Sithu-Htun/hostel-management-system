<?php
session_start(); // Start the session
include('includes/checklogin.php');
check_login();
include('getName.php');
include('includes/config.php');
if (isset($_SESSION['hostelID'])) {
    $hostelID = intval($_SESSION['hostelID']);
    $position = isset($_POST['posi']) ? intval($_POST['posi']) : 0;

    // Create a database connection

    // Prepare the SQL statement
    $stmt = $mysqli->prepare("SELECT rooms.*, student.mkpt, studentTwo.mkpt as mkptTwo FROM rooms LEFT JOIN student ON rooms.stdID = student.studentID LEFT JOIN student as studentTwo ON rooms.stdIdTwo = studentTwo.studentID WHERE rooms.position = ? AND rooms.floor IN ('G', 'F') AND rooms.hostelID = ?");
    $stmt->bind_param("ii", $position, $hostelID);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch the data
    while ($row = $result->fetch_assoc()) {
        $floorName = $row['floor'] == 'F' ? 'first' : 'ground';
        if (!empty($row['stdID'])) {
            echo "The room on the {$floorName} floor at position {$position} is occupied by <span style='color:blue;'>MKPT: " . htmlspecialchars(($row['mkpt'] ?? ''), ENT_QUOTES, 'UTF-8') . "</span><br>";
            if (!empty($row['mkptTwo'])) {
                echo "The second student is: <span style='color:blue;'> MKPT:" . htmlspecialchars(($row['mkptTwo'] ?? ''), ENT_QUOTES, 'UTF-8') . "</span><br>";
            }
        } else {
            echo "The room on the {$floorName} floor at position {$position} is empty.<br>";
        }
        
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Session variable 'hostelID' is not set.";
}
?>
