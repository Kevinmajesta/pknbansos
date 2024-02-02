<?php
class Lokasi_model extends CI_Model {
	
	var $data;
	var $data_sub;
	var $data_kampung;
	var $data_rw;
	var $data_rt;
	var $fieldmap = array();
	
	function __construct()
  {
    // Call the Model constructor
    parent::__construct();
		$this->_table='LOKASI';
		$this->_pk='ID_LOKASI';
		
		$this->fieldmap = array(
			'id' => 'ID_LOKASI',
			'idparent' => 'ID_PARENT_LOKASI',
			'lokasi' => 'LOKASI',
			'level' => 'LEVEL_LOKASI'
		);
	}
	function fill_data()
	{
		$lokasi= $this->input->post('lokasi'); $lokasi=($lokasi)?$lokasi:null;

		foreach($this->fieldmap as $key => $value){
			switch ($key){
				case 'LEVEL_LOKASI' : $$key = '1'; break;
				case 'id' : $$key = $this->input->post($key) ? ($this->input->post($key)) : NULL; $$key == 'new' ? $$key = NULL : '';break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			if(isset($$key))
				$this->data[$value] = $$key;
		}
	}
	
	function get_data($param)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			if (array_key_exists($param['search_field'], $this->fieldmap)) {
				$wh = "UPPER(".$this->fieldmap[$param['search_field']].")";
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
		$this->db->where('ID_PARENT_LOKASI',null);
		$result = $this->db->get($this->_table)->result_array();
		
		$this->db->trans_complete();
		if(count($result)>0) {
			return $result;
		} else {
			return FALSE;
		}
	}
	
	function get_data2($param)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			if (array_key_exists($param['search_field'], $this->fieldmap)) {
				$wh = "UPPER(".$this->fieldmap[$param['search_field']].")";
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
		//($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        //($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		
		if($param['search'] == 'true'){
			$this->db->select("r.ID_LOKASI, r.LOKASI,r.LEVEL_LOKASI");
			$this->db->from('LOKASI r');
			$this->db->where('r.ID_LOKASI <>',0);
			$result = $this->db->get()->result_array();
			return $result;
		}
		else{
			
			$query = $this->db->query("
				WITH RECURSIVE t AS (
					SELECT ID_LOKASI, LOKASI, LEVEL_LOKASI FROM LOKASI WHERE ID_PARENT_LOKASI IS NULL
				UNION ALL
					SELECT t1.ID_LOKASI, t1.LOKASI, t1.LEVEL_LOKASI FROM t JOIN LOKASI t1 ON (t1.ID_PARENT_LOKASI = t.ID_LOKASI)
				)
				SELECT * FROM t
			");
			return $query->result_array();
		}
		$this->db->trans_complete();
	}
	
	function get_data_sublokasi($param,$ID_LOKASI)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			if (array_key_exists($param['search_field'], $this->fieldmap)) {
				$wh = "UPPER(".$this->fieldmap[$param['search_field']].")";
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
		$this->db->select('*');
		$this->db->from('LOKASI');
		$this->db->where('ID_PARENT_LOKASI',$ID_LOKASI);
		$result = $this->db->get();
		return $result;
		$this->db->trans_complete();
		
	}
	
	function get_data_sublokasi_kampung($param,$ID_LOKASI)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			if (array_key_exists($param['search_field'], $this->fieldmap)) {
				$wh = "UPPER(".$this->fieldmap[$param['search_field']].")";
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
		$this->db->select('*');
		$this->db->from('LOKASI');
		$this->db->where('ID_PARENT_LOKASI',$ID_LOKASI);
		$result = $this->db->get();
		return $result;
		$this->db->trans_complete();
		
	}
	
	function get_data_by_id($id)
	{
		$this->db->trans_start();
		$this->db->where_in($this->_pk, $id);
		$result = $this->db->get($this->_table)->row_array();
		$this->db->trans_complete();
		if(count($result) > 0)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	
/* 	function get_data_by_duplikasi($str)
	{
		$this->db->trans_start();
		$this->db->select('LOKASI');
		$this->db->where_in('LOKASI', $id);
		$result = $this->db->get($this->_table)->row_array();die($this->db->last_query() );
		$this->db->trans_complete();
		if(count($result) > 0)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
 */	
	function insert_data()
	{
		$this->fill_data();
		$this->db->trans_start();
		$insert = $this->db->insert($this->_table, $this->data);
		$newid = $id = $this->db->query('select max(ID_LOKASI) as ID from LOKASI')->row_array();
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
	
	function check_dependency($id){
		$this->db->trans_start();
		$this->db->select('a.ID_LOKASI');
		$this->db->select('(select count(b.ID_LOKASI) from LOKASI_KEGIATAN b where b.ID_LOKASI = a.ID_LOKASI) LOKASI_KEGIATAN_PAKAI');
		$this->db->where('a.ID_LOKASI', $id);
		$result = $this->db->get('LOKASI a')->row_array();
		$this->db->trans_complete();
		if ($result['LOKASI_KEGIATAN_PAKAI'] > 0 ) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
	///////////////////////////////////////////// Sub Lokasi ///////////////////////////////////////////////////
	function fill_data_sublokasi()
	{
		$idparent = $this->input->post('idparent'); $idparent=($idparent)?$idparent:null;
		$lokasi= $this->input->post('lokasi'); $lokasi=($lokasi)?$lokasi:null;

		foreach($this->fieldmap as $key => $value){
			switch ($key){
				case 'LEVEL_LOKASI' : $$key = '2'; break;
				case 'id' : $$key = $this->input->post($key) ? ($this->input->post($key)) : NULL; $$key == 'new' ? $$key = NULL : '';break;	
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			if(isset($$key))
				$this->data_sub[$value] = $$key;
		}
	}
	
	function insert_data_sublokasi()
	{
		$this->fill_data_sublokasi();
		$this->db->trans_start();
		$insert = $this->db->insert($this->_table, $this->data_sub);
		$newid = $this->db->query('select max(ID_LOKASI) as ID from LOKASI')->row_array();
		$this->db->trans_complete();
		return $newid['ID'];
	}
	
	function update_data_sublokasi($id)
	{		
		//$lokasi= $this->input->post('lokasi'); $lokasi=($lokasi)?$lokasi:null;
		$this->fill_data_sublokasi();
		$this->db->trans_start();
		$this->db->where($this->_pk, $id);
		$this->db->update($this->_table, $this->data_sub);
		$this->db->trans_complete();	
	}
	///////////////////////////////////////////// End Sub Lokasi ///////////////////////////////////////////////////
	///////////////////////////////////////////// Kampung ///////////////////////////////////////////////////
	function fill_data_sublokasi_kampung()
	{
		$idparent = $this->input->post('idparent'); $idparent=($idparent)?$idparent:null;
		$lokasi= $this->input->post('lokasi'); $lokasi=($lokasi)?$lokasi:null;

		foreach($this->fieldmap as $key => $value){
		switch ($key){
				case 'LEVEL_LOKASI' : $$key = '3'; break;
				case 'id' : $$key = $this->input->post($key) ? ($this->input->post($key)) : NULL; $$key == 'new' ? $$key = NULL : '';break;	
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			if(isset($$key))
				$this->data_kampung[$value] = $$key;
		}
	}
	
	function insert_data_sublokasi_kampung()
	{
		$this->fill_data_sublokasi_kampung();
		$this->db->trans_start();
		$insert = $this->db->insert($this->_table, $this->data_kampung);
		$newid = $this->db->query('select max(ID_LOKASI) as ID from LOKASI')->row_array();
		$this->db->trans_complete();
		return $newid['ID'];
	}
	
	function update_data_sublokasi_kampung($id)
	{		
		//$lokasi= $this->input->post('lokasi'); $lokasi=($lokasi)?$lokasi:null;
		$this->fill_data_sublokasi_kampung();
		$this->db->trans_start();
		$this->db->where($this->_pk, $id);
		$this->db->update($this->_table, $this->data_kampung);
		$this->db->trans_complete();	
	}
	////////////////////////////////////////////////////// End Kampung //////////////////////////////////////////////////
	////////////////////////////////////////////////// Start RW //////////////////////////////////////////////////////////////
	function get_data_sublokasi_rw($param,$ID_LOKASI)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			if (array_key_exists($param['search_field'], $this->fieldmap)) {
				$wh = "UPPER(".$this->fieldmap[$param['search_field']].")";
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
		$this->db->select('*');
		$this->db->from('LOKASI');
		$this->db->where('ID_PARENT_LOKASI',$ID_LOKASI);
		$result = $this->db->get();
		return $result;
		$this->db->trans_complete();
		
	}
	function fill_data_sublokasi_rw()
	{
		$idparent = $this->input->post('idparent'); $idparent=($idparent)?$idparent:null;
		$lokasi= $this->input->post('lokasi'); $lokasi=($lokasi)?$lokasi:null;

		foreach($this->fieldmap as $key => $value){
		switch ($key){
				case 'LEVEL_LOKASI' : $$key = '4'; break;
				case 'id' : $$key = $this->input->post($key) ? ($this->input->post($key)) : NULL; $$key == 'new' ? $$key = NULL : '';break;	
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			if(isset($$key))
				$this->data_rw[$value] = $$key;
		}
	}
	
	function insert_data_sublokasi_rw()
	{
		$this->fill_data_sublokasi_rw();
		$this->db->trans_start();
		$insert = $this->db->insert($this->_table, $this->data_rw);
		$newid = $this->db->query('select max(ID_LOKASI) as ID from LOKASI')->row_array();
		$this->db->trans_complete();
		return $newid['ID'];
	}
	
	function update_data_sublokasi_rw($id)
	{		
		//$lokasi= $this->input->post('lokasi'); $lokasi=($lokasi)?$lokasi:null;
		$this->fill_data_sublokasi_rw();
		$this->db->trans_start();
		$this->db->where($this->_pk, $id);
		$this->db->update($this->_table, $this->data_rw);
		$this->db->trans_complete();	
	}
	
	function check_sub_lokasi_rw()
	{
		$idparent 	= $this->input->post('idparent'); $idparent=($idparent)?$idparent:null;
		$lokasi 	= $this->input->post('lokasi');$lokasi=($lokasi)?$lokasi:null;
		
		$this->db->trans_start();
		$query = "SELECT * FROM LOKASI WHERE  LOKASI = '$lokasi' AND ID_PARENT_LOKASI='$idparent'";
		$result = $this->db->query($query);
		
		if(count($result->row_array()) > 0){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		$this->db->trans_complete();	
	}
	
	////////////////////////////////////////////////// End RW //////////////////////////////////////////////////////////////
	
	////////////////////////////////////////////////// Start RT //////////////////////////////////////////////////////////////
	function get_data_sublokasi_rt($param,$ID_LOKASI)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			if (array_key_exists($param['search_field'], $this->fieldmap)) {
				$wh = "UPPER(".$this->fieldmap[$param['search_field']].")";
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
		$this->db->select('*');
		$this->db->from('LOKASI');
		$this->db->where('ID_PARENT_LOKASI',$ID_LOKASI);
		$result = $this->db->get();
		return $result;
		$this->db->trans_complete();
		
	}
	function fill_data_sublokasi_rt()
	{
		$idparent = $this->input->post('idparent'); $idparent=($idparent)?$idparent:null;
		$lokasi= $this->input->post('lokasi'); $lokasi=($lokasi)?$lokasi:null;

		foreach($this->fieldmap as $key => $value){
		switch ($key){
				case 'LEVEL_LOKASI' : $$key = '5'; break;
				case 'id' : $$key = $this->input->post($key) ? ($this->input->post($key)) : NULL; $$key == 'new' ? $$key = NULL : '';break;	
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			if(isset($$key))
				$this->data_rt[$value] = $$key;
		}
	}
	
	function insert_data_sublokasi_rt()
	{
		$this->fill_data_sublokasi_rt();
		$this->db->trans_start();
		$insert = $this->db->insert($this->_table, $this->data_rt);
		$newid = $this->db->query('select max(ID_LOKASI) as ID from LOKASI')->row_array();
		$this->db->trans_complete();
		return $newid['ID'];
	}
	
	function update_data_sublokasi_rt($id)
	{		
		//$lokasi= $this->input->post('lokasi'); $lokasi=($lokasi)?$lokasi:null;
		$this->fill_data_sublokasi_rt();
		$this->db->trans_start();
		$this->db->where($this->_pk, $id);
		$this->db->update($this->_table, $this->data_rt);
		$this->db->trans_complete();	
	}
	
	function check_sub_lokasi_rt()
	{
		$idparent 	= $this->input->post('idparent'); $idparent=($idparent)?$idparent:null;
		$lokasi 	= $this->input->post('lokasi');$lokasi=($lokasi)?$lokasi:null;
		
		$this->db->trans_start();
		$query = "SELECT * FROM LOKASI WHERE  LOKASI = '$lokasi' AND ID_PARENT_LOKASI='$idparent'";
		$result = $this->db->query($query);
		
		if(count($result->row_array()) > 0){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		$this->db->trans_complete();	
	}
	
	////////////////////////////////////////////////// End RT //////////////////////////////////////////////////////////////
	
	function check_parent($id){
		$this->db->trans_start();
    $this->db->select('count(a.ID_LOKASI) ID_LOKASI');
    $this->db->from('LOKASI a');
		$this->db->where('a.ID_PARENT_LOKASI', $id);
		$result = $this->db->get()->row_array();
		$this->db->trans_complete();
		if ($result['ID_LOKASI'] > 0 ) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
	
	function get_sub_pilih($id)
	{
		//returns the query string
		$this->db->trans_start();
		$this->db->where('ID_PARENT_LOKASI',$id);
		$result = $this->db->get($this->_table)->result_array();
		
		$this->db->trans_complete();
		if(count($result)>0) {
			return $result;
		} else {
			return FALSE;
		}
	}
	
	function check_lokasi()
	{
		$lokasi = $this->input->post('lokasi');$lokasi=($lokasi)?$lokasi:null;
		
		$this->db->trans_start();
		$query = "SELECT * FROM LOKASI WHERE  LOKASI = '$lokasi' AND LEVEL_LOKASI='1'";
		$result = $this->db->query($query);
		
		if(count($result->row_array()) > 0){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		$this->db->trans_complete();	
	}
	
	function check_sub_lokasi()
	{
		$idparent 	= $this->input->post('idparent'); $idparent=($idparent)?$idparent:null;
		$lokasi 	= $this->input->post('lokasi');$lokasi=($lokasi)?$lokasi:null;
		
		$this->db->trans_start();
		$query = "SELECT * FROM LOKASI WHERE  LOKASI = '$lokasi' AND ID_PARENT_LOKASI='$idparent'";
		$result = $this->db->query($query);
		
		if(count($result->row_array()) > 0){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		$this->db->trans_complete();	
	}
	
	function check_sub_lokasi_kampung()
	{
		$idparent 	= $this->input->post('idparent'); $idparent=($idparent)?$idparent:null;
		$lokasi 	= $this->input->post('lokasi');$lokasi=($lokasi)?$lokasi:null;
		
		$this->db->trans_start();
		$query = "SELECT * FROM LOKASI WHERE  LOKASI = '$lokasi' AND ID_PARENT_LOKASI='$idparent'";
		$result = $this->db->query($query);
		
		if(count($result->row_array()) > 0){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		$this->db->trans_complete();	
	}
}
?>