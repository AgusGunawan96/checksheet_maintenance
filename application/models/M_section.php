<?php

// defined('BASEPATH') or exit('No direct script access allowed');

// class M_section extends CI_Model
// {

//     public function __construct()
//     {
//         parent::__construct();
//     }

//     public function get()
//     {
//         return $this->db->get('section')->result_array();
//     }

//     public function getSectionId($id)
//     {
//         return $this->db->get_where('section', ['id' => $id])->row_array();
//     }

//     public function insert_section($data)
//     {
//         return $this->db->insert('section', $data);
//     }

//     public function update_section($data, $id)
//     {
//         return $this->db->update('section', $data, ['id' => $id]);
//     }

//     public function delete_section($id)
//     {
//         return $this->db->delete('section', ['id' => $id]);
//     }
// }
defined('BASEPATH') or exit('No direct script access allowed');

class M_section extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        return $this->db->get('section')->result_array();
    }

    // Fungsi untuk mengambil data section dengan urutan sesuai abjad nama section
    public function get_sorted_sections()
    {
        $this->db->order_by('section_name', 'ASC'); // Mengurutkan berdasarkan nama section (ascending)
        return $this->db->get('section')->result_array();
    }

    public function getSectionId($id)
    {
        return $this->db->get_where('section', ['id' => $id])->row_array();
    }

    public function insert_section($data)
    {
        return $this->db->insert('section', $data);
    }

    public function update_section($data, $id)
    {
        return $this->db->update('section', $data, ['id' => $id]);
    }

    public function delete_section($id)
    {
        return $this->db->delete('section', ['id' => $id]);
    }
}
?>
