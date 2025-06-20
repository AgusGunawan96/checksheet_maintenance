<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
    }


    public function index()
    {
        $data = [
            'judul' => 'Dashboard',
            'section' => $this->db->get('section')->num_rows(),
            'machine' => $this->db->get('machine')->num_rows(),
        ];
        $this->load->view('dashboard/index', $data);
    }

    public function logout()
    {
        $this->session->sess_destroy();

        redirect('login', 'refresh');
    }
}

/* End of file Dashboard.php */
