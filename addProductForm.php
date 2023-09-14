<?php
session_start();
//if not login then redirect to login page
if(empty($_SESSION['userName'])){
    header('Location: adminLoginPage.html');
    exit;
}
//if directly open this page it will go to managepproduct if login
// if(empty($_GET['p_id'])){

//db connection
include 'DBconn.php';
//categorylist for dropdown
$categorylist = $conn->prepare("SELECT * FROM category_details");
$categorylist->execute();
$categorylist->setFetchMode(PDO::FETCH_OBJ);
$check=-1; //condition for add or edit product button and title
$SuccessMsg="";
$show_p_name=$show_p_price=$show_p_desc=$show_p_cate="";
//getting p_id from manageproduct page
if(isset($_GET['p_id'])){
    $check=$_GET['p_id']; //id of selected prodct
    $que= $conn->prepare("SELECT * FROM product_details inner join category_details on product_details.categoryid=category_details.categoryid  where productid=$check");
    $que->execute();
    $que->setFetchMode(PDO::FETCH_ASSOC);
    $show = $que->fetch();
    $show_p_name=$show['productname'];
    $show_p_price=$show['productprice'];
    $show_p_desc=$show['productdescription'];
    $show_p_cate=$show['categoryid'];
}
//form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prd_name=$_POST['txt_product_name'];
    $prd_price=$_POST['txt_product_price'];
    $prd_cate=$_POST['sel_category'];
    $prd_desc=$_POST['product_descp'];
    $prd_img=$_FILES['product_img']['name'];
    if(empty($_POST['txt_product_name']) || empty($_POST['txt_product_price']) || $_POST['sel_category']=="" || empty($_POST['product_descp']) || $_FILES["product_img"]["error"]!=UPLOAD_ERR_OK){
        $SuccessMsg="Please fill all details";
    }
    else{
        $dest1 = "../img/".$_FILES['product_img']['name']; //update image also
        move_uploaded_file($_FILES["product_img"]['tmp_name'],$dest1);
        if(isset($_POST['btn_add']) ){//add button
            $queryrun = $conn->prepare("INSERT INTO  product_details(productname,productprice,categoryid,productdescription,productimg,is_deleted)VALUES('$prd_name','$prd_price','$prd_cate','$prd_desc','$prd_img',1)");
            $queryrun->execute();
            $SuccessMsg="Successfully ADDED";
        }
        if($_POST['btn_update']){//update button
            $update_que = "UPDATE product_details SET productname='$prd_name',productprice='$prd_price',categoryid='$prd_cate',productdescription='$prd_desc',productimg='$prd_img'  where productid=$check";
            $update_que = $conn->prepare($update_que);
            $update_que->execute();
            $SuccessMsg="Successfully UPDATED";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>product form</title>
    <link rel="stylesheet" href="assets/css/form.css">
    <script src="assets/js/library.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body>
    <header>
        <?php if($check==-1){ ?>
            <h1>ADD NEW PRODUCT</h1>
        <?php } else{ ?>
            <h1>EDIT PRODUCT</h1>
        <?php } ?>
    </header>
    <main>
        <form method="post" enctype="multipart/form-data">
            <div class="name">
                <label for="product name">Product Name: </label>
                <input type="text" id="txt_product_name" name="txt_product_name"
                    value="<?php echo $show_p_name; ?>" >
            </div>
            <br><br>
            Product Price:<input type="number" id="txt_product_price" name="txt_product_price" placeholder="Enter..."
                    value="<?php echo $show_p_price; ?>" ><br><br>
            <div class="category">
                <label for="category">category: </label>
                <select id="sel_category" name="sel_category">
                    <option value="<?php echo  $show_p_cate; ?>" default><?php if($check>0){ echo $show['categoryname']; } ?></option>
                    <?php
                    foreach($categorylist as $cate){
                        if($cate->categoryname !=$show['categoryname']){; ?>
                    <option value="<?php echo $cate->categoryid; ?>"> <?php echo $cate->categoryname; ?></option>
                    <?php } } ?>
                </select>
            </div>
            <div class="image">
                <br><br>
                    Select image:<input type="file" name="product_img" id="product_img" accept="image/*">
            </div>
            <br><br>
            <div class="Description">
                Product Description:
                <br><textarea id ='product_descp' name="product_descp" rows="3" cols="20"><?php echo $show_p_desc; ?></textarea><br><br>
            </div>
            <div class="sub">
                <?php if($check>0){ ?>
                <input type="submit" value="UPDATE" id="btn_update"  name="btn_update" class="btn_product_form">
                <?php } else
                 {?> <input type="submit" value="ADD" id="btn_add"  name="btn_add" class="btn_product_form">
                <?php  }  ?>
                <a href="manageProduct.php"><input type="button" value="BACK" id="btn_back"  name="btn_back"></a>
            </div>
            <div class="productMsg"> <?php echo $SuccessMsg; ?> </div>
        </form>
    </main>
    <?php $conn = null; ?>
</body>
</html>