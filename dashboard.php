<?php require __DIR__.'/header.php'; ?>
<h1 style="color: #394679;"> Welcome <h1>
<?php
    function start_mailing()
    {
        $info = get_headers('https://c.xkcd.com/random/comic/');
        $temp_link = substr($info[8],10);
        $img_response = file_get_contents($temp_link.'/info.0.json');
        $array = json_decode($img_response,true);
        $image_link = $array['img'];//link to image
        $imagedata = base64_encode(file_get_contents($image_link));
        echo '<img src="data:image/jpeg;base64,'.$imagedata.'"><br>';
        //for inline data
        $fname = $array['safe_title'].'.jpeg';
        $sep = 'image';
        
        $email = $_SESSION['email'];
        $apikey = getenv('API_KEY'); //API_KEY;
        $email_from = getenv('email_from');
        $email_from_name = getenv('email_from_name');
        if(isset($_SERVER['SERVER_PROTOCOL']))
        {
            if(isset($_SERVER['HTTP_HOST']))
            {
                $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
                $url = $protocol.$_SERVER['HTTP_HOST'];
            }
        }

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
        <a href='$url/unsubscribe.php?unsub=true&email=$email'>UnSubscribe</a>
        </body>
        </html>";
        $subject = 'Comic Update - ComiSite';

        $headers = array(
            'Authorization: Bearer '.$apikey,
            'Content-Type: application/json'
        );

        $data = array(
            'personalizations' => array(
                array(
                    'to' => array(
                        array(
                            'email' => $email,
                            'name' => $email
                        )
                    )
                )
            ),
            'from' => array(
                'email' => $email_from,
                'name' => $email_from_name
            ),
            'subject' => $subject,
            'content' => array(
                array(
                    'type' => 'text/html',
                    'value' => $body
                )
                ),
                'attachments' => array(
                    array(
                        'content' => $imagedata,
                        'content_id' => $sep,
                        'disposition' => 'inline',
                        'type' => 'image/jpeg',
                        'filename' => $fname
                    ),
                    array(
                        'content' => $imagedata,
                        'content_id' => 'Attachment',
                        'disposition' => 'attachment',
                        'type' => 'image/jpeg',
                        'filename' => $fname
                    )
                ),
                    
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/mail/send');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        $db_host = getenv('db_host');
        $db_username = getenv('db_username');
        $db_password = getenv('db_password');
        $db_database = getenv('db_database');

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
            phpalert('Please Login To Continue');
            redirect('login.php');
        }
    }
    if(isset($_GET['run']))
    {
        start_mailing();
    }
    if(isset($_GET['subscribe']))
    {
        $db_host = getenv('db_host');
        $db_username = getenv('db_username');
        $db_password = getenv('db_password');
        $db_database = getenv('db_database');

        $con = mysqli_connect($db_host,$db_username,$db_password,$db_database);
        $email = $_SESSION['email'];    
        $sql2 = "UPDATE user_data SET sub_status = 1 WHERE email = '$email'";
        if($con->query($sql2))
        {
            echo 'Subscribed Successfull';
        }
        else
        {
            echo '<br>Failed Try again After sometime';
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
<?php require __DIR__.'/footer.php'; ?>