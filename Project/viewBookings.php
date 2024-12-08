<?php

require "functions.php";
$user = "test@stu.uob.edu.bh";
$_SESSION["userID"] = $user;

if(!isset($_SESSION['userID']))
  exit("You are not authorized to view this page");

$bookings = [
  [
    "StartTime" => "08:30:00",
    "EndTime" => "09:00:00"
  ]
];

function validation() {
  if($_SERVER['REQUEST_METHOD'] !== "POST")
    return "";

  $date = DateTime::createFromFormat("Y-m-d", $_POST['date']);

  $connection = databaseConnect();

  $sql = "SELECT StartTime, EndTime ". 
          "FROM booking ". 
          "WHERE Booking.Date = :date ";

  $currentDate = date("Y-m-d");
  $currentTime = date("H:s:i");

  if($_POST['date'] == $currentDate)
    $sql = $sql . "AND StartTime > '$currentTime'";


  $result = dbQuery($connection, $sql, [
    ":date" => $date->format("Y-m-d")
  ]);

  if(isset($result['error']))
    return $result['error'];

  global $bookings;
  $bookings = $result;
  return "";
}


function printTimeline() {
  $result = "";
  global $bookings;
  $time = DateTime::createFromFormat("H:i:s", "8:00:00");
  $nextTime = clone $time;
  $nextTime->modify("+1 hours");
  $finalTime = DateTime::createFromFormat("H:i:s", "17:00:00");
  $i = 0;
  $firstHalf = "";
  $secondHalf = "";
  while($time <= $finalTime) {
    if($time == $nextTime) {
      $insertedTime = clone $time;
      $insertedTime->modify("-1 hours");
      $result = $result . 
      "<div class='hour'>" .
        "<div class='time'>" .
          "<h1>{$insertedTime->format('H:i')}</h1>" .
        "</div>" .
        "<div class='timeSpan'>" .
          "<div id='firstHalf' class='$firstHalf'></div>" . 
          "<div id='secondHalf' class='$secondHalf'></div>" .
        "</div>" .
      "</div>";
      $firstHalf = "";
      $secondHalf = "";
      $nextTime->modify("+1 hours");
    }
    if($i < count($bookings) &&
      $time >= DateTime::createFromFormat("H:i:s", $bookings[$i]['StartTime']) &&
      $time < DateTime::createFromFormat("H:i:s", $bookings[$i]['EndTime'])
    ) {
      if($time->format("i") === "00")
        $firstHalf = "booked";
      elseif($time->format("i") === "30")
        $secondHalf = "booked";

    }
    if($i < count($bookings) && $time >= DateTime::createFromFormat("H:i:s" , $bookings[$i]['EndTime']))
      $i++;
    $time->modify("+30 minutes");
  }
  // $result = "";
  // for ($i=8; $i <= 16; $i++) { 
  //   $result = $result . 
  //   "<div class='hour'>" .
  //     "<div class='time'>" .
  //       "<h1>$i:00</h1>" .
  //     "</div>" .
  //     "<div class='timeSpan'>" .
  //       "<div id='firstHalf' class=''></div>" . 
  //       "<div id='secondHalf' class=''></div>" .
  //     "</div>" .
  //   "</div>";
  // }

  return $result;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Bookings</title>
  <link rel="stylesheet" href="viewBookingsStyle.css">
</head>
<body>
  <nav>
    <form action="viewBookings.php" method="POST">
      <input type="date" name="date" min="<?=date("Y-m-d")?>" value="<?=date("Y-m-d")?>"/>
      <input type="submit" value="View Bookings"/>
    </form>
  </nav>
  <p><?=validation()?></p>
  <main>
    <?=printTimeline()?>
  </main>
</body>
</html>