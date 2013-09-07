<!DOCTYPE html>
<html>
	<head>
		@include('partials.htmlHeader')
	
		<script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
	</head>

	{{ flush() }}

	<body>

		@yield('content')

		{{ flush() }}

		@include('partials.htmlFooterAnon')
	</body>
</html>
