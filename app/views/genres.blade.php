@extends('layouts.master')

@section('content')

<div id="genres" data-role="page" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}">
	
	@include('partials.header')

	<div data-role="content">
	
		<form class="ui-filterable">
    			<input id="genresListFilter" data-type="search">
		</form>

		<ul id="genresList" data-role="listview" data-filter="true" data-input="#genresListFilter" data-inset="true" data-theme="{{ $theme_buttons }}" data-divider-theme="{{ $theme_bars }}"> 
			<li data-role="list-divider">{{ $genres_i18n }}</li>

			<?php if (isset($genres) && (count($genres) > 0) && $genres != '') : ?>

			<?php foreach($genres as $genre) : ?>

				<li>

				{{ HTML::link('genre/'.urlencode(urlencode($genre)).'/artists', $genre, array('data-transition'=>$default_page_transition)) }}

				</li>

			<?php endforeach; ?>

			<?php endif; ?>

			<li data-role="list-divider"></li>
		</ul>

		@include('partials/lazyloaderProgress')

	</div> 

	@include('partials.footer')

</div>
