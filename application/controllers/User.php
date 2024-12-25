<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');

        is_logged_in(); 
    }

    // Profil User
    public function profile()
    {
        $id = $this->session->userdata('id'); // Ambil username dari session
        $data['user'] = $this->User_model->getUserById($id); // Ambil user berdasarkan username

        $data['title'] = "Profil Saya";
        $this->load->view('__partials/_head');
        $this->load->view('user/profile', $data);
    }

    // Daftar Semua User
    public function list()
    {
        $id = $this->session->userdata('id'); // Ambil id dari session
        $data['user'] = $this->User_model->getUserById($id); // Ambil user login
        $data['queryAllUser'] = $this->User_model->getAllUsers()->result(); // Ambil semua user

        $data['title'] = "Data User";
        $this->load->view('__partials/_head');
        $this->load->view('user/list', $data);
    }

    public function add()
{
    $id = $this->session->userdata('id');
    $data['user'] = $this->User_model->getUserById($id);
    $data['last_fingerprint_id'] = $this->db->select_max('id_fingerprint')->get('users')->row()->id_fingerprint;

     // Periksa apakah ada alat yang terhubung
        $is_connected = $this->db->where('is_connected', 1)->get('alat')->num_rows();

        // Jika tidak ada alat yang terhubung, tampilkan pesan peringatan
        if ($is_connected == 0) {
            $data['connected_status'] = false;
        } else {
            $data['connected_status'] = true;
        }

    // Dapatkan IP alat yang terhubung
    $connected_device = $this->db->select('ip_address')
                                 ->from('alat')
                                 ->where('is_connected', 1)
                                 ->get()
                                 ->row();

    // Jika ada perangkat terhubung, kirimkan IP-nya ke view
    $data['ipAddressConnected'] = $connected_device ? $connected_device->ip_address : null;

    $this->form_validation->set_rules('name', 'Name', 'required|trim');
    $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.username]', [
        'is_unique' => 'Username already exists!'
    ]);
    $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]');
    $this->form_validation->set_rules('password2', 'Password', 'required|trim|min_length[3]|matches[password1]');
    $this->form_validation->set_rules('password_absen', 'Password Absen', 'trim|numeric|matches[password_absen_confirm]');
    $this->form_validation->set_rules('password_absen_confirm', 'Konfirmasi Password Absen', 'trim|numeric|matches[password_absen]');

    if ($this->form_validation->run() == false) {
        $data['title'] = "Buat User Baru";
        $this->load->view('__partials/_head');
        $this->load->view('user/create_new_user', $data);
    } else {
        $id_fingerprint = $this->input->post('role') === 'admin' ? null : $this->db->select_max('id_fingerprint')->get('users')->row()->id_fingerprint + 1;
        $password_absen = $this->input->post('role') === 'admin' ? null : $this->input->post('password_absen');

        $data = [
            'name' => htmlspecialchars($this->input->post('name', true)),
            'id_fingerprint' => $id_fingerprint,
            'username' => htmlspecialchars($this->input->post('username', true)),
            'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
            'role' => $this->input->post('role'),
            'prodi' => $this->input->post('prodi'),
            'semester' => $this->input->post('semester'),
            'kelas' => $this->input->post('kelas'),
            'image' => 'default.png',
            'password_absen' => $password_absen,
            'is_active' => 1,
            'date_created' => time()
        ];

        if ($this->db->insert('users', $data)) {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">User baru berhasil ditambahkan!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Terjadi kesalahan pada sistem. Coba lagi nanti!</div>');
        }

        redirect('user/add');
    }
}



    // Edit Profil User
    public function edit()
    {
        $user_id = $this->session->userdata('id');
        $data['user'] = $this->User_model->getUserById($user_id);
    
        $data['title'] = "Edit Profil";
    
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');
    
        if ($this->form_validation->run() == false) {
            $this->load->view('__partials/_head');
            $this->load->view('user/edit', $data);
        } else {
            $name = $this->input->post('name');
            $username = $this->input->post('username');
    
            // Check image upload
            $upload_image = $_FILES['image']['name'];
    
            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png|webp';
                $config['max_size'] = '10240';
                $config['upload_path'] = './assets/images/profile/';
    
                $this->load->library('upload', $config);
    
                if ($this->upload->do_upload('image')) {
                    $old_image = $data['user']['image'];
                    if ($old_image != 'default.png') {
                        unlink(FCPATH . 'assets/images/profile/' . $old_image);
                    }
                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '</div>');
                    redirect(site_url('user/edit'));
                }
            }
    
            $update = [
                'name' => $name,
                'username' => $username
            ];
            $this->db->where('id', $user_id); // Menggunakan 'id' sebagai kondisi
            $this->db->update('users', $update);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Profil anda berhasil diperbaharui!</div>');
            redirect(site_url('user/edit'));
        }
    }
    
}