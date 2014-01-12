@extends('layouts.master')

@section('content')

<div id="stations" data-role="page" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}" data-url="/stations" > 
	
	@include('partials.header')

	<div data-role="content">

                <a href="/stations/edit/{{ $users_station_id }}" data-role="button" data-rel="dialog" data-transition="{{ $default_dialog_transition }}" class="ui-btn ui-btn-corner-all ui-shadow ui-btn-up-{{ $theme_buttons }}" data-theme="{{ $theme_buttons }}">

                        <span class="ui-btn-inner ui-btn-corner-all">
                                <span class="ui-btn-text">{{ $customize_your_station_i18n }}</span>
                        </span>
                </a>

                <a href="/stations/add" data-role="button" data-rel="dialog" data-transition="{{ $default_dialog_transition }}" class="ui-btn ui-btn-corner-all ui-shadow ui-btn-up-{{ $theme_action }}" data-theme="{{ $theme_action }}">

                        <span class="ui-btn-inner ui-btn-corner-all">
                                <span class="ui-btn-text">{{ $add_a_new_station_i18n }}</span>
                        </span>
                </a>

		<form class="ui-filterable">
    			<input id="stationsListFilter" data-type="search">
		</form>

		<ul id="stationsList" data-filter="true" data-input="#stationsListFilter" data-role="listview" data-inset="true" data-filter="true" data-divider-theme="{{ $theme_bars }}" data-theme="{{ $theme_buttons }}"> 

			<li data-role="list-divider">{{ $stations_i18n }}</li>

                        @foreach($stations as $station)

                                @include('partials.station')

                        @endforeach

			<li data-role="list-divider"></li>
		</ul>
		
		@include('partials/lazyloaderProgress')
		
	</div> 

	@include('partials.header')

</div>

@stop
