<?php
// Start the session
session_start();
if(empty($_SESSION['sesArr'])){
    header('Location:productListingPage.php');//if session empty redirect to home page
}
//db connection
include 'DBconn.php';
//remove from cart
if(isset($_GET['dlt_id'])){
    $dlt=$_GET['dlt_id'];
    $index=array_search($dlt,$_SESSION['sesArr']);// search index of id
    unset($_SESSION['sesArr'][$index]);//unset id from sesArr
    header('Location: cart.php');
}
//place order button
if(isset($_POST['btn_order'])){
    session_unset();
    header('Location:order.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cart page</title>
    <link rel="stylesheet" href="assets/css/pdp.css">
    <link rel="stylesheet" href="assets/css/cart.css">
    <script src="assets/js/library.js"></script>
    <script src="assets/js/price.js"></script>
</head>
<body>
    <header>
        <div class="topnav">
            <img src="./assets/img/logo.PNG" alt="logo.PNG" />
            <a href="productListingPage.php"><button>HOME </button></a>
        </div>
        <H1>MY CART</H1>
    </header>
    <main>
        <form method=post class="display">
     <?php
     $total=0;
     /****************loop starts ****************/
     foreach($_SESSION['sesArr'] as $id){ // loop starts
        $queryrun = $conn->prepare("SELECT * FROM product_details  where productid=$id ");
        $queryrun->execute();//returns 1 or 0
        $queryrun->setFetchMode(PDO::FETCH_ASSOC);
        $result = $queryrun->fetch();
        $total=$total+$result['productprice']; ?>
        <div class="plp_div">
            <div class="plp_img">
                <figure><img src="../img/<?php echo $result['productimg'] ?>"  alt="<?php echo $result['productimg'] ?>"  id='cart_img' width='100' height='100'/></figure>
            </div>
            <div class="plp_details">
                <div><?php echo "NAME : ".$result['productname']; ?></div><br>
                <div><?php echo "PRICE : ".$result['productprice']; ?></div><br>
                <div><?php echo "DESCRIPTION : ".$result['productdescription']; ?></div><br>
            </div>
            <div>
               Quantity:<select name="quantity" id="quantity" class="quantity">
               <option value="<?php echo $result['productprice']*1 ?>">1</option>
               <option value="<?php echo $result['productprice']*2 ?>">2</option>
               <option value="<?php echo $result['productprice']*3 ?>">3</option>
               </select>
            </div>
            <div>
                <a href="cart.php?dlt_id= <?php echo $result['productid']; ?> "><input type='button' value='REMOVE' name='btn_remove' id='btn_remove'> </a>
            </div>
        </div>
        <?php  } //loop ends ?>
        <!-- /****************loop ends ****************/ -->
        <div class="price">
           <h5>TOTAL PRICE</h5>
           <div class="totalPrice" id="totalPrice">
               <?php  echo $total; ?>
           </div>
        </div>
        <input type="submit" value="PLACE ORDER" name="btn_order" id="btn_order">
        </form>
    </main>
    <?php $conn = null; ?>
</body>
</html>