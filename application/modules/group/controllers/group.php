<?php

class Group extends Admin_Controller
{
  public
  function __construct()
  {
    parent::__construct();
    $this->load->model('Group_model');
    $user_data = $this->session->userdata;
    $this->load->vars($user_data);
    $this->load->model('auth/login_model', 'auth');
  }

  function cek()
  {
    $this->Group_model->max_id_group();
  }

  function index()
  {
    $this->group_list();
  }

  function groups($id = 0)
  {
    $data['title'] = 'SIMHIMBANSOS - Group';
    $data['main_content'] = 'group_add';
    $data['modul'] = 'group';
    if($id !== 0){
      $data['data'] = $this->Group_model->get_group_by_id($id);
      $group = $this->Group_model->is_checked($id);
      $data['group_menus'] = ($group);
    }
    else
    {
      $group = $this->Group_model->get_group_menu();
      $data['group_menus'] = ($group);
      $data['group_menu'] = $group;
    }

    $this->load->view('layout/template', $data);
    $data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
  }

  function group_list()
  {
    $data['title'] = 'SIMHIMBANSOS - Group';
    $data['main_content'] = 'group_list';
    $data['modul'] = 'group';
    $data['group'] = $this->Group_model->get_daftar_group();
    $data['count'] = $this->Group_model->count_('GROUPS');
    $data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    $this->load->view('layout/template', $data);
  }

  function user($id = 0)
  {
    $data['title'] = 'SIMHIMBANSOS - User';
    $data['main_content'] = 'user_add';
    $data['modul'] = 'group/user_list';
    if($id !== 0){
      $data['data'] = $this->Group_model->get_data_by_id($id);
    }
    $data['groups'] = $this->Group_model->get_daftar_group();
    $data['users'] = $this->Group_model->get_daftar();
    $this->load->view('layout/template', $data);
    $data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
  }

  function user_list()
  {
    $data['title'] = 'SIMHIMBANSOS - User';
    $data['main_content'] = 'user_list';
    $data['modul'] = 'group';
    $data['users'] = $this->Group_model->get_daftar();
    $data['count'] = $this->Group_model->count_('USERS');
    $data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    $this->load->view('layout/template', $data);
  }

  function menu($id = 0)
  {
    $data['title'] = 'SIMHIMBANSOS - Menu';
    $data['modul'] = 'group';
    if($id !== 0){
      $data['main_content'] = 'menu_add';
      $parent_id = $this->Group_model->get_one("PARENT_ID","MENU","ID = '".$id."'");
      if($parent_id == '0'){
        //tambah
        $data['caption'] = 'Tambah Child Menu '.$this->Group_model->get_one("TITLE","MENU","ID = '".$id."'");
        $data['idn'] = $id;
      }
      else
      {
        //edit
        $data['data'] = $this->Group_model->get_menu_by_id($id);
        $data['caption'] = 'Edit Menu';
      }
      $data['parent'] = $this->Group_model->get_parent_menu();
      $this->load->view('layout/template', $data);
    }
    else
    {
      $data['main_content'] = 'group_add_2';
      $group = $this->Group_model->get_group_menu();
      $data['group_menus'] = json_encode($group);
      $data['group_menu'] = $group;
      $this->load->view('layout/template', $data);
      $data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    }

  }
  
  function upload()
  {
    
    $response = (object) NULL;
    
    $path = realpath( FCPATH.'assets/img/user/');
                $config['upload_path'] = $path;
				$config['allowed_types'] = 'gif|jpg|png|jpeg|ico';
				$config['max_size']	= '1000';
                $config['remove_spaces']	= TRUE;
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('image'))
                {
                    //return $this->upload->display_errors();
                    $response = array ('message' =>  $this->upload->display_errors(),'isSuccess' => FALSE);
                }
                else
                {
                  $dataz = $this->upload->data();
                  //return $dataz['file_name'];
                  $response = array ('filename' => $dataz['file_name'],'isSuccess' => TRUE,'message' => 'Gambar berhasil diupload');
                }
    
    echo json_encode($response);
      
  }

  function user_proses()
  {
    $response = (object) NULL;


    $this->load->library('form_validation');

    $this->form_validation->set_rules('username', 'Username', 'required|trim|max_length[50]|callback__cek_username');
    $this->form_validation->set_rules('email', 'Email', 'required|trim|callback__cek_email');
    if($this->input->post('mode') == 'new'){
      $this->form_validation->set_rules('passwd', 'Password', 'required|trim|min_length[8]');
      $this->form_validation->set_rules('repasswd', 'Ulangi password', 'required|trim|min_length[8]|matches[passwd]');
    }
    
    $this->form_validation->set_message('required', '%s tidak boleh kosong.');
    $this->form_validation->set_message('max_length', '%s tidak boleh melebihi %s karakter.');
    $this->form_validation->set_message('min_length', '%s minimal %s karakter.');
    $this->form_validation->set_message('_cek_username', '%s sudah ada.');
    $this->form_validation->set_message('_cek_email', '%s sudah ada atau Email tidak valid.');
    $this->form_validation->set_message('matches', '%s tidak sama.');

    if($this->form_validation->run() == TRUE){
      // $this->Group_model->fill_data();
      $success = $this->Group_model->save_data();
      

      if(!$success)
      {
        $response->isSuccess = TRUE;
        $response->message = 'Data berhasil disimpan';
        //$response->id = $this->Group_model->id;
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
    
    //$response->isSuccess = TRUE;

    echo json_encode($response);
  }

  public function _cek_username($username)
  {
    return $this->Group_model->cek_duplikasi_username($username);
  }

  public function _cek_email($email)
  {
    return $this->Group_model->cek_duplikasi_email($email);
  }

  public function _cek_password_lama($old_passwd)
  {
    return $this->Group_model->cek_old_password($old_passwd);
  }

  function group_proses()
  {
    $response = (object) NULL;

    $this->load->library('form_validation');

    $this->form_validation->set_rules('gname', 'Nama Group', 'required|trim|max_length[50]|callback__cek_group');


    $this->form_validation->set_message('required', '%s tidak boleh kosong.');
    $this->form_validation->set_message('_cek_group', '%s sudah ada.');


    if($this->form_validation->run() == TRUE){
      //$this->data_model->fill_data();
      $success = $this->Group_model->save_group();

      if(!$success)
      {
        $response->isSuccess = TRUE;
        $response->message = 'Data berhasil disimpan';
        //$response->id = $this->Group_model->id;
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

  function act()
  {
    $response = (object) NULL;
    $aksi = NULL;
    //check ketika aksi delete (user ato group)
    if($this->input->post('act') == 'delete'){
      //DELETE USER, sebelum didelete di cek apakah id yg akan dihapus aktif (sedang login) ato tidak
      if($this->input->post('type') == 'USERS'){
        if($this->input->post('id') == $this->session->userdata('id_user')) //id yg mo didelete sama dengan id session user yg sedang login
        {
          $response->isSuccess = FALSE;
          $response->message = 'Proses Gagal, User sedang Aktif';
        }
        else $aksi = TRUE;
      }

      //DELETE GROUP, sebelum didelete di cek apakah group punya anggota ato tdk
      elseif($this->input->post('type') == 'GROUPS'){
        $cek = $this->Group_model->_cek_member_group($this->input->post('id'));
        //jika group ada anggotanya
        if($cek != '0'){
          $response->isSuccess = FALSE;
          $response->message = 'Proses Gagal, Group masih digunakan user';
        }
        else $aksi = TRUE;
      }

    }

    //ketika post act == update
    if($this->input->post('act') == 'update')     /*{
    if($this->input->post('type') == 'USERS')
    {
    if($this->input->post('id') == $this->session->userdata('id_user')) //id yg mo diupdate sama dengan id session user yg sedang login
    {
    $response->isSuccess = FALSE;
    $response->message = 'Proses Gagal, User sedang Aktif, tidak bisa dinonaktifkan';
    }
    else $aksi = TRUE;
    }

    elseif($this->input->post('type') == 'GROUPS')
    {
    if($this->input->post('id') == $this->session->userdata('group')) //id yg mo diupdate sama dengan id session group yg sedang login
    {
    $response->isSuccess = FALSE;
    $response->message = 'Proses Gagal, Group sedang digunakan, tidak bisa dinonaktifkan';
    }
    else $aksi = TRUE;
    }
    }*/

    $aksi = TRUE;

    if($aksi == TRUE){
      $success = $this->Group_model->act();

      if(!$success){
        $response->isSuccess = TRUE;
        $response->message = 'Proses Berhasil';
      }
      else
      {
        $response->isSuccess = FALSE;
        $response->message = 'Proses Gagal';
      }
    }
    //else echo 'g ada aksi';

    echo json_encode($response);
  }

  public function _cek_group($gname)
  {
    return $this->Group_model->cek_duplikasi_groupname($gname);
  }

  public function update()
  {
    $act = $_POST['action'];
    $updt= $_POST['itemz'];

    if($act == "updates"){
      $x = 1;
      foreach($updt as $val){
        $qry = $this->Group_model->update_arr($x,$val);
        $x++;
      }
    }
  }

	function menu_proses()
	{
		$response = (object) NULL;
		$this->load->library('form_validation');

		//$this->form_validation->set_rules('n_menu', 'Nama menu', 'required|trim|max_length[20]');
		//$this->form_validation->set_message('required', '%s tidak boleh kosong.');
		//add_separator $this->input->post('n_menu')

		// if($this->form_validation->run() == TRUE){
		//$this->data_model->fill_data();
		if($this->input->post('is_checked') != '0' && $this->input->post('links') != '#23'){
			$success = $this->Group_model->save_menu();

			if(!$success)
			{
				$response->isSuccess = TRUE;
				$response->message = 'Data berhasil disimpan';
				//$response->id = $this->Group_model->id;
				$response->sql = $this->db->queries;
			}
			else
			{
				$response->isSuccess = FALSE;
				$response->message = 'Data gagal disimpan';
				$response->sql = $this->db->queries;
			}
		}
		else{
			$response->isSuccess = TRUE;
			$response->message = 'Separator berhasil disimpan';
			$successs = $this->Group_model->separator($this->input->post('parent'));
			//redirect('group/menu');
		}
		/* }
		else
		{
		$response->isSuccess = FALSE;
		$response->message = validation_errors();
		} */

		echo json_encode($response);
	}
	
	function simpan_menu()
	{
		$response = (object) NULL;				
		$this->Group_model->simpan_menu_form();
		$response->isSuccess = TRUE;
		$response->message = 'Perubahan Menu Berhasil !!!';
		$response->sql = $this->db->queries;
		echo json_encode($response); 
	}

	function delete_menu()
	{
		$response = (object) NULL;

		$success = $this->Group_model->del($this->input->post('id'));

		if(!$success)
		{
			$response->isSuccess = TRUE;
			$response->message = 'Menu berhasil dihapus';
			//$response->id = $this->Group_model->id;
			$response->sql = $this->db->queries;
		}
		else
			{
			$response->isSuccess = FALSE;
			$response->message = 'Menu gagal dihapus';
			$response->sql = $this->db->queries;
		}

		echo json_encode($response);
	}

	function add_separator($id)
	{
		$success = $this->Group_model->separator($id);
		redirect('group/menu');
	}

  function menu_by_id()
  {
    /*$this->load->model('auth/login_model', 'auth');
    $akses = $this->auth->get_level_akses($this->uri->slash_segment(1));
    if($akses != '0')
    {*/
    $result   = $this->Group_model->get_menu_by_id($this->input->post('id'));
    $response = (object) NULL;
    $response->results = array();
    if($result){
      $response = array(
        'id'       => $result['ID'],
        'title'    => $result['TITLE'],
        'links'    => $result['LINK'],
        'aktif'    => $result['AKTIF'],
        'parent_id'=> $result['PARENT_ID']
      );
    }
    echo json_encode($response);
  }
  //}
  
	function parent_by_id($id)
	{
		$result   = $this->Group_model->get_parent_by_id($id);
		$response = (object) NULL;
		$response->results = array();
		if($result){
			/* $response = array(
				'id'       => $result['ID'],
				'title'    => $result['TITLE'],
				'links'    => $result['LINK'],
				'aktif'    => $result['AKTIF'],
				'parent_id'=> $result['PARENT_ID']
			); */
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id'] = $result[$i]['ID'];
				$response->rows[$i]['title'] = $result[$i]['TITLE'];
			}
		}
		
		echo json_encode($response);
	}
	
	function data_parent_by_id($id)
	{
		$result   = $this->Group_model->get_data_parent_by_id($id);
		$response = (object) NULL;
		$response->results = array();
		if($result){
			/* $response = array(
				'id'       => $result['ID'],
				'title'    => $result['TITLE'],
				'links'    => $result['LINK'],
				'aktif'    => $result['AKTIF'],
				'parent_id'=> $result['PARENT_ID']
			); */
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id'] = $result[$i]['ID'];
				$response->rows[$i]['title'] = $result[$i]['TITLE'];
			}
		}
		
		echo json_encode($response);
	}
  
  function icon()
  {
    $response = (object) NULL;

      $success = $this->Group_model->update_icon();
      if(!$success)
      {
        $response->isSuccess = TRUE;
        $response->message = 'Icon berhasil dihapus';
        //$response->id = $this->Group_model->id;
        $response->sql = $this->db->queries;
      }
      else
      {
        $response->isSuccess = FALSE;
        $response->message = 'Icon gagal dihapus';
        $response->sql = $this->db->queries;
      }

    echo json_encode($response);
  }


}