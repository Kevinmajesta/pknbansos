<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spj_model extends Aktivitas_Model {

  var $fields;
  var $fieldmap;
  var $fieldmap_spj;
  var $fieldmap_rincian;
  var $fieldmap_doc;
  var $data_spj;
  var $data_rincian;
  var $data_doc;
  var $purge_rek;
  var $purge_pjk;
  var $purge_file;
  var $path;

  function __construct()
  {
    parent::__construct();

    $this->tipe = 'SPJ';
    $this->path = './uploads/';
    
    $this->fields = array(
      'ID_AKTIVITAS',
      'NOMOR',
      'TANGGAL',
      'KODE_SKPD_LKP',
      'NAMA_SKPD',
      'DESKRIPSI',
      'NOMINAL',

    );

    $this->fieldmap = array(
      'id' => 'a.ID_AKTIVITAS',
      'no' => 'a.NOMOR',
      'tgl' => 'a.TANGGAL',
      'kdskpd' => 's.KODE_SKPD_LKP',
      'nmskpd' => 's.NAMA_SKPD',
      'ket' => 'cast(substring(a.deskripsi from 1 for 5000) as varchar(5000))',
      'nom' => 'a.NOMINAL',
    );

    $this->fieldmap_spj = array(
      'id' => 'ID_AKTIVITAS',
      'beban' => 'BEBAN',
      'keperluan' => 'KEPERLUAN',
      'pekas' => 'ID_REKENING_PEKAS',
      'bk' => 'ID_BK',
      'pa' => 'ID_PA',
    );

    $this->fieldmap_rincian = array(
      'idr' => 'ID_RINCIAN_SPJ',
      'id' => 'ID_AKTIVITAS',
      'idkeg' => 'ID_KEGIATAN',
      'idrek' => 'ID_REKENING',
      'nom' => 'NOMINAL',
    );
    
    $this->fieldmap_doc = array(
      'id_doc' => 'ID_DOKUMEN',
      'id_proposal' => 'ID_PROPOSAL',
      'kategori' => 'KATEGORI',
      'nama_doc' => 'NAMA_DOKUMEN',
      'nama_file' => 'NAMA_FILE',
      'mime' => 'MIME',
      'ukuran' => 'UKURAN',
      'tgl_upload' => 'TANGGAL_UPLOAD',
      'sub_kategori' => 'SUB_KATEGORI',
      'id' => 'ID_AKTIVITAS'
    );
		
		$this->fieldmap_daftar_aggregate = array(
			'nom' => "sum(NOMINAL)",
		);
  }

  function get_grid_model()
  {
    $grid = array(
        'colNames' => array('Nomor', 'Tanggal', 'Kode SKPD', 'Nama SKPD', 'Keterangan', 'Nominal'),
        'colModel' => array(
          array('name' => 'no', 'width' => 130, 'sortable' => true),
          array('name' => 'tgl', 'width' => 80, 'sortable' => true, 'formatter' => 'date', 'align' => 'center'),
          array('name' => 'kdskpd', 'width' => 80, 'sortable' => true),
          array('name' => 'nmskpd','width' => 235, 'sortable' => true),
          array('name' => 'ket','width' => 200, 'sortable' => true),
          array('name' => 'nom','width' => 150, 'sortable' => true, 'formatter' => 'currency', 'align' => 'right'),
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
              'ket' => array('name' => 'Keterangan', 'kategori'=>'string'),
              'nom' => array('name' => 'Nominal', 'kategori'=>'numeric'),
             );

    return $fields;
  }

  function fill_data()
  {
    parent::fill_data();
    
        
    /* ambil data SPJ */
    foreach($this->fieldmap_spj as $key => $value){
      $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      switch($key)
      {
        case 'nominal'   : $$key = $this->input->post($key) ? prepare_numeric($this->input->post($key)) : '0'; break;
        default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }
      if(isset($$key))
        $this->data_spj[$value] = $$key;
    }

    /* ambil data rincian Rekening SPJ */
    $this->purge_rek = $this->input->post('purge_rek'); $this->purge_rek = $this->purge_rek ? $this->purge_rek : NULL;
    $rinci = $this->input->post('rinci') ? $this->input->post('rinci') : NULL;
    if ($rinci)
    {
      $rinci = json_decode($rinci);
      for ($i=0; $i <= count($rinci) - 1; $i++) {
        foreach($this->fieldmap_rincian as $key => $value){
          $$key = isset($rinci[$i]->$key) && $rinci[$i]->$key ? $rinci[$i]->$key : NULL;
          if(isset($$key))
            $this->data_rincian[$i][$value] = $$key;
        }
      }
    }
    
    
        /* ambil grid fileupload */
    $this->purge_file = $this->input->post('purge_file'); $this->purge_file = $this->purge_file ? $this->purge_file : NULL;
    $file = $this->input->post('file') ? $this->input->post('file') : NULL;
    if ($file)
    {
      $file = json_decode($file);
      for ($i=0; $i <= count($file) - 1; $i++) {
        foreach($this->fieldmap_doc as $key => $value){
          switch ($key)
          {
            case 'tgl_upload' : $$key = isset($file[$i]->$key) ? prepare_date($file[$i]->$key) : NULL; break;
            default : $$key = isset($file[$i]->$key) && $file[$i]->$key ? $file[$i]->$key : NULL;
          }
          if(isset($$key))
            $this->data_doc[$i][$value] = $$key;
        }
      }
    }
    
  }

  /* Simpan data SPJ */
  function insert_spj()
  {
    $this->db->select('1')->from('SPJ')->where('ID_AKTIVITAS', $this->id);
    $rs = $this->db->get()->row_array();

    if ($rs)
    {
      $this->db->where('ID_AKTIVITAS', $this->id);
      $this->db->update('SPJ', $this->data_spj);
    }
    else
    {
      $this->data_spj['ID_AKTIVITAS'] = $this->id;
      $this->db->insert('SPJ', $this->data_spj);
    }
  }

  /* Simpan rincian Rekening SPJ */
  function insert_rincian()
  {
    if($this->purge_rek)
    {
      $this->db->where_in('ID_RINCIAN_SPJ', $this->purge_rek);
      $this->db->delete('RINCIAN_SPJ');
    }

    $jml = count($this->data_rincian);
    for ($i=0; $i <= $jml - 1; $i++)
    {
      $idr = $this->data_rincian[$i]['ID_RINCIAN_SPJ'];
      $this->db->select('1')->from('RINCIAN_SPJ')->where('ID_RINCIAN_SPJ', $idr);
      $rs = $this->db->get()->row_array();

      if ($rs)
      {
        $this->db->where('ID_RINCIAN_SPJ', $idr);
        $this->db->update('RINCIAN_SPJ', $this->data_rincian[$i]);
      }
      else
      {
        unset($this->data_rincian[$i]['ID_RINCIAN_SPJ']);
        $this->data_rincian[$i]['ID_AKTIVITAS'] = $this->id;
        $this->db->insert('RINCIAN_SPJ', $this->data_rincian[$i]);
      }
    }
  }

  function save_detail()
  {
    $this->insert_spj();
    $this->insert_rincian();
    $this->insert_dokumen();
  }

  /* Query daftar */
  function build_query_daftar()
  {
    $this->db->select("
        a.id_aktivitas,
        a.nomor,
        a.tanggal,
        cast(substring(a.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
        a.tahun,
        a.nominal,
        a.nama_kegiatan,
        a.id_skpd,
        s.kode_skpd_lkp,
        s.nama_skpd,
        a.username,
        a.beban,
        a.keperluan
    ");
    $this->db->from('v_spj_kegiatan a');
    $this->db->join('v_skpd s', 's.ID_SKPD = a.ID_SKPD');
    $this->db->where('a.tahun', $this->tahun);
    /* filter skpd jika diset */
    if ($this->session->userdata('id_skpd') != 0) {
      $this->db->where('a.id_skpd', $this->session->userdata('id_skpd'));
    }
  }

  /* Query form */
  function build_query_form($id=0)
  {
    $this->db->select('
        a.id_aktivitas,
        a.nomor,
        a.tanggal,
        cast(substring(a.deskripsi from 1 for 5000) as varchar(5000)) deskripsi,
        a.tahun,
        b.nominal,
        b.tanggal_pengesahan,
        b.id_rekening_pekas,
        r.kode_rekening kode_rekening_pekas,
        r.nama_rekening nama_rekening_pekas,
        a.id_skpd,
        s.kode_skpd_lkp,
        s.nama_skpd,
        a.username,
        b.beban,
        b.keperluan,
        b.id_bk,
        bk.nama_pejabat bk_nama,
        b.id_pa,
        pa.nama_pejabat pa_nama,
        b.id_ppk_skpd,
        ppk.nama_pejabat ppk_nama
    ');
    $this->db->from('aktivitas a');
    $this->db->join('spj b', 'b.id_aktivitas = a.id_aktivitas');
    $this->db->join('v_skpd s', 'a.id_skpd = s.id_skpd');
    $this->db->join('rekening r', 'r.id_rekening = b.id_rekening_pekas');
    $this->db->join('pejabat_skpd bk', 'bk.id_pejabat_skpd = b.id_bk', 'left');
    $this->db->join('pejabat_skpd pa', 'pa.id_pejabat_skpd = b.id_pa', 'left');
    $this->db->join('pejabat_skpd ppk', 'ppk.id_pejabat_skpd = b.id_ppk_skpd', 'left');
    $this->db->where('a.id_aktivitas', $id);
  }

  function build_query_hapus($id=0)
  {
    $this->db->where('id_aktivitas', $id)->delete('rincian_spj');
    $this->db->where('id_aktivitas', $id)->delete('spj');
    $this->db->where('id_aktivitas', $id)->delete('aktivitas');
  }

  /* Query rincian Kegiatan */
  function get_rinci_kegiatan_by_id($id)
  {
    $this->db->select('
        k.id_kegiatan,
        k.kode_kegiatan_skpd,
        k.nama_kegiatan,
        sum(b.nominal) nominal
    ');
    $this->db->from('aktivitas a');
    $this->db->join('rincian_spj b', 'b.id_aktivitas = a.id_aktivitas');
    $this->db->join('v_kegiatan_skpd k', 'k.id_kegiatan = b.id_kegiatan and k.id_skpd = a.id_skpd');
    $this->db->where('b.id_aktivitas', $id);
    $this->db->group_by('k.id_kegiatan, k.kode_kegiatan_skpd, k.nama_kegiatan');
    $result = $this->db->get()->result_array();

    return $result;
  }

  /* Query rincian Rekening */
  function get_rinci_rekening_by_id($id)
  {
    $this->db->select('
        b.id_rincian_spj,
        b.id_rekening,
        b.id_kegiatan,
        k.kode_kegiatan_skpd,
        k.nama_kegiatan,
        r.kode_rekening,
        r.nama_rekening,
        b.nominal
    ');
    $this->db->from('aktivitas a ');
    $this->db->join('rincian_spj b', 'b.id_aktivitas = a.id_aktivitas');
    $this->db->join('rekening r', 'r.id_rekening = b.id_rekening');
    $this->db->join('v_kegiatan_skpd k', 'k.id_kegiatan = b.id_kegiatan  and k.id_skpd = a.id_skpd','left');
    $this->db->where('a.id_aktivitas', $id);
    $result = $this->db->get()->result_array();

    return $result;
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
		if($param['id_aktivitas'])
			$this->db->where('a.id_aktivitas <>', $param['id_aktivitas']);
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
		if($ada){
			if(isset($param['tanggal']))
			$this->db->where('a.tanggal <=', prepare_date($param['tanggal']));
		}
		$ssu = $this->db->get()->row_array();

		return ($result['NOMINAL']-$spj['NOMINAL']-$ssu['NOMINAL']);
	}

  function get_sisa_kegiatan($param)
  {
    $this->db->select('a.nominal, a.nominal_all');
    $this->db->from("
          sisa_spj_kegiatan("
            .$param['id_skpd'].", "
            .$this->tahun.", "
            ."'".prepare_date($param['tanggal'])."', "
            .$param['id_kegiatan'].", "
            ."'".$param['keperluan']."', "
            ."'".$param['beban']."', "
            .$param['id_aktivitas']."
          ) a
    ");
    $result = $this->db->get()->row_array();

    return $result;
  }

	function get_sisa_rekening($param,$tipe)
	{
		if($tipe=='sekarang') $tamb = " and ak.tanggal <='".prepare_date($param['tanggal'])."'";
		else $tamb='';
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
				join aktivitas ak on ak.id_aktivitas=a.id_aktivitas
				where a.beban='BTL' and a.keperluan='LS' and b.id_rekening=r.id_rekening and ak.id_skpd=".$param['id_skpd']." ".$tamb."
			) nominal_spp,
			(
				select coalesce(sum(b.nominal),0) nominal
				from rincian_spj b
				join spj a on a.id_aktivitas=b.id_aktivitas
				join aktivitas ak on ak.id_aktivitas=a.id_aktivitas
				where b.id_rekening=r.id_rekening and ak.id_skpd=".$param['id_skpd']." ".$tamb." and a.id_aktivitas <> ".$param['id_aktivitas']."
			) nominal_spj
		");
		$this->db->from('rincian_anggaran r');
		$this->db->join('form_anggaran fa','r.id_form_anggaran=fa.id_form_anggaran');
		$this->db->join('rekening rk','rk.id_rekening=r.id_rekening');
		$this->db->join("tahun_anggaran t", "t.tahun = fa.tahun and fa.status = t.status_kini");
		$this->db->where('fa.tipe','RKA21');
		$this->db->where('fa.tahun',$this->tahun);
		$this->db->where('r.id_rekening',$param['id_rekening']);
		$this->db->where('fa.id_skpd',$param['id_skpd']);
		$this->db->group_by('1,2,3,4,5');
		$result = $this->db->get()->row_array();
		return $result;
	}
  
  function unlink_fileupload($filename)
  {
    $path = $this->path;
    $file = $path . $filename;
    array_map( 'unlink', glob($file) );    
  }
  
    function get_scan_file($id)
  {
    $this->db->select('a.id_dokumen, a.nama_dokumen, a.nama_file, a.mime, a.ukuran, a.tanggal_upload')
        ->from('dokumen a')
        ->where('a.id_aktivitas', $id)
        ->where('a.kategori', 'SPJ')
        ->order_by('a.id_dokumen');
    $result = $this->db->get()->result_array();

    return $result;
  }

  
    function insert_dokumen()
  {
    if ($this->purge_file)
    {
      $this->db->select('NAMA_FILE');
      $this->db->from('DOKUMEN');
      $this->db->where_in('ID_DOKUMEN', $this->purge_file); 
      $result = $this->db->get()->result_array();
      foreach ($result as $row)
      {
        $this->unlink_fileupload($row['NAMA_FILE']);
      }

      $this->db->where_in('ID_DOKUMEN', $this->purge_file);
      $this->db->or_where_in('NAMA_FILE', $this->purge_file);
      $this->db->delete('DOKUMEN');      
    }
    
    for ($i=0; $i<=count($this->data_doc)-1; $i++)
    {
      $id_doc = isset($this->data_doc[$i]['ID_DOKUMEN']) ? $this->data_doc[$i]['ID_DOKUMEN'] : NULL;
      $this->db->select('1')->from('DOKUMEN')->where('ID_AKTIVITAS', $this->id)->where('ID_DOKUMEN', $id_doc);
      $rs = $this->db->get()->row_array();
      
      if ( !$rs )
      {
        unset ( $this->data_doc[$i]['ID_DOKUMEN'] );
        $this->data_doc[$i]['KATEGORI'] = 'SPJ';
        $this->data_doc[$i]['ID_AKTIVITAS'] = $this->id;
        $this->db->insert('DOKUMEN ', $this->data_doc[$i]);
      }
    }
    
  }
  
	function check_dependency($id){
		$this->db->select('count(a.id_aktivitas_spj) spj_exists');
		$this->db->from('spp a');
		$this->db->where('a.id_aktivitas_spj', $id);
		$result = $this->db->get()->row_array();

		if ($result && $result['SPJ_EXISTS'] > 0 )
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

}