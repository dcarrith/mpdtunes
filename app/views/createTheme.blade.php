<div id="create_new_theme" data-role="dialog" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}">

	<div data-role="content" class="align-center" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}">

		{{ Form::open(array('id'=>'create_theme_form', 'url'=>'', 'method'=>'POST')) }}

			<div data-role="fieldcontain" class="width-hundred-percent align-left">

				<div class="width-hundred-percent align-left">

                                {{ Form::label('theme_name', $theme_name_i18n) }}

				<br />

				{{-- Form :: input ( type, name, value, other attributes ) --}}
				{{ Form::input('text', 'theme_name', $theme_name, array('id'=>'theme_name', 'data-theme'=>$theme_buttons, 'class'=>'width-ninety-five-percent')) }}

			    	</div>
				<br />
				<div class="width-hundred-percent align-left">

                                	{{ Form::label('icon_color', $icon_color_i18n, array('class'=>'select width-hundred-percent align-left')) }}
				</div>
				<div style="text-align:left; width:100%;">

					{{ Form::select('icon_color', $icon_color_options, array('id'=>'icon_color', 'data-role'=>'slider', 'data-track-theme'=>'b', 'data-theme'=>'b')) }}
				</div>
				<br />
				<div class="width-hundred-percent align-left">

                                	{{ Form::label('icon_disc', $icon_disc_i18n, array('class'=>'select width-hundred-percent align-left')) }}
				</div>
				<div style="text-align:left; width:100%;">

					{{ Form::select('icon_disc', $icon_disc_options, array('id'=>'icon_disc', 'data-role'=>'slider', 'data-track-theme'=>'b', 'data-theme'=>'b')) }}
				</div>
				<br />
				<div class="width-hundred-percent align-left">

                                	{{ Form::label('bars_letter_code', $bars_i18n, array('class'=>'select width-hundred-percent align-left')) }}
				</div>
				<div class="width-hundred-percent align-left">

					{{ Form::select('bars_letter_code', $theme_color_options, array('id'=>'bars_letter_code', 'data-theme'=>$theme_buttons, 'data-native-menu'=>'true')) }}
				</div>
				<br />
				<div class="width-hundred-percent align-left">

                                	{{ Form::label('buttons_letter_code', $buttons_i18n, array('class'=>'select width-hundred-percent align-left')) }}
				</div>
				<div class="width-hundred-percent align-left">


					{{ Form::select('buttons_letter_code', $theme_color_options, array('id'=>'buttons_letter_code', 'data-theme'=>$theme_buttons, 'data-native-menu'=>'true')) }}
				</div>
				<br />
				<div class="width-hundred-percent align-left">

                                	{{ Form::label('body_letter_code', $body_i18n, array('class'=>'select width-hundred-percent align-left')) }}
				</div>
				<div class="width-hundred-percent align-left">

					{{ Form::select('body_letter_code', $theme_color_options, array('id'=>'body_letter_code', 'data-theme'=>$theme_buttons, 'data-native-menu'=>'true')) }}
				</div>
				<br />
				<div class="width-hundred-percent align-left">

                                	{{ Form::label('controls_letter_code', $controls_i18n, array('class'=>'select width-hundred-percent align-left')) }}
				</div>
				<div class="width-hundred-percent align-left">

					{{ Form::select('controls_letter_code', $theme_color_options, array('id'=>'controls_letter_code', 'data-theme'=>$theme_buttons, 'data-native-menu'=>'true')) }}
				</div>
				<br />
				<div class="width-hundred-percent align-left">

                                	{{ Form::label('action_letter_code', $action_i18n, array('class'=>'select width-hundred-percent align-left')) }}
				</div>
				<div style="text-align:left; width:100%;">

					{{ Form::select('action_letter_code', $theme_color_options, array('id'=>'action_letter_code', 'data-theme'=>$theme_buttons, 'data-native-menu'=>'true')) }}
				</div>
				<br />
				<div class="width-hundred-percent align-left">

                                	{{ Form::label('active_state_letter_code', $active_state_i18n, array('class'=>'select width-hundred-percent align-left')) }}
				</div>
				<div sclass="width-hundred-percent align-left">

					{{ Form::select('active_state_letter_code', $theme_color_options, array('id'=>'active_state_letter_code', 'data-theme'=>$theme_buttons, 'data-native-menu'=>'true')) }}
				</div>
				<br />
			</div>
			<div data-role="fieldcontain" class="padding-top-twenty-pixels">

                                {{ Form::submit($save_i18n, array_merge( array('id'=>'create_theme_save', 'name'=>'create_theme_save', 'type'=>'submit', 'data-theme'=>$theme_action, 'aria-disabled'=>'false') ) ) }}

                                {{ HTML::link('', $cancel_i18n, array('data-role'=>'button', 'data-rel'=>'back', 'data-direction'=>'reverse', 'data-theme'=>$theme_buttons, 'data-transition'=>$default_dialog_transition)) }}

			</div>
		</form>
	</div>
</div>
