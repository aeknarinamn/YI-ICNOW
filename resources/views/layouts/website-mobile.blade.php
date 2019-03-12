@extends('layouts.html5')

@section('meta')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
@stop

@section('html5-head')
    @yield('head')
    <style type="text/css">
    	    
    	    html , body {
    	    	font-family: 
    	    	"pslregular", 
    	    	"Helvetica Neue", RSU_Regular, RSU_BOLD, sans-serif !important;
    	    }

    	    label {
    	    	font-weight: bold !important;
    	    }
    </style>
@stop

@section('html5-body')
    @yield('body')
@stop