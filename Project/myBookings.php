<?php
require "functions.php";
require "bahrainTimezone.php";
session_start();
if(!isset($_SESSION['userID']))
  exit("Your are not authorized to view this page");

deleteBooking();
function checkIfNoBookings():string {
  $date = date("Y-m-d");
  $time = date("H:i:s");
  $connection = databaseConnect();
  $sql = "SELECT COUNT(bookingID) AS count FROM Booking WHERE PersonID = :userID AND Date > :date";
  $result1 = dbQuery($connection, $sql, [
    ':userID' => $_SESSION['userID'],
    ':date' => $date
  ]);
  
  if(isset($result1['error'])) {
    exit($result1['error']);
  }


  $sql = "SELECT COUNT(bookingID) AS count FROM booking WHERE PersonID = :userID AND Date = :date AND StartTime > :time";

  $result2 = dbQuery($connection, $sql, [
    ':userID' => $_SESSION['userID'],
    ':date' => $date,
    ':time' => $time
  ]);

  if(isset($result2['error']))
    exit($result2['error']);

  if($result1[0]['count'] > 0 && $result2[0]['count'] > 0)
    return "";

  return "<h3 class='error'>There is no booking</h3>";

}
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
  $currentTime = date("H:i:s");
  $sql = "Select BookingID, Date, StartTime, EndTime, RoomName, Location, Description , ImageName ". 
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

  $sql = "Select BookingID, Date, StartTime, EndTime, RoomName, Location, Description, ImageName ". 
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
                  "<p>{$booking['Date']}</p>". 
                  "<div class='duration'>" .
                    "<p class='startTime'>{$booking['StartTime']}</p>". 
                    "<p>-</p>" .
                    "<p class='endTime'>{$booking['EndTime']}</p>". 
                  "</div>" .
                  "<p class='location'>{$booking['Location']}</p>". 
                  "<p class='description'>{$booking['Description']}</p>" .
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
      <?=checkIfNoBookings()?>
      <?=printBookings()?>
    </div>
  </div>
</body>
</html>