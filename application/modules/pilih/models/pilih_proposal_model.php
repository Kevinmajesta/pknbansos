<?php
class Pilih_proposal_model extends Pilih_Model {

  function __construct()
  {
    parent::__construct();
    $this->tahun = $this->session->userdata('tahun');
  }

  function getProposal($param, $isCount=FALSE)
  {
    $fieldmap = array(
      'id' => 'a.ID_PROPOSAL',
      'no' => 'a.NOMOR',
      'tgl' => 'a.TANGGAL',
      'nama' => 'a.NAMA_PEMOHON'
    );

    $wh = $this->checkSearch($param['q'], $fieldmap);
    //if ($wh) $this->db->or_where($wh);
    if ($wh) {
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

    ($param['sort_by'] != null && !$isCount) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    /*
      mode : pengujian
      keterangan : pengujian
      dipakai di Pengujian
    */
    if ($param['mode'] === 'pengujian'){
      $this->db->select('
        a.id_proposal,
        a.nomor,
        a.tanggal,
        a.nama_pemohon,
        a.nominal_diajukan');
      $this->db->from('proposal a');
      $this->db->where('a.posted', '1');
      $this->db->where('a.jenis_bantuan', $param['bantuan']);
      $this->db->where('a.id_proposal not in (select id_proposal from pengujian)');
      $this->db->where('a.tahun', $this->tahun);
      $this->db->group_by('
        a.id_proposal,
        a.nomor,
        a.tanggal,
        a.nama_pemohon,
        a.nominal_diajukan');
    }
    /*
      mode : disposisi
      keterangan : disposisi
      dipakai di Disposisi
    */
    else if ($param['mode'] === 'disposisi'){
      $this->db->select('
        a.id_proposal,
        a.nomor,
        b.tanggal,
        a.nama_pemohon,
        a.nominal_diajukan');
      $this->db->from('proposal a');
      $this->db->join('pengujian b', 'a.id_proposal = b.id_proposal');
      $this->db->where('a.posted', '1');
      $this->db->where('b.posted', '1');
      $this->db->where('a.jenis_bantuan', $param['bantuan']);
      $this->db->where('a.tahun', $this->tahun);
      $this->db->where('a.id_proposal not in (select id_proposal from disposisi)');
      $this->db->group_by('
        a.id_proposal,
        a.nomor,
        b.tanggal,
        a.nama_pemohon,
        a.nominal_diajukan');
    }
    /*
      mode : rka
      keterangan : rka
      dipakai di RKA
    */
    else if ($param['mode'] === 'rka'){
      $id_proposal = array();
      $result = $this->db->query('select r.id_proposal from rincian_anggaran r where r.id_proposal is not null');
      foreach ($result->result_array() as $row) {
        $id_proposal[] = $row['ID_PROPOSAL'];
      }
      
      $this->db->select('
        a.id_proposal,
        a.nomor,
        a.nama_pemohon,
        a.nominal_diajukan,
        a.alamat_pemohon,
        cast(substring(a.ringkasan from 1 for 5000) as varchar(5000)) ringkasan');
      $this->db->from('proposal a');
      $this->db->join('disposisi b', 'a.id_proposal = b.id_proposal');
      $this->db->where('b.keputusan', 1);
      $this->db->where('a.jenis_bantuan', $param['bantuan']);
      $this->db->where('a.kategori', $param['kategori']);
      $this->db->where('a.tahun', $this->tahun);
      if (!empty($id_proposal)) $this->db->where_not_in('a.id_proposal', $id_proposal);
      $this->db->group_by('
        a.id_proposal,
        a.nomor,
        a.nama_pemohon,
        a.nominal_diajukan,
        a.alamat_pemohon,
        ringkasan');
    }
    /*
      mode : nphd
      keterangan : nphd
      dipakai di NPHD
    */
    else if ($param['mode'] === 'nphd'){
      $this->db->select('
        a.id_proposal,
        a.nomor,
        a.tanggal,
        a.nama_pemohon,
        a.nominal_diajukan');
      $this->db->from('proposal a');
      $this->db->join('rincian_spp_proposal b', 'a.id_proposal = b.id_proposal');
      $this->db->join('v_spp c', 'b.id_aktivitas = c.id_aktivitas');
      $this->db->join('v_spm d', 'c.id_aktivitas = d.id_spp');
      $this->db->join('v_sp2d e', 'd.id_aktivitas = e.id_aktivitas_spm');
      $this->db->where('a.jenis_bantuan', $param['bantuan']);
      $this->db->where('a.id_proposal not in (select id_proposal from perjanjian)');
	  $this->db->where('a.tahun',$this->tahun);
      $this->db->group_by('
        a.id_proposal,
        a.nomor,
        a.tanggal,
        a.nama_pemohon,
        a.nominal_diajukan');
    }
    /*
      mode : spp
      keterangan : spp
      dipakai di SPP
    */
    else if ($param['mode'] === 'spp'){
      $query = $this->db->query('select p.id_proposal, p.nominal_diajukan, sum(r.nominal) total_cair
                        from proposal p join rincian_spp_proposal r on r.id_proposal = p.id_proposal
                        group by p.id_proposal, p.nominal_diajukan');
      
      foreach ($query->result_array() as $row) {
        if ($row['TOTAL_CAIR'] == $row['NOMINAL_DIAJUKAN']) $id[] = $row['ID_PROPOSAL'];
      }

      $this->db->select('
        a.id_proposal,
        a.nomor,
        a.tanggal,
        a.nama_pemohon,
        a.nominal_diajukan,
        b.pagu');
      $this->db->from('proposal a');
      $this->db->join('rincian_anggaran b', 'a.id_proposal = b.id_proposal');
      $this->db->join('form_anggaran c', 'b.id_form_anggaran = c.id_form_anggaran');
      $this->db->where('c.id_skpd', $param['skpd']);
      $this->db->where('a.tahun', $this->tahun);
      if ($param['beban'] === 'BL')
        $this->db->where('c.tipe', 'RKA221');
      else if ($param['beban'] === 'BTL')
        $this->db->where('c.tipe', 'RKA21');
      if (isset($id) && count($id) > 0) $this->db->where_not_in('a.id_proposal', $id);
      $this->db->group_by('
        a.id_proposal,
        a.nomor,
        a.tanggal,
        a.nama_pemohon,
        a.nominal_diajukan,
        b.pagu');
    }
    /*
      mode : bast
      keterangan : bast
      dipakai di BAST
    */
    else if ($param['mode'] === 'bast'){
/*       $this->db->select('
        a.id_proposal,
        a.nomor,
        a.tanggal,
        a.nama_pemohon,
        a.nominal_disetujui');
      $this->db->from('proposal a');
      $this->db->join('rincian_spp_proposal b', 'a.id_proposal = b.id_proposal');
      $this->db->join('v_spp c', 'b.id_aktivitas = c.id_aktivitas');
      $this->db->join('v_spm d', 'c.id_aktivitas = d.id_spp');
      $this->db->join('v_sp2d e', 'd.id_aktivitas = e.id_aktivitas_spm');
      $this->db->where('a.jenis_bantuan', $param['bantuan']);
      $this->db->where('a.id_proposal not in (select id_proposal from bast)');
      $this->db->where('a.tahun', $this->tahun);
      $this->db->group_by('
        a.id_proposal,
        a.nomor,
        a.tanggal,
        a.nama_pemohon,
        a.nominal_disetujui');
 */    
      $this->db->select('a.ID_PROPOSAL, a.NOMOR, a.TANGGAL, a.NAMA_PEMOHON, a.NOMINAL_DISETUJUI');
      $this->db->from("(SELECT p.ID_PROPOSAL, p.NOMOR, p.TANGGAL, p.NAMA_PEMOHON, p.NOMINAL_DISETUJUI
                          FROM PROPOSAL p
                              JOIN RINCIAN_SPP_PROPOSAL rsp ON p.ID_PROPOSAL = rsp.ID_PROPOSAL
                              JOIN V_SPP v1 ON rsp.ID_AKTIVITAS = v1.ID_AKTIVITAS
                              JOIN V_SPM v2 ON v1.ID_AKTIVITAS = v2.ID_SPP
                              JOIN V_SP2D v3 ON v2.ID_AKTIVITAS = v3.ID_AKTIVITAS_SPM
                          WHERE p.JENIS_BANTUAN =  'Bantuan Sosial'
                              AND p.ID_PROPOSAL NOT IN (SELECT ID_PROPOSAL FROM BAST)
                              AND p.TAHUN =  '".$this->tahun."'
                          UNION ALL
                          SELECT p.ID_PROPOSAL, p.NOMOR, p.TANGGAL, p.NAMA_PEMOHON, p.NOMINAL_DISETUJUI
                          FROM PROPOSAL p
                              JOIN RINCIAN_ANGGARAN ra ON ra.ID_PROPOSAL = p.ID_PROPOSAL
                              JOIN FORM_ANGGARAN fa ON fa.ID_FORM_ANGGARAN = ra.ID_FORM_ANGGARAN
                              JOIN RINCIAN_SPP_PROPOSAL rsp ON p.ID_PROPOSAL = rsp.ID_PROPOSAL
                              JOIN V_SPP v1 ON rsp.ID_AKTIVITAS = v1.ID_AKTIVITAS
                              JOIN V_SPM v2 ON v1.ID_AKTIVITAS = v2.ID_SPP
                              JOIN V_SP2D v3 ON v2.ID_AKTIVITAS = v3.ID_AKTIVITAS_SPM
                          WHERE p.JENIS_BANTUAN =  'Hibah'
                              AND p.ID_PROPOSAL NOT IN (SELECT ID_PROPOSAL FROM BAST)
                              AND p.TAHUN =  '".$this->tahun."'
                              AND p.ID_PROPOSAL IN(SELECT ID_PROPOSAL FROM PERJANJIAN)
                    ) a");
        $this->db->group_by('ID_PROPOSAL, NOMOR, TANGGAL, NAMA_PEMOHON, NOMINAL_DISETUJUI');
    }
    /*
      mode : -
      keterangan : semua proposal
      dipakai di ...
    */
    else {
      $this->db->select('
        a.id_proposal,
        a.nomor,
        a.tanggal,
        a.nama_pemohon,
        a.nominal_diajukan
      ');
      $this->db->from('proposal a');
      $this->db->where('a.posted', '1');
    }

    if ($isCount) {
      $result = $this->db->count_all_results();
      return $result;
    }
    else {
      $result = $this->db->get()->result_array();
      return $result;
    }
  }

}