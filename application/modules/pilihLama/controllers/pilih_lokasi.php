<?php

class Pilih_lokasi extends Pilih_Controller{
  public function __construct()
  {
    parent:: __construct();
    $this->load->model('pilih_lokasi_model', 'pilih_model');
  }

  function index()
  {
  }

  /*
      Pilih Lokasi
  */

  var $opt_lokasi = array(
    'multi' => array('default' =>0),
//    'mode' => array('default' =>''),
  );

  function getlokasi()
  {
    $param = $this->getParam($this->opt_lokasi);
    $result = $this->pilih_model->getLokasi($param);
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    if ($result){
      for($i=0; $i<count($result); $i++){
				for($i=0; $i<count($result); $i++)
				{
					if($result[$i]['LEVEL_LOKASI']=='1'){
						$LOKASI= $result[$i]['LOKASI'];
					}
					elseif($result[$i]['LEVEL_LOKASI']=='2'){
						$LOKASI= '   '.$result[$i]['LOKASI'];
					}
					elseif($result[$i]['LEVEL_LOKASI']=='3'){
						$LOKASI= '      '.$result[$i]['LOKASI'];
					}
					elseif($result[$i]['LEVEL_LOKASI']=='4'){
						$LOKASI= '         '.$result[$i]['LOKASI'];
					}
					elseif($result[$i]['LEVEL_LOKASI']=='5'){
						$LOKASI= '               '.$result[$i]['LOKASI'];
					}
					
					
					$response->rows[$i]['id']=$result[$i]['ID_LOKASI'];
					$response->rows[$i]['cell']=array(
													$result[$i]['ID_LOKASI'],
													$LOKASI,
													$result[$i]['LEVEL_LOKASI']
												);
				}
      }
    }
    echo json_encode($response);
  }

  function lokasi()
  {
    $data['dialogname'] = 'lokasi';
    $data['colsearch'] = array('lokasi' => 'Lokasi');
    $data['colnames'] = array('', 'Lokasi', 'Level Lokasi');
    $data['colmodel'] = array(
      array('name' => 'id', 'hidden' => true),
      array('name' => 'lokasi', 'width' => '500', 'sortable' => true),
      array('name' => 'lvl_lokasi', 'hidden' => true),
    );
    $data['orderby'] = 'id';
    $data['param'] = $this->getParam($this->opt_lokasi);

    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/pilih_lokasi/get'.$data['dialogname'],
      'pager' => '#pgrDialog'.$data['dialogname'],
      'sortname' => $data['orderby'],
      'multiselect' => (int)$data['param']['multi'],
      'colNames' => $data['colnames'],
      'colModel' => $data['colmodel'],
      'postData' => $data['param'],
    );

    echo json_encode($response);
  }
}