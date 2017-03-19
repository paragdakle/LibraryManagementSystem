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
						<li><a href="/" class="active"><i class="lnr lnr-home"></i> <span>Home</span></a></li>
						<li><a href="/users" class=""><i class="lnr lnr-users"></i> <span>Users</span></a></li>
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
					<!-- OVERVIEW -->
					<div class="panel panel-headline">
						<div class="panel-heading">
							<h3 class="panel-title">Library Overview</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-3">
									<div class="metric">
										<span class="icon"><i class="fa fa-book"></i></span>
										<p>
											<span class="number">{{ $bk_cnt }}</span>
											<span class="title">Books</span>
										</p>
									</div>
								</div>
								<div class="col-md-3">
									<div class="metric">
										<span class="icon"><i class="fa fa-users"></i></span>
										<p>
											<span class="number">{{ $u_cnt }}</span>
											<span class="title">Users</span>
										</p>
									</div>
								</div>
								<div class="col-md-3">
									<div class="metric">
										<span class="icon"><i class="fa fa-level-down"></i></span>
										<p>
											<span class="number">{{ $ln_cnt }}</span>
											<span class="title">Books Out</span>
										</p>
									</div>
								</div>
								<div class="col-md-3">
									<div class="metric">
										<span class="icon"><i class="fa fa-usd"></i></span>
										<p>
											<span class="number">{{ $fn_amt }}</span>
											<span class="title">Fines Due</span>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- END OVERVIEW -->
					<div class="row">
						<div class="col-md-6">
							<!-- RECENT PURCHASES -->
							<div class="panel">
								<div class="panel-heading">
									<h3 class="panel-title">Check out book</h3>
								</div>
								<div class="panel-body text-center">
									<div class="input-group">
										<input type="text" value="" id="loan_search_text_box" class="form-control" placeholder="Search library...">
										<span class="input-group-btn"><button type="button" class="btn btn-primary" id="btn_loan">Search</button></span>
									</div>
									<br>
									<span class="label label-default">OR</span>
									<br><br>
									<input type="text" class="form-control" id="co_book_isbn_text_box" placeholder="Book ISBN">
									<br>
									<input type="text" class="form-control" id="co_card_no_text_box" placeholder="Card number">
									<br>
									<span class="input-group-btn"><button type="button" class="btn btn-primary" id="btn_checkout">Checkout</button></span>
								</div>
							</div>
							<!-- END RECENT PURCHASES -->
						</div>
						<div class="col-md-6">
							<!-- RECENT PURCHASES -->
							<div class="panel">
								<div class="panel-heading">
									<h3 class="panel-title">Check in book</h3>
								</div>
								<div class="panel-body text-center">
									<div class="input-group">
										<input type="text" value="" id="return_search_text_box" class="form-control" placeholder="Search library...">
										<span class="input-group-btn"><button type="button" class="btn btn-success" id="btn_return">Search</button></span>
									</div>
									<br>
									<span class="label label-default">OR</span>
									<br><br>
									<input type="text" class="form-control" id="ci_book_isbn_text_box" placeholder="Book ISBN">
									<br>
									<input type="text" class="form-control" id="ci_card_no_text_box" placeholder="Card number">
									<br>
									<span class="input-group-btn"><button type="button" class="btn btn-success" id="btn_checkin">Checkin</button></span>
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