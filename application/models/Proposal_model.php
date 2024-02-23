<?php
// application/models/Proposal_model.php

class Proposal_model extends CI_Model {

    public function get_data_proposal() {
        return $this->db->get('PROPOSAL')->result_array();
    }
    public function get_data_skpd() {
        return $this->db->get('SKPD')->result_array();
    }
    public function get_data_bast() {
        return $this->db->get('DOKUMEN_BAST')->result_array();
    }
    public function get_data_pejdaerah() {
        return $this->db->get('PEJABAT_DAERAH')->result_array();
    }
    public function get_data_pengujian() {
        return $this->db->get('PENGUJIAN')->result_array();
    }
}


?>
