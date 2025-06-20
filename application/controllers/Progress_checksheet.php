<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Drawing;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Progress_checksheet extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_checksheet');
        $this->load->model('M_master');
        is_login();
    }

    public function index()
    {
        $data = [
            'judul' => 'Inspection Progress Checksheet',
            'user' => $this->M_checksheet->getUser(),
            'section' => $this->M_checksheet->getSection(),
            // 'data' => $this->M_checksheet->getProgressChecksheet(),
        ];
        $this->load->view('progress_checksheet/index', $data);
    }

    function get_progress_checksheet()
    {
        $tanggal = $this->input->post('tgl_checksheet');
        //convert $tanggal (array) to seperated comma
        // $tanggal = implode(",", $tanggal);
        $user_id = $this->input->post('user_id');
        // $user_id = implode(",", $user_id);
        $section = $this->input->post('section');
        $level = $this->session->userdata('level');
        if ($level == 2) {
            $user_id = $this->session->userdata('id_user');
        }
        $data = [
            'data' => $this->M_checksheet->getProgressChecksheet($tanggal, $user_id, $section),
        ];
        $this->load->view('progress_checksheet/report_progress_checksheet', $data);
    }

    function get_tgl_checksheet()
    {
        $user_id = $this->input->post('user_id');
        // $user_id = $user_ids;
        $tgl_checksheet = $this->M_checksheet->getTglChecksheet($user_id);
        echo json_encode($tgl_checksheet);
    }

    function export_excel()
    {
        $tanggal = $this->input->post('tgl_checksheet');
        //convert $tanggal (array) to seperated comma
        // $tanggal = implode(",", $tanggal);
        $user_id = $this->input->post('user_id');
        // $user_id = implode(",", $user_id);
        $section = $this->input->post('section');
        $level = $this->session->userdata('level');
        if ($level == 2) {
            $user_id = $this->session->userdata('id_user');
        }
        $data = $this->M_checksheet->getProgressChecksheet($tanggal, $user_id, $section);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator('Checksheet Maintenance')
            ->setLastModifiedBy('Checksheet Maintenance')
            ->setTitle('Checksheet')
            ->setSubject('Checksheet')
            ->setDescription('Checksheet, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Checksheet');

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Lembar Informasi Hasil Pengecekan Mesin Produksi')
            ->setCellValue('A3', 'Line        : ' . $this->M_checksheet->getSectionbyId($data[0]['section_id'])['section_name'])
            ->setCellValue('A4', 'Tanggal :' . date('d F Y', strtotime($tgl_checksheet)));

        $user = $this->M_checksheet->get_ttd_user($user_id);
    }
}

/* End of file progress_checksheet.php */
