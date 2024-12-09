<?php
require "functions.php";
$user = "1";
$_SESSION["userID"] = $user;

function deleteBooking():string {
  if($_SERVER['REQUEST_METHOD'] !== "POST") {
    return '';
  }

  $connection = databaseConnect();
  $roomID = $_POST['roomID'];
  $personID = $_POST['personID'];

  $sql = "DELETE FROM booking " . 
          "WHERE RoomID = :roomID AND ". 
          "PersonID = :personID";

  try {
    $statment = $connection->prepare($sql);
    $statment->execute([
      ":roomID" => $roomID,
      ":personID" => $personID
    ]);
  } catch (PDOException $th) {
    exit("Something went wrong with the delete query:" . $th->getMessage());

  }

  return "hello world";

}

function printBookings():string {
  $connection = databaseConnect();
  //$sql = "Select Room.RoomID, Date, StartTime, EndTime, RoomName, Location, ImageName ". 
  //      "From booking, room " .
  //      "Where booking.RoomID = room.RoomID AND " . 
  //      "PersonID = :userID";
  //
  //$SQLResult = dbQuery($connection, $sql, [
  //  'userID' => $_SESSION['userID']
  //]);
  //
  //if(isset($SQLResult['error']))
  //  return $SQLResult['error'];

  $SQLResult = [
    [
      'RoomID' => '1',
      'PersonID' => '3',
      'Date' => '2024-12-5',
      'StartTime' => '10:00:00',
      'EndTime' => "12:00:00" ,
      "RoomName" => "hello",
      "Location" => "this is the location",
      "ImageName" => '674d8a045d1a3.jpg'
    ],
    [
      'RoomID' => '2',
      'PersonID' => '3',
      'Date' => '2024-12-7',
      'StartTime' => '10:00:00',
      'EndTime' => "12:00:00" ,
      "RoomName" => "hi",
      "Location" => "this is the location",
      "ImageName" => '674d8a045d1a3.jpg'
    ]
  ];


  $result = "";
  foreach($SQLResult As $booking) {
    $result = $result . 
              "<div class='booking'>" .
                "<div class='roomPhoto'>" . 
                  "<img src='Images\\$booking[ImageName]'" . 
                "</div>" . 
                "<div class='bookingInfo'>" .
                  "<p>{$booking['Date']}</p>" .
                  "<div class='timeLine'>" .
                    "<p class='startTime'>{$booking['StartTime']}</p>" . 
                    "<p class='endTime'>{$booking['EndTime']}</p>" .
                  "</div>".
                  "<div class='roomInfo'>" .
                    "<p class='roomName'>{$booking['RoomName']}</p>" .
                    "<p class='location'>{$booking['Location']}</p>" .
                  "</div>".
                "</div>".
                "<form action='myBookings.php' method='POST'>" . 
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
  <p><?=deleteBooking()?></p>
  <nav></nav>
  <main>
    <?=printBookings()?>
  </main>
</body>
</html>