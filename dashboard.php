<?php require "header.php"; ?>
<h1 style="color: grey;"> Welcome <?php echo $_SESSION["username"]; ?>,<h1>
<?php
    function start_mailing()
    {
        $num = rand(1,2000);
        $img_response = file_get_contents("https://xkcd.com/$num/info.0.json ");
        $array = json_decode($img_response,true);
        $image_link = $array["img"];//link to image
        $imagedata = base64_encode(file_get_contents($image_link));
        echo '<img src="data:image/jpeg;base64,'.$imagedata.'"><br>';
        //for inline data
        $fname = $array["safe_title"].".jpeg";
        $sep = 'image';//sha1(date('r', time()));
        
        $email = $_SESSION["email"];
        require "config.php";
        $apikey = $API_KEY;
        $name = "ComiSite";
        $body = "<!DOCTYPE html>
        <html>
        <head><title>COMIC IMAGE</title>
        </head>
        <body>
        Here is Your Comic: <br> <img src='cid:$sep'><br>
        You are receiving this email because you are subscribed to 'mysite'.
        <br>
        If you do not wish to receive this email please click below to unsubscribe.
        <br>
        <a href='https://php-project-email.herokuapp.com/unsubscribe.php?unsub=true&email=$email'>UnSubscribe</a>
        </body>
        </html>";
        $subject = "Comic Update - ComiSite";

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
                ),
                "attachments" => array(
                    array(
                        "content" => $imagedata,
                        "content_id" => $sep,
                        "disposition" => "inline",
                        "type" => "image/jpeg",
                        "filename" => $fname
                    ),
                    array(
                        "content" => $imagedata,
                        "content_id" => "Attachment",
                        "disposition" => "attachment",
                        "type" => "image/jpeg",
                        "filename" => $fname
                    )
                ),
                    
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

        $con = mysqli_connect($db_host,$db_username,$db_password,$db_database);
        $email = $_SESSION['email'];
        $sql = "SELECT sub_status FROM `user_data` WHERE email='$email'";
        $result = $con->query($sql);
        $row = mysqli_fetch_array($result);
    }
    if(empty($_GET))
    {
        if(!logged_in())
        {
            phpalert("Please Login To Continue");
            redirect("login.php");
        }
    }
    if(isset($_GET['run']))
    {
        start_mailing();
    }
    if(isset($_GET['subscribe']))
    {
        require "config.php";
        $con = mysqli_connect($db_host,$db_username,$db_password,$db_database);
        $email = $_SESSION['email'];    
        $sql2 = "UPDATE user_data SET sub_status = 1 WHERE email = '$email'";
        if($con->query($sql2))
        {
            echo "Subscribed Successfull";
        }
        else
        {
            echo "<br>Failed Try again After sometime";
        }
    }
?>
<br>

<a href="dashboard.php?run=true"><button style="width: 30%;">Send Sample Comic Mail To You</button></a>
<a href="dashboard.php?subscribe=true"><button style="background-color: #32a14d; width: 30%;">Subcribe To Comic Mailing</button></a>
<br>
<p style="padding: 0px;
    font: small-caption;
    font-variant: all-petite-caps;
    color: darkgray;
">*If You Subscribe You will Receive Comic mail from us every 5 minutes</p>
<?php require "footer.php"; ?>