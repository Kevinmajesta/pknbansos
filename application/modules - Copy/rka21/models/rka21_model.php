<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rka21_model extends Rka_Model {

  var $fields;
  var $fieldmap;
  var $fieldmap_rincian;
  var $data_rincian;
  var $fieldmap_pembahasan;
  var $data_pembahasan;
  var $fieldmap_sumberdana;
  var $data_sumberdana;
  var $data_detil_rek;
  var $purge_pembahasan;
  var $purge_sumberdana;

  function __construct()
  {
    parent::__construct();

    $this->tipe = 'RKA21';

    $this->fields = array(
      'ID_FORM_ANGGARAN',
      'KODE_SKPD_LKP',
      'NAMA_SKPD',
      'NOMINAL_ANGGARAN',
    );

    $this->fieldmap = array(
      'kdskpd' => 's.KODE_SKPD_LKP',
      'nmskpd' => 's.NAMA_SKPD',
      'kdkeg' => 'a.KODE_KEGIATAN_SKPD',
      'pagu' => 'a.NOMINAL_ANGGARAN',
    );

    $this->fieldmap_rincian = array(
      'id' => 'ID_FORM_ANGGARAN',
      'idra' => 'ID_RINCIAN_ANGGARAN',
      'iddr' => 'ID_DETIL_REKENING',
      'idrek' => 'ID_REKENING',
      'uraian' => 'URAIAN',
      'vol' => 'VOLUME',
      'sat' => 'SATUAN',
      'trf' => 'TARIF',
      'jml_next' => 'PAGU_TAHUN_DEPAN',
      'no' => 'NO_URUT',
      'ket' => 'KETERANGAN',
    );

    $this->fieldmap_pembahasan = array(
      'id' => 'ID_FORM_ANGGARAN',
      'no' => 'NO_URUT',
      'ket' => 'CATATAN',
    );

    $this->fieldmap_sumberdana = array(
      'id' => 'ID_FORM_ANGGARAN',
      'idsd' => 'ID_SUMBER_DANA',
      'nom' => 'NOMINAL',
    );

  }

  function get_grid_model()
  {
    $grid = array(
        'colNames' => array('Kode SKPD', 'Nama SKPD', 'Pagu'),
        'colModel' => array(
          array('name' => 'kd_skpd', 'width' => 100, 'sortable' => true),
          array('name' => 'nm_skpd', 'width' => 500, 'sortable' => true),
          array('name' => 'pagu','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
        ),
    );
    return $grid;
  }

  function fill_data()
  {
    parent::fill_data();

    $this->purge_rincian = $this->input->post('purge_rincian'); $this->purge_rincian = $this->purge_rincian ? $this->purge_rincian : NULL;
    $this->purge_pembahasan = $this->input->post('purge_pembahasan'); $this->purge_pembahasan = $this->purge_pembahasan ? $this->purge_pembahasan : NULL;
    $this->purge_sumberdana = $this->input->post('purge_sumberdana'); $this->purge_sumberdana = $this->purge_sumberdana ? $this->purge_sumberdana : NULL;

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
    $this->insert_pembahasan();
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
    /* filter skpd jika diset */
    if ($this->session->userdata('id_skpd') != 0) $this->db->where('a.id_skpd', $this->session->userdata('id_skpd'));
  }

  /*function get_rincian($id=0)
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
          -r.id_rekening id_rincian_anggaran,
          0 id_detil_rekening,
          ra.id_rekening,
          r.nama_rekening uraian,
          0 volume,
          cast('' as varchar(50)) satuan,
          0 tarif,
          cast('' as varchar(2000)) keterangan,
          sum(ra.pagu) pagu,
          sum(ra.pagu_tahun_depan) pagu_tahun_depan,
          r.kode_rekening,
          r.nama_rekening,
          5 level_rekening,
          r.id_parent_rekening,
          0 no_urut,
          count(ra.id_rekening) child,
          0 id_proposal
        from rekening r
        join rincian_anggaran ra on r.id_rekening = ra.id_rekening
        join v_form_anggaran_lkp fa on ra.id_form_anggaran = fa.id_form_anggaran
        where fa.id_form_anggaran = ".$id."
        group by
          r.id_rekening,
          ra.id_rekening,
          r.nama_rekening,
          r.kode_rekening,
          r.nama_rekening,
          r.id_parent_rekening

      union all

        select
          ra.id_rincian_anggaran,
          ra.id_detil_rekening,
          ra.id_rekening,
          cast(substring(ra.uraian from 1 for 2000) as varchar(2000)) uraian,
          ra.volume,
          coalesce(ra.satuan, '') satuan,
          ra.tarif,
          coalesce(cast(substring(ra.keterangan from 1 for 2000) as varchar(2000)), '') keterangan,
          coalesce(ra.pagu, 0) pagu,
          coalesce(ra.pagu_tahun_depan, 0) pagu_tahun_depan,
          r.kode_rekening,
          ra.uraian nama_rekening,
          0 level_rekening,
          r.id_parent_rekening,
          ra.no_urut,
          0 child,
          ra.id_proposal

        from rekening r
        join rincian_anggaran ra on r.id_rekening = ra.id_rekening
        join form_anggaran fa on ra.id_form_anggaran = fa.id_form_anggaran
        where fa.id_form_anggaran = ".$id."

      union all

        select
          -r.id_rekening id_rincian_anggaran,
          0 id_detil_rekening,
          r.id_rekening,
          r.nama_rekening uraian,
          0 volume,
          '' satuan,
          0 tarif,
          '' keterangan,
          (
				select sum(ra.pagu) 
				from rincian_anggaran ra
				join rekening rn on rn.id_rekening = ra.id_rekening 
				where ra.id_form_anggaran = ".$id."
				and rn.kode_rekening starting with r.kode_rekening
			) pagu,
		   (
				select sum(ra.pagu_tahun_depan) 
				from rincian_anggaran ra
				join rekening rn on rn.id_rekening = ra.id_rekening 
				where ra.id_form_anggaran = ".$id."
				and rn.kode_rekening starting with r.kode_rekening
			) pagu_tahun_depan,
          r.kode_rekening,
          r.nama_rekening,
          r.level_rekening,
          r.id_parent_rekening,
          0 no_urut,
          (
				select count(1)
				from rincian_anggaran ra
				join rekening rn on rn.id_rekening = ra.id_rekening 
				where ra.id_form_anggaran =".$id."
				and rn.kode_rekening starting with r.kode_rekening
			) child,
          0 id_proposal

        from rekening r
        join rek on rek.id_parent_rekening = r.id_rekening
      )
      select distinct
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
        rek.pagu,
        cast(0 as decimal(18,2)) realisasi,
        rek.pagu_tahun_depan,
        rek.keterangan,
        rek.id_proposal,
        rek.child
      from rek
      order by 7, 6
      ) x
    ");
    return $result->result_array();
  }

  function get_rincian_murni($id=0)
  {
   $result = $this->db->query("
        select
          ra.id_rincian_anggaran,
          ra.id_detil_rekening,
          ra.volume,
          coalesce(ra.satuan, '') satuan,
          ra.tarif,
          ra.pagu
        from v_form_anggaran_lkp fa
        join tahun_anggaran ta on ta.tahun = fa.tahun
        join v_form_anggaran_lkp fm on fm.tahun = fa.tahun
          and fm.id_skpd = fa.id_skpd
          and fm.tipe = fa.tipe
          and (fm.tipe <> 'RKA221' or fm.lanjutan = fa.lanjutan)
          and fm.status = ta.status_awal
        join rincian_anggaran ra on ra.id_form_anggaran = fm.id_form_anggaran
        join rekening r on r.id_rekening = ra.id_rekening
        where fa.id_form_anggaran = ".$id."
    ");
    return $result->result_array();
  }*/

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
        cast(substring(a.keterangan from 1 for 5000) as varchar(5000)) keterangan,
        a.tanggal_pembahasan,
        a.id_pejabat_skpd,
        p1.nip,
        p1.nama_pejabat
    ');
    $this->db->from('form_anggaran a');
    $this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
    $this->db->join('pejabat_skpd p1', 'p1.id_pejabat_skpd = a.id_pejabat_skpd', 'left');
    $this->db->where('a.id_form_anggaran', $id);
  }

  function build_query_hapus($id=0)
  {
    $this->db->where('id_form_anggaran', $id)->delete('rincian_anggaran');
    $this->db->where('id_form_anggaran', $id)->delete('catatan_pembahasan_anggaran');
    $this->db->where('id_form_anggaran', $id)->delete('sumber_dana_kegiatan');
    $this->db->where('id_form_anggaran', $id)->delete('form_anggaran');
  }
  
  function data_exists()
  {
    $mode = $this->input->post('mode');

    $this->db->select('count(fa.id_form_anggaran) anggaran_exists');
    $this->db->from('v_form_anggaran_lkp fa');
    $this->db->where('fa.id_skpd', $this->data_fa['ID_SKPD']);
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
