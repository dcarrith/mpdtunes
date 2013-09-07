<div data-role="dialog" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}" id="save_playlist"> 
	
	<div data-role="content" class="align-center" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}">

		@if ($saved_successfully)

			@include('partials.success')

		@endif
		
		{{ Form::open(array('url'=>'/playlist/save', 'method'=>'POST', 'data-ajax'=>'true', 'data-transition'=>'none', 'data-history'=>'false')) }}

			<div data-role="fieldcontain" class="form-field-div align-left">

			{{ Form::label('playlist_name', $playlist_name_i18n) }}

			{{-- Form :: input ( type, name, value, other attributes ) --}}
			{{ Form::input('text', 'playlist_name', '', array_merge( array('id'=>'playlist_name', 'data-theme'=>$theme_buttons) )) }}

			@if ($errors->has('playlist_name'))
			<div class="required-field-error">{{ $errors->first('playlist_name') }}</div>
			@endif

			</div>
			<br />
			<div data-role="fieldcontain">

                                {{ Form::submit($save_i18n, array_merge( array('id'=>'create_playlist_save', 'name'=>'create_playlist_save', 'type'=>'submit', 'data-theme'=>$theme_action, 'aria-disabled'=>'false') ) ) }}

                                {{ HTML::link('', $cancel_i18n, array('data-role'=>'button', 'data-rel'=>'back', 'data-direction'=>'reverse', 'data-theme'=>$theme_buttons, 'data-transition'=>$default_dialog_transition)) }}

			</div>
		</form>
	</div>
</div>
