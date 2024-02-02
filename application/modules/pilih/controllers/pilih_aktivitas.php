<?php

class Pilih_aktivitas extends Pilih_Controller{
  public function __construct()
  {
    parent:: __construct();
    $this->load->model('pilih_aktivitas_model', 'pilih_model');
  }

  function index()
  {
  }

  /*
    Pilih Aktivitas SPP
    Dipakai di : SPM
  */

  var $opt_spp = array(
    'multi' => array('default' =>0),
    'mode' => array('default' =>''),
    'tanggal' => array('default' =>''),
    'id_skpd' => array('default' => 0),
  );

  function getspp()
  {
    $param = $this->getParam($this->opt_spp);
    $result = $this->pilih_model->getSPP($param);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_AKTIVITAS'];
        $response->rows[$i]['cell'] = array(
            $result[$i]['ID_AKTIVITAS'],
            $result[$i]['NOMOR'],
            $result[$i]['TANGGAL'],
            $result[$i]['KODE_SKPD_LKP'],
            $result[$i]['NAMA_SKPD'],
            $result[$i]['NAMA_KEGIATAN'],
            $result[$i]['KEPERLUAN'],
            $result[$i]['BEBAN'],
            $result[$i]['NOMINAL'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function spp()
  {
    $data['dialogname'] = 'spp';
    $data['colsearch'] = array('no' => 'Nomor', 'kdskpd' => 'Kode SKPD', 'nmskpd' => 'Nama SKPD', 'nmkeg' => 'Nama Kegiatan');
    $data['colnames'] = array('', 'Nomor', 'Tanggal', 'Kode SKPD', 'Nama SKPD', 'Nama Kegiatan', 'Keperluan', 'Beban', 'Nominal');
    $data['colmodel'] = array(
      array('name' => 'id', 'hidden' => true),
      array('name' => 'no', 'width' => '150', 'sortable' => true),
      array('name' => 'tgl', 'width' => '80', 'sortable' => true, 'formatter' => 'date'),
      array('name' => 'kdskpd', 'width' => '100', 'sortable' => true),
      array('name' => 'nmskpd', 'width' => '200', 'sortable' => true),
      array('name' => 'nmkeg', 'width' => '250', 'sortable' => true),
      array('name' => 'kpl', 'width' => '100', 'sortable' => true, 'align' => 'center'),
      array('name' => 'bbn', 'width' => '100', 'sortable' => true, 'align' => 'center'),
      array('name' => 'nom', 'width' => '150', 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
    );
    $data['orderby'] = 'no';
    $data['param'] = array();
    foreach ($this->opt_spp as $key => $value)
    {
      $data['param'][$key] = $this->input->post( $key ) ? $this->input->post( $key ) : $value['default'];
    }

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/pilih_aktivitas/get'.$data['dialogname'],
      'pager' => '#pgrDialog'.$data['dialogname'],
      'sortname' => $data['orderby'],
      'multiselect' => $data['param']['multi'],
      'colNames' => $data['colnames'],
      'colModel' => $data['colmodel'],
      'postData' => $data['param'],
    );

    echo json_encode($response);
  }

  /*
    Pilih Aktivitas SPM
    Dipakai di : SP2D
  */

  var $opt_spm = array(
    'multi' => array('default' =>0),
    'mode' => array('default' =>''),
    'tanggal' => array('default' =>''),
    'id_skpd' => array('default' => 0),
  );

  function getspm()
  {
    $param = $this->getParam($this->opt_spm);
    $result = $this->pilih_model->getSPM($param);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_AKTIVITAS'];
        $response->rows[$i]['cell'] = array(
            $result[$i]['ID_AKTIVITAS'],
            $result[$i]['NOMOR'],
            $result[$i]['TANGGAL'],
            $result[$i]['KODE_SKPD_LKP'],
            $result[$i]['NAMA_SKPD'],
            $result[$i]['NAMA_KEGIATAN'],
            $result[$i]['KEPERLUAN'],
            $result[$i]['BEBAN'],
            $result[$i]['NOMINAL'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function spm()
  {
    $data['dialogname'] = 'spm';
    $data['colsearch'] = array('no' => 'Nomor', 'kdskpd' => 'Kode SKPD', 'nmskpd' => 'Nama SKPD', 'nmkeg' => 'Nama Kegiatan');
    $data['colnames'] = array('', 'Nomor', 'Tanggal', 'Kode SKPD', 'Nama SKPD', 'Nama Kegiatan', 'Keperluan', 'Beban', 'Nominal');
    $data['colmodel'] = array(
      array('name' => 'id', 'hidden' => true),
      array('name' => 'no', 'width' => '150', 'sortable' => true),
      array('name' => 'tgl', 'width' => '80', 'sortable' => true, 'formatter' => 'date'),
      array('name' => 'kdskpd', 'width' => '100', 'sortable' => true),
      array('name' => 'nmskpd', 'width' => '200', 'sortable' => true),
      array('name' => 'nmkeg', 'width' => '250', 'sortable' => true),
      array('name' => 'kpl', 'width' => '100', 'sortable' => true),
      array('name' => 'bbn', 'width' => '100', 'sortable' => true),
      array('name' => 'nom', 'width' => '150', 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
    );
    $data['orderby'] = 'no';
    $data['param'] = array();
    foreach ($this->opt_spm as $key => $value)
    {
      $data['param'][$key] = $this->input->post( $key ) ? $this->input->post( $key ) : $value['default'];
    }

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/pilih_aktivitas/get'.$data['dialogname'],
      'pager' => '#pgrDialog'.$data['dialogname'],
      'sortname' => $data['orderby'],
      'multiselect' => $data['param']['multi'],
      'colNames' => $data['colnames'],
      'colModel' => $data['colmodel'],
      'postData' => $data['param'],
    );

    echo json_encode($response);
  }

  /*
    Pilih Aktivitas SP2D
    Dipakai di : Daftar Penguji, BUD
  */

  var $opt_sp2d = array(
    'multi' => array('default' =>0),
    'mode' => array('default' =>''),
    'tanggal' => array('default' =>''),
	'id_skpd' => array('default' => 0),
  );

  function getsp2d()
  {
    $param = $this->getParam($this->opt_sp2d);
    $result = $this->pilih_model->getSP2D($param);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_AKTIVITAS'];
        $response->rows[$i]['cell'] = array(
            $result[$i]['ID_AKTIVITAS'],
            $result[$i]['NOMOR'],
            $result[$i]['TANGGAL'],
            $result[$i]['KODE_SKPD_LKP'],
            $result[$i]['NAMA_SKPD'],
            $result[$i]['NAMA_KEGIATAN'],
            $result[$i]['KODE_REKENING'],
            $result[$i]['NAMA_REKENING'],
            $result[$i]['NAMA_PENERIMA'],
            $result[$i]['DESKRIPSI'],
            $result[$i]['NOMINAL'],
            $result[$i]['NOMINAL_KOTOR'],
            $result[$i]['NOMINAL_RINCIAN'],
            $result[$i]['NOMINAL_PFK'],
            $result[$i]['NOMINAL_PFK_INFORMASI'],
            $result[$i]['NOMINAL_PAJAK'],
            $result[$i]['NOMINAL_PAJAK_INFORMASI'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function sp2d()
  {
    $data['dialogname'] = 'sp2d';
    $data['colsearch'] = array('no' => 'Nomor', 'nmskpd' => 'Nama SKPD', );
    $data['colnames'] = array('', 'Nomor', 'Tanggal', '', 'Nama SKPD', 'Nama Kegiatan', '', '', '', 'Deskripsi', 'Nominal', '', '', '', '', '', '');
    $data['colmodel'] = array(
      array('name' => 'id', 'hidden' => true),
      array('name' => 'no', 'width' => '150', 'sortable' => true),
      array('name' => 'tgl', 'width' => '80', 'sortable' => true, 'formatter' => 'date'),
      array('name' => 'kdskpd', 'hidden' => true),
      array('name' => 'nmskpd', 'width' => '200', 'sortable' => true),
      array('name' => 'nmkeg', 'width' => '250', 'sortable' => true),
      array('name' => 'kdrek', 'hidden' => true),
      array('name' => 'nmrek', 'hidden' => true),
      array('name' => 'penerima', 'hidden' => true),
      array('name' => 'ket', 'width' => '300', 'sortable' => true),
      array('name' => 'nom', 'width' => '150', 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
      array('name' => 'brutto', 'hidden' => true),
      array('name' => 'rincian', 'hidden' => true),
      array('name' => 'pfk', 'hidden' => true),
      array('name' => 'pfkinfo', 'hidden' => true),
      array('name' => 'pjk', 'hidden' => true),
      array('name' => 'pjkinfo', 'hidden' => true),
    );
    $data['orderby'] = 'no';
    $data['param'] = array();
    foreach ($this->opt_sp2d as $key => $value)
    {
      $data['param'][$key] = $this->input->post( $key ) ? $this->input->post( $key ) : $value['default'];
    }

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/pilih_aktivitas/get'.$data['dialogname'],
      'pager' => '#pgrDialog'.$data['dialogname'],
      'sortname' => $data['orderby'],
      'multiselect' => $data['param']['multi'],
      'colNames' => $data['colnames'],
      'colModel' => $data['colmodel'],
      'postData' => $data['param'],
    );

    echo json_encode($response);
  }

  /*
    Pilih Aktivitas SPJ
    Dipakai di : SPP
  */

  var $opt_spj = array(
    'multi' => array('default' =>0),
    'mode' => array('default' =>''),
    'tanggal' => array('default' =>''),
    'id_skpd' => array('default' =>'0'),
    'keperluan' => array('default' =>''),
    'beban' => array('default' =>''),
  );

  function getspj()
  {
    $param = $this->getParam($this->opt_spj);
    $result = $this->pilih_model->getSPJ($param);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_AKTIVITAS'];
        $response->rows[$i]['cell'] = array(
            $result[$i]['ID_AKTIVITAS'],
            $result[$i]['NOMOR'],
            $result[$i]['TANGGAL'],
            $result[$i]['KODE_SKPD_LKP'],
            $result[$i]['NAMA_SKPD'],
            $result[$i]['KEPERLUAN'],
            $result[$i]['BEBAN'],
            $result[$i]['DESKRIPSI'],
            $result[$i]['NOMINAL'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function spj()
  {
    $data['dialogname'] = 'spj';
    $data['colsearch'] = array('no' => 'Nomor', 'kdskpd' => 'Kode SKPD', 'nmskpd' => 'Nama SKPD', 'kpl' => 'Keperluan', 'bbn' => 'Beban', 'ket' => 'Deskripsi', );
    $data['colnames'] = array('', 'Nomor', 'Tanggal', 'Kode SKPD', 'Nama SKPD', 'Keperluan', 'Beban', 'Deskripsi', 'Nominal');
    $data['colmodel'] = array(
      array('name' => 'id', 'hidden' => true),
      array('name' => 'no', 'width' => '150', 'sortable' => true),
      array('name' => 'tgl', 'width' => '80', 'align' => 'center', 'sortable' => true, 'formatter' => 'date'),
      array('name' => 'kdskpd', 'width' => '80', 'align' => 'center', 'sortable' => true),
      array('name' => 'nmskpd', 'width' => '250', 'sortable' => true),
      array('name' => 'kpl', 'width' => '60', 'align' => 'center', 'sortable' => true),
      array('name' => 'bbn', 'width' => '50', 'align' => 'center', 'sortable' => true),
      array('name' => 'ket', 'width' => '300', 'sortable' => true),
      array('name' => 'nom', 'width' => '150', 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
    );
    $data['orderby'] = 'no';
    $data['param'] = array();
    foreach ($this->opt_spj as $key => $value)
    {
      $data['param'][$key] = $this->input->post( $key ) ? $this->input->post( $key ) : $value['default'];
    }

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/pilih_aktivitas/get'.$data['dialogname'],
      'pager' => '#pgrDialog'.$data['dialogname'],
      'sortname' => $data['orderby'],
      'multiselect' => $data['param']['multi'],
      'colNames' => $data['colnames'],
      'colModel' => $data['colmodel'],
      'postData' => $data['param'],
    );

    echo json_encode($response);
  }

  /*
    Pilih Aktivitas SPD (Surat Penyediaan Dana)
    Dipakai di : SPP
  */

  var $opt_spd = array(
    'multi' => array('default' =>0),
    'mode' => array('default' =>''),
    'id' => array('default' =>'0'),
    'tanggal' => array('default' =>''),
    'id_skpd' => array('default' =>'0'),
    'beban' => array('default' =>''),
    'keperluan' => array('default' =>''),
  );

  function getspd()
  {
    $param = $this->getParam($this->opt_spd);
    $result = $this->pilih_model->getSPD($param);
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_AKTIVITAS'];
        $response->rows[$i]['cell'] = array(
            $result[$i]['ID_AKTIVITAS'],
            $result[$i]['NOMOR'],
            $result[$i]['TANGGAL'],
            $result[$i]['KODE_SKPD_LKP'],
            $result[$i]['NAMA_SKPD'],
            $result[$i]['BEBAN'],
            $result[$i]['DESKRIPSI'],
            $result[$i]['NOMINAL'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function spd()
  {
    $data['dialogname'] = 'spd';
    $data['colsearch'] = array(
        'no' => 'Nomor',
        'kdskpd' => 'Kode SKPD',
        'nmskpd' => 'Nama SKPD',
        'bbn' => 'Beban',
        'ket' => 'Deskripsi',
        'nom' => 'Nominal',
    );
    $data['colnames'] = array('', 'Nomor', 'Tanggal', 'Kode SKPD', 'Nama SKPD', 'Beban', 'Deskripsi', 'Nominal');
    $data['colmodel'] = array(
      array('name' => 'id', 'hidden' => true),
      array('name' => 'no', 'width' => '150', 'sortable' => true),
      array('name' => 'tgl', 'width' => '80', 'sortable' => true, 'formatter' => 'date'),
      array('name' => 'kdskpd', 'width' => '100', 'sortable' => true),
      array('name' => 'nmskpd', 'width' => '150', 'sortable' => true),
      array('name' => 'bbn', 'width' => '80', 'sortable' => true, 'align' => 'center'),
      array('name' => 'ket', 'width' => '300', 'sortable' => true),
      array('name' => 'nom','width' => '150', 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
    );
    $data['orderby'] = 'no';
    $data['param'] = array();
    foreach ($this->opt_spd as $key => $value)
    {
      $data['param'][$key] = $this->input->post( $key ) ? $this->input->post( $key ) : $value['default'];
    }

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/pilih_aktivitas/get'.$data['dialogname'],
      'pager' => '#pgrDialog'.$data['dialogname'],
      'sortname' => $data['orderby'],
      'multiselect' => $data['param']['multi'],
      'colNames' => $data['colnames'],
      'colModel' => $data['colmodel'],
      'postData' => $data['param'],
    );

    echo json_encode($response);
  }

}