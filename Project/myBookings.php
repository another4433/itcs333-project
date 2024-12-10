<?php
require "functions.php";
session_start();
if(!isset($_SESSION['userID']))
  exit("Your are not authorized to view this page");

function printBookings():string {
  $connection = databaseConnect();
  $date = date("Y-m-d");
  $currentTime = date("H:s:i");
  $sql = "Select Room.RoomID, Date, StartTime, EndTime, RoomName, Location, ImageName ". 
        "From booking, room " .
        "Where booking.RoomID = room.RoomID AND " .
        "PersonID = :userID";
  
  $SQLResult = dbQuery($connection, $sql, [
    ':userID' => $_SESSION['userID'],
  ]);
  
  if(isset($SQLResult['error']))
    exit($SQLResult['error']);


  $result = "";
  foreach($SQLResult As $booking) {
    $result = $result . 
              "<div class='room'>". 
                "<div class='room-image'>". 
                  "<img src='Images\\$booking[ImageName]'>". 
                "</div>". 
                "<h3>{$booking['RoomName']}</h3>". 
                "<div class='room-info'>". 
                  "<p>{$booking['Date']}</p>". 
                  "<div class='timeLine'>". 
                    "<p class='startTime'>{$booking['StartTime']}</p>". 
                    "<p class='endTime'>{$booking['EndTime']}</p>". 
                  "</div>". 
                  "<div class='roomInfo'>". 
                    "<p class='location'>{$booking['Location']}</p>". 
                  "</div>". 
                "</div>". 
                "<form action='newMyBookings.php' method='POST'>". 
                  "<input type='hidden' name='roomID' value='<?={$booking['RoomID']}?>'>". 
                  "<input type='hidden' name='personID' value='<?={$_SESSION['userID']}?>'>". 
                  "<button type='submit'>Delete</button>". 
                "</form>". 
              "</div>";
  }

  return $result;
}
     
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="myBookings.css">
</head>
<body>


  <nav>
      <h2>My Bookings</h2>
      <div>
          <a href="browsing.php">browse rooms</a>
          <a href="profile.php">my profile</a>
      </div>
  </nav>

  <div class="container">
    <div class="ALLroom">
      <div class="msg">
        <h3><?php if(isset($msg)) echo $msg; ?></h3>
        <h3><?php if(isset($msg2)) echo $msg2; ?></h3>
      </div>
      <?=printBookings()?>
    </div>
  </div>
</body>
</html>