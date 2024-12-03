<?php
    class user {
        protected int $userId = 0, $hasAdmin = 0;
        protected string $email = "alimohamedhassan9@outlook.com"; 
        protected string $password = "hidden"; 
        protected string $firstName = "Ali"; 
        protected string $lastName = "Hassan"; 
        protected string $image = "not available";
        protected function __construct($userId, $email, $password, $firstName, $lastName, $hasAdmin, $image)
        {
            $this->userId = $userId;
            $this->email = $email;
            $this->password = $password;
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->hasAdmin = $hasAdmin;
            $this->image = $image;
        }
        public function getUserId(){
            return $this->userId;
        }
        public function getEmail(){
            return $this->email;
        }
        public function getPassword(){
            return $this->password;
        }
        public function getFirstName(){
            return $this->firstName;
        }
        public function getLastName(){
            return $this->lastName;
        }
        public function isAdmin(){
            return $this->hasAdmin;
        }
        public function getImage(){
            return $this->image;
        }
    }
    class Room {
        protected int $roomId = 0;
        protected string $location = "S40";
        protected int $size = 40;
        protected string $roomName = "S40-2045";
        protected int $hasPC = 1, $hasProjector = 1;
        protected string $image = "not available";
        protected function __construct($roomId, $location, $size, $roomName, $hasPC, $hasProjector, $image){
            $this->roomId = $roomId;
            $this->location = $location;
            $this->size = $size;
            $this->roomName = $roomName;
            $this->hasPC = $hasPC;
            $this->hasProjector = $hasProjector;
            $this->image = $image;
        }
        public function getRoomId(){
            return $this->roomId;
        }
        public function getLocation(){
            return $this->location;
        }
        public function getSize(){
            return $this->size;
        }
        public function getRoomName(){
            return $this->roomName;
        }
        public function getHasPC(){
            return $this->hasPC;
        }
        public function getHasProjector(){
            return $this->hasProjector;
        }
        public function getImage(){
            return $this->image;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Administrator Dashboard</title>
        <meta name="Data Encoder" charset="utf-8">
        <meta name="Possible Page Themes" content="dark white">
        <meta name="Scaling Compatibility" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
        <link rel="stylesheet" href="Style_profile.css">
    </head>
</html>
<body>
    <?php
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=bookingdb;charset=utf8mb4;port=3306", "root", "");
        } 
        catch(PDOException $pDOException){
            echo "There's an error in the database connection.";
            print_r("Error message: ");
            print_r($pDOException->getMessage());
            die();
        }
    ?>
    <header>Room Booking System Administrator Dashboard</header><br>
    <div class="pico">
        <form action="system.administrator.php" method="get">
            <label for="views">Choose one of the following views: </label><br>
            <input name="views" id="pView1" type="radio" value="Users"><p>Users</p><br>
            <input name="views" id="pView2" type="radio" value="Rooms" default><p>Rooms</p><br>
            <input name="views" id="pView3" type="radio" value="Bookings"><p>Bookings</p><br>
        </form><br>
    </div>
    <?php
        try {
            if (isset($_GET["views"])):
                $view_selector = $_GET["views"];
                if ($view_selector == "Users"):
                    $stmt = $pdo->query("SELECT * FROM `person`");
    ?>
    <table border="3px" class="container">
        <tr>
            <th>Person ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>HasAdmin</th>
            <th>Image File Name</th>
        </tr>
        <tr>
            <?php
                foreach ($stmt as $row):
            ?>
            <td><?= $row["`PersonID`"];?></td>
            <td><?= $row["`FirstName`"];?></td>
            <td><?= $row["`LastName`"];?></td>
            <td><?= $row["`Email`"];?></td>
            <td><?= $row["`Password`"];?></td>
            <td><?= $row["`hasAdmin`"];?></td>
            <td><?= $row["`ImageName`"];?></td>
            <?php
                endforeach; 
            ?>
        </tr>
    </table><br>
    <?php
                elseif ($view_selector == "Rooms"):
                    $rows = $pdo->query("SELECT * from `room`");
    ?>
    <table border="3px" class="container">
        <tr>
            <th>Room ID</th>
            <th>Room Name</th>
            <th>Location</th>
            <th>Capacity</th>
            <th>HasPCs</th>
            <th>HasProjectors</th>
            <th>Image File Name</th>
        </tr>
        <tr>
            <?php
                foreach($rows as $row):
            ?>
            <td><?= $row["`RoomID`"];?></td>
            <td><?= $row["`RoomName`"];?></td>
            <td><?= $row["`Location`"];?></td>
            <td><?= $row["`Capacity`"];?></td>
            <td><?= $row["`HasPCs`"];?></td>
            <td><?= $row["`HasProjectors`"];?></td>
            <td><?= $row["`ImageName`"];?></td>
            <?php
                endforeach;
            ?>
        </tr>
    </table><br>
    <?php
                elseif ($view_selector == "Bookings"):
                    $rows = $pdo->query("SELECT * FROM `person` P, `room` R, `booking` B");
    ?>
    <table border="3px" class="container">
        <tr>
            <th>Person ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>HasAdmin</th>
            <th>Person Image File Name</th>
            <th>Room ID</th>
            <th>Room Name</th>
            <th>Location</th>
            <th>Capacity</th>
            <th>HasPCs</th>
            <th>HasProjectors</th>
            <th>Room Image File Name</th>
            <th>Booking Person ID</th>
            <th>Booking Room ID</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Booking Description</th>
        </tr>
        <tr>
            <?php
                foreach($rows as $row):
            ?>
            <td><?= $row["P.`PersonID`"];?></td>
            <td><?= $row["P.`FirstName`"];?></td>
            <td><?= $row["P.`LastName`"];?></td>
            <td><?= $row["P.`Email`"];?></td>
            <td><?= $row["P.`Password`"];?></td>
            <td><?= $row["P.`hasAdmin`"];?></td>
            <td><?= $row["P.`ImageName`"];?></td>
            <td><?= $row["R.`RoomID`"];?></td>
            <td><?= $row["R.`RoomName`"];?></td>
            <td><?= $row["R.`Location`"];?></td>
            <td><?= $row["R.`Capacity`"];?></td>
            <td><?= $row["R.`HasPCs`"];?></td>
            <td><?= $row["R.`HasProjectors`"];?></td>
            <td><?= $row["R.`ImageName`"];?></td>
            <td><?= $row["B.`PersonID`"];?></td>
            <td><?= $row["B.`RoomID`"];?></td>
            <td><?= $row["B.`StartTime`"];?></td>
            <td><?= $row["B.`EndTime`"];?></td>
            <td><?= $row["B.`StartDate`"];?></td>
            <td><?= $row["B.`EndDate`"];?></td>
            <td><?= $row["B.`Description`"];?></td>
            <?php
                endforeach;
            ?>
        </tr>
    </table><br>
    <?php
                endif;
            endif;
        }
        catch(PDOException $pDOException){
            die("There's a problem with the database.");
        } 
        catch(Exception $e){
            die("Error details: ".$e->getMessage());
        }
    ?>
    <div class="pico">
        <form action="system_administrator.php" method="post">
            <label for="actions">Choose your action: </label><br>
            <input name="actions" id="pAction1" type="radio" value="Add Person"><p>Add Person</p><br>
            <input name="actions" id="pAction2" type="radio" value="Delete Person"><p>Delete Person</p><br>
            <input name="actions" id="pAction3" type="radio" value="Add Room"><p>Add Room</p><br>
            <input name="actions" id="pAction4" type="radio" value="Edit Room"><p>Edit Room</p><br>
            <input name="actions" id="pAction5" type="radio" value="Delete Room"><p>Delete Room</p><br>
            <input name="actions" id="pAction6" type="radio" value="Cancel Action" default><p>Cancel Action</p><br>
        </form><br>
    </div>
    <?php
        try {
            if (isset($_POST["actions"])):
                $action_selection = $_POST["actions"];
                if ($action_selection == "Add Person"):
                    $stmt = $pdo->prepare("INSERT INTO `person`(PersonID, FirstName, LastName, Email, Password, hasAdmin, ImageName) values (?, ?, ?, ?, ?, ?, ?)");
    ?>
    <div class="pico">
        <form action="system_administrator.php" method="post">
            <label for="id">Enter the id for person: </label>
            <input name="id" id="pid" type="number" placeholder="Identification number of the person"><br>
            <label for="Fname">Enter the first name for person: </label>
            <input name="Fname" id="pFName" type="text" placeholder="First name of the person"><br>
            <label for="Lname">Enter the last name for person: </label>
            <input name="Lname" id="pLName" type="text" placeholder="Last name of the person"><br>
            <label for="email">Enter the email for person: </label>
            <input name="email" id="pEmail" type="email" placeholder="Email of the person"><br>
            <label for="password">Enter the password for person: </label>
            <input name="password" id="Password" type="text" placeholder="Password for the person"><br>
            <input name="submit" id="pSubmit" type="submit" value="Submit"><br> 
        </form>
    </div><br>
    <?php
                    if (isset($_POST["submit"]) && $_POST["submit"] == "Submit"):
                        if (isset($_POST["id"]) && isset($_POST["email"]) && isset($_POST["password"])):
                            $person = new user($_POST["id"], $_POST["email"], $_POST["password"], $_POST["Fname"], $_POST["Lname"], 0, "not available");
                            $stmt->execute([
                                'PersonID' => $person->getUserId(),
                                'FirstName' => $person->getFirstName(),
                                'LastName' => $person->getLastName(),
                                'Email' => $person->getEmail(),
                                'Password' => $person->getPassword(),
                                'hasAdmin' => $person->isAdmin(),
                                'ImageName' => $person->getImage()
                            ]);
                            echo "New record inserted with ID: " . $pdo->lastInsertId()."<br>";
                        endif;
                    endif;
                elseif ($action_selection == "Delete Person"):
    ?>
    <div class="pico">
        <form action="system_administrator.php" method="get">
            <label for="looking">Enter the id to look for person: </label>
            <input name="looking" id="pLooking" type="number" placeholder="Identification number of the person"><br>
            <input name="Submit" id="pSubmit2" type="submit" value="Submit"><br><br>
        </form>
    </div>
    <?php
                    $stmt = $pdo->prepare("DELETE FROM `person` WHERE PersonID = :personID");
                    if (isset($_GET["Submit"]) && isset($_GET["looking"]) && $_GET["Submit"] == "Submit"):
                        $stmt->bindParam(':personID', $_GET["looking"]);
                        $stmt->execute();
                        echo "Record have been deleted."."<br>";
                    endif;
                elseif ($action_selection == "Add Room"):
                    $statement = $pdo->prepare("INSERT INTO `room`(`RoomID`, `RoomName`, `Location`, `Capacity`, `HasPCs`, `HasProjectors`, `ImageName`) VALUES (?, ?, ?, ?, ?, ?, ?)");
    ?>
    <div class="pico">
        <form action="system_administrator.php" method="post">
            <label for="roomID">Enter the id of the room: </label>
            <input name="roomID" id="pRoomID" type="number" placeholder="Identification number of the room"><br>
            <label for="roomName">Enter the name of the room: </label>
            <input name="roomName" id="pRoomName" type="text" placeholder="Name of the room"><br>
            <label for="location">Enter the location of the room: </label>
            <input name="location" id="pLocation" type="text" placeholder="Location of the room"><br>
            <label for="capacity">Enter the capacity of the room: </label>
            <input name="capacity" id="size" type="number" placeholder="Capacity of the room"><br>
            <label for="hasPCs">Enter '1' if it contains PC otherwise '0': </label>
            <input name="hasPCs" id="PC" type="number" placeholder="Room contains PC?"><br>
            <label for="hasProjectors">Enter '1' if it contains Projector otherwise '0': </label>
            <input name="hasProjectors" id="Projector" type="number" placeholder="Room contains Projector?"><br>
            <input name="submit" id="pRoomSubmit" type="submit" value="Submit"><br><br>
        </form>
    </div>
    <?php
                    if (isset($_POST["submit"]) && isset($_POST["roomID"]) && $_POST["submit"] == "Submit"):
                        $roomDetails = new Room($_POST["roomID"], $_POST["location"], $_POST["capacity"], $_POST["roomName"], $_POST["hasPCs"], $_POST["hasProjectors"], $image);
                        $statement->execute([
                            'RoomID' => $roomDetails->getRoomId(),
                            'RoomName' => $roomDetails->getRoomName(),
                            'Location' => $roomDetails->getLocation(),
                            'Capacity' => $roomDetails->getSize(),
                            'HasPCs' => $roomDetails->getHasPC(),
                            'HasProjectors' => $roomDetails->getHasProjector(),
                            'ImageName' => $roomDetails->getImage()
                        ]);
                        echo "New record inserted with ID: " . $pdo->lastInsertId()."<br>";
                    endif;
                elseif ($action_selection == "Delete Room"):
    ?>
    <div class="pico">
        <form action="system_administrator.php" method="get">
            <label for="looking">Enter the id to look for room: </label>
            <input name="looking" id="pRoomLook" type="number" placeholder="Identification number of the room"><br>
            <input name="Submit" id="pRoomSubmit2" type="submit" value="Submit"><br><br>
        </form>
    </div>
    <?php
                    $action = $pdo->prepare("DELETE FROM `room` WHERE `RoomID` = :roomID");
                    if (isset($_GET["Submit"]) && isset($_GET["looking"]) && $_GET["Submit"] == "Submit"):
                        $action->bindParam(':roomID', $_GET["looking"]);
                        $action->execute();
                        echo "Record have been deleted."."<br>";
                    endif;
                elseif ($action_selection == "Edit Room"):
    ?>
    <div class="pico">
        <form action="system_administrator.php" method="post">
            <label for="oldID">Enter the id of the current room: </label>
            <input name="oldID" id="pOldID" type="number" placeholder="Identification number of the old room"><br>
            <label for="newroomName">Enter the name of the room: </label>
            <input name="newroomName" id="pNewRoomName" type="text" placeholder="New Name of the room"><br>
            <label for="newlocation">Enter the location of the room: </label>
            <input name="newlocation" id="pNewLocation" type="text" placeholder="New Location of the room"><br>
            <label for="newcapacity">Enter the capacity of the room: </label>
            <input name="newcapacity" id="newsize" type="number" placeholder="New Capacity of the room"><br>
            <label for="newhasPCs">Enter '1' if it contains PC otherwise '0': </label>
            <input name="newhasPCs" id="newPC" type="number" placeholder="New Room contains PC?"><br>
            <label for="newhasProjectors">Enter '1' if it contains Projector otherwise '0': </label>
            <input name="newhasProjectors" id="newProjector" type="number" placeholder="New Room contains Projector?"><br>
            <input name="newsubmit" id="pNewRoomSubmit" type="submit" value="Submit"><br><br>
        </form>
    </div>
    <?php
                    if (isset($_POST["newSubmit"]) && isset($_POST["oldID"]) && $_POST["newSubmit"] == "Submit"):
                        $change_room = new Room($_POST["oldID"], $_POST["newlocation"], $_POST["newcapacity"], $_POST["newroomName"], $_POST["newhasPCs"], $_POST["newhasProjectors"], $image);
                        $data = [
                            'RoomName' => $change_room->getRoomName(),
                            'Location' => $change_room->getLocation(),
                            'Capacity' => $change_room->getSize(),
                            'HasPCs' => $change_room->getHasPC(),
                            'HasProjectors' => $change_room->getHasProjector(),
                            'ImageName' => $change_room->getImage()
                        ];
                        $concat = "";
                        foreach ($data as $single):
                            $concat = $concat.$single." ";
                        endforeach;
                        $concat = rtrim($concat, ',');
                        $temporary = $pdo->prepare("UPDATE `room` SET $concat WHERE `RoomID` = :roomID");
                        $temporary->bindParam(":roomID", $_POST["oldID"]);
                        $data['RoomID'] = $change_room->getRoomId();
                        $temporary->execute($data);
                        echo "Record updated successfully!";
                    endif;
                endif;
            endif;
        }
        catch(PDOException $pDOException){
            die("There's a problem with the database.");
        }
        catch(Exception $exception){
            echo "There's an error in the website.";
            print_r("Error details: ");
            print_r($exception->getMessage());
            die();
        }
    ?>
</body>