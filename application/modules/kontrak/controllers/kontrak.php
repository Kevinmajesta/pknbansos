<?php

class Kontrak extends Admin_Controller {

  public function __construct()
  {
    parent::__construct();
    $user_data = $this->session->userdata;
    $this->load->vars($user_data);
    //$this->load->library(array('session','form_validation'));
    $this->load->model('kontrak_model','data_model');
    $this->load->model('auth/login_model', 'auth');
  }

  public function index()
  {
    $this->daftar();
  }

  public function daftar()
  {
    $data['breadcrumbs'] = 'Daftar Kontrak';
    $data['title'] = PRODUCT_NAME.' - '.$data['breadcrumbs'];
    $data['modul'] = 'kontrak';
    $data['main_content']='kontrak_view';
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
        "limit" => null,
        "search" => $_REQUEST['_search'],
        "search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
        "search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
        "search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
    );

    $row = $this->data_model->get_data($req_param);
    if(!$row)  // tidak ada data
    {
			$response->total = 0; 
			$response->records = 0;
			echo json_encode($response);
			return '';
    }

		$count = count($row); 
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
      $response->rows[$i]['id'] = $result[$i]['ID'];
      $response->rows[$i]['cell'] = array(
          $result[$i]['NO_KONTRAK'],
          $result[$i]['TANGGAL_KONTRAK'],
          $result[$i]['NO_BAP'],
          $result[$i]['TANGGAL_BAP'],
          $result[$i]['NOMINAL_KONTRAK'],
          $result[$i]['NAMA_PERUSAHAAN'],
          $result[$i]['NAMA_PIMPINAN'],
          $result[$i]['NAMA_BANK']
      );
    }
    echo json_encode($response);
  }

  public function form($id=0)
  {
    $data['title'] = PRODUCT_NAME;
    $data['modul'] = 'kontrak';
    $this->load->model('auth/login_model', 'auth');
    $data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));

    if ($id!==0)
    {
      $data['data'] = $this->data_model->get_data_by_id($id);
    }

    $data['main_content']='kontrak_form';
    $this->load->view('layout/template',$data);
  }

  public function proses()
  {
    $response = (object) NULL;
    $this->load->library('form_validation');

    $this->form_validation->set_rules('nok', 'Nomor Kontrak', 'required|trim|max_length[50]|callback__cek_nomor');
    $this->form_validation->set_rules('tglk', 'Tanggal Kontrak', 'required|trim');
    $this->form_validation->set_rules('nilaik', 'Nominal Kontrak', 'required|trim');
    $this->form_validation->set_rules('nobap', 'Tanggal BAP', 'required|trim');
    $this->form_validation->set_rules('tglbap', 'Tanggal BAP', 'required|trim');
    $this->form_validation->set_rules('nominalbap', 'Nominal BAP', 'required|trim');
    $this->form_validation->set_rules('id_skpd', 'SKPD', 'required|trim|integer');
    $this->form_validation->set_rules('id_keg', 'Kegiatan', 'required|trim|integer');
    //$this->form_validation->set_rules('rincian', 'Rincian Rekening', 'required|callback__cek_minus_rek');

    $this->form_validation->set_rules('nmperusahaan', 'Nama Perusahaan', 'required|trim|max_length[500]');
    $this->form_validation->set_rules('nmpimpinan', 'Nama Pemimpin', 'required|trim|max_length[100]');
    $this->form_validation->set_rules('almperusahaan', 'Nama Pemimpin', 'required|trim|max_length[200]');
    $this->form_validation->set_rules('npwp', 'NPWP', 'required|trim|max_length[50]');
    $this->form_validation->set_rules('nmbank', 'Nama Bank', 'required|trim|max_length[100]');
    $this->form_validation->set_rules('norekb', 'Rekening Bank', 'required|trim|max_length[50]');
    /* TODO : cek rincian ada isinya atau tidak */

    $this->form_validation->set_message('required', '%s tidak boleh kosong.');
    $this->form_validation->set_message('max_length', '%s tidak boleh melebihi %s karakter.');
    $this->form_validation->set_message('_cek_nomor', '%s sudah ada.');
    //$this->form_validation->set_message('_cek_minus_rek', 'Rincian Rekening ada yang sisanya minus.');
    
    if ($this->form_validation->run() == TRUE){
      $this->data_model->fill_data();
      $success = $this->data_model->save_data();

      if (!$success)
      {
        $response->isSuccess = TRUE;
        $response->message = 'Data berhasil disimpan';
        $response->id = $this->data_model->id;
      }
      else
      {
        $response->isSuccess = FALSE;
        $response->message = 'Data gagal disimpan';
      }
    }
    else
    {
      $response->isSuccess = FALSE;
      $response->message = validation_errors();
    }

    $response->sql = $this->db->queries;
    echo json_encode($response);
  }
  

  public function _cek_nomor($nomor)
  {
    return $this->data_model->cek_duplikasi_nomor($nomor);
  }

  public function get_anggaran()
  {
    $idr = $this->input->post('idr') ? $this->input->post('idr') : 0;
    $param = array(
      'id_skpd' => $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0,
      'id_kegiatan' => $this->input->post('id_kegiatan') ? $this->input->post('id_kegiatan') : 0,
      'id_rekening' => $this->input->post('id_rekening') ? $this->input->post('id_rekening') : 0,
      'id' => $this->input->post('id') ? $this->input->post('id') : 0
    );
    $result = $this->data_model->get_anggaran($param);
    $response = (object) NULL;
    if ($result){
      $response = array(
          'idr' => $idr,
          'anggaran' => $result['ANGGARAN'],
      );
    }
    echo json_encode($response);
  }
  
    public function get_sisa_anggaran()
  {
    $idr = $this->input->post('idr') ? $this->input->post('idr') : 0;

    
    $param = array(
      'id_skpd' => $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0,
      'id_kegiatan' => $this->input->post('id_kegiatan') ? $this->input->post('id_kegiatan') : 0,
      'id_rekening' => $this->input->post('id_rekening') ? $this->input->post('id_rekening') : 0,
      'tanggal' => $this->input->post('tanggal_kontrak') ? $this->input->post('tanggal_kontrak') : 0,
      'id' => $this->input->post('id') ? $this->input->post('id') : 0,
      'no_kontrak' => $this->input->post('nok') ? $this->input->post('nok') : 0,
    );
    $result = $this->data_model->get_sisa_anggaran($param);
    $response = (object) NULL;
    if ($result){
      $response = array(
          'idr' => $idr,
          'kontrak' => $result['KONTRAK'],
      );
    }
    echo json_encode($response);
  }

  public function rinci($id=0)
  {
    $result = $this->data_model->get_rinci_by_id($id);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_REKENING'];
        $response->rows[$i]['cell'] = array(
            $result[$i]['ID_REKENING'],
            $result[$i]['KODE_REKENING'],
            $result[$i]['NAMA_REKENING'],
            $result[$i]['ANGGARAN'],
            $result[$i]['NOMINAL_KONTRAK'],
            $result[$i]['NOMINAL_BAP']
        );
      }
    }
    echo json_encode($response);
  }

  public function prev($id=0)
  {
    $response = (object) NULL;
    $response->isSuccessful = FALSE;
    if ($id!==0)
    {
      $result = $this->data_model->get_prev_id($id);
      if ($result)
      {
        $response->isSuccessful = TRUE;
        $response->id = $result;
      }
    }
    echo json_encode($response);
  }

  public function next($id=0)
  {
    $response = (object) NULL;
    $response->isSuccessful = FALSE;
    if ($id!==0)
    {
      $result = $this->data_model->get_next_id($id);
      if ($result)
      {
        $response->isSuccessful = TRUE;
        $response->id = $result;
      }
    }
    echo json_encode($response);
  }

  public function hapus()
  {
    $id = $this->input->post('id');
    $result = $this->data_model->check_dependency($id);
    $response = (object) NULL;
    if ($result) {
      // bisa dihapus
      $this->data_model->delete_data($id);
      $response->isSuccess = TRUE;
      $response->message = 'Kontrak telah dihapus';
    }
    else{
      // ada dependensi, tampilkan pesan kesalahan
      $response->isSuccess = FALSE;
      $response->Message  = 'Kontrak sudah dipakai di tabel lain. Tidak bisa dihapus.';
    }
    echo json_encode($response);
  }

}