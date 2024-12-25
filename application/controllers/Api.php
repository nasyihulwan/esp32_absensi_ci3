<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('User_model'); 

        // Zona waktu
        date_default_timezone_set('Asia/Jakarta');
    }

    public function ambilDataUser() {
        $id_fingerprint = intval($this->input->get('id_fingerprint'));

        // Validasi ID
        if ($id_fingerprint <= 0) {
            $this->output
                ->set_status_header(400) // Bad Request
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Invalid ID."
                ]));
            return;
        }
        
        $user = $this->User_model->getUserByFingerprintId($id_fingerprint);

        if ($user) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    "status" => "success",
                    "nama" => $user['name'],
                    "username" => $user['username'],
                    "password_absen" => $user['password_absen'],
                    "role" => $user['role'],
                    "prodi" => $user['prodi'],
                    "semester" => $user['semester'],
                    "kelas" => $user['kelas']
                ]));
        } else {
            $this->output
                ->set_status_header(404) 
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "User not found."
                ]));
        }
    }

    public function kirimDataAbsen() {
        // Set header JSON
        header('Content-Type: application/json');
    
        // Ambil data dari POST
        $id_fingerprint = intval($this->input->post('id_fingerprint'));
    
        // Validasi id_fingerprint
        if ($id_fingerprint <= 0) {
            echo json_encode(["status" => "error", "message" => "ID User tidak valid."]);
            return;
        }
    
        // Ambil data user berdasarkan id_fingerprint
        $user = $this->db->get_where('users', ['id_fingerprint' => $id_fingerprint])->row();
    
        if (!$user) {
            echo json_encode(["status" => "error", "message" => "Pengguna tidak ditemukan."]);
            return;
        }
    
        // Cek jadwal yang sesuai
        $today = date('Y-m-d');
        $current_time = date('H:i:s');
    
        $this->db->where('prodi', $user->prodi);
        $this->db->where('semester', $user->semester);
        $this->db->where('kelas', $user->kelas);
        $this->db->where('tanggal', $today);
        $this->db->where('status', "Dibuka");
        $jadwal = $this->db->get('jadwal_absen_matakuliah')->row();
    
        if (!$jadwal) {
            echo json_encode(["status" => "error", "message" => "Tidak ada jadwal absen yang sesuai."]);
            return;
        }
    
        // Tentukan status absensi (Tepat Waktu atau Terlambat)
        $maks_absen_masuk = strtotime($jadwal->jam_masuk) + strtotime($jadwal->buka_absen_masuk); // $jadwal->buka_absen_masuk harus dalam detik
        $status_absensi = (strtotime($current_time) > $maks_absen_masuk) ? 'Tepat Waktu' : 'Terlambat';

    
        // Cek apakah sudah ada absen masuk hari ini
        $this->db->where('id_fingerprint', $id_fingerprint);
        $this->db->where('DATE(absen_masuk)', $today);
        $result = $this->db->get('absen_matakuliah');
    
        if ($result->num_rows() > 0) {
            $row = $result->row();
    
            if ($row->absen_keluar === null) {
                if (strtotime($current_time) < strtotime($jadwal->jam_keluar)) {
                echo json_encode(["status" => "error", "message" => "Absen keluar belum dibuka."]);
                return;
                }

                $maks_absen_keluar = strtotime($jadwal->jam_keluar) + (strtotime("1970-01-01 $jadwal->buka_absen_keluar") - strtotime("1970-01-01 00:00:00"));

                if (strtotime($current_time) > $maks_absen_keluar) {
                    // Update status jadwal menjadi "Ditutup"
                    $this->db->where('id', $jadwal->id);
                    $this->db->update('jadwal_absen_matakuliah', ['status' => 'Ditutup']);

                    echo json_encode(["status" => "error", "message" => "Jadwal telah ditutup."]);
                    return;
                }
                
                // Update absen_keluar jika absen_keluar masih NULL
                $this->db->where('id_fingerprint', $id_fingerprint);
                $this->db->where('DATE(absen_masuk)', $today);
                $update = $this->db->update('absen_matakuliah', ['absen_keluar' => date('Y-m-d H:i:s')]);
    
                if ($update) {
                    echo json_encode(["status" => "success", "message" => "Absen Keluar berhasil dicatat."]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Gagal mencatat Absen Keluar."]);
                }
            } else {
                // Jika absen_keluar sudah ada
                echo json_encode(["status" => "error", "message" => "Sudah absen."]);
            }
        } else {
            // Insert absen_masuk baru dengan id_absen_matkul dari jadwal dan status absensi
            $data = [
                'id_fingerprint' => $id_fingerprint,
                'id_absen_matkul' => $jadwal->id,
                'absen_masuk' => date('Y-m-d H:i:s'),
                'ket_absen_masuk' => $status_absensi
            ];
            $insert = $this->db->insert('absen_matakuliah', $data);
    
            if ($insert) {
                echo json_encode(["status" => "success", "message" => "Absen Masuk berhasil dicatat.", "status_absensi" => $status_absensi]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal mencatat Absen Masuk."]);
            }
        }
    }
    
    public function kirimIPAddress() {
        // Set header JSON
        header('Content-Type: application/json');
    
        // Ambil data dari POST
        $kodeAlat = $this->input->post('kodeAlat');
        $namaAlat = $this->input->post('namaAlat');
        $ssid = $this->input->post('ssid');
        $ipAddress = $this->input->post('ipAddress');
    
        // Validasi input
        if (empty($kodeAlat)) {
            echo json_encode(["status" => "error", "message" => "Parameter kodeAlat wajib diisi."]);
            return;
        }
        
        if (empty($namaAlat)) {
            echo json_encode(["status" => "error", "message" => "Parameter namaAlat wajib diisi."]);
            return;
        }
        
        if (empty($ipAddress)) {
            echo json_encode(["status" => "error", "message" => "Parameter ipAddress wajib diisi."]);
            return;
        }        
    
        // Cek apakah kodeAlat sudah ada
        $this->db->where('kode_alat', $kodeAlat);
        $existing = $this->db->get('alat')->row();
    
        if ($existing) {
            // Update IP address jika kodeAlat sudah ada
            $updateData = [
                'nama_alat' => $namaAlat,
                'ip_address' => $ipAddress,
                'ssid' => $ssid,
                'updated_at' => date('Y-m-d H:i:s')
            ];
    
            $this->db->where('kode_alat', $kodeAlat);
            $update = $this->db->update('alat', $updateData);
    
            if ($update) {
                echo json_encode(["status" => "success", "message" => "Data alat berhasil diperbarui."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal memperbarui data alat."]);
            }
        } else {
            // Insert data baru jika kodeAlat belum ada
            $insertData = [
                'kode_alat' => $kodeAlat,
                'nama_alat' => $namaAlat,
                'ssid' => $ssid,
                'ip_address' => $ipAddress,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
    
            $insert = $this->db->insert('alat', $insertData);
    
            if ($insert) {
                echo json_encode(["status" => "success", "message" => "Data alat berhasil disimpan."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal menyimpan data alat."]);
            }
        }
    }

    public function heartbeat() {
        // Set header JSON
        header('Content-Type: application/json');
        
        // Ambil data dari POST
        $kodeAlat = $this->input->post('kodeAlat');
        
        // Validasi input
        if (empty($kodeAlat)) {
            echo json_encode(["status" => "error", "message" => "Parameter kodeAlat wajib diisi."]);
            return;
        }
        
        // Update waktu terakhir alat aktif
        $this->db->where('kode_alat', $kodeAlat);
        $update = $this->db->update('alat', ['last_heartbeat' => date('Y-m-d H:i:s')]);
        
        if ($update) {
            echo json_encode(["status" => "success", "message" => "Heartbeat diterima."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal memperbarui heartbeat."]);
        }
    }

    public function hapusAlatTidakAktif() {
        // Set waktu batas alat dianggap tidak aktif (misalnya, 10 detik)
        $batas_waktu = date('Y-m-d H:i:s', strtotime('-10 seconds'));
        
        // Hapus alat yang tidak mengirimkan heartbeat sebelum batas waktu
        $this->db->where('last_heartbeat <', $batas_waktu);
        $hapus = $this->db->delete('alat');
        
        if ($hapus) {
            echo json_encode(["status" => "success", "message" => "Alat tidak aktif berhasil dihapus."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal menghapus alat tidak aktif."]);
        }
    }
    
}