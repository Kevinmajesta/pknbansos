<?php

class Sumber_dana extends Base_Controller {
	public function __construct()
    {
		parent::__construct();
		$this->load->model('sumberdana_model','data_model');
		$this->load->model('auth/login_model', 'auth');
		$this->load->library('session');
		$this->load->library('form_validation');
	}
	
	public function index() 
	{
		$data['title']=PRODUCT_NAME.' - '.' Sumber Dana';
		$data['main_content']='sumberdana_view';
		$data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
		$this->load->view('layout/template',$data);
	}
	
	public function proses_form() 
	{
		$response = (object) NULL;
    $id = $this->input->post('id');
		$idrekening = $this->input->post('idrekening');
		$oper = $this->input->post('oper');

		if($oper == 'del') 
		{
			$this->hapus($id);
			return '';
		} 
		
		$this->form_validation->set_rules('namasumber', 'NAMA SUMBER DANA', 'required|trim|max_length[100]');
		//$this->form_validation->set_rules('idrekening', 'ID REKENING', 'required|trim|max_length[20]');
		//$this->form_validation->set_rules('nama_rekening', 'NAMA REKENING', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('namabank', 'NAMA BANK', 'required|trim|max_length[100]');
		$this->form_validation->set_rules('norekening', 'REKENING BANK', 'required|trim|max_length[100]');
		$idrek = $this->input->post('idrek');
		if ($this->form_validation->run()==TRUE)
		{
			if($oper == 'edit') {
				if($id == 'new') {
				$result = $this->data_model->check_isi();
				if ($result == FALSE){
					$newid = $this->data_model->insert_data();
					$response->isSuccess = TRUE;
					$response->message = 'Sumber Dana telah disimpan.';
					$response->id = $newid;
				} 
				else {
					$response->isSuccess = FALSE;					
					$response->message = 'Sumber Dana tersebut sudah ada.';
				}
			}
			else 
			{
				$result = $this->data_model->check_isi_1();
				if ($result == TRUE){
					$result2 = $this->data_model->check_isi_2();
					if ($result2 == TRUE){
						$this->data_model->update_data($id);
						$response->isSuccess = TRUE;
						$response->message = 'Sumber Dana telah dirubah.';
					}
					else{
						$response->isSuccess = FALSE;
						$response->message = 'Sumber Dana tidak bisa disimpan, Nama Sumber Dana telah dipakai.';
					}
				} 
				else 
				{
					$response->isSuccess = FALSE;
					$response->message = 'Sumber Dana tidak bisa disimpan, Rekening telah dipakai.';
				}
			}
				
			}
		}
		else 
		{
			$response->error = validation_errors();
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
			$response->isSuccess = TRUE;
			$response->message = 'Sumber Dana telah dihapus';
		} 
		else 
		{
			// ada dependensi, tampilkan pesan kesalahan
			$response->isSuccess = FALSE;
			$response->message = 'Sumber Dana tidak bisa dihapus, masih dipakai di tabel lain.';
			//$this->output->set_status_header('500');
		}
		echo json_encode($response);
	}
	

	
	public function get_daftar() 
	{
		$response = (object) NULL;
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
		   
		$row = $this->data_model->get_data($req_param);
		$row = $row->result_array();
		$count = count($row);
		if( $count >0 ) { 
			$total_pages = ceil($count/$limit); 
		} else { 
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
		  
		$result = $this->data_model->get_data($req_param)->result_array();
		
		$response->page = $page; 
		$response->total = $total_pages; 
		$response->records = $count;
					
		for($i=0; $i<count($result); $i++)
		{
			$response->rows[$i]['id']=$result[$i]['ID_SUMBER_DANA'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->rows[$i]['cell']=array(
											$result[$i]['NAMA_SUMBER_DANA'],
											$result[$i]['ID_REKENING'],
											$result[$i]['KODE_REKENING'],
											$result[$i]['NAMA_REKENING'],
											$result[$i]['NAMA_BANK'],
											$result[$i]['NO_REKENING_BANK']
											
										);
		}
		echo json_encode($response); 
	
	}
	
}