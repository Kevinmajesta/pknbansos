<?php

class Pengujian extends Admin_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('pengujian_model', 'data_model');
    $this->load->model('auth/login_model', 'auth');
  }

  public function index()
  {
    $data['breadcrumbs'] = 'Daftar Pengujian';
		$data['title'] = PRODUCT_NAME.' - '.$data['breadcrumbs'];
    $data['link_daftar'] = '/get_daftar';
    $data['modul'] = 'pengujian';
    $data['main_content'] = 'pengujian_view';
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
          $result[$i]['NOMOR_PROPOSAL'],
          $result[$i]['NAMA_PEMOHON'],
          $result[$i]['ALAMAT_PEMOHON'],
          $result[$i]['NOMINAL_DIAJUKAN'],
          $result[$i]['HASIL_UJI_ADMINISTRASI'],
          $result[$i]['UJI_ADMINISTRASI'],
          $result[$i]['HASIL_UJI_MATERIAL'],
          $result[$i]['UJI_MATERIAL'],
      );
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  public function form($id=0)
  {
    $data['title'] = 'Pengujian';
    $data['modul'] = 'pengujian';
    $data['link_proses'] = 'proses';
    $data['link_back'] = '/daftar';
    $data['header'] = 'Pengujian';
		$data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    if ($id!==0)
    {
      $data['data'] = $this->data_model->get_data_by_id($id);
    }
    
    $data['jenis_bantuan'] = $this->data_model->get_data_jenis_bantuan()->result_array();

    $data['main_content']='pengujian_form';
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
              $result[$i]['TANGGAL'],
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
  
  public function tim_uji($id=0)
  {
    $result = $this->data_model->get_tim_uji_by_id($id);
    $response = (object) NULL;
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
    
  public function proses()
  {
    $response = (object) NULL;
    $this->load->library('form_validation');

    $this->form_validation->set_rules('no_proposal', 'Nomor Proposal', 'required|trim|max_length[100]');
    $this->form_validation->set_rules('no_uji', 'Nomor Uji', 'required|trim|max_length[100]|callback_duplikat_number');
    $this->form_validation->set_rules('tgl', 'Tanggal Pengujian', 'required|trim');
    $this->form_validation->set_rules('hasil_uji_adm', 'Hasil Uji Administrasi', 'required|trim');
    $this->form_validation->set_rules('hasil_uji_mat', 'Hasil Uji Material', 'required|trim');

    /* TODO : cek rincian ada isinya atau tidak */

    $this->form_validation->set_message('required', '%s tidak boleh kosong.');
    $this->form_validation->set_message('max_length', '%s tidak boleh melebihi %s karakter.');
    $this->form_validation->set_message('integer', '%s harus angka.');
    
    $tgl_proposal = prepare_date($this->input->post('tgl_proposal'));
    $tgl_pengujian = prepare_date($this->input->post('tgl'));
    
    if ($tgl_proposal > $tgl_pengujian)
    {
      $response->isSuccess = FALSE;
      $response->message = 'Data gagal disimpan, tanggal pengujian tidak boleh kurang dari tanggal proposal.';
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
  
  public function do_upload()
  {
    $this->load->helper('file');
    $jenis_uji = $this->input->post('jenis_uji');
    
    $result = (object) NULL;

    $path = $this->data_model->path;
    if (!file_exists($path)) {
      mkdir('uploads', 0777, true);
    }
    
    if(isset($_FILES["file"]))
    {      
      $error =$_FILES["file"]["error"];
      //You need to handle  both cases
      //If Any browser does not support serializing of multiple files using FormData() 
      if(!is_array($_FILES["file"]["name"])) //single file
      {
        $filename = $_FILES["file"]["name"];
        $filesize = filesize($_FILES["file"]["tmp_name"]);
        $newfilename = $this->set_filename($filename, $jenis_uji);
        
        move_uploaded_file($_FILES["file"]["tmp_name"],$path.$newfilename);
        
        $result->realname = $filename;
        $result->name = $newfilename;
        $result->size = $filesize;
        $result->mime = get_mime_by_extension($filename);
        $result->tgl = date('Y-m-d');
      }
      else  //Multiple files, file[]
      {
        $fileCount = count($_FILES["file"]["name"]);
        for($i=0; $i < $fileCount; $i++)
        {
          $filename = $_FILES["file"]["name"][$i];
          $filesize = filesize($_FILES["file"]["tmp_name"]);
          $newfilename = $this->set_filename($filename, $jenis_uji);
          
          move_uploaded_file($_FILES["file"]["tmp_name"][$i],$path.$newfilename);

          $result->realname = $filename;
          $result->name = $newfilename;
          $result->size = $filesize;
          $result->mime = get_mime_by_extension($filename);
          $result->tgl = date('Y-m-d');
        }
      
      }
    }

    echo json_encode($result);
  }
  
  public function set_filename($filename, $jenis_uji)
  {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    
    if ($jenis_uji === 'adm')
      $ext_name = 'dokumen_uji_administrasi';
    else if ($jenis_uji === 'mat')
      $ext_name = 'dokumen_uji_material';
    
    $date = date('Y_m_d_G_i_s');
    $filename = strtolower($filename);
    $newfilename = $ext_name.'_'.$date.'_'.$filename.'.'.$ext;
    
    return $newfilename;
  }
  
  public function delete_fileupload()
  {
    $path = $this->data_model->path;
    $filename = $this->input->post('filename');
    if ( isset($filename) )
    {
      $unlink = $this->data_model->unlink_fileupload($filename);
      if ( !file_exists( $path . $filename ) ) {
        $response->isSuccess = TRUE;
      } else {
        $response->isSuccess = FALSE;
      }
      echo json_encode($response);
    }
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
}