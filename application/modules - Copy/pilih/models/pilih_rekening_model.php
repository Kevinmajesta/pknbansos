<?php
class Pilih_rekening_model extends Pilih_Model {

  function __construct()
  {
    parent::__construct();
  }

  function RekeningBL()
  {
    $this->db->select('
      r.id_rekening,
      r.id_parent_rekening,
      r.kode_rekening,
      r.nama_rekening,
      r.level_rekening,
      r.saldo_normal,
      r.child_count
    ');
    $this->db->from("rekening r");
    $this->db->where("exists (select 1 from v_tkjor_bl v "
        ."where v.tipe = r.tipe and "
        ."(v.kelompok = '' or v.kelompok = r.kelompok) and "
        ."(v.jenis = '' or v.jenis = r.jenis) and "
        ."(v.objek = '' or v.objek = r.objek) and "
        ."(v.rincian = '' or v.rincian = r.rincian)"
        .") or (r.tipe = '5' and r.kelompok = '')"
    );
  }

  function RekeningBTL()
  {
    $this->db->select('
      r.id_rekening,
      coalesce(r.id_parent_rekening, 0) as id_parent_rekening,
      r.kode_rekening,
      r.nama_rekening,
      r.level_rekening,
      r.saldo_normal,
      r.child_count
    ');
    $this->db->from("rekening r");
    $this->db->where("exists (select 1 from v_tkjor_btl v "
        ."where v.tipe = r.tipe and "
        ."(v.kelompok = '' or v.kelompok = r.kelompok) and "
        ."(v.jenis = '' or v.jenis = r.jenis) and "
        ."(v.objek = '' or v.objek = r.objek) and "
        ."(v.rincian = '' or v.rincian = r.rincian)"
        .") or (r.tipe = '5' and r.kelompok = '') "
    );
  }

  function RekeningAnggaranBL($id_skpd, $sppspm, $keperluan, $tanggal, $id, $id_kegiatan)
  {
    $this->db->select('
      r.id_rekening,
      r.id_parent_rekening,
      r.kode_rekening,
      r.nama_rekening,
      r.level_rekening,
      r.saldo_normal,
      r.child_count
    ');
    $this->db->from("rekening r");
    $this->db->join("v_rekening_rincian_anggaran rin", "rin.id_rekening = r.id_rekening");
    $this->db->join("v_form_anggaran_lkp f", "f.id_form_anggaran = rin.id_form_anggaran");
    $this->db->join("tahun_anggaran t", "t.tahun = f.tahun and f.status = t.status_kini");
    $this->db->where("f.id_skpd", $id_skpd);
    $this->db->where("f.tahun", $this->tahun);
    $this->db->where("f.tipe", "RKA221");
    $this->db->where("f.id_kegiatan", $id_kegiatan);

    if (strtoupper($sppspm) === "SPD" || strtoupper($sppspm) === "SPJP")
    {
      $sql = "
        (coalesce((select sum(rd.nominal)
        from rincian_rekening_spd rd
        join aktivitas d on d.id_aktivitas = rd.id_aktivitas
        where rd.id_kegiatan = fa.id_kegiatan
          and d.tahun = f.tahun
          and d.id_skpd = f.id_skpd
          and rd.id_rekening = rin.id_rekening
          ".( strtoupper($sppspm) === 'SPD' ? "and d.id_aktivitas <> ".$id : "")."), 0)
        +
        coalesce((select sum(rj.nominal)
        from rincian_spjp_b rj
        join v_spjp j on j.id_aktivitas = rj.id_aktivitas
        where rj.id_kegiatan = f.id_kegiatan
          and j.tahun = f.tahun
          and j.id_skpd = f.id_skpd
          and rj.id_rekening = rin.id_rekening
          and j.beban = 'BL'
          ".( strtoupper($sppspm) === "SPJP" ? "and j.id_aktivitas <> ".$id : "")."), 0) )
      ";
      $this->db->where("coalesce(rin.pagu, 0) >", $sql);
    }
/*
    else if ($keperluan === 'GU' && $konf['SPP_GU_SPJ'] === 1)
    {
      $this->db->where("(select nominal_sisa
         from sisa_".$sppspm."_gu(".$id_skpd.", ".$this->tahun.", '".prepare_date($tanggal)."')) > 0");
    }
*/

    $this->db->group_by("
      r.id_rekening,
      r.id_parent_rekening,
      r.kode_rekening,
      r.nama_rekening,
      r.level_rekening,
      r.saldo_normal,
      r.child_count
    ");
  }

  function RekeningAnggaranBTL($id_skpd, $sppspm, $keperluan, $jenis_beban, $tanggal, $id)
  {
    $this->db->select('
      r.id_rekening,
      r.id_parent_rekening,
      r.kode_rekening,
      r.nama_rekening,
      r.level_rekening,
      r.saldo_normal,
      r.child_count
    ');
    $this->db->from("rekening r");
    $this->db->join("v_rekening_rincian_anggaran rin", "rin.id_rekening = r.id_rekening");
    $this->db->join("v_form_anggaran_lkp f", "f.id_form_anggaran = rin.id_form_anggaran");
    $this->db->join("tahun_anggaran t", "t.tahun = f.tahun and f.status = t.status_kini");
    $this->db->where("f.id_skpd", $id_skpd);
    $this->db->where("f.tahun", $this->tahun);
    $this->db->where("f.tipe", "RKA21");

    if ($jenis_beban === 'GJ')
    {
      $this->db->join("join v_tkjor_gaji v", "v.tipe = r.tipe and "
        ."(v.kelompok = '' or v.kelompok = r.kelompok) and "
        ."(v.jenis = '' or v.jenis = r.jenis) and "
        ."(v.objek = '' or v.objek = r.objek) and "
        ."(v.rincian = '' or v.rincian = r.rincian)");
    }
    else if ($jenis_beban === 'BTL')
    {
      $this->db->where("r.id_rekening not in (
        select r2.id_rekening from rekening r2
        join v_tkjor_gaji v2 on
        (v2.tipe = '' or v2.tipe = r2.tipe) and
        (v2.kelompok = '' or v2.kelompok = r2.kelompok) and
        (v2.jenis = '' or v2.jenis = r2.jenis) and
        (v2.objek = '' or v2.objek = r2.objek) and
        (v2.rincian = '' or v2.rincian = r2.rincian))");
    }

    if (strtoupper($sppspm) === "SPD" || strtoupper($sppspm) === "SPJP")
    {
      $sql = "
        (coalesce((select sum(rd.nominal)
        from rincian_rekening_spd rd
        join aktivitas d on d.id_aktivitas = rd.id_aktivitas
        where rd.id_kegiatan is null
          and d.tahun = f.tahun
          and d.id_skpd = f.id_skpd
          and rd.id_rekening = rin.id_rekening
          ".( strtoupper($sppspm) === 'SPD' ? "and d.id_aktivitas <> ".$id : "")."), 0)
        +
        coalesce((select sum(rj.nominal)
        from rincian_spjp_b rj
        join v_spjp j on j.id_aktivitas = rj.id_aktivitas
        where rj.id_kegiatan is null
          and j.tahun = f.tahun
          and j.id_skpd = f.id_skpd
          and rj.id_rekening = rin.id_rekening
          and j.beban = 'BTL'
          ".( strtoupper($sppspm) === "SPJP" ? "and j.id_aktivitas <> ".$id : "")."), 0) )
      ";
      $this->db->where("coalesce(rin.pagu, 0) >", $sql);
    }
/*
    else if ($keperluan === 'GU' && $konf['SPP_GU_SPJ'] === 1)
    {
      $this->db->where("(select nominal_sisa
         from sisa_".$sppspm."_gu(".$id_skpd.", ".$this->tahun.", '".prepare_date($tanggal)."')) > 0");
    }
*/

    $this->db->group_by("
      r.id_rekening,
      r.id_parent_rekening,
      r.kode_rekening,
      r.nama_rekening,
      r.level_rekening,
      r.saldo_normal,
      r.child_count
    ");
  }

  function RekeningAnggaranKB($id_skpd, $sppspm, $keperluan, $tanggal, $id)
  {
    $this->db->select('
      r.id_rekening,
      r.id_parent_rekening,
      r.kode_rekening,
      r.nama_rekening,
      r.level_rekening,
      r.saldo_normal,
      r.child_count
    ');
    $this->db->from("rekening r");
    $this->db->join("v_rekening_rincian_anggaran rin", "rin.id_rekening = r.id_rekening");
    $this->db->join("v_form_anggaran_lkp f", "f.id_form_anggaran = rin.id_form_anggaran");
    $this->db->join("tahun_anggaran t", "t.tahun = f.tahun and f.status = t.status_kini");
    $this->db->where("f.id_skpd", $id_skpd);
    $this->db->where("f.tahun", $this->tahun);
    $this->db->where("f.tipe", "RKA32");

    if (strtoupper($sppspm) === "SPD" || strtoupper($sppspm) === "SPJP")
    {
      $sql = "
        (coalesce((select sum(rd.nominal)
        from rincian_rekening_spd rd
        join aktivitas d on d.id_aktivitas = rd.id_aktivitas
        where rd.id_kegiatan is null
          and d.tahun = f.tahun
          and d.id_skpd = f.id_skpd
          and rd.id_rekening = rin.id_rekening
          ".( strtoupper($sppspm) === 'SPD' ? "and d.id_aktivitas <> ".$id : "")."), 0)
        +
        coalesce((select sum(rj.nominal)
        from rincian_spjp_b rj
        join v_spjp j on j.id_aktivitas = rj.id_aktivitas
        where rj.id_kegiatan is null
          and j.tahun = f.tahun
          and j.id_skpd = f.id_skpd
          and rj.id_rekening = rin.id_rekening
          and j.beban = 'KB'
          ".( strtoupper($sppspm) === "SPJP" ? "and j.id_aktivitas <> ".$id : "")."), 0) )
      ";
      $this->db->where("coalesce(rin.pagu, 0) >", $sql);
    }
/*
    else if ($keperluan === 'GU' && $konf['SPP_GU_SPJ'] === 1)
    {
      $this->db->where("(select nominal_sisa
         from sisa_".$sppspm."_gu(".$id_skpd.", ".$this->tahun.", '".prepare_date($tanggal)."')) > 0");
    }
*/

    $this->db->group_by("
      r.id_rekening,
      r.id_parent_rekening,
      r.kode_rekening,
      r.nama_rekening,
      r.level_rekening,
      r.saldo_normal,
      r.child_count
    ");
  }

  function RekeningSPPSPM($id_skpd, $sppspm, $id, $arrspd, $keperluan, $beban, $jenis_beban, $tanggal, $id_kegiatan)
  {
    $this->db->select('
      r.id_rekening,
      r.id_parent_rekening,
      r.kode_rekening,
      r.nama_rekening,
      r.level_rekening,
      r.saldo_normal,
      r.child_count
    ');
    $this->db->from("rekening_spd(".$id_skpd.", ".$this->tahun.", '".$beban."', ".$id_kegiatan.", '".$arrspd."') rekd");
    $this->db->join("rekening r", "r.id_rekening = rekd.id_rekening");

    if ($jenis_beban === 'GJ')
    {
      $this->db->join("v_tkjor_gaji v", "v.tipe = r.tipe and ".
          "(v.kelompok = '' or v.kelompok = r.kelompok) and ".
          "(v.jenis = '' or v.jenis = r.jenis) and ".
          "(v.objek = '' or v.objek = r.objek) and ".
          "(v.rincian = '' or v.rincian = r.rincian)", "inner", true);
    }
    else
    {
//      $this->db->join("sisa_sppjp_rekening(".$id_skpd.", ".$this->tahun.", ".$id_kegiatan.", r.id_rekening, '".prepare_date($tanggal)."', '".$beban."', ".$id.", '".$arrspd."') sr", "1 = 1", "left");
//      $this->db->where("coalesce(sr.nominal, 0) > 0");

      if ($jenis_beban === 'BTL')
      {
        $this->db->where("r.id_rekening not in (
           select r2.id_rekening
           from rekening r2
           join v_tkjor_gaji v2 on (v2.tipe = '' or v2.tipe = r2.tipe) and
              (v2.kelompok = '' or v2.kelompok = r2.kelompok) and
              (v2.jenis = '' or v2.jenis = r2.jenis) and
              (v2.objek = '' or v2.objek = r2.objek) and
              (v2.rincian = '' or v2.rincian = r2.rincian)
           )");
      }
    }
/*
    if ($keperluan === 'GU' && $konf['SPP_GU_SPJ'] === 1)
    {
      $this->db->join("sisa_".$sppspm."_gu(".$id_skpd.", ".$this->tahun.", '".$prepare_date($tanggal).") sgu", "1 = 1", "left");
      $this->db->where("coalesce(sgu.nominal, 0) > 0");
    }
*/
    }

  function getRekening($param)
  {
    $fieldmap = array(
      'id' => 'r.ID_REKENING',
      'kdrek' => 'r.KODE_REKENING',
      'nmrek' => 'r.NAMA_REKENING',
    );

    $wh = $this->checkSearch($param['q'], $fieldmap);
    if ($wh) $this->db->or_where($wh);

    ($param['sort_by'] != null) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';
    /*mode : rka21
      keterangan : rekening belanja tidak langsung
      dipakai di RKA 21
    */
    if ($param['mode'] === 'rka21'){
      $this->RekeningBTL();
    }
    /*mode : rka221
      keterangan : rekening belanja langsung
      dipakai di RKA 221
    */
    else if ($param['mode'] === 'rka221'){
      $this->RekeningBL();
    }
    /*
      mode : anggaran_bl
      keterangan : rekening belanja langsung yang memiliki anggaran
      dipakai di ...
    */
    else if ($param['mode'] === 'anggaranbl'){
      $this->RekeningAnggaranBL($param['id_skpd'], '', '', 0, 0, $param['id_kegiatan']);
    }
    /*
      mode : anggaran_btl
      keterangan : rekening belanja tidak langsung yang memiliki anggaran
      dipakai di ...
    */
    else if ($param['mode'] === 'anggaranbtl'){
      $this->RekeningAnggaranBTL($param['id_skpd'], '', '', '', 0, 0);
    }
    /*
      mode : spd
      keterangan : rekening yang bisa dibuatkan spd
      dipakai di SPD
    */
    else if ($param['mode'] === 'spd'){
      switch ($param['beban'])
      {
        case 'BTL' : $tp = 'RKA21'; break;
        case 'BL'  : $tp = 'RKA221'; break;
        case 'KB'  : $tp = 'RKA32'; break;
        default : $tp = '';
      }
      $keg = ($param['id_kegiatan'] === 0 ? 'NULL' : $param['id_kegiatan']);

      $this->db->select('
        r.id_rekening,
        r.id_parent_rekening,
        r.kode_rekening,
        r.nama_rekening,
        r.level_rekening,
        r.saldo_normal,
        r.child_count
      ');
      $this->db->from('rekening r');
      $this->db->join("rincian_anggaran rin", "rin.id_rekening = r.id_rekening");
      $this->db->join("v_form_anggaran_lkp f", "f.id_form_anggaran = rin.id_form_anggaran");
      $this->db->join("tahun_anggaran t", "t.tahun = f.tahun and f.status = t.status_kini");

      $this->db->join("sisa_dpa_rekening(".$param['id_skpd'].", ".$this->tahun.", ".$keg.", r.id_rekening, '".$param['beban']."', ".$param['id'].", null) s1", "1 = 1", 'left');
      $this->db->where('f.id_skpd', $param['id_skpd'] );
      $this->db->where('f.tahun', $this->tahun);
      $this->db->where('f.tipe', $tp);

      if($param['beban'] === 'BL')
      {
        $this->db->where('f.id_kegiatan', $keg);
      }

      $this->db->where('s1.nominal > 0');
      $this->db->group_by("
        r.id_rekening,
        r.id_parent_rekening,
        r.kode_rekening,
        r.nama_rekening,
        r.level_rekening,
        r.saldo_normal,
        r.child_count
      ");
    }
    /*
      mode : spp
      keterangan : rekening yang bisa dibuatkan spp
      dipakai di SPP
    */
    else if ($param['mode'] === 'spp'){

      //$beban = ($param['beban'] === 'GJ' ? 'BTL' : $param['beban'] );
      //$arrspd = (is_array($param['arrspd']) ? join(',', $param['arrspd']) : $param['arrspd'] );
      //$this->RekeningSPPSPM($param['id_skpd'], 'spp', $param['id'], $arrspd, $param['keperluan'], $beban, $param['beban'], $param['tanggal'], $param['id_kegiatan']);
		switch ($param['beban'])
		{
			case 'BTL' : $tp = 'RKA21'; break;
			case 'BL'  : $tp = 'RKA221'; break;
			default : $tp = '';
		}
		$keg = ($param['id_kegiatan'] === 0 ? 'NULL' : $param['id_kegiatan']);

		$this->db->select('distinct
			r.id_rekening,
			r.id_parent_rekening,
			r.kode_rekening,
			r.nama_rekening,
			r.level_rekening,
			r.saldo_normal,
			r.child_count
		');
		$this->db->from('rekening r');
		$this->db->join("rincian_anggaran rin", "rin.id_rekening = r.id_rekening");
		$this->db->join("v_form_anggaran_lkp f", "f.id_form_anggaran = rin.id_form_anggaran");
		$this->db->join("tahun_anggaran t", "t.tahun = f.tahun and f.status = t.status_kini");
		$this->db->where('f.id_skpd', $param['id_skpd'] );
		$this->db->where('f.tahun', $this->tahun);
		$this->db->where('f.tipe', $tp);
    }
    /*
      mode : spj
      keterangan : rekening yang bisa dibuatkan spj
      dipakai di SPJ
    */
    else if ($param['mode'] === 'spj'){
      $this->db->select('distinct
        r.id_rekening,
        r.id_parent_rekening,
        r.kode_rekening,
        r.nama_rekening,
        r.level_rekening,
        r.saldo_normal,
        r.child_count
      ');
      $this->db->from('rekening r');
      $this->db->join("rekening_sp2d_spj(".$this->tahun.", ".$param['id_skpd'].", ".$param['id_kegiatan'].", '".$param['keperluan']."', '".$param['beban']."') a", "a.id_rekening = r.id_rekening");
      //$this->db->join("sisa_spj_rekening (".$param['id_skpd'].", ".$this->tahun.", '".prepare_date($param['tanggal'])."', ".$param['id_kegiatan'].", r.id_rekening, '".$param['keperluan']."', '".$param['beban']."', ".$param['id_spj'].") s", "1 = 1");
      //$this->db->where("s.nominal > 0");
    }
    /*
      mode : sdskpd
      keterangan : rekening belanja langsung yang memiliki anggaran
      dipakai di ...
    */
    else if ($param['mode'] === 'sdskpd'){
      $this->db->select('
        r.id_rekening,
        r.id_parent_rekening,
        r.kode_rekening,
        r.nama_rekening,
        r.level_rekening,
        r.saldo_normal,
        r.child_count
      ');
      $this->db->from("rekening r");
      $this->db->join("sumber_dana_skpd sd", "sd.id_rekening = r.id_rekening");
    }
    else if ($param['mode'] === 'hutang'){
      $this->db->select('
        r.id_rekening,
        r.id_parent_rekening,
        r.kode_rekening,
        r.nama_rekening,
        r.level_rekening,
        r.saldo_normal,
        r.child_count
      ');
      $this->db->from("rekening r");
      $this->db->where("r.tipe = '2' ");
    }
    /*
      mode : piutang
      keterangan : rekening piutang
      dipakai di ...
    */
    else if ($param['mode'] === 'piutang'){
      $this->db->select('
        r.id_rekening,
        r.id_parent_rekening,
        r.kode_rekening,
        r.nama_rekening,
        r.level_rekening,
        r.saldo_normal,
        r.child_count
      ');
      $this->db->from("rekening r");
      $this->db->where("r.tipe = '1' ");
      $this->db->where("r.kelompok = '1' ");
      $this->db->where("r.jenis = '3' ");
    }
    /*
      mode : bkbt
      keterangan : rekening untuk bendahara pengeluaran/penerimaan
      dipakai di ...
    */
    else if ($param['mode'] === 'bkbt'){
      $this->db->select('
        r.id_rekening,
        r.id_parent_rekening,
        r.kode_rekening,
        r.nama_rekening,
        r.level_rekening,
        r.saldo_normal,
        r.child_count
      ');
      $this->db->from("rekening r");
      $this->db->join("v_tkjor_bk_bt v", "v.tipe = '' or v.tipe = r.tipe ".
        "and (v.kelompok = '' or v.kelompok = r.kelompok) ".
        "and (v.jenis = '' or v.jenis = r.jenis) ".
        "and (v.objek = '' or v.objek = r.objek) ".
        "and (v.rincian = '' or v.rincian = r.rincian)");
    }
    /*
      mode : kas
      keterangan : rekening untuk kas (1.1.1)
      dipakai di ...
    */
    else if ($param['mode'] === 'kas'){
      $this->db->select('
        r.id_rekening,
        r.id_parent_rekening,
        r.kode_rekening,
        r.nama_rekening,
        r.level_rekening,
        r.saldo_normal,
        r.child_count
      ');
      $this->db->from("rekening r");
      $this->db->join("v_tkjor_kas v", "v.tipe = '' or v.tipe = r.tipe ".
        "and (v.kelompok = '' or v.kelompok = r.kelompok) ".
        "and (v.jenis = '' or v.jenis = r.jenis) ".
        "and (v.objek = '' or v.objek = r.objek) ".
        "and (v.rincian = '' or v.rincian = r.rincian)");
    }
    /*
      mode : -
      keterangan : semua rekening
      dipakai di ...
    */
    else {
      $this->db->select('
        r.id_rekening,
        r.id_parent_rekening,
        r.kode_rekening,
        r.nama_rekening,
        r.level_rekening,
        r.saldo_normal,
        r.child_count
      ');
      $this->db->from("rekening r");
    }

    $result = $this->db->get()->result_array();
    return $result;
  }
  
	function getRekeningRKA($param)
	{
		$fieldmap = array(
		  'kdrek' => 'r.KODE_REKENING',
		  'nmrek' => 'r.NAMA_REKENING',
		);

		$wh = $this->checkSearch($param['q'], $fieldmap);
		if (!empty($wh))
		{
			$count = count($wh);
			$string = '(';
			$i = 1;
			foreach ($wh as $key=>$val) {
				$string .= $key ." '". $val ."'";
				if ($i < $count) $string .= ' OR ';
				$i++;
			}
			$string .= ')';
			$where = ' and '.$string;
		}
		else
		{
			$where = '';
		}

		/*mode : rka21
		  keterangan : rekening yang bertipe 5.1
		  dipakai di RKA 21
		*/
		if ($param['mode'] === 'rka21'){
			$result = $this->db->query("with recursive rek as (
				select
					r.id_rekening,
					r.id_parent_rekening,
					r.level_rekening,
					r.kode_rekening,
					r.nama_rekening,
					r.child_count
				from rekening r
				where r.kode_rekening starting with '5.1'
				and r.level_rekening >= 5
				".$where."

				union all

				select
					rr.id_rekening,
					rr.id_parent_rekening,
					rr.level_rekening,
					rr.kode_rekening,
					rr.nama_rekening,
					rr.child_count
				from rekening rr
				join rek on rr.id_rekening = rek.id_parent_rekening
		  )
		  select distinct *
		  from rek
		  order by rek.kode_rekening");
		}
		/*mode : rka221
		  keterangan : rekening yang bertipe 5.2
		  dipakai di RKA 221
		*/
		else if ($param['mode'] === 'rka221'){
			$result = $this->db->query("with recursive rek as (
				select
					r.id_rekening,
					r.id_parent_rekening,
					r.level_rekening,
					r.kode_rekening,
					r.nama_rekening,
					r.child_count
				from rekening r
				where r.kode_rekening starting with '5.2'
				and r.level_rekening >= 5
				".$where."

				union all

				select
					rr.id_rekening,
					rr.id_parent_rekening,
					rr.level_rekening,
					rr.kode_rekening,
					rr.nama_rekening,
					rr.child_count
				from rekening rr
				join rek on rr.id_rekening = rek.id_parent_rekening
		  )
		  select distinct *
		  from rek
		  order by rek.kode_rekening");
		}
		/*mode : -
		  keterangan : -
		*/
		else {
			$result = $this->db->query("with recursive rek as (
				select
					r.id_rekening,
					r.id_parent_rekening,
					r.level_rekening,
					r.kode_rekening,
					r.nama_rekening,
					r.child_count
				from rekening r
				where r.level_rekening >= 5
				".$where."

				union all

				select
					rr.id_rekening,
					rr.id_parent_rekening,
					rr.level_rekening,
					rr.kode_rekening,
					rr.nama_rekening,
					rr.child_count
				from rekening rr
				join rek on rr.id_rekening = rek.id_parent_rekening
			)
			select distinct *
			from rek
			order by rek.kode_rekening");
		}
		return $result->result_array();
	}
	
	function getRekeningSPJ($param)
	{
		$fieldmap = array(
		  'kdrek' => 'r.KODE_REKENING',
		  'nmrek' => 'r.NAMA_REKENING',
		);

		$wh = $this->checkSearch($param['q'], $fieldmap);
		if (!empty($wh))
		{
			$count = count($wh);
			$string = '(';
			$i = 1;
			foreach ($wh as $key=>$val) {
				$string .= $key ." '". $val ."'";
				if ($i < $count) $string .= ' OR ';
				$i++;
			}
			$string .= ')';
			$where = ' and '.$string;
		}
		else
		{
			$where = '';
		}
		
		if ($param['mode'] === 'spj'){
			$this->db->select("
				r.id_rekening, 
				rk.id_parent_rekening,
				rk.kode_rekening,
				rk.nama_rekening,
				rk.level_rekening,
				coalesce(sum(r.pagu),0) nominal_rka,
				(
					select 
						coalesce(sum(b.nominal),0) 
					from rincian_spp b
					join spp a on a.id_aktivitas=b.id_aktivitas
					where a.beban='BTL' and a.keperluan='LS' and b.id_rekening=r.id_rekening
				) nominal_spp,
				(
					select coalesce(sum(b.nominal),0) nominal
					from rincian_spj b
					join spj a on a.id_aktivitas=b.id_aktivitas
					where a.beban='BTL' and a.keperluan in ('UP','GU') and b.id_rekening=r.id_rekening
				) nominal_spj
			");
			$this->db->from('rincian_anggaran r');
			$this->db->join('form_anggaran fa','r.id_form_anggaran=fa.id_form_anggaran');
			$this->db->join('rekening rk','rk.id_rekening=r.id_rekening');
			$this->db->join("tahun_anggaran t", "t.tahun = fa.tahun and fa.status = t.status_kini");
			$this->db->where('fa.tipe','RKA21');
			$this->db->where('fa.tahun',$this->tahun);
			$this->db->group_by('1,2,3,4,5');
		}
		$result = $this->db->get()->result_array();
		return $result;
	}
	
	function getRekeningUraian($param)
	{
		$fieldmap = array(
		  'kode_rekening' => 'x.KODE_REKENING',
		  'nmrek' => 'x.URAIAN',
		);

		$wh = $this->checkSearch($param['q'], $fieldmap);
		if (!empty($wh))
		{
			$count = count($wh);
			$string = '(';
			$i = 1;
			foreach ($wh as $key=>$val) {
				$string .= $key ." '". $val ."'";
				if ($i < $count) $string .= ' OR ';
				$i++;
			}
			$string .= ')';
			$where = ' where '.$string;
		}
		else
		{
			$where = '';
		}
		
		if($param['mode']=='NPHD')
			$add = "and r.kode_rekening starts with '5.1.4'";
		else if($param['mode']=='BANSOS')
			$add = "and r.kode_rekening starts with '5.1.5'";
		else if($param['mode']=='BANKEU')
			$add = "and r.kode_rekening starts with '5.1.7'";
		else if($param['mode']=='BANSOSNON')
			$add = "and r.kode_rekening starts with '5.1.5.03'";
		else 
			$add= "";
		
		
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
					join tahun_anggaran ta on ta.tahun = fa.tahun and ta.status_kini=fa.status
					where fa.tahun=".$this->tahun." and fa.tipe='RKA21' and fa.id_skpd=".$param['id_skpd']." ".$add." and ra.pagu > 0
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
					join tahun_anggaran ta on ta.tahun = fa.tahun and ta.status_kini=fa.status
					where fa.tahun=".$this->tahun." and fa.tipe='RKA21' and fa.id_skpd=".$param['id_skpd']." ".$add." and ra.pagu > 0

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
					where rek.level_rekening <> 0 ".$add."
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
			".$where."
		");
		return $result->result_array();
	}

}