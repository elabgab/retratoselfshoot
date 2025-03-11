
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content</title>
    <script src="https://kit.fontawesome.com/ab9ec8f000.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="conten.css">

    <style>
        nav{
            padding: 1.5rem;
            position: fixed;
            width: 260px;
            
        }
        nav .nav-link, .register-link, .logut-mod {
            list-style: none;
            
        }
      
        
    </style>
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

                <li><a href="addtimeslot.php">
                <i class="fa-regular fa-clock"></i>
                <span class="link-name">Add Time Slot</span>
                </a>
                </li>

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

<!--
<section class="dashboard">
    <div class="top">
        <i class="fa-solid fa-bars sidebar-toggle"></i>

       
    </div>
</section>
---->
       
      <div class="container">
        <div class="d-flex" style= "
    position: fixed;
    top: 0%;
    left: 250px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: calc(100% - 250px);
    padding: 10px 14px;
    margin-top: 60px;
    background-color: aliceblue;
">
        <h2><a href="content.php" class="text-dark">
        <i class="bi bi-file-earmark"></i>Self-shoot</a>
    </h2>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#Addpromo">
        <i class="bi bi-plus-lg"></i> Add Promo
        </button>

      </div>
    </div>
    
    <div class="container mt-5 p-0">


<table class="table table-hover text-center" style="margin-top: 230px;">
    <thead class="bg-dark text-light">
        <tr>
        <th width="40%" >Promo Name</th>
        <th width="20%" >Promo Code</th>
        <th width="15%" >Price</th>
        <th width="35%"  class="rounded-end">Action</th>
        </tr>
    </thead>
    
      <!--delete show php-->
            <?php
            $connection = mysqli_connect("localhost","root", "");
            $db = mysqli_select_db($connection, 'package_db');

            $query = "SELECT * FROM promos";
            $query_run = mysqli_query($connection, $query);
            ?>
            <?php
                if($query_run){
                    while($row = mysqli_fetch_array($query_run)){

                        ?>
                        <tbody class="bg-white">
                            <tr>
                                <th><?php echo $row['Name'];?></th>
                                <th><?php echo $row['Package'];?></th>
                                <th><?php echo $row['Price'];?></th>
                                    <th>
                                    <form action="editpromo.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                        <button type="submit" name="edit_promo" class="btn btn-success">Edit</button>
                                    </form>
                                    </th>
                                        <form action="deletepromo.php" method="post">
                                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                            <th> <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this promo?')">Delete</button>
                                            </th>
                                        
                                        </form>
                            
                            </tr>
                        </tbody>
                        <?php
                        
                    }                    
                }
                else{
                    echo "No Record Found";
                }
            ?>
        
        <!--============= PHP ===============-->
           
</table>
</div>


    


    <div class="modal fade" id="Addpromo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
     <form action="" method="POST" enctype="multipart/form-data">
     <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Promo</h5>
      </div>
      <div class="modal-body">
      <div class="input-group mb-3">
     <span class="input-group-text">Name</span>
     <input type="text" class="form-control" name="name" required>
      </div>
      <div class="input-group mb-3">
     <span class="input-group-text">Promo Code</span>
     <input type="text" class="form-control" name="promo" min="1" required>
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text">Price</span>
        <input type="text" class="form-control" name="price" min="1" required>
      </div>
             <!--description-->
      
      <div class="modal-footer">
        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-success" name="Addpromo" id="promo-btn">Add</button>
      </div>
    </div>
    </form>
    </div>
   </div> 

   <script>
    document.getElementById('promo-btn').addEventListener('click', function(){
        alert('Promo Added Successful!');

    });
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>
</html>







<?php

//connect to database

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'package_db';


$conn = mysqli_connect($host, $user, $password, $dbname);

if(!$conn){
    die('Connection Failed: '. mysqli_connect_error());
}
//check if form is submitted

if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']== 'POST'){
//retrieve data from the input 

$name = $_POST['name'];
$promo = $_POST['promo'];
$price = $_POST['price'];

//execute query 

$query = "INSERT INTO promos (Name, Package, Price) VALUES ('$name','$promo',$price)";
$result = mysqli_query($conn,$query);
if($result){
    echo "Promo Added";
}else{
    echo "Error: " .mysqli_error($conn);
}
}
//close connection
mysqli_close($conn);
?>




