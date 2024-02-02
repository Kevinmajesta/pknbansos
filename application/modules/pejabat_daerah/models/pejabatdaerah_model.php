<?php
class Pejabatdaerah_model extends CI_Model {
	
	var $data;
	var $fieldmap = array();
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->_table='PEJABAT_DAERAH';
		$this->_pk='ID_PEJABAT_DAERAH';
		
		$this->fieldmap = array(
			'id' => 'ID_PEJABAT_DAERAH',
			'jabatan' => 'JABATAN',
			'kode_jabatan' => 'KODE_JABATAN',
			'nama' => 'NAMA_PEJABAT',
			'nip' => 'NIP',
			'aktif' => 'AKTIF'
		);
	}
	
	function fill_data()
	{
		$jabatan 	= $this->input->post('jabatan');$jabatan=$jabatan ? $jabatan : null;
		$nama		= $this->input->post('nama');	$nama = $nama ? $nama:null; 
		$nip 		= $this->input->post('nip');//	$nip = $nip ? $nip : null;
		$aktif		= $this->input->post('aktif');	/*$aktif=($aktif)?$aktif:0;*/ 
		
		if($jabatan == "Kepala Daerah"){
			$kode_jabatan = "KADA";
		}
		else if($jabatan == "Wakil Kepala Daerah"){
			$kode_jabatan = "WKADA";		
		}
		else if($jabatan == "Ketua DPRD"){
			$kode_jabatan = "KADPRD";		
		}
		else if($jabatan == "Wakil Ketua DPRD"){
			$kode_jabatan = "WKADPRD";		
		}
		else if($jabatan == "Kepala BAPPEDA"){
			$kode_jabatan = "KABAPPEDA";		
		}
		else if($jabatan == "Kepala BAWASDA"){
			$kode_jabatan = "KABAWASDA";		
		}
		else if($jabatan == "Sekretaris Daerah"){
			$kode_jabatan = "SEKDA";		
		}
		else if($jabatan == "Sekretaris DPRD"){
			$kode_jabatan = "SEKDPRD";		
		}
		else if($jabatan == "Asisten Daerah I"){
			$kode_jabatan = "ASEKDA1";		
		}
		else if($jabatan == "Asisten Daerah II"){
			$kode_jabatan = "ASEKDA2";		
		}
		else if($jabatan == "Asisten Daerah III"){
			$kode_jabatan = "ASEKDA3";		
		}
		else if($jabatan == "Asisten Daerah IV"){
			$kode_jabatan = "ASEKDA4";		
		}
		else if($jabatan == "Kepala Bagian Keuangan"){
			$kode_jabatan = "KABAGKEU";		
		}
		else if($jabatan == "BUD/Kuasa BUD"){
			$kode_jabatan = "BUD";		
		}
		else if($jabatan == "PPKD"){
			$kode_jabatan = "PPKD";		
		}
		else{
			$kode_jabatan = "";	
		}
		
		foreach($this->fieldmap as $key => $value){
			/*switch ($key){
				case 'AKTIF' : $$key	= $aktif?$aktif:0; break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}*/
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
				if($param['search_str']=='AKTIF'){
					$param['search_str']='1';
				}
				elseif($param['search_str']=='TIDAK AKTIF'){
					$param['search_str']='0';
				}
				else{
					$param['search_str'] = $param['search_str'];
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
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
		if($param['sort_by']=='nama'){
			$param['sort_by'] = 'nama_pejabat';
		}
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		$this->db->select('ID_PEJABAT_DAERAH,JABATAN,NAMA_PEJABAT,NIP,AKTIF');
		//$this->db->where('AKTIF','1');
		$this->db->order_by('NAMA_PEJABAT');
		$result = $this->db->get($this->_table)->result_array();
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
	
	function check_duplication($nama, $id)
	{
		$this->db->trans_start();
		$this->db->where_in('NAMA_PEJABAT', $nama);
		if ($id) $this->db->where('ID_PEJABAT_DAERAH !=', $id);
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
		unset($this->data['ID_PEJABAT_DAERAH']);
		$result = $this->db->insert($this->_table, $this->data);
		$newid = $this->db->query('select max(ID_PEJABAT_DAERAH) as ID from PEJABAT_DAERAH')->row_array();
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
		$this->db->select('a.ID_PEJABAT_DAERAH');
		$this->db->select('(select count(b.ID_PEJABAT_DAERAH) from TIM_ANGGARAN b where b.ID_PEJABAT_DAERAH = a.ID_PEJABAT_DAERAH) TIM_ANGGARAN_PAKAI');
		$this->db->select('(select count(b.ID_BUD) from SPD b where b.ID_BUD = a.ID_PEJABAT_DAERAH) SPD_PAKAI');
		$this->db->where('a.ID_PEJABAT_DAERAH', $id);
		$result = $this->db->get('PEJABAT_DAERAH a')->row_array();
		$this->db->trans_complete();
		if ($result['TIM_ANGGARAN_PAKAI'] > 0 || $result['SPD_PAKAI'] > 0 ) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
	
	function check_isi()
	{
		$NIP = $this->input->post('nip');$NIP=($NIP)?$NIP:null;
		
		$this->db->trans_start();
		if ($NIP != null){
			$query = "SELECT * FROM PEJABAT_DAERAH WHERE NIP='$NIP'";
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
	
	function check_isi2($id) // cek NIP sebelum update
	{
		$NIP = $this->input->post('nip');$NIP=($NIP)?$NIP:null;
		
		$this->db->trans_start();
		if ($NIP != null){
			$query = "SELECT * FROM PEJABAT_DAERAH WHERE NIP='$NIP' and ID_PEJABAT_DAERAH!='$id'";
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
	
}
?>