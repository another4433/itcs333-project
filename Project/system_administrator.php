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
    function Bin($onOrOff){
        if (isset($onOrOff))
            return 1;
        return 0;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <script>
        window.console.log("The \"user\" and \"room\" classes have been created to simplify adding records to the database.");
        window.console.time();
        function userCreated(){
            window.alert("Thank you for creating a record to \"user\" table in the database.");
            window.console.warn("A record have been added to \"user\" table in the database.");
            window.console.time();
        }
        function userDeleted(){
            window.alert("Thank you for deleting a record from \"user\" table in the database.");
            window.console.warn("A record have been deleted from \"user\" table in the database.");
            window.console.time();
        }
        function roomCreated(){
            window.alert("Thank you for creating a record to \"room\" table in the database.");
            window.console.warn("A record have been added to \"room\" table in the database.");
            window.console.time();
        }
        function roomDeleted(){
            window.alert("Thank you for deleting a record from \"room\" table in the database.");
            window.console.warn("A record have been deleted from \"room\" table in the database.");
            window.console.time();
        }
        function roomModified(){
            window.alert("Thank you for modifiying a record in \"room\" table in the database.");
            window.console.warn("A record have been modified from \"room\" table in the database.");
            window.console.time();
        }
        function userDisplayed(){
            window.alert("The record of \"user\" table from the database have been displayed.");
            window.console.log("Records for \"user\" table have been displayed.");
            window.console.time();
        }
        function roomDisplayed(){
            window.alert("The record of \"room\" table from the database have been displayed.");
            window.console.log("Records for \"room\" table have been displayed.");
            window.console.time();
        }
        function bookingDisplayed(){
            window.alert("The record of \"booking\" table from the database have been displayed.");
            window.console.log("Records for \"room\" table have been displayed.");
            window.console.time();
        }
        function selectedChoice(){
            window.confirm.console.log("Nothing happened");
            window.console.time();
        }
    </script>
    <head>
        <title>Administrator Dashboard</title>
        <meta name="Data Encoder" charset="utf-8">
        <meta name="Possible Page Themes" content="dark white">
        <meta name="Scaling Compatibility" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
        <link rel="stylesheet" href="Style_admin.css">
    </head>
</html>
<body>
    <?php
        $error = "";
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=bookingdb;charset=utf8mb4;port=3306", "root", "");
        } 
        catch(PDOException $pDOException){
            $error = "Error message: ".$pDOException->getMessage();
            die();
        }
    ?>
    <?php if ($error !== ""): ?>
    <script>
        window.alert("There's an error in the database connection.");
        window.console.error("There's an error in the database connection.");
        window.console.time();
    </script>
    <h1 class="error"><?= $error; ?></h1>
    <?php $error = ""; ?>
    <?php endif; ?>
    <header>Room Booking System Administrator Dashboard</header><br>
    <div class="pico">
        <form action="system.administrator.php" method="get">
            <label for="views">Choose one of the following views: </label><br>
            <input name="views" id="pView1" type="radio" value="Users" onclick="userDisplayed()"><p>Users</p><br>
            <input name="views" id="pView2" type="radio" value="Rooms" onclick="roomDisplayed()"><p>Rooms</p><br>
            <input name="views" id="pView3" type="radio" value="Bookings" onclick="bookingDisplayed()" default><p>Bookings</p><br>
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
                    $rows = $pdo->query("SELECT P.`PersonID, `R.`RoomID`, R.`RoomName`, B.`StartDate`, B.`StartTime`, B.`EndDate`, B.`EndTime`
                    FROM `person` P, `room` R, `booking` B WHERE P.`PersonID` = B.`PersonID` AND R.`RoomID` = B.`RoomID`");
    ?>
    <table border="3px" class="container">
        <tr>
            <th>Person ID</th>
            <th>Person Email</th>
            <th>Room ID</th>
            <th>Room Name</th>
            <th>Start Date</th>
            <th>Start Time</th>
            <th>End Date</th>
            <th>End Time</th>
        </tr>
        <tr>
            <?php
                foreach($rows as $row):
            ?>
            <td><?= $row["P.`PersonID`"]; ?></td>
            <td><?= $row["R.`RoomID`"];?></td>
            <td><?= $row["R.`RoomName`"];?></td>
            <td><?= $row["B.`StartDate`"];?></td>
            <td><?= $row["B.`StartTime`"];?></td>
            <td><?= $row["B.`EndDate`"];?></td>
            <td><?= $row["B.`EndTime`"];?></td>
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
            $error = "Error message: ".$pDOException->getMessage();
            die("There's a problem with the database.");
        } 
        catch(Exception $e){
            $error = "Error details: ".$e->getMessage();
            die();
        }
    ?>
    <?php if ($error !== ""): ?>
    <script>
        window.alert("An error occured in the website.");
        window.console.error("There's an error in the website.");
        window.console.info("The error could be database connection or general error.");
        window.console.time();
    </script>
    <h1 class="error"><?= $error; ?></h1>
    <?php $error = ""; ?>
    <?php endif; ?>
    <div class="pico">
        <form action="system_administrator.php" method="post">
            <label for="actions">Choose your action: </label><br>
            <input name="actions" id="pAction1" type="radio" value="Add Person"><p>Add Person</p><br>
            <input name="actions" id="pAction2" type="radio" value="Delete Person"><p>Delete Person</p><br>
            <input name="actions" id="pAction3" type="radio" value="Add Room"><p>Add Room</p><br>
            <input name="actions" id="pAction4" type="radio" value="Edit Room"><p>Edit Room</p><br>
            <input name="actions" id="pAction5" type="radio" value="Delete Room"><p>Delete Room</p><br>
            <input name="actions" id="pAction6" type="radio" value="Cancel Action" onclick="selectedChoice()" default><p>Cancel Action</p><br>
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
    ?>
    <script>userCreated();</script>
    <?php
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
    ?>
    <script>userDeleted();</script>
    <?php
                    endif;
                elseif ($action_selection == "Add Room"):
                    $statement = $pdo->prepare("INSERT INTO `room`(`RoomID`, `RoomName`, `Location`, `Capacity`, `HasPCs`, `HasProjectors`, `ImageName`) VALUES (?, ?, ?, ?, ?, ?, ?)");
    ?>
    <div class="pico">
        <form action="system_administrator.php" method="post" enctype="multipart/form-data">
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
            <label for="ImageName">Upload room image here: </label>
            <input type="file" name="ImageName" accept="image/*" placeholder="File path for image file"><br>
            <input name="submit" id="pRoomSubmit" type="submit" value="Submit"><br><br>
        </form>
    </div>
    <?php
                    if (isset($_POST["submit"]) && isset($_POST["roomID"]) && isset($_FILES["ImageName"]) && $_POST["submit"] == "Submit"):
                        $fileName = basename($_FILES["ImageName"]);
                        $roomDetails = new Room($_POST["roomID"], $_POST["location"], $_POST["capacity"], $_POST["roomName"], $_POST["hasPCs"], $_POST["hasProjectors"], $fileName);
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
                        $pattern = "{(\w+).(\w+)}";
                        if (preg_match($pattern, $fileName)){
                            print_r("Database have been interacted without any problem.");
                        }
                        else {
                            print_r("There might be a problem in the database.");
                        }
                    endif;
                    if ($_SERVER['REQUEST_METHOD'] == "POST"):
                        if (!isset($_FILES['ImageName']) || $_FILES['ImageName']['error'] == UPLOAD_ERR_NO_FILE)
                            exit('No file was Uploaded');
                        $image = $_FILES['ImageName'];
                        $imageName = basename($image['name']);
                        $fileExt = "." . pathinfo($imageName, PATHINFO_EXTENSION);
                        $targetDir = 'Images/';
                        $imageName = "";
                         do {
                            $uniqueName = uniqid();
                            $targetFile = $targetDir . $uniqueName . $fileExt;
                        } while (file_exists($targetFile));
                        if (move_uploaded_file($image['tmp_name'], $targetFile)):
    ?>
    <script>window.alert("File uploaded successfully");</script>
    <?php
                        else:
    ?>
    <script>window.alert("An error occured when uploading file.");</script>
    <?php
                            die();
                        endif;
                    endif;
    ?>
    <script>roomCreated();</script>
    <?php
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
    ?>
    <script>roomDeleted();</script>
    <?php
                elseif ($action_selection == "Edit Room"):
    ?>
    <div class="pico">
        <form action="system_administrator.php" method="post" enctype="multipart/form-data">
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
            <label for="editFile">Upload the new image file here: </label>
            <input type="file" name="editFile" accept="image/*" placeholder="File path for new image"><br>
            <input name="newsubmit" id="pNewRoomSubmit" type="submit" value="Submit"><br><br>
        </form>
    </div>
    <?php
                    if (isset($_POST["newSubmit"]) && isset($_POST["oldID"]) && $_POST["newSubmit"] == "Submit"):
                        $newFile = basename($_FILES["editFile"]);
                        $change_room = new Room($_POST["oldID"], $_POST["newlocation"], $_POST["newcapacity"], $_POST["newroomName"], $_POST["newhasPCs"], $_POST["newhasProjectors"], $newFile);
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
                        $temporary->execute($data);
                        $pattern = "{(\w+).(\w+)}";
                        if (preg_match($pattern, $editFile)){
                            print_r("Database have been interacted without any problem.");
                        }
                        else {
                            print_r("There might be a problem in the database.");
                        }
                    endif;
                    if ($_SERVER['REQUEST_METHOD'] == "POST"):
                        if (!isset($_FILES['ImageName']) || $_FILES['ImageName']['error'] == UPLOAD_ERR_NO_FILE)
                            exit('No file was Uploaded');
                        $image = $_FILES['ImageName'];
                        $imageName = basename($image['name']);
                        $fileExt = "." . pathinfo($imageName, PATHINFO_EXTENSION);
                        $targetDir = 'Images/';
                        $imageName = "";
                         do {
                            $uniqueName = uniqid();
                            $targetFile = $targetDir . $uniqueName . $fileExt;
                        } while (file_exists($targetFile));
                        if (move_uploaded_file($image['tmp_name'], $targetFile)):
    ?>
    <script>window.alert("File uploaded successfully");</script>
    <?php
                        else:
    ?>
    <script>window.alert("An error occured when uploading file.");</script>
    <?php
                            die();
                        endif;
                    endif;
    ?>
    <script>roomModified();</script>
    <?php
                endif;
            endif;
        }
        catch(PDOException $pDOException){
            $error = "Error details: ".$pDOException->getMessage();
            die("There's a problem with the database.");
        }
        catch(TypeError $typeError){
            $error = "Error details: ".$typeError->getMessage();
            die("Mismatch data type from input.");
        }
        catch(Exception $exception){
            $error = "Error details: ".$exception->getMessage();
            die();
        }
    ?>
    <?php if ($error !== ""): ?>
    <script>
        window.alert("An error occured in the website.");
        window.console.error("There's an error in the website.");
        window.console.info("The error could be database connection or general error.");
        window.console.time();
    </script>
    <h1 class="error"><?= $error; ?></h1>
    <?php $error = ""; ?>
    <?php endif; ?>
</body>