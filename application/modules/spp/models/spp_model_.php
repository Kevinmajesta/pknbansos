<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spp_model extends Aktivitas_Model {

  var $fields;
  var $fieldmap;
  var $fieldmap_spp;
  var $fieldmap_rincian;
  var $fieldmap_pakai_spd;
  var $data_spp;
  var $data_rincian;
  //var $data_potongan;
  var $data_pajak;
  var $data_pakai_spd;
  var $purge_spd;
  var $purge_rekening;
  //var $purge_pfk;
  var $purge_pajak;
  var $skpd;

  function __construct()
  {
    parent::__construct();

    $this->tipe = 'SPP';
    $this->skpd = $this->session->userdata('id_skpd');

    $this->fields = array(
      'ID_AKTIVITAS',
      'NOMOR',
      'TANGGAL',
      'NAMA_SKPD',
      'KEPERLUAN',
      'BEBAN',
      'NOMINAL',
      'NAMA_KEGIATAN',
      'DESKRIPSI'
    );

    $this->fieldmap = array(
      'no' => 'a.NOMOR',
      'tgl' => 'a.TANGGAL',
      'kdskpd' => 's.KODE_SKPD_LKP',
      'nmskpd' => 's.NAMA_SKPD',
      'kpl' => 'a.KEPERLUAN',
      'bbn' => 'a.JENIS_BEBAN',
      'nom' => 'a.NOMINAL',
      'keg' => 'a.NAMA_KEGIATAN',
      'ket' => 'cast(substring(a.DESKRIPSI from 1 for 5000) as varchar(5000))',
    );

    $this->fieldmap_spp = array(
      'id' => 'ID_AKTIVITAS',
      'spj' => 'ID_AKTIVITAS_SPJ',
      'beban' => 'BEBAN',
      'jenis_beban' => 'JENIS_BEBAN',
      'keperluan' => 'KEPERLUAN',
      'total' => 'NOMINAL',
      'kontrak' => 'NO_KONTRAK',
      'bank' => 'NAMA_BANK',
      'npwp' => 'NPWP',
      'penerima' => 'NAMA_PENERIMA',
      'norek' => 'NO_REKENING_BANK',
      'no_dpa' => 'NO_DPA',
      'tgl_dpa' => 'TANGGAL_DPA',
      'pagu_dpa' => 'PAGU_DPA',
      'bk' => 'ID_BK',
      'pptk' => 'ID_PPTK',
      'pa' => 'ID_PA',
	  'pagu_btt' => 'PAGU_BTT',
	  'nominal_btt' => 'NOMINAL_BTT'
    );

    $this->fieldmap_rincian = array(
      'idr' => 'ID_RINCIAN_SPP',
      'id' => 'ID_AKTIVITAS',
      'idkeg' => 'ID_KEGIATAN',
      'idrek' => 'ID_REKENING',
      'nom' => 'NOMINAL'
    );

    $this->fieldmap_pakai_spd = array(
      'idr' => 'ID_SPP_PAKAI_SPD',
      'idspd' => 'ID_AKTIVITAS_SPD',
      'no' => 'NOMOR_SPD',
      'tgl' => 'TANGGAL_SPD',
      'nom' => 'NOMINAL_SPD',
    );

    /*$this->fieldmap_potongan = array(
      'idrek' => 'ID_REKENING',
      'nom' => 'NOMINAL',
      'info' => 'IS_INFORMASI',
    );*/

    $this->fieldmap_pajak = array(
      'idp' => 'ID_PAJAK',
      'nom' => 'NOMINAL',
      'info' => 'IS_INFORMASI',
    );
  }

  function get_grid_model()
  {
    $grid = array(
        'colNames' => array('Nomor', 'Tanggal', 'Nama SKPD', 'Keperluan', 'Beban', 'Nominal', 'Nama Kegiatan', 'Keterangan'),
        'colModel' => array(
          array('name' => 'no', 'width' => 200, 'sortable' => true),
          array('name' => 'tgl', 'width' => 80, 'sortable' => true, 'formatter' => 'date', 'align' => 'center'),
          array('name' => 'nmskpd','width' => 250, 'sortable' => true),
          array('name' => 'kpl', 'width' => 60, 'sortable' => true, 'align' => 'center'),
          array('name' => 'bbn', 'width' => 60, 'sortable' => true, 'align' => 'center'),
          array('name' => 'nom','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
          array('name' => 'keg','width' => 200, 'sortable' => true),
          array('name' => 'ket','width' => 300, 'sortable' => true),
        ),
    );
    
    return $grid;
  }

  // ----- search advance ---- >>
  function get_data_fields()
  {
    $fields['no'] = array('name' => 'Nomor', 'kategori'=>'string');
    $fields['tgl'] = array('name' => 'Tanggal', 'kategori'=>'date');
    if ($this->skpd == 0) {
      $fields['nmskpd'] = array('name' => 'Nama SKPD', 'kategori'=>'string');
    }
    $fields['kpl'] = array('name' => 'Keperluan', 'kategori'=>'predefined', 'options'=> array('UP'=>'UP', 'LS'=>'LS', 'GU'=>'GU', 'GU NIHIL'=>'GU NIHIL'));
    $fields['bbn'] = array('name' => 'Beban', 'kategori'=>'predefined', 'options'=> array('BTL'=>'Beban Tidak Langsung', 'BL'=>'Beban Langsung'));
    $fields['nom'] = array('name' => 'Nominal', 'kategori'=>'numeric');
    $fields['keg'] = array('name' => 'Nama Kegiatan', 'kategori'=>'string');
    $fields['ket'] = array('name' => 'Keterangan', 'kategori'=>'string');

    return $fields;
  }

  function fill_data()
  {
    parent::fill_data();

    /* ambil data SPP */
    foreach($this->fieldmap_spp as $key => $value){
    $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      switch($key)
      {
        case 'total'   : $$key = $this->input->post($key) ? $this->input->post($key) : NULL; break;
        case 'tgl_dpa'   : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        case 'pagu_dpa'   : $$key = $this->input->post($key) ? $this->input->post($key) : NULL; break;
        default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }
      if(isset($$key))
        $this->data_spp[$value] = $$key;
    }

    /* ambil data rincian Rekening SPP */
    $this->purge_rekening = $this->input->post('purge_rek'); $this->purge_rekening = $this->purge_rekening ? $this->purge_rekening : NULL;
    $rinci = $this->input->post('rincian') ? $this->input->post('rincian') : NULL;
    if ($rinci)
    {
      $rinci = json_decode($rinci);
      for ($i=0; $i <= count($rinci) - 1; $i++) {
        foreach($this->fieldmap_rincian as $key => $value){
          $$key = isset($rinci[$i]->$key) && $rinci[$i]->$key ? $rinci[$i]->$key : NULL;
          if(isset($$key))
            $this->data_rincian[$i][$value] = $$key;
        }
      }
    }

    /* ambil data rincian SPD */
    $this->purge_spd = $this->input->post('purge_spd'); $this->purge_spd = $this->purge_spd ? $this->purge_spd : NULL;
    $spd = $this->input->post('spd') ? $this->input->post('spd') : NULL;
    if ($spd)
    {
      $spd = json_decode($spd);
      for ($i=0; $i <= count($spd) - 1; $i++) {
        foreach($this->fieldmap_pakai_spd as $key => $value){
          switch($key)
          {
            case 'tgl' : $$key = isset($spd[$i]->$key) && $spd[$i]->$key ? prepare_date($spd[$i]->$key) : NULL; break;
            case 'nom' : $$key = isset($spd[$i]->$key) && $spd[$i]->$key ? prepare_numeric($spd[$i]->$key) : 0; break;
            default : $$key = isset($spd[$i]->$key) && $spd[$i]->$key ? $spd[$i]->$key : NULL;
          }
          if(isset($$key))
            $this->data_pakai_spd[$i][$value] = $$key;
        }
      }
    }

    /* ambil data rincian Rekening PFK */
    //$this->purge_pfk = $this->input->post('purge_pfk'); $this->purge_pfk = $this->purge_pfk ? $this->purge_pfk : NULL;
 //$pfk = $this->input->post('pfk') ? $this->input->post('pfk') : NULL;
    /*if ($pfk)
    {
      $pfk = json_decode($pfk);
      for ($i=0; $i <= count($pfk) - 1; $i++) {
        foreach($this->fieldmap_potongan as $key => $value){
          $$key = isset($pfk[$i]->$key) && $pfk[$i]->$key ? $pfk[$i]->$key : NULL;
          if(isset($$key))
            $this->data_potongan[$i][$value] = $$key;
        }
      }
    }*/

    /* ambil data rincian Rekening Pajak */
    $this->purge_pajak = $this->input->post('purge_pjk'); $this->purge_pajak = $this->purge_pajak ? $this->purge_pajak : NULL;
    $pajak = $this->input->post('pjk') ? $this->input->post('pjk') : NULL;
    if ($pajak)
    {
      $pajak = json_decode($pajak);
      for ($i=0; $i <= count($pajak) - 1; $i++) {
        foreach($this->fieldmap_pajak as $key => $value){
          $$key = isset($pajak[$i]->$key) && $pajak[$i]->$key ? $pajak[$i]->$key : NULL;
          if(isset($$key))
            $this->data_pajak[$i][$value] = $$key;
        }
      }
    }
  }

  /* Simpan data SPP */
  function insert_spp()
  {
    $this->db->select('1')->from('SPP')->where('ID_AKTIVITAS', $this->id);
    $rs = $this->db->get()->row_array();

    if ($rs)
    {
      $this->db->where('ID_AKTIVITAS', $this->id);
      $this->db->update('SPP', $this->data_spp);
    }
    else
    {
      $this->data_spp['ID_AKTIVITAS'] = $this->id;
      $this->db->insert('SPP', $this->data_spp);
    }
  }

  /* Simpan rincian Rekening SPP */
  function insert_rincian()
  {
    if($this->purge_rekening)
    {
      $this->db->where_in('ID_RINCIAN_SPP', $this->purge_rekening);
      $this->db->delete('RINCIAN_SPP');
    }

    $jml = count($this->data_rincian);
    for ($i=0; $i <= $jml - 1; $i++)
    {
      $idr = $this->data_rincian[$i]['ID_RINCIAN_SPP'];
      $this->db->select('1')->from('RINCIAN_SPP')->where('ID_RINCIAN_SPP', $idr);
      $rs = $this->db->get()->row_array();

      if ($rs)
      {
        $this->db->where('ID_RINCIAN_SPP', $idr);
        $this->db->update('RINCIAN_SPP', $this->data_rincian[$i]);
      }
      else
      {
        unset( $this->data_rincian[$i]['ID_RINCIAN_SPP'] );
        $this->data_rincian[$i]['ID_AKTIVITAS'] = $this->id;
        $this->db->insert('RINCIAN_SPP', $this->data_rincian[$i]);
      }
    }
  }

  /* Simpan rincian Rekening PFK */
  /*function insert_potongan()
  {
    if($this->purge_pfk)
    {
      $this->db->where_in('ID_REKENING', $this->purge_pfk);
      $this->db->where('ID_AKTIVITAS', $this->id);
      $this->db->delete('RINCIAN_SPP_PFK');
    }

    $jml = count($this->data_potongan);
    for ($i=0; $i <= $jml - 1; $i++)
    {
      $idr = $this->data_potongan[$i]['ID_REKENING'];
      $this->db->select('1')->from('RINCIAN_SPP_PFK')->where('ID_AKTIVITAS', $this->id)->where('ID_REKENING', $idr);
      $rs = $this->db->get()->row_array();

      if ($rs)
      {
        $this->db->where('ID_REKENING', $idr);
        $this->db->where('ID_AKTIVITAS', $this->id);
        $this->db->update('RINCIAN_SPP_PFK', $this->data_potongan[$i]);
      }
      else
      {
        $this->data_potongan[$i]['ID_AKTIVITAS'] = $this->id;
        $this->db->insert('RINCIAN_SPP_PFK', $this->data_potongan[$i]);
      }
    }
  }*/

  /* Simpan rincian Rekening Pajak */
  function insert_pajak()
  {
    if($this->purge_pajak)
    {
      $this->db->where_in('ID_PAJAK', $this->purge_pajak);
      $this->db->where('ID_AKTIVITAS', $this->id);
      $this->db->delete('RINCIAN_SPP_PAJAK');
    }

    $jml = count($this->data_pajak);
    for ($i=0; $i <= $jml - 1; $i++)
    {
      $idr = $this->data_pajak[$i]['ID_PAJAK'];
      $this->db->select('1')->from('RINCIAN_SPP_PAJAK')->where('ID_AKTIVITAS', $this->id)->where('ID_PAJAK', $idr);
      $rs = $this->db->get()->row_array();

      if ($rs)
      {
        $this->db->where('ID_PAJAK', $idr);
        $this->db->where('ID_AKTIVITAS', $this->id);
        $this->db->update('RINCIAN_SPP_PAJAK', $this->data_pajak[$i]);
      }
      else
      {
        $this->data_pajak[$i]['ID_AKTIVITAS'] = $this->id;
        $this->db->insert('RINCIAN_SPP_PAJAK', $this->data_pajak[$i]);
      }
    }
  }

  /* Simpan rincian SPD */
  function insert_spp_pakai_spd()
  {
    if($this->purge_spd)
    {
      $this->db->where_in('ID_AKTIVITAS_SPD', $this->purge_spd);
      $this->db->where('ID_AKTIVITAS_SPP', $this->id);
      $this->db->delete('SPP_PAKAI_SPD');
    }

    $jml = count($this->data_pakai_spd);
    for ($i=0; $i <= $jml - 1; $i++)
    {
      $id_spd = $this->data_pakai_spd[$i]['ID_AKTIVITAS_SPD'];
      $this->db->select('1')->from('SPP_PAKAI_SPD')
        ->where('ID_AKTIVITAS_SPP', $this->id)
        ->where('ID_AKTIVITAS_SPD', $id_spd);;
      $rs = $this->db->get()->row_array();

      if ($rs)
      {
        $this->db->where('ID_AKTIVITAS_SPP', $this->id);
        $this->db->where('ID_AKTIVITAS_SPD', $id_spd);
        $this->db->update('SPP_PAKAI_SPD', $this->data_pakai_spd[$i]);
      }
      else
      {
        unset( $this->data_pakai_spd[$i]['ID_SPP_PAKAI_SPD'] );
        $this->data_pakai_spd[$i]['ID_AKTIVITAS_SPP'] = $this->id;
        $this->db->insert('SPP_PAKAI_SPD', $this->data_pakai_spd[$i]);
      }
    }
  }
  
	function save_detail()
	{
		$this->insert_spp();
		$this->insert_rincian();
		//$this->insert_potongan();
		$this->insert_pajak();
		$this->insert_spp_pakai_spd();
	}

  /* Query daftar */
  function build_query_daftar()
  {
    $this->db->select("
      a.id_aktivitas,
      a.nomor,
      a.tanggal,
      cast(substring(a.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
      a.id_skpd,
      s.kode_skpd_lkp,
      s.nama_skpd,
      a.nominal,
      a.beban, a.keperluan,
      a.nama_kegiatan
    ");
    $this->db->from('v_spp_kegiatan a');
    $this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
    $this->db->where('a.tahun', $this->tahun);
    /* filter skpd jika diset */
    if ($this->session->userdata('id_skpd') != 0) $this->db->where('a.id_skpd', $this->session->userdata('id_skpd'));
  }

  /* Query form */
  function build_query_form($id=0)
  {
    $this->db->select('
      a.id_aktivitas,
      a.nomor,
      a.tanggal,
      cast(substring(a.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
      a.id_skpd,
      s.kode_skpd_lkp,
      s.nama_skpd,
      b.nominal,
      b.keperluan,
      b.beban,
      b.jenis_beban,
      b.no_kontrak,
      b.nama_penerima,
      b.instansi_penerima,
      b.nama_bank,
      b.no_rekening_bank,
      b.npwp,
      b.no_dpa,
      b.tanggal_dpa,
      b.pagu_dpa,
      b.id_bk,
      bk.nama_pejabat bk_nama,
      bk.nip bk_nip,
      b.id_pptk,
      pptk.nama_pejabat pptk_nama,
      pptk.nip pptk_nip,
      b.id_pa,
      pa.nama_pejabat pa_nama,
      pa.nip pa_nip,
	  b.pagu_btt,
      b.nominal_btt,
	  sp.id_aktivitas id_spj,
	  sp.nomor nomor_spj
    ');
    $this->db->from('aktivitas a');
    $this->db->join('spp b', 'b.id_aktivitas = a.id_aktivitas');
    $this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
    $this->db->join('v_spj sp', 'sp.id_aktivitas = b.id_aktivitas_spj','LEFT');
    $this->db->join('pejabat_skpd bk', 'bk.id_pejabat_skpd = b.id_bk','LEFT');
    $this->db->join('pejabat_skpd pptk', 'pptk.id_pejabat_skpd = b.id_pptk','LEFT');
    $this->db->join('pejabat_skpd pa', 'pa.id_pejabat_skpd = b.id_pa','LEFT');
    $this->db->where('a.id_aktivitas', $id);
  }

  function build_query_hapus($id=0)
  {
    $this->db->where('id_aktivitas_spp', $id)->delete('spp_pakai_spd');
    $this->db->where('id_aktivitas', $id)->delete('rincian_spp_pajak');
    //$this->db->where('id_aktivitas', $id)->delete('rincian_spp_pfk');
    $this->db->where('id_aktivitas', $id)->delete('rincian_spp');
    $this->db->where('id_aktivitas', $id)->delete('spp');
    $this->db->where('id_aktivitas', $id)->delete('aktivitas');
  }

  /* Query rincian SPD */
  function get_spd_by_id($id=0)
  {
    $this->db->select('
      a.id_spp_pakai_spd,
      a.id_aktivitas_spd,
      b.nomor,
      b.tanggal,
      b.nominal
    ');
    $this->db->from('spp_pakai_spd a');
    $this->db->join('v_spd b', 'b.id_aktivitas = a.id_aktivitas_spd');
    $this->db->where('a.id_aktivitas_spp', $id);
    $result = $this->db->get()->result_array();

    return $result;
  }

  /* Query rincian Kegiatan SPP */
  function get_keg_by_id($id=0)
  {
    $this->db->select('
      k.id_kegiatan,
      k.kode_kegiatan_skpd,
      k.nama_kegiatan
    ');
    $this->db->from('rincian_spp b');
    $this->db->join('aktivitas a', 'a.id_aktivitas = b.id_aktivitas');
    $this->db->join('v_kegiatan_skpd k', 'k.id_kegiatan = b.id_kegiatan and k.id_skpd = a.id_skpd');
    $this->db->where('b.id_aktivitas', $id);
    $this->db->group_by('k.id_kegiatan, k.kode_kegiatan_skpd, k.nama_kegiatan');
    $result = $this->db->get()->result_array();

    return $result;
  }

  /* Query rincian Rekening SPP */
  function get_rek_by_id($id=0)
  {
    $this->db->select('
      b.id_rincian_spp,
      b.id_rekening,
      b.id_kegiatan,
      k.kode_kegiatan_skpd,
      k.nama_kegiatan,
      r.kode_rekening,
      r.nama_rekening,
      b.nominal
    ');
    $this->db->from('rincian_spp b');
    $this->db->join('aktivitas a', 'a.id_aktivitas = b.id_aktivitas');
    $this->db->join('rekening r', 'r.id_rekening = b.id_rekening');
    $this->db->join('v_kegiatan_skpd k', 'b.id_kegiatan = k.id_kegiatan and k.id_skpd = a.id_skpd', 'LEFT');
    $this->db->where('b.id_aktivitas', $id);
    $result = $this->db->get()->result_array();

    return $result;
  }

  /* Query rincian Rekening PFK */
  /*function get_potongan_by_id($id=0)
  {
    $this->db->select('
      b.id_aktivitas,
      b.id_rekening,
      r.kode_rekening,
      r.nama_rekening,
      b.is_informasi,
      b.keterangan,
      b.nominal
    ');
    $this->db->from('rincian_spp_pfk b');
    $this->db->join('rekening r', 'r.id_rekening = b.id_rekening');
    $this->db->where('b.id_aktivitas', $id);
    $result = $this->db->get()->result_array();

    return $result;
  }*/

  /* Query rincian Rekening Pajak */
  function get_pajak_by_id($id=0)
  {
    $this->db->select('
      b.id_aktivitas,
      b.id_pajak,
      p.nama_pajak,
      r.id_rekening,
      r.kode_rekening,
      r.nama_rekening,
      p.persen,
      b.nominal,
      b.is_informasi,
      b.keterangan
    ');
    $this->db->from('rincian_spp_pajak b');
    $this->db->join('pajak p', 'p.id_pajak = b.id_pajak');
    $this->db->join('rekening r', 'r.id_rekening = p.id_rekening');
    $this->db->where('b.id_aktivitas', $id);
    $result = $this->db->get()->result_array();

    return $result;
  }

  function get_sisa_spd($param)
  {
    $this->db->select("a.nominal_sisa, a.nominal_sisa_tanggal");
    $this->db->from("sisa_spd_aktivitas("
        .$param['id_skpd'].", "
        .$this->tahun.", "
        ."'".prepare_date($param['tanggal'])."', "
        ."'".$param['beban']."', "
        .$param['id_aktivitas'].", "
        ."'".$param['arrspd']."') a");
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_sisa_kegiatan($param)
  {
    $arrspd = (is_array($param['arrspd']) ? join(',', $param['arrspd']) : $param['arrspd'] );

    $this->db->select('
        a.nominal,
        0 nominal_mp
    ');
    $this->db->from("sisa_sppjp_kegiatan("
        .$param['id_skpd'].", "
        .$this->tahun.", "
        .$param['id_kegiatan'].", "
        ."'".$param['beban']."', "
        .$param['id_aktivitas'].", "
        ."'".$arrspd."') a");
    $result = $this->db->get()->row_array();

    return $result;
  }

	function get_sisa_rekening($param)
	{
		$this->db->select('coalesce(sum(rin.pagu),0) nominal');
		$this->db->from('rincian_anggaran rin');
		$this->db->join("v_form_anggaran_lkp f", "f.id_form_anggaran = rin.id_form_anggaran");
		$this->db->join("tahun_anggaran t", "t.tahun = f.tahun and f.status = t.status_kini");
		$this->db->where('rin.id_rekening', $param['id_rekening'] );
		$this->db->where('f.id_skpd', $param['id_skpd'] );
		$this->db->where('f.tahun', $this->tahun);
		$this->db->group_by('rin.id_rekening');
		if($param['beban']=='BTL')$this->db->where('f.tipe', 'RKA21');
		else $this->db->where('f.tipe', 'RKA221');
		$anggaran = $this->db->get()->row_array();
		
		$this->db->select("
			coalesce(sum(iif(a.keperluan = 'LS', b.nominal, 0)), 0) spp_ls,
			coalesce(sum(iif(a.keperluan = 'GU', b.nominal, 0)), 0) spp_gu,
			coalesce(sum(iif(a.keperluan = 'GU NIHIL', b.nominal, 0)), 0) spp_gu_nihil
		");
		$this->db->from('spp a');
		$this->db->join('rincian_spp b','b.id_aktivitas=a.id_aktivitas');
		$this->db->where('a.beban', $param['beban']);
		$this->db->where('b.id_rekening', $param['id_rekening'] );
		if($param['id_spj'])
			$this->db->where('a.id_aktivitas_spj', $param['id_spj']);
		if($param['id_aktivitas'])
			$this->db->where('a.id_aktivitas <> ', $param['id_aktivitas']);
		$rinc_spp = $this->db->get()->row_array();
		
		$this->db->select("coalesce(sum(a.nominal),0) nominal_spj");
		$this->db->from('rincian_spj a');
		$this->db->where('a.id_rekening', $param['id_rekening']);
		$this->db->where('a.id_aktivitas', $param['id_spj']);
		$rinc_spj = $this->db->get()->row_array();
		
		if($param['beban']=='BTL')
			$sisa = $anggaran['NOMINAL'] - $rinc_spp['SPP_LS'] - $rinc_spp['SPP_GU'] - $rinc_spp['SPP_GU_NIHIL'];
		else 
			$sisa =  $anggaran['NOMINAL'] - $rinc_spp['SPP_LS'];
			
		$hasil = array('batas'=>$anggaran['NOMINAL'],'sisa'=>$sisa,'sisa_gu'=>($rinc_spp['SPP_GU']+$rinc_spp['SPP_GU_NIHIL']),'spj'=>$rinc_spj['NOMINAL_SPJ']);
		return $hasil;
	}

	function get_sisa_rekening_sts($param)
	{
		$rincian_spp = "
			select coalesce(sum(rp.nominal), 0)
			from v_spp p
			join rincian_spp rp on rp.id_aktivitas = p.id_aktivitas
			where p.tahun = ".$this->tahun."
			and p.id_skpd = ".$param['id_skpd']."
			and p.tanggal <= '".$param['tanggal']."'
			and p.keperluan = 'PP'
			and p.id_aktivitas <> ".$param['id_aktivitas']."
			and rp.id_rekening = rs.id_rekening
		";

		$this->db->select("coalesce(sum(rs.nominal), 0) - ($rincian_spp) nominal")
		->from("v_sts s")
		->join('rincian_sts rs', 'rs.id_aktivitas = s.id_aktivitas')
		->where("s.tanggal <=", prepare_date($param['tanggal']) )
		->where("s.id_skpd", $param['id_skpd'] )
		->where("s.tahun", $this->tahun )
		->group_by("rs.id_rekening, s.id_skpd, s.tahun");
		$result = $this->db->get()->row_array();

		return $result;
	}

	function get_sisa_skpd($param)
	{
		$this->db->select("a.nominal");
		$this->db->from("
			  pagu_anggaran_skpd(".$this->tahun.",".$param['id_skpd'].",'".$param['beban']."') a
		");
		$result = $this->db->get()->row_array();
		
		$this->db->select('coalesce(sum(b.nominal_rincian),0) nominal_rincian');
		$this->db->from('aktivitas a');
		$this->db->join('spp b', 'a.id_aktivitas = b.id_aktivitas');
		$this->db->where('b.beban', $param['beban']);    
		$this->db->where('a.tahun', $this->tahun);
		$this->db->where('a.id_skpd', $param['id_skpd']);
		if($param['beban']=='BL'){
			$this->db->where('b.keperluan', $param['keperluan']);
		}
		if($param['beban']=='BTL'){
			$this->db->where("b.keperluan <> 'UP' ");
		}
		if($param['spj'])	
			$this->db->where('b.id_aktivitas_spj', $param['spj']);
		$spp = $this->db->get()->row_array();

		return ($result['NOMINAL']-$spp['NOMINAL_RINCIAN']);
	}
  
	function get_sisa_spj($param)
	{
		$this->db->select('coalesce(sum(b.nominal),0) nominal');
		$this->db->from('aktivitas a');
		$this->db->join('spj b', 'a.id_aktivitas = b.id_aktivitas');
		$this->db->where('a.tahun', $this->tahun);
		$this->db->where('b.beban', 'BTL');
		$this->db->where('a.id_skpd', $param['id_skpd']);	
		//if($param['spj'])	
			//$this->db->where('a.id_aktivitas', $param['spj']);		
		$spj = $this->db->get()->row_array();
		
		$this->db->select('coalesce(sum(b.nominal_rincian),0) nominal');
		$this->db->from('aktivitas a');
		$this->db->join('spp b', 'a.id_aktivitas = b.id_aktivitas');
		$this->db->where('b.beban', 'BTL');    
		$this->db->where("b.keperluan in ('GU','GU NIHIL')");      
		$this->db->where('a.tahun', $this->tahun);
		$this->db->where('a.id_skpd', $param['id_skpd']);
		//if($param['spj'])	
			//$this->db->where('b.id_aktivitas_spj', $param['spj']);
		if($param['id'])	
			$this->db->where('a.id_aktivitas <>', $param['id']);
		$spp = $this->db->get()->row_array();

		//return ($spj['NOMINAL']-$spp['NOMINAL']);
		$hasil = array('spj'=>$spj['NOMINAL'],'spp'=>$spp['NOMINAL']);
		return $hasil;
	}

  /* function get_sisa_skpd($param)
  {
    $arrspd = (is_array($param['arrspd']) ? join(',', $param['arrspd']) : $param['arrspd'] );

    $this->db->select('a.nominal');
    $this->db->from("sisa_sppjp_skpd(".$param['id_skpd'].",
                    ".$this->tahun.",
                    '".prepare_date($param['tanggal'])."',
                    '".$param['beban']."',
                    ".$param['id'].",
                    '".$arrspd."' ) a");
    $result = $this->db->get()->row_array();

    return $result['NOMINAL'];
  } */

  function get_sisa_gu($param)
  {
    $this->db->select('sum(j.nominal) nominal')
      ->from('spj j')
      ->join('aktivitas a', 'a.id_aktivitas = j.id_aktivitas')
      ->where('j.keperluan', 'UP')
      ->where('a.tahun', $this->tahun)
      ->where('a.id_skpd', $param['id_skpd']);
    $spj = $this->db->get()->row_array();

    $this->db->select('sum(s.nominal) nominal')
      ->from('setoran_sisa s')
      ->join('aktivitas a', 'a.id_aktivitas = s.id_aktivitas')
      ->join('rincian_bku rb', 'rb.id_aktivitas_setoran_sisa = s.id_aktivitas')
      ->join('aktivitas b', 'b.id_aktivitas = rb.id_aktivitas')
      ->where('s.keperluan', 'UP')
      ->where('a.tahun', $this->tahun)
      ->where('a.id_skpd', $param['id_skpd'])
      ->where('b.tanggal <=', prepare_date($param['tanggal']));
    $ssu = $this->db->get()->row_array();

    $this->db->select('sum(s.nominal) nominal')
      ->from('spp s')
      ->join('aktivitas a', 'a.id_aktivitas = s.id_aktivitas')
      ->where('s.keperluan', 'GU')
      ->where('a.tahun', $this->tahun)
      ->where('a.id_skpd', $param['id_skpd'])
      ->where('a.id_aktivitas <>', $param['id']);
    $spp = $this->db->get()->row_array();

    return $spj['NOMINAL'] + $ssu['NOMINAL'] - $spp['NOMINAL'];
  }

  function get_sisa_sts($param)
  {
    $this->db->select("coalesce(sum(s.nominal), 0) nominal")
      ->from("v_sts s")
      ->where("s.tanggal <=", prepare_date($param['tanggal']) )
      ->where("s.id_skpd", $param['id_skpd'] )
      ->where("s.tahun", $this->tahun );
    $sts = $this->db->get()->row_array();

    $this->db->select("coalesce(sum(p.nominal), 0) nominal")
      ->from("v_spp p")
      ->where("p.tanggal <=", prepare_date($param['tanggal']) )
      ->where("p.id_skpd", $param['id_skpd'] )
      ->where("p.tahun", $this->tahun )
      ->where("p.keperluan", 'PP')
      ->where("p.id_aktivitas <>", $param['id']);
    $spp = $this->db->get()->row_array();

    return $sts['NOMINAL'] - $spp['NOMINAL'];
  }
  
  function get_dpa($param)
  {
    $arrkeg = (is_array($param['arrkeg']) ? join(',', $param['arrkeg']) : $param['arrkeg'] );

    switch ($param['beban'])
    {
      case 'BL'  : $tp = 'RKA221'; break;
      case 'BTL' : $tp = 'RKA21'; break;
      case 'KB'  : $tp = 'RKA32'; break;
      default : $tp = '';
    }

    $this->db->select("s.tanggal_dpa, sum(pagu) as pagu")
      ->from("v_form_anggaran_lkp f")
      ->join("status_anggaran s", "f.status = s.status and f.tahun = s.tahun")
      ->join("rincian_anggaran ra", "f.id_form_anggaran = ra.id_form_anggaran")
      ->where("f.tipe", $tp)
      ->where("f.tahun", $this->tahun)
      ->where("f.status", $this->status)
      ->where("f.id_skpd", $param['id_skpd'])
      ->group_by("s.tanggal_dpa");

    if ($param['beban'] === 'BL' )
    {
      $this->db->where_in("f.id_kegiatan", $arrkeg);
    }
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_no_dpa($param)
  {
    $this->db->select("s.kode_skpd_lkp")
      ->from("v_skpd s")
      ->where("s.id_skpd", $param['id_skpd']);
    $skpd = $this->db->get()->row_array();

    switch ($param['beban'])
    {
      case 'BTL' : $nomor = (isset($skpd['KODE_SKPD_LKP']) ? $skpd['KODE_SKPD_LKP'] : '').'.00.00.5.1'; break;
      case 'BL'  : $nomor = (isset($skpd['KODE_SKPD_LKP']) ? $skpd['KODE_SKPD_LKP'] : '').'.00.00.5.2'; break;
      case 'KB'  : $nomor = (isset($skpd['KODE_SKPD_LKP']) ? $skpd['KODE_SKPD_LKP'] : '').'.00.00.6.2'; break;
      default : $nomor = '';
    }

    return $nomor;
  }

  function check_dependency($id){
    $this->db->select('a.spm_pakai');
    $this->db->from('spp a');
    $this->db->where('a.id_aktivitas', $id);
    $result = $this->db->get()->row_array();

    if ($result && $result['SPM_PAKAI'] > 0 )
    {
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }
	
	function get_data_spj($id_spj,$id_skpd)
	{
		$this->db->select("
			a.id_rekening,
			b.kode_rekening,
			b.nama_rekening,
			(
				select coalesce(sum(e.nominal),0)  
				from spp d
				join rincian_spp e on e.id_aktivitas=d.id_aktivitas
				join aktivitas f on f.id_aktivitas=d.id_aktivitas
				where d.keperluan in ('GU','GU NIHIL')
				and e.id_rekening =a.id_rekening and f.tahun=".$this->tahun." and f.id_skpd=".$id_skpd."
				and d.id_aktivitas_spj = ".$id_spj." 
			) nominal_spp,
			coalesce(sum(a.nominal),0) nominal_spj
		");
		$this->db->from('rincian_spj a');
		$this->db->join('rekening b','b.id_rekening=a.id_rekening');
		$this->db->where('a.id_aktivitas', $id_spj);
		$this->db->group_by('1,2,3,4');
		$result = $this->db->get()->result_array();

		return $result;
	}
}
?>