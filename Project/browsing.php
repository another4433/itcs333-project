<?php
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
     
    
    if($_SERVER['REQUEST_METHOD']=="GET"){
        if(isset($_GET['search'])){
            $roomName=$_GET['room'];
            try{
                $sql="SELECT * FROM room WHERE RoomName=?";
                $stmt=$db->prepare($sql);
                $stmt->execute(array($roomName));
                if($stmt->rowcount()==0){
                    $msg="there's no room";
                }
            }catch(PDOException $ex) {
                echo "there's error";
                die ($ex->getMessage());
            }
        }
        if(isset($_GET['filter'])){ 

            if(!empty($_GET['location']) || !empty($_GET['capacity']) || isset($_GET['equipment']) || isset($_GET['favorites'])){

                $location=$_GET['location'];
                $capacity=$_GET['capacity'];

                if($capacity=="Small"){$min=10; $max=30;}
                if($capacity=="Medium"){$min=31; $max=75;}
                if($capacity=="Large"){$min=76; $max=500;}  

                try{
                    $sql="SELECT * FROM room R WHERE 1=1";
                    $params = [];
                    if(!empty($_GET['capacity'])){
                        $sql.=" AND R.Capacity between :min and :max";
                        $params[':min']=$min;
                        $params[':max']=$max;
                    }
                    if(!empty($_GET['location'])){
                        $sql.=" AND R.Location= :loc ";
                        $params[':loc']= $location;
                    }
                    if(isset($_GET['equipment'])){
                        $sql.=" AND (R.HasPCs>0 or R.HasProjectors>0)";
                    }
                    if(isset($_GET['favorites'])){
                        $sql.=" AND R.RoomID in (SELECT RoomID from favourite where PersonID= :personID)";
                        $params[':personID']=$personID;
                    }
                    $stmt=$db->prepare($sql); 
                    $stmt->execute($params);
                    if($stmt->rowcount()==0){
                        $msg="there's no room";
                    }

                }catch(PDOException $ex) {
                    echo "there's error";
                    die ($ex->getMessage());
                }
            }
        }
        if(isset($_GET['add'])){
            $RoomID=$_GET['RoomID'];
            $sql2="SELECT RoomID from favourite where RoomID= ?";
            $stmt2=$db->prepare($sql2);
            $stmt2->execute(array($RoomID));
            $exists= $stmt2->rowcount();
            if($exists>0){$msg2="you already added this room to your favorites";}
            else{
                try{
                    $sql2="INSERT INTO favourite VALUES(?,?)";
                    $stmt2=$db->prepare($sql2);
                    $stmt2->execute(array($RoomID,$personID));

                }catch(PDOException $ex) {
                    echo "there's error";
                    die ($ex->getMessage());
                }
            }
        }

        if(isset($_GET['remove'])){
            $RoomID=$_GET['RoomID'];
            $sql2="SELECT RoomID from favourite where RoomID= ?";
            $stmt2=$db->prepare($sql2);
            $stmt2->execute(array($RoomID));
            $exists= $stmt2->rowcount();
            if($exists>0){
                try{
                    $sql2="DELETE FROM favourite WHERE RoomID= ? and PersonID= ?";
                    $stmt2=$db->prepare($sql2);
                    $stmt2->execute(array($RoomID,$personID));

                }catch(PDOException $ex) {
                    echo "there's error";
                    die ($ex->getMessage());
                }
            }
        }

        if(isset($_GET['booking'])){
            $RoomID=$_GET['RoomID'];
            header("Location: booking.php?RoomID=$RoomID");
        }
    }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="browsing.css">
</head>
<body>


    <nav>
        <h2>welcome to browsing page</h2>
        <div>
            <a href="myBookings.php">my booking</a>
            <a href="profile.php">my profile</a>
        </div>
    </nav>

    <div class="search-bar">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
            <input class="search" type="search" placeholder="Search about room name" name="room" required>
            <input class="submit" type="submit" value="search" name="search">
        </form>
    </div>

    <div class="container">

        <div class="filter">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
                <label for="capacity">capacity</label>
                <select name="capacity" id="capacity">
                    <option value="" selected>All</option>

                    <option value="Small" >Small</option>
                    <option value="Medium" >Medium</option>
                    <option value="Large" >Large</option>
                </select>
                <label for="location">location</label>
                <select name="location" id="location">
                    <option value="" selected>All</option>
                    <?php while($row=$stmt1->fetch()) {?>
                        <option value="<?php echo $row[0]; ?>" > <?php echo $row[0]; ?> </option>
                    <?php } ?>
                </select>
                <label>equipment</label>
                <input class="checkbox" type="checkbox" name="equipment" >
                <label>favorites</label>
                <input class="checkbox" type="checkbox" name="favorites">
                <input class="submit" type="submit" value="filter" name="filter">
            </form>
        </div>

    <div class="ALLroom">
        <div class="msg">
            <h3><?php if(isset($msg)) echo $msg; ?></h3>
            <h3><?php if(isset($msg2)) echo $msg2; ?></h3>
        </div>
        <?php while($row= $stmt->fetch()){ ?>
                <div class="room">
                    <div class="room-image">
                        <img src="<?php echo "Images/". $row['ImageName']; ?>" alt="">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
                            <input type="submit" value="booking" name="booking">
                            <input type="submit" value="add to favorites" name="add">
                            <input type="hidden" name="RoomID" value= <?php echo $row['RoomID']; ?>>
                        </form>
                    </div>
                    <h3><?php echo $row['RoomName']; ?></h3>
                    <div class="room-info">
                        <div>
                            <p>capacity: <?php echo $row['Capacity']; ?> </p>
                            <p>location: <?php echo $row['Location']; ?> </p>  
                        </div>
                        <div>
                            <p>PCs: <?php if($row['HasPCs']>0)echo"YES";else echo"NO"; ?> </p>
                            <p>Projectors: <?php if($row['HasProjectors'])echo"YES"; else echo"NO";?> </p>  
                        </div>
                    </div>
                    <?php if(isset($_GET['favorites'])){?>
                            <form class="remove" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
                                <input type="submit" value="remove" name="remove">
                                <input type="hidden" name="RoomID" value= <?php echo $row['RoomID']; ?>>
                            </form> <?php } ?>
                </div>
        <?php } ?>
    </div>

    </div>
</body>
</html>