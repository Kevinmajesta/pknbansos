<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sp2d_model extends Aktivitas_Model {

  var $fields;
  var $fieldmap;
  var $fieldmap_sp2d;
  var $data_sp2d;
  var $skpd;

  function __construct()
  {
    parent::__construct();

    $this->tipe = 'SP2D';
    $this->skpd = $this->session->userdata('id_skpd');

    $this->fields = array(
      'ID_AKTIVITAS',
      'NOMOR',
      'TANGGAL',
      'KODE_SKPD_LKP',
      'NAMA_SKPD',
      'KEPERLUAN',
      'BEBAN',
      'NOMINAL_KOTOR',
//      'NOMINAL_PFK',
//      'NOMINAL_PFK_INFORMASI',
      'NOMINAL_PAJAK',
      'NOMINAL_PAJAK_INFORMASI',
      'NOMINAL',
      'NAMA_PROGRAM',
      'NAMA_KEGIATAN',
      'NAMA_SUMBER_DANA',
      'NOMOR_SPM',
      'DESKRIPSI',
    );

    $this->fieldmap = array(
      'no' => 'a.NOMOR',
      'tgl' => 'a.TANGGAL',
      'kdskpd' => 's.KODE_SKPD_LKP',
      'nmskpd' => 's.NAMA_SKPD',
      'kpl' => 'a.KEPERLUAN',
      'bbn' => 'a.BEBAN',
      'bruto' => 'a.NOMINAL_KOTOR',
      //'pfk' => 'a.NOMINAL_PFK',
      //'pfkinfo' => 'a.NOMINAL_PFK_INFORMASI',
      'pjk' => 'a.NOMINAL_PAJAK',
      'pjkinfo' => 'a.NOMINAL_PAJAK_INFORMASI',
      'nom' => 'a.NOMINAL',
      'prog' => 'a.NAMA_PROGRAM',
      'keg' => 'a.NAMA_KEGIATAN',
      'sd' => 'b2.NAMA_SUMBER_DANA',
      'nospm' => 'a.NOMOR_SPM',
      'ket' => 'a.DESKRIPSI',
    );

    $this->fieldmap_sp2d = array(
      'id' => 'ID_AKTIVITAS',
      'id_spm' => 'ID_AKTIVITAS_SPM',
      'id_sd'=> 'ID_SUMBER_DANA',
      'id_pekas'=> 'ID_REKENING_PEKAS',
      'bud' => 'ID_BUD',
    );
  }

  function get_grid_model()
  {
    $grid = array(
        'colNames' => array('Nomor', 'Tanggal', 'Kode SKPD', 'Nama SKPD', 'Keperluan', 'Beban', 'Nominal Kotor'/*, 'PFK (Potongan)', 'PFK (Informasi)'*/, 'Pajak (Potongan)', 'Pajak (Informasi)', 'Nominal Bersih', 'Nama Program', 'Nama Kegiatan', 'Sumber Dana', 'Nomor SPM', 'Keterangan'),
        'colModel' => array(
          array('name' => 'no', 'width' => 200, 'sortable' => true),
          array('name' => 'tgl', 'width' => 80, 'sortable' => true, 'formatter' => 'date', 'align' => 'center'),
          array('name' => 'kdskpd', 'width' => 80, 'sortable' => true),
          array('name' => 'nmskpd','width' => 250, 'sortable' => true),
          array('name' => 'kpl', 'width' => 60, 'sortable' => true, 'align' => 'center'),
          array('name' => 'bbn', 'width' => 60, 'sortable' => true, 'align' => 'center'),
          array('name' => 'bruto','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
          //array('name' => 'pfk','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
          //array('name' => 'pfk_info','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
          array('name' => 'pjk','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
          array('name' => 'pjk_info','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
          array('name' => 'nom','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
          array('name' => 'prog','width' => 200, 'sortable' => true),
          array('name' => 'keg','width' => 200, 'sortable' => true),
          array('name' => 'sd','width' => 100, 'sortable' => true),
          array('name' => 'nospm','width' => 100, 'sortable' => true),
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
    $fields['kpl'] = array('name' => 'Keperluan', 'kategori'=>'predefined', 'options'=> array('UP'=>'UP', 'LS'=>'LS', 'GU'=>'GU', 'GU NIHIL'=>'GU NIHIL'));
    $fields['bbn'] = array('name' => 'Beban', 'kategori'=>'predefined', 
                          'options'=> array('-'=>'Tidak Ada', 'GJ'=>'Belanja Gaji', 'BTL'=>'Beban Tidak Langsung', 'BL'=>'Beban Langsung', 'KB'=>'Pembiayaan'));
    $fields['bruto'] = array('name' => 'Nominal Kotor', 'kategori'=>'numeric');
    // $fields['pfk'] = array('name' => 'PFK (Potongan)', 'kategori'=>'numeric');
    // $fields['pfkinfo'] = array('name' => 'PFK (Informasi)', 'kategori'=>'numeric');
    $fields['pjk'] = array('name' => 'Pajak (Potongan)', 'kategori'=>'numeric');
    $fields['pjkinfo'] = array('name' => 'Pajak (Informasi)', 'kategori'=>'numeric');
    $fields['nom'] = array('name' => 'Nominal Bersih', 'kategori'=>'numeric');
    $fields['prog'] = array('name' => 'Nama Program', 'kategori'=>'string');
    $fields['keg'] = array('name' => 'Nama Kegiatan', 'kategori'=>'string');
    $fields['sd'] = array('name' => 'Sumber Dana', 'kategori'=>'string');
    $fields['nospm'] = array('name' => 'Nomor SPM', 'kategori'=>'string');
    $fields['ket'] = array('name' => 'Keterangan', 'kategori'=>'string');
    
    return $fields;
  }

  function fill_data()
  {
    parent::fill_data();

    /* ambil data SP2D */
    foreach($this->fieldmap_sp2d as $key => $value){
      $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      if(isset($$key))
        $this->data_sp2d[$value] = $$key;
    }
  }

  /* Simpan data SP2D */
  function insert_sp2d()
  {
    $this->db->select('1')->from('SP2D')->where('ID_AKTIVITAS', $this->id);
    $rs = $this->db->get()->row_array();

    if ($rs)
    {
      $this->db->where('ID_AKTIVITAS', $this->id);
      $this->db->update('SP2D', $this->data_sp2d);
    }
    else
    {
      $this->data_sp2d['ID_AKTIVITAS'] = $this->id;
      $this->db->insert('SP2D', $this->data_sp2d);
    }
  }

  function save_detail()
  {
    $this->insert_sp2d();
  }

  /* Query daftar */
  function build_query_daftar()
  {
    $this->db->select('
      a.id_aktivitas,
      a.nomor,
      a.tanggal,
      a.tanggal_cair,
      cast(substring(a.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
      a.id_skpd,
      s.kode_skpd,
      s.kode_skpd_lkp,
      s.nama_skpd,
      b2.nama_sumber_dana,
      a.no_bku,
      a.id_aktivitas_spm,
      a.nominal,
      a.nominal_rincian,
      a.nomor_spm,
      a.keperluan,
      a.beban,
      a.nama_kegiatan,
      a.nama_program,
      a.nominal_kotor,
		a.nominal_pfk,
      a.nominal_pfk_informasi,
      a.nominal_pajak,
      a.nominal_pajak_informasi
    ');
    $this->db->from('v_sp2d_kegiatan a');
    $this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
    $this->db->join('sumber_dana b2', 'b2.id_sumber_dana = a.id_sumber_dana');
    $this->db->where('a.tahun', $this->tahun);
    /* filter skpd jika diset */
    if ($this->session->userdata('id_skpd') !== 0) $this->db->where('a.id_skpd', $this->session->userdata('id_skpd'));
  }

  /* Query form */
  function build_query_form($id=0)
  {
    $this->db->select('
      a.id_aktivitas,
      a.nomor,
      a.tanggal,
      b.tanggal_cair,
      cast(substring(a.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
      a.id_skpd,
      s.kode_skpd_lkp,
      s.nama_skpd,
      c.nominal,
      c.id_aktivitas id_spm,
      c.nomor nomor_spm,
      c.tanggal tanggal_spm,
      c.id_spp,
      c.keperluan,
      c.beban,
      c.jenis_beban,
      b.id_rekening_pekas,
      r.kode_rekening kode_rekening_pekas,
      r.nama_rekening nama_rekening_pekas,
      b.id_sumber_dana,
      sd.nama_sumber_dana,
      c.nama_penerima,
      c.instansi_penerima,
      c.nama_bank,
      c.no_rekening_bank,
      c.npwp,
      c.no_dpa,
      c.tanggal_dpa,
      c.pagu_dpa,
      c.id_bk,
      bk.nama_pejabat bk_nama,
      bk.nip bk_nip,
      c.id_pa id_kepala_skpd,
      pa.nama_pejabat kaskpd_nama,
      pa.nip kaskpd_nip,
      b.id_bud,
      bud.nama_pejabat bud_nama,
      bud.nip bud_nip
    ');
    $this->db->from('aktivitas a');
    $this->db->join('sp2d b', 'b.id_aktivitas = a.id_aktivitas');
    $this->db->join('v_spm c', 'c.id_aktivitas = b.id_aktivitas_spm');
    $this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
    $this->db->join('rekening r', 'r.id_rekening = b.id_rekening_pekas');
    $this->db->join('sumber_dana sd', 'sd.id_sumber_dana = b.id_sumber_dana');
    $this->db->join('pejabat_skpd bk', 'bk.id_pejabat_skpd = c.id_bk','LEFT');
    $this->db->join('pejabat_skpd pa', 'pa.id_pejabat_skpd = c.id_pa','LEFT');
    $this->db->join('pejabat_daerah bud', 'bud.id_pejabat_daerah = b.id_bud','LEFT');
    $this->db->where('a.id_aktivitas', $id);
  }

  function build_query_hapus($id=0)
  {
    $this->db->where('id_aktivitas', $id)->delete('sp2d');
    $this->db->where('id_aktivitas', $id)->delete('aktivitas');
  }

  function get_id_spm($id)
  {
    $this->db->select('id_aktivitas_spm')->from('sp2d')->where('id_aktivitas', $id);
    $result = $this->db->get()->row_array();

    return $result['ID_AKTIVITAS_SPM'];
  }

  function get_spm_by_id($id=0)
  {
    $this->db->select('
      a.id_aktivitas,
      a.id_spp,
      a.nomor,
      a.tanggal,
      cast(substring(a.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
      a.id_skpd,
      s.kode_skpd_lkp,
      s.nama_skpd,
      a.nominal,
      a.keperluan,
      a.beban,
      a.jenis_beban,
      a.no_kontrak,
      a.nama_penerima,
      a.instansi_penerima,
      a.nama_bank,
      a.no_rekening_bank,
      a.npwp,
      a.no_dpa,
      a.tanggal_dpa,
      a.pagu_dpa,
      a.id_pa id_kaskpd,
      pa.nama_pejabat kaskpd_nama,
      pa.nip kaskpd_nip,
      a.id_bk,
      bk.nama_pejabat bk_nama,
      bk.nip bk_nip,
    ');
    $this->db->from('v_spm a');
    $this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
    $this->db->join('pejabat_skpd bk', 'bk.id_pejabat_skpd = a.id_bk','LEFT');
    $this->db->join('pejabat_skpd pa', 'pa.id_pejabat_skpd = a.id_pa','LEFT');
    $this->db->where('a.id_aktivitas', $id);
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_sisa_skpd($param)
  {
    $arrspd = (is_array($param['arrspd']) ? join(',', $param['arrspd']) : $param['arrspd'] );

    $this->db->select('a.nominal');
    $this->db->from("sisa_spp_skpd("
        .$param['id_skpd'].", "
        .$this->tahun.", "
        ."'".prepare_date($param['tanggal'])."', "
        ."'".$param['keperluan']."', "
        ."'".$param['beban']."', "
        .$param['id'].", "
        ."'".$arrspd."') a"
    );
    $result = $this->db->get()->row_array();

    return $result['NOMINAL'];
  }

  function get_sisa_spj($param, $kep)
  {
    $this->db->select('nominal_sisa')
      ->from("sisa_sp2d_belum_spj("
          .$this->tahun.", "
          ."'".prepare_date($param['tanggal'])."', "
          .$param['id_skpd'].", "
          ."'".$kep."', "
          ."'-', "
          .$param['id'].")"
      );
    $spj = $this->db->get()->row_array();

    return $spj['NOMINAL_SISA'];
  }

  function delete_data($id)
  {
    $this->db->trans_start();
    $this->db->where('id_aktivitas', $id);
    $this->db->delete('sp2d');
    $this->db->where('id_aktivitas', $id);
    $this->db->delete('aktivitas');
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
  }

  function check_dependency($id){
    // tidak ada dependency
    return TRUE;
  }

}
