<?php
class Rekening_model extends CI_Model {
	
	var $data;
	var $data_kelompok;
	var $data_jenis;
	var $data_objek;
	var $data_rincian;
	var $data_sub1;
	var $data_sub2;
	var $data_sub3;
	var $fieldmap = array();
	var $fieldmap_kelompok = array();
	var $fieldmap_jenis = array();
	var $fieldmap_objek = array();
	var $fieldmap_rincian = array();
	var $fieldmap_sub1 = array();
	var $fieldmap_sub2 = array();
	var $fieldmap_sub3 = array();
  var $tahun;
  var $status;
  var $status1;
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->_table='REKENING';
		$this->_pk='ID_REKENING';
		$this->tahun	= $this->session->userdata('tahun');
		$this->status = $this->session->userdata('status');
		$this->status1 = "'".$this->status."'";
				
		$this->fieldmap = array(
			'id' => 'r.ID_REKENING',
			'TIPE' => 'r.TIPE',
			'KELOMPOK' => 'r.KELOMPOK',
			'JENIS' => 'r.JENIS',
			'OBJEK' => 'r.OBJEK',
			'RINCIAN' => 'r.RINCIAN',
			'SUB1' => 'r.SUB1',
			'SUB2' => 'r.SUB2',
			'SUB3' => 'r.SUB3',
			'KODER' => 'r.KODE_REKENING',
			'NAMAR' => 'r.NAMA_REKENING',
			'KATEGORI' => 'r.ID_MASTER_REKENING',
			'KTGR' => 'mr.NAMA_REKENING',
			'PAGU' => '(select sum(ra.PAGU)
				  from FORM_ANGGARAN fa
				  join RINCIAN_ANGGARAN ra on ra.ID_FORM_ANGGARAN = fa.ID_FORM_ANGGARAN
				  where fa.TAHUN = '.$this->tahun.'
					and fa.STATUS = '.$this->status1.'
					and ra.ID_REKENING = r.ID_REKENING
				)'
		);
		
		$this->fieldmapA = array(
			'id' => 'ID_REKENING',
			'TIPE' => 'TIPE',
			'KELOMPOK' => 'KELOMPOK',
			'JENIS' => 'JENIS',
			'OBJEK' => 'OBJEK',
			'RINCIAN' => 'RINCIAN',
			'SUB1' => 'SUB1',
			'SUB2' => 'SUB2',
			'SUB3' => 'SUB3',
			'KODE_REKENING' => 'KODE_REKENING',
			'KODER' => 'r.KODE_REKENING',
			'KODE' => 'KODE_REKENING',
			'NAMA_REKENING' => 'NAMA_REKENING',
			'NAMAR' => 'r.NAMA_REKENING',
			'NAMA' => 'NAMA_REKENING',
			'KATEGORI' => 'ID_MASTER_REKENING',
			'KTGR' => 'mr.NAMA_REKENING',
			'PAGU' => 'PAGU'
		);
		
		$this->fieldmap_kelompok = array(
			'idparent' => 'ID_PARENT_REKENING',
			'KATEGORI' => 'ID_MASTER_REKENING',
			'KODE_REKENING' => 'KODE_REKENING',
			'NAMA_REKENING' => 'NAMA_REKENING',
			'LEVEL_REKENING' => 'LEVEL_REKENING',		
			'TIPE' => 'TIPE',
			'KELOMPOK' => 'KELOMPOK',
			'JENIS' => 'JENIS',
			'OBJEK' => 'OBJEK',
			'RINCIAN' => 'RINCIAN',
			'SUB1' => 'SUB1',
			'SUB2' => 'SUB2',
			'SUB3' => 'SUB3'
		);
		
		$this->fieldmap_jenis = array(
			'idparent' => 'ID_PARENT_REKENING',
			'KATEGORI' => 'ID_MASTER_REKENING',
			'KODE_REKENING' => 'KODE_REKENING',
			'NAMA_REKENING' => 'NAMA_REKENING',
			'LEVEL_REKENING' => 'LEVEL_REKENING',		
			'TIPE' => 'TIPE',
			'KELOMPOK' => 'KELOMPOK',
			'JENIS' => 'JENIS',
			'OBJEK' => 'OBJEK',
			'RINCIAN' => 'RINCIAN',
			'SUB1' => 'SUB1',
			'SUB2' => 'SUB2',
			'SUB3' => 'SUB3'
		);
		
		$this->fieldmap_objek = array(
			'idparent' => 'ID_PARENT_REKENING',
			'KATEGORI' => 'ID_MASTER_REKENING',
			'KODE_REKENING' => 'KODE_REKENING',
			'NAMA_REKENING' => 'NAMA_REKENING',
			'LEVEL_REKENING' => 'LEVEL_REKENING',		
			'TIPE' => 'TIPE',
			'KELOMPOK' => 'KELOMPOK',
			'JENIS' => 'JENIS',
			'OBJEK' => 'OBJEK',
			'RINCIAN' => 'RINCIAN',
			'SUB1' => 'SUB1',
			'SUB2' => 'SUB2',
			'SUB3' => 'SUB3'
		);
		
		$this->fieldmap_rincian = array(
			'idparent' => 'ID_PARENT_REKENING',
			'KATEGORI' => 'ID_MASTER_REKENING',
			'KODE_REKENING' => 'KODE_REKENING',
			'NAMA_REKENING' => 'NAMA_REKENING',
			'LEVEL_REKENING' => 'LEVEL_REKENING',		
			'TIPE' => 'TIPE',
			'KELOMPOK' => 'KELOMPOK',
			'JENIS' => 'JENIS',
			'OBJEK' => 'OBJEK',
			'RINCIAN' => 'RINCIAN',
			'SUB1' => 'SUB1',
			'SUB2' => 'SUB2',
			'SUB3' => 'SUB3'
		);
		
		$this->fieldmap_sub1 = array(
			'idparent' => 'ID_PARENT_REKENING',
			'KATEGORI' => 'ID_MASTER_REKENING',
			'KODE_REKENING' => 'KODE_REKENING',
			'NAMA_REKENING' => 'NAMA_REKENING',
			'LEVEL_REKENING' => 'LEVEL_REKENING',		
			'TIPE' => 'TIPE',
			'KELOMPOK' => 'KELOMPOK',
			'JENIS' => 'JENIS',
			'OBJEK' => 'OBJEK',
			'RINCIAN' => 'RINCIAN',
			'SUB1' => 'SUB1',
			'SUB2' => 'SUB2',
			'SUB3' => 'SUB3'
		);
		
		$this->fieldmap_sub2 = array(
			'idparent' => 'ID_PARENT_REKENING',
			'KATEGORI' => 'ID_MASTER_REKENING',
			'KODE_REKENING' => 'KODE_REKENING',
			'NAMA_REKENING' => 'NAMA_REKENING',
			'LEVEL_REKENING' => 'LEVEL_REKENING',		
			'TIPE' => 'TIPE',
			'KELOMPOK' => 'KELOMPOK',
			'JENIS' => 'JENIS',
			'OBJEK' => 'OBJEK',
			'RINCIAN' => 'RINCIAN',
			'SUB1' => 'SUB1',
			'SUB2' => 'SUB2',
			'SUB3' => 'SUB3'
		);
		
		$this->fieldmap_sub3 = array(
			'idparent' => 'ID_PARENT_REKENING',
			'KATEGORI' => 'ID_MASTER_REKENING',
			'KODE_REKENING' => 'KODE_REKENING',
			'NAMA_REKENING' => 'NAMA_REKENING',
			'LEVEL_REKENING' => 'LEVEL_REKENING',		
			'TIPE' => 'TIPE',
			'KELOMPOK' => 'KELOMPOK',
			'JENIS' => 'JENIS',
			'OBJEK' => 'OBJEK',
			'RINCIAN' => 'RINCIAN',
			'SUB1' => 'SUB1',
			'SUB2' => 'SUB2',
			'SUB3' => 'SUB3'
		);
	}
	
	/* function fill_data()
	{		
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING	= $this->input->post('NAMA_REKENING');	$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$KATEGORI		= $this->input->post('KATEGORI');		$KATEGORI=($KATEGORI)?$KATEGORI:null;
		
		
		foreach($this->fieldmap as $key => $value){
			switch ($key){
				case 'LEVEL_REKENING' : $$key = '1'; break;
				case 'KELOMPOK' : $$key = ''; break;
				case 'JENIS' : $$key = ''; break;
				case 'OBJEK' : $$key = ''; break;
				case 'RINCIAN' : $$key = ''; break;
				case 'SUB1' : $$key = ''; break;
				case 'SUB2' : $$key = ''; break;
				case 'SUB3' : $$key = ''; break;
				case 'TIPE' : $$key	= ''; break;
				//case 'TIPE' : $$key	= $this->input->post('KODE_REKENING')?$this->input->post('KODE_REKENING'):null; break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			if(isset($$key))
				$this->data[$value] = $$key;
		}
	} */
	
	function fill_data()
	{
		$ID_MASTER_REKENING=$this->input->post('KATEGORI');$ID_MASTER_REKENING=($ID_MASTER_REKENING)?$ID_MASTER_REKENING:null;
		$KODE_REKENING=$this->input->post('KODE_REKENING');$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING=$this->input->post('NAMA_REKENING');$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$LEVEL_REKENING=$this->input->post('LEVEL_REKENING');$LEVEL_REKENING=($LEVEL_REKENING)?$LEVEL_REKENING:null;
		$TIPE= $this->input->post('TIPE'); $TIPE=($TIPE)?$TIPE:null;
		$KELOMPOK = $this->input->post('KELOMPOK');$KELOMPOK=($KELOMPOK)?$KELOMPOK:null;
		$JENIS= $this->input->post('JENIS');$JENIS=($JENIS)?$JENIS:null;
		$OBJEK = $this->input->post('OBJEK');$OBJEK=($OBJEK)?$OBJEK:null;
		$RINCIAN=$this->input->post('RINCIAN');$RINCIAN=($RINCIAN)?$RINCIAN:null;
		$SUB1=$this->input->post('SUB1');$SUB1=($SUB1)?$SUB1:null;
		$SUB2=$this->input->post('SUB2');$SUB2=($SUB2)?$SUB2:null;
		$SUB3=$this->input->post('SUB3');$SUB3=($SUB3)?implode("-",array_reverse(explode("/",$SUB3))):null;
		$SALDO_NORMAL=$this->input->post('SALDO_NORMAL');$SALDO_NORMAL=($SALDO_NORMAL)?$SALDO_NORMAL:null;
		$CHILD_COUNT=0;
		$child = explode('.',$KODE_REKENING);
		$last = count($child);
		if($last > 1)
		{
			unset($child[$last-1]);
			$parent = implode('.',$child);
		}
		$this->db->select('ID_REKENING');
		$this->db->where('KODE_REKENING', $parent);
		$par = $this->db->get('REKENING')->row_array();
		
		$this->data = array(
			'ID_PARENT_REKENING'=>$par['ID_REKENING'],
			'ID_MASTER_REKENING'=>$ID_MASTER_REKENING,
			'KODE_REKENING'=>$KODE_REKENING,
			'NAMA_REKENING'=>$NAMA_REKENING,
			'LEVEL_REKENING'=>$LEVEL_REKENING,
			'TIPE' => $TIPE,
			'KELOMPOK' => $KELOMPOK,
			'JENIS' => $JENIS,
			'OBJEK' => $OBJEK,
			'RINCIAN' => $RINCIAN,
			'SUB1' => $SUB1,
			'SUB2' => $SUB2,
			'SUB3'=> $SUB3,
			'CHILD_COUNT'=>$CHILD_COUNT
		);
		
		foreach($this->fieldmapA as $key => $value){
			if(isset($$key))
				$this->data[$value] = $$key;
		}
	}
		
  // ----- search advance ---- >>
  function get_data_fields()
  {  
    $kategori = $this->db->query("SELECT DISTINCT r.KODE_REKENING, r.NAMA_REKENING FROM MASTER_REKENING r")->result_array();
    foreach ($kategori as $key=>$val)
    {
      $opt_kategori[$val['KODE_REKENING']] = $val['NAMA_REKENING'];
    }

    $fields = array( 
              'TIPE' => array('name' => 'TIPE', 'kategori'=>'string'),
              'KELOMPOK' => array('name' => 'KELOMPOK', 'kategori'=>'string'),
              'JENIS' => array('name' => 'JENIS', 'kategori'=>'string'),
              'OBJEK' => array('name' => 'OBJEK', 'kategori'=>'string'),
              'RINCIAN' => array('name' => 'RINCIAN', 'kategori'=>'string'),
              'SUB1' => array('name' => 'SUB1', 'kategori'=>'string'),
              'SUB2' => array('name' => 'SUB2', 'kategori'=>'string'),
              'SUB3' => array('name' => 'SUB3', 'kategori'=>'string'),
              'KODER' => array('name' => 'KODE REKENING', 'kategori'=>'string'),
              'NAMAR' => array('name' => 'NAMA REKENING', 'kategori'=>'string'),
              'KATEGORI' => array('name' => 'KATEGORI', 'kategori'=>'predefined', 'options'=> $opt_kategori),
              'PAGU' => array('name' => 'PAGU', 'kategori'=>'numeric'),
             );
    
    return $fields;
  }

	function insert_data()
	{
		$this->fill_data();
		$this->db->trans_start();
		$result = $this->db->insert($this->_table, $this->data);		
		$newid = $this->db->query('select max(ID_REKENING) as ID from REKENING')->row_array();
		$this->db->trans_complete();
		return $newid['ID'];
	}

	function update_data($ID_REKENING)
	{
		$this->fill_data();
		$data = $this->fill_data();
		$this->db->trans_start();
		$this->db->where($this->_pk, $ID_REKENING);
		$update = $this->db->update($this->_table, $this->data);
		$this->db->trans_complete();
		return $update;
	}

	function delete_data($ID_REKENING)
	{
		$this->db->trans_start();
		$this->db->where($this->_pk, $ID_REKENING);
		$delete = $this->db->delete($this->_table);
		$this->db->trans_complete();
		return $delete;
	}
	
	function check_data()
	{
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING	= $this->input->post('NAMA_REKENING');	$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$KATEGORI		= $this->input->post('KATEGORI');		$KATEGORI=($KATEGORI)?$KATEGORI:null;
		
		$this->db->trans_start();
		$query = "SELECT * FROM REKENING WHERE  KODE_REKENING = '$KODE_REKENING' AND ID_MASTER_REKENING='$KATEGORI'";
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
	
	function check_data_induk()
	{
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		
		$this->db->trans_start();
		$child = explode('.',$KODE_REKENING);
		$last = count($child);
		if($last > 1)
		{
			unset($child[$last-1]);
			$parent = implode('.',$child);
		}
		$this->db->select('ID_REKENING');
		$this->db->where('KODE_REKENING', $parent);
		$par = $this->db->get('REKENING')->row_array();
		
		if(count($par) > 0){
			return FALSE;
		}
		else{
			return TRUE;
		}		
		$this->db->trans_complete();	
	}
	
	function check_data_1()
	{		
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING	= $this->input->post('NAMA_REKENING');	$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$KATEGORI		= $this->input->post('KATEGORI');		$KATEGORI=($KATEGORI)?$KATEGORI:null;
		
		$id = $this->db->query("select KODE_REKENING as KODE_REK from REKENING WHERE ID_REKENING='".$this->input->post('id')."'")->row_array();
		
		$this->db->select('KODE_REKENING');
		$this->db->where('KODE_REKENING', $KODE_REKENING);
		$result = $this->db->get('REKENING')->row_array();
		
		
		if($KODE_REKENING == $id['KODE_REK'] ){
			return TRUE;
		}
		else{ 
			if(count($result) > 0){
				return FALSE;
			}
			else{
				return TRUE;
			}
		}
	}
	
	function fill_data_kelompok()
	{			
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
			
		foreach($this->fieldmap_kelompok as $key => $value){
			switch ($key){
				case 'LEVEL_REKENING' : $$key = '2'; break;
				case 'ID_MASTER_REKENING' : $$key	= $this->input->post('KATEGORI')?$this->input->post('KATEGORI'):null; break;
				case 'TIPE' : $$key	= $TIPE?$TIPE:''; break;
				case 'KELOMPOK' : $$key	= $KELOMPOK?$KELOMPOK:''; break;
				
				case 'JENIS' : $$key = ''; break;
				case 'OBJEK' : $$key = ''; break;
				case 'RINCIAN' : $$key = ''; break;
				case 'SUB1' : $$key = ''; break;
				case 'SUB2' : $$key = ''; break;
				case 'SUB3' : $$key = ''; break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			if(isset($$key))
				$this->data_kelompok[$value] = $$key;
		}
	}
	
	function check_data_kelompok()
	{
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING	= $this->input->post('NAMA_REKENING');	$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$KATEGORI		= $this->input->post('KATEGORI');		$KATEGORI=($KATEGORI)?$KATEGORI:null;
		
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		
		$this->db->trans_start();
		$query = "SELECT * FROM REKENING WHERE  TIPE = '$TIPE' AND KELOMPOK = '$KELOMPOK' AND ID_MASTER_REKENING='$KATEGORI' AND LEVEL_REKENING='2'";
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
	
	function check_data_kelompok_1()
	{
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING	= $this->input->post('NAMA_REKENING');	$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$KATEGORI		= $this->input->post('KATEGORI');		$KATEGORI=($KATEGORI)?$KATEGORI:null;
		
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		
		$this->db->trans_start();
		
		$id = $this->db->query("select KODE_REKENING as KODE_REK from REKENING WHERE ID_REKENING='".$this->input->post('id')."'")->row_array();
		
		//$query = "SELECT * FROM REKENING WHERE  TIPE = '$TIPE' AND KELOMPOK = '$KELOMPOK' AND ID_MASTER_REKENING='$KATEGORI' AND LEVEL_REKENING='2'";
		//$result = $this->db->query($query);
		
		$query = "SELECT * FROM REKENING WHERE  KODE_REKENING = '$KODE_REKENING' AND LEVEL_REKENING='2'";
		$result = $this->db->query($query);
		
		if($KODE_REKENING == $id['KODE_REK'] ){
			return TRUE;
		}
		elseif($result->num_rows() > 0){
			return FALSE;
		}
		else{
			return TRUE;
		}		
		$this->db->trans_complete();	
	}
	
	function insert_data_kelompok()
	{
		$this->fill_data_kelompok();
		$this->db->trans_start();
		$result = $this->db->insert($this->_table, $this->data_kelompok);
		$newid = $this->db->query('select max(ID_REKENING) as ID from REKENING')->row_array();
		$this->db->trans_complete();
		return $newid['ID'];
	}

	function update_data_kelompok($ID_REKENING)
	{
		$this->fill_data_kelompok();
		$this->db->trans_start();
		$this->db->where($this->_pk, $ID_REKENING);
		$this->db->update($this->_table, $this->data_kelompok);
		$this->db->trans_complete();
		//return $update;
	}
	
	function fill_data_jenis()
	{			
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
			
		foreach($this->fieldmap_jenis as $key => $value){
			switch ($key){
				case 'LEVEL_REKENING' : $$key = '3'; break;
				case 'ID_MASTER_REKENING' : $$key	= $this->input->post('KATEGORI')?$this->input->post('KATEGORI'):null; break;
				case 'TIPE' : $$key	= $TIPE?$TIPE:''; break;
				case 'KELOMPOK' : $$key	= $KELOMPOK?$KELOMPOK:''; break;
				case 'JENIS' : $$key	= $JENIS?$JENIS:''; break;
				
				case 'OBJEK' 	: $$key = ''; break;
				case 'RINCIAN' 	: $$key = ''; break;
				case 'SUB1' 	: $$key = ''; break;
				case 'SUB2' 	: $$key = ''; break;
				case 'SUB3' 	: $$key = ''; break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			if(isset($$key))
				$this->data_jenis[$value] = $$key;
		}
	}
	
	function check_data_jenis()
	{
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING	= $this->input->post('NAMA_REKENING');	$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$KATEGORI		= $this->input->post('KATEGORI');		$KATEGORI=($KATEGORI)?$KATEGORI:null;
		
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
		
		$this->db->trans_start();
		$query = "SELECT * FROM REKENING WHERE  TIPE = '$TIPE' AND KELOMPOK = '$KELOMPOK' AND JENIS = '$JENIS' AND ID_MASTER_REKENING='$KATEGORI' AND LEVEL_REKENING='3'";
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
	
	function check_data_jenis_1()
	{
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING	= $this->input->post('NAMA_REKENING');	$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$KATEGORI		= $this->input->post('KATEGORI');		$KATEGORI=($KATEGORI)?$KATEGORI:null;
		
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
		
		$this->db->trans_start();
		/*$query = "SELECT * FROM REKENING WHERE  TIPE = '$TIPE' AND KELOMPOK = '$KELOMPOK' AND JENIS = '$JENIS' AND ID_MASTER_REKENING='$KATEGORI' AND LEVEL_REKENING='3'";
		$result = $this->db->query($query);
		
		if($result->num_rows() > 0){
			return TRUE;
		}
		else{
			return FALSE;
		}		*/
		
		
		$id = $this->db->query("select KODE_REKENING as KODE_REK from REKENING WHERE ID_REKENING='".$this->input->post('id')."'")->row_array();
		
		//$query = "SELECT * FROM REKENING WHERE  TIPE = '$TIPE' AND KELOMPOK = '$KELOMPOK' AND ID_MASTER_REKENING='$KATEGORI' AND LEVEL_REKENING='2'";
		//$result = $this->db->query($query);
		
		$query = "SELECT * FROM REKENING WHERE  KODE_REKENING = '$KODE_REKENING' AND LEVEL_REKENING='3'";
		$result = $this->db->query($query);
		
		if($KODE_REKENING == $id['KODE_REK'] ){
			return TRUE;
		}
		elseif($result->num_rows() > 0){
			return FALSE;
		}
		else{
			return TRUE;
		}		
		$this->db->trans_complete();	
	}
	
	function insert_data_jenis()
	{
		$this->fill_data_jenis();
		$this->db->trans_start();
		$result = $this->db->insert($this->_table, $this->data_jenis);
		$newid = $this->db->query('select max(ID_REKENING) as ID from REKENING')->row_array();
		$this->db->trans_complete();
		return $newid['ID'];
	}

	function update_data_jenis($ID_REKENING)
	{
		$this->fill_data_jenis();
		$this->db->trans_start();
		$this->db->where($this->_pk, $ID_REKENING);
		$this->db->update($this->_table, $this->data_jenis);
		$this->db->trans_complete();
		//return $update;
	}
	
	function fill_data_objek()
	{			
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
		$OBJEK	 	= $TIP_KEL[3];
			
		foreach($this->fieldmap_objek as $key => $value){
			switch ($key){
				case 'LEVEL_REKENING' : $$key = '4'; break;
				case 'ID_MASTER_REKENING' : $$key	= $this->input->post('KATEGORI')?$this->input->post('KATEGORI'):null; break;
				case 'TIPE' 	: $$key	= $TIPE?$TIPE:''; break;
				case 'KELOMPOK' : $$key	= $KELOMPOK?$KELOMPOK:''; break;
				case 'JENIS' 	: $$key	= $JENIS?$JENIS:''; break;
				case 'OBJEK' 	: $$key	= $OBJEK?$OBJEK:''; break;
				
				case 'RINCIAN' 	: $$key = ''; break;
				case 'SUB1' 	: $$key = ''; break;
				case 'SUB2' 	: $$key = ''; break;
				case 'SUB3' 	: $$key = ''; break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			if(isset($$key))
				$this->data_objek[$value] = $$key;
		}
	}
	
	function check_data_objek()
	{
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING	= $this->input->post('NAMA_REKENING');	$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$KATEGORI		= $this->input->post('KATEGORI');		$KATEGORI=($KATEGORI)?$KATEGORI:null;
		
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
		$OBJEK	 	= $TIP_KEL[3];
		
		$this->db->trans_start();
		$query = "SELECT * FROM REKENING WHERE  TIPE = '$TIPE' AND KELOMPOK = '$KELOMPOK' AND JENIS = '$JENIS' AND OBJEK = '$OBJEK' AND ID_MASTER_REKENING='$KATEGORI' AND LEVEL_REKENING='4'";
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
	
	function check_data_objek_1()
	{
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING	= $this->input->post('NAMA_REKENING');	$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$KATEGORI		= $this->input->post('KATEGORI');		$KATEGORI=($KATEGORI)?$KATEGORI:null;
		
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
		$OBJEK	 	= $TIP_KEL[3];
		
		$this->db->trans_start();
		//$query = "SELECT * FROM REKENING WHERE  TIPE = '$TIPE' AND KELOMPOK = '$KELOMPOK' AND JENIS = '$JENIS' AND OBJEK = '$OBJEK' AND ID_MASTER_REKENING='$KATEGORI' AND LEVEL_REKENING='4'";
		//$result = $this->db->query($query);
		
		$id = $this->db->query("select KODE_REKENING as KODE_REK from REKENING WHERE ID_REKENING='".$this->input->post('id')."'")->row_array();
		
		$query = "SELECT * FROM REKENING WHERE  KODE_REKENING = '$KODE_REKENING' AND LEVEL_REKENING='4'";
		$result = $this->db->query($query);
		
		if($KODE_REKENING == $id['KODE_REK'] ){
			return TRUE;
		}
		elseif($result->num_rows() > 0){
			return FALSE;
		}
		else{
			return TRUE;
		}		
		$this->db->trans_complete();	
	}
	
	function insert_data_objek()
	{
		$this->fill_data_objek();
		$this->db->trans_start();
		$result = $this->db->insert($this->_table, $this->data_objek);
		$newid = $this->db->query('select max(ID_REKENING) as ID from REKENING')->row_array();
		$this->db->trans_complete();
		return $newid['ID'];
	}

	function update_data_objek($ID_REKENING)
	{
		$this->fill_data_objek();
		$this->db->trans_start();
		$this->db->where($this->_pk, $ID_REKENING);
		$this->db->update($this->_table, $this->data_objek);
		$this->db->trans_complete();
		//return $update;
	}
	
	function fill_data_rincian()
	{			
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
		$OBJEK	 	= $TIP_KEL[3];
		$RINCIAN 	= $TIP_KEL[4];
			
		foreach($this->fieldmap_rincian as $key => $value){
			switch ($key){
				case 'LEVEL_REKENING' : $$key = '5'; break;
				case 'ID_MASTER_REKENING' : $$key	= $this->input->post('KATEGORI')?$this->input->post('KATEGORI'):null; break;
				case 'TIPE' 	: $$key	= $TIPE?$TIPE:''; break;
				case 'KELOMPOK' : $$key	= $KELOMPOK?$KELOMPOK:''; break;
				case 'JENIS' 	: $$key	= $JENIS?$JENIS:''; break;
				case 'OBJEK' 	: $$key	= $OBJEK?$OBJEK:''; break;
				case 'RINCIAN' 	: $$key	= $RINCIAN?$RINCIAN:''; break;
				
				case 'SUB1' 	: $$key = ''; break;
				case 'SUB2' 	: $$key = ''; break;
				case 'SUB3' 	: $$key = ''; break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			if(isset($$key))
				$this->data_rincian[$value] = $$key;
		}
	}
	
	function check_data_rincian()
	{
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING	= $this->input->post('NAMA_REKENING');	$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$KATEGORI		= $this->input->post('KATEGORI');		$KATEGORI=($KATEGORI)?$KATEGORI:null;
		
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
		$OBJEK	 	= $TIP_KEL[3];
		$RINCIAN 	= $TIP_KEL[4];
		
		$this->db->trans_start();
		$query = "SELECT * FROM REKENING WHERE  TIPE = '$TIPE' AND KELOMPOK = '$KELOMPOK' AND JENIS = '$JENIS' AND OBJEK = '$OBJEK' AND RINCIAN = '$RINCIAN' AND ID_MASTER_REKENING='$KATEGORI' AND LEVEL_REKENING='5'";
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
	
	function check_data_rincian_1()
	{
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING	= $this->input->post('NAMA_REKENING');	$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$KATEGORI		= $this->input->post('KATEGORI');		$KATEGORI=($KATEGORI)?$KATEGORI:null;
		
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
		$OBJEK	 	= $TIP_KEL[3];
		$RINCIAN 	= $TIP_KEL[4];
		
		$this->db->trans_start();		
		$id = $this->db->query("select KODE_REKENING as KODE_REK from REKENING WHERE ID_REKENING='".$this->input->post('id')."'")->row_array();
		
		$query = "SELECT * FROM REKENING WHERE  KODE_REKENING = '$KODE_REKENING' AND LEVEL_REKENING='5'";
		$result = $this->db->query($query);
		
		if($KODE_REKENING == $id['KODE_REK'] ){
			return TRUE;
		}
		elseif($result->num_rows() > 0){
			return FALSE;
		}
		else{
			return TRUE;
		}		
		$this->db->trans_complete();	
	}
	
	function insert_data_rincian()
	{
		$this->fill_data_rincian();
		$this->db->trans_start();
		$result = $this->db->insert($this->_table, $this->data_rincian);
		$newid = $this->db->query('select max(ID_REKENING) as ID from REKENING')->row_array();
		$this->db->trans_complete();
		return $newid['ID'];
	}

	function update_data_rincian($ID_REKENING)
	{
		$this->fill_data_rincian();
		$this->db->trans_start();
		$this->db->where($this->_pk, $ID_REKENING);
		$this->db->update($this->_table, $this->data_rincian);
		$this->db->trans_complete();
		//return $update;
	}

	function fill_data_sub1()
	{			
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
		$OBJEK	 	= $TIP_KEL[3];
		$RINCIAN 	= $TIP_KEL[4];
		$SUB1	 	= $TIP_KEL[5];
			
		foreach($this->fieldmap_sub1 as $key => $value){
			switch ($key){
				case 'LEVEL_REKENING' : $$key = '6'; break;
				case 'ID_MASTER_REKENING' : $$key	= $this->input->post('KATEGORI')?$this->input->post('KATEGORI'):null; break;
				case 'TIPE' : $$key	= $TIPE?$TIPE:''; break;
				case 'KELOMPOK' : $$key	= $KELOMPOK?$KELOMPOK:''; break;
				case 'JENIS' 	: $$key	= $JENIS?$JENIS:''; break;
				case 'OBJEK' 	: $$key	= $OBJEK?$OBJEK:''; break;
				case 'RINCIAN' 	: $$key	= $RINCIAN?$RINCIAN:''; break;
				case 'SUB1' 	: $$key	= $SUB1?$SUB1:''; break;
				
				case 'SUB2' 	: $$key = ''; break;
				case 'SUB3' 	: $$key = ''; break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			if(isset($$key))
				$this->data_sub1[$value] = $$key;
		}
	}
	
	function check_data_sub1()
	{
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING	= $this->input->post('NAMA_REKENING');	$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$KATEGORI		= $this->input->post('KATEGORI');		$KATEGORI=($KATEGORI)?$KATEGORI:null;
		
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
		$OBJEK	 	= $TIP_KEL[3];
		$RINCIAN 	= $TIP_KEL[4];
		$SUB1	 	= $TIP_KEL[5];
		
		$this->db->trans_start();
		$query = "SELECT * FROM REKENING WHERE  TIPE = '$TIPE' AND KELOMPOK = '$KELOMPOK' AND JENIS = '$JENIS' AND OBJEK = '$OBJEK' AND RINCIAN = '$RINCIAN' AND SUB1 = '$SUB1' AND ID_MASTER_REKENING='$KATEGORI' AND LEVEL_REKENING='6'";
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
	
	function check_data_sub1_1()
	{
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING	= $this->input->post('NAMA_REKENING');	$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$KATEGORI		= $this->input->post('KATEGORI');		$KATEGORI=($KATEGORI)?$KATEGORI:null;
		
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
		$OBJEK	 	= $TIP_KEL[3];
		$RINCIAN 	= $TIP_KEL[4];
		$SUB1	 	= $TIP_KEL[5];
		
		$this->db->trans_start();		
		$id = $this->db->query("select KODE_REKENING as KODE_REK from REKENING WHERE ID_REKENING='".$this->input->post('id')."'")->row_array();
		
		$query = "SELECT * FROM REKENING WHERE  KODE_REKENING = '$KODE_REKENING' AND LEVEL_REKENING='6'";
		$result = $this->db->query($query);
		
		if($KODE_REKENING == $id['KODE_REK'] ){
			return TRUE;
		}
		elseif($result->num_rows() > 0){
			return FALSE;
		}
		else{
			return TRUE;
		}		
		$this->db->trans_complete();	
	}
	
	function insert_data_sub1()
	{
		$this->fill_data_sub1();
		$this->db->trans_start();
		$result = $this->db->insert($this->_table, $this->data_sub1);
		$newid = $this->db->query('select max(ID_REKENING) as ID from REKENING')->row_array();
		$this->db->trans_complete();
		return $newid['ID'];
	}

	function update_data_sub1($ID_REKENING)
	{
		$this->fill_data_sub1();
		$this->db->trans_start();
		$this->db->where($this->_pk, $ID_REKENING);
		$this->db->update($this->_table, $this->data_sub1);
		$this->db->trans_complete();
		//return $update;
	}
	
	function fill_data_sub2()
	{			
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
		$OBJEK	 	= $TIP_KEL[3];
		$RINCIAN 	= $TIP_KEL[4];
		$SUB1	 	= $TIP_KEL[5];
		$SUB2	 	= $TIP_KEL[6];
			
		foreach($this->fieldmap_sub2 as $key => $value){
			switch ($key){
				case 'LEVEL_REKENING' : $$key = '7'; break;
				case 'ID_MASTER_REKENING' : $$key	= $this->input->post('KATEGORI')?$this->input->post('KATEGORI'):null; break;
				case 'TIPE' : $$key	= $TIPE?$TIPE:''; break;
				case 'KELOMPOK' : $$key	= $KELOMPOK?$KELOMPOK:''; break;
				case 'JENIS' 	: $$key	= $JENIS?$JENIS:''; break;
				case 'OBJEK' 	: $$key	= $OBJEK?$OBJEK:''; break;
				case 'RINCIAN' 	: $$key	= $RINCIAN?$RINCIAN:''; break;
				case 'SUB1' 	: $$key	= $SUB1?$SUB1:''; break;
				case 'SUB2' 	: $$key	= $SUB2?$SUB2:''; break;
				
				case 'SUB3' 	: $$key = ''; break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			if(isset($$key))
				$this->data_sub2[$value] = $$key;
		}
	}
	
	function check_data_sub2()
	{
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING	= $this->input->post('NAMA_REKENING');	$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$KATEGORI		= $this->input->post('KATEGORI');		$KATEGORI=($KATEGORI)?$KATEGORI:null;
		
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
		$OBJEK	 	= $TIP_KEL[3];
		$RINCIAN 	= $TIP_KEL[4];
		$SUB1	 	= $TIP_KEL[5];
		$SUB2	 	= $TIP_KEL[6];
		
		$this->db->trans_start();
		$query = "SELECT * FROM REKENING WHERE  TIPE = '$TIPE' AND KELOMPOK = '$KELOMPOK' AND JENIS = '$JENIS' AND OBJEK = '$OBJEK' AND RINCIAN = '$RINCIAN' AND SUB1 = '$SUB1' AND SUB2 = '$SUB2' AND ID_MASTER_REKENING='$KATEGORI' AND LEVEL_REKENING='7'";
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
	
	function check_data_sub2_1()
	{
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING	= $this->input->post('NAMA_REKENING');	$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$KATEGORI		= $this->input->post('KATEGORI');		$KATEGORI=($KATEGORI)?$KATEGORI:null;
		
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
		$OBJEK	 	= $TIP_KEL[3];
		$RINCIAN 	= $TIP_KEL[4];
		$SUB1	 	= $TIP_KEL[5];
		$SUB2	 	= $TIP_KEL[6];
		
		$this->db->trans_start();		
		$id = $this->db->query("select KODE_REKENING as KODE_REK from REKENING WHERE ID_REKENING='".$this->input->post('id')."'")->row_array();
		
		$query = "SELECT * FROM REKENING WHERE  KODE_REKENING = '$KODE_REKENING' AND LEVEL_REKENING='7'";
		$result = $this->db->query($query);
		
		if($KODE_REKENING == $id['KODE_REK'] ){
			return TRUE;
		}
		elseif($result->num_rows() > 0){
			return FALSE;
		}
		else{
			return TRUE;
		}		
		$this->db->trans_complete();	
	}
	
	function insert_data_sub2()
	{
		$this->fill_data_sub2();
		$this->db->trans_start();
		$result = $this->db->insert($this->_table, $this->data_sub2);
		$newid = $this->db->query('select max(ID_REKENING) as ID from REKENING')->row_array();
		$this->db->trans_complete();
		return $newid['ID'];
	}

	function update_data_sub2($ID_REKENING)
	{
		$this->fill_data_sub2();
		$this->db->trans_start();
		$this->db->where($this->_pk, $ID_REKENING);
		$this->db->update($this->_table, $this->data_sub2);
		$this->db->trans_complete();
		//return $update;
	}
	
	function fill_data_sub3()
	{			
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
		$OBJEK	 	= $TIP_KEL[3];
		$RINCIAN 	= $TIP_KEL[4];
		$SUB1	 	= $TIP_KEL[5];
		$SUB2	 	= $TIP_KEL[6];
		$SUB3	 	= $TIP_KEL[7];
			
		foreach($this->fieldmap_sub3 as $key => $value){
			switch ($key){
				case 'LEVEL_REKENING' : $$key = '8'; break;
				case 'ID_MASTER_REKENING' : $$key	= $this->input->post('KATEGORI')?$this->input->post('KATEGORI'):null; break;
				case 'TIPE' : $$key	= $TIPE?$TIPE:''; break;
				case 'KELOMPOK' : $$key	= $KELOMPOK?$KELOMPOK:''; break;
				case 'JENIS' 	: $$key	= $JENIS?$JENIS:''; break;
				case 'OBJEK' 	: $$key	= $OBJEK?$OBJEK:''; break;
				case 'RINCIAN' 	: $$key	= $RINCIAN?$RINCIAN:''; break;
				case 'SUB1' 	: $$key	= $SUB1?$SUB1:''; break;
				case 'SUB2' 	: $$key	= $SUB2?$SUB2:''; break;
				case 'SUB3' 	: $$key	= $SUB3?$SUB3:''; break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			if(isset($$key))
				$this->data_sub3[$value] = $$key;
		}
	}
	
	function check_data_sub3()
	{
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING	= $this->input->post('NAMA_REKENING');	$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$KATEGORI		= $this->input->post('KATEGORI');		$KATEGORI=($KATEGORI)?$KATEGORI:null;
		
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
		$OBJEK	 	= $TIP_KEL[3];
		$RINCIAN 	= $TIP_KEL[4];
		$SUB1	 	= $TIP_KEL[5];
		$SUB2	 	= $TIP_KEL[6];
		$SUB3	 	= $TIP_KEL[7];
		
		$this->db->trans_start();
		$query = "SELECT * FROM REKENING WHERE  TIPE = '$TIPE' AND KELOMPOK = '$KELOMPOK' AND JENIS = '$JENIS' AND OBJEK = '$OBJEK' AND RINCIAN = '$RINCIAN' AND SUB1 = '$SUB1' AND SUB2 = '$SUB2' AND SUB3 = '$SUB3' AND ID_MASTER_REKENING='$KATEGORI' AND LEVEL_REKENING='8'";
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
	
	function check_data_sub3_1()
	{
		$KODE_REKENING	= $this->input->post('KODE_REKENING');	$KODE_REKENING=($KODE_REKENING)?$KODE_REKENING:null;
		$NAMA_REKENING	= $this->input->post('NAMA_REKENING');	$NAMA_REKENING=($NAMA_REKENING)?$NAMA_REKENING:null;
		$KATEGORI		= $this->input->post('KATEGORI');		$KATEGORI=($KATEGORI)?$KATEGORI:null;
		
		$TIP_KEL 	= explode(".",$this->input->post('KODE_REKENING'));
		$TIPE 		= $TIP_KEL[0];
		$KELOMPOK 	= $TIP_KEL[1];
		$JENIS	 	= $TIP_KEL[2];
		$OBJEK	 	= $TIP_KEL[3];
		$RINCIAN 	= $TIP_KEL[4];
		$SUB1	 	= $TIP_KEL[5];
		$SUB2	 	= $TIP_KEL[6];
		$SUB3	 	= $TIP_KEL[7];
		
		$this->db->trans_start();		
		$id = $this->db->query("select KODE_REKENING as KODE_REK from REKENING WHERE ID_REKENING='".$this->input->post('id')."'")->row_array();
		
		$query = "SELECT * FROM REKENING WHERE  KODE_REKENING = '$KODE_REKENING' AND LEVEL_REKENING='8'";
		$result = $this->db->query($query);
		
		if($KODE_REKENING == $id['KODE_REK'] ){
			return TRUE;
		}
		elseif($result->num_rows() > 0){
			return FALSE;
		}
		else{
			return TRUE;
		}		
		$this->db->trans_complete();	
	}
	
	function insert_data_sub3()
	{
		$this->fill_data_sub3();
		$this->db->trans_start();
		$result = $this->db->insert($this->_table, $this->data_sub3);
		$newid = $this->db->query('select max(ID_REKENING) as ID from REKENING')->row_array();
		$this->db->trans_complete();
		return $newid['ID'];
	}

	function update_data_sub3($ID_REKENING)
	{
		$this->fill_data_sub3();
		$this->db->trans_start();
		$this->db->where($this->_pk, $ID_REKENING);
		$this->db->update($this->_table, $this->data_sub3);
		$this->db->trans_complete();
		//return $update;
	}
	
	/* function get_data($param)
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
		
		$tahun	= $this->session->userdata('tahun');
		$status = $this->session->userdata('status');
		$status1 = "'".$status."'";
		
		//returns the query string
		$this->db->trans_start();
		$this->db->select(
			'R.ID_REKENING,      
				R.KODE_REKENING, 
				R.NAMA_REKENING,      
				MR.NAMA_REKENING as KATEGORI,    
				(select sum(ra.PAGU)
				  from FORM_ANGGARAN fa
				  join RINCIAN_ANGGARAN ra on ra.ID_FORM_ANGGARAN = fa.ID_FORM_ANGGARAN
				  join REKENING re on re.ID_REKENING=ra.ID_REKENING 
				  where fa.TAHUN = '.$tahun.'
					and fa.STATUS = '.$status1.'
					and re.TIPE = r.TIPE
				) PAGU
			');
		$this->db->from('REKENING R');
		$this->db->join('MASTER_REKENING MR', 'R.ID_MASTER_REKENING = MR.ID_MASTER_REKENING');
		$this->db->where('R.ID_PARENT_REKENING',null);
		$this->db->order_by('R.KODE_REKENING');
		$result = $this->db->get();
		//print_r($this->db->last_query());die();
		return $result;
		$this->db->trans_complete();
	} */
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
			'R.ID_REKENING,   
				COALESCE(R.ID_PARENT_REKENING, 0) AS ID_PARENT_REKENING, 
				R.ID_MASTER_REKENING,    
				R.KODE_REKENING, 
				R.NAMA_REKENING,    
				R.TIPE, 
				R.KELOMPOK, 
				R.JENIS, 
				R.OBJEK, 
				R.RINCIAN,    
				R.SUB1, 
				R.SUB2, 
				R.SUB3,    
				MR.NAMA_REKENING as KATEGORI,    
				(select sum(ra.PAGU)
				  from FORM_ANGGARAN fa
				  join RINCIAN_ANGGARAN ra on ra.ID_FORM_ANGGARAN = fa.ID_FORM_ANGGARAN
				  where fa.TAHUN = '.$this->tahun.'
					and fa.STATUS = '.$this->status1.'
					and ra.ID_REKENING = r.ID_REKENING
				) PAGU
			');
		$this->db->from('REKENING R');
		$this->db->join('MASTER_REKENING MR', 'R.ID_MASTER_REKENING = MR.ID_MASTER_REKENING');

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
        $this->db->order_by('R.KODE_REKENING');
        $result = $this->db->get()->result_array();
        return $result;
      }
    }

		$this->db->trans_complete();
	}
	
	function get_data_subrekening_kelompok($param,$tipe)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			if (array_key_exists($param['search_field'], $this->fieldmap_kelompok)) {
				$wh = "UPPER(".$this->fieldmap_kelompok[$param['search_field']].")";
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
		
		$tahun	= $this->session->userdata('tahun');
		$status = $this->session->userdata('status');
		$status1 = "'".$status."'";
		
		//returns the query string
		$this->db->trans_start();
		$this->db->select(
			'R.ID_REKENING, 
				R.ID_PARENT_REKENING, 
				R.KODE_REKENING, 
				R.NAMA_REKENING,      
				MR.NAMA_REKENING as KATEGORI,    
				(select sum(ra.PAGU)
				  from FORM_ANGGARAN fa
				  join RINCIAN_ANGGARAN ra on ra.ID_FORM_ANGGARAN = fa.ID_FORM_ANGGARAN
				  join REKENING re on re.ID_REKENING=ra.ID_REKENING 
				  where fa.TAHUN = '.$tahun.'
					and fa.STATUS = '.$status1.'
					and re.TIPE =r.tipe 
					and re.kelompok=r.kelompok
				) PAGU
			');
		$this->db->from('REKENING R');
		$this->db->join('MASTER_REKENING MR', 'R.ID_MASTER_REKENING = MR.ID_MASTER_REKENING');
		$this->db->where('R.ID_PARENT_REKENING',$tipe);
		$this->db->where('R.LEVEL_REKENING','2');
		$this->db->order_by('R.KODE_REKENING');
		$result = $this->db->get();
		//print_r($this->db->last_query());die();
		return $result;
		$this->db->trans_complete();
	}
	
	function get_data_subrekening_jenis($param,$tipe)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			if (array_key_exists($param['search_field'], $this->fieldmap_jenis)) {
				$wh = "UPPER(".$this->fieldmap_jenis[$param['search_field']].")";
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
		
		$tahun	= $this->session->userdata('tahun');
		$status = $this->session->userdata('status');
		$status1 = "'".$status."'";
		
		//returns the query string
		$this->db->trans_start();
		$this->db->select(
			'R.ID_REKENING, 
				R.ID_PARENT_REKENING, 
				R.KODE_REKENING, 
				R.NAMA_REKENING,      
				MR.NAMA_REKENING as KATEGORI,    
				(select sum(ra.PAGU)
				  from FORM_ANGGARAN fa
				  join RINCIAN_ANGGARAN ra on ra.ID_FORM_ANGGARAN = fa.ID_FORM_ANGGARAN
				  join REKENING re on re.ID_REKENING=ra.ID_REKENING 
				  where fa.TAHUN = '.$tahun.'
					and fa.STATUS = '.$status1.'
					and re.TIPE =r.tipe 
					and re.kelompok=r.kelompok
					and re.jenis=r.jenis
				) PAGU
			');
		$this->db->from('REKENING R');
		$this->db->join('MASTER_REKENING MR', 'R.ID_MASTER_REKENING = MR.ID_MASTER_REKENING');
		$this->db->where('R.ID_PARENT_REKENING',$tipe);
		$this->db->where('R.LEVEL_REKENING','3');
		$this->db->order_by('R.KODE_REKENING');
		$result = $this->db->get();
		//print_r($this->db->last_query());die();
		return $result;
		$this->db->trans_complete();
	}
	
	function get_data_subrekening_objek($param,$tipe)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			if (array_key_exists($param['search_field'], $this->fieldmap_objek)) {
				$wh = "UPPER(".$this->fieldmap_objek[$param['search_field']].")";
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
		
		$tahun	= $this->session->userdata('tahun');
		$status = $this->session->userdata('status');
		$status1 = "'".$status."'";
		
		//returns the query string
		$this->db->trans_start();
		$this->db->select(
			'R.ID_REKENING, 
				R.ID_PARENT_REKENING, 
				R.KODE_REKENING, 
				R.NAMA_REKENING,      
				MR.NAMA_REKENING as KATEGORI,    
				(select sum(ra.PAGU)
				  from FORM_ANGGARAN fa
				  join RINCIAN_ANGGARAN ra on ra.ID_FORM_ANGGARAN = fa.ID_FORM_ANGGARAN
				  join REKENING re on re.ID_REKENING=ra.ID_REKENING 
				  where fa.TAHUN = '.$tahun.'
					and fa.STATUS = '.$status1.'
					and re.TIPE =r.tipe 
					and re.kelompok=r.kelompok
					and re.jenis=r.jenis
					and re.objek = r.objek
				) PAGU
			');
		$this->db->from('REKENING R');
		$this->db->join('MASTER_REKENING MR', 'R.ID_MASTER_REKENING = MR.ID_MASTER_REKENING');
		$this->db->where('R.ID_PARENT_REKENING',$tipe);
		$this->db->where('R.LEVEL_REKENING','4');
		$this->db->order_by('R.KODE_REKENING');
		$result = $this->db->get();
		//print_r($this->db->last_query());die();
		return $result;
		$this->db->trans_complete();
	}
	
	function get_data_subrekening_rincian($param,$tipe)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			if (array_key_exists($param['search_field'], $this->fieldmap_rincian)) {
				$wh = "UPPER(".$this->fieldmap_rincian[$param['search_field']].")";
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
		
		$tahun	= $this->session->userdata('tahun');
		$status = $this->session->userdata('status');
		$status1 = "'".$status."'";
		
		//returns the query string
		$this->db->trans_start();
		$this->db->select(
			'R.ID_REKENING, 
				R.ID_PARENT_REKENING, 
				R.KODE_REKENING, 
				R.NAMA_REKENING,      
				MR.NAMA_REKENING as KATEGORI,    
				(select sum(ra.PAGU)
				  from FORM_ANGGARAN fa
				  join RINCIAN_ANGGARAN ra on ra.ID_FORM_ANGGARAN = fa.ID_FORM_ANGGARAN
				  where fa.TAHUN = '.$tahun.'
					and fa.STATUS = '.$status1.'
					and ra.ID_REKENING = r.ID_REKENING
				) PAGU
			');
		$this->db->from('REKENING R');
		$this->db->join('MASTER_REKENING MR', 'R.ID_MASTER_REKENING = MR.ID_MASTER_REKENING');
		$this->db->where('R.ID_PARENT_REKENING',$tipe);
		$this->db->where('R.LEVEL_REKENING','5');
		$this->db->order_by('R.KODE_REKENING');
		$result = $this->db->get();
		//print_r($this->db->last_query());die();
		return $result;
		$this->db->trans_complete();
	}
	
	function get_data_subrekening_sub1($param,$tipe)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			if (array_key_exists($param['search_field'], $this->fieldmap_sub1)) {
				$wh = "UPPER(".$this->fieldmap_sub1[$param['search_field']].")";
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
		
		$tahun	= $this->session->userdata('tahun');
		$status = $this->session->userdata('status');
		$status1 = "'".$status."'";
		
		//returns the query string
		$this->db->trans_start();
		$this->db->select(
			'R.ID_REKENING, 
				R.ID_PARENT_REKENING, 
				R.KODE_REKENING, 
				R.NAMA_REKENING,      
				MR.NAMA_REKENING as KATEGORI,    
				(select sum(ra.PAGU)
				  from FORM_ANGGARAN fa
				  join RINCIAN_ANGGARAN ra on ra.ID_FORM_ANGGARAN = fa.ID_FORM_ANGGARAN
				  where fa.TAHUN = '.$tahun.'
					and fa.STATUS = '.$status1.'
					and ra.ID_REKENING = r.ID_REKENING
				) PAGU
			');
		$this->db->from('REKENING R');
		$this->db->join('MASTER_REKENING MR', 'R.ID_MASTER_REKENING = MR.ID_MASTER_REKENING');
		$this->db->where('R.ID_PARENT_REKENING',$tipe);
		$this->db->where('R.LEVEL_REKENING','6');
		$this->db->order_by('R.KODE_REKENING');
		$result = $this->db->get();
		//print_r($this->db->last_query());die();
		return $result;
		$this->db->trans_complete();
	}
	
	function get_data_subrekening_sub2($param,$tipe)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			if (array_key_exists($param['search_field'], $this->fieldmap_sub2)) {
				$wh = "UPPER(".$this->fieldmap_sub2[$param['search_field']].")";
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
		
		$tahun	= $this->session->userdata('tahun');
		$status = $this->session->userdata('status');
		$status1 = "'".$status."'";
		
		//returns the query string
		$this->db->trans_start();
		$this->db->select(
			'R.ID_REKENING, 
				R.ID_PARENT_REKENING, 
				R.KODE_REKENING, 
				R.NAMA_REKENING,      
				MR.NAMA_REKENING as KATEGORI,    
				(select sum(ra.PAGU)
				  from FORM_ANGGARAN fa
				  join RINCIAN_ANGGARAN ra on ra.ID_FORM_ANGGARAN = fa.ID_FORM_ANGGARAN
				  where fa.TAHUN = '.$tahun.'
					and fa.STATUS = '.$status1.'
					and ra.ID_REKENING = r.ID_REKENING
				) PAGU
			');
		$this->db->from('REKENING R');
		$this->db->join('MASTER_REKENING MR', 'R.ID_MASTER_REKENING = MR.ID_MASTER_REKENING');
		$this->db->where('R.ID_PARENT_REKENING',$tipe);
		$this->db->where('R.LEVEL_REKENING','7');
		$this->db->order_by('R.KODE_REKENING');
		$result = $this->db->get();
		//print_r($this->db->last_query());die();
		return $result;
		$this->db->trans_complete();
	}
	
	function get_data_subrekening_sub3($param,$tipe)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			if (array_key_exists($param['search_field'], $this->fieldmap_sub3)) {
				$wh = "UPPER(".$this->fieldmap_sub3[$param['search_field']].")";
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
		
		$tahun	= $this->session->userdata('tahun');
		$status = $this->session->userdata('status');
		$status1 = "'".$status."'";
		
		//returns the query string
		$this->db->trans_start();
		$this->db->select(
			'R.ID_REKENING, 
				R.ID_PARENT_REKENING, 
				R.KODE_REKENING, 
				R.NAMA_REKENING,      
				MR.NAMA_REKENING as KATEGORI,    
				(select sum(ra.PAGU)
				  from FORM_ANGGARAN fa
				  join RINCIAN_ANGGARAN ra on ra.ID_FORM_ANGGARAN = fa.ID_FORM_ANGGARAN
				  where fa.TAHUN = '.$tahun.'
					and fa.STATUS = '.$status1.'
					and ra.ID_REKENING = R.ID_REKENING
				) PAGU
			');
		$this->db->from('REKENING R');
		$this->db->join('MASTER_REKENING MR', 'R.ID_MASTER_REKENING = MR.ID_MASTER_REKENING');
		$this->db->where('R.ID_PARENT_REKENING',$tipe);
		$this->db->where('R.LEVEL_REKENING','8');
		$this->db->order_by('R.KODE_REKENING');
		$result = $this->db->get();
		//print_r($this->db->last_query());die();
		return $result;
		$this->db->trans_complete();
	}
	
	function get_data_rekening($param)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			//if (array_key_exists($param['search_field'], $this->fieldmap)) {
				//$wh = "UPPER(".$this->fieldmap[$param['search_field']].")";
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
				'ID_REKENING,   
				KODE_REKENING, 
				NAMA_REKENING,
        LEVEL_REKENING
			');
		$this->db->from('REKENING');
		//$this->db->join('MASTER_REKENING MR', 'R.ID_MASTER_REKENING = MR.ID_MASTER_REKENING');
		$this->db->order_by('KODE_REKENING');
		$result = $this->db->get();
		//print_r($this->db->last_query());die();
		return $result;
		$this->db->trans_complete();
	}
					
	/* option Kategori */
	function get_kategori()
	{
		$query = 
			'Select mr.ID_MASTER_REKENING, mr.NAMA_REKENING
			from MASTER_REKENING mr
			order by mr.KODE_REKENING
			';
			
		$return = $this->db->query($query);
		return $return;
	}
	
	function get_opt_kategori()
	{
		$return = array(
		''=>''
		); 
		
		$result = $this->get_kategori();
		
		foreach($result->result_array() as $row)
		{
			$return[$row['ID_MASTER_REKENING']]=$row['NAMA_REKENING'];
		}
				
		return $return;	
	}
	
	function get_data_by_id_2($id=0)
	{
		$tahun	= $this->session->userdata('tahun');
		$status = $this->session->userdata('status');
		$status1 = "'".$status."'";
		
		$query = 
			'select
				R.ID_REKENING,   
				COALESCE(R.ID_PARENT_REKENING, 0) AS ID_PARENT_REKENING, 
				R.ID_MASTER_REKENING,    
				R.KODE_REKENING, 
				R.NAMA_REKENING,    
				R.TIPE, 
				R.KELOMPOK, 
				R.JENIS, 
				R.OBJEK, 
				R.RINCIAN,    
				R.SUB1, 
				R.SUB2, 
				R.SUB3,    
				MR.NAMA_REKENING as KATEGORI,    
				(select sum(ra.PAGU)
				  from FORM_ANGGARAN fa
				  join RINCIAN_ANGGARAN ra on ra.ID_FORM_ANGGARAN = fa.ID_FORM_ANGGARAN
				  where fa.TAHUN = '.$tahun.'
					and fa.STATUS = '.$status1.'
					and ra.ID_REKENING = r.ID_REKENING
				) PAGU
			FROM REKENING R
			JOIN MASTER_REKENING MR ON R.ID_MASTER_REKENING = MR.ID_MASTER_REKENING
			where R.ID_REKENING = '.$id.'
			';
			
		$result = $this->db->query($query);
		return $result;
	}
		
	function check_dependency_1($ID_REKENING)
	{
		$this->db->trans_start();
		$this->db->select('a.CHILD_COUNT');
		$this->db->where('a.ID_REKENING', $ID_REKENING);
		$result = $this->db->get('REKENING a')->row_array();
		$this->db->trans_complete();
		
		if ($result['CHILD_COUNT'] > 0) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
	
	function check_dependency_2($ID_REKENING)
	{
		$this->db->trans_start();
		$this->db->select('a.ID_REKENING');
		$this->db->select('(select count(b.ID_PARENT_REKENING) from REKENING b where b.ID_PARENT_REKENING = a.ID_REKENING) REKENING_PAKAI'); 
		$this->db->select('(select count(b.ID_REKENING_BT) from SKPD b where b.ID_REKENING_BT = a.ID_REKENING) SKPD_BT_PAKAI'); 
		$this->db->select('(select count(b.ID_REKENING_BK) from SKPD b where b.ID_REKENING_BK = a.ID_REKENING) SKPD_BK_PAKAI'); 
		$this->db->select('(select count(b.ID_REKENING) from DETIL_REKENING b where b.ID_REKENING = a.ID_REKENING) DETIL_REKENING_PAKAI'); 
		$this->db->where('a.ID_REKENING', $ID_REKENING);
		$result = $this->db->get('REKENING a')->row_array();
		$this->db->trans_complete();
		
		if (
        $result['REKENING_PAKAI'] > 0 || 
        $result['SKPD_BT_PAKAI'] > 0 || 
        $result['SKPD_BK_PAKAI'] > 0 || 
        $result['DETIL_REKENING_PAKAI'] > 0 ) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
	
	function check_dependency_3($ID_REKENING)
	{
		$this->db->trans_start();
		$this->db->select('a.ID_REKENING');
		$this->db->select('(select count(b.ID_REKENING) from RINCIAN_ANGGARAN_KAS b where b.ID_REKENING = a.ID_REKENING) RINCIAN_ANGGARAN_KAS_PAKAI'); 
		$this->db->select('(select count(b.ID_REKENING) from SUMBER_DANA b where b.ID_REKENING = a.ID_REKENING) SUMBER_DANA_PAKAI');
		$this->db->select('(select count(b.ID_REKENING_KASDA) from SKPD b where b.ID_REKENING_KASDA = a.ID_REKENING) SKPD_KASDA_PAKAI');
		$this->db->select('(select count(b.ID_REKENING) from RINCIAN_RKA b where b.ID_REKENING = a.ID_REKENING) RINCIAN_RKA_PAKAI'); 
		$this->db->select('(select count(b.ID_REKENING) from RINCIAN_SPP b where b.ID_REKENING = a.ID_REKENING) RINCIAN_SPP_PAKAI'); 
		$this->db->select('(select count(b.ID_REKENING) from PAJAK b where b.ID_REKENING = a.ID_REKENING) PAJAK_PAKAI'); 
		$this->db->where('a.ID_REKENING', $ID_REKENING);
		$result = $this->db->get('REKENING a')->row_array();
		$this->db->trans_complete();
		
		if (
      $result['RINCIAN_ANGGARAN_KAS_PAKAI'] > 0 || 
      $result['SUMBER_DANA_PAKAI'] > 0 || 
      $result['SKPD_KASDA_PAKAI'] > 0 || 
      $result['RINCIAN_RKA_PAKAI'] > 0 || 
      $result['RINCIAN_SPP_PAKAI'] > 0 || 
      $result['PAJAK_PAKAI'] > 0 
      ) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
	
	function check_dependency_4($ID_REKENING)
	{
		$this->db->trans_start();
		$this->db->select('a.ID_REKENING');
		$this->db->select('(select count(b.ID_REKENING) from RINCIAN_SPP b where b.ID_REKENING = a.ID_REKENING) RINCIAN_SPP_PAKAI'); 
		$this->db->select('(select count(b.ID_REKENING) from RINCIAN_SPP_PFK b where b.ID_REKENING = a.ID_REKENING) RINCIAN_SPP_PFK_PAKAI'); 
		$this->db->select('(select count(b.ID_REKENING_PEKAS) from SP2D b where b.ID_REKENING_PEKAS = a.ID_REKENING) SP2D_PAKAI'); 
		$this->db->select('(select count(b.ID_REKENING_PEKAS) from SPJ b where b.ID_REKENING_PEKAS = a.ID_REKENING) SPJ_PAKAI'); 
		$this->db->select('(select count(b.ID_REKENING) from RINCIAN_SPJ b where b.ID_REKENING = a.ID_REKENING) RINCIAN_SPJ_PAKAI'); 
		$this->db->select('(select count(b.ID_REKENING) from REKENING_PFK b where b.ID_REKENING = a.ID_REKENING) REKENING_PFK_PAKAI'); 
		$this->db->select('(select count(b.ID_REKENING) from RINCIAN_ANGGARAN b where b.ID_REKENING = a.ID_REKENING) RINCIAN_ANGGARAN_PAKAI');
		$this->db->select('(select count(b.ID_REKENING) from RINCIAN_KONTRAK b where b.ID_REKENING = a.ID_REKENING) RINCIAN_KONTRAK_PAKAI');
		$this->db->where('a.ID_REKENING', $ID_REKENING);
		$result = $this->db->get('REKENING a')->row_array();
		$this->db->trans_complete();
		
		if ($result['RINCIAN_SPP_PAKAI'] > 0 || 
        $result['RINCIAN_SPP_PFK_PAKAI'] > 0 || 
        $result['SP2D_PAKAI'] > 0 || 
        $result['SPJ_PAKAI'] > 0 || 
        $result['RINCIAN_SPJ_PAKAI'] > 0 || 
        $result['REKENING_PFK_PAKAI'] > 0 ||
        $result['RINCIAN_ANGGARAN_PAKAI'] > 0 ||
        $result['RINCIAN_KONTRAK_PAKAI'] > 0 
       ) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
	
	function check_dependency_5($ID_REKENING)
	{
		$this->db->trans_start();
		$this->db->select('a.ID_REKENING');
		$this->db->select('(select count(b.ID_REKENING) from RINCIAN_REKENING_SPD b where b.ID_REKENING = a.ID_REKENING) RINCIAN_REKENING_SPD_PAKAI'); 
		$this->db->select('(select count(b.ID_REKENING_BANK) from SKPD b where b.ID_REKENING_BANK = a.ID_REKENING) SKPD_BANK_PAKAI');
		$this->db->select('(select count(b.ID_REKENING) from SUMBER_DANA_SKPD b where b.ID_REKENING = a.ID_REKENING) SUMBER_DANA_SKPD_PAKAI'); 
		$this->db->select('(select count(b.ID_REKENING) from SPP_PAKAI_SPD_REKENING b where b.ID_REKENING = a.ID_REKENING) SPP_PAKAI_SPD_REKENING_PAKAI'); 
		$this->db->where('a.ID_REKENING', $ID_REKENING);
		$result = $this->db->get('REKENING a')->row_array();
		$this->db->trans_complete();
		
		if ($result['RINCIAN_REKENING_SPD_PAKAI'] > 0 || 
        $result['SKPD_BANK_PAKAI'] > 0 || 
        $result['SUMBER_DANA_SKPD_PAKAI'] > 0 ||
        $result['SPP_PAKAI_SPD_REKENING_PAKAI'] > 0
        ) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
				
	function get_skpd()
	{
		$this->db->select('*');
		$this->db->from('V_SKPD');
		$result = $this->db->get()->result_array();
		return $result;
	}
	
}
?>