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

    // function section($name)
    // {
    //     if (!$name) {
    //         redirect('dashboard');
    //     }
    //     $list_rank = $this->M_checksheet->get_rank_list();
    //     $rank = $this->input->get('rank');
    //     if ($rank == '') {
    //         $rank = $list_rank[0]['rank'];
    //     }
    //     $this->db->where('rank', $rank);
    //     $section = $this->db->get_where('section', ['section_name' => $name])->row_array();
    //     $data = [
    //         'judul' => 'Equipment Inspection',
    //         'data' => $this->M_checksheet->getMachineId($section['id'], $rank),
    //         'section' => $section,
    //         'machine' => $this->M_checksheet->getMachine(),
    //         'list_rank' => $list_rank,
    //         'rank' => $rank
    //     ];
    //     $this->load->view('checksheet/section', $data);
    // }

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
        $data = $this->M_checksheet->getMachineId($section['section_name']);
        // Ambil semua machine yang tersedia untuk dropdown
        $machine = $this->M_checksheet->getMachine();

        // Set default rank atau ambil dari database
        // Opsi 1: Set rank default
        // $rank = 1; // atau nilai default lainnya

        // Opsi 2: Ambil dari tabel section jika ada field rank
     $rank = isset($section['rank']) ? $section['rank'] : 1;

    // Opsi 3: Ambil rank tertinggi + 1 untuk section ini
    // $this->db->select_max('rank');
    // $this->db->where('section_id', $section['id']);
    // $max_rank = $this->db->get('equipment_inspection')->row_array();
    // $rank = ($max_rank['rank'] ? $max_rank['rank'] + 1 : 1);
    
        // Persiapkan data untuk dikirim ke view
        $pageData = [
            'judul' => 'Equipment Inspection',
            'data' => $data,
            'section' => $section,
            'machine' => $machine,
        'rank' => $rank
        ];
    
        // Menampilkan halaman dengan data
        $this->load->view('checksheet/section', $pageData);
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
        // $checksheet = $this->M_checksheet->get_checksheet($id);
        $tgl_checksheet = $this->input->post('tgl_checksheet');
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
                }
            }
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

    // public function edit_detail_part() {
    //     $id = $this->input->post('id');
    //     $measure_data = $this->input->post('measure_data');
    //     $judgement = $this->input->post('judgement');
    //     $measure = $this->input->post('measure');
    //     $csrf_token = $this->input->post($this->security->get_csrf_token_name());
    
    //     // Validate CSRF token
    //     if ($csrf_token !== $this->security->get_csrf_hash()) {
    //         echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token']);
    //         return;
    //     }
    
    //     // Update the record in the database
    //     $this->db->where('id', $id);
    //     $this->db->update('equpment_inspection_part_detail', [
    //         'measure_data' => $measure_data,
    //         'judgement' => $judgement,
    //         'measure' => $measure
    //     ]);
    
    //     echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui']);
    // }
    
    // public function edit_all_details() {
    //     $changes = $this->input->post('changes');
    //     $csrf_token = $this->input->post($this->security->get_csrf_token_name());
    
    //     // Validate CSRF token
    //     if ($csrf_token !== $this->security->get_csrf_hash()) {
    //         echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token']);
    //         return;
    //     }
    
    //     // Decode changes
    //     $changes = json_decode($changes, true);
    
    //     if (is_array($changes)) {
    //         foreach ($changes as $change) {
    //             $this->db->where('id', $change['id']);
    //             $this->db->update('your_table_name', [
    //                 'measure_data' => $change['measure_data'],
    //                 'judgement' => $change['judgement'],
    //                 'measure' => $change['measure']
    //             ]);
    //         }
    //         echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui']);
    //     } else {
    //         echo json_encode(['status' => 'error', 'message' => 'Invalid data format']);
    //     }
    // }

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
    
    
    // public function edit_all_details() {
    //     if ($this->input->server('REQUEST_METHOD') == 'POST') {
    //         // Ambil data JSON dari request
    //         $changes = $this->input->post('changes');
    //         $changes = json_decode($changes, true);

    //         // Validasi dan proses data
    //         if (is_array($changes) && !empty($changes)) {
    //             $this->load->model('M_checksheet');
    //             $result = $this->M_checksheet->update_details($changes);

    //             // Kirimkan respons JSON
    //             if ($result) {
    //                 $response = array('status' => 'success', 'message' => 'Data berhasil diupdate');
    //             } else {
    //                 $response = array('status' => 'error', 'message' => 'Terjadi kesalahan saat menyimpan data');
    //             }
    //         } else {
    //             $response = array('status' => 'error', 'message' => 'Data tidak valid');
    //         }

    //         echo json_encode($response);
    //     } else {
    //         // Respons jika bukan metode POST
    //         $response = array('status' => 'error', 'message' => 'Invalid request method');
    //         echo json_encode($response);
    //     }
    // }

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
            ->setCellValue('G2', 'Inspection day')
            ->setCellValue('H2', date('d F Y', strtotime($data['tgl_checksheet'])))
            ->setCellValue('G3', 'Inspector')
            ->setCellValue('H3', $data['nama'])
            ->setCellValue('G4', 'Judgement')
            ->setCellValue('H4', 'O : No Abnormality')
            ->setCellValue('H5', 'λ : Cautious')
            ->setCellValue('H6', 'X : Abnormality');
        // give bold and font size 20 to a1
        $spreadsheet->getActiveSheet(0)->getStyle('A1')->getFont()->setBold(true)->setSize(20);

        $spreadsheet->getActiveSheet()->mergeCells("A6:B6");
        $spreadsheet->getActiveSheet()->mergeCells("A7:B7");
        $spreadsheet->getActiveSheet()->mergeCells("A8:B8");

        $spreadsheet->getActiveSheet()->getStyle("A6:C8")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle("D8:G8")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // bold and center in A6, A7, A8
        $spreadsheet->getActiveSheet()->getStyle("A6:E8")->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle("F8:G8")->getFont()->setBold(true);


        $url = base_url('api/table/' . $id);
        $file = file_get_contents($url);
        $dom = new DOMDocument();
        @$dom->loadHTML($file);
        $xpath = new DOMXPath($dom);
        $elements = $xpath->query('//table');
        $table = $elements[0]->C14N();
        // $spreadsheet->getActiveSheet()->fromArray(
        //     explode("\t", $table),
        //     NULL,
        //     'A10'
        // );

        $spreadsheet = $reader->loadFromString($table, $spreadsheet);

        // find how many <tr> in table
        $tr = $xpath->query('//tr');
        $tr_length = $tr->length;
        (int)$row_after = 10 + (int)$tr_length + 2;
        $row_after_1 = 10 + (int)$tr_length - 1;
        $spreadsheet->getActiveSheet()->getStyle("A10:H$row_after_1")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        //set widht in column A until H
        $a = range('A', 'H');
        $size = [
            'A' => 18,
            'B' => 16,
            'C' => 30,
            'D' => 29,
            'E' => 34,
            'F' => 14,
            'G' => 14,
            'H' => 14
        ];
        foreach ($a as $key) {
            $spreadsheet->getActiveSheet()->getColumnDimension($key)->setWidth($size[$key]);
        }

        if ($img_checksheet1 != null || $img_checksheet1 != '') {
            // the first image is from base64
            $imageData1 = base64_decode($img_checksheet1);
            $imagePath1 = APPPATH . '../assets/image.jpg';
            file_put_contents($imagePath1, $imageData1);

            // image1 to excel
            // set width and height
            $drawing->setPath($imagePath1);
            $drawing->setWidthAndHeight(200, 200);
            $drawing->setCoordinates("A$row_after");
            $drawing->setWorksheet($spreadsheet->getActiveSheet());
        }

        if ($img_checksheet2 != null || $img_checksheet2 != '') {
            // // the second image is from base64
            $imageData2 = base64_decode($img_checksheet2);
            $imagePath2 = APPPATH . '../assets/image1.jpg';
            file_put_contents($imagePath2, $imageData2);

            // image2 to excel
            // set width and height
            $drawing2->setPath($imagePath2);
            $drawing2->setWidthAndHeight(200, 200);
            $drawing2->setCoordinates("C$row_after");
            $drawing2->setWorksheet($spreadsheet->getActiveSheet());
        }

        if ($img_checksheet3 != null || $img_checksheet3 != '') {
            $imageData3 = base64_decode($img_checksheet3);
            $imagePath3 = APPPATH . '../assets/image2.jpg';
            file_put_contents($imagePath3, $imageData3);

            // image3 to excel
            // set width and height
            $row_img1 = $row_after + 11;
            $drawing3->setPath($imagePath3);
            $drawing3->setWidthAndHeight(200, 200);
            $drawing3->setCoordinates("B$row_img1");
            $drawing3->setWorksheet($spreadsheet->getActiveSheet());
        }

        $cell_plus1 = $row_after + 1;
        $cell_plus7 = $row_after + 7;
        $cell_plus3 = $row_after + 3;
        $cell_plus9 = $row_after + 9;
        $cell_plus6 = $row_after + 6;
        $cell_plus10 = $row_after + 10;
        $cell_plus11 = $row_after + 11;
        $cell_plus13 = $row_after + 13;
        $cell_plus14 = $row_after + 14;

        $spreadsheet->getActiveSheet()->mergeCells("E$cell_plus1:E$cell_plus3");
        $spreadsheet->getActiveSheet()->mergeCells("E$cell_plus7:E$cell_plus9");
        //wrap text in E20 and E27
        $spreadsheet->getActiveSheet()->getStyle("E$cell_plus1")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("E$cell_plus7")->getAlignment()->setWrapText(true);
        //set top align in E20 and E27
        $spreadsheet->getActiveSheet()->getStyle("E$cell_plus1")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        $spreadsheet->getActiveSheet()->getStyle("E$cell_plus7")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E$row_after", "Inspection item of addition");
        //bold
        $spreadsheet->getActiveSheet()->getStyle("E$row_after")->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E$cell_plus1", $additional_item);
        //merge E20:E23
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E$cell_plus6", "Purchase Part");
        //bold
        $spreadsheet->getActiveSheet()->getStyle("E$cell_plus6")->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E$cell_plus7", $purchase_part);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue("F$cell_plus10", "Inspector");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("G$cell_plus10", "Supervisor");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("H$cell_plus10", "Manager");
        //bold
        $spreadsheet->getActiveSheet()->getStyle("F$cell_plus10:H$cell_plus10")->getFont()->setBold(true);

        // merge f32:f34
        $spreadsheet->getActiveSheet()->mergeCells("F$cell_plus11:F$cell_plus13");
        $spreadsheet->getActiveSheet()->mergeCells("G$cell_plus11:G$cell_plus13");
        $spreadsheet->getActiveSheet()->mergeCells("H$cell_plus11:H$cell_plus13");
        $spreadsheet->getActiveSheet()->getStyle("F$cell_plus10:H$cell_plus14")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $drawing4 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing5 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing6 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

        $img_ttd_inspector = $ttd['ttd_inspector'];
        $img_ttd_supervisor = $ttd['ttd_supervisor'];
        $img_ttd_manager = $ttd['ttd_manager'];

        if ($img_ttd_inspector != null || $img_ttd_inspector != '') {
            $imageData4 = base64_decode($img_ttd_inspector);
            $imagePath4 = APPPATH . '../assets/ttd_inspector.jpg';
            file_put_contents($imagePath4, $imageData4);

            // image4 to excel
            // set width and height
            $drawing4->setPath($imagePath4);
            $drawing4->setWidthAndHeight(75, 75);
            $drawing4->setCoordinates("F$cell_plus11");
            $drawing4->setWorksheet($spreadsheet->getActiveSheet());
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("F$cell_plus14", $ttd['nama_inspector']);
        }

        if ($img_ttd_supervisor != null || $img_ttd_supervisor != '') {
            $imageData5 = base64_decode($img_ttd_supervisor);
            $imagePath5 = APPPATH . '../assets/ttd_supervisor.jpg';
            file_put_contents($imagePath5, $imageData5);

            // image5 to excel
            // set width and height
            $drawing5->setPath($imagePath5);
            $drawing5->setWidthAndHeight(75, 75);
            $drawing5->setCoordinates("G$cell_plus11");
            $drawing5->setWorksheet($spreadsheet->getActiveSheet());
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("G$cell_plus14", $ttd['nama_supervisor']);
        }

        if ($img_ttd_manager != null || $img_ttd_manager != '') {
            $imageData6 = base64_decode($img_ttd_manager);
            $imagePath6 = APPPATH . '../assets/ttd_manager.jpg';
            file_put_contents($imagePath6, $imageData6);

            // image6 to excel
            // set width and height
            $drawing6->setPath($imagePath6);
            $drawing6->setWidthAndHeight(75, 75);
            $drawing6->setCoordinates("H$cell_plus11");
            $drawing6->setWorksheet($spreadsheet->getActiveSheet());
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("H$cell_plus14", $ttd['nama_manager']);
        }


        $spreadsheet->getActiveSheet()->getStyle("A10:H10")->getFont()->setBold(true);

        $writer = new Xlsx($spreadsheet);
        $filename = $data['machine_name'] . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
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

}

/* End of file Checksheet.php */
