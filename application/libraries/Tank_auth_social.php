<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tank_auth Social
 *
 * Social Extension to Authentication library for Code Igniter.
 *
 * @package		Tank_auth_social
 * @author		David Mamber (http://github.com/sicsol/)
 * @version		1.0
 * @based on	Ilya Konyukhov (http://konyukhov.com/soft/)
 * @license		MIT License Copyright (c) 2008 Erick Hartanto
 */
class Tank_auth_social
{
	private $error = array();

	function __construct()
	{
		$this->ci =& get_instance();

		$this->ci->load->config('tank_auth_social', TRUE);
		$this->ci->load->model('tank_auth_social/users_social');

		$this->ci->load->library( 'facebook/facebook', array(
			'appId' => $this->ci->config->item('facebook_app_id','tank_auth_social'), 
			'secret' => $this->ci->config->item('facebook_app_secret','tank_auth_social')
		));
	}

	function facebookLoginURL( $redirect_url )
	{
		/* Configure Scope */
		$scope = '';
		if( $this->ci->config->item('facebook_user_email','tank_auth_social') )
		{
			$scope .= "email,";
		}

		if( $this->ci->config->item('facebook_offline','tank_auth_social') )
		{
			$scope .= "offline_access,";
		}
		
		$scope .= $this->ci->config->item('facebook_scope','tank_auth_social');
		
		return $this->ci->facebook->getLoginUrl( 
			array( 'redirect_uri' => $redirect_url, 
			'scope' => $scope ) 
		);
	}
	
	function has_account( $fb_id )
	{
		if( !is_null( $profile = $this->ci->users_social->get_user_by_facebook_id( $fb_id ) ) )
		{
			return $profile;
		}
		else
		{
			return NULL;
		}
	}
	
	
	
}

/* End of file Tank_auth_social.php */
/* Location: ./application/libraries/Tank_auth_social.php */