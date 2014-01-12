@extends('layouts.master')

@section('content')

<div id="artists" data-role="page" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}" {{ $data_url }}>

	@include('partials.header')
	
	<div data-role="content">

		<form class="ui-filterable">
    			<input id="artistsListFilter" data-type="search">
		</form>
		
		<input type="hidden" id="param_one" name="param_one" value="{{ $selected_genre }}" />

		<ul id="artistsList" data-role="listview" data-filter="true" data-input="#artistsListFilter" data-inset="true" data-theme="{{ $theme_buttons }}" data-divider-theme="{{ $theme_bars }}"> 

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
