<?php require "header.php"; ?>
<?php
    function login_user($email,$password)
    {
        $email = htmlentities($email);
        $password = htmlentities($password);
        //$con = mysqli_connect("localhost","root","","users");
        $con = mysqli_connect("remotemysql.com","eXcArO3s4n","gJ0S2ixTiU","eXcArO3s4n");
        $sql = "SELECT * FROM user_data WHERE email='".mysqli_real_escape_string($con,$email)."' AND password='".$password."' AND validity = 1";
        $result = $con -> query($sql);
        $row = mysqli_fetch_array($result);
        $_SESSION["username"] = $row['name'];
        $_SESSION["email"] = $row["email"];
        $_SESSION["validity"] = $row["validity"];
        $_SESSION["mailing"] = $row["mailing"];

        if(mysqli_num_rows($result)==1){
            return True;
        }
        return False;
        
    }
    if(!isset($_POST["email"]))
    {
        echo "Enter Email";
    }
    elseif(!isset($_POST["password"]))
    {
        echo "Enter Password";
    }
    else
    {
        if(login_user($_POST["email"],$_POST["password"])){
            redirect('dashboard.php');
        }
        else
        {
            echo "User Not Found";
        }
    }
?>

<div class="countainer">
<h1 class="h1" >Log In</h1>
<form action="login.php" method="post">
    <div class="form">
        <input type="text" name="email" id="email" placeholder="Enter Email">
        <input type="password" name="password" id="password" placeholder="Password">
        <button style='background-color: rgb(102, 102, 179)' type="submit">submit</button>
        <br>
        <a href="register.php">Register Instead</a>
    </div>
</form>
</div>
<?php require "footer.php"; ?>