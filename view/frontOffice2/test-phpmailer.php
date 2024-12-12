
Now, let's create a simple test script to verify the installation:

```php file="test-phpmailer.php" type="code"
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//require 'vendor/autoload.php'; // If using Composer
// OR
require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
 require '../../PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ammichemedsalah2004@gmail.com';    // Replace with your email
    $mail->Password   = 'mxsx wjjx xuhk tbjx';    // Replace with your password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('mailerjoeuser@gmail.com', 'Mailer');
    $mail->addAddress('ammichemedsalah2004@gmail.come', 'Joe User');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'PHPMailer Test';
    $mail->Body    = 'This is a test email to confirm PHPMailer is working correctly.';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
