<?php

  session_start();

 include __DIR__.'/../config.php';
 require(__DIR__.'/../database/db_connect.php');

 $current_page = basename($_SERVER['REQUEST_URI']);

 // loging out user after his session ends
//  if(isset($_SESSION['login_time'])){
//   if(time() - $_SESSION['login_time'] > 1200){
//     session_unset();
//     session_destroy();
//   }
//  }

  // handling number of items in the cart
  $total_quantity = 0;
  $total_price = 0;
  if(isset($_SESSION['customer_id'])){
    $customer_id=$_SESSION['customer_id'];
    $q = "SELECT total_quantity, total_price FROM  view_customer_total_items WHERE customer_id ='$customer_id'; ";
    $r = mysqli_query($connect,$q);
    if($r && mysqli_num_rows($r) > 0){
      $result = mysqli_fetch_assoc($r);
      $total_quantity = $result['total_quantity'];
      $total_price = $result['total_price'];
    }
  }else{
    if(isset($_SESSION['cart'])){
      foreach($_SESSION['cart'] as $item){
        $total_quantity += $item['in_cart_quantity'];
        $total_price += (float) $item['price'] * $item['in_cart_quantity'];
      }
    }
  }
    
 
 
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- font awesome kit -->
    <script src="https://kit.fontawesome.com/06211be5ed.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="<?=MAIN_DIRECTORY?>/css/style.css" >
    <title>MKTIME</title>
  </head>

  <body>
    <div class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
                <a href="#" class="navbar-brand">MKTIME</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navigators" aria-controls="navigators" aria-expanded="false" aria-label="toggle navigator">
                
                  <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navigators">
                  <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                      <a href="<?= MAIN_DIRECTORY?>/home.php" class="nav-link <?= $current_page == 'home.php'? 'active':''; ?>">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" arial-expanded="false">Category</a>

                      <ul class="dropdown-menu" aria-labelledby="navbarDropdown active">
                        <li><a href="<?= MAIN_DIRECTORY?>/category/men_clothing.php" class="dropdown-item <?= $current_page == 'category/men_clothing.php'? 'active':''; ?>">men's clothing</a></li>
                        <li><a href="<?= MAIN_DIRECTORY?>/category/jewelery.php" class="dropdown-item <?= $current_page == 'category/jewelery.php'? 'active':''; ?>">jewelery</a></li>
                        <li><a href="<?= MAIN_DIRECTORY?>/category/electronic.php" class="dropdown-item <?= $current_page == 'category/electronic.php'? 'active':''; ?>">electronics</a></li>
                        <li><a href="<?= MAIN_DIRECTORY?>/category/women_clothing.php" class="dropdown-item <?= $current_page == 'women_clothing.php'? 'active':''; ?>">women's clothing</a></li>
                      </ul>
                    
                    </li>
                  </ul> 

                  
                      
                  <form class="m-0" action="<?=MAIN_DIRECTORY?>/home.php" method="post" >
                    <div class="input-group">
                      <input  name="search" type="search" class="form-control" placeholder="Search"  value="<?=isset($_POST['search'])?$_POST['search']:''?>">
                      <input class="btn btn-light text-dark" type="submit" name="search_btn" value="Search">
                    </div>
                  </form>
                       
                    

                     
                        <ul class="navbar-nav ms-auto">
                          <li class="nav-item my-auto">
                            <a href="<?= MAIN_DIRECTORY?>/user/cart.php" class="nav-link  <?=$current_page == 'cart.php'? 'active':'' ?>"><i class="fa-solid fa-lg fa-cart-shopping" style="color: #FFD43B;">
                              <?=$total_quantity?>
                            </i></a>
                          </li>
                          <li class="nav-item text-white">
                            <?php if(isset($_SESSION['username'])){
                              ?>
        
                                 <form class="my-auto" action="<?=MAIN_DIRECTORY?>/user/logout.php" method="post">
                                    <button type="submit" name="logout" class="btn btn-md btn-dark " style="box-shadow: none;">Logout</button>
                                 </form>

                              <?php
                            }else{ ?>

                              <a href="<?= MAIN_DIRECTORY?>/user/login.php" class="nav-link <?= $current_page=='login.php' ? 'active':''?>" >Login </a>
                            
                            <?php
                            }
                            ?>
                          </li>
                          <li class="nav-item text-white">
                            <?php
                              if(isset($_SESSION['username'])){
                                ?>
                                    <a class="nav-link active fw-bold" href="<?=MAIN_DIRECTORY?>/user/profile.php"><?=$_SESSION["username"]?></a>
                                <?php
                              }
                            ?>
                          </li>
                        </ul>
                   
                </div>
        </div>
    </div>
    
    
    <?php
      // display message after adding items to the cart
      if(isset($_SESSION['cart_message'])){
        ?>
            <div class="container-fluid">
              <div class="row justify-content-center">
                <div class="col col-10 col-md-4  text-center">
                  <div class="alert alert-warning alert-dismissible fade show mt-3 fw-bold" role="alert">
                      <?=$_SESSION['cart_message']?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                </div>
              </div>
            </div>

        <?php

        unset($_SESSION['cart_message']);
      }

    ?>
