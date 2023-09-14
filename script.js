$(document).ready(function () {
 
  //ajax- for login button
  $("#btn_ok").click(function () {
    let user = $("#txt_username").val();
    let pswd = $("#txt_pwd").val();
      $.post("pdoconAjax.php", {
      username: user,
      password: pswd,
      },
      function (data, status) {//function- user & pwd match or not
        // let result = JSON.parse(data);
        if (data == "INVALID! user and password not match") {
          $('#err_msg').html(data);
        }
        else {
          window.location.href = 'http://localhost/php/fullstackassessment/AssessmentRE/adminSection/manageProduct.php';
        }
      });
  });
 //ajax- if login directly go to productmanage page
 $(".login").load("pdoconAjax.php",
  function(data,status){
   if (status == "success") {
    data = JSON.parse(data);
    if(data=="already loggedin"){
      //alert(data);
      location = "manageProduct.php";
    }
  }
});
  //add product form
  $(".btn_product_form").click(function (event) {
    let p_name = $("#txt_product_name").val();
    let p_price = $("#txt_product_price").val();
    let p_cate = $("#sel_category").val();
    let p_img = $("#product_img").val();
    let p_desc = $("#product_descp").val();
    let finalSubmit = true;
    //name
    if (/^[a-zA-Z]*$/.test(p_name) && p_name.length != 0 ) {
      $("#txt_product_name").addClass('greenBox');
    }
    else {
      $("#txt_product_name").removeClass('greenBox').addClass('redBox');
      finalSubmit = false;
    }
    //price
    if (p_price == "" || isNaN(p_price)) {
      $("#txt_product_price").removeClass('greenBox').addClass('redBox');
      finalSubmit = false;
    }
    else{
      $("#txt_product_price").addClass('greenBox');
    }
    //category
    if (p_cate == "") {
      $('#sel_category').removeClass('greenBox').addClass('redBox');
      finalSubmit = false;
    }
    else{
      $("#sel_category").addClass('greenBox');
    }
    //image
    if (p_img == "") {
      $('#product_img').removeClass('greenBox').addClass('redBox');
      finalSubmit = false;
    }
    else{
      $("#product_img").addClass('greenBox');
    }
    //description
    if (p_desc == "" || p_desc.length == 0 || /^[.0-9 ]*$/.test(p_desc)) {
      $('#product_descp').removeClass('greenBox').addClass('redBox');
      finalSubmit = false;
    }
    else{
      $("#product_descp").addClass('greenBox');
    }
    // check all condition
    if (finalSubmit) {
      $('.productMsg').html("Successfull").addClass('greenText');
    }
    else {
      $('.productMsg').html("INVALID!").removeClass('productMsg').addClass('errTextColour');
      event.preventDefault(); //prevent form from submit
    }

  });
  //add category form
  $(".btn_category_addform").click(function (event) {
    let c_name = $("#txt_category_name").val();
    let c_desc = $("#category_descp").val();
    let finalSubmit = true;
     //name
     if (/^[a-zA-Z]*$/.test(c_name) && c_name.length != 0) {
      $("#txt_category_name").addClass('greenBox');
    }
    else {
      $("#txt_category_name").removeClass('greenBox').addClass('redBox');
      finalSubmit = false;
    }
    if (c_desc == "" || /^[.0-9  ]*$/.test(c_desc)) {
      $('#category_descp').removeClass('greenBox').addClass('redBox');
      finalSubmit = false;
    }
    else{
      $("#category_descp").addClass('greenBox');
    }
    // check all condition
    if (finalSubmit) {
      $('.categoryMsg').html("Successfull").addClass('greenText');
    }
    else {
      $('.categoryMsg').html("INVALID!").removeClass('categoryMsg').addClass('errTextColour');
      event.preventDefault(); //prevent form from submit
    }
  });

$("input").keypress(function () {
  $(this).removeClass('redBox');
})
$('select').focus(function(){
    $(this).removeClass('redBox');
})
$('textarea').focus(function(){
  $(this).removeClass('redBox');
})

});
