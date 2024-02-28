
<?php
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>
<html>
    <head>

    </head>
        <titile></title>
     <?php
     // Check if the category parameter is set
     if(isset($_GET['category'])){
        // Get the category from the URL parameter
        $category = $_GET['category'];
        // Use prepared statement to avoid SQL injection
        $select_products = $conn->prepare("SELECT * FROM `products` WHERE category LIKE ?");
        $select_products->execute(['%'.$category.'%']);
        if($select_products->rowCount() > 0){
           while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
         <div class="price"><span>RS.</span><?= $fetch_product['price']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
           }
        }else{
           echo '<p class="empty">no Itemfound!</p>';
        }
     }else{
        // If no category is selected, display a message
        echo '<p class="empty">Please select a category!</p>';
     }
   ?>

   </div>
<html>