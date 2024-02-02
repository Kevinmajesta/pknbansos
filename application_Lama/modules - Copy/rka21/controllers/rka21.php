<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rka21 extends Rka_Controller {

  public function __construct()
  {
    parent::__construct();

    $this->modul_name = 'rka21';
    $this->modul_display = 'RKA 21';
    $this->view_form = 'rka21_form';
    $this->view_daftar = 'rka21_view';
    $this->load->model('rka21_model', 'data_model');

    $this->message_dihapus = 'RKA 21 telah dihapus.';
    $this->message_gagal_dihapus = 'RKA 21 tidak bisa dihapus.';
  }

  function validasi()
  {
    //$this->form_validation->set_rules('id_sd', 'Sumber Dana', 'required|trim|integer');
    //$this->form_validation->set_rules('kaskpd', 'Kepala SKPD', 'trim|integer');
  }

}