<?php
function databaseConnect():PDO {
  $database = "mysql";
  $db = "bookingdb";
  $host = "localhost";
  $username = "root";
  $password = "";
  $dns = "$database:host=$host;dbname=$db";
  return new PDO($dns, $username, $password);
}

function dbQuery(PDO $connection, string $sql, array $paramaters): array {
  $statement = $connection->prepare($sql);
  try {
    $statement->execute($paramaters);
  } catch (PDOException $e) {
    return ['error' => $e->getMessage()];
  }
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function conflictCheck(DateTime $date, DateTime $startTime,DateTime $endTime): string {
  $connection = databaseConnect();

  $sql = "SELECT * FROM booking " . 
          "WHERE :date = booking.Date AND " .
          ":startTime <= booking.EndTime AND :startTime >= booking.StartTime OR "  . 
          ":endTime <= booking.EndTime AND :endTime >= booking.StartTime ";

  $result = dbQuery($connection, $sql, [
    ":date" => $date->format("Y-m-d"),
    ":startTime" => $startTime->format("H:i:s"),
    ":endTime" => $endTime->format("H:i:s")
  ]);

  if(isset($result['error']))
    return $result['error'];

  if(count($result) > 0) 
    return "Your booking is conflicting with another booking";

  return "good";
}

//function containsFri(DateTime $firstDate, DateTime $secondDate):bool {
//  $temp = new DateTime($firstDate->format("Y-m-d"));
//  while($temp <= $secondDate) {
//    if($temp->format("N") == 5)
//      return true;
//
//    $temp->modify('+1 day');
//  }
//
//  return false;
//}