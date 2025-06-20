<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Method extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->load->model(array('M_method'));
        is_login();
        is_access(1, [2]);
    }

    public function index()
    {
        $data = [
            'judul' => 'Method',
            'data' => $this->M_method->get()
        ];
        $this->load->view('method/index', $data);
    }

    public function add()
    {
        $data = [
            'method_name' => htmlspecialchars($this->input->post('method_name', true)),
        ];
        $check_same_name = $this->db->get_where('method', ['method_name' => $data['method_name']])->row_array();
        if ($check_same_name) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
            redirect('method');
        }
        $this->db->insert('method', $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
        redirect('method');
    }

    public function get_method($id)
    {
        $data = $this->M_method->getMethodId($id);
        echo json_encode($data);
    }

    public function update()
    {
        $id = $this->input->post('id');
        $data = [
            'method_name' => htmlspecialchars($this->input->post('method_name', true)),
        ];
        $check_same_name = $this->db->get_where('method', ['method_name' => $data['method_name']])->row_array();
        if ($check_same_name) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
            redirect('method');
        }
        $this->db->where('id', $id);
        $this->db->update('method', $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
        redirect('method');
    }

    public function delete($id)
    {
        $csrf = $this->input->get('_csrf');
        if ($csrf == $this->security->get_csrf_hash()) {
            $this->M_method->delete_method($id);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
            redirect('method');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
            redirect('method');
        }
    }
}
