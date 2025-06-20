<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->load->model(array('M_master'));
        is_login();
        is_access(1, [2]);
    }

    public function index()
    {
        redirect('dashboard', 'refresh');
    }

    public function users()
    {
        $data = [
            'judul' => 'User',
            'user' => $this->M_master->get_user()
        ];
        $this->load->view('master/user/index', $data);
    }

    public function user_add()
    {
        $this->form_validation->set_rules(
            'username',
            'Username',
            'required|is_unique[users.username]',
            array(
                'required'      => 'Form %s tidak boleh kosong.',
                'is_unique'     => 'Username %s telah digunakan.'
            )
        );
        $this->form_validation->set_rules(
            'password',
            'Password',
            'required|min_length[5]|max_length[12]',
            array(
                'required'      => 'Form %s tidak boleh kosong.',
                'min_length'    => 'Panjang %s minimal 5 karakter.',
                'max_length'    => 'Panjang %s maksimal 12 karakter.'
            )
        );
        $this->form_validation->set_rules(
            'password2',
            'Password Confirmation',
            'required|min_length[5]|max_length[12]|matches[password]',
            array(
                'required'      => 'Form %s tidak boleh kosong.',
                'matches'       => 'Password yang anda masukkan tidak sama.',
                'min_length'    => 'Panjang password minimal 5 karakter.',
                'max_length'    => 'Panjang password maksimal 12 karakter.'
            )
        );
        if ($this->form_validation->run() == FALSE) {
            $data = [
                'judul' => 'Add User',
                'level' => $this->M_master->get_level()
            ];
            $this->load->view('master/user/add', $data);
        } else {
            $ttd = base64_encode(file_get_contents($_FILES['ttd']['tmp_name']));
            $ttd = 'data:image/png;base64,' . $ttd;
            $data = [
                'nama' => htmlspecialchars($this->input->post('nama', true)),
                'username' => htmlspecialchars($this->input->post('username')),
                'department' => htmlspecialchars($this->input->post('department')),
                'divisi' => htmlspecialchars($this->input->post('divisi')),
                'posisi' => htmlspecialchars($this->input->post('posisi')),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'level' => $this->input->post('level'),
                'ttd' => $ttd
            ];
            $this->db->insert('users', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
            redirect('master/users');
        }
    }

    public function user_edit($id = null)
    {
        // get data from model
        $data = $this->M_master->getUserId($id);
        if (empty($id) or empty($data)) {
            redirect('master/users');
        }
        $this->form_validation->set_rules(
            'username',
            'Username',
            'required',
            array(
                'required'      => 'Form %s tidak boleh kosong.',
            )
        );
        if ($this->input->post('password')) {
            $this->form_validation->set_rules(
                'password',
                'Password',
                'required|min_length[5]|max_length[12]',
                array(
                    'required'      => 'Form %s tidak boleh kosong.',
                    'min_length'    => 'Panjang %s minimal 5 karakter.',
                    'max_length'    => 'Panjang %s maksimal 12 karakter.'
                )
            );
        }
        if (
            $this->form_validation->run() == FALSE
        ) {
            $data = [
                'judul' => 'Update User',
                'level' => $this->M_master->get_level(),
                'data' => $data
            ];
            $this->load->view('master/user/edit', $data);
        } else {
            if ($this->input->post('password')) {
                $data = [
                    'nama' => htmlspecialchars($this->input->post('nama', true)),
                    'username' => htmlspecialchars($this->input->post('username')),
                    'department' => htmlspecialchars($this->input->post('department')),
                    'divisi' => htmlspecialchars($this->input->post('divisi')),
                    'posisi' => htmlspecialchars($this->input->post('posisi')),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'level' => $this->input->post('level'),
                    'ttd' => $this->input->post('ttd_edit')
                ];
            } else {
                $data = [
                    'nama' => htmlspecialchars($this->input->post('nama', true)),
                    'username' => htmlspecialchars($this->input->post('username')),
                    'department' => htmlspecialchars($this->input->post('department')),
                    'divisi' => htmlspecialchars($this->input->post('divisi')),
                    'posisi' => htmlspecialchars($this->input->post('posisi')),
                    'level' => $this->input->post('level'),
                    'ttd' => $this->input->post('ttd_edit')
                ];
            }
            $this->db->where('id_user', $id);
            $this->db->update('users', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
            redirect('master/users');
        }
    }

    public function user_delete($id)
    {
        $csrf = $this->input->get('_csrf');
        if ($csrf == $this->security->get_csrf_hash()) {
            $this->M_master->delete_user($id);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
            redirect('master/users');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
            redirect('master/users');
        }
    }

    // public function profil()
    // {
    //     $this->form_validation->set_rules(
    //         'username',
    //         'Username',
    //         'required',
    //         array(
    //             'required'      => 'Form %s tidak boleh kosong.'
    //         )
    //     );
    //     if ($this->form_validation->run() == FALSE) {
    //         $data = [
    //             'judul' => 'User',
    //             'data' => $this->M_master->get_profil()
    //         ];
    //         $this->load->view('master/profil/index', $data);
    //     } else {
    //         $password = $this->input->post('password');
    //         if ($password != '') {
    //             $data = [
    //                 'nama' => htmlspecialchars($this->input->post('nama', true)),
    //                 'username' => htmlspecialchars($this->input->post('username')),
    //                 'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
    //             ];
    //         } else {
    //             $data = [
    //                 'nama' => htmlspecialchars($this->input->post('nama', true)),
    //                 'username' => htmlspecialchars($this->input->post('username', true)),
    //             ];
    //         }
    //         // update session
    //         $this->session->set_userdata('nama', $data['nama']);
    //         $this->session->set_userdata('username', $data['username']);
    //         $this->db->where('id_user', $this->session->userdata('id_user'));
    //         $this->db->update('users', $data);
    //         $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
    //         redirect('master/profil');
    //     }
    // }

    public function profil()
    {
        $this->form_validation->set_rules(
            'username',
            'Username',
            'required',
            array(
                'required'      => 'Form %s tidak boleh kosong.'
            )
        );

        $user_id = $this->session->userdata('id_user');

        if ($this->form_validation->run() == FALSE) {
            $data = [
                'judul' => 'User',
                'data' => $this->M_master->getUserId($user_id)
            ];
            $this->load->view('master/profil/index', $data);
        } else {
            $password = $this->input->post('password');
            if ($password != '') {
                $data = [
                    'nama' => htmlspecialchars($this->input->post('nama', true)),
                    'username' => htmlspecialchars($this->input->post('username')),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                ];
            } else {
                $data = [
                    'nama' => htmlspecialchars($this->input->post('nama', true)),
                    'username' => htmlspecialchars($this->input->post('username', true)),
                ];
            }
            // update session
            $this->session->set_userdata('nama', $data['nama']);
            $this->session->set_userdata('username', $data['username']);
            $this->db->where('id_user', $this->session->userdata('id_user'));
            $this->db->update('users', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
            redirect('master/profil');
        }
    }

    public function satuan()
    {
        $data = [
            'judul' => 'Satuan',
            'data' => $this->db->get('satuan')->result_array()
        ];
        $this->load->view('satuan/index', $data);
    }

    public function addSatuan()
    {
        $data = [
            'satuan' => htmlspecialchars($this->input->post('satuan', true)),
        ];
        $check_same_name = $this->db->get_where('satuan', ['satuan' => $data['satuan']])->row_array();
        if ($check_same_name) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
            redirect('master/satuan');
        }
        $this->db->insert('satuan', $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
        redirect('master/satuan');
    }

    public function get_satuan($id)
    {
        $data = $this->db->get_where('satuan', ['id' => $id])->row_array();
        echo json_encode($data);
    }

    public function updateSatuan()
    {
        $id = $this->input->post('id');
        $data = [
            'satuan' => htmlspecialchars($this->input->post('satuan', true)),
        ];
        $check_same_name = $this->db->get_where('satuan', ['satuan' => $data['satuan']])->row_array();
        if ($check_same_name) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
            redirect('master/satuan');
        }
        $this->db->where('id', $id);
        $this->db->update('satuan', $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
        redirect('master/satuan');
    }

    public function deleteSatuan($id)
    {
        $csrf = $this->input->get('_csrf');
        if ($csrf == $this->security->get_csrf_hash()) {
            $this->db->where('id', $id);
            $this->db->delete('satuan');
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
            redirect('master/satuan');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
            redirect('master/satuan');
        }
    }
}

/* End of file Master.php */
