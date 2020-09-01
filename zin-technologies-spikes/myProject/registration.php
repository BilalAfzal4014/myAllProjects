<!DOCTYPE html>
<html lang="en">
<head>
  <title>Registration Page</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container" style="margin-top:10%;">
  <!--/action_page.php-->
<h3>Registration Form</h3> <br>

  <form action="regController.php" method="post">
    
    <div class="form-group">
      <label for="name">Name:</label>
      <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name">
    </div>

    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
    </div>
    
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd">
    </div>
    
    <div class="form-group">
      <label for="pwd">Confirm Password:</label>
      <input type="password" class="form-control" id="pwd1" placeholder="Enter above password" name="pwd1">
    </div>

    <div class="form-group">
      <label for="Education">Education:</label>
      <input type="text" class="form-control" id="education1" placeholder="Enter your Education" name="education">
      
      <div id="educationAppend">
        

      </div>

      <br>
      <a id="addEduction" style="margin-left:88%">Add More Education</a>
    </div>


    <div class="form-group">
      <label for="Experience">Experience:</label>
      <input type="text" class="form-control" id="experience1" placeholder="Enter your Experience" name="experience">
     
      <div id="experienceAppend">
        

      </div>

      <br>
      <a id="addExperience" style="margin-left:87%">Add More Experience</a>
    </div>

    <!--
    <div class="form-group">
      <label for="email">Upload CV:</label>
      <input type="file" class="form-control" id="file" placeholder="Enter email" name="file">
    </div>
	-->


    <!--
    <div class="checkbox">
      <label><input type="checkbox" name="remember"> Remember me</label>
    </div>
    -->

    <button type="submit" class="btn btn-primary">Submit</button>
  </form>



    
    



</body>



<script>

var educationId=1;
var experienceId=1;
$(document).ready(function(){

  $("#addEduction").click(function(){

    educationId++;
    var inputStr='<br><input type="text" class="form-control" id="education'+educationId+'"'+' placeholder="Enter your Education" name="education">';
    $("#educationAppend").append(inputStr);

  });

  $("#addExperience").click(function(){

    experienceId++;
    var inputStr='<br><input type="text" class="form-control" id="education'+experienceId+'"'+' placeholder="Enter your Experience" name="experience">';
    $("#experienceAppend").append(inputStr);
  });

});


</script>















</html>
