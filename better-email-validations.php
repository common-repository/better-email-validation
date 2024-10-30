<?php
/*
Plugin Name: Better Email Validation
Plugin URI: http://www.techeach.com/wordpress-plugins
Description: Provides better email validations to protect your blog from spam comments and bots creating accounts.
Version: 1.0
Author: Vijay Sharma
Author Email: vijay@techeach.com
License:

  Copyright 2014 Vijay Sharma (vijay@techeach.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

class BetterEmailValidation {

	/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/
	const name = 'Better Email Validation';
	const slug = 'better-email-validation';
	
	/**
	 * Constructor
	 */
	function __construct() {
		define ('BEV_VERSION', '1.0.0');	
		//register an activation hook for the plugin
		register_activation_hook( __FILE__, array( &$this, 'install_better_email_validation' ) );
		
		require_once dirname(__FILE__) . '/includes/email_verifier.php';
		//Hook up to the init action
		add_action( 'init', array( &$this, 'init_better_email_validation' ) );
		
	}
  
	/**
	 * Runs when the plugin is activated
	 */  
	function install_better_email_validation() {
		// do not generate any output here
	}
  
	/**
	 * Runs when the plugin is initialized
	 */
	function init_better_email_validation() {
		// Setup localization
		load_plugin_textdomain( self::slug, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
			
		add_filter( 'is_email', array( &$this, 'validate_email' ) );
	}

	/**
	 * Function to Verify if Email is Valid
	 */
	public function validate_email( $emailID )
	{
		//If the format of the email itself is wrong return false without further checking
		if( !filter_var( $emailID, FILTER_VALIDATE_EMAIL ) )
			return FALSE;

		if ( function_exists('fsockopen')){
			 $fp = @fsockopen('smtp.live.com',25, $errno, $errstr, 2);
			 if ( $fp ) {
			 	$email_verifier = new Email_Verifier;
				return $email_verifier->verify( $emailID );
			 }
		}
		//If fsockopen doesn't exist or port 25 is block nothing much we can do :( and let WordPress handle it.
		return TRUE; 
	}
	
	  
} // end class
new BetterEmailValidation();