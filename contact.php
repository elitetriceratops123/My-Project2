<?php
$con = mysqli_connect("localhost", "root", "", "echonest");
if (!$con) {
    die("connection not established");
}

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

if (isset($_POST['send'])) {
    $fname = $_POST['fname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subj = $_POST['subj'];
    $msg = $_POST['msg'];


    $sql = "INSERT INTO contacts(fname,Email,phone,Subj,Msg) VALUES('$fname','$email','$phone','$subj','$msg')";

    if ($query = mysqli_query($con, $sql)) {

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   //Enable SMTP authentication
            $mail->Username = 'echonest01@gmail.com';                     //SMTP username
            $mail->Password = 'ljnt wcsz tuer wgxu';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
            $mail->Port = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom($email, $fname);
            $mail->addAddress('echonest01@gmail.com');     //Add a recipient
            $mail->addReplyTo($email, $fname);

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subj;
            $mail->Body = "<p><strong>From: </strong>{$email}</p><p><strong>To: </strong>EchoNest</p><p><strong>Subject: </strong>{$subj}</p><p>{$msg}</p>";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            if ($mail->send()) {
                echo '<script> window.location = "index.html" </script>;';
                exit();
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

?>