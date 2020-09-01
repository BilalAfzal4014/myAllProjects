<?php

include ('employeeProtection.php');
include ('employeeDashboard3Controller.php');

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <title>Cv Manager</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.0.min.js"></script>
  <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
  <script src="http://malsup.github.com/jquery.form.js"></script> 
  <script src="jslibs/jquery.js" type="text/javascript"></script>
  <script src="jslibs/ajaxupload-min.js" type="text/javascript"></script>

  <!-- below written link is for extension validation library for validate method-->
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
        Upload CV(s)
      </div>

      <div class="panel-body">

        <div class="row">

          <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" style="margin-left:23%; margin-top:2%;">

            <form id="myForm" action="employeeUploadCv.php" method="post" enctype="multipart/form-data">


             
                
            <div id="append">

                <div class="form-group">
                
                    <label for="name">CV 1:</label> 
                    <input  class="form-control" type="text" id="title0" name="titleName0" placeholder="Title of Your Cv">
                    <br>
                    <input  class="form-control myClass" type="file" id="name0" name="cv0" >
                    <span class="help-block" id="nameError"></span>
                </div>


             </div>   
              
               <a id="addExperience" class="pull-right" style="cursor:pointer" >Add More CV</a>
  <!--              
<a id="addExperience" class="pull-right" style="pointer-events: none; cursor: default; color: grey;" >Add More CV</a>
 -->

              <span class="help-block" id="successMessage"></span>

              <button id="btn" type="submit" class="btn btn-lg btn-success">Upload</button>

            </form>

          </div>

        </div>



      </div>

    </div>





  </div>



<!-------------------------->


<div class="container">

    <div class="panel panel-default">

      <div class="panel-heading">
        View CV(s)
      </div>

      <div class="panel-body">

        <div class="row">

          <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" style="margin-left:23%; margin-top:2%;">

            <form>

            <div id="append2">

            <!--
                <div id="bLhJ7wFyq.txt_big" class="form-group" onclick="removeBig(this)">

                  <div><a style="color: brown; pointer-events: none; cursor: default;">q.txt</a></div>
                  
                    <div class="pull-right">
                    
                      <div id="bLhJ7wFyq.txt_download" onclick="download(this)" >
                        <a style="cursor:pointer">Download</a>
                      </div>
                    
                      <div id="bLhJ7wFyq.txt_delete" onclick="delete1(this)" >
                        <a style="cursor:pointer; color: red;">delete</a>
                      </div>
                    
                      <div id="we.txt" onclick="default1(this)">
                          <a style="cursor:pointer; color: green;">Make Default</a>
                      </div>
        
                      <!--        
                      <div id="'+cvs[i][0]+'_default"'+' onclick="default1(this)">
                      <a style="pointer-events: none; cursor: default; color: grey;">Make Default</a>
                      </div>';
                      -->

                    </div>
                    <br><br><br>
                </div>
      
              
            </div>   
              

            </form>

          </div>

        </div>



      </div>

    </div>





  </div>






</body>

<script>
var currentDefault = -1;
var educationId=0;
var currentBorder=$("#name0").css('border');
var oneFile=false;
var totalFilesCheck=0;
var idArray = [];

<?php
    
    echo "makeActive($active);";
    if(isset($_SESSION['image']) && !empty($_SESSION['image']))
    { 
        $image = json_encode($_SESSION['image']);
        echo "printImage($image);";
    }

    $cvs = json_encode($rows);
    $count = $i;

    echo "printCvs($cvs,$count);";

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
  

  function printCvs(cvs,count)
  {
    var body="";

    for(var i = 0; i < count; i++)
    {
      body=body+'<div id="'+cvs[i][0]+'_big"'+' class="form-group" onclick="removeBig(this)"><div><a style="color: brown; pointer-events: none; cursor: default;">'+cvs[i][3]+'</a></div><div class="pull-right"><div id="'+cvs[i][0]+'_download"'+' onclick="download(this)" ><a style="cursor:pointer">Download</a></div><div id="'+cvs[i][0]+'_delete"'+' onclick="delete1(this)" ><a style="cursor:pointer; color: red;">Delete</a></div>';
                    
      if(cvs[i][2]==0)
      {
        body=body+'<div id="'+cvs[i][0]+'_default"'+' ><a id="'+cvs[i][0]+'_default_d"'+' onclick="default1(this)" style="cursor:pointer; color: green;" >Make Default</a></div>';
                
      }            
      else
      {
        currentDefault = cvs[i][0];
        body=body+'<div id="'+cvs[i][0]+'_default"'+'><a id="'+cvs[i][0]+'_default_d"'+' onclick="default1(this)" style="pointer-events: none; cursor: default; color: grey;" >Default</a></div>';
      } 

      body=body+'</div><br><br><br></div>';
    }
    $("#append2").append(body);
   
  }

  function download(obj)
  {
    var v = obj.id.split("_");
    var cid = v[0];
    
    var myUrl="downloadCv.php?id="+cid;
    window.location.href=myUrl;
  
    // see how to download file through ajax
  }

  function delete1(obj)
  {
    var v = obj.id.split("_");
    var cid = v[0];
    var myUrl="deleteCv.php?id="+cid;

     $.ajax({
                            
        url: myUrl,
        type: "get",
        dataType:"json",
        
        success: function(response) {
            
          if(response==1)
          {
            currentDefault = -1;
          }
          cid = cid+"_big";
          $('div[id="'+cid+'"'+']').remove();
        },
        error:function(xhr,textStatus,thrownError){

            alert("fail");
                                    
        } 
                            

      });
  }

  function default1(obj)
  {
      
     var v = obj.id.split("_");
     var cid = v[0];
     obj.id = v[0]+"_default";
     
    // alert(obj.id);
     

     if(currentDefault != -1)
     {  
        v = currentDefault.split("_");
        var cid2 = v[0];
        currentDefault = v[0]+"_default";
     }
     else
     {
        cid2 = currentDefault;
     }

     var myUrl="makeDefaultCv.php?id="+cid+"&id2="+cid2;

     $.ajax({
                            
        url: myUrl,
        type: "get",
        dataType:"json",
        
        success: function(response) {
            


            $('div[id="'+obj.id+'"'+']').html('<a id="'+obj.id+'_d"'+' onclick="default1(this)" style="pointer-events: none; cursor: default; color: grey;" >Default</a>');
            
            if(currentDefault != -1)
            { //alert("here");
              $('div[id="'+currentDefault+'"'+']').html('<a id="'+currentDefault+'_d"'+' onclick="default1(this)" style="cursor:pointer; color: green;" >Make Default</a>');
            }                              
            currentDefault=obj.id;                         
        },
        error:function(xhr,textStatus,thrownError){

            alert("fail");
                                    
        } 
                            

      });
      

      //$("div[id='we.txt']").html('<a style="pointer-events: none; cursor: default; color: grey;">Make Default</a>');
      //$("#we\\.txt").html('<a style="pointer-events: none; cursor: default; color: grey;">Make Default</a>');
      // above jquery id selector is written in order to avoid dot because jquery doesn handle dot in its selector
  }


  


  $(document).ready(function(){

    $("#myForm").validate({

      errorClass: "my-error-class",
      
     
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
      
      for(var i=0; i <= educationId; i++)
      {
        
        $("#name"+i).attr('name','cv[]');
        $("#title"+i).attr('name','title[]');      
      }
      
      
      $.ajax({
                            
        url: form.action,
        type: form.method,
        dataType:'json',
        data: new FormData($("#myForm")[0]),
        processData: false,
        contentType: false,

        success: function(response) {
                                
            $("#successMessage").text("Files has been uploaded").css("color", "green").fadeIn().delay(2000).fadeOut();               
             
            for(var i=0; i <= educationId; i++)
            {
        
              $("#name"+i).val("");
              $("#title"+i).val("");
            }
            $("#append2").html("");
            printCvs(response.cvs,response.count);                       
        },
        error:function(xhr,textStatus,thrownError){

            alert("fail");
                                    
        } 
                            

      });
    
    }

    /*
    invalidHandler : function(form)
    {
      alert("hello");
      // if i wanted to make it functional put comma just after submithandler closing bracket

    }*/


  });


function splitValue(value, index) 
{
    return value.substring(0, index) + "," + value.substring(index);
}

$("#btn").click(function (e) {
    

    for(var i=0; i <= educationId; i++)
    {
      $("#name"+i).rules('add', {
            
          required: function(e){

          	var currentIdForRule = $(e).attr('id');
            var v = splitValue(currentIdForRule, 4);
            var vj = v.split(",");
            
            var value = $("#title"+vj[1]).val();
            
            if(oneFile == false || value.length != 0)
            	return true;
             return false;
          },
          
          extension: "doc|pdf|txt",
              
          messages: 
          {
                
            required: function(){

            	if(oneFile == false )	
            		return "At least upload one CV in any of these";
            	return "Upload Your CV for which u have mentioned the title";
        	},
            
            extension: "file should only be document or pdf file"
          
          }
        
      });
      /////////////////////////////////
     
     var titleId="#title"+i;
     $(titleId).rules('add', {
            
          required: function(e){

              var currentIdForRule = $(e).attr('id');
              var v = splitValue(currentIdForRule, 5);
              var vj = v.split(",");
              var idToFind="name"+vj[1];
              var index = idArray.indexOf(idToFind);
              if(oneFile == false || index > -1)
              {
                return true;
              }
              return false;   
          },    
          messages: 
          {
                
            required: function(){

              if(oneFile == false)
                return "Please enter title";
               return "Please enter title of below cv";
            }
          
          }
        
      });
    }
   
});



 /*  
$(".myClass").on('change' ,function() {
         ///// Your code
       alert("hello");        // i have commented this event and using below event bcz field were dynamically entered

        oneFile=true;
        
  });
*/

$(document).on('change', '.myClass', function() {

      var fileName = $(this).val();
      if(fileName.length != 0 )
      {   
        totalFilesCheck++;
        idArray.push($(this).attr('id'));
        //console.log(idArray);
        // alert($(this).attr('id')); push id into array
      }
      else
      {  
        totalFilesCheck--;
        var index = idArray.indexOf($(this).attr('id'));
        if (index > -1) 
        {
          idArray.splice(index, 1);
        }
        //console.log(idArray);
        // pop id out of array
      }
      
      if(totalFilesCheck >= 1)
        oneFile=true;
      else
        oneFile=false;
  });


  $("#addExperience").click(function(){

    educationId++;
    var inputStr='<div class="form-group"><label for="name">CV '+(educationId+1)+':</label><input  class="form-control" type="text" id="title'+educationId+'"'+' name="titleName'+educationId+'"'+' placeholder="Title of Your Cv"><br><input  class="form-control myClass" type="file"  id="name'+educationId+'" name="cv'+educationId+'" ><span class="help-block" id="nameError"></span></div>';
     $("#append").append(inputStr);
   // alert(inputStr);
  
  });













  });







</script>



</html>

