<!DOCTYPE html>
<html>
	<head>
		@include('partials.htmlHeader')
	</head>

	{{ flush() }}

	<body>

		<div class="inner-body {{ $theme_classes }}">

			<div id="player" class="audioWrapper">

			</div>

		  	@yield('content')

			{{ flush() }}

			@include('partials.htmlFooter')

		</div>
	</body>
</html>
