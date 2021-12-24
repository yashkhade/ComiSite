<?php require "header.php"; ?>
<?php 
function activate_user()
{
    if(isset($_GET["email"]))
    {
        $email = htmlentities($_GET["email"]);
        if(isset($_GET["code"])){
            $validation_code = htmlentities($_GET["code"]);
        }
        else
        {
            phpalert('Error occured, Sorry Your Account is Not Activated');
            return;
        }
        // require "config.php"; 
        $db_host = getenv("db_host");
        $db_username = getenv("db_username");
        $db_password = getenv("db_password");
        $db_database = getenv("db_database");
        
        $con = mysqli_connect($db_host,$db_username,$db_password,$db_database);
        $sql = "SELECT name FROM user_data WHERE email ='".mysqli_real_escape_string($con,$email)."' AND validation_code ='".mysqli_real_escape_string($con,$validation_code)."'";
        if($con->query($sql))
        {
            $sql2 = "UPDATE user_data SET validity = 1 WHERE email = '".mysqli_real_escape_string($con,$email)."' AND validation_code ='".mysqli_real_escape_string($con,$validation_code)."' ";
            if($con->query($sql2))
            {
                phpalert("Your Account is Activated Please Login");
            }
            else{
                phpalert("Error occured, Sorry Please Try Again");
            }
            
        }
        else
        {
            phpalert('Error occured, Sorry Your Account is Not Activated');
        }
    }
}
?>
<?php activate_user(); ?>
<?php require "footer.php"; ?>
