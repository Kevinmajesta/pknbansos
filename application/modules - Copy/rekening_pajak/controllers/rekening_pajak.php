<?php

class Rekening_pajak extends Admin_Controller {

  public function __construct()
  {
    parent::__construct();
    $user_data = $this->session->userdata;
    $this->load->vars($user_data);
    $this->load->library(array('session','form_validation'));
    $this->load->model('rekening_pajak_model','data_model');
    $this->load->model('auth/login_model', 'auth');
  }

  public function index()
  {
    $this->daftar();
  }

  public function daftar()
  {
    $data['breadcrumbs'] = 'Daftar Rekening Pajak';
    $data['title'] = PRODUCT_NAME.' - '.$data['breadcrumbs'];
    $data['modul'] = 'rekening_pajak';
    $data['main_content']='rekening_pajak_view';
    $data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    $this->load->view('layout/template',$data);
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
    $page = $total_pages;
    

    $result = $this->data_model->get_data($req_param);

    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;

    for($i=0; $i<count($result); $i++)
    {
      $response->rows[$i]['id'] = $result[$i]['ID_PAJAK'];
      $response->rows[$i]['cell'] = array(
          $result[$i]['ID_PAJAK'],
          $result[$i]['ID_REKENING'],
          $result[$i]['KODE_PAJAK'],
          $result[$i]['NAMA_PAJAK'],
          $result[$i]['NAMA_REKENING'],
          $result[$i]['PERSEN']
      );
    }
    echo json_encode($response);
  }

  
 
  public function proses()
  {
    $response = (object) NULL;
    $id = $this->input->post('id');
    $idrek = $this->input->post('idrek');
    $kd = $this->input->post('kdpajak');
    $nm = $this->input->post('nmpajak');
    $per = $this->input->post('persen');
    $oper = $this->input->post('oper');

    if ($oper == 'del'){
      $this->hapus($id);
      return;
    }

    $this->form_validation->set_rules('kdpajak','Kode Pajak','required|trim|max_length[10]');		
    $this->form_validation->set_rules('nmpajak','Nama Pajak','required|trim|max_length[50]');
    $this->form_validation->set_rules('persen','Persen','required|trim|max_length[50]');

    if ($this->form_validation->run() == TRUE){
	  $param = array(
        'KODE_PAJAK' => $kd,
        'NAMA_PAJAK' => $nm,
        'ID_REKENING'=> $idrek,
        'PERSEN' => $per
      );	
      
		if($oper == 'edit'){
			if($id <= 0 ){
        $result = $this->data_model->check_data($param);	
        if (!$result){
          $newid = $this->data_model->insert_data($param);
          $response->message = 'Pajak '.$kd.' berhasil disimpan.';
          $response->isSuccess = TRUE;
          $response->id = $newid;
        }
        else 
        {
          $response->isSuccess = FALSE;
          $response->message = 'Pajak '.$kd.' sudah ada.';
        }
			}else{
        if (!$this->data_model->check_data($param, $id))
        {
          $newid = $this->data_model->update_data($id,$param);

          $response->isSuccess = TRUE;
          $response->message = 'Pajak '.$kd.' telah diubah.';
          $response->id = $newid;
        }
        else 
        {
          $response->isSuccess = FALSE;
          $response->message = 'Pajak '.$kd.' sudah ada';
        };
			}
		}	
    }
    else // data tidak lolos validasi
    {
      $response->isSuccess = FALSE;
      $response->message = validation_errors();
    }
    echo json_encode($response);
  }	
  

  public function hapus()
  {
		$response = (object) NULL;
    $id = $this->input->post('id');
		$result = $this->data_model->check_dependency($id);
		
		if($result){
			$this->data_model->delete_data($id);
			$response->message = 'Rekening pajak sudah dihapus';
			$response->isSuccess = TRUE;
		}
		else
		{
			$response->message = 'Rekening pajak tidak dapat dihapus, masih dipakai di tabel lain';
			$response->isSuccess = FALSE;
		}
		echo json_encode($response);	
  }

}