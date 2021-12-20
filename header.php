<?php 
    session_start();
    function logged_in()
    {
        if (isset($_SESSION["email"]))
        {
            if(!isset($_SESSION["validity"]))
            {
                return false;
            }
            return true;
        }
        return FALSE;
    }
    function redirect($location)
    {
        return header("Location: {$location}");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
</head>

<link rel="stylesheet" href="style.css">
<body>
<div style="background-color: rgb(96, 202, 101);
    color: rgb(247, 234, 181);
    text-align: center;
    font-size: xx-large;">ComiSite</div>
<br>
<div class="navbar" style = "
  background-color: lightblue;
  text-align: center;
  float: initial;
  padding: 6px 3px;
  font-size: large;">
  <a href="dashboard.php">Dashboard</a>
  <a href="register.php">Register</a>
  <a href="login.php">Login</a>
  <a href="logout.php">log Out</a>
</div>
