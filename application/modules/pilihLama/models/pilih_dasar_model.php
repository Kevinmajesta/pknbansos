<?php
class Pilih_dasar_model extends Pilih_Model {

  function __construct()
  {
    parent::__construct();
  }

  function getPejabatDaerahSelect($param)
  {
    $this->db->select('
      s.id_pejabat_daerah,
      s.nama_pejabat,
      s.kode_jabatan,
      s.jabatan
    ');
    $this->db->from('pejabat_daerah s');
    if ($param['q'] != '')
      $this->db->like('Upper(s.nama_pejabat)', strtoupper($param['q']));


    $result = $this->db->get()->result_array();
    return $result;
  }
  
  function getPejabatPengujiSelect($param)
  {
    $this->db->select('r.ID_PEJABAT_PENGUJI, r.NAMA_PEJABAT,r.NIP,s.NAMA_SKPD,s.ID_SKPD');
	$this->db->from('PEJABAT_PENGUJI r');
	$this->db->join('SKPD s','s.ID_SKPD=r.ID_SKPD');
	if($this->session->userdata('id_skpd') != '0' || $this->session->userdata('id_skpd') != '' ){
		$this->db->where('s.ID_SKPD',$this->session->userdata('id_skpd'));
	}
    if ($param['q'] != '')
      $this->db->like('Upper(r.nama_pejabat)', strtoupper($param['q']));


    $result = $this->db->get()->result_array();
    return $result;
  }

  function getPejabatSKPDSelect($param)
  {
    $this->db->select('
      s.id_skpd,
      s.id_pejabat_skpd,
      s.nama_pejabat,
      s.kode_jabatan,
      s.jabatan
    ');
    $this->db->from('pejabat_skpd s');
    if ($param['skpd'] != null)
      $this->db->where('s.id_skpd', $param['skpd']);

    if ($param['q'] != '')
      $this->db->like('Upper(s.nama_pejabat)', strtoupper($param['q']));

    if ($param['kode'] != '')
    {
      $this->db->limit(1);
      $this->db->like('Upper(s.kode_jabatan)', strtoupper($param['kode']));
    }

    $result = $this->db->get()->result_array();
    return $result;
  }

  function getPenyetorSelect($param)
  {
    $this->db->distinct('
      a.nama_penyetor,
      a.alamat_penyetor
    ');
    $this->db->from('tbp a');

    if ($param['q'] != '')
      $this->db->like('Upper(a.nama_penyetor)', strtoupper($param['q']));

    $result = $this->db->get()->result_array();
    return $result;
  }

  function getWPSelect($param)
  {
    $this->db->distinct('
      a.nama_wp,
      a.alamat_wp
    ');
    $this->db->from('skrd a');

    if ($param['q'] != '')
      $this->db->like('Upper(a.nama_wp)', strtoupper($param['q']));

    $result = $this->db->get()->result_array();
    return $result;
  }

  function getSKPD($param, $isCount=FALSE)
  {
    $fieldmap = array(
      'id' => 's.ID_SKPD',
      'kode' => 's.KODE_SKPD_LKP',
      'nama' => 's.NAMA_SKPD'
    );

    $wh = $this->checkSearch($param['q'], $fieldmap);
    if ($wh) $this->db->or_where($wh);

    ($param['sort_by'] != null && !$isCount) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    $this->db->select('
      s.id_skpd,
      s.kode_skpd_lkp,
      s.nama_skpd
    ');
    $this->db->from('v_skpd s');
    if ($param['mode'] != '') {
      $tipe = '';
      switch ($param['mode']) {
        case 'rka1' : $tipe = 'RKA1'; break;
        case 'rka21' : $tipe = 'RKA21'; break;
        case 'rka31' : $tipe = 'RKA31'; break;
        case 'rka32' : $tipe = 'RKA32'; break;
        case 'dpa1' : $tipe = 'DPA1'; break;
        case 'dpa21' : $tipe = 'DPA21'; break;
        case 'dpa31' : $tipe = 'DPA31'; break;
        case 'dpa32' : $tipe = 'DPA32'; break;
      }

      $this->db->where("not exists(
        select 1
        from v_form_anggaran_lkp fa
        where fa.tahun = ".$this->tahun."
          and fa.status = '".$this->status."'
          and fa.tipe = '".$tipe."'
          and fa.id_skpd = s.id_skpd
      )");
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

  function getRekeningPPTKDef($param)
  {
    $this->db->select('
      a.id_rekening,
      r.kode_rekening,
      r.nama_rekening,
      p.id_pejabat_skpd,
      p.nama_pejabat
    ');
    $this->db->from('rekening_pptk a');
    $this->db->join('rekening r', 'r.id_rekening = a.id_rekening');
    $this->db->join('pejabat_skpd p', 'p.id_pejabat_skpd = a.id_pptk', 'left');
    $this->db->where('a.id_kegiatan', $param['kegiatan']);

    $result = $this->db->get()->result_array();
    return $result;
  }

  function getSumberdanaDef($param)
  {
    $this->db->select('
      d.id_sumber_dana,
      d.id_rekening,
      d.nama_sumber_dana,
      r.kode_rekening,
      r.nama_rekening
    ');
    $this->db->from('sumber_dana d');
    $this->db->join('rekening r', 'r.id_rekening = d.id_rekening');
    $this->db->join('skpd s', 's.id_rekening_kasda = d.id_rekening');
    $this->db->where('s.id_skpd', $param['skpd']);

    $result = $this->db->get()->result_array();
    return $result;
  }

  function getSumberdanaSKPDDef($param)
  {
    $this->db->select('
      r.id_rekening,
      r.kode_rekening,
      r.nama_rekening
    ');
    $this->db->from('rekening r');
    $this->db->join('skpd s', 's.id_rekening_bk = r.id_rekening');
    $this->db->where('s.id_skpd', $param['skpd']);

    $result = $this->db->get()->result_array();
    return $result;
  }

  function getSumberdana($param)
  {
    $fieldmap = array(
      'id' => 's.ID_SUMBER_DANA',
      'nama' => 's.NAMA_SUMBER_DANA',
      'bank' => 's.NAMA_BANK',
      'norek' => 's.NO_REKENING_BANK',
      'kdrek' => 'r.KODE_REKENING',
      'nmrek' => 'r.NAMA_REKENING',
    );

    $wh = $this->checkSearch($param['q'], $fieldmap);
    if ($wh) $this->db->or_where($wh);

    ($param['sort_by'] != null) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    $this->db->select('
      s.id_sumber_dana,
      s.id_rekening,
      s.nama_sumber_dana,
      s.nama_bank,
      s.no_rekening_bank,
      r.kode_rekening,
      r.nama_rekening,
      s.id_rekening
    ');
    $this->db->from('sumber_dana s');
    $this->db->join('rekening r', 'r.id_rekening = s.id_rekening');

    $result = $this->db->get()->result_array();
    return $result;
  }

  function getSDSKPD($param)
  {
    $fieldmap = array(
      'id' => 's.ID_SUMBER_DANA_SKPD',
      'nama' => 's.NAMA_SUMBER_DANA',
      'bank' => 's.NAMA_BANK',
      'norek' => 's.NO_REKENING_BANK',
      'kdrek' => 'r.KODE_REKENING',
      'nmrek' => 'r.NAMA_REKENING',
    );

    $wh = $this->checkSearch($param['q'], $fieldmap);
    if ($wh) $this->db->or_where($wh);

    ($param['sort_by'] != null) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    $this->db->select('
      s.id_sumber_dana_skpd,
      s.id_skpd,
      s.id_rekening,
      s.nama_sumber_dana,
      s.nama_bank,
      s.no_rekening_bank,
      r.kode_rekening,
      r.nama_rekening
    ');
    $this->db->from('sumber_dana_skpd s');
    $this->db->join('rekening r', 'r.id_rekening = s.id_rekening');
    $this->db->where('s.id_skpd', $param['id_skpd']);

    $result = $this->db->get()->result_array();
    return $result;
  }

  function getPajak($param)
  {
    $fieldmap = array(
      'id' => 'p.ID_PAJAK',
      'kode' => 'p.KODE_PAJAK',
      'nama' => 'p.NAMA_PAJAK',
      'persen' => 'p.PERSEN',
      'kdrek' => 'r.KODE_REKENING',
      'nmrek' => 'r.NAMA_REKENING',
    );

    $wh = $this->checkSearch($param['q'], $fieldmap);
    if ($wh) $this->db->or_where($wh);

    ($param['sort_by'] != null) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    if ($param['mode'] === 'spjk')
      {
      $jurnal = "
          select sum(rj.kredit)
          from rincian_jurnal rj
          join v_jurnal j on j.id_jurnal = rj.id_jurnal
          where rj.id_rekening = r.id_rekening
            and j.tanggal_jurnal <= '".prepare_date($param['tanggal'])."'";
      $aktivitas = "
          select sum(rsp.nominal)
          from rincian_setoran_pajak_sp2d rsp
          join setoran_pajak_sp2d sp on rsp.id_aktivitas = sp.id_aktivitas
          join aktivitas a on a.id_aktivitas = sp.id_aktivitas
          where rsp.id_pajak = p.id_pajak
            and a.tanggal <= '".prepare_date($param['tanggal'])."'";
      $this->db->select('
          p.id_pajak,
          p.kode_pajak,
          p.nama_pajak,
          r.id_rekening,
          r.kode_rekening,
          r.nama_rekening,
          r.level_rekening,
          p.persen,
          p.is_ppn
      ');
      $this->db->from("pajak p");
      $this->db->join("rekening r", "r.id_rekening = p.id_rekening");
      $this->db->where("coalesce((".$jurnal."), 0) - coalesce((".$aktivitas."), 0) > 0");
    }
    else
    {
      $this->db->select('
          p.id_pajak,
          p.kode_pajak,
          p.nama_pajak,
          r.id_rekening,
          r.kode_rekening,
          r.nama_rekening,
          r.level_rekening,
          p.persen,
          p.is_ppn
      ');
      $this->db->from("pajak p");
      $this->db->join("rekening r", "r.id_rekening = p.id_rekening");
    }

    $result = $this->db->get()->result_array();
    return $result;
  }

  function getPotongan($param)
  {
    $fieldmap = array(
      'id' => 'r.ID_REKENING',
      'kode' => 'r.KODE_REKENING',
      'nama' => 'r.NAMA_REKENING',
    );

    $wh = $this->checkSearch($param['q'], $fieldmap);
    if ($wh) $this->db->or_where($wh);

    ($param['sort_by'] != null) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    $this->db->select('
        r.id_rekening,
        r.kode_rekening,
        r.nama_rekening
    ');
    $this->db->from("rekening_pfk p");
    $this->db->join("rekening r", "r.id_rekening = p.id_rekening");
    $result = $this->db->get()->result_array();
    return $result;
  }

  function getKontrak($param)
  {
    $fieldmap = array(
      'no' => 'k.NO_KONTRAK',
      'perusahaan' => 'k.NAMA_PERUSAHAAN',
      'pimpinan' => 'k.NAMA_PIMPINAN',
      'bank' => 'k.NAMA_BANK',
      'norek' => 'k.NO_REKENING_BANK',
      'alamat' => 'k.ALAMAT_PERUSAHAAN',
    );

    $wh = $this->checkSearch($param['q'], $fieldmap);
    if ($wh) $this->db->or_where($wh);

    ($param['sort_by'] != null) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    $this->db->select('
      k.no_kontrak,
      k.nama_perusahaan,
      k.nama_pimpinan,
      k.nama_bank,
      k.no_rekening_bank,
      k.alamat_perusahaan,
      k.npwp
    ');
    $this->db->from('kontrak k');

    $result = $this->db->get()->result_array();
    return $result;
  }

  function getPejabatdaerah($param)
  {
    $fieldmap = array(
      'id' => 'a.ID_PEJABAT_DAERAH',
      'nama' => 'a.NAMA_PEJABAT',
      'jabat' => 'a.JABATAN',
      'nip' => 'a.NIP',
    );

    $wh = $this->checkSearch($param['q'], $fieldmap);
    if ($wh) $this->db->or_where($wh);

    ($param['sort_by'] != null) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    $this->db->select('
      a.id_pejabat_daerah,
      a.nama_pejabat,
      a.jabatan,
      a.nip,
    ');
    $this->db->from('pejabat_daerah a');
    $this->db->where('a.AKTIF','1');

    $result = $this->db->get()->result_array();
    return $result;
  }

  function getPejabatskpd($param)
  {
    $fieldmap = array(
      'id' => 'a.ID_PEJABAT_SKPD',
      'nama' => 'a.NAMA_PEJABAT',
      'jabat' => 'a.JABATAN',
      'nip' => 'a.NIP',
    );

    $wh = $this->checkSearch($param['q'], $fieldmap);
    if ($wh) $this->db->or_where($wh);

    ($param['sort_by'] != null) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    $this->db->select('
      a.id_pejabat_skpd,
      a.nama_pejabat,
      a.jabatan,
      a.nip,
    ');
    $this->db->from('pejabat_skpd a');
    if ($param['id_skpd'] != '' )
      $this->db->where('a.id_skpd', $param['id_skpd']);

    $result = $this->db->get()->result_array();
    return $result;
  }
  
  function getPejabatpenguji($param)
  {
    $fieldmap = array(
      'id' => 'r.ID_PEJABAT_PENGUJI',
      'nama' => 'r.NAMA_PEJABAT',
      'nip' => 'r.NIP',
      'skpd' => 's.NAMA_SKPD',
    );

    $wh = $this->checkSearch($param['q'], $fieldmap);
    if ($wh) $this->db->or_where($wh);

    ($param['sort_by'] != null) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';
	
	$this->db->select("r.ID_PEJABAT_PENGUJI, r.NAMA_PEJABAT,r.NIP");
	$this->db->from('PEJABAT_PENGUJI r');
	$this->db->join('SKPD s','s.ID_SKPD=r.ID_SKPD');
	$this->db->where('r.TAHUN',$this->session->userdata('tahun'));	
    /* $this->db->select('
      a.id_pejabat_skpd,
      a.nama_pejabat,
      a.jabatan,
      a.nip,
    ');
    $this->db->from('pejabat_skpd a'); */
    if ($param['id_skpd'] != '' )
      $this->db->where('s.ID_SKPD', $param['id_skpd']);

    $result = $this->db->get()->result_array();
    return $result;
  }
  
	function getSPJSPP($param)
	{
		$fieldmap = array(
			'nomor' => 's.NOMOR',
		);

		$wh = $this->checkSearch($param['q'], $fieldmap);
		if ($wh) $this->db->or_where($wh);

		($param['sort_by'] != null) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

		$this->db->select("
			s.id_aktivitas,
			s.nomor,
			s.nominal,
			(
				select
					coalesce(sum(x.nominal_kotor),0)
				from spp x
				join aktivitas z on z.id_aktivitas=x.id_aktivitas
				where z.tahun=s.tahun and x.keperluan in ('GU','GU NIHIL') and x.id_aktivitas_spj = s.id_aktivitas and z.id_skpd=s.id_skpd
			) total_spp
		");
		$this->db->from('v_spj s');
		$this->db->join('v_skpd sk', 'sk.id_skpd = s.id_skpd');
		$this->db->where('s.tahun',$this->tahun);
		$this->db->where('s.id_skpd',$param['id_skpd']);

		$result = $this->db->get()->result_array();
		return $result;
	}

}