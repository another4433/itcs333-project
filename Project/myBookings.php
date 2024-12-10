<?php
require "functions.php";
    $personID=1;
    $msg="";
     try{
        require_once('connection.php');
        $sql="SELECT * FROM ROOM ";
        $stmt=$db->prepare($sql);
        $stmt->execute();
        if($stmt->rowcount()==0){
            $msg="there's no room";
        }
        $sql1="SELECT distinct Location FROM room";
        $stmt1=$db->prepare($sql1);
        $stmt1->execute();
    }catch(PDOException $ex) {
        echo "there's error";
        die ($ex->getMessage());
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
              "<div class='room'>". 
                "<div class='room-image'>". 
                  "<img src='Images\\$booking[ImageName]'>". 
                "</div>". 
                "<h3>{$booking['RoomName']}</h3>". 
                "<div class='room-info'>". 
                  "<p>{$booking['Date']}</p>". 
                  "<div class='timeLine'>". 
                    "<p class='startTime'>{$booking['StartTime']}</p>". 
                    "<p class='endTime'>{$booking['EndTime']}</p>". 
                  "</div>". 
                  "<div class='roomInfo'>". 
                    "<p class='location'>{$booking['Location']}</p>". 
                  "</div>". 
                "</div>". 
                "<form action='newMyBookings.php' method='POST'>". 
                  "<input type='hidden' name='roomID' value='<?={$booking['RoomID']}?>'>". 
                  "<input type='hidden' name='personID' value='<?={$booking['PersonID']}?>'>". 
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
      <div class="msg">
        <h3><?php if(isset($msg)) echo $msg; ?></h3>
        <h3><?php if(isset($msg2)) echo $msg2; ?></h3>
      </div>
      <?=printBookings()?>
    </div>
  </div>
</body>
</html>