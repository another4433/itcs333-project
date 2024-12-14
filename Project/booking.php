<?php
require "functions.php";
require "bahrainTimezone.php";
session_start();

if(!isset($_SESSION['userID']) || !isset($_SESSION['roomID']))
  exit("You are not authorized to view this page");

$connection = databaseConnect();
$sql = "Select RoomName From room where roomID = :roomID";
$result = dbQuery($connection, $sql, [
  ':roomID' => $_SESSION['roomID']
]);

if(isset($result['error']))
  exit($result['error']);

$roomName = $result[0]['RoomName'];


$personID = $_SESSION['userID'];

function printStatus(string $status, string $message): String {
  return ""  .
        "<div class='status $status'>" . 
          "<h2>$message</h2>" .
        "</div>";
}
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
          "WHERE booking.Date = :date AND " . 
          "booking.RoomID = :roomID ";



  $result = dbQuery($connection, $sql, [
    ":date" => $date->format("Y-m-d"),
    ":roomID" => $_SESSION['roomID']
  ]);

  if(isset($result['error']))
    exit($result['error']);

  $bookings = $result;
  $bookings = sortTime($bookings);
  return helperTimeline($bookings);
}

function helperTimeline($bookings) {
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
  if($date->format("l") === "Friday") {
    $message = "You can't book a room on friday";
    return printStatus("red", $message);
  } 
  if($startTime > $endTime) {
    $message = "The start time of the booking must be less than the end time.";
    return printStatus("red", $message);
  }
  if($startTime == $endTime) {
    $message =  "The start time of the booking must not be equal to the end time.";
    return printStatus("red", $message);
  }
  if(
    $date == DateTime::createFromFormat("Y-m-d", date("Y-m-d")) && 
    $startTime <= DateTime::createFromFormat("H:i:s" ,date("H:i:s"))
  ) {
    $message = "Invalid booking, the date and time of your booking should be less than the current date and time";
    return printStatus("red" , $message);
  }

  $status = conflictCheck($date, $_SESSION['roomID'], $startTime, $endTime);
  if($status !== "good") {
    return printStatus("red", $status);
  }

  $connection = databaseConnect();


  $sql = "INSERT INTO booking (PersonID, RoomID, StartTime, EndTime, Date, Description)" . 
        "Values(:personID, :roomID, :startTime, :endTime, :date, :description)";


  $result = dbQuery($connection, $sql, [
    ":personID" => $_SESSION['userID'],
    ":roomID" => $_SESSION['roomID'],
    ":startTime" => $startTime->format("H:i:s"),
    ":endTime" => $endTime->format("H:i:s"),
    ":date" => $date->format("Y-m-d"),
    ":description" => htmlspecialchars($_POST['description'])
  ]);

  if(isset($result['error']))
    exit($result['error']);

  return printStatus("green", "Booking Successful");
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
  <header>
    <nav>
      <h2>Add Booking</h2>
      <a href="browsing.php">browsing</a>
    </nav>
    <?=addBooking()?>
  </header>
  <main>
    <h2><?=$roomName?></h2>
    <form class="bookingForm" action=booking.php method="POST">
      <label>Duration: </label>
      <div class="duration">
        <select id="startTime" name="startTime">
          <?=printTimes()?>
        </select>
        <select name="endTime">
          <?=printTimes()?>
        </select>
      </div>
      <label for="date">Date:</label>
      <div class="date">
        <input type="date" id="date" name="date" min="<?=date("Y-m-d")?>" required/>
      </div>
      <label for="description">Description:</label>
      <textarea id="description" name="description"></textarea>
      <input type="hidden" name="requestType" value="addBooking">
      <input type="submit" value="Submit"/>
    </form>
    <form class="timelineTable" action="booking.php" method="POST">
      <input type="date" name="date" min="<?=date("Y-m-d")?>" value="<?=date("Y-m-d")?>"/>
      <input type="hidden" name="requestType" value="generateTimeline">
      <input type="submit" value="View Bookings"/>
      <?=printTimeline()?>
    </form>
  </main>
</body>
</html>