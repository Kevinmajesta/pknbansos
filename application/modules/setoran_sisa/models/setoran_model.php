<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setoran_model extends Aktivitas_Model {
	var $fields;
	var $fieldmap;
	var $fieldmap_ssu;
	var $fieldmap_rincian;
	var $fieldmap_sumberdana;
	var $data_ssu;
	var $data_rincian;
	var $data_sumberdana;
	var $purge_rincian;
	var $purge_sumberdana;

	function __construct()
	{
		parent::__construct();

		$this->tipe = 'SSU';
		$this->tipe_jurnal = 'SETORAN SISA UP';

		$this->fields = array(
			'ID_AKTIVITAS',
			'NOMOR',
			'TANGGAL',
			'KODE_SKPD_LKP',
			'NAMA_SKPD',
			'NOMINAL',
			'DESKRIPSI',
		);

		//$this->fieldmap_daftar_aggregate = array('nom' => "sum(b.nominal)",);

		$this->fieldmap = array(
			'no' => 'a.NOMOR',
			'tgl' => 'a.TANGGAL',
			'ket' => 'cast(substring(a.deskripsi from 1 for 5000) as varchar(5000))',
			'kdskpd' => 's.KODE_SKPD_LKP',
			'nmskpd' => 's.NAMA_SKPD',
			'nom' => 'b.NOMINAL',
		);

		$this->fieldmap_ssu = array(
			'id' => 'ID_AKTIVITAS',
			'id_sd' => 'ID_SUMBER_DANA',
			'pekas' => 'ID_REKENING_PEKAS',
			'total' => 'NOMINAL',
		);
	}

	function get_grid_model()
	{
		$grid = array(
			'colNames' => array('Nomor', 'Tanggal', 'Kode SKPD', 'Nama SKPD', 'Nominal', 'Keterangan'),
			'colModel' => array(
				array('name' => 'no', 'width' => 130, 'sortable' => true),
				array('name' => 'tgl', 'width' => 80, 'sortable' => true, 'formatter' => 'date', 'align' => 'center'),
				array('name' => 'kdskpd', 'width' => 80, 'sortable' => true),
				array('name' => 'nmskpd','width' => 250, 'sortable' => true),
				array('name' => 'nom','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
				array('name' => 'ket','width' => 300, 'sortable' => true),
			),
		);
		return $grid;
	}

	// ----- search advance ---- >>
	function get_data_fields()
	{
		$fields = array(
			'no' => array('name' => 'Nomor', 'kategori'=>'string'),
			'tgl' => array('name' => 'Tanggal', 'kategori'=>'date'),
			'kdskpd' => array('name' => 'Kode SKPD', 'kategori'=>'string'),
			'nmskpd' => array('name' => 'Nama SKPD', 'kategori'=>'string'),
			'nom' => array('name' => 'Nominal', 'kategori'=>'numeric'),
			'ket' => array('name' => 'Keterangan', 'kategori'=>'string'),
		);

		return $fields;
	}

	function fill_data()
	{
		parent::fill_data();
		$keperluan = $this->input->post('keperluan') ? $this->input->post('keperluan') : '';
		$beban = $this->input->post('beban') ? $this->input->post('beban') : '';
		$spptu = $this->session->userdata('konf_spp_tu');

		/* ambil data Setoran Sisa */
		foreach($this->fieldmap_ssu as $key => $value){
			$$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			switch($key)
			{
				case 'total'   : $$key = $this->input->post($key) ? prepare_numeric($this->input->post($key)) : '0'; break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			if(isset($$key))
			$this->data_ssu[$value] = $$key;
		}    
	}

	/* Simpan data Setoran Sisa */
	function insert_ssu()
	{
		$this->db->select('1')->from('SETORAN_SISA')->where('ID_AKTIVITAS', $this->id);
		$rs = $this->db->get()->row_array();

		if ($rs)
		{
			$this->db->where('ID_AKTIVITAS', $this->id);
			$this->db->update('SETORAN_SISA', $this->data_ssu);
			//$this->check_trans_status('update setoran_sisa failed');
		}
		else
		{
			$this->data_ssu['ID_AKTIVITAS'] = $this->id;
			$this->db->insert('SETORAN_SISA', $this->data_ssu);
			//$this->check_trans_status('insert setoran_sisa failed');
		}
	}

	function get_data_bku()
	{
		$this->db->select('rb.ID_AKTIVITAS, a.NO_BKU')->from('SETORAN_SISA a')->join('RINCIAN_BKU rb', 'rb.ID_AKTIVITAS_SETORAN_SISA = a.ID_AKTIVITAS')->where('a.ID_AKTIVITAS', $this->id);
		$result = $this->db->get()->row_array();

		$this->id_bku = isset($result['ID_AKTIVITAS']) ? $result['ID_AKTIVITAS'] : '0';
		$this->no_bku = isset($result['NO_BKU']) ? $result['NO_BKU'] : '';
	}

	function save_detail()
	{
		$this->insert_ssu();
	}

	/* Query daftar */
	function build_query_daftar()
	{
		$this->db->select('
			a.id_aktivitas,
			a.tipe,
			a.nomor,
			a.tanggal,
			cast(substring(a.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
			a.tahun,
			a.id_skpd,
			s.kode_skpd,
			s.kode_skpd_lkp,
			s.nama_skpd,
			b.nominal
		');
		$this->db->from('aktivitas a');
		$this->db->join('setoran_sisa b','b.id_aktivitas = a.id_aktivitas');
		$this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
		$this->db->where('a.tahun', $this->tahun);
		/* filter skpd jika diset */
		if ($this->session->userdata('id_skpd') !== 0)
		{
			$this->db->where('a.id_skpd', $this->session->userdata('id_skpd'));
			//$this->db->where('a.is_ppkd', $this->session->userdata('ppkd'));
		}
	}

	/* Query form */
	function build_query_form($id=0)
	{
		$this->db->select('
			a.id_aktivitas,
			a.tanggal,
			a.nomor,
			a.nomor_int,
			cast(substring(a.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
			a.id_skpd,
			s.kode_skpd_lkp,
			s.nama_skpd,
			b.nominal,
			b.id_rekening_pekas,
			rp.kode_rekening as kode_rekening_pekas,
			rp.nama_rekening as nama_rekening_pekas
		');
		$this->db->from('aktivitas a');
		$this->db->join('setoran_sisa b', 'b.id_aktivitas = a.id_aktivitas');
		$this->db->join('v_skpd s', 's.id_skpd = a.id_skpd');
		$this->db->join('rekening rp', 'rp.id_rekening = b.id_rekening_pekas');
		$this->db->where('a.id_aktivitas', $id);
	}

	function build_query_hapus($id=0)
	{
		$this->db->where('id_aktivitas', $id)->delete('setoran_sisa');
		//$this->check_trans_status('delete setoran_sisa failed');
		$this->db->where('id_aktivitas', $id)->delete('aktivitas');
		//$this->check_trans_status('delete aktivitas failed');
	}

  /* Query rincian Setoran Sisa */
  function get_rinci_by_id($id)
  {
    $this->db->select('
      b.id_rincian_setoran_sisa,
      b.id_kegiatan,
      b.id_rekening,
      k.kode_kegiatan_skpd,
      k.nama_kegiatan,
      r.kode_rekening,
      r.nama_rekening,
      b.nominal,
      r.id_sumber_dana
    ');
    $this->db->from('aktivitas a');
    $this->db->join('rincian_setoran_sisa b', 'b.id_aktivitas = a.id_aktivitas');
    $this->db->join('rekening r', 'r.id_rekening = b.id_rekening', 'left');
    $this->db->join('v_kegiatan_skpd k', 'k.id_kegiatan = b.id_kegiatan and k.id_skpd = a.id_skpd', 'left');
    $this->db->where('b.id_aktivitas', $id);
    $result = $this->db->get()->result_array();

    return $result;
  }

  /* Query rincian Sumber Dana */
  function get_sumberdana_by_id($id=0)
  {
    $this->db->select('
      a.id_sumber_dana,
      b.nama_sumber_dana,
      r.kode_rekening,
      r.nama_rekening,
      a.nominal
    ');
    $this->db->from('sumber_dana_aktivitas a');
    $this->db->join('sumber_dana b', 'b.id_sumber_dana = a.id_sumber_dana');
    $this->db->join('rekening r', 'r.id_rekening = b.id_rekening');
    $this->db->where('a.id_aktivitas', $id);
    $result = $this->db->get()->result_array();

    return $result;
  }

  function get_sisa_kas_bank($param)
  {
    $this->db->select("a.nominal, a.nominal_tanggal");
    $this->db->from("sisa_kas_bank("
            .$param['id_skpd'].", "
            .$this->tahun.", "
            ."'".$param['tanggal']."', "
            ."'".$param['id_kegiatan']."', "
            ."'".$param['keperluan']."', "
            ."'".$param['beban']."', "
            ."0, 0, ".$param['id'].") a
    ");
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_sisa_kas_tunai($param)
  {
    $this->db->select("a.nominal, a.nominal_tanggal");
    $this->db->from("sisa_kas_tunai("
            .$param['id_skpd'].", "
            .$this->tahun.", "
            ."'".$param['tanggal']."', "
            ."'".$param['id_kegiatan']."', "
            ."'".$param['keperluan']."', "
            ."'".$param['beban']."', "
            .$param['id_rekening_pekas'].", "
            ."0, 0, 0, ".$param['id'].") a
    ");
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_sisa_rekening($param)
  {
    $this->db->select('nominal');
    $this->db->from("
      sisa_ssu_rekening("
          .$param['id_skpd'].", "
          .$this->tahun.", "
          .$param['id_kegiatan'].", "
          .$param['id_rekening'].", "
          ."'".$param['beban']."', "
          ."'".$param['keperluan']."', "
          .$param['id_aktivitas']."
      )
    ");
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_sisa_kegiatan($param)
  {
    $this->db->select('nominal');
    $this->db->from("
      sisa_ssu_kegiatan("
          .$param['id_skpd'].", "
          .$this->tahun.", "
          .$param['id_kegiatan'].", "
          ."'BL', "
          ."'".$param['keperluan']."', "
          .$param['id_aktivitas']."
      )
    ");
    $result = $this->db->get()->row_array();

    return $result;
  }

	function check_dependency($id)
	{return TRUE;
		/* // cek apakah setelah dihapus, sisa kas tunai menjadi minus
		$this->db->select("a.id_aktivitas, a.tanggal, a.id_skpd, b.id_rekening_pekas, b.keperluan, b.beban, cast(substring(coalesce(list(distinct rs.id_kegiatan), 0) from 1 for 5000) as varchar(5000)) id_kegiatan");
		$this->db->from("aktivitas a");
		$this->db->join("setoran_sisa b", "b.id_aktivitas = a.id_aktivitas");
		$this->db->join("rincian_setoran_sisa rs", "rs.id_aktivitas = a.id_aktivitas", "left");
		$this->db->where("a.id_aktivitas", $id);
		$this->db->group_by("a.id_aktivitas, a.tanggal, a.id_skpd, b.id_rekening_pekas, b.keperluan, b.beban");
		$data = $this->db->get()->row_array();

		$this->db->select("a.nominal, a.nominal_tanggal");
		$this->db->from("sisa_kas_tunai("
			.$data['ID_SKPD'].", "
			.$this->tahun.", "
			."'".$data['TANGGAL']."', "
			."'".$data['ID_KEGIATAN']."', "
			."'".$data['KEPERLUAN']."', "
			."'".$data['BEBAN']."', "
			.$data['ID_REKENING_PEKAS'].", "
			."0, 0, 0 , ".$data['ID_AKTIVITAS'].") a
		");
		$sisa_kas = $this->db->get()->row_array();

		// cek apakah sudah masuk BUD
		$this->db->select('count(a.id_aktivitas) bku_pakai')
		->from('rincian_bku a')
		->where('a.id_aktivitas_setoran_sisa', $id);
		$result = $this->db->get()->row_array();

		if ($result && ($result['BKU_PAKAI'] > 0 || $sisa_kas['NOMINAL'] < 0 || $sisa_kas['NOMINAL_TANGGAL'] < 0))
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		} */
	}

	// return TRUE jika sudah masuk BUD
	function check_bku($id)
	{
		$this->db->select('count(1) bku');
		$this->db->from('rincian_bku rb')->where('rb.id_aktivitas_setoran_sisa', $id);
		$result = $this->db->get()->row_array();

		return $result['BKU'] > 0;
	}

	function susun_jurnal($id)
	{
		try {
			$this->db->query('execute procedure rebuild_jurnal_ssu_id('.$id.');');
			$this->check_trans_status('susun jurnal setoran sisa id:'.$id.' failed');
		}
		catch(Exception $e){
		//TODO : log error to file
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->last_error_id = $this->db->_error_number();
			$this->last_error_message = $this->db->_error_message();
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
	}

	function get_sisa_skpd($param,$ada=false)
	{
		//jumlah sp2d
		$this->db->select("coalesce(sum(c.nominal_kotor),0) nominal");
		$this->db->from("sp2d a");
		$this->db->join('spm b', 'b.id_aktivitas=a.id_aktivitas_spm');
		$this->db->join('spp c', 'c.id_aktivitas=b.id_spp');
		$this->db->join('aktivitas d', 'a.id_aktivitas=d.id_aktivitas');
		if($ada){
			if(isset($param['tanggal']))
			$this->db->where('d.tanggal <=', prepare_date($param['tanggal']));
		}
		$this->db->where("c.keperluan in ('UP','GU')");
		$this->db->where('d.id_skpd', $param['id_skpd']);
		$result = $this->db->get()->row_array();
		
		//jumlah spj
		$this->db->select('coalesce(sum(b.nominal),0) nominal');
		$this->db->from('aktivitas a');
		$this->db->join('spj b', 'a.id_aktivitas = b.id_aktivitas');  
		$this->db->where('a.tahun', $this->tahun);
		$this->db->where('a.id_skpd', $param['id_skpd']);		
		if($ada){
			if(isset($param['tanggal']))
			$this->db->where('a.tanggal <=', prepare_date($param['tanggal']));
		}
		$spj = $this->db->get()->row_array();
		
		//jumlah ssu
		$this->db->select('coalesce(sum(b.nominal),0) nominal');
		$this->db->from('aktivitas a');
		$this->db->join('setoran_sisa b', 'a.id_aktivitas = b.id_aktivitas');  
		$this->db->where('a.tahun', $this->tahun);
		$this->db->where('a.id_skpd', $param['id_skpd']);
		if($param['id_aktivitas'])
			$this->db->where('a.id_aktivitas <>', $param['id_aktivitas']);
		if($ada){
			if(isset($param['tanggal']))
			$this->db->where('a.tanggal <=', prepare_date($param['tanggal']));
		}
		$ssu = $this->db->get()->row_array();

		return ($result['NOMINAL']-$spj['NOMINAL']-$ssu['NOMINAL']);
	}	
}