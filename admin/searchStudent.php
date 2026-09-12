
<head>
<link rel="stylesheet" href="css/dashboard.css">
</head>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Only an authenticated admin can change the admin hostel context, and only to a
// valid hostel. A student can never choose another hostel's data here.
if (isset($_GET['hostelID'])) {
    $requested = intval($_GET['hostelID']);
    $valid = $mysqli->prepare("SELECT hostelID FROM hostel WHERE hostelID = ?");
    $valid->bind_param("i", $requested);
    $valid->execute();
    $valid->store_result();
    if ($valid->num_rows === 1) {
        $_SESSION['hostelID'] = $requested;
    }
    $valid->close();
}
?>
<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['search'])) {
        $mkpt = $_POST['search'];
        $hostelID = $_SESSION['hostelID']; 

        // Reset the color of the previous rect to its original color
        if (isset($_SESSION['prev_position'])) {
            echo "<script>
            var prev_rect = document.getElementById('".$_SESSION['prev_position']."');
            prev_rect.style.fill = '#70bfff'; // Replace 'original_color' with the original color of the rect
            </script>";
        }

        $stmt = $mysqli->prepare("SELECT studentID FROM student WHERE mkpt = ?");
        $stmt->bind_param('i', $mkpt);
        $stmt->execute();
        $stmt->bind_result($studentID);
        if ($stmt->fetch()) {
            $stmt->close(); // Close the first statement
            
            // Prepare a new statement to get the name from the userRegistration table
            $stmt1 = $mysqli->prepare("SELECT Name FROM userregistration WHERE userID = ?");
            $stmt1->bind_param('i', $studentID);
            $stmt1->execute();
            $stmt1->bind_result($name);
            if ($stmt1->fetch()) {
                echo "MKPT: " . htmlspecialchars($mkpt, ENT_QUOTES, 'UTF-8') . " <br> " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ." . ";
                $stmt1->close(); // Close the second statement
                
                // Prepare a new statement to get the roomNo from the rooms table
                $stmt2 = $mysqli->prepare("SELECT floor, roomNo, position FROM rooms WHERE (stdID = ? OR stdIdTwo = ?) AND hostelID = ?");
                $stmt2->bind_param('iii', $studentID, $studentID, $hostelID);
                $stmt2->execute();
                $stmt2->bind_result($floor, $roomNo, $position);
                
                if ($stmt2->fetch()) {
                    echo "<br>The room number is: " . htmlspecialchars($roomNo, ENT_QUOTES, 'UTF-8');
                    echo "<br>The floor is ";
                    if ($floor == 'F') {
                        echo "First Floor!";
                    } elseif ($floor == 'G') {
                        echo "Ground Floor!";
                    }
                    echo "<script>
                    var rect = document.getElementById('$position');
                    rect.style.fill = 'green';
                    </script>";

                    // Store the current position in the session
                    $_SESSION['prev_position'] = $position;
                } else {
                    echo "No room found for that student ID in the specified hostel.";
                }
                $stmt2->close(); // Close the third statement
            } else {
                echo "No user found with that student ID in the userRegistration table.";
            }
        } else {
            echo "No student found with that mkpt.";
        }
    }
}
?>



