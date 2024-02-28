<?php
// add_product.php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit;
}

$message = []; // Array to store messages

if (isset($_POST['add_product'])) {

    // Get form data
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $details = $_POST['details'];
    $details = filter_var($details, FILTER_SANITIZE_STRING);
    $category = $_POST['category']; // Get the selected category

    // Check if images are uploaded and process them
    if (isset($_FILES['image_01']['name'])) {
        // Get image properties
        $image_01 = $_FILES['image_01']['name'];
        $image_size_01 = $_FILES['image_01']['size'];
        $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];

        // Set image folder paths
        $image_folder_01 = '../uploaded_img/' . $image_01;
        $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
        $select_products->execute([$name]);
        if($select_products->rowCount() > 0)
        {
           $message[] = 'Item Name Already Exist!';
        }else
        {
     
           $insert_products = $conn->prepare("INSERT INTO `products`(name, details, price, image_01, category) VALUES(?,?,?,?,?)");
           $insert_products->execute([$name, $details, $price, $image_01, $category]);
     
           if($insert_products)
          {
              if($image_size_01 > 2000000 )
            {
                 $message[] = 'Image Size Is Too Large!';
              }else
            {
                 move_uploaded_file($image_tmp_name_01, $image_folder_01);
                 
                 $message[] = 'New Product Added!';
              }
     
           }
     
        }  
    } 
    else {
        // If any of the images are missing, show an error message
        $message[] = 'Please Upload All Three Images!';
    }
}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:products.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Items</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-products">

   <h1 class="heading">Add Item</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>Item Name (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="Enter Item Name" name="name">
         </div>
         <div class="inputBox">
            <span>Item Price (required)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="Enter Item Price" onkeypress="if(this.value.length == 10) return false;" name="price">
         </div>
        <div class="inputBox">
            <span>Image 01 (required)</span>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
        <div class="inputBox">
            <span>Category (required)</span>
            <select id="category" name="category" required>
                <option value="Cool Drinks">Cool Drinks</option>
                <option value="Combo">Combo</option>
                <option value="BreakFast">BreakFast</option>
                <option value="Hot Drinks">Hot Drinks</option>
                <option value="Non-Veg">Non-Veg</option>
                <option value="Veg">Veg</option>
                <option value="IceCream">IceCream</option>
                <option value="Huka">Huka</option>
            </select>
        </div>
         <div class="inputBox">
            <span>Item Details (required)</span>
            <textarea name="details" placeholder="Enter Item Details" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
      </div>
      
      <input type="submit" value="add product" class="btn" name="add_product">
   </form>

</section>

<section class="show-products">

   <h1 class="heading">Items Added</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="price">RS.<span><?= $fetch_products['price']; ?></span>/-</div>
      <div class="details"><span><?= $fetch_products['details']; ?></span></div>
      <div class="category"><?= $fetch_products['category']; ?></div>
      <div class="flex-btn">
         <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Update</a>
         <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">Delete</a>
      </div>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">No Items Added Yet!</p>';
      }
   ?>
   
   </div>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>
