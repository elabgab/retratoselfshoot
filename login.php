<?php
session_start();
include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Establish database connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare statement to fetch user details
    $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($admin_id, $hashed_password);
        $stmt->fetch();

        // Debugging: Check if password is being retrieved
        if (empty($hashed_password)) {
            die("Error: No password found for this user. Check your database.");
        }

        // Verify hashed password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin_id'] = $admin_id;
            header("Location: admin.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid username.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Log in</title>
</head>
<body>
    
<div class="wrapper">
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <h1>Login</h1>
        
        <div class="input-box">
            <input type="text" name="username" placeholder="Name" style="color: black;" required>
            <i class='bx bxs-user'></i>
        
        </div>

    
        <div class="input-box">
            <input type="Password" name="password" placeholder="Password" style="color: black;" required>
            <i class='bx bxs-lock-alt' ></i>
        </div>
        
            <input type="submit" name="submit" value="Login" class="btn">
        

         


    </form>
</div>

<script href = "login.js"></script>

</body>
</html>






