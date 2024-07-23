<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Function to generate a unique alphanumeric order ID
function generateOrderId($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$con = mysqli_connect("localhost", "root", "", "echonest");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['send'])) {
    // Collect form data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $color = $_POST['color'];
    $quantity = $_POST['quan'];
    $country = $_POST['con'];
    $province = $_POST['prov'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $payment_method = $_POST['pmeth'];

    if ($payment_method == "COD") {
        $full_address = $_POST['fulladdress'];
        $card_number = "n/a";
        $card_name = "n/a";
        $card_expiry = "n/a";
        $card_cvv = "n/a";
    } else {
        $full_address = "n/a";
        $card_number = $_POST['cno'];
        $card_name = $_POST['cname'];
        $card_expiry = $_POST['cexp'];
        $card_cvv = $_POST['cvv'];
    }

    // Generate a unique order ID
    $order_id = generateOrderId();

    // Prepare and execute SQL statement
    $stmt = $con->prepare("INSERT INTO order_table (order_id, first_name, last_name, email, phone, color, quantity, country, province, city, zip, payment_method, full_address, card_number, card_name, card_expiry, card_cvv, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssssissssssssss", $order_id, $fname, $lname, $email, $phone, $color, $quantity, $country, $province, $city, $zip, $payment_method, $full_address, $card_number, $card_name, $card_expiry, $card_cvv);

    if (empty($fname) || empty($lname) || empty($email) || empty($phone) || empty($color) || empty($quantity) || empty($country) || empty($province) || empty($city) || empty($zip) || empty($payment_method) || ($payment_method == 'COD' && empty($full_address)) || ($payment_method == 'card' && (empty($card_number) || empty($card_name) || empty($card_expiry) || empty($card_cvv)))) {
        echo "<script>alert('Please fill in all required fields.')</script>";
        echo "<script>window.history.back();</script>";
    }

    if ($stmt->execute()) {
        // Send confirmation email
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true;
            $mail->Username = 'email'; // SMTP username
            $mail->Password = 'app password'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('from', 'Echonest');
            $mail->addAddress($email, $fname . ' ' . $lname);

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Order Confirmation';
            $mail->Body = "Thank you for your order! Your order ID is <strong>$order_id</strong>.";

            if ($mail->send()) {
                echo "<script>alert('Form submitted. A confirmation email has been sent to your email address.')</script>";
                echo "<script>window.location = 'index.html'</script>;";
            }
            else {
                echo "<script>window.history.back();</script>";
            }

        } catch (Exception $e) {
            echo "<script>alert('Form submitted. Email could not be sent. Mailer Error: {$mail->ErrorInfo}')</script>";
        }
    } else {
        echo "<script>alert('Error: " . $stmt->error . "')</script>";
    }

    $stmt->close();
}

mysqli_close($con);
?>
