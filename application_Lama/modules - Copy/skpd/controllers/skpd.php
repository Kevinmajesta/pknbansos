<?php

class Skpd extends Admin_Controller {
	public function __construct()
    {
        parent::__construct();
		$this->load->model('skpd_model','data_model');
    $this->load->model('auth/login_model', 'auth');
		$this->load->library(array('session','form_validation'));
	}
	
	public function index() 
	{		
		$data['user_data']['DATA_DASAR_LOGIN'] = $this->session->userdata('group');
		$data['title']='Daftar SKPD';
		$data['main_content']='skpd_view';
    $data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
		$data['fields'] = $this->data_model->get_data_fields();
		$this->load->view('layout/template',$data);
	}
	
	/* SKPD */
	public function proses_form() 
	{
		$id = $this->input->post('id');
		$idbidangtambahan = $this->input->post('idbidangtambahan');
		$oper = $this->input->post('oper');
		
		if ($oper == 'del'){
			$this->hapus($id);
			return '';
		}
		
		
		$this->form_validation->set_rules('skpd', 'SKPD', 'required|trim|max_length[10]');
		$this->form_validation->set_rules('namaskpd', 'NAMA', 'required|trim|max_length[100]');
		$this->form_validation->set_rules('lokasi', 'LOKASI', 'max_length[100]');
		$this->form_validation->set_rules('alamat', 'ALAMAT', 'max_length[100]');
		$this->form_validation->set_rules('npwp', 'NPWP', 'max_length[50]');
		//$this->form_validation->set_rules('idbidangtambahan', 'idbidangtambahan', 'max_length[1000]');

	
	if ($this->form_validation->run()==TRUE)
		{
			if($oper == 'edit') {
				if($id == 'new') {
					$result = $this->data_model->check_data();
					if ($result == FALSE){
						$newid = $this->data_model->insert_data();
						$response->message = 'SKPD telah disimpan,silahkan anda tambahkan pejabat SKPD dan atau Bidang Tambahan';
						$response->isSuccess = TRUE;
						$response->id = $newid;
					}
					else 
					{
						// SKPD sudah ada, tampilkan pesan kesalahan
						$response->message = 'SKPD tersebut sudah ada.';
						$response->isSuccess = FALSE;
					}
				} 
				else {
					$rst = $this->data_model->check_data2();
					if ($rst == TRUE){
						$newid= $this->data_model->update_data($id);
						$response->message = 'SKPD telah diubah.';
						$response->isSuccess = TRUE;
						$response->id = $newid;
					}
					else 
					{
						// SKPD sudah ada, tampilkan pesan kesalahan
						$response->message = 'SKPD tersebut sudah ada.';
						$response->isSuccess = TRUE;
					}
				}
			}
		}
		else 
		{
			$response->isSuccess = FALSE;
			$response->error = validation_errors();					
		}
		echo json_encode($response);		

	}
	
	public function hapus() 
	{
		$id = $this->input->post('id');		
		$id = $id[0];	
		$result = $this->data_model->check_dependency($id);
		if ($result) {
			// bisa dihapus
			$this->data_model->delete_data($id);
			$response->message = 'SKPD telah dihapus';
			$response->isSuccess = TRUE;
		} 
		else 
		{
			// ada dependensi, tampilkan pesan kesalahan
			$response->message = 'SKPD tidak bisa dihapus, masih dipakai di tabel lain.';
			$response->isSuccess = FALSE;			
		}
		echo json_encode($response);
	}
	
	public function get_daftar()
	{
		if(!isset($_POST['oper']))
		{
			$page = $_REQUEST['page']; // get the requested page 
			$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid 
			$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort 
			$sord = $_REQUEST['sord']; // get the direction if(!$sidx) $sidx =1;  
			 
			$req_param = array (
					"sort_by" => $sidx,
					"sort_direction" => $sord,
					"limit" => null,
					"m" => isset($_REQUEST['m']) ? $_REQUEST['m'] : '',
					"q" => isset($_REQUEST['q']) ? $_REQUEST['q'] : '',
					"search" => $_REQUEST['_search'],
					"search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
					"search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					
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
			
			
			$z=0;
			$last_urusan = $last_bidang = 0;
			for($i=0; $i<count($result); $i++)
			{
				$PLAFON_BTL =0.00;
				
				$query= $this->data_model->get_data_bidangtambahan($result[$i]['ID_SKPD']);
				
				if ($query) {
					$BIDANG_TAMBAHAN = $query;
				}
				else 
				{					
					$BIDANG_TAMBAHAN['ID_BIDANG_TAMBAHAN'] = '';
					$BIDANG_TAMBAHAN['BIDANG_TAMBAHAN'] = '';
				}
				
				$query2= $this->data_model->get_data_bidangskpd($result[$i]['ID_SKPD']);
				if ($query2) {
					$BIDANGSKPD = $query2;
				}
				else 
				{
					$BIDANGSKPD['ID_BIDANG_SKPD'] = '';
					$BIDANGSKPD['BIDANG_SKPD'] = '';
				}
				
				
				if ($result[$i]['ID_URUSAN'] != $last_urusan){
					// tambahkan urusan
					$response->rows[$z]['cell']=array(
						'00',
						null,
						$result[$i]['KODE_URUSAN'],
						null,
						null,
						$result[$i]['NAMA_URUSAN'],
						null,
						null,
						null,
						null,
						null,
						null,
						null,
						null
					);
					$z++;
				}
				
				if ($result[$i]['ID_BIDANG'] != $last_bidang){
					// tambahkan bidang
					$response->rows[$z]['cell']=array(
						'01',
						$result[$i]['ID_BIDANG'],
						null,
						$result[$i]['KODE_BIDANG'],
						null,
						$result[$i]['NAMA_BIDANG'],
						null,
						null,
						null,
						null,
						null,
						null,
						null,
						null
					);
					$z++;
				}
				if($result[$i]['ID_SKPD'] != '')
				{
				$response->rows[$z]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$z]['cell']=array($result[$i]['ID_SKPD'],
												$result[$i]['ID_BIDANG'],
												null,
												null,
												$result[$i]['KODE_SKPD'],
												$result[$i]['NAMA_SKPD'],
												$result[$i]['LOKASI'],
												$result[$i]['ALAMAT'],
												$result[$i]['NPWP'],
												$BIDANG_TAMBAHAN['BIDANG_TAMBAHAN'],
												$BIDANG_TAMBAHAN['ID_BIDANG_TAMBAHAN'],
												$BIDANGSKPD['BIDANG_SKPD'],
												$BIDANGSKPD['ID_BIDANG_SKPD'],
												$PLAFON_BTL
											);
				$z++;
				$last_urusan = $result[$i]['ID_URUSAN'];
				$last_bidang = $result[$i]['ID_BIDANG'];
				}
			}
			$response->sql = $this->db->queries;
			echo json_encode($response); 
		}
	}
	
	public function proses_form_gridbidang() 
	{
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');

		if($oper == 'del') 
		{
			$this->hapus_bidangtambahan($id);
			return '';
		} 
		
		if($_POST['ID_BIDANG']) 
		{
			$rsbidang = $this->data_model->check_bidang();
			if ($rsbidang == TRUE){	
				$this->insert_bidangtambahan();			
				$response->message = 'Bidang Tambahan telah disimpan.';
				$response->isSuccess = TRUE;
			}
			else 
			{						
				$response->message = 'Bidang tambahan tersebut sudah ada.';
				$response->isSuccess = FALSE;
			}
		}
	
		echo json_encode($response);
	}
	
	public function hapus_bidangtambahan() 
	{
			$id = $this->input->post('id');
			$this->data_model->delete_data_bidangtambahan($id);
			$response->message = 'Bidang Tambahan telah dihapus';
			$response->isSuccess = TRUE;
			echo json_encode($response);

	}
	
	public function insert_bidangtambahan() 
	{	
		$this->data_model->fill_data_bidangtambahan();
		$this->data_model->insert_data_bidangtambahan();
	}
	
	public function proses_form_gridbidang_skpd() 
	{
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');

		if($oper == 'del') 
		{
			$this->hapus_bidangtambahan_skpd($id);
			return '';
		} 
		
		
		if($oper == 'edit'){				
			if($id == 'new'){
				$result = $this->data_model->check_data_bidang_skpd();
				if ($result == FALSE){
					$this->data_model->fill_data_bidangtambahan_skpd();
					$newid = $this->data_model->insert_bidangtambahan_skpd();
					$response->message = 'Bagian SKPD sudah disimpan';
					$response->isSuccess = TRUE;
					$response->id = $newid;	
				}
				else 
				{
					// Bidang SKPD sudah ada, tampilkan pesan kesalahan
					$response->message = 'Bagian SKPD tersebut sudah ada.';
					$response->isSuccess = FALSE;
				}
			}
			else
			{
				$rst = $this->data_model->check_data_bidang_skpd2();
				if ($rst == TRUE){
					$this->data_model->fill_data_bidangtambahan_skpd();
					$this->data_model->update_bidangtambahan_skpd($id);
					$response->message = 'Bagian SKPD sudah di ubah';
					$response->isSuccess = TRUE;
				}
				else 
				{
					// Bidang SKPD sudah ada, tampilkan pesan kesalahan
					$response->message = 'Bagian SKPD tersebut sudah ada.';
					$response->isSuccess = FALSE;
				}
			}
		}
	
		echo json_encode($response);
	}
	
	public function hapus_bidangtambahan_skpd() 
	{
			$id = $this->input->post('id');
			$this->data_model->delete_data_bidangtambahan_skpd($id);
			$response->message = 'Bidang SKPD telah dihapus';
			$response->isSuccess = TRUE;
			echo json_encode($response);

	}
	
	public function insert_bidangtambahan_skpd() 
	{
		$this->data_model->fill_data_bidangtambahan();
		$this->data_model->insert_data_bidangtambahan();
		//redirect('skpd/index');
	}
	
	
	/* Pejabat SKPD */
	public function proses_form_pejabat() 
	{
		$ID_PEJABAT_SKPD = $this->input->post('ID_PEJABAT_SKPD');
		$oper = $this->input->post('oper');
		$id = $this->input->post('id');

		if($oper == 'del') 
		{
			$this->hapus_pejabat($id);
			return '';
		} 
		
		$this->form_validation->set_rules('JABATAN', 'JABATAN', 'required|trim|max_length[50]');
		$this->form_validation->set_rules('NAMA_PEJABAT', 'NAMA PEJABAT', 'required|trim|max_length[100]');
		$this->form_validation->set_rules('NIP', 'NIP', 'required|trim|max_length[50]');
		//$this->form_validation->set_rules('nip', 'NIP', 'callback_duplikasi_kode');		
		
		if ($this->form_validation->run()==TRUE)
		{
			if($oper == 'edit') {
				if($ID_PEJABAT_SKPD) {
					$this->data_model->update_data_pejabat($ID_PEJABAT_SKPD);
					$response->message = 'Pejabat SKPD telah diubah.';
					$response->isSuccess = TRUE;	
					$response->id = $id;						
				} else { 
					$newid = $this->data_model->insert_data_pejabat();
					$response->message = 'Pejabat SKPD telah disimpan.';
					$response->isSuccess = TRUE;
					$response->id = $newid;						
				}
				}
		}
		else 
		{
			$response->isSuccess = FALSE;
			$response->message = 'Pejabat SKPD gagal disimpan, Silahkan lengkapi Nama, Jabatan, dan NIP.';
			//$response->error = validation_errors();							
		}
		echo json_encode($response);
	}
	
	public function hapus_pejabat()
	{		
			$id=$this->input->post('id');
			$this->data_model->delete_data_pejabat($id);
			$response->message = 'Pejabat SKPD telah dihapus';
			$response->isSuccess = TRUE;
			echo json_encode($response);
	}
	
	public function get_daftar_pejabat($ID_SKPD='') 
	{
		if(!isset($_POST['oper']))
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
			   
			$row = $this->data_model->get_data_pejabat($req_param,$ID_SKPD)->result_array();
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
			
			$result = $this->data_model->get_data_pejabat($req_param,$ID_SKPD)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['ID_PEJABAT_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				if($result[$i]['AKTIF'] == 1)
				{
				$AKTIF = 'Aktif';
				}
				else if($result[$i]['AKTIF'] == 0)
				{
				$AKTIF = 'Tidak Aktif';
				}

				$response->rows[$i]['cell']=array($result[$i]['ID_PEJABAT_SKPD'],
												$result[$i]['JABATAN'],
												$result[$i]['NAMA_PEJABAT'],
												$result[$i]['NIP'],
												$AKTIF
											);
			}
			echo json_encode($response); 
		}
	}


	/*Tambah Data SKPD (insert)*/

	/*public function bidangtambahan($id)
	{
		$data['id'] = $id;
		$this->load->view('bidangtambahan', $data);
	}
	
	public function bidangskpd($id)
	{
		$data['id'] = $id;
		$this->load->view('bidangskpd', $data);
		
		$this->session->set_userdata('ID_SKPD_BIDANG',$id);
	}*/
	
	public function bidangtambahan()
	{
		//$data['id'] = $id;
		$this->load->view('bidangtambahan');
	}
	
	public function bidangskpd()
	{
		//$data['id'] = $id;
		$this->load->view('bidangskpd');
		//$this->session->set_userdata('ID_SKPD_BIDANG',$id);
	}
	
	public function session_id()
	{
		$ID_SKPD = $_POST['ID_SKPD'];
		$this->session->set_userdata('ID_SKPD_BIDANG',$ID_SKPD);
	}
	
	public function get_bidangtambahan()
	{
	if(!isset($_POST['oper']))
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
			  
			$row = $this->data_model->get_data_bidang($req_param);
			$count = count($row); 
			if( $count >0 ) 
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else
			{ 
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
			
			$result = $this->data_model->get_data_bidang($req_param);
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['ID_BIDANG_TAMBAHAN'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['ID_BIDANG_TAMBAHAN'],
												$result[$i]['BIDANG_TAMBAHAN'],
												$result[$i]['NAMA_BIDANG']
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_bidangskpd()
	{
		if(!isset($_POST['oper']))
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
			  
			$row = $this->data_model->get_data_bidang_skpd($req_param);
			$count = count($row); 
			if( $count >0 ) 
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else
			{ 
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
			
			$result = $this->data_model->get_data_bidang_skpd($req_param);
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['ID_UNIT_KERJA'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['ID_UNIT_KERJA'],
												$result[$i]['KODE_UNIT_KERJA'],
												$result[$i]['NAMA_UNIT_KERJA'],
												$result[$i]['ID_SKPD']
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function pilih_op()
	{
		$this->load->view('skpd_pilih_op');
	}

	/* Pilih SKPD */
	public function filter_skpd()
	{	
		if(isset($_REQUEST['op_teks_skpd']))
		{				
			$arr_temp['op_teks_skpd'] = trim($_REQUEST['op_teks_skpd']);
			
			if(isset($_REQUEST['op_filter_skpd']))
			{
				$arr_temp['op_filter_skpd'] = trim($_REQUEST['op_filter_skpd']);
				
				if(isset($_REQUEST['in_teks_skpd'])) $arr_temp['in_teks_skpd'] = trim($_REQUEST['in_teks_skpd']);
			}
		}

		if(count($arr_temp)>0) $this->session->set_userdata('tmp_filter_skpd',$arr_temp);
		else $this->session->unset_userdata('tmp_filter_skpd');	
	}
	
	public function daftar_subskpd()
	{
		$req_param='';
					
		if(!isset ($_POST['oper']))
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
			
			$row = $this->data_model->get_data_subskpd($req_param)->result_array();
			
			$count = count($row); 
			if( $count >0 ) 
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_data_subskpd($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
			
			//print_r($this->db->last_query());die();
			
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['ID_SKPD']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID_SKPD'],
												$result[$i]['KODE_SKPD_LKP'],
												$result[$i]['NAMA_SKPD']
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_list_skpd()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					
			);     
			   
			$row = $this->data_model->get_list_skpd($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_list_skpd($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['ID_SKPD'],
												$result[$i]['KODE'],
												$result[$i]['NAMA_SKPD'],
											);
			}
			echo json_encode($response); 
		}
	}
	
	/* Pilih SKPD u/ RKA*/
	public function pilih()
	{
		$this->load->view('skpd_pilih');
	}
	
	public function pilih_kua()
	{
		$this->load->view('skpd_kua_pilih');
	}
	
	public function pilih_tim()
	{
		$this->load->view('skpd_pilih_tim');
	}
	
	public function pilih_rka()
	{
		$this->load->view('skpd_rka_pilih');
	}
	
	public function pilih_rka1()
	{
		$this->load->view('skpd_rka1_pilih');
	}
	
	public function pilih_rka21()
	{
		$this->load->view('skpd_rka21_pilih');
	}
	
	public function pilih_rka22()
	{
		$this->load->view('skpd_rka22_pilih');
	}
	
	public function pilih_rka221()
	{
		$this->load->view('skpd_rka221_pilih');
	}
	
	public function pilih_rka31()
	{
		$this->load->view('skpd_rka31_pilih');
	}
	
	public function pilih_rka32()
	{
		$this->load->view('skpd_rka32_pilih');
	}
	
	public function pilih_dpa221()
	{
		$this->load->view('skpd_dpa_221_pilih');
	}
	
	public function pilih_dpa22()
	{
		$this->load->view('skpd_dpa22_pilih');
	}
	
	public function pilih_dpa21()
	{
		$this->load->view('skpd_dpa21_pilih');
	}
	
	public function pilih_dpa1()
	{
		$this->load->view('skpd_dpa1_pilih');
	}
	
	public function pilih_dpa31()
	{
		$this->load->view('skpd_dpa31_pilih');
	}
	
	public function pilih_dpa32()
	{
		$this->load->view('skpd_dpa32_pilih');
	}
	
	public function pilih_dpa()
	{
		$this->load->view('skpd_dpa_pilih');
	}
	
	public function pilih_dpalanjut()
	{
		$this->load->view('skpd_dpalanjut_pilih');
	}
	
	public function bidangtambahan2()
	{
		$this->load->view('bidangtambahan2');
	}

	public function get_bidangtambahan2()
	{
		if(!isset($_POST['oper']))
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
			   
			$row = $this->data_model->get_data_bidangtambahan2($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_data_bidangtambahan2($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_BIDANG'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['ID_BIDANG'],
												$result[$i]['KODE_BIDANG_LKP'],
												$result[$i]['NAMA_BIDANG']
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_skpd_rka()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					
			);     
			   
			$row = $this->data_model->get_pilih_rka($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_pilih_rka($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['KODE'],
												$result[$i]['NAMA_SKPD'],
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_skpd_kua()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					
			);     
			   
			$row = $this->data_model->get_pilih_kua($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_pilih_kua($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['ID_SKPD'],
												$result[$i]['KODE_SKPD_LKP'],
												$result[$i]['NAMA_SKPD']
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_skpd_rka1()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					
			);     
			   
			$row = $this->data_model->get_pilih_rka1($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_pilih_rka1($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['KODE'],
												$result[$i]['NAMA_SKPD'],
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_skpd_rka21()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					
			);     
			   
			$row = $this->data_model->get_pilih_rka21($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_pilih_rka21($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['KODE'],
												$result[$i]['NAMA_SKPD'],
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_skpd_rka22()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					
			);     
			   
			$row = $this->data_model->get_pilih_rka22($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_pilih_rka22($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['KODE_SKPD_LKP'],
												$result[$i]['NAMA_SKPD'],
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_skpd_rka221()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
			);     
			   
			$row = $this->data_model->get_pilih_rka221($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_pilih_rka221($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['KODE'],
												$result[$i]['NAMA_SKPD'],
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_skpd_rka31()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					
			);     
			   
			$row = $this->data_model->get_pilih_rka31($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_pilih_rka31($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['KODE'],
												$result[$i]['NAMA_SKPD'],
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_skpd_rka32()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					
			);     
			   
			$row = $this->data_model->get_pilih_rka32($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_pilih_rka32($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['KODE'],
												$result[$i]['NAMA_SKPD'],
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_skpd_dpa221()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
			);     
			   
			$row = $this->data_model->get_pilih_dpa221($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_pilih_dpa221($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['KODE'],
												$result[$i]['NAMA_SKPD'],
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_skpd_dpa22()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					
			);     
			   
			$row = $this->data_model->get_pilih_dpa22($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_pilih_dpa22($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['KODE'],
												$result[$i]['NAMA_SKPD'],
												$result[$i]['FORM_ANGGARAN'],
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_skpd_dpa21()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					
			);     
			   
			$row = $this->data_model->get_pilih_dpa21($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_pilih_dpa21($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['KODE'],
												$result[$i]['NAMA_SKPD'],
												$result[$i]['FORM_ANGGARAN'],
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_skpd_dpa1()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					
			);     
			   
			$row = $this->data_model->get_pilih_dpa1($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_pilih_dpa1($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['KODE'],
												$result[$i]['NAMA_SKPD'],
												$result[$i]['FORM_ANGGARAN'],
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_skpd_dpa31()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					
			);     
			   
			$row = $this->data_model->get_pilih_dpa31($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_pilih_dpa31($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['KODE'],
												$result[$i]['NAMA_SKPD'],
												$result[$i]['FORM_ANGGARAN'],

											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_skpd_dpa32()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					
			);     
			   
			$row = $this->data_model->get_pilih_dpa32($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_pilih_dpa32($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['KODE'],
												$result[$i]['NAMA_SKPD'],
												$result[$i]['FORM_ANGGARAN'],
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_skpd_dpa()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					
			);     
			   
			$row = $this->data_model->get_pilih_dpa($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_pilih_dpa($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['KODE'],
												$result[$i]['NAMA_SKPD'],
												$result[$i]['PEJABAT_SKPD'],
												$result[$i]['FORM_ANGGARAN'],
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function cek_murni($skpd, $kegiatan)
	{
		$murni = $this->data_model->cek_murni($skpd, $kegiatan);
		$respon->rows['status'] = false;
		if($murni)
		{
			$respon->rows['status'] = true;
			$respon->rows['form'] = $murni['ID_FORM_ANGGARAN'];
		}
		echo json_encode($respon);
	}
	
	public function get_skpd_dpalanjut()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
			);     
			   
			$row = $this->data_model->get_pilih_dpalanjut($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_pilih_dpalanjut($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['KODE'],
												$result[$i]['NAMA_SKPD'],
											);
			}
			echo json_encode($response); 
		}
	}
	
	public function get_jabatan()
	{
		$q = strtolower($_GET["q"]);
		if (!$q) return;
		$result = array('Kepala SKPD','PPTK','Bendahara Pengeluaran','Bendahara Penerimaan');
		
			foreach($result as $key=>$value)
			{
				if (strpos(strtolower($value), $q) !== false)
				{
					echo $value."\n";
				}
			}
		
	}
	
	public function get_list_skpd_tim()
	{
		if(!isset($_POST['oper']))
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					
			);     
			   
			$row = $this->data_model->get_list_skpd_tim($req_param)->result_array();
			$count = count($row); 
			if( $count >0 )
			{ 
				$total_pages = ceil($count/$limit); 
			} 
			else 
			{ 
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
			
			$result = $this->data_model->get_list_skpd_tim($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array(
												$result[$i]['ID_SKPD'],
												$result[$i]['KODE_SKPD_LKP'],
												$result[$i]['NAMA_SKPD'],
											);
			}
			echo json_encode($response); 
		}
	}
	
}