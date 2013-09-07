@extends('layouts.master')

@section('content')

<div data-role="page" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}" id="edit_user_config" data-transition="{{ $default_page_transition }}"> 
	
	@include('partials.header')

	<div data-role="content" class="align-center" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}">
		
                @if ( $saved_successfully )

                        @include('partials.success')

                @endif

                {{ Form::open(array('url'=>'/admin/edit/user/'.$user_id, 'method'=>'POST', 'data-ajax'=>'true', 'data-transition'=>'none', 'data-history'=>'false')) }}

                	{{ Form::input('hidden', 'csrf_token', csrf_token()) }}

			{{ Form::input('hidden', 'user_id', $user_id, array('id'=>'user_id')) }}

			<div data-role="fieldcontain" class="width-hundred-percent align-left">

				<div class="form-field-div width-hundred-percent align-left">

					{{ Form::label('user_account_active', $user_account_active_i18n) }}
				
					{{-- Form :: input ( type, name, value, other attributes ) --}}
                                        {{ Form::input('checkbox', 'user_account_active', $user_account_active, array_merge(array('id'=>'user_account_active', 'data-theme'=>$theme_buttons), $user_account_active_input_options)) }}

				</div>

				<div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('mpd_host', $mpd_host_i18n) }}

                                        {{ Form::input('text', 'mpd_host', $mpd_host, array('id'=>'mpd_host', 'data-theme'=>$theme_buttons)) }}

                                        @if ($errors->has('mpd_host'))
                                        <div class="required-field-error">{{ $errors->first('mpd_host') }}</div>
                                        @endif
			    	</div>
			
				<div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('mpd_port', $mpd_port_i18n) }}

                                        {{ Form::input('text', 'mpd_port', $mpd_port, array('id'=>'mpd_port', 'data-theme'=>$theme_buttons)) }}

                                        @if ($errors->has('mpd_port'))
                                        <div class="required-field-error">{{ $errors->first('mpd_port') }}</div>
                                        @endif
			    	</div>

				<div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('mpd_stream_port', $mpd_stream_port_i18n) }}

                                        {{ Form::input('text', 'mpd_stream_port', $mpd_stream_port, array('id'=>'mpd_stream_port', 'data-theme'=>$theme_buttons)) }}

                                        @if ($errors->has('mpd_stream_port'))
                                        <div class="required-field-error">{{ $errors->first('mpd_stream_port') }}</div>
                                        @endif
			    	</div>

				<div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('mpd_password', $mpd_password_i18n) }}

                                        {{ Form::input('password', 'mpd_password', $mpd_password, array('id'=>'mpd_password', 'data-theme'=>$theme_buttons)) }}

                                        @if ($errors->has('mpd_password'))
                                        <div class="required-field-error">{{ $errors->first('mpd_password') }}</div>
                                        @endif
			    	</div>

				<div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('mpd_password_confirmation', $confirm_mpd_password_i18n) }}

                                        {{ Form::input('password', 'mpd_password_confirmation', $mpd_password_confirmation, array('id'=>'mpd_password_confirmation', 'data-theme'=>$theme_buttons)) }}

                                        @if ($errors->has('mpd_password_confirmation'))
                                        <div class="required-field-error">{{ $errors->first('mpd_password_confirmation') }}</div>
                                        @endif
				</div>

				<div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('mpd_dir', $mpd_dir_i18n) }}

                                        {{ Form::input('text', 'mpd_dir', $mpd_dir, array('id'=>'mpd_dir', 'data-theme'=>$theme_buttons)) }}

                                        @if ($errors->has('mpd_dir'))
                                        <div class="required-field-error">{{ $errors->first('mpd_dir') }}</div>
                                        @endif
			    	</div>

				<div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('music_dir', $music_dir_i18n) }}

                                        {{ Form::input('text', 'music_dir', $music_dir, array('id'=>'music_dir', 'data-theme'=>$theme_buttons)) }}

                                        @if ($errors->has('music_dir'))
                                        <div class="required-field-error">{{ $errors->first('music_dir') }}</div>
                                        @endif
			    	</div>

				<div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('queue_dir', $queue_dir_i18n) }}

                                        {{ Form::input('text', 'queue_dir', $queue_dir, array('id'=>'queue_dir', 'data-theme'=>$theme_buttons)) }}

                                        @if ($errors->has('queue_dir'))
                                        <div class="required-field-error">{{ $errors->first('queue_dir') }}</div>
                                        @endif
			    	</div>

				<div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('art_dir', $art_dir_i18n) }}

                                        {{ Form::input('text', 'art_dir', $art_dir, array('id'=>'art_dir', 'data-theme'=>$theme_buttons)) }}

                                        @if ($errors->has('art_dir'))
                                        <div class="required-field-error">{{ $errors->first('art_dir') }}</div>
                                        @endif
			    	</div>
			</div>

			<div data-role="fieldcontain">

                                {{ Form::submit($save_i18n, array('id'=>'admin_user_save', 'name'=>'admin_user_save', 'type'=>'submit', 'data-theme'=>$theme_action, 'aria-disabled'=>'false') ) }}

                                {{ HTML::link('', $cancel_i18n, array('data-role'=>'button', 'data-rel'=>'back', 'data-direction'=>'reverse', 'data-theme'=>$theme_buttons, 'data-transition'=>$default_dialog_transition)) }}

			</div>

			@if ($user_id > 1) 

				<div data-role="fieldcontain" class="width-hundred-percent align-left">

					<a href="/admin/confirm_delete?item_type=user&item_name={{ $user->email }}&item_id={{ $user_id }}" data-role="button" data-rel="dialog" data-transition="{{ $default_alert_transition }}" data-theme="{{ $theme_alert }}">
						<span class="ui-btn-text">{{ $delete_user_account_i18n }}</span>
					</a>
				</div>
			
			@endif

		</form>
	</div>

	@include('partials.footer')

</div>

@stop
