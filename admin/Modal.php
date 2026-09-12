<div class="row col-md-3" style="margin-bottom: 10px;">
	




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
<script src="js/jquery.min.js"></script>

</div>
    
<script>
$(document).ready(function(){
$("#searchForm").submit(function(event){
event.preventDefault(); // prevent the form from submitting normally
$.ajax({
url: $(this).attr('action'),
type: $(this).attr('method'),
data: $(this).serialize(), // serialize the form data
success: function(response){
$("#result").html(response); // update the #result div with the response
}
});
});
});
</script>
    

<div class="floormapSlide col-md-5" >
<div class="floorMapContainer" style="margin-bottom: 25px;justify-content:center;align-items:center">
<?php include 'floorMapContainer.php'; ?>
</div>
</div>

<div id="modal" style="margin-bottom: 20px;" class="col-md-3">
<h4><i style="padding:10px;" class="fa fa-hotel"></i>Search Student!</h4>
<P>The room is showed with a green color!</P>
<form id="searchForm" action="searchStudent.php" method="post" style="padding: 10px;">
<input type="text" name="search" placeholder="Enter MKPT!" pattern="\d{4}" title="Please enter exactly four digits">
<button type="submit"> <i class="fa fa-search"></i></button>
</form>
<div id="result" style="padding: 15px;font-size:large"></div>
<button onclick="location.reload();">clear</button>

</div>
<div class="col-md-2" style="background-color: aqua; height: 210px;width:450px; padding:8px ;border:2px dotted;margin-left:70px ">
<span id="room-availability-status" style="font-size:20px; font-weight:bold; color:red;"></span>
</div>