
<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include '../db_connection.php';

// Add error checking for database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

// Current UTC time and user
$current_utc = gmdate('Y-m-d H:i:s');
$current_user = 'Apasaur';

// Calculate total reservations
$sqlTotalReservations = "SELECT COUNT(*) AS total_reservations FROM bookeds";
$resultTotalReservations = mysqli_query($conn, $sqlTotalReservations);
if (!$resultTotalReservations) {
    die("Error in total reservations query: " . mysqli_error($conn));
}
$rowTotalReservations = mysqli_fetch_assoc($resultTotalReservations);
$totalReservations = $rowTotalReservations['total_reservations'];

// Calculate total revenue
$sqlTotalRevenue = "SELECT SUM(price) AS total_revenue FROM bookeds";
$resultTotalRevenue = mysqli_query($conn, $sqlTotalRevenue);
if (!$resultTotalRevenue) {
    die("Error in total revenue query: " . mysqli_error($conn));
}
$rowTotalRevenue = mysqli_fetch_assoc($resultTotalRevenue);
$totalRevenue = $rowTotalRevenue['total_revenue'] ?? 0;

// Calculate average booking value
$averageBookingValue = $totalReservations > 0 ? $totalRevenue / $totalReservations : 0;

// Get monthly data for the chart
$sqlMonthlyData = "SELECT 
    COUNT(*) as row_count, 
    SUM(price) as total_amount 
    FROM bookeds 
    WHERE MONTH(booking_time) = MONTH(CURRENT_DATE())";
$resultMonthlyData = mysqli_query($conn, $sqlMonthlyData);
if (!$resultMonthlyData) {
    die("Error in monthly data query: " . mysqli_error($conn));
}
$monthlyData = mysqli_fetch_assoc($resultMonthlyData);
$rowCount = $monthlyData['row_count'] ?? 0;
$totalAmount = $monthlyData['total_amount'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=, initial-scale=1.0">
  <link rel="stylesheet" href="analytics.css">
  <script src="https://kit.fontawesome.com/ab9ec8f000.js" crossorigin="anonymous"></script>
  <title>Analytics</title>
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
                <li><a href="analytics.php">
                <i class="fa-solid fa-spinner"></i>
                <span class="link-name">Refresh</span>
                </a></li>

                <li><a href="addtimeslot.php">
                <i class="fa-regular fa-clock"></i>
                <span class="link-name">Add Time Slot</span>
                </a>
                </li>

            </ul>


            <ul class="logut-mod">
                <li><a href="login.php">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span class="link-name">Log out</span>
                </a></li>
            </ul>

        </div>
   </nav>

   

   <div class="current-user">
        <i class="fas fa-user"></i>
        <span><?php echo htmlspecialchars($current_user); ?></span>
        <i class="fas fa-clock"></i>
        <span><?php echo htmlspecialchars($current_utc); ?></span>
    </div>

    <div class="analytics-cards">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="card-title">Total Reservations</div>
            <div class="card-value"><?php echo $totalReservations; ?></div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="card-title">Total Revenue</div>
            <div class="card-value">₱<?php echo number_format($totalRevenue, 2); ?></div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="card-title">Average Booking Value</div>
            <div class="card-value">₱<?php echo number_format($averageBookingValue, 2); ?></div>
        </div>
    </div>

    <div class="chart-container">
        <canvas id="Myreservation"></canvas>
    </div>



<!----
<section class="dashboard">
    <div class="top">
        <i class="fa-solid fa-bars sidebar-toggle"></i>

       
    </div>
</section>
---->

     
     
     
     <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>




<!-- Rest of your HTML code remains the same until the script section -->

<script>
    // Create the chart
    const ctx = document.getElementById('Myreservation').getContext('2d');
        const rowCount = <?php echo json_encode($rowCount); ?>;
        const totalAmount = <?php echo json_encode($totalAmount); ?>;
        
        const data = {
            labels: ['Current Month'],
            datasets: [{
                label: 'Reservations Made',
                data: [rowCount],
                backgroundColor: ['rgb(0, 255, 26)'],
            },{
                label: 'Monthly Sales (₱)',
                data: [totalAmount],
                backgroundColor: ['rgb(204, 204, 255)'],
            }]
        };

        const config = {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Monthly Analytics Overview'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        };

        new Chart(ctx, config);
</script>
</body>
</html>


