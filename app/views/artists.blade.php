@extends('layouts.master')

@section('content')

<div id="artists" data-role="page" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}" {{ $data_url }}>

	@include('partials.header')
	
	<div data-role="content">

		<div class="padding-bottom-fifteen-pixels"></div>
		
		<input type="hidden" id="param_one" name="param_one" value="{{ $selected_genre }}" />

		<ul id="artistsList" data-role="listview" role="lazyloader" data-filter="true" data-inset="true" data-theme="{{ $theme_buttons }}" data-divider-theme="{{ $theme_bars }}"> 

			<li data-role="list-divider">{{ $artists_i18n }}</li>

                        @foreach($artists as $artist)
	
                                @include('partials.artist')

                        @endforeach

			<li data-role="list-divider"></li>
		</ul>

		@include('partials/lazyloaderProgress')

	</div>

	@include('partials.footer')

</div>

@stop
