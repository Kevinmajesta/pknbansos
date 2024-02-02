<?php
class Pengujian_model extends CI_Model {

  var $id;
  var $fieldmap;
  var $fieldmap_uji;
  var $data;
  var $data_tim;
  var $data_doc_adm;
  var $data_doc_mat;
  var $data_doc_tim_uji;
  var $purge_fileadm;
  var $purge_filemat;
  var $purge_tim_uji;
  var $path;
  var $tahun;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    
    $this->tahun = $this->session->userdata('tahun');
    //$this->path = './uploads/';
	$this->load->helper('path');
	$this->path = dirname( realpath(SELF) )."/uploads/";

    $this->fieldmap = array(
      'id' => 'a.ID_PROPOSAL',
      'no' => 'b.NOMOR',
      'tgl' => 'a.TANGGAL',
      'hasil_uji_mat' => 'a.HASIL_UJI_MATERIAL',
      'hasil_uji_adm' => 'a.HASIL_UJI_ADMINISTRASI',
      'nama_pmh' => 'b.NAMA_PEMOHON',
      'alamat_pmh' => 'b.ALAMAT_PEMOHON',
      'nom_aju' => 'b.NOMINAL_DIAJUKAN',
    );
    
    $this->fieldmap_uji = array(
      'id_proposal' => 'ID_PROPOSAL',
      'no_uji' => 'NOMOR',
      'tgl' => 'TANGGAL',
      'uji_mat' => 'UJI_MATERIAL',
      'hasil_uji_mat' => 'HASIL_UJI_MATERIAL',
      'uji_adm' => 'UJI_ADMINISTRASI',
      'hasil_uji_adm' => 'HASIL_UJI_ADMINISTRASI',
    );

    $this->fieldmap_tim_uji = array(
      'id_proposal' => 'ID_PROPOSAL',
      'id' => 'ID_PEJABAT',
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
      'sub_kategori' => 'SUB_KATEGORI'
    );
  }

  function fill_data() 
  {
		$this->purge_fileadm = $this->input->post('purge_fileadm'); $this->purge_fileadm = $this->purge_fileadm ? $this->purge_fileadm : NULL;
		$this->purge_filemat = $this->input->post('purge_filemat'); $this->purge_filemat = $this->purge_filemat ? $this->purge_filemat : NULL;
		$this->purge_tim_uji = $this->input->post('purge_tim_uji'); $this->purge_tim_uji = $this->purge_tim_uji ? $this->purge_tim_uji : NULL;

    foreach($this->fieldmap_uji as $key => $value){
      switch ($key)
      {
        case 'tgl'   : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        case 'hasil_uji_mat'   : $$key = $this->input->post($key) ? $this->input->post($key) : 0; break;
        case 'hasil_uji_adm'   : $$key = $this->input->post($key) ? $this->input->post($key) : 0; break;
        default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }
      $this->data[$value] = $$key;
    }

    /* foreach($this->fieldmap_tim_uji as $key => $value){
      switch ($key)
      {
        default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }
      $this->data_tim[$value] = $$key;
    } */

    /* ambil grid fileupload administrasi*/
    $file_tim_uji = $this->input->post('file_tim_uji') ? $this->input->post('file_tim_uji') : NULL;
    if ($file_tim_uji)
    {
      $file_tim_uji = json_decode($file_tim_uji);
      for ($i=0; $i <= count($file_tim_uji) - 1; $i++) {
        foreach($this->fieldmap_tim_uji as $key => $value){
          switch ($key)
          {
			case 'id_proposal' : $$key = $this->input->post('id_proposal') ? prepare_date($this->input->post('id_proposal')) : NULL; break;
            default : $$key = isset($file_tim_uji[$i]->$key) && $file_tim_uji[$i]->$key ? $file_tim_uji[$i]->$key : NULL;
          }
          if(isset($$key))
            $this->data_doc_tim_uji[$i][$value] = $$key;
        }
      }
    }
	
	/* ambil grid fileupload administrasi*/
    $file_adm = $this->input->post('file_adm') ? $this->input->post('file_adm') : NULL;
    if ($file_adm)
    {
      $file_adm = json_decode($file_adm);
      for ($i=0; $i <= count($file_adm) - 1; $i++) {
        foreach($this->fieldmap_doc as $key => $value){
          switch ($key)
          {
            case 'tgl_upload' : $$key = isset($file_adm[$i]->$key) ? prepare_date($file_adm[$i]->$key) : NULL; break;
            default : $$key = isset($file_adm[$i]->$key) && $file_adm[$i]->$key ? $file_adm[$i]->$key : NULL;
          }
          if(isset($$key))
            $this->data_doc_adm[$i][$value] = $$key;
        }
      }
    }

    /* ambil grid fileupload material*/
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
              'no' => array('name' => 'Nomor Proposal', 'kategori'=>'string'),
              'tgl' => array('name' => 'Tanggal', 'kategori'=>'date'),
              'nama_pmh' => array('name' => 'Nama Pemohon', 'kategori'=>'string'),
              'alamat_pmh' => array('name' => 'Alamat', 'kategori'=>'string'),
              'bantuan' => array('name' => 'Jenis Bantuan', 'kategori'=>'predefined', 'options'=> $opt_bantuan),
              'kategori' => array('name' => 'Kelompok', 'kategori'=>'predefined', 'options'=> $opt_kategori),
              'nom_aju' => array('name' => 'Nominal', 'kategori'=>'numeric'),
              'hasil_uji_adm' => array('name' => 'Uji Administrasi', 'kategori'=>'predefined', 'options'=> array(0=>'Ditolak', 1=>'Diterima')),
              'hasil_uji_mat' => array('name' => 'Uji Material', 'kategori'=>'predefined', 'options'=> array(0=>'Ditolak', 1=>'Diterima')),
             );
    
    return $fields;
  }
  
  function insert_data()
  {    
    // update status proposal
    $this->update_proposal($this->data['ID_PROPOSAL'], $this->data['HASIL_UJI_ADMINISTRASI'], $this->data['HASIL_UJI_MATERIAL']);
	$this->insert_update_tim_uji($this->data['ID_PROPOSAL']);
    if (isset($this->data['ID_PROPOSAL']) && $this->input->post('mode') === 'edit')
    {
      $this->db->where('ID_PROPOSAL', $this->data['ID_PROPOSAL']);
      $this->db->update('PENGUJIAN', $this->data);

      $this->db->where('ID_PROPOSAL', $this->data_tim['ID_PROPOSAL']);
      
      return $this->data['ID_PROPOSAL'];
    }
    else
    {
      $this->db->insert('PENGUJIAN', $this->data);

      return $this->data['ID_PROPOSAL'];
    }
  }
	function insert_update_tim_uji($id_proposal){
		$this->db->where('ID_PROPOSAL', $id_proposal);
		$this->db->delete('TIM_PENGUJI');
		
		for ($i=0; $i <= count($this->data_doc_tim_uji) - 1; $i++) {
			$this->db->insert('TIM_PENGUJI', $this->data_doc_tim_uji[$i]);
		}
	}
  
  function insert_dokumen()
  {
    if ($this->purge_fileadm)
    {
      $this->db->select('NAMA_FILE');
      $this->db->from('DOKUMEN');
      $this->db->where_in('ID_DOKUMEN', $this->purge_fileadm); 
      $result = $this->db->get()->result_array();
      foreach ($result as $row)
      {
        $this->unlink_fileupload($row['NAMA_FILE']);
      }

      $this->db->where_in('ID_DOKUMEN', $this->purge_fileadm);
      $this->db->or_where_in('NAMA_FILE', $this->purge_fileadm);
      $this->db->delete('DOKUMEN');      
    }
    
    if ($this->purge_filemat)
    {
      $this->db->select('NAMA_FILE');
      $this->db->from('DOKUMEN');
      $this->db->where_in('ID_DOKUMEN', $this->purge_filemat);
      $result = $this->db->get()->result_array();
      foreach ($result as $row)
      {
        $this->unlink_fileupload($row['NAMA_FILE']);
      }

      $this->db->where_in('ID_DOKUMEN', $this->purge_filemat);
      $this->db->or_where_in('NAMA_FILE', $this->purge_filemat);
      $this->db->delete('DOKUMEN');
    }
    
    for ($i=0; $i<=count($this->data_doc_adm)-1; $i++)
    {
      $id_doc = isset($this->data_doc_adm[$i]['ID_DOKUMEN']) ? $this->data_doc_adm[$i]['ID_DOKUMEN'] : NULL;
      $this->db->select('1')->from('DOKUMEN')->where('ID_PROPOSAL', $this->id)->where('ID_DOKUMEN', $id_doc);
      $rs = $this->db->get()->row_array();
      
      if ( !$rs )
      {
        unset ( $this->data_doc_adm[$i]['ID_DOKUMEN'] );
        $this->data_doc_adm[$i]['KATEGORI'] = 'PENGUJIAN';
        $this->data_doc_adm[$i]['SUB_KATEGORI'] = 'administrasi';
        $this->data_doc_adm[$i]['ID_PROPOSAL'] = $this->id;
        $this->db->insert('DOKUMEN ', $this->data_doc_adm[$i]);
      }
    }
    
    for ($i=0; $i<=count($this->data_doc_mat)-1; $i++)
    {
      $id_doc = isset($this->data_doc_mat[$i]['ID_DOKUMEN']) ? $this->data_doc_mat[$i]['ID_DOKUMEN'] : NULL;
      $this->db->select('1')->from('DOKUMEN')->where('ID_PROPOSAL', $this->id)->where('ID_DOKUMEN', $id_doc);
      $rs = $this->db->get()->row_array();
      
      if ( !$rs )
      {
        unset ( $this->data_doc_mat[$i]['ID_DOKUMEN'] );
        $this->data_doc_mat[$i]['KATEGORI'] = 'PENGUJIAN';
        $this->data_doc_mat[$i]['SUB_KATEGORI'] = 'material';
        $this->data_doc_mat[$i]['ID_PROPOSAL'] = $this->id;
        $this->db->insert('DOKUMEN ', $this->data_doc_mat[$i]);
      }
    }
  }
  
  function update_proposal($id=0, $uji_adm=FALSE, $uji_mat=FALSE, $del=FALSE)
  {
    if ($del === TRUE) {
      $status = array('STATUS' => null);
    } else {
      if ((isset($uji_adm) && $uji_adm == 1) || (isset($uji_mat) && $uji_mat == 1))
        $status = array('STATUS' => 'Lolos Uji');
      else if ((isset($uji_adm) && $uji_adm == 0) && (isset($uji_mat) && $uji_mat == 0))
        $status = array('STATUS' => 'Tidak Lolos Uji');
    }

    $this->db->where('ID_PROPOSAL', $id);
    $this->db->update('PROPOSAL', $status);
  }

  function unlink_fileupload($filename)
  {
    $path = $this->path;
    $file = $path . $filename;
    array_map( 'unlink', glob($file) );    
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
  
  function kirim_proposal()
  {
    $id = $this->input->post('id');
    $data_post = array('posted' => 1);
    $this->db->trans_start();
    $this->db->where('ID_PROPOSAL', $id);
    $this->db->update('PENGUJIAN', $data_post);
    
    return $this->db->trans_status();
    
  }
  
  function get_data_jenis_bantuan()
  {
    $this->db->select('r.jenis_bantuan');
    $this->db->distinct('r.jenis_bantuan');
    $result = $this->db->get('kategori_pemohon r');
    
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
			a.id_proposal,
			b.nomor nomor_proposal,
			b.nama_pemohon,
			b.alamat_pemohon,
			b.nominal_diajukan,
			iif(a.hasil_uji_material = 1, 'Diterima', 'Ditolak') hasil_uji_material,
			a.uji_material,
			iif(a.hasil_uji_administrasi = 1, 'Diterima', 'Ditolak') hasil_uji_administrasi,
			a.uji_administrasi,
		");
		$this->db->from('pengujian a');
		$this->db->join('proposal b', 'a.id_proposal = b.id_proposal');
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
        a.nomor nomor_uji,
        b.nomor nomor_proposal,
        b.jenis_bantuan,
        a.tanggal,
        a.uji_material,
        a.hasil_uji_material,
        a.uji_administrasi,
        a.hasil_uji_administrasi,
        a.posted,
        c.id_pejabat,
        d.nama_pejabat,
        b.tanggal tanggal_proposal
    ');
    $this->db->from('pengujian a');
    $this->db->join('proposal b', 'a.id_proposal = b.id_proposal');
    $this->db->join('tim_penguji c', 'a.id_proposal = c.id_proposal', 'left');
    $this->db->join('pejabat_daerah d', 'c.id_pejabat = d.id_pejabat_daerah', 'left');
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
       r.tanggal,
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
  
  function get_tim_uji_by_id($id)
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

  function get_prev_id($id)
  {
    $this->db->select('coalesce(max(a.id_proposal), 0) id_proposal');
    $this->db->from('pengujian a');
    $this->db->where('id_proposal < ', $id);
    $result = $this->db->get()->row_array();

    return $result['ID_PROPOSAL'];
  }

  function get_next_id($id)
  {
    $this->db->select('coalesce(min(a.id_proposal), 0) id_proposal');
    $this->db->from('pengujian a');
    $this->db->where('id_proposal > ', $id);
    $result = $this->db->get()->row_array();

    return $result['ID_PROPOSAL'];
  }

  function delete_data($id)
  {
    $this->db->trans_start();
    
    $this->db->where('id_proposal', $id);
    $this->db->delete('pengujian');
    
    $this->db->where('id_proposal', $id);
    $this->db->delete('tim_penguji');
    
    // get nama fileupload
    $this->db->select('nama_file');
    $this->db->from('dokumen');
    $this->db->where('id_proposal', $id);
    $result = $this->db->get()->result_array();
    foreach ($result as $row)
    {
      $this->unlink_fileupload($row['NAMA_FILE']);
    }
    
    $this->db->where('id_proposal', $id);
    $this->db->delete('dokumen');
    
    $this->update_proposal($id, FALSE, FALSE, TRUE);

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
  }

  function check_dependency($id)
  {
    $this->db->select('count(a.id_proposal) disposisi_pakai');
    $this->db->from('disposisi a');
    $this->db->where('a.id_proposal', $id);
    $result1 = $this->db->get()->row_array();
    
    if ($result1 && $result1['DISPOSISI_PAKAI'] > 0 )
    {
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }
  
  function check_posted($id)
  {
    $this->db->select('a.posted');
    $this->db->from('pengujian a');
    $this->db->where('a.id_proposal', $id);
    $result = $this->db->get()->row_array();
    
    if ($result['POSTED'] === 1)
    {
      return TRUE;
    }
    else
    {
      return FALSE;
    }
  }
  
  function check_duplikat_number($no)
  {
    $id = $this->input->post('id');
    $time = strtotime(prepare_date($this->input->post('tgl')));
    $year = date("Y", $time);
    
    $this->db->select('count(a.nomor) nomor_pakai');
    $this->db->from('pengujian a');
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
