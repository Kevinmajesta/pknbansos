<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*********************************************************************************************
  SUPER CLASS dari semua Modul Pilih
 *********************************************************************************************/
class Pilih_Controller extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $user_data = $this->session->userdata;
    $this->load->vars($user_data);
  }

  protected function getParam($opt)
  {
    $sidx = isset($_REQUEST['sidx']) ? $_REQUEST['sidx'] : null;
    $sord = isset($_REQUEST['sord']) ? $_REQUEST['sord'] : null;
    $q = isset($_REQUEST['q']) ? $_REQUEST['q'] : null;

    $param = array();
    $param['sort_by'] = $sidx;
    $param['sort_direction'] = $sord;
    $param['q'] = $q;

    foreach ($opt as $key => $value)
    {
      $param[$key] = $this->input->post( $key ) ? $this->input->post( $key ) : $value['default'];
    }

    return $param;
  }

}