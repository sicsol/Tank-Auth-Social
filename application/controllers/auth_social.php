<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_social extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->load->library('tank_auth_social');
		$this->lang->load('tank_auth');
	}

	function index()
	{
		redirect('/auth/login/');
	}

	/**
	 * Login user via facebook
	 *
	 * @return void
	 */
	function fblogin()
	{
		// Get User Details
		$fb_id = $this->facebook->getUser();
		
		// User is found
		if( isset($fb_id) )
		{
			// Get Facebook User profile
			$user_profile = $this->facebook->api('/me');
			
			// User already has an account log them in
			if( !is_null( $profile = $this->tank_auth_social->has_account( $fb_id ) ) ) 
			{
				$data = $this->users->get_user_by_id($profile->user_id, 1);
				
				// Login user
				$this->session->set_userdata(array(
					'user_id'	=> $data->id,
					'username'	=> $data->username,
					'firstname'	=> $data->firstname,
					'lastname'	=> $data->lastname,
					'status'	=> "1",
				));
			}
			
			// This is a new user add them to users table and login
			else 
			{
				// Check if we are using email configuration
				$use_email = $this->config->item('facebook_user_email', 'tank_auth_social');
			
				// Create user
				if ( !is_null( $data = $this->tank_auth->create_user(
					'',
					$user_profile['first_name'],
					$user_profile['last_name'],
					$use_email ? $user_profile['email'] : '',
					'',
					FALSE )
				) ) 
				{
					// Check if offline access
					$offline = $this->config->item('facebook_offline', 'tank_auth_social');
				
					// Add Facebook Connect To Profile
					$this->users_social->create_facebook_connect( 
						$data['user_id'], 
						$fb_id, 
						$offline ? $this->input->get('code') : '' 
					); 

					// Login user
					$this->session->set_userdata(array(
						'user_id'	=> $data['user_id'],
						'username'	=> $data['username'],
						'firstname'	=> $data['firstname'],
						'lastname'	=> $data['lastname'],
						'status'	=> "1",
					));
					
				}
			}
			
			redirect('/');
		}
		else
		{
			redirect('/auth/login');
		}
	}

}

/* End of file auth_social.php */
/* Location: ./application/controllers/auth_social.php */