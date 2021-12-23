<?php require "header.php"; ?>
<?php
  function email_exists($email)
  {
    require "config.php";
    $con = mysqli_connect($db_host,$db_username,$db_password,$db_database);
    $sql = "SELECT email FROM user_data WHERE email ='$email'";
    $result = $con->query($sql);
    if(mysqli_num_rows($result) >=1)
    {
      return true;
    }
    else 
    {
      return false;
    }
  }
  function valid_pass($password,$cnfpass)
  {
    if (empty($password))
    {
      echo "Please enter Password";
      return true;
    }
    elseif ($password != $cnfpass) {
      echo "Password Mismatch";
      return false;
    }
    return true;
  }
  if(isset($_POST['name']))
  {
    require "config.php";
    $con = mysqli_connect($db_host,$db_username,$db_password,$db_database);
    $name = mysqli_real_escape_string($con, $_POST["name"]);
    if(isset($_POST["surname"])){
      $surname = mysqli_real_escape_string($con, $_POST["surname"]);
    }
    if(isset($_POST["email"])){
      $email = mysqli_real_escape_string($con, $_POST["email"]);
    }
    if(isset($_POST["pass"])){
      $password =mysqli_real_escape_string($con, $_POST["pass"]);
    }
    if(isset($_POST["cnfpass"])){
      $cnfpass = mysqli_real_escape_string($con, $_POST["cnfpass"]);  
    }
    if(email_exists($email))
    {
      phpalert('Sorry that email is already registered, try checking your inbox.');
      redirect("login.php");
      return;
    }
    if(valid_pass($password,$cnfpass))
    {
      $validation_code = md5($name.$surname);
      $sql = "INSERT INTO `user_data` (`id`, `name`, `surname`, `email`, `password`,`validation_code`) VALUES (NULL,'$name','$surname','$email','$password','$validation_code');";
      if($con->query($sql) == FALSE)
      {
        die("Database Error");
      }
        require "config.php";
        $apikey = $API_KEY;
        $name = "ComiSite";
        $body = "<!DOCTYPE html>
        <html>
        <head><title>Verify your email</title>
        </head>
        <body>please click on this link  
        <a href = 'https://php-project-email.herokuapp.com/activate.php?email=$email&code=$validation_code'>click here</a> <br>
        To activate your account.
        </body>
        </html>";
        $subject = "Verify Email - ComiSite";

        $headers = array(
            'Authorization: Bearer '.$apikey,
            'Content-Type: application/json'
        );

        $data = array(
            "personalizations" => array(
                array(
                    "to" => array(
                        array(
                            "email" => $email,
                            "name" => $name
                        )
                    )
                )
            ),
            "from" => array(
                "email" => "khadeyash547@gmail.com"
            ),
            "subject" => $subject,
            "content" => array(
                array(
                    "type" => "text/html",
                    "value" => $body
                )
            )
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        if($response)
        {
          phpalert('Please check Your mail for Verification');
          redirect("login.php");
        }
    }
}
?>
<script>
   function required()
    {
      var empt = document.forms["register"]["email"].value;
      if (empt == "")
      {
          alert("Please Provide Email");
          return false;
      }
      var empt1 = document.forms["register"]["pass"].value;
      if (empt1 == "")
      {
          alert("Please Provide Password");
          return false;
      }
      var empt2 = document.forms["register"]["cnfpass"].value;
      if (empt2 == "")
      {
          alert("Please Provide Password");
          return false;
      }
      else
      {
        alert("Please Check Your Email For Verification");
        return false;
      }
    }

</script>
<div class="countainer">
<h1 class="h1" >Sign Up Form</h1>
<form name="register" action="register.php" onsubmit="required()" method="post">
    <div class="form">
        <input type="text" name="name" id="name" placeholder="Enter name">
        <input type="text" name="surname" id="surname" placeholder="Enter Surname">
        <input type="text" name="email" id="email" placeholder="Enter Email">
        <input type="password" name="pass" id="pass" placeholder="Password">
        <input type="password" name="cnfpass" id="cnfpass" placeholder="Confirm Password"><br>
        <button style='background-color: rgb(102, 102, 179)' type="submit">submit</button>
    </div>
</form>
</div>

<?php require "footer.php"; ?>