<?php
class Tim_anggaran_model extends CI_Model {

  var $tahun;
  var $tipe;
  var $username;
  var $id;
  var $fieldmap;
  var $data;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();

    $tahun = $this->session->userdata('tahun');
    $this->username = $this->session->userdata('username');
    $status = $this->session->userdata('status');

    $this->_table = 'TIM_ANGGARAN';
    $this->_pk    = 'ID_TIM_ANGGARAN';
    
    $this->fieldmap = array(
			'TAHUN' => $tahun,
			'STATUS' => $status,
			'idp' => 'ID_PEJABAT_DAERAH',
      'id' => 'ID_TIM_ANGGARAN',
      'id_ps' => 'ID_PEJABAT_SKPD',
      'NAMA_PEJABAT' => 'NAMA_PEJABAT',
      'JABATAN' => 'JABATAN',
      'NIP' => 'NIP'
		);
    
  }

  	function fill_data()
	{
    $idp 	= $this->input->post('idp');$idp=$idp ? $idp : null;
		$id_ps		= $this->input->post('id_ps');	$id_ps = $id_ps ? $id_ps:null; 
		
  
		foreach($this->fieldmap as $key => $value){
			if(isset($$key))
			$this->data[$value] = $$key;
		}
		
		//print_r($this->form);
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

    //returns the query string
    $this->db->trans_start();
    $this->db->select('I.ID_PEJABAT_DAERAH,S.NAMA_PEJABAT,S.JABATAN,S.NIP,I.ID_TIM_ANGGARAN');
		$this->db->from('TIM_ANGGARAN as I');
		$this->db->join('PEJABAT_DAERAH S','S.ID_PEJABAT_DAERAH = I.ID_PEJABAT_DAERAH');
		$this->db->where('I.TAHUN',$this->session->userdata('tahun'));
		$this->db->where('I.STATUS',$this->session->userdata('status'));
		$this->db->where('S.AKTIF','1');
		$this->db->where('I.ID_PEJABAT_SKPD is null');

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
        $this->db->order_by('S.NAMA_PEJABAT');
        $result = $this->db->get()->result_array();
        return $result;
      }
    }
    $this->db->trans_complete();
  }

  function insert_data($param)
  {
    $result = $this->db->insert('TIM_ANGGARAN', $param);

    if($result) return TRUE;
  }


  function delete_data($id)
  {
    $this->db->where('ID_PEJABAT_DAERAH', $id);
    $result = $this->db->delete('TIM_ANGGARAN');

    if($result) return TRUE;
  }

  function check_data($param)
  {
    $this->db->select('COUNT(ID_PEJABAT_DAERAH) PEJABAT_PAKAI');
    $this->db->from('TIM_ANGGARAN');
    $this->db->where('ID_PEJABAT_DAERAH', $param['ID_PEJABAT_DAERAH']);
    $this->db->where('TAHUN', $param['TAHUN']);
    $rs = $this->db->get()->row_array();
    
    if ($rs && $rs['PEJABAT_PAKAI'] > 0) {
      return FALSE;
    } else {
      return TRUE;
    }

  }

  
}
