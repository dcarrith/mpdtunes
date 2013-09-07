<!DOCTYPE html>
<html>
	<head>
		@include('partials.htmlHeader')
	</head>

	{{ flush() }}

	<body>
		<div id="playerOne" class="audioWrapper">
			
		</div>

		<div id="playerTwo" class="audioWrapper">
			
		</div>

	  	@yield('content')

		{{ flush() }}

		@include('partials.htmlFooter')
	
	</body>
</html>
