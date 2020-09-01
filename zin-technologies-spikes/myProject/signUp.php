<?php
 include ('unRegisteredProtection.php');     

?>



       <html>
       <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign Up Form</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link href='https://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/main.css">
        <meta charset="utf-8">
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>
      </head>

      <style>
        .my-error-class {
          color:red;
        } 
        *, *:before, *:after {
          -moz-box-sizing: border-box;
          -webkit-box-sizing: border-box;
          box-sizing: border-box;
        }

        body {
          font-family: 'Nunito', sans-serif;
          color: #384047;
        }

        form {
          max-width: 300px;
          margin: 10px auto;
          padding: 10px 20px;
          background: #f4f7f8;
          border-radius: 8px;
        }

        h1 {
          margin: 0 0 30px 0;
          text-align: center;
        }

        input[type="text"],
        input[type="password"],
        input[type="date"],
        input[type="datetime"],
        input[type="email"],
        input[type="number"],
        input[type="search"],
        input[type="tel"],
        input[type="time"],
        input[type="url"],
        textarea,
        select {
          background: rgba(255,255,255,0.1);
          border: none;
          font-size: 16px;
          height: auto;
          margin: 0;
          outline: 0;
          padding: 15px;
          width: 100%;
          background-color: #e8eeef;
          color: #8a97a0;
          box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
          margin-bottom: 30px;
        }

        input[type="radio"],
        input[type="checkbox"] {
          margin: 0 4px 8px 0;
        }

        select {
          padding: 6px;
          height: 32px;
          border-radius: 2px;
        }

        button {
          padding: 19px 39px 18px 39px;
          color: #FFF;
          background-color: #4bc970;
          font-size: 18px;
          text-align: center;
          font-style: normal;
          border-radius: 5px;
          width: 100%;
          border: 1px solid #3ac162;
          border-width: 1px 1px 3px;
          box-shadow: 0 -1px 0 rgba(255,255,255,0.1) inset;
          margin-bottom: 10px;
        }

        fieldset {
          margin-bottom: 30px;
          border: none;
        }

        legend {
          font-size: 1.4em;
          margin-bottom: 10px;
        }

        label {
          display: block;
          margin-bottom: 8px;
        }

        label.light {
          font-weight: 300;
          display: inline;
        }

        .number {
          background-color: #5fcf80;
          color: #fff;
          height: 30px;
          width: 30px;
          display: inline-block;
          font-size: 0.8em;
          margin-right: 4px;
          line-height: 30px;
          text-align: center;
          text-shadow: 0 1px 0 rgba(255,255,255,0.2);
          border-radius: 100%;
        }

        @media screen and (min-width: 480px) {

          form {
            max-width: 480px;
          }

        }
      </style>



      <body>






        <form id="myForm"  class="doubleForm" action="signUpController.php"   method="post" >

          <h1>Sign Up</h1>

          <fieldset>

            <legend><span class="number">1</span>Your basic info</legend>

            <br>
            <div class="form-group">

              <label for="Type">Select Type:</label>

              <select name="person"  id="personType">

                <option value="employee">employee</option>
                <option value="employer">employer</option>

              </select>

            </div>

              <div class="form-group">

                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name"  name="name" >
                <span class="help-block" id="nameError" style="color:red"></span>
              
              </div>

              <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" class="form-control" id="email"  name="email" >
                <span class="help-block" id="emailError" style="color:red"></span>  


              </div>


              <div class="form-group">
                <label for="pwd">Password:</label>
                <input type="password" class="form-control" id="pwd"  name="pwd">
                <span class="help-block" id="passwordError" style="color:red"></span>
              </div>

              <div class="form-group">
                <label for="pwd">Confirm Password:</label>
                <input type="password" class="form-control" id="pwd1"  name="pwd1" >
                <span class="help-block" id="confirmPasswordError" style="color:red"></span>
              </div>

              <div class="form-group" id="company" style="display: none">

                <label for="Company">Company Name:</label>
                <input type="text" class="form-control" id="comp"  name="comp" >
                <span class="help-block" id="companyNameError" style="color:red"></span>

              </div>

            </fieldset>

            <button type="submit">Sign Up</button>

            <div>
              <a class="navbar-brand" href="logIn.php">Login ?</a>
            </div>

          </form>

        </body>

        <script>
          var currentBorder=$("#name").css('border');

          var employeeId="myForm";
          var employerId="form2";
          var formId="#myForm";

          $(document).ready(function(){



        $("#myForm").validate({
          
        errorClass: "my-error-class",
          
         rules: 
         { 
           
            name: "required", 
            email: 
            { 
               required: true, 
               email: true 
            }, 
            pwd: 
            { 
               required:true, 
               minlength:8
            },
            pwd1: 
            { 
               equalTo: "#pwd",
               required:true
            },
            comp:
            {
              required: function (){
                if($(".doubleForm").attr('id')=="form2")
                {
                  return true;
                }
                else
                {
                  return false;
                }
              }
            }  
        },
        
          messages: 
          {
            name: "Please specify your name",
            email: 
            {
              required: "Please enter email address",
              email: "Your email address must be in the format of name@domain.com"
            },
            pwd: 
            { 
              required: "Password is required", 
              minlength:"password should be 8 characters long"  
            }, 
            pwd1: 
            { 
              equalTo: "Password didn't matched",
              required: "Confirm your Password"
            },
            comp:
            {
              required:"Please enter your company name"
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
          
          
          submitHandler: function(form,e) 
          {
      
            if($(".doubleForm").attr('id')=="myForm")
            { 
              $.ajax({

                url: form.action,
                type: form.method,
                dataType:'json',
                data:$("#myForm").serialize(),
                success: function(response) {

                    employeeFunction(response);
            
                },
                error:function(xhr,textStatus,thrownError){

                  //alert("fail");

                } 


              });
            }
            else
            {
              $.ajax({

                url: form.action,
                type: form.method,
                dataType:'json',
                data:$("#form2").serialize(),
                success: function(response) {

                    employerFunction(response);
                    
                },
                error:function(xhr,textStatus,thrownError){

                 // alert("fail");

                } 


              });
            }

              //form.submit();  // without ajax request
          }// submit handler brackets




          });// validation function brackets



        $("#email").focusin(function () {

         $("#emailError").fadeOut(1000);
       });

        $("#name").focusin(function () {

         $("#nameError").fadeOut(1000);
       });

        $("#pwd").focusin(function () {

         $("#passwordError").fadeOut(1000);
       });

        $("#pwd1").focusin(function () {

         $("#confirmPasswordError").fadeOut(1000);
       });

        $("#comp").focusin(function () {

         $("#companyNameError").fadeOut(1000);
       });


        $("#email").focusout(function () {

          // if($("#email").val()=="") 
            $("#emailError").fadeIn(1000);
        
      });

        $("#name").focusout(function () {
 
          // if($("#name").val()=="") 
              $("#nameError").fadeIn(1000);
       });

        $("#pwd").focusout(function () {

         // if($("#pwd").val()=="")
            $("#passwordError").fadeIn(1000);
        
      });

        $("#pwd1").focusout(function () {
          
        //  if($("#pwd1").val()=="")  
           $("#confirmPasswordError").fadeIn(1000);
       });

        $("#comp").focusout(function () {

        //  if($("#comp").val()=="")
            $("#companyNameError").fadeIn(1000);
       });

      

          $("#personType").change(function(){

            if($("#personType").val()=="employer")
            {
              $("#company").css("display","block");
              //$("#company").css({"display":"block"}); // can also write it like this 
              $(".doubleForm").attr('id',employerId);
              formId=$(".doubleForm").attr('id');  
              formId='"'+'#'+formId+'"';
            }

            if($("#personType").val()=="employee")
            { 
              $("#company").css({"display":"none"});  
              $(".doubleForm").attr('id',employeeId);
              formId=$(".doubleForm").attr('id');
              formId='"'+'#'+formId+'"';
            }

          });



});


    function employerFunction(response)
    {
        if(response.tip==1)
        {
            
            $("#nameError").text(response.nameError);
            $("#emailError").text(response.emailError);
            $("#passwordError").text(response.passwordError);  
            $("#confirmPasswordError").text(response.confirmPasswordError);
            $("#companyNameError").text(response.companyNameError);
        }
        else
        {
              
              window.location.href="employerDashboard1.php";
              //alert("pass");      // on next page of employer
        }

    }

    function employeeFunction(response)
    {
      
        if(response.tip==1)
        {   

            $("#nameError").text(response.nameError);
            $("#emailError").text(response.emailError);
            $("#passwordError").text(response.passwordError);  
            $("#confirmPasswordError").text(response.confirmPasswordError);
        }
        else
        {
             window.location.href="employeeDashboard1.php"; 
             // alert("pass");      // on next page of employee
        }      
    }





        </script>

        </html>