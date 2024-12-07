<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "bookingdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user_id']; 
$sql = "SELECT * FROM user_profiles WHERE id = $userId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $bio = $row['bio'];
    $profilePicture = $row['profile_picture'];
} else {
    $username = "Not set";
    $bio = "No bio";
    $profilePicture = "default-avatar.jpg"; 
}

// Update profile data if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $bio = htmlspecialchars($_POST['bio']);
    
    // Update profile picture if uploaded
    if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $destPath = $uploadDir . basename($_FILES['profile-pic']['name']);
        
        if (move_uploaded_file($_FILES['profile-pic']['tmp_name'], $destPath)) {
            $profilePicture = $destPath;
        } else {
            echo "Error uploading the profile picture.";
        }
    }

    // Update profile in the database
    $updateSql = "UPDATE user_profiles SET username = ?, bio = ?, profile_picture = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sssi", $username, $bio, $profilePicture, $userId);

    if ($stmt->execute()) {
        echo "<p>Profile updated successfully!</p>";
    } else {
        echo "<p>Error updating the profile: " . $conn->error . "</p>";
    }
}
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
                <li><a href="index.php">Home</a></li>
                <li><a href="profile.php">My Profile</a></li> 
            </ul>
        </nav>
    </header>

    <!-- Profile Container -->
    <div class="profile-container">
        <h1>My Profile</h1>

        <div class="profile-display">
            <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="profile-picture">
            <h2><?php echo $username; ?></h2>
            <p><?php echo $bio; ?></p>
        </div>

        <div class="profile-edit-form">
            <h2>Edit Profile</h2>
            <form action="profile.php" method="post" enctype="multipart/form-data">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo $username; ?>" required>

                <label for="bio">Bio:</label>
                <textarea name="bio" id="bio" rows="4"><?php echo $bio; ?></textarea>

                <label for="profile-pic">Profile Picture:</label>
                <input type="file" name="profile-pic" id="profile-pic" accept="image/*">

                <button type="submit">Update Profile</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
