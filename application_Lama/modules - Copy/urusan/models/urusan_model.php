<?php
class Urusan_model extends CI_Model {
	
	var $data;
	var $fieldmap = array();
	
	 function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->_table='URUSAN';
		$this->_pk='ID_URUSAN';
		
		$this->fieldmap = array(
			'id' => 'ID_URUSAN',
			'kode' => 'KODE_URUSAN',
			'nama' => 'NAMA_URUSAN'
		);
	}
	function fill_data()
	{
		$kode= $this->input->post('kode'); $kode=($kode)?$kode:null;
		$nama = $this->input->post('nama');$nama=($nama)?$nama:null;
		
		foreach($this->fieldmap as $key => $value){
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
		$this->db->select('ID_URUSAN,KODE_URUSAN,NAMA_URUSAN');
		$result = $this->db->get($this->_table)->result_array();
		$this->db->trans_complete();
		if(count($result)>0) {
			return $result;
		} else {
			return FALSE;
		}	
	}
	
	function check_duplication($kode, $id)
	{
		$this->db->trans_start();
		$this->db->where_in('KODE_URUSAN', $kode);
		if ($id) $this->db->where('ID_URUSAN !=', $id);
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
	
	function insert_data()
	{
		$this->fill_data();
		$this->db->trans_start();
		$result = $this->db->insert($this->_table, $this->data);
		$newid = $this->db->query('select max(ID_URUSAN) as ID from URUSAN')->row_array();
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
		$this->db->select('a.ID_URUSAN');
		$this->db->select('(select count(b.ID_URUSAN) from BIDANG b where b.ID_URUSAN = a.ID_URUSAN) BIDANG_PAKAI');
		$this->db->where('a.ID_URUSAN', $id);
		$result = $this->db->get('URUSAN a')->row_array();
		$this->db->trans_complete();
		if ($result['BIDANG_PAKAI'] > 0 ) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
		
	function check_data()
	{
		$KODE	= $this->input->post('kode');	$KODE=($KODE)?$KODE:null;
		
		$this->db->trans_start();
		$query = "SELECT * FROM URUSAN WHERE  KODE_URUSAN = '$KODE'";
		$result = $this->db->query($query);
		$result = $result->result_array();
		
		if(count($result) > 0){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		$this->db->trans_complete();	
	}
	
	function check_data2()
	{
		$KODE	= $this->input->post('kode');	$KODE=($KODE)?$KODE:null;
		$NAMA	= $this->input->post('nama');	$NAMA=($NAMA)?$NAMA:null;
		
		$id = $this->db->query("select KODE_URUSAN as ID from URUSAN WHERE ID_URUSAN='".$this->input->post('id')."'")->row_array();
		
		$this->db->select('KODE_URUSAN');
		$this->db->from('URUSAN');
		$this->db->where('KODE_URUSAN <>', $id['ID']);
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['KODE_URUSAN'];
			}
		}
		
		$this->db->trans_start();
		$this->db->select('*');
		$this->db->from('URUSAN');
		$this->db->where('KODE_URUSAN', $id['ID']);
		if($hasil)
		{
			$this->db->where_not_in('KODE_URUSAN',$hasil);
		}
		$result = $this->db->get();
		/*$this->db->trans_start();
		$query = "SELECT * FROM URUSAN WHERE KODE_URUSAN = '$id[ID]' OR KODE_URUSAN not in $hasil";
		$result = $this->db->query($query);
		
		if($result->num_rows() > 0){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		if($KODE == $id['ID']){
			return TRUE;
		}
		else{
			return FALSE;
		}	
		if($result->num_rows() > 0){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		$this->db->trans_complete();	*/
		if($KODE == $id['ID']){
			return TRUE;
		}
		else if (in_array($KODE, $hasil)) {
			return FALSE;
		}	
		else{
			return TRUE;
		}
	}
}
?>