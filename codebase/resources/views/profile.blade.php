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
									<li><a href="#" class="active"><i class="lnr lnr-user"></i>User Profile</a></li>
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
					<div id="navbar-menu" class="navbar-collapse collapse">
						<form class="navbar-form navbar-right hidden-xs">
							<div class="input-group">
                                <span class="input-group-btn"><button type="button" class="btn btn-success" data-card="{{ $user['card_id']}}" id="btn_clear_fines">Clear Fines</button></span>
							</div>
						</form>
					</div>
				</div>
			</nav>
			<!-- END NAVBAR -->
			<!-- MAIN CONTENT -->
			<div class="main-content">
				<div class="container-fluid">
					<div class="panel panel-profile">
						<div class="clearfix">
							<!-- LEFT COLUMN -->
							<div class="profile-left">
								<!-- PROFILE HEADER -->
								<div class="profile-header">
									<div class="profile-main">
										<h3 class="name">{{ $user['bname'] }}</h3>
									</div>
								</div>
								<!-- END PROFILE HEADER -->
								<!-- PROFILE DETAIL -->
								<div class="profile-detail">
									<div class="profile-info">
										<h4 class="heading">Basic Info</h4>
										<ul class="list-unstyled list-justify">
											<li>Card No. <span>{{ $user['card_id'] }}</span></li>
											<li>SSN <span>{{ $user['ssn'] }}</span></li>
											<li>Mobile <span>{{ $user['phone'] }}</span></li>
											<li>Email <span>{{ $user['email'] }}</span></li>
											<li>Address <span>{{ $user['address'] }}</span></li>
											<li>City <span>{{ $user['city'] }}</span></li>
											<li>State <span>{{ $user['state'] }}</span></li>
										</ul>
									</div>
								</div>
								<!-- END PROFILE DETAIL -->
							</div>
							<!-- END LEFT COLUMN -->
							<!-- RIGHT COLUMN -->
							<div class="profile-right">
								<!-- TABBED CONTENT -->
								<div class="custom-tabs-line tabs-line-bottom left-aligned">
									<ul class="nav" role="tablist">
										<li class="active"><a href="#tab-bottom-left1" role="tab" data-toggle="tab">Borrowed Books</a></li>
										<li><a href="#tab-bottom-left2" role="tab" data-toggle="tab">Fines</a></li>
									</ul>
								</div>
								<div class="tab-content">
									<div class="tab-pane fade in active" id="tab-bottom-left1">
										<div class="table-responsive">
											<table class="table project-table">
												<thead>
													<tr>
														<th>ISBN</th>
														<th>Date Out</th>
														<th>Date Due</th>
														<th>Date In</th>
													</tr>
												</thead>
												<tbody>
													@foreach($user['loan_history'] as $item)
													<tr>
														<td>{{ $item['isbn'] }}</td>
														<td>{{ date('m/d/y', $item['date_out']) }}</td>
														<td>{{ date('m/d/y', $item['due_date']) }}</td>
														@if($item['date_in'] == null)
														<td><span class="input-group-btn"><button type="button" class="btn btn-primary" data-card="{{ $user['card_id']}}" data-isbn="{{ $item['isbn']}}" id="btn_checkin_bk">Checkin</button></span></td>
														@else
															<td>{{ date('m/d/y', $item['date_in']) }}</td>
														@endif
													</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="tab-bottom-left2">
										<div class="table-responsive">
											<table class="table project-table">
												<thead>
													<tr>
														<th>ISBN</th>
														<th>Fine</th>
														<th>Date Paid</th>
														<th>Status</th>
													</tr>
												</thead>
												<tbody>
													@foreach($user['fine_history'] as $item)
													<tr>
														<td>{{ $item['isbn'] }}</td>
														<td>{{ $item['fine_amt'] }}</td>
														@if ($item['paid'] == 0)
														<td> - </td>
														<td><span class="input-group-btn"><button type="button" class="btn btn-warning" data-card="{{ $user['card_id']}}" data-loan="{{ $item['loan_id']}}" id="btn_pay_fine">Pay</button></span></td>
														@else
														<td>{{ date('d/m/y', $item['date_paid']) }}</td>
														<td><span class="label 
														label-success">Paid</span></td>
														@endif
													</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<!-- END TABBED CONTENT -->
							</div>
							<!-- END RIGHT COLUMN -->
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
