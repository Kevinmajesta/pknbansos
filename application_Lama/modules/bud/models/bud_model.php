<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bud_model extends Aktivitas_Model {

  var $fields;
  var $fieldmap;
  var $fieldmap_bud;
  var $fieldmap_rincian;
  var $data_bud;
  var $data_rincian;

  function __construct()
  {
    parent::__construct();

    $this->tipe = 'BKU';

    $this->fields = array(
      'ID_AKTIVITAS',
      'NOMOR',
      'TANGGAL',
      'DEBET',
      'KREDIT',
      'DESKRIPSI',
    );

    $this->fieldmap = array(
      'id' => 'a.ID_AKTIVITAS',
      'no' => 'a.NOMOR',
      'tgl' => 'a.TANGGAL',
      'ket' => 'cast(substring(a.deskripsi from 1 for 5000) as varchar(5000))',
      'debet' => 'b.DEBET',
      'kredit' => 'b.KREDIT',
    );

    $this->fieldmap_bud = array(
      'id' => 'ID_AKTIVITAS',
      'bud' => 'ID_BUD',
    );

    $this->fieldmap_rincian = array(
      'id' => 'ID_AKTIVITAS',
      'idr' => 'ID_RINCIAN_BKU',
      'nobku' => 'NO_BKU',
      'idsp2d' => 'ID_AKTIVITAS_SP2D',
      'idsts' => 'ID_AKTIVITAS_STS',
      'idcp' => 'ID_AKTIVITAS_KONTRA_POS',
      'idspfk' => 'ID_AKTIVITAS_FPK_SP2D',
      'idspjk' => 'ID_AKTIVITAS_PAJAK_SP2D',
      'idssu' => 'ID_AKTIVITAS_SETORAN_SISA',
      'idsd' => 'ID_SUMBER_DANA',
      'ket' => 'DESKRIPSI_TRANSAKSI',
      'debet' => 'DEBET',
      'kredit' => 'KREDIT',
    );
  }

  function get_grid_model()
  {
    $grid = array(
        'colNames' => array('Nomor', 'Tanggal', 'Penerimaan', 'Pengeluaran', 'Keterangan'),
        'colModel' => array(
          array('name' => 'no', 'width' => 200, 'sortable' => true),
          array('name' => 'tgl', 'width' => 80, 'sortable' => true, 'formatter' => 'date', 'align' => 'center'),
          array('name' => 'debet','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
          array('name' => 'kredit','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
          array('name' => 'ket','width' => 300, 'sortable' => true),
        ),
    );
    return $grid;
  }

  function fill_data()
  {
    parent::fill_data();

    /* ambil data BUD */
    foreach($this->fieldmap_bud as $key => $value){
      $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      if(isset($$key))
        $this->data_bud[$value] = $$key;
    }

    /* ambil data rincian BUD */
    $rinci = $this->input->post('rincian') ? $this->input->post('rincian') : NULL;
    if ($rinci)
    {
      $rinci = json_decode($rinci);
      for ($i=0; $i <= count($rinci) - 1; $i++) {
        foreach($this->fieldmap_rincian as $key => $value){
          switch ($key)
          {
            case 'idr'   :  $$key = isset($rinci[$i]->$key) && $rinci[$i]->$key && (int)$rinci[$i]->$key > 0? $rinci[$i]->$key : NULL;; break;
            default : $$key = isset($rinci[$i]->$key) && $rinci[$i]->$key ? $rinci[$i]->$key : NULL;
          }
          if(isset($$key))
            $this->data_rincian[$i][$value] = $$key;
        }
      }
    }
  }

  /* Simpan data BUD */
  function insert_bud()
  {
    $this->db->select('1')->from('BKU')->where('ID_AKTIVITAS', $this->id);
    $rs = $this->db->get()->row_array();

    if ($rs)
    {
      $this->db->where('ID_AKTIVITAS', $this->id);
      $this->db->update('BKU', $this->data_bud);
    }
    else
    {
      $this->data_bud['ID_AKTIVITAS'] = $this->id;
      $this->db->insert('BKU', $this->data_bud);
    }
  }

  /* Simpan rincian BUD */
  function insert_rincian()
  {
    $jml = count($this->data_rincian);
    for ($i=0; $i <= $jml - 1; $i++)
    {
      $idr = isset($this->data_rincian[$i]['ID_RINCIAN_BKU']) ? $this->data_rincian[$i]['ID_RINCIAN_BKU'] : 0;
      $this->db->select('1')->from('RINCIAN_BKU')->where('ID_RINCIAN_BKU', $idr);
      $rs = $this->db->get()->row_array();

      if ($rs)
      {
        $this->db->where('ID_RINCIAN_BKU', $idr);
        $this->db->update('RINCIAN_BKU', $this->data_rincian[$i]);
      }
      else
      {
        $this->data_rincian[$i]['ID_AKTIVITAS'] = $this->id;
        $this->db->insert('RINCIAN_BKU', $this->data_rincian[$i]);
      }
    }
  }

  function save_detail()
  {
    $this->insert_bud();
    $this->insert_rincian();
  }

  /* Query daftar */
  function build_query_daftar()
  {
    $this->db->select('
        a.id_aktivitas,
        a.nomor,
        a.tanggal,
        cast(substring(a.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
        b.id_bud,
        b.debet,
        b.kredit
    ');
    $this->db->from('aktivitas a');
    $this->db->join('bku b', 'a.id_aktivitas = b.id_aktivitas');
    $this->db->where('a.tahun', $this->tahun);
  }

  /* Query form */
  function build_query_form($id=0)
  {
    $this->db->select('
        a.id_aktivitas,
        a.nomor,
        a.tanggal,
        cast(substring(a.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
        b.id_bud,
        b.debet,
        b.kredit
    ');
    $this->db->from('aktivitas a');
    $this->db->join('bku b', 'a.id_aktivitas = b.id_aktivitas');
    $this->db->where('a.id_aktivitas', $id);
  }

  function build_query_hapus($id=0)
  {
    $this->db->where('id_aktivitas', $id)->delete('rincian_bku');
    $this->db->where('id_aktivitas', $id)->delete('bku');
    $this->db->where('id_aktivitas', $id)->delete('aktivitas');
  }

  /* Query rincian BUD */
  function get_rinci_by_id($id)
  {
    $this->db->select('
          a.id_rincian_bku,
          a.id_aktivitas_kontra_pos,
          a.id_aktivitas_pajak_sp2d,
          a.id_aktivitas_pfk_sp2d,
          a.id_aktivitas_setoran_sisa,
          a.id_aktivitas_sp2d,
          a.id_aktivitas_sts,
          a.id_sumber_dana,
          a.no_bku,
          a.nomor_transaksi,
          a.kode_rekening,
          a.nama_rekening,
          a.debet,
          a.kredit,
          cast(substring(a.deskripsi_transaksi from 1 for 5000) as varchar(5000)) deskripsi_transaksi
    ');
    $this->db->from('v_rincian_bku_3 a');
    $this->db->where('a.id_aktivitas', $id);
    $result = $this->db->get()->result_array();

    return $result;
  }

  function get_sumberdana_by_id($id, $tanggal)
  {
    $result = $this->db->query("
          select
          a.id_sumber_dana,
          a.nama_sumber_dana,
          sum(a.saldo_awal) saldo_awal,
          0 saldo_akhir
          from (
              select
                sd.id_sumber_dana,
                sd.nama_sumber_dana,
                0 saldo_awal
              from sumber_dana sd
              union all
              select
                sd.id_sumber_dana,
                sd.nama_sumber_dana,
                sum(rj.debet - rj.kredit) saldo_awal
              from v_jurnal_3 j
              join rincian_jurnal rj on rj.id_jurnal = j.id_jurnal
              join sumber_dana sd on sd.id_rekening = rj.id_rekening
              where j.tahun = ".$this->tahun."
                and (j.tanggal_jurnal < '".prepare_date($tanggal)."'
                  or (j.tanggal_jurnal = '".prepare_date($tanggal)."'
                    and (j.id_aktivitas_pengesah is null or j.id_aktivitas_pengesah < ".$id.")
                    and j.id_aktivitas_pengesah <> ".$id."
                  )
                )
              group by 1, 2
          ) a
          group by 1, 2
          order by 2
    ")->result_array();

    return $result;
  }

  function get_aktivitas_by_id($id)
  {
    $this->db->select('
        a.tipe,
        a.id_skpd,
        s.kode_skpd_lkp,
        s.nama_skpd
    ');
    $this->db->from('aktivitas a');
    $this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
    $this->db->where('a.id_aktivitas', $id);
    $result = $this->db->get()->row_array();

    return $result;
  }

}