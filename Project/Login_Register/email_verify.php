<?php
    include("database.php");
    $error = "";

    try {
        $pdo = new PDO($dsn, $db_user, $db_password, $PDOoptions);
        /*--------------------------------------------------------------*/
        /*--------------------Login Functions---------------------------*/
        /*--------------------------------------------------------------*/

        // function admin($adminEmail, $adminPass) {
        //     global $error, $pdo;

        //     $sql = "SELECT * FROM admins WHERE email = :email";
        //     $stmt = $pdo->prepare($sql);
        //     $stmt->execute(['email' => $adminEmail]);
        //     $row = $stmt->fetch();

        //     if ($row  && password_verify($adminPass, $row->password)) {
        //         header("location: admin.php");
        //     } else {
        //         $error = "<p class='error'>Invalid email or password!</p>";
        //     }
        // }

        function person($personEmail, $personPass) {
            global $error, $pdo;


            $sql = "SELECT * FROM person WHERE Email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $personEmail]);
            $row = $stmt->fetch();
            if ($row && password_verify($personPass, $row->Password)) {
                $_SESSION['userID'] = $row->PersonID;
                ($row->hasAdmin==0) ? header("location: ../browsing.php") : header("location: ../system_administrator.php");
            } else {
                $error = "<p class='error'>Invalid email or password!</p>";
            }
        }

        function verifyUOB($email, $pass) {
            global $error;
            $userDomain = explode('@', $email)[1];
            if ($userDomain === "stu.uob.edu.bh" || $userDomain === "uob.edu.bh") {
                person($email, $pass);
            } else {
                $error = "<p class='error'>Invalid email domain</p>";
            }
        }


        /*--------------------------------------------------------------*/
        /*-----------------Register Functions---------------------------*/
        /*--------------------------------------------------------------*/

        function register($email, $password, $firstName, $lastName) {
            global $pdo, $error;

            $hash = password_hash($password, PASSWORD_DEFAULT);


            $sql = "INSERT INTO person (Email, Password, FirstName, LastName) VALUES (:email, :password, :firstName, :lastName)";

            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['email' => $email, 'password' => $hash, 'firstName' => $firstName, 'lastName' => $lastName]);
                header("location: index.php");
            } catch (PDOException $e) {
                $error = "<p class='error'>This email is already used! Use another or sign in.</p>";
            }
        }

        function verifyUOBreg($email, $pass, $firstName, $lastName) {
            global $error;
            $userDomain = explode('@', $email)[1];
            if ($userDomain === "stu.uob.edu.bh" || $userDomain === "uob.edu.bh") {
                register($email, $pass, $firstName, $lastName);
            }else {
                $error =  "<p class='error'>Invalid email domain</p>";
            }
        }

} catch(PDOException $e) {
    $error = "<p class='error'>can't connect to database please try again later<p>";
}
?>