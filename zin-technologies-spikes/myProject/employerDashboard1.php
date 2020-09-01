<?php

include ('employerProtection.php');
include ('employerDashBoard1Controller.php');

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <title>Home</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.0.min.js"></script>
  <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
  <script src="http://malsup.github.com/jquery.form.js"></script> 
  <script src="jslibs/jquery.js" type="text/javascript"></script>
  <script src="jslibs/ajaxupload-min.js" type="text/javascript"></script>


  <style>

    .my-error-class {

      color:red;
    } 

  </style>

</head>

<body>

  <?php include ('employerHeader.php'); ?>
  <div class="container" id="imageDiv" style="display: none"> 
 
    <img id="image" class="img-responsive" src="" alt="Chania" width="70" height="55"> 
    <br>
  
  </div>

  <div class="container">

    <div class="panel panel-default">

      <div class="panel-heading">
        Post Your Job
      </div>

      <div class="panel-body">

        <div class="row">

          <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" style="margin-left:23%; margin-top:2%;">

            <form id="myForm" action="jobAjaxSubmit.php" method="post" >

              <div class="form-group">
              <label for="job">Job Title:</label> 
                <input class="form-control"  id="jjob" name="jobb">
                <span class="help-block" id="jobError"></span>
              </div>

              <div class="form-group ">
                <label for="salary">Salary:</label> 
                <input class="form-control"  id="ssalary" name="salaryy">                
                <span class="help-block" id="salaryError"></span>
              </div>

              <div class="form-group">
                
                <label for="comment">Job Description:</label>
                <textarea class="form-control" name="jobDescription" rows="5" id=""></textarea>
              
              </div>

              <div class="form-group ">
                <label for="type">Job Type:</label> 
                <input class="form-control"  id="jobType" name="jobTypee">                
                
              </div>


              <span class="help-block" id="successMessage"></span>

              <button type="submit" class="btn btn-lg btn-success">Post</button>

            </form>

          </div>

        </div>



      </div>

    </div>





  </div>


</body>


<script>
  var currentBorder=$("#jjob").css('border');

  <?php
    echo "makeActive($active);";
    if(isset($_SESSION['image']) && !empty($_SESSION['image']))
    { 
      $image = json_encode($_SESSION['image']);
      echo "printImage($image);";
    }
    
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

        jobb: 
        { 
         required: true 
       }, 
       salaryy: 
       { 
         required:true,
         digits: true 
       },
       jobDescription:
       {
        required:true
       },
       jobTypee:
       {
        required:true
       }
     },

     messages: 
     {

      jobb: 
      {
        required: "Please enter job Description"
      },
      salaryy: 
      { 
        required: "salary is required",
        digits: "please enter digits only"
      },
      jobDescription:
      {
        required:"Job Description is required"
      },
      jobTypee:
      {
        required:"Job Type is required"
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
        data:$("#myForm").serialize(),
        success: function(response) {

          if(response.tip==1)
          {
            $("#successMessage").text("Job has been posted").css("color", "green").fadeIn().delay(2000).fadeOut();
          }

        },
        error:function(xhr,textStatus,thrownError){

          alert("fail");

        } 


      });

    }





  });

  });






</script>



</html>
