<?php
class Kategorirekening_model extends CI_Model {
	
	var $data;
	var $fieldmap = array();
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->_table='MASTER_REKENING ';
		$this->_pk='ID_MASTER_REKENING';
		
		$this->fieldmap = array(
			'id' => 'ID_MASTER_REKENING',
			'kode' => 'KODE_REKENING',
			'nama' => 'NAMA_REKENING',
			'saldo' => 'SALDO_NORMAL'
		);
	}
	
	function fill_data()
	{
		$kode= $this->input->post('kode'); $kode=($kode)?$kode:null;
		$nama = $this->input->post('nama');$nama=($nama)?$nama:null;
		$saldo= $this->input->post('saldo');$saldo=($saldo)?$saldo:null;
		
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
				if($param['search_str'] == 'KREDIT'){
					$param['search_str'] = '1';
				}
				elseif($param['search_str'] == 'DEBET'){
					$param['search_str'] = '-1';
				}
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
        
		if($param['sort_by'] == 'kode'){
			$param['sort_by'] = 'KODE_REKENING';
		}
		elseif($param['sort_by'] == 'nama'){
			$param['sort_by'] = 'NAMA_REKENING';
		}
		elseif($param['sort_by'] == 'saldo'){
			$param['sort_by'] = 'SALDO_NORMAL';
		}
		
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		$this->db->trans_start();
		$this->db->select('ID_MASTER_REKENING,KODE_REKENING,NAMA_REKENING,SALDO_NORMAL');
		$this->db->from('MASTER_REKENING');
    
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
          $this->db->order_by('KODE_REKENING');
          $result = $this->db->get()->result_array();
          return $result;
      }
    }  
		
    $this->db->trans_complete();
			
	}
	
	function check_duplication($kode, $id)
	{
		$this->db->trans_start();
		$this->db->where_in('KODE_REKENING', $kode);
		if ($id) $this->db->where('ID_MASTER_REKENING !=', $id);
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
		$newid = $this->db->insert_id();
		$this->db->trans_complete();
		return $newid;
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
		$this->db->select('a.ID_MASTER_REKENING');
		$this->db->select('(select count(b.ID_MASTER_REKENING) from REKENING b where b.ID_MASTER_REKENING = a.ID_MASTER_REKENING) REKENING_PAKAI');
		$this->db->where('a.ID_MASTER_REKENING', $id);
		$result = $this->db->get('MASTER_REKENING a')->row_array();
		$this->db->trans_complete();
		if ($result['REKENING_PAKAI'] > 0 ) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
	
	function check_isi()
	{
		$kode 	= $this->input->post('kode');$kode=($kode)?$kode:null;
		$nama 	= $this->input->post('nama');$nama=($nama)?$nama:null;
		$saldo 	= $this->input->post('saldo');$saldo=($saldo)?$saldo:null;		
		
		$this->db->trans_start();
		//$query = "SELECT * FROM MASTER_REKENING WHERE  KODE_REKENING = '$kode' AND NAMA_REKENING='$nama' AND SALDO_NORMAL='$saldo'";
		$query = "SELECT * FROM MASTER_REKENING WHERE  KODE_REKENING = '$kode'";
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
		$kode 	= $this->input->post('kode');$kode=($kode)?$kode:null;
		$nama 	= $this->input->post('nama');$nama=($nama)?$nama:null;
		$saldo 	= $this->input->post('saldo');$saldo=($saldo)?$saldo:null;
		
		$id = $this->db->query("select KODE_REKENING as ID from MASTER_REKENING WHERE ID_MASTER_REKENING='".$this->input->post('id')."'")->row_array();
		
		$this->db->select('KODE_REKENING');
		$this->db->from('MASTER_REKENING');
		$this->db->where('KODE_REKENING <>', $id['ID']);
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['KODE_REKENING'];
			}
		}
				
		if($kode == $id['ID']){
			return TRUE;
		}
		else if (in_array($kode, $hasil, TRUE)) {
			return FALSE;
		}	
		else{
			return TRUE;
		}
	}
}
?>