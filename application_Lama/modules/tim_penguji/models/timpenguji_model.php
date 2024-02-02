<?php
class Timpenguji_model extends CI_Model {
	
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
		$this->_table='PEJABAT_PENGUJI';
		$this->_pk='ID_PEJABAT_PENGUJI';
		
		$this->fieldmap = array(
			'id' => 'ID_PEJABAT_PENGUJI',
			'nama_pejabat' => 'NAMA_PEJABAT',
			'nip' => 'NIP',
			'idskpd' => 'ID_SKPD',
			'tahun' => 'TAHUN'
		);
	}
	function fill_data()
	{
		$lokasi= $this->input->post('lokasi'); $lokasi=($lokasi)?$lokasi:null;

		foreach($this->fieldmap as $key => $value){
			switch ($key){
				case 'id' : $$key = $this->input->post($key) ? ($this->input->post($key)) : NULL; $$key == 'new' ? $$key = NULL : '';break;
				case 'tahun' : $$key = $this->session->userdata('tahun');break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			if(isset($$key))
				$this->data[$value] = $$key;
		}
	}
	
	function get_data($param)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
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
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		$this->db->select("r.ID_PEJABAT_PENGUJI, r.NAMA_PEJABAT,r.NIP,s.NAMA_SKPD,s.ID_SKPD");
		$this->db->from('PEJABAT_PENGUJI r');
		$this->db->join('SKPD s','s.ID_SKPD=r.ID_SKPD');
		$this->db->where('r.TAHUN',$this->session->userdata('tahun'));
		$result = $this->db->get()->result_array();
		
		$this->db->trans_complete();
		if(count($result)>0) {
			return $result;
		} 
	}
	
	function check_update($id)
	{
		$NIP = $this->input->post('nip');$NIP=($NIP)?$NIP:null;
		
		$this->db->trans_start();
		if ($NIP != null){
			$query = "SELECT * FROM PEJABAT_PENGUJI WHERE NIP='$NIP' and ID_PEJABAT_PENGUJI !='$id'";
			$result = $this->db->query($query);
			$result = count($result->result_array());
			
			if($result > 0){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
		$this->db->trans_complete();	
	}
	
	function check_timpenguji()
	{
		$idskpd = $this->input->post('idskpd');$idskpd=($idskpd)?$idskpd:null;
		$nip = $this->input->post('nip');$nip=($nip)?$nip:null;
		
		$this->db->trans_start();
		$query = "SELECT * FROM PEJABAT_PENGUJI WHERE NIP='$nip'";
		$result = $this->db->query($query);
		
		if(count($result->row_array()) > 0){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		$this->db->trans_complete();	
	}
	
	function insert_data()
	{
		$this->fill_data();
		$this->db->trans_start();
		$insert = $this->db->insert($this->_table, $this->data);
		$newid = $this->db->query('select max(ID_PEJABAT_PENGUJI) as ID from PEJABAT_PENGUJI')->row_array();
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
	
	function check_dependency($id)
	{
		$this->db->select('a.ID_PEJABAT_PENGUJI');
		$this->db->select('(select count(b.ID_PEJABAT) from TIM_PENGUJI b where b.ID_PEJABAT = a.ID_PEJABAT_PENGUJI) TIM_PENGUJI_PAKAI');
		$this->db->where('a.ID_PEJABAT_PENGUJI', $id);
		$result = $this->db->get('PEJABAT_PENGUJI a')->row_array();

		if ($result['TIM_PENGUJI_PAKAI'] > 0) {
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
}
?>