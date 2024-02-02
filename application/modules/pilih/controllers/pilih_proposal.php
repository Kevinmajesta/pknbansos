<?php

class Pilih_proposal extends Pilih_Controller{
  public function __construct()
  {
    parent:: __construct();
    $this->load->model('pilih_proposal_model', 'pilih_model');
    $this->load->model('proposal/proposal_model', 'proposal_model');
  }

  function index()
  {
  }

  /*
      Pilih Proposal
  */

  var $opt_proposal = array(
    'multi' => array('default' =>0),
    'mode' => array('default' =>''),
    'bantuan' => array('default' =>''),
    'kategori' => array('default' =>''),
    'beban' => array('default' =>''),
    'skpd' => array('default' =>0)
  );

  function getproposal()
  {
    $param = $this->getParam($this->opt_proposal);
    $result = $this->pilih_model->getProposal($param);
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    
    if ($result){
      if ($param['mode'] === 'rka')
      {
        for($i=0; $i<count($result); $i++){
          $response->rows[$i]['id'] = $result[$i]['ID_PROPOSAL'];
          $response->rows[$i]['cell'] = array(
            $result[$i]['ID_PROPOSAL'],
            $result[$i]['NOMOR'],
            $result[$i]['NAMA_PEMOHON'],
            $result[$i]['ALAMAT_PEMOHON'],
            $result[$i]['NOMINAL_DIAJUKAN'],
            $result[$i]['RINGKASAN'],
          );
        }
      }
      else if ($param['mode'] === 'spp')
      {
        for($i=0; $i<count($result); $i++){
          $response->rows[$i]['id'] = $result[$i]['ID_PROPOSAL'];
          $response->rows[$i]['cell'] = array(
            $result[$i]['ID_PROPOSAL'],
            $result[$i]['NOMOR'],
            $result[$i]['TANGGAL'],
            $result[$i]['NAMA_PEMOHON'],
            $result[$i]['NOMINAL_DIAJUKAN'],
            $result[$i]['PAGU'],
          );
        }
      }
      else if ($param['mode'] === 'bast')
      {
        for($i=0; $i<count($result); $i++){
          $response->rows[$i]['id'] = $result[$i]['ID_PROPOSAL'];
          $response->rows[$i]['cell'] = array(
            $result[$i]['ID_PROPOSAL'],
            $result[$i]['NOMOR'],
            $result[$i]['TANGGAL'],
            $result[$i]['NAMA_PEMOHON'],
            $result[$i]['NOMINAL_DISETUJUI'],
          );
        }
      }
      else
      {
        for($i=0; $i<count($result); $i++){
          $response->rows[$i]['id'] = $result[$i]['ID_PROPOSAL'];
          $response->rows[$i]['cell'] = array(
            $result[$i]['ID_PROPOSAL'],
            $result[$i]['NOMOR'],
            $result[$i]['TANGGAL'],
            $result[$i]['NAMA_PEMOHON'],
            $result[$i]['NOMINAL_DIAJUKAN'],
          );
        }
      }
    }
    echo json_encode($response);
  }

  function proposal()
  {
    $param = $this->getParam($this->opt_proposal);

    $data['dialogname'] = 'proposal';
    $data['param'] = $this->getParam($this->opt_proposal);
    
    if ($data['param']['mode'] === 'rka')
    {
      $data['jenis_bantuan'] = $this->proposal_model->get_data_jenis_bantuan()->result_array();
      $data['colsearch'] = array('no' => 'Nomor', 'nama' => 'Nama Pemohon');
      $data['colnames'] = array('', 'Nomor', 'Nama Pemohon', 'Alamat Pemohon', 'Nominal Pengajuan', 'Ringkasan Proposal');
      $data['colmodel'] = array(
        array('name' => 'id', 'hidden' => true),
        array('name' => 'no', 'width' => '80', 'sortable' => true),
        array('name' => 'nama', 'width' => '200', 'sortable' => true),
        array('name' => 'alamat', 'width' => '300', 'sortable' => true),
        array('name' => 'nom', 'formatter' => 'currency', 'align' => 'right', 'width' => '150', 'sortable' => true),
        array('name' => 'ringkasan', 'width' => '400', 'sortable' => true),
      );
      $data['orderby'] = 'no';

      $response = (object) NULL;
      $response->html = $this->load->view('v_pilih_proposal_rka', $data, true);
      $response->grid = array(
        'url' => base_url().'pilih/pilih_proposal/get'.$data['dialogname'],
        'pager' => '#pgrDialog'.$data['dialogname'],
        'sortname' => $data['orderby'],
        'multiselect' => (int)$data['param']['multi'],
        'colNames' => $data['colnames'],
        'colModel' => $data['colmodel'],
        'postData' => $data['param'],
        'height' => 230,
      );
    }
    else if ($data['param']['mode'] === 'spp')
    {
      $data['colsearch'] = array('no' => 'Nomor', 'nama' => 'Nama Pemohon');
      $data['colnames'] = array('', 'Nomor', 'Tanggal', 'Nama Pemohon', 'Nominal Pengajuan', 'Pagu');
      $data['colmodel'] = array(
        array('name' => 'id', 'hidden' => true),
        array('name' => 'no', 'width' => '80', 'sortable' => true),
        array('name' => 'tgl', 'formatter' => 'date', 'hidden' => true),
        array('name' => 'nama', 'width' => '350', 'sortable' => true),
        array('name' => 'nom', 'formatter' => 'currency', 'align' => 'right', 'width' => '150', 'sortable' => true),
        array('name' => 'pagu', 'formatter' => 'currency', 'align' => 'right', 'width' => '150', 'sortable' => true),
      );
      $data['orderby'] = 'no';

      $response = (object) NULL;
      $response->html = $this->load->view('v_pilih', $data, true);
      $response->grid = array(
        'url' => base_url().'pilih/pilih_proposal/get'.$data['dialogname'],
        'pager' => '#pgrDialog'.$data['dialogname'],
        'sortname' => $data['orderby'],
        'multiselect' => (int)$data['param']['multi'],
        'colNames' => $data['colnames'],
        'colModel' => $data['colmodel'],
        'postData' => $data['param'],
      );
    }
    else if ($data['param']['mode'] === 'bast')
    {
      $data['colsearch'] = array('no' => 'Nomor', 'nama' => 'Nama Pemohon');
      $data['colnames'] = array('', 'Nomor', 'Tanggal', 'Nama Pemohon', 'Nominal Persetujuan');
      $data['colmodel'] = array(
        array('name' => 'id', 'hidden' => true),
        array('name' => 'no', 'width' => '170', 'sortable' => true),
        array('name' => 'tgl', 'formatter' => 'date', 'hidden' => true),
        array('name' => 'nama', 'width' => '350', 'sortable' => true),
        array('name' => 'nom', 'formatter' => 'currency', 'align' => 'right', 'width' => '180', 'sortable' => true),
      );
      $data['orderby'] = 'no';

      $response = (object) NULL;
      $response->html = $this->load->view('v_pilih', $data, true);
      $response->grid = array(
        'url' => base_url().'pilih/pilih_proposal/get'.$data['dialogname'],
        'pager' => '#pgrDialog'.$data['dialogname'],
        'sortname' => $data['orderby'],
        'multiselect' => (int)$data['param']['multi'],
        'colNames' => $data['colnames'],
        'colModel' => $data['colmodel'],
        'postData' => $data['param'],
      );
    }
    else
    {
      $data['colsearch'] = array('no' => 'Nomor', 'nama' => 'Nama Pemohon');
      $data['colnames'] = array('', 'Nomor', 'Tanggal', 'Nama Pemohon', 'Nominal Pengajuan');
      $data['colmodel'] = array(
        array('name' => 'id', 'hidden' => true),
        array('name' => 'no', 'width' => '170', 'sortable' => true),
        array('name' => 'tgl', 'formatter' => 'date', 'hidden' => true),
        array('name' => 'nama', 'width' => '350', 'sortable' => true),
        array('name' => 'nom', 'formatter' => 'currency', 'align' => 'right', 'width' => '180', 'sortable' => true),
      );
      $data['orderby'] = 'no';

      $response = (object) NULL;
      $response->html = $this->load->view('v_pilih', $data, true);
      $response->grid = array(
        'url' => base_url().'pilih/pilih_proposal/get'.$data['dialogname'],
        'pager' => '#pgrDialog'.$data['dialogname'],
        'sortname' => $data['orderby'],
        'multiselect' => (int)$data['param']['multi'],
        'colNames' => $data['colnames'],
        'colModel' => $data['colmodel'],
        'postData' => $data['param'],
      );
    }

    echo json_encode($response);
  }
}