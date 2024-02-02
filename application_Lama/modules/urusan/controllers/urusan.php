<?php

class Urusan extends Base_Controller {
  public function __construct()
  {
    parent::__construct();
    $this->load->model('urusan_model','data_model');
    $this->load->model('auth/login_model', 'auth');
  }

  public function index()
  {
    $data['title']=PRODUCT_NAME.' - '.' Urusan';
    $data['main_content']='urusan_view';
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

    $this->load->library('form_validation');
    $this->form_validation->set_rules('kode','URUSAN','required|trim|max_lenght[20]');
    //$this->form_validation->set_rules('kode','URUSAN','callback_duplikasi_kode');
    $this->form_validation->set_rules('nama','NAMA','required|trim|max_lenght[50]');

    if($this->form_validation->run() == TRUE)
    {
      if($oper == 'edit'){
        if($id == 'new'){
          $result = $this->data_model->check_data();
          if ($result == FALSE){
            $newid = $this->data_model->insert_data();
            $response->isSuccess = TRUE;
            $response->message = 'Urusan sudah disimpan';
            $response->id = $newid;
          }
          else
          {
            // Urusan sudah ada, tampilkan pesan kesalahan
            $response->isSuccess = FALSE;
            $response->message = 'Urusan tersebut sudah ada.';
          }
        }
        else
        {
          $rst = $this->data_model->check_data2();
          if ($rst == TRUE){
            $this->data_model->update_data($id);
            $response->isSuccess = TRUE;
            $response->message = 'Urusan sudah di ubah';
          }
          else
          {
            // Urusan sudah ada, tampilkan pesan kesalahan
            $response->isSuccess = FALSE;
            $response->message = 'Urusan tersebut sudah ada.';
          }
        }

      }
    }
    else
    {
      $response->error = validation_erros();
      $this->output->set_status_header('500');
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
      $response->isSuccess = TRUE;
      $response->message = 'Urusan sudah dihapus';
    }
    else
    {
      $response->isSuccess = FALSE;
      $response->message = 'Urusan tidak dapat dihapus, masih dipakai di tabel lain';
      //$this->output->set_status_header('500');
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

      if($sidx=="kode"){
        $sidx = "KODE_URUSAN";
      }
      if($sidx=="nama"){
        $sidx = "NAMA_URUSAN";
      }

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
      if(!$row) // tidak ada data
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
        $response->rows[$i]['id']=$result[$i]['ID_URUSAN'];
        // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
        $response->rows[$i]['cell']=array(
                        $result[$i]['KODE_URUSAN'],
                        $result[$i]['NAMA_URUSAN']
                      );
      }
      echo json_encode($response);
    }
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