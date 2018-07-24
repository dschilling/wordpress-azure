<?php
/*
Plugin Name: Popup Maker
Plugin URI: http://www.activeconvert.com
Description: Beautiful popups and full screen overlays to increase leads and reduce bounce rates. To get started: 1) Click the "Activate" link to the left of this description, 2) Go to your Popup WP plugin Settings page, and click Get My API Key.
Version: 1.2.39
Author: ActiveConvert
Author URI: http://www.activeconvert.com/
*/

$acwp_domain = plugins_url();
add_action('init', 'acwp_init');
add_action('admin_notices', 'acwp_notice');
add_filter('plugin_action_links', 'acwp_plugin_actions', 10, 2);
add_action('wp_footer', 'acwp_insert',4);
add_action('admin_footer', 'acwpRedirect');
define('acwp_DASHBOARD_URL', "https://www.activeconvert.com/dashboard.do?wp=true");
define('acwp_SMALL_LOGO',plugin_dir_url( __FILE__ ).'ac-small-white.png');
function acwp_init() {
    if(function_exists('current_user_can') && current_user_can('manage_options')) {
        add_action('admin_menu', 'acwp_add_settings_page');
        add_action('admin_menu', 'acwp_create_menu');
    }
}
function acwp_insert() {

    global $current_user;
    if(strlen(get_option('acwp_widgetID')) == 32 ) {
	echo("\n<!-- Popup WP Plugin by ActiveConvert (www.activeconvert.com) -->\n<script src='//www.activeconvert.com/api/activeconvert.1.0.js#".get_option('acwp_widgetID')."' async='async'></script>\n");
    }
}

function acwp_notice() {
    if(!get_option('acwp_widgetID')) echo('<div class="error" style="padding:10px;"><p><strong><a style="text-decoration:none;border-radius:3px;color:white;padding:10px; ;background:#029dd6;border-color:#06b9fd #029dd6 #029dd6;margin-right:20px;"'.sprintf(__('href="%s">Activate your Popup WP account</a></strong>  Almost done - activate your account and say hello to beautiful popups and exit intents.' ), admin_url('options-general.php?page=popup-wp')).'</p></div>');
}

function acwp_plugin_actions($links, $file) {
    static $this_plugin;
    $acwp_domain = plugins_url();
    if(!$this_plugin) $this_plugin = plugin_basename(__FILE__);
    if($file == $this_plugin && function_exists('admin_url')) {
        $settings_link = '<a href="'.admin_url('options-general.php?page=popup-wp').'">'.__('Settings', $acwp_domain).'</a>';
        array_unshift($links, $settings_link);
    }
    return($links);
}

function acwp_add_settings_page() {
    function acwp_settings_page() {
        global $acwp_domain ?>
	<div class="wrap">
        <p style="margin-left:4px;font-size:18px;"><strong>Popup WP</strong> by <?php wp_nonce_field('update-options') ?>
		<a href="http://www.activeconvert.com/wordpress.jsp" target="_blank" title="ActiveConvert"><?php echo '<img src="'.plugins_url( 'activeconvert.png' , __FILE__ ).'" height="17" style="margin-bottom:-1px;"/>';?></a> increases conversions and reduces bounce rates by up to 40%.</p>
 		
	<div id="acwp_register" class="inside" style="padding: -30px 10px"></p>	
        	<div class="postbox" style="max-width:600px;height:50px;padding:30px;">
            	
		<div style="float:left">	
			<b>Activate Popup WP</b> <br>
			<p>Login or sign up now for a free trial.</p>
		</div>
		<div><a href='https://www.activeconvert.com/wordpress.jsp' class="right button button-primary" target="_blank">Get My API Key</a></div>
		</div>

   		<div class="postbox" style="max-width:600px;height:50px;padding:30px;">
            	<div style="float:left">
			<b>Enter Your API Key</b> <br>
			<p>If you already know your API Key.</p>
		</div>
		<div class="">
		<form id="saveSettings" method="post" action="options.php">
                   <?php wp_nonce_field('update-options') ?>
			<div style="float:right">
			<input type="text" name="acwp_widgetID" id="acwp_widgetID" placeholder="Your API Key" value="<?php echo(get_option('acwp_widgetID')) ?>" style="margin-right:10px;" />
                        <input type="hidden" name="page_options" value="acwp_widgetID" />
			<input type="hidden" name="action" value="update" />
                        <input type="submit" class="right button button-primary" name="acwp_submit" id="acwp_submit" value="<?php _e('Save Key', $acwp_domain) ?>" /> 
			</div>
                </form>
		</div>
               
            	
	</div>
	</div>
	

	<div id="acwp_registerComplete" class="inside" style="padding: -20px 10px;display:none;">
	<div class="postbox" style="max-width:600px;height:250px;padding:30px;padding-top:5px">
<h3 class=""><span id="sicp_noAccountSpan">Popup WP Settings</span></h3>
		<p>Customize your popup by selecting 'Customize' below. By default your popup is triggered when a visitor is leaving your site.  You can change this to trigger immediately, or after a configurable amount of time.</p>
		<p>
		<div style="text-align:center">
		
		<a href='https://www.activeconvert.com/dashboard.do?wp=true' class="button button-primary" target="_ac">Dashboard</a>&nbsp;
<a href='https://www.activeconvert.com/campaigns.do' class="button button-primary" target="_ac">Customize</a>&nbsp;
		
<a href='https://www.activeconvert.com/wppreview.do?wid=<?php echo(get_option('acwp_widgetID')) ?>' class="button button-primary" target="_ac">Popup Preview</a>&nbsp;
		<br><br><a id="changeWidget" class="" target="_blank">Enter Different API Key</a>&nbsp;
		</div>
		</p>* The popup is only triggered once per browser session.  Open a new browser window to test multiple times.

</div>
</div>
<script>
jQuery(document).ready(function($) {

var acwp_wid= $('#acwp_widgetID').val();
if (acwp_wid=='') 
{}
else
{

	$( "#acwp_enterwidget" ).hide();
	$( "#acwp_register" ).hide();
	$( "#acwp_registerComplete" ).show();
	$( "#acwp_noAccountSpan" ).html("Popup WP Plugin Settings");

}

$(document).on("click", "#acwp_inputSaveSettings", function () {
	$( "#saveDetailSettings" ).submit();
});

$(document).on("click", "#changeWidget", function () {
$( "#acwp_register" ).show();
$( "#acwp_inputSaveSettings" ).hide();
});


});
</script>
<?php }
$acwp_domain = plugins_url();
add_submenu_page('options-general.php', __('Popup WP', $acwp_domain), __('Popup WP', $acwp_domain), 'manage_options', 'popup-wp', 'acwp_settings_page');
}
function addacwpLink() {
$dir = plugin_dir_path(__FILE__);
include $dir . 'options.php';
}
function acwp_create_menu() {
  $optionPage = add_menu_page('Popup WP', 'Popup WP', 'administrator', 'acwp_dashboard', 'addacwpLink', plugins_url('ac-small-white.png', __FILE__));
}
function acwpRedirect() {
$redirectUrl = "https://www.activeconvert.com/dashboard.do?wp=true";
echo "<script> jQuery('a[href=\"admin.php?page=acwp_dashboard\"]').attr('href', '".$redirectUrl."').attr('target', '_blank') </script>";}
?>