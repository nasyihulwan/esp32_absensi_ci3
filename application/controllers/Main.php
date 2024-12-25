<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Absen_model');

        is_logged_in(); 
    }
	
	public function jadwal()
	{
		if ($this->session->userdata('role') == "mahasiswa") {
			redirect('');
		}
		
		$id = $this->session->userdata('id'); // Ambil id dari session
        $data['user'] = $this->User_model->getUserById($id); // Ambil user login
		$data['querySemuaJadwal'] = $this->Absen_model->getAllJadwal()->result(); // Ambil semua user
		
		$data['title'] = "Jadwal Mata Kuliah";
        $this->load->view('__partials/_head');
		$this->load->view('main/jadwal', $data);
	}

	public function tambah()
	{
		// Data user untuk keperluan view, jika diperlukan
		$id = $this->session->userdata('id');
		$data['user'] = $this->User_model->getUserById($id);
	
		//Validasi Form
		$this->form_validation->set_rules('id_dosen', 'ID Dosen', 'required|numeric', [
			'required' => 'ID Dosen wajib diisi!',
		]);
		$this->form_validation->set_rules('prodi', 'Program Studi', 'required', [
			'required' => 'Program Studi wajib dipilih!'
		]);
		$this->form_validation->set_rules('nama_matakuliah', 'Nama Mata Kuliah', 'required|trim', [
			'required' => 'Nama Mata Kuliah wajib diisi!'
		]);
		$this->form_validation->set_rules('pertemuan', 'Pertemuan', 'required', [
			'required' => 'Pertemuan wajib dipilih!',
		]);
		$this->form_validation->set_rules('semester', 'Semester', 'required', [
			'required' => 'Semester wajib dipilih!',
		]);
		$this->form_validation->set_rules('kelas', 'Kelas', 'required', [
			'required' => 'Kelas wajib dipilih!'
		]);
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required', [
			'required' => 'Tanggal wajib diisi!'
		]);
		$this->form_validation->set_rules('jam_masuk', 'Jam Masuk', 'required', [
			'required' => 'Jam Masuk wajib diisi!'
		]);
		$this->form_validation->set_rules('jam_keluar', 'Jam Keluar', 'required', [
			'required' => 'Jam Keluar wajib diisi!'
		]);
		$this->form_validation->set_rules('buka_absen_masuk', 'Buka Absen Masuk', 'required', [
			'required' => 'Buka Absen Masuk wajib diisi!'
		]);
		$this->form_validation->set_rules('buka_absen_keluar', 'Buka Absen Keluar', 'required', [
			'required' => 'Buka Absen Keluar wajib diisi!'
		]);	
	
		if ($this->form_validation->run() == false) {
			// Jika validasi gagal, tampilkan kembali form
			$data['title'] = "Buat Jadwal Absen Baru";
			$this->load->view('__partials/_head');
			$this->load->view('main/tambah', $data);
		} else {
			// Data yang akan dimasukkan ke tabel jadwal_absen_matakuliah
			$data = [
				'id_dosen' => $this->input->post('id_dosen'),
				'prodi' => $this->input->post('prodi'),
				'nama_matakuliah' => htmlspecialchars($this->input->post('nama_matakuliah', true)),
				'pertemuan' => $this->input->post('pertemuan'),
				'semester' => $this->input->post('semester'),
				'kelas' => $this->input->post('kelas'),
				'tanggal' => $this->input->post('tanggal'),
				'jam_masuk' => $this->input->post('jam_masuk'),
				'jam_keluar' => $this->input->post('jam_keluar'),
				'buka_absen_masuk' => $this->input->post('buka_absen_masuk'),
				'buka_absen_keluar' => $this->input->post('buka_absen_keluar'),
				'status' => $this->input->post('status')
			];
	
			// Insert data ke tabel jadwal_absen_matakuliah
			if ($this->db->insert('jadwal_absen_matakuliah', $data)) {
				// Set flashdata jika berhasil
				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Jadwal Absen berhasil ditambahkan!</div>');
			} else {
				// Set flashdata jika gagal
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Terjadi kesalahan pada sistem. Coba lagi nanti!</div>');
			}
	
			redirect('main/tambah');
		}
	}

	public function kehadiran($id_jadwal)
	{
		if ($this->session->userdata('role') == "mahasiswa") {
			redirect('');
		}
		
		$id = $this->session->userdata('id'); // Ambil id dari session
		$data['user'] = $this->User_model->getUserById($id); // Ambil user login
		$data['queryKehadiran'] = $this->Absen_model->getKehadiranMahasiswa($id_jadwal)->result(); // Ambil semua kehadiran
		
		
		$data['title'] = "Kehadiran Mahasiswa";
        $this->load->view('__partials/_head');
		$this->load->view('main/kehadiran', $data);
	}

	public function riwayat($id_fingerprint)
	{
		$id = $this->session->userdata('id'); // Ambil id dari session
		$id_fingerprint = $this->session->userdata('id_fingerprint');

		if ($this->session->userdata('role') != "mahasiswa") {
			redirect('');
		}

		$data['user'] = $this->User_model->getUserById($id); // Ambil user login
		$data['queryKehadiran'] = $this->Absen_model->getRiwayatKehadiran($id_fingerprint)->result(); // Ambil  kehadiran
		
		
		$data['title'] = "Riwayat Kehadiran";
        $this->load->view('__partials/_head');
		$this->load->view('main/riwayat_kehadiran', $data);
	}
}