<?php
include '../db_connection.php'; 

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the date is formatted properly
$sql = "SELECT DATE(booking_time) AS date, COUNT(*) AS count FROM bookeds WHERE status = 'confirmed' GROUP BY DATE(booking_time)";
$result = $conn->query($sql);

$events = [];
while ($row = mysqli_fetch_assoc($result)) {
    $count = isset($row['count']) && $row['count'] !== null ? $row['count'] : ''; // Ensure count is never 0 if empty
    $events[] = [
        'title' => 'Bookings',
        'start' => $row['date'],
        'count' => $count // Send empty string instead of 0 if count is missing
    ];
}

echo json_encode($events);

?>
