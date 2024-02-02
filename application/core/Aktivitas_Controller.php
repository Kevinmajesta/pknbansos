<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*********************************************************************************************
  SUPER CLASS dari semua Modul Aktivitas
 *********************************************************************************************/
class Aktivitas_Controller extends CI_Controller{
	var $appconfig;
	var $config_file;
	var $data_model;
	var $modul_name;
	var $modul_display;
	var $view_form;
	var $view_daftar;
	var $message_aktivitas_dihapus  = 'Aktivitas telah dihapus.';
	var $message_aktivitas_gagal_dihapus  = 'Aktivitas tidak bisa dihapus.';

	public function __construct()
	{
		parent::__construct();
		$user_data = $this->session->userdata;
		$this->load->vars($user_data);
		
		$this->appconfig = 'appconfig';
		$this->appconfig_path = APPPATH.'config/';
		$this->appconfig_file = $this->appconfig_path.$this->appconfig.'.php';

		$this->config_file = APPPATH.'config/appconfig.php';
	}

  function index()
  {
    $this->daftar();
  }

  function get_grid_model()
  {
    // di override di modul Aktivitas
  }

  function get_data_fields()
  {
    // di override di modul Aktivitas
  }

  function daftar()
  {
    $this->load->model('auth/login_model', 'auth');
    $data['breadcrumbs'] = 'Daftar '.$this->modul_display;
    $data['title'] = PRODUCT_NAME;
    $data['modul'] = $this->modul_name;
    $data['grid']['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    $data['grid']['skpd'] = $this->session->userdata('id_skpd');
    $data['grid']['url'] = base_url($this->modul_name.'/get_daftar');
    $data['grid']['url_add'] = base_url($this->modul_name.'/form');
    $data['grid']['url_del'] = base_url($this->modul_name.'/hapus');
    $data['grid']['data'] = $this->data_model->get_grid_model();
    $data['grid']['fields'] = $this->data_model->get_data_fields();
    $data['main_content'] = $this->view_daftar;
    $this->load->view('layout/template', $data);
  }

  function get_daftar()
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
        "sa" => isset($_REQUEST['sa']) ? $_REQUEST['sa'] : '',
        "search_field" => isset($_REQUEST['searchField']) ? $_REQUEST['searchField'] : null,
        "search_operator" => isset($_REQUEST['searchOper']) ? $_REQUEST['searchOper'] : null,
        "search_str" => isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : null,
    );

    //$count = $this->data_model->get_data($req_param, TRUE);
		$aggregate = $this->data_model->get_data($req_param, TRUE);
    $count = $aggregate['CNT'];

    $response = (object) NULL;
    $response->sql = $this->db->queries;
    if($count == 0) // tidak ada data
    {
			// menambahkan userdata jika ada
			$agg_fields = $this->data_model->fieldmap_daftar_aggregate;
      foreach($agg_fields as $kolom => $value)
      {
        $response->userdata[$kolom] = $aggregate[ strtoupper($kolom) ];
      }
			
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

    $fields = $this->data_model->fields;
    for($i=0; $i<count($result); $i++)
    {
      $response->rows[$i]['id'] = $result[$i][$fields[0]];
      $data = array();
      for ($n=1; $n < count($fields); $n++)
      {
        $data[] = $result[$i][$fields[$n]];
      }
      $response->rows[$i]['cell'] = $data;
    }
		
		// menambahkan userdata jika ada
    $agg_fields = $this->data_model->fieldmap_daftar_aggregate;
    foreach($agg_fields as $kolom => $value)
    {
      $response->userdata[$kolom] = $aggregate[ strtoupper($kolom) ];
    }
		
    echo json_encode($response);
  }

	function form($id=0)
	{
		$data['title'] = PRODUCT_NAME;
		$data['modul'] = $this->modul_name;
		$data['modul_display'] = $this->modul_display;
		$this->load->model('auth/login_model', 'auth');
		$data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
		$data['config_kodeskpd'] = $this->config_kodeskpd()?$this->config_kodeskpd():'0';
		if ($id!==0)
		{
			$data['data'] = $this->data_model->get_data_by_id($id);
		}
		$data['main_content']= $this->view_form;
		$this->load->view('layout/template',$data);
	}
	
	function config_kodeskpd()
	{
		$this->load->config($this->appconfig, TRUE);
		$app_config   = $this->config->item($this->appconfig);
		$config_kodeskpd   = $app_config['config_kodeskpd'];

		return $config_kodeskpd;
	}

  function validasi_form()
  {
    $this->form_validation->set_rules('no', 'Nomor', 'required|trim|max_length[50]|callback__cek_nomor');
    $this->form_validation->set_rules('tgl', 'Tanggal', 'required|trim');
    $this->form_validation->set_rules('deskripsi', 'Keterangan', 'trim');

    $this->form_validation->set_message('required', '%s tidak boleh kosong.');
    $this->form_validation->set_message('max_length', '%s tidak boleh melebihi %s karakter.');
    $this->form_validation->set_message('_cek_nomor', '%s sudah ada.');
  }

  function proses()
  {
    $response = (object) NULL;
    $this->load->model('auth/login_model', 'auth');
    $akses = $this->auth->get_level_akses($this->uri->slash_segment(1));
    if($akses == '3')
    {
      $this->load->library('form_validation');

      $this->validasi_form();

      if($this->form_validation->run() == TRUE)
      {
        $this->data_model->fill_data();
        $success = $this->data_model->save_data();

        if(!$success){
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
    else
    {
      $response->isSuccess = FALSE;
      $response->message = 'Proses Gagal, Anda tidak memiliki hak akses';
    }
    echo json_encode($response);
  }

  public function _cek_nomor($nomor)
  {
    return $this->data_model->cek_duplikasi_nomor($nomor);
  }

  public function prev($id=0)
  {
    $response = (object) NULL;
    $response->isSuccessful = FALSE;
    if ($id !== 0)
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
    if ($id !== 0)
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
    $response = (object) NULL;
    $this->load->model('auth/login_model', 'auth');
    $akses = $this->auth->get_level_akses($this->uri->slash_segment(1));

    if($akses !== 3)
    {
        $response->isSuccess = FALSE;
        $response->message = 'Anda tidak memiliki Hak akses.';
        echo json_encode($response);
        return;
    }

    $id = $this->input->post('id');
    $result = $this->data_model->check_dependency($id);

    if($result)
    {
      // bisa dihapus
      $this->data_model->delete_data($id);
      $response->isSuccess = TRUE;
      $response->message = $this->message_aktivitas_dihapus; // 'STS telah dihapus';
    }
    else
    {
      // ada dependensi, tampilkan pesan kesalahan
      $response->isSuccess = FALSE;
      $response->message = $this->message_aktivitas_gagal_dihapus;  // 'STS sudah dicairkan. Tidak bisa dihapus.';
    }

    echo json_encode($response);
  }


  function check_dependency($id)
  {
    return TRUE;  // di override di modul Aktivitas
  }
}