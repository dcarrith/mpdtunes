<?php

// We need to be able to initialize some javascript variables using values from PHP
// First though, let's enable gzip compression for the PHP output if it's supported
//if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
?>
<script type="text/javascript">

var defaultPageTransition = "slide";
var defaultDialogTransition = "pop";

// This is the global theme object to be used in the javascript of the application
var theme = new Object;

theme.body 	= '<?php echo $theme_body; 	?>';
theme.bars 	= '<?php echo $theme_bars; 	?>';
theme.buttons 	= '<?php echo $theme_buttons; 	?>';
theme.controls 	= '<?php echo $theme_controls; 	?>';
theme.action 	= '<?php echo $theme_action; 	?>';
theme.active 	= '<?php echo $theme_active; 	?>';

// set the default theme of the loading message to be the same as the bars
$.mobile.loadingMessageTheme = theme.bars;

// set the style to use for the active button state (button hover will give the desired effect
$.mobile.activeBtnClass = 'ui-btn-hover-'+theme.active;

var recaptcha_public_key = '<?php echo $recaptcha_public_key; ?>';
var recaptcha_widget_name = 'recaptcha_widget';
var recaptcha_options = { theme: 'custom', custom_translations: <?php echo $recaptcha_translations; ?>, lang: '<?php echo $language; ?>' };
 
</script>
