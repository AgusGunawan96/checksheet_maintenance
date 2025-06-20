<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Inspection_part extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->load->model(array('M_inspection_part'));
        is_login();
        is_access(1, [2]);
        require_once APPPATH . 'third_party/SimpleXLSX.php'; // Path ke file SimpleXLSX.php
    }

    public function index()
    {
        $data = [
            'judul' => 'Inspection Part',
            'data' => $this->M_inspection_part->get()
        ];
        $this->load->view('inspection_part/index', $data);
    }

    public function add()
    {
        $data = [
            'inspection_part_name' => htmlspecialchars($this->input->post('inspection_part_name', true)),
        ];
        $check_same_name = $this->db->get_where('inspection_part', ['inspection_part_name' => $data['inspection_part_name']])->row_array();
        if ($check_same_name) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
            redirect('inspection_part');
        }
        $this->db->insert('inspection_part', $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
        redirect('inspection_part');
    }

    public function get_inspection_part($id)
    {
        $data = $this->M_inspection_part->getInspectionId($id);
        echo json_encode($data);
    }

    public function update()
    {
        $id = $this->input->post('id');
        $data = [
            'inspection_part_name' => htmlspecialchars($this->input->post('inspection_part_name', true)),
        ];
        $check_same_name = $this->db->get_where('inspection_part', ['inspection_part_name' => $data['inspection_part_name']])->row_array();
        if ($check_same_name) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
            redirect('inspection_part');
        }
        $this->db->where('id', $id);
        $this->db->update('inspection_part', $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
        redirect('inspection_part');
    }

    public function delete($id)
    {
        $csrf = $this->input->get('_csrf');
        if ($csrf == $this->security->get_csrf_hash()) {
            $this->M_inspection_part->delete_inspection_part($id);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
            redirect('inspection_part');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
            redirect('inspection_part');
        }
    }

    public function upload_excel() {
        // Memuat file SimpleXLSX
        require_once APPPATH . 'third_party/SimpleXLSX.php';
    
        // Konfigurasi upload file
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'xlsx|xls';
        $config['max_size'] = 2048; // 2MB
        $this->load->library('upload', $config); // Pastikan library upload dimuat
    
        // Debugging: Memeriksa jika folder uploads ada dan dapat ditulisi
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true); // Buat folder jika belum ada
        }
    
        if (!$this->upload->do_upload('file_excel')) { // Pastikan nama file input konsisten
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error', $error);
            redirect('inspection_part');
        } else {
            $file_data = $this->upload->data();
            $file_path = './uploads/' . $file_data['file_name'];
    
            // Debugging: Periksa path file
            echo "File Path: " . $file_path;
    
            // Load file Excel menggunakan SimpleXLSX
            if (class_exists('Shuchkin\SimpleXLSX')) {
                $xlsx = \Shuchkin\SimpleXLSX::parse($file_path);
                if ($xlsx) {
                    $data = $xlsx->rows();
                    
                    // Skip header row
                    array_shift($data);
    
                    foreach ($data as $row) {
                        $item_data = array(
                            'inspection_part_name' => $row[0]
                        );
                        $this->M_inspection_part->insert_inspection_part($item_data);
                    }
    
                    $this->session->set_flashdata('success', 'File Excel berhasil diunggah dan data berhasil diimpor.');
                } else {
                    $this->session->set_flashdata('error', 'Gagal memuat file Excel: ' . \Shuchkin\SimpleXLSX::parseError());
                }
            } else {
                $this->session->set_flashdata('error', 'Class SimpleXLSX tidak ditemukan.');
            }
    
            redirect('inspection_part');
        }
    }
    
}
