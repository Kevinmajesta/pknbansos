<?php
class Bastnon_model extends CI_Model {

	var $id;
	var $fieldmap;
	var $fieldmap_bast;
	var $fieldmap_doc;
	var $data;
	var $data_doc;
	var $purge_file;
	var $path;
	var $data_doc_mat;
	var $purge_filemat;

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();

		$this->tahun = $this->session->userdata('tahun');
		$this->load->helper('path');
		$this->path = dirname( realpath(SELF) )."/uploads/";

		$this->fieldmap = array(
			'no' => 'a.NOMOR_BAST',
			'tgl' => 'a.TANGGAL',
			'nama' => 'a.NAMA',
			'peruntukan' => 'a.PERUNTUKAN',
			'keperluan' => 'a.KEPERLUAN',
			'nominal' => 'a.NOMINAL',			
		);
    
		$this->fieldmap_bast = array(
			'id' => 'ID_BAST',
			'no' => 'NOMOR_BAST',
			'tgl' => 'TANGGAL',
			'nom' => 'NOMINAL',
			'id_rincian_anggaran' => 'ID_RINCIAN_ANGGARAN',
			'id_rekening' => 'ID_REKENING',
			'nama' => 'NAMA',
			'nik' => 'NIK',
			'alamat' => 'ALAMAT',
			'tgl_lahir' => 'TANGGAL_LAHIR',
			'pekerjaan' => 'PEKERJAAN',
			'mewakili' => 'MEWAKILI',
			'nama_rekening' => 'NAMA_REKENING',
			'nomor_rekening' => 'NOMOR_REKENING',
			'nama_bank' => 'NAMA_BANK',
			'npwp' => 'NPWP',
			'pejabat_daerah' => 'ID_PEJABAT_DAERAH',
			'ppt' => 'ID_PEJABAT_PENANDA_TANGAN',
			'peruntukan' => 'PERUNTUKAN',
			'keperluan' => 'KEPERLUAN',
			'id_skpd' => 'ID_SKPD',
			'jenis' => 'JENIS',
		);
		
		$this->fieldmap_doc = array(
			'id_doc' => 'ID_DOKUMEN_BAST',
			'id' => 'ID_BAST',
			'nama_doc' => 'NAMA_DOKUMEN',
			'nama_file' => 'NAMA_FILE',
			'mime' => 'MIME',
			'ukuran' => 'UKURAN',
			'tgl_upload' => 'TANGGAL_UPLOAD'
		);

	}

	function fill_data() 
	{
		foreach($this->fieldmap_bast as $key => $value){
			switch ($key)
			{
				case 'tgl'   : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
				case 'tgl_lahir'   : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
				case 'jenis'   : $$key = 'BANSOSNON'; break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			$this->data[$value] = $$key;
		}
		
		$this->purge_filemat = $this->input->post('purge_filemat'); $this->purge_filemat = $this->purge_filemat ? $this->purge_filemat : NULL;
		
		$file_mat = $this->input->post('file_mat') ? $this->input->post('file_mat') : NULL;
		if ($file_mat)
		{
			$file_mat = json_decode($file_mat);
			for ($i=0; $i <= count($file_mat) - 1; $i++) {
				foreach($this->fieldmap_doc as $key => $value){
					switch ($key)
					{
						case 'tgl_upload' : $$key = isset($file_mat[$i]->$key) ? prepare_date($file_mat[$i]->$key) : NULL; break;
						default : $$key = isset($file_mat[$i]->$key) && $file_mat[$i]->$key ? $file_mat[$i]->$key : NULL;
					}
					if(isset($$key))
					$this->data_doc_mat[$i][$value] = $$key;
				}
			}
		}
	}
  
	// ----- search advance ---- >>
	function get_data_fields()
	{
		$fields = array( 
			'no' => array('name' => 'Nomor BAST', 'kategori'=>'string'),
			'tgl' => array('name' => 'Tanggal', 'kategori'=>'date'),
			'nama' => array('name' => 'Nama', 'kategori'=>'string'),
			'peruntukan' => array('name' => 'Peruntukan', 'kategori'=>'string'),
			'keperluan' => array('name' => 'Keperluan', 'kategori'=>'string'),
			'nominal' => array('name' => 'Nominal', 'kategori'=>'numeric'),
			
        );
    
		return $fields;
	}
  
	function insert_data()
	{
		if (isset($this->data['ID_BAST']) && $this->input->post('mode') === 'edit')
		{
			$this->db->where('ID_BAST', $this->data['ID_BAST']);
			$this->db->update('BAST', $this->data);

			return $this->data['ID_BAST'];
		}
		else
			{
			$this->db->insert('BAST', $this->data);
			$this->db->select_max('ID_BAST')->from('BAST');
			$rs = $this->db->get()->row_array();
			return $rs['ID_BAST'];
		}
	}
	
	function insert_dokumen()
	{        
		if ($this->purge_filemat)
		{
			$this->db->select('NAMA_FILE');
			$this->db->from('DOKUMEN_BAST');
			$this->db->where_in('ID_DOKUMEN_BAST', $this->purge_filemat);
			$result = $this->db->get()->result_array();
			foreach ($result as $row)
			{
				$this->unlink_fileupload($row['NAMA_FILE']);
			}

			$this->db->where_in('ID_DOKUMEN_BAST', $this->purge_filemat);
			$this->db->or_where_in('NAMA_FILE', $this->purge_filemat);
			$this->db->delete('DOKUMEN_BAST');
		}
		
		for ($i=0; $i<=count($this->data_doc_mat)-1; $i++)
		{
			$id_doc = isset($this->data_doc_mat[$i]['ID_DOKUMEN_BAST']) ? $this->data_doc_mat[$i]['ID_DOKUMEN_BAST'] : NULL;
			$this->db->select('1')->from('DOKUMEN_BAST')->where('ID_BAST', $this->id)->where('ID_DOKUMEN_BAST', $id_doc);
			$rs = $this->db->get()->row_array();

			if ( !$rs )
			{
				unset ( $this->data_doc_mat[$i]['ID_DOKUMEN_BAST'] );
				$this->data_doc_mat[$i]['ID_BAST'] = $this->id;
				$this->db->insert('DOKUMEN_BAST ', $this->data_doc_mat[$i]);
			}
		}
	}

	function save_data()
	{
		$this->db->trans_start();
		$this->id = $this->insert_data();
		$this->insert_dokumen();
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
	}
   
	function get_data($param, $isCount=FALSE, $CompileOnly=False)
	{
		isset($param['limit']) && $param['limit'] ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '';

		if (isset($param['m']) && $param['m'] == 'm' && $param['q'] !== ''){
			foreach ($param['q'] as $key=>$val) {
				$search_str = isset($val['searchString']) ? $val['searchString'] : '';
				$search_str1 = isset($val['searchString1']) ? $val['searchString1'] : '';
				$search_str2 = isset($val['searchString2']) ? $val['searchString2'] : '';
				$flt[$val['searchField']] = array('search_str' => $search_str, 'search_str1' => $search_str1, 'search_str2' => $search_str2, 
				'search_op' => $val['searchOper'], 'search_ctg' => $val['searchKategori']);
			}
			$wh = get_where_str($flt, $this->fieldmap);

			$this->db->where($wh);
		}
		else if (isset($param['m']) && $param['m'] == 's' && $param['q'] !== ''){
			$flt = array();
			foreach($this->fieldmap as $key => $value){
				$flt[$key] = array('search_str' => $param['q'], 'search_op' => 'cn');
			}
			$wh = get_where_str($flt, $this->fieldmap);
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
    
		$this->db->select("
			a.id_bast,
			a.nomor_bast,
			a.tanggal,
			coalesce(a.nominal,0) nominal,
			a.id_rincian_anggaran,
			a.id_rekening,
			a.nama,
			a.nik,
			a.alamat,
			a.tanggal_lahir,
			a.pekerjaan,
			a.mewakili,
			a.nama_rekening,
			a.nomor_rekening,
			a.nama_bank,
			a.npwp,
			a.id_pejabat_daerah,
			b.nama_pejabat nama_pejabat_daerah,
			a.id_pejabat_penanda_tangan,
			c.nama_pejabat nama_pejabat_penanda_tangan,
			a.peruntukan,
			a.keperluan,
			a.id_skpd,
			d.kode_skpd_lkp,
			d.nama_skpd,
			e.uraian 
		");
		$this->db->from('bast a');
		$this->db->join('pejabat_daerah b', 'a.id_pejabat_daerah = b.id_pejabat_daerah');
		$this->db->join('pejabat_daerah c', 'a.id_pejabat_penanda_tangan = c.id_pejabat_daerah');
		$this->db->join('rincian_anggaran e', 'e.id_rincian_anggaran = a.id_rincian_anggaran');
		$this->db->join('v_skpd d', 'd.id_skpd = a.id_skpd');
		$this->db->where("EXTRACT( YEAR FROM a.tanggal) = ",$this->tahun);
		$this->db->where('a.jenis','BANSOSNON');
    
		if ($isCount) {
			$result = $this->db->count_all_results();
			return $result;
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

	function get_data_by_id($id)
	{
		$this->db->select("
			a.id_bast,
			a.nomor_bast,
			a.tanggal,
			coalesce(a.nominal,0) nominal,
			a.id_rincian_anggaran,
			a.id_rekening,
			a.nama,
			a.nik,
			a.alamat,
			a.tanggal_lahir,
			a.pekerjaan,
			a.mewakili,
			a.nama_rekening,
			a.nomor_rekening,
			a.nama_bank,
			a.npwp,
			a.id_pejabat_daerah,
			b.nama_pejabat nama_pejabat_daerah,
			a.id_pejabat_penanda_tangan,
			c.nama_pejabat nama_pejabat_penanda_tangan,
			a.peruntukan,
			a.keperluan,
			a.id_skpd,
			d.kode_skpd_lkp,
			d.nama_skpd,
			e.uraian 
		");
		$this->db->from('bast a');
		$this->db->join('pejabat_daerah b', 'a.id_pejabat_daerah = b.id_pejabat_daerah');
		$this->db->join('pejabat_daerah c', 'a.id_pejabat_penanda_tangan = c.id_pejabat_daerah');
		$this->db->join('rincian_anggaran e', 'e.id_rincian_anggaran = a.id_rincian_anggaran');
		$this->db->join('v_skpd d', 'd.id_skpd = a.id_skpd');
		$this->db->where('a.id_bast', $id);
		$result = $this->db->get()->row_array();

		return $result;
	}
 
	function cek_duplikasi_nomor($nomor)
	{
		$id = $this->input->post('id') ? $this->input->post('id') : NULL;

		if ($id) $this->db->where('a.ID_BAST <>', $id);
		$this->db->select('COUNT(a.NOMOR_BAST) DUP')
		->from('BAST a')
		->where('a.jenis', 'BANSOSNON')
		->where('a.NOMOR_BAST', $nomor);
		$rs = $this->db->get()->row_array();

		return (integer)$rs['DUP'] === 0;
	}

	function get_prev_id($id)
	{
		$this->db->select('coalesce(max(a.id_bast), 0) id_bast');
		$this->db->from('bast a');
		$this->db->where('a.jenis','BANSOSNON');
		$this->db->where('a.id_bast < ', $id);
		$result = $this->db->get()->row_array();

		return $result['ID_BAST'];
	}

	function get_next_id($id)
	{
		$this->db->select('coalesce(min(a.id_bast), 0) id_bast');
		$this->db->from('bast a');
		$this->db->where('a.jenis','BANSOSNON');
		$this->db->where('a.id_bast > ', $id);
		$result = $this->db->get()->row_array();

		return $result['ID_BAST'];
	}

	function delete_data($id)
	{
		$this->db->trans_start();
		
		// get nama fileupload
		$this->db->select('nama_file');
		$this->db->from('dokumen_bast');
		$this->db->where('id_bast', $id);
		$result = $this->db->get()->result_array();
		foreach ($result as $row)
		{
			$this->unlink_fileupload($row['NAMA_FILE']);
		}

		$this->db->where('id_bast', $id);
		$this->db->delete('bast');

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
	}
	
	function unlink_fileupload($filename)
	{
		$path = $this->path;
		$file = $path . $filename;
		array_map( 'unlink', glob($file) );    
	}

	function check_dependency($id)
	{
	}
	
	function get_scan_file_mat($id)
	{
		$this->db->select('a.id_dokumen_bast, a.nama_dokumen, a.nama_file, a.mime, a.ukuran, a.tanggal_upload')
			->from('dokumen_bast a')
			->where('a.id_bast', $id)
			->order_by('a.id_dokumen_bast');
		$result = $this->db->get()->result_array();

		return $result;
	}
	
	function get_data_rekening($param)
	{		
		$this->db->select('rk.nama_rekening, coalesce(sum(rin.pagu),0) total_pagu_rekening');
		$this->db->from('rincian_anggaran rin');
		$this->db->join('rekening rk','rk.id_rekening=rin.id_rekening');
		$this->db->join('form_anggaran fa','fa.id_form_anggaran=rin.id_form_anggaran');
		$this->db->join('tahun_anggaran ta','fa.tahun=ta.tahun and ta.status_kini=fa.status');
		$this->db->where('rin.id_rekening', $param['idrek'] );
		$this->db->where('fa.id_skpd', $param['id_skpd'] );
		$this->db->where('fa.tahun', $this->tahun);
		$this->db->where('fa.tipe', 'RKA21');
		$this->db->group_by('1');
		$anggaran = $this->db->get()->row_array();
		
		//total bast
		$this->db->select("
			coalesce(sum(b.nominal), 0) bast_pakai
		");
		$this->db->from('bast b');
		$jenis = array('BANKEU','BANSOSNON','BANSOS','NPHD');
		$this->db->where_in('b.jenis',$jenis);
		$this->db->where('b.id_rekening', $param['idrek'] );
		if($param['id'])
			$this->db->where('b.id_bast <> ', $param['id']);
		$bast = $this->db->get()->row_array();
		
		//nominal sp2d
		$this->db->select("
			coalesce(sum(a.nominal), 0) nominal_sp2d
		");
		$this->db->from('v_sp2d_kegiatan a');
		$this->db->where('a.tahun', $this->tahun);
		$this->db->where('a.beban', 'BTL');
		$this->db->where('a.id_skpd', $param['id_skpd'] );
		if($param['keperluan']=='UP'){
			$kep = array('UP','GU');
			$this->db->where_in('a.keperluan', $kep);
		}
		else
			$this->db->where('a.keperluan', $param['keperluan']);
		$sp2d = $this->db->get()->row_array();
					
		$hasil = array('nama_rekening'=>$anggaran['NAMA_REKENING'], 'total_pagu'=>$anggaran['TOTAL_PAGU_REKENING'], 'bast_pakai'=>$bast['BAST_PAKAI'], 'nominal_sp2d'=>$sp2d['NOMINAL_SP2D']);
		return $hasil;
	}
	
	function get_rincian($id,$id_skpd,$id_rekening,$id_rincian_anggaran)
	{
		if($id > 0){
			$result = $this->db->query("
				select
					-r.id_rekening id_rincian_anggaran,
					r.id_rekening,
					r.kode_rekening,
					r.nama_rekening,
					0 nominal_bast,
					1 level,
					sum(ra.pagu) pagu
				from rincian_anggaran ra
				join rekening r on r.id_rekening = ra.id_rekening
				join form_anggaran fa on fa.id_form_anggaran = ra.id_form_anggaran
				join tahun_anggaran ta on ta.tahun = fa.tahun and ta.status_kini=fa.status
				where fa.id_skpd=".$id_skpd." and fa.tahun=".$this->tahun." and ra.id_rekening=".$id_rekening."
				group by 1,2,3,4,5
				
				union all 
				
				select
					ra.id_rincian_anggaran,
					r.id_rekening,
					'' kode_rekening,
					ra.uraian,
					coalesce(ba.nominal,0) nominal_bast,
					2 level,
					sum(ra.pagu) pagu
				from rincian_anggaran ra
				join rekening r on r.id_rekening = ra.id_rekening
				join form_anggaran fa on fa.id_form_anggaran = ra.id_form_anggaran
				join tahun_anggaran ta on ta.tahun = fa.tahun and ta.status_kini=fa.status
				left join bast ba on ba.id_rincian_anggaran=ra.id_rincian_anggaran and ba.jenis='BANSOSNON'
				where fa.id_skpd=".$id_skpd." and fa.tahun=".$this->tahun." and ra.id_rincian_anggaran=".$id_rincian_anggaran." and ba.id_bast=".$id."
				group by 1,2,3,4,5
			");
		}
		else
		{
			$result = $this->db->query("
				select
					-r.id_rekening id_rincian_anggaran,
					r.id_rekening,
					r.kode_rekening,
					r.nama_rekening,
					0 nominal_bast,
					1 level,
					sum(ra.pagu) pagu
				from rincian_anggaran ra
				join rekening r on r.id_rekening = ra.id_rekening
				join form_anggaran fa on fa.id_form_anggaran = ra.id_form_anggaran
				join tahun_anggaran ta on ta.tahun = fa.tahun and ta.status_kini=fa.status
				where fa.id_skpd=".$id_skpd." and fa.tahun=".$this->tahun." and ra.id_rekening=".$id_rekening."
				group by 1,2,3,4,5
				
				union all 
				
				select
					ra.id_rincian_anggaran,
					r.id_rekening,
					'' kode_rekening,
					ra.uraian,
					0 nominal_bast,
					2 level,
					sum(ra.pagu) pagu
				from rincian_anggaran ra
				join rekening r on r.id_rekening = ra.id_rekening
				join form_anggaran fa on fa.id_form_anggaran = ra.id_form_anggaran
				join tahun_anggaran ta on ta.tahun = fa.tahun and ta.status_kini=fa.status
				where fa.id_skpd=".$id_skpd." and fa.tahun=".$this->tahun." and ra.id_rincian_anggaran=".$id_rincian_anggaran." 
				group by 1,2,3,4,5
			");
		}
		return $result->result_array();
	}
	
	function total_bast($id_bast,$id_rekening){
		//total bast
		$this->db->select("
			coalesce(sum(b.nominal), 0) bast_pakai
		");
		$this->db->from('bast b');
		$this->db->where('b.jenis','BANSOSNON');
		$this->db->where('b.id_rekening', $id_rekening);
		if($id_bast)
			$this->db->where('b.id_bast <> ', $id_bast);
		$bast = $this->db->get()->row_array();
		return $bast['BAST_PAKAI'];	
	}
	
	function check_nik($nik)
	{
		$id = $this->input->post('id');

		$this->db->select('first 1 a.tanggal, a.jenis, count(a.nik) nik_pakai');
		$this->db->from('bast a');
		$this->db->where('a.id_bast <>', $id);
		$this->db->where('a.nik', $nik);
		$this->db->group_by('a.tanggal, a.jenis');
		$this->db->order_by('a.tanggal desc');

		$result = $this->db->get()->row_array();

		return $result;
	}
}
