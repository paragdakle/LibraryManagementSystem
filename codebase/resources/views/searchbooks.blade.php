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
								<input type="text" value="{{ $search_term }}" id="loan_search_text_box" class="form-control" placeholder="Search library...">
								<span class="input-group-btn"><button type="button" class="btn btn-primary" id="btn_loan">Search</button></span>
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
												<th>ISBN</th>
												<th>Cover</th>
												<th>Title</th>
												<th>Authors</th>
												<th>Publisher</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
											@foreach($results as $result)
											<tr>
												<td><a href="{{ '/book?isbn=' . $result['isbn']}}">{{ $result['isbn'] }}</a></td>
												<td><img src="{{ $result['cover'] }}"/></td>
												<td>{{ $result['title'] }}</td>
												<td>{{ $result['authors'] }}</td>
												<td>{{ $result['publisher'] }}</td>
												@if ($result['copies_avl'] > 0)
												<td><span class="label label-success">AVAILABLE</span></td>
												@elseif ($result['copies_avl'] == 0)
												<td><span class="label label-warning">BORROWED</span></td>
												@else
												td><span class="label 
												label-error">MISSING</span></td>
												@endif
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								<div class="panel-footer">
									<div class="row">
										<div class="col-md-6"><span class="panel-note"><i class="fa fa-clock-o"></i> Last 24 hours</span></div>
										<div class="col-md-6 text-right"><a href="#" class="btn btn-primary">View All Books</a></div>
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
