<?php if (isset($_SESSION['room_id']) && isset($_SESSION['roomNo']) && $_SESSION['room_id'] !== '' && $_SESSION['roomNo'] !== ''): ?>
<div class="form-group">
<label class="col-sm-2 control-label">Room ID:</label>
<div class="col-sm-8">
<input type="text" name="roomID" id="roomID"  class="form-control" value="<?php echo $_SESSION['room_id'] ;?>"  readonly>
</div>
</div>	
<div class="form-group">
<label class="col-sm-2 control-label">RoomNo:</label>
<div class="col-sm-8">
<input type="text" class="form-control" value="<?php echo $_SESSION['roomNo'];?>"  readonly>
</div>
</div>
<?php else: ?>
<p style="color:red;">Please choose a room from the floor map first.</p>
<?php endif; ?>