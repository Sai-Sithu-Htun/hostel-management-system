<?php

include('includes/config.php');

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

include('includes/config.php');

if(isset($_SESSION['id'])) {
    $studentID = $_SESSION['id'];
    $query = "SELECT stayHostel FROM student WHERE studentID = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $studentID);
    $stmt->execute();
    
    $stmt->bind_result($stayHostel);
    
    $stayHostelIDs = array();
    while ($stmt->fetch()) {
        $stayHostelIDs[] = $stayHostel;
    }
    
    $stmt->close();
    
    foreach ($stayHostelIDs as $stayHostel) {
        $query2 = "SELECT hostelName, hostelID FROM hostel WHERE hostelID = ?";
        $stmt2 = $mysqli->prepare($query2);
        $stmt2->bind_param('i', $stayHostel);
        if ($stmt2->execute()) {
            $stmt2->bind_result($hostelName, $hostelID);
            if ($stmt2->fetch()) {
                $_SESSION['hostel'] = $hostelName;
                $_SESSION['hostelID'] = $hostelID;
            }
        }
        $stmt2->close();
    }
    
}

?>