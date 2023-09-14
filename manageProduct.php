<?php
// Start the session
session_start();
//if not login then redirect to login page
if(empty($_SESSION['userName'])){
    header('Location: adminLoginPage.html');
}
//LOGOUT button
if(isset($_POST['btn_logout'])){
    session_unset();//unset session variable
    header('Location: adminLoginPage.html');
}
//db connection
include_once 'DBconn.php';
$data_rows=$cond1=$cond2=$data_err="";
$queryrun ="SELECT * FROM product_details inner join category_details on product_details.categoryid=category_details.categoryid where is_deleted=1";
//search button
if(isset($_POST['btn_search'])){
    $filter=$_POST['txt_filter'];
    $user_category=$_POST['sel_category'];
   
    if($_POST['sel_category']!="Choosecategory" ){
        $cond1=" and  category_details.categoryname='$user_category' ";
    }
    if(!empty($_POST['txt_filter'])){
        $cond2=" and productname like '$filter%' ";
    }
}
$queryrun=$queryrun.$cond1.$cond2; //concate conditins
$queryrun = $conn->prepare($queryrun);
$queryrun->execute();
$queryrun->setFetchMode(PDO::FETCH_OBJ);
$data_rows=$queryrun->rowcount();
$queryrun = $queryrun->fetchall();
//categorylist for dropdown
$categorylist = $conn->prepare("SELECT * FROM category_details");
$categorylist->execute();
$categorylist->setFetchMode(PDO::FETCH_OBJ);
//delete button
if(isset($_GET['productid'])){
    $id=$_GET['productid'];
    $delete_que = "UPDATE product_details SET is_deleted=null where productid=$id";
    $stmt = $conn->prepare($delete_que);
    $stmt->execute();
    header('Location: manageProduct.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage product list page</title>
    <link rel="stylesheet" href="assets/css/manageProduct.css">
</head>
<body>
    <?php include 'header.php'; ?>
 <main>
    <h1>PRODUCT LIST PAGE</h1>
    <div class="filterSection">
        <form method="post">
            Product Name:<input type="text" name="txt_filter" id="txt_filter" >
            <select name="sel_category" id="sel_category" >
              <option value="Choosecategory">Choose category</option>
              <?php
              foreach($categorylist as $cate){ ?>
                <option value= "<?php echo $cate->categoryname; ?>"> <?php echo $cate->categoryname; ?>   </option>
              <?php } ?>
            </select>
            <input type="submit" value="SEARCH" name="btn_search" id="btn_search">
            <input type="submit" value="SHOW ALL" name="btn_all" id="btn_all"><br><br>
            <input type="submit" name='btn_logout' id='btn_logout' value="LOGOUT"><br><br>
            <a href="addProductForm.php"> <input type='button' name='btn_add_product' id='btn_add_product' value='Add New Product'></a><br><br>
        </form>
    </div>
    <div class="listProducts">
        <table>
            <tr>
                <th>SI NO</th>
                <th>PRODUCT NAME</th>
                <th>PRODUCT CATEGORY</th>
                <th>PRICE</th>
                <th>ACTION</th>
            </tr>
            <?php
            if($data_rows==0){
                $data_err="Data not found";
           }else{
            foreach($queryrun as $row){?>
            <tr>
            <td><?php echo $row->productid; ?></td>
            <td><?php echo  $row->productname; ?></td>
            <td><?php echo $row->categoryname; ?></td>
            <td><?php echo  $row->productprice; ?></td>
            <td> <a href="addProductForm.php?p_id= <?php echo $row->productid ?>" ><input type='button' value='EDIT' name='btn_edit' id='btn_edit'></a>
            <a href='manageProduct.php?productid= <?php echo $row->productid ?>'><input type='submit' value='DELETE' name='btn_delete' id='btn_delete'><br></a>
            </tr>
            <?php } } ?>
        </table>
        <div> <?php echo $data_err ?> </div>
    </div>
 </main>
    <?php $conn = null; ?>
</body>
</html>