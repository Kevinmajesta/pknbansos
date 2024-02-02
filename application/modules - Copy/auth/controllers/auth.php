<?php

class Auth extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Login_model');
  }

  function cek()
  {
    $this->Login_model->max_id_group();
  }

  function index()
  {
    if($this->session->userdata('login') == TRUE){
      redirect('home');
    }
    else
    {
      $data['title'] = PRODUCT_NAME.' | Login';
      $data['option_tahun'] = $this->Login_model->get_thn();
      $data['tahun_kini'] = date('Y');
      $this->load->view('login_view', $data);;
    }
  }

  function process_login()
  {
    $this->load->library('form_validation');

    $this->form_validation->set_rules('username','Username');
    $this->form_validation->set_rules('password','Password');

    if($this->form_validation->run() == TRUE){
      $username = $this->input->post('username');
      $password = $this->input->post('password');
      $tahun    = $this->input->post('tahun');

      $cek = $this->Login_model->check_user($username, $password);
      //print_r($cek)  ;
      switch($cek){

        case 1 :
        $error = "<strong>Status akun anda tidak aktif. Hubungi Administrator</strong>";
        $this->session->set_flashdata('message', $error);
        break;

        case 2 : //berhasil
        $user = $this->Login_model->get_nama($username);
        $status = $this->Login_model->cek_status($tahun);
        $pemda = $this->Login_model->get_pemda();
        $user_data = array(
          'username' => $username,
          'nama_operator' => $user['NAME'],
          'login'  => TRUE,
          'id_user' => $user['ID'],
          'id_skpd' => $user['SKPD_ID'] ? $user['SKPD_ID'] : 0,
          'kode_skpd' => $user['KODE_SKPD_LKP'],
          'nama_skpd' => $user['NAMA_SKPD'],
          'group' => $user['GROUP_ID'],
          'tahun' => $tahun,
          'status' => $status,
          'nama_pemda' => $pemda['NAMA_PEMDA'],
        );

        $this->session->set_userdata($user_data);
        break;

        case 3 : //group tdk aktif
        $error = "<strong>Status Group anda tidak aktif.</strong>";
        $this->session->set_flashdata('message', $error);
        break;

        default:
        $error = "<B>Username atau password Anda salah!</B>";
        $this->session->set_flashdata('message', $error);
        break;
      }
      redirect('login');
    }
    else
    {
      $this->load->view('login_view',$data);
    }
  }
  function get_thn_session($tahun = 0)
  {
    $option_status = $this->Login_model->get_sts($tahun);
    $js = 'id="status_id" class="span2"';
    if($option_status)
    {
      echo form_dropdown('STATUS', $option_status, '', $js);
    }
    else
    {
      echo form_dropdown('STATUS', array(''=> '-'), '', $js);
    }
  }

  function user($id)
  {
    $data['title'] = PRODUCT_NAME.' - User';
    $data['main_content'] = 'user_add';
    if($id == 0 || $id != $this->session->userdata('id_user')){
      redirect('');
    }

    $data['data'] = $this->Login_model->get_data_by_id($id);
    $data['groups'] = $this->Login_model->get_daftar_group();
    $data['users'] = $this->Login_model->get_daftar();
    $this->load->view('layout/template', $data);
  }
  
  function group($id)
	{
		$data['title'] = 'SIMHIMBANSOS - Privacy Setting';
		$data['main_content'] = 'group_add';
		$data['modul'] = 'auth';
		$isi = $this->Login_model->get_data_by_id($id);
		if($isi['GROUP_ID'] !== 0){
			$data['isi'] = $this->Login_model->get_data_by_id($id);
			$data['data'] = $this->Login_model->get_group_by_id($isi['GROUP_ID']);
			$group = $this->Login_model->is_checked($isi['GROUP_ID']);
			$data['group_menus'] = json_encode($group);
		}
		/* else
		{
			$group = $this->Group_model->get_group_menu();
			$data['group_menus'] = json_encode($group);
			$data['group_menu'] = $group;
		} */

		$this->load->view('layout/template', $data);
	}
  
  function user_list()
  {
    $data['title'] = PRODUCT_NAME.' - User';
    $data['main_content'] = 'user_list';
    $data['modul'] = 'auth';
    $data['users'] = $this->Login_model->get_daftar();
    $this->load->view('layout/template', $data);
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
    elseif($this->input->post('mode') == "edit"){
      if($this->input->post('displaypassword') == "true"){
        $this->form_validation->set_rules('old_passwd', 'Password Lama', 'required|trim|callback__cek_password_lama');
        $this->form_validation->set_rules('passwd', 'Password baru', 'required|trim|min_length[8]');
        $this->form_validation->set_rules('repasswd', 'Ulangi password baru', 'required|trim|min_length[8]|matches[passwd]');
      }
      else $this->form_validation->run() == TRUE;
    }

    $this->form_validation->set_message('required', '%s tidak boleh kosong.');
    $this->form_validation->set_message('max_length', '%s tidak boleh melebihi %s karakter.');
    $this->form_validation->set_message('min_length', '%s minimal %s karakter.');
    $this->form_validation->set_message('_cek_username', '%s sudah ada.');
    $this->form_validation->set_message('_cek_email', '%s sudah ada atau Email tidak valid.');
    $this->form_validation->set_message('_cek_password_lama', '%s tidak sama.');
    $this->form_validation->set_message('matches', '%s tidak sama.');

    if($this->form_validation->run() == TRUE){
      $success = $this->Login_model->save_data();

      if(!$success)
      {
        $response->isSuccess = TRUE;
        if($this->input->post('displaypassword') == "true")
        {
          $this->session->sess_destroy();
          $response->message = 'Data berhasil diubah, Silakan login kembali';
          $response->redirect = TRUE;
        }
        else $response->message = 'Data berhasil disimpan';
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

  public function _cek_username($username)
  {
    return $this->Login_model->cek_duplikasi_username($username);
  }

  public function _cek_email($email)
  {
    return $this->Login_model->cek_duplikasi_email($email);
  }

  public function _cek_group($gname)
  {
    return $this->Login_model->cek_duplikasi_groupname($gname);
  }

  public function _cek_password_lama($old_passwd)
  {
    return $this->Login_model->cek_old_password($old_passwd);
  }

  public function lost_paswd()
  {

    if($this->input->post('save')){
      if($this->input->post('email') === "")//inputan kosong..
      {
        $error = "Email masih kosong";
        $this->session->set_flashdata('message', $error);
      }
      else
      {
        $imel = $this->Login_model->get_one('EMAIL','USERS',"EMAIL = '".$this->input->post('email')."'");

        if($imel != "")//exist
        {
          $newpassword = $this->createPassword(8);

          /*$config['charset'] = "utf-8";
          $config['mailtype'] = "html";
          $config['newline'] = "\r\n";

          $this->email->initialize($config);*/

          $config['protocol'] = "smtp";
          $config['smtp_host'] = "ssl://smtp.googlemail.com";
          $config['smtp_port'] = "465";
          $config['smtp_user'] = "danangh88@gmail.com";//also valid for Google Apps Accounts
          $config['smtp_pass'] = "ecommerce";
          $config['charset'] = "utf-8";
          $config['mailtype'] = "html";
          $config['newline'] = "\r\n";

          //$this->email->initialize($config);
          $this->load->library('email',$config);

          $this->email->from('No Reply');
          $this->email->to($imel);

          $this->email->subject('Reset Password');
          $message = ".:: Password telah direset::. \r\n ";

          $message .= "Password baru telah berhasil di generate. Password baru Anda : <strong>".$newpassword."</strong> \n";
          $this->email->message($message);

          //$this->email->send();
          //echo $this->email->print_debugger();

          if($this->email->send()){
            //update password baru

            $uid         = $this->Login_model->get_one('ID','USERS',"EMAIL = '".$this->input->post('email')."'");
            $salt        = $this->Login_model->get_one('SALT','USERS',"EMAIL = '".$this->input->post('email')."'");
            $data_update = array("PASSWD"=> sha1($salt.$newpassword));
            $update = $this->Login_model->update_passlost($data_update,$uid);
            $data['info'] = "Password Baru berhasil dikirim. Cek email Anda. (".$imel.")";
          }
          else
          {
            $this->data['error'] = "Pesan Gagal dikirim";
          }
        }
        else
        {
          $data['error'] = "Username atau Email tidak terdaftar dalam sistem.";
        }
      }
    }
    else $data['info'] = "Silakan masukkan Username or Email.";
    //$data['title'] = $this->function_lib->get_one('site_title_caption','sys_site_title','site_title_name = "Project Name"');

    $data['title'] = PRODUCT_NAME;
    $data['caption'] = 'Tambah User';
    $data['main_content'] = 'lost_passwd';
    $this->load->view('layout/template', $data);
  }

  function createPassword($length)
  {
    $chars    = "234567890abcdefghijkmnopqrstuvwxyz";
    $i        = 0;
    $password = "";
    while($i <= $length)
    {
      $password .= $chars
      {
        mt_rand(0,strlen($chars))
      };
      $i++;
    }
    return $password;
  }

  function logout()
  {
    $this->session->sess_destroy();
    redirect('login','refresh');
  }

  //upload image
  function upload()
  {
    $response = (object) NULL;

    $path = realpath( FCPATH.'assets/img/user/');
    $config['upload_path'] = $path;
    $config['allowed_types'] = 'gif|jpg|png|jpeg|ico';
    $config['max_size']  = '1000';
    $config['remove_spaces']  = TRUE;
    $this->load->library('upload', $config);
    if(!$this->upload->do_upload('image'))
    {
      //return $this->upload->display_errors();
      $response = array ('message' =>  $this->upload->display_errors(), 'isSuccess' => FALSE);
    }
    else
    {
      $dataz = $this->upload->data();
      //return $dataz['file_name'];
      $response = array ('filename' => $dataz['file_name'],'isSuccess' => TRUE, 'message' => 'Gambar berhasil diupload');
    }

    echo json_encode($response);
  }

  function icon()
  {
    $response = (object) NULL;

      $success = $this->Login_model->update_icon();
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
  
  public function logo() 
	{
		$this->db->select('LOGO');				
		$logo = $this->db->get('PEMDA');
		$result = $logo->result_array();
		$image = $this->db->get_blob($result[0]['LOGO']);
		if ($logo->num_rows() > 0)
		{
			header('Content-type: image/bmp');
			echo $image;
		}
	}

}