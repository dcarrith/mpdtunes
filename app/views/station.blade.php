@extends('layouts.master')

@section('content')

<div id="station" data-role="dialog" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}" data-url="{{ $data_url }}">
	
	<div data-role="content" class="align-center" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}">

		@if ($saved_successfully)

			@include('partials.success')

		@endif

                {{ Form::open(array('id'=>'stations_add_form', 'url'=>$form_action_url, 'method'=>'POST', 'data-ajax'=>'true', 'data-transition'=>'none', 'data-history'=>'false')) }}

                {{ Form::input('hidden', 'csrf_token', csrf_token()) }}

                {{ Form::input('hidden', 'station_id', $station_id, array('id'=>'station_id')) }}

                {{ Form::input('hidden', 'station_icon_id', $icon_id, array('id'=>'station_icon_id')) }}

			@if (( $mode == "edit" ) && $deletable )

				<a href="/station/confirm_delete?item_type=station&item_name={{ urlencode($station_name) }}&item_id={{ $station_id }}" data-role="button" data-rel="dialog" data-transition="{{ $default_alert_transition }}" data-theme="{{ $theme_alert }}">

					<span class="ui-btn-text">{{ $delete_station_i18n }}</span>
				</a>

			@endif

			<div data-role="fieldcontain" class="align-left">

				<div class="station-form-field-div">

                                	{{ Form::label('station_url', $station_url_i18n.$url_special_note_i18n) }}

                                	{{-- Form :: input ( type, name, value, other attributes ) --}}
                                	{{ Form::input('text', 'station_url', $station_url , array_merge( array('id'=>'station_url', 'data-theme'=>$theme_buttons), $station_url_input_disabled )) }}

                                	@if ($errors->has('station_url'))
                                	<div class="required-field-error">{{ $errors->first('station_url') }}</div>
                                	@endif
				</div>

				<br />

				<label class="station-labels width-hundred-percent valign-bottom" for="station_image_file">{{ $icon_jpg_gif_or_png_i18n }}: </label>

	            		<div class="station-field-divs">

                    			<div class="station-image-fields-outer-div">

                    				<table class="station-image-fields-table" border="0">
                    					<tr>
					    			<td class="station-image-field-cell">
					    				<div id="uploaded_image_div" class="station-uploaded-image-div"><img id="uploaded_image" src="{{ $icon_url_path }}" class="station-uploaded-image" /></div>
					    			</td>
                    						<td>
	                    						<table class="width-hundred-percent align-left">
	                    							<tr>
	                    								<td>
						    						<div id="station_image_file_div" class="station-image-file-div">
						    							<input id="station_image_file" name="file" type="file" {{ $station_image_file_disabled }} />
						    						</div>
						    					</td>
						    				</tr>
					    				</table>
					    			</td>
					    		</tr>
						</table>
                    			</div>
	            		</div>

	            		<br style="clear:both;"/>

				<div class="station-form-field-div">

                                        {{ Form::label('station_name', $station_name_i18n." ".$station_name_maximum_i18n) }}

                                        {{ Form::input('text', 'station_name', ((($mode == 'edit') || (!$saved_successfully)) ? $station_name : ''), array_merge( array('id'=>'station_name', 'data-theme'=>$theme_buttons), $station_name_input_disabled )) }}

                                        @if ($errors->has('station_name'))
                                        <div class="required-field-error">{{ $errors->first('station_name') }}</div>
                                        @endif
			    	</div>

			    	<br />

				<div class="station-form-field-div">

                                        {{ Form::label('station_description', $station_description_i18n." ".$station_description_maximum_i18n) }}

                                        {{ Form::input('text', 'station_description', $station_description, array_merge( array('id'=>'station_description', 'data-theme'=>$theme_buttons), $station_description_input_disabled )) }}
     
					@if ($errors->has('station_description'))
                                        <div class="required-field-error">{{ $errors->first('station_description') }}</div>
                                        @endif
					
			    	</div>

			    	<br />

				<div class="station-form-field-div">

                                        {{ Form::label('station_visibility', $broadcast_to_the_public_i18n) }}

					{{ Form::input('checkbox', 'station_visibility', $station_visibility, array_merge(array('id'=>'station_visibility', 'data-theme'=>$theme_buttons), $station_visibility_input_options)) }}

				</div>

			</div>

			<div data-role="fieldcontain">

                		{{ Form::submit($save_i18n, array_merge( array('id'=>'station_save_test', 'name'=>'station_save_test', 'type'=>'submit', 'data-theme'=>$theme_action, 'aria-disabled'=>'false'), $station_save_button_disabled ) ) }}

                		{{ HTML::link('', $cancel_i18n, array('data-role'=>'button', 'data-rel'=>'back', 'data-direction'=>'reverse', 'data-theme'=>$theme_buttons, 'data-transition'=>$default_dialog_transition)) }}

			</div>
		</form>
	</div>
</div>

@stop
