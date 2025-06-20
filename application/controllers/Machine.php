<?php

// defined('BASEPATH') or exit('No direct script access allowed');

// class Machine extends CI_Controller
// {
//     public function __construct()
//     {
//         parent::__construct();
//         //Do your magic here
//         $this->load->model(array('M_machine'));
//         is_login();
//         is_access(1, [2]);
//     }

//     public function index()
//     {
//         $data = [
//             'judul' => 'Machine',
//             'data' => $this->M_machine->get()
//         ];
//         $this->load->view('machine/index', $data);
//     }

//     public function add()
//     {
//         $data = [
//             'machine_name' => htmlspecialchars($this->input->post('machine_name', true)),
//             'equipment_no' => htmlspecialchars($this->input->post('equipment_no')),
//             'cycle' => htmlspecialchars($this->input->post('cycle')),
//             'document_no' => htmlspecialchars($this->input->post('document_no')),
//         ];
//         $check_same_name = $this->db->get_where('machine', ['machine_name' => $data['machine_name']])->row_array();
//         if ($check_same_name) {
//             $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
//             redirect('machine');
//         }
//         $this->db->insert('machine', $data);
//         $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
//         redirect('machine');
//     }

//     public function get_machine($id)
//     {
//         $data = $this->M_machine->getMachineId($id);
//         echo json_encode($data);
//     }

//     public function update()
//     {
//         $id = $this->input->post('id');
//         $data = [
//             'machine_name' => htmlspecialchars($this->input->post('machine_name', true)),
//             'equipment_no' => htmlspecialchars($this->input->post('equipment_no')),
//             'cycle' => htmlspecialchars($this->input->post('cycle')),
//             'document_no' => htmlspecialchars($this->input->post('document_no')),
//         ];
//         $check_same_name = $this->db->get_where('machine', ['machine_name' => $data['machine_name']])->row_array();
//         if ($check_same_name) {
//             $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
//             redirect('machine');
//         }
//         $this->db->where('id', $id);
//         $this->db->update('machine', $data);
//         $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
//         redirect('machine');
//     }

//     public function delete($id)
//     {
//         $csrf = $this->input->get('_csrf');
//         if ($csrf == $this->security->get_csrf_hash()) {
//             $this->M_machine->delete_machine($id);
//             $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
//             redirect('machine');
//         } else {
//             $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
//             redirect('machine');
//         }
//     }
// }

defined('BASEPATH') or exit('No direct script access allowed');

class Machine extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_machine'));
        is_login();
        is_access(1, [2]);
    }

    public function index()
    {
        $data = [
            'judul' => 'Machine',
            'data' => $this->M_machine->get()
        ];
        $this->load->view('machine/index', $data);
    }

    // public function add()
    // {
    //     $data = [
    //         'machine_name' => htmlspecialchars($this->input->post('machine_name', true)),
    //         'equipment_no' => htmlspecialchars($this->input->post('equipment_no')),
    //         'cycle' => htmlspecialchars($this->input->post('cycle')),
    //         'document_no' => htmlspecialchars($this->input->post('document_no')),
    //     ];

    //     $check_same_entry = $this->db->get_where('machine', [
    //         'machine_name' => $data['machine_name'],
    //         'equipment_no' => $data['equipment_no'],
    //         'document_no' => $data['document_no']
    //     ])->row_array();

    //     if ($check_same_entry) {
    //         $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Entry sudah ada!</div>');
    //         redirect('machine');
    //     }

    //     $this->db->insert('machine', $data);
    //     $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
    //     redirect('machine');
    // }

    public function add()
{
    $data = [
        'machine_name' => $this->input->post('machine_name', true),
        'equipment_no' => $this->input->post('equipment_no'),
        'cycle' => $this->input->post('cycle'),
        'document_no' => $this->input->post('document_no'),
    ];

    $check_same_entry = $this->db->get_where('machine', [
        'machine_name' => $data['machine_name'],
        'equipment_no' => $data['equipment_no'],
        'document_no' => $data['document_no']
    ])->row_array();

    if ($check_same_entry) {
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Entry sudah ada!</div>');
        redirect('machine');
    }

    $this->db->insert('machine', $data);
    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
    redirect('machine');
}

public function update()
{
    $id = $this->input->post('id');
    $data = [
        'machine_name' => $this->input->post('machine_name', true),
        'equipment_no' => $this->input->post('equipment_no'),
        'cycle' => $this->input->post('cycle'),
        'document_no' => $this->input->post('document_no'),
    ];

    $check_same_entry = $this->db->get_where('machine', [
        'machine_name' => $data['machine_name'],
        'equipment_no' => $data['equipment_no'],
        'document_no' => $data['document_no'],
        'id !=' => $id
    ])->row_array();

    if ($check_same_entry) {
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Entry sudah ada!</div>');
        redirect('machine');
    }

    $this->db->where('id', $id);
    $this->db->update('machine', $data);
    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
    redirect('machine');
}


    public function get_machine($id)
    {
        $data = $this->M_machine->getMachineId($id);
        echo json_encode($data);
    }

    // public function update()
    // {
    //     $id = $this->input->post('id');
    //     $data = [
    //         'machine_name' => htmlspecialchars($this->input->post('machine_name', true)),
    //         'equipment_no' => htmlspecialchars($this->input->post('equipment_no')),
    //         'cycle' => htmlspecialchars($this->input->post('cycle')),
    //         'document_no' => htmlspecialchars($this->input->post('document_no')),
    //     ];

    //     $check_same_entry = $this->db->get_where('machine', [
    //         'machine_name' => $data['machine_name'],
    //         'equipment_no' => $data['equipment_no'],
    //         'document_no' => $data['document_no'],
    //         'id !=' => $id
    //     ])->row_array();

    //     if ($check_same_entry) {
    //         $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Entry sudah ada!</div>');
    //         redirect('machine');
    //     }

    //     $this->db->where('id', $id);
    //     $this->db->update('machine', $data);
    //     $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
    //     redirect('machine');
    // }

    public function delete($id)
    {
        $csrf = $this->input->get('_csrf');
        if ($csrf == $this->security->get_csrf_hash()) {
            $this->M_machine->delete_machine($id);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
            redirect('machine');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
            redirect('machine');
        }
    }
}
