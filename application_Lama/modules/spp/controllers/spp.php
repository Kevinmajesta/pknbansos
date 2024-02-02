<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spp extends Aktivitas_Controller {

  public function __construct()
  {
    parent::__construct();

    $this->modul_name = 'spp';
    $this->modul_display = 'SPP';
    $this->view_form = 'spp_form';
    $this->view_daftar = 'spp_view';
    $this->load->model('Spp_model','data_model');

    $this->message_aktivitas_dihapus = 'SPP telah dihapus.';
    $this->message_aktivitas_gagal_dihapus = 'SPP Tidak Bisa Dihapus Karena Sudah Ada SPM.';
  }

  function validasi()
  {
    /* cek rincian ada isinya atau tidak dan apakah sisanya ada yang minus */
    $this->form_validation->set_rules('rincian', 'Rincian Rekening', 'required|callback__cek_minus_rek');
//    $this->form_validation->set_message('_cek_minus_rek', 'Rincian Rekening ada yang sisanya minus.');

    /* cek rincian ada isinya atau tidak */
    $this->form_validation->set_rules('spd', 'Rincian SPD', 'required');
  }

  public function _cek_minus_rek($data)
  {
    $spd = $this->input->post('spd') ? $this->input->post('spd') : '';
    $arrspd = json_decode($spd);
    $arrdata = json_decode($data);

    if (is_array($arrdata)){
      $param = array(
        'id_skpd' => $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0,
        'beban' => $this->input->post('beban') ? $this->input->post('beban') : '',
        'keperluan' => $this->input->post('keperluan') ? $this->input->post('keperluan') : '',
        'tanggal' => $this->input->post('tgl') ? $this->input->post('tgl') : '',
        'id_aktivitas' => $this->input->post('id') ? $this->input->post('id') : 0,
      );
      for ($i = 0; $i < count($arrspd); $i++){
        $param['arrspd'][] = $arrspd[$i]->idspd;
      }

      for ($i = 0; $i < count($arrdata); $i++){
        $param['id_kegiatan'] = $arrdata[$i]->idkeg ? $arrdata[$i]->idkeg : 0;
        $param['id_rekening'] = $arrdata[$i]->idrek;
        $sisa = $this->data_model->get_sisa_rekening($param);

        if ($sisa && (float) $sisa['NOMINAL'] - $arrdata[$i]->nom < 0.0) return false;
      }
    }
    else return true;
  }

  function spd($id=0)
  {
    $result = $this->data_model->get_spd_by_id($id);

    $response = (object) NULL;
    if ($result){
      for($i = 0; $i < count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_SPP_PAKAI_SPD'];
        $response->rows[$i]['cell'] = array(
              $result[$i]['ID_SPP_PAKAI_SPD'],
              $result[$i]['ID_AKTIVITAS_SPD'],
              $result[$i]['NOMOR'],
              $result[$i]['TANGGAL'],
              $result[$i]['NOMINAL'],
        );
      }
    }
    echo json_encode($response);
  }

  function kegiatan($id=0)
  {
    $result = $this->data_model->get_keg_by_id($id);

    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_KEGIATAN'];
        $response->rows[$i]['cell'] = array(
            $result[$i]['ID_KEGIATAN'],
            $result[$i]['KODE_KEGIATAN_SKPD'],
            $result[$i]['NAMA_KEGIATAN'],
        );
      }
    }
    echo json_encode($response);
  }

  function rekening($id=0)
  {
    $result = $this->data_model->get_rek_by_id($id);

    $response = (object) NULL;
    $response->sql = $this->db->queries;
    if ($result){
      for($i = 0; $i < count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_RINCIAN_SPP'];
        $response->rows[$i]['cell'] = array(
            $result[$i]['ID_RINCIAN_SPP'],
            $result[$i]['ID_REKENING'],
            $result[$i]['ID_KEGIATAN'],
            $result[$i]['KODE_KEGIATAN_SKPD'],
            $result[$i]['KODE_REKENING'],
            $result[$i]['NAMA_REKENING'],
            $result[$i]['NOMINAL']
        );
      }
    }
    echo json_encode($response);
  }

  /*function potongan($id=0)
  {
    $result = $this->data_model->get_potongan_by_id($id);

    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_REKENING'];
        $response->rows[$i]['cell'] = array(
              $result[$i]['ID_REKENING'],
              $result[$i]['KODE_REKENING'],
              $result[$i]['NAMA_REKENING'],
              $result[$i]['NOMINAL']
        );
      }
    }
    echo json_encode($response);
  }*/

  function pajak($id=0)
  {
    $result = $this->data_model->get_pajak_by_id($id);

    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_PAJAK'];
        $response->rows[$i]['cell']=array(
              $result[$i]['ID_PAJAK'],
              $result[$i]['ID_REKENING'],
              $result[$i]['NAMA_PAJAK'],
              $result[$i]['KODE_REKENING'],
              $result[$i]['NAMA_REKENING'],
              $result[$i]['PERSEN'],
              $result[$i]['NOMINAL'],
              $result[$i]['IS_INFORMASI']
        );
      }
    }
    echo json_encode($response);
  }
  
  public function sisa_spd()
  {
    $idr = $this->input->post('idr') ? $this->input->post('idr') : 0;
    $param = array(
      'id_skpd' => $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0,
      'beban' => $this->input->post('beban') ? $this->input->post('beban') : '',
      'tanggal' => $this->input->post('tanggal') ? $this->input->post('tanggal') : '',
      'id_aktivitas' => $this->input->post('id') ? $this->input->post('id') : 0,
      'arrspd' => $this->input->post('arrspd') ? $this->input->post('arrspd') : 0,
    );

    $result = $this->data_model->get_sisa_spd($param);
    $response = (object) NULL;
    if ($result){
      $response = array(
          'idr' => $idr,
          'sisa_all' => $result['NOMINAL_SISA'],
          'sisa' => $result['NOMINAL_SISA_TANGGAL']
      );
    }
    echo json_encode($response);
  }

  public function sisa_kegiatan()
  {
    $idr = $this->input->post('idr') ? $this->input->post('idr') : 0;
    $param = array(
      'id_skpd' => $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0,
      'beban' => $this->input->post('beban') ? $this->input->post('beban') : '',
      'keperluan' => $this->input->post('keperluan') ? $this->input->post('keperluan') : '',
      'id_kegiatan' => $this->input->post('id_kegiatan') ? $this->input->post('id_kegiatan') : 0,
      'tanggal' => $this->input->post('tanggal') ? $this->input->post('tanggal') : '',
      'id_aktivitas' => $this->input->post('id') ? $this->input->post('id') : 0,
      'arrspd' => $this->input->post('arrspd') ? $this->input->post('arrspd') : ''
    );

    $result = $this->data_model->get_sisa_kegiatan($param);
    $response = (object) NULL;
    if ($result){
      $response = array(
          'idr' => $idr,
          'sisa' => $result['NOMINAL'],
          'mp' => $result['NOMINAL_MP']
      );
    }
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
			'id_aktivitas' => $this->input->post('id') ? $this->input->post('id') : 0,
			'id_spj' => $this->input->post('id_spj') ? $this->input->post('id_spj') : 0,
			'arrspd' => $this->input->post('arrspd') ? $this->input->post('arrspd') : '0',
		);

		$result = $this->data_model->get_sisa_rekening($param);
		$response = (object) NULL;
		if ($result){
			$response = array(
				'idr' => $idr,
				'batas' => $result['batas'],
				'sisa' => $result['sisa'],
				'sisa_gu' => $result['sisa_gu'],
				'spj' => $result['spj'],
				'sql' => $this->db->queries,
			);
		}
		echo json_encode($response);
	}

	public function sisa_skpd()
	{
		$param = array(
			'id' => $this->input->post('id') ? $this->input->post('id') : 0,
			'id_skpd' => $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0,
			'beban' => $this->input->post('beban') ? $this->input->post('beban') : '',
			'keperluan' => $this->input->post('keperluan') ? $this->input->post('keperluan') : '',
			'id_aktivitas' => $this->input->post('id') ? $this->input->post('id') : 0,
			'spj' => $this->input->post('spj') ? $this->input->post('spj') : 0,
		);

		$sisa = $this->data_model->get_sisa_skpd($param);
		$sisa_gu = $this->data_model->get_sisa_spj($param);

		$response = (object) NULL;
		$response = array(
			'sisa_pagu' => $sisa,
			//'sisa_gu' => ($param['id']==0)?$sisa_gu['spj']-$sisa_gu['spp']:$sisa_gu['spj'],
			'sisa_gu' => $sisa_gu['spj']-$sisa_gu['spp'],
			'sql' => $this->db->queries,
		);
		echo json_encode($response);
	}

  public function dpa()
  {
    $param = array(
      'id_skpd' => $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0,
      'beban' => $this->input->post('beban') ? $this->input->post('beban') : '',
      'keperluan' => $this->input->post('keperluan') ? $this->input->post('keperluan') : '',
      'arrkeg' => $this->input->post('arrkeg') ? $this->input->post('arrkeg') : 0,
    );

    $result = $this->data_model->get_dpa($param);
    $no_dpa = $this->data_model->get_no_dpa($param);
    $response = (object) NULL;

    $response = array(
        'no_dpa' => $no_dpa ? $no_dpa : '',
        'tgl_dpa' => isset($result['TANGGAL_DPA']) ? format_date($result['TANGGAL_DPA']) : '',
        'pagu_dpa' => isset($result['PAGU']) ? $result['PAGU'] : 0,
        'sql' => $this->db->queries,
    );

    echo json_encode($response);
  }
  
	function ambil_data_spj($id_spj,$id_skpd)
	{
		//$id_spj = $this->input->post('id_spj') ? $this->input->post('id_spj') : 0
		$result = $this->data_model->get_data_spj($id_spj,$id_skpd);

		$response = (object) NULL;
		$response->sql = $this->db->queries;
		if ($result){
			for($i = 0; $i < count($result); $i++)
			{
				$response->rows[$i]['id'] = $result[$i]['ID_REKENING'];
				$response->rows[$i]['cell'] = array(
					0-($i+1),
					$result[$i]['ID_REKENING'],
					0,
					'',
					$result[$i]['KODE_REKENING'],
					$result[$i]['NAMA_REKENING'],
					$result[$i]['NOMINAL_SPJ']-$result[$i]['NOMINAL_SPP'],
					0,
					$result[$i]['NOMINAL_SPJ']-$result[$i]['NOMINAL_SPP']
				);
			}
		}
		echo json_encode($response);
	}

}