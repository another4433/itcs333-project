<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Management</title>
    <link rel="stylesheet" href="style_profile.css">
</head>
<body>
    <?php
    $username = "Abdulla Khamis";
    $bio = "Cyber Security Student";
    ?>
    <div class="profile-container">
        <h1>My Profile</h1>

        <div class="profile-display">
            <img src="man-with-beard-avatar-character-isolated-icon-free-vector.jpg" alt="Profile Picture" class="profile-picture">
            <h2><?php echo $username; ?></h2>
            <p><?php echo $bio; ?></p>
        </div>

        <div class="profile-edit-form">
            <h2>Edit Profile</h2>
            <form action="update_profile.php" method="post" enctype="multipart/form-data">
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

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = htmlspecialchars($_POST['username']);
        $bio = htmlspecialchars($_POST['bio']);

        if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $destPath = $uploadDir . basename($_FILES['profile-pic']['name']);

            if (move_uploaded_file($_FILES['profile-pic']['tmp_name'], $destPath)) {
                echo "Profile picture uploaded successfully.";
            } else {
                echo "Error uploading the profile picture.";
            }
        }

        echo "<p>Profile updated: $username</p>";
        echo "<p>Bio: $bio</p>";
    }
    ?>
</body>
</html>