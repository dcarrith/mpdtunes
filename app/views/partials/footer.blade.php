<div id="site_footer" data-role="footer" data-theme="{{ $theme_bars }}" class="ui-bar-{{ $theme_bars }} ui-footer" data-position="fixed" data-tap-toggle="false" data-update-page-padding="false" >
	<table class="width-hundred-percent">
		<tr>
			<td class="width-twenty-percent align-left">

				{{ HTML::link('#', '', array('id'=>'scroll_up', 'onclick'=>'incremental_scroll_up();', 'data-role'=>'button', 'data-icon'=>'arrow-u', 'data-iconpos'=>'notext', 'class'=>'ui-btn-right', 'data-theme'=>$theme_buttons, 'title'=>$scroll_up_i18n, 'data-transition'=>$default_page_transition)) }}

			</td>
			<td class="width-sixty-percent align-center">

    			@if ($debug)

				<div class="width-hundred-percent align-center">

					{{-- HTML::link('#', $toggle_profiler_results_i18n, array('id'=>'scroll_up', 'onclick'=>'$(\'#codeigniter_profiler\').toggle();', 'data-role'=>'button', 'data-theme'=>$theme_action, 'title'=>$toggle_profiler_results_i18n)) --}}

				</div>
				
	   		@endif

    		</td>
    		<td class="width-twenty-percent align-right">

			{{ HTML::link('#', '', array('id'=>'scroll_down', 'onclick'=>'incremental_scroll_down(\''.$section.'\');', 'data-role'=>'button', 'data-icon'=>'arrow-d', 'data-iconpos'=>'notext', 'class'=>'ui-btn-left', 'data-theme'=>$theme_buttons, 'title'=>$scroll_down_i18n)) }}

			</td>
		</tr>
	</table>
</div>
