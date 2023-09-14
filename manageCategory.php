<?php
// Start the session
session_start();
//if not login then redirect to login page
if(empty($_SESSION['userName'])){
    header('Location: adminLoginPage.html');
}
//LOGOUT button
if(isset($_POST['btn_logout'])){
    session_unset();//unset sess variable
    header('Location: adminLoginPage.html');
}
//db connection
include 'DBconn.php';
$count=$data_rows= $cond=$data_err="";
$queryrun = "SELECT category_details.categoryid, categoryname, count(productid) as total FROM category_details LEFT JOIN  product_details
ON category_details.categoryid=product_details.categoryid AND is_deleted=1";
$cond=" GROUP BY category_details.categoryid ";
//search button
if(isset($_POST['btn_search'])){
    if(!empty($_POST['txt_filter']) ){
        $filter=$_POST['txt_filter'];
        $cond = " where categoryname like '$filter%' ".$cond;
    }
}
$queryrun=$queryrun.$cond; //concate conditions
$queryrun = $conn->prepare($queryrun);
$queryrun->execute();
$queryrun->setFetchMode(PDO::FETCH_OBJ);
$data_rows=$queryrun->rowcount();//rows
$queryrun = $queryrun->fetchall();

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
        <h1>CATEGORY LIST PAGE</h1>
        <div class="filterSection">
            <form method="post">
                Category Name:<input type="text" name=txt_filter>
                <input type="submit" value="SEARCH" name="btn_search" id="btn_search">
                <input type="submit" value="SHOW ALL" name="btn_all" id="btn_all"><br><br>
                <input type="submit" name='btn_logout' id='btn_logout' value="LOGOUT"><br><br>
                <a href='addCategoryForm.php'> <input type='button' name='btn_add_category' id='btn_add_category' value='Add New category'> </a>
                <br><br>
            </form>
        </div>
        <div class="listProducts">
           <table>
              <tr>
                 <th>SI NO</th>
                 <th>CATEGORY NAME</th>
                 <th>NO OF PRODUCTS</th>
                 <th>ACTION</th>
              </tr>
              <?php
              if($data_rows==0){
                 $data_err="Data not found";
              }else{
                 foreach($queryrun as $row){?>
                    <tr>
                    <td><?php echo $row->categoryid ?></td>
                    <td> <?php echo $row->categoryname ?></td>
                    <td> <?php echo $row->total ?></td>
                    <td> <a href='addCategoryForm.php?c_id= <?php echo $row->categoryid ?>' ><input type='button' value='EDIT' name='btn_edit' id='btn_edit'></a>
                    </tr>
                <?php } } ?>
            </table>
            <div> <?php echo $data_err ?> </div>
        </div>
    </main>
    <?php $conn = null; ?>
</body>
</html>