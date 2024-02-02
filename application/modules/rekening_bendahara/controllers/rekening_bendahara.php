<?php

class Rekening_bendahara extends Admin_Controller {
  public function __construct()
  {
    parent::__construct();
    $user_data = $this->session->userdata;
    $this->load->vars($user_data);
    $this->load->model('auth/login_model', 'auth');
    $this->load->model('rekeningbendahara_model','data_model');
  }

  public function index()
  {
    $data['title'] = PRODUCT_NAME.' - '.' Rekening Bendahara';
    $data['modul'] = 'rekening_bendahara';
    $data['main_content'] = 'rekeningbendahara_view';
    $data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    $this->load->view('layout/template',$data);
  }

  public function proses_form()
  {
    
    $id = $this->input->post('id');
    $idrekening = $this->input->post('idrekening');
    $idskpd=$this->input->post('idskpd');

    $oper = $this->input->post('oper');

    if($oper == 'del')
    {
      $this->hapus($id);
      return '';
    }

    $this->load->library('form_validation');
    $this->form_validation->set_rules('namasumber', 'NAMA SUMBER DANA', 'required|trim|max_length[100]');
    $this->form_validation->set_rules('idrekening', 'REKENING', 'required|integer');
    $this->form_validation->set_rules('namabank', 'NAMA BANK', 'required|trim|max_length[100]');
    $this->form_validation->set_rules('norekening', 'REKENING BANK', 'required|trim|max_length[50]');

    $this->form_validation->set_message('required', '%s tidak boleh kosong.');
    $this->form_validation->set_message('max_length', '%s tidak boleh melebihi %s karakter.');
    if ($this->form_validation->run()==TRUE)
    {
      if($oper == 'edit') {
        if($id == 'new') {

          $result = $this->data_model->check_isi();

          if ($result == FALSE){

            $newid = $this->data_model->insert_data();
            $response->isSuccess = TRUE;
            $response->message = 'Rekening Bendahara telah disimpan.';
            $response->id = $newid;
          }
          else {
            $response->isSuccess = FALSE;
            $response->message = 'Rekening Bendahara tersebut sudah ada.';
          }
        }
        else
        {
          $result = $this->data_model->check_isi_1();
          if ($result == TRUE){
            $result2 = $this->data_model->check_isi_2();
            if ($result2 == TRUE){
              $this->data_model->update_data($id);
              $response->isSuccess = TRUE;
              $response->message = 'Rekening Bendahara telah dirubah.';
            }
            else{
              $response->isSuccess = FALSE;
              $response->message = 'Rekening Bendahara tidak bisa disimpan, Nama Sumber Dana SKPD telah dipakai.';
            }
          }
          else
          {
            $response->isSuccess = FALSE;
            $response->message = 'Rekening Bendahara tidak bisa disimpan, Rekening telah dipakai.';
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
    $id = $this->input->post('id');
      
      $this->data_model->delete_data($id);
      $response->isSuccess = TRUE;
      $response->message = 'Rekening Bendahara telah dihapus';
    
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
    $response->sql = $this->db->queries;
    

    for($i=0; $i<count($result); $i++)
    {
            $response->rows[$i]['id'] = $result[$i]['ID_SUMBER_DANA_SKPD'];
            $response->rows[$i]['cell'] = array(
                      $result[$i]['ID_REKENING'],
                      $result[$i]['ID_SKPD'],
                      $result[$i]['KODE_SKPD_LKP'],
                      $result[$i]['NAMA_SKPD'],
                      $result[$i]['NAMA_SUMBER_DANA'],
                      $result[$i]['KODE_REKENING'],
                      $result[$i]['NAMA_REKENING'],
                      $result[$i]['NAMA_BANK'],
                      $result[$i]['NO_REKENING_BANK']
      );
      }
    echo json_encode($response);
  }

}