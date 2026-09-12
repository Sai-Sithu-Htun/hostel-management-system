<?php
if (!function_exists('check_login')) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include('includes/config.php');
    include('includes/checklogin.php');
}
check_login();
?>

<div class="col-md-10" >
						<h2 class="page-title">All Registered Students</h2>
						<div class="panel panel-default">
							<div class="panel-heading">All Details</div>
							<div class="panel-body">
								<table id="regstd" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Sno.</th>
											<th>Student Name</th>
											<th>Email</th>
											<th>Contact no </th>
											<th>MKPT </th>		
											<th>Hostel </th>								
											<th>Action</th>
										</tr>
									</thead>
									<tfoot>
										
									</tfoot>
									<tbody>
									<?php
$cnt=1;
$query = "SELECT userregistration.*, student.mkpt, student.stayHostel, hostel.hostelName FROM userregistration LEFT JOIN student ON userregistration.userID = student.studentID LEFT JOIN hostel ON student.stayHostel = hostel.hostelID WHERE userregistration.role IS NULL OR userregistration.role != 'admin'"; 
$stmt = $mysqli->prepare($query);

$stmt->execute();
$res = $stmt->get_result();
while($row = $res->fetch_object())
{
    
  
	  	?>
<tr><td><?php echo $cnt;;?></td>
<td><?php echo htmlspecialchars(($row->Name ?? ''), ENT_QUOTES, 'UTF-8');?>
<td><?php echo htmlspecialchars(($row->email ?? ''), ENT_QUOTES, 'UTF-8');?></td>
<td><?php echo htmlspecialchars(($row->contactNo ?? ''), ENT_QUOTES, 'UTF-8');?></td>
<td><?php echo htmlspecialchars(($row->mkpt ?? ''), ENT_QUOTES, 'UTF-8');?></td>
<td><?php echo htmlspecialchars(($row->hostelName ?? ''), ENT_QUOTES, 'UTF-8');?></td>

<td>
<form method="post" action="manage-students.php" style="display:inline;" onsubmit="return confirm('Do you want to delete');">
    <button type="submit" name="delete" value="<?php echo htmlspecialchars(($row->userID ?? ''), ENT_QUOTES, 'UTF-8');?>" title="Delete Record" style="border:none;background:none;color:inherit;cursor:pointer;">Delete</button>
    <?php echo csrf_field(); ?>
</form>
</td>
										</tr>
									<?php
$cnt=$cnt+1;
									 } ?>
											
										
									</tbody>
								</table>

								
							</div>
						</div>

					</div>
