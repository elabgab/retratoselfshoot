<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../db_connection.php'; // Include database connection file


if (isset($_POST['fetch_slots'])) {
    $slot_date = $_POST['slot_date'];
    
    $sql = "SELECT start_time FROM time_slots WHERE slot_date = ? ";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $slot_date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $available_slots = [];
    while ($row = $result->fetch_assoc()) {
        $available_slots[] = $row['start_time'];
    }
    if (isset($_POST['fetch_slots'])) {
        header('Content-Type: application/json'); // Ensure JSON response
    }
    echo json_encode($available_slots);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_slot'])) {
        $slot_date = $_POST['slot_date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        
        $sql = "INSERT INTO time_slots (slot_date, start_time, end_time, status) VALUES (?, ?, ?, 'available')";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sss", $slot_date, $start_time, $end_time);
        
        if ($stmt->execute()) {
            echo "<script>alert('Time slot added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding time slot: " . $stmt->error . "');</script>";
        }
        
        $stmt->close();
    }
    
    if (isset($_POST['update_status'])) {
        $slot_date = $_POST['slot_date'];
        $start_time = $_POST['start_time'];
        $new_status = $_POST['new_status'];
        
        $sql = "UPDATE time_slots SET status = ? WHERE slot_date = ? AND start_time = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sss", $new_status, $slot_date, $start_time);
        
        if ($stmt->execute()) {
            echo "<script>alert('Slot status updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating slot status: " . $stmt->error . "');</script>";
        }
        
        $stmt->close();
    }
    
    $connection->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content</title>
    <script src="https://kit.fontawesome.com/ab9ec8f000.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/ab9ec8f000.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="conten.css">
</head>
<body>

<div class="current-user">
    <i class="bi bi-person-circle"></i>
    <span>|</span>
    <i class="bi bi-clock"></i>
    <span><?php echo date('Y-m-d H:i:s'); ?></span>
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
                <li><a href="admin.php">
                    <i class="fa-solid fa-house"></i>
                      <span class="link-name">Home</span>
                </a></li>
                <li><a href="content.php">
                    <i class="fa-regular fa-file"></i>
                     <span class="link-name">Content</span>
                </a></li>
                <li><a href="analytics.php">
                    <i class="fa-solid fa-chart-simple"></i>
                    <span class="link-name">Analytics</span>
                </a></li>
                <li><a href="book.php">
                    <i class="fa-regular fa-calendar-check"></i>
                    <span class="link-name">Book</span>
                </a></li>
                <li><a href="content.php">
                <i class="fa-solid fa-spinner"></i>
                <span class="link-name">Refresh</span>
                </a></li>
            </ul>


            <ul class="logut-mod">
                <li><a href="login.php">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span class="link-name">Log out</span>
                </a></li>
            </ul>

        </div>
   </nav>



   <div class="container mt-5">
    <h2>Add Available Time Slot</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="slot_date" class="form-label">Slot Date</label>
            <input type="date" class="form-control" id="slot_date" name="slot_date" required>
        </div>
        <div class="mb-3">
            <label for="start_time" class="form-label">Start Time</label>
            <select class="form-control" id="start_time" name="start_time" required>
                <option value="09:00:00">09:00 AM</option>
                <option value="10:00:00">10:00 AM</option>
                <option value="11:00:00">11:00 AM</option>
                <option value="12:00:00">12:00 PM</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="end_time" class="form-label">End Time</label>
            <select class="form-control" id="end_time" name="end_time" required>
                <option value="10:00:00">10:00 AM</option>
                <option value="11:00:00">11:00 AM</option>
                <option value="12:00:00">12:00 AM</option>
                <option value="13:00:00">01:00 PM</option>
            </select>
        </div>
        <button type="submit" name="add_slot" class="btn btn-primary">Add Slot</button>
    </form>

    <h2 class="mt-5">Update Slot Status</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="update_slot_date" class="form-label">Slot Date</label>
            <input type="date" class="form-control" id="update_slot_date" name="slot_date" required>
        </div>
        <div class="mb-3">
            <label for="available_slots" class="form-label">Available Slots</label>
            <select class="form-control" id="available_slots" name="start_time" required>
                <option value="">Select a time</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="new_status" class="form-label">New Status</label>
            <select class="form-control" id="new_status" name="new_status" required>
                <option value="available">Available</option>
                <option value="booked">Booked</option>
            </select>
        </div>
        <button type="submit" name="update_status" class="btn btn-warning">Update Status</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#update_slot_date').change(function() {
            let selectedDate = $(this).val();
            $.ajax({
                type: "POST",
                url: "",
                data: { fetch_slots: true, slot_date: selectedDate },
                dataType: "json",
                success: function(response) {
                    let slotDropdown = $('#available_slots');
                    slotDropdown.empty();
                    slotDropdown.append('<option value="">Select a time</option>');
                    response.forEach(function(time) {
                        slotDropdown.append('<option value="' + time + '">' + time + '</option>');
                    });
                }
            });
        });
    });
</script>



</body>
</html>











