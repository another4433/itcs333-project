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




function validation() {
  if($_SERVER['REQUEST_METHOD'] !== "POST")
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
    <title>Booking</title>
  </head>
  <body>
    <p><?=validation()?></p>
    <header>Booking</header>
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
      <input type="submit" value="Submit"/>
    </form>
    <a href="viewBookings.php">
      <button>hello world</button>
    </a>
  </body>
</html>