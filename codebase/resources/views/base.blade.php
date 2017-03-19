<!DOCTYPE html>
<html lang="en">
    <head>
    	<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <title>Open Library</title>
        <base href="{{URL::asset('/')}}" target="_top">
        @section('head')
            <!-- CSS -->
            <link rel="stylesheet" href="assets/css/bootstrap.min.css">
            <link rel="stylesheet" href="assets/css/vendor/icon-sets.css">
            <link rel="stylesheet" href="assets/css/main.css">
            <!-- GOOGLE FONTS -->
            <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
            <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">
        @show
    </head>
    <body>
    	@yield('body')
        @section('jsimports')
            <script src="assets/js/jquery/jquery-2.1.0.min.js"></script>
            <script src="assets/js/bootstrap/bootstrap.min.js"></script>
            <script src="assets/js/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
            <script src="assets/js/plugins/toastr/toastr.min.js"></script>
        @show
    </body>
</html>