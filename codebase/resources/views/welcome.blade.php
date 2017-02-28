@extends('base')

@section('head')
    @parent
    <link rel="stylesheet" href="/css/welcome.css">
@stop

@section('body')
    <div class="container test">
        <div class="row">    
            <div class="col-xs-8 col-xs-offset-2">
                <div class="input-group">
                    <div class="input-group-btn search-panel">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <span id="search_concept">Search by</span> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                        <li><a href="#author">Author</a></li>
                        <li><a href="#book_name">Book Name</a></li>
                        <li><a href="#publisher">Publisher</a></li>
                        <li class="divider"></li>
                        <li><a href="#all">Anything</a></li>
                        </ul>
                    </div>
                    <input type="hidden" name="search_param" value="all" id="search_param">         
                    <input type="text" class="form-control" name="x" placeholder="Search term...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
                    </span>
                </div>
            </div>
        </div>
    </div>
@stop