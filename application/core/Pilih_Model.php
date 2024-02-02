<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*********************************************************************************************
  SUPER CLASS dari semua Model Pilih
 *********************************************************************************************/
class Pilih_Model extends CI_Model{

  function __construct()
  {
    parent::__construct();

    $this->tahun  = $this->session->userdata('tahun');
    $this->status = $this->session->userdata('status');
  }

  function checkSearch($q, $fieldmap)
  {
    $wh = '';

    if ($q)
    {
      $flt = array();
      foreach($fieldmap as $key => $value){
        $flt[$key] = array('search_str' => $q, 'search_op' => 'cn');
      }
      $wh = get_where_str($flt, $fieldmap);
    }
    return $wh;
  }

}