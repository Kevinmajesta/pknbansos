<?php

class Pemda extends Admin_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->modul_name = 'pemda';
    $this->modul_display = 'Data Pemda';
    $this->view_form = 'pemda_form';
	$this->load->library('form_validation');
	$this->load->model('auth/login_model', 'auth');
    $this->load->library('session');
    $this->load->model('pemda_model', 'data_model');
  }

  public function index()
  {
    $data = array();
	$data['title'] = PRODUCT_NAME.' - '.' Data Pemda';
    $data['modul'] = $this->modul_name;
    $data['modul_display'] = $this->modul_display;
    $data['data'] = $this->data_model->get_data_pemda();
    $data['main_content'] = $this->view_form;
	$data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    $this->load->view('layout/template', $data);
  }

  public function proses()
  {
    $response = (object) NULL;
    $success = $this->data_model->update_data();

    if($success){
      $response->isSuccess = TRUE;
      $response->message = 'Data berhasil diperbaharui';
      $response->sql = $this->db->queries;
    }
    else
    {
      $response->isSuccess = FALSE;
      $response->message = 'Data gagal diperbaharui';
      $response->error = $this->data_model->last_error_message;
      $response->sql = $this->db->queries;
    }

    echo json_encode($response);
  }


    public function hapus_logo()
    {
    $this->data_model->delete_logo();
    $this->index();
    }

  function upload()
  {
    $response = (object) NULL;

    $path = realpath( FCPATH.'assets/img/');
    $File = realpath( FCPATH.'assets/img/logo-city.jpg');

    if (file_exists($File)) {
      unlink($File);
    }

    $config['file_name'] = 'logo-city';
    $config['upload_path'] = $path;
    //$config['allowed_types'] = 'gif|jpg|png|jpeg|ico';
    $config['allowed_types'] = 'jpg|jpeg';
    $config['max_height']  = '400';
    $config['max_width']  = '400';
    $config['remove_spaces']  = TRUE;
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

  function icon()
  {
    $response = (object) NULL;
    $File     = realpath( FCPATH.'assets/img/logo-city.jpg');

    if (file_exists($File)) {
      unlink($File);
      $this->data_model->update_icon();
      $response->isSuccess = TRUE;
      $response->message = 'Logo berhasil dihapus';
    }
    else{
      $response->isSuccess = FALSE;
      $response->message = 'Logo gagal dihapus';
    }
    echo json_encode($response);
  }
}