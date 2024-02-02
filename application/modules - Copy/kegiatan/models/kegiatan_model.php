<?php
class Kegiatan_model extends CI_Model {
	
	var $data;
	var $fieldmap = array();
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->_table='KEGIATAN';
		$this->_pk='ID_KEGIATAN';
		
		$this->fieldmap = array(
			'id' => 'ID_KEGIATAN',
			'KODE_URUSAN' => 'KODE_URUSAN',
			'KODE_BIDANG' => 'KODE_BIDANG',
			'PROGRAM' => 'ID_PROGRAM',
			'KODE_PROGRAM' => 'KODE_PROGRAM',
			'NAMA_PROGRAM' => 'NAMA_PROGRAM',
			'KODE_KEGIATAN' => 'KODE_KEGIATAN',
			'NAMA_KEGIATAN' => 'NAMA_KEGIATAN',
		);
	}

	function fill_data()
	{
		$ID_PROGRAM = $this->input->post('PROGRAM');$ID_PROGRAM=($ID_PROGRAM)?$ID_PROGRAM:null;
		$KODE_KEGIATAN=$this->input->post('KODE_KEGIATAN');$KODE_KEGIATAN=($KODE_KEGIATAN)?$KODE_KEGIATAN:null;
		$NAMA_KEGIATAN=$this->input->post('NAMA_KEGIATAN');$NAMA_KEGIATAN=($NAMA_KEGIATAN)?$NAMA_KEGIATAN:null;
		
		$this->data = array(
			'ID_PROGRAM' => $ID_PROGRAM,
			'KODE_KEGIATAN' => $KODE_KEGIATAN,
			'NAMA_KEGIATAN' => $NAMA_KEGIATAN,
		);
		
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
		
		//($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
    //($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
    $this->db->trans_start();
    
		$this->db->select(
			'U.KODE_URUSAN, 
			B.KODE_BIDANG, 
			S.KODE_SKPD, 
			P.ID_PROGRAM, 
			P.KODE_PROGRAM, 
			P.NAMA_PROGRAM,    
			K.ID_KEGIATAN, 
			K.KODE_KEGIATAN, 
			K.NAMA_KEGIATAN'
		);
		$this->db->from('PROGRAM P');
		$this->db->join('BIDANG B', 'P.ID_BIDANG = B.ID_BIDANG', 'LEFT OUTER');
		$this->db->join('URUSAN U', 'U.ID_URUSAN = B.ID_URUSAN', 'LEFT OUTER');
		$this->db->join('SKPD S', 'S.ID_SKPD = P.ID_SKPD', 'LEFT OUTER');
		$this->db->join('KEGIATAN K', 'P.ID_PROGRAM = K.ID_PROGRAM', 'LEFT OUTER');
		
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
          $order= "COALESCE(U.KODE_URUSAN, ''''), COALESCE(B.KODE_BIDANG, ''''),    COALESCE(S.KODE_SKPD, ''''), P.KODE_PROGRAM, K.KODE_KEGIATAN";
          $this->db->group_by('U.KODE_URUSAN, B.KODE_BIDANG, S.KODE_SKPD, P.ID_PROGRAM, P.KODE_PROGRAM, P.NAMA_PROGRAM,    K.ID_KEGIATAN, K.KODE_KEGIATAN, K.NAMA_KEGIATAN');
          $this->db->order_by('U.KODE_URUSAN, B.KODE_BIDANG, P.KODE_PROGRAM, K.KODE_KEGIATAN');
          $result = $this->db->get()->result_array();
          return $result;
      }
    }

    //echo $this->db->last_query();
        $this->db->trans_complete();
    //		return $result;
		
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

	function update_data($ID_KEGIATAN)
	{
		$this->fill_data();
		$data = $this->fill_data();
		$this->db->trans_start();
		$this->db->where($this->_pk, $ID_KEGIATAN);
		$update = $this->db->update($this->_table, $this->data);
		$this->db->trans_complete();
		return $update;
	}

	function delete_data($ID_KEGIATAN)
	{
		$this->db->trans_start();
		$this->db->where($this->_pk, $ID_KEGIATAN);
		$delete = $this->db->delete($this->_table);
		$this->db->trans_complete();
		return $delete;
	}
	
	/* option Urusan */
	function get_urusan()
	{
		$return = array(
		''=>'Pilih Urusan'
		); 
	
		$query = 
			'Select u.ID_URUSAN, u.KODE_URUSAN
			from URUSAN as u
			order by u.KODE_URUSAN
			';
			
		$return = $this->db->query($query);
		return $return;
	}
	
	function get_opt_urusan()
	{
		$return = array(
		''=>''
		); 
		
		$result = $this->get_urusan();
		
		foreach($result->result_array() as $row)
		{
			$return[$row['ID_URUSAN']]=$row['KODE_URUSAN'];
		}
				
		return $return;	
	}
	
	/* option Bidang */
	function get_bidang($ID_URUSAN=0)
	{
		$query = 
			'Select b.ID_BIDANG, b.KODE_BIDANG
			from BIDANG b 
			 where b.ID_URUSAN = '.$ID_URUSAN.'
			 order by b.KODE_BIDANG
			';
			
		$result = $this->db->query($query);
		return $result;
	}
			
	/* optin Program */
	function get_program_x()
	{
	
		$query = 
			"SELECT 
			distinct
			P.ID_PROGRAM, 
			P.KODE_PROGRAM,
			P.NAMA_PROGRAM
			FROM PROGRAM P  
			LEFT OUTER JOIN BIDANG B ON P.ID_BIDANG = B.ID_BIDANG  
			LEFT OUTER JOIN URUSAN U ON U.ID_URUSAN = B.ID_URUSAN  
			LEFT OUTER JOIN SKPD S ON S.ID_SKPD = P.ID_SKPD  
			LEFT OUTER JOIN KEGIATAN K ON P.ID_PROGRAM = K.ID_PROGRAM  
			GROUP BY U.KODE_URUSAN, B.KODE_BIDANG, S.KODE_SKPD, P.ID_PROGRAM, P.KODE_PROGRAM, P.NAMA_PROGRAM,    K.ID_KEGIATAN, K.KODE_KEGIATAN, K.NAMA_KEGIATAN  
			ORDER BY U.KODE_URUSAN, B.KODE_BIDANG, S.KODE_SKPD, P.KODE_PROGRAM, K.KODE_KEGIATAN 
			";
			
		$return = $this->db->query($query);
		return $return;
	}
	
	function get_opt_program()
	{
		$return = array(
		''=>''
		); 
		
		$result = $this->get_program_x();
		
		foreach($result->result_array() as $row)
		{
			$return[$row['ID_PROGRAM']]=$row['KODE_PROGRAM'];
		}
				
		return $return;	
	}
	
	function get_program($ID_BIDANG=0)
	{
		$query = 
			'Select p.ID_PROGRAM, p.KODE_PROGRAM
			from PROGRAM p
			 where p.ID_BIDANG = '.$ID_BIDANG.'
			 order by p.KODE_PROGRAM
			';
			
		$result = $this->db->query($query);
		return $result;
	}
	
	function get_program_2($ID_PROGRAM=0)
	{
		$query = 
			'Select p.ID_PROGRAM, p.KODE_PROGRAM
			from PROGRAM p
			 where p.ID_PROGRAM = '.$ID_PROGRAM.'
			 order by p.KODE_PROGRAM
			';
			
		$result = $this->db->query($query);
		return $result;
	}
	
	function get_program_3()
	{
		$query = 
			'Select p.ID_PROGRAM, p.KODE_PROGRAM
			from PROGRAM p
			 order by p.KODE_PROGRAM
			';
			
		$result = $this->db->query($query);
		return $result;
	}
	
	function get_data_by_id_2($id=0)
	{
		$query = 
			'select
			u.ID_URUSAN,
			u.KODE_URUSAN,
			b.ID_BIDANG,
			b.KODE_BIDANG,
			s.ID_SKPD,
			s.KODE_SKPD,
			p.ID_PROGRAM,
			p.KODE_PROGRAM,
			k.ID_KEGIATAN,
			k.KODE_KEGIATAN,
			k.NAMA_KEGIATAN
			from KEGIATAN k
			left outer join PROGRAM p on p.ID_PROGRAM = k.ID_PROGRAM
			left outer join SKPD s on s.ID_SKPD = p.ID_SKPD
			left outer join BIDANG b on b.ID_BIDANG = p.ID_BIDANG
			left outer join URUSAN u on u.ID_URUSAN = b.ID_URUSAN
			 where k.ID_KEGIATAN = '.$id.'
			';
			
		$result = $this->db->query($query);
		return $result;
	}
	
	function get_data_by_id_3($id=0)
	{
		$query = 
			'select
			u.ID_URUSAN,
			u.KODE_URUSAN,
			b.ID_BIDANG,
			b.KODE_BIDANG,
			s.ID_SKPD,
			s.KODE_SKPD,
			p.ID_PROGRAM,
			p.KODE_PROGRAM
			from PROGRAM p
			left outer join SKPD s on s.ID_SKPD = p.ID_SKPD
			left outer join BIDANG b on b.ID_BIDANG = p.ID_BIDANG
			left outer join URUSAN u on u.ID_URUSAN = b.ID_URUSAN
			 where p.ID_PROGRAM = '.$id.'
			';
			
		$result = $this->db->query($query);
		return $result;
	}
	
	function check_dependency($ID_KEGIATAN)
	{
		$this->db->trans_start();
		$this->db->select('a.ID_KEGIATAN');
		
		$this->db->select('(select count(b.ID_KEGIATAN) from RKA_DPA_SKPD_221 b where b.ID_KEGIATAN = a.ID_KEGIATAN) RKA_DPA_SKPD_221_PAKAI');
						
		$this->db->where('a.ID_KEGIATAN', $ID_KEGIATAN);
		$result = $this->db->get('KEGIATAN a')->row_array();
		$this->db->trans_complete();
		
		if ($result['RKA_DPA_SKPD_221_PAKAI'] > 0 ) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
	
	function check_data()
	{
		$KODE_KEGIATAN	= $this->input->post('KODE_KEGIATAN');	$KODE_KEGIATAN=($KODE_KEGIATAN)?$KODE_KEGIATAN:null;
		$PROGRAM		= $this->input->post('PROGRAM');	$PROGRAM=($PROGRAM)?$PROGRAM:null;
		
		$this->db->trans_start();
		$query = "SELECT * FROM KEGIATAN WHERE  ID_PROGRAM = '$PROGRAM' AND KODE_KEGIATAN = '$KODE_KEGIATAN'";
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
		$KODE_KEGIATAN	= $this->input->post('KODE_KEGIATAN');	$KODE_KEGIATAN=($KODE_KEGIATAN)?$KODE_KEGIATAN:null;
		$PROGRAM		= $this->input->post('PROGRAM');		$PROGRAM=($PROGRAM)?$PROGRAM:null;
		
		$id_bid = $this->db->query("select KODE_KEGIATAN as KODE_KEG, ID_PROGRAM as PRG from KEGIATAN WHERE ID_KEGIATAN='".$this->input->post('id')."'")->row_array();
		
		
		$this->db->select('KODE_KEGIATAN');
		$this->db->from('KEGIATAN');
		$this->db->where('ID_PROGRAM', $this->input->post('PROGRAM'));
		$this->db->where('KODE_KEGIATAN <>', $this->input->post('KODE_KEGIATAN'));
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['KODE_KEGIATAN'];
			}
		}
		
		if ($PROGRAM == $id_bid['PRG'] && in_array($KODE_KEGIATAN, $hasil)) {
			return FALSE;
		}
		else if ($PROGRAM == $id_bid['PRG'] && in_array($KODE_KEGIATAN, $hasil, FALSE)) {
			return TRUE;
		}
		else if ($PROGRAM == $id_bid['PRG'] && ($KODE_KEGIATAN == $id_bid['KODE_KEG'])) {
			return TRUE;
		}
	}
}
?>