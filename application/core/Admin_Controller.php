<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * SUPER CLASS dari semua Modul Admin
 */
class Admin_Controller extends CI_Controller
{
    protected $jenis = "admin";
    protected $data = array();
    function __construct()
    {
        parent::__construct();
        $user_data = $this->session->userdata;
        $this->load->vars($user_data);

        //authentifikasi menu
		$cek = $this->authe->check_menu_privilage($this->uri->slash_segment(1)/*.$this->uri->segment(2)*/);
        //$this->output->enable_profiler(TRUE);
        if(! $this->authe->auth_admin())
        {
            redirect($this->config->item('base_url'));
			//echo 'tidak bisya';
        }
		elseif($cek == '1')
        {
			return TRUE;
        }
		else
		{
			redirect('');
		}
    }
	
	public function duplikat_nik()
	{
		$nik = $this->input->post('nik');
		$response = (object) NULL;
		
		$result = $this->data_model->check_nik($nik);
		if ($result && $result['NIK_PAKAI'] > 0)
		{
			$response->isSuccess = FALSE;
			$response->tanggal = format_date($result['TANGGAL'],$style='d F Y');
			switch ($result['JENIS']){
				case 'BANSOS' :
					$response->jenis = 'BAST Bantuan Sosial';
					break;
				case 'BANKEU' :
					$response->jenis = 'BAST Bantuan Keuangan';
					break;
				case 'NPHD' :
					$response->jenis = 'Hibah';
					break;
				case 'BANSOSNON' :
					$response->jenis = 'BAST Non Proposal';
					break;				
			}
			
		}
		else{
			$response->isSuccess = TRUE;
		}
		$response->sql = $this->db->queries;
		echo json_encode($response);
	}
}
?>
