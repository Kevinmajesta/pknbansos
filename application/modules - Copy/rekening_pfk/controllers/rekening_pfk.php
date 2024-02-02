<?php

class Rekening_pfk extends Admin_Controller {

  public function __construct()
  {
    parent::__construct();
    $user_data = $this->session->userdata;
    $this->load->vars($user_data);
    $this->load->library(array('session','form_validation'));
    $this->load->model('rekening_pfk_model','data_model');
  }

  public function index()
  {
    $this->daftar();
  }

  public function daftar()
  {
    $data['breadcrumbs'] = 'Daftar Rekening PFK';
    $data['title'] = PRODUCT_NAME.' - '.$data['breadcrumbs'];
    $data['modul'] = 'rekening_pfk';
    $data['main_content']='rekening_pfk_view';
    $this->load->view('layout/template',$data);
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
    $start = $limit * $page - $limit;
    if($start < 0) $start = 0;
    $req_param['limit'] = array(
      'start' => $start,
      'end' => $limit
    );

    $result = $this->data_model->get_data($req_param);

    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;

    for($i=0; $i<count($result); $i++)
    {
      $response->rows[$i]['id'] = $result[$i]['ID_REKENING'];
      $response->rows[$i]['cell'] = array(
        $result[$i]['ID_REKENING'],
        $result[$i]['KODE_REKENING'],
        $result[$i]['NAMA_REKENING']
      );
    }
    echo json_encode($response);
  }

  public function proses()
  {
    $response = (object) NULL;
    $id = $this->input->post('id');
    $kd = $this->input->post('kd');
    $oper = $this->input->post('oper');

    if ($oper == 'del'){
      $this->hapus($id);
      return;
    }

    $param = array(
      'ID_REKENING'=> $id
    );

    $result = $this->data_model->check_data($param);
    if (!$result){
      $newid = $this->data_model->insert_data($param);
      $response->message = 'Rekening PFK '.$kd.' berhasil disimpan.';
      $response->isSuccess = TRUE;
      $response->id = $newid;
    }
    else
    {
      $response->isSuccess = FALSE;
      $response->message = 'Rekening PFK '.$kd.' sudah ada.';
    }

    echo json_encode($response);
  }

  public function hapus()
  {
    $response = (object) NULL;
    $id = $this->input->post('id');
    $kd = $this->input->post('kd');
    $result = $this->data_model->delete_data($id);

    if ($result == TRUE)
    {
      $response->message = 'Rekening PFK '.$kd.' sudah dihapus';
      $response->isSuccess = TRUE;
    }
    else
    {
      $response->message = 'Rekening PFK '.$kd.'  gagal dihapus';
      $response->isSuccess = FALSE;
    }

    echo json_encode($response);
  }

}