<?php

require "functions.php";
$user = "test@stu.uob.edu.bh";
$_SESSION["userID"] = $user;

if(!isset($_SESSION['userID']))
  exit("You are not authorized to view this page");

$result = [];

function validation() {
  if($_SERVER['REQUEST_METHOD'] !== "POST")
    return "";

  $date = DateTime::createFromFormat("Y-m-d", $_POST['date']);

  $connection = databaseConnect();

  $sql = "SELECT StartTime, EndTime ". 
          "FROM booking ". 
          "WHERE StartDate <= :date AND " .
          "EndDate >= :date ";

  $currentDate = date("Y-m-d");
  $currentTime = date("H:s:i");

  if($_POST['date'] === $currentDate)
    $sql = $sql . "AND StartTime > '$currentTime'";


  $result = dbQuery($connection, $sql, [
    "date" => $date->format("Y-m-d")
  ]);

  if(isset($result['error']))
    return $result['error'];

  return "";
}

function printTimeLine(): string {
  $html = 
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
    <!-- <div class="hour">
      <div class="time">
        <h1><?=$i . ":00" ?></h1>
      </div>
      <div class="timeSpan"></div>
    </div> -->
  </main>
</body>
</html>