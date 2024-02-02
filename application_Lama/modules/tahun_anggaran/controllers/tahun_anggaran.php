<?php

class Tahun_anggaran extends Admin_Controller {
  public function __construct()
  {
    parent::__construct();
    $this->load->model('tahun_anggaran_model','data_model');
    $this->load->library('form_validation');
    $this->load->model('auth/login_model', 'auth');
  }

  public function index()
  {
    $data['breadcrumbs'] = 'Tahun Anggaran';
		$data['title'] = PRODUCT_NAME.' - '.$data['breadcrumbs'];
    $data['daftar_url'] = '/get_daftar';
    $data['ubah_url'] = '/proses_form';
    $data['modul'] = 'tahun_anggaran';
		$data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    $data['main_content'] = 'tahun_anggaran_view';
    $this->load->view('layout/template',$data);
  }

  public function proses_form()
  {
		$id = $this->input->post('id');
		$TAHUN = $this->input->post('tahun');
		$STATUS = $this->input->post('status_awal');
		$STATUS_KINI = $this->input->post('status_kini');
		$oper = $this->input->post('oper');
		
		$this->form_validation->set_rules('tahun', 'Tahun Anggaran', 'required|trim|max_length[100]|min_val[0]');

    $this->form_validation->set_message('required', '%s tidak boleh kosong.');

		if($this->form_validation->run() == TRUE)
		{
			if($oper == 'edit'){
					if ($id == 'new'){ 
						$result = $this->data_model->check_tahun();
						if($result == FALSE){
							$newid = $this->data_model->insert_data();
              $response->isSuccess = TRUE;
							$response->message = 'Tahun Anggaran telah disimpan, silahkan anda tambahkan status';
							$response->id = $newid;
						}
						else{
              $response->isSuccess = FALSE;
							$response->message = 'Tahun Anggaran tersebut sudah ada.';
						}
					}else{ 
						$result2 = $this->data_model->check_tahun2();
						if($result2 == FALSE){
              $this->data_model->update_data($TAHUN);
              $response->isSuccess = TRUE;
              $response->message = 'Tahun Anggaran telah diubah';
              $this->session->set_userdata( 'STATUS_AWAL',  $STATUS );
						}
						else{
              $response->isSuccess = FALSE;
							$response->message = 'Tahun Anggaran tersebut sudah ada.';
						}
					}
			}
		}
		else
		{
			$response->error = validation_errors();
			$this->output->set_status_header('500');
		}
    $response->sql = $this->db->queries;
		echo json_encode($response);	
  }

	public function hapus() 
	{		
		$TAHUN = $this->input->post('tahun');
		// cek apakah ada dependensi dengan tabel lain
		$result = $this->data_model->check_dependency_tahun($TAHUN);
		
		if ($TAHUN != $this->session->userdata('tahun'))
		{
			if ($result == FALSE) {
				// bisa dihapus
				$this->data_model->delete_data($TAHUN);
				$response->isSuccess = TRUE;
				$response->message = 'Tahun Anggaran telah dihapus';
			} 
			else 
			{
				// ada dependensi, tampilkan pesan kesalahan
				$response->isSuccess = FALSE;
				$response->message = 'Tahun Anggaran tidak bisa dihapus, masih digunakan di status anggaran';
				//$this->output->set_status_header('500');
			}
		}
		else 
		{
			// ada dependensi, tampilkan pesan kesalahan
      $response->isSuccess = FALSE;
			$response->message = 'Tahun Anggaran tidak bisa dihapus, Tahun sedang digunakan.';
			//$this->output->set_status_header('500');
		}
    $response->sql = $this->db->queries;
    
		echo json_encode($response);
	}


  public function get_daftar()
  {
    $page = $_REQUEST['page']; // get the requested page
    $limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
    $sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
    $sord = $_REQUEST['sord']; // get the direction if(!$sidx) $sidx =1;

    $req_param = array (
        "sort_by" => $sidx,
        "sort_direction" => $sord,
        "limit" => null,
        // "search" => $_REQUEST['_search'],
        // "search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
        // "search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
        // "search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
    );

    $count = $this->data_model->get_data($req_param, TRUE);
    $response = (object) NULL;
    if($count == 0) // tidak ada data
    {
      echo json_encode($response);
      return '';
    }

    if ($limit == -1)
    {
      $page = 1;
      $total_pages = 1;
      $req_param['limit'] = NULL;
    }
    else
    {
      $total_pages = ceil($count/$limit);

      if ($page > $total_pages)
      $page = $total_pages;
      $start = $limit * $page - $limit;
      if($start < 0) $start = 0;
      $req_param['limit'] = array(
          'start' => $start,
          'end' => $limit
      );
    }

    $result = $this->data_model->get_data($req_param);

    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;

    for($i=0; $i<count($result); $i++)
    {
      $response->rows[$i]['id'] = $result[$i]['TAHUN'];
      $response->rows[$i]['cell'] = array(
          $result[$i]['TAHUN'],
          $result[$i]['STATUS_AWAL'],
          $result[$i]['STATUS_KINI'],
      );
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

	public function getselect($TAHUN)
	{
		echo form_dropdown('', $this->data_model->get_opt_status($TAHUN) );
	}
  
  public function proses_form_status()
  {
		$id = $this->input->post('id');
		$c = explode("-", $this->input->post('id'));
		if ($id != "new")
		{
			$TAHUN 	= $c[0];
			$STATUS = $c[1];
		}

		$oper = $this->input->post('oper');
				
		$this->form_validation->set_rules('status', 'Status', 'required|trim|max_length[100]');
		
    $this->form_validation->set_message('required', '%s tidak boleh kosong.');
		
    if($this->form_validation->run() == TRUE)
		{
			if($oper == 'edit'){
				if ($id == 'new'){
					$newid = $this->data_model->insert_data_status();
					$response->isSuccess = TRUE;
					$response->message = 'Status Anggaran telah disimpan';
					$response->id = $newid;
				}
				else{
 					if($this->session->userdata('tmp_tahun_for_status_1')==$this->session->userdata('tahun') && $this->session->userdata('status')==$this->session->userdata('tmp_status_for_update_1')){
            $response->isSuccess = FALSE;
						$response->message = 'Status Anggaran tidak bisa diubah karena sedang digunakan';
					}
					else{
            // $result = $this->data_model->check_dependency_status($TAHUN,$STATUS); -- redmine #1296
            // if ($result == FALSE) { -- redmine #1296
              // bisa dirubah
              $this->data_model->update_data_status();
              $response->isSuccess = TRUE;
              $response->message = 'Status Anggaran telah diubah';
            // } -- redmine #1296
            // else -- redmine #1296
            // { -- redmine #1296
              // // ada dependensi, tampilkan pesan -- redmine #1296
              // $response->isSuccess = FALSE; -- redmine #1296
              // $response->message = 'Status Anggaran tidak dapat dirubah karena telah berisi pagu anggaran'; -- redmine #1296
            // } -- redmine #1296
					}
				}
			}
		}
		else
		{
			$response->error = validation_errors();
			$this->output->set_status_header('500');
		}
    
    $response->sql = $this->db->queries;
	
		echo json_encode($response);
  }
  
	public function hapus_status()
	{
		$c = explode("-", $this->input->post('id'));
    $TAHUN 	= $c[0];
    $STATUS = $c[1];
    
		// cek apakah ada dependensi dengan tabel lain
		$result = $this->data_model->check_dependency_status($TAHUN, $STATUS);
		//$result = $this->data_model->delete_data_status($TAHUN, $STATUS);
		if ($STATUS != $this->session->userdata('status') || $TAHUN != $this->session->userdata('tahun'))
		{
			if ($result == FALSE) 
      {
				// bisa dihapus
				$this->data_model->delete_data_status($TAHUN, $STATUS);
				$response->isSuccess = TRUE;	
				$response->message = 'Status Anggaran telah dihapus';	
			} 
			else 
			{
				// ada dependensi, tampilkan pesan kesalahan
				$response->isSuccess = FALSE;	
				$response->message = 'Status Anggaran tidak dapat dihapus karena telah berisi pagu anggaran';
				//$this->output->set_status_header('500');
			}
		}
    else
    {
			// ada dependensi, tampilkan pesan kesalahan
      $response->isSuccess = FALSE;	
			$response->message = 'Status Anggaran tidak dapat dihapus, Status ini sedang digunakan.';
			//$this->output->set_status_header('500');
		}
		echo json_encode($response);
	}

 	public function get_daftar_status($TAHUN='') 
	{
		$this->session->set_userdata('tmp_tahun_for_status_1',$TAHUN);	

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
					// "search" => $_REQUEST['_search'],
					// "search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
					// "search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
					// "search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
			);     
			   
			$row = $this->data_model->get_data_status($req_param,$TAHUN);
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
			
			$result = $this->data_model->get_data_status($req_param,$TAHUN);
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['TAHUN']."-".$result[$i]['STATUS'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['TAHUN'],
												$result[$i]['STATUS'],
												$result[$i]['TANGGAL_RKA'],
												$result[$i]['TANGGAL_APBD'],
												$result[$i]['NOMOR_APBD'],
												$result[$i]['TANGGAL_PERKADA'],
												$result[$i]['NOMOR_PERKADA'],
												$result[$i]['TANGGAL_DPA'],
												$result[$i]['NOMOR_DPA']
											);
			
			}
      $response->sql = $this->db->queries;
			echo json_encode($response); 
		}
	}

	public function session_tahun()
	{
		$this->session->unset_userdata('tmp_tahun_grid');
		$TAHUN = $_POST['TAHUN'];
		$this->session->set_userdata('tmp_tahun_grid',$TAHUN);
	}

	public function session_status()
	{
		$this->session->unset_userdata('tmp_status_for_update_1');
		$STATUS = $_POST['STATUS'];
		$this->session->set_userdata('tmp_status_for_update_1',$STATUS);
	}
}