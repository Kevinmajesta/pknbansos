<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spj extends Aktivitas_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->modul_name = 'spj';
    $this->modul_display = 'SPJ';
    //$this->view_form = ($this->session->userdata('id_skpd') === 0 ? 'spj_sipkd_form' : 'spj_form');
    $this->view_form = 'spj_form';
    $this->view_daftar = 'spj_view';
    $this->load->model('Spj_model','data_model');

    $this->message_aktivitas_dihapus = 'SPJ telah dihapus.';
    $this->message_aktivitas_gagal_dihapus = 'SPJ tidak bisa dihapus.';
  }

  function validasi()
  {
    $this->form_validation->set_rules('id_skpd', 'SKPD', 'required|trim|integer');
    $this->form_validation->set_rules('sah', 'Tanggal Pengesahan', 'required|trim');
    $this->form_validation->set_rules('pekas', 'Rekening Bendahara', 'trim|integer');
    $this->form_validation->set_rules('bk', 'Bendahara Pengeluaran', 'trim|integer');
    $this->form_validation->set_rules('pa', 'Pengguna Anggaran', 'trim|integer');
    $this->form_validation->set_rules('ppk', 'PPK SKPD', 'trim|integer');

    /* TODO : cek rincian ada isinya atau tidak */
    $this->form_validation->set_rules('rinci', 'Rincian Rekening', 'required|callback__cek_minus_rek');
    $this->form_validation->set_message('_cek_minus_rek', 'Rincian Rekening ada yang sisanya minus.');
  }

  public function _cek_minus_rek($data)
  {
    $arrdata = json_decode($data);

    if (is_array($arrdata)){
      $param = array(
        'id_skpd' => $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0,
        'beban' => $this->input->post('beban') ? $this->input->post('beban') : '',
        'keperluan' => $this->input->post('keperluan') ? $this->input->post('keperluan') : '',
        'tanggal' => $this->input->post('tgl') ? $this->input->post('tgl') : '',
        'id_aktivitas' => $this->input->post('id') ? $this->input->post('id') : 0,
      );

      for ($i = 0; $i < count($arrdata); $i++){
        $param['id_kegiatan'] = $arrdata[$i]->idkeg ? $arrdata[$i]->idkeg : 0;
        $param['id_rekening'] = $arrdata[$i]->idrek;
        $sisa = $this->data_model->get_sisa_rekening($param);

        if ($sisa && (float) $sisa['NOMINAL'] - $arrdata[$i]->nom < 0.0) return false;
      }
    }
    else return true;
  }

  public function rekening($id=0)
  {
    $result = $this->data_model->get_rinci_rekening_by_id($id);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_RINCIAN_SPJ'];
        $response->rows[$i]['cell'] = array(
                        $result[$i]['ID_RINCIAN_SPJ'],
                        $result[$i]['ID_REKENING'],
                        $result[$i]['KODE_REKENING'],
                        $result[$i]['NAMA_REKENING'],
                        $result[$i]['NOMINAL']
                    );
      }
    }
    echo json_encode($response);
  }

	public function sisa_skpd()
	{
		$param = array(
			'id_skpd' => $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0,
			'beban' => $this->input->post('beban') ? $this->input->post('beban') : '',
			'keperluan' => $this->input->post('keperluan') ? $this->input->post('keperluan') : '',
			'tanggal' => $this->input->post('tanggal') ? $this->input->post('tanggal') : '',
			'id_aktivitas' => $this->input->post('id') ? $this->input->post('id') : 0
		);

		$result_sp2d_tanggal = $this->data_model->get_sisa_skpd($param,TRUE);
		$result_sp2d = $this->data_model->get_sisa_skpd($param);
		$response = (object) NULL;
		/* $response = array(
			'sisa' => isset($result_sp2d_tanggal['NOMINAL']) ? $result_sp2d_tanggal['NOMINAL'] : 0,
			'sisa_all' => isset($result_sp2d['NOMINAL']) ? $result_sp2d['NOMINAL'] : 0,
			'sql' => $this->db->queries,
		); */
		$response = array(
			'sisa' => isset($result_sp2d_tanggal) ? $result_sp2d_tanggal : 0,
			'sisa_all' => isset($result_sp2d) ? $result_sp2d : 0,
			'sql' => $this->db->queries,
		);
		echo json_encode($response);
	}

	public function sisa_rekening()
	{
		$idr = $this->input->post('idr') ? $this->input->post('idr') : 0;
		$param = array(
			'id_skpd' => $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0,
			'beban' => $this->input->post('beban') ? $this->input->post('beban') : '',
			'keperluan' => $this->input->post('keperluan') ? $this->input->post('keperluan') : '',
			'id_kegiatan' => $this->input->post('id_kegiatan') ? $this->input->post('id_kegiatan') : 0,
			'id_rekening' => $this->input->post('id_rekening') ? $this->input->post('id_rekening') : 0,
			'tanggal' => $this->input->post('tanggal') ? $this->input->post('tanggal') : '',
			'id_aktivitas' => $this->input->post('id') ? $this->input->post('id') : 0
		);

		$result_sekarang = $this->data_model->get_sisa_rekening($param,'sekarang');
		$result_seluruh = $this->data_model->get_sisa_rekening($param,'seluruh');
		$response = (object) NULL;
		$response = array(
			'idr' => $idr,
			'sisa' => $result_seluruh['NOMINAL_RKA']-$result_seluruh['NOMINAL_SPP']-$result_seluruh['NOMINAL_SPJ'],
			'sisa_all' => $result_seluruh['NOMINAL_RKA']-$result_seluruh['NOMINAL_SPP']-$result_seluruh['NOMINAL_SPJ'],
			'sql' => $this->db->queries,
		);
		echo json_encode($response);
	}
  
    public function do_upload()
  {
    $this->load->helper('file');
    
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
        $newfilename = $this->set_filename($filename);
        
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
          $newfilename = $this->set_filename($filename);
          
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
 
  public function set_filename($filename)
  {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    
    $ext_name = 'dokumen_spj';
    
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

  function get_fileupload($id = 0)
  {
    $response = (object) NULL;

    if ($id === 0)
    {
      echo json_encode($response);
      return;
    }

    $result = $this->data_model->get_scan_file($id);

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