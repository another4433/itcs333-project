<?php
require "functions.php";
require "bahrainTimezone.php";
session_start();
if(!isset($_SESSION['userID']))
  exit("Your are not authorized to view this page");

deleteBooking();
function deleteBooking() {
  if($_SERVER['REQUEST_METHOD'] != "POST")
    return;

  $connection = databaseConnect();
  $sql = "DELETE FROM booking " . 
        "WHERE BookingID = :bookingID";
  
  $result = dbQuery($connection, $sql, [
    ':bookingID' => $_POST['bookingID']
  ]);
  
  if(isset($result['error']))
    exit($result['error']);

}
function printBookings():string {
  $connection = databaseConnect();
  $date = date("Y-m-d");
  $currentTime = date("H:s:i");
  $sql = "Select BookingID, Date, StartTime, EndTime, RoomName, Location, ImageName ". 
        "From booking, room " .
        "Where booking.RoomID = room.RoomID AND " .
        "PersonID = :userID AND " . 
        "Date > :currentDate";
  
  $afterTodayBookings = dbQuery($connection, $sql, [
    ':userID' => $_SESSION['userID'],
    ":currentDate" => $date
  ]);

  if(isset($afterTodayBookings['error']))
    exit($afterTodayBookings['error']);

  $sql = "Select BookingID, Date, StartTime, EndTime, RoomName, Location, ImageName ". 
        "From booking, room " .
        "Where booking.RoomID = room.RoomID AND " .
        "PersonID = :userID AND " . 
        "Date = :currentDate AND " . 
        "StartTime > :currentTime";

  $todayBookings = dbQuery($connection, $sql, [
    ':userID' => $_SESSION['userID'],
    ':currentDate' => $date,
    ':currentTime' => $currentTime
  ]);

  if(isset($todayBookings['error']))
    exit($todayBookings['error']);
  

  $SQLResult = array_merge($afterTodayBookings, $todayBookings);


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
                "<form action='myBookings.php' method='POST'>". 
                  "<input type='hidden' name='bookingID' value='{$booking['BookingID']}'>". 
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
      <?=printBookings()?>
    </div>
  </div>
</body>
</html>