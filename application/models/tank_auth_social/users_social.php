<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Users Social
 *
 * This model is an add on to Tank Auth User Model
 * - user account data,
 * - user profiles
 *
 * @package	Tank_auth_social
 * @author	David Mamber (http://github.com/sicsol/)
 */
class Users_social extends CI_Model
{
	private $table_name			= 'users';			// user accounts
	private $profile_table_name	= 'user_profiles';	// user profiles

	function __construct()
	{
		parent::__construct();

		$ci =& get_instance();
		$this->table_name			= $ci->config->item('db_table_prefix', 'tank_auth').$this->table_name;
		$this->profile_table_name	= $ci->config->item('db_table_prefix', 'tank_auth').$this->profile_table_name;
	}

	/**
	 * Get user record by Facebook Id
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	function get_user_by_facebook_id( $fb_id )
	{
		$this->db->where('facebook_id', $fb_id);
		$query = $this->db->get($this->profile_table_name);
		
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Create Facebook Connect
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	function create_facebook_connect( $user_id, $fb_id, $token )
	{
		$this->db->set('facebook_id', $fb_id);
		$this->db->set('facebook_token', $token);
		
		$this->db->where('user_id', $user_id);

		$this->db->update( $this->profile_table_name );
	}

}

/* End of file users_social.php */
/* Location: ./application/models/tank_auth_social/users_social.php */