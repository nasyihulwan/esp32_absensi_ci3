<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Alat_model extends CI_Model
{
    public function update_connection_status($kode_alat, $status) {
        $data = [
            'is_connected' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->db->where('kode_alat', $kode_alat);
        return $this->db->update('alat', $data);
    }
}