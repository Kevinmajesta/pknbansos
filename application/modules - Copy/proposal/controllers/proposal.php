<?php

class Proposal extends Admin_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('proposal_model', 'data_model');
    $this->load->model('auth/login_model', 'auth');
  }

  public function index()
  {
    $data['breadcrumbs'] = 'Daftar Proposal';
		$data['title'] = PRODUCT_NAME.' - '.$data['breadcrumbs'];
    $data['link_daftar'] = '/get_daftar';
    $data['modul'] = 'proposal';
    $data['main_content'] = 'proposal_view';
    $data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    $data['fields'] = $this->data_model->get_data_fields();
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
        "m" => isset($_REQUEST['m']) ? $_REQUEST['m'] : '',
        "q" => isset($_REQUEST['q']) ? $_REQUEST['q'] : '',
        "search_field" => isset($_REQUEST['searchField']) ? $_REQUEST['searchField'] : null,
        "search_operator" => isset($_REQUEST['searchOper']) ? $_REQUEST['searchOper'] : null,
        "search_str" => isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : null,
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
      $response->rows[$i]['id'] = $result[$i]['ID_PROPOSAL'];
      $response->rows[$i]['cell'] = array(
          $result[$i]['NOMOR'],
          $result[$i]['NAMA_PEMOHON'],
          $result[$i]['ALAMAT_PEMOHON'],
          $result[$i]['TANGGAL'],
          $result[$i]['NAMA_PROGRAM'],
          $result[$i]['NAMA_KEGIATAN'],
          $result[$i]['NAMA_REKENING'],
          $result[$i]['NOMINAL_DIAJUKAN'],
          $result[$i]['NOMINAL_DISETUJUI'],
          $result[$i]['STATUS'],
      );
    }
    echo json_encode($response);
  }

  public function get_kategori()
  {
    $result = $this->data_model->get_data_kategori()->result_array();
    $kategori = array();
    foreach($result as $row)
    {
      $kategori[] = $row['KATEGORI'];
    }

    echo json_encode($kategori);
  }

  public function form($id=0)
  {
    $data['title'] = 'Proposal';
    $data['modul'] = 'proposal';
    $data['link_proses'] = 'proses';
    $data['link_back'] = '/daftar';
    $data['header'] = 'Proposal';
		$data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    if ($id!==0)
    {
      $data['data'] = $this->data_model->get_data_by_id($id);
    }
    
    $data['jenis_bantuan'] = $this->data_model->get_data_jenis_bantuan()->result_array();

    $data['main_content']='proposal_form';
    $this->load->view('layout/template',$data);
  }
  
  public function proses()
  {
    $response = (object) NULL;
    $this->load->library('form_validation');

    $this->form_validation->set_rules('no', 'Nomor Proposal', 'required|trim|max_length[100]');
    $this->form_validation->set_rules('tgl', 'Tanggal Proposal Masuk', 'required|trim');
    $this->form_validation->set_rules('bantuan', 'Jenis Bantuan', 'required|trim|max_length[20]');
    $this->form_validation->set_rules('kategori', 'Kategori Pemohon', 'required|trim|max_length[30]');
    $this->form_validation->set_rules('nom_aju', 'Nominal Pengajuan', 'required|trim');
	//$this->form_validation->set_rules('nik', 'NIK', 'callback__duplikat_nik');

    /* TODO : cek rincian ada isinya atau tidak */

    $this->form_validation->set_message('required', '%s tidak boleh kosong.');
    $this->form_validation->set_message('max_length', '%s tidak boleh melebihi %s karakter.');
    $this->form_validation->set_message('integer', '%s harus angka.');

    if ($this->form_validation->run() == TRUE){
      $this->data_model->fill_data();
      $success = $this->data_model->save_data();

      if (!$success)
      {
        $response->isSuccess = TRUE;
        $response->message = 'Data berhasil disimpan';
        $response->id = $this->data_model->id;
        $response->nama_pmh = $this->data_model->nama_pmh;
        $response->alamat_pmh = $this->data_model->alamat_pmh;
        $response->tgl_lhr = format_date($this->data_model->tgl_lhr);
        $response->nama_pimp = $this->data_model->nama_pimp;
        $response->bidang = $this->data_model->bidang;
        $response->sql = $this->db->queries;
      }
      else
      {
        $response->isSuccess = FALSE;
        $response->message = 'Data gagal disimpan';
        $response->sql = $this->db->queries;
      }
    }
    else
    {
      $response->isSuccess = FALSE;
      $response->message = validation_errors();
    }

    echo json_encode($response);
  }
  
  public function duplikat_number($no)
  {
    $result = $this->data_model->check_duplikat_number($no);
    if ($result === TRUE)
    {
      return TRUE;
    }
    else
    {
      $this->form_validation->set_message('duplikat_number', '%s ada yang sama.');
      return FALSE;
    }
  }
  
   public function duplikat_nik()
  {
	$no= $this->input->post('no');
    $nik = $this->input->post('nik');

    $result = $this->data_model->check_nik($no,$nik);
	if ($result && $result['NIK_PAKAI'] > 0)
	{
	$response->isSuccess = FALSE;
	$response->tanggal = format_date($result['TANGGAL'],$style='d F Y');
	}
	else{
	$response->isSuccess = TRUE;
	}
	echo json_encode($response);
  }
  
     public function duplikat_no()
  {
	$no= $this->input->post('no');

    $result = $this->data_model->check_duplikat_number($no);
    if ($result === TRUE)
    {

	  $response->isSuccess = TRUE;
    }
    else
    {
	  $response->isSuccess = FALSE;
	  $response->message = 'Data Nomor '.$no.' sudah ada.';

    }
	echo json_encode($response);
  }
  
       public function cek_tanggal()
  {
	$nik = $this->input->post('nik');

    $result = $this->data_model->get_tanggal($nik);
	
    $response = (object) NULL;
    if (count($result) == 0) {
      echo json_encode($response);
      return '';
    }
    
    $response->tanggal = format_date($result['TANGGAL']);
    echo json_encode($response);
  }
  
  public function kirim()
  {
    $result = $this->data_model->kirim_proposal();
    if ($result === TRUE)
    {
      $response->isSuccess = TRUE;
      $response->message = 'Data Proposal telah dikirim.';
    }
    else
    {
      $response->isSuccess = FALSE;
      $response->message = 'Data Proposal gagal dikirim.';
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
    $response = (object) NULL;
    
    $check_posted = $this->data_model->check_posted($id);
    if ($check_posted === TRUE)
    {
      $response->isSuccess = FALSE;
      $response->message = 'Data tidak dapat dihapus karena sudah dikirim.';
    }
    else
    {
      $check_depend = $this->data_model->check_dependency($id);
      if ($check_depend) {
        // bisa dihapus
        $this->data_model->delete_data($id);
        $response->isSuccess = TRUE;
        $response->message = 'Data Proposal telah dihapus.';
      }
      else{
        // ada dependensi, tampilkan pesan kesalahan
        $response->isSuccess = FALSE;
        $response->message = 'Data tidak dapat dihapus karena sudah dipakai di tabel lain.';
      }
    }
    echo json_encode($response);
  }

  public function get_jabatan_skpd()
  {
    $result = $this->data_model->get_jabatan_skpd();
    for($i=0; $i<count($result); $i++)
    {
      $response->rows[$i]['ID_PEJABAT_SKPD'] = $result[$i]['ID_PEJABAT_SKPD'];
      $response->rows[$i]['NAMA_PEJABAT'] = $result[$i]['NAMA_PEJABAT'];
    }
    echo json_encode($response);
  }

  public function get_data_pejabat_skpd($jabat)
  {
    $result = $this->data_model->get_data_pejabat_skpd($jabat);
    
    $response = (object) NULL;
    if (count($result) == 0) {
      echo json_encode($response);
      return '';
    }
    
    $response->jabatan = $result['JABATAN'];
    $response->nip   = $result['NIP'];
    echo json_encode($response);
  }
}