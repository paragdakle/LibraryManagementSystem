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
				<a href="/">Open Library</a>
			</div>
			<div class="sidebar-scroll">
				<nav>
					<ul class="nav">
						<li><a href="/"><i class="lnr lnr-home"></i> <span>Home</span></a></li>
						<li><a href="/borrowers" class="active"><i class="lnr lnr-users"></i> <span>Users</span></a></li>
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
					<div id="navbar-menu" class="navbar-collapse collapse">
						<form class="navbar-form navbar-right hidden-xs">
							<div class="input-group">
                                <span class="input-group-btn"><button type="button" class="btn btn-success" id="btn_add_new_user">Add New User</button></span>
							</div>
						</form>
					</div>
				</div>
			</nav>
			<!-- END NAVBAR -->
			<!-- MAIN CONTENT -->
			<div class="main-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<!-- RECENT PURCHASES -->
							<div class="panel">
								<div class="panel-heading">
									<h3 class="panel-title">Users</h3>
								</div>
								<div class="panel-body no-padding">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>Card No.</th>
												<th>SSN</th>
												<th>Name</th>
												<th>Email</th>
												<th>Address</th>
												<th>City</th>
												<th>State</th>
												<th>Phone</th>
											</tr>
										</thead>
										<tbody>
											@foreach($results as $result)
											<tr>
												<td><a href="{{ '/borrower?card_id=' . $result['card_id'] }}">{{ $result['card_id'] }}</a></td>
												<td>{{ $result['ssn'] }}</td>
												<td>{{ $result['bname'] }}</td>
												<td>{{ $result['email'] }}</td>
												<td>{{ $result['address'] }}</td>
												<td>{{ $result['city'] }}</td>
												<td>{{ $result['state'] }}</td>
												<td>{{ $result['phone'] }}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								<div class="panel-footer">
									<div class="row">
										<div class="col-md-6"><span class="panel-note">Showing {{ count($results)}} users.</span></div>
										<div class="col-md-6 text-right">
											@if ($page_number > 1)
											<a href="{{ '/users?page=' . ($page_number - 1)}}" class="btn btn-primary">Prev Page</a>
											@endif
											@if(count($results) > 0)
											<a href="{{ '/users?page=' . ($page_number + 1)}}" class="btn btn-primary">Next Page</a>
											@endif
										</div>
									</div>
								</div>
							</div>
							<!-- END RECENT PURCHASES -->
						</div>
					</div>
				</div>
			</div>
			<!-- END MAIN CONTENT -->
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