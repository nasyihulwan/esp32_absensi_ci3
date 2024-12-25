<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function getUserById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('users')->row_array(); 
    }
    
    public function getAllUsers()
    {
        return $this->db->get('users');
    }

    public function getUserByFingerprintId($id_fingerprint) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id_fingerprint', $id_fingerprint);
        $query = $this->db->get();

        return $query->row_array(); // Mengembalikan data user atau null jika tidak ditemukan
    }
}