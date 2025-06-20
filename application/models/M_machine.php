<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_machine extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        return $this->db->get('machine')->result_array();
    }

    public function getMachineId($id)
    {
        return $this->db->get_where('machine', ['id' => $id])->row_array();
    }

    public function insert_machine($data)
    {
        return $this->db->insert('machine', $data);
    }

    public function update_machine($data, $id)
    {
        return $this->db->update('machine', $data, ['id' => $id]);
    }

    public function delete_machine($id)
    {
        return $this->db->delete('machine', ['id' => $id]);
    }
}
