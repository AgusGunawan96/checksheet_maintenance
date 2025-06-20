<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_item extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        return $this->db->get('item')->result_array();
    }

    public function getItemId($id)
    {
        return $this->db->get_where('item', ['id' => $id])->row_array();
    }

    public function insert_item($data)
    {
        return $this->db->insert('item', $data);
    }

    public function update_item($data, $id)
    {
        return $this->db->update('item', $data, ['id' => $id]);
    }

    public function delete_item($id)
    {
        return $this->db->delete('item', ['id' => $id]);
    }
}
