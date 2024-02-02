<?php
class Pemda_model extends CI_Model {

	var $field_pemda;
	var $data;

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();

		$this->fieldmap_pemda = array(
		  'nama' => 'NAMA_PEMDA',
		  'lokasi' => 'LOKASI',
		  //'logo' => 'LOGO',
		);
	}

	function fill_data()
	{
		foreach($this->fieldmap_pemda as $key => $value){
			switch ($key){
				//case 'logo' : $$key = 'logo-city'; break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			if(isset($$key))
			$this->data[$value] = $$key;
		}
	}

	function get_data_pemda()
	{
		$this->db->select('nama_pemda, lokasi, logo');
		$this->db->from('pemda');
		$result = $this->db->get()->row_array();

		return $result;
	}

	function update_data() {
		$this->fill_data();

		$this->db->trans_begin();
		$update = $this->db->update('pemda', $this->data);
		
		$dbh = $this->db->conn_id;
		$File	= realpath( FCPATH.'assets/img/logo-city.jpg');
		if (file_exists($File)) {
			$fd = fopen($File, 'r');
			$blob = ibase_blob_import($dbh, $fd);
			fclose($fd);

			if (!is_string($blob)) {
				// import failed
				echo "Gagal Import File";
			} else {
				$query = "UPDATE PEMDA SET LOGO = ?";
				$prepared = ibase_prepare($dbh, $query);
				if (!ibase_execute($prepared, $blob)) {
					// record update failed
					echo "Gagal Simpan Logo";
				}
			}
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->last_error_id = $this->db->_error_number();
			$this->last_error_message = $this->db->_error_message();
			$this->db->trans_rollback();
			return FALSE;
		}
		$this->db->trans_commit();
		return TRUE;
	}
	
	/* function save_logo()
	{
		$dbh = $this->db->conn_id;
		$fileName1 = $_FILES['image']['name'];
		$filename = './uploads/tmp/'.$fileName1;
		
		$fd = fopen($filename, 'r');
		if ($fd) {

			$blob = ibase_blob_import($dbh, $fd);
			fclose($fd);

			if (!is_string($blob)) {
				// import failed
				echo "Gagal Import File";
			} else {
				$query = "UPDATE PEMDA SET LOGO = ?";
				$prepared = ibase_prepare($dbh, $query);
				if (!ibase_execute($prepared, $blob)) {
					// record update failed
					echo "Gagal Simpan Logo";
				}
			}
		} else {
			// unable to open the data file
			echo "Tidak dapat membuka data";
		}
	} */
  
	function update_icon()
	{
		$this->db->where('NAMA_PEMDA', $this->input->post('nama'));
		$this->db->update('PEMDA', array('LOGO' => NULL));
	}
}