<?php
class Tahun_anggaran_model extends CI_Model {

  var $data;
  var $data_status;
  var $fieldmap = array();
  var $fieldmap_status = array();

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();

    $this->fieldmap = array(
      'tahun' => 'TAHUN',
      'status_awal' => 'STATUS_AWAL',
	  'status_kini' => 'STATUS_KINI',
    );

    $this->fieldmap_status = array(
      'tahun' => 'TAHUN',
      'status' => 'STATUS',      
      'tgl_rka' => 'TANGGAL_RKA',
      'tgl_apbd' => 'TANGGAL_APBD',
      'no_apbd' => 'NOMOR_APBD',
      'tgl_perkada' => 'TANGGAL_PERKADA',
      'no_perkada' => 'NOMOR_PERKADA',
      'tgl_dpa' => 'TANGGAL_DPA',
      'no_dpa' => 'NOMOR_DPA',
    );
  }

  function fill_data()
  {
    foreach($this->fieldmap as $key => $value){
      $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
//      if(isset($$key))
        $this->data[$value] = $$key;
    }
    
    foreach($this->fieldmap_status as $key => $value){
      switch ($key)
      {
        case 'tahun' : $$key = $this->session->userdata('tmp_tahun_for_status_1');	
        case 'tgl_rka' : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        case 'tgl_apbd' : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        case 'tgl_perkada' : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        case 'tgl_dpa' : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }
      if(isset($$key))
        $this->data_status[$value] = $$key;
    }
  }

  function get_data($param, $isCount=FALSE, $CompileOnly=False)
  {
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
    if (isset($param['sort_by']) && $param['sort_by'] != null && !$isCount && $ob = get_order_by_str($param['sort_by'], $this->fieldmap))
    {
      $this->db->order_by($ob, $param['sort_direction']);
    }
        
		//returns the query string
		
		$this->db->select('DISTINCT TAHUN, STATUS_AWAL, STATUS_KINI');
    $this->db->from('TAHUN_ANGGARAN');

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
  }

	function insert_data()
	{		
		$this->fill_data();
		$this->db->trans_start();
		$result = $this->db->insert('TAHUN_ANGGARAN', $this->data);
		$newid = $this->db->query('select max(TAHUN) as ID from TAHUN_ANGGARAN')->row_array();
		$this->db->trans_complete();
		return $newid['ID'];
	}

	function update_data($TAHUN)
	{
		$TAHUN_1 = $this->session->userdata('tmp_tahun_grid');
		
		$data = $this->fill_data();
		$this->db->trans_start();
		$this->db->where('TAHUN', $TAHUN_1);
		$update = $this->db->update('TAHUN_ANGGARAN', $this->data);
		return $update;
		$this->db->trans_complete();
	}
  
 	function check_tahun()
	{
		$tahun = $this->input->post('tahun');	$tahun=($tahun)?$tahun:null;
		
		$this->db->trans_start();		
		$result = $this->db->query("SELECT * FROM TAHUN_ANGGARAN WHERE TAHUN = '$tahun'")->result_array();		
		if(!empty($result)){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		$this->db->trans_complete();	
	}
	
 	function check_tahun2()
	{
		$TAHUN_1 = $this->session->userdata('tmp_tahun_grid');
		$TAHUN_2 = $this->input->post('tahun');	$TAHUN_2=($TAHUN_2)?$TAHUN_2:null;
		
		$this->db->trans_start();		
		$result = $this->db->query("SELECT * FROM TAHUN_ANGGARAN WHERE TAHUN = '$TAHUN_2' AND TAHUN <> '$TAHUN_1'")->result_array();

		if(!empty($result)){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		$this->db->trans_complete();	
	}
  
	function check_dependency_tahun($TAHUN)
	{
		$this->db->trans_start();
		$this->db->select('a.TAHUN');
		$this->db->select('(select count(b.TAHUN) from STATUS_ANGGARAN b where b.TAHUN = a.TAHUN) STATUS_ANGGARAN_PAKAI');
		$this->db->select('(select count(b.TAHUN) from AKTIVITAS b where b.TAHUN = a.TAHUN) AKTIVITAS_PAKAI');
		$this->db->select('(select count(b.TAHUN) from SPP_PAKAI_SPD_REKENING b where b.TAHUN = a.TAHUN) SPP_PAKAI_SPD_REKENING_PAKAI');
		$this->db->select('(select count(b.TAHUN) from SPP_PAKAI_SPD_KEGIATAN b where b.TAHUN = a.TAHUN) SPP_PAKAI_SPD_KEGIATAN_PAKAI');
		$this->db->select('(select count(b.TAHUN) from SPP_PAKAI_SPD b where b.TAHUN = a.TAHUN) SPP_PAKAI_SPD_PAKAI');			
		
		$this->db->where('a.TAHUN', $TAHUN);
		$result = $this->db->get('TAHUN_ANGGARAN a')->row_array();
		$this->db->trans_complete();

		if ($result['STATUS_ANGGARAN_PAKAI'] > 0 || $result['AKTIVITAS_PAKAI'] > 0 || $result['SPP_PAKAI_SPD_REKENING_PAKAI'] > 0  || $result['SPP_PAKAI_SPD_KEGIATAN_PAKAI'] > 0 || $result['SPP_PAKAI_SPD_PAKAI'] > 0) 
    {
			return TRUE;
		} 
		else
		{
			return FALSE;
		}
	}
  
	function delete_data($TAHUN){
		$TAHUN_1 = $this->session->userdata('tmp_tahun_grid');
		
		$this->db->trans_start();
		$this->db->where('TAHUN', $TAHUN_1);
		$delete = $this->db->delete('TAHUN_ANGGARAN');
		return $delete;
		$this->db->trans_complete();
	}
  
  function get_data_status($param,$TAHUN)
  {
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
    ($param['sort_by'] != null) ? $this->db->order_by($this->fieldmap_status[$param['sort_by']], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		
		$this->db->select(
				'sa.TAHUN,sa.STATUS,sa.TANGGAL_RKA,sa.TANGGAL_APBD,sa.NOMOR_APBD,sa.TANGGAL_PERKADA,sa.NOMOR_PERKADA,sa.TANGGAL_DPA,sa.NOMOR_DPA'
		);
		$this->db->from('STATUS_ANGGARAN sa');
		$this->db->join('TAHUN_ANGGARAN t', 't.TAHUN = sa.TAHUN', 'LEFT');
		$this->db->where('sa.TAHUN',$TAHUN);
		$result = $this->db->get()->result_array();
		$this->db->trans_complete();
    
		return $result;
  }
  
	function insert_data_status()
	{		
		$this->fill_data();
		$this->db->trans_start();
		$result = $this->db->insert('STATUS_ANGGARAN', $this->data_status);
		$newid = $this->db->insert_id();
		$this->db->trans_complete();
		return $newid;
	}

	function update_data_status()
	{
		$TAHUN = $this->session->userdata('tmp_tahun_for_status_1');	
		$STATUS_as = $this->session->userdata('tmp_status_for_update_1');	
		
		$data = $this->fill_data();
		$this->db->trans_start();
		$this->db->where('TAHUN', $TAHUN);
		$this->db->where('STATUS', $STATUS_as);
		$update = $this->db->update('STATUS_ANGGARAN', $this->data_status);
		return $update;
		$this->db->trans_complete();
	}
  
	function check_dependency_status($TAHUN, $STATUS)
	{
	//echo ($TAHUN);
		$this->db->trans_start();
		$this->db->select('a.TAHUN');
		$this->db->select('a.STATUS');
		$this->db->select('(select count(b.STATUS) from FORM_ANGGARAN b where b.STATUS = a.STATUS and b.TAHUN = a.TAHUN) FORM_ANGGARAN_PAKAI');
		$this->db->select('(select count(b.STATUS) from TIM_ANGGARAN b where b.STATUS = a.STATUS and b.TAHUN = a.TAHUN) TIM_ANGGARAN_PAKAI');
		$this->db->select('(select count(b.STATUS_AWAL) from TAHUN_ANGGARAN b where b.STATUS_AWAL = a.STATUS and b.TAHUN = a.TAHUN) TAHUN_ANGGARAN_AWAL_PAKAI');
		$this->db->select('(select count(b.STATUS_KINI) from TAHUN_ANGGARAN b where b.STATUS_KINI = a.STATUS and b.TAHUN = a.TAHUN) TAHUN_ANGGARAN_KINI_PAKAI');
		$this->db->select('(select count(b.STATUS) from RINCIAN_RKA b where b.STATUS = a.STATUS and b.TAHUN = a.TAHUN) RINCIAN_RKA_PAKAI');
		$this->db->select('(select count(b.STATUS) from RINCIAN_RKA_22 b where b.STATUS = a.STATUS and b.TAHUN = a.TAHUN) RINCIAN_RKA_22_PAKAI');
			
		$this->db->where('a.STATUS', $STATUS);
		$this->db->where('a.TAHUN', $TAHUN);
		$result = $this->db->get('STATUS_ANGGARAN a')->row_array();
		$this->db->trans_complete();
		
		if ($result['FORM_ANGGARAN_PAKAI'] > 0 || $result['TIM_ANGGARAN_PAKAI'] > 0 || $result['RINCIAN_RKA_PAKAI'] > 0 || $result['RINCIAN_RKA_22_PAKAI'] > 0 || $result['TAHUN_ANGGARAN_AWAL_PAKAI'] > 0 || $result['TAHUN_ANGGARAN_KINI_PAKAI'] > 0 ) {
			return TRUE;
		} 
		else
		{
			return FALSE;
		}
	}
  
	function delete_data_status($TAHUN, $STATUS)
	{
    $this->db->trans_start();
    $this->db->where('STATUS', $STATUS);
    $this->db->where('TAHUN', $TAHUN);
    $delete = $this->db->delete('STATUS_ANGGARAN');
    return $delete;
    $this->db->trans_complete();
	}

	/* option Status Anggaran */
	function get_status($TAHUN)
	{
		$query = 
			'Select STATUS as STATUS from STATUS_ANGGARAN
			 where TAHUN = '.$TAHUN.'
			';
			
		$return = $this->db->query($query);
		return $return;
	}
	
	function get_opt_status($TAHUN)
	{
		$return = array(
		''=>''
		); 
		
		$result = $this->get_status($TAHUN);
		
		foreach($result->result_array() as $row)
		{
			$return[$row['STATUS']]=$row['STATUS'];
		}
				
		return $return;	
	}
}
?>