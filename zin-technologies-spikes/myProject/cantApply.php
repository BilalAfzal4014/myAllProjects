
<div class="container">

	<div class="panel panel-default">

		<div class="panel-heading">
			Jobs
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

</div>




<script>

	<?php

		$result = $mysqli->query("SELECT jobId,jobDescription,salary,companyName FROM user,job,company WHERE id=userCompanyId AND id=userJobId");

		$rows;
		$i=0;
		while($row = $result->fetch_array(MYSQLI_NUM))
		{
			$rows[$i] =$row;
			$i++;
		}
		$jobs=$rows;
		$rowCount=$i;
		$records=json_encode($jobs);
		echo "printjobs($records,$rowCount);";

	?>

	function printjobs(records,count)
	{
		var body="";
		for(var i=0 ; i< count ; i++)
		{

			body=body+'<tr><td>'+(i+1)+'</td><td>'+records[i][1]+'</td><td>'+records[i][2]+'</td><td>'+records[i][3]+'</td><td><button id="'+records[i][0]+'"'+' class="btn btn-xs btn-danger" onclick="details(this)">Details</button></td></tr>';
		}

		$("#applyJobTable").html(body);

	}

	function details(obj)
	{
		alert("please Login Or sign Up to see the details of this job");
	}
	
	function beforeSearchJobs()
	{
		var title=$("#searchTitle").val();
		$("#searchTitle1").val(title);
		searchJobs(title);
	}

	function searchJobs(title)
	{
	
		var myUrl="AllJobAjaxSearch.php?id="+title;
	 
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

</script>