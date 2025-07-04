<?php

defined('BASEPATH') or exit('No direct script access allowed');
//use phpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Drawing;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Checksheet extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_checksheet');
        $this->load->model('M_master');

        is_login();
        // is_access(1, [2,3,4]);
    }

    public function index()
    {
        $data = [
            'judul' => 'Equipment Inspection',
            'data' => $this->M_checksheet->get(),
            'section' => $this->M_checksheet->getSection(),
            'machine' => $this->M_checksheet->getMachine()
        ];
        $this->load->view('checksheet/index', $data);
    }

    // public function section($name)
    // {
    //     if (!$name) {
    //         redirect('dashboard');
    //     }
    
    //     $section = $this->db->get_where('section', ['section_name' => $name])->row_array();
    
    //     if (empty($section)) {
    //         echo "Section tidak ditemukan!";
    //         exit;
    //     }
    
    //     // Ambil data berdasarkan section ID
    //     $data = $this->M_checksheet->getMachineId($section['section_name']);
    //     // Ambil semua machine yang tersedia untuk dropdown
    //     $machine = $this->M_checksheet->getMachine();

    //     // Set default rank atau ambil dari database
    //     // Opsi 1: Set rank default
    //     // $rank = 1; // atau nilai default lainnya

    //     // Opsi 2: Ambil dari tabel section jika ada field rank
    //  $rank = isset($section['rank']) ? $section['rank'] : 1;

    // // Opsi 3: Ambil rank tertinggi + 1 untuk section ini
    // // $this->db->select_max('rank');
    // // $this->db->where('section_id', $section['id']);
    // // $max_rank = $this->db->get('equipment_inspection')->row_array();
    // // $rank = ($max_rank['rank'] ? $max_rank['rank'] + 1 : 1);
    
    //     // Persiapkan data untuk dikirim ke view
    //     $pageData = [
    //         'judul' => 'Equipment Inspection',
    //         'data' => $data,
    //         'section' => $section,
    //         'machine' => $machine,
    //     'rank' => $rank
    //     ];
    
    //     // Menampilkan halaman dengan data
    //     $this->load->view('checksheet/section', $pageData);
    // }

    /**
 * Modifikasi fungsi section() untuk menambahkan status pemeriksaan
 */
public function section($name)
{
    if (!$name) {
        redirect('dashboard');
    }

    $section = $this->db->get_where('section', ['section_name' => $name])->row_array();

    if (empty($section)) {
        echo "Section tidak ditemukan!";
        exit;
    }

    // Ambil data berdasarkan section ID
    $machines = $this->M_checksheet->getMachineId($section['section_name']);
    
    // Tambahkan status pemeriksaan untuk setiap mesin
    $machines_with_status = [];
    $urgent_machines = [];
    $warning_machines = [];
    
    if (is_array($machines)) {
        foreach ($machines as $machine) {
            $status = $this->calculateInspectionStatus($machine['section_id'], $machine['machine_id']);
            $machine['inspection_status'] = $status;
            $machines_with_status[] = $machine;
            
            // Kumpulkan mesin yang perlu notifikasi
            if ($status['alert_type'] === 'urgent') {
                $urgent_machines[] = $machine;
            } elseif ($status['alert_type'] === 'warning') {
                $warning_machines[] = $machine;
            }
        }
    }
    
    // Ambil semua machine yang tersedia untuk dropdown
    $machine = $this->M_checksheet->getMachine();

    // Set default rank atau ambil dari database
    $rank = isset($section['rank']) ? $section['rank'] : 1;

    // Persiapkan data untuk dikirim ke view
    $pageData = [
        'judul' => 'Equipment Inspection',
        'data' => $machines_with_status,
        'section' => $section,
        'machine' => $machine,
        'rank' => $rank,
        'urgent_machines' => $urgent_machines,
        'warning_machines' => $warning_machines
    ];

    // Menampilkan halaman dengan data
    $this->load->view('checksheet/section', $pageData);
}

/**
 * Fungsi untuk mendapatkan ringkasan status pemeriksaan (dengan CSRF protection)
 */
public function get_inspection_summary() {
    // Cek request method
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error', 
            'message' => 'Method not allowed'
        ]);
        return;
    }
    
    // Cek CSRF token jika diperlukan
    if ($this->config->item('csrf_protection') === TRUE) {
        $csrf_token = $this->input->post($this->security->get_csrf_token_name());
        if (!$csrf_token) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error', 
                'message' => 'CSRF token required'
            ]);
            return;
        }
    }
    
    $section_id = $this->input->post('section_id');
    
    if (!$section_id) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error', 
            'message' => 'Section ID required'
        ]);
        return;
    }
    
    try {
        // Ambil semua mesin di section ini
        $this->db->select('machine.id as machine_id, machine.machine_name, ' . (int)$section_id . ' as section_id');
        $this->db->from('machine');
        $this->db->join('equipment_inspection', 'machine.id = equipment_inspection.machine_id AND equipment_inspection.section_id = ' . (int)$section_id, 'left');
        $this->db->group_by('machine.id');
        $machines = $this->db->get()->result_array();
        
        $summary = [
            'total_machines' => count($machines),
            'urgent' => 0,
            'warning' => 0,
            'info' => 0,
            'success' => 0,
            'never_inspected' => 0,
            'urgent_list' => [],
            'warning_list' => []
        ];
        
        foreach ($machines as $machine) {
            $status = $this->calculateInspectionStatus($section_id, $machine['machine_id']);
            
            switch ($status['alert_type']) {
                case 'urgent':
                    $summary['urgent']++;
                    $summary['urgent_list'][] = [
                        'machine_name' => $machine['machine_name'],
                        'status_text' => $status['status_text'],
                        'days_remaining' => $status['days_remaining']
                    ];
                    break;
                case 'warning':
                    $summary['warning']++;
                    $summary['warning_list'][] = [
                        'machine_name' => $machine['machine_name'],
                        'status_text' => $status['status_text'],
                        'days_remaining' => $status['days_remaining']
                    ];
                    break;
                case 'info':
                    $summary['info']++;
                    break;
                case 'success':
                    $summary['success']++;
                    break;
                default:
                    $summary['never_inspected']++;
                    $summary['urgent_list'][] = [
                        'machine_name' => $machine['machine_name'],
                        'status_text' => $status['status_text'],
                        'days_remaining' => null
                    ];
                    break;
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($summary);
        
    } catch (Exception $e) {
        log_message('error', 'Error in get_inspection_summary: ' . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Internal server error'
        ]);
    }
}

/**
 * Fungsi untuk mendapatkan detail status pemeriksaan mesin tertentu
 */
public function get_machine_inspection_detail() {
    // Cek request method
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error', 
            'message' => 'Method not allowed'
        ]);
        return;
    }
    
    // Cek CSRF token jika diperlukan
    if ($this->config->item('csrf_protection') === TRUE) {
        $csrf_token = $this->input->post($this->security->get_csrf_token_name());
        if (!$csrf_token) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error', 
                'message' => 'CSRF token required'
            ]);
            return;
        }
    }
    
    $section_id = $this->input->post('section_id');
    $machine_id = $this->input->post('machine_id');
    
    if (!$section_id || !$machine_id) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error', 
            'message' => 'Section ID dan Machine ID diperlukan'
        ]);
        return;
    }
    
    try {
        // Ambil nama mesin
        $machine = $this->db->get_where('machine', ['id' => $machine_id])->row();
        if (!$machine) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error', 
                'message' => 'Mesin tidak ditemukan'
            ]);
            return;
        }
        
        // Ambil status pemeriksaan
        $status = $this->calculateInspectionStatus($section_id, $machine_id);
        
        // Ambil riwayat pemeriksaan terakhir (5 terakhir)
        $this->db->select('tgl_checksheet, users.nama as inspector_name, step_proses');
        $this->db->from('equipment_inspection');
        $this->db->join('users', 'users.id_user = equipment_inspection.user_id', 'left');
        $this->db->where('equipment_inspection.section_id', $section_id);
        $this->db->where('equipment_inspection.machine_id', $machine_id);
        $this->db->where('equipment_inspection.step_proses >=', 2);
        $this->db->order_by('equipment_inspection.tgl_checksheet', 'DESC');
        $this->db->limit(5);
        $history = $this->db->get()->result_array();
        
        $response = [
            'status' => 'success',
            'machine_name' => $machine->machine_name,
            'inspection_status' => $status,
            'history' => $history
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
        
    } catch (Exception $e) {
        log_message('error', 'Error in get_machine_inspection_detail: ' . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Internal server error'
        ]);
    }
}

/**
 * Fungsi untuk mendapatkan notifikasi pemeriksaan untuk dashboard
 */
public function get_inspection_notifications() {
    try {
        $user_id = $this->session->userdata('id_user');
        $level = $this->session->userdata('level');
        
        // Query untuk mendapatkan semua mesin yang perlu notifikasi
        $this->db->select('machine.machine_name, section.section_name, equipment_inspection.section_id, equipment_inspection.machine_id, MAX(equipment_inspection.tgl_checksheet) as last_inspection');
        $this->db->from('equipment_inspection');
        $this->db->join('machine', 'machine.id = equipment_inspection.machine_id', 'left');
        $this->db->join('section', 'section.id = equipment_inspection.section_id', 'left');
        $this->db->where('equipment_inspection.step_proses >=', 2);
        
        // Filter berdasarkan level user jika diperlukan
        if ($level != 1) {
            $this->db->where('equipment_inspection.user_id', $user_id);
        }
        
        $this->db->group_by(['equipment_inspection.section_id', 'equipment_inspection.machine_id']);
        $machines = $this->db->get()->result_array();
        
        $notifications = [];
        foreach ($machines as $machine) {
            $status = $this->calculateInspectionStatus($machine['section_id'], $machine['machine_id']);
            
            if ($status['alert_type'] === 'urgent' || $status['alert_type'] === 'warning') {
                $notifications[] = [
                    'machine_name' => $machine['machine_name'],
                    'section_name' => $machine['section_name'],
                    'status' => $status,
                    'section_id' => $machine['section_id'],
                    'machine_id' => $machine['machine_id']
                ];
            }
        }
        
        // Urutkan berdasarkan prioritas (urgent dulu, kemudian warning)
        usort($notifications, function($a, $b) {
            $priority = ['urgent' => 1, 'warning' => 2];
            return $priority[$a['status']['alert_type']] - $priority[$b['status']['alert_type']];
        });
        
        header('Content-Type: application/json');
        echo json_encode($notifications);
        
    } catch (Exception $e) {
        log_message('error', 'Error in get_inspection_notifications: ' . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Internal server error'
        ]);
    }
}
    
    function add_eq_inspection()
    {
        $section_name = $this->input->post('section_name');
        $data = [
            'section_id' => $this->input->post('section_id'),
            'machine_id' => htmlspecialchars($this->input->post('machine_id', true)),
            'user_id' => $this->session->userdata('id_user'),
            'step_proses' => 2,
            'tgl_checksheet' => date('Y-m-d'),
            'step_proses_user' => $this->session->userdata('id_user')
        ];
        $this->db->insert('equipment_inspection', $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
        redirect('checksheet/section/' . $section_name);
    }

    function delete_eq_inspection($id)
    {
        $section_name = $this->input->get('section_name');
        $rank = $this->input->get('rank');
        $this->db->where('id', $id);
        $this->db->delete('equipment_inspection');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
        redirect('checksheet/section/' . $section_name . '?rank=' . $rank);
    }

    function show($section_id, $machine_id)
    {
        if (!$machine_id || !$section_id) {
            redirect('dashboard');
        }
        $machine = $this->db->get_where('machine', ['id' => $machine_id])->row_array();
        $data = [
            'judul' => 'Checksheet',
            'data' => $this->M_checksheet->getEquipmentInspectionbyId($section_id, $machine_id),
            'machine' => $machine
        ];
        $this->load->view('checksheet/lihat', $data);
    }

    // tambahan dashboard menampilkan form

    public function get_inspected_machines($section_id)
{
    // Mengambil data mesin yang sudah diperiksa dengan join tabel
    $this->db->select('users.nama as user_name, machine.machine_name, equipment_inspection.tgl_checksheet, section.section_name');
    $this->db->from('equipment_inspection');
    $this->db->join('machine', 'equipment_inspection.machine_id = machine.id', 'left');
    $this->db->join('users', 'users.id_user = equipment_inspection.user_id', 'left');
    $this->db->join('section', 'section.id = equipment_inspection.section_id', 'left'); // Join dengan tabel section
    $this->db->where('section.id', $section_id); // Tambahkan filter berdasarkan section_id
    
    $query = $this->db->get();

    // Mengembalikan hasil dalam format JSON
    echo json_encode($query->result_array());
}
    function copy_checksheet()
{
    $section_id = $this->input->post('section_id');
    $machine_id = $this->input->post('machine_id');
    $tgl_checksheet = $this->input->post('tgl_checksheet');
    $marked_rows = $this->input->post('marked_rows'); // Data marking yang dikirim dari client
    
    $eq = $this->M_checksheet->getEqbySectionMachine($section_id, $machine_id);
    $data = [
        'section_id' => $section_id,
        'machine_id' => $machine_id,
        'user_id' => $this->session->userdata('id_user'),
        'step_proses' => '2',
        'step_proses_user' => $this->session->userdata('id_user'),
        'tgl_checksheet' => $tgl_checksheet,
    ];
    $this->db->insert('equipment_inspection', $data);
    $new_id = $this->db->insert_id();

    // Array untuk menyimpan mapping ID lama ke ID baru
    $id_mapping = array();
    
    $part = $this->M_checksheet->getPart($eq['id']);
    foreach ($part as $p) {
        $inspection_part = $this->M_checksheet->getInspectionPart($p['id']);
        $data = [
            'eq_id' => $new_id,
            'part' => $p['part']
        ];
        $this->db->insert('equpment_inspection_part', $data);
        $new_part_id = $this->db->insert_id();

        foreach ($inspection_part as $inspection) {
            $inspection_part = [
                'part_id' => $new_part_id,
                'inspection_part' => $inspection['inspection_part']
            ];
            $this->db->insert('equpment_inspection_inspection_part', $inspection_part);
            $inspection_part_id = $this->db->insert_id();

            $detail = $this->M_checksheet->getAllDetailPart($inspection['id']);
            foreach ($detail as $d) {
                $detail_data = [
                    'inspection_part_id' => $inspection_part_id,
                    'item' => $d['item'],
                    'method' => $d['method'],
                    'determination_standard' => $d['determination_standard'],
                    'measure_data' => '',
                    'judgement' => '',
                    'measure' => '',
                    'img_item' => $d['img_item']
                ];
                $this->db->insert('equpment_inspection_part_detail', $detail_data);
                $new_detail_id = $this->db->insert_id();
                
                // Simpan mapping ID lama ke ID baru
                $id_mapping[$d['id']] = $new_detail_id;
            }
        }
    }
    
    // Jika ada data marking yang dikirim, kirim mapping ID kembali ke client
    if (!empty($marked_rows)) {
        $marked_rows_array = json_decode($marked_rows, true);
        $new_marked_rows = array();
        
        foreach ($marked_rows_array as $old_id) {
            if (isset($id_mapping[$old_id])) {
                $new_marked_rows[] = $id_mapping[$old_id];
            }
        }
        
        // Set session untuk menyimpan data marking yang baru
        $this->session->set_flashdata('new_marked_rows', json_encode($new_marked_rows));
    }
    
    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
    redirect('checksheet/show/' . $section_id . '/' . $machine_id);
}

    function detail()
    {
        $id = $this->uri->segment(3);
        $checksheet = $this->M_checksheet->getDetail($id);
        //user_management 
        $eq = $this->M_checksheet->getEq($id);

        $id_user = $this->session->userdata('id_user');
        if ($this->session->userdata('level') == 2 && $eq['step_proses'] == 2) {
            $user_management = $this->db->get_where('view_users_management', ['id_inspector' => $id_user])->result_array();
            foreach ($user_management as $row) {
                $users_management[] = [
                    'id_user' => $row['id_supervisor'],
                    'nama' => $row['nama_supervisor']
                ];       
            }
        } elseif ($this->session->userdata('level') == 3 && $eq['step_proses'] == 3) {
            $this->db->group_by('id_manager');
            $user_management = $this->db->get_where('view_users_management', ['id_supervisor' => $id_user])->result_array();
            foreach ($user_management as $row) {
                $users_management[] = [
                    'id_user' => $row['id_manager'],
                    'nama' => $row['nama_manager']
                ];
            }
        } else {
            $users_management = [];
        }


        $this->M_checksheet->read_notif($id);

        $data = [
            'judul' => 'Detail Equipment Inspection',
            'data' => $checksheet,
            'part' => $this->M_checksheet->getPart($id),
            'parts' => $this->db->get('part')->result_array(),
            'parts_machine' => $this->M_checksheet->getPartsEq($id),
            'inspection_parts' => $this->db->get('inspection_part')->result_array(),
            'items' => $this->db->get('item')->result_array(),
            'methods' => $this->db->get('method')->result_array(),
            'determination_standards' => $this->db->get('determination_standard')->result_array(),
            'eq' => $eq,
            'users_management' => $users_management,
        ];
        $this->load->view('checksheet/detail', $data);
    }

    function add_part()
    {
        $data = [
            'eq_id' => $this->input->post('eq_id'),
            'part' => $this->input->post('part'),
        ];
        $this->db->insert('equpment_inspection_part', $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
        redirect('checksheet/detail/' . $this->input->post('eq_id'));
    }

    function add_inspection_part()
    {
        $inspection_parts = [
            'part_id' => $this->input->post('part_id'),
            'inspection_part' => $this->input->post('inspection_part'),
        ];
        $this->db->insert('equpment_inspection_inspection_part', $inspection_parts);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
        redirect('checksheet/detail/' . $this->input->post('eq_id'));
    }

    function add_item()
    {
        $details = [
            // 'eq_part_id' => $this->input->post('eq_part_id'),
            'inspection_part_id' => $this->input->post('inspection_part_id'),
            'item' => $this->input->post('item'),
            'method' => $this->input->post('method'),
            'determination_standard' => $this->input->post('determination_standard'),
        ];
        $this->db->insert('equpment_inspection_part_detail', $details);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
        redirect('checksheet/detail/' . $this->input->post('eq_id'));
    }

    function get_detail_part($id)
    {
        $section_id = $this->input->get('section_id');
        $machine_id = $this->input->get('machine_id');
        $no = $this->input->get('no');
        $jumlah = $this->input->get('jumlah');
        $part_id = $this->input->get('part_id');
        $part_id = explode(',', $part_id);
        $detail = $this->M_checksheet->getDetailPart($id);
        $list_last_eq = $this->M_checksheet->get_last_eq($section_id, $machine_id, $detail['eq_id']);
        $data = [
            'detail' => $detail,
            'id_part' => $id,
            'section_id' => $section_id,
            'machine_id' => $machine_id,
            'list_last_eq' => $list_last_eq,
            'no' => $no,
            'jumlah' => $jumlah,
            'part_id' => $part_id
        ];
        $this->load->view('checksheet/detail_part', $data);
    }

    function pemeriksaan()
    {
        $id = $this->input->get('id');
        $no = $this->input->get('no');
        $jumlah = $this->input->get('jumlah');
        $detail = $this->M_checksheet->getDetailPart($id);
        $data = [
            'detail' => $detail,
            'id_part' => $id,
            'no' => $no,
            'jumlah' => $jumlah
        ];
        $this->load->view('checksheet/detail_part_pemeriksaan', $data);
    }

    function check_measure()
    {
        $id = $this->input->post('id');
        $detail = $this->M_checksheet->getDetailPart($id);

        $data = [
            // 'detail' => $detail,
            'status' => (isset($detail['measure_data']) && $detail['measure_data'] != '') ? true : false,
        ];
        echo json_encode($data);
    }

    function add_detail_part()
    {
        $data = [
            'eq_part_id' => $this->input->post('id'),
            'inspection_part' => $this->input->post('inspection_part'),
            'item' => $this->input->post('item'),
            'method' => $this->input->post('method'),
            'determination_standard' => $this->input->post('determination_standard'),
            'measure_data' => $this->input->post('measure_data'),
            'judgement' => $this->input->post('judgement'),
            'measure' => $this->input->post('measure'),
        ];
        $this->db->insert('equpment_inspection_part_detail', $data);
        echo json_encode(['status' => true]);
    }

    function get_detail_part_table($id)
    {
        $data = $this->M_checksheet->getDetailPart($id);
        echo json_encode($data);
    }

    function get_detail_part_edit($id)
    {
        $data = $this->M_checksheet->getDetailPartId($id);
        echo json_encode($data);
    }

    function get_inspection_part($id)
    {
        $data = $this->M_checksheet->getInspectionPart($id);
        echo json_encode($data);
    }

    function edit_detail_part()
    {
        $data = [
            'measure_data' => $this->input->post('measure_data'),
            'judgement' => $this->input->post('judgement'),
            'measure' => $this->input->post('measure'),
        ];
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('equpment_inspection_part_detail', $data);
        echo json_encode(['status' => true]);
    }

    // tambahan 11-05-2024
    public function save_measure_data() {
        $ids = $this->input->post('id');
        $measure_data = $this->input->post('measure_data');
        $judgements = $this->input->post('judgement');
    
        $data_to_insert = array();
    
        foreach ($ids as $index => $id) {
            $data_to_insert[] = array(
                'id' => $id,
                'measure_data' => $measure_data[$index],
                'judgement' => $judgements[$index],
                // Add other necessary fields as needed
            );
        }
    
        // Load model and call insert/update method
        $this->load->model('M_checksheet');
        $result = $this->YourModel->save_measure_data($data_to_insert);
    
        if ($result) {
            $this->session->set_flashdata('success', 'Data saved successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to save data.');
        }
    
        redirect('checksheet/tabel_detail');
    }
    
    public function edit_all_details()
{
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
        // Ambil data perubahan dari POST
        $changes = $this->input->post('changes');
        
        // Debug: Log data yang diterima
        log_message('debug', 'Data yang diterima: ' . print_r($changes, true));

        if (empty($changes)) {
            $response = ['status' => 'error', 'message' => 'Tidak ada perubahan data.'];
            echo json_encode($response);
            return;
        }

        // Decode data JSON
        $changes = json_decode($changes, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', 'Kesalahan saat mendecode JSON: ' . json_last_error_msg());
            $response = ['status' => 'error', 'message' => 'Data JSON tidak valid.'];
            echo json_encode($response);
            return;
        }

        // Validasi format data
        if (!is_array($changes) || empty($changes)) {
            $response = ['status' => 'error', 'message' => 'Format data tidak valid.'];
            echo json_encode($response);
            return;
        }

        // Load model untuk pembaruan
        $this->load->model('M_checksheet');

        // Proses data
        $result = $this->M_checksheet->update_details($changes);

        // Cek hasil dari model
        if ($result['status'] === 'success') {
            $response = ['status' => 'success', 'message' => 'Data berhasil diperbarui.'];
        } else {
            $response = ['status' => 'error', 'message' => $result['message'] ?? 'Terjadi kesalahan saat memperbarui data.'];
        }

        // Debug: Log hasil pembaruan
        log_message('debug', 'Hasil update_details: ' . print_r($result, true));

        // Kirim respons JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Jika bukan POST, kirim respons error
        $response = ['status' => 'error', 'message' => 'Invalid request method.'];
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

    function update_checksheet($field)
    {
        if ($field == 'img1') {
            $img_checksheet1 = base64_encode(file_get_contents($_FILES['img_checksheet1']['tmp_name']));
            $img_checksheet1 = 'data:image/jpg' . ';base64,' . $img_checksheet1;
            $data = [
                'img_checksheet1' => $img_checksheet1
            ];
        } elseif ($field == 'img2') {
            $img_checksheet2 = base64_encode(file_get_contents($_FILES['img_checksheet2']['tmp_name']));
            $img_checksheet2 = 'data:image/jpg' . ';base64,' . $img_checksheet2;
            $data = [
                'img_checksheet2' => $img_checksheet2
            ];
        } elseif ($field == 'img3') {
            $img_checksheet3 = base64_encode(file_get_contents($_FILES['img_checksheet3']['tmp_name']));
            $img_checksheet3 = 'data:image/jpg' . ';base64,' . $img_checksheet3;
            $data = [
                'img_checksheet3' => $img_checksheet3
            ];
        } elseif ($field == 'additional') {
            $data = [
                'additional_item' => $this->input->post('additional_item'),
                'purchase_part' => $this->input->post('purchase_part')
            ];
        } elseif ($field == 'step_proses') {
            $step_proses_user = $this->input->post('step_proses_user');
            $level = $this->db->get_where('users', ['id_user' => $step_proses_user])->row_array()['level'];
            $data = [
                'step_proses' => $level,
                'step_proses_user' => $step_proses_user
            ];
            $eq_id = $this->input->post('id');
            // buat notifikasi
            $nama = $this->session->userdata('nama');
            //find id_penerima by level
            $link = site_url('checksheet/detail/' . $this->input->post('id'));
            $section = $this->M_checksheet->getSectionName($this->input->post('section_id'));
            $machine = $this->M_checksheet->getMachineName($this->input->post('machine_id'));
            $message = "Checksheet Section " . $section . " Machine " . $machine . " telah selesai diisi oleh Inspector " . $nama . ". Silahkan cek hasilnya.";
            $this->M_checksheet->saveNotif($step_proses_user, $message, $link, $eq_id);

            //set session flashdata
            $this->session->set_flashdata('message_kirim_checksheet', '<div class="alert alert-success" role="alert">Checksheet berhasil dikirimkan!</div>');
            
            // MODIFIKASI: Update data terlebih dahulu
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $this->db->update('equipment_inspection', $data);
            
            // MODIFIKASI: Langsung redirect ke export setelah kirim checksheet
            redirect('checksheet/export/' . $id);
            return; // Stop execution disini
        }
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->update('equipment_inspection', $data);
        redirect('checksheet/detail/' . $id, 'refresh');
    }

    function delete_part($id)
    {
        $eq_id = $this->input->get('eq_id');
        $this->db->where('id', $id);
        $this->db->delete('equpment_inspection_part_detail');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
        redirect('checksheet/detail/' . $eq_id);
    }

    function cek_checksheet()
    {
        $tgl_checksheet = $this->input->post('tgl_checksheet');
        $section_id = $this->input->post('section_id');
        $machine_id = $this->input->post('machine_id');
        $item = $this->input->post('item');
        $method = $this->input->post('method');
        $determination_standard = $this->input->post('determination_standard');

        $checksheet = $this->M_checksheet->findChecksheet($tgl_checksheet, $section_id, $machine_id, $item, $method, $determination_standard);

        echo json_encode($checksheet);
    }

    /**
 * Fungsi export() yang diperbaiki untuk mengatasi masalah encoding
 * Ganti fungsi export() yang lama dengan yang ini
 */
function export($id)
{
    // use view to model the xlsx file
    $spreadsheet = new Spreadsheet();
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing3 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();

    $data = $this->M_checksheet->getEq($id);
    $ttd = $this->M_checksheet->get_ttd_user($data['user_id']);
    $img_checksheet1 = str_replace('data:image/jpg;base64,', '', $data['img_checksheet1']);
    $img_checksheet2 = str_replace('data:image/jpg;base64,', '', $data['img_checksheet2']);
    $img_checksheet3 = str_replace('data:image/jpg;base64,', '', $data['img_checksheet3']);
    $additional_item = $data['additional_item'];
    $purchase_part = $data['purchase_part'];
    
    $spreadsheet->getProperties()->setCreator('Checksheet Maintenance')
        ->setLastModifiedBy('Checksheet Maintenance')
        ->setTitle('Checksheet')
        ->setSubject('Checksheet')
        ->setDescription('Checksheet, generated using PHP classes.')
        ->setKeywords('office 2007 openxml php')
        ->setCategory('Checksheet');

    // === PENGATURAN PAGE SETUP YANG OPTIMAL ===
    $worksheet = $spreadsheet->getActiveSheet();
    $pageSetup = $worksheet->getPageSetup();
    $pageMargins = $worksheet->getPageMargins();
    
    // Set orientation ke landscape
    $pageSetup->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    
    // Set paper size ke A4 (210 x 297 mm)
    $pageSetup->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    
    // Set margins yang optimal untuk readability dan maksimal space
    $pageMargins->setLeft(0.2);     // Margin yang wajar
    $pageMargins->setRight(0.2);    // Margin yang wajar
    $pageMargins->setTop(0.2);      // Margin yang wajar
    $pageMargins->setBottom(0.2);   // Margin yang wajar
    $pageMargins->setHeader(0.1);   // Header yang cukup
    $pageMargins->setFooter(0.1);   // Footer yang cukup
    
    // Set center on page
    $pageSetup->setHorizontalCentered(true);
    $pageSetup->setVerticalCentered(true);
    
    // PENTING: Set fit to page untuk MEMAKSA semua konten dalam 1 halaman
    $pageSetup->setFitToPage(true);
    $pageSetup->setFitToWidth(1);    // PASTI fit dalam 1 halaman lebar
    $pageSetup->setFitToHeight(1);   // PASTI fit dalam 1 halaman tinggi
    $pageSetup->setScale(100);       // Base scale 100% (akan auto-adjust oleh fit to page)

    $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Equipment Inspection Sheet')
        ->setCellValue('A6', 'Rank')
        ->setCellValue('C6', $data['rank'])
        ->setCellValue('A7', 'Section')
        ->setCellValue('C7', $data['section_name'])
        ->setCellValue('A8', 'Machine Name')
        ->setCellValue('C8', $data['machine_name'])
        ->setCellValue('D8', 'Equipment No')
        ->setCellValue('E8', $data['equipment_no'])
        ->setCellValue('F8', 'Cycle')
        ->setCellValue('G8', $data['cycle'])
        // Format baru untuk Inspection Day
        ->setCellValue('G2', 'Inspection Day')
        ->setCellValue('H2', date('d F Y', strtotime($data['tgl_checksheet'])))
        // Format baru untuk Inspector  
        ->setCellValue('G3', 'Inspector')
        ->setCellValue('H3', $data['nama'])
        // Format baru untuk Judgement - UPDATED WITH REPAIRED FIX
        ->setCellValue('G4', 'Judgement')
        ->setCellValue('H4', 'O   : No Abnormality')
        ->setCellValue('H5', 'Δ   : Cautious')
        ->setCellValue('H6', 'X   : Abnormal')
        ->setCellValue('H7', '⊘   : Repaired Fix'); // ADDED REPAIRED FIX SYMBOL

    // === OPTIMASI KHUSUS UNTUK TITLE "Equipment Inspection Sheet" ===
    // Merge cells untuk title agar tidak terpotong
    $spreadsheet->getActiveSheet()->mergeCells("A1:H1");
    
    // Give bold, italic, underline dan font size yang lebih besar untuk title Equipment Inspection Sheet
    $spreadsheet->getActiveSheet(0)->getStyle('A1')->getFont()->setBold(true)->setItalic(true)->setUnderline(\PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_SINGLE)->setSize(18);
    
    // Set alignment untuk title agar tetap di kiri dan optimal
    $spreadsheet->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $spreadsheet->getActiveSheet()->getStyle("A1")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    
    // PENTING: Set row height khusus untuk title yang lebih besar agar teks tidak tertutup
    $worksheet->getRowDimension(1)->setRowHeight(30); // Diperbesar khusus untuk title
    
    // Enable text wrapping untuk title jika diperlukan
    $spreadsheet->getActiveSheet()->getStyle("A1")->getAlignment()->setWrapText(true);
    
    // Set underline hanya untuk data Inspection Day (H2)
    $spreadsheet->getActiveSheet()->getStyle("H2")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    
    // Set underline hanya untuk data Inspector (H3)
    $spreadsheet->getActiveSheet()->getStyle("H3")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $spreadsheet->getActiveSheet()->mergeCells("A6:B6");
    $spreadsheet->getActiveSheet()->mergeCells("A7:B7");
    $spreadsheet->getActiveSheet()->mergeCells("A8:B8");

    // === PENAMBAHAN WARNA BACKGROUND ===
    // Warna untuk Rank (A6:C6) - Blue sesuai gambar
    $spreadsheet->getActiveSheet()->getStyle("A6:C6")->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setRGB('B4C6E7'); // Blue sesuai gambar
    
    // Warna untuk Section (A7:C7) - Blue sesuai gambar
    $spreadsheet->getActiveSheet()->getStyle("A7:C7")->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setRGB('B4C6E7'); // Blue sesuai gambar
    
    // Warna untuk Machine Name (A8:C8) - Blue sesuai gambar
    $spreadsheet->getActiveSheet()->getStyle("A8:C8")->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setRGB('B4C6E7'); // Blue sesuai gambar
    
    // Warna untuk Equipment No (D8:E8) - Blue sesuai gambar
    $spreadsheet->getActiveSheet()->getStyle("D8:E8")->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setRGB('B4C6E7'); // Blue sesuai gambar
    
    // Warna untuk Cycle (F8:G8) - Blue sesuai gambar
    $spreadsheet->getActiveSheet()->getStyle("F8:G8")->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setRGB('B4C6E7'); // Blue sesuai gambar

    // Set borders yang konsisten untuk area rank, section, machine
    $spreadsheet->getActiveSheet()->getStyle("A6:C8")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle("D8:G8")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    // Bold and center in A6, A7, A8 dengan font size yang diperbesar
    $spreadsheet->getActiveSheet()->getStyle("A6:E8")->getFont()->setBold(true)->setSize(11);
    $spreadsheet->getActiveSheet()->getStyle("F8:G8")->getFont()->setBold(true)->setSize(11);
    
    // Font styling untuk area Inspection Day, Inspector, dan Judgement yang baru - UPDATED FOR 4 JUDGEMENT LINES
    $spreadsheet->getActiveSheet()->getStyle("G2:G4")->getFont()->setBold(true)->setSize(10);
    $spreadsheet->getActiveSheet()->getStyle("H2:H7")->getFont()->setSize(10); // Extended to H7 for Repaired Fix
    
    // Set underline untuk label Judgement
    $spreadsheet->getActiveSheet()->getStyle("G4")->getFont()->setUnderline(\PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_SINGLE);
    
    // Set alignment untuk area inspection day, inspector, judgement - UPDATED FOR 4 JUDGEMENT LINES
    $spreadsheet->getActiveSheet()->getStyle("G2:H7")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); // Extended to H7
    $spreadsheet->getActiveSheet()->getStyle("G2:H7")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // Extended to H7

    // === BAGIAN YANG DIPERBAIKI UNTUK MENGATASI MASALAH ENCODING ===
    // SOLUSI DIPERBAIKI: Langsung panggil fungsi table yang sudah diperbaiki
    ob_start();
    $this->table_fixed($id); // Menggunakan fungsi table_fixed yang sudah diperbaiki
    $table_html = ob_get_clean();

    // Bersihkan HTML dari karakter yang tidak diinginkan sebelum parsing
    $table_html = str_replace(['Â', 'â'], '', $table_html);

    $dom = new DOMDocument();
    // Set encoding UTF-8 untuk memastikan parsing yang benar
    $dom->encoding = 'UTF-8';
    
    // Load HTML dengan encoding UTF-8
    @$dom->loadHTML('<?xml encoding="UTF-8">' . $table_html);
    
    $xpath = new DOMXPath($dom);
    $elements = $xpath->query('//table');

    if ($elements->length > 0) {
        $table = $elements[0]->C14N();
        
        // Bersihkan table dari karakter yang tidak diinginkan sebelum load ke spreadsheet
        $table = str_replace(['Â', 'â'], '', $table);
        
        $spreadsheet = $reader->loadFromString($table, $spreadsheet);
    }

    // Find how many <tr> in table
    $tr = $xpath->query('//tr');
    $tr_length = $tr->length;
    (int)$row_after = 10 + (int)$tr_length + 2;
    $row_after_1 = 10 + (int)$tr_length - 1;
    $spreadsheet->getActiveSheet()->getStyle("A10:H$row_after_1")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    // Set width yang diperbesar untuk memaksimalkan penggunaan halaman
    $a = range('A', 'H');
    $size = [
        'A' => 16,   // Part - diperbesar maksimal
        'B' => 20,   // Inspection Part - diperbesar untuk isi space kosong
        'C' => 30,   // Item - diperbesar maksimal
        'D' => 28,   // Method - diperbesar maksimal
        'E' => 35,   // Determination Standard - diperbesar maksimal
        'F' => 16,   // Measure Data - diperbesar untuk judgment area
        'G' => 16,   // Judgement - diperbesar untuk menampung teks penuh
        'H' => 20    // Data area - diperbesar untuk inspection day, inspector, judgement data
    ];
    foreach ($a as $key) {
        $spreadsheet->getActiveSheet()->getColumnDimension($key)->setWidth($size[$key]);
    }

    // Ukuran gambar yang lebih besar untuk memanfaatkan space
    $imageWidth = 120;   // Diperbesar kembali
    $imageHeight = 120;

    if ($img_checksheet1 != null || $img_checksheet1 != '') {
        $imageData1 = base64_decode($img_checksheet1);
        $imagePath1 = APPPATH . '../assets/image.jpg';
        file_put_contents($imagePath1, $imageData1);

        $drawing->setPath($imagePath1);
        $drawing->setWidthAndHeight($imageWidth, $imageHeight);
        $drawing->setCoordinates("A$row_after");
        $drawing->setWorksheet($spreadsheet->getActiveSheet());
    }

    if ($img_checksheet2 != null || $img_checksheet2 != '') {
        $imageData2 = base64_decode($img_checksheet2);
        $imagePath2 = APPPATH . '../assets/image1.jpg';
        file_put_contents($imagePath2, $imageData2);

        $drawing2->setPath($imagePath2);
        $drawing2->setWidthAndHeight($imageWidth, $imageHeight);
        $drawing2->setCoordinates("C$row_after");
        $drawing2->setWorksheet($spreadsheet->getActiveSheet());
    }

    if ($img_checksheet3 != null || $img_checksheet3 != '') {
        $imageData3 = base64_decode($img_checksheet3);
        $imagePath3 = APPPATH . '../assets/image2.jpg';
        file_put_contents($imagePath3, $imageData3);

        // Posisikan gambar ketiga di kolom A-B setelah area purchase selesai
        $row_img1 = $end_purchase_row + 8; // Setelah area purchase dan sebelum signature
        $drawing3->setPath($imagePath3);
        $drawing3->setWidthAndHeight($imageWidth, $imageHeight);
        $drawing3->setCoordinates("A$row_img1"); // Pindah ke kolom A
        $drawing3->setWorksheet($spreadsheet->getActiveSheet());
    }

    // === LANJUTAN KODE YANG SAMA SEPERTI SEBELUMNYA ===
    // Buat area untuk Inspection item of addition dan Purchase a necessary part
    // di atas kolom inspector/supervisor/manager (kolom F, G, H)
    
    $spreadsheet->setActiveSheetIndex(0)->setCellValue("F$row_after", "Inspection item of addition");
    $spreadsheet->getActiveSheet()->getStyle("F$row_after")->getFont()->setBold(true);
    
    // Merge header inspection dari F sampai H
    $spreadsheet->getActiveSheet()->mergeCells("F$row_after:H$row_after");
    
    // Buat 5 baris untuk Inspection item of addition dengan bullet points
    if (!empty($additional_item)) {
        $inspection_lines = explode("\n", $additional_item);
        for ($i = 0; $i < 5; $i++) {
            $row_num = $row_after + 1 + $i;
            $content = isset($inspection_lines[$i]) ? "• " . trim($inspection_lines[$i]) : "•";
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("F$row_num", $content);
            // Merge setiap baris bullet point dari F sampai H
            $spreadsheet->getActiveSheet()->mergeCells("F$row_num:H$row_num");
        }
    } else {
        // Jika tidak ada data, buat bullet points kosong
        for ($i = 0; $i < 5; $i++) {
            $row_num = $row_after + 1 + $i;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("F$row_num", "•");
            // Merge setiap baris bullet point dari F sampai H
            $spreadsheet->getActiveSheet()->mergeCells("F$row_num:H$row_num");
        }
    }
    
    // Area Purchase a necessary part
    $purchase_header_row = $row_after + 6;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue("F$purchase_header_row", "Purchase a necessary part");
    $spreadsheet->getActiveSheet()->getStyle("F$purchase_header_row")->getFont()->setBold(true);
    
    // Merge header purchase dari F sampai H
    $spreadsheet->getActiveSheet()->mergeCells("F$purchase_header_row:H$purchase_header_row");
    
    // Buat 5 baris untuk Purchase a necessary part dengan bullet points
    if (!empty($purchase_part)) {
        $purchase_lines = explode("\n", $purchase_part);
        for ($i = 0; $i < 5; $i++) {
            $row_num = $purchase_header_row + 1 + $i;
            $content = isset($purchase_lines[$i]) ? "• " . trim($purchase_lines[$i]) : "•";
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("F$row_num", $content);
            // Merge setiap baris bullet point dari F sampai H
            $spreadsheet->getActiveSheet()->mergeCells("F$row_num:H$row_num");
        }
    } else {
        // Jika tidak ada data, buat bullet points kosong
        for ($i = 0; $i < 5; $i++) {
            $row_num = $purchase_header_row + 1 + $i;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("F$row_num", "•");
            // Merge setiap baris bullet point dari F sampai H
            $spreadsheet->getActiveSheet()->mergeCells("F$row_num:H$row_num");
        }
    }
    
    // Set borders hanya di bawah bullet points (tidak ada garis vertikal)
    $end_purchase_row = $purchase_header_row + 5;
    
    // Border bawah untuk header inspection
    $spreadsheet->getActiveSheet()->getStyle("F$row_after:H$row_after")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    
    // Border bawah untuk setiap baris inspection
    for ($i = 1; $i <= 5; $i++) {
        $row_num = $row_after + $i;
        $spreadsheet->getActiveSheet()->getStyle("F$row_num:H$row_num")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    }
    
    // Border bawah untuk header purchase
    $spreadsheet->getActiveSheet()->getStyle("F$purchase_header_row:H$purchase_header_row")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    
    // Border bawah untuk setiap baris purchase
    for ($i = 1; $i <= 5; $i++) {
        $row_num = $purchase_header_row + $i;
        $spreadsheet->getActiveSheet()->getStyle("F$row_num:H$row_num")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    }
    
    // Hitung posisi untuk signature area
    $cell_plus10 = $end_purchase_row + 2; // Memberikan jarak untuk signature
    $cell_plus11 = $end_purchase_row + 3;
    $cell_plus13 = $end_purchase_row + 5;
    $cell_plus14 = $end_purchase_row + 6;

    $spreadsheet->setActiveSheetIndex(0)->setCellValue("F$cell_plus10", "Inspector");
    $spreadsheet->setActiveSheetIndex(0)->setCellValue("G$cell_plus10", "Supervisor");
    $spreadsheet->setActiveSheetIndex(0)->setCellValue("H$cell_plus10", "Manager");
    $spreadsheet->getActiveSheet()->getStyle("F$cell_plus10:H$cell_plus10")->getFont()->setBold(true);
    
    // Set center alignment untuk header signature
    $spreadsheet->getActiveSheet()->getStyle("F$cell_plus10:H$cell_plus10")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Merge cells untuk signature dengan borders yang konsisten
    $spreadsheet->getActiveSheet()->mergeCells("F$cell_plus11:F$cell_plus13");
    $spreadsheet->getActiveSheet()->mergeCells("G$cell_plus11:G$cell_plus13");
    $spreadsheet->getActiveSheet()->mergeCells("H$cell_plus11:H$cell_plus13");
    
    // Set borders yang konsisten untuk semua area signature
    $spreadsheet->getActiveSheet()->getStyle("F$cell_plus10:H$cell_plus14")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $drawing4 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing5 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing6 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

    $img_ttd_inspector = $ttd['ttd_inspector'];
    $img_ttd_supervisor = $ttd['ttd_supervisor'];
    $img_ttd_manager = $ttd['ttd_manager'];

    // Ukuran tanda tangan yang lebih besar untuk readability
    $ttdWidth = 60;   // Diperbesar kembali
    $ttdHeight = 60;

    if ($img_ttd_inspector != null || $img_ttd_inspector != '') {
        $imageData4 = base64_decode($img_ttd_inspector);
        $imagePath4 = APPPATH . '../assets/ttd_inspector.jpg';
        file_put_contents($imagePath4, $imageData4);

        $drawing4->setPath($imagePath4);
        $drawing4->setWidthAndHeight($ttdWidth, $ttdHeight);
        $drawing4->setCoordinates("F$cell_plus11");
        $drawing4->setWorksheet($spreadsheet->getActiveSheet());
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("F$cell_plus14", $ttd['nama_inspector']);
        // Set center alignment untuk nama inspector
        $spreadsheet->getActiveSheet()->getStyle("F$cell_plus14")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    if ($img_ttd_supervisor != null || $img_ttd_supervisor != '') {
        $imageData5 = base64_decode($img_ttd_supervisor);
        $imagePath5 = APPPATH . '../assets/ttd_supervisor.jpg';
        file_put_contents($imagePath5, $imageData5);

        $drawing5->setPath($imagePath5);
        $drawing5->setWidthAndHeight($ttdWidth, $ttdHeight);
        $drawing5->setCoordinates("G$cell_plus11");
        $drawing5->setWorksheet($spreadsheet->getActiveSheet());
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("G$cell_plus14", $ttd['nama_supervisor']);
        // Set center alignment untuk nama supervisor
        $spreadsheet->getActiveSheet()->getStyle("G$cell_plus14")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    if ($img_ttd_manager != null || $img_ttd_manager != '') {
        $imageData6 = base64_decode($img_ttd_manager);
        $imagePath6 = APPPATH . '../assets/ttd_manager.jpg';
        file_put_contents($imagePath6, $imageData6);

        $drawing6->setPath($imagePath6);
        $drawing6->setWidthAndHeight($ttdWidth, $ttdHeight);
        $drawing6->setCoordinates("H$cell_plus11");
        $drawing6->setWorksheet($spreadsheet->getActiveSheet());
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("H$cell_plus14", $ttd['nama_manager']);
        // Set center alignment untuk nama manager
        $spreadsheet->getActiveSheet()->getStyle("H$cell_plus14")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    $spreadsheet->getActiveSheet()->getStyle("A10:H10")->getFont()->setBold(true)->setSize(10); // Header tabel diperbesar
    
    // === PENAMBAHAN WARNA BACKGROUND UNTUK HEADER TABEL ===
    // Warna biru untuk header kolom tabel (A10:H10) - sama dengan warna area di atas
    $spreadsheet->getActiveSheet()->getStyle("A10:H10")->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setRGB('B4C6E7'); // Blue sesuai gambar
    
    // Pastikan karakter Δ (Delta) ditampilkan dengan benar dengan spacing yang konsisten
    $spreadsheet->getActiveSheet()->getCell('H5')->setValueExplicit('Δ   : Cautious', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    
    // Pastikan karakter ⊘ (Repaired Fix) ditampilkan dengan benar - ADDED FOR REPAIRED FIX
    $spreadsheet->getActiveSheet()->getCell('H7')->setValueExplicit('⊘   : Repaired Fix', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    
    // Set center alignment untuk header table
    $spreadsheet->getActiveSheet()->getStyle("A10:H10")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Set print area untuk memastikan semua konten tercetak dalam 1 halaman
    $lastRow = $cell_plus14;
    $printArea = "A1:H$lastRow";
    $worksheet->getPageSetup()->setPrintArea($printArea);
    
    // === OPTIMASI ROW HEIGHT DENGAN PRIORITAS KHUSUS UNTUK TITLE ===
    for ($i = 1; $i <= $lastRow; $i++) {
        if ($i == 1) {
            // PENTING: Row khusus untuk title "Equipment Inspection Sheet" - diperbesar signifikan
            $worksheet->getRowDimension($i)->setRowHeight(30); // Diperbesar dari 25 ke 30 untuk memastikan teks tidak tertutup
        } elseif ($i >= 2 && $i <= 9) {
            // Header rows lainnya - tinggi yang cukup, termasuk area inspection day, inspector, judgement
            $worksheet->getRowDimension($i)->setRowHeight(16);
        } elseif ($i >= 10 && $i <= $row_after_1) {
            // Table data rows - tinggi yang optimal untuk readability
            $worksheet->getRowDimension($i)->setRowHeight(14);
        } elseif ($i >= $row_after && $i <= $end_purchase_row) {
            // Inspection dan Purchase area (sekarang di kolom F-H) - tinggi yang cukup untuk bullet points
            $worksheet->getRowDimension($i)->setRowHeight(14);
        } else {
            // Footer/signature rows - tinggi yang cukup
            $worksheet->getRowDimension($i)->setRowHeight(12);
        }
    }
    
    // Set font size yang lebih besar untuk table data agar mudah dibaca
    $spreadsheet->getActiveSheet()->getStyle("A10:H$row_after_1")->getFont()->setSize(9);
    
    // Set font size untuk area inspection dan purchase (sekarang di kolom F-H)
    $spreadsheet->getActiveSheet()->getStyle("F$row_after:H$end_purchase_row")->getFont()->setSize(9);
    
    // Set font size untuk signature area
    $spreadsheet->getActiveSheet()->getStyle("F$cell_plus10:H$cell_plus14")->getFont()->setSize(9);
    
    // === OPTIMASI UNTUK MEMAKSIMALKAN PENGGUNAAN HALAMAN ===
    // Set word wrap untuk sel yang mungkin memiliki teks panjang
    $spreadsheet->getActiveSheet()->getStyle("C10:E$row_after_1")->getAlignment()->setWrapText(true);
    
    // Set vertical alignment untuk semua cell agar rapi
    $spreadsheet->getActiveSheet()->getStyle("A1:H$lastRow")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
    
    // Set alignment khusus untuk area inspection dan purchase (sekarang di kolom F-H)
    $spreadsheet->getActiveSheet()->getStyle("F$row_after:H$end_purchase_row")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
    $spreadsheet->getActiveSheet()->getStyle("F$row_after:H$end_purchase_row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    
    // Pengaturan fit to page yang optimal - tidak terlalu dipaksa kecil
    $pageSetup->setFitToPage(true);
    $pageSetup->setFitToWidth(1);
    $pageSetup->setFitToHeight(1);
    
    // === PERBAIKAN BORDERS UNTUK KONSISTENSI ===
    // Pastikan semua borders table konsisten
    $spreadsheet->getActiveSheet()->getStyle("A10:H$row_after_1")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    
    // === PENGATURAN FINAL UNTUK LAYOUT OPTIMAL ===
    // Pastikan print area sudah benar
    $worksheet->getPageSetup()->clearPrintArea();
    $worksheet->getPageSetup()->setPrintArea($printArea);

    $writer = new Xlsx($spreadsheet);
    $filename = $data['machine_name'] . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    // === BERSIHKAN OUTPUT BUFFER SEBELUM MENULIS FILE ===
    ob_end_clean();
    
    $writer->save('php://output');
    exit();
}
    function get_table_detail($id)
    {
        $checksheet = $this->M_checksheet->getDetail($id);
        $section_id = $this->input->get('section_id');
        $machine_id = $this->input->get('machine_id');
        $data = [
            'data' => $checksheet,
            'section_id' => $section_id,
            'machine_id' => $machine_id
        ];
        $this->load->view('checksheet/table_detail', $data);
    }

    function delete_eq($id)
    {
        $section_id = $this->input->get('section_id');
        $machine_id = $this->input->get('machine_id');
        $this->db->where('id', $id);
        $this->db->delete('equipment_inspection');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
        redirect('checksheet/show/' . $section_id . '/' . $machine_id);
    }

    function report_abnormal()
    {
        $data = [
            'judul' => 'Lembar Informasi Hasil Pengecekan Mesin Produksi',
            'user' => $this->M_checksheet->getUser(),
        ];
        $this->load->view('checksheet/report_abnormal', $data);
    }

    function get_tgl_checksheet()
    {
        $user_id = $this->input->post('user_id');
        $tgl_checksheet = $this->M_checksheet->getTglChecksheet($user_id);
        echo json_encode($tgl_checksheet);
    }

    function get_report_abnormal()
    {
        $tanggal = $this->input->post('tgl_checksheet');
        $user_id = $this->input->post('user_id');
        $level = $this->session->userdata('level');
        if ($level == 2) {
            $user_id = $this->session->userdata('id_user');
        }
        $dataAbnormal = $this->M_checksheet->getReportAbnormal($tanggal, $user_id);
        $this->M_checksheet->insertDataAbnormal($dataAbnormal, $tanggal, $user_id);
        // die;
        $data = [
            'data' => $this->M_checksheet->getDataAbnormal($tanggal, $user_id),
        ];
        $this->load->view('checksheet/report_abnormal_tabel', $data);
    }

    function get_report($id)
    {
        $data = $this->M_checksheet->getReport($id);
        echo json_encode($data);
    }

    function update_report()
    {
        $id = $this->input->post('id');
        $data = [
            'tindakan' => $this->input->post('tindakan'),
            'status' => $this->input->post('status'),
        ];
        $this->db->where('id', $id);
        $this->db->update('report_abnormal', $data);
        echo json_encode(['status' => 'success']);
    }

    function export_report($tgl_checksheet, $user_id)
    {
        $data = $this->M_checksheet->getDataAbnormal($tgl_checksheet, $user_id);
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

        $spreadsheet->getActiveSheet()->getStyle("A1:B4")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->mergeCells("A1:F1");
        $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setSize(16);
        $spreadsheet->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // create header table in a6
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A6', 'No')
            ->setCellValue('B6', 'Nama Mesin')
            ->setCellValue('C6', 'Kejanggalan')
            ->setCellValue('D6', 'Tindakan')
            ->setCellValue('E6', 'P.I.C')
            ->setCellValue('F6', 'Status');

        $spreadsheet->getActiveSheet()->getStyle("A6:F6")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("A6:F6")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("A6:F6")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("A6:F6")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $a = range('A', 'F');
        $size = [
            'A' => 5,
            'B' => 21,
            'C' => 60,
            'D' => 29,
            'E' => 12,
            'F' => 12
        ];
        foreach ($a as $key) {
            $spreadsheet->getActiveSheet()->getColumnDimension($key)->setWidth($size[$key]);
        }

        //looping data
        $no = 1;
        $row = 7;
        foreach ($data as $d) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A$row", $no)
                ->setCellValue("B$row", $d['nama_mesin'])
                ->setCellValue("C$row", $d['kejanggalan'])
                ->setCellValue("D$row", $d['tindakan'])
                ->setCellValue("E$row", $this->M_master->getUserId($d['user_id'])['nama'])
                // ->setCellValue("E$row", $this->M_checksheet->getNamaUser($d['user_id']))
                ->setCellValue("F$row", $d['status']);
            $no++;
            $row++;
        }

        //pic and tindakan center align
        $spreadsheet->getActiveSheet()->getStyle("E7:E$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("F7:F$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $spreadsheet->getActiveSheet()->getStyle("A7:F$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        // add value in a$row + 2
        $row2 = $row + 2;
        $row3 = $row + 3;
        $row4 = $row + 4;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A$row2", "Ket :")
            ->setCellValue("B$row2", "- Form ini di isi langsung saat pengecekan mesin.")
            ->setCellValue("B$row3", "- Setelah dilakukan perbaikan lingkari simbol pengecekan  pada form FM.MT.00-128 dan FM.MT.00-118")
            ->setCellValue("B$row4", "- Form ini dilaporkan bersama dengan hasil pengecekan/perbaikan mesin.");

        $row40 = $row + 7;
        $row401 = $row40 + 1;
        $row406 = $row40 + 6;
        $row410 = $row40 + 10;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("B$row40", "Hasil Pengecekan :")
            ->setCellValue("D$row40", "Hasil Perbaikan :")
            ->setCellValue("B$row401", "Disiapkan")
            ->setCellValue("C$row401", "Mengetahui")
            ->setCellValue("D$row401", "Disiapkan")
            ->setCellValue("E$row401", "Mengetahui")
            ->setCellValue("B$row406", $user['nama_inspector'])
            ->setCellValue("C$row406", $user['nama_supervisor'] . "                                               " . $user['nama_manager'])
            ->setCellValue("D$row406", $user['nama_inspector'])
            ->setCellValue("E$row406", $user['nama_supervisor'])
            ->setCellValue("F$row406", $user['nama_manager'])
            ->setCellValue("F$row410", "FM.MT.00-118, (Rev.A) Eff.date : " . date('d M Y', strtotime($tgl_checksheet)));

        //border B$row40 until E$row401
        $spreadsheet->getActiveSheet()->getStyle("B$row40:F$row401")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle("B$row406:F$row406")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        //merger B$row40 until C$row40
        $spreadsheet->getActiveSheet()->mergeCells("B$row40:C$row40");
        // merge D$row40 until E$row40
        $spreadsheet->getActiveSheet()->mergeCells("D$row40:F$row40");
        $spreadsheet->getActiveSheet()->mergeCells("E$row401:F$row401");

        //give border outside in B$row40 until F$row410
        $spreadsheet->getActiveSheet()->getStyle("B$row401:B$row406")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle("C$row401:C$row406")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle("D$row401:D$row406")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle("E$row401:E$row406")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle("F$row401:F$row406")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        //center B$row40 until F$row410
        $spreadsheet->getActiveSheet()->getStyle("B$row40:F$row410")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        //set align right in F$row40 + 10
        $spreadsheet->getActiveSheet()->getStyle("F$row410")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Report.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        $writer->save('php://output');
        exit();
    }

    function upload_img_item()
    {
        $data = $this->input->post();
        $new_data = [
            'id' => $data['id_detail'],
            'img_item' => $this->input->post('img_item')
        ];

        $this->db->where('id', $data['id_detail']);
        $this->db->update('equpment_inspection_part_detail', $new_data);
        // $id = $data['id_detail'];
        // $detail = $this->M_checksheet->getDetailPart($id);
        // $this->load->view('checksheet/section_img', ['detail' => $detail]);
        echo json_encode(['status' => true, 'img_item' => $this->input->post('img_item')]);
    }

    public function import_excel_csv()
{
    // Validasi apakah file diupload
    if (empty($_FILES['excel_file']['name'])) {
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Pilih file CSV terlebih dahulu!</div>');
        redirect('checksheet/detail/' . $this->input->post('eq_id'));
        return;
    }

    $eq_id = $this->input->post('eq_id');
    
    // Set upload path
    $upload_dir = FCPATH . 'uploads/';
    
    // Pastikan direktori ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Tentukan nama file unik
    $file_name = 'import_' . time() . '.csv';
    $file_path = $upload_dir . $file_name;
    
    // Upload file secara manual
    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $file_path)) {
        try {
            // Baca file CSV
            $file_content = file_get_contents($file_path);
            
            // Deteksi encoding dan konversi ke UTF-8 jika perlu
            if (!mb_check_encoding($file_content, 'UTF-8')) {
                $file_content = mb_convert_encoding($file_content, 'UTF-8');
                file_put_contents($file_path, $file_content);
            }
            
            // Baca file CSV dengan berbagai pemisah (delimiter)
            $delimiters = array(',', ';', "\t");
            $file = fopen($file_path, 'r');
            $firstLine = fgets($file);
            fclose($file);
            
            // Deteksi delimiter yang digunakan
            $delimiter = ','; // default
            $maxCount = 0;
            foreach ($delimiters as $d) {
                $count = count(str_getcsv($firstLine, $d));
                if ($count > $maxCount) {
                    $maxCount = $count;
                    $delimiter = $d;
                }
            }
            
            // Baca file CSV dengan delimiter yang terdeteksi
            $file = fopen($file_path, 'r');
            $data = [];
            $row_number = 0;
            
            $current_part = '';
            $current_inspection_part = '';
            
            while (($line = fgetcsv($file, 0, $delimiter)) !== FALSE) {
                $row_number++;
                
                // Skip header
                if ($row_number == 1) {
                    continue;
                }
                
                // Handle baris dengan format yang benar
                if (count($line) >= 3) {
                    // Jika part kosong, gunakan part terakhir
                    $part = !empty(trim($line[0])) ? trim($line[0]) : $current_part;
                    
                    // Jika inspection part kosong, gunakan inspection part terakhir
                    $inspection_part = !empty(trim($line[1])) ? trim($line[1]) : $current_inspection_part;
                    
                    $item = isset($line[2]) ? trim($line[2]) : '';
                    $method = isset($line[3]) ? trim($line[3]) : '';
                    $determination_standard = isset($line[4]) ? trim($line[4]) : '';
                    
                    // Simpan nilai untuk baris berikutnya
                    $current_part = $part;
                    $current_inspection_part = $inspection_part;
                    
                    // Validasi data: Item, Method, dan Determination Standard tidak boleh kosong
                    if (!empty($item) && !empty($method) && !empty($determination_standard)) {
                        $data[] = [
                            'part' => $part,
                            'inspection_part' => $inspection_part,
                            'item' => $item,
                            'method' => $method,
                            'determination_standard' => $determination_standard
                        ];
                    }
                }
            }
            
            fclose($file);
            
            // Hapus file CSV yang diupload
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            
            if (empty($data)) {
                $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Tidak ada data valid ditemukan dalam file CSV! Pastikan format sesuai dengan template.</div>');
                redirect('checksheet/detail/' . $eq_id);
                return;
            }
            
            // Import data ke database
            $this->load->model('M_checksheet');
            $result = $this->M_checksheet->import_from_excel($data, $eq_id);
            
            if ($result) {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Berhasil mengimport ' . count($data) . ' data!</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal mengimport data ke database!</div>');
            }
            
        } catch (Exception $e) {
            log_message('error', 'CSV import error: ' . $e->getMessage());
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Error: ' . $e->getMessage() . '</div>');
        }
    } else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal mengupload file CSV!</div>');
    }
    
    redirect('checksheet/detail/' . $eq_id);
}

public function download_template_csv()
{
    // Set header untuk download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="template_import_checksheet.csv"');
    header('Cache-Control: max-age=0');
    
    // Buat file handler
    $output = fopen('php://output', 'w');
    
    // Tambahkan BOM untuk Excel agar mendukung UTF-8
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Tambahkan header CSV
    fputcsv($output, ['Part', 'Inspection Part', 'Item', 'Method', 'Determination Standard']);
    
    // Contoh data yang sesuai dengan format hierarki
    fputcsv($output, ['Pneumatik unit', 'Solenoid valve', 'Kebocoran angin, kerusakan koil', 'Lihat, cek kebocoran dan kerusakan', 'Tidak ada kebocoran dan kerusakan']);
    fputcsv($output, ['Pneumatik unit', 'Solenoid valve', 'Pengabelan dan fleksibel kabel', 'Kabel rapi dan fleksibel kabel tidak sobek', '']);
    fputcsv($output, ['Pneumatik unit', 'Solenoid valve', 'Suara abnormal, temperature', 'Dengar dan cek dgn digital termometer', 'Tdk ada suara abnormal, Max 60°C']);
    
    fputcsv($output, ['Drive unit', 'Gear motor', 'Motor puly (M1)', 'Ukur dengan clamp meter', 'Mak 3.75 A']);
    fputcsv($output, ['Drive unit', 'Gear motor', 'Pengabelan dan fleksibel kabel', 'Kabel rapi dan fleksibel kabel tidak sobek', '']);
    
    fputcsv($output, ['Paper unit', 'Paper motor A', 'Suara abnormal, temperature', 'Dengar dan cek dgn digital termometer', 'Tdk ada suara abnormal, Max 40°C']);
    fputcsv($output, ['Paper unit', 'Paper motor A', 'Motor sanding A (M2)', 'Ukur dengan clamp meter', 'Mak 7.5 A']);
    
    // Tutup file
    fclose($output);
    exit;
}

// Method untuk mendapatkan informasi marking
public function get_marking_info($eq_id) 
{
    // Ambil data detail checksheet
    $checksheet_data = $this->M_checksheet->getDetail($eq_id);
    
    $response = [
        'status' => 'success',
        'total_items' => count($checksheet_data),
        'eq_id' => $eq_id,
        'machine_name' => '', // Will be filled from database
        'section_name' => ''  // Will be filled from database
    ];
    
    // Get machine and section info
    $eq_info = $this->M_checksheet->getEq($eq_id);
    if ($eq_info) {
        $response['machine_name'] = $eq_info['machine_name'];
        $response['section_name'] = $eq_info['section_name'];
    }
    
    echo json_encode($response);
}

// Method untuk export marking data dalam format yang lebih lengkap
public function export_marking_data($eq_id) 
{
    $marked_ids = $this->input->post('marked_ids');
    
    if (empty($marked_ids)) {
        echo json_encode(['status' => 'error', 'message' => 'Tidak ada data marking untuk di-export']);
        return;
    }
    
    $marked_ids_array = json_decode($marked_ids, true);
    
    // Get detailed information for marked items
    $marked_details = [];
    foreach ($marked_ids_array as $id) {
        $detail = $this->M_checksheet->getDetailPartId($id);
        if ($detail) {
            $marked_details[] = [
                'id' => $id,
                'part' => $detail['part'],
                'inspection_part' => $detail['inspection_part'],
                'item' => $detail['item'],
                'method' => $detail['method'],
                'determination_standard' => $detail['determination_standard'],
                'marked_at' => date('Y-m-d H:i:s')
            ];
        }
    }
    
    echo json_encode([
        'status' => 'success',
        'data' => $marked_details,
        'total_marked' => count($marked_details)
    ]);
}

// Method untuk import marking data dari checksheet lain
public function import_marking_from_checksheet() 
{
    $source_eq_id = $this->input->post('source_eq_id');
    $target_eq_id = $this->input->post('target_eq_id');
    $marked_items = $this->input->post('marked_items'); // JSON string of item details
    
    if (empty($source_eq_id) || empty($target_eq_id) || empty($marked_items)) {
        echo json_encode(['status' => 'error', 'message' => 'Parameter tidak lengkap']);
        return;
    }
    
    $marked_items_array = json_decode($marked_items, true);
    $target_details = $this->M_checksheet->getDetail($target_eq_id);
    
    $new_marked_ids = [];
    
    // Match items based on part, inspection_part, item, method, and determination_standard
    foreach ($marked_items_array as $marked_item) {
        foreach ($target_details as $target_detail) {
            if ($target_detail['part'] == $marked_item['part'] &&
                $target_detail['inspection_part'] == $marked_item['inspection_part'] &&
                $target_detail['item'] == $marked_item['item'] &&
                $target_detail['method'] == $marked_item['method'] &&
                $target_detail['determination_standard'] == $marked_item['determination_standard']) {
                
                $new_marked_ids[] = $target_detail['id'];
                break; // Found match, move to next marked item
            }
        }
    }
    
    echo json_encode([
        'status' => 'success',
        'new_marked_ids' => $new_marked_ids,
        'matched_count' => count($new_marked_ids),
        'original_count' => count($marked_items_array)
    ]);
}

// Method untuk mendapatkan history checksheet yang bisa dijadikan sumber marking
public function get_checksheet_history($section_id, $machine_id) 
{
    $this->db->select('equipment_inspection.*, users.nama as inspector_name');
    $this->db->from('equipment_inspection');
    $this->db->join('users', 'users.id_user = equipment_inspection.user_id', 'left');
    $this->db->where('equipment_inspection.section_id', $section_id);
    $this->db->where('equipment_inspection.machine_id', $machine_id);
    $this->db->where('equipment_inspection.step_proses >=', 2); // Only completed inspections
    $this->db->order_by('equipment_inspection.tgl_checksheet', 'DESC');
    $this->db->limit(10); // Get last 10 inspections
    
    $query = $this->db->get();
    $result = $query->result_array();
    
    echo json_encode([
        'status' => 'success',
        'data' => $result,
        'count' => count($result)
    ]);
}

// Method untuk clear marking data (server-side logging)
public function log_marking_clear() 
{
    $eq_id = $this->input->post('eq_id');
    $marked_count = $this->input->post('marked_count');
    $user_id = $this->session->userdata('id_user');
    
    // Log the marking clear action (optional - for audit trail)
    $log_data = [
        'eq_id' => $eq_id,
        'user_id' => $user_id,
        'action' => 'CLEAR_MARKING',
        'details' => 'Cleared ' . $marked_count . ' marked items',
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    // You can insert this into a logging table if needed
    // $this->db->insert('marking_log', $log_data);
    
    echo json_encode(['status' => 'success', 'message' => 'Marking clear logged']);
}

// Method untuk backup marking data sebelum copy
public function backup_marking_data() 
{
    $eq_id = $this->input->post('eq_id');
    $marked_ids = $this->input->post('marked_ids');
    $user_id = $this->session->userdata('id_user');
    
    if (empty($marked_ids)) {
        echo json_encode(['status' => 'error', 'message' => 'No marking data to backup']);
        return;
    }
    
    $marked_ids_array = json_decode($marked_ids, true);
    
    // Create backup data
    $backup_data = [
        'eq_id' => $eq_id,
        'user_id' => $user_id,
        'marked_ids' => $marked_ids,
        'marked_count' => count($marked_ids_array),
        'backup_date' => date('Y-m-d H:i:s'),
        'backup_type' => 'PRE_COPY'
    ];
    
    // You can insert this into a backup table if needed
    // $this->db->insert('marking_backup', $backup_data);
    
    echo json_encode([
        'status' => 'success', 
        'message' => 'Marking data backed up',
        'backup_id' => time() // Use timestamp as backup ID
    ]);
}

// Tambahkan function ini ke dalam class Checksheet di file paste.txt

function print_checksheet($id)
{
    $data = $this->M_checksheet->getEq($id);
    $ttd = $this->M_checksheet->get_ttd_user($data['user_id']);
    $checksheet_data = $this->M_checksheet->getDetail($id);
    
    // Get table HTML from API
    // $url = base_url('api/table/' . $id);
    $url = base_url('checksheet/table_with_units/' . $id);
    $table_html = file_get_contents($url);
    
    $print_data = [
        'judul' => 'Equipment Inspection Sheet - Print',
        'eq' => $data,
        'ttd' => $ttd,
        'checksheet_data' => $checksheet_data,
        'table_html' => $table_html,
        'additional_item' => $data['additional_item'],
        'purchase_part' => $data['purchase_part']
    ];
    
    $this->load->view('checksheet/print_view', $print_data);
}

function table($id)
{
    // Get checksheet data
    $data = $this->M_checksheet->getDetail($id);
    
    if (empty($data)) {
        echo '<table><tr><td>No data found</td></tr></table>';
        return;
    }
    
    // Initialize rowspan arrays
    $partRowspan = array();
    $inspectionPartRowspan = array();
    
    // Count rowspan for parts and inspection parts
    foreach ($data as $row) {
        $part_id = $row['part_id'];
        $inspection_part_id = $row['inspection_part_id'];

        if (!isset($partRowspan[$part_id])) {
            $partRowspan[$part_id] = 1;
        } else {
            $partRowspan[$part_id]++;
        }
        if (!isset($inspectionPartRowspan[$part_id][$inspection_part_id])) {
            $inspectionPartRowspan[$part_id][$inspection_part_id] = 1;
        } else {
            $inspectionPartRowspan[$part_id][$inspection_part_id]++;
        }
    }
    
    // Generate table HTML
    echo '<table class="table table-bordered">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Part</th>';
    echo '<th>Inspection Part</th>';
    echo '<th>Item</th>';
    echo '<th>Method</th>';
    echo '<th>Determination Standard</th>';
    echo '<th>Measure Data</th>';
    echo '<th>Judgement</th>';
    echo '<th>Measure</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    $printedParts = array(); // To keep track of printed parts
    
    foreach ($data as $row) {
        $part_id = $row['part_id'];
        $inspection_part_id = $row['inspection_part_id'];
        
        echo '<tr>';
        
        // Part column with rowspan
        if (!in_array($part_id, $printedParts)) {
            echo '<td rowspan="' . $partRowspan[$part_id] . '">' . htmlspecialchars($row['part']) . '</td>';
            $printedParts[] = $part_id;
        }
        
        // Inspection Part column with rowspan
        if ($inspectionPartRowspan[$part_id][$inspection_part_id] !== false) {
            echo '<td rowspan="' . $inspectionPartRowspan[$part_id][$inspection_part_id] . '">' . htmlspecialchars($row['inspection_part']) . '</td>';
            $inspectionPartRowspan[$part_id][$inspection_part_id] = false; // Skip rowspan
        }
        
        echo '<td>' . htmlspecialchars($row['item']) . '</td>';
        echo '<td>' . htmlspecialchars($row['method']) . '</td>';
        echo '<td>' . htmlspecialchars($row['determination_standard']) . '</td>';
        
        // === MEASURE DATA DENGAN SATUAN (FITUR BARU) ===
        $measure_data_display = $row['measure_data'];
        
        // Extract satuan dari determination standard jika measure data adalah angka
        if (!empty($row['determination_standard']) && !empty($measure_data_display) && is_numeric($measure_data_display)) {
            $determination = $row['determination_standard'];
            $extracted_unit = $this->extractUnitFromDeterminationSimple($determination);
            
            // Jika measure data adalah angka dan ada satuan, tampilkan dengan satuan
            if (!empty($extracted_unit)) {
                $measure_data_display = $measure_data_display . ' ' . $extracted_unit;
            }
        }
        
        echo '<td>' . htmlspecialchars($measure_data_display) . '</td>';
        
        // === JUDGEMENT DENGAN SIMBOL REPAIRED FIX (DIPERBAIKI) ===
        $judgement_display = '';
        if ($row['judgement'] == 'No Abnormality') {
            $judgement_display = 'O';
        } elseif ($row['judgement'] == 'Cautious') {
            $judgement_display = '△'; // Delta symbol
        } elseif ($row['judgement'] == 'Abnormal') {
            $judgement_display = 'X';
        } elseif ($row['judgement'] == 'Repaired Fix') {
           $judgement_display = '⊘'; // Repaired Fix symbol
        } else {
            $judgement_display = htmlspecialchars($row['judgement']);
        }
        echo '<td>' . $judgement_display . '</td>';
        
        echo '<td>' . htmlspecialchars($row['measure']) . '</td>';
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
}

// === FUNGSI HELPER SEDERHANA UNTUK EKSTRAK SATUAN ===
private function extractUnitFromDeterminationSimple($determination_standard) {
    if (empty($determination_standard)) {
        return '';
    }
    
    // Pattern yang diperluas untuk mencari satuan, termasuk variasi derajat celsius
    $patterns = array(
        // === PATTERN UNTUK DERAJAT CELSIUS (SEMUA VARIASI) ===
        '/Max\s*[\d,\.]+\s*(°C|oC|ºC|degrees?[\s]*C|celsius)\b/i',
        '/Min\s*[\d,\.]+\s*(°C|oC|ºC|degrees?[\s]*C|celsius)\b/i',
        '/[\d,\.]+\s*(°C|oC|ºC|degrees?[\s]*C|celsius)\b/i',
        
        // === PATTERN UNTUK SATUAN LISTRIK ===
        '/Max\s*[\d,\.]+\s*(A|Ampere|ampere|amp)\b/i',
        '/Min\s*[\d,\.]+\s*(A|Ampere|ampere|amp)\b/i',
        '/[\d,\.]+\s*(A|Ampere|ampere|amp)\b/i',
        
        // === PATTERN UNTUK SATUAN FREKUENSI ===
        '/Max\s*[\d,\.]+\s*(Hz|hertz|frequency)\b/i',
        '/Min\s*[\d,\.]+\s*(Hz|hertz|frequency)\b/i',
        '/[\d,\.]+\s*(Hz|hertz|frequency)\b/i',
        
        // === PATTERN UNTUK SATUAN TEGANGAN ===
        '/Max\s*[\d,\.]+\s*(V|volt|volts|voltage)\b/i',
        '/Min\s*[\d,\.]+\s*(V|volt|volts|voltage)\b/i',
        '/[\d,\.]+\s*(V|volt|volts|voltage)\b/i',
        
        // === PATTERN UNTUK SATUAN DAYA ===
        '/Max\s*[\d,\.]+\s*(W|watt|watts|kW|kilowatt)\b/i',
        '/Min\s*[\d,\.]+\s*(W|watt|watts|kW|kilowatt)\b/i',
        '/[\d,\.]+\s*(W|watt|watts|kW|kilowatt)\b/i',
        
        // === PATTERN UNTUK SATUAN PANJANG ===
        '/Max\s*[\d,\.]+\s*(mm|millimeter|cm|centimeter|m|meter)\b/i',
        '/Min\s*[\d,\.]+\s*(mm|millimeter|cm|centimeter|m|meter)\b/i',
        '/[\d,\.]+\s*(mm|millimeter|cm|centimeter|m|meter)\b/i',
        
        // === PATTERN UNTUK SATUAN BERAT ===
        '/Max\s*[\d,\.]+\s*(kg|kilogram|g|gram)\b/i',
        '/Min\s*[\d,\.]+\s*(kg|kilogram|g|gram)\b/i',
        '/[\d,\.]+\s*(kg|kilogram|g|gram)\b/i',
        
        // === PATTERN UNTUK SATUAN TEKANAN ===
        '/Max\s*[\d,\.]+\s*(bar|Bar|psi|PSI|Mpa|MPa|mpa|Kpa|KPa|kpa|pascal)\b/i',
        '/Min\s*[\d,\.]+\s*(bar|Bar|psi|PSI|Mpa|MPa|mpa|Kpa|KPa|kpa|pascal)\b/i',
        '/[\d,\.]+\s*(bar|Bar|psi|PSI|Mpa|MPa|mpa|Kpa|KPa|kpa|pascal)\b/i',
        
        // === PATTERN UNTUK SATUAN KECEPATAN ===
        '/Max\s*[\d,\.]+\s*(rpm|RPM|revolution)\b/i',
        '/Min\s*[\d,\.]+\s*(rpm|RPM|revolution)\b/i',
        '/[\d,\.]+\s*(rpm|RPM|revolution)\b/i',
        
        // === PATTERN UNTUK SATUAN LISTRIK KECIL ===
        '/Max\s*[\d,\.]+\s*(mA|milliampere|microA|μA)\b/i',
        '/Min\s*[\d,\.]+\s*(mA|milliampere|microA|μA)\b/i',
        '/[\d,\.]+\s*(mA|milliampere|microA|μA)\b/i',
        
        // === PATTERN UNTUK SATUAN FREKUENSI TINGGI ===
        '/Max\s*[\d,\.]+\s*(kHz|kilohertz|MHz|megahertz|GHz|gigahertz)\b/i',
        '/Min\s*[\d,\.]+\s*(kHz|kilohertz|MHz|megahertz|GHz|gigahertz)\b/i',
        '/[\d,\.]+\s*(kHz|kilohertz|MHz|megahertz|GHz|gigahertz)\b/i',
        
        // === PATTERN UNTUK SATUAN VOLUME ===
        '/Max\s*[\d,\.]+\s*(L|liter|litre|ml|milliliter|cc)\b/i',
        '/Min\s*[\d,\.]+\s*(L|liter|litre|ml|milliliter|cc)\b/i',
        '/[\d,\.]+\s*(L|liter|litre|ml|milliliter|cc)\b/i'
    );
    
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $determination_standard, $matches)) {
            // Normalisasi output untuk derajat celsius
            $unit = $matches[1];
            if (preg_match('/(oC|ºC|degrees?[\s]*C|celsius)/i', $unit)) {
                return '°C'; // Standardisasi ke simbol derajat yang benar
            }
            // Normalisasi output untuk ampere
            elseif (preg_match('/(ampere|amp)/i', $unit)) {
                return 'A';
            }
            // Normalisasi output untuk hertz
            elseif (preg_match('/(hertz|frequency)/i', $unit)) {
                return 'Hz';
            }
            // Normalisasi output untuk volt
            elseif (preg_match('/(volt|volts|voltage)/i', $unit)) {
                return 'V';
            }
            // Normalisasi output untuk watt
            elseif (preg_match('/(watt|watts)/i', $unit)) {
                return 'W';
            }
            // Normalisasi output untuk kilowatt
            elseif (preg_match('/(kilowatt)/i', $unit)) {
                return 'kW';
            }
            // Normalisasi output untuk millimeter
            elseif (preg_match('/(millimeter)/i', $unit)) {
                return 'mm';
            }
            // Normalisasi output untuk centimeter
            elseif (preg_match('/(centimeter)/i', $unit)) {
                return 'cm';
            }
            // Normalisasi output untuk meter
            elseif (preg_match('/(meter)/i', $unit)) {
                return 'm';
            }
            // Normalisasi output untuk kilogram
            elseif (preg_match('/(kilogram)/i', $unit)) {
                return 'kg';
            }
            // Normalisasi output untuk gram
            elseif (preg_match('/(gram)/i', $unit)) {
                return 'g';
            }
            // Normalisasi output untuk RPM
            elseif (preg_match('/(revolution)/i', $unit)) {
                return 'rpm';
            }
            // Normalisasi output untuk milliampere
            elseif (preg_match('/(milliampere)/i', $unit)) {
                return 'mA';
            }
            // Normalisasi output untuk kilohertz
            elseif (preg_match('/(kilohertz)/i', $unit)) {
                return 'kHz';
            }
            // Normalisasi output untuk megahertz
            elseif (preg_match('/(megahertz)/i', $unit)) {
                return 'MHz';
            }
            // Normalisasi output untuk gigahertz
            elseif (preg_match('/(gigahertz)/i', $unit)) {
                return 'GHz';
            }
            // Normalisasi output untuk liter
            elseif (preg_match('/(liter|litre)/i', $unit)) {
                return 'L';
            }
            // Normalisasi output untuk milliliter
            elseif (preg_match('/(milliliter)/i', $unit)) {
                return 'ml';
            }
            // Normalisasi output untuk pascal
            elseif (preg_match('/(pascal)/i', $unit)) {
                return 'Pa';
            }
            else {
                return strtoupper($unit); // Return unit as-is tapi uppercase
            }
        }
    }
    
    return '';
}

public function get_existing_checksheet_dates()
{
    $section_id = $this->input->post('section_id');
    $machine_id = $this->input->post('machine_id');
    
    // Validasi input
    if (!$section_id || !$machine_id) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Section ID dan Machine ID harus diisi',
            'data' => []
        ]);
        return;
    }
    
    // Query untuk mendapatkan tanggal checksheet yang sudah ada
    $this->db->select('tgl_checksheet, users.nama as inspector_name');
    $this->db->from('equipment_inspection');
    $this->db->join('users', 'users.id_user = equipment_inspection.user_id', 'left');
    $this->db->where('equipment_inspection.section_id', $section_id);
    $this->db->where('equipment_inspection.machine_id', $machine_id);
    $this->db->order_by('equipment_inspection.tgl_checksheet', 'DESC');
    
    $result = $this->db->get();
    
    if ($result->num_rows() > 0) {
        $existing_dates = $result->result_array();
        
        // Format tanggal untuk tampilan yang lebih baik
        $formatted_dates = [];
        foreach ($existing_dates as $date) {
            $formatted_dates[] = [
                'tgl_checksheet' => $date['tgl_checksheet'],
                'inspector_name' => $date['inspector_name'],
                'formatted_date' => date('d F Y', strtotime($date['tgl_checksheet'])),
                'day_name' => date('l', strtotime($date['tgl_checksheet']))
            ];
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Data berhasil ditemukan',
            'data' => $formatted_dates,
            'total' => count($formatted_dates)
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'message' => 'Belum ada data checksheet untuk mesin ini',
            'data' => [],
            'total' => 0
        ]);
    }
}

/**
 * Validasi tanggal checksheet sebelum submit
 */
public function validate_checksheet_date()
{
    $section_id = $this->input->post('section_id');
    $machine_id = $this->input->post('machine_id');
    $tgl_checksheet = $this->input->post('tgl_checksheet');
    
    // Validasi input
    if (!$section_id || !$machine_id || !$tgl_checksheet) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Data tidak lengkap',
            'is_duplicate' => false
        ]);
        return;
    }
    
    // Cek apakah tanggal sudah ada
    $this->db->where('section_id', $section_id);
    $this->db->where('machine_id', $machine_id);
    $this->db->where('tgl_checksheet', $tgl_checksheet);
    $existing = $this->db->get('equipment_inspection');
    
    if ($existing->num_rows() > 0) {
        $existing_data = $existing->row_array();
        
        // Ambil nama inspector
        $inspector = $this->db->get_where('users', ['id_user' => $existing_data['user_id']])->row_array();
        
        echo json_encode([
            'status' => 'error',
            'message' => 'Tanggal checksheet sudah ada',
            'is_duplicate' => true,
            'existing_data' => [
                'tgl_checksheet' => $existing_data['tgl_checksheet'],
                'inspector' => $inspector['nama'],
                'formatted_date' => date('d F Y', strtotime($existing_data['tgl_checksheet']))
            ]
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'message' => 'Tanggal tersedia',
            'is_duplicate' => false
        ]);
    }
}

function table_with_units($id)
{
    // Get checksheet data
    $data = $this->M_checksheet->getDetail($id);
    
    if (empty($data)) {
        echo '<table><tr><td>No data found</td></tr></table>';
        return;
    }
    
    // Initialize rowspan arrays (sama seperti di table_detail.php)
    $partRowspan = array();
    $inspectionPartRowspan = array();
    
    // Count rowspan for parts and inspection parts
    foreach ($data as $row) {
        $part_id = $row['part_id'];
        $inspection_part_id = $row['inspection_part_id'];

        if (!isset($partRowspan[$part_id])) {
            $partRowspan[$part_id] = 1;
        } else {
            $partRowspan[$part_id]++;
        }
        if (!isset($inspectionPartRowspan[$part_id][$inspection_part_id])) {
            $inspectionPartRowspan[$part_id][$inspection_part_id] = 1;
        } else {
            $inspectionPartRowspan[$part_id][$inspection_part_id]++;
        }
    }
    
    // Generate table HTML (sama seperti di table_detail.php)
    echo '<table class="table table-bordered table-sm">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Part</th>';
    echo '<th>Inspection Part</th>';
    echo '<th>Item</th>';
    echo '<th>Method</th>';
    echo '<th>Determination Standard</th>';
    echo '<th>Measure Data</th>';
    echo '<th>Judgement</th>';
    echo '<th>Measure</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    $printedParts = array(); // To keep track of printed parts
    
    foreach ($data as $row) {
        $part_id = $row['part_id'];
        $inspection_part_id = $row['inspection_part_id'];
        
        echo '<tr>';
        
        // Part column with rowspan
        if (!in_array($part_id, $printedParts)) {
            echo '<td rowspan="' . $partRowspan[$part_id] . '">' . htmlspecialchars($row['part']) . '</td>';
            $printedParts[] = $part_id;
        }
        
        // Inspection Part column with rowspan
        if ($inspectionPartRowspan[$part_id][$inspection_part_id] !== false) {
            echo '<td rowspan="' . $inspectionPartRowspan[$part_id][$inspection_part_id] . '">' . htmlspecialchars($row['inspection_part']) . '</td>';
            $inspectionPartRowspan[$part_id][$inspection_part_id] = false; // Skip rowspan
        }
        
        echo '<td>' . htmlspecialchars($row['item']) . '</td>';
        echo '<td>' . htmlspecialchars($row['method']) . '</td>';
        echo '<td>' . htmlspecialchars($row['determination_standard']) . '</td>';
        
        // === MEASURE DATA DENGAN SATUAN ===
        $measure_data_display = $row['measure_data'];
        
        // Extract satuan dari determination standard jika measure data adalah angka
        if (!empty($row['determination_standard']) && is_numeric($measure_data_display)) {
            $determination = $row['determination_standard'];
            $extracted_unit = $this->extractUnitFromDetermination($determination);
            
            // Jika measure data adalah angka dan ada satuan, tampilkan dengan satuan
            if (!empty($measure_data_display) && !empty($extracted_unit)) {
                $measure_data_display = $measure_data_display . ' ' . $extracted_unit;
            }
        }
        
        echo '<td>' . htmlspecialchars($measure_data_display) . '</td>';
        
        // === JUDGEMENT DENGAN SIMBOL REPAIRED FIX ===
        $judgement_display = '';
        if ($row['judgement'] == 'No Abnormality') {
            $judgement_display = 'O';
        } elseif ($row['judgement'] == 'Cautious') {
            $judgement_display = '△'; // Menggunakan simbol Delta
        } elseif ($row['judgement'] == 'Abnormal') {
            $judgement_display = 'X';
        } elseif ($row['judgement'] == 'Repaired Fix') {
            $judgement_display = '⊘'; // Simbol Repaired Fix
        } else {
            $judgement_display = htmlspecialchars($row['judgement']);
        }
        echo '<td>' . $judgement_display . '</td>';
        
        echo '<td>' . htmlspecialchars($row['measure']) . '</td>';
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
}

// === FUNGSI HELPER UNTUK EKSTRAK SATUAN ===
private function extractUnitFromDetermination($determination_standard) {
    if (empty($determination_standard)) {
        return '';
    }
    
    // Pattern untuk mencari satuan yang umum digunakan
    $unit_patterns = [
        '/(\d+[,.]?\d*)\s*(A|Ampere|ampere)(?!\w)/i' => 'A',
        '/(\d+[,.]?\d*)\s*(°C|celsius|C|oC)(?!\w)/i' => '°C',
        '/(\d+[,.]?\d*)\s*(Hz|hertz)(?!\w)/i' => 'Hz',
        '/(\d+[,.]?\d*)\s*(V|volt|volts)(?!\w)/i' => 'V',
        '/(\d+[,.]?\d*)\s*(W|watt|watts)(?!\w)/i' => 'W',
        '/(\d+[,.]?\d*)\s*(mm|millimeter)(?!\w)/i' => 'mm',
        '/(\d+[,.]?\d*)\s*(cm|centimeter)(?!\w)/i' => 'cm',
        '/(\d+[,.]?\d*)\s*(m|meter)(?!\w)/i' => 'm',
        '/(\d+[,.]?\d*)\s*(kg|kilogram)(?!\w)/i' => 'kg',
        '/(\d+[,.]?\d*)\s*(g|gram)(?!\w)/i' => 'g',
        '/(\d+[,.]?\d*)\s*(bar|Bar)(?!\w)/i' => 'bar',
        '/(\d+[,.]?\d*)\s*(psi|PSI)(?!\w)/i' => 'psi',
        '/(\d+[,.]?\d*)\s*(rpm|RPM)(?!\w)/i' => 'rpm',
        '/(\d+[,.]?\d*)\s*(kW|kilowatt)(?!\w)/i' => 'kW',
        '/(\d+[,.]?\d*)\s*(mA|milliampere)(?!\w)/i' => 'mA',
        '/(\d+[,.]?\d*)\s*(kHz|kilohertz)(?!\w)/i' => 'kHz',
        '/(\d+[,.]?\d*)\s*(L|liter|litre)(?!\w)/i' => 'L',
        '/(\d+[,.]?\d*)\s*(ml|milliliter)(?!\w)/i' => 'ml',
        '/(\d+[,.]?\d*)\s*(Mpa|MPa|mpa)(?!\w)/i' => 'Mpa',
        '/(\d+[,.]?\d*)\s*(Kpa|KPa|kpa)(?!\w)/i' => 'Kpa',
        // Pattern untuk Max/Min dengan satuan
        '/Max\s*(\d+[,.]?\d*)\s*(A|°C|Hz|V|W|mm|cm|m|kg|g|bar|psi|rpm|kW|mA|kHz|L|ml|Mpa|Kpa)(?!\w)/i' => '$2',
        '/Min\s*(\d+[,.]?\d*)\s*(A|°C|Hz|V|W|mm|cm|m|kg|g|bar|psi|rpm|kW|mA|kHz|L|ml|Mpa|Kpa)(?!\w)/i' => '$2'
    ];
    
    foreach ($unit_patterns as $pattern => $unit) {
        if (preg_match($pattern, $determination_standard, $matches)) {
            if (strpos($unit, '$') !== false) {
                return $matches[2]; // Return captured group
            } else {
                return $unit;
            }
        }
    }
    
    return '';
}

private function cleanUnit($unit) {
    if (empty($unit)) {
        return '';
    }
    
    // Hapus karakter yang tidak diinginkan seperti Â
    $unit = str_replace(['Â', 'â'], '', $unit);
    
    // Pastikan encoding UTF-8
    if (!mb_check_encoding($unit, 'UTF-8')) {
        $unit = mb_convert_encoding($unit, 'UTF-8');
    }
    
    // Bersihkan dan normalisasi karakter khusus
    $unit = trim($unit);
    
    return $unit;
}

/**
 * Fungsi untuk ekstraksi satuan yang diperbaiki (mengganti extractUnitFromDeterminationSimple)
 */
private function extractUnitFromDeterminationFixed($determinationStandard) {
    if (empty($determinationStandard)) {
        return '';
    }
    
    // Pastikan string bersih dari karakter yang tidak diinginkan
    $determinationStandard = str_replace(['Â', 'â'], '', $determinationStandard);
    
    // Pattern untuk mencari satuan dengan prioritas pada pattern yang lebih spesifik
    $unitPatterns = [
        // === DERAJAT CELSIUS (PRIORITAS TERTINGGI) ===
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:°C|oC|ºC|degrees?\s*C|celsius)(?!\w)/i', 'unit' => '°C'],
        ['pattern' => '/Max\s*(\d+[,.]?\d*)\s*(?:°C|oC|ºC|degrees?\s*C|celsius)(?!\w)/i', 'unit' => '°C'],
        ['pattern' => '/Min\s*(\d+[,.]?\d*)\s*(?:°C|oC|ºC|degrees?\s*C|celsius)(?!\w)/i', 'unit' => '°C'],
        
        // === AMPERE ===
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:A|Ampere|ampere|amp)(?!\w)/i', 'unit' => 'A'],
        ['pattern' => '/Max\s*(\d+[,.]?\d*)\s*(?:A|Ampere|ampere|amp)(?!\w)/i', 'unit' => 'A'],
        ['pattern' => '/Min\s*(\d+[,.]?\d*)\s*(?:A|Ampere|ampere|amp)(?!\w)/i', 'unit' => 'A'],
        
        // === HERTZ ===
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:Hz|hertz)(?!\w)/i', 'unit' => 'Hz'],
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:kHz|kilohertz)(?!\w)/i', 'unit' => 'kHz'],
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:MHz|megahertz)(?!\w)/i', 'unit' => 'MHz'],
        ['pattern' => '/Max\s*(\d+[,.]?\d*)\s*(?:Hz|hertz|kHz|kilohertz|MHz|megahertz)(?!\w)/i', 'unit' => function($match) {
            if (preg_match('/kHz|kilohertz/i', $match[0])) return 'kHz';
            if (preg_match('/MHz|megahertz/i', $match[0])) return 'MHz';
            return 'Hz';
        }],
        ['pattern' => '/Min\s*(\d+[,.]?\d*)\s*(?:Hz|hertz|kHz|kilohertz|MHz|megahertz)(?!\w)/i', 'unit' => function($match) {
            if (preg_match('/kHz|kilohertz/i', $match[0])) return 'kHz';
            if (preg_match('/MHz|megahertz/i', $match[0])) return 'MHz';
            return 'Hz';
        }],
        
        // === VOLT ===
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:V|volt|volts|voltage)(?!\w)/i', 'unit' => 'V'],
        ['pattern' => '/Max\s*(\d+[,.]?\d*)\s*(?:V|volt|volts|voltage)(?!\w)/i', 'unit' => 'V'],
        ['pattern' => '/Min\s*(\d+[,.]?\d*)\s*(?:V|volt|volts|voltage)(?!\w)/i', 'unit' => 'V'],
        
        // === WATT ===
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:W|watt|watts)(?!\w)/i', 'unit' => 'W'],
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:kW|kilowatt)(?!\w)/i', 'unit' => 'kW'],
        ['pattern' => '/Max\s*(\d+[,.]?\d*)\s*(?:W|watt|watts|kW|kilowatt)(?!\w)/i', 'unit' => function($match) {
            if (preg_match('/kW|kilowatt/i', $match[0])) return 'kW';
            return 'W';
        }],
        ['pattern' => '/Min\s*(\d+[,.]?\d*)\s*(?:W|watt|watts|kW|kilowatt)(?!\w)/i', 'unit' => function($match) {
            if (preg_match('/kW|kilowatt/i', $match[0])) return 'kW';
            return 'W';
        }],
        
        // === PANJANG ===
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:mm|millimeter)(?!\w)/i', 'unit' => 'mm'],
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:cm|centimeter)(?!\w)/i', 'unit' => 'cm'],
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:\bm\b|meter)(?!\w)/i', 'unit' => 'm'],
        ['pattern' => '/Max\s*(\d+[,.]?\d*)\s*(?:mm|millimeter|cm|centimeter|\bm\b|meter)(?!\w)/i', 'unit' => function($match) {
            if (preg_match('/millimeter|mm/i', $match[0])) return 'mm';
            if (preg_match('/centimeter|cm/i', $match[0])) return 'cm';
            if (preg_match('/\bm\b|meter/i', $match[0])) return 'm';
            return 'mm';
        }],
        ['pattern' => '/Min\s*(\d+[,.]?\d*)\s*(?:mm|millimeter|cm|centimeter|\bm\b|meter)(?!\w)/i', 'unit' => function($match) {
            if (preg_match('/millimeter|mm/i', $match[0])) return 'mm';
            if (preg_match('/centimeter|cm/i', $match[0])) return 'cm';
            if (preg_match('/\bm\b|meter/i', $match[0])) return 'm';
            return 'mm';
        }],
        
        // === BERAT ===
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:kg|kilogram)(?!\w)/i', 'unit' => 'kg'],
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:\bg\b|gram)(?!\w)/i', 'unit' => 'g'],
        ['pattern' => '/Max\s*(\d+[,.]?\d*)\s*(?:kg|kilogram|\bg\b|gram)(?!\w)/i', 'unit' => function($match) {
            if (preg_match('/kg|kilogram/i', $match[0])) return 'kg';
            return 'g';
        }],
        ['pattern' => '/Min\s*(\d+[,.]?\d*)\s*(?:kg|kilogram|\bg\b|gram)(?!\w)/i', 'unit' => function($match) {
            if (preg_match('/kg|kilogram/i', $match[0])) return 'kg';
            return 'g';
        }],
        
        // === TEKANAN ===
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:bar|Bar)(?!\w)/i', 'unit' => 'bar'],
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:psi|PSI)(?!\w)/i', 'unit' => 'psi'],
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:Mpa|MPa|mpa)(?!\w)/i', 'unit' => 'Mpa'],
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:Kpa|KPa|kpa)(?!\w)/i', 'unit' => 'Kpa'],
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:pascal)(?!\w)/i', 'unit' => 'Pa'],
        ['pattern' => '/Max\s*(\d+[,.]?\d*)\s*(?:bar|Bar|psi|PSI|Mpa|MPa|mpa|Kpa|KPa|kpa|pascal)(?!\w)/i', 'unit' => function($match) {
            if (preg_match('/pascal/i', $match[0])) return 'Pa';
            if (preg_match('/Mpa|MPa|mpa/i', $match[0])) return 'Mpa';
            if (preg_match('/Kpa|KPa|kpa/i', $match[0])) return 'Kpa';
            if (preg_match('/bar|Bar/i', $match[0])) return 'bar';
            if (preg_match('/psi|PSI/i', $match[0])) return 'psi';
            return 'bar';
        }],
        ['pattern' => '/Min\s*(\d+[,.]?\d*)\s*(?:bar|Bar|psi|PSI|Mpa|MPa|mpa|Kpa|KPa|kpa|pascal)(?!\w)/i', 'unit' => function($match) {
            if (preg_match('/pascal/i', $match[0])) return 'Pa';
            if (preg_match('/Mpa|MPa|mpa/i', $match[0])) return 'Mpa';
            if (preg_match('/Kpa|KPa|kpa/i', $match[0])) return 'Kpa';
            if (preg_match('/bar|Bar/i', $match[0])) return 'bar';
            if (preg_match('/psi|PSI/i', $match[0])) return 'psi';
            return 'bar';
        }],
        
        // === RPM ===
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:rpm|RPM|revolution)(?!\w)/i', 'unit' => 'rpm'],
        ['pattern' => '/Max\s*(\d+[,.]?\d*)\s*(?:rpm|RPM|revolution)(?!\w)/i', 'unit' => 'rpm'],
        ['pattern' => '/Min\s*(\d+[,.]?\d*)\s*(?:rpm|RPM|revolution)(?!\w)/i', 'unit' => 'rpm'],
        
        // === MILLI AMPERE ===
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:mA|milliampere)(?!\w)/i', 'unit' => 'mA'],
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:microA|μA)(?!\w)/i', 'unit' => 'μA'],
        ['pattern' => '/Max\s*(\d+[,.]?\d*)\s*(?:mA|milliampere|microA|μA)(?!\w)/i', 'unit' => function($match) {
            if (preg_match('/milliampere|mA/i', $match[0])) return 'mA';
            if (preg_match('/microA|μA/i', $match[0])) return 'μA';
            return 'mA';
        }],
        ['pattern' => '/Min\s*(\d+[,.]?\d*)\s*(?:mA|milliampere|microA|μA)(?!\w)/i', 'unit' => function($match) {
            if (preg_match('/milliampere|mA/i', $match[0])) return 'mA';
            if (preg_match('/microA|μA/i', $match[0])) return 'μA';
            return 'mA';
        }],
        
        // === VOLUME ===
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:L|liter|litre)(?!\w)/i', 'unit' => 'L'],
        ['pattern' => '/(\d+[,.]?\d*)\s*(?:ml|milliliter|cc)(?!\w)/i', 'unit' => 'ml'],
        ['pattern' => '/Max\s*(\d+[,.]?\d*)\s*(?:L|liter|litre|ml|milliliter|cc)(?!\w)/i', 'unit' => function($match) {
            if (preg_match('/liter|litre|L/i', $match[0])) return 'L';
            if (preg_match('/milliliter|ml|cc/i', $match[0])) return 'ml';
            return 'L';
        }],
        ['pattern' => '/Min\s*(\d+[,.]?\d*)\s*(?:L|liter|litre|ml|milliliter|cc)(?!\w)/i', 'unit' => function($match) {
            if (preg_match('/liter|litre|L/i', $match[0])) return 'L';
            if (preg_match('/milliliter|ml|cc/i', $match[0])) return 'ml';
            return 'L';
        }]
    ];
    
    // Cari pattern yang cocok
    foreach ($unitPatterns as $pattern) {
        if (preg_match($pattern['pattern'], $determinationStandard, $matches)) {
            if (is_callable($pattern['unit'])) {
                $unit = $pattern['unit']($matches);
            } else {
                $unit = $pattern['unit'];
            }
            
            // Bersihkan unit dari karakter yang tidak diinginkan
            $unit = $this->cleanUnit($unit);
            
            return $unit;
        }
    }
    
    return '';
}

/**
 * Fungsi untuk menambahkan satuan pada measure data yang diperbaiki
 */
private function addUnitToMeasureDataFixed($measureData, $determinationStandard) {
    if (!$measureData || !is_numeric($measureData)) {
        return $measureData;
    }
    
    $unit = $this->extractUnitFromDeterminationFixed($determinationStandard);
    if (!empty($unit)) {
        // Pastikan tidak ada karakter yang tidak diinginkan
        $unit = $this->cleanUnit($unit);
        return $measureData . ' ' . $unit;
    }
    
    return $measureData;
}

/**
 * Fungsi table yang diperbaiki untuk mengganti function table() yang lama
 */
function table_fixed($id)
{
    // Get checksheet data
    $data = $this->M_checksheet->getDetail($id);
    
    if (empty($data)) {
        echo '<table><tr><td>No data found</td></tr></table>';
        return;
    }
    
    // Initialize rowspan arrays
    $partRowspan = array();
    $inspectionPartRowspan = array();
    
    // Count rowspan for parts and inspection parts
    foreach ($data as $row) {
        $part_id = $row['part_id'];
        $inspection_part_id = $row['inspection_part_id'];

        if (!isset($partRowspan[$part_id])) {
            $partRowspan[$part_id] = 1;
        } else {
            $partRowspan[$part_id]++;
        }
        if (!isset($inspectionPartRowspan[$part_id][$inspection_part_id])) {
            $inspectionPartRowspan[$part_id][$inspection_part_id] = 1;
        } else {
            $inspectionPartRowspan[$part_id][$inspection_part_id]++;
        }
    }
    
    // Generate table HTML
    echo '<table class="table table-bordered">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Part</th>';
    echo '<th>Inspection Part</th>';
    echo '<th>Item</th>';
    echo '<th>Method</th>';
    echo '<th>Determination Standard</th>';
    echo '<th>Measure Data</th>';
    echo '<th>Judgement</th>';
    echo '<th>Measure</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    $printedParts = array(); // To keep track of printed parts
    
    foreach ($data as $row) {
        $part_id = $row['part_id'];
        $inspection_part_id = $row['inspection_part_id'];
        
        echo '<tr>';
        
        // Part column with rowspan
        if (!in_array($part_id, $printedParts)) {
            echo '<td rowspan="' . $partRowspan[$part_id] . '">' . htmlspecialchars($row['part']) . '</td>';
            $printedParts[] = $part_id;
        }
        
        // Inspection Part column with rowspan
        if ($inspectionPartRowspan[$part_id][$inspection_part_id] !== false) {
            echo '<td rowspan="' . $inspectionPartRowspan[$part_id][$inspection_part_id] . '">' . htmlspecialchars($row['inspection_part']) . '</td>';
            $inspectionPartRowspan[$part_id][$inspection_part_id] = false; // Skip rowspan
        }
        
        echo '<td>' . htmlspecialchars($row['item']) . '</td>';
        echo '<td>' . htmlspecialchars($row['method']) . '</td>';
        echo '<td>' . htmlspecialchars($row['determination_standard']) . '</td>';
        
        // === MEASURE DATA DENGAN SATUAN (DIPERBAIKI) ===
        $measure_data_display = $row['measure_data'];
        
        // Extract satuan dari determination standard jika measure data adalah angka
        if (!empty($row['determination_standard']) && !empty($measure_data_display) && is_numeric($measure_data_display)) {
            $determination = $row['determination_standard'];
            $extracted_unit = $this->extractUnitFromDeterminationFixed($determination);
            
            // Jika measure data adalah angka dan ada satuan, tampilkan dengan satuan
            if (!empty($extracted_unit)) {
                $measure_data_display = $measure_data_display . ' ' . $extracted_unit;
            }
        }
        
        echo '<td>' . htmlspecialchars($measure_data_display) . '</td>';
        
        // === JUDGEMENT DENGAN SIMBOL REPAIRED FIX (DIPERBAIKI) ===
        $judgement_display = '';
        if ($row['judgement'] == 'No Abnormality') {
            $judgement_display = 'O';
        } elseif ($row['judgement'] == 'Cautious') {
            $judgement_display = 'Δ'; // Delta symbol yang benar
        } elseif ($row['judgement'] == 'Abnormal') {
            $judgement_display = 'X';
        } elseif ($row['judgement'] == 'Repaired Fix') {
            $judgement_display = '⊘'; // Repaired Fix symbol yang benar
        } else {
            $judgement_display = htmlspecialchars($row['judgement']);
        }
        echo '<td>' . $judgement_display . '</td>';
        
        echo '<td>' . htmlspecialchars($row['measure']) . '</td>';
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
}

/**
 * Fungsi table_with_units yang diperbaiki untuk mengganti function table_with_units() yang lama
 */
function table_with_units_fixed($id)
{
    // Get checksheet data
    $data = $this->M_checksheet->getDetail($id);
    
    if (empty($data)) {
        echo '<table><tr><td>No data found</td></tr></table>';
        return;
    }
    
    // Initialize rowspan arrays (sama seperti di table_detail.php)
    $partRowspan = array();
    $inspectionPartRowspan = array();
    
    // Count rowspan for parts and inspection parts
    foreach ($data as $row) {
        $part_id = $row['part_id'];
        $inspection_part_id = $row['inspection_part_id'];

        if (!isset($partRowspan[$part_id])) {
            $partRowspan[$part_id] = 1;
        } else {
            $partRowspan[$part_id]++;
        }
        if (!isset($inspectionPartRowspan[$part_id][$inspection_part_id])) {
            $inspectionPartRowspan[$part_id][$inspection_part_id] = 1;
        } else {
            $inspectionPartRowspan[$part_id][$inspection_part_id]++;
        }
    }
    
    // Generate table HTML (sama seperti di table_detail.php)
    echo '<table class="table table-bordered table-sm">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Part</th>';
    echo '<th>Inspection Part</th>';
    echo '<th>Item</th>';
    echo '<th>Method</th>';
    echo '<th>Determination Standard</th>';
    echo '<th>Measure Data</th>';
    echo '<th>Judgement</th>';
    echo '<th>Measure</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    $printedParts = array(); // To keep track of printed parts
    
    foreach ($data as $row) {
        $part_id = $row['part_id'];
        $inspection_part_id = $row['inspection_part_id'];
        
        echo '<tr>';
        
        // Part column with rowspan
        if (!in_array($part_id, $printedParts)) {
            echo '<td rowspan="' . $partRowspan[$part_id] . '">' . htmlspecialchars($row['part']) . '</td>';
            $printedParts[] = $part_id;
        }
        
        // Inspection Part column with rowspan
        if ($inspectionPartRowspan[$part_id][$inspection_part_id] !== false) {
            echo '<td rowspan="' . $inspectionPartRowspan[$part_id][$inspection_part_id] . '">' . htmlspecialchars($row['inspection_part']) . '</td>';
            $inspectionPartRowspan[$part_id][$inspection_part_id] = false; // Skip rowspan
        }
        
        echo '<td>' . htmlspecialchars($row['item']) . '</td>';
        echo '<td>' . htmlspecialchars($row['method']) . '</td>';
        echo '<td>' . htmlspecialchars($row['determination_standard']) . '</td>';
        
        // === MEASURE DATA DENGAN SATUAN (DIPERBAIKI) ===
        $measure_data_display = $row['measure_data'];
        
        // Extract satuan dari determination standard jika measure data adalah angka
        if (!empty($row['determination_standard']) && is_numeric($measure_data_display)) {
            $determination = $row['determination_standard'];
            $extracted_unit = $this->extractUnitFromDeterminationFixed($determination);
            
            // Jika measure data adalah angka dan ada satuan, tampilkan dengan satuan
            if (!empty($measure_data_display) && !empty($extracted_unit)) {
                $measure_data_display = $measure_data_display . ' ' . $extracted_unit;
            }
        }
        
        echo '<td>' . htmlspecialchars($measure_data_display) . '</td>';
        
        // === JUDGEMENT DENGAN SIMBOL REPAIRED FIX (DIPERBAIKI) ===
        $judgement_display = '';
        if ($row['judgement'] == 'No Abnormality') {
            $judgement_display = 'O';
        } elseif ($row['judgement'] == 'Cautious') {
            $judgement_display = 'Δ'; // Menggunakan simbol Delta yang benar
        } elseif ($row['judgement'] == 'Abnormal') {
            $judgement_display = 'X';
        } elseif ($row['judgement'] == 'Repaired Fix') {
            $judgement_display = '⊘'; // Simbol Repaired Fix yang benar
        } else {
            $judgement_display = htmlspecialchars($row['judgement']);
        }
        echo '<td>' . $judgement_display . '</td>';
        
        echo '<td>' . htmlspecialchars($row['measure']) . '</td>';
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
}


/**
 * Fungsi untuk menghitung status pemeriksaan mesin
 */
private function calculateInspectionStatus($section_id, $machine_id) {
    try {
        // Ambil pemeriksaan terakhir untuk mesin ini
        $this->db->select('MAX(tgl_checksheet) as last_inspection');
        $this->db->from('equipment_inspection');
        $this->db->where('section_id', $section_id);
        $this->db->where('machine_id', $machine_id);
        $this->db->where('step_proses >=', 2); // Hanya yang sudah selesai
        $query = $this->db->get();
        
        $result = $query->row();
        
        if (!$result || !$result->last_inspection) {
            return [
                'status' => 'belum_pernah',
                'status_text' => 'Belum Pernah Diperiksa',
                'status_class' => 'badge-danger',
                'last_inspection' => null,
                'next_inspection' => null,
                'days_remaining' => null,
                'alert_type' => 'urgent'
            ];
        }
        
        $last_inspection = new DateTime($result->last_inspection);
        $now = new DateTime();
        $next_inspection = clone $last_inspection;
        $next_inspection->add(new DateInterval('P6M')); // Tambah 6 bulan
        
        // Hitung sisa hari
        $interval = $now->diff($next_inspection);
        $days_remaining = $interval->invert ? -$interval->days : $interval->days;
        
        // Tentukan status berdasarkan sisa hari
        if ($days_remaining < 0) {
            // Sudah lewat jadwal
            return [
                'status' => 'terlambat',
                'status_text' => 'Terlambat ' . abs($days_remaining) . ' hari',
                'status_class' => 'badge-danger blink-animation',
                'last_inspection' => $last_inspection->format('d M Y'),
                'next_inspection' => $next_inspection->format('d M Y'),
                'days_remaining' => $days_remaining,
                'alert_type' => 'urgent'
            ];
        } elseif ($days_remaining <= 30) {
            // Dalam 1 bulan akan jatuh tempo
            return [
                'status' => 'segera_jatuh_tempo',
                'status_text' => 'Segera Jatuh Tempo (' . $days_remaining . ' hari)',
                'status_class' => 'badge-warning',
                'last_inspection' => $last_inspection->format('d M Y'),
                'next_inspection' => $next_inspection->format('d M Y'),
                'days_remaining' => $days_remaining,
                'alert_type' => 'warning'
            ];
        } elseif ($days_remaining <= 90) {
            // Dalam 3 bulan akan jatuh tempo
            return [
                'status' => 'akan_jatuh_tempo',
                'status_text' => 'Akan Jatuh Tempo (' . $days_remaining . ' hari)',
                'status_class' => 'badge-info',
                'last_inspection' => $last_inspection->format('d M Y'),
                'next_inspection' => $next_inspection->format('d M Y'),
                'days_remaining' => $days_remaining,
                'alert_type' => 'info'
            ];
        } else {
            // Masih aman
            return [
                'status' => 'aman',
                'status_text' => 'Aman (' . $days_remaining . ' hari)',
                'status_class' => 'badge-success',
                'last_inspection' => $last_inspection->format('d M Y'),
                'next_inspection' => $next_inspection->format('d M Y'),
                'days_remaining' => $days_remaining,
                'alert_type' => 'success'
            ];
        }
    } catch (Exception $e) {
        log_message('error', 'Error calculating inspection status: ' . $e->getMessage());
        return [
            'status' => 'error',
            'status_text' => 'Error',
            'status_class' => 'badge-secondary',
            'last_inspection' => null,
            'next_inspection' => null,
            'days_remaining' => null,
            'alert_type' => 'error'
        ];
    }
}

}

/* End of file Checksheet.php */
