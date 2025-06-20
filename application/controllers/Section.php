<?php

// defined('BASEPATH') or exit('No direct script access allowed');

// class Section extends CI_Controller
// {
//     public function __construct()
//     {
//         parent::__construct();
//         //Do your magic here
//         $this->load->model(array('M_section'));
//         is_login();
//         is_access(1, [2]);
//     }

//     // public function index()
//     // {
//     //     $data = [
//     //         'judul' => 'Section',
//     //         'data' => $this->M_section->get()
//     //     ];
//     //     $this->load->view('section/index', $data);
//     // }

//     public function index()
// {
//     $data = [
//         'judul' => 'Section',
//         'data' => $this->M_section->get_sorted_sections() // Mengambil data section yang sudah diurutkan
//     ];
//     $this->load->view('section/index', $data);
// }


//     public function add()
//     {
//         $data = [
//             'section_name' => htmlspecialchars($this->input->post('section_name', true)),
//             'rank' => htmlspecialchars($this->input->post('rank')),
//         ];
//         $check_same_name = $this->db->get_where('section', ['section_name' => $data['section_name'], 'rank' => $data['rank']])->row_array();
//         if ($check_same_name) {
//             $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
//             redirect('section');
//         }
//         $this->db->insert('section', $data);
//         $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
//         redirect('section');
//     }

//     public function get_section($id)
//     {
//         $data = $this->M_section->getSectionId($id);
//         echo json_encode($data);
//     }

//     public function update()
//     {
//         $id = $this->input->post('id');
//         $data = [
//             'section_name' => htmlspecialchars($this->input->post('section_name', true)),
//             'rank' => htmlspecialchars($this->input->post('rank')),
//         ];
//         $check_same_name = $this->db->get_where('section', ['section_name' => $data['section_name'], 'rank' => $data['rank']])->row_array();
//         if ($check_same_name) {
//             $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
//             redirect('section');
//         }
//         $this->db->where('id', $id);
//         $this->db->update('section', $data);
//         $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
//         redirect('section');
//     }

//     public function delete($id)
//     {
//         $csrf = $this->input->get('_csrf');
//         if ($csrf == $this->security->get_csrf_hash()) {
//             $this->M_section->delete_section($id);
//             $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
//             redirect('section');
//         } else {
//             $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
//             redirect('section');
//         }
//     }
// }



// defined('BASEPATH') or exit('No direct script access allowed');

// class Section extends CI_Controller
// {
//     public function __construct()
//     {
//         parent::__construct();
//         //Do your magic here
//         $this->load->model(array('M_section'));
//         is_login();
//         is_access(1, [2]);
//     }

//     public function index()
//     {
//         $data = [
//             'judul' => 'Section',
//             'data' => $this->M_section->get_sorted_sections() // Mengambil data section yang sudah diurutkan
//         ];
//         $this->load->view('section/index', $data);
//     }

//     public function add()
//     {
//         $data = [
//             'section_name' => htmlspecialchars($this->input->post('section_name', true)),
//             'rank' => htmlspecialchars($this->input->post('rank')),
//             'category' => htmlspecialchars($this->input->post('category'))
//         ];
//         $check_same_name = $this->db->get_where('section', ['section_name' => $data['section_name'], 'rank' => $data['rank'], 'category' => $data['category']])->row_array();
//         if ($check_same_name) {
//             $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
//             redirect('section');
//         }
//         $this->db->insert('section', $data);
//         $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
//         redirect('section');
//     }

//     public function get_section($id)
//     {
//         $data = $this->M_section->getSectionId($id);
//         echo json_encode($data);
//     }

//     public function update()
//     {
//         $id = $this->input->post('id');
//         $data = [
//             'section_name' => htmlspecialchars($this->input->post('section_name', true)),
//             'rank' => htmlspecialchars($this->input->post('rank')),
//             'category' => htmlspecialchars($this->input->post('category'))
//         ];
//         $check_same_name = $this->db->get_where('section', ['section_name' => $data['section_name'], 'rank' => $data['rank'], 'category' => $data['category']])->row_array();
//         if ($check_same_name) {
//             $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
//             redirect('section');
//         }
//         $this->db->where('id', $id);
//         $this->db->update('section', $data);
//         $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
//         redirect('section');
//     }

//     public function delete($id)
//     {
//         $csrf = $this->input->get('_csrf');
//         if ($csrf == $this->security->get_csrf_hash()) {
//             $this->M_section->delete_section($id);
//             $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
//             redirect('section');
//         } else {
//             $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
//             redirect('section');
//         }
//     }
// }

defined('BASEPATH') or exit('No direct script access allowed');

class Section extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_section'));
        is_login();
        is_access(1, [2]); // Misalnya ini untuk validasi akses, sesuaikan dengan kebutuhan Anda
    }

    public function index()
    {
        $data = [
            'judul' => 'Section',
            'data' => $this->M_section->get_sorted_sections() // Mendapatkan data section yang sudah diurutkan
        ];
        $this->load->view('section/index', $data);
    }

    public function add()
    {
        $data = [
            'section_name' => htmlspecialchars($this->input->post('section_name', true)),
            'rank' => htmlspecialchars($this->input->post('rank')),
            'category' => htmlspecialchars($this->input->post('category'))
        ];
        $check_same_name = $this->db->get_where('section', [
            'section_name' => $data['section_name'],
            'rank' => $data['rank'],
            'category' => $data['category']
        ])->row_array();
        
        if ($check_same_name) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
            redirect('section');
        }

        $this->db->insert('section', $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
        redirect('section');
    }

    public function get_section($id)
    {
        $data = $this->M_section->getSectionId($id);
        echo json_encode($data);
    }

    public function update()
    {
        $id = $this->input->post('id');
        $data = [
            'section_name' => htmlspecialchars($this->input->post('section_name', true)),
            'rank' => htmlspecialchars($this->input->post('rank')),
            'category' => htmlspecialchars($this->input->post('category'))
        ];
        
        $check_same_name = $this->db->get_where('section', [
            'section_name' => $data['section_name'],
            'rank' => $data['rank'],
            'category' => $data['category']
        ])->row_array();
        
        if ($check_same_name) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
            redirect('section');
        }

        $this->db->where('id', $id);
        $this->db->update('section', $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
        redirect('section');
    }

    public function delete($id)
    {
        $csrf = $this->input->get('_csrf');
        if ($csrf == $this->security->get_csrf_hash()) {
            $this->M_section->delete_section($id);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
            redirect('section');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
            redirect('section');
        }
    }
}
