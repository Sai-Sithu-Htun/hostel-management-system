


<div class="form-group">
<label class="col-sm-4 control-label">You get Random Room,Random Bed:</label>
<div class="col-sm-8">

<?php 
$sql = "SELECT roomID FROM rooms WHERE hostelID = 4  AND stdID IS NULL";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    echo "<select name='roomID' style='padding: 10px; font-size: 18px;'>";
    while($row = $result->fetch_assoc()) {
        echo "<option  value='" . htmlspecialchars(($row['roomID'] ?? ''), ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars(($row['roomID'] ?? ''), ENT_QUOTES, 'UTF-8') . "</option>";
    }
    echo "</select>";
} else {
    echo "0 results";
}

?>

</div>


</div>	
