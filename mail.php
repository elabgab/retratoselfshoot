<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Required files
require 'phpmailer/phpmailer/src/Exception.php';
require 'phpmailer/phpmailer/src/PHPMailer.php';
require 'phpmailer/phpmailer/src/SMTP.php';

// Check if the form is submitted
if (isset($_POST["send"])) {
  
  // Create a PHPMailer instance
  $mail = new PHPMailer(true);

  try {
    // Server settings
    $mail->isSMTP();                                    // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';               // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                           // Enable SMTP authentication
    $mail->Username   = 'no.replyretratoselfshootstudio@gmail.com';         // SMTP username
    $mail->Password   = 'gpksvnuvlsipovel';             // SMTP password
    $mail->SMTPSecure = 'ssl';                          // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 465;                            // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    // Sender's email and name
    $mail->setFrom($_POST["email"], $_POST["name"]);
    
    // Add a recipient
    $mail->addAddress($_POST["email"]);
    
    // Reply to sender's email
    $mail->addReplyTo($_POST["email"], $_POST["name"]);
    
    // Email subject
    $mail->Subject = $_POST["subject"];
    
    // Email message (HTML content)
    $mail->isHTML(true);
    $mail->Body = <<<EOT
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Reminder</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <div class="container" style="max-width: 600px; margin: 0 auto;">
        <div class="header" style="background-color: #f0f0f0; padding: 20px;">
            <h1 style="margin: 0;">Payment Reminder</h1>
        </div>
        <div class="content" style="padding: 20px;">
            <p>
                Good evening!<br><br>
                Please be reminded that the deadline for 
                <strong>DOWNPAYMENTS IS WITHIN 24 HOURS AND 
                AT LEAST PAY 50% OF YOUR CHOSEN PACKAGE</strong>. 
                Kindly settle your downpayments within 24 hours; we will not be giving any more extensions. 
                Payment details can be found on the first page of the payment form attached to your order confirmation. 
                (You may also see attached images for the mode of payments.)<br><br>
                <strong>REMINDERS:</strong><br>
                For GCASH Payments: If our accounts are already on limit, you may send your payments via bank transfer. 
                You can refer to our mode of payments indicated in the payment form for our bank details.<br>
                Once payment has been settled, don't forget to submit your proof of payment in our payment form as soon as possible. 
                <strong>NO PAYMENT = NO RESERVATION.</strong><br><br>
                For any other concerns, please email us at <a href="mailto:retratoselfshoot.studio@gmail.com">retratoselfshoot.studio@gmail.com</a>.<br><br>
                <strong>PAYMENT FORM:</strong> 
                <a href="https://docs.google.com/forms/d/1GUr-XjtInCOpCkiCs7_yvrIiIe0CFJrjvQN4JLDqSFI/edit" target="_blank" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">Payment Form Link</a><br>
            </p>
        </div>
    </div>
</body>
</html>
EOT;

if ($mail->send()) {
    echo "<script>alert('Message was sent successfully!'); window.location.href = 'book.php';</script>";
    exit();
    } else {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
   }
  } catch (Exception $e) {
   echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
  }
   ?>
