<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bud extends Aktivitas_Controller {
  public function __construct()
  {
    parent::__construct();

    $this->modul_name = 'bud';
    $this->modul_display = 'Bendahara Umum Daerah';
    $this->view_form = 'bud_form';
    $this->view_daftar = 'bud_view';
    $this->load->model('bud_model','data_model');

    $this->message_aktivitas_dihapus = 'BUD telah dihapus.';
    $this->message_aktivitas_gagal_dihapus = 'BUD tidak bisa dihapus.';
  }

  function validasi()
  {
    $this->form_validation->set_rules('bud', 'BUD/Kuasa BUD', 'trim|integer');

    /* cek rincian ada isinya atau tidak */
    $this->form_validation->set_rules('rincian', 'Rincian BUD', 'required');
  }

  public function rinci($id=0)
  {
    $result = $this->data_model->get_rinci_by_id($id);

    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        if ($result[$i]['ID_AKTIVITAS_KONTRA_POS'] > 0)
          $aktivitas = $this->data_model->get_aktivitas_by_id($result[$i]['ID_AKTIVITAS_KONTRA_POS']);
        else if ($result[$i]['ID_AKTIVITAS_PAJAK_SP2D'] > 0)
          $aktivitas = $this->data_model->get_aktivitas_by_id($result[$i]['ID_AKTIVITAS_PAJAK_SP2D']);
        else if ($result[$i]['ID_AKTIVITAS_PFK_SP2D'] > 0)
          $aktivitas = $this->data_model->get_aktivitas_by_id($result[$i]['ID_AKTIVITAS_PFK_SP2D']);
        else if ($result[$i]['ID_AKTIVITAS_SETORAN_SISA'] > 0)
          $aktivitas = $this->data_model->get_aktivitas_by_id($result[$i]['ID_AKTIVITAS_SETORAN_SISA']);
        else if ($result[$i]['ID_AKTIVITAS_STS'] > 0)
          $aktivitas = $this->data_model->get_aktivitas_by_id($result[$i]['ID_AKTIVITAS_STS']);
        else if ($result[$i]['ID_AKTIVITAS_SP2D'] > 0)
          $aktivitas = $this->data_model->get_aktivitas_by_id($result[$i]['ID_AKTIVITAS_SP2D']);
        else {
          $aktivitas['TIPE'] = 'Pembukuan';
          $aktivitas['ID_SKPD'] = '';
          $aktivitas['KODE_SKPD_LKP'] = '';
          $aktivitas['NAMA_SKPD'] = '';
        }
        $response->rows[$i]['id'] = $result[$i]['ID_RINCIAN_BKU'];
        $response->rows[$i]['cell'] = array(
              $result[$i]['ID_RINCIAN_BKU'],
              $result[$i]['ID_SUMBER_DANA'],
              0,  // id sts
              0,  // id sp2d
              0,  // id cp
              0,  // id spfk
              0,  // id spjk
              0,  // id ssu
              $result[$i]['NO_BKU'],
              $aktivitas['TIPE'],
              $result[$i]['NOMOR_TRANSAKSI'],
              $result[$i]['KODE_REKENING'],
              $result[$i]['NAMA_REKENING'],
              $result[$i]['DEBET'],
              $result[$i]['KREDIT'],
              $aktivitas['KODE_SKPD_LKP'],
              $aktivitas['NAMA_SKPD'],
              $result[$i]['DESKRIPSI_TRANSAKSI']
        );
      }
    }
    echo json_encode($response);
  }

  public function sumber_dana($id=0)
  {
    $tgl = $this->input->post('tanggal');

    $result = $this->data_model->get_sumberdana_by_id($id, $tgl);
    $response = (object) NULL;
    $saldo_awal = $saldo_akhir = 0;
    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_SUMBER_DANA'];
        $response->rows[$i]['cell'] = array(
              $result[$i]['ID_SUMBER_DANA'],
              $result[$i]['NAMA_SUMBER_DANA'],
              $result[$i]['SALDO_AWAL'],
              $result[$i]['SALDO_AKHIR']
        );
        $saldo_awal += $result[$i]['SALDO_AWAL'];
        $saldo_akhir += $result[$i]['SALDO_AKHIR'];
      }
    }
    $response->userdata['nama'] = 'TOTAL';
    $response->userdata['awal'] = $saldo_awal;
    $response->userdata['akhir'] = $saldo_akhir;
    echo json_encode($response);
  }

}