<?php

class Bast extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('bast_model', 'data_model');
		$this->load->model('auth/login_model', 'auth');
	}

	public function index()
	{
		$data['breadcrumbs'] = 'Daftar BAST Bantuan Sosial';
		$data['title'] = PRODUCT_NAME.' - '.$data['breadcrumbs'];
		$data['link_daftar'] = '/get_daftar';
		$data['modul'] = 'bast';
		$data['main_content'] = 'bast_view';
		$data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
		$data['fields'] = $this->data_model->get_data_fields();
		$this->load->view('layout/template',$data);
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
			"m" => isset($_REQUEST['m']) ? $_REQUEST['m'] : '',
			"q" => isset($_REQUEST['q']) ? $_REQUEST['q'] : '',
			"search_field" => isset($_REQUEST['searchField']) ? $_REQUEST['searchField'] : null,
			"search_operator" => isset($_REQUEST['searchOper']) ? $_REQUEST['searchOper'] : null,
			"search_str" => isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : null,
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
		$page = $total_pages;
		$start = $limit * $page - $limit;
		if($start < 0) $start = 0;
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
			$response->rows[$i]['id'] = $result[$i]['ID_BAST'];
			$response->rows[$i]['cell'] = array(
				$result[$i]['NOMOR_BAST'],
				$result[$i]['TANGGAL'],
				$result[$i]['NAMA'],
				$result[$i]['PERUNTUKAN'],
				$result[$i]['KEPERLUAN'],
				$result[$i]['NOMINAL'],				
			);
		}
		$response->sql = $this->db->queries;
		echo json_encode($response);
	}

	public function form($id=0)
	{
		$data['title'] = PRODUCT_NAME.' - '.'BAST - Bantuan Sosial';
		$data['modul'] = 'bast';
		$data['link_proses'] = 'proses';
		$data['link_back'] = '/daftar';
		$data['header'] = 'BAST';
		$data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
		if ($id!==0)
		{
			$data['data'] = $this->data_model->get_data_by_id($id);
		}
		
		//$data['jenis_bantuan'] = $this->data_model->get_data_jenis_bantuan()->result_array();

		$data['main_content']='bast_form';
		$this->load->view('layout/template',$data);
	}
    
	public function proses()
	{
		$response = (object) NULL;
		$this->load->library('form_validation');

		$this->form_validation->set_rules('no', 'Nomor BAST', 'required|trim|max_length[100]|callback__cek_nomor');
		$this->form_validation->set_rules('tgl', 'Tanggal', 'required|trim');
		$this->form_validation->set_rules('nik', 'NIK', 'required|trim');
		$this->form_validation->set_rules('nama', 'Nama', 'required|trim');
		$this->form_validation->set_rules('pekerjaan', 'Pekerjaan', 'required|trim');
		$this->form_validation->set_rules('mewakili', 'Mewakili', 'required|trim');

		/* TODO : cek rincian ada isinya atau tidak */

		$this->form_validation->set_message('required', '%s tidak boleh kosong.');
		$this->form_validation->set_message('max_length', '%s tidak boleh melebihi %s karakter.');
		$this->form_validation->set_message('_cek_nomor', '%s sudah ada.');

		if ($this->form_validation->run() == TRUE){
			$this->data_model->fill_data();
			$success = $this->data_model->save_data();

			if (!$success)
			{
				$response->isSuccess = TRUE;
				$response->message = 'Data berhasil disimpan';
				$response->id = $this->data_model->id;
				$response->sql = $this->db->queries;
			}
			else
			{
				$response->isSuccess = FALSE;
				$response->message = 'Data gagal disimpan';
				$response->sql = $this->db->queries;
			}
		}
		else
		{
			$response->isSuccess = FALSE;
			$response->message = validation_errors();
		}

		echo json_encode($response);
	}
	
	public function _cek_nomor($nomor)
	{
		return $this->data_model->cek_duplikasi_nomor($nomor);
	}
  
	public function prev($id=0)
	{
		$response = (object) NULL;
		$response->isSuccessful = FALSE;
		if ($id!==0)
		{
			$result = $this->data_model->get_prev_id($id);
			if ($result)
			{
				$response->isSuccessful = TRUE;
				$response->id = $result;
			}
		}
		echo json_encode($response);
	}

	public function next($id=0)
	{
		$response = (object) NULL;
		$response->isSuccessful = FALSE;
		if ($id!==0)
		{
			$result = $this->data_model->get_next_id($id);
			if ($result)
			{
				$response->isSuccessful = TRUE;
				$response->id = $result;
			}
		}
		echo json_encode($response);
	}

	public function hapus()
	{
		$id = $this->input->post('id');
		$response = (object) NULL;
		
		$this->data_model->delete_data($id);
		$response->isSuccess = TRUE;
		$response->message = 'Data BAST telah dihapus.';

		echo json_encode($response);
	}
	
	public function do_upload()
	{
		$this->load->helper('file');
		$jenis_uji = $this->input->post('jenis_uji');

		$result = (object) NULL;

		$path = $this->data_model->path;
		if (!file_exists($path)) {
			mkdir('uploads', 0777, true);
		}

		if(isset($_FILES["file"]))
		{      
			$error =$_FILES["file"]["error"];
			//You need to handle  both cases
			//If Any browser does not support serializing of multiple files using FormData() 
			if(!is_array($_FILES["file"]["name"])) //single file
			{
				$filename = $_FILES["file"]["name"];
				$filesize = filesize($_FILES["file"]["tmp_name"]);
				$newfilename = $this->set_filename($filename, $jenis_uji);

				move_uploaded_file($_FILES["file"]["tmp_name"],$path.$newfilename);

				$result->realname = $filename;
				$result->name = $newfilename;
				$result->size = $filesize;
				$result->mime = get_mime_by_extension($filename);
				$result->tgl = date('Y-m-d');
			}
			else  //Multiple files, file[]
			{
				$fileCount = count($_FILES["file"]["name"]);
				for($i=0; $i < $fileCount; $i++)
				{
					$filename = $_FILES["file"]["name"][$i];
					$filesize = filesize($_FILES["file"]["tmp_name"]);
					$newfilename = $this->set_filename($filename, $jenis_uji);

					move_uploaded_file($_FILES["file"]["tmp_name"][$i],$path.$newfilename);

					$result->realname = $filename;
					$result->name = $newfilename;
					$result->size = $filesize;
					$result->mime = get_mime_by_extension($filename);
					$result->tgl = date('Y-m-d');
				}
			}
		}

		echo json_encode($result);
	}
	
	public function set_filename($filename, $jenis_uji)
	{
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		
		if ($jenis_uji === 'adm')
		  $ext_name = 'dokumen_uji_administrasi';
		else if ($jenis_uji === 'mat')
		  $ext_name = 'dokumen_uji_material';
		
		$date = date('Y_m_d_G_i_s');
		$filename = strtolower($filename);
		$newfilename = $ext_name.'_'.$date.'_'.$filename.'.'.$ext;
		
		return $newfilename;
	}
	
	function get_fileupload_mat($id = 0)
	{
		$response = (object) NULL;

		if ($id === 0)
		{
			echo json_encode($response);
			return;
		}

		$result = $this->data_model->get_scan_file_mat($id);

		if ($result){
			for($i=0; $i<count($result); $i++)
			{
				$file = $this->data_model->path.$result[$i]['NAMA_FILE'];
				if (file_exists($file))
				{
					$response->rows[$i]['id'] = $result[$i]['ID_DOKUMEN_BAST'];
					$response->rows[$i]['cell']=array(
						$result[$i]['ID_DOKUMEN_BAST'],
						$result[$i]['NAMA_DOKUMEN'],
						$result[$i]['NAMA_FILE'],
						$result[$i]['MIME'],
						$result[$i]['UKURAN'],
						$result[$i]['TANGGAL_UPLOAD'],
					);
				}
			}
		}
		echo json_encode($response);
	}
	
	function rincian($id = 0,$id_skpd = 0,$id_rekening = 0,$id_rincian_anggaran = 0)
	{
		$result = $this->data_model->get_rincian($id,$id_skpd,$id_rekening,$id_rincian_anggaran);		
		$bast_pakai = $this->data_model->total_bast($id,$id_rekening);
		$response = (object) NULL;
		if ($result){
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id'] = $result[$i]['ID_RINCIAN_ANGGARAN'];
				$response->rows[$i]['cell']=array(
					$result[$i]['ID_RINCIAN_ANGGARAN'],
					$result[$i]['ID_REKENING'],
					$result[$i]['KODE_REKENING'],
					$result[$i]['NAMA_REKENING'],
					($result[$i]['LEVEL']==2)?(($result[$i]['NOMINAL_BAST']>0)?$result[$i]['NOMINAL_BAST']:$result[$i]['PAGU']):$result[$i]['PAGU'],
					//($result[$i]['PAGU']-$bast_pakai),
					0,
					($result[$i]['PAGU']-$bast_pakai),
					$result[$i]['LEVEL'],
				);
			}
		}
		$response->sql = $this->db->queries;
		echo json_encode($response);
	}
	
	public function data_rekening()
	{
		$id = $this->input->post('id') ? $this->input->post('id') : 0;
		$param = array(
			'id' => $this->input->post('id') ? $this->input->post('id') : 0,
			'id_skpd' => $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0,
			'idra' => $this->input->post('idra') ? $this->input->post('idra') : 0,
			'idrek' => $this->input->post('idrek') ? $this->input->post('idrek') : 0,
			'keperluan' => $this->input->post('keperluan') ? $this->input->post('keperluan') : '',
		);

		$result = $this->data_model->get_data_rekening($param);
		$response = (object) NULL;
		if ($result){
			$response = array(
				'id' => $id,
				'nama_rekening' => $result['nama_rekening'],
				'total_pagu' => $result['total_pagu'],
				'bast_pakai' => $result['bast_pakai'],
				'nominal_sp2d' => $result['nominal_sp2d'],
				'sql' => $this->db->queries,
			);
		}
		echo json_encode($response);
	}
}