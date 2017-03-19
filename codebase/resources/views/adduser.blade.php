@extends('base')

@section('head')
    @parent
@stop

@section('body')
	<!-- WRAPPER -->
	<div id="wrapper">
		<!-- SIDEBAR -->
		<div class="sidebar">
			<div class="brand">
				<a href="index.html"><img src="assets/img/logo.png" alt="Klorofil Logo" class="img-responsive logo"></a>
			</div>
			<div class="sidebar-scroll">
				<nav>
					<ul class="nav">
						<li><a href="/" class=""><i class="lnr lnr-home"></i> <span>Home</span></a></li>
						<li>
							<a href="/users" class="active"><i class="lnr lnr-users"></i> <span>Users</span></a>
							<div id="subPages" class="collapse in">
								<ul class="nav">
									<li><a href="#" class="active"><i class="lnr lnr-user"></i>Add User</a></li>
								</ul>
							</div>
						</li>
						<li><a href="/books" class=""><i class="lnr lnr-book"></i> <span>Books</span></a></li>
					</ul>
				</nav>
			</div>
		</div>
		<!-- END SIDEBAR -->
		<!-- MAIN -->
		<div class="main">
			<!-- NAVBAR -->
			<nav class="navbar navbar-default">
				<div class="container-fluid">
					<div class="navbar-btn">
						<button type="button" class="btn-toggle-fullwidth"><i class="lnr lnr-arrow-left-circle"></i></button>
					</div>
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-menu">
							<span class="sr-only">Toggle Navigation</span>
							<i class="fa fa-bars icon-nav"></i>
						</button>
					</div>
				</div>
			</nav>
			<!-- END NAVBAR -->
			<!-- MAIN CONTENT -->
			<div class="main-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-7">
							<!-- RECENT PURCHASES -->
							<div class="panel">
								<div class="panel-heading">
									<h3 class="panel-title">New User Details</h3>
									<p class="panel-subtitle">All fields are mandatory</p>
								</div>
								<div class="panel-body text-center">
									<input type="text" class="form-control" id="user_name_text_box" placeholder="Enter name">
									<br>
									<input type="text" class="form-control" id="user_ssn_text_box" placeholder="Enter ssn">
									<br>
									<input type="text" class="form-control" id="user_email_text_box" placeholder="Enter email">
									<br>
									<input type="text" class="form-control" id="user_address_text_box" placeholder="Enter address">
									<br>
									<input type="text" class="form-control" id="user_city_text_box" placeholder="Enter city">
									<br>
									<input type="text" class="form-control" id="user_state_text_box" placeholder="Enter state">
									<br>
									<input type="text" class="form-control" id="user_phone_text_box" placeholder="Enter phone number">
									<br>
									<span class="input-group-btn"><button type="button" class="btn btn-primary" id="btn_add">Add</button></span>
								</div>
							</div>
							<!-- END RECENT PURCHASES -->
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- END MAIN -->
	</div>
	<!-- END WRAPPER -->
	<!-- Javascript -->
@stop

@section('jsimports')
    @parent
    <script src="assets/js/portal.js"></script>
@stop
