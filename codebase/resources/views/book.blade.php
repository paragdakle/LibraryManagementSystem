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
				<a href="/"><img src="assets/img/logo.png" alt="Klorofil Logo" class="img-responsive logo"></a>
			</div>
			<div class="sidebar-scroll">
				<nav>
					<ul class="nav">
						<li><a href="/"><i class="lnr lnr-home"></i> <span>Home</span></a></li>
						<li><a href="/users" class=""><i class="lnr lnr-users"></i> <span>Users</span></a></li>
						<li>
							<a href="/books" class="active"><i class="lnr lnr-book"></i> <span>Books</span></a>
							<div id="subPages" class="collapse in">
								<ul class="nav">
									<li><a href="" class="active"><i class="lnr lnr-file-empty"></i>Book Details</a></li>
								</ul>
							</div>
						</li>
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
						<div class="col-md-2 text-center" style="padding-top: 80px;">
							<img src="{{ $result->cover }}" alt="No Book Cover"/>
						</div>
						<div class="col-md-6">
							<div class="profile-detail">
								<div class="profile-info">
									<h4 class="heading">{{ $result->title}}</h4>
									<ul class="list-unstyled list-justify">
										<li>ISBN <span>{{ $result->isbn }}</span></li>
										<li>ISBN 13 <span>{{ $result->isbn13 }}</span></li>
										<li>Authors <span>{{ $result->authors }}</span></li>
										<li>Publisher <span>{{ $result->publisher }}</span></li>
										<li>Pages <span>{{ $result->pages }}</span></li>
										@if ($result->copies_avl > 0)
										<td><span class="label label-success">AVAILABLE</span></td>
										@elseif ($result->copies_avl == 0)
										<td><span class="label label-warning">BORROWED</span></td>
										@else
										td><span class="label 
										label-error">MISSING</span></td>
										@endif
									</ul>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<!-- RECENT PURCHASES -->
							<div class="panel">
								<div class="panel-heading">
									@if ($result->copies_avl > 0)
									<h3 class="panel-title">Check out book</h3>
									@elseif ($result->copies_avl == 0)
									<h3 class="panel-title">Check in book</h3>
									@endif
								</div>
								<div class="panel-body text-center">
									<input type="text" class="form-control" id="card_no_text_box" placeholder="Card number">
									<br>
									@if ($result->copies_avl > 0)
									<span class="input-group-btn"><button type="button" class="btn btn-primary" data-isbn="{{ $result->isbn}}" id="btn_bk_checkout">Checkout</button></span>
									@elseif ($result->copies_avl == 0)
									<span class="input-group-btn"><button type="button" class="btn btn-success" data-isbn="{{ $result->isbn}}" id="btn_checkin_bk">Checkin</button></span>
									@endif
								</div>
							</div>
							<!-- END RECENT PURCHASES -->
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<!-- RECENT PURCHASES -->
							<div class="panel">
								<div class="panel-heading">
									<h3 class="panel-title">Loan History</h3>
								</div>
								<div class="panel-body no-padding">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>Card No.</th>
												<th>Date Out</th>
												<th>Date Due</th>
												<th>Date In</th>
											</tr>
										</thead>
										<tbody>
											@foreach($result->loan_history as $result)
											<tr>
												<td>{{ $result['card_id'] }}</td>
												<td>{{ date('m/d/y', $result['date_out']) }}</td>
												<td>{{ date('m/d/y', $result['due_date']) }}</td>
												@if($result['date_in'] == null)
												<td><span class="label label-warning">OUT</span></td>
												@else
													<td>{{ date('m/d/y', $result['date_in']) }}</td>
												@endif
											</tr>
											@endforeach
										</tbody>
									</table>
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