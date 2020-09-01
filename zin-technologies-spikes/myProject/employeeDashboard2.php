<?php

include ('employeeProtection.php');
include ('employeeDashboard2Controller.php');


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <title>Update Info</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.0.min.js"></script>
  <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
  <script src="http://malsup.github.com/jquery.form.js"></script> 
  <script src="jslibs/jquery.js" type="text/javascript"></script>
  <script src="jslibs/ajaxupload-min.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>


  <style>

    .my-error-class {

      color:red;
    } 

  </style>

</head>

<body>

  <?php include ('employeeHeader.php'); ?>

  <div class="container" id="imageDiv" style="display: none"> 
 
    <img id="image" class="img-responsive" src="" alt="Chania" width="70" height="55"> 
    <br>
  
  </div>
  
  <div class="container">

    <div class="panel panel-default">

      <div class="panel-heading">
        Edit Your Profile
      </div>

      <div class="panel-body">

        <div class="row">

          <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" style="margin-left:23%; margin-top:2%;">

            <form id="myForm" action="employeeDashboard2ControllerAjaxSubmit.php" method="post" >

              <div class="form-group">
              <label for="name">Name:</label> 
                <input class="form-control"  id="name" name="nameName" >
                <span class="help-block" id="nameError"></span>
              </div>

              <div class="form-group ">
                <label for="email">Email:</label> 
                <input class="form-control"  id="email" name="emailName"  disabled>                

              </div>

              <div class="form-group ">
                <label for="type">Type:</label> 
                <input class="form-control"  id="type" name="typeName"  disabled>                

              </div>

              <div class="form-group">
                
                <label for="name">Choose Image:</label> 
                <input  class="form-control myClass" type="file" id="imageFile" name="imageName" >
                <span class="help-block" id="imageError"></span>
                
              </div>

              <span class="help-block" id="successMessage"></span>

              <button type="submit" class="btn btn-lg btn-success">update</button>

            </form>

          </div>

        </div>



      </div>

    </div>





  </div>


</body>


<script>
  var currentBorder=$("#name").css('border');

  <?php

    echo "makeActive($active);";
    if(isset($_SESSION['image']) && !empty($_SESSION['image']))
    { 
        $image = json_encode($_SESSION['image']);
        echo "printImage($image);";
    }

    $details=json_encode($row);
    echo "printProfileDetails($details);";

  ?>

  

  function printImage(image)
  {
      if(image.length != 0)
      {
          $("#image").attr("src","images/"+image);
          $("#imageDiv").css({"display":"block"});
      }
      else
      {
          $("#imageDiv").css({"display":"none"});
      }
  }


  $(document).ready(function(){

    $("#myForm").validate({

      errorClass: "my-error-class",

      rules: 
      { 

        nameName: 
        { 
         required: true 
        },
        
        imageName:
        {
          extension: "jpg|png"
        }
       
     },

     messages: 
     {

      nameName: 
      {
        required: "Please enter your Name"
      },
      
      imageName:
      {
        extension: "File should only be jpg or png format"
      }

    },

    highlight: function(element) 
    {
      $(element).css({"border":"1px solid red"});
    }, 

    unhighlight: function(element) 
    {
      $(element).css({"border":currentBorder});
    },


    submitHandler: function(form) 
    {
      
      $.ajax({

        url: form.action,
        type: form.method,
        dataType:'json',
        data: new FormData($("#myForm")[0]),
        processData: false,
        contentType: false,
        success: function(response) {

          $("#imageFile").val("");
          
          if(response.tip != 0)
            printImage(response.tip);
             
          updateName($("#name").val());
          $("#successMessage").text("Information has been updated").css("color", "green").fadeIn().delay(2000).fadeOut();
          

        },
        error:function(xhr,textStatus,thrownError){

          alert("fail");

        } 


      });

    }





  });

  });


  function printProfileDetails(details)
  {
    $("#name").val(details[0]);
    $("#email").val(details[1]);
    $("#type").val(details[2]);
  }





</script>



</html>
