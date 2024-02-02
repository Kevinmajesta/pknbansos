<?php
class Skpd_model extends CI_Model {
	
	var $data;
	var $dataTambahan;
	var $fieldmap = array();
	var $fieldmapSKPD = array();
	var $fieldmapTambahan = array();

	
	 function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->_table= array('SKPD','SKPD_BIDANG_TAMBAHAN');
		$this->_pk='ID_SKPD';
		
    $this->fieldmap = array (
      'urusan' => 'U.KODE_URUSAN', 
      'bidang' => 'B.KODE_BIDANG', 
      'skpd' => 'S.KODE_SKPD',
      'namaskpd' => 'S.NAMA_SKPD',
    );

		$this->fieldmapSKPD = array (
		'id' => 'ID_SKPD',
		'idbidang' => 'ID_BIDANG',	
		'skpd' => 'KODE_SKPD',
		'namaskpd' => 'NAMA_SKPD',
		'lokasi' => 'LOKASI',
		'alamat' => 'ALAMAT',
		'npwp' => 'NPWP',
		);
		
		$this->fieldmapTambahan = array(
		'id' => 'ID_SKPD',
		'idbidangtambahan' => 'ID_BIDANG'
		);
	}
	
	/* SKPD */
	
	function fill_data()
	{
		foreach($this->fieldmapSKPD as $key => $value){
			switch ($key){
				case 'id' : $$key = $this->input->post($key) ? ($this->input->post($key)) : NULL; $$key == 'new' ? $$key = NULL : '';break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			//if(isset($$key))
				$this->data[$value] = $$key;
		}

		$idbidangtambahan = json_decode ($this->input->post('idbidangtambahan'));
		$id_tambah = explode (",", $idbidangtambahan);
		
		for($i=0;$i<count($id_tambah);$i++)
		{
			foreach($this->fieldmapTambahan as $key => $value){
				switch ($key){
					case 'idbidangtambahan' : $$key = $id_tambah[$i];break;
					default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
				}
				if(isset($$key))
					$this->dataTambahan[$i][$value] = $$key;
			}
		}
	}
	
  // ----- search advance ---- >>
  function get_data_fields()
  {  
    $fields = array( 
              'urusan' => array('name' => 'URUSAN', 'kategori'=>'string'),
              'bidang' => array('name' => 'BIDANG', 'kategori'=>'string'),
              'skpd' => array('name' => 'SKPD', 'kategori'=>'string'),
              'namaskpd' => array('name' => 'NAMA', 'kategori'=>'string'),
             );
    
    return $fields;
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

			$this->db->or_where($wh);
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
        
		//returns the query string
		$this->db->trans_start();
		
		$this->db->select(
			'U.KODE_URUSAN,U.NAMA_URUSAN, B.ID_BIDANG, B.KODE_BIDANG,B.NAMA_BIDANG, S.ID_SKPD, S.KODE_SKPD, S.NAMA_SKPD, S.LOKASI, S.NPWP, S.ALAMAT, S.ID_REKENING_BANK, U.ID_URUSAN
			'
			);
		$this->db->from('URUSAN as U');
		$this->db->join('BIDANG as B', 'U.ID_URUSAN = B.ID_URUSAN', 'LEFT OUTER');
		$this->db->join('SKPD as S', 'S.ID_BIDANG = B.ID_BIDANG', 'LEFT OUTER');
		
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
				$this->db->order_by ('U.KODE_URUSAN, B.KODE_BIDANG,S.KODE_SKPD');
				$result = $this->db->get()->result_array();
				return $result;
			}
		}
		//echo $this->db->last_query();
		$this->db->trans_complete();
//		return $result;		
	}
	
	function check_data()
	{
		$bidang	= $this->input->post('idbidang');	$bidang=($bidang)?$bidang:null;
		$skpd	= $this->input->post('skpd');	$skpd=($skpd)?$skpd:null;
		
		$this->db->trans_start();		
		$result = $this->db->query("SELECT * FROM SKPD WHERE  kode_skpd = '$skpd' AND id_bidang = '$bidang'")->result_array();		
		if(!empty($result)){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		$this->db->trans_complete();	
	}
	
	function check_data2()
	{
		$bidang	= $this->input->post('idbidang');	$bidang=($bidang)?$bidang:null;
		$skpd	= $this->input->post('skpd');	$skpd=($skpd)?$skpd:null;
		
		$id = $this->db->query("select ID_BIDANG as ID_BID, KODE_SKPD as KODE from SKPD WHERE ID_SKPD='".$this->input->post('id')."'")->row_array();
		
		$this->db->select('KODE_SKPD');
		$this->db->from('SKPD');
		$this->db->where('ID_BIDANG', $id['ID_BID']);
		$this->db->where('KODE_SKPD <>', $id['KODE']);
		$ada = $this->db->get()->result_array();
		
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['KODE_SKPD'];
			}
		}
		
		$query = "SELECT * FROM SKPD WHERE  kode_skpd = '$skpd' AND id_bidang = '$bidang'";
		$result = $this->db->query($query);
		
		
		if($skpd == $id['KODE'] && $bidang == $id['ID_BID']){
			return TRUE;
		}
		else if (in_array($skpd, $hasil)) {
			return FALSE;
		}	
		else if($result->num_rows() > 0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	
	function check_data_bidang_skpd()
	{
		$KODE_UNIT_KERJA	= $this->input->post('KODE_UNIT_KERJA');	$KODE_UNIT_KERJA=($KODE_UNIT_KERJA)?$KODE_UNIT_KERJA:null;
		
		$this->db->trans_start();
		$this->db->select('*');
		$this->db->from('UNIT_KERJA');
		$this->db->where('KODE_UNIT_KERJA', $KODE_UNIT_KERJA);
		$this->db->where('ID_SKPD', $this->session->userdata('ID_SKPD_BIDANG'));
		$ada = $this->db->get()->result_array();
		if(count($ada) > 0)
		{
			return TRUE;
		}
		else{
			return FALSE;
		}		
		
		$this->db->trans_complete();	
	}
	
	function check_data_bidang_skpd2()
	{
		$KODE_UNIT_KERJA	= $this->input->post('KODE_UNIT_KERJA');	$KODE_UNIT_KERJA=($KODE_UNIT_KERJA)?$KODE_UNIT_KERJA:null;
		
		$id = $this->db->query("select KODE_UNIT_KERJA as ID from UNIT_KERJA WHERE ID_UNIT_KERJA='".$this->input->post('id')."'")->row_array();
		
		$this->db->select('KODE_UNIT_KERJA');
		$this->db->from('UNIT_KERJA');
		$this->db->where('KODE_UNIT_KERJA <>', $id['ID']);
		$this->db->where('ID_SKPD', $this->session->userdata('ID_SKPD_BIDANG'));
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['KODE_UNIT_KERJA'];
			}
		}
				
		if($KODE_UNIT_KERJA == $id['ID']){
			return TRUE;
		}
		else if (in_array($KODE_UNIT_KERJA, $hasil, TRUE)) {
			return FALSE;
		}	
		else{
			return TRUE;
		}
	}
	
	function get_parent1($id)
	{
		$this->db->select('
					ID_URUSAN,
					KODE_URUSAN,
					NAMA_URUSAN
				');
		$this->db->where('ID_URUSAN', $id);
		$result = $this->db->get('URUSAN')->row_array();
		return $result;
	}
	
	function get_parent2($id)
	{
		$this->db->select('
					ID_BIDANG,
					KODE_BIDANG,
					NAMA_BIDANG
				');
		$this->db->where('ID_BIDANG', $id);
		$result = $this->db->get('BIDANG')->row_array();
		return $result;
	}
	
	function get_data_bidangtambahan($id)
	{		
		if($id)
		{
		$this->db->trans_start();
		$result = $this->db->query ("select 
			v.kode_skpd_lkp, v.nama_skpd,cast(list((vb.id_bidang)) as varchar(100)) id_bidang_tambahan, 
			cast(list('[' || vb.kode_bidang_lkp || '] ' || vb.nama_bidang, ', ' ) as varchar(5000))  bidang_tambahan
			from v_skpd v
			left join skpd_bidang_tambahan sb on sb.id_skpd = v.id_skpd 
			left join v_bidang vb on vb.id_bidang = sb.id_bidang
			where V.ID_SKPD = ".$id."
			group by 1,2 ")->row_array();
		$this->db->trans_complete();
		return $result;
		}
		else
		{
			return FALSE;
		}
		
	}
	
	function get_data_bidangskpd($id)
	{		
		if($id)
		{
		$this->db->trans_start();
		$result = $this->db->query ("select cast(list((vb.id_unit_kerja)) as varchar(100)) id_bidang_skpd, 
			cast(list('[' || vb.kode_unit_kerja || '] ' || vb.nama_unit_kerja, ', ' ) as varchar(5000))  bidang_skpd
			from v_skpd v
			left join unit_kerja vb on vb.id_skpd = v.id_skpd
			where v.id_skpd = ".$id."")->row_array();
		$this->db->trans_complete();
		return $result;
		}
		else
		{
			return FALSE;
		}
		
	}
	
	function get_data_bidangtambahan2()
	{		
		$this->db->trans_start();
		$this->db->select('ID_BIDANG,KODE_BIDANG_LKP,NAMA_BIDANG');
		$this->db->from('V_BIDANG');		
		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;
	}

	function get_data_by_id($id)
	{
		$this->db->trans_start();
		$this->db->select('	S.ID_SKPD, 
					S.KODE_SKPD, 
					S.NAMA_SKPD, 
					B.ID_BIDANG,
					B.KODE_BIDANG, 
					S.SINGKATAN,    
					S.LOKASI, 
					S.ID_REKENING_BT, 
					S.ID_REKENING_KASDA, 
					S.ID_REKENING_BK,    
					S.NPWP, 
					S.ALAMAT,
					S.ID_REKENING_BANK, 
					S.SINGKATAN_SKPD, 
					U.ID_URUSAN, 
					U.KODE_URUSAN, 
					U.NAMA_URUSAN,    
					B.ID_BIDANG, 
					B.KODE_BIDANG, 
					B.NAMA_BIDANG  ');
		$this->db->from('URUSAN U ');
		$this->db->join('BIDANG as B','U.ID_URUSAN=B.ID_URUSAN','LEFT OUTER');
		$this->db->join('SKPD as S','S.ID_BIDANG=B.ID_BIDANG','LEFT OUTER');
		$this->db->where('ID_SKPD', $ID_SKPD);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
		$this->db->trans_complete();
	}
	
	
	function insert_data()
	{
		$this->fill_data();
		$this->db->trans_start();	
		$result = $this->db->insert('SKPD', $this->data);
		$newid = $this->db->insert_id();
		$this->db->trans_complete();
		$this->db->select_max('ID_SKPD', 'ID');
		$id = $this->db->get('SKPD')->row_array();
		return $id['ID'];		
	}
	
	function update_data($id)
	{
		$this->fill_data();
		//$data = $this->fill_data();
		$this->db->trans_start();
		$this->db->where('ID_SKPD', $id);
		$result = $this->db->update('SKPD', $this->data);
		//$this->insert_update_tambahan($id);
		$this->db->trans_complete();
		return $result;
	}
	

	
	function insert_update_tambahan($id)
	{
	
		for ($i=0; $i <= count($this->dataTambahan) - 1; $i++) {
			if (empty($this->dataTambahan[$i]['ID_SKPD']) || $this->dataTambahan[$i]['ID_SKPD'] == 'new') {
				unset($this->dataTambahan[$i]['ID_SKPD']);
				$this->dataTambahan[$i]['ID_SKPD'] = $id;
				$this->db->insert('SKPD_BIDANG_TAMBAHAN', $this->dataTambahan[$i]);
			}
			else
			{
			
				//$this->db->where('ID_SKPD', $this->dataTambahan[$i]['ID_SKPD']);
				//$this->db->where('ID_BIDANG', $this->dataTambahan[$i]['ID_BIDANG']);
				$this->db->update('SKPD_BIDANG_TAMBAHAN', $this->dataTambahan[$i]);
				//die ($this->db->last_query() );
				// $this->db->select('ID_BIDANG');
				// $this->db->from('SKPD_BIDANG_TAMBAHAN');
					// if ('ID_BIDANG')
						// {
						// return FALSE;
						// }
						// else
						// {
						// $this->db->where('ID_SKPD', $this->dataTambahan[$i]['ID_SKPD'] = $id);
						// $this->db->update('SKPD_BIDANG_TAMBAHAN', $this->dataTambahan[$i]);
						// }
			}
		}
	 
	}	
	
	function fill_data_bidangtambahan()
	{
		$ID_BIDANG = $this->input->post('ID_BIDANG');
		$ID_SKPD = $this->input->post('ID_SKPD');

		
		$this->data_bidangtambahan = array(
			'ID_BIDANG' => $ID_BIDANG,
			'ID_SKPD' => $ID_SKPD
		);
	
	}
	
	function fill_data_bidangtambahan_skpd()
	{
		$KODE_UNIT_KERJA = $this->input->post('KODE_UNIT_KERJA');
		$NAMA_UNIT_KERJA = $this->input->post('NAMA_UNIT_KERJA');
		$ID_SKPD_BIDANG = $this->session->userdata('ID_SKPD_BIDANG');

		
		$this->data_bidangtambahan_skpd = array(
			'KODE_UNIT_KERJA' => $KODE_UNIT_KERJA,
			'NAMA_UNIT_KERJA' => $NAMA_UNIT_KERJA,
			'ID_SKPD' => $ID_SKPD_BIDANG
		);
	
	}
	
	function check_bidang()
	{
		$bidang	= $this->input->post('ID_BIDANG');	
		$skpd	= $this->input->post('ID_SKPD');
		
		$this->db->trans_start();		
		$result = $this->db->query("SELECT * FROM SKPD_BIDANG_TAMBAHAN WHERE  id_skpd = '$skpd' AND id_bidang = '$bidang'")->result_array();		
		if(empty($result)){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		$this->db->trans_complete();	
	}
	
	
	
	function insert_data_bidangtambahan()
	{
		$this->db->trans_start();
		$insert = $this->db->insert('SKPD_BIDANG_TAMBAHAN', $this->data_bidangtambahan);
		return $insert;
		$this->db->trans_complete();
	}
	
	function insert_bidangtambahan_skpd()
	{
		$this->db->trans_start();
		$insert = $this->db->insert('UNIT_KERJA', $this->data_bidangtambahan_skpd);
		$newid = $this->db->query('select max(ID_UNIT_KERJA) as ID from UNIT_KERJA')->row_array();
		return $newid['ID'];
		$this->db->trans_complete();
	}
	
	function update_bidangtambahan_skpd()
	{
		$this->db->trans_start();
		$this->db->where('ID_UNIT_KERJA', $this->input->post('id'));
		$update = $this->db->update('UNIT_KERJA', $this->data_bidangtambahan_skpd);
		return $update;
		$this->db->trans_complete();
	}

	function check_dependency($id)
	{
		$this->db->trans_start();
		$this->db->select('a.ID_SKPD');
		$this->db->select('(select count(b.ID_SKPD) from FORM_ANGGARAN b where b.ID_SKPD = a.ID_SKPD) FORM_ANGGARAN_PAKAI');
		$this->db->select('(select count(b.ID_SKPD) from AKTIVITAS b where b.ID_SKPD = a.ID_SKPD) AKTIVITAS_PAKAI');		
		$this->db->select('(select count(b.ID_SKPD) from PEJABAT_SKPD b where b.ID_SKPD = a.ID_SKPD) PEJABAT_SKPD_PAKAI');
		$this->db->select('(select count(b.ID_SKPD) from SUMBER_DANA_SKPD b where b.ID_SKPD = a.ID_SKPD) SUMBER_DANA_SKPD_PAKAI');
		$this->db->select('(select count(b.ID_SKPD) from UNIT_KERJA b where b.ID_SKPD = a.ID_SKPD) UNIT_KERJA_PAKAI');
		$this->db->select('(select count(b.ID_SKPD) from SKPD_BIDANG_TAMBAHAN b where b.ID_SKPD = a.ID_SKPD) SKPD_BIDANG_TAMBAHAN_PAKAI');
		$this->db->where('a.ID_SKPD', $id);         
		$result = $this->db->get('SKPD a')->row_array();		
		$this->db->trans_complete();
		
		if ( 
        $result['FORM_ANGGARAN_PAKAI'] > 0 || 
        $result['AKTIVITAS_PAKAI'] > 0 ||
        $result['PEJABAT_SKPD_PAKAI'] > 0 ||
        $result['UNIT_KERJA_PAKAI'] > 0 ||
        $result['SKPD_BIDANG_TAMBAHAN_PAKAI'] > 0 ||
				$result['SUMBER_DANA_SKPD_PAKAI'] > 0 
		) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
	
	function delete_data($id){
		$this->db->trans_start();
		$this->db->where_in('ID_SKPD', $id);
		$delete = $this->db->delete('SKPD_BIDANG_TAMBAHAN');
		$this->db->where_in('ID_SKPD', $id);
		$delete = $this->db->delete('SKPD');
		//return $delete;
		$this->db->trans_complete();
	}
	
	function delete_data_bidangtambahan($id){
		$this->db->trans_start();
		$this->db->where_in('ID_BIDANG', $id);
		$this->db->where('ID_SKPD', $this->session->userdata('ID_SKPD_BIDANG'));
		$delete = $this->db->delete('SKPD_BIDANG_TAMBAHAN');
		return $delete;
		$this->db->trans_complete();
	}
	
	function delete_data_bidangtambahan_skpd($id){
		$this->db->trans_start();
		$this->db->where_in('ID_UNIT_KERJA', $id);
		$delete = $this->db->delete('UNIT_KERJA');
		return $delete;
		$this->db->trans_complete();
	}
	
	/* Pejabat SKPD */
	
	function get_data_pejabat($param,$ID_SKPD)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		
		$this->db->select(
			'*'		
			);
		$this->db->from('PEJABAT_SKPD as ps');
		$this->db->where('ps.ID_SKPD',$ID_SKPD);
		$result = $this->db->get();
		//die($this->db->last_query());
		
		return $result;
		$this->db->trans_complete();
		
	}
	
	function get_data_pejabat_by_id($ID_PEJABAT_SKPD)
	{
		$this->db->trans_start();
		$this->db->select('	* ');
		$this->db->from('PEJABAT_SKPD as ps ');
		$this->db->join('SKPD as s','s.ID_SKPD=ps.ID_SKPD','LEFT');
		$this->db->where('ps.ID_PEJABAT_SKPD', $ID_PEJABAT_SKPD);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
		$this->db->trans_complete();
	}
	function get_all_data_pejabat()
	{
		$this->db->trans_start();
		$this->db->select('	* ');
			$this->db->select('	* ');
		$this->db->from('PEJABAT_SKPD as ps ');
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
		// $this->db->trans_complete();
	}
	
	function fill_data_pejabat()
	{
		$JABATAN= $this->input->post('JABATAN'); $JABATAN=($JABATAN)?$JABATAN:null;
		$NAMA_PEJABAT= $this->input->post('NAMA_PEJABAT');$NAMA_PEJABAT=($NAMA_PEJABAT)?$NAMA_PEJABAT:null;
		$NIP = $this->input->post('NIP');$NIP=($NIP)?$NIP:null;
		$AKTIF=$this->input->post('AKTIF');$AKTIF=($AKTIF)?$AKTIF:null;
		$ID_SKPD=$this->input->post('id');$ID_SKPD= $ID_SKPD ? $ID_SKPD:null;
		
		//print_r($AKTIF);die();
		if($_POST['AKTIF'] == '')
		{
			$AKTIF=1;
		}else{
			$AKTIF=$this->input->post('AKTIF');
		}
		
		if($JABATAN == "Kepala SKPD"){
			$KODE_JABATAN = "KASKPD";
		}
		else if($JABATAN == "PPTK"){
			$KODE_JABATAN = "PPTK";		
		}
		else if($JABATAN == "Bendahara Pengeluaran"){
			$KODE_JABATAN = "BK";		
		}
		else if($JABATAN == "Bendahara Penerimaan"){
			$KODE_JABATAN = "BT";		
		}
		else{
			$KODE_JABATAN = null;
		}
		
		
		$this->data_pejabat = array(
			'JABATAN' => $JABATAN,
			'KODE_JABATAN' => $KODE_JABATAN,
			'NAMA_PEJABAT' => $NAMA_PEJABAT,
			'NIP' => $NIP,
			'AKTIF' => $AKTIF,
			'ID_SKPD' => $ID_SKPD
		);
		
		$this->data_pejabat_update = array(
			'JABATAN' => $JABATAN,
			'KODE_JABATAN' => $KODE_JABATAN,
			'NAMA_PEJABAT' => $NAMA_PEJABAT,
			'NIP' => $NIP,
			'AKTIF' => $AKTIF
		);
	}
	
	function insert_data_pejabat()
	{
		$this->fill_data_pejabat();
		$this->db->trans_start();
		$insert = $this->db->insert('PEJABAT_SKPD', $this->data_pejabat);
		$newid = $this->db->insert_id();
		$this->db->trans_complete();
		return $newid;
	}

	function update_data_pejabat($ID_PEJABAT_SKPD)
	{
		$data = $this->fill_data_pejabat();
		$this->db->trans_start();
		$this->db->where('ID_PEJABAT_SKPD', $ID_PEJABAT_SKPD);
		$update = $this->db->update('PEJABAT_SKPD', $this->data_pejabat_update);
		return $update;
		$this->db->trans_complete();
	}

	function delete_data_pejabat($id){
		$this->db->trans_start();
		$this->db->where('ID_PEJABAT_SKPD', $id);
		$delete = $this->db->delete('PEJABAT_SKPD');
		$this->db->trans_complete();
		return $delete;
	}
	
	function get_data_bidang($param)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmapSKPD ?
			if (array_key_exists($param['search_field'], $this->fieldmapSKPD)) {
				$wh = "UPPER(".$this->fieldmapSKPD[$param['search_field']].")";
				$param['search_str'] = strtoupper($param['search_str']);
				switch ($param['search_operator']) {
					case "bw": // begin with
						$wh .= " LIKE '".$param['search_str']."%'";
						break;
					case "cn": // contain %param%
						$wh .= " LIKE '%".$param['search_str']."%'";
						break;
					default :
						$wh = "";
				}
				$this->db->where($wh);
			}
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
		
		//returns the query string
		$this->db->trans_start();
		$result = $this->db->query("select 
			v.kode_skpd_lkp, v.nama_skpd,vb.nama_bidang,cast(list((vb.id_bidang)) as varchar(100)) id_bidang_tambahan, 
			cast(list('[' || vb.kode_bidang_lkp || ']' ) as varchar(5000))  bidang_tambahan
			from v_skpd v
			join skpd_bidang_tambahan sb on sb.id_skpd = v.id_skpd 
			join v_bidang vb on vb.id_bidang = sb.id_bidang
			where V.ID_SKPD = ".$this->session->userdata('ID_SKPD_BIDANG')."
			group by 1,2,3 order by bidang_tambahan")->result_array();
		
		$this->db->trans_complete();
		if(count($result)>0) {
			return $result;
		} 
	}
	
	function get_data_bidang_skpd($param)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmapSKPD ?
			//if (array_key_exists($param['search_field'], $this->fieldmapSKPD)) {
				$wh = "UPPER(".$this->fieldmapSKPD[$param['search_field']].")";
				$param['search_str'] = strtoupper($param['search_str']);
				switch ($param['search_operator']) {
					case "bw": // begin with
						$wh .= " LIKE '".$param['search_str']."%'";
						break;
					case "cn": // contain %param%
						$wh .= " LIKE '%".$param['search_str']."%'";
						break;
					default :
						$wh = "";
				}
				$this->db->where($wh);
			//}
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
		
		//returns the query string
		$this->db->trans_start();		
		$this->db->select('vb.id_unit_kerja,vb.kode_unit_kerja,vb.nama_unit_kerja,v.id_skpd');
		$this->db->from('v_skpd v');
		$this->db->join('unit_kerja vb','vb.id_skpd = v.id_skpd');
		$this->db->where('v.id_skpd',$this->session->userdata('ID_SKPD_BIDANG'));
		$this->db->order_by('vb.kode_unit_kerja');
		$result = $this->db->get()->result_array();
		$this->db->trans_complete();
		if(count($result)>0) {
			return $result;
		} 
	}
	
	
	/* Tambah Data Pejabat SKPD*/
	function check_dependency_pejabat($ID_PEJABAT_SKPD){
		$this->db->trans_start();
		$this->db->select('a.ID_PEJABAT_SKPD');
		$this->db->select('(select count(b.ID_PEJABAT_SKPD) from TIM_ANGGARAN b where b.ID_PEJABAT_SKPD = a.ID_PEJABAT_SKPD) TIM_ANGGARAN_PAKAI');
		$this->db->where('a.ID_PEJABAT_SKPD', $ID_PEJABAT_SKPD);
		$result = $this->db->get('PEJABAT_SKPD a')->row_array();
		$this->db->trans_complete();
		if ($result['TIM_ANGGARAN_PAKAI'] > 0 ) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
	
	function get_list_skpd($param)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        //($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		
		$this->db->select(
			"S.ID_SKPD, 
			(U.KODE_URUSAN||'.'||B.KODE_BIDANG||'.'||S.KODE_SKPD) as KODE,
			S.NAMA_SKPD
			"
			);
		$this->db->from('SKPD as S');
		$this->db->join('BIDANG as B', 'S.ID_BIDANG = B.ID_BIDANG', 'LEFT');
		$this->db->join('URUSAN as U', 'U.ID_URUSAN = B.ID_URUSAN', 'LEFT');
		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;
	}
	
	/* Data Pilih SKPD di RKA */
	function get_pilih_rka($param)
	{		
		$this->db->select('A.ID_SKPD ');
		$this->db->from('V_SKPD A');
		$this->db->join('FORM_ANGGARAN B','A.ID_SKPD=B.ID_SKPD');
		$this->db->where('B.TIPE', 'RKA');
		$this->db->where('B.STATUS', $this->session->userdata('STATUS_LOGIN'));
		$this->db->where('B.TAHUN', $this->session->userdata('TAHUN_LOGIN'));
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['ID_SKPD'];
			}
		}
		
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		
		$this->db->select("VS.ID_SKPD, VS.KODE_SKPD_LKP as kode, VS.NAMA_SKPD");
		$this->db->distinct("VS.ID_SKPD");
		$this->db->from("V_SKPD as VS");
		$this->db->join("V_FORM_ANGGARAN_LKP_7 as V", "V.ID_SKPD = VS.ID_SKPD");
		//$this->db->where("V.ID_SKPD is null");
		//$this->db->where("V.ID_SKPD is not null");
		if($hasil)
		{
			$this->db->where_not_in('V.ID_SKPD',$hasil);
		}
		$this->db->order_by("VS.KODE_SKPD_LKP");
		$result = $this->db->get();
		/*$result = $this->db->query("
				SELECT DISTINCT(S.ID_SKPD),s.KODE_SKPD_LKP as kode,s.NAMA_SKPD 
				FROM FORM_ANGGARAN FA
				JOIN V_SKPD S ON FA.ID_SKPD=S.ID_SKPD
				WHERE FA.ID_SKPD NOT IN
				(SELECT A.ID_SKPD FROM V_SKPD A JOIN FORM_ANGGARAN B ON A.ID_SKPD=B.ID_SKPD WHERE B.TIPE='RKA' AND B.TAHUN='".$this->session->userdata('TAHUN_LOGIN')."' AND B.STATUS='".$this->session->userdata('STATUS_LOGIN')."')
				ORDER BY S.KODE_SKPD_LKP
		");*/
		$this->db->trans_complete();
		return $result;
	}
	
	function get_pilih_rka1($param)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		
		$this->db->select(
			"VS.ID_SKPD, VS.KODE_SKPD_LKP as kode, VS.NAMA_SKPD"
			);
		$this->db->from("V_SKPD as VS");
		$this->db->join("V_FORM_ANGGARAN_LKP_7 as V", "V.ID_SKPD = VS.ID_SKPD AND V.TIPE = 'RKA1' AND V.TAHUN = '".$this->session->userdata('TAHUN_LOGIN')."' AND V.STATUS = '".$this->session->userdata('STATUS_LOGIN')."'", "LEFT");
		$this->db->where("V.ID_SKPD is null");
		$this->db->order_by("VS.KODE_SKPD_LKP");
		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;
	}
	
	function get_pilih_rka21($param)
	{		
		$this->db->select('ID_SKPD');
		$this->db->from('V_FORM_ANGGARAN_LKP_7');
		$this->db->where('TIPE', 'RKA21');
		$this->db->where('STATUS', $this->session->userdata('STATUS_LOGIN'));
		$this->db->where('TAHUN', $this->session->userdata('TAHUN_LOGIN'));
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['ID_SKPD'];
			}
		}
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();

		$this->db->select(
			"DISTINCT (VS.ID_SKPD), VS.KODE_SKPD_LKP as kode, VS.NAMA_SKPD"
			);
		$this->db->from("V_SKPD as VS");
		$this->db->join("V_FORM_ANGGARAN_LKP_7 as V", "V.ID_SKPD = VS.ID_SKPD ", "LEFT");
		//$this->db->where("V.ID_SKPD is null");
		//$this->db->where("V.ID_SKPD is not null");
		$this->db->order_by("VS.KODE_SKPD_LKP");
		if($hasil)
		{
			$this->db->where_not_in('VS.ID_SKPD',$hasil);
		}
		$result = $this->db->get();
		
		/*$result = $this->db->query("
				SELECT DISTINCT(S.ID_SKPD),s.KODE_SKPD_LKP as kode,s.NAMA_SKPD 
                        FROM V_SKPD S
                        WHERE S.ID_SKPD NOT IN
                        (SELECT B.ID_SKPD FROM FORM_ANGGARAN B WHERE B.TIPE='RKA21' AND B.TAHUN='".$this->session->userdata('TAHUN_LOGIN')."' AND B.STATUS='".$this->session->userdata('STATUS_LOGIN')."')
                        ORDER BY S.KODE_SKPD_LKP
		");*/
		$this->db->trans_complete();
		return $result;
	}
	
	function get_pilih_rka22($param)
	{		
		$this->db->select('ID_SKPD');
		$this->db->from('V_FORM_ANGGARAN_LKP_7');
		$this->db->where('TIPE', 'RKA22');
		$this->db->where('STATUS', $this->session->userdata('STATUS_LOGIN'));
		$this->db->where('TAHUN', $this->session->userdata('TAHUN_LOGIN'));
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['ID_SKPD'];
			}
		}
		
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		

		 
		$this->db->trans_start();		
		$this->db->select(
			'fa.ID_SKPD, s.KODE_SKPD_LKP, s.NAMA_SKPD'
			);
		$this->db->distinct('fa.ID_SKPD');
		$this->db->from("V_FORM_ANGGARAN_LKP_7 fa");
		$this->db->join("V_SKPD s","s.ID_SKPD = fa.ID_SKPD", "LEFT");
		$this->db->where('fa.TIPE','RKA221');
		$this->db->where('fa.STATUS', $this->session->userdata('STATUS_LOGIN'));
		$this->db->where('fa.TAHUN', $this->session->userdata('TAHUN_LOGIN'));
		if($hasil)
		{
			$this->db->where_not_in('fa.ID_SKPD',$hasil);
		}
		$this->db->order_by("s.KODE_SKPD_LKP");
		
		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;
		/*$result = $this->db->query("
				select distinct
				fa.ID_SKPD,
				s.KODE_SKPD_LKP as kode,
				s.NAMA_SKPD
				from V_FORM_ANGGARAN_LKP_7 fa
				left join V_SKPD s on s.ID_SKPD = fa.ID_SKPD
				where fa.tipe = 'RKA221'
				and fa.lanjutan IS NOT NULL
				and fa.ID_SKPD not in (select fa1.ID_SKPD from V_FORM_ANGGARAN_LKP_7 fa1 where fa1.TIPE = 'RKA22' and fa1.STATUS = '".$this->session->userdata('STATUS_LOGIN')."' AND fa1.TAHUN = ".$this->session->userdata('TAHUN_LOGIN').")
				and fa.STATUS = '".$this->session->userdata('STATUS_LOGIN')."'
				and fa.TAHUN = ".$this->session->userdata('TAHUN_LOGIN')."
		");*/
		
		//return $result;
	}
	
	function get_pilih_rka221($param)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		
		/*$this->db->select(
			"S.ID_SKPD, 
			(U.KODE_URUSAN||'.'||B.KODE_BIDANG||'.'||S.KODE_SKPD) as KODE,
			S.NAMA_SKPD
			"
			);
		$this->db->from('SKPD as S');
		$this->db->join('BIDANG as B', 'S.ID_BIDANG = B.ID_BIDANG', 'LEFT');
		$this->db->join('URUSAN as U', 'U.ID_URUSAN = B.ID_URUSAN', 'LEFT');*/
		$this->db->select("ID_SKPD, KODE_SKPD_LKP as KODE, NAMA_SKPD");
		$this->db->from('V_SKPD');
		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;
	}
	
	function get_pilih_rka31($param)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();

		$this->db->select(
			"VS.ID_SKPD, VS.KODE_SKPD_LKP as kode, VS.NAMA_SKPD"
			);
		$this->db->from("V_SKPD as VS");
		$this->db->join("V_FORM_ANGGARAN_LKP_7 as V", "V.ID_SKPD = VS.ID_SKPD AND V.TIPE = 'RKA31' AND V.TAHUN = '".$this->session->userdata('TAHUN_LOGIN')."' AND V.STATUS = '".$this->session->userdata('STATUS_LOGIN')."'", "LEFT");
		$this->db->where("V.ID_SKPD is null");
		$this->db->order_by("VS.KODE_SKPD_LKP");
		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;
	}
	
	function get_pilih_rka32($param)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();

		$this->db->select(
			"VS.ID_SKPD, VS.KODE_SKPD_LKP as kode, VS.NAMA_SKPD"
			);
		$this->db->from("V_SKPD as VS");
		$this->db->join("V_FORM_ANGGARAN_LKP_7 as V", "V.ID_SKPD = VS.ID_SKPD AND V.TIPE = 'RKA32' AND V.TAHUN = '".$this->session->userdata('TAHUN_LOGIN')."' AND V.STATUS = '".$this->session->userdata('STATUS_LOGIN')."'", "LEFT");
		$this->db->where("V.ID_SKPD is null");
		$this->db->order_by("VS.KODE_SKPD_LKP");
		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;
	}
	
	/* Pilih SKPD */
	
	function get_data_subskpd($param)
	{
		
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
		
		$where_filter = $this->session->userdata('tmp_filter_skpd');
		
		if(count($where_filter)>0)
			{	
				if( isset($where_filter['in_teks_skpd']) && $where_filter['in_teks_skpd'] != null)
					{
					if($where_filter['op_teks_skpd']=='KODE_SKPD_LKP')
						{
							if($where_filter['op_filter_skpd']=='bw') 
							{
							$this->db->like('lower(vs.KODE_SKPD_LKP)', strtolower($where_filter['in_teks_skpd']), 'after'); 
							}
							else 
							{
							$this->db->like('lower(vs.KODE_SKPD_LKP)', strtolower($where_filter['in_teks_skpd']), 'both');
							}
						}
					else if($where_filter['op_teks_skpd']=='NAMA_SKPD')
						{
							if($where_filter['op_filter_skpd']=='bw') 
							{
							$this->db->like('lower(vs.NAMA_SKPD)', strtolower($where_filter['in_teks_skpd']), 'after'); 
							}
							else
							{
							$this->db->like('lower(vs.NAMA_SKPD)', strtolower($where_filter['in_teks_skpd']), 'both');
							}
						}
					}
			}
		
		
		
		//$this->db->trans_start();
		$this->db->select('*');
		$this->db->from('V_SKPD vs');
		$this->db->order_by('vs.KODE_SKPD_LKP');		
		$result = $this->db->get();
		return $result;
		
		//$this->db->trans_complete();
	}
	
	function get_pilih_dpa221($param)
	{		
		/*$this->db->select('ID_FORM_ANGGARAN_RKA');
		$this->db->from('V_FORM_ANGGARAN_LKP_7');
		$this->db->where('TIPE', 'DPA221');
		$this->db->where('LANJUTAN', '0');
		$this->db->where('STATUS', $this->session->userdata('STATUS_LOGIN'));
		$this->db->where('TAHUN', $this->session->userdata('TAHUN_LOGIN'));
		$this->db->where('ID_FORM_ANGGARAN_RKA is not null');
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['ID_FORM_ANGGARAN_RKA'];
			}
		}*/
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		
		$this->db->select(
			" distinct S.ID_SKPD, 
			S.KODE_SKPD_LKP as KODE,
			S.NAMA_SKPD
			"
			);
		$this->db->from('V_FORM_ANGGARAN_LKP_7 as FA');
		$this->db->join('V_SKPD as S', 'S.ID_SKPD = FA.ID_SKPD');
		$this->db->where('FA.TIPE', 'RKA221');
		$this->db->where('FA.LANJUTAN', '0');
		$this->db->where('FA.STATUS', $this->session->userdata('STATUS_LOGIN'));
		$this->db->where('FA.TAHUN', $this->session->userdata('TAHUN_LOGIN'));
		$this->db->where('FA.ID_FORM_ANGGARAN_DPA is null');
		/*if($hasil)
		{
			$this->db->where_not_in('FA.ID_FORM_ANGGARAN',$hasil);
		}*/

		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;
	}
	
	function get_pilih_dpa22($param)
	{		
		$this->db->select('ID_FORM_ANGGARAN_RKA');
		$this->db->from('V_FORM_ANGGARAN_LKP_7');
		$this->db->where('TIPE', 'DPA22');
		$this->db->where('STATUS', $this->session->userdata('STATUS_LOGIN'));
		$this->db->where('TAHUN', $this->session->userdata('TAHUN_LOGIN'));
		$this->db->where('ID_FORM_ANGGARAN_RKA is not null');
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['ID_FORM_ANGGARAN_RKA'];
			}
		}
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		$this->db->trans_start();
		$this->db->select(
			'fa.ID_SKPD, s.KODE_SKPD_LKP as kode, s.NAMA_SKPD,fa.ID_FORM_ANGGARAN as form_anggaran'
			);
		$this->db->from("V_FORM_ANGGARAN_LKP_7 fa");
		$this->db->join("V_SKPD s","s.ID_SKPD = fa.ID_SKPD", "LEFT");
		$this->db->where('fa.TIPE','RKA22');
		$this->db->where('fa.STATUS', $this->session->userdata('STATUS_LOGIN'));
		$this->db->where('fa.TAHUN', $this->session->userdata('TAHUN_LOGIN'));
		if($hasil)
		{
			$this->db->where_not_in('fa.ID_FORM_ANGGARAN',$hasil);
		}
		$this->db->order_by("s.KODE_SKPD_LKP");
		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;
		/*$result = $this->db->query("
				select 
				fa.ID_SKPD,
				s.KODE_SKPD_LKP as kode,
				s.NAMA_SKPD,
				fa.ID_FORM_ANGGARAN as form_anggaran
				from V_FORM_ANGGARAN_LKP_7 fa
				left join V_SKPD s on s.ID_SKPD = fa.ID_SKPD
				where fa.tipe = 'RKA22'
				and fa.ID_FORM_ANGGARAN not in (select fa1.ID_FORM_ANGGARAN_RKA from V_FORM_ANGGARAN_LKP_7 fa1 where fa1.ID_FORM_ANGGARAN_RKA is not null and fa1.TIPE = 'DPA22')
				and fa.STATUS = '".$this->session->userdata('STATUS_LOGIN')."'
				and fa.TAHUN = ".$this->session->userdata('TAHUN_LOGIN')."
				order by s.KODE_SKPD_LKP
		");
		return $result;
		$this->db->trans_start();

		$this->db->select(
			"VS.ID_SKPD, VS.KODE_SKPD_LKP as kode, VS.NAMA_SKPD, V.ID_FORM_ANGGARAN AS FORM_ANGGARAN"
			);
		$this->db->from("V_FORM_ANGGARAN_LKP_7 as V");
		$this->db->join("V_SKPD as VS", "V.ID_SKPD = VS.ID_SKPD", "LEFT");
		$this->db->where('V.TIPE','RKA22');
		$this->db->where('V.TAHUN',$this->session->userdata('TAHUN_LOGIN'));
		$this->db->where('V.STATUS',$this->session->userdata('STATUS_LOGIN'));
		//$this->db->where("V.ID_SKPD is null");
		//$this->db->where("V.ID_SKPD is not null");
		if($hasil)
		{
			$this->db->where_not_in('V.ID_FORM_ANGGARAN',$hasil);
		}

		$this->db->order_by("VS.KODE_SKPD_LKP");
		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;*/
	}
	
	function get_pilih_dpa21($param)
	{		
		$this->db->select('ID_FORM_ANGGARAN_RKA');
		$this->db->from('V_FORM_ANGGARAN_LKP_7');
		$this->db->where('TIPE', 'DPA21');
		$this->db->where('STATUS', $this->session->userdata('STATUS_LOGIN'));
		$this->db->where('TAHUN', $this->session->userdata('TAHUN_LOGIN'));
		$this->db->where('ID_FORM_ANGGARAN_RKA is not null');
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['ID_FORM_ANGGARAN_RKA'];
			}
		}
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        //($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();

		$this->db->select(
			"VS.ID_SKPD, VS.KODE_SKPD_LKP as kode, VS.NAMA_SKPD, V.ID_FORM_ANGGARAN AS FORM_ANGGARAN"
			);
		$this->db->from("V_FORM_ANGGARAN_LKP_7 as V");
		$this->db->join("V_SKPD as VS", "V.ID_SKPD = VS.ID_SKPD", "LEFT");
		$this->db->where('V.TIPE','RKA21');
		$this->db->where('V.TAHUN',$this->session->userdata('TAHUN_LOGIN'));
		$this->db->where('V.STATUS',$this->session->userdata('STATUS_LOGIN'));
		//$this->db->where("V.ID_SKPD is null");
		//$this->db->where("V.ID_SKPD is not null");
		if($hasil)
		{
			$this->db->where_not_in('V.ID_FORM_ANGGARAN',$hasil);
		}

		$this->db->order_by("VS.KODE_SKPD_LKP");
		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;
	}
	
	function get_pilih_dpa1($param)
	{		
		$this->db->select('ID_FORM_ANGGARAN_RKA');
		$this->db->from('V_FORM_ANGGARAN_LKP_7');
		$this->db->where('TIPE', 'DPA1');
		$this->db->where('STATUS', $this->session->userdata('STATUS_LOGIN'));
		$this->db->where('TAHUN', $this->session->userdata('TAHUN_LOGIN'));
		$this->db->where('ID_FORM_ANGGARAN_RKA is not null');
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['ID_FORM_ANGGARAN_RKA'];
			}
		}
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        //($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		
		$this->db->select(
			"VS.ID_SKPD, VS.KODE_SKPD_LKP as kode, VS.NAMA_SKPD, V.ID_FORM_ANGGARAN AS FORM_ANGGARAN"
			);
		/*$this->db->from("V_SKPD as VS");
		$this->db->join("FORM_ANGGARAN as V", "V.ID_SKPD = VS.ID_SKPD", "LEFT");
		$this->db->where("V.TIPE = 'RKA1' AND V.TAHUN = '".$this->session->userdata('TAHUN_LOGIN')."' AND V.STATUS = '".$this->session->userdata('STATUS_LOGIN')."' AND V.ID_ANGGARAN_GLOBAL is null");*/
		
		$this->db->from("V_FORM_ANGGARAN_LKP_7 as V");
		$this->db->join("V_SKPD as VS", "V.ID_SKPD = VS.ID_SKPD", "LEFT");
		$this->db->where('V.TIPE','RKA1');
		$this->db->where('V.TAHUN',$this->session->userdata('TAHUN_LOGIN'));
		$this->db->where('V.STATUS',$this->session->userdata('STATUS_LOGIN'));
		if($hasil)
		{
			$this->db->where_not_in('V.ID_FORM_ANGGARAN',$hasil);
		}
		$this->db->order_by("VS.KODE_SKPD_LKP");
		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;
	}
	
	function get_pilih_dpa31($param)
	{		
		$this->db->select('ID_FORM_ANGGARAN_RKA');
		$this->db->from('V_FORM_ANGGARAN_LKP_7');
		$this->db->where('TIPE', 'DPA31');
		$this->db->where('STATUS', $this->session->userdata('STATUS_LOGIN'));
		$this->db->where('TAHUN', $this->session->userdata('TAHUN_LOGIN'));
		$this->db->where('ID_FORM_ANGGARAN_RKA is not null');
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['ID_FORM_ANGGARAN_RKA'];
			}
		}
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        //($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();

		$this->db->select(
			"VS.ID_SKPD, VS.KODE_SKPD_LKP as kode, VS.NAMA_SKPD, V.ID_FORM_ANGGARAN AS FORM_ANGGARAN"
			);
		$this->db->from("V_FORM_ANGGARAN_LKP_7 as V");
		$this->db->join("V_SKPD as VS", "V.ID_SKPD = VS.ID_SKPD", "LEFT");
		$this->db->where('V.TIPE','RKA31');
		$this->db->where('V.TAHUN',$this->session->userdata('TAHUN_LOGIN'));
		$this->db->where('V.STATUS',$this->session->userdata('STATUS_LOGIN'));
		//$this->db->where("V.ID_SKPD is null");
		if($hasil)
		{
			$this->db->where_not_in('V.ID_FORM_ANGGARAN',$hasil);
		}
		$this->db->order_by("VS.KODE_SKPD_LKP");
		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;
	}
	
	function get_pilih_dpa32($param)
	{		
		$this->db->select('ID_FORM_ANGGARAN_RKA');
		$this->db->from('FORM_ANGGARAN');
		$this->db->where('TIPE', 'DPA32');
		$this->db->where('STATUS', $this->session->userdata('STATUS_LOGIN'));
		$this->db->where('TAHUN', $this->session->userdata('TAHUN_LOGIN'));
		$this->db->where('ID_FORM_ANGGARAN_RKA is not null');
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['ID_FORM_ANGGARAN_RKA'];
			}
		}
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        //($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();

		$this->db->select(
			"VS.ID_SKPD, VS.KODE_SKPD_LKP as kode, VS.NAMA_SKPD, V.ID_FORM_ANGGARAN AS FORM_ANGGARAN"
			);
		$this->db->from("V_FORM_ANGGARAN_LKP_7 as V");
		$this->db->join("V_SKPD as VS", "V.ID_SKPD = VS.ID_SKPD", "LEFT");
		$this->db->where('V.TIPE','RKA32');
		$this->db->where('V.TAHUN',$this->session->userdata('TAHUN_LOGIN'));
		$this->db->where('V.STATUS',$this->session->userdata('STATUS_LOGIN'));
		if($hasil)
		{
			$this->db->where_not_in('V.ID_FORM_ANGGARAN',$hasil);
		}
		$this->db->order_by("VS.KODE_SKPD_LKP");
		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;
	}
	
	function get_pilih_dpa($param)
	{		
		$this->db->select('ID_FORM_ANGGARAN_RKA');
		$this->db->from('V_FORM_ANGGARAN_LKP_7');
		$this->db->where('TIPE', 'DPA');
		$this->db->where('STATUS', $this->session->userdata('STATUS_LOGIN'));
		$this->db->where('TAHUN', $this->session->userdata('TAHUN_LOGIN'));
		$this->db->where('ID_FORM_ANGGARAN_RKA is not null');
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['ID_FORM_ANGGARAN_RKA'];
			}
		}
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        //($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		
		$this->db->select(
			"VS.ID_SKPD, VS.KODE_SKPD_LKP as kode, VS.NAMA_SKPD, V.ID_PEJABAT_SKPD as PEJABAT_SKPD, V.ID_FORM_ANGGARAN as FORM_ANGGARAN"
			);
		$this->db->from("V_FORM_ANGGARAN_LKP_7 as V");
		$this->db->join("V_SKPD as VS", "V.ID_SKPD = VS.ID_SKPD", "LEFT");
		$this->db->where('V.TIPE','RKA');
		$this->db->where('V.TAHUN',$this->session->userdata('TAHUN_LOGIN'));
		$this->db->where('V.STATUS',$this->session->userdata('STATUS_LOGIN'));
		//$this->db->where("V.ID_SKPD is not null");
		if($hasil)
		{
			$this->db->where_not_in('V.ID_FORM_ANGGARAN',$hasil);
		}
		$this->db->order_by("VS.KODE_SKPD_LKP");
		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;
	}
	
	function cek_murni($skpd, $kegiatan)
	{
		$this->db->select('f.ID_FORM_ANGGARAN');
		$this->db->from('FORM_ANGGARAN f');
		$this->db->join('RKA_DPA_SKPD_221 rk', 'rk.ID_FORM_ANGGARAN = f.ID_FORM_ANGGARAN', 'left');
		$this->db->where('f.ID_SKPD', $skpd);
		$this->db->where('f.STATUS', 'Murni');
		$this->db->where('f.TIPE', 'RKA221');
		$this->db->where('rk.ID_KEGIATAN', $kegiatan);
		$result = $this->db->get()->row_array();
		if(count($result) > 0)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_pilih_dpalanjut($param)
	{		
		$this->db->select('ID_FORM_ANGGARAN_RKA');
		$this->db->from('V_FORM_ANGGARAN_LKP_7');
		$this->db->where('TIPE', 'DPA221');
		$this->db->where('LANJUTAN', '1');
		$this->db->where('STATUS', $this->session->userdata('STATUS_LOGIN'));
		$this->db->where('TAHUN', $this->session->userdata('TAHUN_LOGIN'));
		$this->db->where('ID_FORM_ANGGARAN_RKA is not null');
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['ID_FORM_ANGGARAN_RKA'];
			}
		}
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->select(
			" distinct S.ID_SKPD, 
			S.KODE_SKPD_LKP as KODE,
			S.NAMA_SKPD
			"
			);
		$this->db->from('V_FORM_ANGGARAN_LKP_7 as FA');
		$this->db->join('V_SKPD as S', 'S.ID_SKPD = FA.ID_SKPD');
		$this->db->where('FA.TIPE', 'RKA221');
		$this->db->where('FA.LANJUTAN', '1');
		$this->db->where('FA.STATUS', $this->session->userdata('STATUS_LOGIN'));
		$this->db->where('FA.TAHUN', $this->session->userdata('TAHUN_LOGIN'));
		if($hasil)
		{
			$this->db->where_not_in('FA.ID_FORM_ANGGARAN',$hasil);
		}
		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;
	}
	
	function get_list_skpd_tim($param)
	{		
		$this->db->select('distinct(ID_SKPD)');
		$this->db->from('TIM_ANGGARAN');
		$this->db->where('ID_SKPD is not null');
		$this->db->where('STATUS', $this->session->userdata('STATUS_LOGIN'));
		$this->db->where('TAHUN', $this->session->userdata('TAHUN_LOGIN'));
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['ID_SKPD'];
			}
		}
		
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        //($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		
		$this->db->select("S.ID_SKPD,S.KODE_SKPD_LKP,S.NAMA_SKPD");
		$this->db->from('V_SKPD as S');
		if($hasil)
		{
			$this->db->where_not_in('S.ID_SKPD',$hasil);
		}
		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;
	}
	
	function get_pilih_kua($param)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        //($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		
		$this->db->select("ID_SKPD,NAMA_SKPD,KODE_SKPD_LKP");
		$this->db->from("V_SKPD");
		$this->db->order_by("KODE_SKPD_LKP");
		$result = $this->db->get();
		$this->db->trans_complete();
		return $result;
	}
}
?>