<div id="volume_crossfade" data-role="dialog" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}"> 

	<div data-role="content" class="align-center" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}">

		{{ Form::open(array('id'=>'volume_crossfade_form', 'url'=>'', 'method'=>'POST')) }}

			<div data-role="fieldcontain" class="width-hundred-percent align-left">

				@if ($admin_user)

				<br />
				<div class="width-hundred-percent align-left">

                                {{ Form::label('volume_control', $volume_control_i18n) }}
				
				<br />
                                
				{{-- Form :: input ( type, name, value, other attributes ) --}}
				{{ Form::input('range', 'volume_control', $current_volume, array('id'=>'volume_control', 'min'=>'0', 'max'=>'100', 'step'=>'5', 'data-theme'=>$theme_buttons, 'data-track-theme'=>$theme_buttons, 'class'=>'width-ninety-five-percent', 'data-highlight'=>'true')) }}
				
				</div>

			 	@endif

				<br />
				<div class="width-hundred-percent align-left">

                                {{ Form::label('xfade_control', $xfade_control_i18n) }}
				
				<br />
				
				{{-- Form :: input ( type, name, value, other attributes ) --}}
                                {{ Form::input('range', 'xfade_control', $current_xfade, array('id'=>'xfade_control', 'min'=>'0', 'max'=>'100', 'step'=>'5', 'data-theme'=>$theme_buttons, 'data-track-theme'=>$theme_buttons, 'class'=>'width-ninety-five-percent', 'data-highlight'=>'true')) }}

			 	</div>

				<br />
				<div class="width-hundred-percent align-left">
	
				{{ Form::label('volume_fade_control', $volume_fade_control_i18n) }}

				<br />

				{{-- Form :: input ( type, name, value, other attributes ) --}}
                                {{ Form::input('range', 'volume_fade_control', $current_volume_fade, array('id'=>'volume_fade_control', 'min'=>'0', 'max'=>'100', 'step'=>'5', 'data-theme'=>$theme_buttons, 'data-track-theme'=>$theme_buttons, 'class'=>'width-ninety-five-percent', 'data-highlight'=>'true')) }}

			 	</div>

				<br />
			 	<br />
			</div>

			<div data-role="fieldcontain">

                                {{ Form::submit($save_i18n, array('class'=>'ui-btn-hidden', 'data-rel'=>'back', 'data-direction'=>'reverse', 'value'=>'submit-value', 'id'=>'volume_crossfade_form_save', 'name'=>'volume_crossfade_form_save', 'data-theme'=>$theme_action, 'type'=>'button', 'aria-disabled'=>'false', 'content'=>$save_i18n) ) }}

                                {{ HTML::link('', $cancel_i18n, array('data-role'=>'button', 'data-rel'=>'back', 'data-direction'=>'reverse', 'data-theme'=>$theme_buttons, 'data-transition'=>$default_dialog_transition)) }}
				
			</div>
		</form>
	</div>
</div>
