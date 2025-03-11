<?php 
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include '../db_connection.php'; 

// Create a new connection using constants from db_connection.php
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("UPDATE bookeds SET status = 'confirmed' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: book.php");
    exit();
}

$result = $conn->query("SELECT * FROM bookeds ORDER BY booking_time ASC");



?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Book</title>
    <script src="https://kit.fontawesome.com/ab9ec8f000.js" crossorigin="anonymous"></script>
    <script src="Book.js"></script>   
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="book.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <style>
        body {
            margin: 15px auto;
            padding: 0;
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 16px;
            color: #333;
        }
        #calendar {
            width: 80%;
            height: 490px;
            margin-left: 260px;
        }
        .fc-daygrid-day {
            background-color: #f6c3cb;
            border: 1px solid #ccc;
            padding: 5px;
            text-align: center;
            cursor: pointer;
            font-size: 16px;
            color: #333;
        }
        .fc-day-today {
            background-color: #ffff99;
            border-color: #dddd00;
            color: #000;
        }
        .fc-non-business {
            background-color: #E7D1FC;
        }
        .fc-daygrid-day-number {
            color: #000;
        }
    </style>
</head>
<body>
<div class="current-time">
    <i class="fas fa-clock"></i>
    <span id="current-time"></span>
</div>



    <nav>
        <div class="logo-name">
            <div class="logo-image">
                <img src="icon.png" alt="">
            </div>
            <span class="logo_name">Self-shoot</span>
        </div>
        <div class="menu-item">
            <ul class="nav-link">
                <li><a href="admin.php"><i class="fa-solid fa-house"></i><span class="link-name">Home</span></a></li>
                <li><a href="content.php"><i class="fa-regular fa-file"></i><span class="link-name">Content</span></a></li>
                <li><a href="analytics.php"><i class="fa-solid fa-chart-simple"></i><span class="link-name">Analytics</span></a></li>
                <li><a href="book.php"><i class="fa-regular fa-calendar-check"></i><span class="link-name">Book</span></a></li>
                <li><a href="book.php"><i class="fa-solid fa-spinner"></i><span class="link-name">Refresh</span></a></li>
                <li><a href="addtimeslot.php">
                <i class="fa-regular fa-clock"></i>
                <span class="link-name">Add Time Slot</span>
                </a>
                </li>
            </ul>
            <ul class="logut-mod">
                <li><a href="index.php"><i class="fa-solid fa-arrow-right-from-bracket"></i><span class="link-name">Log out</span></a></li>
            </ul>
        </div>
    </nav>
    
    <div id='calendar'></div>


    
    <main>
        <div class="bottom_data" style="margin-left: 260px; margin-top: 90px;">
            <div class="orders">
                <div class="header">
                    <h3>Recent Booking</h3>
                </div>
                <table>
    <thead>
        <tr>
            <th>Package</th>
            <th>Amount</th>
            <th>Name</th>
            <th>Email</th>
            <th>Number</th>
            <th>Payment</th>
            <th>Booking Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['package_type']); ?></td>
        <td><?= htmlspecialchars($row['price']); ?></td> 
        <td><?= htmlspecialchars($row['name']); ?></td>
        <td><?= htmlspecialchars($row['email']); ?></td>
        <td><?= htmlspecialchars($row['phone']); ?></td>
        <td><?= htmlspecialchars($row['payment_method']); ?></td>
        <td><?= htmlspecialchars($row['booking_time']); ?></td>
        <td><?= htmlspecialchars($row['status']); ?></td>
        <td>
    <div class="d-flex gap-2">
        <!-- Confirm Payment Button (Now First) -->
        <form method="POST" action="book.php">
            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']); ?>">
            <button type="submit" name="confirm" class="btn btn-primary">Confirm Payment</button>
        </form>

        <!-- Send Email Button -->
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#SendMail">Send Email</button>

        <!-- Delete Button -->
        <form method="POST" action="deletesched.php" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']); ?>">
            <button type="submit" name="delete" class="btn btn-danger">Cancel The Booking</button>
        </form>
    </div>
</td>

    </tr>
    <?php endwhile; ?>
</tbody>

</table>




            </div>

            <div class="modal fade" id="SendMail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form id="contact" action="mail.php" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Sending Email</h5>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3">
                                    <span name="name" class="input-group-text">Payment Mode</span>
                                    <select name="pay" class="box">
                                        <option value="Gcash">Gcash</option>
                                        <option value="Bank_Transfer"> Bank Transfer</option>
                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Email</span>
                                    <input type="email" class="form-control" name="email" min="1" required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Subject</span>
                                    <input type="text" class="form-control" name="subject" min="1" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" id="contact-submit" class="btn btn-success" name="send">Sent</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
<script>
   document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth', // Show a full month
        events: 'get_confirmed_bookings.php', // Load confirmed bookings from the server
        eventContent: function(arg) {
            let count = arg.event.extendedProps.count || 0; // Ensure count is never undefined
            return { html: `<b>${arg.event.title}</b> (${count})` };
        }
    });

    calendar.render();
});

</script>
    
</body>
</html>




<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/phpmailer/src/Exception.php';
require 'phpmailer/phpmailer/src/PHPMailer.php';
require 'phpmailer/phpmailer/src/SMTP.php';

if (isset($_POST["send"])) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'no.replyretratoselfshootstudio@gmail.com';
        $mail->Password   = 'gpksvnuvlsipovel';   
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        $mail->setFrom($_POST["email"], $_POST["name"]);
        $mail->addAddress($_POST["email"]);
        $mail->addReplyTo($_POST["email"], $_POST["name"]);
        $mail->Subject = $_POST["subject"];
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