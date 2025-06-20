<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_user_management extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        $this->db->order_by('id_manager', 'asc');
        $this->db->order_by('id_supervisor', 'asc');
        return $this->db->get('view_users_management')->result_array();
    }

    public function getSectionId($id)
    {
        return $this->db->get_where('user_management', ['id' => $id])->row_array();
    }

    public function insert_user_management($data)
    {
        return $this->db->insert('user_management', $data);
    }

    public function update_user_management($data, $id)
    {
        return $this->db->update('user_management', $data, ['id' => $id]);
    }

    public function delete_user_management($id)
    {
        return $this->db->delete('user_management', ['id' => $id]);
    }
}
