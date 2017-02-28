<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="shortcut icon" href="/favicon.ico">
    	<meta charset="UTF-8">
        <title>Open Library</title>
        @section('head')
            <meta name="viewport" content="width=device-width, initial-scale=1">
        	<link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
        @show
    </head>
    <body>
    	@yield('body')
        @section('jsimports')
            <script src="/jquery/jquery.min.js"></script>
            <script type="text/javascript" src="/bootstrap/js/bootstrap.min.js"></script>
        @show
    </body>
</html>