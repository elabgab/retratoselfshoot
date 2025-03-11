
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php
    $connection = mysqli_connect("localhost","root","");
    $db = mysqli_select_db($connection,"package_db");

    if(isset($_POST["id"])){
    $id = $_POST['id'];
    
    $query = "SELECT *FROM promos WHERE id = '$id' ";
    $query_run = mysqli_query($connection,$query);
   
    if($query_run){
     while($row = mysqli_fetch_array($query_run)){
     
        ?>
        

        <div class="container">
            <div class="jumbotron">
                <h2>Update Promo</h2>
                <hr>
                <form action="" method="post">
                    <input type="hidden" name="id" value="<?php echo $row['id']?>">
                    <div class="form-group">
                        <label for="">Promo Name</label>
                        <input type="text" name="promo_name" class="form-control" value="<?php echo $row['Name']?>"  placeholder="Enter Promo Name" required>

                    </div>

                    <div class="form-group">
                        <label for="">Promo Code</label>
                        <input type="text" name="promo_code" class="form-control"  value="<?php echo $row['Package']?>" placeholder="Enter Promo Code" required>
                        
                    </div>

                    <div class="form-group">
                        <label for="">Price</label>
                        <input type="text" name="price" class="form-control" value="<?php echo $row['Price']?>"  placeholder="Enter Price" required>


                    </div>

                    <button type="submit" name="update" class="btn btn-primary" id="save-btn">Save Data</button>
                    <a href="content.php" class="btn btn-danger">Cancel</a>
                </form>
     
                <?php 
                if(isset($_POST['update'])){
                    $name = $_POST['promo_name'];
                    $package = $_POST['promo_code'];
                    $price = $_POST['price'];

                    $query = "UPDATE promos SET Name='$name', Package='$package' , Price='$price' WHERE id='$id' ";
                    $query_run = mysqli_query($connection,$query);

                    if($query_run){
                        echo '<script> alert("Data Updated"); </script>';
                        header("location:content.php");
                    }else{
                        echo '<script> alert("Data Not Updated"); </script>';
                    }
                    
                }
                ?>
                
            </div>

        </div>
        <?php
 }    
   } }


else{
    echo '<script> alert("Data Not Saved"); </script>';
}

    ?>
<script>
    document.getElementById('save-btn').addEventListener('click', function(){
        alert('Promo Edited Successful!');

    });
</script>

</body>
</html>