<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spd extends Aktivitas_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->modul_name = 'spd';
    $this->modul_display = 'SPD';
    $this->view_form = 'spd_form';
    $this->view_daftar = 'spd_view';
    $this->load->model('Spd_model','data_model');

    $this->message_aktivitas_dihapus = 'SPD telah dihapus.';
    $this->message_aktivitas_gagal_dihapus = 'SPD Tidak Bisa Dihapus Karena Sudah Ada SPP.';
  }

  function validasi()
  {
    $this->form_validation->set_rules('id_sd', 'Sumber Dana', 'required|trim|integer');
    $this->form_validation->set_rules('kaskpd', 'Kepala SKPD', 'trim|integer');
    $this->form_validation->set_rules('bt', 'Bendahara Penerimaan', 'trim|integer');

    /* cek rincian ada isinya atau tidak dan apakah sisanya ada yang minus */
    if ($this->input->post('beban') === 'BL'){
      $this->form_validation->set_rules('keg', '', 'required|callback__cek_minus_keg');
      $this->form_validation->set_message('_cek_minus_keg', 'Rincian Kegiatan ada yang sisanya minus.');
    }
    $this->form_validation->set_rules('rek', '', 'required|callback__cek_minus_rek');
    $this->form_validation->set_message('_cek_minus_rek', 'Rincian Rekening ada yang sisanya minus.');

    /* TODO : cek rincian ada isinya atau tidak */
  }

  public function _cek_minus_keg($data)
  {
    $arrdata = json_decode($data);
    if (is_array($arrdata)){
      $param = array(
        'id_skpd' => $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0,
        'beban' => $this->input->post('beban') ? $this->input->post('beban') : '',
        'tanggal' => $this->input->post('tgl') ? $this->input->post('tgl') : '',
        'id_aktivitas' => $this->input->post('id') ? $this->input->post('id') : 0,
      );
      for ($i=0; $i < count($arrdata); $i++){
        $param['id_kegiatan'] = $arrdata[$i]->idkeg ? $arrdata[$i]->idkeg : 0;
        $sisa = $this->data_model->get_sisa_kegiatan($param);

        if ($sisa && (float) $sisa['NOMINAL'] - $arrdata[$i]->nom < 0.0) return false;
      }
    }
    else return true;
  }

  public function _cek_minus_rek($data)
  {
    $arrdata = json_decode($data);
    if (is_array($arrdata)){
      $param = array(
        'id_skpd' => $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0,
        'beban' => $this->input->post('beban') ? $this->input->post('beban') : '',
        'id_aktivitas' => $this->input->post('id') ? $this->input->post('id') : 0,
      );
      for ($i=0; $i < count($arrdata); $i++){
        $param['id_kegiatan'] = $arrdata[$i]->idkeg ? $arrdata[$i]->idkeg : 0;
        $param['id_rekening'] = $arrdata[$i]->idrek;
        $sisa = $this->data_model->get_sisa_rekening($param);

        if ($sisa && (float) $sisa['NOMINAL'] - $arrdata[$i]->nom < 0.0) return false;
      }
    }
    else return true;
  }

  function kegiatan($id=0)
  {
    $result = $this->data_model->get_kegiatan_by_id($id);

    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_KEGIATAN'];
        $response->rows[$i]['cell'] = array(
              $result[$i]['ID_KEGIATAN'],
              $result[$i]['KODE_KEGIATAN_SKPD'],
              $result[$i]['NAMA_KEGIATAN'],
              0,0,
              $result[$i]['NOMINAL'],
              0,
              $result[$i]['NOMINAL'],
              0,0,0,0,0,0,0,0
        );
      }
    }
    echo json_encode($response);
  }

  function rekening($id=0)
  {
    $result = $this->data_model->get_rekening_by_id($id);

    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_RINCIAN_REKENING_SPD'];
        $response->rows[$i]['cell'] = array(
              $result[$i]['ID_RINCIAN_REKENING_SPD'],
              $result[$i]['ID_REKENING'],
              $result[$i]['ID_KEGIATAN'],
              $result[$i]['KODE_KEGIATAN_SKPD'],
              $result[$i]['KODE_REKENING'],
              $result[$i]['NAMA_REKENING'],
              0,
              $result[$i]['NOMINAL'],
        );
      }
    }
    echo json_encode($response);
  }

  public function sisa_skpd()
  {
    $response = (object) NULL;

    $param = array(
      'id_skpd' => $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0,
      'beban' => $this->input->post('beban') ? $this->input->post('beban') : '',
      'id_aktivitas' => $this->input->post('id') ? $this->input->post('id') : 0,
    );

    $dpa = $this->data_model->get_sisa_skpd($param);

    $param = array(
      'id_skpd' => $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0,
      'beban' => '-',
      'id_aktivitas' => $this->input->post('id') ? $this->input->post('id') : 0,
    );

    $skpd = $this->data_model->get_sisa_skpd($param);

    $response->dpa = $dpa ? $dpa : 0;
    $response->skpd = $skpd ? $skpd : 0;

    echo json_encode($response);
  }

  public function sisa_kegiatan()
  {
    $param = array(
      'id_skpd' => $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0,
      'beban' => $this->input->post('beban') ? $this->input->post('beban') : '',
      'id_kegiatan' => $this->input->post('id_kegiatan') ? $this->input->post('id_kegiatan') : 0,
      'tanggal' => $this->input->post('tanggal') ? $this->input->post('tanggal') : '',
      'id_aktivitas' => $this->input->post('id') ? $this->input->post('id') : 0,
    );

    $result = $this->data_model->get_sisa_kegiatan($param);
    $response = (object) NULL;
    if ($result){
      $response = array(
            'idr' => $param['id_kegiatan'],
            'pagu' => $result['NOMINAL_ANGGARAN'],
            'spd_all' => $result['NOMINAL_SPD'],
            'spd' => $result['NOMINAL_SPD_TANGGAL'],
            'sisa' => $result['NOMINAL'],
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
      'id_kegiatan' => $this->input->post('id_kegiatan') ? $this->input->post('id_kegiatan') : 0,
      'id_rekening' => $this->input->post('id_rekening') ? $this->input->post('id_rekening') : 0,
      'id_aktivitas' => $this->input->post('id') ? $this->input->post('id') : 0,
    );

    $result = $this->data_model->get_sisa_rekening($param);
    $response = (object) NULL;
    if ($result){
      $response = array(
            'idr' => $idr,
            'pagu' => $result['NOMINAL_ANGGARAN'],
            'sisa' => $result['NOMINAL'],
            'spd' => $result['NOMINAL_SPD'],
      );
    }
    echo json_encode($response);
  }

}