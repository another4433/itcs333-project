<?php
require "functions.php";
$user = "1";
$_SESSION["userID"] = $user;

function printBookings():string {
  $connection = databaseConnect();
  $sql = "Select Room.RoomID, Date, StartTime, EndTime, RoomName, Location, ImageName ". 
        "From booking, room " .
        "Where booking.RoomID = room.RoomID AND " . 
        "PersonID = :userID";
  
  $SQLResult = dbQuery($connection, $sql, [
    'userID' => $_SESSION['userID']
  ]);

  if(isset($SQLResult['error']))
    return $SQLResult['error'];

  $result = "";
  foreach($SQLResult As $booking) {
    $result = $result . 
              "<div class='booking'>" .
                "<div class='roomPhoto'>" . 
                  "<img src='Images\$booking['ImageName']'" . 
                "</div>" . 
                "<div class='bookingInfo'>" .
                  "<p>{$booking['Date']}</p>" .
                  "<div class='timeLine'>" .
                    "<p class='startTime'>{$booking['StartTime']}</p>" . 
                    "<p class='endTime'>{$booking['EndTime']}</p>" .
                  "</div>".
                  "<div class='roomInfo'>" .
                    "<p class='roomName'>{$booking['RomeName']}</p>" .
                    "<p class='roomInfo'>{$booking['RoomInfo']}</p>" .
                  "</div>".
                "</div>".
                "<form action='bookingDelete.php' method='POST'>" . 
                  "<input type='hidden' name='roomID' value='<?={$booking['RoomID']}?>'>" .
                  "<input type='hidden' name='personID' value='<?={$booking['PersonID']}?>'>" .
                  "<button type='submit'>Delete</button>" .
                "</form>" .
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
  <title>My Bookings</title>
</head>
<body>
  <nav></nav>
  <main>
    <?=printBookings()?>
  </main>
</body>
</html>