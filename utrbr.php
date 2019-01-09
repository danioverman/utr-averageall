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

//Define some stuff
define( 'utrbr_PLUGIN_DIR', dirname( plugin_basename( __FILE__ ) ) );
define( 'utrbr_PLUGIN_URL', plugins_url( dirname( plugin_basename( __FILE__ ) ) ) );
load_plugin_textdomain( 'utrbr', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

//Admin Menu
add_action('admin_menu', 'utrbr_create_menu');
function utrbr_create_menu() {

	add_options_page(__('Uptime Robot Setup'),__('Uptime Robot Setup'), 'manage_options', utrbr_PLUGIN_DIR.'/utrbr_settings.php');
}

//Add Shortcode
add_shortcode('utrbr', 'utrbr_shortcode');
//Hook for Activation
register_activation_hook( __FILE__, 'utrbr_activate' );
//Hook for Deactivation
register_deactivation_hook( __FILE__, 'utrbr_deactivate' );

add_action('admin_init', 'utrbr_register_settings' );
function utrbr_register_settings() {
	//register API Key setting
	register_setting( 'utrbr-settings', 'utrbr-apikey');

}

function utrbr_shortcode( $atts ) {
	$json = pum_get_data();
	$sum= $count=0;

	foreach ($json->monitors as $monitor) {
			$sum+=$monitor->all_time_uptime_ratio;
			$count++;
	}
	$br='<span class="utraverage">'.round($sum/$count, 3).'</span>';
	return $br; 

}

//Activate
function utrbr_activate() {
	// no output here
}

//Deactivate
function utrbr_deactivate() {
	// no output here
}



function utrbr_get_data() {

		// set up request
		$api_key = get_option( 'utrbr-apikey' ); // My Settings > API Information > Monitor-specific API keys > Select a Monitor > Click to Create One
		//$url = "https://api.uptimerobot.com/v2/getMonitors?api_key=" . $api_key . "&logs=1&showTimezone=1&format=json&noJsonCallback=1";

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
  			CURLOPT_POSTFIELDS =>"api_key=".$api_key."&format=json&logs=0&monitor>all_time_uptime_ratio",
  			CURLOPT_HTTPHEADER => array(
    			"cache-control: no-cache",
    			"content-type: application/x-www-form-urlencoded"
  			),
		));
		$responseJSON = curl_exec($curl);
		$json = json_decode($responseJSON);

		curl_close($curl);

	return $json;
}


?>
