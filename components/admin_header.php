<?php

$admin_id = $_SESSION['admin_id']; // Assuming you store the admin ID in the session
$select_category = $conn->prepare("SELECT category FROM `admins` WHERE id = ?");
$select_category->execute([$admin_id]);
$fetch_category = $select_category->fetch(PDO::FETCH_ASSOC);
$adminCategory = $fetch_category['category'];


   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>SOE</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <style>
    /* Add this in your CSS or within a style tag */
    .notification-bell {
        position: relative;
        cursor: pointer;
    }

    .notification-count {
        position: absolute;
        top: -7px;
        right:-14;
        background-color: red;
        color: white;
        border-radius: 50%;
        padding: 4px;
        font-size: 12px;
    }

    .notification-dropdown {
        display: none;
        position: absolute;
        top: 30px;
        right: 0;
        width: 300px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border: 1px solid #ddd;
        z-index: 1;
    }

    .notification-dropdown a {
        display: block;
        padding: 10px;
        text-decoration: none;
        color: #333;
        transition: background-color 0.3s;
    }

    .notification-dropdown a:hover {
        background-color: #f5f5f5;
    }
</style>

</head>
<body>

<header class="header">

   <section class="flex">

   <a href="<?= getHomeLink($adminCategory) ?>"class="logo">SOE   <span>Admin_Panel</span></a>

   <nav class="navbar">
            <a href="<?= getHomeLink($adminCategory) ?>">Home</a>
            <?php if ($adminCategory == 'admin') : ?>
                <a href="../admin/admin_accounts.php">Admins</a>
            <?php endif; ?>
            <?php if ($adminCategory == 'admin'|| $adminCategory == 'manager') : ?>
                <a href="../admin/users_accounts.php">Users</a>
            <?php endif; ?>
            <a href="../admin/products.php">Items</a>
            <a href="../admin/placed_orders.php">Orders</a>
            <?php if ($adminCategory == 'admin'|| $adminCategory == 'manager') : ?>
            <a href="../admin/messages.php">Messages</a>
            <?php endif; ?>
         </nav>
      <<div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>
        <div class="notification-bell" id="notificationBell">
            <i class="fas fa-bell"></i>
            <span class="notification-count" id="notificationCount">0</span>
            <div class="notification-dropdown" id="notificationDropdown">
                <!-- Add your notification items here -->
                <!-- Example: <a href="#">Notification 1</a> -->
            </div>

        <div class="profile">
            <?php
            $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <p><?= $fetch_profile['name']; ?></p>
            <a href="../admin/update_profile.php" class="btn">Update Profile</a>
            <div class="flex-btn">
                <a href="../admin/register_admin.php" class="option-btn">Register</a>
                <a href="../admin/admin_login.php" class="option-btn">Login</a>
            </div>
            <a href="../components/admin_logout.php" class="delete-btn"
                onclick="return confirm('logout from the website?');">logout</a>
        </div>


   </section>

</header>
<?php
// Function to get the dynamic home link based on the admin category
function getHomeLink($category)
{
    switch ($category) {
        case 'admin':
            return '../admin/dashboard.php';
        case 'manager':
            return '../admin/manager_admin.php'; // Change this to the actual manager dashboard link
        case 'staff':
            return '../admin/staff_admin.php'; // Change this to the actual staff dashboard link
        default:
            return '../admin/dashboard.php'; // Default to admin dashboard if category is not recognized
    }
}
?>
</body>
</html>