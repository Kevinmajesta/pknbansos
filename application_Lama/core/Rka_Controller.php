<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*********************************************************************************************
  SUPER CLASS dari semua Modul Aktivitas
 *********************************************************************************************/
class Rka_Controller extends CI_Controller{

  var $data_model;
  var $modul_name;
  var $modul_display;
  var $view_form;
  var $view_daftar;
  var $message_dihapus  = 'RKA telah dihapus.';
  var $message_gagal_dihapus  = 'RKA tidak bisa dihapus.';

  public function __construct()
  {
    parent::__construct();
    $user_data = $this->session->userdata;
    $this->load->vars($user_data);
  }

  function index()
  {
    $this->daftar();
  }

  function get_grid_model()
  {
    // di override di modul Aktivitas
  }

  function daftar()
  {
    $this->load->model('auth/login_model', 'auth');
    $data['breadcrumbs'] = 'Daftar '.$this->modul_display;
    $data['title'] = PRODUCT_NAME;
    $data['modul'] = $this->modul_name;
    $data['grid']['modul'] = $this->modul_name;
    $data['grid']['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    $data['grid']['skpd'] = $this->session->userdata('id_skpd');
    $data['grid']['url'] = base_url($this->modul_name.'/get_daftar');
    $data['grid']['url_add'] = base_url($this->modul_name.'/form');
    $data['grid']['url_del'] = base_url($this->modul_name.'/hapus');
    $data['grid']['data'] = $this->data_model->get_grid_model();
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

  function form($id = 0)
  {
    $data['title'] = PRODUCT_NAME;
    $data['modul'] = $this->modul_name;
    $this->load->model('auth/login_model', 'auth');
    $data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    if ($id!==0)
    {
      $data['data'] = $this->data_model->get_data_by_id($id);
    }
    $data['main_content']= $this->view_form;
    $this->load->view('layout/template',$data);
  }

  function indikator($id = 0, $id_keg = null)
  {
    $response = (object) NULL;

    if ($id === 0)
    {
      echo json_encode($response);
      return;
    }

    $result = $this->data_model->get_indikator($id, $id_keg);
    if ($this->data_model->perubahan)
    {
      $murni = $this->data_model->get_indikator_murni($id, $id_keg);
    }

    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_INDIKATOR_KINERJA'];
        $response->rows[$i]['cell'][] = $result[$i]['ID_INDIKATOR_KINERJA'];
        $response->rows[$i]['cell'][] = $result[$i]['TIPE_INDIKATOR'];
        $response->rows[$i]['cell'][] = $result[$i]['NAMA_INDIKATOR'];
        if ($this->data_model->perubahan)
        {
          $row_murni = $this->search_array($murni, 'TIPE_INDIKATOR', $result[$i]['TIPE_INDIKATOR']);
          if ($row_murni) $row_murni = current($row_murni);

          $response->rows[$i]['cell'][] = ($row_murni ? $row_murni['TOLOK_UKUR'] : '');
          $response->rows[$i]['cell'][] = ($row_murni ? $row_murni['TARGET'] : '');
          $response->rows[$i]['cell'][] = ($row_murni ? $row_murni['JUMLAH_TARGET'] : '');
        }
        $response->rows[$i]['cell'][] = $result[$i]['TOLOK_UKUR'];
        $response->rows[$i]['cell'][] = $result[$i]['TARGET'];
        $response->rows[$i]['cell'][] = $result[$i]['JUMLAH_TARGET'];
      }
    }
    echo json_encode($response);
  }

  private function search_array($array, $key, $value) {
    $return = array();
    foreach ($array as $k=>$subarray){
      if (isset($subarray[$key]) && $subarray[$key] == $value) {
        $return[$k] = $subarray;
        return $return;
      }
    }
  }

  // menampilkan rincian anggaran rka
	// dipanggil dari form RKA / DPA
	// fungsi ini di override di RKA / DPA Lanjutan
	// param : id => id_form_anggaran rka
	function rincian($id = 0)
	{
		$result = $this->data_model->get_rincian($id);
		if ($this->data_model->perubahan)
		{
			$id_murni = $this->data_model->get_id_rka_murni($id);
			$murni_ = $this->data_model->get_rincian_murni($id_murni);
			$murni = array();
			foreach ($murni_ as $key => $value){
				$murni[$value['ID_DETIL_REKENING']] = $value;
			}
		}

		$response = (object) NULL;
		if ($result){
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id'] = $result[$i]['ID_RINCIAN_ANGGARAN'];
				$response->rows[$i]['cell'][] = $result[$i]['ID_RINCIAN_ANGGARAN'];
				$response->rows[$i]['cell'][] = $result[$i]['ID_DETIL_REKENING'];
				$response->rows[$i]['cell'][] = $result[$i]['ID_REKENING'];
				$response->rows[$i]['cell'][] = $result[$i]['ID_PARENT_REKENING'];
				$response->rows[$i]['cell'][] = $result[$i]['LEVEL_REKENING'];
				$response->rows[$i]['cell'][] = $result[$i]['CHILD'];
				$response->rows[$i]['cell'][] = ($result[$i]['LEVEL_REKENING'] > 0 ? $result[$i]['KODE_REKENING'] : '');
				$response->rows[$i]['cell'][] = $result[$i]['URAIAN'];
				if ($this->data_model->perubahan)
				{
					$row_murni = array();
					if (isset($murni[ $result[$i]['ID_DETIL_REKENING']])){
						$row_murni = $murni[ $result[$i]['ID_DETIL_REKENING']];
					}
					else {
						$row_murni['VOLUME'] = '';
						$row_murni['TARIF'] = '';
						$row_murni['SATUAN'] = '';
						$row_murni['PAGU'] = '';
					}
					
					$response->rows[$i]['cell'][] = ((isset($row_murni['ID_DETIL_REKENING'])) ? 1 : 0);
					$response->rows[$i]['cell'][] = ($row_murni && $result[$i]['LEVEL_REKENING'] > 0 ? '' : $row_murni['VOLUME']);
					$response->rows[$i]['cell'][] = ($row_murni ? $row_murni['SATUAN'] : '');
					$response->rows[$i]['cell'][] = ($row_murni && $result[$i]['LEVEL_REKENING'] > 0 ? '' : $row_murni['TARIF']);
					$response->rows[$i]['cell'][] = ($row_murni ? $row_murni['PAGU'] : '');
				}
				$response->rows[$i]['cell'][] = ($result[$i]['LEVEL_REKENING'] > 0 ? '' : $result[$i]['VOLUME']);
				$response->rows[$i]['cell'][] = $result[$i]['SATUAN'];
				$response->rows[$i]['cell'][] = ($result[$i]['LEVEL_REKENING'] > 0 ? '' : $result[$i]['TARIF']);
				$response->rows[$i]['cell'][] = $result[$i]['PAGU'];
				if ($this->data_model->perubahan)
					$response->rows[$i]['cell'][] = ($row_murni ? $result[$i]['PAGU'] - $row_murni['PAGU'] : $result[$i]['PAGU']);
				$response->rows[$i]['cell'][] = ($result[$i]['LEVEL_REKENING'] > 0 ? $result[$i]['REALISASI'] : '' );
				$response->rows[$i]['cell'][] = $result[$i]['PAGU_TAHUN_DEPAN'];
				$response->rows[$i]['cell'][] = $result[$i]['KETERANGAN'];
				$response->rows[$i]['cell'][] = ($this->data_model->tipe === 'RKA31' || $this->data_model->tipe === 'RKA32') ? $result[$i]['PAGU'] : 0;
				if (in_array($this->data_model->tipe, array('RKA1', 'RKA21', 'RKA31', 'RKA32')))
					$response->rows[$i]['cell'][] = $result[$i]['PAGU_TAHUN_DEPAN'];
				$response->rows[$i]['cell'][] = ($result[$i]['ID_PROPOSAL']  ? $result[$i]['ID_PROPOSAL'] : 0 );
			}
		}
		$response->sql = $this->db->queries;
		echo json_encode($response);
	}

  function kas($id = 0)
  {

  }

  function bahasan($id = 0, $id_keg = null)
  {
    $response = (object) NULL;

    if ($id === 0)
    {
      echo json_encode($response);
      return;
    }

    $result = $this->data_model->get_bahasan($id, $id_keg);

    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['NO_URUT'];
        $response->rows[$i]['cell']=array(
            $result[$i]['NO_URUT'],
            $result[$i]['CATATAN'],
        );
      }
    }
    echo json_encode($response);
  }

  function lokasi($id = 0, $id_keg = null)
  {
    $response = (object) NULL;

    if ($id === 0)
    {
      echo json_encode($response);
      return;
    }

    $result = $this->data_model->get_lokasi($id, $id_keg);

    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_LOKASI'];
        $response->rows[$i]['cell']=array(
            $result[$i]['ID_LOKASI'],
            $result[$i]['LOKASI'],
            $result[$i]['NOMINAL'],
        );
      }
    }
    echo json_encode($response);
  }

  function sumberdana($id = 0, $id_keg = null)
  {
    $response = (object) NULL;

    if ($id === 0)
    {
      echo json_encode($response);
      return;
    }

    $result = $this->data_model->get_sumberdana($id, $id_keg);

    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_SUMBER_DANA'];
        $response->rows[$i]['cell']=array(
            $result[$i]['ID_SUMBER_DANA'],
            $result[$i]['NAMA_SUMBER_DANA'],
            $result[$i]['NOMINAL'],
        );
      }
    }
    echo json_encode($response);
  }

  function validasi_form()
  {
    $this->form_validation->set_rules('tgl', 'Tanggal', 'required|trim');
    $this->form_validation->set_rules('ket', 'Keterangan', 'trim');

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
        $data_exists = $this->data_model->data_exists();
        
        if ($data_exists == TRUE)
        {
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
          if ($this->data_model->data_fa['TIPE'] == 'RKA21')
            $response->message = 'RKA dengan SKPD tersebut sudah ada';
          else if ($this->data_model->data_fa['TIPE'] == 'RKA221')
            $response->message = 'SKPD dengan Kegiatan tersebut sudah ada';

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
      $response->message = $this->message_dihapus;
    }
    else
    {
      // ada dependensi, tampilkan pesan kesalahan
      $response->isSuccess = FALSE;
      $response->Message = $this->message_gagal_dihapus;
    }

    echo json_encode($response);
  }


  function check_dependency($id)
  {
    return TRUE;  // di override di modul Aktivitas
  }

  function get_id_fa()
  {
    $result = $this->data_model->get_id_form();

    if (count($result) > 0) $response->id_fa = $result['ID_FORM_ANGGARAN']; else $response->id_fa = 0;

    $response->sql = $this->db->queries;

    echo json_encode($response);
  }
}