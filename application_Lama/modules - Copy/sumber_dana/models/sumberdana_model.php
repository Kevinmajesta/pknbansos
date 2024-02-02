<?php
class Sumberdana_model extends CI_Model {
	
	var $data;
	var $fieldmap = array();
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->_table='SUMBER_DANA';
		$this->_pk='ID_SUMBER_DANA';
		
		$this->fieldmap = array (
		'id' => 'ID_SUMBER_DANA',
		'idrekening' => 'ID_REKENING',
		'namasumber' => 'NAMA_SUMBER_DANA',
		'namabank' => 'NAMA_BANK',
		'norekening' => 'NO_REKENING_BANK',
		'koderekening' => 'KODE_REKENING',
		'namarekening' => 'NAMA_REKENING'
		);
	}
	
	function fill_data()
	{
		
		$idrekening = $this->input->post('idrekening'); $idrekening = $idrekening;
		$namasumber= $this->input->post('namasumber'); $namasumber=($namasumber)?$namasumber:null;
		$namabank = $this->input->post('namabank');$namabank=($namabank)?$namabank:null;
		$norekening=$this->input->post('norekening');$norekening=($norekening)?$norekening: ' ';
		$koderekening=$this->input->post('koderekening');$koderekening=($koderekening)?$koderekening:null;
		$namarekening=$this->input->post('namarekening');$namarekening=($namarekening)?$namarekening:null;
		
		foreach($this->fieldmap as $key => $value){
			switch ($key){
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
        
		if($param['sort_by'] == 'namabank'){
			$param['sort_by'] = 'sd.NAMA_BANK';
		}
		elseif($param['sort_by'] == 'namasumber'){
			$param['sort_by'] = 'sd.NAMA_SUMBER_DANA';
		}
		elseif($param['sort_by'] == 'norekening'){
			$param['sort_by'] = 'sd.NO_REKENING_BANK';
		}
		elseif($param['sort_by'] == 'koderekening'){
			$param['sort_by'] = 'r.KODE_REKENING';
		}
		elseif($param['sort_by'] == 'namarekening'){
			$param['sort_by'] = 'r.NAMA_REKENING';
		}		
		
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		$this->db->select(
			'sd.ID_SUMBER_DANA, sd.NAMA_SUMBER_DANA, r.KODE_REKENING, r.NAMA_REKENING, sd.NAMA_BANK, sd.NO_REKENING_BANK, sd.ID_REKENING'
		);
		$this->db->from('SUMBER_DANA as sd');
		$this->db->join('REKENING as r', 'r.ID_REKENING = sd.ID_REKENING', 'LEFT');
		$this->db->order_by('r.KODE_REKENING');
		$result = $this->db->get();
		return $result;
		$this->db->trans_complete();
	}	
	
	function insert_data()
	{
		$this->fill_data();
		$this->db->trans_start();
		unset($this->data['KODE_REKENING']);
		unset($this->data['NAMA_REKENING']);
		$result = $this->db->insert($this->_table, $this->data);
		$newid = $this->db->query('select max(ID_SUMBER_DANA) as ID from SUMBER_DANA')->row_array();
		$this->db->trans_complete();
		return $newid['ID'];
	}

	function update_data($id)
	{
		$this->fill_data();
		$data = $this->fill_data();
		$this->db->trans_start();
		unset($this->data['KODE_REKENING']);
		unset($this->data['NAMA_REKENING']);
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
		//return $update;
	}
	
	function check_dependency($id)
	{
		$this->db->trans_start();
		$this->db->select('a.ID_SUMBER_DANA');
		
		$this->db->select('(select count(b.ID_SUMBER_DANA) from SUMBER_DANA_KEGIATAN b where b.ID_SUMBER_DANA = a.ID_SUMBER_DANA) SUMBER_DANA_PAKAI');
		$this->db->select('(select count(b.ID_SUMBER_DANA) from STS b where b.ID_SUMBER_DANA = a.ID_SUMBER_DANA) STS_PAKAI');
		$this->db->select('(select count(b.ID_SUMBER_DANA) from SP2D b where b.ID_SUMBER_DANA = a.ID_SUMBER_DANA) SP2D_PAKAI');
		$this->db->select('(select count(b.ID_SUMBER_DANA) from KONTRA_POS b where b.ID_SUMBER_DANA = a.ID_SUMBER_DANA) KONTRA_POS_PAKAI');
		$this->db->select('(select count(b.ID_SUMBER_DANA) from RINCIAN_BKU b where b.ID_SUMBER_DANA = a.ID_SUMBER_DANA) RINCIAN_BKU_PAKAI');
		$this->db->select('(select count(b.ID_SUMBER_DANA) from SKPD b where b.ID_SUMBER_DANA = a.ID_SUMBER_DANA) SKPD_PAKAI');
		
		$this->db->where('a.ID_SUMBER_DANA', $id);
		$result = $this->db->get('SUMBER_DANA a')->row_array();
		$this->db->trans_complete();
		
		//echo "aa $result[SUMBER_DANA_PAKAI],$result[STS_PAKAI],$result[SP2D_PAKAI],$result[KONTRA_POS_PAKAI],$result[RINCIAN_BKU_PAKAI],$result[SKPD_PAKAI]";
		if ($result['SUMBER_DANA_PAKAI'] > 0 || $result['STS_PAKAI'] > 0 || $result['SP2D_PAKAI'] > 0 || $result['KONTRA_POS_PAKAI'] > 0 || $result['RINCIAN_BKU_PAKAI'] > 0 || $result['SKPD_PAKAI'] > 0) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
	
	function check_isi()
	{
		$idrekening = $this->input->post('idrekening');$idrekening=($idrekening)?$idrekening:null;
		$namasumber = $this->input->post('namasumber');$namasumber=($namasumber)?$namasumber:null;
		$namabank 	= $this->input->post('namabank');$namabank=($namabank)?$namabank:null;		
		$norekening	= $this->input->post('norekening');$norekening=($norekening)?$norekening:null;
		
		$this->db->trans_start();
		$query = "SELECT * FROM SUMBER_DANA WHERE ID_REKENING='$idrekening' OR NAMA_SUMBER_DANA='$namasumber'";
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
	
	function check_isi_1()
	{
		$idrekening = $this->input->post('idrekening');$idrekening=($idrekening)?$idrekening:null;
		$namasumber = $this->input->post('namasumber');$namasumber=($namasumber)?$namasumber:null;
		$namabank 	= $this->input->post('namabank');$namabank=($namabank)?$namabank:null;		
		$norekening	= $this->input->post('norekening');$norekening=($norekening)?$norekening:null;
		
		$this->db->trans_start();
		$id = $this->db->query("select ID_REKENING as ID_REK, NAMA_SUMBER_DANA as NAMA_SUMB from SUMBER_DANA WHERE ID_SUMBER_DANA='".$this->input->post('id')."'")->row_array();
		
		//$query = "SELECT * FROM SUMBER_DANA WHERE ID_REKENING='$idrekening'";
		//$result = $this->db->query($query);
		
		$this->db->select('ID_REKENING');
		$this->db->from('SUMBER_DANA');
		$this->db->where('ID_REKENING <>', $id['ID_REK']);
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['ID_REKENING'];
			}
		}
		
		//echo "KOK $id[ID_REK],$id[NAMA_SUMB],$namasumber";
		
		/*if($idrekening == $id['ID_REK'] ){
			return TRUE;
		}
		else if (in_array($idrekening, $hasil)) {
			return FALSE;
		}
		else{
			return TRUE;
		}	*/
		
		if($idrekening == $id['ID_REK']){
			return TRUE;
		}
		else if ($idrekening <> $id['ID_REK']) {
			$query = "SELECT * FROM SUMBER_DANA WHERE ID_REKENING='$idrekening'";
			$result = $this->db->query($query)->result_array();
			
			if(count($result) > 0){
				return FALSE;
			}
			else{
				return TRUE;
			}		
		}			
		$this->db->trans_complete();	
	}
	
	function check_isi_2()
	{
		$idrekening = $this->input->post('idrekening');$idrekening=($idrekening)?$idrekening:null;
		$namasumber = $this->input->post('namasumber');$namasumber=($namasumber)?$namasumber:null;
		$namabank 	= $this->input->post('namabank');$namabank=($namabank)?$namabank:null;		
		$norekening	= $this->input->post('norekening');$norekening=($norekening)?$norekening:null;
		
		$this->db->trans_start();
		$id = $this->db->query("select ID_REKENING as ID_REK, NAMA_SUMBER_DANA as NAMA_SUMB from SUMBER_DANA WHERE ID_SUMBER_DANA='".$this->input->post('id')."'")->row_array();
		
		
		$this->db->select('NAMA_SUMBER_DANA');
		$this->db->from('SUMBER_DANA');
		$this->db->where('NAMA_SUMBER_DANA <>', $id['NAMA_SUMB']);
		$adas = $this->db->get()->result_array();
		$sumber = null;
		if(count($adas) > 0)
		{
			foreach($adas as $rows)
			{
				$sumber[] = $rows['NAMA_SUMBER_DANA'];
			}
		}
		
		/*if($namasumber == $id['NAMA_SUMB'] ){
			return TRUE;
		}
		else if (in_array($namasumber, $sumber)) {
			return FALSE;
		}
		else{
			return TRUE;
		}	*/	
		
		if($namasumber == $id['NAMA_SUMB']){
			return TRUE;
		}
		else if ($namasumber <> $id['NAMA_SUMB']) {
			$query = "SELECT * FROM SUMBER_DANA WHERE NAMA_SUMBER_DANA='$namasumber'";
			$result = $this->db->query($query)->result_array();
			
			if(count($result) > 0){
				return FALSE;
			}
			else{
				return TRUE;
			}		
		}	
		$this->db->trans_complete();	
	}
}
?>