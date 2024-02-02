<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rka221_model extends Rka_Model {

  var $fields;
  var $fieldmap;
  var $fieldmap_rincian;
  var $data_rincian;
  var $fieldmap_rka221;
  var $data_rka221;
  var $fieldmap_indikator;
  var $data_indikator;
  var $fieldmap_pembahasan;
  var $data_pembahasan;
  var $fieldmap_lokasi;
  var $data_lokasi;
  var $fieldmap_sumberdana;
  var $data_sumberdana;

  function __construct()
  {
    parent::__construct();

    $this->tipe = 'RKA221';
    $this->lanjutan = 0;

    $this->fields = array(
      'ID_FORM_ANGGARAN',
      'KODE_SKPD_LKP',
      'NAMA_SKPD',
      'KODE_KEGIATAN_SKPD',
      'NAMA_KEGIATAN',
      'NOMINAL_RKA',
    );

    $this->fieldmap = array(
      'kdskpd' => 's.KODE_SKPD_LKP',
      'nmskpd' => 's.NAMA_SKPD',
      'kdkeg' => 'a.KODE_KEGIATAN_SKPD',
      'nmkeg' => 'a.NAMA_KEGIATAN',
      'pagu' => 'a.NOMINAL_ANGGARAN',
    );

    $this->fieldmap_rincian = array(
      'id' => 'ID_FORM_ANGGARAN',
      'idra' => 'ID_RINCIAN_ANGGARAN',
      'iddr' => 'ID_DETIL_REKENING',
      'idrek' => 'ID_REKENING',
      'uraian' => 'URAIAN',
      'vol' => 'VOLUME',
      'unit' => 'SATUAN',
      'trf' => 'TARIF',
      'jml_next' => 'PAGU_TAHUN_DEPAN',
      'no' => 'NO_URUT',
      'ket' => 'KETERANGAN',
    );

    $this->fieldmap_rka221 = array(
      'id' => 'ID_FORM_ANGGARAN',
      'id_keg' => 'ID_KEGIATAN',
      'tahun_lalu' => 'PAGU_TAHUN_LALU',
      'tahun_depan' => 'PAGU_TAHUN_DEPAN',
      'sasaran' => 'KELOMPOK_SASARAN_KEGIATAN',
    );

    $this->fieldmap_indikator = array(
      'id_indi' => 'ID_INDIKATOR_KINERJA',
      'id' => 'ID_FORM_ANGGARAN',
      'tipe' => 'TIPE_INDIKATOR',
      'jenis' => 'NAMA_INDIKATOR',
      'tolok' => 'TOLOK_UKUR',
      'target' => 'TARGET',
      'no' => 'NO_URUT',
      'jml' => 'JUMLAH_TARGET',
    );

    $this->fieldmap_pembahasan = array(
      'id' => 'ID_FORM_ANGGARAN',
      'no' => 'NO_URUT',
      'ket' => 'CATATAN',
    );

    $this->fieldmap_lokasi = array(
      'id' => 'ID_FORM_ANGGARAN',
      'lok' => 'ID_LOKASI',
      'nom_lokasi' => 'NOMINAL',
    );

    $this->fieldmap_sumberdana = array(
      'id' => 'ID_FORM_ANGGARAN',
      'idsd' => 'ID_SUMBER_DANA',
      'nom_sd' => 'NOMINAL',
    );
  }

  function get_grid_model()
  {
    $grid = array(
        'colNames' => array('Kode SKPD', 'Nama SKPD', 'Kode Kegiatan', 'Nama Kegiatan', 'Nominal'),
        'colModel' => array(
          array('name' => 'kd_skpd', 'width' => 100, 'sortable' => true),
          array('name' => 'nm_skpd', 'width' => 200, 'sortable' => true),
          array('name' => 'kd_keg', 'width' => 120, 'sortable' => true),
          array('name' => 'nm_keg','width' => 200, 'sortable' => true),
          array('name' => 'pagu','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
        ),
    );
    return $grid;
  }

  function fill_data()
  {
    parent::fill_data();

		$this->purge_indikator = $this->input->post('purge_indi'); $this->purge_indikator = $this->purge_indikator ? $this->purge_indikator : NULL;
		$this->purge_rincian = $this->input->post('purge_rincian'); $this->purge_rincian = $this->purge_rincian ? $this->purge_rincian : NULL;
		$this->purge_pembahasan = $this->input->post('purge_pembahasan'); $this->purge_pembahasan = $this->purge_pembahasan ? $this->purge_pembahasan : NULL;
		$this->purge_lokasi = $this->input->post('purge_lokasi'); $this->purge_lokasi = $this->purge_lokasi ? $this->purge_lokasi : NULL;
		$this->purge_sumberdana = $this->input->post('purge_sumberdana'); $this->purge_sumberdana = $this->purge_sumberdana ? $this->purge_sumberdana : NULL;

    /* ambil grid indikator */
    $indi = $this->input->post('indikator') ? $this->input->post('indikator') : NULL;
    if ($indi)
    {
      $indi = json_decode($indi);
      for ($i=0; $i <= count($indi) - 1; $i++) {
        foreach($this->fieldmap_indikator as $key => $value){
          switch ($key)
          {
//            case 'id_indi' : $$key = $this->input->post($key) ? $this->input->post($key) : NULL; break;
            default : $$key = isset($indi[$i]->$key) ? $indi[$i]->$key : NULL;
          }
          if(isset($$key))
            $this->data_indikator[$i][$value] = $$key;
        }
      }
    }

    /* ambil grid rincian anggaran dan detil rekening*/
    $rinci = $this->input->post('rincian') ? $this->input->post('rincian') : NULL;
    if ($rinci)
    {
      $rinci = json_decode($rinci);
      for ($i=0; $i <= count($rinci) - 1; $i++) {
        foreach($this->fieldmap_rincian as $key => $value){
          switch ($key)
          {
            case 'uraian' : $$key = isset($rinci[$i]->$key) && $rinci[$i]->$key ? $rinci[$i]->$key : ''; break;
            case 'vol' : $$key = isset($rinci[$i]->$key) && $rinci[$i]->$key ? $rinci[$i]->$key : 0; break;
            case 'trf' : $$key = isset($rinci[$i]->$key) && $rinci[$i]->$key ? $rinci[$i]->$key : 0; break;
            default : $$key = isset($rinci[$i]->$key) && $rinci[$i]->$key ? $rinci[$i]->$key : NULL;
          }
          if(isset($$key))
            $this->data_rincian[$i][$value] = $$key;
        }
      }
    }
    
    /* ambil grid pembahasan anggaran */
    $bahas = $this->input->post('bahasan') ? $this->input->post('bahasan') : NULL;
    if ($bahas)
    {
      $bahas = json_decode($bahas);
      for ($i=0; $i <= count($bahas) - 1; $i++) {
        foreach($this->fieldmap_pembahasan as $key => $value){
          switch ($key)
          {
            default : $$key = isset($bahas[$i]->$key) && $bahas[$i]->$key ? $bahas[$i]->$key : NULL;
          }
          if(isset($$key))
            $this->data_pembahasan[$i][$value] = $$key;
        }
      }
    }

    /* ambil grid lokasi */
    $lokasi = $this->input->post('lokasi') ? $this->input->post('lokasi') : NULL;
    if ($lokasi)
    {
      $lokasi = json_decode($lokasi);
      for ($i=0; $i <= count($lokasi) - 1; $i++) {
        foreach($this->fieldmap_lokasi as $key => $value){
          switch ($key)
          {
            default : $$key = isset($lokasi[$i]->$key) && $lokasi[$i]->$key ? $lokasi[$i]->$key : NULL;
          }
          if(isset($$key))
            $this->data_lokasi[$i][$value] = $$key;
        }
      }
    }
    
    /* ambil grid sumber dana */
    $sd = $this->input->post('sumberdana') ? $this->input->post('sumberdana') : NULL;
    if ($sd)
    {
      $sd = json_decode($sd);
      for ($i=0; $i <= count($sd) - 1; $i++) {
        foreach($this->fieldmap_sumberdana as $key => $value){
          switch ($key)
          {
            default : $$key = isset($sd[$i]->$key) && $sd[$i]->$key ? $sd[$i]->$key : NULL;
          }
          if(isset($$key))
            $this->data_sumberdana[$i][$value] = $$key;
        }
      }
    }
    
    /* ambil data RKA 221 */
    foreach($this->fieldmap_rka221 as $key => $value){
			switch ($key){
					case 'id' : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;break;
					default : $$key = $this->input->post($key);
				}
			if(isset($$key))
				$this->data_rka221[$value] = $$key;
    }

  }

  /* Simpan data rincian */
  function insert_rincian()
  {
    if ($this->purge_rincian)
    {
      $this->db->where_in('ID_RINCIAN_ANGGARAN', $this->purge_rincian);
      $this->db->delete('RINCIAN_ANGGARAN');
    }

    for ($i=0; $i<=count($this->data_rincian)-1; $i++)
    {
      $iddr = isset($this->data_rincian[$i]['ID_DETIL_REKENING']) ? $this->data_rincian[$i]['ID_DETIL_REKENING'] : NULL;
      $this->db->select('1')->from('DETIL_REKENING')->where('ID_DETIL_REKENING', $iddr);
      $rsiddr = $this->db->get()->row_array();
      
      if ($rsiddr)
      {
        $this->data_rincian[$i]['ID_DETIL_REKENING'] = $iddr;
      }
      else
      {
        $this->data_detil_rek = array(
              'ID_REKENING' => $this->data_rincian[$i]['ID_REKENING'], 'KODE_DETIL_REKENING' => '', 'URAIAN' => $this->data_rincian[$i]['URAIAN']);
        $this->db->insert('DETIL_REKENING', $this->data_detil_rek);
        $this->db->select_max('ID_DETIL_REKENING')->from('DETIL_REKENING');
        $rs = $this->db->get()->row_array();
        $this->data_rincian[$i]['ID_DETIL_REKENING'] = $rs['ID_DETIL_REKENING'];
      }

      $idra = isset($this->data_rincian[$i]['ID_RINCIAN_ANGGARAN']) ? str_replace('new_', 0, $this->data_rincian[$i]['ID_RINCIAN_ANGGARAN']) : NULL;
      $this->db->select('1')->from('RINCIAN_ANGGARAN')->where('ID_FORM_ANGGARAN', $this->id)->where('ID_RINCIAN_ANGGARAN', $idra);
      $rs = $this->db->get()->row_array();

      if ($rs)
      {
        $this->data_rincian[$i]['NO_URUT'] = $i+1;
        $this->db->where('ID_RINCIAN_ANGGARAN', $idra);
        $this->db->where('ID_FORM_ANGGARAN', $this->id);
        $this->db->update('RINCIAN_ANGGARAN', $this->data_rincian[$i]);
      }
      else
      {
        unset( $this->data_rincian[$i]['ID_RINCIAN_ANGGARAN'] );
        $this->data_rincian[$i]['NO_URUT'] = $i+1;
        $this->data_rincian[$i]['ID_FORM_ANGGARAN'] = $this->id;
        $this->db->insert('RINCIAN_ANGGARAN', $this->data_rincian[$i]);
      }

    }
  }

  /* Simpan data rka221 */
  function insert_rka221()
  {
    $this->db->select('1')->from('RKA_DPA_SKPD_221 ')->where('ID_FORM_ANGGARAN', $this->id);
    $rs = $this->db->get()->row_array();
    
    if ($rs)
    {
      $this->db->where('ID_FORM_ANGGARAN', $this->id);
      $this->db->update('RKA_DPA_SKPD_221', $this->data_rka221);
    }
    else
    {
      $this->data_rka221['ID_FORM_ANGGARAN'] = $this->id;
      $this->db->insert('RKA_DPA_SKPD_221', $this->data_rka221);
    }
  }

  /* Simpan data indikator */
  function insert_indikator()
  {
    if ($this->purge_indikator)
    {
      $this->db->where_in('ID_INDIKATOR_KINERJA', $this->purge_pembahasan);
      $this->db->where('ID_FORM_ANGGARAN', $this->id);
      $this->db->delete('INDIKATOR_KINERJA');
    }

    for ($i=0; $i<=count($this->data_indikator)-1; $i++)
    {
      $id_indi = !empty($this->data_indikator[$i]['ID_INDIKATOR_KINERJA']) ? $this->data_indikator[$i]['ID_INDIKATOR_KINERJA'] : 0;
      $this->db->select('1')->from('INDIKATOR_KINERJA')->where('ID_FORM_ANGGARAN', $this->id)->where('ID_INDIKATOR_KINERJA', $id_indi);
      $rs = $this->db->get()->row_array();
      
      if ($rs)
      {
        $this->data_indikator[$i]['NO_URUT'] = $i+1;
        $this->db->where('ID_INDIKATOR_KINERJA', $id_indi);
        $this->db->where('ID_FORM_ANGGARAN', $this->id);
        $this->db->update('INDIKATOR_KINERJA ', $this->data_indikator[$i]);
      }
      else
      {
        unset ($this->data_indikator[$i]['ID_INDIKATOR_KINERJA']);
        $this->data_indikator[$i]['NO_URUT'] = $i+1;
        $this->data_indikator[$i]['ID_FORM_ANGGARAN'] = $this->id;
        $this->db->insert('INDIKATOR_KINERJA ', $this->data_indikator[$i]);
      }
    }
  }

  /* Simpan data pembahasan */
  function insert_pembahasan()
  {
    if ($this->purge_pembahasan)
    {
      $this->db->where_in('NO_URUT', $this->purge_pembahasan);
      $this->db->where('ID_FORM_ANGGARAN', $this->id);
      $this->db->delete('CATATAN_PEMBAHASAN_ANGGARAN');
    }
    
    for ($i=0; $i<=count($this->data_pembahasan)-1; $i++)
    {
      $no_urut = isset($this->data_pembahasan[$i]['NO_URUT']) ? $this->data_pembahasan[$i]['NO_URUT'] : NULL;
      $this->db->select('1')->from('CATATAN_PEMBAHASAN_ANGGARAN')->where('ID_FORM_ANGGARAN', $this->id)->where('NO_URUT', $no_urut);
      $rs = $this->db->get()->row_array();
      
      if ($rs)
      {
        $this->data_pembahasan[$i]['NO_URUT'] = $i+1;
        $this->db->where('NO_URUT', $no_urut);
        $this->db->where('ID_FORM_ANGGARAN', $this->id);
        $this->db->update('CATATAN_PEMBAHASAN_ANGGARAN ', $this->data_pembahasan[$i]);
      }
      else
      {
        $this->data_pembahasan[$i]['NO_URUT'] = $i+1;
        $this->data_pembahasan[$i]['ID_FORM_ANGGARAN'] = $this->id;
        $this->db->insert('CATATAN_PEMBAHASAN_ANGGARAN ', $this->data_pembahasan[$i]);
      }
    }
  }

  /* Simpan data lokasi */
  function insert_lokasi()
  {
    if ($this->purge_lokasi)
    {
      $this->db->where_in('ID_LOKASI', $this->purge_lokasi);
      $this->db->where('ID_FORM_ANGGARAN', $this->id);
      $this->db->delete('LOKASI_KEGIATAN');
    }

    for ($i=0; $i<=count($this->data_lokasi)-1; $i++)
    {
      $lok = isset($this->data_lokasi[$i]['ID_LOKASI']) ? $this->data_lokasi[$i]['ID_LOKASI'] : NULL;
      $this->db->select('1')->from('LOKASI_KEGIATAN')->where('ID_FORM_ANGGARAN', $this->id)->where('ID_LOKASI', $lok);
      $rs = $this->db->get()->row_array();
      
      if ($rs)
      {
        $this->db->where('ID_LOKASI', $lok);
        $this->db->where('ID_FORM_ANGGARAN', $this->id);
        $this->db->update('LOKASI_KEGIATAN', $this->data_lokasi[$i]);
      }
      else
      {
        $this->data_lokasi[$i]['ID_FORM_ANGGARAN'] = $this->id;
        $this->db->insert('LOKASI_KEGIATAN', $this->data_lokasi[$i]);
      }
    }
  }

  /* Simpan data sumberdana */
  function insert_sumberdana()
  {
    if ($this->purge_sumberdana)
    {
      $this->db->where_in('ID_SUMBER_DANA', $this->purge_sumberdana);
      $this->db->where('ID_FORM_ANGGARAN', $this->id);
      $this->db->delete('SUMBER_DANA_KEGIATAN');
    }

    for ($i=0; $i<=count($this->data_sumberdana)-1; $i++)
    {
      $idsd = isset($this->data_sumberdana[$i]['ID_SUMBER_DANA']) ? $this->data_sumberdana[$i]['ID_SUMBER_DANA'] : NULL;
      $this->db->select('1')->from('SUMBER_DANA_KEGIATAN')->where('ID_FORM_ANGGARAN', $this->id)->where('ID_SUMBER_DANA', $idsd);
      $rs = $this->db->get()->row_array();
      
      if ($rs)
      {
        $this->db->where('ID_SUMBER_DANA', $idsd);
        $this->db->where('ID_FORM_ANGGARAN', $this->id);
        $this->db->update('SUMBER_DANA_KEGIATAN', $this->data_sumberdana[$i]);
      }
      else
      {
        $this->data_sumberdana[$i]['ID_FORM_ANGGARAN'] = $this->id;
        $this->db->insert('SUMBER_DANA_KEGIATAN', $this->data_sumberdana[$i]);
      }
    }
  }

  function save_detail()
  {
    $this->insert_rincian();
    $this->insert_rka221();
    $this->insert_pembahasan();
    $this->insert_indikator();
    $this->insert_lokasi();
    $this->insert_sumberdana();
  }

  /* Query daftar */
  function build_query_daftar()
  {
    $this->db->select('
        a.id_form_anggaran,
        a.tipe,
        a.id_skpd,
        s.kode_skpd_lkp,
        s.nama_skpd,
        a.kode_kegiatan_skpd,
        a.id_kegiatan,
        a.nama_kegiatan,
        a.nominal_lokasi nominal_lokasi_rka,
        a.nominal_sumber_dana nominal_sumber_dana_rka,
        a.nominal_anggaran,
        a.nominal_anggaran as nominal_rka,
        a.triwulan_1 + a.triwulan_2 + a.triwulan_3 + a.triwulan_4 as nominal_dpa
    ');
    $this->db->from('v_form_anggaran_lkp a');
    $this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
    $this->db->where('a.tahun', $this->tahun);
    $this->db->where('a.status', $this->status);
    $this->db->where('a.tipe', $this->tipe);
    $this->db->where('a.lanjutan', $this->lanjutan);
    /* filter skpd jika diset */
    if ($this->session->userdata('id_skpd') != 0) $this->db->where('a.id_skpd', $this->session->userdata('id_skpd'));
  }

  /* Query form */
  function build_query_form($id=0)
  {
    $this->db->select('
        a.id_form_anggaran,
        a.tahun,
        a.status,
        a.tipe,
        a.id_skpd,
        s.kode_skpd_lkp,
        s.nama_skpd,
        a.id_kegiatan,
        a.kode_kegiatan_skpd,
        a.nama_kegiatan,
        cast(substring(a.kelompok_sasaran_kegiatan from 1 for 5000) as varchar(5000)) kelompok_sasaran_kegiatan,
        cast(substring(a.keterangan from 1 for 5000) as varchar(5000)) keterangan,
        a.tanggal_pembahasan,
        a.id_pejabat_skpd,
        p1.nama_pejabat,
        a.pagu_tahun_lalu,
        a.pagu_tahun_depan,
        a.nominal_anggaran
    ');
    $this->db->from('v_form_anggaran_lkp a');
    $this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
    $this->db->join('pejabat_skpd p1', 'p1.id_pejabat_skpd = a.id_pejabat_skpd', 'left');
    $this->db->where('a.id_form_anggaran', $id);
  }

  function get_indikator_murni($id=0, $id_keg=null)
  {
   $result = $this->db->query("
        select 
          ik.tipe_indikator, 
          ik.tolok_ukur, 
          ik.target, 
          ik.jumlah_target
        from v_form_anggaran_lkp fa 
        join tahun_anggaran ta on ta.tahun = fa.tahun 
        join v_form_anggaran_lkp fm on fm.tahun = fa.tahun 
          and fm.id_skpd = fa.id_skpd 
          and fm.tipe = fa.tipe
          and (fm.tipe <> 'RKA21' or fm.lanjutan = fa.lanjutan) 
          and fm.status = ta.status_awal
        join indikator_kinerja ik on ik.id_form_anggaran = fm.id_form_anggaran 
        where fa.id_form_anggaran = ".$id."
          and fa.lanjutan = 0
          and fa.id_kegiatan = ".$id_keg."
    ");
    return $result->result_array();
  }

  function build_query_hapus($id=0)
  {
    $this->db->where('id_form_anggaran', $id)->delete('rincian_anggaran');
    $this->db->where('id_form_anggaran', $id)->delete('rka_dpa_skpd_221');
    $this->db->where('id_form_anggaran', $id)->delete('catatan_pembahasan_anggaran');
    $this->db->where('id_form_anggaran', $id)->delete('indikator_kinerja');
    $this->db->where('id_form_anggaran', $id)->delete('lokasi_kegiatan');
    $this->db->where('id_form_anggaran', $id)->delete('sumber_dana_kegiatan');
    $this->db->where('id_form_anggaran', $id)->delete('form_anggaran');
  }
  
  function data_exists()
  {
    $mode = $this->input->post('mode');
    
    $this->db->select('count(fa.id_form_anggaran) anggaran_exists');
    $this->db->from('v_form_anggaran_lkp fa');
    $this->db->where('fa.id_skpd', $this->data_fa['ID_SKPD']);
    $this->db->where('fa.id_kegiatan', $this->data_rka221['ID_KEGIATAN']);
    $this->db->where('fa.tahun', $this->tahun);
    $this->db->where('fa.status', $this->status);
    $result = $this->db->get()->row_array();
    
    if ($result && $result['ANGGARAN_EXISTS'] > 0 && $mode == 'new') {
      return FALSE; 
    } else {
      return TRUE;
    }
  }

}