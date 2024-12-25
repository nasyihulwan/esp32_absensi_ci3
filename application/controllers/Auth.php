<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        if ($this->session->userdata('username')) {
            redirect('dashboard');
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('__partials/_head.php');
            $this->load->view('auth/login');
            $this->load->view('__partials/_js.php');
        } else {
            $this->_login();
        }
    }

    private function _login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $check_username = $this->db->get_where('users', ['username' => $username])->row_array();

        if ($check_username) {
            if (password_verify($password, $check_username['password'])) {
                $data = [
                    'id'=> $check_username['id'],
                    'name' => $check_username['name'],
                    'username' => $check_username['username'],
                    'id_fingerprint' => $check_username['id_fingerprint'],
                    'role' => $check_username['role'],
                    'prodi' => $check_username['prodi'],
                    'semester' => $check_username['semester'],
                    'kelas' => $check_username['kelas'],
                    'is_active' => $check_username['is_active']
                ];
                $this->session->set_userdata($data);
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Password salah!</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Username tidak terdaftar!</div>');
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('role');
        $this->session->unset_userdata('prodi');
        $this->session->unset_userdata('semester');
        $this->session->unset_userdata('kelas');
        $this->session->unset_userdata('is_active');
        $this->session->set_flashdata('message', '<div class="alert alert-success">Berhasil logout!</div>');
        redirect('auth');
    }

    public function blocked()
    {
        $this->load->view('auth/__partials/_head.php');
        $this->load->view("auth/blocked");
        $this->load->view('auth/__partials/_js.php');
    }
}