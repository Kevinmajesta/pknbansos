<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spm_model extends Aktivitas_Model {

  var $fields;
  var $fieldmap;
  var $fieldmap_spm;
  var $data_spm;
  var $skpd;

  function __construct()
  {
    parent::__construct();

    $this->tipe = 'SPM';
    $this->skpd = $this->session->userdata('id_skpd');

    $this->fields = array(
      'ID_AKTIVITAS',
      'NOMOR',
      'TANGGAL',
      'NAMA_SKPD',
      'KEPERLUAN',
      'BEBAN',
      'NOMINAL_KOTOR',
      //'NOMINAL_PFK',
      //'NOMINAL_PFK_INFORMASI',
      'NOMINAL_PAJAK',
      'NOMINAL_PAJAK_INFORMASI',
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
      'bbn' => 'a.BEBAN',
      'bruto' => 'a.NOMINAL_KOTOR',
      //'pfk' => 'a.NOMINAL_PFK',
      //'pfkinfo' => 'a.NOMINAL_PFK_INFORMASI',
      'pjk' => 'a.NOMINAL_PAJAK',
      'pjkinfo' => 'a.NOMINAL_PAJAK_INFORMASI',
      'nom' => 'a.NOMINAL',
      'keg' => 'a.NAMA_KEGIATAN',
      'ket' => 'a.DESKRIPSI',
			'pihak3' => 'a.NAMA_PENERIMA'
    );

    $this->fieldmap_spm = array(
      'id' => 'ID_AKTIVITAS',
      'id_spp' => 'ID_SPP',
    );
		
		$this->fieldmap_daftar_aggregate = array(
			'bruto' => "sum(a.NOMINAL_KOTOR)",
			'pjk' => "sum(a.NOMINAL_PAJAK)",
			'pjk_info' => "sum(a.NOMINAL_PFK_INFORMASI)",
			'nom' => "sum(a.NOMINAL)",
		);
  }

  function get_grid_model()
  {
    $grid = array(
        'colNames' => array('Nomor', 'Tanggal', 'Nama SKPD', 'Keperluan', 'Beban', 'Nominal Kotor', /*'PFK (Potongan)', 'PFK (Informasi)',*/ 'Pajak(Potongan)', 'Pajak (Informasi)','Nominal Bersih', 'Nama Kegiatan', 'Keterangan'),
        'colModel' => array(
          array('name' => 'no', 'width' => 200, 'sortable' => true),
          array('name' => 'tgl', 'width' => 80, 'sortable' => true, 'formatter' => 'date', 'align' => 'center'),
          array('name' => 'nmskpd','width' => 250, 'sortable' => true),
          array('name' => 'kpl', 'width' => 60, 'sortable' => true, 'align' => 'center'),
          array('name' => 'bbn', 'width' => 60, 'sortable' => true, 'align' => 'center'),
          array('name' => 'bruto','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
          //array('name' => 'pfk','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
          //array('name' => 'pfk_info','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
          array('name' => 'pjk','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
          array('name' => 'pjk_info','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
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
    $fields['bruto'] = array('name' => 'Nominal Kotor', 'kategori'=>'numeric');
    $fields['pjk'] = array('name' => 'Pajak (Potongan)', 'kategori'=>'numeric');
    $fields['nom'] = array('name' => 'Nominal Bersih', 'kategori'=>'numeric');
    $fields['keg'] = array('name' => 'Nama Kegiatan', 'kategori'=>'string');
    $fields['ket'] = array('name' => 'Keterangan', 'kategori'=>'string');
    $fields['pihak3'] = array('name' => 'Pihak Ketiga', 'kategori'=>'string');
    
    return $fields;
  }

  function fill_data()
  {
    parent::fill_data();

    /* ambil data SPM */
    foreach($this->fieldmap_spm as $key => $value){
      switch($key)
      {
        default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }
      if(isset($$key))
        $this->data_spm[$value] = $$key;
    }
  }

  /* Simpan data SPM */
  function insert_spm()
  {
    $this->db->select('1')->from('SPM')->where('ID_AKTIVITAS', $this->id);
    $rs = $this->db->get()->row_array();

    if ($rs)
    {
      $this->db->where('ID_AKTIVITAS', $this->id);
      $this->db->update('SPM', $this->data_spm);
    }
    else
    {
      $this->data_spm['ID_AKTIVITAS'] = $this->id;
      $this->db->insert('SPM', $this->data_spm);
    }
  }

  function save_detail()
  {
    $this->insert_spm();
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
      a.id_spp,
      a.beban,
      a.keperluan,
      a.nominal_rincian,		
      a.nominal_pajak,
      a.nama_kegiatan,
      a.nominal_pajak_informasi,
      a.nominal_kotor,
	  a.nominal_pfk,
	  a.nominal_pfk_informasi
    ");
    $this->db->from('v_spm_kegiatan a');
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
			c.nominal,
			b.id_spp,
			d.id_aktivitas_spj id_spj,
			c.nomor no_spp,
			c.tanggal tanggal_spp,
			c.nominal nominal_spp,
			c.keperluan,
			c.beban,
			c.jenis_beban,
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
			c.id_pa,
			ka.nama_pejabat pa_nama,
			ka.nip pa_nip
		');
		$this->db->from('aktivitas a');
		$this->db->join('spm b', 'b.id_aktivitas = a.id_aktivitas');
		$this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
		$this->db->join('v_spp c', 'c.id_aktivitas = b.id_spp','LEFT');
		$this->db->join('spp d', 'd.id_aktivitas = c.id_aktivitas','LEFT');
		$this->db->join('pejabat_skpd bk', 'bk.id_pejabat_skpd = c.id_bk','LEFT');
		$this->db->join('pejabat_skpd ka', 'ka.id_pejabat_skpd = c.id_pa','LEFT');
		$this->db->where('a.id_aktivitas', $id);
	}

  function build_query_hapus($id=0)
  {
    $this->db->where('id_aktivitas', $id)->delete('spm');
    $this->db->where('id_aktivitas', $id)->delete('aktivitas');
  }

  /* Query data SPP */
  function get_spp_by_id($id=0)
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
      b.id_aktivitas_spj,
      b.id_bk,
      bk.nama_pejabat bk_nama,
      bk.nip bk_nip,
      b.id_pa,
      pa.nama_pejabat pa_nama,
      pa.nip pa_nip
    ');
    $this->db->from('aktivitas a');
    $this->db->join('spp b', 'b.id_aktivitas = a.id_aktivitas');
    $this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
    $this->db->join('pejabat_skpd bk', 'bk.id_pejabat_skpd = b.id_bk','LEFT');
    $this->db->join('pejabat_skpd pa', 'pa.id_pejabat_skpd = b.id_pa','LEFT');
    $this->db->where('a.id_aktivitas', $id);
    $result = $this->db->get()->row_array();

    return $result;
  }

  /* Query rincian SPD */
  function get_spd_by_id($id_spp = 0)
  {
    $this->db->select('
        a.id_spp_pakai_spd id_pakai_spd,
        a.id_aktivitas_spd id_spd,
        b.nomor,
        b.tanggal,
        b.nominal
    ');
    $this->db->from('spp_pakai_spd a');
    $this->db->join('v_spd b', 'b.id_aktivitas = a.id_aktivitas_spd');
    $this->db->where('a.id_aktivitas_spp', $id_spp);
    $result = $this->db->get()->result_array();

    return $result;
  }

  /* Query rincian Kegiatan SPM */
  function get_kegiatan_by_id($id_spp = 0)
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
  function get_rekening_by_id($id_spp = 0)
  {
    $this->db->select('
        b.id_rincian_spp id_rincian,
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
    $this->db->where('b.id_aktivitas', $id_spp);
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
        ."'".$param['arrspd']."') a"
    );
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_sisa_kegiatan($param)
  {
    $arrspd = (is_array($param['arrspd']) ? join(',', $param['arrspd']) : $param['arrspd'] );

    $this->db->select('
        a.nominal,
        a.nominal_cp,
        a.nominal_ssu,
        a.nominal_mp
    ');
    $this->db->from("sisa_spp_kegiatan_2("
        .$param['id_skpd'].", "
        .$this->tahun.", "
        .$param['id_kegiatan'].", "
        ."'".prepare_date($param['tanggal'])."', "
        ."'".$param['keperluan']."', "
        ."'".$param['beban']."', "
        .$param['id_aktivitas'].", "
        ."'".$arrspd."') a"
    );
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_sisa_rekening($param)
  {
    $arrspd = (is_array($param['arrspd']) ? join(',', $param['arrspd']) : $param['arrspd'] );

    $this->db->select('
        a.nominal,
        a.nominal_cp,
        a.nominal_ssu,
        a.nominal_mp
    ');
    $this->db->from("sisa_spp_rekening_2("
        .$param['id_skpd'].", "
        .$this->tahun.", "
        .$param['id_kegiatan'].", "
        .$param['id_rekening'].", "
        ."'".prepare_date($param['tanggal'])."', "
        ."'".$param['keperluan']."', "
        ."'".$param['beban']."', "
        .$param['id_aktivitas'].", "
        ."'".$arrspd."') a"
    );
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
      ->from('spm s')
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
      ->from("v_sts_2 s")
      ->where("s.tanggal <=", prepare_date($param['tanggal']) )
      ->where("s.id_skpd", $param['id_skpd'] )
      ->where("s.tahun", $this->tahun );
    $sts = $this->db->get()->row_array();

    $this->db->select("coalesce(sum(p.nominal), 0) nominal")
      ->from("v_spm p")
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
      ->join("v_rincian_anggaran ra", "f.id_form_anggaran = ra.id_form_anggaran")
      ->where("f.tipe", $tp)
      ->where("f.tahun", $this->tahun)
      ->where("f.status", $this->status)
      ->where("f.id_skpd", $param['id_skpd'])
      ->group_by("s.tanggal_dpa");

    if ($param['beban'] === 'BL')
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
    $this->db->select('a.sp2d_pakai');
    $this->db->from('spm a');
    $this->db->where('a.id_aktivitas', $id);
    $result = $this->db->get()->row_array();

    if ($result['SP2D_PAKAI'] > 0 )
    {
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }

}
?>