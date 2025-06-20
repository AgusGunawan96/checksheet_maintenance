<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Item extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->load->model(array('M_item'));
        is_login();
        is_access(1, [2]);
    }

    public function index()
    {
        $data = [
            'judul' => 'Item',
            'data' => $this->M_item->get()
        ];
        $this->load->view('item/index', $data);
    }

    public function add()
    {
        $data = [
            'item_name' => htmlspecialchars($this->input->post('item_name', true)),
        ];
        $check_same_name = $this->db->get_where('item', ['item_name' => $data['item_name']])->row_array();
        if ($check_same_name) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
            redirect('item');
        }
        $this->db->insert('item', $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
        redirect('item');
    }

    public function get_item($id)
    {
        $data = $this->M_item->getItemId($id);
        echo json_encode($data);
    }

    public function update()
    {
        $id = $this->input->post('id');
        $data = [
            'item_name' => htmlspecialchars($this->input->post('item_name', true)),
        ];
        $check_same_name = $this->db->get_where('item', ['item_name' => $data['item_name']])->row_array();
        if ($check_same_name) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama sudah ada!</div>');
            redirect('item');
        }
        $this->db->where('id', $id);
        $this->db->update('item', $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
        redirect('item');
    }

    public function delete($id)
    {
        $csrf = $this->input->get('_csrf');
        if ($csrf == $this->security->get_csrf_hash()) {
            $this->M_item->delete_item($id);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
            redirect('item');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
            redirect('item');
        }
    }
}
