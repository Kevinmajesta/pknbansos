<?php

class Rekening extends Base_Controller {
	public function __construct()
  {
    parent::__construct();
		$this->load->model('rekening_model','data_model');
		$this->load->model('auth/login_model', 'auth');
		$this->load->library('session');
		$this->load->library('form_validation');
	}
	
	public function index() 
	{
		$data['title']=PRODUCT_NAME.' - '.' Rekening';
		$data['main_content']='rekening_view';
		$data['modul']='rekening';
		$data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    $data['fields'] = $this->data_model->get_data_fields();
		$this->load->view('layout/template',$data);
	}
	
	/* Rekening */
	public function session_id()
	{
		$ID_REKENING = $_POST['ID_REKENING'];
		$this->session->set_userdata('ID_REKENING',$ID_REKENING);
	}
	
	public function proses_form() 
	{
		$response = (object) NULL;
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
		
		$ID_REKENING = $this->session->userdata('ID_REKENING');	
		
		if($oper == 'del') 
		{
			$this->hapus($ID_REKENING);
			return '';
		} 
		
		$this->form_validation->set_rules('KATEGORI', 'KATEGORI', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('KODE_REKENING', 'KODE REKENING', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('NAMA_REKENING', 'NAMA REKENING', 'required|trim|max_length[200]');
		$this->form_validation->set_message('required', '%s Harus diisi');
	
		if ($this->form_validation->run()==TRUE)
			{
				if($oper == 'edit') {
					if($id == 'new') {
						$result = $this->data_model->check_data();
						if ($result == FALSE){
							$result_induk = $this->data_model->check_data_induk();
							if ($result_induk == FALSE){
								$newid = $this->data_model->insert_data();
								$response->isSuccess = TRUE;
								$response->message = 'Rekening telah disimpan.';
								$response->id = $newid;
							}
							else 
							{
								$response->isSuccess = FALSE;
								$response->message = 'Rekening tersebut belum memiliki induk di atasnya.';
							}
						}
						else 
						{
							// Rekening sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Rekening tersebut sudah ada.';
						}
					} else {
						$result2 = $this->data_model->check_data_1();
						if ($result2 == TRUE){
							$this->data_model->update_data($ID_REKENING);
							$response->isSuccess = TRUE;
							$response->message = 'Rekening telah diubah.';
						}
						else 
						{
							// Rekening sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Rekening tersebut sudah ada.';
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
	
	public function proses_form_kelompok() 
	{
		$response = (object) NULL;
    $idparent = $this->input->post('idparent');
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
		
		$ID_REKENING = $this->session->userdata('ID_REKENING');	
		
		if($oper == 'del') 
		{
			$this->hapus($ID_REKENING);
			return '';
		} 
		
		$this->form_validation->set_rules('KATEGORI', 'KATEGORI', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('KODE_REKENING', 'KODE REKENING', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('NAMA_REKENING', 'NAMA REKENING', 'required|trim|max_length[200]');
		$this->form_validation->set_message('required', '%s Harus diisi');
	
		if ($this->form_validation->run()==TRUE)
			{
				if($oper == 'edit') {
					if($id == 'new') {
						$result = $this->data_model->check_data_kelompok();
						if ($result == FALSE){
							$newid = $this->data_model->insert_data_kelompok();
							$response->isSuccess = TRUE;
							$response->message = 'Rekening telah disimpan.';
							$response->id = $newid;
						}
						else 
						{
							// Rekening sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Rekening tersebut sudah ada.';
						}
					} 
					else {
						$result = $this->data_model->check_data_kelompok_1();
						if ($result == TRUE){
							$this->data_model->update_data_kelompok($ID_REKENING);
							$response->isSuccess = TRUE;
							$response->message = 'Rekening telah diubah.';
						}
						else 
						{
							// Rekening sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Rekening tersebut sudah ada.';
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
	
	public function proses_form_jenis() 
	{
		$response = (object) NULL;
    $idparent = $this->input->post('idparent');
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
		
		$ID_REKENING = $this->session->userdata('ID_REKENING');	
		
		if($oper == 'del') 
		{
			$this->hapus($ID_REKENING);
			return '';
		} 
		
		$this->form_validation->set_rules('KATEGORI', 'KATEGORI', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('KODE_REKENING', 'KODE REKENING', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('NAMA_REKENING', 'NAMA REKENING', 'required|trim|max_length[200]');
		$this->form_validation->set_message('required', '%s Harus diisi');
	
		if ($this->form_validation->run()==TRUE)
			{
				if($oper == 'edit') {
					if($id == 'new') {
						$result = $this->data_model->check_data_jenis();
						if ($result == FALSE){
							$newid = $this->data_model->insert_data_jenis();
							$response->isSuccess = TRUE;
							$response->message = 'Rekening telah disimpan.';
							$response->id = $newid;
						}
						else 
						{
							// Rekening sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Rekening tersebut sudah ada.';
						}
					} else {
						$result = $this->data_model->check_data_jenis_1();
						if ($result == TRUE){
							$this->data_model->update_data_jenis($ID_REKENING);
							$response->isSuccess = TRUE;
							$response->message = 'Rekening telah diubah.';
						}
						else 
						{
							// Rekening sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Rekening tersebut sudah ada.';
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
	
	public function proses_form_objek() 
	{
		$response = (object) NULL;
    $idparent = $this->input->post('idparent');
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
		
		$ID_REKENING = $this->session->userdata('ID_REKENING');	
		
		if($oper == 'del') 
		{
			$this->hapus($ID_REKENING);
			return '';
		} 
		
		$this->form_validation->set_rules('KATEGORI', 'KATEGORI', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('KODE_REKENING', 'KODE REKENING', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('NAMA_REKENING', 'NAMA REKENING', 'required|trim|max_length[200]');
		$this->form_validation->set_message('required', '%s Harus diisi');
	
		if ($this->form_validation->run()==TRUE)
			{
				if($oper == 'edit') {
					if($id == 'new') {
						$result = $this->data_model->check_data_objek();
						if ($result == FALSE){
							$newid = $this->data_model->insert_data_objek();
							$response->isSuccess = TRUE;
							$response->message = 'Rekening telah disimpan.';
							$response->id = $newid;
						}
						else 
						{
							// Rekening sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Rekening tersebut sudah ada.';
						}
					} else {
						$result = $this->data_model->check_data_objek_1();
						if ($result == TRUE){
							$this->data_model->update_data_objek($ID_REKENING);
							$response->isSuccess = TRUE;
							$response->message = 'Rekening telah diubah.';
						}
						else 
						{
							// Rekening sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Rekening tersebut sudah ada.';
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
	
	public function proses_form_rincian() 
	{
		$response = (object) NULL;
    $idparent = $this->input->post('idparent');
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
		
		$ID_REKENING = $this->session->userdata('ID_REKENING');	
		
		if($oper == 'del') 
		{
			$this->hapus($ID_REKENING);
			return '';
		} 
		
		$this->form_validation->set_rules('KATEGORI', 'KATEGORI', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('KODE_REKENING', 'KODE REKENING', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('NAMA_REKENING', 'NAMA REKENING', 'required|trim|max_length[200]');
		$this->form_validation->set_message('required', '%s Harus diisi');
	
		if ($this->form_validation->run()==TRUE)
			{
				if($oper == 'edit') {
					if($id == 'new') {
						$result = $this->data_model->check_data_rincian();
						if ($result == FALSE){
							$newid = $this->data_model->insert_data_rincian();
							$response->isSuccess = TRUE;
							$response->message = 'Rekening telah disimpan.';
							$response->id = $newid;
						}
						else 
						{
							// Rekening sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Rekening tersebut sudah ada.';
						}
					} else {
						$result = $this->data_model->check_data_rincian_1();
						if ($result == TRUE){
							$this->data_model->update_data_rincian($ID_REKENING);
							$response->isSuccess = TRUE;
							$response->message = 'Rekening telah diubah.';
						}
						else 
						{
							// Rekening sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Rekening tersebut sudah ada.';
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
	
	public function proses_form_sub1() 
	{
		$response = (object) NULL;
    $idparent = $this->input->post('idparent');
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
		
		$ID_REKENING = $this->session->userdata('ID_REKENING');	
		
		if($oper == 'del') 
		{
			$this->hapus($ID_REKENING);
			return '';
		} 
		
		$this->form_validation->set_rules('KATEGORI', 'KATEGORI', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('KODE_REKENING', 'KODE REKENING', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('NAMA_REKENING', 'NAMA REKENING', 'required|trim|max_length[200]');
		$this->form_validation->set_message('required', '%s Harus diisi');
	
		if ($this->form_validation->run()==TRUE)
			{
				if($oper == 'edit') {
					if($id == 'new') {
						$result = $this->data_model->check_data_sub1();
						if ($result == FALSE){
							$newid = $this->data_model->insert_data_sub1();
							$response->isSuccess = TRUE;
							$response->message = 'Rekening telah disimpan.';
							$response->id = $newid;
						}
						else 
						{
							// Rekening sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Rekening tersebut sudah ada.';
						}
					} else {
						$result = $this->data_model->check_data_sub1_1();
						if ($result == TRUE){
							$this->data_model->update_data_sub1($ID_REKENING);
							$response->isSuccess = TRUE;
							$response->message = 'Rekening telah diubah.';
						}
						else 
						{
							// Rekening sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Rekening tersebut sudah ada.';
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
	
	public function proses_form_sub2() 
	{
		$response = (object) NULL;
    $idparent = $this->input->post('idparent');
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
		
		$ID_REKENING = $this->session->userdata('ID_REKENING');	
		
		if($oper == 'del') 
		{
			$this->hapus($ID_REKENING);
			return '';
		} 
		
		$this->form_validation->set_rules('KATEGORI', 'KATEGORI', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('KODE_REKENING', 'KODE REKENING', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('NAMA_REKENING', 'NAMA REKENING', 'required|trim|max_length[200]');
		$this->form_validation->set_message('required', '%s Harus diisi');
	
		if ($this->form_validation->run()==TRUE)
			{
				if($oper == 'edit') {
					if($id == 'new') {
						$result = $this->data_model->check_data_sub2();
						if ($result == FALSE){
							$newid = $this->data_model->insert_data_sub2();
							$response->isSuccess = TRUE;
							$response->message = 'Rekening telah disimpan.';
							$response->id = $newid;
						}
						else 
						{
							// Rekening sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Rekening tersebut sudah ada.';
						}
					} else {
						$result = $this->data_model->check_data_sub2_1();
						if ($result == TRUE){
							$this->data_model->update_data_sub2($ID_REKENING);
							$response->isSuccess = TRUE;
							$response->message = 'Rekening telah diubah.';
						}
						else 
						{
							// Rekening sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Rekening tersebut sudah ada.';
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
	
	public function proses_form_sub3() 
	{
		$response = (object) NULL;
    $idparent = $this->input->post('idparent');
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
		
		$ID_REKENING = $this->session->userdata('ID_REKENING');	
		
		if($oper == 'del') 
		{
			$this->hapus($ID_REKENING);
			return '';
		} 
		
		$this->form_validation->set_rules('KATEGORI', 'KATEGORI', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('KODE_REKENING', 'KODE REKENING', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('NAMA_REKENING', 'NAMA REKENING', 'required|trim|max_length[200]');
		$this->form_validation->set_message('required', '%s Harus diisi');
	
		if ($this->form_validation->run()==TRUE)
			{
				if($oper == 'edit') {
					if($id == 'new') {
						$result = $this->data_model->check_data_sub3();
						if ($result == FALSE){
							$newid = $this->data_model->insert_data_sub3();
							$response->isSuccess = TRUE;
							$response->message = 'Rekening telah disimpan.';
							$response->id = $newid;
						}
						else 
						{
							// Rekening sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Rekening tersebut sudah ada.';
						}
					} else {
						$result = $this->data_model->check_data_sub3_1();
						if ($result == TRUE){
							$this->data_model->update_data_sub3($ID_REKENING);
							$response->isSuccess = TRUE;
							$response->message = 'Rekening telah diubah.';
						}
						else 
						{
							// Rekening sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Rekening tersebut sudah ada.';
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
		$id		= $this->input->post('id');
		$kode	= $this->input->post('kode');
		$jml	= count($id);
		$sukes	= 0;
		$gagal	= 0;
		for($x=0;$x<count($id);$x++){
			$result_1 = $this->data_model->check_dependency_1($id[$x]);
			$result_2 = $this->data_model->check_dependency_2($id[$x]);
			$result_3 = $this->data_model->check_dependency_3($id[$x]);
			$result_4 = $this->data_model->check_dependency_4($id[$x]);
			$result_5 = $this->data_model->check_dependency_5($id[$x]);
			if ($result_1 && $result_2 && $result_3 && $result_4 && $result_5 ) 
			{
				$hapus = $this->data_model->delete_data($id[$x]);
				$response->isSuccess = TRUE;
				$response->message = 'Hapus data Rekening berhasil!';
			}
			else
			{
				$response->isSuccess = FALSE;
				$response->message = 'Rekening '.$kode[$x].' tidak bisa dihapus, masih dipakai di tabel lain.';
			}
		}
		echo json_encode($response);
	}
	
	public function get_daftar() 
	{
		$response = (object) NULL;
		$req_param='';
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
          "m" => isset($_REQUEST['m']) ? $_REQUEST['m'] : '',
          "q" => isset($_REQUEST['q']) ? $_REQUEST['q'] : '',
					"search" => $_REQUEST['_search'],
					"search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
					"search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
			);     
			   
			$count = $this->data_model->get_data($req_param, TRUE);
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
			  
			$result = $this->data_model->get_data($req_param);
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				
				
				if ($result[$i]['PAGU'] == null)
				{
					$pagu='0.00';
				}
				else
				{
					$pagu=$result[$i]['PAGU'];
				}
				$response->rows[$i]['id']=$result[$i]['ID_REKENING'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID_REKENING'],
												$result[$i]['ID_PARENT_REKENING'],
												$result[$i]['TIPE'],
												$result[$i]['KELOMPOK'],
												$result[$i]['JENIS'],
												$result[$i]['OBJEK'],
												$result[$i]['RINCIAN'],
												$result[$i]['SUB1'],
												$result[$i]['SUB2'],
												$result[$i]['SUB3'],
												$result[$i]['KODE_REKENING'],
												$result[$i]['NAMA_REKENING'],
												$result[$i]['KATEGORI'],
												$pagu
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_daftar_subrekening_kelompok($tipe) 
	{
		$response = (object) NULL;
    $req_param='';
	
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
			   
			$row = $this->data_model->get_data_subrekening_kelompok($req_param,$tipe);
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
			  
			$result = $this->data_model->get_data_subrekening_kelompok($req_param,$tipe)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['ID_REKENING'];
				
				if ($result[$i]['PAGU'] == null)
				{
					$pagu='0.00';
				}
				else
				{
					$pagu=$result[$i]['PAGU'];
				}
				
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID_REKENING'],
												$result[$i]['KODE_REKENING'],
												$result[$i]['NAMA_REKENING'],
												$result[$i]['KATEGORI'],
												$pagu
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_daftar_subrekening_jenis($tipe) 
	{
		$response = (object) NULL;
    $req_param='';
	
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
			   
			$row = $this->data_model->get_data_subrekening_jenis($req_param,$tipe);
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
			  
			$result = $this->data_model->get_data_subrekening_jenis($req_param,$tipe)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['ID_REKENING'];
				
				if ($result[$i]['PAGU'] == null)
				{
					$pagu='0';
				}
				else
				{
					$pagu=$result[$i]['PAGU'];
				}
				
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID_REKENING'],
												$result[$i]['KODE_REKENING'],
												$result[$i]['NAMA_REKENING'],
												$result[$i]['KATEGORI'],
												$pagu
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_daftar_subrekening_objek($tipe) 
	{
		$response = (object) NULL;
    $req_param='';
	
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
			   
			$row = $this->data_model->get_data_subrekening_objek($req_param,$tipe);
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
			  
			$result = $this->data_model->get_data_subrekening_objek($req_param,$tipe)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['ID_REKENING'];
				
				if ($result[$i]['PAGU'] == null)
				{
					$pagu='0.00';
				}
				else
				{
					$pagu=$result[$i]['PAGU'];
				}
				
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID_REKENING'],
												$result[$i]['KODE_REKENING'],
												$result[$i]['NAMA_REKENING'],
												$result[$i]['KATEGORI'],
												$pagu
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_daftar_subrekening_rincian($tipe) 
	{
		$response = (object) NULL;
    $req_param='';
	
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
			   
			$row = $this->data_model->get_data_subrekening_rincian($req_param,$tipe);
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
			  
			$result = $this->data_model->get_data_subrekening_rincian($req_param,$tipe)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['ID_REKENING'];
				
				if ($result[$i]['PAGU'] == null)
				{
					$pagu='0.00';
				}
				else
				{
					$pagu=$result[$i]['PAGU'];
				}
				
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID_REKENING'],
												$result[$i]['KODE_REKENING'],
												$result[$i]['NAMA_REKENING'],
												$result[$i]['KATEGORI'],
												$pagu
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_daftar_subrekening_sub1($tipe) 
	{
		$response = (object) NULL;
    $req_param='';
	
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
			   
			$row = $this->data_model->get_data_subrekening_sub1($req_param,$tipe);
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
			  
			$result = $this->data_model->get_data_subrekening_sub1($req_param,$tipe)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['ID_REKENING'];
				
				if ($result[$i]['PAGU'] == null)
				{
					$pagu='0.00';
				}
				else
				{
					$pagu=$result[$i]['PAGU'];
				}
				
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID_REKENING'],
												$result[$i]['KODE_REKENING'],
												$result[$i]['NAMA_REKENING'],
												$result[$i]['KATEGORI'],
												$pagu
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_daftar_subrekening_sub2($tipe) 
	{
		$response = (object) NULL;
    $req_param='';
	
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
			   
			$row = $this->data_model->get_data_subrekening_sub2($req_param,$tipe);
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
			  
			$result = $this->data_model->get_data_subrekening_sub2($req_param,$tipe)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['ID_REKENING'];
				
				if ($result[$i]['PAGU'] == null)
				{
					$pagu='0.00';
				}
				else
				{
					$pagu=$result[$i]['PAGU'];
				}
				
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID_REKENING'],
												$result[$i]['KODE_REKENING'],
												$result[$i]['NAMA_REKENING'],
												$result[$i]['KATEGORI'],
												$pagu
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_daftar_subrekening_sub3($tipe) 
	{
		$response = (object) NULL;
    $req_param='';
	
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
			   
			$row = $this->data_model->get_data_subrekening_sub3($req_param,$tipe);
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
			  
			$result = $this->data_model->get_data_subrekening_sub3($req_param,$tipe)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['ID_REKENING'];
				
				if ($result[$i]['PAGU'] == null)
				{
					$pagu='0.00';
				}
				else
				{
					$pagu=$result[$i]['PAGU'];
				}
				
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID_REKENING'],
												$result[$i]['KODE_REKENING'],
												$result[$i]['NAMA_REKENING'],
												$result[$i]['KATEGORI'],
												$pagu
											);
			}
			echo json_encode($response); 
		}
	}
					
	public function get_daftar_rekening() 
	{
		$req_param='';
	
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
			   
			$row = $this->data_model->get_data_rekening($req_param);
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
			  
			$result = $this->data_model->get_data_rekening($req_param)->result_array();
			$result = $this->data_model->get_data_rekening($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['ID_REKENING']=$result[$i]['ID_REKENING'];
				
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID_REKENING'],
												$result[$i]['KODE_REKENING'],
												$result[$i]['NAMA_REKENING'],
												$result[$i]['LEVEL_REKENING']
											);
			}
			echo json_encode($response); 
		}
	}

	public function pilih($id='')
	{
		if($id)
		{
			$data['stat'] = $id;
			$this->load->view('rekening_pilih',$data);
		}
		else
		{
			$this->load->view('rekening_pilih');
		}
	}

	/* Get Select */
	public function getselect_kategori()
	{
		$js = 'id="kategori" name="kategori"';
		echo form_dropdown('kategori', $this->data_model->get_opt_kategori(),$js );
	}
	
	public function get_data_id($id=0)
	{
		$response = (object) NULL;
    $result = $this->data_model->get_data_by_id_2($id)->result_array();
		
		for($i=0; $i<count($result); $i++){
			$response->opt[$i] =$result[$i]['ID_REKENING'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->opt[$i] =array(
									'ID_REKENING'=>$result[$i]['ID_REKENING'],
									'ID_PARENT_REKENING'=>$result[$i]['ID_PARENT_REKENING'],
									'ID_MASTER_REKENING'=>$result[$i]['ID_MASTER_REKENING'],
									'KODE_REKENING'=>$result[$i]['KODE_REKENING'],
									'NAMA_REKENING'=>$result[$i]['NAMA_REKENING'],
									'TIPE'=>$result[$i]['TIPE'],
									'KELOMPOK'=>$result[$i]['KELOMPOK'],
									'JENIS'=>$result[$i]['JENIS'],
									'OBJEK'=>$result[$i]['OBJEK'],
									'RINCIAN'=>$result[$i]['RINCIAN'],
									'SUB1'=>$result[$i]['SUB1'],
									'SUB2'=>$result[$i]['SUB2'],
									'SUB3'=>$result[$i]['SUB3'],
									'KATEGORI'=>$result[$i]['KATEGORI'],
									'PAGU'=>$result[$i]['PAGU']
									);
		}
				
		echo json_encode($response);
	}
	
	public function get_skpd()
	{
		$result = $this->data_model->get_skpd();
		for($i=0; $i<count($result); $i++)
		{
			$response->rows[$i]['ID_SKPD'] = $result[$i]['ID_SKPD'];
			$response->rows[$i]['KODE_SKPD_LKP'] = $result[$i]['KODE_SKPD_LKP'];
			$response->rows[$i]['NAMA_SKPD'] = $result[$i]['NAMA_SKPD'];
		}
		echo json_encode($response);
	}
	
}