<?php
class Proposal_controller extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load model atau library yang diperlukan di sini
    }
    
    public function index() {
        $this->load->model('Proposal_model');
        $data['SPP'] = $this->Proposal_model->get_data();
        $this->load->view('layout/template', $data);
    }
}

