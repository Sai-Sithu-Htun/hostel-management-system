<div class="col-md-12">

<h2 class="page-title">Dashboard &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $_SESSION['hostel'];?></h2>



        


<div class="col-md-7" >
<div class="floorMapContainer" >
<?php include 'floorMapContainer.php'; ?>
</div>

</div>

<div class="col-md-5">
<div style="margin-top: 10px; margin-left: 10px;">

<svg  width="282" height="330" style="background-color:#d4fac8;margin-left:20px">

<rect x="15" y="15" stroke="black" stroke-width="0.5" fill="#70bfff" rx="3" ry="3" width="30" height="30"/>
<text x="60" y="32" font-family="cursive" font-size="16" fill="#70bfff">:Available Rooms &#x263B;</text>

<rect x="15" y="60" stroke="black" stroke-width="0.5" fill="#ff5e5e" rx="3" ry="3"   width="30" height="30"/>
<text x="60" y="77" font-family="cursive" font-size="16" fill=#ff5e5e>:Ground floor / 1 student = Red &#x2639;</text>

<rect  x="15" y="105" stroke="black" stroke-width="0.5" fill="blue" rx="3" ry="3"   width="30" height="30"/>
<text x="60" y="122" font-family="cursive" font-size="16" fill=blue>:First floor / 1 student = Blue &#x2639;</text>

<rect  x="15" y="145" stroke="black" stroke-width="0.5" fill="#252426" rx="3" ry="3"   width="30" height="30"/>
<text x="60" y="162" font-family="cursive" font-size="16" fill=#252426>:Full / 2 students = Black &#x2639;</text>

<rect  x="15" y="235" stroke="black" stroke-width="0.5" fill="#ffb030" rx="3" ry="3" width="30"  height="30"/>
<text x="60" y="255" font-family="cursive" font-size="16" fill="#ffb030">:Stairs &#x279A;</text>

<rect  x="15" y="280" stroke="black" stroke-width="0.5" fill="#d279d4" rx="3" ry="3" width="30"  height="30"/>
<text x="60" y="300" font-family="cursive" font-size="16" fill="#d279d4">:Bathrooms &#x267B;</text>

<rect  x="15" y="190" stroke="black" stroke-width="0.5" fill="#f5ea51" rx="3" ry="3" width="30"  height="30"/>
<text x="60" y="212" font-family="cursive" font-size="16" fill="#b8ad18">:Dining room &#x2668;</text>
</svg>
</div>
</div>

<div id="modal" class="col-md-7">
<h4><i style="padding:10px;" class="fa fa-hotel"></i>Choose room!</h4>
<form action="book-hostel.php"name="applyRoom" id="applyRoom" method="post" onsubmit="return valid();" style="padding: 10px; display: flex; flex-direction: column; align-items: flex-start;">
<?php echo csrf_field(); ?>
<div style="display: flex;">
<div>
<input type="radio" id="FirstFloor" name="floor" value="F"required>
<label for="FirstFloor">FirstFloor:  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  F-</label><br>
<input type="radio" id="GroundFloor" name="floor" value="G">
<label for="GroundFloor">GroundFloor:&nbsp; &nbsp;&nbsp;G-</label><br>
</div>
<div>
<input type="hidden" id="position" name="position" required>
<ul id="roomList" style="font: size 9px;font-weight:bold">
</ul>
</div>
</div>
<input type="submit" name="applyRoom" class="btn btn-primary btn-block" value="Apply Room" >

</form>
<span id="room-availability-status"></span>
<script>
document.getElementById("applyRoom").addEventListener("submit", function(event){
var form = event.target;
var position = form.elements["position"].value;

if (position === "") {
alert("Please select a position On the floor Map.");
event.preventDefault();
}
});
</script>
</div>



<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
<script src="js/jquery.min.js"></script>
<script>
$(document).ready(function() {
// Attach a click event handler to each <rect>
for (let i = 1; i <= 84; i++) {
$('#' + i).click(function() {
var ul = document.getElementById('roomList'); // Replace 'myList' with the id of your list


// Clear the list
while (ul.firstChild) {
ul.removeChild(ul.firstChild);
}

// Create two list items for the roomId
for (var j = 1; j <= 2; j++) {
var li = document.createElement('li');
li.appendChild(document.createTextNode(i));
ul.appendChild(li);
}
$('#position').val(i);  $.ajax({
url: 'checkRoom.php', // Replace with the path to your PHP script
type: 'POST',
data: { posi: i },
success: function(response) {
console.log(response); // This will log the response to the console
$("#room-availability-status").html(response); // This will display the response in the span
}

});
});
}
});

</script>

</div>
