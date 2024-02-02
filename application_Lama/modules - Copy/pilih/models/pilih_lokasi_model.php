<?php
class Pilih_lokasi_model extends Pilih_Model {

  function __construct()
  {
    parent::__construct();
  }

  function getLokasi($param, $isCount=FALSE)
  {
    $fieldmap = array(
      'id' => 'ID_LOKASI',
      'idp' => 'ID_PARENT_LOKASI',
      'lokasi' => 'LOKASI',
      'lvl' => 'LEVEL_LOKASI'
    );

    $wh = $this->checkSearch($param['q'], $fieldmap);
   // if ($wh) $this->db->or_where($wh);

    //($param['sort_by'] != null && !$isCount) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    /* $this->db->select('
      a.id_lokasi,
      a.id_parent_lokasi,
      a.lokasi,
      a.level_lokasi');
    $this->db->from('lokasi a');
    $this->db->where('a.id_lokasi <>', 0);
    $this->db->group_by('
      a.id_lokasi,
      a.id_parent_lokasi,
      a.lokasi,
      a.level_lokasi'); */
	
	if($param['q']){
		$query = $this->db->query("
			WITH RECURSIVE t AS (
				SELECT ID_LOKASI, LOKASI, LEVEL_LOKASI, ID_PARENT_LOKASI FROM LOKASI WHERE ID_PARENT_LOKASI IS NULL
			UNION ALL
				SELECT t1.ID_LOKASI, t1.LOKASI, t1.LEVEL_LOKASI, t1.ID_PARENT_LOKASI FROM t JOIN LOKASI t1 ON (t1.ID_PARENT_LOKASI = t.ID_LOKASI)
			)
			SELECT * FROM t where UPPER(lokasi) LIKE UPPER('%".$param['q']."%')
		");
	}
	else{
		
		$query = $this->db->query("
			WITH RECURSIVE t AS (
				SELECT ID_LOKASI, LOKASI, LEVEL_LOKASI, ID_PARENT_LOKASI FROM LOKASI WHERE ID_PARENT_LOKASI IS NULL
			UNION ALL
				SELECT t1.ID_LOKASI, t1.LOKASI, t1.LEVEL_LOKASI, t1.ID_PARENT_LOKASI FROM t JOIN LOKASI t1 ON (t1.ID_PARENT_LOKASI = t.ID_LOKASI)
			)
			SELECT * FROM t
		");
		//return $query->result_array();
	}	

    if ($isCount) {
		$this->db->select('
		  a.id_lokasi,
		  a.id_parent_lokasi,
		  a.lokasi,
		  a.level_lokasi');
		$this->db->from('lokasi a');
		$this->db->where('a.id_lokasi <>', 0);
		$this->db->group_by('
		  a.id_lokasi,
		  a.id_parent_lokasi,
		  a.lokasi,
		  a.level_lokasi');
		$result = $this->db->count_all_results();
		return $result;
    }
    else {
		$result = $query->result_array();
		return $result;
    }
  }

}