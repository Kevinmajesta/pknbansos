<?php
class Rekeningpptk_model extends CI_Model {
	
	var $data;
	var $fieldmap = array();
  var $id_skpd;
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->_table='REKENING_PPTK';
		$this->_pk='ID_REKENING';
    
    // set id_skpd diambil dari session
    $this->id_skpd = $this->session->userdata('id_skpd');
		
		$this->fieldmap = array (
		'idrekening' => 'ID_REKENING',
		'koderekening' => 'KODE_REKENING',
		'namarekening' => 'NAMA_REKENING',
		'idkegiatan' => 'ID_KEGIATAN',
		'kodekegiatan' => 'KODE_KEGIATAN',
		'namakegiatan' => 'NAMA_KEGIATAN',
    'idpptk' => 'ID_PPTK',
    'namapejabat' => 'NAMA_PEJABAT'
		);
	}
	
	function fill_data()
	{
		
		$idrekening = $this->input->post('idrekening'); $idrekening = $idrekening;
		$koderekening=$this->input->post('koderekening');$koderekening=($koderekening)?$koderekening:null;
		$namarekening=$this->input->post('namarekening');$namarekening=($namarekening)?$namarekening:null;
		$idkegiatan = $this->input->post('idkegiatan'); $idkegiatan = $idkegiatan;
		$kodekegiatan=$this->input->post('kodekegiatan');$kodekegiatan=($kodekegiatan)?$kodekegiatan:null;
		$namakegiatan=$this->input->post('namakegiatan');$namakegiatan=($namakegiatan)?$namakegiatan:null;
		$idpptk = $this->input->post('idpptk'); $idpptk = $idpptk;
		$namapejabat=$this->input->post('namapejabat');$namapejabat=($namapejabat)?$namapejabat:null;
		
		foreach($this->fieldmap as $key => $value){
			switch ($key){
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
        
		if($param['sort_by'] == 'koderekening'){
			$param['sort_by'] = 'r.KODE_REKENING';
		}
		elseif($param['sort_by'] == 'namarekening'){
			$param['sort_by'] = 'r.NAMA_REKENING';
		}		
		elseif($param['sort_by'] == 'kodekegiatan'){
			$param['sort_by'] = 'k.KODE_KEGIATAN_SKPD';
		}
		elseif($param['sort_by'] == 'namakegiatan'){
			$param['sort_by'] = 'k.NAMA_KEGIATAN';
		}
		elseif($param['sort_by'] == 'namapejabat'){
			$param['sort_by'] = 'p.NAMA_PEJABAT';
		}
		
    ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		$this->db->select(
			'rp.ID_REKENING, rp.ID_KEGIATAN, rp.ID_PPTK, r.KODE_REKENING, r.NAMA_REKENING, 
      k.KODE_KEGIATAN_SKPD, k.NAMA_KEGIATAN, p.NAMA_PEJABAT'
		);
		$this->db->from('REKENING_PPTK as rp');
		$this->db->join('REKENING as r', 'r.ID_REKENING = rp.ID_REKENING');
		$this->db->join('V_KEGIATAN_SKPD as k', 'k.ID_KEGIATAN = rp.ID_KEGIATAN');
		$this->db->join('PEJABAT_SKPD as p', 'p.ID_PEJABAT_SKPD = rp.ID_PPTK');
    $this->db->where('k.ID_SKPD', $this->id_skpd);
		$this->db->order_by('r.KODE_REKENING');
		$result = $this->db->get();
		return $result;
		$this->db->trans_complete();
	}	
	
  function get_data_pejabat()
  {
    $this->db->select('p.ID_PEJABAT_SKPD, p.NAMA_PEJABAT');
    $this->db->from('PEJABAT_SKPD as p');
    $this->db->where('p.KODE_JABATAN', 'PPTK');
    $result = $this->db->get();
    
    return $result;
  }
  
	function insert_data()
	{
		$this->fill_data();
		$this->db->trans_start();
		unset($this->data['KODE_REKENING']);
		unset($this->data['NAMA_REKENING']);
		unset($this->data['KODE_KEGIATAN']);
		unset($this->data['NAMA_KEGIATAN']);
		unset($this->data['NAMA_PEJABAT']);
		$result = $this->db->insert($this->_table, $this->data);
		$this->db->trans_complete();
    return $result;
	}

	function update_data($id)
	{
		$this->fill_data();
		$data = $this->fill_data();
		$this->db->trans_start();
		unset($this->data['KODE_REKENING']);
		unset($this->data['NAMA_REKENING']);
		unset($this->data['KODE_KEGIATAN']);
		unset($this->data['NAMA_KEGIATAN']);
		unset($this->data['NAMA_PEJABAT']);
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
		$this->db->select('a.ID_REKENING');
		$this->db->select('(select count(b.ID_REKENING_PPTK) from PANJAR b where b.ID_REKENING_PPTK = a.ID_REKENING) PANJAR_PAKAI');
		$this->db->where('a.ID_REKENING', $id);
		$result = $this->db->get('REKENING_PPTK a')->row_array();
		$this->db->trans_complete();
		
		if ($result['PANJAR_PAKAI'] > 0) {
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
		
		$this->db->trans_start();
		$query = "SELECT * FROM REKENING_PPTK WHERE ID_REKENING='$idrekening'";
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
		$idkegiatan = $this->input->post('idkegiatan');$idkegiatan=($idkegiatan)?$idkegiatan:null;
		
		$this->db->trans_start();
		$query = "SELECT * FROM REKENING_PPTK WHERE ID_KEGIATAN='$idkegiatan'";
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
  
	function check_isi_2()
	{
		$idrekening = $this->input->post('idrekening');$idrekening=($idrekening)?$idrekening:null;
		
		$this->db->trans_start();
		$id = $this->db->query("select ID_REKENING as ID_REK from REKENING_PPTK WHERE ID_REKENING='".$this->input->post('id')."'")->row_array();
						
		if($idrekening == $id['ID_REK']){
			return TRUE;
		}
		else if ($idrekening <> $id['ID_REK']) {
			$query = "SELECT * FROM REKENING_PPTK WHERE ID_REKENING='$idrekening'";
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
  
  
	function check_isi_3()
	{
		$idkegiatan = $this->input->post('idkegiatan');$idkegiatan=($idkegiatan)?$idkegiatan:null;
		
		$this->db->trans_start();
		$id = $this->db->query("select ID_KEGIATAN as ID_KEG from REKENING_PPTK WHERE ID_REKENING='".$this->input->post('id')."'")->row_array();
						
		if($idkegiatan == $id['ID_KEG']){
			return TRUE;
		}
		else if ($idkegiatan <> $id['ID_KEG']) {
			$query = "SELECT * FROM REKENING_PPTK WHERE ID_KEGIATAN='$idkegiatan'";
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
  
  function check_pejabat()
  {
    $namapejabat = $this->input->post('namapejabat') ? $this->input->post('namapejabat') : 0;
    
    $this->db->select('p.ID_PEJABAT_SKPD');
    $this->db->from('PEJABAT_SKPD as p');
    $this->db->where('p.NAMA_PEJABAT', $namapejabat);
    $result = $this->db->get()->result_array();
    
    if (count($result) > 0){
      return TRUE;
    }else{
      return FALSE;
    }
  }
}
?>