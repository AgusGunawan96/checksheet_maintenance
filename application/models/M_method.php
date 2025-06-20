<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_method extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        return $this->db->get('method')->result_array();
    }

    public function getMethodId($id)
    {
        return $this->db->get_where('method', ['id' => $id])->row_array();
    }

    public function insert_method($data)
    {
        return $this->db->insert('method', $data);
    }

    public function update_method($data, $id)
    {
        return $this->db->update('method', $data, ['id' => $id]);
    }

    public function delete_method($id)
    {
        return $this->db->delete('method', ['id' => $id]);
    }
}
