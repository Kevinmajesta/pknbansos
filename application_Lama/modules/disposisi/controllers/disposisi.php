<?php

class Disposisi extends Admin_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('disposisi_model', 'data_model');
    $this->load->model('auth/login_model', 'auth');
  }

  public function index()
  {
    $data['breadcrumbs'] = 'Daftar Rekomendasi';
		$data['title'] = PRODUCT_NAME.' - '.$data['breadcrumbs'];
    $data['link_daftar'] = '/get_daftar';
    $data['modul'] = 'disposisi';
    $data['main_content'] = 'disposisi_view';
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

    for($i=0; $i<count($result); $i++)
    {
      $response->rows[$i]['id'] = $result[$i]['ID_PROPOSAL'];
      $response->rows[$i]['cell'] = array(
          $result[$i]['NOMOR_DISPOSISI'],
          $result[$i]['TANGGAL'],
          $result[$i]['NOMOR_UJI'],
          $result[$i]['NOMOR_PROPOSAL'],
          $result[$i]['NAMA_PEMOHON'],
          $result[$i]['ALAMAT_PEMOHON'],
          $result[$i]['NOMINAL_DIAJUKAN'],
          $result[$i]['KEPUTUSAN'],
          $result[$i]['CATATAN'],
      );
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  public function get_data_uji()
  {
    $result = $this->data_model->get_data_pengujian()->row_array();

    echo json_encode($result);
  }
  
  function get_fileupload_adm($id = 0)
  {
    $response = (object) NULL;

    if ($id === 0)
    {
      echo json_encode($response);
      return;
    }

    $result = $this->data_model->get_scan_file_adm($id);

    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $file = $this->data_model->path.$result[$i]['NAMA_FILE'];
        if (file_exists($file))
        {
          $response->rows[$i]['id'] = $result[$i]['ID_DOKUMEN'];
          $response->rows[$i]['cell']=array(
              $result[$i]['ID_DOKUMEN'],
              $result[$i]['NAMA_DOKUMEN'],
              $result[$i]['NAMA_FILE'],
              $result[$i]['MIME'],
              $result[$i]['UKURAN'],
              $result[$i]['TANGGAL_UPLOAD'],
          );
        }
      }
    }
    echo json_encode($response);
  }
  
  function get_fileupload_mat($id = 0)
  {
    $response = (object) NULL;

    if ($id === 0)
    {
      echo json_encode($response);
      return;
    }

    $result = $this->data_model->get_scan_file_mat($id);

    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $file = $this->data_model->path.$result[$i]['NAMA_FILE'];
        if (file_exists($file))
        {
          $response->rows[$i]['id'] = $result[$i]['ID_DOKUMEN'];
          $response->rows[$i]['cell']=array(
              $result[$i]['ID_DOKUMEN'],
              $result[$i]['NAMA_DOKUMEN'],
              $result[$i]['NAMA_FILE'],
              $result[$i]['MIME'],
              $result[$i]['UKURAN'],
              $result[$i]['TANGGAL_UPLOAD'],
          );
        }
      }
    }
    echo json_encode($response);
  }
  
  function get_tim_uji($id = 0)
  {
    $response = (object) NULL;

    if ($id === 0)
    {
      echo json_encode($response);
      return;
    }

    $result = $this->data_model->get_data_tim_penguji($id);

    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_PEJABAT_PENGUJI'];
        $response->rows[$i]['cell'] = array(
              $result[$i]['ID_PEJABAT_PENGUJI'],
              $result[$i]['NIP'],
              $result[$i]['NAMA_PEJABAT']
        );
      }
    }
    echo json_encode($response);
  }
  
  public function form($id=0)
  {
    $data['title'] = 'Rekomendasi';
    $data['modul'] = 'disposisi';
    $data['link_proses'] = 'proses';
    $data['link_back'] = '/daftar';
    $data['header'] = 'Rekomendasi';
		$data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    if ($id!==0)
    {
      $data['data'] = $this->data_model->get_data_by_id($id);
    }
    
    $data['jenis_bantuan'] = $this->data_model->get_data_jenis_bantuan()->result_array();

    $data['main_content']='disposisi_form';
    $this->load->view('layout/template',$data);
  }

  public function rinci($id=0)
  {
    $result = $this->data_model->get_rinci_by_id($id);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_PROPOSAL'];
        $response->rows[$i]['cell'] = array(
              $result[$i]['ID_PROPOSAL'],
              $result[$i]['NIK'],
              $result[$i]['NAMA_PEMOHON'],
              $result[$i]['ALAMAT_PEMOHON'],
              $result[$i]['NAMA_PIMPINAN'],
              $result[$i]['TANGGAL_LAHIR'],
              $result[$i]['RINGKASAN'],
              $result[$i]['NOMINAL_DIAJUKAN']
        );
      }
    }

    echo json_encode($response);
  }
    
  public function proses()
  {
    $response = (object) NULL;
    $this->load->library('form_validation');

    $this->form_validation->set_rules('no_proposal', 'Nomor Proposal', 'required|trim|max_length[100]');
    $this->form_validation->set_rules('no_disposisi', 'Nomor Disposisi', 'required|trim|max_length[100]|callback_duplikat_number');
    $this->form_validation->set_rules('tgl', 'Tanggal Disposisi', 'required|trim');
    $this->form_validation->set_rules('hasil_disposisi', 'Keputusan', 'required|trim');
    $this->form_validation->set_rules('cttn_disposisi', 'Catatan Disposisi', 'required|trim|max_length[500]');

    /* TODO : cek rincian ada isinya atau tidak */

    $this->form_validation->set_message('required', '%s tidak boleh kosong.');
    $this->form_validation->set_message('max_length', '%s tidak boleh melebihi %s karakter.');
    $this->form_validation->set_message('integer', '%s harus angka.');

    $tgl_pengujian = prepare_date($this->input->post('tgl_pengujian'));
    $tgl_disposisi = prepare_date($this->input->post('tgl'));
    
    if ($tgl_pengujian > $tgl_disposisi)
    {
      $response->isSuccess = FALSE;
      $response->message = 'Data gagal disimpan, tanggal disposisi tidak boleh kurang dari tanggal pengujian.';
    }
    else
    {
      if ($this->form_validation->run() == TRUE){
        $this->data_model->fill_data();
        $success = $this->data_model->save_data();

        if (!$success)
        {
          $response->isSuccess = TRUE;
          $response->message = 'Data berhasil disimpan';
          $response->id = $this->data_model->id;
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
	
	$result = $this->data_model->check_dependency($id);
	if ($result) {
		// bisa dihapus
		$this->data_model->delete_data($id);
		$response->message = 'Data Proposal telah dihapus.';
		$response->isSuccess = TRUE;
	} 
	else 
	{
		// ada dependensi, tampilkan pesan kesalahan
		$response->message = 'Data Proposal tidak dapat dihapus.';
		$response->isSuccess = FALSE;			
	}
    
    echo json_encode($response);
  }

}