<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_master extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_user()
    {
        return $this->db->get('users')->result_array();
    }

    public function getUserId($id)
    {
        return $this->db->get_where('users', ['id_user' => $id])->row_array();
    }

    public function insert_user($data)
    {
        return $this->db->insert('users', $data);
    }

    public function update_user($data, $id)
    {
        return $this->db->update('users', $data, ['id_user' => $id]);
    }

    public function delete_user($id)
    {
        return $this->db->delete('users', ['id_user' => $id]);
    }

    public function get_level()
    {
        return $this->db->get('user_level')->result_array();
    }
    public function get_profil()
    {
        return $this->db->get('users', ['id_user' => $this->session->userdata('id_user')])->row_array();
    }
}

/* End of file M_master.php */
