<?php

class Bidang extends Admin_Controller {
	public function __construct()
    {
        parent::__construct();
		$this->load->model('bidang_model','data_model');
		$this->load->library(array('session','form_validation'));
    $this->load->model('auth/login_model', 'auth');
	}
	
	public function index() 
	{		    	
		$data['user_data']['DATA_DASAR_LOGIN'] = $this->session->userdata('group');
		$data['title']='Daftar Bidang';
		$data['main_content']='bidang_view';
    $data['modul'] = 'bidang';
    $data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
		$this->load->view('layout/template',$data);
	}
	
	public function proses_form()
	{
		$response = (object) NULL;
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
		
		if($oper == 'del') 
		{
			$this->hapus($id);
			return '';
		} 
		
		$this->form_validation->set_rules('fungsi', 'ID_FUNGSI', 'max_length[10]');
		$this->form_validation->set_rules('urusan', 'URUSAN', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('bidang', 'BIDANG', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('nama', 'NAMA', 'required|trim|max_length[200]');
	
		if ($this->form_validation->run()==TRUE)
			{
				if($oper == 'edit') {
					if($id == 'new') {
						$result = $this->data_model->check_data();						
						if ($result == 1){
							$rst = $this->data_model->check_data_bidang();								
							if ($rst != 1){
								$newid = $this->data_model->insert_data();
								$response->message = 'Bidang telah disimpan.';
								$response->isSuccess = TRUE;
								$response->id = $newid;
							}
							else{
								// Kode Bidang sudah ada, tampilkan pesan kesalahan
								$response->isSuccess = FALSE;
								$response->message = 'Dalam Satu Urusan,Tidak Boleh Ada Kode Bidang Yang Sama';
							}
						}
						else 
						{
							// Bidang sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Bidang tersebut sudah ada.';
						}
					} 
					else {
						$rst = $this->data_model->check_data2();
						if ($rst == TRUE){
							$this->data_model->update_data($id);
							$response->isSuccess = TRUE;
							$response->message = 'Bidang telah diubah.';
						}
						else 
						{
							// Bidang sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Bidang tersebut sudah ada..';
						}
					}
				}
			}
			else 
			{
				$response->isSuccess = FALSE;
			    $response->message = validation_errors();
				$this->output->set_status_header('500');			
			}
			echo json_encode($response);
	
	}
	
	public function hapus() 
	{
		$response = (object) NULL;
    $id = $this->input->post('id');				
		$result = $this->data_model->check_dependency($id);
		if ($result) {
			// bisa dihapus
			$this->data_model->delete_data($id);
			$response->message = 'Bidang telah dihapus';
			$response->isSuccess = TRUE;
		} 
		else 
		{
			// ada dependensi, tampilkan pesan kesalahan
			$response->message = 'Bidang tidak bisa dihapus, masih dipakai di tabel lain.';
			$response->isSuccess = FALSE;
			//$this->output->set_status_header('500');
		}
		echo json_encode($response);
	}
	
	public function get_daftar() 
	{
		$response = (object) NULL;
    if(!isset($_POST['oper']))
		{
			
			$page = $_REQUEST['page']; // get the requested page 
			$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid 
			$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort 
			$sord = $_REQUEST['sord']; // get the direction if(!$sidx) $sidx =1;  
			 
			$req_param = array (
					"sort_by" => $sidx,
					"sort_direction" => $sord,
					"limit" => null,
					"search" => $_REQUEST['_search'],
					"search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
					"search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
			);     
			  		
      $count = $this->data_model->get_data($req_param, TRUE);
      $response = (object) NULL;
       if($count == 0) // tidak ada data
      {
        echo json_encode($response);
        return '';
      }

			$total_pages = ceil($count/$limit); 
			if ($page > $total_pages) 
				$page=$total_pages; 

			$result = $this->data_model->get_data($req_param);
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
			
			$ids ='';
			$z=0;
			$last_fungsi = 0;
			$response->rows = array();		
			for($i=0; $i<count($result); $i++)
			{
				if ($result[$i]['ID_FUNGSI'] != $last_fungsi){
					// tambahkan fungsi
					$response->rows[$z]['id']='id_fungsi_'.$result[$i]['KODE_FUNGSI'];
					$response->rows[$z]['cell']=array(
					'00',
					$result[$i]['ID_FUNGSI'],
					$result[$i]['KODE_FUNGSI'],
					null,
					null,
					$result[$i]['NAMA_FUNGSI']
					);
					$z++;							
				}
				
				if($result[$i]['ID_BIDANG'] != '')
				{
				$response->rows[$z]['id']=$result[$i]['ID_BIDANG'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$z]['cell']=array(
												$result[$i]['ID_BIDANG'],
												$result[$i]['ID_FUNGSI'],
												null,
												$result[$i]['KODE_URUSAN'],
												$result[$i]['KODE_BIDANG'],
												$result[$i]['NAMA_BIDANG']
											);
				$z++;
				$last_fungsi = $result[$i]['ID_FUNGSI'];	
				}
			}
			echo json_encode($response); 
		}
	}
	
	public function getselect()
	{
		//$this->data_model->get_opt_get_opt_urusan();
		echo form_dropdown('', $this->data_model->get_opt_urusan() );
	}
	
	function pilihtambahan()
	{
		$this->load->view('bidang_pilih');
	}
	
	public function get_bidang() 
	{
		$response = (object) NULL;
    if(!isset($_POST['oper']))
		{
			$page = $_REQUEST['page']; // get the requested page 
			$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid 
			$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort 
			$sord = $_REQUEST['sord']; // get the direction if(!$sidx) $sidx =1;  
			 
			$req_param = array (
					"sort_by" => $sidx,
					"sort_direction" => $sord,
					"limit" => null,
					"search" => $_REQUEST['_search'],
					"search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
					"search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
			);     
			  
			$row = $this->data_model->get_data_bidang($req_param);
			//print_r($row);die();
			$count = count($row); 
			if( $count >0 ) 
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else
			{ 
				$total_pages = 0; 
			} 
			if ($page > $total_pages) 
				$page=$total_pages; 
			$start = $limit*$page - $limit; // do not put $limit*($page - 1) 
			if($start <0) $start = 0;
			$req_param['limit'] = array(
						'start' => $start,
						'end' => $limit
			);
			
			$result = $this->data_model->get_data_bidang($req_param);
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['ID_BIDANG']=$result[$i]['ID_BIDANG'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['ID_BIDANG'],
												$result[$i]['KODE_BIDANG_LKP'],
												$result[$i]['NAMA_BIDANG']
											);
			}
			echo json_encode($response); 
		}
	}
}