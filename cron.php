<!-- https://comisite.herokuapp.com/cron.php?start=true -->
<?php
if(isset($_GET['start']))
{
    $record = array();
    // require "config.php";
    $db_host = getenv("db_host");
    $db_username = getenv("db_username");
    $db_password = getenv("db_password");
    $db_database = getenv("db_database");

    $con = mysqli_connect($db_host,$db_username,$db_password,$db_database);
    $sql = "SELECT email FROM `user_data` WHERE sub_status = '1'";
    $result = $con->query($sql);
    $record = mysqli_fetch_row($result);
    echo var_dump($record);
    function start_mailing($record)
    {
        $num = rand(1,2000);
        $response = file_get_contents("https://xkcd.com/$num/info.0.json ");
        $array = json_decode($response,true);
        $image_link = $array["img"];//link to image
        $imagedata = base64_encode(file_get_contents($image_link));


        //for inline data
        $fname = $array["safe_title"];
        $sep = sha1(date('r', time()));


        foreach($record as $email)
        {
            // require "config.php";
            $apikey = getenv("API_KEY");//$API_KEY;
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
            <a href='https://comisite.herokuapp.com/unsubscribe.php?unsub=true&email=$email'>UnSubscribe</a>
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

            echo $email;
            echo $response;
            echo "<br>"; 
        }
    }
    start_mailing($record);
}
?>