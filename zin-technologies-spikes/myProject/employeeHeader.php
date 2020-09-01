<nav class="navbar navbar-default">

		<div class="container-fluid">

			<div class="navbar-header">

				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>                        
				</button>

				<a class="navbar-brand" >Employee</a>

			</div>

			<div class="collapse navbar-collapse" id="myNavbar">

				<ul class="nav navbar-nav">

					<li id="id1" class="active operation"><a href="employeeDashboard1.php">Home</a></li>
					<!--<li id="id4" ><a href="employeeDashboard4.php">Home</a></li>-->
				</ul>



				<ul class="nav navbar-nav navbar-right">



					<div class="navbar-header">

						<a class="navbar-brand" id="updateName" ><?php echo $_SESSION['name'][0]; ?> </a>

					</div>

					<li class="dropdown">

						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Profile<span class="caret"></span></a>

						<ul class="dropdown-menu">

							<li id="id2" class="operation" ><a href="employeeDashboard2.php">Edit</a></li>
							<li id="id3" class="operation" ><a href="employeeDashboard3.php">CV Manager</a></li>
							<li><a href="logOut.php">Logout</a></li>

						</ul>

					</li>

				</ul>

			</div>

		</div>

	</nav>

	<script>

	
	
	function makeActive(active)
	{
		if(active == 1)
		{
			$("#id1").addClass("active");
			$("#id2").removeClass('active');
			$("#id3").removeClass('active');
		}
		if(active == 2)
		{
			$("#id2").addClass("active");
			$("#id1").removeClass('active');
			$("#id3").removeClass('active');
		}
		if(active == 3)
		{
			$("#id3").addClass("active");
			$("#id2").removeClass('active');
			$("#id1").removeClass('active');
		}
	}
	
	function updateName(name)
	{
		$("#updateName").text(name);
	}

	</script>