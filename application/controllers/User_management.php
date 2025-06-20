<?php

defined('BASEPATH') or exit('No direct script access allowed');

class user_management extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_user_management');

        is_login();
        is_access(1, [2]);
    }

    public function index()
    {
        $data = [
            'judul' => 'User Management',
            'data' => $this->M_user_management->get_all(),
            'managers' => $this->db->get_where('users', ['level' => 4])->result_array(),
            'supervisors' => $this->db->get_where('users', ['level' => 3])->result_array(),
            'inspectors' => $this->db->get_where('users', ['level' => 2])->result_array(),
        ];
        $this->load->view('user_management/index', $data);
    }

    function add()
    {
        $data = [
            'manager' => $this->input->post('manager'),
            'supervisor' => $this->input->post('supervisor'),
            'inspector' => $this->input->post('inspector'),
        ];
        $check_same_name = $this->db->get_where('user_management', ['inspector' => $data['inspector']])->row_array();
        if ($check_same_name) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">User has been added to Management!</div>');
            redirect('user_management');
        }
        $this->M_user_management->insert_user_management($data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">User has been added!</div>');
        redirect('user_management');
    }

    public function delete($id)
    {
        $csrf = $this->input->get('_csrf');
        if ($csrf == $this->security->get_csrf_hash()) {
            $this->db->where('id', $id);
            $this->db->delete('user_management');
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
            redirect('user_management');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
            redirect('user_management');
        }
    }
}

/* End of file user_management.php */
