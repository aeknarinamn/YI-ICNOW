<!DOCTYPE html>
<html lang="en">
	<head>
		@yield('meta')
		<meta charset="UTF-8">
		<title>FWD</title>
		<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-theme.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/html5.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/fonts.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/dataTables-bootstrap.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datetimepicker.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">
    	<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-tagsinput.css') }}">
		<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}" defer="defer"></script>
		<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js') }}" defer="defer"></script>
		<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}" defer="defer"></script>
		<script type="text/javascript" src="{{ asset('js/bootstrap3-typeahead.min.js') }}" defer="defer"></script>
		<script type="text/javascript" src="{{ asset('js/restfulizer.js') }}" defer="defer"></script>
		<script type="text/javascript" src="{{ asset('js/dataTables.min.js') }}" defer="defer"></script>
		<script type="text/javascript" src="{{ asset('js/dataTables.bootstrap.js') }}" defer="defer"></script>
		<script type="text/javascript" src="{{ asset('js/html5.js') }}" defer="defer"></script>
		<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.min.js') }}" defer="defer"></script>
		<script type="text/javascript" src="{{ asset('js/bootstrap-select.min.js') }}" defer="defer"></script>
    	<script type="text/javascript" src="{{ asset('js/jquery-swapsies.js')  }}" defer="defer"></script>
    	<script type="text/javascript" src="{{ asset('js/bootstrap-tagsinput.min.js') }}" defer="defer"></script>
		@yield('html5-head')
	</head>
	
	<body>	
		@yield('html5-body')
	</body>
</html>