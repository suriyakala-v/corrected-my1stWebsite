<?php
session_start();
//if not login then redirect to login page
if(empty($_SESSION['userName'])){
    header('Location: manageProduct.php');
}
//db connection
include 'DBconn.php';
$check=-1; // condition for add or edit category button and title
$SuccessMsg="";
$show_c_desc=$show_c_name="";
//getting c_id from manageproduct page
if(isset($_GET['c_id'])){
    $check=$_GET['c_id'];
    $que= $conn->prepare("SELECT * FROM category_details  where categoryid=$check");
    $que->execute();
    $que->setFetchMode(PDO::FETCH_ASSOC);
    $show = $que->fetch();
    $show_c_name=$show['categoryname'];
    $show_c_desc=$show['categorydescription'];
}
//form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cate_name=$_POST['txt_category_name'];
    $cate_desc=$_POST['category_descp'];
    if(empty($_POST['txt_category_name']) || empty($_POST['category_descp'])){
        $SuccessMsg="Please fill all details";
    }
    elseif(isset($_POST['btn_add'])){//add button
        $add_que = $conn->prepare("INSERT INTO  category_details(categoryname,categorydescription)VALUES('$cate_name','$cate_desc')");
        $add_que->execute();
        $SuccessMsg="Successfully ADDED";
    }
    elseif(isset($_POST['btn_update'])){//update button
        $update_que = "UPDATE category_details SET categoryname='$cate_name',categorydescription='$cate_desc'  where categoryid=$check";
        $update_que=$conn->prepare($update_que);
        $update_que->execute();
        $SuccessMsg="Successfully UPDATED";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>category form</title>
    <link rel="stylesheet" href="assets/css/form.css">
    <script src="assets/js/library.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body>
    <header>
        <?php if($check==-1){ ?>
            <h1>ADD NEW CATEGORY </h1>
        <?php } else{ ?>
            <h1>UPDATE CATEGORY </h1>
        <?php } ?>
    </header>
    <main>
        <form method="post">
            <div class="name">
                <label for="category name">category  Name: </label>
                <input type="text" id="txt_category_name" name="txt_category_name" placeholder="Enter..."
                value="<?php echo $show_c_name; ?>" ><br><br>
            </div>
            <div class="Description">
                category  Description:
                <br><textarea id ='category_descp' name="category_descp" rows="3" cols="20"><?php echo $show_c_desc; ?></textarea><br><br>
            <div class="sub">
                <input type="submit"  class="btn_category_addform" <?php if($check>0){ ?> value="UPDATE" id="btn_update"  name="btn_update"  <?php } else {?> value="ADD" id="btn_add"  name="btn_add" <?php }?> >
                <a href="manageCategory.php"><input type="button" value="BACK" id="btn_back"  name="btn_back"></a>
            </div>
            <div class="categoryMsg"> <?php echo $SuccessMsg; ?> </div>
        </form>
    </main>
        <?php $conn = null; ?>
</body>
</html>