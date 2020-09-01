<?php
include ('employerProtection.php');
include ('employerDashBoard2Controller.php');

?>     





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <title>your jobs</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.0.min.js"></script>
  <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
  <script src="http://malsup.github.com/jquery.form.js"></script> 




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
        Your Jobs
      </div>

      <div class="panel-body">

        <div class="row">

          <div class="table-responsive">          
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Job Title</th>
                  <th>Salary</th>

                  <th>See Applicants</th>
                  <th>See Details</th>

                </tr>
              </thead>
              <tbody id="jobTable">

              </tbody>
            </table>

            
              <div class="col-md-1 col-sm-1 col-xs-1 col-lg-1">
                
                <button onclick="searchRecords()" class="btn btn-md">Search</button>
              
              </div>


              <div class="col-md-2 col-sm-2 col-xs-2 col-lg-2">

                <input  class="form-control" id="searchTitle" name="Title" placeholder="Job Title"> 
                
              </div>
              
            
          


        </div>


      </div>

    </div>

    </div>
    </div>
  
    <!--   -->
        <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Job Details</h4>
          </div>
          <div class="modal-body">

            <form id="myForm" action="jobAjaxSubmit.php" method="post" >

              <div class="form-group">
                <label for="job">Job Title:</label> 
                <input class="form-control"  id="jjob" name="jobb" disabled>
                <span class="help-block" id="jobError"></span>
              </div>

              <div class="form-group ">
                <label for="salary">Salary:</label> 
                <input class="form-control"  id="ssalary" name="salaryy" disabled>                
                <span class="help-block" id="salaryError"></span>
              </div>

              <div class="form-group">

                <label for="comment">Job Description:</label>
                <textarea class="form-control" name="jobDescription" rows="5" id="jobbDescription" disabled></textarea>

              </div>

              <div class="form-group ">
                <label for="type">Job Type:</label> 
                <input class="form-control"  id="jobType" name="jobTypee" disabled>                

              </div>


              <span class="help-block" id="successMessage"></span>


            </form>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

	<!--   -->
    	 <div class="modal fade" id="myModal2" role="dialog">
      		
      		<div class="modal-dialog">

        <!-- Modal content-->
        	<div class="modal-content">
          		<div class="modal-header">
            		<button type="button" class="close" data-dismiss="modal">&times;</button>
            		<h4 class="modal-title">Perform Opertion</h4>
          		</div>
          		<div class="modal-body">

           		 	<form id="myFormModal2" action="performStatus.php" method="post">

              			<div class="form-group">
                			 <input class="form-control"  id="personId" name="personIdName" type="hidden">
                		</div>

              			<div class="form-group ">
                			<input class="form-control"  id="jobId" name="jobIdName" type="hidden">                
                		</div>
						
						<div class="form-group">
							
							<label for="Type">Select Status:</label>
							<select name="statusSelectionName"  id="statusSelectionId">
							
							</select>
						
						</div>
						     
						  <button type="button" onclick="submitStatus()" class="btn btn-default" >Done</button>           		
            
              <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
            
          </form>

          		</div>
          
          <div class="modal-footer">
           <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
          </div>
       
        </div>

      </div>
    
    </div>


    <!--   -->
    <div class="modal fade" id="myModal3" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Applicant details</h4>
          </div>
          <div class="modal-body">

            <div id="applicantDivId" style="display: none">

                <img id="applicantImageId" class="img-responsive"  alt="Chania" width="70" height="55">
            
            </div>  
            
            <br>

            <form id="myFormModal3" action="" method="" >

              <div class="form-group">
                <label for="job">Name:</label> 
                <input class="form-control"  id="nameId" name="nameModal" disabled>
                <span class="help-block" id="jobError"></span>
              </div>

              <div class="form-group ">
                <label for="salary">Email:</label> 
                <input class="form-control"  id="emailId" name="emailNameModal" disabled>                
                <span class="help-block" id="salaryError"></span>
              </div>
              
              <div class="form-group">

                <label for="comment">Applicant Job Letter:</label>
                <textarea class="form-control" name="jobLetterName" rows="5" id="jobLetter1" disabled></textarea>

              </div>

              <div id="hasCv" style="display: none;">

                <div class="form-group">

                 <!-- <label for="comment">Applicant CV:</label> -->
                  <input class="form-control" name="cvDownloadName" type="hidden">

                </div>  

              

                <input type="hidden" class="form-control" name="cvCustomDownloadName"  id="cvCustomDownload">
                <button type="button" onclick="downloadCv()" class="btn btn-default">Download CV</button>   
                <a id="cvDownload" style="pointer-events: none; cursor: default; color: grey;"></a>  
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>

             </div>
                
            </form>

          </div>
          <div class="modal-footer">
           <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
          </div>
        </div>

      </div>
    </div>

    <!-------------------->

   <div class="container">
    
    <div class="panel panel-default" style="display:none" id="showThis">

      <div class="panel-heading">
        Applicants for this job
      </div>
      
      <!-- 
        <br>
        <div class="col-md-2 col-sm-2 col-xs-2 col-lg-2">

           

            
            <input  class="form-control" id="applicantJobTitle" name="applicantJobTitleName" disabled> 
                
          
                
        </div>

        <div class="col-md-2 col-sm-2 col-xs-2 col-lg-2">

           

            <label for="job">Job Title:</label> 
            <input  class="form-control" id="applicantJobSalary" name="applicantJobSalaryName" disabled> 
            
        </div>
      
        -->
    
        
      <div class="panel-body">


        <div class="row">

          <div class="table-responsive">          
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Perform Operation</th>
                  <th>Applicant details</th>
                  <th>Status</th>


                </tr>
              </thead>
              <tbody id="applicantTable">

              </tbody>
            </table>

          </div>


        </div>


      </div>

    </div>




  </div>


</body>


<script>

  var jobid;

  <?php

   echo "makeActive($active);";
    $offerdJobs=$rows;
    $rowCount=$i;
    $records=json_encode($offerdJobs);
    if(isset($_SESSION['image']) && !empty($_SESSION['image']))
    { 
      $image = json_encode($_SESSION['image']);
      echo "printImage($image);";
    }
    echo "printtable($records,$rowCount);";

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

  function printtable(rows,count)
  {
    var body="";
    for(var i=0 ; i< count ; i++)
    {
      body=body+'<tr id="'+rows[i][0]+'"'+'><td>'+(i+1)+'</td><td>'+rows[i][1]+'</td><td>'+rows[i][2]+'</td><td><button id="'+rows[i][0]+'"'+' class="btn btn-xs btn-primary" onclick="seeApplicants(this)">Applicants</button><td><button id="'+rows[i][0]+'"'+' class="btn btn-xs btn-danger" onclick="details(this)">Details</button></td></tr>';
    }
    $("#jobTable").html(body);
  } 

  function seeApplicants(obj)
  {

    var myUrl="jobApplicants.php?id="+obj.id;
    jobid=obj.id;


    $.ajax({

      url: myUrl,
      type: 'get',
      dataType:'json',  
      success: function(response) {

          printApplicants(response);
  
      },
      error:function(xhr,textStatus,thrownError){

        alert("fail");

      } 

      });

  }

  function printApplicants(response)
  {
    var body="";
    for(var i=0 ; i < response.count ; i++)
    {
      body=body+'<tr id="'+response.records[i][2]+'row"'+' ><td>'+(i+1)+'</td><td>'+response.records[i][0]+'</td><td>'+response.records[i][1]+'</td><td id="'+response.records[i][2]+'col"'+' >';
      
        body=body+'<button id="'+response.records[i][2]+'"'+' class="btn btn-xs btn-primary" onclick="performStatus(this)" >Operation</button>';
     
      body=body+'</td><td><button id="'+response.records[i][2]+'"'+' class="btn btn-xs btn-danger" onclick="seeApplicantDetails(this)" >Details</button></td>';

      if(response.records[i][3] == 1)	
      	body=body+'<td id="'+response.records[i][2]+'status"'+' >seen</td></tr>';

      if(response.records[i][3] == 2)	
      	body=body+'<td id="'+response.records[i][2]+'status"'+' >hold</td></tr>';

      if(response.records[i][3] == 3)	
      	body=body+'<td id="'+response.records[i][2]+'status"'+' >shortlisted</td></tr>';
    
	}

    if(response.count>0)
    {
      $("#applicantTable").html(body);
      $("#showThis").css({"display":"block"});
    }
    else
    {
      $("#applicantTable").html(body);
      $("#showThis").css({"display":"none"});
    }

  }

  function test()
  {
    alert("yello");   // this is just a test function to jude below code
  }

  function performStatus(obj)
  {
  	var body="";
    $("#personId").val(obj.id);
  	$("#jobId").val(jobid);
  	if($("#"+obj.id+"status").text() == "seen")
  	{
  		body=body+'<option value="5">reject</option><option value="2">hold</option><option value="3">shortlist</option>';
  	}
  	if($("#"+obj.id+"status").text() == "hold")
  	{
  		body=body+'<option value="2">hold</option><option value="5">reject</option><option value="3">shortlist</option>';
  	}
  	if($("#"+obj.id+"status").text() == "shortlisted")
  	{
  		body=body+'<option value="4">unShort</option><option value="2">hold</option><option value="5">reject</option>';
  	}
  	$("#statusSelectionId").html(body);
  	$("#myModal2").modal('show');
  }

  function submitStatus()
  {

    $.ajax({

      url: "performStatus.php",
      type: "post",
      data: $("#myFormModal2").serialize(),
      dataType:"json",

      success: function(response) {

        if(response.tip == 1)
        {
          var status = response.status;

          if(status == 1)
          {
          	$("#"+$("#personId").val()+"status").text("seen");
          }
          if(status == 2)
          {
          	$("#"+$("#personId").val()+"status").text("hold");	
          }
          if(status == 3)
          {
          	$("#"+$("#personId").val()+"status").text("shortlisted");
          }

          $("#myModal2").modal('hide');
        }
        else
        {
        	$("#myModal2").modal('hide');
          	$("#"+$("#personId").val()+"row").remove(); 
        }

      },
      error:function(xhr,textStatus,thrownError){

        alert("fail");

      } 

    });
  }


  function downloadCv()
  {  
    var cid = $("#cvCustomDownload").val();
    var myUrl="downloadCv.php?id="+cid;
      window.location.href=myUrl;
  }

  function printApplicantImage(image)
  {
      if(image.length != 0)
      {
          $("#applicantImageId").attr("src","images/"+image);
          $("#applicantDivId").css({"display":"block"});
      }
      else
      {
          $("#applicantDivId").css({"display":"none"});
      }
  }


  function seeApplicantDetails(obj)
  {
    var myUrl="applicantDetail.php?pId="+obj.id+"&jId="+jobid;
    

    $.ajax({

      url: myUrl,
      type: "get",
      dataType:"json",
      success: function(response) {

        if(response.tip == 1)
        {	
        	printApplicantImage(response.image); 
          $("#nameId").val(response.details[0]);
        	$("#emailId").val(response.details[1]);
        	$("#jobLetter1").val(response.details[2]);
        	if(response.cv[0] != 0)
        	{ 
          		$("#cvDownload").text(response.cv[2]);
          		$("#cvCustomDownload").val(response.cv[1]);
          		$("#hasCv").css({"display":"block"});
        
        	}
        	else
        	{
          		$("#hasCv").css({"display":"none"});  
        	}
        	
        	$("#myModal3").modal("show");    
        }
        else
        {
        	$("#"+obj.id+"row").remove();
        }  
              
      },
      error:function(xhr,textStatus,thrownError){

        alert("fail");

      } 

    });
  }

  function details(obj)
  {

    var myUrl="jobAjaxDetails.php?id="+obj.id;



    $.ajax({

      url: myUrl,
      type: "get",
      dataType:"json",
      success: function(response) {

        printJobDetails(response);

      },
      error:function(xhr,textStatus,thrownError){

        alert("fail");

      } 

    });

  }

  function printJobDetails(response)
  {
    $("#jjob").val(response.details[0]);
    $("#ssalary").val(response.details[1]);
    $("#jobbDescription").val(response.details[2]);
    $("#jobType").val(response.details[3]);
    $("#myModal").modal("show");
  }

  function searchRecords()
  {
    
      var title=$("#searchTitle").val();
      var myUrl="jobAjaxSearch.php?id="+title;



    $.ajax({

      url: myUrl,
      type: "get",
      dataType:"json",
      success: function(response) {

        printtable(response.rows,response.count);

      },
      error:function(xhr,textStatus,thrownError){

        alert("fail");

      } 

    });

    $("#showThis").css({"display":"none"});
  }


</script>



</html>
