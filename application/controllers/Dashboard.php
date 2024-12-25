<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('User_model');
        $this->load->model('Alat_model');
        is_logged_in();
    }

    public function index()
    {
        $id = $this->session->userdata('id');
        $id_fingerprint = $this->session->userdata('id_fingerprint');
        
        $data['user'] = $this->User_model->getUserById($id);
        $data['dosen'] = $this->db->where('role', 'dosen')->count_all_results('users');
        $data['mahasiswa'] = $this->db->where('role', 'mahasiswa')->count_all_results('users');
        $data['admin'] = $this->db->where('role', 'admin')->count_all_results('users');
        $data['jadwalHariIni'] = $this->db->where('DATE(tanggal)', date('Y-m-d'))->count_all_results('jadwal_absen_matakuliah');
        
        $data['absenTepatWaktu'] = $this->db->where('id_fingerprint', $id_fingerprint)
                                    ->where('ket_absen_masuk', "Tepat Waktu")
                                    ->count_all_results('absen_matakuliah');
        $data['absenTerlambat'] = $this->db->where('id_fingerprint', $id_fingerprint)
                                    ->where('ket_absen_masuk', "Terlambat")
                                    ->count_all_results('absen_matakuliah');
        $data['title'] = 'Welcome ';
        
        // Ambil data alat aktif
        $current_time = date('Y-m-d H:i:s');
        $time_minus_5_seconds = date('Y-m-d H:i:s', strtotime('-5 seconds', strtotime($current_time)));
        $this->db->where('last_heartbeat >=', $time_minus_5_seconds);
        $this->db->where('is_connected', 1);
        $data['alat'] = $this->db->get('alat')->row_array();

        $data['alat_list'] = $this->db->get('alat')->result();

        // // Ambil data alat yang terhubung
        // $data['alat_connected'] = $this->db->get_where('alat', ['is_connected' => 1])->row_array();

        $this->load->view('__partials/_head');
        $this->load->view('dashboard', $data);
    }

    public function hubungkan($kode_alat)
    {
        if ($this->Alat_model->update_connection_status($kode_alat, 1)) {
            $this->session->set_flashdata('success', 'Alat berhasil dihubungkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghubungkan alat.');
        }
        redirect('dashboard'); 
    }

    public function putuskan($kode_alat)
    {
        if ($this->Alat_model->update_connection_status($kode_alat, 0)) {
            $this->session->set_flashdata('success', 'Alat berhasil diputuskan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memutuskan alat.');
        }
        redirect('dashboard');
    }
    
}