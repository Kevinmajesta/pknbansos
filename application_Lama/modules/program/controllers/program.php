<?php

class Program extends Base_Controller {
	public function __construct()
  {
    parent::__construct();
		$this->load->model('program_model','data_model');
		$this->load->model('auth/login_model', 'auth');
		$this->load->library('session');
		$this->load->library('form_validation');
	}
	
	public function index() 
	{
		$data['title'] = 'Program';
		$data['main_content']='program_view';
		$data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
		$this->load->view('layout/template',$data);
	}
		
	public function session_id()
	{
		$ID_PROGRAM = $_POST['ID_PROGRAM'];
		$this->session->set_userdata('ID_PROGRAM',$ID_PROGRAM);
	}

	public function proses_form() 
	{
		$response = (object) NULL;
    $id = $this->input->post('id');
		$oper = $this->input->post('oper');
		
		$ID_PROGRAM = $this->session->userdata('ID_PROGRAM');	
		
		if($oper == 'del') 
		{
			$this->hapus($ID_PROGRAM);
			return '';
		} 
		
		$this->form_validation->set_rules('KODE_PROGRAM', 'PROGRAM', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('NAMA_PROGRAM', 'NAMA', 'required|trim|max_length[200]');
		$this->form_validation->set_message('required', '%s Harus diisi');
	
		if ($this->form_validation->run()==TRUE)
		{
			if($oper == 'edit') {
				$result = $this->data_model->check_isi();
				if($id == 'new') {
					if ($result==FALSE) {
						$newid = $this->data_model->insert_data();
						$response->isSuccess = TRUE;
						$response->message = 'Program telah disimpan.';
						$response->id = $newid;
					}
					else 
					{
						// program sudah ada, tampilkan pesan kesalahan
						$response->isSuccess = FALSE;
						$response->message = 'Kode program tersebut sudah ada.';
						//$this->output->set_status_header('500');
					}
				} 
				else {
					$rst = $this->data_model->check_data2();
					if ($rst == TRUE){
						$this->data_model->update_data($ID_PROGRAM);
						$response->isSuccess = TRUE;
						$response->message = 'Program telah diubah.';
					}
					else 
					{
						// Program sudah ada, tampilkan pesan kesalahan
						$response->isSuccess = FALSE;
						$response->message = 'Program tersebut sudah ada.';
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
    $ID_PROGRAM = $this->input->post('ID_PROGRAM');
    $jml = count($ID_PROGRAM);
    $jml_error = 0;

    for ($i=0; $i<= $jml - 1; $i++) {
      if (!$this->data_model->check_dependency($ID_PROGRAM[$i]) )
      {
        $jml_error++;
      }
    }
    
		if ($jml_error == $jml ) {
			// ada dependensi, tampilkan pesan kesalahan
			$response->isSuccess = FALSE;
			$response->message = 'Program tidak bisa dihapus, masih dipakai di Kegiatan.';
		}
    else if( $jml_error >= 1)
    {
    // ada dependensi, tampilkan pesan kesalahan
			$response->isSuccess = FALSE;
			$response->message = ' Ada Program yang tidak bisa dihapus, masih dipakai di Kegiatan.';
    }
		else if( $jml_error == 0)
		{
			// bisa dihapus
			$this->data_model->delete_data($ID_PROGRAM);
			$response->isSuccess = TRUE;
			$response->message = 'Program telah dihapus';
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
					
			for($i=0; $i<count($result); $i++){
				$BIDANG = $result[$i]['KODE_BIDANG'].' - '.$result[$i]['NAMA_BIDANG'];
				$response->rows[$i]['id']=$result[$i]['ID_PROGRAM'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID_PROGRAM'],
												$result[$i]['KODE_URUSAN'],
												$result[$i]['KODE_BIDANG'],
												$BIDANG,
												$result[$i]['KODE_SKPD'],
												$result[$i]['KODE_PROGRAM'],
												$result[$i]['NAMA_PROGRAM']
											);
			}
			echo json_encode($response); 
		}
	}
	
	/* Get Select */
	
	public function getselect_urusan()
	{
		$js = 'id="urusan" name="urusan"';
		echo form_dropdown('urusan', $this->data_model->get_opt_urusan(),$js );
	}
	
	public function getselect_bidang($ID_URUSAN=0)
	{
		$response = (object) NULL;
    $result = $this->data_model->get_bidang($ID_URUSAN)->result_array();
		
		for($i=0; $i<count($result); $i++){
			$response->opt[$i] =$result[$i]['ID_BIDANG'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->opt[$i] =array('ID_BIDANG'=>$result[$i]['ID_BIDANG'],
									'KODE_BIDANG'=>$result[$i]['KODE_BIDANG']
									);
		}
		
		echo json_encode($response);
	}
	
	public function getselect_skpd($ID_BIDANG=0)
	{
		$response = (object) NULL;
    $result = $this->data_model->get_skpd($ID_BIDANG)->result_array();
		
		for($i=0; $i<count($result); $i++){
			$response->opt[$i] =$result[$i]['ID_SKPD'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->opt[$i] =array('ID_SKPD'=>$result[$i]['ID_SKPD'],
									'KODE_SKPD'=>$result[$i]['KODE_SKPD']
									);
		}
		
		echo json_encode($response);
	}
	
	public function get_data_id($id=0)
	{
		$response = (object) NULL;
    $result = $this->data_model->get_data_by_id_2($id)->result_array();
		
		for($i=0; $i<count($result); $i++){
			$response->opt[$i] =$result[$i]['ID_PROGRAM'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->opt[$i] =array(
									'ID_URUSAN'=>$result[$i]['ID_URUSAN'],
									'KODE_URUSAN'=>$result[$i]['KODE_URUSAN'],
									'ID_BIDANG'=>$result[$i]['ID_BIDANG'],
									'KODE_BIDANG'=>$result[$i]['KODE_BIDANG'],
									'ID_SKPD'=>$result[$i]['ID_SKPD'],
									'KODE_SKPD'=>$result[$i]['KODE_SKPD'],
									'ID_PROGRAM'=>$result[$i]['ID_PROGRAM'],
									'KODE_PROGRAM'=>$result[$i]['KODE_PROGRAM'],
									'NAMA_PROGRAM'=>$result[$i]['NAMA_PROGRAM'],
									
									);
		}
		
		echo json_encode($response);
	}
	
}