
<?php

include ('unRegisteredProtection.php');
?>


 <html>
  <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LogIn Form</title>
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
    
      <form id="myForm" action="logInController.php" method="post">
      
        <h1>Login</h1>
        
        <fieldset>
          
          <legend><span class="number">1</span>Enter your credentials</legend>
          
          <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" class="form-control" id="email"  name="email" value="<?php  if(isset($_SESSION['email']) && !empty($_SESSION['email'])){ echo $_SESSION['email']; }   unset($_SESSION['email']); ?>">

            <?php
              
              if(isset($_SESSION['emailError']) && !empty($_SESSION['emailError']))
              {
                echo '<span class="help-block" id="emailError" style="color:red">'. $_SESSION['emailError'].'</span>';                  
              }   
              unset($_SESSION['emailError']);
            ?>
          </div>
    
          <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" id="pwd"  name="pwd" value="<?php if(isset($_SESSION['password']) && !empty($_SESSION['password'])){ echo $_SESSION['password']; }   unset($_SESSION['password']); ?>">
                  
              <span class="help-block" id="passwordError" style="color:red">
                <?php
                  if(isset($_SESSION['passwordError']) && !empty($_SESSION['passwordError']))
                  {
                     echo $_SESSION['passwordError'];                  
                  }   
                  unset($_SESSION['passwordError']);
                ?>
              </span>

          </div>
            

          <div class="form-group">
            <input type="checkbox" name="remember" id="checkIt" >Remember Me
          </div>  

        <span class="help-block" id="passwordError" style="color:red">
                <?php
                  if(isset($_SESSION['loginError']) && !empty($_SESSION['loginError']))
                  {
                     echo $_SESSION['loginError'];                  
                  }   
                  unset($_SESSION['loginError']);
                ?>
              </span>
          
        </fieldset>
        
        <button type="submit">LogIn</button>
        
         <div>
             <a class="navbar-brand" href="SignUp.php">Signup ?</a>
        </div>


      </form>
    

    </body>


<script>



var currentBorder=$("#email").css('border');


<?php
  
  if(isset($_COOKIE['rememberMe']))
  {
    $info =  unserialize($_COOKIE['rememberMe']);
    $email = json_encode($info[0]);
    $password = json_encode($info[1]);
    
    echo "fillCredentials($email,$password);";
  }  

?>

function fillCredentials(email,password)
{
  $("#email").val(email);
  $("#pwd").val(password);
  $("#checkIt").attr('checked', true);  

}


$(document).ready(function(){

  $("#myForm").validate({
   
    errorClass: "my-error-class",
    
   rules: 
   { 
      
      email: 
      { 
         required: true, 
         email: true 
      }, 
      pwd: 
      { 
         required:true 
      }
  },
  
    messages: 
    {
      
      email: 
      {
        required: "Please enter email address",
        email: "Your email address must be in the format of name@domain.com"
      },
      pwd: 
      { 
        required: "Password is required"
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
      form.submit(); 
      
    }



  

    });


  $("#email").focusin(function () {
    
     $("#emailError").fadeOut(1000);
  });
   
  $("#pwd").focusin(function () {
    
     $("#passwordError").fadeOut(1000);
  });

  $("#email").focusout(function () {
    
     if($("#email").val()=="")
        $("#emailError").fadeIn();
  
  });

  $("#pwd").focusout(function () {
    
     if($("#pwd").val()=="")
        $("#passwordError").fadeIn();
  
  });


});
</script>

</html>