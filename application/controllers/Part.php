<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Part extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->load->model(array('M_part'));
        is_login();
        is_access(1, [2]);
    }

    public function index()
    {
        $data = [
            'judul' => 'Part',
            'data' => $this->M_part->get()
        ];
        $this->load->view('part/index', $data);
    }

    public function add()
    {
        $data = [
            'part_name' => htmlspecialchars($this->input->post('part_name', true)),
        ];
        $check_same_name = $this->db->get_where('part', ['part_name' => $data['part_name']])->row_array();
        if ($check_same_name) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
            redirect('part');
        }
        $this->db->insert('part', $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
        redirect('part');
    }

    public function get_part($id)
    {
        $data = $this->M_part->getId($id);
        echo json_encode($data);
    }

    public function update()
    {
        $id = $this->input->post('id');
        $data = [
            'part_name' => htmlspecialchars($this->input->post('part_name', true)),
        ];
        $check_same_name = $this->db->get_where('part', ['part_name' => $data['part_name']])->row_array();
        if ($check_same_name) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
            redirect('part');
        }
        $this->db->where('id', $id);
        $this->db->update('part', $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
        redirect('part');
    }

    public function delete($id)
    {
        $csrf = $this->input->get('_csrf');
        if ($csrf == $this->security->get_csrf_hash()) {
            $this->M_part->delete_part($id);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
            redirect('part');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
            redirect('part');
        }
    }
}
