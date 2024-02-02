<?php

class Pilih extends Pilih_Controller{
  public function __construct()
  {
    parent:: __construct();
    $this->load->model('pilih_dasar_model', 'pilih_model');
  }

  function index()
  {
  }

  /*
    Pilih pejabat_daerah
    Keterangan : mengembalikan daftar pejabat daerah
    Digunakan untuk komponen select2
  */

  function pejabat_daerah()
  {
    $q = $this->input->post('q');
    $skpd = $this->input->post('skpd');

    $req_param = array(
      "skpd" => $skpd,
      "q" => $q,
    );
    $result = $this->pilih_model->getPejabatDaerahSelect($req_param);
    $response = (object) NULL;
    $response->results = array();
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->results[] = array(
          'id' => $result[$i]['ID_PEJABAT_DAERAH'],
          'text' => $result[$i]['NAMA_PEJABAT'],
          'jabatan' => $result[$i]['JABATAN'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }
  
  /*
    Pilih pejabat_penguji
    Keterangan : mengembalikan daftar pejabat penguji
    Digunakan untuk komponen select2
  */

  function pejabat_penguji()
  {
    $q = $this->input->post('q');
    $skpd = $this->input->post('skpd');

    $req_param = array(
      "skpd" => $skpd,
      "q" => $q,
    );
    $result = $this->pilih_model->getPejabatPengujiSelect($req_param);
    $response = (object) NULL;
    $response->results = array();
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->results[] = array(
          'id' => $result[$i]['ID_PEJABAT_PENGUJI'],
          'text' => $result[$i]['NAMA_PEJABAT'],
          'nip' => $result[$i]['NIP'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  /*
    Pilih pejabat_skpd
    Keterangan : mengembalikan daftar pejabat skpd, difilter sesuai SKPD, Kode Jabatan
    Digunakan untuk komponen select2
  */

  function pejabat_skpd()
  {
    $q = $this->input->post('q');
    $skpd = $this->input->post('skpd');
    $kode = $this->input->post('kode');

    $req_param = array(
      "skpd" => $skpd,
      "kode" => $kode,
      "q" => $q,
    );
    $result = $this->pilih_model->getPejabatSKPDSelect($req_param);
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    $response->results = array();
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->results[] = array(
          'id' => $result[$i]['ID_PEJABAT_SKPD'],
          'text' => $result[$i]['NAMA_PEJABAT'],
          'jabatan' => $result[$i]['JABATAN'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }
  

  /*
    Pilih penyetor
    Keterangan : mengembalikan daftar penyetor di TBP
    Digunakan untuk komponen select2
  */

  function penyetor()
  {
    $q = $this->input->post('q');

    $req_param = array(
      "q" => $q,
    );
    $result = $this->pilih_model->getPenyetorSelect($req_param);
    $response = (object) NULL;
    $response->results = array();
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->results[] = array(
          'id' => $result[$i]['NAMA_PENYETOR'],
          'text' => $result[$i]['NAMA_PENYETOR'],
          'alamat' => $result[$i]['ALAMAT_PENYETOR'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  /*
    Pilih wp
    Keterangan : mengembalikan daftar wajib pajak  di SKPD/SKRD
    Digunakan untuk komponen select2
  */

  function wp()
  {
    $q = $this->input->post('q');

    $req_param = array(
      "q" => $q,
    );
    $result = $this->pilih_model->getWPSelect($req_param);
    $response = (object) NULL;
    $response->results = array();
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->results[] = array(
          'id' => $result[$i]['NAMA_WP'],
          'text' => $result[$i]['NAMA_WP'],
          'alamat' => $result[$i]['ALAMAT_WP'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  /*
    Pilih rekening_pptk_def
    Keterangan : mengembalikan rekening PPTK default sesuai kegiatan
  */

  function rekening_pptk_def()
  {
    $kegiatan = $this->input->post('id_kegiatan');

    $req_param = array(
      "kegiatan" => $kegiatan,
    );
    $result = $this->pilih_model->getRekeningPPTKDef($req_param);
    $response = (object) NULL;
    $response->results = array();
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->results[] = array(
          'idrek' => $result[$i]['ID_REKENING'],
          'kdrek' => $result[$i]['KODE_REKENING'],
          'nmrek' => $result[$i]['NAMA_REKENING'],
          'idpptk' => $result[$i]['ID_PEJABAT_SKPD'],
          'nmpptk' => $result[$i]['NAMA_PEJABAT'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  /*
    Pilih sumberdana_def
    Keterangan : mengembalikan sumber dana default sesuai SKPD
  */

  function sumberdana_def()
  {
    $skpd = $this->input->post('skpd');

    $req_param = array(
      "skpd" => $skpd,
    );
    $result = $this->pilih_model->getSumberdanaDef($req_param);
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    $response->results = array();
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->results[] = array(
          'id' => $result[$i]['ID_SUMBER_DANA'],
          'nama' => $result[$i]['NAMA_SUMBER_DANA'],
          'idrek' => $result[$i]['ID_REKENING'],
          'kdrek' => $result[$i]['KODE_REKENING'],
          'nmrek' => $result[$i]['NAMA_REKENING'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  /*
    Pilih sumberdana_skpd_def
    Keterangan : mengembalikan sumber dana skpd default sesuai SKPD
  */

  function sumberdana_skpd_def()
  {
    $skpd = $this->input->post('skpd');

    $req_param = array(
      "skpd" => $skpd,
    );
    $result = $this->pilih_model->getSumberdanaSKPDDef($req_param);
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    $response->results = array();
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->results[] = array(
          'idrek' => $result[$i]['ID_REKENING'],
          'kdrek' => $result[$i]['KODE_REKENING'],
          'nmrek' => $result[$i]['NAMA_REKENING'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  /*
    Pilih sumberdana
    Keterangan : mengembalikan daftar sumber dana
  */
  var $opt_sd = array(
    'multi' => array('default' =>0),
  );

  function getsumberdana()
  {
    $param = $this->getParam($this->opt_sd);
    $result = $this->pilih_model->getSumberdana($param);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_SUMBER_DANA'];
        $response->rows[$i]['cell'] = array(
          $result[$i]['ID_SUMBER_DANA'],
          $result[$i]['ID_REKENING'],
          $result[$i]['NAMA_SUMBER_DANA'],
          $result[$i]['NAMA_BANK'],
          $result[$i]['NO_REKENING_BANK'],
          $result[$i]['KODE_REKENING'],
          $result[$i]['NAMA_REKENING'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function sumberdana()
  {
    $data['dialogname'] = 'sumberdana';
    $data['colsearch'] = array(
        'nama' => 'Nama Sumberdana',
        'bank' => 'Nama Bank',
        'norek' => 'No Rekening Bank',
        'kdrek' => 'Kode Rekening',
        'nmrek' => 'Nama Rekening'
    );
    $data['colnames'] = array('', '', 'Nama Sumberdana', 'Nama Bank', 'No Rekening Bank', 'Kode Rekening', 'Nama Rekening');
    $data['colmodel'] = array(
      array('name' => 'id', 'hidden' => true),
      array('name' => 'idrek', 'hidden' => true),
      array('name' => 'nama', 'width' => 300, 'sortable' => true),
      array('name' => 'bank', 'width' => 200, 'sortable' => true),
      array('name' => 'norek', 'width' => 150, 'sortable' => true),
      array('name' => 'kdrek','width' => 100, 'sortable' => true),
      array('name' => 'nmrek','width' => 200, 'sortable' => true),
    );
    $data['orderby'] = 'nama';
    $data['param'] = $this->getParam($this->opt_sd);

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/get'.$data['dialogname'],
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
    Pilih sumberdanaskpd
    Keterangan : mengembalikan daftar sumber dana skpd
  */

  var $opt_sdskpd = array(
    'multi' => array('default' =>0),
    'id_skpd' => array('default' =>'0'),
  );

  function getsumberdanaskpd()
  {
    $param = $this->getParam($this->opt_sdskpd);
    $result = $this->pilih_model->getSDSKPD($param);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_SUMBER_DANA_SKPD'];
        $response->rows[$i]['cell'] = array(
          $result[$i]['ID_SUMBER_DANA_SKPD'],
          $result[$i]['ID_REKENING'],
          $result[$i]['NAMA_SUMBER_DANA'],
          $result[$i]['KODE_REKENING'],
          $result[$i]['NAMA_REKENING'],
          $result[$i]['NAMA_BANK'],
          $result[$i]['NO_REKENING_BANK'],

        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function sumberdanaskpd()
  {
    $data['dialogname'] = 'sumberdanaskpd';
    $data['colsearch'] = array(
        'nama' => 'Nama Rekening Bendahara',
        'bank' => 'Nama Bank',
        'norek' => 'No Rekening Bank',
        'kdrek' => 'Kode Rekening',
        'nmrek' => 'Nama Rekening'
    );
    $data['colnames'] = array('', '', 'Nama Rekening Bendahara', 'Kode Rekening', 'Nama Rekening', 'Nama Bank', 'No Rekening Bank');
    $data['colmodel'] = array(
      array('name' => 'id', 'hidden' => true),
      array('name' => 'idrek', 'hidden' => true),
      array('name' => 'nama', 'width' => '300', 'sortable' => true),
      array('name' => 'kdrek', 'width' => '100', 'sortable' => true),
      array('name' => 'nmrek', 'width' => '200', 'sortable' => true),
      array('name' => 'bank', 'width' => '200', 'sortable' => true),
      array('name' => 'norek', 'width' => '150', 'sortable' => true),
    );
    $data['orderby'] = 'nama';
    $data['param'] = $this->getParam($this->opt_sdskpd);

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/get'.$data['dialogname'],
      'pager' => '#pgrDialog'.$data['dialogname'],
      'sortname' => $data['orderby'],
      'multiselect' => $data['param']['multi'],
      'colNames' => $data['colnames'],
      'colModel' => $data['colmodel'],
      'postData' => $data['param'],
    );
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  /*
    Pilih pajak
    Keterangan : mengembalikan daftar pajak
  */

  var $opt_pajak = array(
    'multi' => array('default' =>0),
    'mode' => array('default' =>''),
    'tanggal' => array('default' =>''),
  );

  function getpajak()
  {
    $param = $this->getParam($this->opt_pajak);
    $result = $this->pilih_model->getPajak($param);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_PAJAK'];
        $response->rows[$i]['cell'] = array(
          $result[$i]['ID_PAJAK'],
          $result[$i]['ID_REKENING'],
          $result[$i]['KODE_PAJAK'],
          $result[$i]['NAMA_PAJAK'],
          $result[$i]['PERSEN'],
          $result[$i]['KODE_REKENING'],
          $result[$i]['NAMA_REKENING'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function pajak()
  {
    $data['dialogname'] = 'pajak';
    $data['colsearch'] = array(
        'kode' => 'Kode Pajak',
        'nama' => 'Nama Pajak',
        'persen' => 'Persen',
        'kdrek' => 'Kode Rekening',
        'nmrek' => 'Nama Rekening'
    );
    $data['colnames'] = array('', '', 'Kode Pajak', 'Nama Pajak', 'Persen', 'Kode Rekening', 'Nama Rekening');
    $data['colmodel'] = array(
      array('name' => 'id', 'hidden' => true),
      array('name' => 'idrek', 'hidden' => true),
      array('name' => 'kode', 'width' => '100', 'sortable' => true),
      array('name' => 'nama', 'width' => '300', 'sortable' => true),
      array('name' => 'persen', 'width' => '100', 'sortable' => true, 'align' => 'right', 'formatter' => 'currency'),
      array('name' => 'kdrek', 'width' => '120', 'sortable' => true),
      array('name' => 'nmrek', 'width' => '300', 'sortable' => true),
    );
    $data['orderby'] = 'kode';
    $data['param'] = $this->getParam($this->opt_pajak);

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/get'.$data['dialogname'],
      'pager' => '#pgrDialog'.$data['dialogname'],
      'sortname' => $data['orderby'],
      'multiselect' => $data['param']['multi'],
      'colNames' => $data['colnames'],
      'colModel' => $data['colmodel'],
      'postData' => $data['param'],
    );
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  /*
    Pilih potongan
    Keterangan : mengembalikan daftar potongan
  */

  var $opt_pfk = array(
    'multi' => array('default' =>0),
    'mode' => array('default' =>''),
    'tanggal' => array('default' =>''),
  );

  function getpotongan()
  {
    $param = $this->getParam($this->opt_pfk);
    $result = $this->pilih_model->getPotongan($param);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_REKENING'];
        $response->rows[$i]['cell'] = array(
          $result[$i]['ID_REKENING'],
          $result[$i]['KODE_REKENING'],
          $result[$i]['NAMA_REKENING'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function potongan()
  {
    $data['dialogname'] = 'potongan';
    $data['colsearch'] = array(
        'kode' => 'Kode Rekening',
        'nama' => 'Nama Rekening'
    );
    $data['colnames'] = array('', 'Kode Rekening', 'Nama Rekening');
    $data['colmodel'] = array(
      array('name' => 'id', 'hidden' => true),
      array('name' => 'kode', 'width' => '120', 'sortable' => true),
      array('name' => 'nama', 'width' => '300', 'sortable' => true),
    );
    $data['orderby'] = 'kode';
    $data['param'] = $this->getParam($this->opt_pfk);

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/get'.$data['dialogname'],
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
    Pilih kontrak
    Keterangan : mengembalikan daftar kontrak
  */

  var $opt_kontrak = array(
    'multi' => array('default' =>0),
  );

  function getkontrak()
  {
    $param = $this->getParam($this->opt_kontrak);
    $result = $this->pilih_model->getKontrak($param);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['NO_KONTRAK'];
        $response->rows[$i]['cell'] = array(
          $result[$i]['NO_KONTRAK'],
          $result[$i]['NAMA_PERUSAHAAN'],
          $result[$i]['NAMA_PIMPINAN'],
          $result[$i]['NAMA_BANK'],
          $result[$i]['NO_REKENING_BANK'],
          $result[$i]['NPWP'],
          $result[$i]['ALAMAT_PERUSAHAAN'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function kontrak()
  {
    $data['dialogname'] = 'kontrak';
    $data['colsearch'] = array(
        'no' => 'Nomor Kontrak',
        'perusahaan' => 'Nama Perusahaan',
        'pimpinan' => 'Nama Pimpinan',
        'bank' => 'Nama Bank',
        'norek' => 'No. Rekening',
        'alamat' => 'Alamat',
        'npwp' => 'NPWP'
    );
    $data['colnames'] = array('Nomor Kontrak', 'Nama Perusahaan', 'Nama Pimpinan', 'Nama Bank', 'No. Rekening', 'NPWP', 'Alamat');
    $data['colmodel'] = array(
      array('name' => 'no', 'width' => '100', 'sortable' => true),
      array('name' => 'perusahaan', 'width' => '200', 'sortable' => true),
      array('name' => 'pimpinan', 'width' => '200', 'sortable' => true),
      array('name' => 'bank', 'width' => '200', 'sortable' => true),
      array('name' => 'norek', 'width' => '130', 'sortable' => true),
      array('name' => 'npwp', 'width' => '130', 'sortable' => true),
      array('name' => 'alamat', 'width' => '200', 'sortable' => true),
    );
    $data['orderby'] = 'no';
    $data['param'] = $this->getParam($this->opt_kontrak);

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/get'.$data['dialogname'],
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
	Pilih SPJ di from SPP
	Keterangan : mengembalikan daftar SPJ di from SPP
	*/
  
  	var $opt_spjspp = array(
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

	function getspjspp()
	{
		$param = $this->getParam($this->opt_spjspp);
		$result = $this->pilih_model->getSPJSPP($param);
		$response = (object) NULL;
		$response->sql = $this->db->queries;
		if ($result){
			$x = 0;
			for($i=0; $i<count($result); $i++){
				if($result[$i]['NOMINAL'] > $result[$i]['TOTAL_SPP']){
					$response->rows[$x]['id'] = $result[$i]['ID_AKTIVITAS'];
					$response->rows[$x]['cell'] = array(
						$result[$i]['ID_AKTIVITAS'],
						$result[$i]['NOMOR'],
					);
					$x+=1;
				}
			}
		}
		$response->sql = $this->db->queries;
		echo json_encode($response);
	}

	function spjspp()
	{
		$data['dialogname'] = 'spjspp';
		$data['colsearch'] = array(
			'nomor' => 'Nomor SPJ'
		);

		$data['param'] = $this->getParam($this->opt_spjspp);
		$data['colnames'] = array('', 'Nomor SPJ');
		$data['colmodel'] = array(
			array('name' => 'id_aktivitas', 'hidden' => true),
			array('name' => 'nomor', 'width' => '250', 'sortable' => true),
		);
		$data['orderby'] = 'nomor';

		$response = (object) NULL;
		$response->html = $this->load->view('v_pilih', $data, true);
		$response->grid = array(
			'url' => base_url().'pilih/get'.$data['dialogname'],
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
    Pilih skpd
    Keterangan : mengembalikan daftar skpd
  */

  var $opt_skpd = array(
    'multi' => array('default' =>0),
    'mode' => array('default' => '')
  );

  function getskpd()
  {
    $param = $this->getParam($this->opt_skpd);
    $result = $this->pilih_model->getSKPD($param);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_SKPD'];
        $response->rows[$i]['cell'] = array(
          $result[$i]['ID_SKPD'],
          $result[$i]['KODE_SKPD_LKP'],
          $result[$i]['NAMA_SKPD'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function skpd()
  {
    $data['dialogname'] = 'skpd';
    $data['colsearch'] = array('kode' => 'Kode SKPD', 'nama' => 'Nama SKPD');
    $data['colnames'] = array('', 'Kode SKPD', 'Nama SKPD');
    $data['colmodel'] = array(
      array('name' => 'id', 'hidden' => true),
      array('name' => 'kode', 'width' => '100', 'sortable' => true),
      array('name' => 'nama', 'width' => '560', 'sortable' => true),
    );
    $data['orderby'] = 'kode';
    $data['param'] = $this->getParam($this->opt_skpd);

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/get'.$data['dialogname'],
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
    Pilih Pejabat Daerah
    Keterangan : mengembalikan daftar pejabat daerah
  */
  var $opt_pejabatdaerah = array(
    'multi' => array('default' =>0),
  );

  function getpejabatdaerah()
  {
    $param = $this->getParam($this->opt_pejabatdaerah);
    $result = $this->pilih_model->getPejabatdaerah($param);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_PEJABAT_DAERAH'];
        $response->rows[$i]['cell'] = array(
          $result[$i]['ID_PEJABAT_DAERAH'],
          $result[$i]['JABATAN'],
          $result[$i]['NAMA_PEJABAT'],
          $result[$i]['NIP'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function pejabatdaerah()
  {
    $data['dialogname'] = 'pejabatdaerah';
    $data['colsearch'] = array(
        'jabat' => 'Jabatan',
        'nama' => 'Nama Pejabat',
        'nip' => 'NIP',
    );
    $data['colnames'] = array('', 'Jabatan', 'Nama Pejabat', 'NIP');
    $data['colmodel'] = array(
      array('name' => 'id', 'hidden' => true),
      array('name' => 'jabat', 'width' => 200, 'sortable' => true),
      array('name' => 'nama', 'width' => 400, 'sortable' => true),
      array('name' => 'nip', 'width' => 150, 'sortable' => true),
    );
    $data['orderby'] = 'nama';
    $data['param'] = $this->getParam($this->opt_pejabatdaerah);

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/get'.$data['dialogname'],
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
    Pilih Pejabat SKPD
    Keterangan : mengembalikan daftar pejabat skpd
  */
  var $opt_pejabatskpd = array(
    'multi' => array('default' =>0),
    'id_skpd' => array('default' => 0),
  );

  function getpejabatskpd()
  {
    $param = $this->getParam($this->opt_pejabatskpd);
    $result = $this->pilih_model->getPejabatskpd($param);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_PEJABAT_SKPD'];
        $response->rows[$i]['cell'] = array(
          $result[$i]['ID_PEJABAT_SKPD'],
          $result[$i]['JABATAN'],
          $result[$i]['NAMA_PEJABAT'],
          $result[$i]['NIP'],
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function pejabatskpd()
  {
    $data['dialogname'] = 'pejabatskpd';
    $data['colsearch'] = array(
        'jabat' => 'Jabatan',
        'nama' => 'Nama Pejabat',
        'nip' => 'NIP',
    );
    $data['colnames'] = array('', 'Jabatan', 'Nama Pejabat', 'NIP');
    $data['colmodel'] = array(
      array('name' => 'id', 'hidden' => true),
      array('name' => 'jabat', 'width' => 200, 'sortable' => true),
      array('name' => 'nama', 'width' => 400, 'sortable' => true),
      array('name' => 'nip', 'width' => 150, 'sortable' => true),
    );
    $data['orderby'] = 'nama';
    $data['param'] = $this->getParam($this->opt_pejabatskpd);

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/get'.$data['dialogname'],
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
    Pilih Pejabat Penguji
    Keterangan : mengembalikan daftar Penguji
  */
  var $opt_pejabatpenguji = array(
    'multi' => array('default' =>0),
    'id_skpd' => array('default' => 0),
  );

  function getpejabatpenguji()
  {
    $param = $this->getParam($this->opt_pejabatpenguji);
    $result = $this->pilih_model->getPejabatpenguji($param);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_PEJABAT_PENGUJI'];
        $response->rows[$i]['cell'] = array(
          $result[$i]['ID_PEJABAT_PENGUJI'],
		  $result[$i]['NIP'],
          $result[$i]['NAMA_PEJABAT'],
          
        );
      }
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function pejabatpenguji()
  {
    $data['dialogname'] = 'pejabatpenguji';
    $data['colsearch'] = array(
        'jabat' => 'Jabatan',
        'nama' => 'Nama Pejabat',
        'nip' => 'NIP',
    );
    $data['colnames'] = array('', 'NIP', 'Nama Pejabat');
    $data['colmodel'] = array(
      array('name' => 'id', 'hidden' => true),
	  array('name' => 'nip', 'width' => 150, 'sortable' => true),
      array('name' => 'nama', 'width' => 400, 'sortable' => true),
      
    );
    $data['orderby'] = 'nama';
    $data['param'] = $this->getParam($this->opt_pejabatpenguji);

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/get'.$data['dialogname'],
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