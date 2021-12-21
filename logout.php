<?php require "header.php"; ?>

<?php
    session_destroy();
    redirect("login.php");
?>

<?php require "footer.php"; ?>