<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_part extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        return $this->db->get('part')->result_array();
    }

    public function getId($id)
    {
        return $this->db->get_where('part', ['id' => $id])->row_array();
    }

    public function insert_part($data)
    {
        return $this->db->insert('part', $data);
    }

    public function update_part($data, $id)
    {
        return $this->db->update('part', $data, ['id' => $id]);
    }

    public function delete_part($id)
    {
        return $this->db->delete('part', ['id' => $id]);
    }
}
