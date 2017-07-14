<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<!--meta para caracteres especiales-->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!--Let browser know website is optimized for mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ config('app.name', 'Laravel') }}</title>
	<!--Import Google Icon Font-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!--Import materialize.css-->
	<!--<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.min.css">
	<!--estilo css personal-->
	<link type="text/css" rel="stylesheet" href="{{ asset('css/style.css') }}"  media="screen,projection"/>
</head>
<body class="grey lighten-3">

@yield('content')

<!--Import jQuery before materialize.js-->
<!--Script para hacer los datos ordenarse-->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>
<script>

$(document).ready(function() { //iniciamos el jQuery del select
	$('select').material_select();
	$('.modal').modal();
});

</script>
</body>
</html>

