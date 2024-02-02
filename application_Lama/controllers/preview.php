<?php

class Preview extends CI_Controller{

  var $path2engine;
  var $path2fr3;
  var $path2cache;
  var $path2output;
  var $attachment;

  public function __construct()
  {
    parent:: __construct();
    $this->load->helper('path');
    $this->path2engine = dirname( realpath(SELF) )."/assets/fr/";
    $this->path2fr3 = $this->path2engine . 'fr3/';
    $this->path2cache = $this->path2engine . 'cache/';
    $this->path2output = $this->path2engine . 'output/';
    $this->attachment = 1; // 0 : show in the browser;  1 : download file
  }

  function index()
  {

  }

  private function start($param)
  {
    $this->load->helper('file');

    $data = 'ReportName='. $param['fr3'] ."\n";
    $data .= 'OutputType='. $param['format'] ."\n";
    $data .= 'DriverName='.$this->db->dbdriver."\n";
    $data .= 'Connection=DB Express'."\n";
    $data .= 'DBPort='.$this->db->port."\n";
    $data .= 'DBServer='.$this->db->hostname."\n";
    $data .= 'DBName='.$this->db->database."\n";
    $data .= 'DBUser='. $this->db->username."\n";
    $data .= 'DBPassword='. $this->db->password."\n";

    if (isset($param['opt'])) {
      foreach($param['opt'] as $value){
        $data.=$value."\n";
      }
    }

    $in = tempnam($this->path2cache, "in");
    write_file($in, $data);
    $out = basename( $in, '.tmp');
    $outfile = $this->path2output.$out;

    if( PHP_OS == 'WIN32' || PHP_OS == 'WINNT')
    {
      exec($this->path2engine.'RepEngine.exe "'.$in.'" "'.$outfile.'"');
    }
    else
    {
      exec('DISPLAY=:100 wine '.$this->path2engine.'RepEngine.exe "'.$in.'" "'.$outfile.'" 2>'.$this->path2engine.'RepEngine.log');
    }

    $response = (object) NULL;
    if (file_exists($outfile.".ok"))
    {
      $response->isSuccessful = TRUE;
      $response->id = $out;
      $response->nama = rawurlencode($param['nama']);
    }
    else
    {
      $response->isSuccessful = FALSE;
      $response->message = 'Laporan gagal dibuat.';
    }
    echo json_encode($response);
  }

  public function view($key = '', $reportname = '')
  {
    $this->load->helper('file');

    $ext = "";
    if( PHP_OS == 'WIN32' || PHP_OS == 'WINNT') $ext = ".tmp";

    if (!file_exists($this->path2cache.$key.$ext)) {
      $this->output->set_status_header(500);
      return;
    }

    $config = file($this->path2cache.$key.$ext);
    $filetype = explode('=', $config[1]);
    $filetype = preg_replace("/[\n\r]/","", $filetype[1]);
	$filetype = $filetype == 'rtf' ? 'doc' : $filetype;

    $filename = $key. "." .$filetype;
    $fileerr = $key.".err";

    if (file_exists($this->path2output.$filename)) {
      $outfile = $reportname. "." .$filetype;

      header('Content-Disposition: ' . ($this->attachment == 1 ? 'attachment; ' : '') .'filename="'.$outfile.'"');
      switch ($filetype){
        case 'pdf' :
          header('Content-Type: application/pdf');
          break;
        case 'xls' :
          header('Content-Type: application/xls');
          break;
        case 'xlsx' :
          header('Content-Type: application/xls');
          break;
        case 'doc' :
          header('Content-Type: application/doc');
          break;
        case 'docx' :
          header('Content-Type: application/doc');
          break;
        case 'odt' :
          header('Content-Type: application/odt');
          break;
        case 'ods' :
          header('Content-Type: application/odf');
          break;
      }
      header('Content-Length: '.filesize($this->path2output.$filename));
      header('Cache-Control: no-store');
      readfile($this->path2output.$filename);
    }
    elseif (file_exists($this->path2output.$fileerr)) {
      $this->output->set_status_header(500);
    }
  }

  function sts()
  {
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';

    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\Rpt_Daftar_STS.fr3';
          $param['nama'] = 'Daftar_STS';
          $param['format'] = $format;
          // ambil query daftar
          $this->load->model('sts/sts_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;
      case 'form' :
          $param['fr3'] = 'Shared\Rpt_Form_STS.fr3';
          $param['nama'] = 'Form_STS';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id
          );
          break;
    }
    $this->start($param);
  }
  
  
  function memo_penyesuaian()
  {
  
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\Rpt_Daftar_MP.fr3';
          $param['nama'] = 'Daftar_MP';
          $param['format'] = $format;
          // ambil query daftar
          $this->load->model('memo_penyesuaian/memo_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE); //var_dump($query_str); die();
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;
      case 'form' :
          $param['fr3'] = 'Shared\Rpt_Form_MP.fr3';
          $param['nama'] = 'Form_MP';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id
          );
          break;
    }
    $this->start($param);
  }

  function kontra_pos(){
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';

    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\Rpt_Daftar_CP.fr3';
          $param['nama'] = 'Daftar Kontra Pos';
          $param['format'] = $format;
          // ambil query daftar
          $this->load->model('kontra_pos/kontra_pos_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;
      case 'form' :
          $param['fr3'] = 'Shared\Rpt_Form_CP.fr3';
          $param['nama'] = 'Form Kontra Pos';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id
          );
          break;
    }
    $this->start($param);
  }

  function setoran_sisa(){
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';

    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\Rpt_Daftar_SSU.fr3';
          $param['nama'] = 'Daftar Setoran Sisa';
          $param['format'] = $format;
          // ambil query daftar
          $this->load->model('setoran_sisa/setoran_sisa_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;
      case 'form' :
          $param['fr3'] = 'Shared\Rpt_Form_SSU.fr3';
          $param['nama'] = 'Form Setoran Sisa';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id
          );
          break;
    }
    $this->start($param);
  }

  function spd(){
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
    $m = $this->input->post('m') ? $this->input->post('m') : '';
    $q = $this->input->post('q') ? $this->input->post('q') : '';

    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\Rpt_Daftar_SPD.fr3';
          $param['nama'] = 'Daftar SPD';
          $param['format'] = $format;
          $param['m'] = $m;
          $param['q'] = $q;
          // ambil query daftar
          $this->load->model('spd/spd_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;
      case 'form' :
          $param['fr3'] = 'Shared\Rpt_Form_SPD.fr3';
          $param['nama'] = 'Form SPD';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id,"tahun=".$this->session->userdata('tahun'),"status=".$this->session->userdata('status')
          );
          break;
    }
    $this->start($param);
  }

  function spp(){
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
	$m 		= $this->input->post('m') ? $this->input->post('m') : '';
	$q 		= $this->input->post('q') ? $this->input->post('q') : '';
	
	$keperluan = $this->input->post('keperluan') ? $this->input->post('keperluan') : '';
	$jenis_beban = $this->input->post('jenis_beban') ? $this->input->post('jenis_beban') : '';

    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\Rpt_Daftar_SPP.fr3';
          $param['nama'] = 'Daftar SPP';
          $param['format'] = $format;
		  $param['q'] = $q;
		  $param['m'] = $m;
          // ambil query daftar
          $this->load->model('spp/spp_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;
      case 'form' :
          $param['fr3'] = 'Shared\Rpt_Form_SPP.fr3';
          $param['nama'] = 'Form SPP';
          $param['format'] = $format;
          $param['opt'] = array(
            "id_aktivitas=".$id,"tahun=".$this->session->userdata('tahun'),"status=".$this->session->userdata('status'),"keperluan=".$keperluan,"beban=".$jenis_beban
          );
          break;
    }
    $this->start($param);
  }

  function spm(){
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
	$m 		= $this->input->post('m') ? $this->input->post('m') : '';
	$q 		= $this->input->post('q') ? $this->input->post('q') : '';
    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\Rpt_Daftar_SPM.fr3';
          $param['nama'] = 'Daftar SPM';
          $param['format'] = $format;
		  $param['q'] = $q;
		  $param['m'] = $m;
          // ambil query daftar
          $this->load->model('spm/spm_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;
      case 'form' :
          $param['fr3'] = 'Shared\Rpt_Form_SPM.fr3';
          $param['nama'] = 'Form SPM';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id
          );
          break;
    }
    $this->start($param);
  }

  function sp2d(){
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
	$m 		= $this->input->post('m') ? $this->input->post('m') : '';
	$q 		= $this->input->post('q') ? $this->input->post('q') : '';
	
    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\Rpt_Daftar_SP2D.fr3';
          $param['nama'] = 'Daftar SP2D';
          $param['format'] = $format;
		  $param['q'] = $q;
		  $param['m'] = $m;
          // ambil query daftar
          $this->load->model('sp2d/sp2d_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
			"SQLFilter=".$query_str
			);
          break;
          
      case 'form' :
          $param['fr3'] =  'Shared\Rpt_Form_SP2D.fr3';
          $param['nama'] = 'Form SP2D';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id
          );
          break;
    }
    $this->start($param);
  }

  function setoran_pfk(){
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';

    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\Rpt_Daftar_SPFK.fr3';
          $param['nama'] = 'Daftar Setoran PFK';
          $param['format'] = $format;
          // ambil query daftar
          $this->load->model('setoran_pfk/setoran_pfk_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;
      case 'form' :
          $param['fr3'] = 'Shared\Rpt_Form_SPFK.fr3';
          $param['nama'] = 'Form Setoran PFK';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id
          );
          break;
    }
    $this->start($param);
  }

  function setoran_pajak(){
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';

    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\Rpt_Daftar_SPJK.fr3';
          $param['nama'] = 'Daftar Setoran Pajak';
          $param['format'] = $format;
          // ambil query daftar
          $this->load->model('setoran_pajak/setoran_pajak_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;
      case 'form' :
          $param['fr3'] = 'Shared\Rpt_Form_SPJK.fr3';
          $param['nama'] = 'Form Setoran Pajak';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id
          );
          break;
    }
    $this->start($param);
  }

  function daftar_penguji(){
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';

    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\Rpt_Daftar_DP.fr3';
          $param['nama'] = 'Daftar Daftar Penguji';
          $param['format'] = $format;
          // ambil query daftar
          $this->load->model('daftar_penguji/daftar_penguji_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;
      case 'form' :
          $param['fr3'] = 'Shared\Rpt_Form_DP.fr3';
          $param['nama'] = 'Form Daftar Penguji';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id
          );
          break;
    }
    $this->start($param);
  }

  function spj(){
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
	$m 		= $this->input->post('m') ? $this->input->post('m') : '';
	$q 		= $this->input->post('q') ? $this->input->post('q') : '';
	
	$id_skpd = $this->session->userdata('id_skpd');
    $tahun = $this->session->userdata('tahun');
    $status = $this->session->userdata('status');

    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\Rpt_Daftar_SPJ.fr3';
          $param['nama'] = 'Daftar SPJ';
          $param['format'] = $format;
			$param['q'] = $q;
			$param['m'] = $m;
          // ambil query daftar
          $this->load->model('spj/spj_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
			$param['opt'] = array(
				"SQLFilter=".$query_str
			);
          break;
      case 'form' :
          $param['fr3'] = 'Shared\Rpt_Form_SPJ.fr3';
          $param['nama'] = 'Form SPJ';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id,"id_skpd=".$id_skpd,"tahun=".$tahun
          );
          break;
    }
    $this->start($param);
  }

  function spj_pendapatan(){
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';

    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\Rpt_Daftar_SPJP.fr3';
          $param['nama'] = 'Daftar SPJ Pendapatan';
          $param['format'] = $format;
          // ambil query daftar
          $this->load->model('spj_pendapatan/spj_pendapatan_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;
      case 'form' :
          $param['fr3'] = 'Shared\Rpt_Form_SPJP.fr3';
          $param['nama'] = 'Form SPJ Pendapatan';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id
          );
          break;
    }
    $this->start($param);
  }

  function bud(){
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';

    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\Rpt_Daftar_BKU.fr3';
          $param['nama'] = 'Daftar BUD';
          $param['format'] = $format;
          // ambil query daftar
          $this->load->model('bud/bud_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;
      case 'form' :
          $param['fr3'] = 'Shared\Rpt_Form_BKU.fr3';
          $param['nama'] = 'Form BUD';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id
          );
          break;
    }
    $this->start($param);
  }

	function proposal(){
		$tipe 	= $this->input->post('tipe') ? $this->input->post('tipe') : '';
		$id 	= $this->input->post('id') ? $this->input->post('id') : '0';
		$format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
		$m 		= $this->input->post('m') ? $this->input->post('m') : '';
		$q 		= $this->input->post('q') ? $this->input->post('q') : '';

		$nama_pejabat_skpd	= $this->input->post('nama') ? urldecode($this->input->post('nama')) : '';
		$jabatan = $this->input->post('jabatan') ? urldecode($this->input->post('jabatan')) : '';
		$nip = $this->input->post('nip') ? urldecode($this->input->post('nip')) : '';
		
		$id_skpd = $this->session->userdata('id_skpd');

		if ($tipe == '') return;

		switch ($tipe){
		  case 'daftar' :
			  $param['fr3'] = 'Shared\Rpt_Daftar_Proposal.fr3';
			  $param['nama'] = 'Daftar Proposal';
			  $param['format'] = $format;
				$param['q'] = $q;
				$param['m'] = $m;
			  // ambil query daftar
			  $this->load->model('proposal/proposal_model','data_model');
			  $query_str = $this->data_model->get_data($param, FALSE, TRUE);
			  $query_str = preg_replace("/[\n\r]/"," ", $query_str);		
			
				$param['opt'] = array(
				"SQLFilter=".$query_str
				);
			  break;
		  case 'form' :
			  $param['fr3'] = 'Shared\Rpt_Form_Proposal.fr3';
			  $param['nama'] = 'Form Proposal';
			  $param['format'] = $format;
			  $param['opt'] = array(
				"id=".$id,"nama_pejabat_skpd=".$nama_pejabat_skpd,"jabatan=".$jabatan,"nip=".$nip,"id_skpd=".$id_skpd
			  );
			  break;
		}
		$this->start($param);
	}

	function pengujian(){
		$tipe 	= $this->input->post('tipe') ? $this->input->post('tipe') : '';
		$id 	= $this->input->post('id') ? $this->input->post('id') : '0';
		$format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
		$m 		= $this->input->post('m') ? $this->input->post('m') : '';
		$q 		= $this->input->post('q') ? $this->input->post('q') : '';

		if ($tipe == '') return;

		switch ($tipe){
			case 'daftar' :
				$param['fr3'] = 'Shared\Rpt_Daftar_Pengujian.fr3';
				$param['nama'] = 'Daftar Pengujian';
				$param['format'] = $format;
				$param['q'] = $q;
				$param['m'] = $m;
				// ambil query daftar
				$this->load->model('pengujian/pengujian_model','data_model');
				$query_str = $this->data_model->get_data($param, FALSE, TRUE);
				$query_str = preg_replace("/[\n\r]/"," ", $query_str);
				$param['opt'] = array(
				"SQLFilter=".$query_str
				);
				break;
			case 'form' :
				$param['fr3'] = 'Shared\Rpt_Form_Pengujian.fr3';
				$param['nama'] = 'Form Pengujian';
				$param['format'] = $format;
				$param['opt'] = array(
				"id=".$id
				);
				break;
		}
		$this->start($param);
	}

  function disposisi(){
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
    $m = $this->input->post('m') ? $this->input->post('m') : '';
    $q = $this->input->post('q') ? $this->input->post('q') : '';

    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\Rpt_Daftar_Disposisi.fr3';
          $param['nama'] = 'Daftar Disposisi';
          $param['format'] = $format;
          $param['q'] = $q;
          $param['m'] = $m;
          // ambil query daftar
          $this->load->model('disposisi/disposisi_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;
      case 'form' :
          $param['fr3'] = 'Shared\Rpt_Form_Disposisi.fr3';
          $param['nama'] = 'Form Disposisi';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id
          );
          break;
    }
    $this->start($param);
  }

  function rka21(){
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
    $m = $this->input->post('m') ? $this->input->post('m') : '';
    $q = $this->input->post('q') ? $this->input->post('q') : '';

    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\DaftarRKA21.fr3';
          $param['nama'] = 'Daftar RKA 2.1';
          $param['format'] = $format;
          $param['q'] = $q;
          $param['m'] = $m;
          // ambil query daftar
          $this->load->model('rka21/rka21_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
           "tahun=".$this->session->userdata('tahun'),"status=".$this->session->userdata('status')
          );
          break;
      case 'form' :
          $param['fr3'] = 'Shared\RKA21.fr3';
          $param['nama'] = 'Form RKA 2.1';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id
          );
          break;
    }
    $this->start($param);
  }

  function rka221(){
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
    $m = $this->input->post('m') ? $this->input->post('m') : '';
    $q = $this->input->post('q') ? $this->input->post('q') : '';

    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\DaftarRKA221.fr3';
          $param['nama'] = 'Daftar RKA 2.2.1';
          $param['format'] = $format;
          $param['q'] = $q;
          $param['m'] = $m;
          // ambil query daftar
          $this->load->model('rka221/rka221_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str,
						"tahun=".$this->session->userdata('tahun'),"status=".$this->session->userdata('status')
          );
          break;
      case 'form' :
          $param['fr3'] = 'Shared\RKA221.fr3';
          $param['nama'] = 'Form RKA 2.2.1';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id
          );
          break;
    }
    $this->start($param);
  }

  function nphd(){
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
    $m = $this->input->post('m') ? $this->input->post('m') : '';
    $q = $this->input->post('q') ? $this->input->post('q') : '';
	$pejabat_nphd = $this->input->post('pejabat_nphd') ? $this->input->post('pejabat_nphd') : '';
    
    $tahun = $this->session->userdata('tahun');
    $status = $this->session->userdata('status');
    $id_skpd = $this->session->userdata('id_skpd');

    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\Rpt_Daftar_NPHD.fr3';
          $param['nama'] = 'Daftar Disposisi';
          $param['format'] = $format;
          $param['q'] = $q;
          $param['m'] = $m;
          // ambil query daftar
          $this->load->model('nphd/nphd_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str,
						"tahun=".$tahun
          );
          break;
      case 'form' :
          $param['fr3'] = 'Shared\Rpt_Form_NPHD.fr3';
          $param['nama'] = 'Form NPHD';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id,"id_skpd=".$id_skpd,"tahun=".$tahun,"status=".$status,"pejabat_nphd=".$pejabat_nphd
          );
          break;
    }
    $this->start($param);
  }
  
	function skpd(){
		$tipe 	= $this->input->post('tipe') ? $this->input->post('tipe') : '';
		$id 	= $this->input->post('id') ? $this->input->post('id') : '0';
		$format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
		$m 		= $this->input->post('m') ? $this->input->post('m') : '';
		$q 		= $this->input->post('q') ? $this->input->post('q') : '';

		if ($tipe == '') return;

		switch ($tipe){
			case 'daftar' :
				$param['fr3'] = 'Shared\DaftarSKPD.fr3';
				$param['nama'] = 'Daftar SKPD';
				$param['format'] = $format;
				$param['q'] = $q;
				$param['m'] = $m;
				// ambil query daftar
				$this->load->model('skpd/skpd_model','data_model');
				$query_str = $this->data_model->get_data($param, FALSE, TRUE);
				$query_str = preg_replace("/[\n\r]/"," ", $query_str);
				$param['opt'] = array(
				"SQLFilter=".$query_str
				);
				break;
			case 'form' :
				$param['fr3'] = 'Shared\DaftarSKPD.fr3';
				$param['nama'] = 'Form SKPD';
				$param['format'] = $format;
				$param['opt'] = array(
				"id=".$id
				);
				break;
		}
		$this->start($param);
	}
  
  function program(){
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
    $search = $this->input->post('search') ? $this->input->post('search') : '';
		$search_field = $this->input->post('searchField') ? $this->input->post('searchField') : null;
		$search_operator = $this->input->post('searchOper') ? $this->input->post('searchOper') : null;
		$search_str = $this->input->post('searchString') ? $this->input->post('searchString') : null;
    $sort_by = $this->input->post('sort_by') ? $this->input->post('sort_by') : '';

    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\DaftarProgram.fr3';
          $param['nama'] = 'Daftar Program';
          $param['format'] = $format;
          $param['search'] = $search;
          $param['search_field'] = $search_field;
          $param['search_operator'] = $search_operator;
          $param['search_str'] = $search_str;
          $param['sort_by'] = $sort_by;
          // ambil query daftar
          $this->load->model('program/program_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;
      case 'form' :
          $param['fr3'] = 'Shared\DaftarProgram.fr3';
          $param['nama'] = 'Form Program';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id
          );
          break;
    }
    $this->start($param);
  }
  
    function kegiatan(){

    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
    $search = $this->input->post('search') ? $this->input->post('search') : '';
    $search_field = $this->input->post('searchField') ? $this->input->post('searchField') : null;
		$search_operator = $this->input->post('searchOper') ? $this->input->post('searchOper') : null;
		$search_str = $this->input->post('searchString') ? $this->input->post('searchString') : null;
    $sort_by = $this->input->post('sort_by') ? $this->input->post('sort_by') : '';
    
    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
        $param['fr3'] = 'Shared\DaftarKegiatan.fr3';
        $param['nama'] = 'Daftar Kegiatan';
        $param['format'] = $format;
        $param['search'] = $search;
          $param['search_field'] = $search_field;
          $param['search_operator'] = $search_operator;
          $param['search_str'] = $search_str;
          $param['sort_by'] = $sort_by;
        // ambil query daftar
          $this->load->model('kegiatan/kegiatan_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;

      case 'form' :
        $param['fr3'] = 'Shared\Rpt_Form_Kegiatan.fr3';
        $param['nama'] = 'Form Kegiatan';
        $param['format'] = $format;
        $param['opt'] = array(
        "id=".$id
        );
        break;
    }
    $this->start($param);
  }
  
  function kategorirekening(){

    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
    $search = $this->input->post('search') ? $this->input->post('search') : '';
    $search_field = $this->input->post('searchField') ? $this->input->post('searchField') : null;
		$search_operator = $this->input->post('searchOper') ? $this->input->post('searchOper') : null;
		$search_str = $this->input->post('searchString') ? $this->input->post('searchString') : null;
    $sort_by = $this->input->post('sort_by') ? $this->input->post('sort_by') : '';
    
    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
        $param['fr3'] = 'Shared\DaftarKategoriRekening.fr3';
        $param['nama'] = 'Daftar Ketegori Rekening';
        $param['format'] = $format;
        $param['search'] = $search;
          $param['search_field'] = $search_field;
          $param['search_operator'] = $search_operator;
          $param['search_str'] = $search_str;
          $param['sort_by'] = $sort_by;
        // ambil query daftar
          $this->load->model('kategori_rekening/kategorirekening_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;

      case 'form' :
        $param['fr3'] = 'Shared\Rpt_Form_Kegiatan.fr3';
        $param['nama'] = 'Form Kegiatan';
        $param['format'] = $format;
        $param['opt'] = array(
        "id=".$id
        );
        break;
    }
    $this->start($param);
  }
  
  function fungsi(){

    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
    $search = $this->input->post('search') ? $this->input->post('search') : '';
    $search_field = $this->input->post('searchField') ? $this->input->post('searchField') : null;
		$search_operator = $this->input->post('searchOper') ? $this->input->post('searchOper') : null;
		$search_str = $this->input->post('searchString') ? $this->input->post('searchString') : null;
    $sort_by = $this->input->post('sort_by') ? $this->input->post('sort_by') : '';
    
    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
        $param['fr3'] = 'Shared\DaftarFungsi.fr3';
        $param['nama'] = 'Daftar Fungsi';
        $param['format'] = $format;
        $param['search'] = $search;
          $param['search_field'] = $search_field;
          $param['search_operator'] = $search_operator;
          $param['search_str'] = $search_str;
          $param['sort_by'] = $sort_by;
        // ambil query daftar
          $this->load->model('fungsi/fungsi_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;

    }
    $this->start($param);
  }
  
  function bidang(){

    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
    $search = $this->input->post('search') ? $this->input->post('search') : '';
    $search_field = $this->input->post('searchField') ? $this->input->post('searchField') : null;
		$search_operator = $this->input->post('searchOper') ? $this->input->post('searchOper') : null;
		$search_str = $this->input->post('searchString') ? $this->input->post('searchString') : null;
    $sort_by = $this->input->post('sort_by') ? $this->input->post('sort_by') : '';
    
    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
        $param['fr3'] = 'Shared\DaftarBidang.fr3';
        $param['nama'] = 'Daftar Bidang';
        $param['format'] = $format;
        $param['search'] = $search;
          $param['search_field'] = $search_field;
          $param['search_operator'] = $search_operator;
          $param['search_str'] = $search_str;
          $param['sort_by'] = $sort_by;
        // ambil query daftar
          $this->load->model('bidang/bidang_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;
    }
    $this->start($param);
  }
  
  function rekening_pajak(){

    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
    $search = $this->input->post('search') ? $this->input->post('search') : '';
    $search_field = $this->input->post('searchField') ? $this->input->post('searchField') : null;
		$search_operator = $this->input->post('searchOper') ? $this->input->post('searchOper') : null;
		$search_str = $this->input->post('searchString') ? $this->input->post('searchString') : null;
    $sort_by = $this->input->post('sort_by') ? $this->input->post('sort_by') : '';
    
    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
        $param['fr3'] = 'Shared\DaftarPajak.fr3';
        $param['nama'] = 'Daftar Rekening Pajak';
        $param['format'] = $format;
        $param['search'] = $search;
          $param['search_field'] = $search_field;
          $param['search_operator'] = $search_operator;
          $param['search_str'] = $search_str;
          $param['sort_by'] = $sort_by;
        // ambil query daftar
          $this->load->model('rekening_pajak/rekening_pajak_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;

      case 'form' :
        $param['fr3'] = 'Shared\Rpt_Form_Pajak.fr3';
        $param['nama'] = 'Form Pajak';
        $param['format'] = $format;
        $param['opt'] = array(
        "id=".$id
        );
        break;
    }
    $this->start($param);
  }
   
  function rekening()
  {
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
    $m = $this->input->post('m') ? $this->input->post('m') : '';
    $q = $this->input->post('q') ? $this->input->post('q') : '';
	
    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'Shared\DaftarRekening.fr3';
          $param['nama'] = 'Daftar Rekening';
          $param['format'] = $format;
          $param['q'] = $q;
          $param['m'] = $m;
          // ambil query daftar
          $this->load->model('rekening/rekening_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "tahun=".$this->session->userdata('tahun'), "status=".$this->session->userdata('status')
          );
          break;
    }
    $this->start($param);
  }

  function rekening_pfk(){

    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
    $search = $this->input->post('search') ? $this->input->post('search') : '';
    $search_field = $this->input->post('searchField') ? $this->input->post('searchField') : null;
		$search_operator = $this->input->post('searchOper') ? $this->input->post('searchOper') : null;
		$search_str = $this->input->post('searchString') ? $this->input->post('searchString') : null;
    $sort_by = $this->input->post('sort_by') ? $this->input->post('sort_by') : '';
    
    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
        $param['fr3'] = 'Shared\DaftarRekeningPFK.fr3';
        $param['nama'] = 'Daftar Rekening PFK';
        $param['format'] = $format;
        $param['search'] = $search;
          $param['search_field'] = $search_field;
          $param['search_operator'] = $search_operator;
          $param['search_str'] = $search_str;
          $param['sort_by'] = $sort_by;
        // ambil query daftar
          $this->load->model('rekening_pfk/rekening_pfk_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;

    }
    $this->start($param);
  }
  
  function rekening_bendahara(){

    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
    $search = $this->input->post('search') ? $this->input->post('search') : '';
    $search_field = $this->input->post('searchField') ? $this->input->post('searchField') : null;
		$search_operator = $this->input->post('searchOper') ? $this->input->post('searchOper') : null;
		$search_str = $this->input->post('searchString') ? $this->input->post('searchString') : null;
    $sort_by = $this->input->post('sort_by') ? $this->input->post('sort_by') : '';
    
    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
        $param['fr3'] = 'Shared\DaftarBendahara.fr3';
        $param['nama'] = 'Daftar Rekening Bendahara';
        $param['format'] = $format;
        $param['search'] = $search;
          $param['search_field'] = $search_field;
          $param['search_operator'] = $search_operator;
          $param['search_str'] = $search_str;
          $param['sort_by'] = $sort_by;
        // ambil query daftar
          $this->load->model('rekening_bendahara/rekeningbendahara_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str,
            "id_skpd=".$this->session->userdata('id_skpd')
          );
          break;

      case 'form' :
        $param['fr3'] = 'Shared\Rpt_Form_Pajak.fr3';
        $param['nama'] = 'Form Pajak';
        $param['format'] = $format;
        $param['opt'] = array(
        "id=".$id
        );
        break;
    }
    $this->start($param);
  }
  
	function bast(){
		$tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
		$id = $this->input->post('id') ? $this->input->post('id') : '0';
		$format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
		$m = $this->input->post('m') ? $this->input->post('m') : '';
		$q = $this->input->post('q') ? $this->input->post('q') : '';
		$jenis_bantuan = $this->input->post('jenis_bantuan') ? $this->input->post('jenis_bantuan') : '';
		
		$id_skpd = $this->session->userdata('id_skpd');
		$tahun = $this->session->userdata('tahun');
		$status = $this->session->userdata('status');

		if ($tipe == '') return;
		
		switch ($tipe){
		  case 'daftar' :
			  $param['fr3'] = 'Shared\Rpt_Daftar_BAST.fr3';
			  $param['nama'] = 'Daftar BAST';
			  $param['format'] = $format;
			  $param['q'] = $q;
			  $param['m'] = $m;
			  // ambil query daftar
			  $this->load->model('bast/bast_model','data_model');
			  $query_str = $this->data_model->get_data($param, FALSE, TRUE);
			  $query_str = preg_replace("/[\n\r]/"," ", $query_str);
			  $param['opt'] = array(
				"SQLFilter=".$query_str
			  );
			  break;
		  case 'form' :
			  $param['fr3'] = 'Shared\Rpt_Form_Bansos.fr3';
			  $param['nama'] = 'Form Bansos';
			  $param['format'] = $format;
			  $param['opt'] = array(
				"id=".$id,"tahun=".$tahun
			  );
			  break;
		}
		$this->start($param);
	}
	
	function bastkeu(){
		$tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
		$id = $this->input->post('id') ? $this->input->post('id') : '0';
		$format = $this->input->post('format') ? $this->input->post('format') : 'pdf';
		$m = $this->input->post('m') ? $this->input->post('m') : '';
		$q = $this->input->post('q') ? $this->input->post('q') : '';
		$jenis_bantuan = $this->input->post('jenis_bantuan') ? $this->input->post('jenis_bantuan') : '';
		
		$id_skpd = $this->session->userdata('id_skpd');
		$tahun = $this->session->userdata('tahun');
		$status = $this->session->userdata('status');

		if ($tipe == '') return;
		
		switch ($tipe){
		  case 'daftar' :
			  $param['fr3'] = 'Shared\Rpt_Daftar_BAST.fr3';
			  $param['nama'] = 'Daftar BAST';
			  $param['format'] = $format;
			  $param['q'] = $q;
			  $param['m'] = $m;
			  // ambil query daftar
			  $this->load->model('bastkeu/bastkeu_model','data_model');
			  $query_str = $this->data_model->get_data($param, FALSE, TRUE);
			  $query_str = preg_replace("/[\n\r]/"," ", $query_str);
				$param['opt'] = array(
					"SQLFilter=".$query_str,
					"tahun=".$tahun
			  );
			  break;
		  case 'form' :
			  $param['fr3'] = 'Shared\Rpt_Form_Bankeu.fr3';
			  $param['nama'] = 'Form Bankeu';
			  $param['format'] = $format;
			  $param['opt'] = array(
				"id=".$id,"tahun=".$tahun
			  );
			  break;
		}
		$this->start($param);
	}
	
	function bastnon(){
		$tipe 			= $this->input->post('tipe') ? $this->input->post('tipe') : '';
		$id 			= $this->input->post('id') ? $this->input->post('id') : '0';
		$format 		= $this->input->post('format') ? $this->input->post('format') : 'pdf';
		$m 				= $this->input->post('m') ? $this->input->post('m') : '';
		$q 				= $this->input->post('q') ? $this->input->post('q') : '';
		$jenis_bantuan 	= $this->input->post('jenis_bantuan') ? $this->input->post('jenis_bantuan') : '';
		
		$id_skpd 		= $this->session->userdata('id_skpd');
		$tahun 			= $this->session->userdata('tahun');
		$status 		= $this->session->userdata('status');

		if ($tipe == '') return;
		
		switch ($tipe){
			case 'daftar' :
				$param['fr3'] = 'Shared\Rpt_Daftar_BASTNON.fr3';
				$param['nama'] = 'Daftar BAST NON PROPOSAL';
				$param['format'] = $format;
				$param['q'] = $q;
				$param['m'] = $m;
				// ambil query daftar
				$this->load->model('bastnon/bastnon_model','data_model');
				$query_str = $this->data_model->get_data($param, FALSE, TRUE);
				$query_str = preg_replace("/[\n\r]/"," ", $query_str);
				$param['opt'] = array(					
					"SQLFilter=".$query_str,
					"tahun=".$tahun
				);
				break;
			case 'form' :
				$param['fr3'] = 'Shared\Rpt_Form_BastNon.fr3';
				$param['nama'] = 'Form BAST';
				$param['format'] = $format;
				$param['opt'] = array(
					"id=".$id,"id_skpd=".$id_skpd,"tahun=".$tahun,"status=".$status,"jenis_bantuan=".$jenis_bantuan
				);
				break;
		}
		$this->start($param);
	}
}