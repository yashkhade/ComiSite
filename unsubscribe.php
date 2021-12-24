<?php require "header.php"; ?>
<?php
    if(isset($_GET['unsub']))
    {
        if(isset($_GET['email']))
        {
            // require "config.php"; 
            $db_host = getenv("db_host");
            $db_username = getenv("db_username");
            $db_password = getenv("db_password");
            $db_database = getenv("db_database");

            $con = mysqli_connect($db_host,$db_username,$db_password,$db_database);
            $email = $_GET['email'];    
            $sql2 = "UPDATE user_data SET sub_status = 0 WHERE email = '$email'";
            if($con->query($sql2))
            {
                echo "UnSubscribed Successfull";
            }
            else
            {
                echo "<br>Failed Try again After sometime";
            }
        }
        else
        {
            echo "Sorry Some Error Occured with email";
        }
    }
    else{
        echo "Sorry Some Error Occured with unsub";
    }
?>
<?php require "footer.php"; ?>