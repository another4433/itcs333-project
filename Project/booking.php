<?php
require "functions.php";
$user = "test@stu.uob.edu.bh";
$_SESSION["userID"] = $user;

if(!isset($_SESSION['userID']))
  exit("You are not authorized to view this page");

function printTimes() {
  $zeroMinutes = "00";
  $thirtyMinutes = "30";
  $counter = 0;
  $result = "";
  for ($hours=8; $hours <= 16 ;) { 
    $counter = $counter % 2;
    if($counter === 0) {
      $minutes = $zeroMinutes;
    } else {
      $minutes = $thirtyMinutes;
    }
    $result = $result . "<option value=\"$hours:$minutes\">$hours:$minutes</option>";
    if($counter === 1)
      $hours++;
  
    $counter++;
  }

  $result = $result . '<option value="17:00">17:00</option>';

  return $result;
}

function printTimeline() {
  if($_SERVER['REQUEST_METHOD'] !== "POST")
    return "";

  if($_POST['requestType'] !== "generateTimeline")
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

  $bookings = $result;
  return helperTimeline($bookings);
}

function helperTimeline($bookings) {
  $bookings = [
    [
      "StartTime" => "08:30:00",
      "EndTime" => "12:30:00"
    ]
  ];
  $result = "";
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


function addBooking() {
  if($_SERVER['REQUEST_METHOD'] !== "POST" )
    return "";

  if($_POST['requestType'] !== "addBooking")
    return "";

  $date = DateTime::createFromFormat("Y-m-d", $_POST['date']);
  $startTime = DateTime::createFromFormat("H:i:s", $_POST['startTime'] . ":00");
  $endTime = DateTime::createFromFormat("H:i:s", $_POST['endTime'] . ":00");

  // if(containsFri($startDate, $endDate))
  //   return "Booking Invalid, your booking contained the day friday";
    
  //var_dump([
  //  $startDate,
  //  $startDate->format("Y-m-d"),
  //  $endDate,
  //  $endDate->format("Y-m-d"),
  //  $startTime,
  //  $startTime->format("H:i:s"),
  //  $endTime
  //]);
  //die(); 
  if($date->format("l") === "Friday")
    return "You can't book a room on friday";
  if($startTime > $endTime)
    return "The start Time of the booking must be less than the end Time.";
  if($startTime == $endTime)
    return "The start Time of the booking must not be equal to the end Time.";

  $status = conflictCheck($date, $startTime, $endTime);
  if($status !== "good") 
    return $status;
} 
?>

<html lang=en>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8"/>
  <link rel="stylesheet" href="booking.css">
  <title>Booking</title>
</head>
<body>
  <p><?=addBooking()?></p>
  <header>Booking</header>
  <main>
    <section class="bookingForm">
      <form action="booking.php" method="POST">
        <label>Time: 
          <select name="startTime">
            <?=printTimes()?>
          </select>
          <span>To</span>
          <select name="endTime">
            <?=printTimes()?>
          </select>
        </label><br>
        <label>Date:
          <input type="date" name="date" min="<?=date("Y-m-d")?>" value="<?=date("Y-m-d")?>" required/>
        </label><br>
        <label>Description:<br>
          <textarea name="description"></textarea>
        </label><br>
        <input type="hidden" name="requestType" value="addBooking">
        <input type="submit" value="Submit"/>
      </form>
    </section>
    <section class="timelineTable">
      <form action="booking.php" method="POST">
        <input type="date" name="date" min="<?=date("Y-m-d")?>" value="<?=date("Y-m-d")?>"/>
        <input type="hidden" name="requestType" value="generateTimeline">
        <input type="submit" value="View Bookings"/>
      </form>
      <?=printTimeline()?>
    </section>
  </main>
</body>
</html>