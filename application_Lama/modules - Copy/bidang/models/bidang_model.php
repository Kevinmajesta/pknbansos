<?php
class Bidang_model extends CI_Model {
	
	var $data;
	var $fieldmap = array();
	
	 function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->_table='BIDANG';
		$this->_pk='ID_BIDANG';
		
		$this->fieldmap = array(
			'id' => 'ID_BIDANG',
			'urusan' => 'ID_URUSAN',
			'idfungsi' => 'ID_FUNGSI',
			'bidang' => 'KODE_BIDANG',
			'nama' => 'NAMA_BIDANG'
		);
	}
	function fill_data()
	{
		$idfungsi = $this->input->post('idfungsi'); $idfungsi = $idfungsi ? $idfungsi:null;
		$urusan= $this->input->post('urusan'); $urusan = $urusan ? $urusan:null;
		$bidang= $this->input->post('bidang'); $bidang = $bidang ? $bidang:null;
		$nama = $this->input->post('nama'); $nama = $nama ? $nama:null;
		
		foreach($this->fieldmap as $key => $value){
			if(isset($$key))
				$this->data[$value] = $$key;
		}
	}
  
  function get_data($param, $isCount=FALSE, $CompileOnly=False)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			
				$wh = "UPPER(".$param['search_field'].")";
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
		// ($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        // ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
		
		//returns the query string
		$this->db->trans_start();		
		$this->db->select(
			'F.ID_FUNGSI, 
			F.KODE_FUNGSI, 
			F.NAMA_FUNGSI,     
			U.ID_URUSAN, 
			U.KODE_URUSAN,    
			B.ID_BIDANG, 
			B.KODE_BIDANG, 
			B.NAMA_BIDANG'
		);
		$this->db->from('FUNGSI as F');
		$this->db->join('BIDANG as B', 'F.ID_FUNGSI = B.ID_FUNGSI ', 'LEFT OUTER');
		$this->db->join('URUSAN as U', 'U.ID_URUSAN = B.ID_URUSAN', 'LEFT OUTER'); 

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
        $this->db->order_by('F.KODE_FUNGSI,U.KODE_URUSAN,B.KODE_BIDANG');
        $result = $this->db->get()->result_array();
        return $result;
      }
    }
		$this->db->trans_complete();
	}
	
	function get_parent($id)
	{
		$this->db->select('
					ID_FUNGSI,
					KODE_FUNGSI,
					NAMA_FUNGSI
				');
		$this->db->where('ID_FUNGSI', $id);
		$result = $this->db->get('FUNGSI')->row_array();
		//die ($this->db->last_query() );
		return $result;
	}
	
	function get_data_bidang($param)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			//if (array_key_exists($param['search_field'], $this->fieldmap)) {
				$wh = "UPPER(".$param['search_field'].")";
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
		
		$this->db->select(
			'ID_BIDANG,
			KODE_BIDANG_LKP, 
			NAMA_BIDANG'
		);
		$this->db->from('V_BIDANG');
		$result = $this->db->get()->result_array();
		$this->db->trans_complete();
		if(count($result)>0) {
			return $result;
		} else {
			return FALSE;
		}	
	}
	
	function get_data_by_id($id)
	{
		$this->db->trans_start();
		
		$this->db->select(
				'b.*,
				u.ID_URUSAN,
				u.KODE_URUSAN,
				u.NAMA_URUSAN,
				f.ID_FUNGSI,
				f.KODE_FUNGSI,
				f.NAMA_FUNGSI'
		);
		$this->db->from('BIDANG as b');
		$this->db->join('URUSAN as u', 'u.ID_URUSAN = b.ID_URUSAN', 'LEFT');
		$this->db->join('FUNGSI as f', 'f.ID_FUNGSI = b.ID_FUNGSI', 'LEFT');
		$this->db->where('ID_BIDANG', $id);
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
		$result = $this->db->insert($this->_table, $this->data);
		$newid = $this->db->query('select max(ID_BIDANG) as ID from BIDANG')->row_array();
		$this->db->trans_complete();
		return $newid['ID'];
	}

	function update_data($id)
	{
		$this->fill_data();
		$data = $this->fill_data();
		$this->db->trans_start();
		$this->db->where($this->_pk, $id);
		$update = $this->db->update($this->_table, $this->data);
		$this->db->trans_complete();
		return $update;
	}

	function delete_data($id){
		$this->db->trans_start();
		$this->db->where($this->_pk, $id);
		$delete = $this->db->delete($this->_table);
		$this->db->trans_complete();
		return $delete;
	}
	
	
	function check_dependency($ID_BIDANG)
	{
		$this->db->trans_start();
		$this->db->select('a.ID_BIDANG');
		$this->db->select('(select count(b.ID_BIDANG) from V_SKPD b where b.ID_BIDANG = a.ID_BIDANG) V_SKPD_PAKAI');
		$this->db->select('(select count(b.ID_BIDANG) from SKPD b where b.ID_BIDANG = a.ID_BIDANG) SKPD_PAKAI');
		$this->db->select('(select count(b.ID_BIDANG) from PROGRAM b where b.ID_BIDANG = a.ID_BIDANG) PROGRAM_PAKAI');
		$this->db->select('(select count(b.ID_BIDANG) from SKPD_BIDANG_TAMBAHAN b where b.ID_BIDANG = a.ID_BIDANG) SKPD_BIDANG_TAMBAHAN_PAKAI');
				
		$this->db->where('a.ID_BIDANG', $ID_BIDANG);
		$result = $this->db->get('BIDANG a')->row_array();		
		$this->db->trans_complete();
		
		if ($result['V_SKPD_PAKAI'] > 0 || $result['SKPD_PAKAI'] > 0 || $result['PROGRAM_PAKAI'] > 0 || $result['SKPD_BIDANG_TAMBAHAN_PAKAI'] > 0 ) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
	
	function get_urusan()
	{
		$return = array(
		''=>''
		); 
		$query = 
			'Select u.ID_URUSAN, u.KODE_URUSAN
			from URUSAN as u		
			';
			
		$return = $this->db->query($query);
		return $return;
	}
	
	function get_opt_urusan()
	{
		$return = array(
		''=>''
		); 
		
		$result = $this->get_urusan();
		
		foreach($result->result_array() as $row)
		{
			$return[$row['ID_URUSAN']]=$row['KODE_URUSAN'];
		}
				
		return $return;	
	}
	
	function check_data()
	{
		$fungsi	= $this->input->post('idfungsi');	$fungsi=($fungsi)?$fungsi:null;
		$urusan	= $this->input->post('urusan');	$urusan=($urusan)?$urusan:null;
		$bidang	= $this->input->post('bidang');	$bidang=($bidang)?$bidang:null;
		
		$this->db->trans_start();
		$query = "SELECT * FROM BIDANG WHERE  id_fungsi = '$fungsi' AND id_urusan = '$urusan' AND kode_bidang = '$bidang'";		
		$result = $this->db->query($query);
		
		if($result->num_rows() > 0){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		$this->db->trans_complete();	
	}
	
	function check_data_bidang()
	{
		$urusan	= $this->input->post('urusan');	$urusan=($urusan)?$urusan:null;
		$bidang	= $this->input->post('bidang');	$bidang=($bidang)?$bidang:null;
		
		$this->db->trans_start();
		$query = "SELECT * FROM BIDANG WHERE id_urusan = '$urusan' AND kode_bidang = '$bidang'";
		$result = $this->db->query($query)->result_array();
		
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
		$fungsi	= $this->input->post('idfungsi');	$fungsi=($fungsi)?$fungsi:null;
		$urusan	= $this->input->post('urusan');	$urusan=($urusan)?$urusan:null;
		$bidang	= $this->input->post('bidang');	$bidang=($bidang)?$bidang:null;
		
		$id_bid = $this->db->query("select KODE_BIDANG as ID_BID, ID_URUSAN as ID_UR, ID_FUNGSI as ID_FU from BIDANG WHERE ID_BIDANG='".$this->input->post('id')."'")->row_array();
		
		
		$this->db->select('KODE_BIDANG');
		$this->db->from('BIDANG');
		$this->db->where('ID_FUNGSI', $this->input->post('idfungsi'));
		$this->db->where('ID_URUSAN', $this->input->post('urusan'));
		$this->db->where('KODE_BIDANG <>', $this->input->post('bidang'));
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['KODE_BIDANG'];
			}
		}		
				
		if($bidang == $id_bid['ID_BID']){
			$this->db->select('*');
			$this->db->from('BIDANG');
			$this->db->where('ID_FUNGSI', $this->input->post('idfungsi'));
			$this->db->where('ID_URUSAN', $this->input->post('urusan'));
			$this->db->where('KODE_BIDANG', $id_bid['ID_BID']);
			$ada = $this->db->get()->result_array();
			if(count($ada) > 0)
			{
				return TRUE;
			}				
		}
		else if($bidang <> $id_bid['ID_BID']){
			$this->db->select('*');
			$this->db->from('BIDANG');
			$this->db->where('ID_FUNGSI', $this->input->post('idfungsi'));
			$this->db->where('ID_URUSAN', $this->input->post('urusan'));
			$this->db->where('KODE_BIDANG', $bidang);
			$ada = $this->db->get()->result_array();
			if(count($ada) > 0)
			{
				return FALSE;
			}	
			else{
				return TRUE;
			}	
		}
	}

}
?>