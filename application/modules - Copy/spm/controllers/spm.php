<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spm extends Aktivitas_Controller {

  public function __construct()
  {
    parent::__construct();

    $this->modul_name = 'spm';
    $this->modul_display = 'SPM';
    $this->view_form = 'spm_form';
    $this->view_daftar = 'spm_view';
    $this->load->model('Spm_model','data_model');

    $this->message_aktivitas_dihapus = 'SPM telah dihapus.';
    $this->message_aktivitas_gagal_dihapus = 'SPM Tidak Bisa Dihapus Karena Sudah Ada SP2D.';
  }

  function validasi()
  {
    /* cek rincian ada isinya atau tidak dan apakah sisanya ada yang minus */
    $this->form_validation->set_rules('rincian', 'Rincian Rekening', 'required|callback__cek_minus_rek');
    $this->form_validation->set_message('_cek_minus_rek', 'Rincian Rekening ada yang sisanya minus.');

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
        'id_aktivitas' => $this->input->post('id_spp') ? $this->input->post('id_spp') : 0,
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

  function spp($id=0)
  {
    $result = $this->data_model->get_spp_by_id($id);

    $response = (object) NULL;
    if ($result){
      $response->id_spp = $result['ID_AKTIVITAS'];
      $response->id_spj = $result['ID_AKTIVITAS_SPJ'];
      $response->no_spp = $result['NOMOR'];
      $response->tgl_spp = format_date($result['TANGGAL']);
      $response->deskripsi = $result['DESKRIPSI'];
      $response->nominal_spp = $result['NOMINAL'];
      $response->id_skpd = $result['ID_SKPD'];
      $response->kd_skpd = $result['KODE_SKPD_LKP'];
      $response->nm_skpd = $result['NAMA_SKPD'];
      $response->beban = $result['BEBAN'];
      $response->jenis_beban = $result['JENIS_BEBAN'];
      $response->keperluan = $result['KEPERLUAN'];
      $response->penerima = $result['NAMA_PENERIMA'];
      $response->bank = $result['NAMA_BANK'];
      $response->norek = $result['NO_REKENING_BANK'];
      $response->kontrak = $result['NO_KONTRAK'];
      $response->npwp = $result['NPWP'];
      $response->no_dpa = format_date($result['NO_DPA']);
      $response->tgl_dpa = $result['TANGGAL_DPA'];
      $response->pagu_dpa = $result['PAGU_DPA'];
      $response->bk = $result['ID_BK'];
      $response->nm_bk = $result['BK_NAMA'];
      $response->kaskpd = $result['ID_PA'];
      $response->nm_kaskpd = $result['PA_NAMA'];
    }
    echo json_encode($response);
  }

  function spd($id=0, $spp=0)
  {
    $result = $this->data_model->get_spd_by_id($id, $spp);

    $response = (object) NULL;
    $response->sql = $this->db->queries;
    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_PAKAI_SPD'];
        $response->rows[$i]['cell']=array(
              $result[$i]['ID_PAKAI_SPD'],
              $result[$i]['ID_SPD'],
              $result[$i]['NOMOR'],
              $result[$i]['TANGGAL'],
              $result[$i]['NOMINAL'],
        );
      }
    }
    echo json_encode($response);
  }

  function kegiatan($id=0, $spp=0)
  {
    $result = $this->data_model->get_kegiatan_by_id($id, $spp);

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

  function rekening($id=0, $spp=0)
  {
    $result = $this->data_model->get_rekening_by_id($id, $spp);

    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_RINCIAN'];
        $response->rows[$i]['cell']=array(
              $result[$i]['ID_RINCIAN'],
              $result[$i]['ID_REKENING'],
              $result[$i]['ID_KEGIATAN'],
              $result[$i]['KODE_KEGIATAN_SKPD'],
              $result[$i]['KODE_REKENING'],
              $result[$i]['NAMA_REKENING'],
              $result[$i]['NOMINAL'],
        );
      }
    }
    $response->sql = $this->db->queries;
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

  function spj($id=0)
  {
    $result = $this->data_model->get_spj_by_id($id);

    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_RINCIAN_SPJ'];
        $response->rows[$i]['cell']=array(
              $result[$i]['ID_RINCIAN_SPJ'],
              $result[$i]['KODE_KEGIATAN_SKPD'],
              $result[$i]['NAMA_KEGIATAN'],
              $result[$i]['KODE_REKENING'],
              $result[$i]['NAMA_REKENING'],
              $result[$i]['NOMINAL']
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
          'sisa' => $result['NOMINAL_SISA_TANGGAL'],
          'sql' => $this->db->queries
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
          'cp' => $result['NOMINAL_CP'],
          'ssu' => $result['NOMINAL_SSU'],
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
      'arrspd' => $this->input->post('arrspd') ? $this->input->post('arrspd') : '0',
    );

    $result = $this->data_model->get_sisa_rekening($param);
    $response = (object) NULL;
    if ($result){
      $response = array(
          'idr' => $idr,
          'sisa' => $result['NOMINAL'],
          'cp' => $result['NOMINAL_CP'],
          'ssu' => $result['NOMINAL_SSU'],
          'mp' => $result['NOMINAL_MP']
      );
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
      'id' => $this->input->post('id') ? $this->input->post('id') : 0,
      'arrspd' => $this->input->post('arrspd') ? $this->input->post('arrspd') : 0,
    );

    if ($param['keperluan'] === 'PP')
    {
      $sisa = $this->data_model->get_sisa_sts($param);
      $sisa_gu = 0;
      $label = 'Sisa STS';
    }
    else
    {
      if ($param['keperluan'] === 'GU Nihil')
      {
        $sisa = 0;
        $sisa_gu = 0;
        $label = 'UP yang dinihilkan';
      }
      else
      {
        $sisa = $this->data_model->get_sisa_skpd($param);
        $sisa_gu = $param['keperluan'] === 'GU' ? $this->data_model->get_sisa_gu($param) : 0;

        $label = 'Sisa Anggaran';
      }
    }

    $response = (object) NULL;
    $response = array(
        'lbl_sisa_pagu' => $label,
        'sisa_pagu' => $sisa,
        'sisa_gu' => $sisa_gu,
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
        'tgl_dpa' => isset($result['TANGGAL_DPA']) ? $result['TANGGAL_DPA'] : '',
        'pagu_dpa' => isset($result['PAGU']) ? $result['PAGU'] : 0,
        'sql' => $this->db->queries,
    );

    echo json_encode($response);
  }

}