<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*********************************************************************************************
  SUPER CLASS dari semua Model Aktivitas
 *********************************************************************************************/
class Aktivitas_Model extends CI_Model{

  var $tahun;
  var $status;
  var $username;
  var $tipe;
  var $id;
  var $id_skpd;
  var $fields;
  var $fieldmap;
  var $fieldmap_aktivitas;
  var $data_aktivitas;
	var $fieldmap_daftar_aggregate; // variabel untuk menampung kolom yang di aggregate (sum, count, dll)

  function __construct()
  {
    parent::__construct();

    $this->tahun = $this->session->userdata('tahun');
    $this->status = $this->session->userdata('status');
    $this->username = $this->session->userdata('username');
    $this->id_skpd = $this->session->userdata('id_skpd');
    $this->tipe = 'AKTIVITAS'; // tipe di override di modul aktivitas

    $this->fieldmap_aktivitas = array(
      'id' => 'ID_AKTIVITAS',
      'tipe' => 'TIPE',
      'tahun' => 'TAHUN',
      'no' => 'NOMOR',
      'tgl' => 'TANGGAL',
      'deskripsi' => 'DESKRIPSI',
      'id_skpd' => 'ID_SKPD',
      'user' => 'USERNAME',
    );
		
		$this->fieldmap_daftar_aggregate = array();
  }

  function get_grid_model()
  {

  }

  function fill_data()
  {
    /* ambil data Aktivitas */
    foreach($this->fieldmap_aktivitas as $key => $value){
      switch ($key)
      {
        case 'tipe'  : $$key = $this->tipe; break;
        case 'tahun' : $$key = $this->tahun; break;
        case 'user'  : $$key = $this->username; break;
        case 'tgl'   : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }
      if(isset($$key))
        $this->data_aktivitas[$value] = $$key;
    }

    /* pengambilan data lain di override di modul aktivitas */
  }

  function insert_aktivitas()
  {
    if (isset($this->data_aktivitas['ID_AKTIVITAS']))
    {
      $this->db->where('ID_AKTIVITAS', $this->data_aktivitas['ID_AKTIVITAS']);
      $this->db->update('AKTIVITAS', $this->data_aktivitas);
      return $this->data_aktivitas['ID_AKTIVITAS'];
    }
    else
    {
      $this->db->insert('AKTIVITAS', $this->data_aktivitas);
      $this->db->select_max('ID_AKTIVITAS')->from('AKTIVITAS');
      $rs = $this->db->get()->row_array();
      return $rs['ID_AKTIVITAS'];
    }
  }

  function save_detail()
  {
    // fungsi ini di override di modul Aktivitas
  }

  function save_data()
  {
    $this->db->trans_start();
    $this->id = $this->insert_aktivitas();
    $this->save_detail();
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
  }

  function delete_data($id)
  {
    $this->db->trans_start();
    $this->build_query_hapus($id);
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
  }

  function build_query_daftar()
  {
    // di override di modul Aktivitas
  }

  function build_query_form($id=0)
  {
    // di override di modul Aktivitas
  }

  function build_query_hapus()
  {
    // di override di modul Aktivitas
  }

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
			if ($wh) {
				$count = count($wh);
				$string = '(';
				$i = 1;
				foreach ($wh as $key=>$val) {
					$string .= $key ." '". $val ."'";
					if ($i < $count) $string .= ' OR ';
					$i++;
				}
				$string .= ')';

				$this->db->where($string);
			}
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

		$this->build_query_daftar();

		if ($isCount) {
			/*
			$result = $this->db->count_all_results();
			return $result;
			*/
			
			$this->db->ar_select = array();
      $this->db->ar_select[] = 'count(*) cnt';
      foreach( $this->fieldmap_daftar_aggregate as $agg_key => $agg_value)
      {
        $this->db->ar_select[] = "coalesce(".$agg_value.", 0) ".$agg_key;
      }

      $result = $this->db->get();
      return $result->row_array();
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

  function get_data_by_id($id)
  {
    $this->build_query_form($id);
    $result = $this->db->get()->row_array();

    return $result;
  }

  function cek_duplikasi_nomor($nomor)
  {
    $id = $this->input->post('id') ? $this->input->post('id') : NULL;

    if ($id) $this->db->where('ID_AKTIVITAS <>', $id);
    $this->db->select('COUNT(ID_AKTIVITAS) DUP')
      ->where('NOMOR', $nomor)
      ->where('TIPE', $this->tipe)
      ->where('TAHUN', $this->tahun);
    $rs = $this->db->get('AKTIVITAS')->row_array();

    return (integer)$rs['DUP'] === 0;
  }

  function get_prev_id($id)
  {
    $this->db->select('coalesce(max(a.id_aktivitas), 0) id_aktivitas')
      ->from('aktivitas a')
      ->where('a.tahun', $this->tahun)
      ->where('a.tipe', $this->tipe)
      ->where('id_aktivitas < ', $id);
    if ($this->id_skpd > 0)
      $this->db->where('a.id_skpd', $this->id_skpd);
    $result = $this->db->get()->row_array();

    return $result['ID_AKTIVITAS'];
  }

  function get_next_id($id)
  {
    $this->db->select('coalesce(min(a.id_aktivitas), 0) id_aktivitas')
      ->from('aktivitas a')
      ->where('a.tahun', $this->tahun)
      ->where('a.tipe', $this->tipe)
      ->where('id_aktivitas > ', $id);
    if ($this->id_skpd > 0)
      $this->db->where('a.id_skpd', $this->id_skpd);
    $result = $this->db->get()->row_array();

    return $result['ID_AKTIVITAS'];
  }

  function check_dependency($id)
  {
    return TRUE; // fungsi ini di override di modul Aktivitas
  }
}