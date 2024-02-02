<?php
class Rekeningbendahara_model extends CI_Model {

  var $data;
  var $data_rekben;
  var $fieldmap;
  var $fieldmap_rekben;
  var $id_skpd;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    $this->_table='SUMBER_DANA_SKPD';
    $this->_pk='ID_SUMBER_DANA_SKPD';

    // set id_skpd diambil dari session
    if($this->session->userdata('id_skpd') != null){
    $this->id_skpd = $this->session->userdata('id_skpd');
    }
    else{
    $this->id_skpd =$this->input->post('idskpd');
    }

    $this->fieldmap = array (
        'id' => 'sd.ID_SUMBER_DANA_SKPD',
        'id_skpd' => 'sd.ID_SKPD',
        'idrekening' => 'sd.ID_REKENING',
        'namasumber' => 'sd.NAMA_SUMBER_DANA',
        'namabank' => 'sd.NAMA_BANK',
        'norekening' => 'sd.NO_REKENING_BANK',
        'koderekening' => 'r.KODE_REKENING',
        'namarekening' => 'r.NAMA_REKENING'
    );

    $this->fieldmap_rekben = array(
        'id' => 'ID_SUMBER_DANA_SKPD',
        'id_skpd' => 'ID_SKPD',
        'idrekening' => 'ID_REKENING',
        'namasumber' => 'NAMA_SUMBER_DANA',
        'namabank' => 'NAMA_BANK',
        'norekening' => 'NO_REKENING_BANK',
    );
  }

  function fill_data()
  {
    foreach($this->fieldmap_rekben as $key => $value){
      switch ($key){
        case 'id' : $$key = $this->input->post($key) ? ($this->input->post($key)) : NULL; $$key == 'new' ? $$key = NULL : '';break;
        case 'id_skpd' : $$key = $this->id_skpd ? $this->id_skpd : NULL; break;
        default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }

      if(isset($$key))
        $this->data_rekben[$value] = $$key;
    }
  }

    function get_data($param, $isCount=FALSE, $CompileOnly=False)
  {
    isset($param['limit']) && $param['limit'] ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '';

    $wh_param = array(
        $param['search_field'] => array(
            'search_op' => $param['search_operator'],
            'search_str' => $param['search_str']
        )
    );

    if (isset($param['search']) && $param['search'] && $wh = get_where_str($wh_param, $this->fieldmap))
    {
      $this->db->where($wh);
    }

    if (isset($param['sort_by']) && $param['sort_by'] != null && !$isCount && $ob = get_order_by_str($param['sort_by'], $this->fieldmap))
    {
      $this->db->order_by($ob, $param['sort_direction']);
    }

    $this->db->select('
        sd.ID_SUMBER_DANA_SKPD,
        sd.ID_SKPD,
        s.NAMA_SKPD,
        s.KODE_SKPD_LKP,
        sd.NAMA_SUMBER_DANA,
        r.KODE_REKENING,
        r.NAMA_REKENING,
        sd.NAMA_BANK,
        sd.NO_REKENING_BANK,
        sd.ID_REKENING
    ');
    $this->db->from('SUMBER_DANA_SKPD as sd');
    $this->db->join('REKENING as r', 'r.ID_REKENING = sd.ID_REKENING');
    $this->db->join('v_skpd as s', 's.ID_SKPD = sd.ID_SKPD');
    if ($this->session->userdata('id_skpd') !== 0) $this->db->where('sd.id_skpd', $this->session->userdata('id_skpd'));

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
    $result = $this->db->insert($this->_table, $this->data_rekben);
    $newid = $this->db->query('select max(ID_SUMBER_DANA_SKPD) as ID from SUMBER_DANA_SKPD')->row_array();
    $this->db->trans_complete();
    return $newid['ID'];
  }

  function update_data($id)
  {
    $this->fill_data();
    $this->db->trans_start();
    $this->db->where($this->_pk, $id);
    $update = $this->db->update($this->_table, $this->data_rekben);
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

  /*function check_dependency($id)
  {
    $this->db->select('a.ID_SUMBER_DANA_SKPD');
    $this->db->select('(select count(b.ID_SUMBER_DANA_SKPD) from RINCIAN_BKU_SKPD b where b.ID_SUMBER_DANA_SKPD = a.ID_SUMBER_DANA_SKPD) RINCIAN_BKU_SKPD_PAKAI');
    $this->db->select('(select count(b.ID_SUMBER_DANA_SKPD) from PANJAR b where b.ID_SUMBER_DANA_SKPD = a.ID_SUMBER_DANA_SKPD) PANJAR_PAKAI');
    $this->db->where('a.ID_SUMBER_DANA_SKPD', $id);
    $result = $this->db->get('SUMBER_DANA_SKPD a')->row_array();

    if ($result['RINCIAN_BKU_SKPD_PAKAI'] > 0 || $result['PANJAR_PAKAI'] > 0) {
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }*/

  function check_isi()
  {
    $idrekening = $this->input->post('idrekening');$idrekening=($idrekening)?$idrekening:null;
    $namasumber = $this->input->post('namasumber');$namasumber=($namasumber)?$namasumber:null;
    $namabank   = $this->input->post('namabank');$namabank=($namabank)?$namabank:null;
    $norekening  = $this->input->post('norekening');$norekening=($norekening)?$norekening:null;

    $this->db->trans_start();
    $query = "SELECT * FROM SUMBER_DANA_SKPD WHERE ID_REKENING='$idrekening' OR NAMA_SUMBER_DANA='$namasumber'";
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

  function check_isi_1()
  {
    $idrekening = $this->input->post('idrekening');$idrekening=($idrekening)?$idrekening:null;
    $namasumber = $this->input->post('namasumber');$namasumber=($namasumber)?$namasumber:null;
    $namabank   = $this->input->post('namabank');$namabank=($namabank)?$namabank:null;
    $norekening  = $this->input->post('norekening');$norekening=($norekening)?$norekening:null;

    $this->db->trans_start();
    $id = $this->db->query("select ID_REKENING as ID_REK, NAMA_SUMBER_DANA as NAMA_SUMB from SUMBER_DANA_SKPD WHERE ID_SUMBER_DANA_SKPD='".$this->input->post('id')."'")->row_array();

    //$query = "SELECT * FROM SUMBER_DANA_SKPD WHERE ID_REKENING='$idrekening'";
    //$result = $this->db->query($query);

    $this->db->select('ID_REKENING');
    $this->db->from('SUMBER_DANA_SKPD');
    $this->db->where('ID_REKENING <>', $id['ID_REK']);
    $ada = $this->db->get()->result_array();
    $hasil = null;
    if(count($ada) > 0)
    {
      foreach($ada as $row)
      {
        $hasil[] = $row['ID_REKENING'];
      }
    }

    //echo "KOK $id[ID_REK],$id[NAMA_SUMB],$namasumber";

    /*if($idrekening == $id['ID_REK'] ){
      return TRUE;
    }
    else if (in_array($idrekening, $hasil)) {
      return FALSE;
    }
    else{
      return TRUE;
    }  */

    if($idrekening == $id['ID_REK']){
      return TRUE;
    }
    else if ($idrekening <> $id['ID_REK']) {
      $query = "SELECT * FROM SUMBER_DANA_SKPD WHERE ID_REKENING='$idrekening'";
      $result = $this->db->query($query)->result_array();

      if(count($result) > 0){
        return FALSE;
      }
      else{
        return TRUE;
      }
    }
    $this->db->trans_complete();
  }

  function check_isi_2()
  {
    $idrekening = $this->input->post('idrekening');$idrekening=($idrekening)?$idrekening:null;
    $namasumber = $this->input->post('namasumber');$namasumber=($namasumber)?$namasumber:null;
    $namabank   = $this->input->post('namabank');$namabank=($namabank)?$namabank:null;
    $norekening  = $this->input->post('norekening');$norekening=($norekening)?$norekening:null;

    $this->db->trans_start();
    $id = $this->db->query("select ID_REKENING as ID_REK, NAMA_SUMBER_DANA as NAMA_SUMB from SUMBER_DANA_SKPD WHERE ID_SUMBER_DANA_SKPD='".$this->input->post('id')."'")->row_array();


    $this->db->select('NAMA_SUMBER_DANA');
    $this->db->from('SUMBER_DANA_SKPD');
    $this->db->where('NAMA_SUMBER_DANA <>', $id['NAMA_SUMB']);
    $adas = $this->db->get()->result_array();
    $sumber = null;
    if(count($adas) > 0)
    {
      foreach($adas as $rows)
      {
        $sumber[] = $rows['NAMA_SUMBER_DANA'];
      }
    }

    /*if($namasumber == $id['NAMA_SUMB'] ){
      return TRUE;
    }
    else if (in_array($namasumber, $sumber)) {
      return FALSE;
    }
    else{
      return TRUE;
    }  */

    if($namasumber == $id['NAMA_SUMB']){
      return TRUE;
    }
    else if ($namasumber <> $id['NAMA_SUMB']) {
      $query = "SELECT * FROM SUMBER_DANA_SKPD WHERE NAMA_SUMBER_DANA='$namasumber'";
      $result = $this->db->query($query)->result_array();

      if(count($result) > 0){
        return FALSE;
      }
      else{
        return TRUE;
      }
    }
    $this->db->trans_complete();
  }
}
?>