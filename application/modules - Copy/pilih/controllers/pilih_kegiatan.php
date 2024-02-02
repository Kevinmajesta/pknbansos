<?php

class Pilih_kegiatan extends Pilih_Controller{
  public function __construct()
  {
    parent:: __construct();
    $this->load->model('pilih_kegiatan_model', 'pilih_model');
  }

  function index()
  {
  }

  /*
      Pilih Kegiatan
  */

  var $opt_kegiatan = array(
    'multi' => array('default' =>0),
    'mode' => array('default' =>''),
    'tanggal' => array('default' =>''),
    'id_skpd' => array('default' =>'0'),
    'keperluan' => array('default' =>''),
    'beban' => array('default' =>''),
  );

  function getkegiatan()
  {
    $param = $this->getParam($this->opt_kegiatan);
    $result = $this->pilih_model->getKegiatan($param);
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_KEGIATAN'];
        $response->rows[$i]['cell'] = array(
          $result[$i]['ID_KEGIATAN'],
          $result[$i]['KODE_KEGIATAN_LKP'],
          $result[$i]['NAMA_KEGIATAN'],
        );
      }
    }
    echo json_encode($response);
  }

  function kegiatan()
  {
    $data['dialogname'] = 'kegiatan';
    $data['colsearch'] = array('kode' => 'Kode Kegiatan', 'nama' => 'Nama Kegiatan');
    $data['colnames'] = array('', 'Kode Kegiatan', 'Nama Kegiatan');
    $data['colmodel'] = array(
      array('name' => 'id', 'hidden' => true),
      array('name' => 'kode', 'width' => '80', 'sortable' => true),
      array('name' => 'nama', 'width' => '350', 'sortable' => true)
    );
    $data['orderby'] = 'kode';
    $data['param'] = $this->getParam($this->opt_kegiatan);

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/pilih_kegiatan/get'.$data['dialogname'],
      'pager' => '#pgrDialog'.$data['dialogname'],
      'sortname' => $data['orderby'],
      'multiselect' => (int)$data['param']['multi'],
      'colNames' => $data['colnames'],
      'colModel' => $data['colmodel'],
      'postData' => $data['param'],
    );

    echo json_encode($response);
  }

  /*
      Pilih Kegiatan Aktivitas
  */

  var $opt_act = array(
    'multi' => array('default' =>0),
    'mode' => array('default' =>''),
    'id' => array('default' =>'0'),
    'id_ssp' => array('default' =>'0'),
    'id_spj' => array('default' =>'0'),
    'id_kegiatan' => array('default' =>'0'),
    'tanggal' => array('default' =>''),
    'id_skpd' => array('default' =>'0'),
    'keperluan' => array('default' =>''),
    'beban' => array('default' =>''),
    'arrspd' => array('default' =>''),
  );

  function getkegiatanAktivitas()
  {
    $param = $this->getParam($this->opt_act);
    $result = $this->pilih_model->getKegiatan($param);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_KEGIATAN'];
        $response->rows[$i]['cell'] = array(
            $result[$i]['ID_KEGIATAN'],
            $result[$i]['KODE_KEGIATAN_SKPD'],
            $result[$i]['NAMA_KEGIATAN'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function kegiatanAktivitas()
  {
    $data['dialogname'] = 'kegiatanAktivitas';
    $data['colsearch'] = array('kodes' => 'Kode Kegiatan', 'nama' => 'Nama Kegiatan');
    $data['colnames'] = array('', 'Kode Kegiatan', 'Nama Kegiatan');
    $data['colmodel'] = array(
      array('name' => 'id', 'hidden' => true),
      array('name' => 'kodes', 'width' => '150', 'sortable' => true),
      array('name' => 'nama', 'width' => '530', 'sortable' => true)
    );
    $data['orderby'] = 'kodes';
    $data['param'] = $this->getParam($this->opt_act);

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/pilih_kegiatan/get'.$data['dialogname'],
      'pager' => '#pgrDialog'.$data['dialogname'],
      'sortname' => $data['orderby'],
      'multiselect' => (int)$data['param']['multi'],
      'colNames' => $data['colnames'],
      'colModel' => $data['colmodel'],
      'postData' => $data['param'],
    );

    echo json_encode($response);
  }

}