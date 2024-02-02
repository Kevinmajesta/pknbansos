<?php
class Pilih_aktivitas_model extends Pilih_Model {

  function __construct()
  {
    parent::__construct();
  }

  function getSPP($param, $isCount=FALSE)
  {
    $fieldmap = array(
      'id' => 'a.ID_AKTIVITAS',
      'no' => 'a.NOMOR',
      'tgl' => 'a.TANGGAL',
      'kdskpd' => 's.KODE_SKPD',
      'nmskpd' => 's.NAMA_SKPD',
      'nmkeg' => 'a.NAMA_KEGIATAN',
      'kpl' => 'a.KEPERLUAN',
      'bbn' => 'a.BEBAN',
    );

    $wh = $this->checkSearch($param['q'], $fieldmap);
    //if ($wh) $this->db->or_where($wh);
    
    if ($wh) {
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

    ($param['sort_by'] != null && !$isCount) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    $this->db->select('
        a.id_aktivitas,
        a.nomor,
        a.tanggal,
        cast(substring(a.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
        a.id_skpd,
        s.kode_skpd,
        s.kode_skpd_lkp,
        s.nama_skpd,
        a.nama_kegiatan,
        a.beban,
        a.keperluan,
        a.nominal,
    ');
    $this->db->from('v_spp_kegiatan a');
    $this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
    $this->db->where('a.tahun', $this->tahun);
    $this->db->where('a.spm_pakai', 0);
    $this->db->where('a.tanggal <=', prepare_date($param['tanggal']) );
    if ($param['id_skpd'] <> 0) $this->db->where('a.id_skpd', $param['id_skpd']);

    if ($isCount) {
      $result = $this->db->count_all_results();
      return $result;
    }
    else {
      $result = $this->db->get()->result_array();
      return $result;
    }
  }

  function getSPM($param, $isCount=FALSE)
  {
    $fieldmap = array(
      'id' => 'a.ID_AKTIVITAS',
      'no' => 'a.NOMOR',
      'tgl' => 'a.TANGGAL',
      'kdskpd' => 's.KODE_SKPD',
      'nmskpd' => 's.NAMA_SKPD',
      'nmkeg' => 'a.NAMA_KEGIATAN',
      'kpl' => 'a.KEPERLUAN',
      'bbn' => 'a.BEBAN',
    );

    $wh = $this->checkSearch($param['q'], $fieldmap);
    //if ($wh) $this->db->or_where($wh);
    
    if ($wh) {
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

    ($param['sort_by'] != null && !$isCount) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    $this->db->select('
        a.id_aktivitas,
        a.nomor,
        a.tanggal,
        cast(substring(a.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
        a.id_skpd,
        s.kode_skpd,
        s.kode_skpd_lkp,
        s.nama_skpd,
        a.nama_kegiatan,
        a.beban,
        a.keperluan,
        a.nominal,
    ');
    $this->db->from('v_spm_kegiatan a');
    $this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
    $this->db->where('a.tahun', $this->tahun);
    $this->db->where('a.sp2d_pakai', 0);
    $this->db->where('a.tanggal <=', prepare_date($param['tanggal']) );
    if ($param['id_skpd'] <> 0) $this->db->where('a.id_skpd', $param['id_skpd']);

    if ($isCount) {
      $result = $this->db->count_all_results();
      return $result;
    }
    else {
      $result = $this->db->get()->result_array();
      return $result;
    }
  }

  function getSP2D($param, $isCount=FALSE)
  {
    $fieldmap = array(
      'id' => 'a.ID_AKTIVITAS',
      'no' => 'a.NOMOR',
      'tgl' => 'a.TANGGAL',
      'nmskpd' => 's.NAMA_SKPD',
      'nmkeg' => 'a.NAMA_KEGIATAN',
      'ket' => 'a.DESKRIPSI',
      'nom' => 'a.nominal',
    );

    $wh = $this->checkSearch($param['q'], $fieldmap);
    //if ($wh) $this->db->or_where($wh);
    
    if ($wh) {
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

    ($param['sort_by'] != null && !$isCount) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    if ($param['mode'] === 'dp'){
      $this->db->select('
          a.id_aktivitas,
          a.nomor,
          a.tanggal,
          cast(substring(a.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
          a.id_skpd,
          s.kode_skpd,
          s.kode_skpd_lkp,
          s.nama_skpd,
          a.nama_kegiatan,
          a.id_sumber_dana,
          sd.nama_sumber_dana,
          r.kode_rekening,
          r.nama_rekening,
          p.nama_penerima,
          a.nominal,
          a.nominal_kotor,
          a.nominal_rincian,
          a.nominal_pfk,
          a.nominal_pfk_informasi,
          a.nominal_pajak,
          a.nominal_pajak_informasi
      ');
      $this->db->from('v_sp2d_kegiatan a');
      $this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
      $this->db->join('spm m', 'm.id_aktivitas = a.id_aktivitas_spm');
      $this->db->join('spp p', 'p.id_aktivitas = m.id_spp');
      $this->db->join('sumber_dana sd', 'sd.id_sumber_dana = a.id_sumber_dana');
      $this->db->join('rekening r', 'r.id_rekening = sd.id_rekening');
      $this->db->where('a.tahun', $this->tahun);
      $this->db->where('a.tanggal <=', prepare_date($param['tanggal']) );
      $this->db->where('a.penguji_pakai', 0);
    }
    else if ($param['mode'] === 'bud'){
      $this->db->select('
          a.id_aktivitas,
          a.nomor,
          a.tanggal,
          cast(substring(a.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
          a.id_skpd,
          s.kode_skpd,
          s.kode_skpd_lkp,
          s.nama_skpd,
          a.nama_kegiatan,
          a.id_sumber_dana,
          sd.nama_sumber_dana,
          r.kode_rekening,
          r.nama_rekening,
          p.nama_penerima,
          a.nominal,
          a.nominal_kotor,
          a.nominal_rincian,
          a.nominal_pfk,
          a.nominal_pfk_informasi,
          a.nominal_pajak,
          a.nominal_pajak_informasi
      ');
      $this->db->from('v_sp2d_kegiatan a');
      $this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
      $this->db->join('spm m', 'm.id_aktivitas = a.id_aktivitas_spm');
      $this->db->join('spp p', 'p.id_aktivitas = m.id_spp');
      $this->db->join('sumber_dana sd', 'sd.id_sumber_dana = a.id_sumber_dana');
      $this->db->join('rekening r', 'r.id_rekening = sd.id_rekening');
      $this->db->where('a.tahun', $this->tahun);
      $this->db->where('a.bku_pakai', 0);
      $this->db->where('a.tanggal <=', prepare_date($param['tanggal']) );
/*
      if ( BUD berdasarkan daftar penguji ){
        $this->db->where('a.tanggal <=', prepare_date($param['tanggal']) );
        $this->db->where('a.penguji_pakai', 0);
      }
*/
    }

    if ($isCount) {
      $result = $this->db->count_all_results();
      return $result;
    }
    else {
      $result = $this->db->get()->result_array();
      return $result;
    }
  }

  function getSPJ($param, $isCount=FALSE)
  {
    $fieldmap = array(
      'id' => 'a.ID_AKTIVITAS',
      'no' => 'a.NOMOR',
      'tgl' => 'a.TANGGAL',
      'kdskpd' => 's.KODE_SKPD_LKP',
      'nmskpd' => 's.NAMA_SKPD',
      'kpl' => 'a.KEPERLUAN',
      'bbn' => 'a.BEBAN',
      'ket' => 'a.DESKRIPSI',
      'nom' => 'b.NOMINAL',
    );

    $wh = $this->checkSearch($param['q'], $fieldmap);
    if ($wh) $this->db->or_where($wh);

    ($param['sort_by'] != null && !$isCount) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    $keperluan = '';
    if (isset($param['keperluan']))
    {
      $keperluan = $param['keperluan'] === 'GU' ? 'UP' : $param['keperluan'];
    }

    if ($param['mode'] === 'spp'){
      $this->db->select('
          a.id_aktivitas,
          a.nomor,
          a.tanggal,
          cast(substring(a.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
          a.keperluan,
          a.beban,
          a.id_skpd,
          s.kode_skpd,
          s.kode_skpd_lkp,
          s.nama_skpd,
          a.nominal
      ');
      $this->db->from('v_spj_kegiatan a');
      $this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
      $this->db->where('a.tahun', $this->tahun);
      $this->db->where('a.id_skpd', $param['id_skpd']);
      $this->db->where('a.keperluan', $keperluan);
      $this->db->where('a.beban', $param['beban']);
      $this->db->where('a.tanggal <=', prepare_date( $param['tanggal'] ));
    }

    if ($isCount) {
      $result = $this->db->count_all_results();
      return $result;
    }
    else {
      $result = $this->db->get()->result_array();
      return $result;
    }
  }

  function getSPD($param, $isCount=FALSE)
  {
    $fieldmap = array(
      'id' => 's.ID_AKTIVITAS',
      'no' => 's.NOMOR',
      'tgl' => 's.TANGGAL',
      'kdskpd' => 'd.KODE_SKPD_LKP',
      'nmskpd' => 'd.NAMA_SKPD',
      'bbn' => 's.BEBAN',
      'ket' => 's.DESKRIPSI',
      'nom' => 's.NOMINAL',
    );

    $beban = ($param['beban'] === 'GJ' ? 'BTL' : $param['beban']);

    $wh = $this->checkSearch($param['q'], $fieldmap);
    //if ($wh) $this->db->or_where($wh);
    
    if ($wh) {
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
    
    ($param['sort_by'] != null && !$isCount) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    $this->db->select('
        s.id_aktivitas,
        s.nomor,
        s.tanggal,
        cast(substring(s.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
        s.id_skpd,
        d.kode_skpd_lkp,
        d.nama_skpd,
        s.nominal,
        s.beban,
        s.nama_kegiatan,
        s.nominal
    ');
    $this->db->from("v_spd_kegiatan s");
    $this->db->join("v_skpd d", "d.id_skpd = s.id_skpd");
//    $this->db->join("sisa_sppmjp_skpd(s.id_skpd, s.tahun, '".prepare_date($param['tanggal'])."', '".$param['keperluan']."', '".$beban."', ".$param['id'].", s.id_aktivitas) s1", "1 = 1", "left");
//    $this->db->join("sisa_spd_aktivitas(s.id_skpd, s.tahun, '".prepare_date($param['tanggal'])."', '".$beban."', ".$param['id'].", s.id_aktivitas) s2", "1 = 1", "left");
    $this->db->where("s.tahun", $this->tahun);
    $this->db->where("s.id_skpd", $param['id_skpd']);
    $this->db->where("s.beban", $beban);
    $this->db->where("s.tanggal <=", prepare_date($param['tanggal']));
/*
    if ($spdall)
    {
      $this->db->where("(s1.nominal > 0 or s2.nominal_sisa_tanggal > 0)");
    }
*/
    if ($isCount) {
      $result = $this->db->count_all_results();
      return $result;
    }
    else {
      $result = $this->db->get()->result_array();
      return $result;
    }
  }

}