<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sp2d extends Aktivitas_Controller {

  public function __construct()
  {
    parent::__construct();

    $this->modul_name = 'sp2d';
    $this->modul_display = 'SP2D';
    $this->view_form = 'sp2d_form';
    $this->view_daftar = 'sp2d_view';
    $this->load->model('Sp2d_model','data_model');

    $this->message_aktivitas_dihapus = 'SP2D telah dihapus.';
    $this->message_aktivitas_gagal_dihapus = 'SP2D telah dicairkan, tidak bisa dihapus.';
  }

  function validasi()
  {
    $this->form_validation->set_rules('id_sd', 'Sumber Dana', 'required|trim|integer');
    $this->form_validation->set_rules('kaskpd', 'Kepala SKPD', 'trim|integer');
  }

  function spm($id=0)
  {
    $result = $this->data_model->get_spm_by_id($id);

    $response = (object) NULL;
    if ($result){
      $response->id_spp = $result['ID_SPP'];
      $response->id_spm = $result['ID_AKTIVITAS'];
      $response->no_spm = $result['NOMOR'];
      $response->tgl_spm = format_date($result['TANGGAL']);
      $response->nominal = $result['NOMINAL'];
      $response->deskripsi = $result['DESKRIPSI'];
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
      $response->kaskpd = $result['ID_KASKPD'];
      $response->nm_kaskpd = $result['KASKPD_NAMA'];
      $response->bk = $result['ID_BK'];
      $response->nm_bk = $result['BK_NAMA'];
      $response->nominal = $result['NOMINAL'];
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

    $sisa = $this->data_model->get_sisa_skpd($param);
    $sisa_gu = $this->data_model->get_sisa_spj($param, 'UP');
    $sisa_tu = $this->data_model->get_sisa_spj($param, 'TU');
    $label = 'Sisa SPD + CP + SSU';

    $response = (object) NULL;
    $response = array(
        'lbl_sisa_pagu' => $label,
        'sisa_pagu' => $sisa,
        'sisa_gu' => $sisa_gu,
        'sisa_tu' => $sisa_tu,
        'sql' => $this->db->queries,
    );
    echo json_encode($response);
  }

}