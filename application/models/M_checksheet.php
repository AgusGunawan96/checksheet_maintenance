<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_checksheet extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        $this->db->join('section as s', 's.id = a.section_id');
        $this->db->join('machine as m', 'm.id = a.machine_id');
        return $this->db->get('equipment_inspection as a')->result_array();
    }

    function getSection()
    {
        return $this->db->get('section')->result_array();
    }

    function getMachine()
    {
        return $this->db->get('machine')->result_array();
    }

    // function getMachineId($id, $rank)
    // {
    //     $level = $this->session->userdata('level');
    //     $this->db->select('a.*, m.machine_name');
    //     $this->db->join('machine as m', 'm.id = a.machine_id');
    //     $this->db->join('section as s', 's.id = a.section_id');
    //     $this->db->where('a.section_id', $id);
    //     $this->db->where('s.rank', $rank);
    //     // $this->db->where('step_proses', $level);
    //     $this->db->group_by('machine_id');
    //     return $this->db->get('equipment_inspection as a')->result_array();
    // }

    public function getMachineId($section_name)
    {
        // Jika section_name kosong, kembalikan array kosong
        if (empty($section_name)) {
            return [];
        }
    
        $this->db->select('a.id, a.machine_id, m.machine_name, s.section_name, s.id as section_id'); // Add a.id
    $this->db->from('equipment_inspection as a');
    $this->db->join('machine as m', 'm.id = a.machine_id');
    $this->db->join('section as s', 's.id = a.section_id');
    $this->db->where('s.section_name', $section_name);

    // Group by machine_id dan machine_name untuk menghindari duplikasi
    $this->db->group_by('a.machine_id, m.machine_name, s.id, s.section_name');
    
        // Menjalankan query
        $query = $this->db->get();
    
        // Mengambil hasil query dalam bentuk array
        $result = $query->result_array();
    
        // Mengembalikan hasilnya
        return $result;
    }
    


    function getDetail($id)
    {
        $this->db->select('inspection_part_id, item, method, inspection_part, part, c.id as part_id, determination_standard, measure_data, judgement, measure, a.id');
        $this->db->join('equpment_inspection_inspection_part as b', 'b.id = a.inspection_part_id');
        $this->db->join('equpment_inspection_part as c', 'c ON c.id = b.part_id');
        $this->db->where('c.eq_id', $id);
        $this->db->order_by('a.inspection_part_id', 'asc');
        return $this->db->get('equpment_inspection_part_detail as a')->result_array();
    }

    function get_checksheet($id)
    {
        $this->db->where('a.id', $id);
        return $this->db->get('equipment_inspection as a')->row_array();
    }

    function getPart($id)
    {
        $this->db->where('eq_id', $id);
        return $this->db->get('equpment_inspection_part')->result_array();
    }

    function getDetailPart($id)
    {
        // $this->db->join('equpment_inspection_part as p', 'p.id = a.eq_part_id');
        $this->db->select('a.*, b.inspection_part, c.part, c.eq_id');
        $this->db->join('equpment_inspection_inspection_part as b', 'b.id = a.inspection_part_id');
        $this->db->join('equpment_inspection_part as c', 'c ON c.id = b.part_id');
        $this->db->where('a.id', $id);
        return $this->db->get('equpment_inspection_part_detail as a')->row_array();
    }
    function getDetailPartId($id)
    {
        // $this->db->join('equpment_inspection_part as p', 'p.id = a.eq_part_id');
        $this->db->where('a.id', $id);
        return $this->db->get('equpment_inspection_part_detail as a')->row_array();
    }

    function getLevel($step_proses)
    {
        $this->db->where('id_level > ', $step_proses);
        return $this->db->get('user_level')->result_array();
    }

    function getEquipmentInspectionbyId($section_id, $machine_id)
    {
        // $this->db->join('section as s', 's.id = a.section_id');
        $this->db->select('a.*, m.machine_name, m.equipment_no, m.cycle, u.nama as nama_user');

        $this->db->join('machine as m', 'm.id = a.machine_id');
        $this->db->join('users as u', 'u.id_user = a.user_id');
        $this->db->where('a.section_id', $section_id);
        $this->db->where('a.machine_id', $machine_id);

        return $this->db->get('equipment_inspection as a')->result_array();
    }

    function get_rank_list()
    {
        $this->db->select('rank');
        $this->db->group_by('rank');
        return $this->db->get('section')->result_array();
    }

    function getPartsEq($eq_id)
    {
        $this->db->where('eq_id', $eq_id);
        return $this->db->get('equpment_inspection_part')->result_array();
    }

    function getInspectionPart($id)
    {
        $this->db->where('part_id', $id);
        return $this->db->get('equpment_inspection_inspection_part')->result_array();
    }

    function getEq($id)
    {
        $this->db->join('section as b', 'b.id = a.section_id');
        $this->db->join('machine as c', 'c.id = a.machine_id');
        $this->db->join('users as u', 'u.id_user = a.user_id');
        $this->db->where('a.id', $id);
        $eq = $this->db->get('equipment_inspection as a')->row_array();
        return $eq;
    }

    function getEqbySectionMachine($section_id, $machine_id)
    {
        $this->db->where('section_id', $section_id);
        $this->db->where('machine_id', $machine_id);
        $this->db->order_by('id', 'desc');
        return $this->db->get('equipment_inspection')->row_array();
    }

    function getIdPenerima($id_user)
    {
        $this->db->where('id_user', $id_user);
        return $this->db->get('users')->row_array()['id_user'];
    }

    function getSectionName($section_id)
    {
        $this->db->where('id', $section_id);
        return $this->db->get('section')->row_array()['section_name'];
    }

    function getMachineName($machine_id)
    {
        $this->db->where('id', $machine_id);
        return $this->db->get('machine')->row_array()['machine_name'];
    }

    function saveNotif($id_penerima, $message, $link, $eq_id)
    {
        $data = array(
            'user_id' => $id_penerima,
            'deskripsi' => $message,
            'link' => $link,
            'eq_id' => $eq_id
        );
        return $this->db->insert('notifikasi', $data);
    }

    function findChecksheet($tgl_checksheet, $section_id, $machine_id, $item, $method, $determination_standard)
    {
        $this->db->where('tgl_checksheet', $tgl_checksheet);
        $this->db->where('section_id', $section_id);
        $this->db->where('machine_id', $machine_id);
        $this->db->where('item', $item);
        $this->db->where('method', $method);
        $this->db->where('determination_standard', $determination_standard);
        return $this->db->get('view_eq')->row_array();
    }

    function getAllDetailPart($inspection_part_id)
    {
        return $this->db->get_where('equpment_inspection_part_detail', ['inspection_part_id' => $inspection_part_id])->result_array();
    }

    function get_ttd_user($user_id)
    {
        $this->db->where('inspector', $user_id);
        $return = $this->db->get('user_management')->row_array();

        $data = [
            'id_manager' => $return['manager'],
            'id_supervisor' => $return['supervisor'],
            'id_inspector' => $return['inspector'],
        ];

        $ttd_inspector = $this->db->get_where('users', ['id_user' => $data['id_inspector']])->row_array();
        $ttd_supervisor = $this->db->get_where('users', ['id_user' => $data['id_supervisor']])->row_array();
        $ttd_manager = $this->db->get_where('users', ['id_user' => $data['id_manager']])->row_array();

        return [
            'nama_inspector' => $ttd_inspector['nama'],
            'ttd_inspector' => str_replace('data:image/png;base64,', '', $ttd_inspector['ttd']),
            'nama_supervisor' => $ttd_supervisor['nama'],
            'ttd_supervisor' => str_replace('data:image/png;base64,', '', $ttd_supervisor['ttd']),
            'nama_manager' => $ttd_manager['nama'],
            'ttd_manager' => str_replace('data:image/png;base64,', '', $ttd_manager['ttd'])
        ];
    }


    function get_last_eq($section_id, $machine_id, $id)
    {
        $this->db->where('section_id', $section_id);
        $this->db->where('machine_id', $machine_id);
        $this->db->where('id <>', $id);
        $this->db->order_by('id', 'desc');
        return $this->db->get('equipment_inspection')->result_array();
    }

    function getReportAbnormal($tanggal, $user_id)
    {
        $this->db->select('section_id, machine_name as nama_mesin, CONCAT(part, " - ", inspection_part, " - ", item) as kejanggalan, user_id, tgl_checksheet');
        $this->db->where('user_id', $user_id);
        $this->db->where('tgl_checksheet', $tanggal);
        $this->db->where('judgement', 'Abnormal');
        return $this->db->get('view_eq')->result_array();
    }

    function insertDataAbnormal($data, $tgl_checksheet, $user_id)
    {
        // cek kesesuaian data antara $data dengan abnormal, jika ada data yang tidak ada di $data, maka hapus data tersebut
        if (!empty($data)) {
            $this->db->where('tgl_checksheet', $tgl_checksheet);
            $this->db->where('user_id', $user_id);
            $this->db->where_not_in('kejanggalan', array_column($data, 'kejanggalan'));
            $this->db->delete('report_abnormal');
        }
        foreach ($data as $row) {
            $check = $this->db->get_where('report_abnormal', ['tgl_checksheet' => $tgl_checksheet, 'user_id' => $user_id, 'section_id' => $row['section_id'], 'nama_mesin' => $row['nama_mesin'], 'kejanggalan' => $row['kejanggalan']])->row_array();
            if (!$check) {
                $this->db->insert('report_abnormal', $row);
            }
        }
    }

    function getDataAbnormal($tanggal, $user_id)
    {
        $this->db->where('tgl_checksheet', $tanggal);
        $this->db->where('user_id', $user_id);
        return $this->db->get('report_abnormal')->result_array();
    }

    function getUser()
    {
        $level = $this->session->userdata('level');
        if ($level == 2) {
            $this->db->where('id_user', $this->session->userdata('id_user'));
        } else {
            $this->db->where('level', 2);
        }
        return $this->db->get('users')->result_array();
    }

    function getTglChecksheet($user_id)
    {
        $this->db->select('tgl_checksheet');
        $this->db->where_in('user_id', $user_id);
        $this->db->group_by('tgl_checksheet');
        return $this->db->get('view_eq')->result_array();
    }

    function getReport($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('report_abnormal')->row_array();
    }

    function getSectionbyId($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('section')->row_array();
    }

    function getProgressChecksheet($tanggal, $user_id, $section)
    {
        $this->db->select('a.*, m.machine_name, m.equipment_no, m.cycle, u.nama as nama_user, s.section_name, m.document_no');
        $this->db->join('machine as m', 'm.id = a.machine_id');
        $this->db->join('users as u', 'u.id_user = a.user_id');
        $this->db->join('section as s', 's.id = a.section_id');
        $this->db->where_in('a.tgl_checksheet', $tanggal);
        $this->db->where_in('a.user_id', $user_id);
        $this->db->where_in('a.section_id', $section);
        return $this->db->get('equipment_inspection as a')->result_array();
    }

    function read_notif($id)
    {
        $level = $this->session->userdata('level');
        $user_id = $this->session->userdata('id_user');
        if ($level == 4) {
            $this->db->where('eq_id', $id);
            $this->db->where('user_id', $user_id);
            return $this->db->update('notifikasi', ['is_read' => 1]);
        }
    }

    // public function update_details($changes) {
    //     $this->db->trans_start();
    //     foreach ($changes as $change) {
    //         $this->db->where('id', $change['id']);
    //         $this->db->update('equpment_inspection_part_detail', array(
    //             'measure_data' => $change['measure_data'],
    //             'judgement' => $change['judgement'],
    //             'measure' => $change['measure']
    //         ));
    //     }
    //     $this->db->trans_complete();

    //     return $this->db->trans_status();
    // }

    // public function update_details($changes)
    // {
    //     $this->db->trans_start(); // Mulai transaksi
    
    //     foreach ($changes as $change) {
    //         // Debug: Tampilkan data yang akan diperbarui
    //         log_message('debug', 'Updating row ID: ' . $change['id'] . ' with data: ' . print_r($change, true));
    
    //         $this->db->where('id', $change['id']);
    //         $this->db->update('equpment_inspection_part_detail', array(
    //             'measure_data' => $change['measure_data'],
    //             'judgement' => $change['judgement'],
    //             'measure' => $change['measure']
    //         ));
    //     }
    
    //     $this->db->trans_complete(); // Akhiri transaksi
    
    //     // Debug: Periksa apakah transaksi berhasil
    //     if ($this->db->trans_status()) {
    //         log_message('debug', 'Update sukses');
    //         return true;
    //     } else {
    //         log_message('error', 'Update gagal');
    //         return false;
    //     }
    // }

    // public function update_details($changes)
    // {
    //     // Mulai transaksi
    //     $this->db->trans_start();
    
    //     // Loop untuk memperbarui setiap perubahan
    //     foreach ($changes as $change) {
    //         // Validasi data, pastikan semua field ada
    //         if (!isset($change['id'], $change['measure_data'], $change['judgement'], $change['measure'])) {
    //             log_message('error', 'Data tidak valid untuk ID: ' . print_r($change, true));
    //             continue; // Skip jika data tidak valid
    //         }
    
    //         // Debug: Log perubahan yang akan dilakukan
    //         log_message('debug', 'Updating row ID: ' . $change['id'] . ' with data: ' . print_r($change, true));
    
    //         // Update data di database
    //         $this->db->where('id', $change['id']);
    //         $update = $this->db->update('equpment_inspection_part_detail', array(
    //             'measure_data' => $change['measure_data'],
    //             'judgement' => $change['judgement'],
    //             'measure' => $change['measure']
    //         ));
    
    //         // Debug: Log query yang dijalankan
    //         log_message('debug', 'Query: ' . $this->db->last_query());
    
    //         // Cek jika baris data tidak terupdate
    //         if (!$update || $this->db->affected_rows() === 0) {
    //             log_message('error', 'Update gagal untuk ID: ' . $change['id'] . '. Query: ' . $this->db->last_query());
    //         }
    //     }
    
    //     // Akhiri transaksi
    //     $this->db->trans_complete();
    
    //     // Cek status transaksi
    //     if ($this->db->trans_status() === FALSE) {
    //         // Log error jika transaksi gagal
    //         log_message('error', 'Transaksi gagal untuk beberapa perubahan.');
    //         return array('status' => 'error', 'message' => 'Kesalahan saat menyimpan data ke database.');
    //     }
    
    //     // Log sukses jika transaksi berhasil
    //     log_message('debug', 'Semua data berhasil diupdate.');
    //     return array('status' => 'success', 'message' => 'Data berhasil diperbarui.');
    // }

    public function update_details($changes)
    {
        // Pastikan data yang diterima valid dan perbarui
        foreach ($changes as $change) {
            // Cek apakah ada ID dan data yang valid
            if (isset($change['id'])) {
                $data = [];

                if (isset($change['measure_data'])) {
                    $data['measure_data'] = $change['measure_data'];
                }
                if (isset($change['judgement'])) {
                    $data['judgement'] = $change['judgement'];
                }
                if (isset($change['measure'])) {
                    $data['measure'] = $change['measure'];
                }

                // Update data ke database
                $this->db->where('id', $change['id']);
                $this->db->update('equpment_inspection_part_detail', $data); // Ganti dengan nama tabel yang sesuai

                // Jika update berhasil, lanjutkan ke perubahan berikutnya
            }
        }

        // Pastikan hasil pembaruan
        return ['status' => 'success', 'message' => 'Data berhasil diperbarui.'];
    }


    
    // Tamabahan 11-05-2024
    public function save_measure_data($data) {
        foreach ($data as $row) {
            // Use insert or update logic based on your requirements
            $this->db->where('id', $row['id']);
            $this->db->update('equpment_inspection_part_detail', $row);
        }
    
        return $this->db->affected_rows() > 0;
    }

    public function import_from_excel($data, $eq_id)
{
    $this->db->trans_start();
    
    $current_part = '';
    $current_part_id = 0;
    $current_inspection_part = '';
    $current_inspection_part_id = 0;
    
    foreach ($data as $row) {
        // Periksa apakah part berbeda dari sebelumnya
        if ($row['part'] != $current_part) {
            // Cek apakah part sudah ada
            $part_exist = $this->db->get_where('equpment_inspection_part', [
                'eq_id' => $eq_id,
                'part' => $row['part']
            ])->row_array();
            
            if (!$part_exist) {
                // Insert part baru
                $this->db->insert('equpment_inspection_part', [
                    'eq_id' => $eq_id,
                    'part' => $row['part']
                ]);
                $current_part_id = $this->db->insert_id();
            } else {
                $current_part_id = $part_exist['id'];
            }
            
            $current_part = $row['part'];
            $current_inspection_part = ''; // Reset inspection part saat part berubah
        }
        
        // Periksa apakah inspection part berbeda dari sebelumnya
        if ($row['inspection_part'] != $current_inspection_part) {
            // Cek apakah inspection part sudah ada
            $inspection_part_exist = $this->db->get_where('equpment_inspection_inspection_part', [
                'part_id' => $current_part_id,
                'inspection_part' => $row['inspection_part']
            ])->row_array();
            
            if (!$inspection_part_exist) {
                // Insert inspection part baru
                $this->db->insert('equpment_inspection_inspection_part', [
                    'part_id' => $current_part_id,
                    'inspection_part' => $row['inspection_part']
                ]);
                $current_inspection_part_id = $this->db->insert_id();
            } else {
                $current_inspection_part_id = $inspection_part_exist['id'];
            }
            
            $current_inspection_part = $row['inspection_part'];
        }
        
        // Cek apakah detail sudah ada
        $detail_exist = $this->db->get_where('equpment_inspection_part_detail', [
            'inspection_part_id' => $current_inspection_part_id,
            'item' => $row['item'],
            'method' => $row['method'],
            'determination_standard' => $row['determination_standard']
        ])->row_array();
        
        if (!$detail_exist) {
            // Insert detail baru
            $this->db->insert('equpment_inspection_part_detail', [
                'inspection_part_id' => $current_inspection_part_id,
                'item' => $row['item'],
                'method' => $row['method'],
                'determination_standard' => $row['determination_standard'],
                'measure_data' => '',
                'judgement' => '',
                'measure' => ''
            ]);
        }
    }
    
    $this->db->trans_complete();
    return $this->db->trans_status();
}
    
    
}
