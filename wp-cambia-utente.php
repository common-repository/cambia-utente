<?php
/**
 * @package Cambia_Utente
 * @version 1.4
 */
/*
Plugin Name: Cambia Utente
Plugin URI: http://wordpress.org/plugins/cambia-utente/
Description: Questo plugin permette di simulare e verificare l'accesso dei clienti di woocommerce. Ottimo strumento per controllare gli ordini effettivamente attivi come i download.
Author: DevFabio
Version: 1.4
Author URI: http://inventyourtrade.it/
*/


// create custom plugin settings menu
add_action('admin_menu', 'cambia_utente_plugin_create_menu');

function cambia_utente_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Cambia Utente Plugin Settings', 'Cambia Utente', 'administrator', __FILE__, 'cambia_utente_plugin_settings_page' , 'dashicons-groups' );

	//call register settings function
	
}

add_action( 'init', 'cambia_utente_action_init'  );
function cambia_utente_action_init(){
	$current_user = wp_get_current_user();
	
	if (user_can( $current_user, 'administrator' ) and current_user_can('administrator' )) {
		
		$blogusers = get_users( 'blog_id=1&orderby=nicename' );//&role=customer
		
		if(isset($_POST['IDUtenteWP']) and wp_verify_nonce($_POST['nonce'], 'wp_cambia_utente'))
		{	$user_id = (int) sanitize_key($_POST['IDUtenteWP']);	
			$user = get_user_by( 'id', $user_id ); 
			if( $user ) {
				wp_set_current_user( $user_id, $user->user_login );
				wp_set_auth_cookie( $user_id );
				do_action( 'wp_login', $user->user_login, $user );				
			}
		}
	}
}


function cambia_utente_plugin_settings_page() {
?>
<div class="wrap cambia_utente">
<h1>Cambia Utente</h1>

<?php 
$current_user = wp_get_current_user();
	
if (user_can( $current_user, 'administrator' ) and current_user_can('administrator' )) {
	
	$blogusers = get_users( 'blog_id=1&orderby=nicename' );//&role=customer
	
	
		echo "<div style=\"width:320px; margin-left:auto; margin-right:auto;\"><ul>";
		foreach ( $blogusers as $user ) {
			echo '<li><span class="utente"><form style="    border-bottom: grey 1px solid;    padding-bottom: 2px;    margin-bottom: 2px;" method="POST"><input type="hidden" name="IDUtenteWP" value="'.$user->ID .'" >'.esc_html($user->nicename) .' '. esc_html( $user->user_email  ) . ' <input type="hidden" name="nonce" value="'.wp_create_nonce('wp_cambia_utente').'" /><input type="submit" value="Accedi" style="float: right;" /></form></span></li>';	}
		echo "</ul><div>";
		
}else{
	echo esc_html("KO Amministratore non Loggato correttamente! ")."<a href=\"".esc_html(get_site_url())."/account\" target=\"_blank\">".esc_html("Torna al sito")."</a>";
	}

?><style>
.cambia_utente li{
	list-style:none;
    line-height: 35px;
	}
.cambia_utente li:nth-child(even) {
    background-color:#F0E9E9;
}</style>
</div>
<?php } 

// This just echoes the chosen line, we'll position it later
/*function cambia_utente_benvenuto() {
	$benvenuto = "Grazie per aver installato il plugin Cambia Utente";
	echo "<p id='cUtente'>".esc_html($benvenuto)."</p>";
}*/

// Now we set that function up to execute when the admin_notices action is called
/*add_action( 'admin_notices', 'cambia_utente_benvenuto' );*/

// We need some CSS to position the paragraph
/*function cambia_utente_css() {
	// This makes sure that the positioning is also good for right-to-left languages
	$x = is_rtl() ? 'left' : 'right';

	echo "
	<style type='text/css'>
	#cUtente {
		float: $x;
		padding-$x: 15px;
		padding-top: 5px;		
		margin: 0;
		font-size: 11px;
	}
	</style>
	";
}

add_action( 'admin_head', 'cambia_utente_css' );*/

?>
