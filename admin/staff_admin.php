<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="dashboard">

   <h1 class="heading">Dashboard</h1>

   <div class="box-container">

      <div class="box">
         <h3>Welcome!</h3>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="update_profile.php" class="btn">Update Profile</a>
      </div>

      <div class="box" >
    
         
         <p id="orderplaced">Total Order placed</p>
         <a href="placed_orders.php" class="btn">See Placed Orders</a>
      </div>

     
      <div class="box">
         <?php
            $select_pending_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = 'pending'");
            $select_pending_orders->execute();
            $number_of_pending_orders = $select_pending_orders->rowCount();
         ?>
         <h3><?= $number_of_pending_orders; ?></h3>
         <p>PendingOrders</p>
         <a href="pending_orders.php" class="btn">See Pending Orders</a>
      </div>

      <div class="box">
         <?php
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            $number_of_products = $select_products->rowCount()
         ?>
         <h3><?= $number_of_products; ?></h3>
         <p>Items Added</p>
         <a href="products.php" class="btn">See Items</a>
      </div>

    

    


      
   </div>

</section>












<script src="../js/admin_script.js"></script>
<script>
    function table(){
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function(){
            document.getElementById("orderplaced").innerHTML = this.responseText;
        }
        xhttp.open ("GET", "ajax.php");
        xhttp.send();

    }

    setInterval(function(){
        table();
    }, 1000)
</script>

</body>
</html>