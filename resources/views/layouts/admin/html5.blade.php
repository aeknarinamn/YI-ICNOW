<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
	    <meta http-equiv="x-ua-compatible" content="ie=edge">
	    {{-- <link rel="shortcut icon" href="{{ asset('rtd/imgs/favicon.png') }}"> --}}
	    <link rel="shortcut icon" href="">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>RTD</title>

		<link rel="stylesheet" type="text/css" href="{{asset('rtd/css/vendor/bootstrap.min.css')}}">
		<link rel="stylesheet" type="text/css" href="{{asset('rtd/css/vendor/font-awesome.min.css')}}">
		<link rel="stylesheet" type="text/css" href="{{asset('rtd/css/vendor/style.css')}}">
		<link rel="stylesheet" type="text/css" href="{{asset('rtd/css/vendor/responsive.css')}}">
		<script src="{{asset('rtd/js/jquery.js')}}"></script>

		@yield('head')
	</head>
	<body>
		<header>
			@include('layouts.rtd.header')
		</header>
		<section>
			@yield('body')
		</section>
		<footer>
			@include('layouts.rtd.footer')
		</footer>
		
		<!-- <script src="https://cdn.jsdelivr.net/foundation/6.2.4/foundation.min.js"></script> -->
		<script src="{{asset('rtd/js/foundation.min.js')}}"></script>
		<script src="{{asset('rtd/js/app.js')}}"></script>
	</body>
</html>