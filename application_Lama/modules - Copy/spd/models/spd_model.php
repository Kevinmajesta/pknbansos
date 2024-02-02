<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spd_model extends Aktivitas_Model {

  var $fields;
  var $fieldmap;
  var $fieldmap_spd;
  var $fieldmap_rincian;
  var $fieldmap_rekening;
  var $data_spd;
  var $data_rincian;
  var $data_rekening;
  var $purge_kegiatan;
  var $purge_rekening;
  var $skpd;

  function __construct()
  {
    parent::__construct();

    $this->tipe = 'SPD';
    $this->skpd = $this->session->userdata('id_skpd');

    $this->fields = array(
        'ID_AKTIVITAS',
        'NOMOR',
        'TANGGAL',
        'KODE_SKPD_LKP',
        'NAMA_SKPD',
        'BEBAN',
        'NOMINAL',
        'TRIWULAN',
        'NAMA_KEGIATAN',
        'DESKRIPSI'
    );

    $this->fieldmap = array(
      'id' => 'a.ID_AKTIVITAS',
      'no' => 'a.NOMOR',
      'tgl' => 'a.TANGGAL',
      'kdskpd' => 's.KODE_SKPD_LKP',
      'nmskpd' => 's.NAMA_SKPD',
      'beban' => 'a.BEBAN',
      'nominal' => 'a.NOMINAL',
      'triwulan' => 'a.TRIWULAN',
      'keg' => 'a.NAMA_KEGIATAN',
      'deskripsi' => 'cast(substring(a.deskripsi from 1 for 5000) as varchar(5000))',
    );

    $this->fieldmap_spd = array(
      'id' => 'ID_AKTIVITAS',
      'triwulan' => 'TRIWULAN',
      'bln1' => 'BULAN_AWAL',
      'bln2' => 'BULAN_AKHIR',
      'total' => 'NOMINAL',
      'beban' => 'BEBAN',
      'no_dpa' => 'NO_DPA',
      'pptk' => 'ID_PPTK',
      'bk' => 'ID_BK',
      'bud' => 'ID_BUD',
    );

    $this->fieldmap_rincian = array(
      'id' => 'ID_AKTIVITAS',
      'idkeg' => 'ID_KEGIATAN',
      'nom' => 'NOMINAL',
    );

    $this->fieldmap_rekening = array(
      'idr' => 'ID_RINCIAN_REKENING_SPD',
      'id' => 'ID_AKTIVITAS',
      'idkeg' => 'ID_KEGIATAN',
      'idrek' => 'ID_REKENING',
      'nom' => 'NOMINAL',
    );
  }

  function get_grid_model()
  {
    $grid = array(
        'colNames' => array('Nomor', 'Tanggal', 'Kode SKPD', 'Nama SKPD', 'Beban', 'Nominal', 'Triwulan', 'Nama Kegiatan', 'Keterangan'),
        'colModel' => array(
          array('name' => 'no', 'width' => 200, 'sortable' => true),
          array('name' => 'tgl', 'width' => 80, 'sortable' => true, 'formatter' => 'date', 'align' => 'center'),
          array('name' => 'kdskpd', 'width' => 80, 'sortable' => true),
          array('name' => 'nmskpd','width' => 250, 'sortable' => true),
          array('name' => 'bbn', 'width' => 60, 'sortable' => true, 'align' => 'center'),
          array('name' => 'nom','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
          array('name' => 'tw', 'width' => 100, 'sortable' => true, 'align' => 'center'),
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
      $fields['kdskpd'] = array('name' => 'Kode SKPD', 'kategori'=>'string');
      $fields['nmskpd'] = array('name' => 'Nama SKPD', 'kategori'=>'string');
    }
    $fields['beban'] = array('name' => 'Beban', 'kategori'=>'predefined', 'options'=> array('BTL'=>'Beban Tidak Langsung', 'BL'=>'Beban Langsung', 'KB'=>'Pembiayaan'));
    $fields['nominal'] = array('name' => 'Nominal', 'kategori'=>'numeric');
    $fields['triwulan'] = array('name' => 'Triwulan', 'kategori'=>'predefined', 'options'=> array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4'));
    $fields['keg'] = array('name' => 'Nama Kegiatan', 'kategori'=>'string');
    $fields['deskripsi'] = array('name' => 'Keterangan', 'kategori'=>'string');

    return $fields;
  }

  function fill_data()
  {
    parent::fill_data();

    /* ambil data SPD */
    foreach($this->fieldmap_spd as $key => $value){
      $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      switch($key)
      {
        case 'nominal'   : $$key = $this->input->post($key) ? prepare_numeric($this->input->post($key)) : '0'; break;
        default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }

      if(isset($$key))
        $this->data_spd[$value] = $$key;
    }

    /* ambil data rincian Kegiatan SPD */
    $this->purge_kegiatan = $this->input->post('purge_keg'); $this->purge_kegiatan = $this->purge_kegiatan ? $this->purge_kegiatan : NULL;
    $keg = $this->input->post('keg') ? $this->input->post('keg') : NULL;
    if ($keg)
    {
      $keg = json_decode($keg);
      for ($i=0; $i <= count($keg) - 1; $i++) {
        foreach($this->fieldmap_rincian as $key => $value){
          $$key = isset($keg[$i]->$key) && $keg[$i]->$key ? $keg[$i]->$key : NULL;
          if(isset($$key))
            $this->data_rincian[$i][$value] = $$key;
        }
      }
    }

    /* ambil data rincian Rekening SPD */
    $this->purge_rekening = $this->input->post('purge_rek'); $this->purge_rekening = $this->purge_rekening ? $this->purge_rekening : NULL;
    $rek = $this->input->post('rek') ? $this->input->post('rek') : NULL;
    if ($rek)
    {
      $rek = json_decode($rek);
      for ($i=0; $i <= count($rek) - 1; $i++) {
        foreach($this->fieldmap_rekening as $key => $value){
          $$key = isset($rek[$i]->$key) && $rek[$i]->$key ? $rek[$i]->$key : NULL;
          if(isset($$key))
            $this->data_rekening[$i][$value] = $$key;
        }
      }
    }
  }

  /* Simpan data SPD */
  function insert_spd()
  {
    $this->db->select('1')->from('SPD')->where('ID_AKTIVITAS', $this->id);
    $rs = $this->db->get()->row_array();

    /* set bulan awal dan akhir sesuai triwulan */
    switch ($this->data_spd['TRIWULAN']){
      case 1 : $this->data_spd['BULAN_AWAL'] =  1; $this->data_spd['BULAN_AKHIR'] = 3; break;
      case 2 : $this->data_spd['BULAN_AWAL'] =  4; $this->data_spd['BULAN_AKHIR'] = 6; break;
      case 3 : $this->data_spd['BULAN_AWAL'] =  7; $this->data_spd['BULAN_AKHIR'] = 9; break;
      case 4 : $this->data_spd['BULAN_AWAL'] = 10; $this->data_spd['BULAN_AKHIR'] = 12; break;
    }

    if ($rs)
    {
      $this->db->where('ID_AKTIVITAS', $this->id);
      $this->db->update('SPD', $this->data_spd);
    }
    else
    {
      $this->data_spd['ID_AKTIVITAS'] = $this->id;
      $this->db->insert('SPD', $this->data_spd);
    }
  }

  /* Simpan rincian Kegiatan SPD */
  function insert_rincian()
  {
    if($this->purge_kegiatan)
    {
      $this->db->where_in('ID_KEGIATAN', $this->purge_kegiatan);
      $this->db->where('ID_AKTIVITAS', $this->id);
      $this->db->delete('RINCIAN_SPD');
    }

    for ($i=0; $i < count($this->data_rincian); $i++)
    {
      $idkeg = $this->data_rincian[$i]['ID_KEGIATAN'];
      $this->db->select('1')->from('RINCIAN_SPD')->where('ID_AKTIVITAS', $this->id)->where('ID_KEGIATAN', $idkeg);
      $rs = $this->db->get()->row_array();

      if ($rs)
      {
        $this->db->where('ID_AKTIVITAS', $this->id);
        $this->db->where('ID_KEGIATAN', $idkeg);
        $this->db->update('RINCIAN_SPD', $this->data_rincian[$i]);
      }
      else
      {
        $this->data_rincian[$i]['ID_AKTIVITAS'] = $this->id;
        $this->db->insert('RINCIAN_SPD', $this->data_rincian[$i]);
      }
    }
  }

  /* Simpan rincian Rekening SPD */
  function insert_rekening()
  {
    if($this->purge_rekening)
    {
      $this->db->where_in('ID_RINCIAN_REKENING_SPD', $this->purge_rekening);
      $this->db->delete('RINCIAN_REKENING_SPD');
    }

    $jml = count($this->data_rekening);
    for ($i=0; $i <= $jml - 1; $i++)
    {
      $idr = $this->data_rekening[$i]['ID_RINCIAN_REKENING_SPD'];
      $this->db->select('1')->from('RINCIAN_REKENING_SPD')->where('ID_RINCIAN_REKENING_SPD', $idr);
      $rs = $this->db->get()->row_array();

      if ($rs)
      {
        $this->db->where('ID_RINCIAN_REKENING_SPD', $idr);
        $this->db->update('RINCIAN_REKENING_SPD', $this->data_rekening[$i]);
      }
      else
      {
        unset( $this->data_rekening[$i]['ID_RINCIAN_REKENING_SPD'] );
        $this->data_rekening[$i]['ID_AKTIVITAS'] = $this->id;
        $this->db->insert('RINCIAN_REKENING_SPD', $this->data_rekening[$i]);
      }
    }
  }

  function save_detail()
  {
    $this->insert_spd();
    $this->insert_rincian();
    $this->insert_rekening();
  }

  /* Query daftar */
  function build_query_daftar()
  {
    $this->db->select('
      a.tahun,
      a.tipe,
      a.id_aktivitas,
      a.nomor,
      a.tanggal,
      cast(substring(a.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
      a.id_skpd,
      s.kode_skpd_lkp,
      s.nama_skpd,
      a.triwulan,
      a.id_bud,
      a.nominal,
      a.beban,
      a.nama_kegiatan
    ');
    $this->db->from('v_spd_kegiatan a');
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
      a.triwulan,
      a.bulan_awal,
      a.bulan_akhir,
      a.id_skpd,
      s.kode_skpd_lkp,
      s.nama_skpd,
      a.beban,
      a.id_bk,
      bk.nip bk_nip,
      bk.nama_pejabat bk_nama,
      a.id_bud,
      bud.nip bud_nip,
      bud.nama_pejabat bud_nama,
      a.id_pptk,
      pptk.nip pptk_nip,
      pptk.nama_pejabat pptk_nama,
      a.no_dpa,
      a.nominal
    ');
    $this->db->from('v_spd a');
    $this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
    $this->db->join('pejabat_skpd bk', 'bk.id_pejabat_skpd = a.id_bk','left');
    $this->db->join('pejabat_skpd pptk ', 'pptk.id_pejabat_skpd = a.id_pptk','left');
    $this->db->join('pejabat_daerah bud ', 'bud.id_pejabat_daerah = a.id_bud','left');
    $this->db->where('a.id_aktivitas', $id);
  }

  function build_query_hapus($id=0)
  {
    $this->db->where('id_aktivitas', $id)->delete('rincian_rekening_spd');
    $this->db->where('id_aktivitas', $id)->delete('rincian_spd');
    $this->db->where('id_aktivitas', $id)->delete('spd');
    $this->db->where('id_aktivitas', $id)->delete('aktivitas');
  }

  /* Query rincian Kegiatan SPD */
  function get_kegiatan_by_id($id)
  {
    $this->db->select('
      b.id_kegiatan,
      k.kode_kegiatan_skpd,
      k.nama_kegiatan,
      b.nominal'
    );
    $this->db->from('aktivitas a');
    $this->db->join('rincian_spd b', 'b.id_aktivitas = a.id_aktivitas');
    $this->db->join('v_kegiatan_skpd k', 'k.id_kegiatan = b.id_kegiatan and k.id_skpd = a.id_skpd', 'LEFT');
    $this->db->where('a.id_aktivitas', $id);
    $this->db->order_by('k.kode_kegiatan_skpd');
    $result = $this->db->get()->result_array();

    return $result;
  }

  /* Query rincian Rekening SPD */
  function get_rekening_by_id($id)
  {
    $this->db->select('
      b.id_rincian_rekening_spd,
      b.id_kegiatan,
      b.id_rekening,
      k.kode_kegiatan_skpd,
      k.nama_kegiatan,
      r.kode_rekening,
      r.nama_rekening,
      b.nominal'
    );
    $this->db->from('aktivitas a');
    $this->db->join('rincian_rekening_spd b', 'b.id_aktivitas = a.id_aktivitas');
    $this->db->join('rekening r', 'r.id_rekening = b.id_rekening');
    $this->db->join('v_kegiatan_skpd k', 'k.id_kegiatan = b.id_kegiatan and k.id_skpd = a.id_skpd','LEFT');
    $this->db->where('a.id_aktivitas', $id);
    $this->db->order_by('k.kode_kegiatan_skpd, r.kode_rekening');
    $result = $this->db->get()->result_array();

    return $result;
  }

  function get_sisa_rekening($param)
  {
    $this->db->select("
          a.nominal_anggaran,
          a.nominal,
          a.nominal_spd,
    ");
    $this->db->from("
          sisa_dpa_rekening("
            .$param['id_skpd'].", "
            .$this->tahun.", "
            .$param['id_kegiatan'].", "
            .$param['id_rekening'].", "
            ."'".$param['beban']."', "
            .$param['id_aktivitas'].",
            null
          ) a
    ");
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_sisa_kegiatan($param)
  {
    $this->db->select('
          a.nominal_anggaran,
          a.triwulan_1,
          a.triwulan_2,
          a.triwulan_3,
          a.triwulan_4,
          a.nominal_spd,
          a.nominal_spd_tanggal,
          a.nominal,
    ');
    $this->db->from("
          sisa_dpa_kegiatan("
            .$param['id_skpd'].", "
            .$this->tahun.", "
            .$param['id_kegiatan'].", "
            ."'".$param['beban']."', "
            .$param['id_aktivitas'].", "
            ."'".prepare_date($param['tanggal'])."'
          ) a
    ");
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_sisa_skpd($param)
  {
    $this->db->select("a.nominal");
    $this->db->from("
          sisa_dpa_skpd("
            .$param['id_skpd'].", "
            .$this->tahun.", "
            ."'".$param['beban']."', "
            .$param['id_aktivitas'].") a
    ");
    $result = $this->db->get()->row_array();

    return $result['NOMINAL'];
  }

  /* cek apakah SPD sudah dipakai di SPP */
  function check_dependency($id){
    $this->db->select('a.ID_AKTIVITAS');
    $this->db->select('(select count(*) from SPP_PAKAI_SPD b where b.ID_AKTIVITAS_SPD = a.ID_AKTIVITAS) SPP_PAKAI_SPD_PAKAI');
    $this->db->where('a.ID_AKTIVITAS', $id);
    $result = $this->db->get('SPD a')->row_array();

    if ( $result['SPP_PAKAI_SPD_PAKAI'] > 0 ) {
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }

}
?>