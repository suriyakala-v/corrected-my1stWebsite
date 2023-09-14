<?php
// Start the session
session_start();
//db connection
include 'DBconn.php';
//redirect to plp if directly come to ths page
if(empty($_GET['productid'])){
    header('Location:productListingPage.php');
}
//getting productid from plp page
$id=$_GET['productid'];
$queryrun ="SELECT * FROM product_details inner join category_details on product_details.categoryid=category_details.categoryid where productid=$id ";
//$queryrun = $conn->prepare("SELECT * FROM product_details  where productid=$id ")//echo $result[0]['categoryname']; associate arr
$queryrun = $conn->prepare($queryrun);
$queryrun->execute();
$queryrun->setFetchMode(PDO::FETCH_ASSOC);
$result = $queryrun->fetchall();

//add to cart button
$added="";
if(isset($_POST['btn_cart'])){
    if(empty($_SESSION['sesArr'])){ //for 1st cart product
        $_SESSION['sesArr'][]= $id;
        $added="Successfully ADDED";
    }
    elseif(in_array($id, $_SESSION['sesArr'])){
        $added="Already in cart";
        header('Location:cart.php');
    }
    else{ //for nth cart product
        $_SESSION['sesArr'][]= $id;
        $added="Successfully ADDED ";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>product detail page</title>
    <link rel="stylesheet" href="assets/css/pdp.css">
</head>
<body>
    <header>
        <H1>PRODUCT DETAIL PAGE</H1>
    </header>
    <main>
        <div class="imgDiv">
            <img src="../img/<?php echo $result[0]['productimg'] ?>" alt="<?php echo $result[0]['productimg'] ?>" id='pdp_img' />
        </div>
        <div class="detailsDiv">
            <h4>PRODUCT NAME           =  <?php echo $result[0]['productname'] ?> </h4>
            <h4>PRODUCT DESCRIPTION          =  <?php echo $result[0]['productdescription'] ?> </h4>
            <h4>PRODUCT PRICE        =  <?php echo $result[0]['productprice'] ?> </h4>
            <h4>PRODUCT CATEGORY        =  <?php echo $result[0]['categoryname'] ?> </h4>
        </div>
    </main>
    <footer>
        <div class="clicks" >
            <form method=post>
                <input type="submit" value="ADD TO CART" name="btn_cart" id="btn_cart">
                <a href="productListingPage.php"><input type="button" value="BACK" id="btn_back"  name="btn_back"></a>
            </form>
        </div>
        <div class="added">
            <h3> <?php  echo $added ?> </h3>
        </div>
    </footer>
    <?php $conn = null; ?>
</body>
</html>