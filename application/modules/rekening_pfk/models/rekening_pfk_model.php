<?php
class Rekening_pfk_model extends CI_Model {

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

    $this->tahun = $this->session->userdata('tahun');
    $this->username = $this->session->userdata('username');

    $this->_table = 'REKENING_PFK';
    $this->_pk    = 'ID_REKENING';

    $this->fieldmap = array(
      'kdrek' => 'KODE_REKENING',
      'nmrek' => 'NAMA_REKENING',
      'idrek'  => 'ID_REKENING'
    );
  }

  function fill_data()
  {
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

    isset($param['limit']) && $param['limit'] ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '';

    if (isset($param['search']) && $param['search'] && $wh = get_where_str(array($param['search_field'] => $param['search_str']), $this->fieldmap))
    {
        $this->db->where($wh);
    }

    if (isset($param['sort_by']) && $param['sort_by'] != null && !$isCount && $ob = get_order_by_str($param['sort_by'], $this->fieldmap))
    {
      $this->db->order_by($ob, $param['sort_direction']);
    }

    $this->db->select('
      p.id_rekening,
      r.kode_rekening,
      r.nama_rekening
    ');
    $this->db->from('rekening_pfk p');
    $this->db->join('rekening r', 'r.id_rekening = p.id_rekening');

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

  function insert_data($param)
  {
    $result = $this->db->insert('REKENING_PFK', $param);

    if($result) return TRUE;
  }


  function delete_data($id)
  {
    $this->db->where('id_rekening', $id);
    $result = $this->db->delete('rekening_pfk');

    if($result) return TRUE;
  }

  function check_data($param, $id = NULL)
  {
    $this->db->where('ID_REKENING', $param['ID_REKENING']);
    if ($id) $this->db->where('ID_REKENING <>', $id);
    $rs = $this->db->get('REKENING_PFK')->result_array();

    return count($rs) > 0;
  }

}
