<?php

	include ('employeeProtection.php');
	include ('employeeDashboard1Controller.php');
	

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

<style>

.my-error-class {
    color:red;
}

</style>

</head>
 
<body>
   
	<?php	include ('employeeHeader.php'); ?>

	 <div class="container" id="imageDiv" style="display: none"> 
 
    	<img id="image" class="img-responsive" src="" alt="Chania" width="70" height="55"> 
    	<br>
  
  	</div>


	<div class="container">

		<div class="panel panel-default">

			<div class="panel-heading">
				Apply for jobs

				<a class="pull-right" style="cursor:pointer; color: grey;" onclick="seeLikedJobs()">See Liked Jobs</a>			
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
									<th>Company Name</th>
									<th>Details</th>
									<th>Add To Favourites</th>

								</tr>
							</thead>
							<tbody id="applyJobTable">

							</tbody>
						</table>

						
						<div class="col-md-1 col-sm-1 col-xs-1 col-lg-1">
                
                			<button onclick="beforeSearchJobs()" class="btn btn-md">Search</button>
              
              			</div>


              			<div class="col-md-2 col-sm-2 col-xs-2 col-lg-2">

                			<input  class="form-control" id="searchTitle" name="Title" placeholder="Job Title"> 
                
              			</div>

              			<input type="hidden"  class="form-control" id="searchTitle1" name="Title" placeholder="Job Title">	

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
						
						<div id="beforeApplyDivId" style="display: none">

							<img id="beforeApplyImageId" class="img-responsive"  alt="Chania" width="70" height="55">
						
						</div>	
						
						<br>
						<form id="myFormModal1" action="" method="" >

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


						</form>


							
						<form id="myFormModal2" action="employeeDashBoard1ControllerAjaxApply.php" method="post" >

							<div class="form-group"> 
								<input class="form-control"  id="hidden" name="hiddenName" type="hidden">
							</div>

							<div class="form-group">

								<label for="comment">Job Letter:</label>
								<textarea class="form-control" name="jobLetterName" rows="5" id="jobLetter"></textarea>

							</div>

							<div class="form-group">

              					<label for="Type">Select CV:</label>

              					<select name="cvSelectionName"  id="cvSelectionId">

                					

              					</select>

            				</div>
								

            				<span class="help-block" id="successMessage"></span>
								
							<button type="submit" class="btn btn-default">Apply</button>

							<button  type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>

						</form>


					</div>
					
					<div class="modal-footer">
					<!--	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					
					--></div>
				
				</div>

			</div>
		
		</div>

		<!-- -->


		<!--   -->

		<div class="modal fade" id="myModalLikedJobs" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Your Liked Jobs</h4>
					</div>
					
					<div class="modal-body">
						
						<div class="row">

							<div class="table-responsive">          
						
								<table class="table table-striped table-bordered table-hover">
									
									<thead>
									
										<tr>
									
											<th>#</th>
											<th>Job Title</th>
											<th>Salary</th>
											<th>Company Name</th>

										</tr>
									
									</thead>
							
									<tbody id="likedJobTable">

									</tbody>
						
								</table>

							</div>

						</div>


					</div>
					
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
						<h4 class="modal-title">Applied job details</h4>
					</div>
					<div class="modal-body">

						<div id="afterApplyDivId" style="display: none">

							<img id="afterApplyImageId" class="img-responsive"  alt="Chania" width="70" height="55">
						
						</div>	
						
						<br>

						<form id="myFormModal3" action="" method="" >

							<div class="form-group">
								<label for="job">Job Title:</label> 
								<input class="form-control"  id="jjob1" name="jobb" disabled>
								<span class="help-block" id="jobError"></span>
							</div>

							<div class="form-group ">
								<label for="salary">Salary:</label> 
								<input class="form-control"  id="ssalary1" name="salaryy" disabled>                
								<span class="help-block" id="salaryError"></span>
							</div>

							<div class="form-group">

								<label for="comment">Job Description:</label>
								<textarea class="form-control" name="jobDescription" rows="5" id="jobbDescription1" disabled></textarea>

							</div>

							<div class="form-group ">
								<label for="type">Job Type:</label> 
								<input class="form-control"  id="jobType1" name="jobTypee" disabled>                

							</div>

							<div class="form-group">

								<label for="comment">Your Job Letter:</label>
								<textarea class="form-control" name="jobLetterName" rows="5" id="jobLetter1" disabled></textarea>

							</div>

							<div id="hasCv" style="display: none;">	
								
								<div class="form-group">

								<!--	<label for="comment" type="hidden">Your CV:</label>-->
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
						<!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
					</div>
				</div>

			</div>
		</div>

		<!-- -->











		<br><br>


		<div class="panel panel-default">

			<div class="panel-heading">
				Applied jobs
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
									<th>Company Name</th>
									<th>cancel Application</th>
									<th>Details</th>
									<th>Status</th>

								</tr>
							</thead>
							<tbody id="appliedJobTable">

							</tbody>
						</table>

					</div>


				</div>


			</div>

		</div>


	</div>


</body>


<script>

	var currentBorder=$("#jobLetter").css('border');
	
	<?php

		echo "makeActive($active);";
		if(isset($_SESSION['image']) && !empty($_SESSION['image']))
    	{ 
        	$image = json_encode($_SESSION['image']);
        	echo "printImage($image);";
    	}

		$jobs=$rows;
		$rowCount=$i;
		$records=json_encode($jobs);
		echo "printjobs($records,$rowCount);";

		$r=json_encode($rows2);

		echo "printAppliedJobs($r,$i2);";        
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


		$("#myFormModal2").validate({

			errorClass: "my-error-class",

			rules: 
			{ 

				jobLetterName: 
				{ 
					required: true
				},
				
				cvSelectionName:
				{
					required: true
				}
				
			},

			messages: 
			{

				jobLetterName: 
				{
					required: "Please enter Something about you for this job"
				},

				cvSelectionName:
				{
					required: "Please upload your CV before Apply"
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
					data: $("#myFormModal2").serialize(),
					dataType:'json',
					success: function(response) {

						$("#successMessage").text("You have applied for this job").css("color", "green").fadeIn().delay(2000).fadeOut();
						
						// although the type is submit but call is ajax so we have to close modals by ourself, type submit hoti aur ajax call na hoti tou modal ny khud ba khud band hojana tha, type button sy modal band nahe hota hai
						setTimeout(function() {$('#myModal').modal('hide');}, 2000);
						setTimeout(function() {$("#jobLetter").val("");}, 2000);
						

						var title = $("#searchTitle1").val();
						
						if(title.length==0)
							printjobs(response.jobs,response.count);
						else
							searchJobs(title);

						printAppliedJobs(response.hasApplied,response.hasCount);

					},
					error:function(xhr,textStatus,thrownError){


					} 

				});
			}





		});





	});

	function downloadCv()
	{	 
		var cid = $("#cvCustomDownload").val();
		var myUrl="downloadCv.php?id="+cid;
    	window.location.href=myUrl;
	}

	function printjobs(records,count)
	{
		var body="";
		for(var i=0 ; i< count ; i++)
		{

			body=body+'<tr><td>'+(i+1)+'</td><td>'+records[i][1]+'</td><td>'+records[i][2]+'</td><td>'+records[i][3]+'</td><td><button id="'+records[i][0]+'"'+' class="btn btn-xs btn-danger" onclick="details(this)">Details</button></td>';
			if(records[i][4] == null)
			{ 
				body=body+'<td id="'+records[i][0]+'fav"'+'><button id="'+records[i][0]+'"'+' class="btn btn-xs btn-primary" onclick="addToFav(this)">Like</button></td>';
			}
			else
			{
				body=body+'<td id="'+records[i][0]+'fav"'+'><button id="'+records[i][0]+'"'+' class="btn btn-xs btn-success" onclick="notAddToFav(this)">Unlike</button></td>';
			}	
			body=body+'</tr>';
		}

		$("#applyJobTable").html(body);

	}

	function printAppliedJobs(records,count)
	{
		var body="";
		for(var i=0 ; i< count ; i++)
		{

			body=body+'<tr id="'+records[i][0]+'"'+'><td>'+(i+1)+'</td><td>'+records[i][1]+'</td><td>'+records[i][2]+'</td><td>'+records[i][3]+'</td><td id="'+records[i][0]+'button"'+'>';
			
			if(records[i][4] != 3)
				body=body+'<button id="'+records[i][0]+'"'+' class="btn btn-xs btn-primary" onclick="dontApply(this)">cancel</button></td><td>';
			else
				body=body+'<button id="'+records[i][0]+'"'+' class="btn btn-xs btn-primary" onclick="dontApply(this)" disabled>cancel</button></td><td>';

			body=body+'<button id="'+records[i][0]+'"'+' class="btn btn-xs btn-danger" onclick="details1(this)">Details</button></td>';
			
			if(records[i][4]==0)
				body=body+'<td id="'+records[i][0]+'status"'+'>recieved</td></tr>';
			if(records[i][4]==1)
				body=body+'<td id="'+records[i][0]+'status"'+'>seen</td></tr>';
			if(records[i][4]==2)
				body=body+'<td id="'+records[i][0]+'status"'+'>hold</td></tr>';
			if(records[i][4]==3)
				body=body+'<td id="'+records[i][0]+'status"'+'>shortlisted</td></tr>';
		}
		$("#appliedJobTable").html(body);            
	}

	function addToFav(obj)
	{
		var myUrl="insertAddToFav.php?id="+obj.id;


		$.ajax({

			url: myUrl,
			type: 'get',
			dataType:'json',
			success: function(response) {

				if(response.tip == 1)
				{	
					var body='<button id="'+obj.id+'"'+' class="btn btn-xs btn-success" onclick="notAddToFav(this)">Unlike</button>';
					$("#"+obj.id+"fav").html(body);
					
				}

			},
			error:function(xhr,textStatus,thrownError){

    			//alert("fail");

			} 

		});
	}


	function notAddToFav(obj)
	{
		
		var myUrl="deleteAddToFav.php?id="+obj.id;


		$.ajax({

			url: myUrl,
			type: 'get',
			dataType:'json',
			success: function(response) {

				if(response.tip == 1)
				{	
					var body='<button id="'+obj.id+'"'+' class="btn btn-xs btn-primary" onclick="addToFav(this)">Like</button>';
					$("#"+obj.id+"fav").html(body);
				}

			},
			error:function(xhr,textStatus,thrownError){


			} 

			});
		
		
	}


/*

function beforeApply(obj)
{
	$("#hidden").val(obj.id);
	$("#myModal2").modal("show");
}


function apply(obj)
{
	var myUrl="employeeDashBoard1ControllerAjaxApply.php?id="+obj.id;



	$.ajax({

		url: myUrl,
		type: 'get',
		dataType:'json',
		success: function(response) {


			printjobs(response.jobs,response.count);
			printAppliedJobs(response.hasApplied,response.hasCount);

		},
		error:function(xhr,textStatus,thrownError){

    //alert("fail");

		} 

	});
}
*/
function dontApply(obj)
{

	var myUrl="employeeDashBoard1ControllerAjaxCancel.php?id="+obj.id;



	$.ajax({

		url: myUrl,
		type: 'get',
		dataType:'json',
		success: function(response) {

    		if(response.tip < 0)
    		{	
    			var title = $("#searchTitle1").val();
				if(title.length == 0)
					printjobs(response.jobs,response.count);
				else
					searchJobs(title);
    			//printjobs(response.jobs,response.count);
    			printAppliedJobs(response.hasApplied,response.hasCount);
    		}
    		else
    		{	
    			var button='<button id="'+response.tip+'"'+' class="btn btn-xs btn-primary"  disabled>cancel</button>';
    			$("#"+response.tip+"button").html(button);
    			$("#"+response.tip+"status").text("shortlisted");
    		}
		},
		
		error:function(xhr,textStatus,thrownError){

			alert("fail");

		} 

	});
}




function details(obj)
{
	var myUrl="employeeDashBoard1ControllerAjaxDetails.php?id="+obj.id;

	$("#hidden").val(obj.id);

	$.ajax({

		url: myUrl,
		type: "get",
		dataType:"json",
		success: function(response) {

			printImageBeforeApply(response.image);
			printCvDetails(response.cvDetails, response.count);
			printJobDetails(response.details);
			
		},

		error:function(xhr,textStatus,thrownError){

			alert("fail");

		} 

	});

}

function printImageBeforeApply(image)
{
    if(image.length != 0)
    {
      	$("#beforeApplyImageId").attr("src","images/"+image);
      	$("#beforeApplyDivId").css({"display":"block"});
    }
    else
    {
      	$("#beforeApplyDivId").css({"display":"none"});
    }
 }

function printImageAfterApply(image)
{
    if(image.length != 0)
    {
      	$("#afterApplyImageId").attr("src","images/"+image);
      	$("#afterApplyDivId").css({"display":"block"});
    }
    else
    {
      	$("#afterApplyDivId").css({"display":"none"});
    }
 }

function printCvDetails(cv,count)
{
	var body="";
	for(var i = 0 ; i < count; i++)
	{
		body=body+'<option value="'+cv[i][0]+'"'+'>'+cv[i][2]+'</option>';
	}
	$("#cvSelectionId").html(body);
}




function details1(obj)
{
	var myUrl="employeeDashBoard1ControllerAjaxDetails1.php?id="+obj.id;



	$.ajax({

		url: myUrl,
		type: "get",
		dataType:"json",
		success: function(response) {

			if(response.tip == 1)
			{	
				printImageAfterApply(response.image);	
				$("#jjob1").val(response.details[0]);
				$("#ssalary1").val(response.details[1]);
				$("#jobbDescription1").val(response.details[2]);
				$("#jobType1").val(response.details[3]);
				$("#jobLetter1").val(response.jobLetter[0]);
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
				
				var status = response.jobLetter[1];

				var button='<button id="'+obj.id+'"'+' class="btn btn-xs btn-primary" onclick="dontApply(this)" >cancel</button>';
    				$("#"+obj.id+"button").html(button);

          		if(status == 1)
          		{
          			$("#"+obj.id+"status").text("seen");
          		}
          		if(status == 2)
          		{
          			$("#"+obj.id+"status").text("hold");	
          		}
          		if(status == 3)
         		{
          			$("#"+obj.id+"status").text("shortlisted");
          			var button='<button id="'+obj.id+'"'+' class="btn btn-xs btn-primary" onclick="dontApply(this)" disabled>cancel</button>';
    				$("#"+obj.id+"button").html(button);
          		}


			}
			else
			{
				//alert("hello");
				$("tr#"+obj.id).remove();
				printjobs(response.rows,response.count);
			}

		},

		error:function(xhr,textStatus,thrownError){

			alert("fail");

		} 

	});

}
function printJobDetails(details)
{
	$("#jobLetter").val("");
	$("#jjob").val(details[0]);
	$("#ssalary").val(details[1]);
	$("#jobbDescription").val(details[2]);
	$("#jobType").val(details[3]);
	$("#myModal").modal("show");
}

function beforeSearchJobs()
{
	var title=$("#searchTitle").val();
	$("#searchTitle1").val(title);
	searchJobs(title);
}

function searchJobs(title)
{
	
	var myUrl="employeeJobAjaxSearch.php?id="+title;

	//alert(title);
	 
	 $.ajax({

      url: myUrl,
      type: "get",
      dataType:"json",
      success: function(response) {

        	printjobs(response.records,response.count);
        

      },
      error:function(xhr,textStatus,thrownError){

        alert("fail");

      } 

    });


}


function seeLikedJobs()
{
	var myUrl="employeeLikedJobs.php";
	 
	 $.ajax({

      url: myUrl,
      type: "get",
      dataType:"json",
      success: function(response) {

        	printLikedJobs(response.jobs,response.count);
        
        	//alert("success");
      },
      error:function(xhr,textStatus,thrownError){

        //alert("fail");

      } 

    });
}			


function printLikedJobs(records,count)
{
	var body="";
	for(var i=0 ; i< count ; i++)
	{

		body=body+'<tr><td>'+(i+1)+'</td><td>'+records[i][1]+'</td><td>'+records[i][2]+'</td><td>'+records[i][3]+'</td></tr>';
	}

	$("#likedJobTable").html(body);
	$("#myModalLikedJobs").modal('show');
}


</script>



</html>
