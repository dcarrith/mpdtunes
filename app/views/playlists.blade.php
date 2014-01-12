@extends('layouts.master')

@section('content')

<div id="playlists" data-role="page" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}" id="playlists" data-url="/playlists" > 

	@include('partials.header')

	<div data-role="content">

		<form class="ui-filterable">
    			<input id="playlistsListFilter" data-type="search">
		</form>

		<ul id="playlistsList" data-role="listview" data-filter="true" data-input="#playlistsListFilter" data-inset="true" data-theme="{{ $theme_buttons }}" data-divider-theme="{{ $theme_bars }}"> 

			<li data-role="list-divider">{{ $playlists_i18n }}</li>

                        @foreach($playlists as $playlist)

                                @include('partials.playlist')

                        @endforeach
			
			<li data-role="list-divider"></li>
		</ul>

		@include('partials/lazyloaderProgress')

	</div> 

	@include('partials.footer')

</div>
