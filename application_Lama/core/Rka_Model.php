<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*********************************************************************************************
  SUPER CLASS dari semua Model RKA
 *********************************************************************************************/
class Rka_Model extends CI_Model{

  var $tahun;
  var $status;
  var $perubahan;
  var $username;
  var $tipe; // { RKA, RKA1, RKA21, RKA22, RKA221, RKA31, RKA32 }
  var $id_kegiatan;
  var $lanjutan;
  var $id;
  var $id_skpd;
  var $fields;
  var $fieldmap;
  var $fieldmap_fa;
  var $data_fa;
	var $fieldmap_daftar_aggregate; // variabel untuk menampung kolom yang di aggregate (sum, count, dll)

  function __construct()
  {
    parent::__construct();

    $this->tahun = $this->session->userdata('tahun');
    $this->status = $this->session->userdata('status');
    $this->perubahan = 0;
    $this->username = $this->session->userdata('username');
    $this->id_skpd = $this->session->userdata('id_skpd');
    $this->tipe = 'RKA'; // tipe di override di modul fa

    $this->db->select('status_awal');
    $this->db->from('tahun_anggaran');
    $this->db->where('tahun', $this->tahun);
    $result = $this->db->get()->row_array();
    
    if ($result['STATUS_AWAL'] !== $this->status) {
      $this->perubahan = 1;
    }

    $this->fieldmap_fa = array(
      'id' => 'ID_FORM_ANGGARAN',
      'tahun' => 'TAHUN',
      'status' => 'STATUS',
      'id_skpd' => 'ID_SKPD',
      'tipe' => 'TIPE',
      'tw1' => 'TRIWULAN_1',
      'tw2' => 'TRIWULAN_2',
      'tw3' => 'TRIWULAN_3',
      'tw4' => 'TRIWULAN_4',
      'ps' => 'ID_PEJABAT_SKPD',
      'pd' => 'ID_PEJABAT_DAERAH',
      'tgl' => 'TANGGAL_PEMBAHASAN',
      'ket' => 'KETERANGAN',
      'latar' => 'LATAR_BELAKANG',
    );
		
		$this->fieldmap_daftar_aggregate = array();
  }

  function get_grid_model()
  {

  }

  function fill_data()
  {
    /* ambil data Form Anggaran */
    foreach($this->fieldmap_fa as $key => $value){
      switch ($key)
      {
        case 'tipe'  : $$key = $this->tipe; break;
        case 'tahun' : $$key = $this->tahun; break;
        case 'status' : $$key = $this->status; break;
        case 'tgl'   : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }
      if(isset($$key))
        $this->data_fa[$value] = $$key;
    }

    /* pengambilan data lain di override di modul rka */
  }

  function insert_fa()
  {
    if (isset($this->data_fa['ID_FORM_ANGGARAN']))
    {
      $this->db->where('ID_FORM_ANGGARAN', $this->data_fa['ID_FORM_ANGGARAN']);
      $this->db->update('FORM_ANGGARAN', $this->data_fa);
      return $this->data_fa['ID_FORM_ANGGARAN'];
    }
    else
    {
      $this->db->insert('FORM_ANGGARAN', $this->data_fa);
      $this->db->select_max('ID_FORM_ANGGARAN')->from('FORM_ANGGARAN');
      $rs = $this->db->get()->row_array();
      return $rs['ID_FORM_ANGGARAN'];
    }
  }

  function save_detail()
  {
    // fungsi ini di override di modul RKA
  }

  function save_data()
  {
    $this->db->trans_start();
    $this->id = $this->insert_fa();
    $this->save_detail();
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
  }

  function delete_data($id)
  {
    $this->db->trans_start();
    $this->build_query_hapus($id);
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
  }

  function build_query_daftar()
  {
    // di override di modul RKA
  }

  function build_query_form($id=0)
  {
    // di override di modul RKA
  }

  function build_query_hapus()
  {
    // di override di modul RKA
  }

  function get_data($param, $isCount=FALSE, $CompileOnly=False)
  {
    isset($param['limit']) && $param['limit'] ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '';

    if (isset($param['m']) == 's' && $param['q'] !== ''){
      $flt = array();
      foreach($this->fieldmap as $key => $value){
        $flt[$key] = array('search_str' => $param['q'], 'search_op' => 'cn');
      }
      $wh = get_where_str($flt, $this->fieldmap);
      
      $count = count($wh);
      $string = '(';
      $i = 1;
      foreach ($wh as $key=>$val) {
        $string .= $key ." '". $val ."'";
        if ($i < $count) $string .= ' OR ';
        $i++;
      }
      $string .= ')';

      $this->db->where($string);
    }
    else
    {
      if (isset($param['search']) && $param['search'] && $wh = get_where_str(array($param['search_field'] => $param['search_str']), $this->fieldmap))
      {
        $this->db->where($wh);
      }
    }

    if (isset($param['sort_by']) && $param['sort_by'] != null && !$isCount && $ob = get_order_by_str($param['sort_by'], $this->fieldmap))
    {
      $this->db->order_by($ob, $param['sort_direction']);
    }

    $this->build_query_daftar();

    if ($isCount) {
			/*
      $result = $this->db->count_all_results();
      return $result;
			*/
			
			$this->db->ar_select = array();
      $this->db->ar_select[] = 'count(*) cnt';
      foreach( $this->fieldmap_daftar_aggregate as $agg_key => $agg_value)
      {
        $this->db->ar_select[] = "coalesce(".$agg_value.", 0) ".$agg_key;
      }

      $result = $this->db->get();
      return $result->row_array();
    }
    else
    {
      if ($CompileOnly)
      {
        return $this->db->get_compiled_select();
      }
      else
      {
        $result = $this->db->get()->result_array();
        return $result;
      }
    }
  }
  
	/* untuk mengambil rincian rka */
  function get_rincian($id = 0)
  {
    $result = $this->db->query("
      select
        x.id_rincian_anggaran,
        x.id_detil_rekening,
        x.id_rekening,
        x.level_rekening,
        x.id_parent_rekening,
        x.no_urut,
        x.kode_rekening,
        x.uraian,
        x.volume,
        x.satuan,
        x.tarif,
        x.pagu,
        x.realisasi,
        x.pagu_tahun_depan,
        x.keterangan,
        x.child,
        x.id_proposal
      from (
      with recursive rek as(
        select
          -ra.id_rekening id_rincian_anggaran,
          -ra.id_rekening id_detil_rekening,
          ra.id_rekening,
          r.nama_rekening uraian,
          0 volume,
          cast('' as varchar(50)) satuan,
          0 tarif,
          cast('' as varchar(2000)) keterangan,
          sum(ra.pagu) pagu,
          0 realisasi,
          sum(ra.pagu_tahun_depan) pagu_tahun_depan,
          r.kode_rekening,
          r.nama_rekening,
          r.level_rekening,
          r.id_parent_rekening,
          0 no_urut,
          count(ra.id_rekening) child,
		  0 id_proposal
        from rekening r
        join rincian_anggaran ra on r.id_rekening = ra.id_rekening
        join v_form_anggaran_lkp fa on ra.id_form_anggaran = fa.id_form_anggaran
        where fa.id_form_anggaran =  ".$id."
        group by
          fa.tahun,
          fa.id_skpd,
          fa.id_kegiatan,
          ra.id_rekening,
          r.nama_rekening,
          r.kode_rekening,
          r.nama_rekening,
          r.level_rekening,
          r.id_parent_rekening

      union all

        select
          ra.id_rincian_anggaran,
          ra.id_detil_rekening,
          ra.id_rekening,
          cast(substring(ra.uraian from 1 for 2000) as varchar(2000)) uraian,
          ra.volume,
          ra.satuan,
          ra.tarif,
          cast(substring(ra.keterangan from 1 for 2000) as varchar(2000)) keterangan,
          coalesce(ra.pagu, 0) pagu,
          0 realisasi,
          coalesce(ra.pagu_tahun_depan, 0) pagu_tahun_depan,
          r.kode_rekening,
          r.nama_rekening,
          0 level_rekening,
          r.id_parent_rekening,
          ra.no_urut,
          0 child,
		  ra.id_proposal
        from rekening r
        join rincian_anggaran ra on r.id_rekening = ra.id_rekening
        join form_anggaran fa on ra.id_form_anggaran = fa.id_form_anggaran
        where fa.id_form_anggaran =  ".$id."

      union all

        select
          -r.id_rekening id_rincian_anggaran,
          -r.id_rekening id_detil_rekening,
          r.id_rekening,
          r.nama_rekening uraian,
          0 volume,
          cast('' as varchar(50)) satuan,
          0 tarif,
          cast('' as varchar(2000)) keterangan,
          rek.pagu,
          rek.realisasi,
          rek.pagu_tahun_depan,
          r.kode_rekening,
          r.nama_rekening,
          r.level_rekening,
          r.id_parent_rekening,
          0 no_urut,
          rek.child,
		  rek.id_proposal
        from rekening r
        join rek on rek.id_parent_rekening = r.id_rekening
        where rek.level_rekening <> 0
      )
      select
        rek.id_rincian_anggaran,
        rek.id_detil_rekening,
        rek.id_rekening,
        rek.level_rekening,
        rek.id_parent_rekening,
        rek.no_urut,
        rek.kode_rekening,
        rek.uraian,
        rek.volume,
        rek.satuan,
        rek.tarif,
        sum(rek.pagu) pagu,
        sum(rek.realisasi) realisasi,
        sum(rek.pagu_tahun_depan) pagu_tahun_depan,
        rek.keterangan,
        sum(rek.child) child,
		rek.id_proposal
      from rek
      group by 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 15, 17
      order by 7, 6
      ) x
    ");
    return $result->result_array();
  }

  function get_rincian_murni($id = 0)
  {
    $result = $this->db->query("
      select
        x.id_rincian_anggaran,
        x.id_detil_rekening,
        x.level_rekening,
        x.kode_rekening,
        x.volume,
        x.satuan,
        x.tarif,
        x.pagu
      from (
        with recursive rek as (
          select
            -r.id_rekening id_rincian_anggaran,
            -r.id_rekening id_detil_rekening,
            r.id_rekening,
            r.level_rekening,
            r.kode_rekening,
            r.id_parent_rekening,
            0 no_urut,
            0 volume,
            cast('' as varchar(50)) satuan,
            0 tarif,
            sum(ra.pagu) pagu
          from rincian_anggaran ra
          join rekening r on r.id_rekening = ra.id_rekening
          join form_anggaran fa on fa.id_form_anggaran = ra.id_form_anggaran
          where  fa.id_form_anggaran = ".$id."
          group by
            r.id_rekening,
            r.level_rekening,
            r.id_parent_rekening,
            r.kode_rekening

          union all

          select
            ra.id_rincian_anggaran,
            ra.id_detil_rekening,
            ra.id_rekening,
            0 level_rekening,
            r.kode_rekening,
            r.id_parent_rekening,
            ra.no_urut,
            ra.volume,
            cast(ra.satuan as varchar(50)) satuan,
            ra.tarif,
            ra.pagu
          from rincian_anggaran ra
          join rekening r on r.id_rekening = ra.id_rekening
          join v_form_anggaran_lkp fa on fa.id_form_anggaran = ra.id_form_anggaran
          where fa.id_form_anggaran = ".$id."

          union all

          select
            -r.id_rekening id_rincian_anggaran,
            -r.id_rekening id_detil_rekening,
            r.id_rekening,
            r.level_rekening,
            r.kode_rekening,
            r.id_parent_rekening,
            0 no_urut,
            0 volume,
            cast('' as varchar(50)) satuan,
            0 tarif,
            rek.pagu
          from rekening r
          join rek on rek.id_parent_rekening = r.id_rekening
          where rek.level_rekening <> 0
        )
        select
          rek.id_rincian_anggaran,
          rek.id_detil_rekening,
          rek.id_rekening,
          rek.kode_rekening,
          rek.level_rekening,
          rek.no_urut,
          rek.volume,
          rek.satuan,
          rek.tarif,
          sum(rek.pagu) pagu
        from rek
        group by 1, 2, 3, 4, 5, 6, 7, 8, 9
        order by 4, 6
      ) x
    ");
    return $result->result_array();
  }
  
  // untuk mengambil id_form_anggaran status awal
  // parameter : id_skpd, id_keg
  function get_id_rka_murni($id)
  {
    $this->db->select("b.id_form_anggaran");
    $this->db->from("v_form_anggaran_lkp a");
    $this->db->join("tahun_anggaran ta", "ta.tahun = a.tahun");
    $this->db->join("v_form_anggaran_lkp b", "b.tahun = a.tahun and b.status = ta.status_awal and b.tipe = a.tipe and b.id_skpd = a.id_skpd".(in_array($this->tipe, array('RKA221', 'DPA221')) ? " and b.id_kegiatan = a.id_kegiatan and b.lanjutan = a.lanjutan" : ""));
    $this->db->where("a.id_form_anggaran", $id);
    $result = $this->db->get()->row_array();

    return isset($result['ID_FORM_ANGGARAN']) ? $result['ID_FORM_ANGGARAN'] : 0;
  }

  function get_indikator($id, $id_keg)
  {
    $this->db->select('a.id_indikator_kinerja, a.tipe_indikator, a.nama_indikator, a.tolok_ukur, a.target, a.jumlah_target');
    $this->db->from('indikator_kinerja a');
    if ($this->perubahan) $this->db->join('v_form_anggaran_lkp v', 'a.id_form_anggaran = v.id_form_anggaran');
    $this->db->where('a.id_form_anggaran', $id);
    if ($this->perubahan) $this->db->where('v.id_kegiatan', $id_keg);
    $this->db->order_by('a.no_urut');
    $result = $this->db->get()->result_array();

    return $result;
  }

  function get_bahasan($id, $id_keg)
  {
    $this->db->select('a.no_urut, a.catatan');
    $this->db->from('catatan_pembahasan_anggaran a');
    if ($this->perubahan) $this->db->join('v_form_anggaran_lkp v', 'a.id_form_anggaran = v.id_form_anggaran');
    $this->db->where('a.id_form_anggaran', $id);
    if ($this->perubahan) $this->db->where('v.id_kegiatan', $id_keg);
    $this->db->order_by('a.no_urut');
    $result = $this->db->get()->result_array();

    return $result;
  }

  function get_lokasi($id, $id_keg)
  {
    $this->db->select('a.id_lokasi, b.lokasi, a.nominal');
    $this->db->from('lokasi_kegiatan a');
    $this->db->join('lokasi b', 'b.id_lokasi = a.id_lokasi');
    if ($this->perubahan) $this->db->join('v_form_anggaran_lkp v', 'a.id_form_anggaran = v.id_form_anggaran');
    $this->db->where('a.id_form_anggaran', $id);
    if ($this->perubahan) $this->db->where('v.id_kegiatan', $id_keg);
    $result = $this->db->get()->result_array();

    return $result;
  }

  function get_sumberdana($id, $id_keg)
  {
    $this->db->select('a.id_sumber_dana, b.nama_sumber_dana, a.nominal');
    $this->db->from('sumber_dana_kegiatan a');
    $this->db->join('sumber_dana b', 'b.id_sumber_dana = a.id_sumber_dana');
    if ($this->perubahan) $this->db->join('v_form_anggaran_lkp v', 'a.id_form_anggaran = v.id_form_anggaran');
    $this->db->where('a.id_form_anggaran', $id);
    if ($this->perubahan) $this->db->where('v.id_kegiatan', $id_keg);
    $result = $this->db->get()->result_array();

    return $result;
  }

  function get_data_by_id($id)
  {
    $this->build_query_form($id);
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_prev_id($id)
  {
    $this->db->select('coalesce(max(a.id_form_anggaran), 0) id_fa')
      ->from('v_form_anggaran_lkp a')
      ->where('a.tahun', $this->tahun)
      ->where('a.status', $this->status)
      ->where('a.tipe', $this->tipe)
      ->where('id_form_anggaran < ', $id);
    if ($this->id_skpd != 0)
      $this->db->where('id_skpd', $this->id_skpd);
    if ($this->tipe === 'RKA221' || $this->tipe === 'DPA221')
      // $this->db->where('id_kegiatan', $this->id_kegiatan)->where('lanjutan', $this->lanjutan);
      $this->db->where('id_kegiatan is not null')->where('lanjutan', $this->lanjutan);

    $result = $this->db->get()->row_array();

    return $result['ID_FA'];
  }

  function get_next_id($id)
  {
    $this->db->select('coalesce(min(a.id_form_anggaran), 0) id_fa')
      ->from('v_form_anggaran_lkp a')
      ->where('a.tahun', $this->tahun)
      ->where('a.status', $this->status)
      ->where('a.tipe', $this->tipe)
      ->where('id_form_anggaran > ', $id);
    if ($this->id_skpd != 0)
      $this->db->where('id_skpd', $this->id_skpd);
    if ($this->tipe === 'RKA221' || $this->tipe === 'DPA221')
      // $this->db->where('id_kegiatan', $this->id_kegiatan)->where('lanjutan', $this->lanjutan);
      $this->db->where('id_kegiatan is not null')->where('lanjutan', $this->lanjutan);
      
    $result = $this->db->get()->row_array();

    return $result['ID_FA'];
  }

  function check_dependency($id)
  {
    return TRUE; // fungsi ini di override di modul RKA
  }

  function get_id_form()
  {
    $id_skpd = $this->input->post('id_skpd');
    $id_keg = $this->input->post('id_keg');
    
    $this->db->select('a.id_form_anggaran')
      ->from('v_form_anggaran_lkp a')
      ->where('a.tahun', $this->tahun)
      ->where('a.tipe', $this->tipe)
      ->where('a.id_skpd', $id_skpd);
    if ($this->tipe === 'RKA221') $this->db->where('a.id_kegiatan', $id_keg)->where('a.lanjutan', $this->lanjutan);
    $result = $this->db->get()->row_array();
    
    return $result;
  }
  
  function data_exists()
  {
    // di override di modul RKA
  }
}