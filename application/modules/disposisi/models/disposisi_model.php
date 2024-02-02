<?php
class Disposisi_model extends CI_Model {

  var $id;
  var $fieldmap;
  var $fieldmap_disposisi;
  var $data;
  var $path;
  var $tahun;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    
      $this->tahun = $this->session->userdata('tahun');
      $this->path = './uploads/';	

     $this->fieldmap = array(
      'id' => 'a.ID_PROPOSAL',
      'no_disposisi' => 'a.NOMOR',
      'tgl' => 'a.TANGGAL',
      'catatan' => 'a.CATATAN',
      'keputusan' => 'a.KEPUTUSAN',
      'no_uji' => 'b.NOMOR',
      'no_proposal' => 'c.NOMOR',
      'nama_pmh' => 'c.NAMA_PEMOHON',
      'alamat_pmh' => 'c.ALAMAT_PEMOHON',
      'nom_aju' => 'c.NOMINAL_DIAJUKAN',
    );
    
    $this->fieldmap_disposisi = array(
      'id_proposal' => 'ID_PROPOSAL',
      'no_disposisi' => 'NOMOR',
      'tgl' => 'TANGGAL',
      'cttn_disposisi' => 'CATATAN',
      'hasil_disposisi' => 'KEPUTUSAN',
    );
    
  }

  function fill_data()
  {
    foreach($this->fieldmap_disposisi as $key => $value){
      switch ($key)
      {
        case 'tgl'   : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        case 'hasil_disposisi'   : $$key = $this->input->post($key) ? $this->input->post($key) : 0; break;
        default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }
      $this->data[$value] = $$key;
    }

  }
  
  // ----- search advance ---- >>
  function get_data_fields()
  {
    $bantuan = $this->db->query("SELECT DISTINCT r.JENIS_BANTUAN FROM KATEGORI_PEMOHON r")->result_array();
    foreach ($bantuan as $key=>$val)
    {
      $opt_bantuan[$val['JENIS_BANTUAN']] = $val['JENIS_BANTUAN'];
    }
    $kategori = $this->db->query("SELECT DISTINCT r.KATEGORI FROM KATEGORI_PEMOHON r")->result_array();
    foreach ($kategori as $key=>$val)
    {
      $opt_kategori[$val['KATEGORI']] = $val['KATEGORI'];
    }
  
    $fields = array( 
              'no_disposisi' => array('name' => 'Nomor Rekomendasi', 'kategori'=>'string'),
              'tgl' => array('name' => 'Tanggal Rekomendasi', 'kategori'=>'date'),
              'no_uji' => array('name' => 'Nomor Uji', 'kategori'=>'string'),
              'no_uji' => array('name' => 'Nomor Proposal', 'kategori'=>'string'),
              'nama_pmh' => array('name' => 'Nama Pemohon', 'kategori'=>'string'),
              'alamat_pmh' => array('name' => 'Alamat', 'kategori'=>'string'),
              'nom_aju' => array('name' => 'Nominal', 'kategori'=>'numeric'),
              'keputusan' => array('name' => 'Status', 'kategori'=>'predefined', 'options'=> array(0=>'Ditolak', 1=>'Diterima')),
             );
    
    return $fields;
  }
  
  
	function get_scan_file_adm($id)
	{
		$this->db->select('a.id_dokumen, a.nama_dokumen, a.nama_file, a.mime, a.ukuran, a.tanggal_upload')
			->from('dokumen a')
			->where('a.id_proposal', $id)
			->where('a.kategori', 'PENGUJIAN')
			->where('a.sub_kategori', 'administrasi')
			->order_by('a.id_dokumen');
		$result = $this->db->get()->result_array();

		return $result;
	}
	
	function get_scan_file_mat($id)
	{
		$this->db->select('a.id_dokumen, a.nama_dokumen, a.nama_file, a.mime, a.ukuran, a.tanggal_upload')
			->from('dokumen a')
			->where('a.id_proposal', $id)
			->where('a.kategori', 'PENGUJIAN')
			->where('a.sub_kategori', 'material')
			->order_by('a.id_dokumen');
		$result = $this->db->get()->result_array();

		return $result;
	}
	
    function get_data_tim_penguji($id)
  {
    $this->db->select('
       r.id_pejabat_penguji,
       r.nip,
       r.nama_pejabat
    ')
      ->from('tim_penguji s')
      ->join('pejabat_penguji r','s.id_pejabat=r.id_pejabat_penguji')
      ->where('s.id_proposal', $id)
      ->order_by('r.nama_pejabat');
    $result = $this->db->get()->result_array();

    return $result;
  }
  
  function insert_data()
  {
    // update status proposal
    $this->update_proposal($this->data['ID_PROPOSAL'], $this->data['KEPUTUSAN']);

    if (isset($this->data['ID_PROPOSAL']) && $this->input->post('mode') === 'edit')
    {
      $this->db->where('ID_PROPOSAL', $this->data['ID_PROPOSAL']);
      $this->db->update('DISPOSISI', $this->data);
      return $this->data['ID_PROPOSAL'];
    }
    else
    {
      $this->db->insert('DISPOSISI', $this->data);
      return $this->data['ID_PROPOSAL'];
    }
  }

  function update_proposal($id=0, $keputusan=FALSE, $del=FALSE)
  {
    if ($del === TRUE) 
    {
      $this->db->select('r.hasil_uji_administrasi, r.hasil_uji_material');
      $this->db->from('pengujian r');
      $this->db->where('r.id_proposal', $id);
      $result = $this->db->get()->row_array();
      
      if ($result['HASIL_UJI_ADMINISTRASI'] === 1 || $result['HASIL_UJI_MATERIAL'] === 1)
        $status = array('STATUS' => 'Lolos Uji');
      else if ($result['HASIL_UJI_ADMINISTRASI'] === 0 && $result['HASIL_UJI_MATERIAL'] === 0)
        $status = array('STATUS' => 'Tidak Lolos Uji');
    } 
    else 
    {
      if ((isset($keputusan) && $keputusan == 1))
        $status = array('STATUS' => 'Lolos Rekomendasi');
      else if ((isset($keputusan) && $keputusan == 0))
        $status = array('STATUS' => 'Disposisi ditolak');
    }
      
    $this->db->where('ID_PROPOSAL', $id);
    $this->db->update('PROPOSAL', $status);
  }

  function save_data()
  {
    $this->db->trans_start();
    $this->id = $this->insert_data();

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
  }
    
  function get_data_jenis_bantuan()
  {
    $this->db->select('r.jenis_bantuan');
    $this->db->distinct('r.jenis_bantuan');
    $result = $this->db->get('kategori_pemohon r');
    
    return $result;
  }
  
  function get_data_pengujian()
  {
    $id = $this->input->post('id');
    $this->db->select('
        a.uji_material,
        a.hasil_uji_material,
        a.uji_administrasi,
        a.hasil_uji_administrasi,
        b.id_pejabat,
        c.nama_pejabat
      ');
    $this->db->from('pengujian a');
    $this->db->join('tim_penguji b', 'a.id_proposal = b.id_proposal', 'left');
    $this->db->join('pejabat_daerah c', 'b.id_pejabat = c.id_pejabat_daerah', 'left');
    $this->db->where('a.id_proposal', $id);
    $result = $this->db->get();
    
    return $result;
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
      
      //$this->db->where($wh);
    }
    else if (isset($param['m']) && $param['m'] == 's' && $param['q'] !== ''){
      $flt = array();
      foreach($this->fieldmap as $key => $value){
        $flt[$key] = array('search_str' => $param['q'], 'search_op' => 'cn');
      }
      $wh = get_where_str($flt, $this->fieldmap);

      //$this->db->or_where($wh);
    }
    else
    {
      if (isset($param['search']) && $param['search'] && $wh = get_where_str(array($param['search_field'] => $param['search_str']), $this->fieldmap))
      {
        //$this->db->where($wh);
      }
    }
    
     if (!empty($wh)) {
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
    
    
    if (isset($param['sort_by']) && $param['sort_by'] != null && !$isCount && $ob = get_order_by_str($param['sort_by'], $this->fieldmap))
    {
      $this->db->order_by($ob, $param['sort_direction']);
    }
    
    $this->db->select("
        a.id_proposal,
        a.nomor nomor_disposisi,
        a.tanggal,
        a.catatan,
        iif(a.keputusan = 1, 'Diterima', 'Ditolak') keputusan,
        b.nomor nomor_uji,
        c.nomor nomor_proposal,
        c.nama_pemohon,
        c.alamat_pemohon,
        c.nominal_diajukan,
    ");
    $this->db->from('disposisi a');
    $this->db->join('pengujian b', 'a.id_proposal = b.id_proposal');
    $this->db->join('proposal c', 'a.id_proposal = c.id_proposal');
    $this->db->where("EXTRACT( YEAR FROM a.tanggal) = ",$this->tahun);

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
    $this->db->select('
        a.id_proposal,
        a.nomor nomor_disposisi,
        a.tanggal,
        a.catatan,
        a.keputusan,
        b.uji_material,
        b.hasil_uji_material,
        b.uji_administrasi,
        b.hasil_uji_administrasi,
        c.nomor nomor_proposal,
        d.id_pejabat,
        e.nama_pejabat
    ');
    $this->db->from('disposisi a');
    $this->db->join('pengujian b', 'a.id_proposal = b.id_proposal');
    $this->db->join('proposal c', 'a.id_proposal = c.id_proposal');
    $this->db->join('tim_penguji d', 'a.id_proposal = d.id_proposal', 'left');
    $this->db->join('pejabat_daerah e', 'd.id_pejabat = e.id_pejabat_daerah', 'left');
    $this->db->where('a.id_proposal', $id);
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_rinci_by_id($id)
  {
    $this->db->select('
       r.id_proposal,
       r.nik,
       r.nama_pemohon,
       r.alamat_pemohon,
       r.nama_pimpinan,
       r.tanggal_lahir,
       cast(substring(r.ringkasan from 1 for 5000) as varchar(5000)) ringkasan,
       r.nominal_diajukan
    ')
      ->from('proposal r')
      ->where('r.id_proposal', $id)
      ->order_by('r.nomor');
    $result = $this->db->get()->result_array();

    return $result;
  }
  
  function get_prev_id($id)
  {
    $this->db->select('coalesce(max(a.id_proposal), 0) id_proposal');
    $this->db->from('disposisi a');
    $this->db->where('id_proposal < ', $id);
    $result = $this->db->get()->row_array();

    return $result['ID_PROPOSAL'];
  }

  function get_next_id($id)
  {
    $this->db->select('coalesce(min(a.id_proposal), 0) id_proposal');
    $this->db->from('disposisi a');
    $this->db->where('id_proposal > ', $id);
    $result = $this->db->get()->row_array();

    return $result['ID_PROPOSAL'];
  }

  function delete_data($id)
  {
    $this->db->trans_start();
    $this->db->where('id_proposal', $id);
    $this->db->delete('disposisi');
    
    $this->update_proposal($id, FALSE, TRUE);
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
  }
  
	function check_dependency($id)
	{
		$this->db->trans_start();
		$this->db->select('a.ID_PROPOSAL');
		$this->db->select('(select count(b.ID_PROPOSAL) from RINCIAN_ANGGARAN b where b.ID_PROPOSAL = a.ID_PROPOSAL) RINCIAN_ANGGARAN_PAKAI');
		$this->db->where('a.ID_PROPOSAL', $id);         
		$result = $this->db->get('PROPOSAL a')->row_array();		
		$this->db->trans_complete();
		
		if ($result['RINCIAN_ANGGARAN_PAKAI'] > 0) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}

  function check_duplikat_number($no)
  {
    $id = $this->input->post('id');
    $time = strtotime(prepare_date($this->input->post('tgl')));
    $year = date("Y", $time);
    
    $this->db->select('count(a.nomor) nomor_pakai');
    $this->db->from('disposisi a');
    $this->db->where('a.nomor', $no);
    $this->db->where('extract(year from a.tanggal) = ', $year);
    $this->db->where('a.id_proposal !=', $id);
    $result = $this->db->get()->row_array();
    
    if ($result && $result['NOMOR_PAKAI'] > 0)
    {
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }
    
}
