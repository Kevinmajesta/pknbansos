<?php
class Pilih_kegiatan_model extends Pilih_Model {

  function __construct()
  {
    parent::__construct();
  }

  function KegiatanAnggaran($id_skpd, $sppspm, $keperluan, $tanggal, $id)
  {
    $this->db->select("
      a.id_kegiatan,
      a.kode_kegiatan_lkp,
      a.kode_kegiatan_skpd,
      a.nama_kegiatan
    ");
    $this->db->from("p_kegiatan_skpd(".$id_skpd.") a");
    $this->db->join("v_form_anggaran_lkp f", "f.id_kegiatan = a.id_kegiatan and f.id_skpd = a.id_skpd");
    $this->db->where("f.tahun", $this->tahun);
    $this->db->where("f.status", $this->status);
    $this->db->where("f.tipe", 'RKA221');
    if (strtoupper($sppspm) === "SPD" || strtoupper($sppspm) === "SPJP")
    {
      $sql = "
        coalesce(select sum(rd.nominal)
        from rincian_spd rd
        join aktivitas d on d.id_aktivitas = rd.id_aktivitas
        where rd.id_kegiatan = a.id_kegiatan
          and d.tahun = f.tahun
          and d.id_skpd = fa.id_skpd "
          .(strtoupper($sppspm) === "SPD" ? "and d.id_aktivitas <> ".$param['id'] : "" ).", 0)
        +
        coalesce(select sum(rj.nominal)
        from rincian_spjp_b rj
        join aktivitas j on j.id_aktivitas = rj.id_aktivitas
        where rj.id_kegiatan = k.id_kegiatan
          and j.tahun = f.tahun
          and j.id_skpd = f.id_skpd "
          .(strtoupper($sppspm) === "SPJP" ? "and j.id_aktivitas <> ".$param['id'] : "").", 0)
      ";
      $this->db->where("coalesce(f.nominal_anggaran, 0) >", "coalesce(".$sql.", 0)");
    }
    else
    {
/*
      if ($param['keperluan'] === 'GU' && $konf['SPP_GU_SPJ'] === 1)
      {
        $this->db->where("(select nominal_sisa from sisa_".$sppspm."_gu(a.id_skpd, ".$this->tahun.", '".prepare_date($param['tanggal']).") > 0 )");
      }
*/
    }
  }

  function KegiatanSPPSPM($id_skpd, $sppspm, $id, $arrspd, $keperluan, $beban, $tanggal)
  {
    $this->db->select("
      a.id_kegiatan,
      a.kode_kegiatan_lkp,
      a.kode_kegiatan_skpd,
      a.nama_kegiatan
    ");
    $this->db->from("kegiatan_spd(".$id_skpd.", ".$this->tahun.", '".$arrspd."') kd");
    $this->db->join("p_kegiatan_skpd(".$id_skpd.") a", "a.id_kegiatan = kd.id_kegiatan");
    //$this->db->join("sisa_sppjp_kegiatan(a.id_skpd, ".$this->tahun.", a.id_kegiatan, '".prepare_date($tanggal)."', '".$beban."', ".$id.", '".$arrspd."') s1", "1 = 1", "left");
/*
    if ($keperluan === 'GU')
    {
      $this->db->join("sisa_".$sppspm."_gu(a.id_skpd, ".$this->tahun.", '".$prepare_date($tanggal)."') s2", "1 = 1", "left");
      $this->db->where("s2.nominal > 0");
    }
*/
    $this->db->group_by("
      a.id_kegiatan,
      a.kode_kegiatan_lkp,
      a.kode_kegiatan_skpd,
      a.nama_kegiatan
    ");
  }

  function getKegiatan($param, $isCount=FALSE)
  {
    $fieldmap = array(
      'id' => 'a.ID_KEGIATAN',
      'kode' => 'a.KODE_KEGIATAN_LKP',
      'kodes' => 'a.KODE_KEGIATAN_SKPD',
      'nama' => 'a.NAMA_KEGIATAN'
    );

    $wh = $this->checkSearch($param['q'], $fieldmap);
    if ($wh) $this->db->or_where($wh);

    ($param['sort_by'] != null && !$isCount) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    /*
      mode : spd
      keterangan : kegiatan yang bisa dibuatkan spd
      dipakai di SPD
    */
    if ($param['mode'] === 'spd'){
      $this->db->select('
        a.id_kegiatan,
        a.kode_kegiatan_lkp,
        a.kode_kegiatan_skpd,
        a.nama_kegiatan
      ');
      $this->db->from('v_form_anggaran_lkp f');
      $this->db->join("v_kegiatan_skpd a","a.id_kegiatan = f.id_kegiatan and a.id_skpd = f.id_skpd");
      $this->db->join("sisa_dpa_kegiatan(f.id_skpd, f.tahun, a.id_kegiatan, 'BL', ".$param['id'].", '".prepare_date($param['tanggal'])."') sd","1 = 1",'LEFT');
      $this->db->join("tahun_anggaran t", "t.tahun = f.tahun and f.status = t.status_kini");
      $this->db->where('f.tahun', $this->tahun);
      $this->db->where('f.id_skpd', $param['id_skpd'] );
      $this->db->where('f.tipe', 'RKA221');
      $this->db->where('sd.nominal > 0');
    }
    /*
      mode : spp
      keterangan : kegiatan yang bisa dibuatkan spp
      dipakai di SPP
    */
    else if ($param['mode'] === 'spp'){
      if (is_array($param['arrspd']))
      {
        $arrspd = join(',', $param['arrspd']);
      }
      else
      {
        $arrspd = $param['arrspd'];
      }

      switch ($param['keperluan'])
      {
        case 'UP' : $mode_spp = 1; $spp_pakai_spd = 0; break;
        case 'GU' : $mode_spp = 1; $spp_pakai_spd = 1; break;
        case 'TU' : $mode_spp = 3; $spp_pakai_spd = 1; break;
        case 'LS' : $mode_spp = 3; $spp_pakai_spd = 1; break;
        default : $spp_pakai_spd = 0;
      }

      if ($spp_pakai_spd && $mode_spp !== 1)
      {
        $this->KegiatanSPPSPM($param['id_skpd'], 'spp', $param['id'], $arrspd, $param['keperluan'], 'LS', $param['tanggal']);
      }
      else
      {
        $this->KegiatanAnggaran($param['id_skpd'], 'spp', '', prepare_date($param['tanggal']), $param['id']);
      }
    }
    /*
      mode : spj
      keterangan : kegiatan yang bisa dibuatkan spj
      dipakai di SPJ
    */
    else if ($param['mode'] === 'spj'){
      $this->db->select('distinct
        a.id_kegiatan,
        a.kode_kegiatan_lkp,
        a.kode_kegiatan_skpd,
        a.nama_kegiatan
      ');
      $this->db->from("kegiatan_sp2d_spj(".$this->tahun.", ".$param['id_skpd'].", '".$param['keperluan']."', 'BL') a");
      //$this->db->join("sisa_spj_kegiatan (".$param['id_skpd'].", ".$this->tahun.", '".prepare_date($param['tanggal'])."', a.id_kegiatan, '".$param['keperluan']."', '".$param['beban']."', ".$param['id_spj'].") b", "1 = 1", "left");
      //$this->db->where("b.nominal > 0");
    }
    /*
      mode : rka221
      keterangan :
      dipakai di RKA221
    */
    else if ($param['mode'] === 'rka221'){
      $this->db->select('distinct
        a.id_kegiatan,
        a.nama_kegiatan,
        a.kode_kegiatan,
        a.kode_kegiatan_lkp,
        a.kode_kegiatan_skpd,
      ');
      $this->db->from("p_kegiatan_skpd(".$param['id_skpd'].") a");
      $this->db->where("not exists (
            select 1 
            from v_form_anggaran_lkp fa 
            where fa.tahun = ".$this->tahun." 
                and fa.status = '".$this->status."'
                and fa.tipe = 'RKA221' 
                and fa.id_skpd = ".$param['id_skpd']." 
                and fa.id_kegiatan = a.id_kegiatan)
      ");
    }
    /*
      mode : -
      keterangan : semua kegiatan
      dipakai di ...
    */
    else {
      $this->db->select('
          a.id_kegiatan,
          a.kode_kegiatan_lkp,
          a.kode_kegiatan_skpd,
          a.nama_kegiatan
        ');
        $this->db->from('v_kegiatan_skpd a ');
        $this->db->where('a.id_skpd', $param['id_skpd']);
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

}