<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Determination_standard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->load->model(array('M_determination_standard'));
        is_login();
        is_access(1, [2]);
    }

    public function index()
    {
        $data = [
            'judul' => 'Determination Standar',
            'data' => $this->M_determination_standard->get()
        ];
        $this->load->view('determination_standard/index', $data);
    }

    public function add()
    {
        $check_same_name = $this->db->get_where('determination_standard', ['determination_name' => $this->input->post('determination_name')])->row_array();
        if ($check_same_name) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
            redirect('determination_standard');
        }
        $data = [
            'determination_name' => htmlspecialchars($this->input->post('determination_name', true)),

        ];
        $this->db->insert('determination_standard', $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
        redirect('determination_standard');
    }

    public function get_determination_standard($id)
    {
        $data = $this->M_determination_standard->getDeterminationId($id);
        echo json_encode($data);
    }

    public function update()
    {
        $id = $this->input->post('id');
        $data = [
            'determination_name' => htmlspecialchars($this->input->post('determination_name', true)),
        ];
        $check_same_name = $this->db->get_where('determination_standard', ['determination_name' => $this->input->post('determination_name')])->row_array();
        if ($check_same_name) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
            redirect('determination_standard');
        }
        $this->db->where('id', $id);
        $this->db->update('determination_standard', $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
        redirect('determination_standard');
    }

    public function delete($id)
    {
        $csrf = $this->input->get('_csrf');
        if ($csrf == $this->security->get_csrf_hash()) {
            $this->M_determination_standard->delete_determination_standard($id);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
            redirect('determination_standard');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
            redirect('determination_standard');
        }
    }
}
