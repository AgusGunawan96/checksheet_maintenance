<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_determination_standard extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        return $this->db->get('determination_standard')->result_array();
    }

    public function getDeterminationId($id)
    {
        return $this->db->get_where('determination_standard', ['id' => $id])->row_array();
    }

    public function insert_determination_standard($data)
    {
        return $this->db->insert('determination_standard', $data);
    }

    public function update_determination_standard($data, $id)
    {
        return $this->db->update('determination_standard', $data, ['id' => $id]);
    }

    public function delete_determination_standard($id)
    {
        return $this->db->delete('determination_standard', ['id' => $id]);
    }
}
