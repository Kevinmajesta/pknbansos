<?php

class Rekening_pptk extends CI_Controller {
	public function __construct()
    {
		parent::__construct();
		$this->load->model('rekeningpptk_model','data_model');
		$this->load->model('auth/login_model', 'auth');
		$this->load->library('session');
		$this->load->library('form_validation');
	}
	
	public function index() 
	{
		$data['title']=PRODUCT_NAME.' - '.' Rekening PPTK';
		$data['main_content']='rekeningpptk_view';
		$data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    $data['id_skpd'] = $this->session->userdata('id_skpd'); // set id_skpd diambil dari session
		$this->load->view('layout/template',$data);
	}
	
	public function proses_form() 
	{
		$id = $this->input->post('id');
		$idrekening = $this->input->post('idrekening');
		$oper = $this->input->post('oper');

		if($oper == 'del') 
		{
			$this->hapus($id);
			return '';
		} 
		
		$this->form_validation->set_rules('idrekening', 'REKENING', 'required|trim');
		$this->form_validation->set_rules('koderekening', 'KODE REKENING', 'required|trim');
		$this->form_validation->set_rules('namarekening', 'NAMA REKENING', 'required|trim');
		$this->form_validation->set_rules('idkegiatan', 'KEGIATAN', 'required|trim');
		$this->form_validation->set_rules('kodekegiatan', 'KODE KEGIATAN', 'required|trim');
		$this->form_validation->set_rules('namakegiatan', 'NAMA KEGIATAN', 'required|trim');
		$this->form_validation->set_rules('namapejabat', 'PEJABAT', 'required|trim');
    
    $this->form_validation->set_message('required', '%s tidak boleh kosong.');
    
		if ($this->form_validation->run()==TRUE)
		{
      $pejabat = $this->data_model->check_pejabat();
      if($pejabat == FALSE) {
        $response->isSuccess = FALSE;
        $response->message = 'Rekening PPTK tidak bisa disimpan, Pejabat belum ada di dalam daftar.';
        $response->cell = 'namapejabat';
      }
      else {
        if($oper == 'edit') {
          if($id == 'new') {
            $result = $this->data_model->check_isi();
            if ($result == FALSE){
              $result1 = $this->data_model->check_isi_1();
              if ($result1 == FALSE){
                $this->data_model->insert_data();
                $response->id = $this->input->post('idrekening');
                $response->isSuccess = TRUE;
                $response->message = 'Rekening PPTK telah disimpan.';
                $response->sql = $this->db->queries;
              }
              else {
                $response->isSuccess = FALSE;					
                $response->message = 'Kegiatan tersebut sudah ada.';
              }
            } 
            else {
              $response->isSuccess = FALSE;					
              $response->message = 'Rekening PPTK tersebut sudah ada.';
            }
          }
          else 
          {
            $result = $this->data_model->check_isi_2();
            if ($result == TRUE){
              $result2 = $this->data_model->check_isi_3();
              if ($result2 == TRUE){
                $this->data_model->update_data($id);
                $response->isSuccess = TRUE;
                $response->message = 'Rekening PPTK telah dirubah.';
                $response->sql = $this->db->queries;
              }
              else{
                $response->isSuccess = FALSE;
                $response->message = 'Rekening PPTK tidak bisa disimpan, Kegiatan telah dipakai.';
              }
            } 
            else 
            {
              $response->isSuccess = FALSE;
              $response->message = 'Rekening PPTK tidak bisa disimpan, Rekening telah dipakai.';
            }
          }
        }
      }
		}
		else 
		{
			$response->error = validation_errors();
			$this->output->set_status_header('500');			
		}
		echo json_encode($response);
	
	}
	
	public function hapus() 
	{
		$id = $this->input->post('id');
		$result = $this->data_model->check_dependency($id);
		if ($result) {
			// bisa dihapus
			$this->data_model->delete_data($id);
			$response->isSuccess = TRUE;
			$response->message = 'Rekening PPTK telah dihapus';
      $response->sql = $this->db->queries;
		} 
		else 
		{
			// ada dependensi, tampilkan pesan kesalahan
			$response->isSuccess = FALSE;
			$response->message = 'Rekening PPTK tidak bisa dihapus, masih dipakai di tabel lain.';
			//$this->output->set_status_header('500');
		}
		echo json_encode($response);
	}
	

	
	public function get_daftar() 
	{
		$page = $_REQUEST['page']; // get the requested page 
		$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid 
		$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort 
		$sord = $_REQUEST['sord']; // get the direction if(!$sidx) $sidx =1;  
		 
		$req_param = array (
				"sort_by" => $sidx,
				"sort_direction" => $sord,
				"limit" => null,
				"search" => $_REQUEST['_search'],
				"search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
				"search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
				"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
		);     
		   
		$row = $this->data_model->get_data($req_param);
		$row = $row->result_array();
		$count = count($row);
		if( $count >0 ) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		} 

		if ($page > $total_pages) 
			$page=$total_pages; 
		$start = $limit*$page - $limit; // do not put $limit*($page - 1) 
		if($start <0) $start = 0;
		$req_param['limit'] = array(
					'start' => $start,
					'end' => $limit
		);
		  
		$result = $this->data_model->get_data($req_param)->result_array();
		
		$response->page = $page; 
		$response->total = $total_pages; 
		$response->records = $count;
    $response->sql = $this->db->queries;
					
		for($i=0; $i<count($result); $i++)
		{
			$response->rows[$i]['id']=$result[$i]['ID_REKENING'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->rows[$i]['cell']=array(
											$result[$i]['ID_REKENING'],
											$result[$i]['KODE_REKENING'],
											$result[$i]['NAMA_REKENING'],
											$result[$i]['ID_KEGIATAN'],
											$result[$i]['KODE_KEGIATAN_SKPD'],
											$result[$i]['NAMA_KEGIATAN'],
											$result[$i]['ID_PPTK'],
											$result[$i]['NAMA_PEJABAT'],											
										);
		}
		echo json_encode($response); 
	
	}
  
  public function get_pejabat()
  {
    $q = strtolower($_POST["q"]) ? $_POST["q"] : null;
    $result = $this->data_model->get_data_pejabat()->result_array();

    $data = array();
    foreach($result as $key=>$value)
    {
      if (strpos(strtolower($value['NAMA_PEJABAT']), $q) !== false)
      {
        $data[] = array('id'=>$value['ID_PEJABAT_SKPD'], 'nama'=>$value['NAMA_PEJABAT']);
      }
    }
    
    echo json_encode($data);

  }
	
}