<?php 
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once('../db_connection.php'); 

// Create a new connection using constants from db_connection.php
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query the database
$query = "SELECT * FROM bookeds";
$result = $conn->query($query);

// Check if the query returned results
if (!$result){
    die("Query Failed: ".$conn->error);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admi.css">
    <script src="https://kit.fontawesome.com/ab9ec8f000.js" crossorigin="anonymous"></script>
    <title>Admin</title>
</head>
<body>

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
                <li><a href="admin.php">
                <i class="fa-solid fa-spinner"></i>
                <span class="link-name">Refresh</span>
                </a></li>
                
            </ul>

    
            <ul class="register-link">
                <li><a href="register.php">
                    <i class="fa-solid fa-id-card"></i>
                    <span>Register Admin</span>
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

    <!-- Move dashboard cards here, before main content -->
    <div class="dashboard-cards">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="card-title">Total Bookings</div>
            <div class="card-value">
                <?php
                    $count_query = "SELECT COUNT(*) as total FROM bookeds";
                    $count_result = mysqli_query($conn, $count_query);
                    $count_data = mysqli_fetch_assoc($count_result);
                    echo $count_data['total'];
                ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="card-title">Total Revenue</div>
            <div class="card-value">
                <?php
                    $revenue_query = "SELECT SUM(price) as total FROM bookeds";
                    $revenue_result = mysqli_query($conn, $revenue_query);
                    $revenue_data = mysqli_fetch_assoc($revenue_result);
                    echo '₱ ' . number_format($revenue_data['total'] ?? 0, 2);
                ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-clock"></i>
            </div>
            <div class="card-title">Today's Bookings</div>
            <div class="card-value">
                <?php
                    $today_query = "SELECT COUNT(*) as total FROM bookeds WHERE DATE(booking_time) = CURDATE()";
                    $today_result = mysqli_query($conn, $today_query);
                    $today_data = mysqli_fetch_assoc($today_result);
                    echo $today_data['total'];
                ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-calculator"></i>
            </div>
            <div class="card-title">Total Reservations</div>
            <div class="card-value">
                <?php
                    $reservations_query = "SELECT COUNT(*) as total FROM bookeds";
                    $reservations_result = mysqli_query($conn, $reservations_query);
                    $reservations_data = mysqli_fetch_assoc($reservations_result);
                    echo $reservations_data['total'];
                ?>
            </div>
        </div>
    </div>

    <!-- Main content starts here -->
    <main>
        <div class="header">
          <h1>Bookings</h1>
        </div>

        <div class="bottom_data">
          <div class="orders">
            <div class="header">
              <h3>List Of Bookings</h3>
            </div>
            <table>
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Package</th>
                  <th>Payment</th>
                  <th>Booking Time</th>
                  <th>Backdrop</th>
                  <th>Status</th> 
                  <th>Price</th>
                  <th>Phone</th>
                </tr>
              </thead>
              <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['package_type']); ?></td>
                    <td><?= htmlspecialchars($row['payment_method']); ?></td>
                    <td><?= htmlspecialchars($row['booking_time']); ?></td>
                    <td><?= htmlspecialchars($row['backdrop']); ?></td>
                    <td><?= htmlspecialchars($row['status']); ?></td>
                    <td><?= htmlspecialchars(number_format($row['price'], 2)); ?></td>
                    <td><?= htmlspecialchars($row['phone']); ?></td>
                </tr>
              <?php endwhile; ?>
              <?php if ($result->num_rows == 0): ?>
                <tr><td colspan="9">No bookings found.</td></tr>
              <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
    </main>
      <!-- Main Close -->



</body>
</html>


<?php
$conn->close();
?>