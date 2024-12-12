<?php
session_start();

if(!isset($_SESSION['userID']))
  exit("you are not authorized to enter this room");

$status = '';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookingdb";

function emailValid($email):bool {
  $userDomain = explode('@', $email)[1];
  if ($userDomain === "stu.uob.edu.bh" || $userDomain === "uob.edu.bh")
    return true;

    return false;
}
try {
    $dsn = "mysql:host=$servername;dbname=$dbname";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $userId = $_SESSION['userID'];
    $sql = "SELECT * FROM person WHERE PersonID = :personID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['personID' => $userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!isset($row['ImageName']))
      $row['ImageName'] = "default.png";

    if ($row) {
        $firstName = $row['FirstName'];
        $lastName = $row['LastName'];
        $email = $row['Email'];
        $imageName = $row['ImageName']; // Default profile picture
    } else {
        echo "User not found!";
        exit();
    }

    // Update profile data if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && emailValid(htmlspecialchars($_POST['email']))) {
        $firstName = htmlspecialchars($_POST['firstName']);
        $lastName = htmlspecialchars($_POST['lastName']);
        $email = htmlspecialchars($_POST['email']);

        // Update profile picture if uploaded
        if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'Images/';
            $destPath = $uploadDir . basename($_FILES['profile-pic']['name']);
            
            if (move_uploaded_file($_FILES['profile-pic']['tmp_name'], $destPath)) {
                $imageName = $destPath;
            } else {
                echo "Error uploading the profile picture.";
            }
        } elseif ($_FILES['profile-pic']['error'] === UPLOAD_ERR_NO_FILE){
          $imageName = "Images/default.png";
        }


        // Update profile in the database
        $updateSql = "UPDATE person SET FirstName = :firstName, LastName = :lastName, Email = :email, ImageName = :imageName WHERE PersonID = :personID";
        $updateStmt = $pdo->prepare($updateSql);
        $updateData = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'imageName' => $imageName,
            'personID' => $userId
        ];

      if($updateStmt->execute($updateData)) {
        $status =  "<h1>Profile updated successfully!</h1>";
      } else {
        $status = "<h1>Error updating the profile.</h1>";
      }
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && !emailValid(htmlspecialchars($_POST['email'])))
  $status = "<h1>Invalid Email</h1>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Management</title>
    <link rel="stylesheet" href="style_profile.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <nav>
            <ul>
                <li><a href="browsing.php">browse room</a></li>
            </ul>
        </nav>
    </header>
    <?=$status?>
    <!-- Profile Container -->
    <div class="profile-container">
        <h1>My Profile</h1>

        <div class="profile-display">
            <img src="<?php echo "$imageName" ?>" alt="Profile Picture" class="profile-picture">
            <h2><?php echo $firstName . " " . $lastName; ?></h2>
            <p>Email: <?php echo $email; ?></p>
        </div>

        <div class="profile-edit-form">
            <h2>Edit Profile</h2>
            <form action="profile.php" method="post" enctype="multipart/form-data">
                <label for="firstName">First Name:</label>
                <input type="text" name="firstName" id="firstName" value="<?php echo $firstName; ?>" required>

                <label for="lastName">Last Name:</label>
                <input type="text" name="lastName" id="lastName" value="<?php echo $lastName; ?>">

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo $email; ?>" required>

                <label for="profile-pic">Profile Picture:</label>
                <input type="file" name="profile-pic" id="profile-pic" accept="image/*">

                <button type="submit">Update Profile</button>
            </form>
        </div>
    </div>
</body>
</html>