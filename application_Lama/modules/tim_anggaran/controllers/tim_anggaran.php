<?php

class Tim_anggaran extends Admin_Controller {

  public function __construct()
  {
    parent::__construct();
    $user_data = $this->session->userdata;
    $this->load->vars($user_data);
    $this->load->library(array('session','form_validation'));
    $this->load->model('tim_anggaran_model','data_model');
    $this->load->model('auth/login_model', 'auth');
  }

  public function index()
  {
    $this->daftar();
  }

  public function daftar()
  {
    $data['user_data']['SYS_ADMIN_LOGIN'] = $this->session->userdata('SYS_ADMIN_LOGIN');
    $data['breadcrumbs'] = 'Daftar Tim Anggaran';
    $data['title'] = PRODUCT_NAME.' - '.$data['breadcrumbs'];
    $data['modul'] = 'tim_anggaran';
    $data['main_content']='tim_anggaran_view';
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
			$response->rows[$i]['id']=$result[$i]['ID_PEJABAT_DAERAH'];
			$response->rows[$i]['cell']=array($result[$i]['ID_PEJABAT_DAERAH'],
											$result[$i]['JABATAN'],
											$result[$i]['NAMA_PEJABAT'],
											$result[$i]['NIP']
										);
    }
    echo json_encode($response);
  }
  
  public function proses()
  {
    $response = (object) NULL;
    $id = $this->input->post('id');
    $idp = $this->input->post ('id');
    $jabat = $this->input->post('JABATAN');
    $nama = $this->input->post('NAMA_PEJABAT');
    $nip = $this->input->post('NIP');
    $tahun = $this->session->userdata('tahun');
    $status = $this->session->userdata('status');
    $oper = $this->input->post('oper');
    
    
    if ($oper == 'del'){
      $this->hapus($id);
      return;
    }

    $param = array(
      'TAHUN' => $tahun,
      'STATUS' => $status,
      'ID_PEJABAT_DAERAH'=> $idp
    );
    
    $result = $this->data_model->check_data($param);

    if ($result){
      $newid = $this->data_model->insert_data($param);

      $response->message = 'Pejabat Tim Anggaran'.$nama.' berhasil disimpan.';
      $response->isSuccess = TRUE;
      $response->id = $newid;
    }
    else
    {
      $response->isSuccess = FALSE;
      $response->message = 'Pejabat Tim Anggaran '.$nama.' sudah ada.';
    }

    echo json_encode($response);
  }

  public function hapus()
  {
    $response = (object) NULL;
    $id = $this->input->post('id');
    $jabat = $this->input->post('JABATAN');
    $nama = $this->input->post('NAMA_PEJABAT');
    $nip = $this->input->post('NIP');
    $result = $this->data_model->delete_data($id);
    
    if ($result == TRUE)
    {
      $response->message = 'Pejabat Tim Anggaran'.$nama.' sudah dihapus';
      $response->isSuccess = TRUE;
    }
    else
    {
      $response->message = 'Pejabat Tim Anggaran'.$nama.'  gagal dihapus';
      $response->isSuccess = FALSE;
    }

    echo json_encode($response);
  }

}