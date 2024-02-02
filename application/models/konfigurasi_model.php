<?php
class Konfigurasi_model extends CI_Model {

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  function get_versi()
  {
    $this->db->select("versi, patch");
    $result = $this->db->get("konfigurasi")->row_array();

    return $result;
  }

  function get_all()
  {
    $this->db->select("
        spd_anggaran,
        spd_up,
        spd_gu,
        spd_gu_spj,
        spd_ls,
        spd_all,
        spp_spm_sp2d,
        spp_up_spd,
        spp_up,
        spp_gu_spj,
        spp_gu_spd,
        spp_gu,
        spp_ls_spd,
        spp_pp_sts,
        bku_dp,
        spj_sp2d,
        spj_belanja,
        spj_auto,
        sts_tbp,
        spp_swakelola,
        no_bku_bersambung,
        bku_auto,
        spd_tu,
        spp_tu,
        spp_tu_spd,
        no_spp_beban,
        spp_gu_rincian_spj,
        kunci_pagu_gaji,
        no_bku_skpd_bersambung,
        panjar
    ");
    $result = $this->db->get("konfigurasi_aktivitas")->row_array();

    return $result;
  }

  function get_mode_spp($keperluan)
  {
    $konf = $this->get_all();

    switch ($keperluan)
    {
      case 'UP' : $mode = $konf['SPP_UP']; break;
      case 'GU' : $mode = $konf['SPP_GU']; break;
      case 'TU' : $mode = $konf['SPP_TU']; break;
      case 'LS' : $mode = $konf['SPP_LS']; break;
      default : $mode = 3;
    }

    return $mode;
  }

  function pakai_spd($keperluan)
  {
    $konf = $this->get_all();

    switch ($keperluan)
    {
      case 'UP' : $mode = $konf['SPP_UP_SPD']; break;
      case 'GU' : $mode = $konf['SPP_GU_SPD']; break;
      case 'TU' : $mode = $konf['SPP_TU_SPD']; break;
      case 'LS' : $mode = $konf['SPP_LS_SPD']; break;
      default : $mode = 0;
    }

    return $mode;
  }
}
