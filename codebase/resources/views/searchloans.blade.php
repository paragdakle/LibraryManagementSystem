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
						<li><a href="/users" class=""><i class="lnr lnr-users"></i> <span>Users</span></a></li>
						<li><a href="/books" class=""><i class="lnr lnr-book"></i> <span>Books</span></a></li>
						<li><a href="#" class="active"><i class="lnr lnr-magnifier"></i> <span>Search Results</span></a></li>
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
						<form class="navbar-form navbar-left hidden-xs">
							<div class="input-group">
								<input type="text" value="{{ $search_term }}" id="search_text_box" class="form-control" placeholder="Search library...">
								<span class="input-group-btn"><button type="button" class="btn btn-primary" id="btn_return">Search</button></span>
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
									<h3 class="panel-title">Results</h3>
								</div>
								<div class="panel-body no-padding">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>Card No.</th>
												<th>Name</th>
												<th>ISBN</th>
												<th>Date Out</th>
												<th>Date Due</th>
												<th>Date In</th>
											</tr>
										</thead>
										<tbody>
											@foreach($results as $result)
											<tr>
												<td><a href="{{ '/borrower?card_id=' . $result['card_id'] }}">{{ $result['card_id'] }}</a></td>
												<td>{{ $result['bname']}}</td>
												<td>{{ $result['isbn'] }}</td>
												<td>{{ date('m/d/y', $result['date_out']) }}</td>
												<td>{{ date('m/d/y', $result['due_date']) }}</td>
												@if($result['date_in'] == null)
												<td><span class="input-group-btn"><button type="button" class="btn btn-primary" data-card="{{ $result['card_id']}}" data-isbn="{{ $result['isbn']}}" id="btn_checkin_bk">Checkin</button></span></td>
												@else
													<td>{{ date('m/d/y', $result['date_in']) }}</td>
												@endif
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								<div class="panel-footer">
									<div class="row">
										<div class="col-md-6"><span class="panel-note">Showing {{ count($results)}} results.</span></div>
										<div class="col-md-6 text-right">
											@if ($page_number > 1)
											<a href="{{ '/bookloans/search?term=' . $search_term . '&page=' . ($page_number - 1)}}" class="btn btn-primary">Prev Page</a>
											@endif
											@if(count($results) > 0)
											<a href="{{ '/bookloans/search?term=' . $search_term . '&page=' . ($page_number + 1)}}" class="btn btn-primary">Next Page</a>
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