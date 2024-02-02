<?php

class Pejabat_daerah extends Base_Controller {
	public function __construct()
  {
    parent::__construct();
		$this->load->model('pejabatdaerah_model','data_model');
		$this->load->model('auth/login_model', 'auth');
		$this->load->library('form_validation');
	}
	
	public function index() 
	{
		$data['title']=PRODUCT_NAME.' - '.' Pejabat Daerah';
		$data['main_content']='pejabat_daerah_view';
		$data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
		$this->load->view('layout/template',$data);
	}
	
	public function proses_form() 
	{
		$response = (object) NULL;
    $id = $this->input->post('id');
		$oper = $this->input->post('oper');

		if($oper == 'del') 
		{
			$this->hapus($id);
			return '';
		} 

		// validasi data
		$this->form_validation->set_rules('jabatan', 'JABATAN', 'required|trim|max_length[100]');
		$this->form_validation->set_rules('nama', 'NAMA', 'required|trim|max_length[50]');

		if ($this->form_validation->run($this)==TRUE)
		{
			if($oper == 'edit') {
				
				if($id == 'new') {
					$result = $this->data_model->check_isi(); // cek NIP
					if ($result == FALSE){
						$newid = $this->data_model->insert_data();
						$response->isSuccess = TRUE;
						$response->message = 'Pejabat daerah telah disimpan.';
						$response->id = $newid;
					}
					else 
					{
						// Pejabat daerah sudah ada, tampilkan pesan kesalahan
						$response->isSuccess = FALSE;
						$response->message = 'Pejabat daerah tersebut sudah ada.';
					}
				} 
				else {
					$result = $this->data_model->check_isi2($id); // cek NIP
					if ($result == FALSE){
						$this->data_model->update_data($id);
						$response->isSuccess = TRUE;
						$response->message = 'Pejabat daerah telah diubah.';
					}
					else 
					{
						// Pejabat daerah sudah ada, tampilkan pesan kesalahan
						$response->isSuccess = FALSE;
						$response->message = 'NIP ada yang sama.';
					}
				}
				
			}
		}
		else 
		{
			$response->isSuccess = FALSE;
			$response->message = validation_errors();
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
			$response->message = 'Pejabat daerah telah dihapus';
		} 
		else 
		{
			// ada dependensi, tampilkan pesan kesalahan
			$response->isSuccess = FALSE;
			$response->message = 'Pejabat daerah tidak bisa dihapus, masih dipakai di tabel lain.';
			//$this->output->set_status_header('500');
		}
		echo json_encode($response);
	}
	
	public function get_daftar() 
	{
		$response = (object) NULL;
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
		if(!$row) // tidak ada data
		{
			$response->total = 0; 
			$response->records = 0;
			echo json_encode($response);
			return '';
		}

		$count = count($row); 
		$total_pages = ceil($count/$limit); 

		if ($page > $total_pages) 
			$page=$total_pages; 
		$start = $limit*$page - $limit; // do not put $limit*($page - 1) 
		if($start <0) $start = 0;
		$req_param['limit'] = array(
					'start' => $start,
					'end' => $limit
		);
		  
		$result = $this->data_model->get_data($req_param);
		$response->page = $page; 
		$response->total = $total_pages; 
		$response->records = $count;
	
		for($i=0; $i<count($result); $i++)
		{
			$response->rows[$i]['id']=$result[$i]['ID_PEJABAT_DAERAH'];
			if($result[$i]['AKTIF'] == 1)
				{
				$AKTIF = 'Aktif';
				}
				else if($result[$i]['AKTIF'] == 0)
				{
				$AKTIF = 'Tidak Aktif';
				}
			
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->rows[$i]['cell']=array(
				$result[$i]['JABATAN'],
				$result[$i]['NAMA_PEJABAT'],
				$result[$i]['NIP'],
				$AKTIF
			);
		}
		echo json_encode($response);
		
	}
	
	function duplikasi_kode($str)
	{
		$id = $this->input->post('id');
		if ($this->data_model->check_isi($str) == TRUE)
		{
			$this->form_validation->set_message('duplikasi_kode', '%s "'.$str.'" sudah ada.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
		
	public function get_jabatan()
	{
		$q = strtolower($_GET["q"]);
		if (!$q) return;
		//$result = $this->data_model->get_opt_jabatan();
		$result = array('Kepala Daerah','Wakil Kepala Daerah','Ketua DPRD','Wakil Ketua DPRD','Kepala BAPPEDA','Kepala BAWASDA','Sekretaris Daerah','Sekretaris DPRD','Asisten Daerah I','Asisten Daerah II','Asisten Daerah III','Asisten Daerah IV','Kepala Bagian Keuangan','BUD/Kuasa BUD','PPKD');
		
			foreach($result as $key=>$value)
			{
				if (strpos(strtolower($value), $q) !== false)
				{
					echo $value."\n";
				}
			}
		
	}

}