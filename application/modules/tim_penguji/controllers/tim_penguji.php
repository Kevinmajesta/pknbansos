<?php

class Tim_penguji extends Base_Controller {
	public function __construct()
  {
    parent::__construct();
	$this->load->model('timpenguji_model','data_model');
    $this->load->model('auth/login_model', 'auth');
    $this->load->library('session');
    $this->load->library('form_validation');
	}
	
	public function index() 
	{
		$data['title'] = PRODUCT_NAME.' - '.' Daftar Tim Penguji';
		$data['main_content'] = 'timpenguji_view';
		$data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
		$this->load->view('layout/template', $data);
	}
	
	public function get_daftar() 
	{
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
			   
			$row = $this->data_model->get_data($req_param);
			if (!$row)
			{
				$response->total = 0;
				$response->records = 0;
				echo json_encode($response);
				return '';
			
			}
			
			$count = count($row); 
			$total_pages = ceil($count/$limit); 
			
			if ($page > $total_pages) 
			$page=$total_pages; 
			$start = $limit*$page - $limit; // do not put $limit*($page - 1) 
			if($start <0) $start = 0;
			$req_param['limit'] = array(
					'start' => $start,
					'end' => $limit
			);
			
			  
			$result = $this->data_model->get_data($req_param);
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['ID_PEJABAT_PENGUJI'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['ID_PEJABAT_PENGUJI'],
												$result[$i]['NAMA_PEJABAT'],
												$result[$i]['NIP'],
												$result[$i]['ID_SKPD'],
												$result[$i]['NAMA_SKPD']
											);
			}
			echo json_encode($response); 
		}
	}	
	
	public function proses_form() 
	{
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
		
		if($oper == 'del') {
			$this->hapus($id);
			return '';
		}
		
		$this->form_validation->set_rules('nama_pejabat', 'NAMA PEJABAT', 'required|max_length[255]');
		$this->form_validation->set_rules('nip', 'NIP', 'required|max_length[255]');
		$this->form_validation->set_rules('idskpd', 'SKPD', 'required');

		if ($this->form_validation->run()==TRUE)
		{
			if($oper == 'edit') {
				if($id == 'new') {
					$result = $this->data_model->check_timpenguji();
					if ($result == FALSE) {
						$newid = $this->data_model->insert_data();
						$response->isSuccess = TRUE;
						$response->message = 'Tim Penguji telah disimpan.';
						//$response->id = $newid;
					}
					else 
					{
						// Tim Penguji sudah ada, tampilkan pesan kesalahan
						$response->isSuccess = FALSE;
						$response->message = 'Tim Penguji tersebut sudah ada.';
					}
				} 
				else {
					$cek_result = $this->data_model->check_update($id);
					if ($cek_result == FALSE) {
						$this->data_model->update_data($id);
						$response->isSuccess = TRUE;
						$response->message = 'Tim Penguji telah diubah.';
					}
					else 
					{
						// Tim Penguji sudah ada, tampilkan pesan kesalahan
						$response->isSuccess = FALSE;
						$response->message = 'Tim Penguji dengan NIP tersebut sudah ada.';
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
		$id = $this->input->post('id');
		$result = $this->data_model->check_dependency($id);
		if ($result) {
			// bisa dihapus
			$this->data_model->delete_data($id);
			$response->isSuccess = TRUE;
			$response->message = 'Tim Penguji telah dihapus';
		} 
		else 
		{
			// ada dependensi, tampilkan pesan kesalahan
			$response->isSuccess = FALSE;
			$response->message = 'Tim Penguji tidak bisa dihapus, masih dipakai di tabel lain.';
		}
		echo json_encode($response);
	}
}
	
	