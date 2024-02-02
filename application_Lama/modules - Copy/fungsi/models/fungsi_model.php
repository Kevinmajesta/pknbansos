<?php
class Fungsi_model extends CI_Model {
	
	var $data;
	var $fieldmap = array();
	
	 function __construct()
   {
      // Call the Model constructor
      parent::__construct();
      $this->_table='FUNGSI';
      $this->_pk='ID_FUNGSI';
      
      $this->fieldmap = array(
        'id' => 'ID_FUNGSI',
        'kode' => 'KODE_FUNGSI',
        'nama' => 'NAMA_FUNGSI'
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

  function get_data($param, $isCount=FALSE, $CompileOnly=False)
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
//		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
//    ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		$this->db->select('ID_FUNGSI,KODE_FUNGSI,NAMA_FUNGSI');
		$this->db->from($this->_table);

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
		$this->db->trans_complete();
	}
	
	function get_data_by_id($id)
	{
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

	function check_duplication($kode, $id)
	{
		$this->db->where_in('KODE_FUNGSI', $kode);
		if ($id) $this->db->where('ID_FUNGSI !=', $id);
		$result = $this->db->get($this->_table)->row_array();
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
		$newid = $this->db->query('select max(ID_FUNGSI) as ID from FUNGSI')->row_array();
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
		$this->db->select('a.ID_FUNGSI');
		$this->db->select('(select count(b.ID_FUNGSI) from BIDANG b where b.ID_FUNGSI = a.ID_FUNGSI) BIDANG_PAKAI');
		$this->db->where('a.ID_FUNGSI', $id);
		$result = $this->db->get('FUNGSI a')->row_array();
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
		$KODE_FUNGSI	= $this->input->post('kode');	$KODE_FUNGSI=($KODE_FUNGSI)?$KODE_FUNGSI:null;
		$NAMA_FUNGSI	= $this->input->post('nama');	$NAMA_FUNGSI=($NAMA_FUNGSI)?$NAMA_FUNGSI:null;
		
		$this->db->trans_start();
		$query = "SELECT * FROM FUNGSI WHERE KODE_FUNGSI = '$KODE_FUNGSI' OR NAMA_FUNGSI = '$NAMA_FUNGSI'";
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
		$KODE_FUNGSI	= $this->input->post('kode');	$KODE_FUNGSI=($KODE_FUNGSI)?$KODE_FUNGSI:null;
		$NAMA_FUNGSI	= $this->input->post('nama');	$NAMA_FUNGSI=($NAMA_FUNGSI)?$NAMA_FUNGSI:null;
		
		$id = $this->db->query("select KODE_FUNGSI as KD, NAMA_FUNGSI as NM from FUNGSI WHERE ID_FUNGSI='".$this->input->post('id')."'")->row_array();
		
		$this->db->select('KODE_FUNGSI');
		$this->db->from('FUNGSI');
		$this->db->where('KODE_FUNGSI <>', $id['KD']);
		$ada1 = $this->db->get()->result_array();
		$hasil1 = null;
		if(count($ada1) > 0)
		{
			foreach($ada1 as $row)
			{
				$hasil1[] = $row['KODE_FUNGSI'];
			}
		}
		
		$this->db->select('NAMA_FUNGSI');
		$this->db->from('FUNGSI');
		$this->db->where('NAMA_FUNGSI <>', $id['NM']);
		$ada2 = $this->db->get()->result_array();
		$hasil2 = null;
		if(count($ada2) > 0)
		{
			foreach($ada2 as $row)
			{
				$hasil2[] = $row['NAMA_FUNGSI'];
			}
		}

		if (in_array($KODE_FUNGSI, $hasil1)) {
			return FALSE;
		}	
		else if (in_array($NAMA_FUNGSI, $hasil2)) {
			return FALSE;
		}	
		else{
			return TRUE;
		}
	}
}
?>