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
}
?>
