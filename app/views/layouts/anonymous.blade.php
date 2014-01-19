<!DOCTYPE html>
<html>
	<head>
		@include('partials.htmlHeaderAnon')
	</head>

	{{ flush() }}

	<body>

		@yield('content')

		{{ flush() }}

		@include('partials.htmlFooterAnon')
	</body>
</html>
