<?php

class Kategori_rekening extends Base_Controller {
	public function __construct()
  {
    parent::__construct();
		$this->load->model('kategorirekening_model','data_model');
		$this->load->model('auth/login_model', 'auth');
		$this->load->library('session');
		$this->load->library('form_validation');
	}
	
	public function index() 
	{
		$data['title']=PRODUCT_NAME.' - '.' Kategori Rekening';
    $data['modul'] = 'kategorirekening';
		$data['main_content']='kategorirekening_view';
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
		
		$this->form_validation->set_rules('kode','KODE REKENING','required|trim|max_length[50]');
		//$this->form_validation->set_rules('kode','KODE REKENING','callback_duplikasi_kode');
		$this->form_validation->set_rules('nama','NAMA REKENING','required|trim|max_length[200]');
		$this->form_validation->set_rules('saldo','SALDO NORMAL','required|trim|max_length[2]');
	
		if ($this->form_validation->run() == TRUE)
		{
			if($oper == 'edit'){
				if($id == 'new'){
					$result = $this->data_model->check_isi();
					if ($result==FALSE) {
						$newid = $this->data_model->insert_data();
						$response->isSuccess = TRUE;
						$response->message = 'Master Rekening telah di simpan';
						$response->id = $newid;
					}
					else 
					{
						// master rekening sudah ada, tampilkan pesan kesalahan
						$response->isSuccess = FALSE;
						$response->message = 'Master Rekening tersebut sudah ada.';
					}
				}
				else
				{
					$rst = $this->data_model->check_data2();
					if ($rst == TRUE){
						$this->data_model->update_data($id);
						$response->isSuccess = TRUE;
						$response->message = 'Master Rekening sudah di ubah';
					}
					else 
					{
						// master rekening sudah ada, tampilkan pesan kesalahan
						$response->isSuccess = FALSE;
						$response->message = 'Master Rekening tersebut sudah ada.';
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
		$result= $this->data_model->check_dependency($id);
		
		if ($result) {
			$this->data_model->delete_data($id);
			$response->isSuccess = TRUE;
			$response->message = 'Rekening sudah di hapus';
			//$response->ada = true;
		}
		else
		{
			$response->isSuccess = FALSE;
			$response->message = 'Rekening tidak bisa di hapus, masih dipakai di tabel lain';
			//$this->output->set_status_header('500');
			//$response->ada = false;
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
				//"limit" => null,
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
		
		
		for($i=0; $i<count($result); $i++)
		{
			$response->rows[$i]['id']=$result[$i]['ID_MASTER_REKENING'];
			if($result[$i]['SALDO_NORMAL'] == 1)
				{
					$SALDO_NORMAL='Kredit';
				}
				else
				{
					$SALDO_NORMAL='Debet';
				}
			
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->rows[$i]['cell']=array(
				$result[$i]['KODE_REKENING'],
				$result[$i]['NAMA_REKENING'],
				$SALDO_NORMAL
			);
		}
		echo json_encode($response);
		
	}
	
	function duplikasi_kode($str)
	{
		$id = $this->input->post('id');
		if ($this->data_model->check_duplication($str, $id))
		{
			$this->form_validation->set_message('duplikasi_kode', '%s "'.$str.'" sudah ada.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	
	
	
}