<?php
class Laporan extends CI_Controller {

  var $path2engine;
  var $path2fr3;
  var $path2cache;
  var $path2output;
  var $attachment;

  public function __construct()
  {
    parent::__construct();
    $user_data = $this->session->userdata;
    $this->load->vars($user_data);
    $this->load->model('laporan_model','data_model');
    $this->load->model('auth/login_model', 'auth');
    $this->load->helper('path');
    $this->path2engine = dirname( realpath(SELF) )."/assets/fr/";
    $this->path2fr3 = $this->path2engine . 'fr3/';
    $this->path2cache = $this->path2engine . 'cache/';
    $this->path2output = $this->path2engine . 'output/';
    $this->attachment = 1; // 0 : show in the browser;  1 : download file
  }

  public function index()
  {
    // load sipkd.xml file
    $xml = $this->load_xml();
    $len_group = count($xml->GROUP);
    $html = '';
    for ($i=0; $i<$len_group; $i++)
    {
      $html .= '<li>'.$xml->GROUP[$i]['caption'];
      $html .= '<ul>';
      $len_report = count($xml->GROUP[$i]->REPORT);
      for ($j=0; $j<$len_report; $j++)
      {
        $html .= '<li><a href="#" onclick="filter_option('.$i.','.$j.');">';
        $html .= $xml->GROUP[$i]->REPORT[$j]['caption'].'</a></li>';
      }
      $html .= '</ul>';
      $html .= '</li>';
    }
    $data['ul'] = $html;

    $data['title']=PRODUCT_NAME.' - '.' Laporan';
    $data['main_content']='laporan_view';
    $data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    $this->load->view('layout/template',$data);
  }

  function load_xml()
  {
    // load sipkd.xml file
    $xml = simplexml_load_file(base_url().'assets/bansos.xml');
    return $xml;
  }

  function data_xml()
  {
    $xml = $this->load_xml();
    $i = intval($this->input->post('i'));
    $j = intval($this->input->post('j'));

    $data = $xml->GROUP[$i]->REPORT[$j];
    return $data;
  }

  function filter_option()
  {
    $data = $this->data_xml()->children();
    $file = (string)$this->data_xml()->Attributes()->file;

    $html = '';
    $html .= '<input type="hidden" class="span2" id="file" value="'.$file.'"/>';
    foreach($data as $dat)
    {
      if ($dat->getName() == 'DATE')
      {
        // set default date u/ parameter combobox
        if((string)$dat['varname'] == 'TanggalAwal')
        {
          switch((string)$dat['default']){
            case 'd' : $def_tgl_awal = date('Y-m-d'); break;
            case 'yb' : $def_tgl_awal = date('Y').'-01-01'; break;
            case 'mb' : $def_tgl_awal = date('Y-m').'-01'; break;
            default: $def_tgl_awal = date('Y-m-d');
          }
        }
        else if((string)$dat['varname'] == 'TanggalAkhir')
        {
          switch((string)$dat['default']){
            case 'd' : $def_tgl_akhir = date('Y-m-d'); break;
            case 'yb' : $def_tgl_akhir = date('Y-01-01'); break;
            case 'mb' : $def_tgl_akhir = date('Y-m-01'); break;
            default: $def_tgl_akhir = date('Y-m-d');
          }
        }

        switch((string)$dat['default']){
          case 'd' : $default = date('d/m/Y'); break;
          case 'yb' : $default = '01/01/'.date('Y'); break;
          case 'mb' : $default = '01/'.date('m/Y'); break;
          default: $default = date('d/m/Y');
        }

        $html .= '<div class="control-row" >';
        $html .= '<label class="control-label" for="'.(string)$dat['varname'].'">';
        $html .= (string)$dat['caption'].'</label>';
        $html .= '<input type="text" id="'.(string)$dat['varname'].'"';
        $html .= 'class="span2 datepicker" value="'.$default.'" />';
        $html .= '</div>';
      }
      else if($dat->getName() == 'HALAMAN')
      {
        $html .= '<div class="control-row" >';
        $html .= '<label class="control-label" for="'.(string)$dat['varname'].'">';
        $html .= (string)$dat['caption'].'</label>';
        $html .= '<input type="text" id="'.(string)$dat['varname'].'"';
        $html .= 'class="span2" value="'.(string)$dat['default'].'" />';
        $html .= '</div>';
      }
      else if($dat->getName() == 'SELECT')
      {
        $key = strtoupper((string)$dat->ITEMSQL['key']);
        $display = strtoupper((string)$dat->ITEMSQL['display']);
        $html .= '<div class="control-row" >';
        $html .= '<label class="control-label" for="'.(string)$dat['varname'].'">';
        $html .= (string)$dat['caption'].'</label>';
        $html .= '<select id="'.(string)$dat['varname'].'">';

        $sql = $dat->children();
        $tgl_awal = isset($def_tgl_awal) ? $def_tgl_awal : date('Y-m-d');
        $tgl_akhir = isset($def_tgl_akhir) ? $def_tgl_akhir : date('Y-m-d');
        $arr = array(':id_bidang'=>'0', ':tanggalawal'=>'\''.$tgl_awal.'\'',
              ':tanggalakhir'=>'\''.$tgl_akhir.'\'', ':id_skpd'=>'0', ':jenis_bantuan'=>'null',':kategori'=>'null',':id_kegiatan'=>'0');
        $sql = strtr($sql,$arr);
        $result = $this->db->query($sql)->result_array();
        foreach($result as $row) {
          $html .= '<option value="'.$row[$key].'"';
          $html .= $row[$key]==(string)$dat['default']?" selected":"";
          $html .= '>'.$row[$display].'</option>';
        }

        $html .= '</select></div>';
      }
    }
    $html .= '<label class="radio" >';
    $html .= '<input type="radio" name="format" id="formatPdf" value="pdf" checked />';
    $html .= '<img src="'.base_url().'assets/img/pdf.png" style="width:30px;height:30px;margin-left:0px;margin-top:0px">';
    $html .= '</label>';
    $html .= '<label class="radio">';
    $html .= '<input type="radio" name="format" id="formatXls" value="xls" />';
    $html .= '<img src="'.base_url().'assets/img/xls.png" style="width:30px;height:30px;margin-left:0px;margin-top:0px">';
    $html .= '</label>';
	$html .= '<label class="radio" >';
    $html .= '<input type="radio" name="format" id="formatDoc" value="docx" />';
    $html .= '<img src="'.base_url().'assets/img/blue-document-word.png" style="width:30px;height:30px;margin-left:0px;margin-top:0px">';
    $html .= '</label>';

    echo json_encode($html);
  }

  public function param_query()
  {
    $id_bidang = $this->input->post('id_bidang') ? $this->input->post('id_bidang') : 0;
    $tgl_awal = $this->input->post('tgl_awal') ? $this->input->post('tgl_awal') : date('d/m/Y');
    $tgl_awal = date('Y-m-d',strtotime($tgl_awal));
    $tgl_akhir = $this->input->post('tgl_akhir') ? $this->input->post('tgl_akhir') : date('d/m/Y');
    $tgl_akhir = date('Y-m-d',strtotime($tgl_akhir));
    $id_skpd = $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0;
    $id_kegiatan = $this->input->post('id_kegiatan') ? $this->input->post('id_kegiatan') : 0;
    $jenis_bantuan = $this->input->post('jenis_bantuan') ? $this->input->post('jenis_bantuan') : 0;
    $kategori = $this->input->post('kategori') ? $this->input->post('kategori') : 0;

    $arr = array(':id_bidang'=>'\''.$id_bidang.'\'',':tanggalawal'=>'\''.$tgl_awal.'\'',
        ':tanggalakhir'=>'\''.$tgl_akhir.'\'',':id_skpd'=>'\''.$id_skpd.'\'',':jenis_bantuan'=>'\''.$jenis_bantuan.'\'',
        ':kategori'=>'\''.$kategori.'\'',':id_kegiatan'=>'\''.$id_kegiatan.'\'');

    return $arr;
  }

  function get_bidang()
  {
    $data = $this->data_xml()->children();

    $opt = '';
    foreach($data as $dat)
    {
      if($dat->getName() == 'SELECT')
      {
        $key = strtoupper((string)$dat->ITEMSQL['key']);
        $display = strtoupper((string)$dat->ITEMSQL['display']);

        $sql = $dat->children();
        $arr = $this->param_query();
        $sql = strtr($sql,$arr);
        if($dat['varname'] == 'Id_Bidang')
        {
          $result = $this->db->query($sql)->result_array();
          foreach($result as $row) {
            $opt .= '<option value="'.$row[$key].'"';
            $opt .= $row[$key]==(string)$dat['default']?" selected":"";
            $opt .= '>'.$row[$display].'</option>';
          }
        }
      }
    }

    echo json_encode($opt);
  }

  function get_skpd()
  {
    $data = $this->data_xml()->children();

    $opt = '';
    foreach($data as $dat)
    {
      if($dat->getName() == 'SELECT')
      {
        $key = strtoupper((string)$dat->ITEMSQL['key']);
        $display = strtoupper((string)$dat->ITEMSQL['display']);

        $sql = $dat->children();
        $arr = $this->param_query();
        $sql = strtr($sql,$arr);
        if($dat['varname'] == 'Id_SKPD')
        {
          $result = $this->db->query($sql)->result_array();
          foreach($result as $row) {
            $opt .= '<option value="'.$row[$key].'"';
            $opt .= $row[$key]==(string)$dat['default']?" selected":"";
            $opt .= '>'.$row[$display].'</option>';
          }
        }
      }
    }

    echo json_encode($opt);
  }

  function get_jenis()
  {
    $data = $this->data_xml()->children();

    $opt = '';
    foreach($data as $dat)
    {
      if($dat->getName() == 'SELECT')
      {
        $key = strtoupper((string)$dat->ITEMSQL['key']);
        $display = strtoupper((string)$dat->ITEMSQL['display']);

        $sql = $dat->children();
        $arr = $this->param_query();
        $sql = strtr($sql,$arr);
        if($dat['varname'] == 'Jenis_bantuan')
        {
          $result = $this->db->query($sql)->result_array();
          foreach($result as $row) {
            $opt .= '<option value="'.$row[$key].'"';
            $opt .= $row[$key]==(string)$dat['default']?" selected":"";
            $opt .= '>'.$row[$display].'</option>';
          }
        }
      }
    }

    echo json_encode($opt);
  }
  
  function get_kategori()
  {
    $data = $this->data_xml()->children();

    $opt = '';
    foreach($data as $dat)
    {
      if($dat->getName() == 'SELECT')
      {
        $key = strtoupper((string)$dat->ITEMSQL['key']);
        $display = strtoupper((string)$dat->ITEMSQL['display']);

        $sql = $dat->children();
        $arr = $this->param_query();
        $sql = strtr($sql,$arr);
        if($dat['varname'] == 'Kategori')
        {
          $result = $this->db->query($sql)->result_array();
          foreach($result as $row) {
            $opt .= '<option value="'.$row[$key].'"';
            $opt .= $row[$key]==(string)$dat['default']?" selected":"";
            $opt .= '>'.$row[$display].'</option>';
          }
        }
      }
    }

    echo json_encode($opt);
  }
  
  
  function get_kegiatan()
  {
    $data = $this->data_xml()->children();

    $opt = '';
    foreach($data as $dat)
    {
      if($dat->getName() == 'SELECT')
      {
        $key = strtoupper((string)$dat->ITEMSQL['key']);
        $display = strtoupper((string)$dat->ITEMSQL['display']);

        $sql = $dat->children();
        $arr = $this->param_query();
        $sql = strtr($sql,$arr);
        if($dat['varname'] == 'Id_Kegiatan')
        {
          $result = $this->db->query($sql)->result_array();
          foreach($result as $row) {
            $opt .= '<option value="'.$row[$key].'"';
            $opt .= $row[$key]==(string)$dat['default']?" selected":"";
            $opt .= '>'.$row[$display].'</option>';
          }
        }
      }
    }

    echo json_encode($opt);
  }

  function get_rekening()
  {
    $data = $this->data_xml()->children();

    $opt = '';
    foreach($data as $dat)
    {
      if($dat->getName() == 'SELECT')
      {
        $key = strtoupper((string)$dat->ITEMSQL['key']);
        $display = strtoupper((string)$dat->ITEMSQL['display']);

        $sql = $dat->children();
        $arr = $this->param_query();
        $sql = strtr($sql,$arr);
        if($dat['varname'] == 'Id_Rekening')
        {
          $result = $this->db->query($sql)->result_array();
          foreach($result as $row) {
            $opt .= '<option value="'.$row[$key].'"';
            $opt .= $row[$key]==(string)$dat['default']?" selected":"";
            $opt .= '>'.$row[$display].'</option>';
          }
        }
      }
    }

    echo json_encode($opt);
  }

  function get_pejabat()
  {
    $data = $this->data_xml()->children();

    $opt = '';
    foreach($data as $dat)
    {
      if($dat->getName() == 'SELECT')
      {
        $key = strtoupper((string)$dat->ITEMSQL['key']);
        $display = strtoupper((string)$dat->ITEMSQL['display']);

        $sql = $dat->children();
        $arr = $this->param_query();
        $sql = strtr($sql,$arr);
        if($dat['varname'] == 'Id_PPK' || $dat['varname'] == 'Id_PA' || $dat['varname'] == 'Id_BP')
        {
          $result = $this->db->query($sql)->result_array();
          foreach($result as $row) {
            $opt .= '<option value="'.$row[$key].'"';
            $opt .= $row[$key]==(string)$dat['default']?" selected":"";
            $opt .= '>'.$row[$display].'</option>';
          }
        }
      }
    }

    echo json_encode($opt);
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
      foreach($param['opt'] as $key => $value){
        $data.=$key.'='.$value."\n";
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

  function preview()
  {
    $tgl_lapor = $this->input->post('tgl_lapor') ? $this->input->post('tgl_lapor') : date('d/m/Y');
    $tgl_awal = $this->input->post('tgl_awal') ? $this->input->post('tgl_awal') : date('d/m/Y');
    $tgl_akhir = $this->input->post('tgl_akhir') ? $this->input->post('tgl_akhir') : date('d/m/Y');
    $no_hlmn = $this->input->post('no_hlmn') ? $this->input->post('no_hlmn') : '1';
    $realisasi = $this->input->post('realisasi') ? $this->input->post('realisasi') : '0';
    $bulan = $this->input->post('bulan') ? $this->input->post('bulan') : '1';
    $header = $this->input->post('header') ? $this->input->post('header') : '1';
    $semester = $this->input->post('semester') ? $this->input->post('semester') : '1';
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '0';
    $jenis_sp2d = $this->input->post('jenis_sp2d') ? $this->input->post('jenis_sp2d') : '0';
    $id_sd = $this->input->post('id_sd') ? $this->input->post('id_sd') : '0';
    $histori = $this->input->post('histori') ? $this->input->post('histori') : '0';
    $id_bidang = $this->input->post('id_bidang') ? $this->input->post('id_bidang') : '0';
    $id_skpd = $this->input->post('id_skpd') ? $this->input->post('id_skpd') : $this->session->userdata('id_skpd') ;
    $jenis_bantuan = $this->input->post('jenis_bantuan') ? $this->input->post('jenis_bantuan') : '0';
    $kategori = $this->input->post('kategori') ? $this->input->post('kategori') : '0';
    $kode_rek = $this->input->post('kode_rek') ? $this->input->post('kode_rek') : '0';
    $id_pa = $this->input->post('id_pa') ? $this->input->post('id_pa') : '0';
    $id_ppk = $this->input->post('id_ppk') ? $this->input->post('id_ppk') : '0';
    $id_bp = $this->input->post('id_bp') ? $this->input->post('id_bp') : '0';
    $id_kegiatan = $this->input->post('id_kegiatan') ? $this->input->post('id_kegiatan') : '0';
    $id_rekening = $this->input->post('id_rekening') ? $this->input->post('id_rekening') : '0';
    $keperluan = $this->input->post('keperluan') ? $this->input->post('keperluan') : '0';
    $file = $this->input->post('file') ? $this->input->post('file') : '';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';

    $exp = explode('.', $file);
    $nama = $exp[0];
    $param['fr3'] = 'Shared/'.$file;
    $param['nama'] = $nama;
    $param['format'] = $format;
    $param['opt'] = array(
          'Tanggal' => $tgl_lapor,
          'TanggalAwal' => $tgl_awal,
          'TanggalAkhir' => $tgl_akhir,
          'NoHalaman' => $no_hlmn,
          'Realisasi' => $realisasi,
          'Bulan' => $bulan,
          'Header' => $header,
          'Semester'=>$semester,
          'Tipe'=>$tipe,
          'JenisSP2D'=>$jenis_sp2d,
          'Id_Sumber_Dana'=>$id_sd,
          'Histori'=>$histori,
          'Id_Bidang' => $id_bidang,
          'Id_SKPD' => $id_skpd,
          'Jenis_bantuan' => $jenis_bantuan,
          'Kategori' => $kategori,
          'kode_rek' => $kode_rek,
          'Id_PA' => $id_pa,
          'Id_PPK' => $id_ppk,
          'Id_BP' => $id_bp,
          'Id_Kegiatan' => $id_kegiatan,
          'Id_Rekening' => $id_rekening,
          'Keperluan' => $keperluan,
          //'PPKD' => $this->session->userdata['ppkd'],
          'Tahun' => $this->session->userdata['tahun'],
          'App_Title' => 'E-Finance',
    );
    $this->start($param);
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

    $filename = $key. "." .$filetype;
    $fileerr = $key.".err";

    if (file_exists($this->path2output.$filename)) {
      $outfile = $reportname. "." .$filetype;

      header('Content-Disposition: ' . ($this->attachment == 1 ? 'attachment; ' : '') .'filename="'.$outfile.'"');
      if ($filetype == 'pdf'){
      header('Content-Type: application/pdf');
      } else {
      header('Content-Type: application/xls');
      }
      header('Content-Length: '.filesize($this->path2output.$filename));
      header('Cache-Control: no-store');
      readfile($this->path2output.$filename);
    }
    elseif (file_exists($this->path2output.$fileerr)) {
      $this->output->set_status_header(500);
    }
  }

}