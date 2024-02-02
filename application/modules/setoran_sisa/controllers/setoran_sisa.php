<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setoran_sisa extends Aktivitas_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->modul_name = 'setoran_sisa';
		$this->modul_display = 'Setoran Sisa UP';
		$this->view_form = 'setoran_form';
		$this->view_daftar = 'setoran_view';
		$this->load->model('setoran_model', 'data_model');

		$this->message_aktivitas_dihapus = 'Setoran Sisa telah dihapus.';
		$this->message_aktivitas_gagal_dihapus = 'Setoran Sisa telah dicairkan, tidak bisa dihapus.';
	}

	function validasi()
	{
		$this->form_validation->set_rules('id_pekas', 'Akun Bendahara', 'trim|integer');
		//$this->form_validation->set_rules('sd', 'Rincian Sumber Dana', 'required');
	}


	public function sisa_skpd()
	{
		$param = array(
			'id_skpd' => $this->input->post('id_skpd') ? $this->input->post('id_skpd') : 0,
			'tanggal' => $this->input->post('tanggal') ? $this->input->post('tanggal') : '',
			'id_aktivitas' => $this->input->post('id') ? $this->input->post('id') : 0
		);

		$result_sp2d_tanggal = $this->data_model->get_sisa_skpd($param,TRUE);
		$result_sp2d = $this->data_model->get_sisa_skpd($param);
		$response = (object) NULL;
		/* $response = array(
			'sisa' => isset($result_sp2d_tanggal['NOMINAL']) ? $result_sp2d_tanggal['NOMINAL'] : 0,
			'sisa_all' => isset($result_sp2d['NOMINAL']) ? $result_sp2d['NOMINAL'] : 0,
			'sql' => $this->db->queries,
		); */
		$response = array(
			'sisa' => isset($result_sp2d_tanggal) ? $result_sp2d_tanggal : 0,
			'sisa_all' => isset($result_sp2d) ? $result_sp2d : 0,
			'sql' => $this->db->queries,
		);
		echo json_encode($response);
	}

}