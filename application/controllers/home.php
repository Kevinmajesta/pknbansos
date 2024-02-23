<?php 

class Home extends CI_Controller{
	public function __construct()
	{
		parent:: __construct();
    
    $user_data = $this->session->userdata;
    $this->load->vars($user_data);
	}
	
	function index()
	{
		$data['title'] = PRODUCT_NAME;
		$this->load->model('Proposal_model');
        $data['PROPOSAL'] = $this->Proposal_model->get_data_proposal();
		$data['SKPD'] = $this->Proposal_model->get_data_skpd();
		$data['BAST'] = $this->Proposal_model->get_data_bast();
		$data['pejdaerah'] = $this->Proposal_model->get_data_pejdaerah();
		$data['pengujian'] = $this->Proposal_model->get_data_pengujian();
		$data['main_content'] = 'v_home';
		$this->load->view('layout/template', $data);
	}
}