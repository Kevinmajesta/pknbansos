<?php
class Program_model extends CI_Model {
	
	var $data;
	var $fieldmap = array();
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->_table='PROGRAM';
		$this->_pk='ID_PROGRAM';
		
		$this->fieldmap = array(
			'id' => 'ID_PROGRAM',
			'KODE_URUSAN' => 'KODE_URUSAN',
			'KODE_BIDANG' => 'KODE_BIDANG',
			'KODE_PROGRAM' => 'KODE_PROGRAM',
			'NAMA_PROGRAM' => 'NAMA_PROGRAM',
			'BIDANG' => 'ID_BIDANG',
			'SKPD' => 'ID_SKPD'
		);
	}
	
	function fill_data()
	{
		$KODE_PROGRAM = $this->input->post('KODE_PROGRAM');$KODE_PROGRAM=($KODE_PROGRAM)?$KODE_PROGRAM:null;
		$NAMA_PROGRAM=$this->input->post('NAMA_PROGRAM');$NAMA_PROGRAM=($NAMA_PROGRAM)?$NAMA_PROGRAM:null;
		$ID_BIDANG= $this->input->post('BIDANG');$ID_BIDANG=($ID_BIDANG)?$ID_BIDANG:null;
		$ID_SKPD= $this->input->post('SKPD');$ID_SKPD=($ID_SKPD)?$ID_SKPD:null;
				
		$this->data = array(
			'KODE_PROGRAM' => $KODE_PROGRAM,
			'NAMA_PROGRAM' => $NAMA_PROGRAM,
			'ID_BIDANG' => $ID_BIDANG,
			'ID_SKPD' => $ID_SKPD
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
		
//		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        //($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		$this->db->select(
			'P.ID_PROGRAM,U.ID_URUSAN, U.KODE_URUSAN, B.ID_BIDANG, B.KODE_BIDANG,B.NAMA_BIDANG, S.ID_SKPD, S.KODE_SKPD,P.KODE_PROGRAM,P.NAMA_PROGRAM'
			);
		$this->db->from('PROGRAM as P');
		$this->db->join('BIDANG as B', 'P.ID_BIDANG = B.ID_BIDANG ', 'LEFT OUTER');
		$this->db->join('URUSAN as U', 'U.ID_URUSAN = B.ID_URUSAN ', 'LEFT OUTER');
		$this->db->join('SKPD as S', 'S.ID_SKPD = P.ID_SKPD ', 'LEFT OUTER');

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
        $this->db->order_by('U.KODE_URUSAN, B.KODE_BIDANG, P.KODE_PROGRAM');
        $result = $this->db->get()->result_array();
        return $result;
      }
    }
		// $result = $this->db->get();
		// return $result;
		$this->db->trans_complete();
	}
	
	function get_data_by_id($ID_PROGRAM)
	{
		$this->db->select(
			'P.ID_PROGRAM,U.KODE_URUSAN,U.NAMA_URUSAN,B.KODE_BIDANG,B.NAMA_BIDANG,S.KODE_SKPD,P.KODE_PROGRAM,P.NAMA_PROGRAM'
			);
		$this->db->from('PROGRAM as P');
		$this->db->join('BIDANG as B', 'P.ID_BIDANG = B.ID_BIDANG ', 'LEFT OUTER');
		$this->db->join('URUSAN as U', 'U.ID_URUSAN = B.ID_URUSAN ', 'LEFT OUTER');
		$this->db->join('SKPD as S', 'S.ID_SKPD = P.ID_SKPD ', 'LEFT OUTER');
		$this->db->where('P.ID_PROGRAM', $ID_PROGRAM);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
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
		$id = $this->db->query('select max(ID_PROGRAM) as ID from PROGRAM')->row_array();
		$newid = $id['ID'];
		$this->db->trans_complete();
		return $newid;
	}

	function update_data($ID_PROGRAM)
	{
		$this->fill_data();
		$data = $this->fill_data();
		$this->db->trans_start();
		$this->db->where($this->_pk, $ID_PROGRAM);
		$update = $this->db->update($this->_table, $this->data);
		$this->db->trans_complete();
		return $update;
	}

	function delete_data($ID_PROGRAM)
	{
    $jml = count($ID_PROGRAM);
    
		$this->db->trans_start();
    for ($i=0; $i<= $jml - 1; $i++) {
		$this->db->where($this->_pk, $ID_PROGRAM[$i]);
		$delete = $this->db->delete($this->_table);
    }
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
		
	/* option SKPD */
	function get_skpd($ID_BIDANG=0)
	{
		$query = 
			'Select s.ID_SKPD, s.KODE_SKPD
			from SKPD s
			 where s.ID_BIDANG = '.$ID_BIDANG.'
			 order by s.KODE_SKPD
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
			p.NAMA_PROGRAM
			from PROGRAM p
			left outer join SKPD s on s.ID_SKPD = p.ID_SKPD
			left outer join BIDANG b on b.ID_BIDANG = p.ID_BIDANG
			left outer join URUSAN u on u.ID_URUSAN = b.ID_URUSAN
			 where p.ID_PROGRAM = '.$id.'
			 order by p.KODE_PROGRAM
			';
			
		$result = $this->db->query($query);
		return $result;
	}
	
	function check_dependency($ID_PROGRAM)
	{
		$this->db->trans_start();
		$this->db->select('a.ID_PROGRAM');
		$this->db->select('(select count(b.ID_PROGRAM) from KEGIATAN b where b.ID_PROGRAM = a.ID_PROGRAM) KEGIATAN_PAKAI');
				
		$this->db->where('a.ID_PROGRAM', $ID_PROGRAM);
		$result = $this->db->get('PROGRAM a')->row_array();
		$this->db->trans_complete();
		
		if ($result['KEGIATAN_PAKAI'] > 0 ) {
			return FALSE;
		} 
		else
		{
			return TRUE;
      return $ID_PROGRAM;
		}
	}
		
	function check_isi()
	{
		$KODE_PROGRAM = $this->input->post('KODE_PROGRAM');$KODE_PROGRAM=($KODE_PROGRAM)?$KODE_PROGRAM:null;
		$NAMA_PROGRAM=$this->input->post('NAMA_PROGRAM');$NAMA_PROGRAM=($NAMA_PROGRAM)?$NAMA_PROGRAM:null;
		$ID_BIDANG= $this->input->post('BIDANG');$ID_BIDANG=($ID_BIDANG)?$ID_BIDANG:null;
		$ID_SKPD= $this->input->post('SKPD');$ID_SKPD=($ID_SKPD)?$ID_SKPD:null;
		
		if($ID_BIDANG == NULL){
			$wh = "AND ID_BIDANG is null ";		
		}
		else{
			$wh = "AND ID_BIDANG='$ID_BIDANG'";
		
		}
		
		$this->db->trans_start();
		$query = "SELECT * FROM PROGRAM  WHERE  KODE_PROGRAM = '$KODE_PROGRAM' $wh ";
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
		if($this->input->post('BIDANG')=='0'){
			$BIDANG = NULL;
		}
		else{
			$BIDANG = $this->input->post('BIDANG');
		}
		
		if($this->input->post('SKPD')=='0'){
			$SKPD = NULL;
		}
		else{
			$SKPD = $this->input->post('SKPD');
		}
		
		
		$KODE_PROGRAM 	= $this->input->post('KODE_PROGRAM');	$KODE_PROGRAM=($KODE_PROGRAM)?$KODE_PROGRAM:null;
		$NAMA_PROGRAM	= $this->input->post('NAMA_PROGRAM');	$NAMA_PROGRAM=($NAMA_PROGRAM)?$NAMA_PROGRAM:null;
		$ID_BIDANG		= $this->input->post('BIDANG');			$ID_BIDANG=($ID_BIDANG)?$ID_BIDANG:null;
		$ID_SKPD		= $this->input->post('SKPD');			$ID_SKPD=($ID_SKPD)?$ID_SKPD:null;
		
		$id_bid = $this->db->query("select KODE_PROGRAM as KODE_PRG, NAMA_PROGRAM as NAMA_PRG, ID_BIDANG as ID_BID from PROGRAM WHERE ID_PROGRAM='".$this->input->post('id')."'")->row_array();
		
		
		$this->db->select('KODE_PROGRAM');
		$this->db->from('PROGRAM');
		$this->db->where('ID_BIDANG', $this->input->post('BIDANG'));
		$this->db->where('KODE_PROGRAM <>', $this->input->post('KODE_PROGRAM'));
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['KODE_PROGRAM'];
			}
		}
		
		/*if ($ID_BIDANG == $id_bid['ID_BID'] && in_array($KODE_PROGRAM, $hasil)) {
			return FALSE;
		}
		else if ($ID_BIDANG == $id_bid['ID_BID'] && in_array($KODE_PROGRAM, $hasil, FALSE)) {
			return TRUE;
		}
		else if ($ID_BIDANG == $id_bid['ID_BID'] && ($KODE_PROGRAM == $id_bid['KODE_PRG'])) {
			return TRUE;
		}*/
		
		//echo "$ID_BIDANG,$ID_SKPD,$id_bid[KODE_PRG],$KODE_PROGRAM";
		
		if($KODE_PROGRAM == $id_bid['KODE_PRG']){
			if($ID_BIDANG == null){
				$id_bid = $this->db->query("select * from PROGRAM WHERE ID_BIDANG is null AND KODE_PROGRAM='$KODE_PROGRAM'")->result_array();
				if(count($id_bid) > 0)
				{
					return TRUE;
				}
				else{
					return FALSE;
				}
				
			}
			else{
				$this->db->select('*');
				$this->db->from('PROGRAM');
				$this->db->where('ID_BIDANG', $this->input->post('BIDANG'));
				$this->db->where('KODE_PROGRAM', $id_bid['KODE_PRG']);
				$ada = $this->db->get()->result_array();
				if(count($ada) > 0)
				{
					return TRUE;
				}	
			}			
		}
		else if($KODE_PROGRAM <> $id_bid['KODE_PRG']){
			if($ID_BIDANG == null){
				$id_bid = $this->db->query("select * from PROGRAM WHERE ID_BIDANG is null AND KODE_PROGRAM='$KODE_PROGRAM'")->result_array();
				if(count($id_bid) > 0)
				{
					return FALSE;
				}
				else{
					return TRUE;
				}
			}
			else{
				$this->db->select('*');
				$this->db->from('PROGRAM');
				$this->db->where('ID_BIDANG', $this->input->post('BIDANG'));
				$this->db->where('KODE_PROGRAM', $KODE_PROGRAM);
				$ada = $this->db->get()->result_array();
				if(count($ada) > 0)
				{
					return FALSE;
				}	
				else{
					return TRUE;
				}	
			}
		}		
	}

}
?>