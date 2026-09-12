
<head>
<link rel="stylesheet" href="css/dashboard.css">
</head>


<?php 
include('getName.php');
include('includes/config.php');
$hostelID = $_SESSION['hostelID'];  // Get the hostelID from the session

// J-Hall has no SVG floor map - the room-color system is not applied to it.
if ($hostelID != 4) {
    // Load every room for this hostel with its two student slots (stdID, stdIdTwo).
    $stmt = $mysqli->prepare("SELECT position, floor, stdID, stdIdTwo FROM rooms WHERE hostelID = ?");
    $stmt->bind_param("i", $hostelID);
    $stmt->execute();
    $result = $stmt->get_result();

    $bluePositions  = array(); // First floor (F), 1 occupant
    $redPositions   = array(); // Ground floor (G), 1 occupant
    $blackPositions = array(); // 2 occupants (full), any floor

    while ($row = $result->fetch_assoc()) {
        // Occupancy is the number of filled student slots in this room row.
        $occupants = 0;
        if (!empty($row['stdID']))    { $occupants++; }
        if (!empty($row['stdIdTwo'])) { $occupants++; }

        if ($occupants == 0) {
            continue; // available - keep existing default SVG fill
        }

        if ($occupants >= 2) {
            // FULL - black regardless of floor.
            if (!in_array($row['position'], $blackPositions)) { $blackPositions[] = $row['position']; }
        } elseif ($row['floor'] == 'F') {
            if (!in_array($row['position'], $bluePositions)) { $bluePositions[] = $row['position']; }
        } elseif ($row['floor'] == 'G') {
            if (!in_array($row['position'], $redPositions)) { $redPositions[] = $row['position']; }
        }
    }
    $stmt->close();

    if (!empty($bluePositions) || !empty($redPositions) || !empty($blackPositions)) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            var rect;";
        // First floor: 1 occupant -> blue
        foreach ($bluePositions as $position) {
            echo "rect = document.getElementById('".$position."'); if (rect) rect.style.fill = 'blue';";
        }
        // Ground floor: 1 occupant -> red
        foreach ($redPositions as $position) {
            echo "rect = document.getElementById('".$position."'); if (rect) rect.style.fill = '#ff5e5e';";
        }
        // 2 occupants -> full black (applied last so it always wins)
        foreach ($blackPositions as $position) {
            echo "rect = document.getElementById('".$position."'); if (rect) rect.style.fill = '#252426';";
        }
        echo "
        });
        </script>";
    }
}


?>




 <?php 
if(isset($_SESSION['hostelID'])) {
    $hostelID = $_SESSION['hostelID'];

    switch($hostelID) {
                case '1':
                    echo '<svg id="mysvg" class="floorPlan flex-child">
  
                    <rect class="corridor1" x="30" y="150" />
                   <rect class="corridor2" x="30" y="315" /> 
                   <rect class="corridor3" x="510" y="150" />
                 
                   <rect class="middleStair" x="300" y="315" /> 
                 
                   <rect class="bathrooms" x="45" y="30" /> 
                   <rect class="bathrooms" x="525" y="30" />
                 
                   <rect class="entrance" x="285" y="390"/>
                   <rect class="food" x="150" y="135"/>
                  
                   <rect class="stairs"x="45" y="165" />  
                    <rect class="stairs"x="525" y="165" /> 
                 
                 
                   <g class="rectGroup">
                   <rect  id="24" x="45" y="195" />
                   <rect id="23" x="45" y="225" /> 
                   <rect  id="22" x="45" y="255" />
                   <rect  id="21" x="45" y="285" /> 
                   <rect   id="25" x="75" y="195" />
                   <rect  id="26" x="75" y="225" /> 
                   <rect  id="27" x="75" y="255" />
                   <rect  id="28" x="75" y="285" /> 
                 
                 
                   <rect   id="42" x="525" y="195" />
                   <rect  id="41" x="525" y="225" /> 
                   <rect    id="40" x="525" y="255" />
                   <rect  id="39"  x="525" y="285" /> 
                   <rect  id="1" x="555" y="195" />
                   <rect  id="2" x="555" y="225" /> 
                   <rect  id="3" x="555" y="255" />
                   <rect  id="4" x="555" y="285" />
                   
                 
                 
                   <rect id="29" x="120" y="330" />
                   <rect  id="30" x="150" y="330" /> 
                   <rect  id="31" x="180" y="330" />
                   <rect  id="32" x="210" y="330" /> 
                   <rect  id="33" x="240" y="330" />
                   <rect  id="17"  x="120" y="360" />
                   <rect  id="16" x="150" y="360" /> 
                   <rect  id="15" x="180" y="360" />
                   <rect  id="14" x="210" y="360" /> 
                   <rect  id="13" x="240" y="360" />
                 
                   <rect   id="34"  x="360" y="330" />
                   <rect  id="35" x="390" y="330" /> 
                   <rect  id="36" x="420" y="330" />
                   <rect  id="37" x="450" y="330" /> 
                   <rect  id="38" x="480" y="330" />
                   <rect  id="12" x="360" y="360" />
                   <rect  id="11" x="390" y="360" /> 
                   <rect  id="10" x="420" y="360" />
                   <rect  id="9" x="450" y="360" /> 
                   <rect  id="8" x="480" y="360" />
                 
                   <rect  id="20" x="45" y="330" />
                   <rect  id="19" x="45" y="360" /> 
                   <rect  id="18" x="75" y="360" />
                 
                   <rect  id="5"   x="555" y="330" />
                   <rect  id="7" x="525" y="360" /> 
                   <rect  id="6" x="555" y="360" />
                 
                 </g>
                 </svg>';
                    break;
                case '2':
                    echo '<svg class="floorPlanM2 flexchild" >

                    <rect  id="" class="corridor"  x="30" y="225" />
                    <g class="rectGroup">
                    <rect  id="1"   x="135" y="240" />
                    <rect  id="2"   x="165" y="240" />
                    <rect  id="3"   x="195" y="240" />
                    <rect  id="4"   x="225" y="240" />
                    <rect  id="5"   x="255" y="240" />
                    <rect  id="6"   x="285" y="240" />
                    <rect  id="7"   x="315" y="240" />
                    <rect  id="8"   x="345" y="240" />
                    <rect  id="9"   x="375" y="240" />
                    <rect  id="10"   x="405" y="240" />
                    <rect  id="11"   x="435" y="240" />
                    <rect  id="12"   x="465" y="240" />
                    <rect  id="13"   x="495" y="240" />
                    
                    <rect  id="26"   x="135" y="270" />
                    <rect  id="25"   x="165" y="270" />
                    <rect  id="24"   x="195" y="270" />
                    <rect  id="23"   x="225" y="270" />
                    <rect  id="22"   x="255" y="270" />
                    <rect  id="21"   x="285" y="270" />
                    <rect  id="20"   x="315" y="270" />
                    <rect  id="19"   x="345" y="270" />
                    <rect  id="18"   x="375" y="270" />
                    <rect  id="17"   x="405" y="270" />
                    <rect  id="16"   x="435" y="270" />
                    <rect  id="15"   x="465" y="270" />
                    <rect  id="14"   x="495" y="270" />
                    </g>
                    <rect  id="" class="bathrooms"  x="30" y="225" />
                    <rect  id=""  class="bathrooms" x="30" y="270" />
                    <rect  id=""  class="stairs" x="90" y="240" />
                    <rect  id="" class="stairs"  x="540" y="240" />
                    <rect  id="" class="bathrooms"  x="570" y="225" />
                    <rect  id="" class="bathrooms"  x="570" y="270" />
                    <rect  id="" class="entrance"  x="315" y="315" />
                    <rect  id="" class="food"  x="345" y="30" />
                    <rect  id="" class="food"  x="495" y="30" />


                    </svg> ';
                    break;
                case '3':
                    echo '<svg class="floorPlanN_hall flexchild" style="margin-top: 80px;">

                    <rect  id="" class="corridor"  x="30" y="105" />
                    <g class="rectGroup">
                    <rect  id="1"   x="135" y="120" />
                    <rect  id="2"   x="165" y="120" />
                    <rect  id="3"   x="195" y="120" />
                    <rect  id="4"   x="225" y="120" />
                    <rect  id="5"   x="255" y="120" />
                    <rect  id="6"   x="285" y="120" />
                    <rect  id="7"   x="315" y="120" />
                    <rect  id="8"   x="345" y="120" />
                    <rect  id="9"   x="375" y="120" />
                    <rect  id="10"   x="405" y="120" />
                    <rect  id="11"   x="435" y="120" />
                    <rect  id="12"   x="465" y="120" />
                    <rect  id="13"   x="495" y="120" />
                    
                    <rect  id="26"   x="135" y="150" />
                    <rect  id="25"   x="165" y="150" />
                    <rect  id="24"   x="195" y="150" />
                    <rect  id="23"   x="225" y="150" />
                    <rect  id="22"   x="255" y="150" />
                    <rect  id="21"   x="285" y="150" />
                    <rect  id="20"   x="315" y="150" />
                    <rect  id="19"   x="345" y="150" />
                    <rect  id="18"   x="375" y="150" />
                    <rect  id="17"   x="405" y="150" />
                    <rect  id="16"   x="435" y="150" />
                    <rect  id="15"   x="465" y="150" />
                    <rect  id="14"   x="495" y="150" />
                    </g>
                    <rect  id="" class="bathrooms"  x="30" y="105" />
                    <rect  id=""  class="bathrooms" x="30" y="150" />
                    <rect  id=""  class="stairs" x="90" y="120" />
                    <rect  id="" class="stairs"  x="540" y="120" />
                    <rect  id="" class="bathrooms"  x="570" y="105" />
                    <rect  id="" class="bathrooms"  x="570" y="150" />
                  
                  
                    <rect  id="" class="food"  x="165" y="30" />
                     <rect  id="" class="food"  x="330" y="30" />
                    </svg> ';
                    break;
                case '4':
                    echo '<div class="hostel4">Hostel 4</div>';
                    break;
                default:
                    echo '<div class="unknown">Unknown Hostel</div>';
            }
        }
    else {


        echo "No results found.";
    }

?>


