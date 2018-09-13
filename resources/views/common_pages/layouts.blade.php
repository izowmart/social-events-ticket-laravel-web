<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @yield('title')    
    <!-- Main css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    
    <!-- Font-icon css-->
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	@yield('styles')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="app sidebar-mini">
    
    @yield('content') 

<script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script>
    (function () {
	"use strict";

	var treeviewMenu = $('.app-menu');

	// Toggle Sidebar
	$('[data-toggle="sidebar"]').click(function(event) {
		event.preventDefault();
		$('.app').toggleClass('sidenav-toggled');
	});

	// Activate sidebar treeview toggle
	$("[data-toggle='treeview']").click(function(event) {
		event.preventDefault();
		if(!$(this).parent().hasClass('is-expanded')) {
			treeviewMenu.find("[data-toggle='treeview']").parent().removeClass('is-expanded');
		}
		$(this).parent().toggleClass('is-expanded');
	});

	// Set initial active toggle
	$("[data-toggle='treeview.'].is-expanded").parent().toggleClass('is-expanded');

	//Activate bootstrip tooltips
	$("[data-toggle='tooltip']").tooltip();

	})();
</script>
<!-- The javascript plugin to display page loading on top-->
<script src="{{ asset('js/plugins/pace.min.js') }}"></script>
@yield('other-scripts') 
</body>
</html>
