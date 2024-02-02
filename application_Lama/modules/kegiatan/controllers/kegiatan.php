<?php

class Kegiatan extends Base_Controller {
	public function __construct()
  {
    parent::__construct();
		$this->load->model('kegiatan_model','data_model');
		$this->load->model('auth/login_model', 'auth');
		$this->load->library('session');
		$this->load->library('form_validation');
	}
	
	public function index() 
	{
		$data['title'] = 'Kegiatan';
    $data['modul'] = 'Kegiatan';
		$data['main_content'] = 'kegiatan_view';
		$data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
		$this->load->view('layout/template',$data);
	}
	
	public function session_id()
	{
		$ID_KEGIATAN = isset($_POST['ID_KEGIATAN']) ? $_POST['ID_KEGIATAN'] : null;
		$this->session->set_userdata('ID_KEGIATAN',$ID_KEGIATAN);
	}
	
	public function proses_form() 
	{
		$response = (object) NULL;
    $id = $this->input->post('id');
		$oper = $this->input->post('oper');
		
		$pecah = substr($this->input->post('id'),0,4);
		if($pecah == "inp_"){
			$hsl = "new";
		}
		else{
			$hsl = $this->input->post('id');
		}
		
		$ID_KEGIATAN = $this->session->userdata('ID_KEGIATAN');	
		
		if($oper == 'del') 
		{
			$this->hapus($ID_KEGIATAN);
			return '';
		} 
				
		$this->form_validation->set_rules('PROGRAM', 'PROGRAM', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('KODE_KEGIATAN', 'KODE KEGIATAN', 'required|trim|max_length[20]');
		$this->form_validation->set_rules('NAMA_KEGIATAN', 'NAMA KEGIATAN', 'required|trim|max_length[500]');
		$this->form_validation->set_message('required', '%s Harus diisi');
	
		if ($this->form_validation->run()==TRUE)
			{
				if($oper == 'edit') {
					if($hsl == "new") {
						$result = $this->data_model->check_data();
						if ($result == FALSE){
							$newid = $this->data_model->insert_data();
							$response->isSuccess = TRUE;
							$response->message = 'Kegiatan telah disimpan.';
							$response->id = $newid;
						}
						else 
						{
							// Kegiatan sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Kegiatan tersebut sudah ada';
						}
					} 
					else {
						
						$rst = $this->data_model->check_data2();
						if ($rst == TRUE){
							$this->data_model->update_data($ID_KEGIATAN);
							$response->isSuccess = TRUE;
							$response->message = 'Kegiatan telah diubah.';
						}
						else 
						{
							// Kegiatan sudah ada, tampilkan pesan kesalahan
							$response->isSuccess = FALSE;
							$response->message = 'Kegiatan tersebut sudah ada.';
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
		$response = (object) NULL;
    $id = $this->input->post('id');
		$result = $this->data_model->check_dependency($id);
		if ($result) {
			// bisa dihapus
			$this->data_model->delete_data($id);
			$response->isSuccess = TRUE;
			$response->message = 'Kegiatan telah dihapus';
		} 
		else 
		{
			// ada dependensi, tampilkan pesan kesalahan
			$response->isSuccess = FALSE;
			$response->message = 'Kegiatan tidak bisa dihapus, masih dipakai di tabel lain.';
			//$this->output->set_status_header('500');
		} 
		echo json_encode($response);
	}
	
	public function get_daftar() 
	{
		$response = (object) NULL;
    if(!isset($_POST['oper']))
		{
			$page = $_REQUEST['page']; // get the requested page 
			$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid 
			$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort 
			$sord = $_REQUEST['sord']; // get the direction if(!$sidx) $sidx =1;  
			 
			$req_param = array (
					"sort_by" => $sidx,
					"sort_direction" => $sord,
					//"limit" => null,
					"search" => $_REQUEST['_search'],
					"search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
					"search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
			);     
			   
			$count = $this->data_model->get_data($req_param, TRUE);
			$response = (object) NULL;
      if($count == 0) // tidak ada data
      {
        echo json_encode($response);
        return '';
      }

			$total_pages = ceil($count/$limit); 
			if ($page > $total_pages) 
				$page=$total_pages; 

			$result = $this->data_model->get_data($req_param);
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['ID_KEGIATAN']=$result[$i]['ID_KEGIATAN'];
			
				if(isset($result[$i]['ID_KEGIATAN']))
				{
					$ID_KEGIATAN = $result[$i]['ID_KEGIATAN'];
				}
				else
				{
					$ID_KEGIATAN = "inp_".$result[$i]['ID_PROGRAM'];
				}

				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$PROGRAM = $result[$i]['KODE_PROGRAM'].' - '.$result[$i]['NAMA_PROGRAM'];
				$response->rows[$i]['id']=$ID_KEGIATAN;
				$response->rows[$i]['cell']=array($result[$i]['ID_KEGIATAN'],
												$result[$i]['KODE_URUSAN'],
												$result[$i]['KODE_BIDANG'],
												$result[$i]['KODE_SKPD'],
												$PROGRAM ,
												$result[$i]['ID_PROGRAM'],
												$result[$i]['KODE_PROGRAM'],
												$result[$i]['KODE_KEGIATAN'],
												$result[$i]['NAMA_KEGIATAN'],
											);
			}
			echo json_encode($response); 
		}
	}
	
	/* Get Select */
	
	public function getselect_urusan()
	{
		$js = 'id="urusan" name="urusan"';
		echo form_dropdown('urusan', $this->data_model->get_opt_urusan(),$js );
	}
		
	public function getselect_bidang($ID_URUSAN=0)
	{
		$response = (object) NULL;
    $result = $this->data_model->get_bidang($ID_URUSAN)->result_array();
		
		for($i=0; $i<count($result); $i++){
			$response->opt[$i] =$result[$i]['ID_BIDANG'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->opt[$i] =array('ID_BIDANG'=>$result[$i]['ID_BIDANG'],
									'KODE_BIDANG'=>$result[$i]['KODE_BIDANG']
									);
		}
		
		echo json_encode($response);
	}	
	
	public function getselect_program_x()
	{
		$js = 'id="program" name="program"';
		echo form_dropdown('progran', $this->data_model->get_opt_program(),$js );
	}
	
	public function getselect_program($ID_BIDANG=0)
	{
		$response = (object) NULL;
    $result = $this->data_model->get_program($ID_BIDANG)->result_array();
		
		for($i=0; $i<count($result); $i++){
			$response->opt[$i] =$result[$i]['ID_PROGRAM'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->opt[$i] =array('ID_PROGRAM'=>$result[$i]['ID_PROGRAM'],
									'KODE_PROGRAM'=>$result[$i]['KODE_PROGRAM']
									);
		}
		
		echo json_encode($response);
	}
	
	public function getselect_program_2($ID_PROGRAM=0)
	{
		$response = (object) NULL;
    $result = $this->data_model->get_program_2($ID_PROGRAM)->result_array();
		
		for($i=0; $i<count($result); $i++){
			$response->opt[$i] =$result[$i]['ID_PROGRAM'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->opt[$i] =array('ID_PROGRAM'=>$result[$i]['ID_PROGRAM'],
									'KODE_PROGRAM'=>$result[$i]['KODE_PROGRAM']
									);
		}
		
		echo json_encode($response);
	}
	
	public function getselect_program_3()
	{
		$response = (object) NULL;
    $result = $this->data_model->get_program_3()->result_array();
		
		for($i=0; $i<count($result); $i++){
			$response->opt[$i] =$result[$i]['ID_PROGRAM'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->opt[$i] =array('ID_PROGRAM'=>$result[$i]['ID_PROGRAM'],
									'KODE_PROGRAM'=>$result[$i]['KODE_PROGRAM']
									);
		}
		
		echo json_encode($response);
	}
	
	public function get_data_id($id=0)
	{
		$response = (object) NULL;
    $result = $this->data_model->get_data_by_id_2($id)->result_array();
		
		for($i=0; $i<count($result); $i++){
			$response->opt[$i] =$result[$i]['ID_KEGIATAN'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->opt[$i] =array(
									'ID_URUSAN'=>$result[$i]['ID_URUSAN'],
									'KODE_URUSAN'=>$result[$i]['KODE_URUSAN'],
									'ID_BIDANG'=>$result[$i]['ID_BIDANG'],
									'KODE_BIDANG'=>$result[$i]['KODE_BIDANG'],
									'ID_SKPD'=>$result[$i]['ID_SKPD'],
									'KODE_SKPD'=>$result[$i]['KODE_SKPD'],
									'ID_PROGRAM'=>$result[$i]['ID_PROGRAM'],
									'KODE_PROGRAM'=>$result[$i]['KODE_PROGRAM'],
									'ID_KEGIATAN'=>$result[$i]['ID_KEGIATAN'],
									'KODE_KEGIATAN'=>$result[$i]['KODE_KEGIATAN'],
									'NAMA_KEGIATAN'=>$result[$i]['NAMA_KEGIATAN'],
									
									);
		}
		
		echo json_encode($response);
	}
	
	public function get_data_id_program($id=0)
	{
		$response = (object) NULL;
    $result = $this->data_model->get_data_by_id_3($id)->result_array();
		
		for($i=0; $i<count($result); $i++){
			$response->opt[$i] =$result[$i]['ID_PROGRAM'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->opt[$i] =array(
									'ID_URUSAN'=>$result[$i]['ID_URUSAN'],
									'KODE_URUSAN'=>$result[$i]['KODE_URUSAN'],
									'ID_BIDANG'=>$result[$i]['ID_BIDANG'],
									'KODE_BIDANG'=>$result[$i]['KODE_BIDANG'],
									'ID_SKPD'=>$result[$i]['ID_SKPD'],
									'KODE_SKPD'=>$result[$i]['KODE_SKPD'],
									'ID_PROGRAM'=>$result[$i]['ID_PROGRAM'],
									'KODE_PROGRAM'=>$result[$i]['KODE_PROGRAM'],
									
									);
		}
		
		echo json_encode($response);
	}

}