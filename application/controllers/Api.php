<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_checksheet');
        // is_login();
    }

    public function index()
    {
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'success', 'message' => 'Welcome to the API'));
    }

    function table($id)
    {
        $data = $this->M_checksheet->getDetail($id);
        $this->load->view('checksheet/table', ['data' => $data]);
    }
    function get_tabel_report($tgl_checksheet, $user_id)
    {
        $data = [
            'data' => $this->M_checksheet->getDataAbnormal($tgl_checksheet, $user_id),
        ];
        $this->load->view('checksheet/report_abnormal_tabel_export', $data);
    }
}

/* End of file Api.php */
