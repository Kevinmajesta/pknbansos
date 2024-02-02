<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rka221 extends Rka_Controller {

  public function __construct()
  {
    parent::__construct();

    $this->modul_name = 'rka221';
    $this->modul_display = 'RKA 221';
    $this->view_form = 'rka221_form';
    $this->view_daftar = 'rka221_view';
    $this->load->model('rka221_model', 'data_model');

    $this->message_dihapus = 'RKA 221 telah dihapus.';
    $this->message_gagal_dihapus = 'RKA 221 tidak bisa dihapus.';
  }

  function validasi()
  {
    //$this->form_validation->set_rules('id_sd', 'Sumber Dana', 'required|trim|integer');
    //$this->form_validation->set_rules('kaskpd', 'Kepala SKPD', 'trim|integer');
  }

}