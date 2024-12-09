<?php
require "function.php";
if($_SERVER['REQUEST_METHOD'] !== "POST") {
  exit("Invalid request");
  return;
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

header("Location: myBookings.php");
exit();