<?php
class Kontrak_model extends CI_Model {

  var $tahun;
  var $username;
  var $id;
  var $fieldmap;
  var $fieldmap_rincian;
  var $data_kontrak;
  var $data_rincian;
  var $purge_rincian;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();

    $this->tahun = $this->session->userdata('tahun');
    $this->username = $this->session->userdata('username');
    $this->status = $this->session->userdata('status');

    $this->fieldmap = array(
      'nok' => 'NO_KONTRAK',
      'nmperusahaan' => 'NAMA_PERUSAHAAN',
      'almperusahaan' => 'ALAMAT_PERUSAHAAN',
      'nmpimpinan' => 'NAMA_PIMPINAN',
      'nmbank' => 'NAMA_BANK',
      'norekb' => 'NO_REKENING_BANK',
      'npwp' => 'NPWP',
      'tglk' => 'TANGGAL_KONTRAK',
      'tglselesai' => 'TANGGAL_SELESAI',
      'nilaik' => 'NOMINAL_KONTRAK',
      'tglbap' => 'TANGGAL_BAP',
      'nominalbap' => 'NOMINAL_BAP',
      'id_keg' => 'ID_KEGIATAN',
      'id_skpd'=>'ID_SKPD',
      'nobap'=>'NO_BAP'
    );

    $this->fieldmap_rincian = array(
      'nok' => 'NO_KONTRAK',
      'idrek' => 'ID_REKENING',
      'nkontrak' => 'NOMINAL_KONTRAK',
      'nbap' => 'NOMINAL_BAP'
    );
  }

  function fill_data()
  {
    foreach($this->fieldmap as $key => $value){
      switch ($key)
      {
        case 'tglk'       : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        case 'tglbap'      : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        case 'tglselesai' : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        case 'nilaik'   : $$key = $this->input->post($key) ? prepare_numeric($this->input->post($key)) : NULL; break;
        case 'nominalbap' : $$key = $this->input->post($key) ? prepare_numeric($this->input->post($key)) : NULL; break;
        default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }
      if(isset($$key))
        $this->data_kontrak[$value] = $$key;
    }
    $this->id = $this->input->post('id') ? $this->input->post('id') : NULL;

    $this->purge_rincian = $this->input->post('purge'); $this->purge_rincian = $this->purge_rincian ? $this->purge_rincian : NULL;
    $rinci = $this->input->post('rincian') ? $this->input->post('rincian') : NULL;

    if ($rinci)
    {
      $rinci = json_decode($rinci);
      for ($i=0; $i <= count($rinci) - 1; $i++) {
        foreach($this->fieldmap_rincian as $key => $value){
          switch ($key)
          {
            case 'nkontrak' : $$key = $rinci[$i]->$key ? $rinci[$i]->$key : NULL; break;
            case 'nbap'   : $$key = $rinci[$i]->$key  ? $rinci[$i]->$key : NULL; break;
            default : $$key = isset($rinci[$i]->$key) && $rinci[$i]->$key ? $rinci[$i]->$key : NULL;
          }

          if(isset($$key))
            $this->data_rincian[$i][$value] = $$key;
        }
      }
    }
  }

  function insert_kontrak()
  {
    if ($this->id)
    {
      $this->db->where('hash(NO_KONTRAK)', $this->id);
      $this->db->update('KONTRAK', $this->data_kontrak);
    }
    else
    {
      $this->db->insert('KONTRAK', $this->data_kontrak);
    }

    $nok = $this->data_kontrak['NO_KONTRAK'];
    $this->db->where('NO_KONTRAK', $nok);
    $this->db->select('hash(NO_KONTRAK) id');
    $rs = $this->db->get('KONTRAK')->row_array();
    return isset($rs['ID']) ? $rs['ID'] : 0;
  }

  function insert_rincian()
  {
    if($this->purge_rincian)
    {
      $this->db->where_in('ID_REKENING', $this->purge_rincian);
      $this->db->where('hash(NO_KONTRAK)', $this->id);
      $this->db->delete('RINCIAN_KONTRAK');
    }

    $nok = $this->data_kontrak['NO_KONTRAK'];
    $jml = count($this->data_rincian);
    for ($i=0; $i <= $jml - 1; $i++)
    {
      $idrek = $this->data_rincian[$i]['ID_REKENING'];

      $this->db->select('1')->from('RINCIAN_KONTRAK');
      $this->db->where('ID_REKENING', $idrek);
      $this->db->where('hash(NO_KONTRAK)',$this->id);
      $rs = $this->db->get()->row_array();

      if ($rs)
      {
        $this->db->where('ID_REKENING', $idrek);
        $this->db->where('hash(NO_KONTRAK)', $this->id);
        $this->db->update('RINCIAN_KONTRAK', $this->data_rincian[$i]);
      }
      else
      {
        $this->data_rincian[$i]['NO_KONTRAK'] = $nok;
        $this->db->insert('RINCIAN_KONTRAK', $this->data_rincian[$i]);
      }
    }
  }

  function save_data()
  {
    $this->db->trans_start();
    $this->id = $this->insert_kontrak();
    $this->insert_rincian();
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
  }

  function get_data($param)
  {
    if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			if (array_key_exists($param['search_field'], $this->fieldmap)) {
				$wh = "UPPER(".$this->fieldmap[$param['search_field']].")";
				$param['search_str'] = strtoupper($param['search_str']);

				if($param['search_str']=='AKTIF'){
					$param['search_str']='1';
				}
				elseif($param['search_str']=='TIDAK AKTIF'){
					$param['search_str']='0';
				}
				else{
					$param['search_str'] = $param['search_str'];
				}
				switch ($param['search_operator']) {
					case "bw": // begin with
						$wh .= " LIKE '".$param['search_str']."%'";
						break;
					case "cn": // contain %param%
						$wh .= " LIKE '%".$param['search_str']."%'";
						break;
					default :
						$wh = "";
				}
				$this->db->where($wh);
			}
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
		if($param['sort_by']=='nmperusahaan'){
			$param['sort_by'] = 'nama_perusahaan';
		}
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
    $this->db->trans_start();
    $this->db->select('
        hash(k.no_kontrak) id,
        k.no_kontrak,
        k.tanggal_kontrak,
        k.no_bap,
        k.tanggal_bap,
        k.nominal_kontrak,
        k.nama_perusahaan,
        k.nama_pimpinan,
        k.nama_bank
    ');
    $this->db->from('kontrak k');
    $this->db->where("EXTRACT( YEAR FROM k.tanggal_kontrak) ='".$this->tahun."'");
    $this->db->order_by('nama_perusahaan');
    /* filter skpd jika diset */
    if ($this->session->userdata('id_skpd') !== 0) $this->db->where('k.id_skpd', $this->session->userdata('id_skpd'));
    $result = $this->db->get()->result_array();
    		$this->db->trans_complete();
		if(count($result)>0) {
			return $result;
		} else {
			return FALSE;
		}
    
  }

  function get_data_by_id($id)
  {
    $this->db->select('
        hash(k.no_kontrak) id,
        k.no_kontrak,
        k.tanggal_kontrak,
        k.no_bap,
        k.nominal_kontrak,
        k.nama_perusahaan,
        k.alamat_perusahaan,
        k.nama_pimpinan,
        k.nama_bank,
        k.no_rekening_bank,
        k.npwp,
        k.nominal_bap,
        k.id_skpd,
        k.id_kegiatan,
        g.kode_kegiatan_skpd,
        g.nama_kegiatan,
        k.keterangan,
        k.tanggal_bap,
        k.id_rekening_hutang,
        r.kode_rekening,
        r.nama_rekening,
        k.tanggal_selesai,
        v.kode_skpd_lkp,
        v.nama_skpd
   ');
    $this->db->from('kontrak k');
    $this->db->join('v_skpd v','k.id_skpd = v.id_skpd', 'left');
    $this->db->join('v_kegiatan_skpd g', 'g.id_kegiatan = k.id_kegiatan and g.id_skpd = k.id_skpd', 'left');
    $this->db->join('rekening r','r.id_rekening = k.id_rekening_hutang', 'left');
    $this->db->where('hash(k.no_kontrak)', $id);
    $result = $this->db->get()->row_array();
    return $result;
  }

  function get_rinci_by_id($id)
  {
    $this->db->select('
        rs.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        rs.nominal_kontrak,
        rs.nominal_bap,
        (select sum(ra.pagu) from v_form_anggaran_lkp fa 
         join tahun_anggaran ta on ta.tahun = fa.tahun and ta.status_kini = fa.status
         join rincian_anggaran ra on ra.id_form_anggaran = fa.id_form_anggaran 
         where fa.tahun = '.$this->tahun.'
           and fa.id_kegiatan = k.id_kegiatan
           and ra.id_rekening = rs.id_rekening
        ) anggaran
    ');
    $this->db->from('kontrak k');
    $this->db->join('rincian_kontrak rs', 'rs.no_kontrak = k.no_kontrak');
    $this->db->join('rekening r', 'r.id_rekening = rs.id_rekening');
    $this->db->where('hash(rs.no_kontrak)', $id);
    $this->db->order_by('r.kode_rekening');
    $result = $this->db->get()->result_array();

    return $result;
  }

  function get_anggaran($param){
    $this->db->select('sum(ra.pagu) as anggaran');
    $this->db->from("v_form_anggaran_lkp va");
    $this->db->join("rincian_anggaran ra", "va.id_form_anggaran = ra.id_form_anggaran");
    $this->db->where("va.tahun",$this->tahun);
    $this->db->where("va.status",$this->status);
    $this->db->where("va.id_skpd",$param['id_skpd']);
    $this->db->where("va.id_kegiatan",$param['id_kegiatan']);
    $this->db->where("ra.id_rekening",$param['id_rekening']);
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_sisa_anggaran($param){
    $this->db->select('sum(rk.NOMINAL_KONTRAK) as kontrak');
    $this->db->from("KONTRAK k");
    $this->db->join("RINCIAN_KONTRAK rk", "rk.NO_KONTRAK = k.NO_KONTRAK");
    $this->db->where("EXTRACT( YEAR FROM k.TANGGAL_KONTRAK) = ",$this->tahun);
    //$this->db->where("va.status",$this->status);
    $this->db->where("k.id_skpd",$param['id_skpd']);
    $this->db->where("k.id_kegiatan",$param['id_kegiatan']);
    $this->db->where("rk.id_rekening",$param['id_rekening']);
    $this->db->where("k.NO_KONTRAK <>",$param['no_kontrak']);
    $result = $this->db->get()->row_array();
    
    return $result;
  }
  
  function get_prev_id($id)
  {
    $this->db->select('coalesce(hash(max(no_kontrak)), 0) no_kontrak');
    $this->db->from('kontrak');
    $this->db->where('hash(no_kontrak) < ', $id);
    $result = $this->db->get()->row_array();
    return $result['NO_KONTRAK'];
  }

  function get_next_id($id)
  {
    $this->db->select('coalesce(hash(min(no_kontrak)), 0) no_kontrak');
    $this->db->from('kontrak');
    $this->db->where('hash(no_kontrak) > ', $id);
    $result = $this->db->get()->row_array();
    return $result['NO_KONTRAK'];
  }

  function delete_data($id)
  {
    $this->db->trans_start();
    $this->db->where('hash(no_kontrak)', $id);
    $this->db->delete('rincian_kontrak');
    $this->db->where('hash(no_kontrak)', $id);
    $this->db->delete('kontrak');
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
  }

  function check_dependency($id)
  {
    $this->db->select('count(c.id_aktivitas) bku_pakai');
    $this->db->from('spp c');
    $this->db->where('hash(c.no_kontrak)', $id);
    $result3 = $this->db->get()->row_array();

    if (($result3 && $result3['BKU_PAKAI'] > 0 ))
    {
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }

  function cek_duplikasi_nomor($nomor)
  {
    $id = $this->input->post('id') ? $this->input->post('id') : NULL;

    $this->db->where('NO_KONTRAK', $nomor);
    if ($id) $this->db->where('hash(NO_KONTRAK) <> '.$id);
    $this->db->select('COUNT(*) DUP');
    $rs = $this->db->get('kontrak')->row_array();
    return (integer)$rs['DUP'] === 0;
  }
}
