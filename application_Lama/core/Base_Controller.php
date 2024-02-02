<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*********************************************************************************************
  SUPER CLASS dari semua Modul 
 *********************************************************************************************/
class Base_Controller extends CI_Controller{

  var $message_berhasil_dihapus  = 'Data telah dihapus.';
  var $message_gagal_dihapus  = 'Data tidak bisa dihapus.';

  public function __construct()
  {
    parent::__construct();
    $user_data = $this->session->userdata;
    $this->load->vars($user_data);
  }

  public function index()
  {
    $this->daftar();
  }

  protected function get_grid_model()
  {
    // di override
  }

  protected function get_data_fields()
  {
    // di override
  }

  protected function daftar()
  {
    // di override
  }

  public function get_daftar()
  {
  
  }
  
  public function form($id=0)
  {
  
  }
  
  protected function validasi_form()
  {
  
  }
  
  public function proses()
  {
    $response = (object) NULL;
    $this->load->model('auth/login_model', 'auth');
    $akses = $this->auth->get_level_akses($this->uri->slash_segment(1));
    if($akses == '3')
    {
      $this->load->library('form_validation');

      $this->validasi_form();

      if($this->form_validation->run() == TRUE)
      {
        $this->data_model->fill_data();
        $success = $this->data_model->save_data();

        if(!$success){
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
    }
    else
    {
      $response->isSuccess = FALSE;
      $response->message = 'Proses Gagal, Anda tidak memiliki hak akses';
    }
    echo json_encode($response);
  }
  
  public function hapus()
  {
    $response = (object) NULL;
    $this->load->model('auth/login_model', 'auth');
    $akses = $this->auth->get_level_akses($this->uri->slash_segment(1));

    if($akses !== 3)
    {
        $response->isSuccess = FALSE;
        $response->message = 'Anda tidak memiliki Hak akses.';
        echo json_encode($response);
        return;
    }

    $id = $this->input->post('id');
    $result = $this->data_model->check_dependency($id);

    if($result)
    {
      // bisa dihapus
      $this->data_model->delete_data($id);
      $response->isSuccess = TRUE;
      $response->message = $this->message_berhasil_dihapus;
    }
    else
    {
      // ada dependensi, tampilkan pesan kesalahan
      $response->isSuccess = FALSE;
      $response->Message = $this->message_gagal_dihapus;
    }

    echo json_encode($response);
  }

}