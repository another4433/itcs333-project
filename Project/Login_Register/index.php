<?php
    session_start();
    include("email_verify.php");

    if(isset($_SESSION['userID'])) {
      header("Location: ../browsing.php");
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    
        if (empty($email) || empty($password)) {
            $error = "<p class='error'>Please fill all fields!</p>";
        } else {
            verifyUOB($email, $password);
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index_styling\index.css">
</head>
<body>
    <div class="main-container">
        <h1>Sign in to lorem</h1>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                <label for="email">University Email address:</label>
                <input type="email" name="email" id="email">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password">
                <input type="submit" value="Sign in" class="cta">
            </form>
        </div>
        <div class="sec-container">
            <p>New to Lorem?</p>
            <a href="register.php">Create an account</a>
        </div>
    </div>
    
    <?php 
        if (isset($error)) echo $error;
    ?>
</body>
</html>

