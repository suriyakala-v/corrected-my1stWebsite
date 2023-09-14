<?php
// Start the session
session_start();
$empty_cart="";
if(empty($_SESSION['sesArr'])){
    $empty_cart="Your CART is waiting :)";
}
//db connection
include 'DBconn.php';
$data_rows=$cond1=$cond2=$data_err="";
$queryrun ="SELECT * FROM  product_details inner join category_details on product_details.categoryid=category_details.categoryid where is_deleted=1";
//search button
if(isset($_POST['btn_search'])){
  
    if($_POST['sel_category']!=null ){//disabled option
        $user_category=$_POST['sel_category'];
        $cond1=" and  category_details.categoryname='$user_category' ";
    }
    if(!empty($_POST['txt_filter'])){
        $filter=$_POST['txt_filter'];
        $cond2=" and productname like '$filter%' ";
    }
}
$queryrun=$queryrun.$cond1.$cond2;  //concate conditins
$queryrun = $conn->prepare($queryrun);
$queryrun->execute();
$queryrun->setFetchMode(PDO::FETCH_OBJ);
$data_rows=$queryrun->rowcount();
$queryrun = $queryrun->fetchall();
//categorylist for dropdown
$categorylist = $conn->prepare("SELECT * FROM category_details");
$categorylist->execute();
$categorylist->setFetchMode(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>product list page</title>
    <link rel="stylesheet" href="assets/css/plp.css">
</head>
<body>
    <header>
        <?php echo $empty_cart ?>
        <div class="topnav">
           <img src="./assets/img/logo.PNG" alt="logo.PNG" />
           <a href="cart.php"><input type="button" name="MY CART" value="MY CART" > </a>
        </div>
    </header>
    <main>
      <H1> *** WELCOME *** </H1>
      <h2>PRODUCT LISTING PAGE</h2>
      <div class="filterSection">
        <form method="post">
            Product Name:<input type="text" name=txt_filter>
            <select name='sel_category'>
            <option disabled selected value='Choosecategory'>Choose category</option>
            <?php
            foreach($categorylist as $cate){ ?>
                <option value= "<?php echo $cate->categoryname; ?>"> <?php echo $cate->categoryname; ?> </option>
              <?php } ?>
            </select>
            <input type="submit" value="SEARCH" name="btn_search" id="btn_search">
            <input type="submit" value="SHOW ALL" name="btn_all" id="btn_all"><br><br>
        </form>
      </div>
      <div class="listProducts">
            <?php
              if($data_rows==0){
                $data_err="Data not found";
               }else{
                foreach($queryrun as $row){?>
                <div class="plp_div">
                    <div class="plp_img">
                        <figure><img src="../img/<?php echo $row->productimg ?>" alt="<?php echo $row->productimg ?>" id='plp_img' /></figure>
                    </div>
                    <div class="plp_details">
                       <div><?php echo "NAME : ".$row->productname; ?></div><br>
                       <div><?php echo "CATEGORY : ".$row->categoryname; ?></div><br>
                       <div><?php echo "PRICE : ".$row->productprice; ?></div><br>
                       <div><?php echo "DESCRIPTION : ".$row->productdescription; ?></div><br>
                       <div><a href='productDetailPage.php?productid=<?php echo $row->productid ?>'><input type='button' value='VIEW' name='btn_view' id='btn_view'></a></div>
                    </div>
                </div>
            <?php }
             }
            echo $data_err ?>
      </div>
    </main>
    <?php $conn = null; ?>
</body>
</html>