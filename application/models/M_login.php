<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_login extends CI_Model
{

    public function getUser($username)
    {
        return $this->db->get_where('users', ['username' => $username])->row_array();
    }

    public function session_login($ip_address)
    {
        $data = [
            'id_user' => $this->session->userdata('id_user'),
            'agent' => $this->agent->agent_string() . ' ' . $ip_address,
        ];
        $this->db->insert('user_log', $data);
    }
}
    
/* End of file M_login.php */