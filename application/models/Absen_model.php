<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Absen_model extends CI_Model
{
    
    public function getAllJadwal()
    {
        $this->db->select('
            jadwal_absen_matakuliah.*,
            users.name AS nama_dosen
        ');
        $this->db->from('jadwal_absen_matakuliah');
        $this->db->join('users', 'jadwal_absen_matakuliah.id_dosen = users.id');
        return $this->db->get();
    }
    

    public function getKehadiranMahasiswa($id_absen)
    {
        $this->db->select('
            absen_matakuliah.id,
            absen_matakuliah.id_fingerprint,
            absen_matakuliah.id_absen_matkul,
            absen_matakuliah.absen_masuk,
            absen_matakuliah.ket_absen_masuk,
            absen_matakuliah.absen_keluar,
            users.name AS nama_mahasiswa,
            users.prodi,
            users.semester,
            users.kelas,
            jadwal_absen_matakuliah.nama_matakuliah,
            jadwal_absen_matakuliah.tanggal,
            jadwal_absen_matakuliah.jam_masuk,
            jadwal_absen_matakuliah.jam_keluar
        ');
        $this->db->from('jadwal_absen_matakuliah');
        $this->db->join('users', 'users.semester = jadwal_absen_matakuliah.semester AND users.kelas = jadwal_absen_matakuliah.kelas');
        $this->db->join('absen_matakuliah', 'absen_matakuliah.id_fingerprint = users.id_fingerprint AND absen_matakuliah.id_absen_matkul = jadwal_absen_matakuliah.id', 'left');
        $this->db->where('jadwal_absen_matakuliah.id', $id_absen);
        $this->db->where('users.role', 'mahasiswa');
        
        return $this->db->get();
    }
    

    public function getRiwayatKehadiran($id_fingerprint)
    {
        $this->db->select('
            absen_matakuliah.id,
            absen_matakuliah.id_fingerprint,
            absen_matakuliah.absen_masuk,
            absen_matakuliah.ket_absen_masuk, 
            absen_matakuliah.absen_keluar,
            users.name AS nama_mahasiswa,
            users.prodi,
            users.semester,
            users.kelas,
            jadwal_absen_matakuliah.nama_matakuliah,
            jadwal_absen_matakuliah.tanggal,
            jadwal_absen_matakuliah.pertemuan,
            jadwal_absen_matakuliah.jam_masuk,
            jadwal_absen_matakuliah.jam_keluar
        ');
        $this->db->from('absen_matakuliah');
        $this->db->join('users', 'users.id_fingerprint = absen_matakuliah.id_fingerprint');
        $this->db->join('jadwal_absen_matakuliah', 
            'jadwal_absen_matakuliah.id = absen_matakuliah.id_absen_matkul', 
            'left'
        );
        $this->db->where('absen_matakuliah.id_fingerprint', $id_fingerprint);
        $this->db->where('users.role', 'mahasiswa');
        $this->db->order_by('jadwal_absen_matakuliah.tanggal', 'DESC');
        
        return $this->db->get();
    }
    
}