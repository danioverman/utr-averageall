<?php
/*
Plugin Name: Barrel Roll Uptime Robot
Plugin URI: 
Description: Barrel Roll Uptime Robot Plugin
Version: 0.1
License: GPLv2
Author: Barrel Roll
Author URI: gobarrelroll.com
*/

//Define the plugin
define( 'utrbr_PLUGIN_DIR', dirname( plugin_basename( __FILE__ ) ) );
define( 'utrbr_PLUGIN_URL', plugins_url( dirname( plugin_basename( __FILE__ ) ) ) );

//Admin Menu
add_action('admin_menu', 'utrbr_create_menu');
function utrbr_create_menu() {
	// or create options menu page
	add_options_page(__('Uptime Robot Setup'),__('Uptime Robot Setup'), 'manage_options', utrbr_PLUGIN_DIR.'/utrbr_settings.php');
}

//Shortcode
add_shortcode('utrbr', 'utrbr_shortcode');
//Hook for Activation
register_activation_hook( __FILE__, 'utrbr_activate' );
//Hook for Deactivation
register_deactivation_hook( __FILE__, 'utrbr_deactivate' );

add_action('admin_init', 'utrbr_register_settings' );
function utrbr_register_settings() {
	//register the settings for the admin page
	register_setting( 'utrbr-settings', 'utrbr-apikey');			
}

function utrbr_shortcode( $atts ) {
	$json = utrbr_get_data();
          
	foreach ($json->monitors as $monitor->custom_uptime_ratios) {
        $avg_ratio = array_sum($custom_uptime_ratio) / count($custom_uptime_ratio);
    }
    echo $avg_ratio;
}

//Activate
function utrbr_activate() {
	//do not generate any output here
}

//Deactivate
function utrbr_deactivate() {
	// do not generate any output here
}


function utrbr_get_data() {
		// set up the request
		$api_key = get_option( 'utrbr-apikey' ); 
		// request via cURL
		$curl = curl_init();
		curl_setopt_array($curl, array(
  			CURLOPT_URL => "https://api.uptimerobot.com/v2/getMonitors",
  			CURLOPT_RETURNTRANSFER => true,
  			CURLOPT_ENCODING => "",
  			CURLOPT_MAXREDIRS => 10,
  			CURLOPT_TIMEOUT => 30,
  			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  			CURLOPT_CUSTOMREQUEST => "POST",
  			CURLOPT_POSTFIELDS => "api_key=".$api_key."&format=json&logs=0&custom_uptime_ratio=30",

		));
		$responseJSON = curl_exec($curl);
		$json = json_decode($responseJSON);
		curl_close($curl);

        return $json;
}
?>
