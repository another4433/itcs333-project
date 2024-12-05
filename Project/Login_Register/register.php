<?php
    include("email_verify.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
        $pass = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
        $firstName = filter_input(INPUT_POST, "firstName", FILTER_SANITIZE_SPECIAL_CHARS);
        $lastName = filter_input(INPUT_POST, "LastName", FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($email) || empty($pass) || empty($firstName) || empty($lastName)) {
            $error = "<p class='error'>Please fill all fields!</p>";
        } else {
            verifyUOBreg($email, $pass, $firstName, $lastName);
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
    <link rel="stylesheet" href="style.css"> <!-- for the JS file -->
</head>
<body>
    <div class="main-container">
        <h1>Register to lorem</h1>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" id="form">
                <!-- ******** Email ******** -->
                <label for="email">University Email address:</label>
                <input type="email" name="email" id="email" required title="Email must end with @stu.uob.edu.bh or @uob.edu.bh">

                <div id="email-message">
                    <h3 style="font-weight:400;">Email must be:</h3>
                    <p class="email-message-item invalid">can't be less than 8 characters</p>
                    <p class="email-message-item invalid">should be valid UOB email</p>
                    <p class="email-message-item invalid">should include @</p>
                </div>

                <!-- ******** Password ******** -->
                <label for="password">Password:</label>
                <input type="password" name="password" id="password"
                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}" 
                        title="Must contain at least one number,one uppercase and lowercase letter, and at least 8 characters" 
                        required>

                <div id="password-message">
                    <h3 style="font-weight:400;">Password must contain:</h3>
                    <p class="password-message-item invalid">At least one lowercase letter</p>
                    <p class="password-message-item invalid">At least one uppercase letter</p>
                    <p class="password-message-item invalid">At least one number</p>
                    <p class="password-message-item invalid">Minimum 8 characters</p>
                    <p class="password-message-item invalid">Maximum 24 characters</p>
                </div>
                
                <!-- ******** Conform Password ******** -->
                <label for="password">confirm Password:</label>
                <input type="password" name="confirmPassword" id="confirmPassword">

                <div id="confirmPassword-message">
                    <p class="confirmPassword-message-item invalid">
                        <b>Please confirm your password</b>
                    </p>
                    <p class="confirmPassword-message-item invalid">
                        <b>Passwords doesn't match!</b>
                    </p>
                </div>

                <!-- ******** Name ******** -->
                <label for="name">First Name:</label>
                <input type="text" name="firstName" id="firstName" required>

                <label for="name">Last Name:</label>
                <input type="text" name="LastName" id="LastName" required> <!--*** capital letter may caise issues ***-->

                <input type="submit" value="Register" class="cta">
            </form>
        </div>
        <div class="sec-container">
            <p>Alredy have an account?</p>
            <a href="index.php">Sign in</a>
        </div>
    </div>

    <script src="script.js"></script>
    <?php 
        if (isset($error)) echo $error;
    ?>


</body>
</html>
