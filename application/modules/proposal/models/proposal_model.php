<?php
class Proposal_model extends CI_Model
{

  var $id;
  var $nama_pmh;
  var $alamat_pmh;
  var $tgl_lhr;
  var $nama_pimp;
  var $bidang;
  var $fieldmap;
  var $fieldmap_proposal;
  var $data;
  var $tahun;
  var $skpd;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();

    $this->tahun = $this->session->userdata('tahun');
    $this->skpd = $this->session->userdata('id_skpd');

    $this->fieldmap = array(
      'id' => 'a.ID_PROPOSAL',
      'no' => 'a.NOMOR',
      'nama_pmh' => 'a.NAMA_PEMOHON',
      'alamat_pmh' => 'a.ALAMAT_PEMOHON',
      'tgl' => 'a.TANGGAL',
      'nom_aju' => 'a.NOMINAL_DIAJUKAN',
      'nom_setuju' => 'a.NOMINAL_DISETUJUI',
      'status' => 'a.STATUS',
      'tahun' => 'a.TAHUN',
    );

    $this->fieldmap_proposal = array(
      'id' => 'ID_PROPOSAL',
      'no' => 'NOMOR',
      'tgl' => 'TANGGAL',
      'bantuan' => 'JENIS_BANTUAN',
      'kategori' => 'KATEGORI',
      'nama_pmh' => 'NAMA_PEMOHON',
      'alamat_pmh' => 'ALAMAT_PEMOHON',
      'tgl_lhr' => 'TANGGAL_LAHIR',
      'nama_pimp' => 'NAMA_PIMPINAN',
      'bidang' => 'BIDANG',
      'ringkasan' => 'RINGKASAN',
      'nom_aju' => 'NOMINAL_DIAJUKAN',
      'nom_setuju' => 'NOMINAL_DISETUJUI',
      'status' => 'STATUS',
      'posted' => 'POSTED',
      'tahun' => 'TAHUN',
      'nik' => 'NIK'
    );
  }

  function fill_data()
  {
    foreach ($this->fieldmap_proposal as $key => $value) {
      switch ($key) {
        case 'tgl':
          $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL;
          break;
        case 'tgl_lhr':
          $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL;
          break;
        case 'tahun':
          $$key = $this->tahun;
          break;
        default:
          $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }
      $this->data[$value] = $$key;
    }
  }

  public function index()
  {
    $this->load->model('proposal_model');
    $data['title'] = 'Judul Halaman Anda';
    $data['totalProposal'] = $this->proposal_model->get_total_proposal();
    $this->load->view('home_view', $data);
  }

  // ----- search advance ---- >>
  function get_data_fields()
  {
    $bantuan = $this->db->query("SELECT DISTINCT r.JENIS_BANTUAN FROM KATEGORI_PEMOHON r")->result_array();
    foreach ($bantuan as $key => $val) {
      $opt_bantuan[$val['JENIS_BANTUAN']] = $val['JENIS_BANTUAN'];
    }
    $kategori = $this->db->query("SELECT DISTINCT r.KATEGORI FROM KATEGORI_PEMOHON r")->result_array();
    foreach ($kategori as $key => $val) {
      $opt_kategori[$val['KATEGORI']] = $val['KATEGORI'];
    }

    $fields = array(
      'no' => array('name' => 'Nomor Proposal', 'kategori' => 'string'),
      'nama_pmh' => array('name' => 'Nama Pemohon', 'kategori' => 'string'),
      'alamat_pmh' => array('name' => 'Alamat Pemohon', 'kategori' => 'string'),
      'tgl' => array('name' => 'Tanggal Masuk', 'kategori' => 'date'),
      'program' => array('name' => 'Program', 'kategori' => 'string'),
      'kegiatan' => array('name' => 'Kegiatan', 'kategori' => 'string'),
      'rekening' => array('name' => 'Rekening', 'kategori' => 'string'),
      'nom_aju' => array('name' => 'Nominal Pengajuan', 'kategori' => 'numeric'),
      'nom_setuju' => array('name' => 'Nominal Persetujuan', 'kategori' => 'numeric'),
      'status' => array('name' => 'Status', 'kategori' => 'predefined', 'options' => array('Lolos Uji' => 'Lolos Uji', 'Lolos Rekomendasi' => 'Lolos Rekomendasi', 'Telah Dianggarkan' => 'Telah Dianggarkan')),
    );

    return $fields;
  }

  function insert_data()
  {
    if (isset($this->data['ID_PROPOSAL'])) {
      $this->db->where('ID_PROPOSAL', $this->data['ID_PROPOSAL']);
      $this->db->update('PROPOSAL', $this->data);
      return $this->data['ID_PROPOSAL'];
    } else {
      $this->db->insert('PROPOSAL', $this->data);
      $this->db->select_max('ID_PROPOSAL')->from('PROPOSAL');
      $rs = $this->db->get()->row_array();
      return $rs['ID_PROPOSAL'];
    }
  }

  function save_data()
  {
    $this->db->trans_start();
    $this->id = $this->insert_data();
    $this->nama_pmh = $this->data['NAMA_PEMOHON'];
    $this->alamat_pmh = $this->data['ALAMAT_PEMOHON'];
    $this->tgl_lhr = $this->data['TANGGAL_LAHIR'];
    $this->nama_pimp = $this->data['NAMA_PIMPINAN'];
    $this->bidang = $this->data['BIDANG'];

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      return FALSE;
    }
  }

  function check_nik($no, $nik)
  {
    $id = $this->input->post('id');

    if ($id == 0) {
      $this->db->select('first 1 a.tanggal, count(a.nik) nik_pakai');
      $this->db->from('proposal a');
      $this->db->where('a.id_proposal <>', $id);
      $this->db->where('a.nik', $nik);
      $this->db->group_by('a.tanggal');
      $this->db->order_by('a.tanggal desc');

      $result = $this->db->get()->row_array();
    } else {
      $this->db->select('first 1 a.tanggal,count(a.nik) nik_pakai');
      $this->db->from('proposal a');
      $this->db->where('a.nomor', $no);
      $this->db->where('a.tahun', $this->tahun);
      $this->db->where('a.id_proposal <>', $id);
      $this->db->where('a.nik', $nik);
      $this->db->group_by('a.tanggal');
      $this->db->order_by('a.tanggal desc');
      $result = $this->db->get()->row_array();
    }

    return $result;
  }



  function kirim_proposal()
  {
    $id = $this->input->post('id');
    $data_post = array('posted' => 1);
    $this->db->trans_start();
    $this->db->where('ID_PROPOSAL', $id);
    $this->db->update('PROPOSAL', $data_post);

    return $this->db->trans_status();
  }

  function get_data_jenis_bantuan()
  {
    $this->db->select('r.jenis_bantuan');
    $this->db->distinct('r.jenis_bantuan');
    $result = $this->db->get('kategori_pemohon r');

    return $result;
  }

  function get_data_kategori()
  {
    $bantuan = $this->input->post('bantuan');
    $this->db->select('r.kategori');
    $this->db->from('kategori_pemohon r');
    $this->db->where('r.jenis_bantuan', $bantuan);
    $this->db->order_by('r.no_urut', 'asc');
    $result = $this->db->get();

    return $result;
  }

  function get_data($param, $isCount = FALSE, $CompileOnly = False)
  {
    isset($param['limit']) && $param['limit'] ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '';

    if (isset($param['m']) && $param['m'] == 'm' && $param['q'] !== '') {
      foreach ($param['q'] as $key => $val) {
        $search_str = isset($val['searchString']) ? $val['searchString'] : '';
        $search_str1 = isset($val['searchString1']) ? $val['searchString1'] : '';
        $search_str2 = isset($val['searchString2']) ? $val['searchString2'] : '';
        $flt[$val['searchField']] = array(
          'search_str' => $search_str, 'search_str1' => $search_str1, 'search_str2' => $search_str2,
          'search_op' => $val['searchOper'], 'search_ctg' => $val['searchKategori']
        );
      }
      $wh = get_where_str($flt, $this->fieldmap);

      $this->db->where($wh);
    } else if (isset($param['m']) && $param['m'] == 's' && $param['q'] !== '') {
      $flt = array();
      foreach ($this->fieldmap as $key => $value) {
        $flt[$key] = array('search_str' => $param['q'], 'search_op' => 'cn');
      }
      $wh = get_where_str($flt, $this->fieldmap);
      if ($wh) {
        $count = count($wh);
        $string = '(';
        $i = 1;
        foreach ($wh as $key => $val) {
          $string .= $key . " '" . $val . "'";
          if ($i < $count) $string .= ' OR ';
          $i++;
        }
        $string .= ')';

        $this->db->where($string);
      }
    } else {
      if (isset($param['search']) && $param['search'] && $wh = get_where_str(array($param['search_field'] => $param['search_str']), $this->fieldmap)) {
        $this->db->where($wh);
      }
    }

    /* 		if (!empty($wh)) {
		  $count = count($wh);
		  $string = '(';
		  $i = 1;
		  foreach ($wh as $key=>$val) {
			$string .= $key ." '". $val ."'";
			if ($i < $count) $string .= ' OR ';
			$i++;
		  }
		  $string .= ')';

		  $this->db->where($string);
		}
 */
    if (isset($param['sort_by']) && $param['sort_by'] != null && !$isCount && $ob = get_order_by_str($param['sort_by'], $this->fieldmap)) {
      $this->db->order_by($ob, $param['sort_direction']);
    }

    $this->db->select("
			a.id_proposal,
			a.nomor,
			a.tanggal,
			a.nama_pemohon,
			a.alamat_pemohon,
			a.nominal_diajukan,
			a.nominal_disetujui,
			a.status,
			e.nama_program,
			c.nama_kegiatan,
			f.nama_rekening,
			a.jenis_bantuan,
			a.kategori
		");
    $this->db->from('proposal a');
    $this->db->join('rincian_anggaran b', 'a.id_proposal = b.id_proposal', 'left');
    $this->db->join('v_form_anggaran_lkp c', 'b.id_form_anggaran = c.id_form_anggaran', 'left');
    $this->db->join('kegiatan d', 'c.id_kegiatan = d.id_kegiatan', 'left');
    $this->db->join('program e', 'd.id_program = e.id_program', 'left');
    $this->db->join('rekening f', 'b.id_rekening = f.id_rekening', 'left');
    $this->db->where('a.tahun', $this->tahun);
    $this->db->where('a.nomor is not null');

    if ($isCount) {
      $result = $this->db->count_all_results();
      return $result;
    } else {
      if ($CompileOnly) {
        return $this->db->get_compiled_select();
      } else {
        $result = $this->db->get()->result_array();
        return $result;
      }
    }
  }

  function get_data_by_id($id)
  {
    $this->db->select('
        a.id_proposal,
        a.nik,
        a.nomor,
        a.tanggal,
        a.jenis_bantuan,
        a.kategori,
        a.nama_pemohon,
        a.alamat_pemohon,
        a.tanggal_lahir,
        a.nama_pimpinan,
        a.bidang,
        cast(substring(a.ringkasan from 1 for 5000) as varchar(5000)) ringkasan,
        a.nominal_diajukan,
        a.nominal_disetujui,
        a.status,
        a.posted
    ');
    $this->db->from('proposal a');
    $this->db->where('a.id_proposal', $id);
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_prev_id($id)
  {
    $this->db->select('coalesce(max(a.id_proposal), 0) id_proposal');
    $this->db->from('proposal a');
    $this->db->where('id_proposal < ', $id);
    $this->db->where('a.tahun', $this->tahun);
    $result = $this->db->get()->row_array();

    return $result['ID_PROPOSAL'];
  }

  function get_next_id($id)
  {
    $this->db->select('coalesce(min(a.id_proposal), 0) id_proposal');
    $this->db->from('proposal a');
    $this->db->where('id_proposal > ', $id);
    $this->db->where('a.tahun', $this->tahun);
    $result = $this->db->get()->row_array();

    return $result['ID_PROPOSAL'];
  }

  function delete_data($id)
  {
    $this->db->trans_start();
    $this->db->where('id_proposal', $id);
    $this->db->delete('proposal');
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      return FALSE;
    }
  }

  function check_dependency($id)
  {
    $this->db->select('count(a.id_proposal) disposisi_pakai');
    $this->db->from('disposisi a');
    $this->db->where('a.id_proposal', $id);
    $result1 = $this->db->get()->row_array();

    $this->db->select('count(a.id_proposal) pengujian_pakai');
    $this->db->from('pengujian a');
    $this->db->where('a.id_proposal', $id);
    $result2 = $this->db->get()->row_array();

    if ($result1 && $result1['DISPOSISI_PAKAI'] > 0) {
      return FALSE;
    } else if ($result2 && $result2['PENGUJIAN_PAKAI'] > 0) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  function check_posted($id)
  {
    $this->db->select('a.posted');
    $this->db->from('proposal a');
    $this->db->where('a.id_proposal', $id);
    $result = $this->db->get()->row_array();

    if ($result['POSTED'] === 1) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function check_duplikat_number($no)
  {
    $id = $this->input->post('id');

    $this->db->select('count(a.nomor) nomor_pakai');
    $this->db->from('proposal a');
    $this->db->where('a.nomor', $no);
    $this->db->where('a.tahun', $this->tahun);
    $this->db->where('a.id_proposal <>', $id);
    $result = $this->db->get()->row_array();

    if ($result && $result['NOMOR_PAKAI'] > 0) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  function get_jabatan_skpd()
  {
    $this->db->select('*');
    $this->db->from('PEJABAT_SKPD');
    if ($this->skpd > 0) $this->db->where('ID_SKPD', $this->skpd);
    $result = $this->db->get()->result_array();
    return $result;
  }

  function get_data_pejabat_skpd($jabat)
  {
    $this->db->select('JABATAN,NIP');
    $this->db->from('PEJABAT_SKPD');
    $this->db->where('ID_PEJABAT_SKPD', $jabat);
    $result = $this->db->get();
    return $result->row_array();
  }
}
