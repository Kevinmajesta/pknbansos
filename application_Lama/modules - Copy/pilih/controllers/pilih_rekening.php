<?php

class Pilih_rekening extends Pilih_Controller{
  public function __construct()
  {
    parent:: __construct();
    $this->load->model('pilih_rekening_model', 'pilih_model');
  }

  function index()
  {
  }

  /*
      Pilih Rekening
  */
  var $opt_rekening = array(
    'multi' => array('default' =>0),
    'mode' => array('default' =>''),
    'id' => array('default' =>'0'),
    'id_spj' => array('default' =>'0'),
    'id_kegiatan' => array('default' =>'0'),
    'tanggal' => array('default' =>''),
    'id_skpd' => array('default' =>'0'),
    'keperluan' => array('default' =>''),
    'beban' => array('default' =>''),
    'arrspd' => array('default' =>''),
    'tree' => array('default' =>'0'),
    'lvl' => array('default' =>'0'),
  );

  function getrekening()
  {
    $param = $this->getParam($this->opt_rekening);
    $result = $this->pilih_model->getRekening($param);
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_REKENING'];
        $response->rows[$i]['cell'] = array(
          $result[$i]['ID_REKENING'],
          $result[$i]['ID_PARENT_REKENING'],
          $result[$i]['LEVEL_REKENING'],
          $result[$i]['KODE_REKENING'],
          $result[$i]['NAMA_REKENING'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function rekening()
  {
    $data['dialogname'] = 'rekening';
    $data['colsearch'] = array(
        'kdrek' => 'Kode Rekening',
        'nmrek' => 'Nama Rekening'
    );

    $data['param'] = $this->getParam($this->opt_rekening);
    $data['colnames'] = array('', '', '', 'Kode Rekening', 'Nama Rekening');
    $data['colmodel'] = array(
      array('name' => 'idrek', 'hidden' => true),
      array('name' => 'idp', 'hidden' => true),
      array('name' => 'lvl', 'hidden' => true),
      array('name' => 'kdrek', 'width' => '110', 'sortable' => $data['param']['tree'] ? false :true),
      array('name' => 'nmrek', 'width' => '550', 'sortable' => $data['param']['tree'] ? false :true),
    );
    $data['orderby'] = 'kdrek';

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/pilih_rekening/get'.$data['dialogname'],
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
	Pilih Rekening RKA
	*/
	var $opt_rekeningRKA = array(
		'multi' => array('default' =>0),
		'mode' => array('default' =>''),
		'id' => array('default' =>'0'),
		'id_skpd' => array('default' =>'0'),
		'tree' => array('default' =>'0'),
		'lvl' => array('default' =>'0'),
	);

	function getrekeningRKA()
	{
		$param = $this->getParam($this->opt_rekeningRKA);
		$result = $this->pilih_model->getRekeningRKA($param);
		$response = (object) NULL;
		$response->sql = $this->db->queries;
		if ($result){
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id'] = $result[$i]['ID_REKENING'];
				$response->rows[$i]['cell'] = array(
					$result[$i]['ID_REKENING'],
					$result[$i]['ID_PARENT_REKENING'],
					$result[$i]['LEVEL_REKENING'],
					$result[$i]['CHILD_COUNT'],
					$result[$i]['KODE_REKENING'],
					$result[$i]['NAMA_REKENING'],
				);
			}
		}
		$response->sql = $this->db->queries;
		echo json_encode($response);
	}

	function rekeningRKA()
	{
		$data['dialogname'] = 'rekeningRKA';
		$data['colsearch'] = array(
			'kdrek' => 'Kode Rekening',
			'nmrek' => 'Nama Rekening'
		);

		$data['param'] = $this->getParam($this->opt_rekeningRKA);
		$data['colnames'] = array('', '', '','', 'Kode Rekening', 'Nama Rekening');
		$data['colmodel'] = array(
			array('name' => 'idrek', 'hidden' => true),
			array('name' => 'idp', 'hidden' => true),
			array('name' => 'lvl', 'hidden' => true),
			array('name' => 'child', 'hidden' => true),
			array('name' => 'kdrek', 'width' => '110', 'sortable' => $data['param']['tree'] ? false :true),
			array('name' => 'nmrek', 'width' => '550', 'sortable' => $data['param']['tree'] ? false :true),
		);

		$response = (object) NULL;
		$response->html = $this->load->view('v_pilih', $data, true);
		$response->grid = array(
		'url' => base_url().'pilih/pilih_rekening/get'.$data['dialogname'],
		'pager' => '#pgrDialog'.$data['dialogname'],
		'multiselect' => $data['param']['multi'],
		'colNames' => $data['colnames'],
		'colModel' => $data['colmodel'],
		'postData' => $data['param'],
		);

		echo json_encode($response);
	}
	
	function getrekeningSPJ()
	{
		$param 		= $this->getParam($this->opt_rekening);
		$result 	= $this->pilih_model->getRekeningSPJ($param);
		$response 	= (object) NULL;
		$response->sql = $this->db->queries;
		if ($result){
			$x = 0;
			for($i=0; $i<count($result); $i++){
				//if((floatval($result[$i]['NOMINAL_RKA']) - floatval($result[$i]['NOMINAL_SPP']) - floatval($result[$i]['NOMINAL_SPJ'])) > 0){
				if($result[$i]['NOMINAL_RKA'] > 0){
					$response->rows[$x]['id'] = $result[$i]['ID_REKENING'];
					$response->rows[$x]['cell'] = array(
						$result[$i]['ID_REKENING'],
						$result[$i]['ID_PARENT_REKENING'],
						$result[$i]['LEVEL_REKENING'],
						$result[$i]['KODE_REKENING'],
						$result[$i]['NAMA_REKENING'],
						($result[$i]['NOMINAL_RKA']-$result[$i]['NOMINAL_SPP']-$result[$i]['NOMINAL_SPJ'])
					);
					$x+=1;
				}
			}
			//print_r($response);
		}
		$response->sql = $this->db->queries;
		echo json_encode($response);
	}

	function rekeningSPJ()
	{
		$data['dialogname'] = 'rekeningSPJ';
		$data['colsearch'] = array(
			'kdrek' => 'Kode Rekening',
			'nmrek' => 'Nama Rekening'
		);

		$data['param'] = $this->getParam($this->opt_rekening);
		$data['colnames'] = array('', '', '', 'Kode Rekening', 'Nama Rekening', 'Nominal');
		$data['colmodel'] = array(
			array('name' => 'idrek', 'hidden' => true),
			array('name' => 'idp', 'hidden' => true),
			array('name' => 'lvl', 'hidden' => true),
			array('name' => 'kdrek', 'width' => '110', 'sortable' => $data['param']['tree'] ? false :true),
			array('name' => 'nmrek', 'width' => '550', 'sortable' => $data['param']['tree'] ? false :true),
			array('name' => 'nom', 'width' => '150', 'sortable' => true, 'formatter' => 'currency', 'align' => 'right', 'hidden' => true),
		);
		$data['orderby'] = 'kdrek';

		$response = (object) NULL;
		$response->html = $this->load->view('v_pilih', $data, true);
		$response->grid = array(
			'url' => base_url().'pilih/pilih_rekening/get'.$data['dialogname'],
			'pager' => '#pgrDialog'.$data['dialogname'],
			'sortname' => $data['orderby'],
			'multiselect' => $data['param']['multi'],
			'colNames' => $data['colnames'],
			'colModel' => $data['colmodel'],
			'postData' => $data['param'],
		);

		echo json_encode($response);
	}
	
	function getrekeningUraian()
	{
		$param 		= $this->getParam($this->opt_rekening);
		$result 	= $this->pilih_model->getRekeningUraian($param);
		$response 	= (object) NULL;
		$response->sql = $this->db->queries;
		if ($result){
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id'] = $result[$i]['ID_RINCIAN_ANGGARAN'];
				$response->rows[$i]['cell'] = array(
					$result[$i]['ID_RINCIAN_ANGGARAN'],
					$result[$i]['ID_REKENING'],
					$result[$i]['ID_PARENT_REKENING'],
					$result[$i]['LEVEL_REKENING'],
					$result[$i]['KODE_REKENING'],
					$result[$i]['URAIAN'],
					$result[$i]['PAGU']
				);
			}
		}
		$response->sql = $this->db->queries;
		echo json_encode($response);
	}

	function rekeningUraian()
	{
		$data['dialogname'] = 'rekeningUraian';
		$data['colsearch'] = array(
			'kode_rekening' => 'Kode Rekening',
			'nmrek' => 'Uraian'
		);

		$data['param'] = $this->getParam($this->opt_rekening);
		$data['colnames'] = array('', '', '', '', 'Kode Rekening', 'Uraian', 'Pagu');
		$data['colmodel'] = array(
			array('name' => 'idra', 'hidden' => true),
			array('name' => 'idrek', 'hidden' => true),
			array('name' => 'idp', 'hidden' => true),
			array('name' => 'lvl', 'hidden' => true),
			array('name' => 'kdrek', 'width' => '110', 'sortable' => $data['param']['tree'] ? false :true),
			array('name' => 'nmrek', 'width' => '550', 'sortable' => $data['param']['tree'] ? false :true),
			array('name' => 'pagu', 'width' => '150', 'sortable' => true, 'formatter' => 'currency', 'align' => 'right', 'hidden' => false),
		);
		$data['orderby'] = 'kdrek';

		$response = (object) NULL;
		$response->html = $this->load->view('v_pilih', $data, true);
		$response->grid = array(
			'url' => base_url().'pilih/pilih_rekening/get'.$data['dialogname'],
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