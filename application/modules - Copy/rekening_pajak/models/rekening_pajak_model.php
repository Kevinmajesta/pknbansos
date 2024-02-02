<?php
class Rekening_pajak_model extends CI_Model {

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

    $this->_table = 'PAJAK';
    $this->_pk    = 'ID_PAJAK';

   $this->fieldmap = array(
      'id' => 'ID_PAJAK',
      'kdpajak' => 'KODE_PAJAK',
      'nmpajak' => 'NAMA_PAJAK',
      'nmrek' => 'NAMA_REKENING',
      'idrek'  => 'ID_REKENING',
      'persen' => 'PERSEN'
    );
  }

  function fill_data()
  {
    $kdpajak 	= $this->input->post('kdpajak');$kdpajak=$kdpajak ? $kdpajak : null;
		$nmpajak		= $this->input->post('nmpajak');	$nmpajak = $nmpajak ? $nmpajak:null; 
		$nmrek 		= $this->input->post('nmrek');
		$persen		= $this->input->post('persen');	$persen=$persen ? $persen : null;
    
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
            if($param['search_str'] == 'KREDIT'){
              $param['search_str'] = '1';
            }
            elseif($param['search_str'] == 'DEBET'){
              $param['search_str'] = '-1';
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
        //($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        

    /*if (isset($param['sort_by']) && $param['sort_by'] != null && !$isCount && $ob = get_order_by_str($param['sort_by'], $this->fieldmap))
    {
      $this->db->order_by($ob, $param['sort_direction']);
    }*/
    
     ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
    
		$this->db->trans_start();    
    $this->db->select('
      p.id_rekening,
      p.id_pajak,
      p.kode_pajak,
      p.nama_pajak,
      r.nama_rekening,
      p.persen
    ');
    $this->db->from('pajak p');
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
    
        $this->db->trans_complete();
  }

  function get_data_by_id($id)
  {
    $this->db->select('
        p.kode_pajak,
        p.nama_pajak,
        p.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        p.persen
    ');
    $this->db->from('pajak p');
    $this->db->join('rekening r', 'p.id_rekening = r.id_rekening');
    $this->db->where('p.id_pajak', $id);
    $result = $this->db->get()->row_array();

    return $result;
  }

  function insert_data($param)
  {
    $this->db->insert('PAJAK', $param);
    // ambil id yang baru di insert
    $this->db->select_max('ID_PAJAK', 'ID');
    $id = $this->db->get('PAJAK')->row_array();
    return $id['ID'];
  }

  function update_data($id, $param)
  {
    $this->db->where('ID_PAJAK', $id);
    $this->db->update('PAJAK', $param);
    return TRUE;
  }

  function delete_data($id)
  {
    $this->db->trans_start();
    $this->db->where('id_pajak', $id);
    $this->db->delete('pajak');
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
  }

  function check_dependency($id)
  {
		$this->db->select('a.ID_PAJAK');
		$this->db->select('(select count(b.ID_PAJAK) from RINCIAN_SPP_PAJAK b where b.ID_PAJAK = a.ID_PAJAK) RINCIAN_SPP_PAJAK_PAKAI'); 
		$this->db->where('a.ID_PAJAK', $id);
		$result = $this->db->get('pajak a')->row_array();

		if ($result['RINCIAN_SPP_PAJAK_PAKAI'] > 0) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
  }

  function check_data($param, $id = NULL)
  {
    return false;
  }


}
