<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_inspection_part extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        return $this->db->get('inspection_part')->result_array();
    }

    public function getInspectionId($id)
    {
        return $this->db->get_where('inspection_part', ['id' => $id])->row_array();
    }

    public function insert_inspection_part($data)
    {
        return $this->db->insert('inspection_part', $data);
    }

    public function update_inspection_part($data, $id)
    {
        return $this->db->update('inspection_part', $data, ['id' => $id]);
    }

    public function delete_inspection_part($id)
    {
        return $this->db->delete('inspection_part', ['id' => $id]);
    }
}
