<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if (isset($_GET['del']) || isset($_GET['delete'])) {
    http_response_code(403);
    exit;
}

$hostelID = $_SESSION['hostelID'];

if(isset($_POST['del']))
{
    csrf_verify();
    $targetID = intval($_POST['del']);

    if ($targetID <= 0) {
        echo "<script>alert('Invalid student ID.');window.history.back();</script>";
        exit;
    }

    $mysqli->begin_transaction();
    try {
        $stmt = $mysqli->prepare("SELECT u.userID, u.role, s.studentID, s.stayHostel FROM userregistration u JOIN student s ON u.userID = s.studentID WHERE u.userID = ?");
        $stmt->bind_param('i', $targetID);
        $stmt->execute();
        $student = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$student) {
            throw new Exception("Student not found.");
        }
        if ($student['role'] === 'admin') {
            throw new Exception("Cannot delete an admin account.");
        }
        if (intval($student['stayHostel']) !== intval($hostelID)) {
            throw new Exception("Student does not belong to this hostel.");
        }

        $stmt = $mysqli->prepare("SELECT roomID, stdID, stdIdTwo FROM rooms WHERE (stdID = ? OR stdIdTwo = ?) AND hostelID = ?");
        $stmt->bind_param('iii', $targetID, $targetID, $hostelID);
        $stmt->execute();
        $room = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($room) {
            if (intval($room['stdID']) === $targetID) {
                $stmt = $mysqli->prepare("UPDATE rooms SET stdID = NULL WHERE roomID = ?");
                $stmt->bind_param('i', $room['roomID']);
                $stmt->execute();
                $stmt->close();
            }
            if (intval($room['stdIdTwo']) === $targetID) {
                $stmt = $mysqli->prepare("UPDATE rooms SET stdIdTwo = NULL WHERE roomID = ?");
                $stmt->bind_param('i', $room['roomID']);
                $stmt->execute();
                $stmt->close();
            }
        }

        $stmt = $mysqli->prepare("DELETE FROM studentinformation WHERE id = ?");
        $stmt->bind_param('i', $targetID);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare("DELETE FROM student WHERE studentID = ?");
        $stmt->bind_param('i', $targetID);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare("DELETE FROM userregistration WHERE userID = ?");
        $stmt->bind_param('i', $targetID);
        $stmt->execute();
        $stmt->close();

        $mysqli->commit();
        echo "<script>alert('Student deleted successfully.');window.location.href='manage-students.php';</script>";
        exit;
    } catch (Exception $e) {
        $mysqli->rollback();
        echo "<script>alert('" . addslashes($e->getMessage()) . "');window.history.back();</script>";
        exit;
    }
}

if(isset($_POST['delete']))
{
    csrf_verify();
    $targetID = intval($_POST['delete']);

    if ($targetID <= 0) {
        echo "<script>alert('Invalid student ID.');window.history.back();</script>";
        exit;
    }

    $mysqli->begin_transaction();
    try {
        $stmt = $mysqli->prepare("SELECT userID, role FROM userregistration WHERE userID = ?");
        $stmt->bind_param('i', $targetID);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$user) {
            throw new Exception("Student not found.");
        }
        if ($user['role'] === 'admin') {
            throw new Exception("Cannot delete an admin account.");
        }

        $stmt = $mysqli->prepare("SELECT stayHostel FROM student WHERE studentID = ?");
        $stmt->bind_param('i', $targetID);
        $stmt->execute();
        $sRow = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$sRow || intval($sRow['stayHostel']) !== intval($hostelID)) {
            throw new Exception("Student does not belong to this hostel.");
        }

        $stmt = $mysqli->prepare("UPDATE rooms SET stdID = NULL WHERE stdID = ?");
        $stmt->bind_param('i', $targetID);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare("UPDATE rooms SET stdIdTwo = NULL WHERE stdIdTwo = ?");
        $stmt->bind_param('i', $targetID);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare("DELETE FROM studentinformation WHERE id = ?");
        $stmt->bind_param('i', $targetID);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare("DELETE FROM student WHERE studentID = ?");
        $stmt->bind_param('i', $targetID);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare("DELETE FROM userregistration WHERE userID = ?");
        $stmt->bind_param('i', $targetID);
        $stmt->execute();
        $stmt->close();

        $mysqli->commit();
        echo "<script>alert('Student deleted successfully.');window.location.href='manage-students.php';</script>";
        exit;
    } catch (Exception $e) {
        $mysqli->rollback();
        echo "<script>alert('" . addslashes($e->getMessage()) . "');window.history.back();</script>";
        exit;
    }
}


?>
<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="theme-color" content="#3e454c">
	<title>Manage Rooms</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">


</head>

<body>
	<?php include('includes/header.php');?>

	<div class="ts-main-content">
			<?php include('includes/sidebar.php');?>
		
			<div class="container-fluid" style="padding: 50px;">
				<div class="row">
					<div class="col-md-12" style="margin-top: 20px;">
						<h2 class="page-title">Room-Applied Students</h2>
						<div class="panel panel-default">
							<div class="panel-heading">All Details</div>
							<div class="panel-body">
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Sno.</th>
											<th>Student Name</th>
											<th>Email</th>
											<th>Contact no </th>
											<th>Room no  </th>
											<th>MKPT </th>									
											<th>Action</th>
										</tr>
									</thead>
									<tfoot>
										
									</tfoot>
									<tbody>
									<?php
$cnt=1;
$hostelID = $_SESSION['hostelID'];

$query = "SELECT rooms.*, student.*, userregistration.* 
FROM rooms 
JOIN student ON rooms.stdID = student.studentID OR rooms.stdIdTwo = student.studentID
JOIN userregistration ON student.studentID = userregistration.userID
WHERE rooms.hostelID = ?";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $hostelID); 
$stmt->execute();
$res = $stmt->get_result();
while($row = $res->fetch_object())
{
?>
<tr>
<td><?php echo $cnt;?></td>
<td><?php echo htmlspecialchars(($row->Name ?? ''), ENT_QUOTES, 'UTF-8');?></td>
<td><?php echo htmlspecialchars(($row->email ?? ''), ENT_QUOTES, 'UTF-8');?></td>
<td><?php echo htmlspecialchars(($row->contactNo ?? ''), ENT_QUOTES, 'UTF-8');?></td>
<td><?php echo htmlspecialchars(($row->roomNo ?? ''), ENT_QUOTES, 'UTF-8');?></td>
<td><?php echo htmlspecialchars(($row->mkpt ?? ''), ENT_QUOTES, 'UTF-8');?></td>
<td>
<a href="fullDetail.php?stdid=<?php echo htmlspecialchars(($row->studentID ?? ''), ENT_QUOTES, 'UTF-8');?>" title="View Full Details"><i class="fa fa-desktop"></i></a>   
<form method="post" action="" style="display:inline;" onsubmit="return confirm('Do you want to delete');">
                    <button type="submit" name="del" value="<?php echo htmlspecialchars(($row->studentID ?? ''), ENT_QUOTES, 'UTF-8');?>" title="Delete Record" style="border:none;background:none;color:inherit;cursor:pointer;"><i class="fa fa-close"></i></button>
                    <?php echo csrf_field(); ?>
                </form>
</td>
</tr>
<?php
$cnt=$cnt+1;
}
?>

											
										
									</tbody>
								</table>

								
							</div>
						</div>

					</div>
			

					<?php include('regStudents.php');?>

			

			</div>
		</div>
	</div>

	<!-- Loading Scripts -->
	<script type="text/javascript" src="js/sidebarPopUp.js"></script>
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>

</body>

</html>
