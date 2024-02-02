<?php

class Lokasi extends Admin_Controller {
	public function __construct()
  {
    parent::__construct();
		$this->load->model('lokasi_model','data_model');
    $this->load->model('auth/login_model', 'auth');
    $this->load->library('session');
    $this->load->library('form_validation');
	}
	
	public function index() 
	{
		$data['title'] = PRODUCT_NAME.' - '.' Daftar Lokasi';
		$data['main_content'] = 'lokasi_view';
		$data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
		$this->load->view('layout/template', $data);
	}
	
	public function proses_form() 
	{
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
		
		if($oper == 'del') {
			$this->hapus($id);
			return '';
		}
		
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'required|max_length[255]');
		//$this->form_validation->set_rules('lokasi', 'Lokasi', 'callback_duplikasi_kode');

		if ($this->form_validation->run()==TRUE)
		{
			if($oper == 'edit') {
				if($id == 'new') {
					$result = $this->data_model->check_lokasi();
					if ($result == FALSE) {
						$newid = $this->data_model->insert_data();
						$response->isSuccess = TRUE;
						$response->message = 'Lokasi telah disimpan.';
						$response->id = $newid;
					}
					else 
					{
						// lokasi sudah ada, tampilkan pesan kesalahan
						$response->isSuccess = TRUE;
						$response->message = 'Lokasi tersebut sudah ada.';
					}
				} else {
					$this->data_model->update_data($id);
          $response->isSuccess = TRUE;
					$response->message = 'Lokasi telah diubah.';
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
			$cek2 = $this->data_model->check_parent($id);
			if($cek2)
			{
				$this->data_model->delete_data($id);
				$response->isSuccess = TRUE;
				$response->message = 'Lokasi telah dihapus';
			}
			else
			{
				$response->isSuccess = FALSE;
				$response->message = 'Lokasi tidak bisa dihapus, silahkan hapus sub-lokasinya terlebih dahulu.';
				//$this->output->set_status_header('500');
			}
		} 
		else 
		{
			// ada dependensi, tampilkan pesan kesalahan
      $response->isSuccess = FALSE;
			$response->message = 'Lokasi tidak bisa dihapus, masih dipakai di tabel lain.';
			//$this->output->set_status_header('500');
		}

		echo json_encode($response);
	}
	
/* 	function duplikasi_kode($str)
	{
		if ($this->data_model->get_data_by_duplikasi($str))
		{
			$this->form_validation->set_message('duplikasi_kode', '%s "'.$str.'" sudah ada.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
 */	
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
				$response->rows[$i]['id']=$result[$i]['ID_LOKASI'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['ID_LOKASI'],
												$result[$i]['LOKASI']
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_daftar_sublokasi($ID_LOKASI='') 
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
			   
			$row = $this->data_model->get_data_sublokasi($req_param,$ID_LOKASI);
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
			
			  
			$result = $this->data_model->get_data_sublokasi($req_param,$ID_LOKASI)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['ID_LOKASI'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['LOKASI']
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function proses_form_sublokasi() 
	{
		//$ID_PARENT_LOKASI = $this->input->post('idlokasi');
		//$ID_LOKASI = $this->input->post('ID_LOKASI');
		$idparent 	= $this->input->post('idparent');
		$id 		= $this->input->post('id');
		$oper 		= $this->input->post('oper');
		
		if($oper == 'del') {
			$this->hapus($id);
			return '';
		}
		
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'required|max_length[100]');
		//$this->form_validation->set_rules('lokasi', 'Lokasi', 'callback_duplikasi_kode');

		if ($this->form_validation->run()==TRUE)
		{
			if($oper == 'edit') {
				if($id == 'new') {
					$result = $this->data_model->check_sub_lokasi();
					if ($result == FALSE){
						$newid = $this->data_model->insert_data_sublokasi();
						$response->isSuccess = TRUE;
						$response->message = 'Sub Lokasi telah disimpan.';
						$response->id = $newid;
					}
					else 
					{
						// sumber dana sudah ada, tampilkan pesan kesalahan
						$response->isSuccess = FALSE;
						$response->message = 'Sub Lokasi tersebut sudah ada.';
					}
				} else {
					$this->data_model->update_data_sublokasi($id);
          $response->isSuccess = TRUE;
					$response->message = 'Sub Lokasi telah diubah.';
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
	
	public function get_daftar_sublokasi_kampung($ID_LOKASI='') 
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
			   
			$row = $this->data_model->get_data_sublokasi_kampung($req_param,$ID_LOKASI);
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
			
			  
			$result = $this->data_model->get_data_sublokasi_kampung($req_param,$ID_LOKASI)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['ID_LOKASI'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['LOKASI']
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function proses_form_sublokasi_kampung() 
	{
		$idparent = $this->input->post('idparent');
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
		
		if($oper == 'del') {
			$this->hapus($id);
			return '';
		}
		
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'required|max_length[100]');
		//$this->form_validation->set_rules('lokasi', 'Lokasi', 'callback_duplikasi_kode');

		if ($this->form_validation->run()==TRUE)
		{
			if($oper == 'edit') {
				if($id == 'new') {
					$result = $this->data_model->check_sub_lokasi_kampung();
					if ($result == FALSE){
						$newid = $this->data_model->insert_data_sublokasi_kampung();
						$response->isSuccess = TRUE;
						$response->message = 'Sub Lokasi telah disimpan.';
						$response->id = $newid;
					}
					else 
					{
						// sumber dana sudah ada, tampilkan pesan kesalahan
						$response->isSuccess = FALSE;
						$response->message = 'Sub Lokasi tersebut sudah ada.';
					}
				} else {
					$this->data_model->update_data_sublokasi_kampung($id);
          $response->isSuccess = TRUE;
					$response->message = 'Sub Lokasi telah diubah.';
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
	
	public function get_daftar_sublokasi_rw($ID_LOKASI='') 
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
			   
			$row = $this->data_model->get_data_sublokasi_rw($req_param,$ID_LOKASI);
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
			
			  
			$result = $this->data_model->get_data_sublokasi_rw($req_param,$ID_LOKASI)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['ID_LOKASI'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['LOKASI']
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function proses_form_sublokasi_rw() 
	{
		$idparent = $this->input->post('idparent');
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
		
		if($oper == 'del') {
			$this->hapus($id);
			return '';
		}
		
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'required|max_length[100]');
		//$this->form_validation->set_rules('lokasi', 'Lokasi', 'callback_duplikasi_kode');

		if ($this->form_validation->run()==TRUE)
		{
			if($oper == 'edit') {
				if($id == 'new') {
					$result = $this->data_model->check_sub_lokasi_rw();
					if ($result == FALSE){
						$newid = $this->data_model->insert_data_sublokasi_rw();
						$response->isSuccess = TRUE;
						$response->message = 'Sub Lokasi telah disimpan.';
						$response->id = $newid;
					}
					else 
					{
						// sumber dana sudah ada, tampilkan pesan kesalahan
						$response->isSuccess = FALSE;
						$response->message = 'Sub Lokasi tersebut sudah ada.';
					}
				} else {
					$this->data_model->update_data_sublokasi_rw($id);
          $response->isSuccess = TRUE;
					$response->message = 'Sub Lokasi telah diubah.';
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
	
	public function get_daftar_sublokasi_rt($ID_LOKASI='') 
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
			   
			$row = $this->data_model->get_data_sublokasi_rt($req_param,$ID_LOKASI);
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
			
			  
			$result = $this->data_model->get_data_sublokasi_rt($req_param,$ID_LOKASI)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['ID_LOKASI'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['LOKASI']
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function proses_form_sublokasi_rt() 
	{
		$idparent = $this->input->post('idparent');
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
		
		if($oper == 'del') {
			$this->hapus($id);
			return '';
		}
		
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'required|max_length[100]');
		//$this->form_validation->set_rules('lokasi', 'Lokasi', 'callback_duplikasi_kode');

		if ($this->form_validation->run()==TRUE)
		{
			if($oper == 'edit') {
				if($id == 'new') {
					$result = $this->data_model->check_sub_lokasi_rt();
					if ($result == FALSE){
						$newid = $this->data_model->insert_data_sublokasi_rt();
						$response->isSuccess = TRUE;
						$response->message = 'Sub Lokasi telah disimpan.';
						$response->id = $newid;
					}
					else 
					{
						// sumber dana sudah ada, tampilkan pesan kesalahan
						$response->isSuccess = FALSE;
						$response->message = 'Sub Lokasi tersebut sudah ada.';
					}
				} else {
					$this->data_model->update_data_sublokasi_rt($id);
          $response->isSuccess = TRUE;
					$response->message = 'Sub Lokasi telah diubah.';
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
	
	
	public function get_list() 
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
			   
			$row = $this->data_model->get_data2($req_param);
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
			
			  
			$result = $this->data_model->get_data2($req_param);
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
			$z = 0;
			for($i=0; $i<count($result); $i++)
			{
				for($i=0; $i<count($result); $i++)
				{
					if($result[$i]['LEVEL_LOKASI']=='1'){
						$LOKASI= $result[$i]['LOKASI'];
					}
					elseif($result[$i]['LEVEL_LOKASI']=='2'){
						$LOKASI= '   '.$result[$i]['LOKASI'];
					}
					elseif($result[$i]['LEVEL_LOKASI']=='3'){
						$LOKASI= '      '.$result[$i]['LOKASI'];
					}
					elseif($result[$i]['LEVEL_LOKASI']=='4'){
						$LOKASI= '         '.$result[$i]['LOKASI'];
					}
					
					
					$response->rows[$i]['id']=$result[$i]['ID_LOKASI'];
					// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
					$response->rows[$i]['cell']=array(
													$result[$i]['ID_LOKASI'],
													$LOKASI,
													$result[$i]['LEVEL_LOKASI']
												);
				}
			}
			echo json_encode($response); 
		}
	}
	
}
	
	